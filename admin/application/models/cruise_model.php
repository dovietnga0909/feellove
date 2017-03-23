<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cruise_Model extends CI_Model{	

	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_cruises($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		$this->db->where('c.deleted !=', DELETED);
		return $this->db->count_all_results('cruises c');
	}
	
	function search_cruises($search_criteria = ''
		, $num, $offset
		, $order_field = 'position', $order_type = 'asc')
	{	
	    $partner_info = ', p.phone, p.fax, p.email, p.sale_contact_name, p.sale_contact_phone, p.sale_contact_email, p.reservation_contact_name, p.reservation_contact_phone, p.reservation_contact_email';
	    
		$this->db->select('c.*, p.name as partner_name'. $partner_info .', u.username as last_modified_by, d.name as destination_name');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->join('partners p', 'c.partner_id = p.id', 'left outer');
		$this->db->join('users u', 'c.user_modified_id = u.id', 'left outer');
		$this->db->join('destinations d', 'c.destination_id = d.id', 'left outer');
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('cruises c', $num, $offset);

		$results =  $query->result_array();
		
		foreach ($results as $k => $cruise) {
			$cruise['type_name'] = $this->_get_cruise_type($cruise['cruise_type']);
			$results[$k] = $cruise;
		}
		
		return $results;
	}
	
	function _get_cruise_type($type_id) {
		$facility_types = $this->config->item('cruise_type');
	
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

	function _set_search_criteria($search_criteria = '', $mask_name = 'c.')
	{
		$this->db->where($mask_name . 'deleted !=', DELETED);
		
		if ($search_criteria == '')	{			
			return;
		}
		
		foreach ($search_criteria as $key => $value) {
			
			$value =  $this->db->escape_str($value);
			
			switch ($key) {
				case 'search_text' :
					$this->db->where("MATCH(". $mask_name ."name) AGAINST ('".$value."*' IN BOOLEAN MODE)");
					break;
				case 'star' :
					$this->db->where($mask_name. 'star', $value);
					break;
				case 'partner_id' :
					$this->db->where($mask_name. 'partner_id', $value);
					break;
				case 'status' :
					$this->db->where($mask_name. 'status', $value);
					break;
				case 'cruise_type' :
					$this->db->where($mask_name.'cruise_type &'.pow(2, $value).' > 0');
					break;
			}
		}
	}
	
	/**
	 * create_cruise
	 *
	 * @return bool
	 **/
	public function create_cruise($cruise)
	{
		$this->db->trans_start();
		
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
		
		$cruise['url_title'] = url_title(convert_unicode($cruise['name']), '-', true);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$cruise = array_merge($cruise, $additional_data);
	
		// create new cruise
		$this->db->insert('cruises', $cruise);
		
		$cruise['id'] = $this->db->insert_id();
		
		$this->db->trans_complete();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_cruise($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('cruises');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{
			return $result[0];
		}
	
		return FALSE;
	}
	
	function update_cruise($cruise) {
		
		$this->db->trans_start();
	
		$cruise['user_modified_id'] 	= get_user_id();
		$cruise['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
		
		// update cruise url title
		if( isset($cruise['name']) && !empty($cruise['name']) ) {
			$cruise['url_title'] = url_title(convert_unicode($cruise['name']), '-', true);
			
			$url_title_history = $this->is_change_cruise_name($cruise['id'], $cruise['name']);
			
			if($url_title_history !== false) {
				$cruise['url_title_history'] = $url_title_history;
			}
		}
	
		// update cruise
		$this->db->update('cruises', $cruise, array('id' => $cruise['id']));
		
		// update cruise facilities
		if( isset($cruise['facilities']) ) {
			$this->update_cruise_facilities($cruise);
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_cruise($id) {
		
		$this->db->trans_start();
		
		$cruise = $this->get_cruise($id);
	
		if(!empty($cruise)) {
			$cruise['deleted'] = DELETED;
			
			$this->db->update('cruises', $cruise, array('id' => $id));
		}
		
		$this->db->trans_complete();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_max_position($type = 0) {
		
		if($type == 0) {
			$this->db->select_max('position');
		} else {
			$this->db->select_min('position');
		}
	
		$query = $this->db->get('cruises');
	
		$results = $query->result_array();
		if (!empty($results)) {
	
			return $results[0]['position'];
		}
	
		return 0;
	}
	
	function get_cruise_destinations() {
		
		$this->db->select('id, name');
		
		//$this->db->join('destinations d', 'd.id = c.destination_id', 'left outer');
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('number_of_cruises >', 0);
		
		$this->db->order_by('name', 'asc');

		$query = $this->db->get('destinations');
		
		return $query->result_array();
	}
	
	function is_unique_cruise_name($name, $id){
			
		$this->db->where('deleted !=', DELETED);
			
		$this->db->where('name',$name);
			
		if(!empty($id)){
	
			$this->db->where('id !=', $id);
	
		}
			
		$cnt = $this->db->count_all_results('cruises');
			
		return $cnt > 0;
	}
	
	/*
	 * is_change_cruise_name
	*
	* check cruise name is changed or not
	*/
	function is_change_cruise_name($id, $name) {
		$cruise = $this->get_cruise($id);
	
		if(!empty($cruise)) {
			if(trim($cruise['name']) != trim($name)) {
					
				$comma = ',';
				if(empty($cruise['url_title_history'])) {
					$comma = '';
				}
					
				$url_title_history = $cruise['url_title_history'] . $comma . url_title($cruise['name']);
					
				return $url_title_history;
			}
		}
	
		return false;
	}
	
	function update_cruise_facilities($cruise) {
		$this->db->trans_start();
		
		// create or update to cruise_destinations table
		$this->db->where('cruise_id', $cruise['id']);
		$this->db->delete('cruise_facilities');
		
		$facilities = explode('-', $cruise['facilities']);
	
		
		foreach ($facilities as $fac) {
			
			if(!empty($fac)) {
				$facility_data = array(
						'cruise_id' 		=> $cruise['id'],
						'facility_id' 	=> $fac,
				);
				
				$this->db->insert('cruise_facilities', $facility_data);
			}
			
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_all_destinations() {
		
		$this->db->select('d.id, d.name');
		
		$this->db->join('destination_places dp', 'dp.destination_id = d.id', 'left outer');
		
		$this->db->where('dp.parent_id', DESTINATION_VIETNAM);
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->where('d.type', DESTINATION_TYPE_CITY);
		
		$this->db->order_by('d.position', 'asc');
	
		$query = $this->db->get('destinations d');
		
		$results = $query->result_array();
		
		foreach ($results as $k => $des) {
			$des['children'] = $this->get_all_sub_destinations($des['id']);
			$results[$k] = $des;
		}
	
		return $results;
	}
	
	/*
	 * Get all children destinations of a city such as district, area, ... etc
	*/
	function get_all_sub_destinations($des_id) {
		
		$types = array(
				DESTINATION_TYPE_DISTRICT,
				DESTINATION_TYPE_AREA,
		);
	
		$this->db->select('d.id, d.name, d.type, d.url_title');
		
		$this->db->join('destinations as d', 'd.id = dp.destination_id', 'left outer');
		
		$this->db->where_in('d.type', $types);
		
		$this->db->where_in('dp.parent_id', $des_id);
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->order_by('d.type','asc');
		
		$this->db->order_by('d.position','asc');
		
		$query = $this->db->get('destination_places dp');
		
		$results = $query->result_array();
	
		return $results;
	}
}

?>