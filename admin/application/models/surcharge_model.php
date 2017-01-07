<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surcharge_Model extends CI_Model {	

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();	
        
		$this->load->database();
    }
	
    function create_surcharge($sur){
    	
    	$login_user_id = get_user_id();
    	
    	$sur['user_created_id'] = $login_user_id;
    	$sur['user_modified_id'] = $login_user_id;
    	
    	$sur['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$sur['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    	$this->db->insert('surcharges', $sur);		
		$id = $this->db->insert_id();
		
		
		// update rate has surcharge
		if( !empty($sur['hotel_id']) ) {
			$sur = $this->get_surcharge($id);
			$this->load->model('Rate_Model');
			$this->Rate_Model->update_hotel_rate_has_surcharge($sur['hotel_id']);
		}
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
    	
    }
    
	function update_surcharge($id, $sur){

		$sur['date_modified'] = date(DB_DATE_TIME_FORMAT);
		
    	$sur['user_modified_id'] = get_user_id();
    	
    	$this->db->where('id', $id);
    	$this->db->update('surcharges', $sur);

    	// update rate has surcharge
    	$sur = $this->get_surcharge($id);
    	$this->load->model('Rate_Model');
    	$this->Rate_Model->update_hotel_rate_has_surcharge($sur['hotel_id']);
		
    	$error_nr = $this->db->_error_number();
   		
		return !$error_nr;
    	
    }
    
    function get_surcharge($id, $get_deleted = false){
    	
    	$this->db->where('id', $id);
    	
    	if(!$get_deleted){
    	
    		$this->db->where('deleted !=', DELETED);
    	
    	}
    	
    	$query = $this->db->get('surcharges');
    	
    	$rerults = $query->result_array();
    	
    	if(count($rerults) > 0){
    		
    		return $rerults[0];
    	}
    	
    	return FALSE;
    }
    
   	function delete_surcharge($id){
   		
   		$this->db->set('deleted', DELETED);
   		
   		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
   		
   		$this->db->set('user_modified_id', get_user_id());
   		
   		$this->db->where('id', $id);
   		
   		$this->db->update('surcharges');
   		
   		// update rate has surcharge
   		if( !empty($sur['hotel_id']) ) {
	    	$sur = $this->get_surcharge($id, true);
	    	$this->load->model('Rate_Model');
	    	$this->Rate_Model->update_hotel_rate_has_surcharge($sur['hotel_id'], $sur['start_date'], $sur['end_date'], true);
   		}
   		
   		$error_nr = $this->db->_error_number();
   		
		return !$error_nr;
   	}
   	
   	function _build_search_surcharge_condition($search_criteria){
   		
   		if(!empty($search_criteria)){

   			if(!empty($search_criteria['name'])){
   				
   				$this->db->like('name', $search_criteria['name'], 'after');
   				
   			}
   			
   			foreach ($search_criteria as $key=>$value){
   				
   				if($key != 'name' && !empty($value)){
   					
   					if($key == 'start_date'){
   						
   						$value = date(DB_DATE_FORMAT, strtotime($value));
   						
   						$this->db->where('end_date >=', $value);
   						
   					}
   					
   					if($key == 'end_date'){
   						
   						$value = date(DB_DATE_FORMAT, strtotime($value));
   						
   						$this->db->where('start_date <=', $value);
   						
   					}
   					
   					if($key == 'charge_type'){
   						
   						$this->db->where('charge_type', $value);
   						
   					}
   					
   					if($key == 'hotel_id'){
   							
   						$this->db->where('hotel_id', $value);
   							
   					}
   					
   					if($key == 'cruise_id'){
   					
   						$this->db->where('cruise_id', $value);
   					
   					}
   				}
   				
   			}
   			
   		}
   		
   	}
    
    function search_surcharge($search_criteria, $per_page, $offset){
    	
    	$this->db->select('s.*, u.username as last_modified_by');
    	
    	$this->db->where('s.deleted !=', DELETED);
    	
    	$this->db->join('users u', 's.user_modified_id = u.id', 'left outer');
    	
    	$this->_build_search_surcharge_condition($search_criteria);
    	
    	$query = $this->db->get('surcharges s', $per_page, $offset);
    	
    	return $query->result_array();
    	
    }
    
    function count_total_surcharge($search_criteria){
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->_build_search_surcharge_condition($search_criteria);
    	
    	return $this->db->count_all_results('surcharges');
    	
    }
   	
    function is_sur_name_exist($name,$id){
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->where('name',$name);
    	
    	if(!empty($id)){
    		
    		$this->db->where('id !=', $id);
    		
    	}
    	
    	$cnt = $this->db->count_all_results('surcharges');
    	
    	return $cnt > 0;
    }
    
    function create_cruise_surcharge($sur){
    	 
    	$sur['user_created_id'] = get_user_id();
    	$sur['user_modified_id'] = get_user_id();
    	 
    	$sur['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$sur['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    	$apply_on_tours = $sur['apply_on_tours'];
    	unset($sur['apply_on_tours']);
    	 
    	$this->db->insert('surcharges', $sur);
    	$sur_id = $this->db->insert_id();
    
    	$this->load->model('Tour_Rate_Model');
    	 
    	foreach ($apply_on_tours as $tour) {
    		$tour_surcharge = array(
    				'surcharge_id'	=> $sur_id,
    				'tour_id'		=> $tour['id'],
    				'adult_amount'	=> $tour['adult_amount'],
    				'children_amount'	=> $tour['children_amount'],
    		);
    		$this->db->insert('surcharge_tours', $tour_surcharge);
    	
    		// update rate has surcharge
    		//$this->Tour_Rate_Model->update_tour_rate_has_surcharge($tour['id']);
    	}
    	$error_nr = $this->db->_error_number();
    
    	return !$error_nr;
    	 
    }

    function update_cruise_surcharge($sur) {

		$sur['date_modified'] = date(DB_DATE_TIME_FORMAT);
		
    	$sur['user_modified_id'] = get_user_id();
    	
    	$apply_on_tours = $sur['apply_on_tours'];
    	unset($sur['apply_on_tours']);
    	
    	$this->db->update('surcharges', $sur, array('id' => $sur['id']));
    	
    	// 1. delete the old tour surcharges
    	$this->db->where('surcharge_id', $sur['id']);
    	$this->db->delete('surcharge_tours');
    	
    	// 2. then create new tour surcharges
    	$this->load->model('Tour_Rate_Model');
    	
    	foreach ($apply_on_tours as $tour) {
    		$tour_surcharge = array(
    				'surcharge_id'	=> $sur['id'],
    				'tour_id'		=> $tour['id'],
    				'adult_amount'	=> $tour['adult_amount'],
    				'children_amount'	=> $tour['children_amount'],
    		);
    		$this->db->insert('surcharge_tours', $tour_surcharge);
    		
    		// update rate has surcharge
    		//$this->Tour_Rate_Model->update_tour_rate_has_surcharge($tour['id']);
    	}

    	$error_nr = $this->db->_error_number();
   		
		return !$error_nr;
    	
    }
    
    function get_tour_surcharge($surcharge_id){
    	 
    	$this->db->where('surcharge_id', $surcharge_id);
    	 
    	$query = $this->db->get('surcharge_tours');
    	 
    	return $query->result_array();
    }
}