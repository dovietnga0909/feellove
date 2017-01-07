<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facility_Model extends CI_Model{	
		
	var $facility_config = array();
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
		$this->config->load('facility_meta');
	}
	
	function get_numb_facilities($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		$this->db->where('f.deleted !=', DELETED);
		return $this->db->count_all_results('facilities f');
	}
	
	function search_facilities($search_criteria = ''
		, $num, $offset
		, $order_field = 'position', $order_type = 'asc')
	{	
		$this->db->select('f.*, u.username as last_modified_by');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->join('users u', 'f.user_modified_id = u.id', 'left outer');
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('facilities f', $num, $offset);
		
		$results = $query->result_array();
		foreach ($results as $k => $facility) {
			$facility['type_name'] = $this->_get_facility_type($facility['type_id']);
			$facility['group_name'] = $this->_get_facility_group($facility['group_id']);
			$results[$k] = $facility;
		}
		
		return $results;
	}
	
	function _get_facility_type($type_id) {
		$facility_types = $this->config->item('facility_types');
		
		$types = '';
		
		foreach ($facility_types as $k => $v) {
			if(is_bit_value_contain($type_id, $k)){
				$types .= lang($v) . ', ';
			}
		}
		
		if( !empty($types) ) {
			$types = rtrim($types, ', ');
		}
		
		return $types;
	}
	
	function _get_facility_group($group_id) {
		$facility_groups = $this->config->item('facility_groups');
	
		foreach ($facility_groups as $k => $group) {
			if($group_id == $k) return lang($group);
		}
	
		return '';
	}

	function _set_search_criteria($search_criteria = '', $mask_name = 'f.')
	{
		$this->db->where($mask_name . 'deleted !=', DELETED);
		
		// ignore custom hotel and cruise facility
		$this->db->where($mask_name.'hotel_id', 0);
		$this->db->where($mask_name.'cruise_id', 0);
		
		if ($search_criteria == '')	{			
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				case 'search_text' :
					$searchStr =  $this->db->escape_like_str($value);
					$this->db->like($mask_name.'name', $value, 'both');
					break;
				case 'type_id' :
					$this->db->where($mask_name.'type_id &'.pow(2, $value).' > 0');
					break;
				case 'group_id' :
					$this->db->where($mask_name.'group_id', $value);
					break;
				case 'hotel_id' :
					$this->db->where($mask_name.'hotel_id', $value);
					break;
				case 'is_important' :
					$this->db->where($mask_name.'is_important', $value);
					break;
			}
		}
	}
	
	/**
	 * create_facility
	 *
	 * @return bool
	 **/
	public function create_facility($facility)
	{
		$position = $this->get_max_position() + 1;
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'status'			=> 1,
				'position'			=> $position,
		);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$facility_data = array_merge($facility, $additional_data);
	
		$this->db->insert('facilities', $facility_data);
	
		$id = $this->db->insert_id();
	
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_facility($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('facilities');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{
			return $result[0];
		}
	
		return FALSE;
	}
	
	function update_facility($facility) {
	
		$facility['user_modified_id'] 	= get_user_id();
		$facility['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
	
		$this->db->update('facilities', $facility, array('id' => $facility['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_facility($id) {
	
		$this->db->trans_start();
		
		// update facilities table
		$facility['deleted'] = DELETED;
	
		$this->db->update('facilities', $facility, array('id' => $id));
		
		// update hotel_facilities table
		$this->db->where('facility_id', $id);
		$this->db->delete('hotel_facilities');
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_facilities_by_type($hotel_id, $group_id = null, $type = FACILITY_HOTEL) {
		
		$this->db->select('id, name, hotel_id, cruise_id');
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('status', STATUS_ACTIVE);
		
		$this->db->where('type_id &'.pow(2, $type).' > 0');
		
		if ( !empty($hotel_id) ) {
			if( ($type = FACILITY_HOTEL || $type = FACILITY_ROOM_TYPE)) {
				$this->db->where_in('hotel_id', array(0, $hotel_id));
			}
			
			if( ($type = FACILITY_CRUISE || $type = FACILITY_CABIN)) {
				$this->db->where_in('cruise_id', array(0, $hotel_id));
			}
		}
		
		if(!empty($group_id)) {
			$this->db->where('group_id', $group_id);
		}
		
		$this->db->order_by('name', 'asc');
		
		$query = $this->db->get('facilities');
		
		return $query->result_array();
	}
	
	function is_unique_field_value($str, $id, $column_name){
			
		$this->db->where('deleted !=', DELETED);
			
		$this->db->where($column_name, $str);
			
		if(!empty($id)){
	
			$this->db->where('id !=', $id);
	
		}
			
		$cnt = $this->db->count_all_results('facilities');
			
		return $cnt > 0;
	}
	
	function count_facilities($hotel_id, $facility_groups, $type = HOTEL) {
		
		$facility_count = array();
		
		// count all
		$facility_count[] = $this->_get_number_of_facilities($hotel_id, null, $type);
		
		// count by group
		foreach ($facility_groups as $key => $group) {
			$facility_count[] = $this->_get_number_of_facilities($hotel_id, $key, $type);
		}
	
		return $facility_count;
	}
	
	function _get_number_of_facilities($hotel_id, $group_id = null, $type = HOTEL) {
		
		$this->db->where('deleted !=', DELETED);
		
		if(!empty($hotel_id)) {
			$this->db->where_in('hotel_id', array(0, $hotel_id));
		}
		
		if($type == HOTEL) {
			$this->db->where('type_id &'.pow(2, 1).' > 0');
		} elseif($type == CRUISE) {
			$this->db->where('type_id &'.pow(2, 3).' > 0');
		} else {
			$this->db->where('type_id &'.pow(2, 2).' > 0');
		} 
		
		if(!empty($group_id)) {
			$this->db->where('group_id', $group_id);
		}
	
		return $this->db->count_all_results('facilities');
	}
	
	function initFacilities() {
		
		$this->db->select('id, name, hotel_id');
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->order_by('id', 'asc');
		
		$query = $this->db->get('facilities');
		
		$results = $query->result_array();
		
		$cnt = 1;
		foreach ($results as $fac) {
			$facility = array(
					'id' 		=> $fac['id'],
					'position' 	=> $cnt
			);
			$this->db->update('facilities', $facility, array('id' => $facility['id']));
			$cnt++;
		}
		echo 'Initialize Facilities completed!';exit();
	}
	
	function get_max_position($type = 0) {
	
		if($type == 0) {
			$this->db->select_max('position');
		} else {
			$this->db->select_min('position');
		}
	
		$query = $this->db->get('facilities');
	
		$results = $query->result_array();
		if (!empty($results)) {
	
			return $results[0]['position'];
		}
	
		return 0;
	}
}

?>