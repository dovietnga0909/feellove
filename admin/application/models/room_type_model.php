<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Room_Type_Model extends CI_Model{	

	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_room_types($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		
		return $this->db->count_all_results('room_types r');
	}
	
	function search_room_types($search_criteria = ''
		, $num, $offset
		, $order_field = 'position', $order_type = 'asc')
	{	
		//$this->benchmark->mark('code_start');
		
		$this->db->select('r.*, u.username as last_modified_by');
		
		$this->db->join('users u', 'r.user_modified_id = u.id', 'left outer');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('room_types r', $num, $offset);
		
		$room_types = $query->result_array();
		
		foreach ($room_types as $k => $room_type) {
			$room_type['picture'] = $this->_get_room_main_photo($room_type['id']);
			$room_types[$k] = $room_type;
		}
		
		//$this->benchmark->mark('code_end');
		
		//echo $this->benchmark->elapsed_time('code_start', 'code_end');exit();
		
		return $room_types;
	}

	public function _set_search_criteria($search_criteria = '')
	{
		$this->db->where('r.deleted !=', DELETED);
		
		if ($search_criteria == '')	{			
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				/*case 'search_text' :
					$searchStr =  $this->db->escape_like_str($value);
					$this->db->like('r.name', $value, 'both');
					break;*/
				case 'hotel_id' :
					$this->db->where('r.hotel_id', $value);
					break;
			}
		}
	}
	
	/**
	 * create_room_type
	 *
	 * @return bool
	 **/
	public function create_room_type($room_type)
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
		$room_type_data = array_merge($room_type, $additional_data);
	
		$this->db->insert('room_types', $room_type_data);
	
		$id = $this->db->insert_id();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_room_type($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('room_types');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{	
			$room_type = $result[0];
			$room_type['picture'] = $this->_get_room_main_photo($room_type['id']);
			return $room_type;
		}
	
		return FALSE;
	}
	
	function update_room_type($room_type) {

		$room_type['user_modified_id'] 	= get_user_id();
		$room_type['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
	
		$this->db->update('room_types', $room_type, array('id' => $room_type['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_room_type($id) {
	
		$room_type['deleted'] = DELETED;
	
		$this->db->update('room_types', $room_type, array('id' => $id));
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_room_types($hotel_id, $extra_info = false)
	{
		if( !$extra_info ) {
			$this->db->select('id, name');
		} else {
			$this->db->select('id, name, number_of_rooms, max_extra_beds, max_occupancy, max_children, rack_rate, min_rate, included_breakfast, included_vat');
		}
		
		$this->db->where('hotel_id', $hotel_id);
		
		$this->db->where('deleted !=', DELETED);
	
		$this->db->order_by('position', 'asc');
		$query = $this->db->get('room_types');
	
		return $query->result_array();
	}
	
	function _get_room_main_photo($room_id) {
		
		$photo_name = '';
		
		$this->db->select('p.name');
		
		$this->db->join('room_photos rp', 'rp.photo_id = p.id', 'left outer');
		
		$this->db->where('rp.room_id', $room_id);
		
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
	
		$query = $this->db->get('room_types');
	
		$results = $query->result_array();
		if (!empty($results)) {
	
			return $results[0]['position'];
		}
	
		return 0;
	}
	
	function is_unique_room_name($hotel_id, $room_name, $room_id) {
		
		$this->db->where('hotel_id', $hotel_id);
		
		$this->db->where('name', $room_name);
		
		$this->db->where('deleted !=', DELETED);
		
		if(!empty($room_id)) {
			$this->db->where('id !=', $room_id);
		}
		
		$this->db->limit(1);
		
		$query = $this->db->get('room_types');
		
		$results = $query->result_array();
		
		if(!empty($results)) {
			return false;
		}
		
		return true;
	}
}

?>