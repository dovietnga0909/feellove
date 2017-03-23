<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promotion_Model extends CI_Model {	

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();	
        
		$this->load->database();
		
		$this->load->model(array('Rate_Model', 'Tour_Rate_Model'));
		
		$this->load->helper('rate');
    }
	
    function create_promotion($pro){
    	
    	$login_user_id = get_user_id();
    	
    	$pro['user_created_id'] = $login_user_id;
    	$pro['user_modified_id'] = $login_user_id;
    	
    	$pro['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$pro['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    	if(isset($pro['pro_room_types'])){
    		
    		$promotion_room_types = $pro['pro_room_types'];
    		
    		unset($pro['pro_room_types']);
    	}
    	
    	$promotion_tours = array();
    	if(isset($pro['pro_tours'])){
    	
    		$promotion_tours = $pro['pro_tours'];
    	
    		unset($pro['pro_tours']);
    	}
    	
    	unset($pro['step']);
    	
    	
    	$this->db->insert('promotions', $pro);		
		$id = $this->db->insert_id();
		$pro['id'] = $id;
		
		if(isset($promotion_room_types)){
			
			$max_get = 0;
			
			foreach ($promotion_room_types as $value){
				
				$value['hotel_id'] = $pro['hotel_id'];
				
				$value['promotion_id'] = $id;
				
				if($value['get'] > $max_get){
					$max_get = $value['get'];
				}
				
				$this->db->insert('promotion_room_types', $value);
				
			}
			
			/**
			 * Khuyenpv: If the promotion specific for each room-type, get the max GET value from the room_type
			 */
			if($max_get > 0 && ($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT || $pro['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT)){
				if($pro['room_type'] == 2){ //specific room type
						
					$this->db->where('id', $id);
					$this->db->set('get_1', $max_get);
					$this->db->update('promotions');
				}
			}
			
		}
			
		if(!empty($pro['hotel_id'])){
			$this->update_hotel_price_from_by_promotion($pro);
		} else {
			// update tour_promotion_details
			$this->_update_tour_promotions($promotion_tours, $pro);
			
			// update tour rates
			$this->update_tour_price_from_by_promotion($pro);
		}
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
    	
    }
    
	function update_promotion($id, $pro){

		$pro['date_modified'] = date(DB_DATE_TIME_FORMAT);
		$pro['user_modified_id'] = get_user_id();
		
		if(isset($pro['pro_room_types'])){
    		
    		$promotion_room_types = $pro['pro_room_types'];
    		
    		unset($pro['pro_room_types']);
    	}
    	
    	$promotion_tours = array();
    	if(isset($pro['pro_tours'])){
    		 
    		$promotion_tours = $pro['pro_tours'];
    		 
    		unset($pro['pro_tours']);
    	}
    	
    	unset($pro['step']);
    	
    	$this->db->where('id', $id);
    	$this->db->update('promotions', $pro);
		
    	$pro['id'] = $id;
    	
		if(isset($promotion_room_types)){
			
		    // delete all promotion_room_types records 
		    $this->db->where('promotion_id', $id);
		    $this->db->delete('promotion_room_types');
		    
		    // insert new promotion_room_types
			$max_get = 0;
			
			foreach ($promotion_room_types as $value){
				
				$value['hotel_id'] = $pro['hotel_id'];
				
				$value['promotion_id'] = $id;
				
				if($value['get'] > $max_get){
					$max_get = $value['get'];
				}
				
				$this->db->insert('promotion_room_types', $value);
				
			}
			
			/**
			 * Khuyenpv: If the promotion specific for each room-type, get the max GET value from the room_type
			 */
			if($max_get > 0 && ($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT || $pro['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT)){
				if($pro['room_type'] == 2){ //specific room type
					
					$this->db->where('id', $id);
					$this->db->set('get_1', $max_get);
					$this->db->update('promotions');
				}
			}
		}
		
		
		if(!empty($pro['hotel_id'])){	// UPDATE hotel rates
			
			$this->update_hotel_price_from_by_promotion($pro);
		
		} else {	// UPDATE tour rates
			
			$this->db->where('promotion_id', $id);
			$this->db->delete('promotion_tours');
			
			$this->db->where('promotion_id', $id);
			$this->db->delete('tour_promotion_details');
			
			// update tour_promotion_details
			$this->_update_tour_promotions($promotion_tours, $pro);
			
			// Update tour rates
			$this->update_tour_price_from_by_promotion($pro);
		}
		
    	$error_nr = $this->db->_error_number();
   		
		return !$error_nr;
    	
    }
    
    function _update_tour_promotions($promotion_tours, $pro){
    	
    	// Update tour promotions
    	if(isset($promotion_tours) && $pro['room_type'] == 2){
    	
    		$max_get_pro = 0;
    	
    		foreach ($promotion_tours as $value){
    		    
    		    $tour_pro_details = array();
    		     
    		    if(isset($value['tour_pro_details'])){
    		         
    		        $tour_pro_details = $value['tour_pro_details'];
    		         
    		        unset($value['tour_pro_details']);
    		    }
    		     
    		    if(isset($pro['cruise_id'])) {
    		        $value['cruise_id'] = $pro['cruise_id'];
    		    }
    		     
    		    $value['promotion_id'] = $pro['id'];
    		     
    		    $this->db->insert('promotion_tours', $value);
    		     
    		    $tour_promotion_id = $this->db->insert_id();
    	
    			$max_get = 0;
    	
    			if(!empty($tour_pro_details)){
    					
    				foreach ($tour_pro_details as $tour_pro_detail){
    	
    					$tour_pro_detail['tour_promotion_id'] = $tour_promotion_id;
    	
    					$tour_pro_detail['promotion_id'] = $pro['id'];
    	
    					if(isset($pro['cruise_id'])) {
    					    $tour_pro_detail['cruise_id'] = $pro['cruise_id'];
    					}
    					
    					if(isset($pro['tour_departure_id'])) {
    					    $tour_pro_detail['tour_departure_id'] = $pro['tour_departure_id'];
    					}
    	
    					$this->db->insert('tour_promotion_details', $tour_pro_detail);
    	
    					if($tour_pro_detail['get'] > $max_get){
    						$max_get = $tour_pro_detail['get'];
    					}
    	
    				}
    					
    			}
    	
    			if($max_get > 0){
    	
    				$this->db->where('id', $tour_promotion_id);
    				$this->db->set('get', $max_get);
    				$this->db->update('promotion_tours');
    					
    				if($max_get > $max_get_pro){
    	
    					$max_get_pro = $max_get;
    				}
    			}
    	
    		}
    			
    		if($max_get_pro > 0){
    			$this->db->where('id', $pro['id']);
    			$this->db->set('get_1', $max_get_pro);
    			$this->db->update('promotions');
    		}
    	
    	} elseif($pro['room_type'] == 1 && !empty($pro['cruise_id'])) { // apply for all tours
    		
    		$cruise_id = $pro['cruise_id'];
    		
    		$tours = $this->get_cruise_tours($cruise_id);
    		
    		foreach ($tours as $tour){
    		
    			$promotion_tour['cruise_id'] = $cruise_id;
    			 
    			$promotion_tour['promotion_id'] = $pro['id'];
    			
    			$promotion_tour['tour_id'] = $tour['id'];
    			
    			$promotion_tour['get'] = $pro['get_1'];
    			
    			$this->db->insert('promotion_tours', $promotion_tour);
    			 
    			$tour_promotion_id = $this->db->insert_id();
    			
    			foreach ($tour['accommodations'] as $acc){
    				
    				$tour_pro_detail['tour_promotion_id'] = $tour_promotion_id;
    				 
    				$tour_pro_detail['promotion_id'] = $pro['id'];
    				 
    				$tour_pro_detail['cruise_id'] = $pro['cruise_id'];
    				
    				$tour_pro_detail['get'] = $pro['get_1'];
    				
    				$tour_pro_detail['tour_id'] = $tour['id'];
    				
    				$tour_pro_detail['accommodation_id'] = $acc['id'];
    				 
    				$this->db->insert('tour_promotion_details', $tour_pro_detail);
    				
    			}
    		}
   
    		
		}
        elseif ($pro['room_type'] == 1 && ! empty($pro['tour_id'])) // for land tour
        { 
		    
		    foreach ($promotion_tours as $value)
            {
                $tour_pro_details = array();
                
                if (isset($value['tour_pro_details']))
                {
                    $tour_pro_details = $value['tour_pro_details'];
                    
                    unset($value['tour_pro_details']);
                }
                
                $value['promotion_id'] = $pro['id'];
                
                $this->db->insert('promotion_tours', $value);
                
                $tour_promotion_id = $this->db->insert_id();
                
                if (! empty($tour_pro_details))
                {
                    
                    foreach ($tour_pro_details as $tour_pro_detail)
                    {
                        
                        $tour_pro_detail['tour_promotion_id'] = $tour_promotion_id;
                        
                        $tour_pro_detail['promotion_id'] = $pro['id'];
                        
                        $this->db->insert('tour_promotion_details', $tour_pro_detail);
                    }
                }
            }
		}
    	
    }
    
    
    function get_promotion($id){
    	
    	$this->db->where('id', $id);
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$query = $this->db->get('promotions');
    	
    	$rerults = $query->result_array();
    	
    	if(count($rerults) > 0){
    		
    		$pro = $rerults[0];
    		
    		if($pro['room_type'] == 1){ // all room types
    			
    		} else { // specific room types
    			
    			$pro['pro_room_types'] = $this->get_promotion_room_types($pro['id']);
    			
    		}
    		
    		$pro['pro_tours'] = $this->get_promotion_tours($pro['id']);
    		
    		return $pro;
    	}
    	
    	return FALSE;
    }
    
    function get_promotion_room_types($promotion_id){
    	
    	$this->db->where('promotion_id', $promotion_id);
    	
    	$query = $this->db->get('promotion_room_types');
    	
    	$rerults = $query->result_array();
    	
    	return $rerults;
    	
    }
    
    function get_promotion_tours($promotion_id){
    	 
    	$this->db->where('promotion_id', $promotion_id);
    	 
    	$query = $this->db->get('promotion_tours');
    	 
    	$rerults = $query->result_array();
    	
    	foreach ($rerults as $key=>$value){
    		
    		$value['tour_pro_details'] = $this->get_promotion_tour_details($value['id']);
    		
    		$rerults[$key] = $value;
    	}
    	
    	 
    	return $rerults;
    	 
    }
    
    function get_promotion_tour_details($promotion_tour_id){
    
    	$this->db->where('tour_promotion_id', $promotion_tour_id);
    
    	$query = $this->db->get('tour_promotion_details');
    
    	$rerults = $query->result_array();
    
    	return $rerults;
    
    }
    
   	function delete_promotion($id){
   		
   		$pro = $this->get_promotion($id);
   		
   		$this->db->set('deleted', DELETED);
   		
   		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
   		
   		$this->db->set('user_modified_id', get_user_id());
   		
   		$this->db->where('id', $id);
   		
   		$this->db->update('promotions');
   		
   		if(!empty($pro['hotel_id'])){	// UPDATE hotel rates
   				
   			$this->update_hotel_price_from_by_promotion($pro);
   		
   		} else {	// UPDATE tour rates
   				
   			$this->db->where('promotion_id', $id);
   			$this->db->delete('promotion_tours');
   				
   			$this->db->where('promotion_id', $id);
   			$this->db->delete('tour_promotion_details');
   				
   			// Update tour rates
   			$this->update_tour_price_from_by_promotion($pro);
   		}
   		
   		$error_nr = $this->db->_error_number();
   		//$error_ms = $this->db->_error_message();
   		
		return !$error_nr;
   	}
   	
   	function _build_search_promotion_condition($search_criteria){
   		
   		if(!empty($search_criteria)){
   			
   			foreach ($search_criteria as $key=>$value){
   				
   				if($key != 'name' && $value !== ''){
   					
   					if($key == 'start_date'){
   						
   						$value = date(DB_DATE_FORMAT, strtotime($value));
   						
   						$this->db->where('stay_date_to >=', $value);
   						
   					}
   					
   					if($key == 'end_date'){
   						
   						$value = date(DB_DATE_FORMAT, strtotime($value));
   						
   						$this->db->where('stay_date_from <=', $value);
   						
   					}
   					
   					if($key == 'promotion_type'){
   						
   						$this->db->where('promotion_type', $value);
   						
   					}
   					
   					if($key == 'show_on_web'){
   							
   						$this->db->where('show_on_web', $value);
   							
   					}
   					
   					if($key == 'hotel_id'){
   							
   						$this->db->where('hotel_id', $value);
   							
   					}
   					
   					if($key == 'cruise_id'){
   					
   						$this->db->where('cruise_id', $value);
   					
   					}
   					
   					if($key == 'tour_id'){
   					
   					    $this->db->where('tour_id', $value);
   					
   					}
   				}
   				
   			}
   			
   		}
   		
   	}
    
    function search_promotion($search_criteria, $per_page, $offset){
    	
    	$this->db->select('p.*, u.username as last_modified_by');
    	
    	$this->db->where('p.deleted !=', DELETED);
    	
    	$this->db->join('users u', 'p.user_modified_id = u.id', 'left outer');
    	
    	$this->_build_search_promotion_condition($search_criteria);
    	
    	$query = $this->db->get('promotions p', $per_page, $offset);
    	
    	return $query->result_array();
    	
    }
    
    function count_total_promotion($search_criteria){
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->_build_search_promotion_condition($search_criteria);
    	
    	return $this->db->count_all_results('promotions');
    	
    }
   	
    function is_pro_name_exist($name,$id){
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->where('name',$name);
    	
    	if(!empty($id)){
    		
    		$this->db->where('id !=', $id);
    		
    	}
    	
    	$cnt = $this->db->count_all_results('promotions');
    	
    	return $cnt > 0;
    }
    
    function get_hotel_room_types_4_pro($hotel_id){
    	
    	$this->db->select('id, name');
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->where('hotel_id', $hotel_id);
    	
    	$this->db->order_by('position','asc');
    	
    	$query = $this->db->get('room_types');
    	
    	return $query->result_array();
    }
    
    function update_hotel_price_from_by_promotion($pro){
    	
    	if(isset($pro['id'])){
    	
    		$this->db->where('promotion_id', $pro['id']);
    		
    		$this->db->delete('hotel_price_froms');
    	
    	}
    	
    	$start_date = $pro['stay_date_from'];
    	$end_date = $pro['stay_date_to'];
    	
    	$rate_dates = get_days_between_2_dates($start_date, $end_date);
    	
    	$this->Rate_Model->update_hotel_price_from($pro['hotel_id'], $rate_dates);
    }
    
    function update_tour_price_from_by_promotion($pro){
    	
    	$start_date = $pro['stay_date_from'];
        $end_date = $pro['stay_date_to'];
        
        $rate_dates = get_days_between_2_dates($start_date, $end_date);
        
        $this->db->select('id');
        
        // Cruise Promotion
        if (! empty($pro['cruise_id']))
        {
            $this->db->where('cruise_id', $pro['cruise_id']);
        }
        // Normal Tour Promotion
        else if (! empty($pro['tour_id']))
        {
            $this->db->where('id', $pro['tour_id']);
        }
        
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('tours');
        
        $tours = $query->result_array();
        
        if (! empty($tours))
        {
            foreach ($tours as $tour)
            {
                $this->Tour_Rate_Model->update_tour_price_from($tour['id'], $rate_dates);
            }
        }
    	
    }
    
    function update_promotion_max_discount_from_room_types(){
    	
    	$this->db->select('id');
    	
    	$this->db->where('deleted != ', DELETED);
    	
    	$this->db->where('show_on_web', STATUS_ACTIVE);
    	
    	$this->db->where('hotel_id > 0');
    	
    	$this->db->where_in('discount_type', array(DISCOUNT_TYPE_DISCOUNT, DISCOUNT_TYPE_FREE_NIGHT));
    	
    	$this->db->where('room_type', 2); //specific room types;
    	
    	$query = $this->db->get('promotions');
    	
    	$promotions = $query->result_array();
    	
    	foreach($promotions as $pro){
    		
    		$pro_room_types = $this->get_promotion_room_types($pro['id']);
    		
    		$max_get = 0;
    		
    		foreach ($pro_room_types as $pro_room_type){
    			
    			if($pro_room_type['get'] > $max_get){
    				
    				$max_get = $pro_room_type['get'];
    				
    			}
    			
    		}
    		
    		if($max_get > 0){
    			
    			$this->db->where('id', $pro['id']);
				$this->db->set('get_1', $max_get);
				$this->db->update('promotions');
    			
    		}
    		
    	}
    	
    }
    
    function get_cruise_tours($cruise_id){
    	
    	$this->db->select('id, name');
    	
    	$this->db->from('tours');
    	
    	$this->db->where('cruise_id', $cruise_id);
    	
    	$this->db->where('status', STATUS_ACTIVE);
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->order_by('position','asc');
    	
    	$query = $this->db->get();
    	
    	$tours = $query->result_array();
    	
    	foreach ($tours as $key=>$tour){
    		
    		$tour['accommodations'] = $this->get_tour_accommodations($tour['id']);
    		
    		$tours[$key] = $tour;
    	}
    	
    	return $tours;
    }
    
    function get_tour_accommodations($tour_id){
    	
    	$this->db->select('id, name');
    	 
    	$this->db->from('accommodations');
    	 
    	$this->db->where('tour_id', $tour_id);
    	 
    	$this->db->where('deleted !=', DELETED);
    	 
    	$this->db->order_by('position','asc');
    	 
    	$query = $this->db->get();
    	 
    	$acc = $query->result_array();
    	 
    	return $acc;
    	
    }

}