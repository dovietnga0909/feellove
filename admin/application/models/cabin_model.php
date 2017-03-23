<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabin_Model extends CI_Model{	

	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_cabins($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		
		return $this->db->count_all_results('cabins cb');
	}
	
	function search_cabins($search_criteria = ''
		, $num, $offset
		, $order_field = 'position', $order_type = 'asc')
	{	
		//$this->benchmark->mark('code_start');
		
		$this->db->select('cb.*, u.username as last_modified_by');
		
		$this->db->join('users u', 'cb.user_modified_id = u.id', 'left outer');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('cabins cb', $num, $offset);
		
		$cabins = $query->result_array();
		
		foreach ($cabins as $k => $cabin) {
			$cabin['picture'] = $this->_get_cabin_main_photo($cabin['id']);
			$cabins[$k] = $cabin;
		}
		
		//$this->benchmark->mark('code_end');
		
		//echo $this->benchmark->elapsed_time('code_start', 'code_end');exit();
		
		return $cabins;
	}

	public function _set_search_criteria($search_criteria = '')
	{
		$this->db->where('cb.deleted !=', DELETED);
		
		if ($search_criteria == '')	{			
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				/*case 'search_text' :
					$searchStr =  $this->db->escape_like_str($value);
					$this->db->like('cb.name', $value, 'both');
					break;*/
				case 'cruise_id' :
					$this->db->where('cb.cruise_id', $value);
					break;
			}
		}
	}
	
	/**
	 * create_cabin
	 *
	 * @return bool
	 **/
	public function create_cabin($cabin)
	{
		$position = $this->get_max_position() + 1;
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'status'			=> STATUS_ACTIVE,
				'position'			=> $position,
		);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$cabin_data = array_merge($cabin, $additional_data);
	
		$this->db->insert('cabins', $cabin_data);
	
		$id = $this->db->insert_id();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_cabin($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('cabins');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{	
			$cabin = $result[0];
			$cabin['picture'] = $this->_get_cabin_main_photo($cabin['id']);
			return $cabin;
		}
	
		return FALSE;
	}
	
	function update_cabin($cabin) {

		$cabin['user_modified_id'] 	= get_user_id();
		$cabin['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
	
		$this->db->update('cabins', $cabin, array('id' => $cabin['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_cabin($id) {
	
		$cabin['deleted'] = DELETED;
	
		$this->db->update('cabins', $cabin, array('id' => $id));
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_cabins($cruise_id, $extra_info = false)
	{
		if( !$extra_info ) {
			$this->db->select('id, name');
		} else {
			$this->db->select('id, name, number_of_cabins, max_extra_beds, max_occupancy, max_children, rack_rate, min_rate, included_vat');
		}
		
		$this->db->where('cruise_id', $cruise_id);
		
		$this->db->where('deleted !=', DELETED);
	
		$this->db->order_by('position', 'asc');
		$query = $this->db->get('cabins');
	
		return $query->result_array();
	}
	
	function _get_cabin_main_photo($cabin_id) {
		
		$photo_name = '';
		
		$this->db->select('p.name');
		
		$this->db->join('room_photos rp', 'rp.photo_id = p.id', 'left outer');
		
		$this->db->where('rp.cabin_id', $cabin_id);
		
		$this->db->where('rp.is_main_photo', 1);

		$this->db->limit(1);
		
		$query = $this->db->get('photos p');
		
		$photos = $query->result_array();
		
		if(!empty($photos)) {
			$photo_name = $photos[0]['name'];
		}
		
		return $photo_name;
	}
	
	function get_max_position($type = 0) {
	
		if($type == 0) {
			$this->db->select_max('position');
		} else {
			$this->db->select_min('position');
		}
	
		$query = $this->db->get('cabins');
	
		$results = $query->result_array();
		if (!empty($results)) {
	
			return $results[0]['position'];
		}
	
		return 0;
	}
	
	function is_unique_cabin_name($cruise_id, $cabin_name, $cabin_id) {
		
		$this->db->where('cruise_id', $cruise_id);
		
		$this->db->where('name', $cabin_name);
		
		if(!empty($cabin_id)) {
			$this->db->where('id !=', $cabin_id);
		}
		
		$this->db->limit(1);
		
		$query = $this->db->get('cabins');
		
		$results = $query->result_array();
		
		if(!empty($results)) {
			return false;
		}
		
		return true;
	}
}

?>