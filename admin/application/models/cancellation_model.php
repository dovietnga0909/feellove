<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cancellation_Model extends CI_Model {	

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();	
        
		$this->load->database();
    }
	
    function create_cancellation($can){
    	
    	$login_user_id = get_user_id();
    	
    	$can['user_created_id'] = $login_user_id;
    	$can['user_modified_id'] = $login_user_id;
    	
    	$can['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$can['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    	$this->db->escape();
    	$this->db->insert('cancellations', $can);		
		$id = $this->db->insert_id();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
    	
    }
    
	function update_cancellation($id, $can){

		$can['date_modified'] = date(DB_DATE_TIME_FORMAT);
		$can['user_modified_id'] = get_user_id();
    	
    	$this->db->where('id', $id);
    	$this->db->update('cancellations', $can);		
		
    	$error_nr = $this->db->_error_number();
   		//$error_ms = $this->db->_error_message();
   		
		return !$error_nr;
    	
    }
    
    function get_cancellation($id){
    	
    	$this->db->where('id', $id);
    	
    	$query = $this->db->get('cancellations');
    	
    	$rerults = $query->result_array();
    	
    	if(count($rerults) > 0){
    		
    		return $rerults[0];
    	}
    	
    	return FALSE;
    }
    
   	function delete_cancellation($id){
   		
   		$this->db->set('deleted', DELETED);
   		
   		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
   		
   		$this->db->set('user_modified_id', get_user_id());  		
   		
   		$this->db->where('id', $id);
   		
   		$this->db->update('cancellations');
   		
   		$error_nr = $this->db->_error_number();
   		//$error_ms = $this->db->_error_message();
   		
		return !$error_nr;
   	}
   	
   	function _build_search_cancellation_condition($search_criteria){
   		
   		if(!empty($search_criteria)){

   			if(!empty($search_criteria['name'])){
   				
   				$this->db->like('name', $search_criteria['name'], 'after');
   				
   			}
   			foreach ($search_criteria as $key=>$value){
   				
   				if($key == 'service_type' && strval($value) != ''){
   					
   					$this->db->where('service_type & '.pow(2,$value).'>',0);
   					
   				}elseif($key != 'name' && strval($value) != ''){
   					
   					$this->db->where($key, $value);
   					
   				}
   			}
   			
   		}
   		
   	}
    
    function search_cancellation($search_criteria, $per_page, $offset){
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->_build_search_cancellation_condition($search_criteria);
    	
    	$query = $this->db->get('cancellations', $per_page, $offset);
    	
    	return $query->result_array();
    	
    }
    
    function get_can_search_filter_data($field_name){
    	
    	$this->db->distinct();
    	
    	$this->db->select($field_name);
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->order_by($field_name,'asc');
    	
    	$query = $this->db->get('cancellations');
    	
    	return $query->result_array();
    }
    
    function count_total_cancellation($search_criteria){
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->_build_search_cancellation_condition($search_criteria);
    	
    	return $this->db->count_all_results('cancellations');
    	
    }
   	
    function is_can_name_exist($name,$id){
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->where('name',$name);
    	
    	if(!empty($id)){
    		
    		$this->db->where('id !=', $id);
    		
    	}
    	
    	$cnt = $this->db->count_all_results('cancellations');
    	
    	return $cnt > 0;
    }
    
    function get_all_cancellations($service_type = null){
    	
    	$this->db->select('id, name, content');
        
        if (! empty($service_type))
        {
            $this->db->where('service_type &' . pow(2, $service_type) . ' > 0');
        }
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->order_by('name', 'asc');
        
        $query = $this->db->get('cancellations');
        
        return $query->result_array();
    }

}