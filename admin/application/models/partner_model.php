<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partner_Model extends CI_Model{	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_partners($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		$this->db->where('p.deleted !=', DELETED);
		return $this->db->count_all_results('partners p');
	}
	
	function search_partners($search_criteria = ''
		, $num, $offset
		, $order_field = 'p.name', $order_type = 'asc')
	{	
		$this->db->select('p.*, u.username as last_modified_by');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->join('users u', 'p.user_modified_id = u.id', 'left outer');
		
		$this->db->where_not_in('p.deleted', DELETED);
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('partners p', $num, $offset);
		
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
					$this->db->like('p.name', $value, 'both');
					break;
				case 'payment_type' :
					$this->db->where('p.payment_type', $value);
					break;
				case 'service_type' :
					$this->db->where('p.service_type &'.pow(2, $value).' > 0');
					break;
			}
		}
	}
	
	/**
	 * create_partner
	 *
	 * @return bool
	 **/
	public function create_partner($partner)
	{	
		// Additional data
		$additional_data = array(
			'user_created_id'	=> get_user_id(),
			'user_modified_id'	=> get_user_id(),
			'date_created'		=> date(DB_DATE_TIME_FORMAT),
			'date_modified'		=> date(DB_DATE_TIME_FORMAT),
			'status'			=> 1,
			'joining_date'		=> date(DB_DATE_FORMAT, strtotime($partner['joining_date'])),		
		);
		
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$partner_data = array_merge($partner, $additional_data);
		
		$this->db->insert('partners', $partner_data);
		
		$id = $this->db->insert_id();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_partner($id) {
		
		if(empty($id)) {
			return FALSE;
		}
		
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where_not_in('deleted', DELETED);
		
		$query = $this->db->get('partners');
		
		$result = $query->result_array();
		
		if (count($result) === 1)
		{
			return $result[0];
		}
		
		return FALSE;
	}
	
	function update_partner($partner) {
		
		$partner['user_modified_id'] 	= get_user_id();
		$partner['date_modified'] 		= date(DB_DATE_TIME_FORMAT);
		
		if(isset($partner['joining_date'])) {
			$partner['joining_date']		= date(DB_DATE_FORMAT, strtotime($partner['joining_date']));
		}
		
		$this->db->update('partners', $partner, array('id' => $partner['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_all_partners($service_type = null) {
		
		$this->db->select('id, name');
		$this->db->where_not_in('deleted', DELETED);
		
		if (! empty($service_type))
        {
            if (is_array($service_type))
            {
                $sql = '';
                foreach ($service_type as $t)
                {
                    $sql .= 'service_type &' . pow(2, $t) . ' > 0 OR ';
                }
                $sql = '(' . rtrim(trim($sql), 'OR') . ')';
                $this->db->where($sql);
            }
            else
            {
                $this->db->where('service_type &' . pow(2, $service_type) . ' > 0');
            }
        }
		
		$this->db->order_by('name', 'asc');
		
		$query = $this->db->get('partners');
		
		return $query->result_array();
	}
	
	function delete_partner($id) {
	
		$partner['deleted'] = DELETED;
	
		$this->db->update('partners', $partner, array('id' => $id));
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function is_unique_field_value($str, $id, $column_name){
			
		$this->db->where('deleted !=', DELETED);
			
		$this->db->where($column_name, $str);
			
		if(!empty($id)){
	
			$this->db->where('id !=', $id);
	
		}
			
		$cnt = $this->db->count_all_results('partners');
			
		return $cnt > 0;
	}
}

?>