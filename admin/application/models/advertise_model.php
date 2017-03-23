<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advertise_Model extends CI_Model {	

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();	
        
		$this->load->database();
    }
	
    function create_advertise($ad){
    	
    	$login_user_id = get_user_id();
    	
    	$ad['user_created_id'] = $login_user_id;
    	$ad['user_modified_id'] = $login_user_id;
    	
    	$ad['date_created'] 	= date(DB_DATE_TIME_FORMAT);
    	$ad['date_modified']	= date(DB_DATE_TIME_FORMAT);
    	$ad['position']			= $this->get_max_position() +1 ;
    	
    	$this->db->insert('advertises', $ad);		
		$id = $this->db->insert_id();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
    	
    }
    
	function update_advertise($id, $ad){

		$ad['date_modified'] = date(DB_DATE_TIME_FORMAT);
		$ad['user_modified_id'] = get_user_id();
    	
    	$this->db->where('id', $id);
    	$this->db->update('advertises', $ad);		
		
    	$error_nr = $this->db->_error_number();
   		
		return !$error_nr;
    	
    }
    
    function get_advertise($id, $get_photo = false){
    	
    	$this->db->where('id', $id);
    	$this->db->where('deleted !=', DELETED);
    	
    	$query = $this->db->get('advertises');
    	
    	$rerults = $query->result_array();
    	
    	if(count($rerults) > 0){
    		
    		$ad = $rerults[0];
    		
    		if($get_photo){
    			
    			$ad['photos'] = $this->get_ad_photos($ad['id']);
    			
    		}
    		
    		return $ad;
    	}
    	
    	return FALSE;
    }
    
    function get_ad_photos($ad_id){
    	
    	$this->db->where('advertise_id', $ad_id);
    	
    	$this->db->order_by('id','asc');
    	
    	
    	$query = $this->db->get('ad_photos');
    	
    	$rerults = $query->result_array();
    	
    	foreach ($rerults as $key => $value){
    		
    		list($width, $height) = getimagesize(get_static_resources('/images/advertises/'.$value['name']));
			$value['width'] = $width;
			$value['height'] = $height;
			
			$rerults[$key] = $value;
    		
    	}
    	
    	return $rerults;
    }
    
   	function delete_advertise($id){
   		
   		$this->db->set('deleted', DELETED);
   		
   		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
   		
   		$this->db->set('user_modified_id', get_user_id());
   		
   		$this->db->where('id', $id);
   		
   		$this->db->update('advertises');
   		
   		$error_nr = $this->db->_error_number();
   		//$error_ms = $this->db->_error_message();
   		
		return !$error_nr;
   	}
   	
   	function _build_search_advertise_condition($search_criteria){
   		
   		if (! empty($search_criteria))
        {
            
            if (! empty($search_criteria['name']))
            {
                
                $this->db->like('ad.name', $search_criteria['name'], 'after');
            }
            
            foreach ($search_criteria as $key => $value)
            {
                
                if ($key == 'display_on' && strval($value) != '')
                {
                    
                    $this->db->where('ad.display_on & ' . pow(2, $value) . '>', 0);
                }
                elseif ($key != 'name' && $key != 'submit_action' && strval($value) != '')
                {
                    
                    $this->db->where('ad.' . $key, $value);
                }
            }
        }
   		
   	}
    
    function search_advertise($search_criteria, $num, $offset, $order_field = 'ad.position', $order_type = 'asc'){
    	
    	$this->db->select('ad.*, u.username as last_modified_by');
    	
    	$this->db->where('ad.deleted !=', DELETED);
    	
    	$this->db->join('users u','u.id = ad.user_modified_id');
    	
    	$this->_build_search_advertise_condition($search_criteria);
    	
    	$this->db->order_by($order_field, $order_type);
    	
    	$query = $this->db->get('advertises ad', $num, $offset);
    	
    	return $query->result_array();
    	
    }
    
 
    
    function count_total_advertise($search_criteria){
    	
    	$this->db->where('ad.deleted !=', DELETED);
    	
    	$this->_build_search_advertise_condition($search_criteria);
    	
    	return $this->db->count_all_results('advertises ad');
    	
    }
   	
    function is_ad_name_exist($name,$id){
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->where('name',$name);
    	
    	if(!empty($id)){
    		
    		$this->db->where('id !=', $id);
    		
    	}
    	
    	$cnt = $this->db->count_all_results('advertises');
    	
    	return $cnt > 0;
    }
    
    function get_destinations($module = HOTEL){
    	
    	$this->db->where('d.deleted !=', DELETED);
    	
    	if($module == HOTEL){
    		$this->db->select('d.id, d.name');
    		$this->db->where('d.is_top_hotel', STATUS_ACTIVE);
    	}
    	if($module == FLIGHT){
    		$this->db->select('d.id, d.name');
    		$this->db->where('d.is_flight_destination', STATUS_ACTIVE);
    	}
    	if($module == TOUR){
    		$this->db->select('d.id, d.name');
    		$this->db->where('d.is_tour_highlight_destination', STATUS_ACTIVE);
    		$this->db->or_where('d.is_tour_destination_group', STATUS_ACTIVE);	
    	}
    	
    	$this->db->order_by('d.position', 'asc');
    	
    	$query = $this->db->get('destinations d');
    	
    	return $query->result_array();
    	
    }
    
    function get_categories(){
    	
    	$this->db->select('cat.id, cat.name');
    	
    	$this->db->where('cat.deleted !=', DELETED);
    	
    	$this->db->order_by('cat.position', 'asc');
    	
    	$query = $this->db->get('categories cat');
    	
    	return $query->result_array();
    
    }
    
    function update_ad_display_setting($id, $ad, $hotel_des, $flight_des, $tour_des, $tour_cat_des){
    	
    	$save_status = $this->update_advertise($id, $ad);
    	
    	if($save_status && !empty($id)){
    		
    		if(!empty($hotel_des) && is_bit_value_contain($ad['display_on'], AD_PAGE_HOTEL_DESTINATION)){
    			
    			$this->db->where('advertise_id', $id);
    			$this->db->where('module', HOTEL);
    			$this->db->delete('ad_destinations');
    			
    			foreach ($hotel_des as $value){
    				
    				$ad_destination['advertise_id'] = $id;
    				
    				$ad_destination['destination_id'] = $value;
    				
    				$ad_destination['module'] = HOTEL;
    				
    				$this->db->insert('ad_destinations', $ad_destination);	
    				
    			}
    			
    		}
    		
    		if(!empty($flight_des) && is_bit_value_contain($ad['display_on'], AD_PAGE_FLIGHT_DESTINATION)){
    			
    			$this->db->where('advertise_id', $id);
    			$this->db->where('module', FLIGHT);
    			$this->db->delete('ad_destinations');
    			
    			foreach ($flight_des as $value){
    				
    				$ad_destination['advertise_id'] = $id;
    				
    				$ad_destination['destination_id'] = $value;
    				
    				$ad_destination['module'] = FLIGHT;
    				
    				$this->db->insert('ad_destinations', $ad_destination);	
    				
    			}
    			
    		}
    		
    		if(!empty($tour_des) && is_bit_value_contain($ad['display_on'], AD_PAGE_TOUR_DESTINATION)){
    			
    			$this->db->where('advertise_id', $id);
    			$this->db->where('module', TOUR);
    			$this->db->delete('ad_destinations');
    			
    			foreach ($tour_des as $value){
    				
    				$ad_destination['advertise_id'] = $id;
    				
    				$ad_destination['destination_id'] = $value;
    				
    				$ad_destination['module'] = TOUR;
    				
    				$this->db->insert('ad_destinations', $ad_destination);	
    				
    			}
    		}
    		
    		if(!empty($tour_cat_des) && is_bit_value_contain($ad['display_on'], AD_PAGE_TOUR_CATEGORY)){
    			
    			$this->db->where('advertise_id', $id);
    			$this->db->delete('ad_tour_categories');
    			
    			foreach ($tour_cat_des as $value){
    				
    				$ad_tour['advertise_id'] = $id;
    				
    				$ad_tour['category_id'] = $value;
    				
    				$this->db->insert('ad_tour_categories', $ad_tour);	
    				
    			}
    		}
    		
    		$error_nr = $this->db->_error_number();
    		return !$error_nr;
    		
    	} else {
    		return $save_status;
    	}
    	
    }
    
    function get_ad_destinations($advertise_id){
    	
    	$this->db->where('advertise_id', $advertise_id);
    	
    	$query = $this->db->get('ad_destinations');
    	
    	return $query->result_array();
    	
    }
    
    function get_ad_tour_categories($advertise_id){
    
    	$this->db->where('advertise_id', $advertise_id);
    	
    	$query = $this->db->get('ad_tour_categories');
    	
    	return $query->result_array();
    	
    }
    
    function create_ad_photos($photos){
    	
    	foreach ($photos as $photo){

    		$this->db->insert('ad_photos', $photo);
    		
    	}   	
    }
    
    function get_photo($id){
    	
    	$this->db->where('id', $id);
    	
    	$query = $this->db->get('ad_photos');
    	
    	$results = $query->result_array();
    	
    	if(count($results) > 0){
    		
    		return $results[0];
    		
    	}
    	return FALSE;
    	
    }
    
    function delete_photo($id){
    	
    	$this->db->where('id', $id);
    	
    	$this->db->delete('ad_photos');
    	
    }
    
    function update_photo($id, $p){
    	
		$this->db->where('id', $id);
		$this->db->update('ad_photos', $p);
    	
    }
    
	function get_max_position($type = 0)  {
        if ($type == 0)
        {
            $this->db->select_max('position');
        }
        else
        {
            $this->db->select_min('position');
        }
    
        $query = $this->db->get('advertises');
    
        $results = $query->result_array();
        
        if (! empty($results)){
    
            return $results[0]['position'];
        }
    
        return 0;
    }
    
    /**
     * Get Tour - Destinations
     * @param string $is_outbound
     */
    function get_tour_destinations($is_outbound = STATUS_INACTIVE){
    	
    	$this->db->select('id, name, nr_tour_domistic, nr_tour_outbound');
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('is_tour_destination_group', STATUS_ACTIVE);
        
      	$this->db->where('is_outbound', $is_outbound);
        
        $this->db->order_by('position', 'asc');
        
        $query = $this->db->get('destinations');
        
        $des_groups = $query->result_array();
 
    	foreach ($des_groups as $key=>$des){
    		
    		$this->db->select('d.id, d.name, d.nr_tour_domistic, d.nr_tour_outbound');
    		
    		$this->db->from('destination_places as dp');
    		
    		$this->db->join('destinations as d', 'd.id = dp.destination_id');
    		
    		$this->db->where('d.deleted !=', DELETED);
    		
    		$this->db->where('dp.parent_id', $des['id']);
    		
    		$this->db->where('d.is_tour_highlight_destination', STATUS_ACTIVE);
    		
    		$this->db->order_by('d.position', 'asc');
    		
    		$query = $this->db->get();
    		
    		$des['destinations'] = $query->result_array();
    		
    		$des_groups[$key] = $des;
    		
    	}
    	
    	return $des_groups;
    }

}