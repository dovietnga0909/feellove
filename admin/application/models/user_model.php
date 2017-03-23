<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model{	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_users($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		$this->db->where('u.deleted !=', DELETED);
		return $this->db->count_all_results('users u');
	}
	
	function search_users($search_criteria = '' , $num, $offset , $order_field = 'id', $order_type = 'asc')
	{
		$this->db->select('u.id, u.username, u.full_name, u.email, u.status, u.date_modified, p.name as partner_name, m.username as last_modified_by, u.display_on, u.allow_assign_request');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->where('u.deleted !=', DELETED);
		
		$this->db->join('partners p', 'u.partner_id = p.id', 'left outer');
		$this->db->join('users m', 'u.user_modified_id = m.id', 'left outer');
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('users u', $num, $offset);
		
		return $query->result_array();
	}

	private function _set_search_criteria($search_criteria = '')
	{
		if ($search_criteria == '')	{
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				case 'search_text' :
					$value =  $this->db->escape_like_str($value);
					$this->db->like('u.full_name', $value, 'both');
					$this->db->or_like('u.username', $value, 'both');
					$this->db->or_like('u.email', $value, 'both');
					$this->db->or_like('u.hotline_number', $value, 'both');
					break;
					
				case 'display_on' :
					if($value !=''){
						$this->db->where('u.display_on & '.pow(2,$value) .' > 0');
					}
					break;
				case 'status' :
					if($value !=''){
						$this->db->where('u.status', $value);
					}
					break;
				case 'allow_assign_request' :
					if($value !=''){
						$this->db->where('u.allow_assign_request', $value);
					}
					break;
			}
		}
	}
	
	/**
	 * register
	 *
	 * @return bool
	 **/
	public function create_user($user)
	{	
		// Additional data
		$additional_data = array(
			'ip_address'    	=> $this->input->ip_address(),
			'user_created_id'	=> get_user_id(),
			'user_modified_id'	=> get_user_id(),
			'date_created'		=> date(DB_DATE_TIME_FORMAT),
			'date_modified'		=> date(DB_DATE_TIME_FORMAT),
			'last_login'		=> time(),
			'status'			=> 1,
		);
		
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$user_data = array_merge($user, $additional_data);
		
		$this->db->insert('users', $user_data);
		
		$id = $this->db->insert_id();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_user($id) {
		
		if(empty($id)) {
			return FALSE;
		}
		
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
		
		$query = $this->db->get('users');
		
		$result = $query->result_array();
		
		if (count($result) === 1)
		{
			$user = $result[0];
			$user['roles'] = $this->get_user_roles($user['id']);
			return $user;
		}
		
		return FALSE;
	}
	
	function update_user($user, $current_roles, $new_roles) {
		
		$this->db->trans_start();
		
		// update user details
		$user['user_modified_id'] = get_user_id();
		$user['date_modified'] = date(DB_DATE_TIME_FORMAT);
		
		$this->db->update('users', $user, array('id' => $user['id']));
		
		// update user roles
		if($current_roles != $new_roles) {
			$this->update_user_roles($user['id'], $new_roles);
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_user($id) {
	
		$user['deleted'] = DELETED;
	
		$this->db->update('users', $user, array('id' => $id));
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_user_roles($user_id) {
		
		$this->db->select('role_id');
		$this->db->where('user_id', $this->db->escape_str($user_id));
		$query = $this->db->get('user_roles');
		
		$results = $query->result_array();
		
		$roles = array();
		
		if(!empty($results)) {
			foreach ($results as $result) {
				$roles[] = $result['role_id'];
			}	
		}
		
		return $roles;
	}
	
	function update_user_roles($user_id, $roles) {
    	
		$this->db->trans_start();
		
        // remove all roles of user
        $this->db->where('user_id', $user_id);
        $this->db->delete('user_roles');

        if(!empty($roles)) {
        	foreach($roles as $role) {
        		$user_role = array(
        				'role_id' => $role,
        				'user_id' => $user_id,
        		);
        	
        		$this->db->insert('user_roles', $user_role);
        	}	
        }

       	$this->db->trans_complete();
		
		return $this->db->trans_status();
    }
    
    /**
     * User Hotline Schedule
     * 
     */
    function _build_search_hotline_schedule_condition($search_criteria){
    	 
    	if(!empty($search_criteria)){
    
    		foreach ($search_criteria as $key=>$value){
    
    			if($key == 'user_id' && $value != ''){
    						
    				$this->db->where('hs.user_id', $value);
    						
    			}
    
    			if($key == 'status' && $value != ''){
    
    				$this->db->where('hs.status', $value);
    
    			}
    			
    			if($key == 'display_on' && $value != ''){
    			
    				$this->db->where('u.display_on & '.pow(2, $value).' > 0');
    			
    			}
    				
    		}
    
    	}
    	 
    }
    
    function search_hotline_schedules($search_criteria, $per_page, $offset){
    	
    	$this->db->select('hs.*, u.username');
    	
    	$this->db->from('hotline_schedules as hs');
    	
    	$this->db->join('users as u', 'u.id = hs.user_id');
    	 
    	$this->_build_search_hotline_schedule_condition($search_criteria);
    	
    	$this->db->order_by('u.username');
    	 
    	$query = $this->db->get('', $per_page, $offset);
    	 
    	return $query->result_array();
    	 
    }
    
    function count_total_hotline_schedule($search_criteria){

    	$this->db->from('hotline_schedules as hs');
    	
    	$this->db->join('users as u', 'u.id = hs.user_id');
    	
    	$this->_build_search_hotline_schedule_condition($search_criteria);
    	 
    	return $this->db->count_all_results();
    	 
    }
    
    function delete_hotline_schedule($id){
    	$this->db->where('id', $id);
    	$this->db->delete('hotline_schedules');
    	$error_nr = $this->db->_error_number();
    	
    	return !$error_nr;
    }
 
    
    function get_hotline_users(){
    	
    	$this->db->where('display_on > ', 0);
    	//$this->db->where('status', STATUS_ACTIVE);
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->order_by('username');
    	
    	$query = $this->db->get('users');
    	
    	return $query->result_array();
    }
    
    function create_schedule($hotline_schedule){
    	
    	$hotline_schedule['user_created_id'] = get_user_id();
    	$hotline_schedule['user_modified_id'] = get_user_id();
    	
    	$hotline_schedule['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$hotline_schedule['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    	$this->db->insert('hotline_schedules', $hotline_schedule);
    	
    	$id = $this->db->insert_id();
    	
    	$error_nr = $this->db->_error_number();
    	
    	return !$error_nr;
    }
    
    function get_hotline_schedule($id){
    	$this->db->where('id', $id);
    	$query = $this->db->get('hotline_schedules');
    	
    	$results = $query->result_array();
    	
    	if(count($results) > 0){
    		return $results[0];
    	} else {
    		return '';
    	}
    }
    
    function edit_schedule($hotline_schedule, $id){
    	
    	$hotline_schedule['user_modified_id'] = get_user_id();
    	 
    	$hotline_schedule['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    	$this->db->where('id', $id);
    	 
    	$this->db->update('hotline_schedules', $hotline_schedule);
    	 
    	$error_nr = $this->db->_error_number();
    	 
    	return !$error_nr;
    }
}

?>