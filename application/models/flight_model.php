<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Flight_Model extends CI_Model {
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
			
		$this->load->database();	
		
		$this->load->helper('url');
		
		$this->load->helper('common');
	}
	
	/*
	 * Get all airlines
	 */
	function get_airlines(){
		
		$this->db->select('id, name, url_title, is_domistic');
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->order_by('position', 'asc');
		
		$query = $this->db->get('airlines');
		
		return $query->result_array();
	}
	/**
	 * Get Airline By ID
	 * Khuyenpv 24.09.2014
	 * @param unknown $id
	 * @return unknown|boolean
	 */
	function get_airline($id){
	
		$this->db->select('id, name, url_title, description');

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
	
	function get_category($id){
		
		$this->db->select('id, name, url_title, description');
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('id', $id);
		
		$query = $this->db->get('flight_categories');
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			return $results[0];
		} else {
			return FALSE;
		}
	}
	
	
	/**
	 * Get all flight categories
	 * Khuyenpv 24.09.2014
	 */
	function get_flight_categories(){
		
		$today = date(DB_DATE_FORMAT);
		
		$this->db->select('id, name, url_title');
		
		$this->db->where('status', STATUS_ACTIVE);
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('start_date <=', $today);
		
		$this->db->where('end_date >=', $today);
		
		$this->db->order_by('position', 'asc');
		
		$query = $this->db->get('flight_categories');
		
		return $query->result_array();
	}
	
	/*
	 * Get flight destination ny code
	 */
	function get_destination_by_code($city_code){
		
		$this->db->select('id, name, destination_code, parent_id');
		
		$this->db->where('destination_code', $city_code);
		
		$this->db->where('deleted != ', DELETED);

		$query = $this->db->get('destinations');

		$results = $query->result_array();
		
		if(count($results) > 0){
			return $results[0];
		} 

		return '';
	}
	
	function search_destinations($term)
	{
		$this->db->select('id, name, destination_code');
		
		//$value = $this->db->escape_like_str($term);
		
		$this->db->like('name', $term, 'both');
		
		$this->db->or_like('destination_code', $term, 'both');
	
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('is_flight_destination', 1);
		
		$this->db->limit(10);
		
		$query = $this->db->get('destinations');
	
		$results = $query->result_array();
		
		//print_r($this->db->last_query());exit();
	
		return $results;
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
	
	function get_popular_fights() {
	
		$this->db->select('f.*, d.name as from_des, d2.name as to_des, d.destination_code as from_code, d2.destination_code as to_code');
		
		$this->db->join('destinations d', 'd.id = f.from_destination_id');
		
		$this->db->join('destinations d2', 'd2.id = f.to_destination_id');
	
		$this->db->where('d.is_flight_destination', 1);
	
		$this->db->where('f.is_show_vietnam_flight_page', 1);
		
		$this->db->where('f.status', 1);
	
		$this->db->order_by('f.position', 'asc');
	
		$this->db->limit(5);
	
		$query = $this->db->get('flight_routes f');
	
		$results = $query->result_array();
	
		if(!empty($results)) {
			foreach ($results as $k => $route) {
				$this->db->where('flight_route_id', $route['id']);
				$this->db->join('airlines a', 'fp.airline_id = a.id');
				$this->db->order_by('a.id', 'asc');
				$query = $this->db->get('flight_basic_prices fp');
				$prices = $query->result_array();
				$route['basic_prices'] = $prices;
	
				$results[$k] = $route;
			}
		}
	
		return $results;
	}
	
	function get_flights_of_destiantion($des_id) {
		
		$this->db->select('f.*, d.name as from_des, d2.name as to_des, d.destination_code as from_code, d2.destination_code as to_code');
		
		$this->db->join('destinations d', 'd.id = f.from_destination_id');
		
		$this->db->join('destinations d2', 'd2.id = f.to_destination_id');
		
		$this->db->where('f.to_destination_id', $des_id);
		
		$this->db->where('d.is_flight_destination', 1);
		
		$this->db->where('f.is_show_flight_destination_page', 1);
		
		$this->db->where('f.status', 1);
		
		$this->db->order_by('f.position', 'asc');
		
		$this->db->limit(5);
		
		$query = $this->db->get('flight_routes f');
		
		$results = $query->result_array();
		
		if(!empty($results)) {
			foreach ($results as $k => $route) {
				$this->db->where('flight_route_id', $route['id']);
				$this->db->join('airlines a', 'fp.airline_id = a.id');
				$this->db->order_by('a.id', 'asc');
				$query = $this->db->get('flight_basic_prices fp');
				$prices = $query->result_array();
				$route['basic_prices'] = $prices;
		
				$results[$k] = $route;
			}
		}
		
		return $results;
	}
	
	function get_all_flight_routes($from = null) {
		
		$this->db->select('d.name as from_des, d2.name as to_des, d.destination_code as from_code, d2.destination_code as to_code');
		
		$this->db->join('destinations d', 'd.id = f.from_destination_id');
		
		$this->db->join('destinations d2', 'd2.id = f.to_destination_id');
		
		$this->db->where('d.is_flight_destination', 1);
		
		$this->db->where('f.status', 1);
		
		if(!empty($from)) {
			$this->db->where('d.destination_code', $from);
		}
		
		$this->db->order_by('f.from_destination_id', 'asc');
		
		$this->db->order_by('f.position', 'asc');
		
		$query = $this->db->get('flight_routes f');
		
		$results = $query->result_array();
		
		$city_codes = array();
		$mapping_city = array();
		
		$cnt = 0;
		foreach ($results as $des) {
			if(!in_array($des['from_code'], $city_codes)) {
				if(!empty($city_codes)) $cnt++;
				
				$city_codes[] = $des['from_code'];
				$mapping_city[] = array($des['from_code'] => array($des['to_code']));
			
			} else {
				
				$list = $mapping_city[$cnt];
				if(isset($list[$des['from_code']])) {
					array_push($list[$des['from_code']], $des['to_code']);
					$mapping_city[$cnt] = $list;
				}
			}
		}
		
		if(!empty($from)) {
			return $results;
		}
		
		return json_encode($mapping_city);
	}
	
	function get_international_flights() {
		$this->db->select ( 'id, name, destination_code, url_title' );
		
		$this->db->where ( 'type', DESTINATION_TYPE_COUNTRY );
		
		$this->db->where ( 'id !=', DESTINATION_VIETNAM ); // ignore vietnam
		
		$this->db->where ( 'deleted != ', DELETED );
		
		$this->db->order_by('url_title', 'asc');
		
		$query = $this->db->get ( 'destinations' );
		
		$results = $query->result_array ();
		
		return $results;
	}
	
	function suggest_flight_destinations($term)
	{
		$this->db->select('id, name, destination_code as code');
	
		$value = $this->db->escape_like_str($term);
	
		$this->db->like('name', $value, 'both');
		
		$this->db->or_like('destination_code', $value, 'both');
	
		$this->db->where('deleted !=', DELETED);
	
		$this->db->where('is_flight_destination	', 1);
	
		$this->db->limit(10);
		
		$this->db->order_by('name', 'asc');
	
		$query = $this->db->get('destinations');
	
		$results = $query->result_array();
	
		return $results;
	}
	
	function has_flight_route($from_code, $to_code){
		
		$this->db->from('flight_routes fr');
		$this->db->join('destinations f_d', 'f_d.id = fr.from_destination_id');
		$this->db->join('destinations t_d', 't_d.id = fr.to_destination_id');
		$this->db->where('f_d.destination_code', $from_code);
		$this->db->where('t_d.destination_code', $to_code);
		$this->db->where('fr.deleted !=', DELETED);
		$this->db->where('fr.status', STATUS_ACTIVE);
		
		$cnt = $this->db->count_all_results();
		
		return $cnt > 0;
	}
	
	function is_domistic_des($des_id){
		
		$this->db->where('destination_id', $des_id);
		$this->db->where('parent_id', DESTINATION_VIETNAM);
		$cnt = $this->db->count_all_results('destination_places');
		
		return ($cnt > 0);
	}
	
	
	/**
	 * Update Booking Info for Flight customer booking
	 * Khuyenpv 22.10.2014
	 */
	function update_vnisc_booking_info($customer_booking_id, $vnisc_booking_id, $vnisc_booking_code){
		
		$this->db->where('id', $customer_booking_id);
		
		$this->db->set('vnisc_boooking_id', $vnisc_booking_id);
		
		$this->db->set('vnisc_booking_code', $vnisc_booking_code);
		
		$this->db->update('customer_bookings');
	}
	
	/**
	 * Update PNR for SR on the VNISC call-back
	 * Khuyenpv 23.10.2014
	 */
	function update_pnr_for_sr($vnisc_booking_code, $flight_from_code, $flight_to_code, $flying_date, $pnr, $status, $error){
		
		$this->db->select('id, is_flight_domistic');
		
		$this->db->from('customer_bookings');
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('vnisc_booking_code', $vnisc_booking_code);
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			
			$cb = $results[0];
			
			if($cb['is_flight_domistic'] == STATUS_ACTIVE){
				
				$this->update_pnr_flight_domistic($cb['id'], $flight_from_code, $flight_to_code, $flying_date, $pnr, $status, $error);
				
			} else {
				
				$this->update_pnr_flight_international($cb['id'], $flight_from_code, $flight_to_code, $flying_date, $pnr, $status, $error);
			}
			
		}
	}
	
	function update_pnr_flight_domistic($cb_id, $flight_from_code, $flight_to_code, $flying_date, $pnr, $status, $error){
		
		$this->db->select('id, start_date, description');
		
		$this->db->from('service_reservations');
		
		$this->db->where('customer_booking_id', $cb_id);
		
		$this->db->where('deleted != ', DELETED);
		
		$this->db->where('flight_from_code', $flight_from_code);
		
		$this->db->where('flight_to_code', $flight_to_code);
		
		$this->db->where('reservation_status', 1); // New status
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		foreach ($results as $sr){
				
			if(date('dmY', strtotime($sr['start_date'])) == $flying_date){
		
				$update_sr['flight_pnr'] = $pnr;
		
				if ($status == 'failed'){
						
					$new_desc = 'PNR Error ['.date('d-m-Y H:i:s').']: '. $error;
						
				} else {
						
					$new_desc = 'PNR Success ['.date('d-m-Y H:i:s').']';
				}
		
				if(!empty($sr['description'])){
					$new_desc .= "\n".$sr['description'];
				}
					
				$update_sr['description'] = $new_desc;
		
				$this->db->where('id', $sr['id']);
		
				$this->db->update('service_reservations', $update_sr);
		
			}
				
		}
		
	}
	
	function update_pnr_flight_international($cb_id, $flight_from_code, $flight_to_code, $flying_date, $pnr, $status, $error){
		
		$this->db->select('id, start_date, description, flight_class');
		
		$this->db->from('service_reservations');
		
		$this->db->where('customer_booking_id', $cb_id);
		
		$this->db->where('deleted != ', DELETED);
		
		$this->db->where('reservation_status', 1); // New status
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		foreach ($results as $sr){
		
			if(!empty($sr['flight_class'])){
				
				$update_sr['flight_pnr'] = $pnr;
			
				if ($status == 'failed'){
		
					$new_desc = 'PNR Error ['.date('d-m-Y H:i:s').']: '. $error;
		
				} else {
		
					$new_desc = 'PNR Success ['.date('d-m-Y H:i:s').']';
				}
		
				if(!empty($sr['description'])){
					$new_desc .= "\n".$sr['description'];
				}
					
				$update_sr['description'] = $new_desc;
		
				$this->db->where('id', $sr['id']);
		
				$this->db->update('service_reservations', $update_sr);
			
			}
		}
	}
}

?>
