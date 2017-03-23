<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Destination_Model extends CI_Model{
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
		$this->load->helper('destination');
	}
	
	function get_numb_destinations($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		$this->db->where('d.deleted !=', DELETED);
		return $this->db->count_all_results('destinations d');
	}
	
	function search_destinations($search_criteria = ''
		, $num, $offset
		, $order_field = 'd.position', $order_type = 'asc')
	{		
		$this->db->select('d.*, p.name as parent_name, u.username as last_modified_by');
		
		$this->db->join('destinations p', 'd.parent_id = p.id', 'left outer');
		$this->db->join('users u', 'd.user_modified_id = u.id', 'left outer');
		
		$this->_set_search_criteria($search_criteria);
		
		$pos_name = '';
		
		if (!empty($search_criteria)) {
			foreach ($search_criteria as $key => $value) {
				if($key == 'is_flight_destination') {
					$pos_name = 'position_flight';
				}
			}
		}
		
		if (!empty($pos_name)) {
			$this->db->order_by('position_flight', 'asc');
		} else {
			$this->db->order_by($order_field, $order_type);
		}
		
		$query = $this->db->get('destinations d', $num, $offset);
		
		$results = $query->result_array();
		
		foreach ($results as $k => $des) {
			$des['type_name'] = get_type_name($des['type']);
			$results[$k] = $des;
		}
		
		return $results;
	}

	function _set_search_criteria($search_criteria = '', $mask_name = 'd.')
	{
		$this->db->where($mask_name . 'deleted !=', DELETED);
		
		if ($search_criteria == '')	{			
			return;
		}
		
		foreach ($search_criteria as $key => $value) {
			
			$value =  $this->db->escape_str($value);
			
			switch ($key) {
				case 'search_text' :
				    $value = search_term_pre_process($value);
					$this->db->like($mask_name.'keywords', $value, 'both');
					break;
				case 'parent_id' :
					$this->db->join('destination_places dp', 'dp.destination_id = d.id', 'left outer');
					$this->db->where('dp.parent_id', $value);
					break;
				case 'type' :
					$this->db->where($mask_name. 'type', $value);
					break;
				case 'is_top_hotel' :
					$this->db->where($mask_name. 'is_top_hotel', $value);
					break;
				case 'is_flight_destination' :
					$this->db->where($mask_name. 'is_flight_destination', $value);
					break;
				case 'is_flight_group' :
					$this->db->where($mask_name. 'is_flight_group', $value);
					break;
				case 'is_tour_departure' :
					$this->db->where($mask_name. 'is_tour_departure_destination', $value);
					break;
				case 'is_tour_des_group' :
					$this->db->where($mask_name. 'is_tour_destination_group', $value);
					break;
				case 'is_tour_highlight' :
					$this->db->where($mask_name. 'is_tour_highlight_destination', $value);
					break;
				case 'is_include_all_children' :
					$this->db->where($mask_name. 'is_tour_includes_all_children_destination', $value);
					break;
					
			}
		}
	}
	
	/**
	 * create_destination
	 *
	 * @return bool
	 **/
	public function create_destination($destination)
	{
		$position = $this->get_max_position() + 1;
		$position_flight = $this->get_max_position('position_flight') + 1;
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'position'			=> $position,
				'position_flight'	=> $position_flight,
		);
		
		$destination['url_title'] = url_title(convert_unicode($destination['name']), '-', true);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$destination_data = array_merge($destination, $additional_data);
		
		$destination_data['keywords'] = get_keywords($destination['name'], 2);
	
		$this->db->insert('destinations', $destination_data);
		
		$destination['id'] = $this->db->insert_id();
		
		// save place destinations
		$this->update_place_destination($destination);
		
		$this->update_is_outbound_of_des($destination['id']);
	
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_destination($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('destinations');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{
			return $result[0];
		}
	
		return FALSE;
	}
	
	function update_destination($destination, $old_parent_id = null) {
		
		//$old_parent_des = $this->Destination_Model->get_all_parent_destinations($old_parent_id);
		//print_r($old_parent_des);exit();
		
		$this->db->trans_start();
	
		$destination['user_modified_id'] 	= get_user_id();
		$destination['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
		
		// update destination url title
		if( isset($destination['name']) && !empty($destination['name']) ) {
			$destination['url_title'] = url_title(convert_unicode($destination['name']), '-', true);
		}
	
		$this->db->update('destinations', $destination, array('id' => $destination['id']));
		
		// save place destinations
		$this->update_place_destination($destination);
		
		// change the parent destination => re-update the tour-destination & hotel destination
		if(isset($destination['parent_id']) && $destination['parent_id'] != $old_parent_id) {
			
			// update place destinations
			$child_des = $this->get_child_destinations($destination['id']);
			foreach ($child_des as $ch_des) {
				$this->update_place_destination($ch_des);
			}
			
			// update hotel destinations in the destination
			$this->update_destination_of_hotel($destination['id'], $old_parent_id, $destination['parent_id']);
			
			// udate tour-destination
			$this->update_destination_of_tour($destination['id'], $old_parent_id, $destination['parent_id']);
		
			
			$parent_des = $this->get_all_parent_destinations($destination['id']);
			if(empty($parent_des)) $parent_des = array();
			$parent_des[] = $destination['id'];
			
			$this->update_number_of_hotels($parent_des);
				
			// update number of tour on each destination (Khuyenpv: 10.09.2014)
			$this->update_number_of_tours($parent_des);	

			
			// update the relation data of old parent destination
			if(!empty($old_parent_id)) {
					
				// update number of hotels
				$old_parent_des = $this->get_all_parent_destinations($old_parent_id);
				if(empty($old_parent_des)) $old_parent_des = array();
				$old_parent_des[] = $old_parent_id;
					
				$this->update_number_of_hotels($old_parent_des);
			
				// update number of tour on each destination (Khuyenpv: 10.09.2014)
				$this->update_number_of_tours($old_parent_des);
			
			}
		}
		
		$this->update_is_outbound_of_des($destination['id']);
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function update_number_of_hotels($hotel_des) {
		
		if(!empty($hotel_des)) {
			foreach ($hotel_des as $des) {
				$this->db->join('hotels h', 'h.id = dh.hotel_id', 'left outer');
			
				$this->db->where('dh.destination_id', $des);
				
				$this->db->where('h.status', STATUS_ACTIVE);
				
				$this->db->where('h.deleted !=', DELETED);
			
				$number_of_hotels = $this->db->count_all_results('destination_hotels dh');
			
				$destination = array(
						'id' => $des,
						'number_of_hotels' => $number_of_hotels,
				);
			
				$this->db->update('destinations', $destination, array('id' => $destination['id']));
			}	
		}
	}
	
	/**
	 * Khuyenpv: 10.09.2014
	 * Update Number of Tours in a Destination
	 */
	function update_number_of_tours($tour_des){
		
		if(!empty($tour_des)) {
			foreach ($tour_des as $destination_id) {
				
				$this->db->distinct();
				
				$this->db->select('t.id, t.is_outbound');
				
				$this->db->from('destination_tours dt');
				
				$this->db->join('tours t', 't.id = dt.tour_id');
					
				$this->db->where('dt.destination_id', $destination_id);
				
				$this->db->where('dt.is_land_tour', STATUS_ACTIVE);
				
				$this->db->where('t.status', STATUS_ACTIVE);
				
				$this->db->where('t.deleted !=', DELETED);
				
				$query = $this->db->get();
				
				$results = $query->result_array();
				
				$nr_tour_domistic = 0;
				$nr_tour_outbound = 0;
				
				foreach ($results as $tour){
					
					if($tour['is_outbound']){
						
						$nr_tour_outbound++;
						
					} else {
						
						$nr_tour_domistic++;
						
					}
					
				}
				
				
					
				$destination = array(
					'id' => $destination_id,
					'nr_tour_domistic' => $nr_tour_domistic,
					'nr_tour_outbound' => $nr_tour_outbound
				);
					
				$this->db->update('destinations', $destination, array('id' => $destination['id']));
				
			}
		}
	}
	
	function update_hotel_destination($hotel, $hotel_des) {
	
		$this->db->trans_start();
	
		// get all parent destinations
		//$hotel_des = $this->Destination_Model->get_all_parent_destinations($hotel['destination_id']);
	
		//$hotel_des[] =  $hotel['destination_id'];
	
		// create or update to hotel_destinations table
		$this->db->where('hotel_id', $hotel['id']);
		$this->db->delete('destination_hotels');
	
		foreach ($hotel_des as $des) {
			$hotel_data = array(
					'hotel_id' 			=> $hotel['id'],
					'destination_id' 	=> $des,
			);
				
			$this->db->insert('destination_hotels', $hotel_data);
		}
	
		$this->db->trans_complete();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	/**
	 * Khuyenpv: 11.09.2014
	 * Update the hotel-destination when the parent destination is changed
	 * @param unknown $des_id
	 */
	
	function update_destination_of_hotel($des_id, $old_parent_id, $new_parent_id) {
		
		$this->db->select('hotel_id');
		
		$this->db->where('destination_id', $des_id);
		
		$query = $this->db->get('destination_hotels');
		
		$destination_hotels = $query->result_array();
		
		$hotel_ids = array();
		
		foreach($destination_hotels as $dh){
				
			$hotel_ids[] = $dh['hotel_id'];
		
		}
		
		if(!empty($hotel_ids)){
			
			if(!empty($old_parent_id)){
					
				$old_parent_des = $this->get_all_parent_destinations($old_parent_id);
				if(empty($old_parent_des)) $old_parent_des = array();
				$old_parent_des[] = $old_parent_id;
					
				// remove the old hotel-destination relation
				$this->db->where_in('hotel_id', $hotel_ids);
				$this->db->where_in('destination_id', $old_parent_des);
				$this->db->delete('destination_hotels');
					
			}
			
			if(!empty($new_parent_id)){
			
				$new_parent_des = $this->get_all_parent_destinations($new_parent_id);
				if(empty($new_parent_des)) $new_parent_des = array();
				$new_parent_des[] = $new_parent_id;
			
				foreach($hotel_ids as $hotel_id){
						
					foreach ($new_parent_des as $des_id){
			
						$hotel_des = array(
							'hotel_id' 			=> $hotel_id,
							'destination_id' 	=> $des_id,
						);
							
						$this->db->insert('destination_hotels', $hotel_des);
			
					}
						
				}
			
			}
				
			
		}
	}
	
	/**
	 * Khuyenpv: 11.09.2014
	 * Update the tour-destination when the parent destination is changed
	 * @param unknown $des_id
	 */
	function update_destination_of_tour($des_id, $old_parent_id, $new_parent_id){
		
		$this->db->select('tour_id');
		
		$this->db->where('destination_id', $des_id);
		
		$query = $this->db->get('destination_tours');
		
		$destination_tours = $query->result_array();
		
		$tour_ids = array();
		
		foreach($destination_tours as $dt){
			
			$tour_ids[] = $dt['tour_id'];
				
		}
		
		if(!empty($tour_ids)){

			if(!empty($old_parent_id)){
					
				$old_parent_des = $this->get_all_parent_destinations($old_parent_id);
				if(empty($old_parent_des)) $old_parent_des = array();
				$old_parent_des[] = $old_parent_id;
				
				// select all the des-tour not in old-parent-des
				$this->db->distinct();
				$this->db->select('destination_id');
				$this->db->where_in('tour_id', $tour_ids);
				$this->db->where_not_in('destination_id', $old_parent_des);
				$query = $this->db->get('destination_tours');
				$d_tours = $query->result_array();
				
				$des_ids = array();
				foreach ($d_tours as $dt){
					$des_ids[] = $dt['destination_id'];
				}
				
				if(!empty($des_ids)){
					$this->db->distinct();
					$this->db->select('parent_id');
					$this->db->where_in('destination_id', $des_ids);
					$query = $this->db->get('destination_places');
					$d_places = $query->result_array();
					
					
					$des_ids = array();
					foreach ($d_places as $dp){
						$des_ids[] = $dp['parent_id'];
					}
				}
				
				
					
				// remove the old tour-destination relation
				$this->db->where_in('tour_id', $tour_ids);
				$this->db->where_in('destination_id', $old_parent_des);
				
				if(!empty($des_ids)){
					
					$this->db->where_not_in('destination_id', $des_ids);
				}
				
				$this->db->delete('destination_tours');
					
			}
			
			if(!empty($new_parent_id)){
				
				$new_parent_des = $this->get_all_parent_destinations($new_parent_id);
				if(empty($new_parent_des)) $new_parent_des = array();
				$new_parent_des[] = $new_parent_id;
				
				foreach($tour_ids as $tour_id){
					
					foreach ($new_parent_des as $des_id){
						
						$tour_des = array(
							'tour_id' 			=> $tour_id,
							'destination_id' 	=> $des_id,
						);
						
						$this->db->where('tour_id', $tour_id);
						$this->db->where('destination_id', $des_id);
						$cnt = $this->db->count_all_results('destination_tours');
					
						if($cnt == 0){
							$this->db->insert('destination_tours', $tour_des);
						}
					}
					
				}
				
			}
			
			$this->load->model('Tour_Model');
			foreach ($tour_ids as $tour_id){
			
				$this->Tour_Model->update_tour_routes($tour_id);
					
			}
		}
	}
	
	
	function get_all_destinations() {
		$this->db->select('id, name');
		$this->db->where_not_in('deleted', DELETED);
		
		$this->db->order_by('name', 'asc');
		
		$query = $this->db->get('destinations');
		
		return $query->result_array();
	}
	
	function get_parent_destinations() {
		$this->db->select('p.id, p.name');
		
		$this->db->join('destinations p', 'd.parent_id = p.id', 'left outer');
		
		$this->db->where_not_in('p.deleted', DELETED);
		
		$this->db->where('d.parent_id !=', 0);
	
		$this->db->order_by('p.name', 'asc');
		
		$this->db->group_by('p.id');
	
		$query = $this->db->get('destinations d');
	
		return $query->result_array();
	}
	
	function delete_destination($id) {
	
		$destination['deleted'] = DELETED;
	
		$this->db->update('destinations', $destination, array('id' => $id));
		
		/**
		 * Khuyenpv: update on 09.10.2014 - delete all relation data
		 */
		$this->db->where('destination_id', $id);
		$this->db->delete('destination_places');
		
		$this->db->where('destination_id', $id);
		$this->db->delete('destination_hotels');
		
		$this->db->where('destination_id', $id);
		$this->db->delete('destination_tours');
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function is_unique_code($code, $id)
	{
		if ($id != NULL) { // Check unique for Update mode
			$this->db->where('id !=', $id);
		}
		$this->db->from('destinations');
		$this->db->where('destination_code', $code);
		$query = $this->db->count_all_results();
		if ($query == 0) {
			return TRUE;
		}
	
		return FALSE;
	}
	
	function is_unique_destination_name($name, $id){
		 
		$this->db->where('deleted !=', DELETED);
		 
		$this->db->where('name',$name);
		 
		if(!empty($id)){
	
			$this->db->where('id !=', $id);
	
		}
		 
		$cnt = $this->db->count_all_results('destinations');
		 
		return $cnt > 0;
	}
	
	/*
	 * type: 0 => get max
	 * type: 1 => get min
	 */
	function get_max_position($type = 0, $field = 'position') {
	
		if($type == 0) {
			$this->db->select_max($field);
		} else {
			$this->db->select_min($field);
		}
	
		$query = $this->db->get('destinations');
	
		$results = $query->result_array();
		if (!empty($results)) {
	
			return $results[0][$field];
		}
	
		return 0;
	}
	
	function initDes() {
		$this->db->select('id, name');
		
		$this->db->order_by('name', 'asc');
		
		$query = $this->db->get('destinations');
		
		$results = $query->result_array();
		
		foreach ($results as $destination) {
			$name = convert_unicode($destination['name']);
			$destination['url_title'] = url_title($name, '-', true);
			
			$this->db->update('destinations', $destination, array('id' => $destination['id']));
		}
		
		echo "Init destination completed !!! (".count($results).")";exit();
	}
	
	function update_place_destination($destination) {
	
		$this->db->trans_start();
	
		// get all parent destinations
		$place_des = $this->get_all_parent_destinations($destination['id']);
	
		// create or update to hotel_destinations table
		$this->db->where('destination_id', $destination['id']);
		$this->db->delete('destination_places');
	
		foreach ($place_des as $des) {
			$place_data = array(
					'destination_id' 	=> $destination['id'],
					'parent_id' 		=> $des,
			);
				
			$this->db->insert('destination_places', $place_data);
		}
	
		$this->db->trans_complete();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	/*
	 * Get all parents destination
	 */
	function get_all_parent_destinations($des_id) {
	
		$this->db->select('parent_id');
	
		$this->db->where('id', $des_id);
	
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('destinations');
	
		$results = $query->result_array();
	
		$parent = array();
	
		if (!empty($results) && !empty($results[0]['parent_id'])) {
			$parent[] = $results[0]['parent_id'];
				
			$parent = array_merge($this->get_all_parent_destinations($results[0]['parent_id']), $parent);
		}
	
		return $parent;
	}
	
	function get_child_destinations($des_id) {
	
		$this->db->select('d.id, d.name');
	
		$this->db->join('destination_places dp', 'dp.destination_id = d.id', 'left outer');
		$this->db->where('dp.parent_id', $des_id);
	
		$this->db->where('d.deleted !=', DELETED);
	
		$query = $this->db->get('destinations d');
	
		$results = $query->result_array();
	
		return $results;
	}
	
	function get_customer_cities(){
		
		$this->db->select('d.id, d.name');
		
		$this->db->from('destination_places dp');
		
		$this->db->join('destinations d', 'd.id = dp.destination_id');
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->where('d.type', DESTINATION_TYPE_CITY);
		
		$this->db->where('dp.parent_id', DESTINATION_VIETNAM);
		
		$this->db->order_by('d.url_title');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
	}
	
	function suggest_destinations($term)
	{
	    $term = $this->db->escape_str($term);
	
	    $term = search_term_pre_process($term);
	    
	    if (strlen($term) > 2)
        {
            $match_sql = "MATCH(d.keywords) AGAINST ('" . $term . "' IN BOOLEAN MODE)";
            
            $this->db->select('d.id, d.name, d.url_title, ' . $match_sql . ' AS score');
            
            $this->db->where('d.deleted !=', DELETED);
            
            $this->db->where($match_sql);
            
            $this->db->order_by('score', 'desc');
        }
        else
        {
            $this->db->select('d.id, d.name, d.url_title');
            
            $this->db->where('d.deleted !=', DELETED);
            
            $this->db->like('d.name', $term, 'both');
        }
	
	    $this->db->order_by('d.type', 'asc');
	
	    $this->db->order_by('d.name', 'asc');
	
	    $this->db->limit(10);
	
	    $query = $this->db->get('destinations d');
	
	    $results = $query->result_array();
	
	    return $results;
	}
	
	/** 
	 * Khuyenpv 15.09.2014
	 * Search Destination for Autocomplete in Search Tour Functions
	 */
	function search_destinations_autocomplete($str_query){
		
		$str_query = urldecode($str_query);
		
		$this->db->select('d.id, d.name, p.name as parent_name');
		
		$this->db->from('destinations d');
		
		$this->db->join('destinations p', 'p.id = d.parent_id', 'left outer');
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->where('d.type <=', DESTINATION_TYPE_ATTRACTION);
		
		$this->db->like('d.name', $str_query, 'both');
		
		$this->db->order_by('d.name', 'asc');
		
		$query = $this->db->get();
		
		return $query->result_array();
		
	}
	
	/**
	 * Khuyenpv 15.09.2014
	 * Specify a destination is [Out bound] or Not
	 * @param unknown $des_id
	 */
	function update_is_outbound_of_des($des_id){
		
		$this->db->where('destination_id', $des_id);
		
		$this->db->where('parent_id', DESTINATION_VIETNAM);
		
		$cnt = $this->db->count_all_results('destination_places');
		
		$is_outbound = $cnt > 0 || $des_id == DESTINATION_VIETNAM ? STATUS_INACTIVE : STATUS_ACTIVE;
		
		$this->db->where('id', $des_id);
		$this->db->set('is_outbound', $is_outbound);
		$this->db->update('destinations');
	}
	
	/**
	 * Khuyenpv 15.09.2014
	 * Update Is Outbound of all destinations
	 */
	function update_is_outbound_of_all_destinations(){
		
		$this->db->select('id');
		
		$this->db->where('deleted != ', DELETED);
		
		$query = $this->db->get('destinations');
		
		$destinations = $query->result_array();
		
		foreach ($destinations as $des){
			
			$this->update_is_outbound_of_des($des['id']);
		}
		
	}
	
	
	/**
	 * Khuyenpv 18.09.2014
	 * Get Tour Hightlight Destinations
	 * @param string $is_outbound
	 */
	function get_tour_destinations($is_outbound = STATUS_INACTIVE){
		 
		$this->db->select('id, name, nr_tour_domistic, nr_tour_outbound');
	
		$this->db->where('deleted !=', DELETED);
	
		$this->db->where('is_tour_destination_group', STATUS_ACTIVE);
	
		$this->db->where('is_outbound', $is_outbound);
	
		$this->db->order_by('position', 'asc');
	
		$query = $this->db->get('destinations');
	
		$des_groups = $query->result_array();
	
		foreach ($des_groups as $key=>$des){
	
			$this->db->select('d.id, d.name, d.nr_tour_domistic, d.nr_tour_outbound');
	
			$this->db->from('destination_places as dp');
	
			$this->db->join('destinations as d', 'd.id = dp.destination_id');
	
			$this->db->where('d.deleted !=', DELETED);
	
			$this->db->where('dp.parent_id', $des['id']);
	
			$this->db->where('d.is_tour_highlight_destination', STATUS_ACTIVE);
	
			$this->db->order_by('d.position', 'asc');
	
			$query = $this->db->get();
	
			$des['destinations'] = $query->result_array();
	
			$des_groups[$key] = $des;
	
		}
		 
		return $des_groups;
	}
}

?>