<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotel_Model extends CI_Model{	

	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
		$this->load->model('Destination_Model');
	}
	
	function get_numb_hotels($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		$this->db->where('h.deleted !=', DELETED);
		return $this->db->count_all_results('hotels h');
	}
	
	function search_hotels($search_criteria = ''
		, $num, $offset
		, $order_field = 'position', $order_type = 'asc')
	{	
		
		$ext_sql = '';
		if (!empty($search_criteria)) {
			
			foreach ($search_criteria as $key => $value) {
					
				$value =  $this->db->escape_str($value);
				
				$search_term = '';
				$terms = explode(' ', $value);
				
				foreach ($terms as $term) {
				    $search_term .= ' "'. $term .'" ';
				}
				$search_term = trim($search_term);
					
				if ($key == 'search_text') {
					$ext_sql = ", MATCH(h.name) AGAINST ('".$search_term."*' IN BOOLEAN MODE) as score";
					break;
				}
			}
			
		}
		
		$partner_info = ', p.phone, p.fax, p.email, p.sale_contact_name, p.sale_contact_phone, p.sale_contact_email, p.reservation_contact_name, p.reservation_contact_phone, p.reservation_contact_email';
		
		$this->db->select('h.*, p.name as partner_name'.$partner_info.', u.username as last_modified_by, d.name as destination_name'.$ext_sql);
		
		$this->db->join('partners p', 'h.partner_id = p.id', 'left outer');
		$this->db->join('users u', 'h.user_modified_id = u.id', 'left outer');
		$this->db->join('destinations d', 'h.destination_id = d.id', 'left outer');
		
		$this->_set_search_criteria($search_criteria);
		
		if (!empty($ext_sql)) {
			$this->db->order_by('score', 'desc');
			$this->db->order_by('name', 'asc');
		}
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('hotels h', $num, $offset);

		return $query->result_array();
	}

	function _set_search_criteria($search_criteria = '', $mask_name = 'h.')
	{
		$this->db->where($mask_name . 'deleted !=', DELETED);
		
		if ($search_criteria == '')	{			
			return;
		}
		
		foreach ($search_criteria as $key => $value) {
			
			$value =  $this->db->escape_str($value);
			
			switch ($key) {
				case 'search_text' :
					
					$exp_term = explode(' ', $value);
					
					$search_term = '';
					foreach ($exp_term as $exp) {
						$search_term .= $exp.'* ';
					}
					$search_term = trim($search_term);
					
					$match_sql = "MATCH(".$mask_name."name) AGAINST ('".$search_term."' IN BOOLEAN MODE)";
					
					$this->db->where($match_sql);
					
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
				case 'destination_id' :
					$this->db->join('destination_hotels dh', $mask_name.'id = dh.hotel_id', 'left outer');
					$this->db->where('dh.destination_id', $value);
					break;
			}
		}
	}
	
	/**
	 * create_hotel
	 *
	 * @return bool
	 **/
	public function create_hotel($hotel)
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
		
		$hotel['url_title'] = url_title(convert_unicode($hotel['name']), '-', true);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$hotel = array_merge($hotel, $additional_data);
		
		$hotel['keywords'] = get_keywords($hotel['name'], HOTEL);
	
		// create new hotel
		$this->db->insert('hotels', $hotel);
		
		$hotel['id'] = $this->db->insert_id();
		
		$parent_des = $this->Destination_Model->get_all_parent_destinations($hotel['destination_id']);
		if(empty($parent_des)) $parent_des = array();
		$parent_des[] = $hotel['destination_id'];
		
		// save hotel destinations
		$this->Destination_Model->update_hotel_destination($hotel, $parent_des);
		
		// update number of hotels
		$this->Destination_Model->update_number_of_hotels($parent_des);
		
		$this->db->trans_complete();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_hotel($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('hotels');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{
			return $result[0];
		}
	
		return FALSE;
	}
	
	function update_hotel($hotel, $old_destination_id = null) {
		
		$this->db->trans_start();
	
		$hotel['user_modified_id'] 	= get_user_id();
		$hotel['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
		
		// update hotel url title
		if( isset($hotel['name']) && !empty($hotel['name']) ) {
			$hotel['url_title'] = url_title(convert_unicode($hotel['name']), '-', true);
			
			$url_title_history = $this->is_change_hotel_name($hotel['id'], $hotel['name']);
			
			if($url_title_history !== false) {
				$hotel['url_title_history'] = $url_title_history;
			}
		}
	
		// update hotel
		$this->db->update('hotels', $hotel, array('id' => $hotel['id']));
		
		if( isset($hotel['destination_id']) && !empty($hotel['destination_id']) ) {
			
			$parent_des = $this->Destination_Model->get_all_parent_destinations($hotel['destination_id']);
			if(empty($parent_des)) $parent_des = array();
			$parent_des[] = $hotel['destination_id'];
			
			// save hotel destinations
			$this->Destination_Model->update_hotel_destination($hotel, $parent_des);
			
			// update number of hotels
			$this->Destination_Model->update_number_of_hotels($parent_des);
			
			// update old hotel destination
			if( !empty($old_destination_id) ) {
				
				$old_parent_des = $this->Destination_Model->get_all_parent_destinations($old_destination_id);
				if(empty($old_parent_des)) $old_parent_des = array();
				$old_parent_des[] = $old_destination_id;
				
				$this->Destination_Model->update_number_of_hotels($old_parent_des);
			}
		}
		
		// update hotel facilities
		if( isset($hotel['facilities']) ) {
			$this->update_hotel_facilities($hotel);
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_hotel($id) {
		
		$this->db->trans_start();
		
		$hotel = $this->get_hotel($id);
	
		if(!empty($hotel)) {
			$hotel['deleted'] = DELETED;
			
			$this->db->update('hotels', $hotel, array('id' => $id));
			
			// update number of hotels
			$this->Destination_Model->update_number_of_hotels($hotel['destination_id']);
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
	
		$query = $this->db->get('hotels');
	
		$results = $query->result_array();
		if (!empty($results)) {
	
			return $results[0]['position'];
		}
	
		return 0;
	}
	
	function get_hotel_destinations() {
		
		$this->db->select('id, name');
		
		//$this->db->join('destinations d', 'd.id = h.destination_id', 'left outer');
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('number_of_hotels >', 0);
		
		$this->db->order_by('name', 'asc');

		$query = $this->db->get('destinations');
		
		return $query->result_array();
	}
	
	function is_unique_hotel_name($name, $id){
			
		$this->db->where('deleted !=', DELETED);
			
		$this->db->where('name',$name);
			
		if(!empty($id)){
	
			$this->db->where('id !=', $id);
	
		}
			
		$cnt = $this->db->count_all_results('hotels');
			
		return $cnt > 0;
	}
	
	/*
	 * is_change_hotel_name
	*
	* check hotel name is changed or not
	*/
	function is_change_hotel_name($id, $name) {
		$hotel = $this->get_hotel($id);
	
		if(!empty($hotel)) {
			if(trim($hotel['name']) != trim($name)) {
					
				$comma = ',';
				if(empty($hotel['url_title_history'])) {
					$comma = '';
				}
					
				$url_title_history = $hotel['url_title_history'] . $comma . url_title($hotel['name']);
					
				return $url_title_history;
			}
		}
	
		return false;
	}
	
	function update_hotel_facilities($hotel) {
		$this->db->trans_start();
		
		// create or update to hotel_destinations table
		$this->db->where('hotel_id', $hotel['id']);
		$this->db->delete('hotel_facilities');
		
		$facilities = explode('-', $hotel['facilities']);
	
		
		foreach ($facilities as $fac) {
			
			if(!empty($fac)) {
				$facility_data = array(
						'hotel_id' 		=> $hotel['id'],
						'facility_id' 	=> $fac,
				);
				
				$this->db->insert('hotel_facilities', $facility_data);
			}
			
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_all_destinations() {
		
		$this->db->select('d.id, d.name, d.number_of_hotels');
		
		$this->db->join('destination_places dp', 'dp.destination_id = d.id', 'left outer');
		
		$this->db->where('dp.parent_id', DESTINATION_VIETNAM);
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->where('d.type', DESTINATION_TYPE_CITY);
		
		//$this->db->order_by('d.position', 'asc');
		$this->db->order_by('d.url_title', 'asc');
	
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
	
		$this->db->select('d.id, d.name, d.type, d.url_title, d.number_of_hotels');
		
		$this->db->join('destinations as d', 'd.id = dp.destination_id', 'left outer');
		
		$this->db->where_in('d.type', $types);
		
		$this->db->where_in('dp.parent_id', $des_id);
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->order_by('d.type','asc');
		
		//$this->db->order_by('d.position','asc');
		$this->db->order_by('d.url_title','asc');
		
		$query = $this->db->get('destination_places dp');
		
		$results = $query->result_array();
	
		return $results;
	}
	
	function suggest_hotels($term)
	{
		$term = $this->db->escape_str($term);
	
		$match_sql = "MATCH(name) AGAINST ('".$term."*' IN BOOLEAN MODE)";
	
		$this->db->select('id, name, url_title, '. $match_sql . ' AS score');
	
		$this->db->where('deleted !=', DELETED);
	
		$this->db->where($match_sql);
	
		$this->db->order_by('score', 'desc');
	
		$this->db->order_by('name', 'asc');
	
		$this->db->limit(5);
	
		$query = $this->db->get('hotels');
	
		$results = $query->result_array();
	
		return $results;
	}
	
	function get_partner_of_hotel($id) {
		
		$this->db->select('p.name as partner_name, p.phone, p.fax, p.email, p.sale_contact_name, p.sale_contact_phone, p.sale_contact_email, p.reservation_contact_name, p.reservation_contact_phone, p.reservation_contact_email');
		
		$this->db->join('partners p', 'h.partner_id = p.id', 'left outer');
		
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->where('h.partner_id', $id);
		
		$query = $this->db->get('hotels h');
		
		$results = $query->result_array();
		
		if(!empty($results)) {
			return $results[0];
		}
		
		return null;
	}
}

?>