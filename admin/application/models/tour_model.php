<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Tour_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        
        $this->load->database();
    }

    function get_numb_tours($search_criteria = '')
    {
        $this->_set_search_criteria($search_criteria);
        $this->db->where('t.deleted !=', DELETED);
        return $this->db->count_all_results('tours t');
    }

    function search_tours($search_criteria = '', $num, $offset, $order_field = 'position', $order_type = 'asc')
    {
        /* $ext_sql = '';
        if (!empty($search_criteria)) {
            	
            foreach ($search_criteria as $key => $value) {
                	
                $value =  $this->db->escape_str($value);
        
                $value = search_term_pre_process($value);
                	
                if ($key == 'search_text') {
                    $ext_sql = ", MATCH(t.name) AGAINST ('".$value."' IN NATURAL LANGUAGE MODE) as score";
                    break;
                }
            }
            	
        } */
        
        $this->db->select('t.*, p.name as partner_name, u.username as last_modified_by, d.name as destination_name');
        
        $this->_set_search_criteria($search_criteria);
        
        $this->db->join('partners p', 't.partner_id = p.id', 'left outer');
        $this->db->join('users u', 't.user_modified_id = u.id', 'left outer');
        $this->db->join('destinations d', 't.destination_id = d.id', 'left outer');
        
        if (!empty($ext_sql)) {
            $this->db->order_by('score', 'desc');
        }
        
        $this->db->order_by($order_field, $order_type);
        $query = $this->db->get('tours t', $num, $offset);
        
        $results = $query->result_array();
        
        return $results;
    }


    function _set_search_criteria($search_criteria = '', $mask_name = 't.')
    {
        $this->db->where($mask_name . 'deleted !=', DELETED);
        
        if ($search_criteria == '')
        {
            return;
        }
        foreach ($search_criteria as $key => $value)
        {
            switch ($key)
            {
                case 'search_text':
                    $value = search_term_pre_process($value);
                    //$this->db->where("MATCH(". $mask_name ."name) AGAINST ('".$value."*' IN BOOLEAN MODE)");
                    $this->db->like($mask_name.'name', $value, 'both');
                    break;
                case 'partner_id':
                    $this->db->where($mask_name . 'partner_id', $value);
                    break;
                case 'cruise_id':
                    $this->db->where($mask_name . 'cruise_id', $value);
                    break;
                case 'is_cruise_tour':
                    $this->db->where($mask_name . 'cruise_id !=', 0);
                    break;
                    
                case 'category_id':
                	
                	$sql_cond = "EXISTS (SELECT 1 FROM tour_categories as tc WHERE tc.tour_id = ".$mask_name."id AND tc.category_id = ".$value.")";
                	
                    $this->db->where($sql_cond, null, false);
                   	break;
                   
                case 'status':
                    $this->db->where($mask_name . 'status', $value);
                    break;
                    
                case 'departure_type':
                   	$this->db->where($mask_name . 'departure_type', $value);
                    break;
                    
                case 'is_outbound':
                   	$this->db->where($mask_name . 'is_outbound', $value);
                   	break;
                    
                case 'des_id':
                	
                	if(!empty($value)){
                	
                   		$sql_cond = "EXISTS (SELECT 1 FROM destination_tours as dt WHERE dt.tour_id = ".$mask_name."id AND dt.is_land_tour = 1 AND dt.destination_id = ".$value.")";
                	
                    	$this->db->where($sql_cond, null, false);
                	}
                    
                    break;
            }
        }
    }

    /**
     * create_tour
     *
     * @return bool
     *
     */
    public function create_tour($tour)
    {
        $this->db->trans_start();
        
        $position = $this->get_max_position() + 1;
        // Additional data
        $additional_data = array(
            'user_created_id' => get_user_id(),
            'user_modified_id' => get_user_id(),
            'date_created' => date(DB_DATE_TIME_FORMAT),
            'date_modified' => date(DB_DATE_TIME_FORMAT),
            'status' => STATUS_ACTIVE,
            'position' => $position
        );
        
        $tour['url_title'] = url_title(convert_unicode($tour['name']), '-', true);
        
        // filter out any data passed that doesnt have a matching column in the users table
        // and merge the set user data and the additional data
        $tour = array_merge($tour, $additional_data);
        
        // update tour is outboundor or not
        $is_outbound = $this->is_outbound_tour($tour['route_ids']);
        $tour['is_outbound'] = $is_outbound ? 1 : 0;
        
        // unset unwanted data
        $route_ids = $tour['route_ids'];
        $route_hidden_ids = $tour['route_hidden_ids'];
        $land_tour_ids = $tour['land_tour_ids'];
        unset($tour['route_ids']);
        unset($tour['route_hidden_ids']);
        unset($tour['land_tour_ids']);
        
        // create new tour
        $this->db->insert('tours', $tour);
        
        $tour['id'] = $this->db->insert_id();
        
        // update tour destinations
        $this->update_tour_destination($tour, $route_ids, $route_hidden_ids, $land_tour_ids);
        
        $this->update_tour_routes($tour['id']);
        
        
        $this->update_land_tour_flag_for_parent_destinations($tour['id']);
        
        $this->db->trans_complete();
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function get_tour($id)
    {
        if (empty($id))
        {
            return FALSE;
        }
        
        $this->db->where('id', $this->db->escape_str($id));
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('tours');
        
        $result = $query->result_array();
        
        if (count($result) === 1)
        {
            return $result[0];
        }
        
        return FALSE;
    }

    function update_tour($tour, $is_change_depart = false)
    {
        $this->db->trans_start();
        
        $tour['user_modified_id'] = get_user_id();
        $tour['date_modified'] = date(DB_DATE_TIME_FORMAT);
        
        // update tour url title
        if (isset($tour['name']) && ! empty($tour['name']))
        {
            $tour['url_title'] = url_title(convert_unicode($tour['name']), '-', true);
            
            $url_title_history = $this->is_change_tour_name($tour['id'], $tour['name']);
            
            if ($url_title_history !== false)
            {
                $tour['url_title_history'] = $url_title_history;
            }
        }
        
        if (isset($tour['route_ids']))
        {
            // update tour is outboundor or not
            $is_outbound = $this->is_outbound_tour($tour['route_ids']);
            $tour['is_outbound'] = $is_outbound ? 1 : 0;
            
            // update tour destinations
            $this->update_tour_destination($tour, $tour['route_ids'], $tour['route_hidden_ids'], $tour['land_tour_ids']);
            unset($tour['route_ids']);
            unset($tour['route_hidden_ids']);
            unset($tour['land_tour_ids']);
        }
        
        // update tour
        $this->db->update('tours', $tour, array(
            'id' => $tour['id']
        ));
        
        // update tour facilities
        if (isset($tour['facilities']))
        {
            $this->update_tour_facilities($tour);
        }
        
        $this->update_tour_routes($tour['id']);
        
        $this->update_land_tour_flag_for_parent_destinations($tour['id']);
        
        // check change departure type
        /*
         * if change departure type from MULTIPLE to SINGLE then
         * remove all tour departures except the first one (keep it as default value for single departure)
         */
        if ($is_change_depart)
        {
            
            $this->load->model('Tour_Departure_Model');
            $tour_departure = $this->Tour_Departure_Model->get_tour_departures($tour['id']);
            
            //echo("<pre>");print_r($tour_departure);echo("</pre>");exit();
            
            foreach ($tour_departure as $key => $value) {
                if($key == 0) continue;
                $this->Tour_Departure_Model->delete_tour_departure($value['id']);
            }
        }
        
        $this->db->trans_complete();
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function update_tour_destination($tour, $tour_des, $route_hidden_ids, $land_tour_ids)
    {
        $this->db->trans_start();
        
        // print_r($route_hidden_ids);exit();
        
        $parent_ids = $this->get_parent_route($tour_des);
        
        $all_des = $tour_des;
        
        foreach ($parent_ids as $des)
        {
            if (! in_array($des, $tour_des))
            {
                $all_des[] = $des;
            }
        }
        
        /*
         * Khuyenpv 10.09.2014 - Update number of tour on each destinations befor delete relations
         */
        $tour_des_before_update = $this->get_all_tour_destination($tour['id']);
        
        // create or update to tour_destinations table
        $this->db->where('tour_id', $tour['id']);
        $this->db->delete('destination_tours');
        
        $cnt = 1;
        foreach ($all_des as $des)
        {
            
            $is_hidden = in_array($des, $route_hidden_ids) ? 1 : 0;
            $is_land_tour = in_array($des, $land_tour_ids) ? 1 : 0;
            $is_show_on_route = in_array($des, $tour_des) ? 1 : 0;
            
            $tour_data = array(
                'tour_id' => $tour['id'],
                'destination_id' => $des,
                'hidden' => $is_hidden,
                'is_land_tour' => $is_land_tour,
                'is_show_on_route' => $is_show_on_route,
                'position' => $cnt
            );
            
            $this->db->insert('destination_tours', $tour_data);
            
            $cnt ++;
        }
        
        /*
         * Khuyenpv 10.09.2014 - Update number of tour on each destinations after update relations
        */
        $tour_des_after_update = $this->get_all_tour_destination($tour['id']);
        
        $this->Destination_Model->update_number_of_tours($tour_des_before_update);
        $this->Destination_Model->update_number_of_tours($tour_des_after_update);
        
        $this->db->trans_complete();
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    /**
     * Get all parent destination
     */
    function get_parent_route($des_ids)
    {
    	/*
        $this->db->select('dp.parent_id');
        $this->db->join('destinations d', 'dp.parent_id = d.id', 'left outer');
        $this->db->where_in('dp.destination_id', $des_ids);
        $this->db->where_in('d.type', array(
            DESTINATION_TYPE_CITY,
            DESTINATION_TYPE_COUNTRY,
            DESTINATION_TYPE_CONTINENT
        ));
        $this->db->where('d.deleted !=', DELETED);
        $this->db->group_by('dp.parent_id');
        */
    	
    	$this->db->distinct();
    	$this->db->select('parent_id');
    	$this->db->where_in('destination_id', $des_ids);
    	
        $query = $this->db->get('destination_places');
        
        $results = $query->result_array();
        
        $parent_ids = array();
        foreach ($results as $des)
        {
            $parent_ids[] = $des['parent_id'];
        }
        
        return $parent_ids;
    }

    function delete_tour($id)
    {
        $this->db->trans_start();
        
        $tour = $this->get_tour($id);
        
        if (! empty($tour))
        {
            $tour['deleted'] = DELETED;
            
            $this->db->update('tours', $tour, array(
                'id' => $id
            ));
        }
        
        $this->db->trans_complete();
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function get_max_position($type = 0)
    {
        if ($type == 0)
        {
            $this->db->select_max('position');
        }
        else
        {
            $this->db->select_min('position');
        }
        
        $query = $this->db->get('tours');
        
        $results = $query->result_array();
        if (! empty($results))
        {
            
            return $results[0]['position'];
        }
        
        return 0;
    }

    function get_tour_destinations()
    {
        $this->db->select('id, name');
        
        // $this->db->join('destinations d', 'd.id = c.destination_id', 'left outer');
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('number_of_tours >', 0);
        
        $this->db->order_by('name', 'asc');
        
        $query = $this->db->get('destinations');
        
        return $query->result_array();
    }

    function is_unique_tour_name($name, $id)
    {
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('name', $name);
        
        if (! empty($id))
        {
            
            $this->db->where('id !=', $id);
        }
        
        $cnt = $this->db->count_all_results('tours');
        
        return $cnt > 0;
    }
    
    
    function is_unique_tour_code($code, $id)
    {
    	if(empty($code)) return false;
    	
    	$this->db->where('deleted !=', DELETED);
    
    	$this->db->where('code', $code);
    
    	if (! empty($id))
    	{
    
    		$this->db->where('id !=', $id);
    	}
    
    	$cnt = $this->db->count_all_results('tours');
    
    	return $cnt > 0;
    }
    
    
    /*
     * is_change_tour_name check tour name is changed or not
     */
    function is_change_tour_name($id, $name)
    {
        $tour = $this->get_tour($id);
        
        if (! empty($tour))
        {
            if (trim($tour['name']) != trim($name))
            {
                
                $comma = ',';
                if (empty($tour['url_title_history']))
                {
                    $comma = '';
                }
                
                $url_title_history = $tour['url_title_history'] . $comma . url_title($tour['name']);
                
                return $url_title_history;
            }
        }
        
        return false;
    }

    function update_tour_facilities($tour)
    {
        $this->db->trans_start();
        
        // create or update to tour_destinations table
        $this->db->where('tour_id', $tour['id']);
        $this->db->delete('tour_facilities');
        
        $facilities = explode('-', $tour['facilities']);
        
        foreach ($facilities as $fac)
        {
            
            if (! empty($fac))
            {
                $facility_data = array(
                    'tour_id' => $tour['id'],
                    'facility_id' => $fac
                );
                
                $this->db->insert('tour_facilities', $facility_data);
            }
        }
        
        $this->db->trans_complete();
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function get_all_destinations()
    {
        $this->db->select('d.id, d.name');
        
        $this->db->join('destination_places dp', 'dp.destination_id = d.id', 'left outer');
        
        $this->db->where('dp.parent_id', DESTINATION_VIETNAM);
        
        $this->db->where('d.deleted !=', DELETED);
        
        $this->db->where('d.type', DESTINATION_TYPE_CITY);
        
        $this->db->order_by('d.position', 'asc');
        
        $query = $this->db->get('destinations d');
        
        $results = $query->result_array();
        
        foreach ($results as $k => $des)
        {
            $des['children'] = $this->get_all_sub_destinations($des['id']);
            $results[$k] = $des;
        }
        
        return $results;
    }
    
    /*
     * Get all children destinations of a city such as district, area, ... etc
     */
    function get_all_sub_destinations($des_id)
    {
        $types = array(
            DESTINATION_TYPE_DISTRICT,
            DESTINATION_TYPE_AREA
        );
        
        $this->db->select('d.id, d.name, d.type, d.url_title');
        
        $this->db->join('destinations as d', 'd.id = dp.destination_id', 'left outer');
        
        $this->db->where_in('d.type', $types);
        
        $this->db->where_in('dp.parent_id', $des_id);
        
        $this->db->where('d.deleted !=', DELETED);
        
        $this->db->order_by('d.type', 'asc');
        
        $this->db->order_by('d.position', 'asc');
        
        $query = $this->db->get('destination_places dp');
        
        $results = $query->result_array();
        
        return $results;
    }

    function get_all_cruises()
    {
        $this->db->select('id, name');
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('cruises');
        
        return $query->result_array();
    }

    function get_routes_by_destination($main_des)
    {
        $dess = array();
        
        $this->db->select('id, name, type');
        $this->db->where('deleted != ', DELETED);
        $this->db->where_in('id', $main_des);
        $this->db->order_by('position', 'asc');
        $query = $this->db->get('destinations');
        $area = $query->result_array();
        
        foreach ($area as $des)
        {
            array_push($dess, array(
                "name" => $des['name'],
                "id" => $des['id'],
                "destinations" => $this->get_all_sub_destinations($des['id'])
            ));
        }
        
        return $dess;
    }

    function get_tour_route($tour_id)
    {
        $this->db->select('dt.destination_id, dt.hidden, dt.is_land_tour, dt.position, d.name');
        
        $this->db->join('destinations d', 'dt.destination_id = d.id', 'left outer');
        
        $this->db->where('dt.tour_id', $tour_id);
        
        $this->db->where('dt.is_show_on_route', STATUS_ACTIVE);
        
        $this->db->order_by('dt.position', 'asc');
        
        $query = $this->db->get('destination_tours dt');
        
        $results = $query->result_array();
        
        return $results;
    }

    /**
     * get all tours of cruise
     *
     * @param cruise_id $cruise_id            
     * @param order_field $order_field            
     * @param order_type $order_type            
     *
     * @return tours
     */
    function get_cruise_tours($cruise_id, $order_field = 'position', $order_type = 'asc')
    {
        $this->db->select('t.*, p.name as partner_name, u.username as last_modified_by, d.name as destination_name');
        
        $this->db->join('partners p', 't.partner_id = p.id', 'left outer');
        $this->db->join('users u', 't.user_modified_id = u.id', 'left outer');
        $this->db->join('destinations d', 't.destination_id = d.id', 'left outer');
        
        $this->db->where('t.cruise_id', $cruise_id);
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->order_by($order_field, $order_type);
        $query = $this->db->get('tours t');
        
        $results = $query->result_array();
        
        return $results;
    }

    function get_tours_of_cruise($cruise_id)
    {
        $this->db->select('id, name, cruise_id');
        $this->db->where('cruise_id', $cruise_id);
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('tours');
        
        return $query->result_array();
    }

    function save_tour_photos($photo_ids, $tour_id)
    {
        foreach ($photo_ids as $id)
        {
            $photo = array(
                'tour_id' => $tour_id,
                'tour_photo_type' => 1 // Photo Gallery
                        );
            $this->db->update('photos', $photo, array(
                'id' => $id
            ));
        }
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function suggest_tours($term)
    {
        $term = $this->db->escape_str($term);
        
        $match_sql = "MATCH(name) AGAINST ('" . $term . "*' IN BOOLEAN MODE)";
        
        $this->db->select('id, name, url_title, ' . $match_sql . ' AS score');
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where($match_sql);
        
        $this->db->order_by('score', 'desc');
        
        $this->db->order_by('name', 'asc');
        
        $this->db->limit(5);
        
        $query = $this->db->get('tours');
        
        $results = $query->result_array();
        
        return $results;
    }
    
    function is_outbound_tour($route_ids)
    {
        
        foreach ($route_ids as $id) {
            
            // ignore vietnam :-)
            if($id == DESTINATION_VIETNAM) {
                continue;
            }
            
            $this->db->select('dp.parent_id');
            
            $this->db->where('dp.parent_id', DESTINATION_VIETNAM);
            
            $this->db->where('dp.destination_id', $id);
            
            $query = $this->db->get('destination_places dp');
            
            $results = $query->result_array();
            
            if (empty($results))
            {
                return true;
            }
            
        }
        
        return false;
    }
    
    /**
     * 
     * Khuyenpv 10.09.2014
     * 
     * Get all Destination Id of Tour-Destinations
     * 
     */
    function get_all_tour_destination($tour_id){
    	
    	$ret = array();
    	
    	$this->db->distinct();
    	
    	$this->db->select('destination_id');
    	
    	$this->db->where('tour_id', $tour_id);
    	
    	$query = $this->db->get('destination_tours');
    	
    	$results = $query->result_array();
    	
    	foreach ($results as $tour_des){
    		
    		$ret[] = $tour_des['destination_id'];
    		
    	}
    	
    	return $ret;
    }
    
    /**
     * Save the tour-routes for performance
     * @param unknown $tour_id
     */
    function update_tour_routes($tour_id){
    	
    	$this->db->distinct();
    	
    	$this->db->select('d.id, d.name');
    	
    	$this->db->from('destination_tours as dt');
    	
    	$this->db->join('destinations as d','d.id = dt.destination_id');
    	
    	$this->db->where('dt.tour_id', $tour_id);
    	
    	$this->db->where('dt.is_show_on_route', STATUS_ACTIVE);
    	
    	$this->db->where('dt.hidden', STATUS_INACTIVE);
    	
    	$this->db->order_by('dt.position');
    	
    	$query = $this->db->get();
    	 
    	$results = $query->result_array();
    	
    	$routes = '';
    	
    	foreach ($results as $des){
    		
    		$routes .= '#'.$des['name'].';'.$des['id'];
    		
    	}
    	
    	if(!empty($routes)){
 
    		$routes .= '#';
    	}
    	
    	$this->db->where('id', $tour_id);
    	$this->db->set('routes', $routes);
    	$this->db->update('tours');
    	
    }
    
    /**
     * 
     * Repair data, update tour-routes for all tour
     * 
     */
   	function update_tour_route_4_all_tour()
    {
        $this->db->select('id');
        
        $this->db->where('status', STATUS_ACTIVE);
        
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('tours');
        
        $results = $query->result_array();
        
        foreach ($results as $tour)
        {
            $this->update_tour_routes($tour['id']);
        }
    }
    
    /**
     * Update the is-land-tour flag in the destination-tours relations
     */
    function update_land_tour_flag_for_parent_destinations($tour_id){
    	
    	$this->db->select('destination_id');
    	
    	$this->db->from('destination_tours');
    	
    	$this->db->where('tour_id', $tour_id);
    	
    	$this->db->where('is_land_tour', STATUS_ACTIVE);
    	
    	$query = $this->db->get();
    	
    	$results = $query->result_array();
    	
    	if(count($results) > 0){

    		$des_land_tour_ids = array();
    		
    		foreach ($results as $key=>$value){
    			
    			$des_land_tour_ids[] = $value['destination_id'];
    			
    		}
    		
    		
    		$this->db->select('destination_id');
    		 
    		$this->db->from('destination_tours');
    		 
    		$this->db->where('tour_id', $tour_id);
    		 
    		$this->db->where('is_land_tour', STATUS_INACTIVE);
    		 
    		$query = $this->db->get();
    		 
    		$des_tours = $query->result_array();
    		
    		if(count($des_tours)){
    			
    			foreach ($des_tours as $des){
    				
    				foreach ($des_land_tour_ids as $des_land_tour_id){
    					
    					$this->db->where('destination_id', $des_land_tour_id);
    					$this->db->where('parent_id', $des['destination_id']);
    					$cnt = $this->db->count_all_results('destination_places');
    					
    					if($cnt > 0){
    						
    						$this->db->set('is_land_tour', STATUS_ACTIVE);
    						$this->db->where('tour_id', $tour_id);
    						$this->db->where('destination_id', $des['destination_id']);
    						$this->db->update('destination_tours');
    						
    						break;
    					}
    					
    				}
    				
    			}
    			
    		}
    		
    	}
    }
    
    
    /**
     *
     * Repair data, update tour-routes for all tour
     *
     */
    function update_tour_des_4_all_tour()
    {
    	$this->db->select('id');
    
    	$this->db->where('status', STATUS_ACTIVE);
    
    	$this->db->where('deleted !=', DELETED);
    
    	$query = $this->db->get('tours');
    
    	$results = $query->result_array();
    
    	foreach ($results as $tour)
    	{
    		$this->update_land_tour_flag_for_parent_destinations($tour['id']);
    	}
    }
   	
   	/**
   	  *  get itineraries for create pdf
   	  *
   	  *  @author toanlk
   	  *  @since  Sep 23, 2014
   	  */
   	function get_itineraries($tour_id, $tour_departure = null)
   	{
   	    if(!empty($tour_departure)) {
   	         
   	        $this->db->join('tour_departure_itineraries as tdi', 'tdi.itinerary_id = i.id', 'left outer');
   	         
   	        $this->db->where('tdi.tour_departure_id', $tour_departure);
   	    }
   	     
   	    $this->db->where('i.tour_id', $tour_id);
   	
   	    $this->db->where('i.deleted !=', DELETED);
   	
   	    $this->db->order_by('i.position', 'asc');
   	    $query = $this->db->get('itineraries as i');
   	
   	    $itineraries =  $query->result_array();
   	
   	    /* foreach ($itineraries as $k => $itinerary) {
   	        $itinerary['photos'] = $this->get_itinerary_photos($itinerary['photos']);
   	        	
   	        $itineraries[$k] = $itinerary;
   	    } */
   	
   	    return $itineraries;
   	}
}

?>