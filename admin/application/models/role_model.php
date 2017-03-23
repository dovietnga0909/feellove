<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_Model extends CI_Model{	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_roles($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		$this->db->where('r.deleted !=', DELETED);
		return $this->db->count_all_results('roles r');
	}
	
	function search_roles($search_criteria = ''
		, $num, $offset
		, $order_field = 'name', $order_type = 'asc')
	{	
		$this->db->select('r.*, u.username as last_modified_by');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->where('r.deleted !=', DELETED);
		
		$this->db->join('users u', 'r.user_modified_id = u.id', 'left outer');
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('roles r', $num, $offset);
		
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
					$this->db->like('r.name', $value, 'both');
					break;
			}
		}
	}
	
	/**
	 * register
	 *
	 * @return bool
	 **/
	public function create_role($role)
	{	
		// Additional data
		$additional_data = array(
			'user_created_id'	=> get_user_id(),
			'user_modified_id'	=> get_user_id(),
			'date_created'		=> date(DB_DATE_TIME_FORMAT),
			'date_modified'		=> date(DB_DATE_TIME_FORMAT),
		);
		
		//filter out any data passed that doesnt have a matching column in the roles table
		//and merge the set role data and the additional data
		$role_data = array_merge($role, $additional_data);
		
		$this->db->insert('roles', $role_data);
		
		$id = $this->db->insert_id();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_role($id) {
		
		if(empty($id)) {
			return FALSE;
		}
		
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
		
		$query = $this->db->get('roles');
		
		$result = $query->result_array();
		
		if (count($result) === 1)
		{
			return $result[0];
		}
		
		return FALSE;
	}
	
	function update_role($role) {
		
		$role['user_modified_id'] = get_user_id();
		$role['date_modified'] = date(DB_DATE_TIME_FORMAT);
		
		$this->db->update('roles', $role, array('id' => $role['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_role($id) {
	
		$role['deleted'] = DELETED;
	
		$this->db->update('roles', $role, array('id' => $id));
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_roles() {
		$this->db->where('deleted !=', DELETED);
		
		$query = $this->db->get('roles');
		
		$results = $query->result_array();
		
		return $results;
	}
}

?>