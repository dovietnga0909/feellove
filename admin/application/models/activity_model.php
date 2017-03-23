<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_Model extends CI_Model{	

	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_activities($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		
		return $this->db->count_all_results('activities a');
	}
	
	function search_activities($search_criteria = '', $num, $offset) {
		
		$this->db->select('a.*, u.username as last_modified_by');
		
		$this->db->join('users u', 'a.user_modified_id = u.id', 'left outer');
		
		$this->_set_search_criteria($search_criteria);
		
		$query = $this->db->get('activities a', $num, $offset);
		
		$activities = $query->result_array();
		
		return $activities;
	}
	
	public function _set_search_criteria($search_criteria = '') {
		$this->db->where('a.deleted !=', DELETED);
		
		if ($search_criteria == '')	{
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				case 'destination_id' :
					$this->db->where('a.destination_id', $value);
					break;
			}
		}
	}
	
	/**
	 * create_activity
	 *
	 * @return bool
	 **/
	
	public function create_activity($activity) {
		
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'status'			=> 1,
		);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		
		$activity_data = array_merge($activity, $additional_data);
	
		$this->db->insert('activities', $activity_data);
	
		$id = $this->db->insert_id();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_activity($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('activities');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{	
			$activity = $result[0];
			return $activity;
		}
	
		return FALSE;
	}
	
	function update_activity($activity){
		
		$activity['user_modified_id'] 	= get_user_id();
		$activity['date_modified'] 		= date(DB_DATE_TIME_FORMAT);
		
		$this->db->update('activities', $activity, array('id' => $activity['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_activity($id){
		
		$activity['deleted'] = DELETED;
	
		$this->db->update('activities', $activity, array('id' => $id));
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
		
	}
	
}