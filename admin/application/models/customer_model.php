<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Model extends CI_Model{
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_customers($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		$this->db->where('c.deleted !=', DELETED);
		return $this->db->count_all_results('customers c');
	}
	
	function search_customers($search_criteria = ''
		, $num, $offset
		, $order_field = 'c.id', $order_type = 'asc')
	{		
		$this->db->select('c.*, u.username as last_modified_by, d.name as destination_name');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->join('users u', 'c.user_modified_id = u.id', 'left outer');
		$this->db->join('destinations d', 'c.destination_id = d.id', 'left outer');
		
		$this->db->where('c.deleted !=', DELETED);
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('customers c', $num, $offset);
		
		return $query->result_array();
	}

	private function _set_search_criteria($search_criteria = '')
	{
		if ($search_criteria == '')	{			
			return;
		}
		foreach ($search_criteria as $key => $value) {
			
			$value =  $this->db->escape_like_str($value);
			
			switch ($key) {
				case 'search_text' :
					$this->db->like('c.full_name', $value, 'both');
					$this->db->or_like('c.email', $value, 'both');
					$this->db->or_like('c.phone', $value, 'both');
					break;
				case 'destiantion_id' :
					$this->db->where('c.destiantion_id', $value);
					break;
				case 'budget' :
					$this->db->where('c.budget', $value);
					break;
				case 'travel_type' :
					$this->db->where('c.travel_type', $value);
					break;
			}
		}
	}
	
	/**
	 * create_customer
	 *
	 * @return bool
	 **/
	public function create_customer($customer)
	{
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'birthday'			=> !empty($customer['birthday']) ? date(DB_DATE_FORMAT, strtotime($customer['birthday'])) : NULL,
		);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$data = array_merge($customer, $additional_data);
	
		$this->db->insert('customers', $data);
	
		$id = $this->db->insert_id();
	
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_customer($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('customers');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{
			return $result[0];
		}
	
		return FALSE;
	}
	
	function update_customer($customer) {
	
		$customer['user_modified_id'] 	= get_user_id();
		$customer['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
		$customer['birthday'] 	=  !empty($customer['birthday']) ? date(DB_DATE_FORMAT, strtotime($customer['birthday'])) : NULL;
	
		$this->db->update('customers', $customer, array('id' => $customer['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_customer($id) {
	
		$customer['deleted'] = DELETED;
	
		$this->db->update('customers', $customer, array('id' => $id));
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_customer_destinations() {
	
		$this->db->select('d.id, d.name');
	
		$this->db->join('destinations d', 'd.id = c.destination_id', 'left outer');
	
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->order_by('d.position');
	
		$query = $this->db->get('customers c');
	
		return $query->result_array();
	}
	
	function is_unique_field_value($str, $id, $column_name){
			
		$this->db->where('deleted !=', DELETED);
			
		$this->db->where($column_name, $str);
			
		if(!empty($id)){
	
			$this->db->where('id !=', $id);
	
		}
			
		$cnt = $this->db->count_all_results('customers');
			
		return $cnt > 0;
	}
}

?>