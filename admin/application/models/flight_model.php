<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flight_Model extends CI_Model{	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
		$this->config->load('flight_meta');
	}
	
	/*
	 * Get all airline brands
	*/
	function get_airlines(){
		$this->db->where('deleted !=', DELETED);
		$query = $this->db->get('airlines');
		return $query->result_array();
	}
	
	function get_numb_flights($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		return $this->db->count_all_results('flight_routes f');
	}
	
	function search_flights($search_criteria = ''
		, $num, $offset
		, $order_field = 'position', $order_type = 'asc')
	{	
		$this->db->select('f.*, d.name as from_des, d2.name as to_des, u.username as last_modified_by');
		
		$this->db->where('d.is_flight_destination', 1);
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->join('users u', 'f.user_modified_id = u.id', 'left outer');
		
		$this->db->join('destinations d', 'd.id = f.from_destination_id');
		
		$this->db->join('destinations d2', 'd2.id = f.to_destination_id');
		
		$this->db->order_by($order_field, $order_type);
		
		$query = $this->db->get('flight_routes f', $num, $offset);
		
		$results = $query->result_array();
		
		return $results;
	}
	
	function _set_search_criteria($search_criteria = '', $mask_name = 'f.')
	{
		$this->db->where($mask_name . 'deleted !=', DELETED);
		
		if ($search_criteria == '')	{
			return;
		}
		foreach ($search_criteria as $key => $value) {
			if($value == '') continue;
			
			switch ($key) {
				case 'from_destination_id' :
					$this->db->where($mask_name. 'from_destination_id', $value);
					break;
				case 'to_destination_id' :
					$this->db->where($mask_name. 'to_destination_id', $value);
					break;
				case 'is_show_vietnam_flight_page' :
					$this->db->where($mask_name. 'is_show_vietnam_flight_page', $value);
					break;
				case 'is_show_flight_destination_page' :
					$this->db->where($mask_name. 'is_show_flight_destination_page', $value);
					break;
				case 'status' :
					$this->db->where($mask_name. 'status', $value);
					break;
			}
		}
	}
	
	function get_flight($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->select('f.*, d.name as from_des, d2.name as to_des');
	
		$this->db->where('d.is_flight_destination', 1);
	
		$this->db->where('f.id', $id);
	
		$this->db->join('destinations d', 'd.id = f.from_destination_id');
	
		$this->db->join('destinations d2', 'd2.id = f.to_destination_id');
	
		$query = $this->db->get('flight_routes f');
	
		$results = $query->result_array();
	
		if(!empty($results)) {
			$route = $results[0];
			$this->db->where('flight_route_id', $route['id']);
			$query = $this->db->get('flight_basic_prices');
			$prices = $query->result_array();
			$route['basic_prices'] = $prices;
				
			return $route;
		}
	
		return FALSE;
	}
	
	function create_flight_basic_price($basic_prices, $route_id) {
		
		if(!empty($basic_prices)) {
			foreach ($basic_prices as $price) {
				$data = array(
						'airline_id'		=> $price['airline_id'],
						'price'				=> $price['price'],
						'flight_route_id'	=> $route_id,
				);
				
				$this->db->insert('flight_basic_prices', $data);
			}
		}
	}
	
	/**
	 * create_flight
	 *
	 * @return bool
	 **/
	public function create_flight($flight_route, $basic_prices = null)
	{
		$this->db->trans_start();
		
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'status'			=> 1,
				'position'			=> $this->_get_max_order() + 1,
		);
		
		$flight_data = array_merge($flight_route, $additional_data);
		
		$this->db->insert('flight_routes', $flight_data);
		
		$route_id = $this->db->insert_id();
		
		// create basic price
		if(!empty($basic_prices)) {
			$this->create_flight_basic_price($basic_prices, $route_id);
		}
		
		// create return route if it isn't existed
		$from 	= $flight_route['from_destination_id'];
		$to 	= $flight_route['to_destination_id'];
		
		if(! $this->check_flight_route($to, $from)) {
			$flight_route['from_destination_id'] = $to;
			$flight_route['to_destination_id'] = $from;
			
			$this->create_flight($flight_route);
		}
		
		$this->db->trans_complete();
		
		// return this id just created
		return $route_id;
	}
	
	function update_flight($flight_route, $basic_prices) {
		
		$this->db->trans_start();

		$flight_route['user_modified_id'] 	= get_user_id();
		$flight_route['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
		
		$this->db->update('flight_routes', $flight_route, array('id' => $flight_route['id']));
		
		// delete basic prices of route
		$this->db->where('flight_route_id', $flight_route['id']);
		$this->db->delete('flight_basic_prices');
		
		// create basic price
		$this->create_flight_basic_price($basic_prices, $flight_route['id']);
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_flight($id) {
	
		$flight['deleted'] = DELETED;
	
		$this->db->update('flight_routes', $flight, array('id' => $id));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function check_flight_route($from, $to, $id = null) {
		//$this->db->select('f.*, d.name as from_des, d2.name as to_des');
		
		$this->db->where('d.id', $from);
		
		$this->db->where('d2.id', $to);
		
		if(!empty($id)) {
			$this->db->where('f.id !=', $id);
		}
		
		$this->db->join('destinations d', 'd.id = f.from_destination_id');
		
		$this->db->join('destinations d2', 'd2.id = f.to_destination_id');
		
		$query = $this->db->get('flight_routes f');
		
		$results = $query->result_array();
		
		if(!empty($results) && count($results) > 0) {
			return true;
		}
		
		return false;
	}
	
	/*
	 * Get flight destinations
	*/
	function get_flight_destinations($parent_des_id) {
	
		$this->db->select ( 'd.id, d.name, d.destination_code, d.url_title' );
			
		$this->db->join('destination_places dp', 'dp.destination_id = d.id', 'left outer');
		
		$this->db->where ( 'dp.parent_id', $parent_des_id );
		$this->db->where ( 'd.is_flight_destination', 1 );
		$this->db->where ( 'd.deleted != ', DELETED );
		$this->db->order_by('d.position_flight', 'asc');
		
		$query = $this->db->get ( 'destinations d' );
		
		return $query->result_array ();
	}
	
	function get_all_flight_destinations() {
		$dess = array();
		
		$this->db->select ( 'id, name, type' );
		$this->db->where('is_flight_group', 1);
		$this->db->where ( 'deleted != ', DELETED );
		$this->db->order_by('position', 'asc');
		$query = $this->db->get('destinations');
		$area = $query->result_array();
		
		foreach ($area as $des) {
			array_push($dess, array("name" => $des['name'], "destinations" => $this->get_flight_destinations($des['id'])));
		}
		
		return $dess;
	}
	
	function _get_max_order() {
	
		$this->db->select_max('position');
	
		$query = $this->db->get('flight_routes');
	
		$results = $query->result_array();
		if (count($results) > 0) {
			return $results[0]['position'];
		}
	
		return 0;
	}
	
	function update_flight_des_position($parent_id = '') {
		if(!empty($parent_id)) {
			$this->db->where('parent_id', $parent_id);
		}
	
		$this->db->where('is_flight_destination', 1);
		$this->db->order_by('name','asc');
	
		$query = $this->db->get('destinations');
		$results = $query->result_array();
			
		foreach ($results as $key => $des) {
			$this->db->set('position_flight', $key+1);
			$this->db->where('id', $des['id']);
			$this->db->update('destinations');
			//echo $des['id'].' - '.($key+1).'<br>';
		}
	
		//echo 'Update position for '.count($results).' destinations completed !!!';exit();
	}
	
	function _get_max_des_order($field = 'position') {
	
		$this->db->select_max($field);
	
		$query = $this->db->get('destinations');
	
		$results = $query->result_array();
		if (count($results) > 0) {
			return $results[0][$field];
		}
	
		return 0;
	}
	
	
	/**
	 *	Search Airlines
	 *	Khuyenpv 23.09.2014 
	 * 
	 **/
	
	function search_airlines($search_criteria = '', $num, $offset, $order_field = 'position', $order_type = 'asc')
	{
		$this->db->select('a.*, u.username as last_modified_by');
	
		$this->db->join('users u', 'a.user_modified_id = u.id', 'left outer');
		
		$this->_set_airline_search_criteria($search_criteria);
	
		$this->db->order_by($order_field, $order_type);
	
		$query = $this->db->get('airlines a', $num, $offset);
	
		$results = $query->result_array();
	
		return $results;
	}
	
	function _set_airline_search_criteria($search_criteria = '', $mask_name = 'a.')
	{
		$this->db->where($mask_name . 'deleted !=', DELETED);
	
		if ($search_criteria == '')	{
			return;
		}
		foreach ($search_criteria as $key => $value) {
			if($value === '') continue;
			
			switch ($key) {
				case 'name' :
					$this->db->like($mask_name. 'name', $value, 'both');
					$this->db->or_like($mask_name. 'code', $value, 'both');
					break;
				case 'is_domistic' :
					$this->db->where($mask_name. 'is_domistic', $value);
					break;
			}
		}
	}
	
	function get_nr_airlines($search_criteria = '')
	{
		$this->_set_airline_search_criteria($search_criteria);
		return $this->db->count_all_results('airlines a');
	}
	
	function get_airline($id){
		
		$this->db->where('id', $id);
		$this->db->where('deleted !=', DELETED);
		$query = $this->db->get('airlines');
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			
			return $results[0];
			
		} else {
			
			return FALSE;
			
		}
	}
	
	function create_airline($airline){
		
		$login_user_id = get_user_id();
		 
		$airline['user_created_id'] = $login_user_id;
		$airline['user_modified_id'] = $login_user_id;
		 
		$airline['date_created'] = date(DB_DATE_TIME_FORMAT);
		$airline['date_modified'] = date(DB_DATE_TIME_FORMAT);
		 
		$this->db->escape();
		$this->db->insert('airlines', $airline);
		$id = $this->db->insert_id();
		
		$this->db->set('position', $id);
		$this->db->where('id', $id);
		$this->db->update('airlines');
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
		
	}
	
	function update_airline($id, $airline){
		
		$airline['date_modified'] = date(DB_DATE_TIME_FORMAT);
		$airline['user_modified_id'] = get_user_id();
		 
		$this->db->where('id', $id);
		$this->db->update('airlines', $airline);
		
		$error_nr = $this->db->_error_number();
		//$error_ms = $this->db->_error_message();
		 
		return !$error_nr;
	}
	
	function delete_airline($id){
		
		$this->db->set('deleted', DELETED);
		$this->db->where('id', $id);
		$this->db->update('airlines');
		
		$this->db->where('airline_id', $id);
		$this->db->delete('flight_basic_prices');
		
		$error_nr = $this->db->_error_number();
		//$error_ms = $this->db->_error_message();
			
		return !$error_nr;
	}
	
	function is_unique_airline_code($code, $id){
		
		if (!empty($id)) { // Check unique for Update mode
			$this->db->where('id !=', $id);
		}
		$this->db->where('code', trim($code));
		$this->db->where('deleted !=', DELETED);
		$query = $this->db->count_all_results('airlines');
		if ($query == 0) {
			
			return TRUE;
		}
		
		return FALSE;
		
	}
	
	function is_unique_airline_name($name, $id){
	
		if (!empty($id)) { // Check unique for Update mode
			$this->db->where('id !=', $id);
		}
		$this->db->where('name', $name);
		$this->db->where('deleted !=', DELETED);
		$query = $this->db->count_all_results('airlines');
		if ($query == 0) {
			return TRUE;
		}
	
		return FALSE;
	
	}
	
	
	/**
	 *	Search Flight Category
	 *	Khuyenpv 23.09.2014
	 *
	 **/
	
	function search_categories($search_criteria = '', $num, $offset, $order_field = 'position', $order_type = 'asc')
	{
		$this->db->select('fc.*, u.username as last_modified_by');
	
		$this->db->join('users u', 'fc.user_modified_id = u.id', 'left outer');
	
		$this->_set_category_search_criteria($search_criteria);
	
		$this->db->order_by($order_field, $order_type);
	
		$query = $this->db->get('flight_categories fc', $num, $offset);
	
		$results = $query->result_array();
	
		return $results;
	}
	
	function _set_category_search_criteria($search_criteria = '', $mask_name = 'fc.')
	{
		$this->db->where($mask_name . 'deleted !=', DELETED);
	
		if ($search_criteria == '')	{
			return;
		}
		foreach ($search_criteria as $key => $value) {
			if($value === '') continue;
				
			switch ($key) {
				case 'name' :
					$this->db->like($mask_name. 'name', $value, 'both');
					break;
				case 'status' :
					$this->db->where($mask_name. 'status', $value);
					break;
			}
		}
	}
	
	function get_nr_categories($search_criteria = '')
	{
		$this->_set_category_search_criteria($search_criteria);
		return $this->db->count_all_results('flight_categories fc');
	}
	
	function get_category($id){
	
		$this->db->where('id', $id);
		$this->db->where('deleted !=', DELETED);
		$query = $this->db->get('flight_categories');
	
		$results = $query->result_array();
	
		if(count($results) > 0){
				
			return $results[0];
				
		} else {
				
			return FALSE;
				
		}
	}
	
	function create_category($category){
	
		$login_user_id = get_user_id();
			
		$category['user_created_id'] = $login_user_id;
		$category['user_modified_id'] = $login_user_id;
			
		$category['date_created'] = date(DB_DATE_TIME_FORMAT);
		$category['date_modified'] = date(DB_DATE_TIME_FORMAT);
			
		$this->db->escape();
		$this->db->insert('flight_categories', $category);
		$id = $this->db->insert_id();
	
		$this->db->set('position', $id);
		$this->db->where('id', $id);
		$this->db->update('flight_categories');
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	
	}
	
	function update_category($id, $category){
	
		$category['date_modified'] = date(DB_DATE_TIME_FORMAT);
		$category['user_modified_id'] = get_user_id();
			
		$this->db->where('id', $id);
		$this->db->update('flight_categories', $category);
	
		$error_nr = $this->db->_error_number();
		//$error_ms = $this->db->_error_message();
			
		return !$error_nr;
	}
	
	function delete_category($id){
	
		$this->db->set('deleted', DELETED);
		$this->db->where('id', $id);
		$this->db->update('flight_categories');
	
		$error_nr = $this->db->_error_number();
		//$error_ms = $this->db->_error_message();
			
		return !$error_nr;
	}
	
	function is_unique_category_name($name, $id){
	
		if (!empty($id)) { // Check unique for Update mode
			$this->db->where('id !=', $id);
		}
		$this->db->where('name', $name);
		$this->db->where('deleted !=', DELETED);
		$query = $this->db->count_all_results('flight_categories');
		if ($query == 0) {
			return TRUE;
		}
	
		return FALSE;
	
	}
	
	/**
	 * Get uploaded photos for flight
	 */	
	function get_flight_photos($airline_id = '', $flight_category_id = ''){
		
		if(!empty($airline_id)){
			$this->db->where('airline_id', $airline_id);
		}
		
		if(!empty($flight_category_id)){
			$this->db->where('flight_category_id', $flight_category_id);
		}
		
		$query = $this->db->get('flight_photos');
		
		$results = $query->result_array();
		
		foreach ($results as $key => $value){
		
			list($width, $height) = getimagesize(get_static_resources('/images/flights/'.$value['name']));
			$value['width'] = $width;
			$value['height'] = $height;
				
			$results[$key] = $value;
		
		}
		
		return $results;
	}
	
	function get_photo($id){
		
		$this->db->where('id', $id);
	
		$query = $this->db->get('flight_photos');
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			
			return $results[0];
			
		}
		return FALSE;
	}
	
	function delete_photo($id){
	
		$this->db->where('id', $id);
	
		$this->db->delete('flight_photos');
	}
	
	function create_photos($photos){
		$this->db->insert_batch('flight_photos', $photos);
	}
	
}

?>