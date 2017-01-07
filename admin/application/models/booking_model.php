<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_Model extends CI_Model{
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
		$this->load->config('booking_meta');
	}
	
	function get_booked_customer_cities(){
		
		$this->db->distinct();
		$this->db->select('d.id, d.name');	
		$this->db->from('customers as c');	
		$this->db->join('destinations as d','d.id = c.destination_id');
		
		//$this->db->where('d.type', DESTINATION_TYPE_CITY);
		//$this->db->where('d.parent_id', DESTINATION_VIETNAM);
		$this->db->where('d.deleted !=', DELETED);
		$this->db->order_by('d.position');
		
		$query = $this->db->get();
	
		$results = $query->result_array();
		return $results;
	}
	
	function get_all_partners(){
	
		$this->db->select('id, name, service_type');
	
		$this->db->where('deleted !=', DELETED);
	
		$this->db->order_by('name', 'asc');
	
		$query = $this->db->get('partners');
	
		return $query->result_array();
	}
	

	function _getSearchCBQueryStrCondition($search_criteria ='', $str_query=''){

		// Eleminate deleted booking [6-12-2012]
		$str_query = $str_query. " WHERE cb.deleted != ". DELETED;

		if (isset($search_criteria['customer_id'])){
				
			$str_query = $str_query. " AND cb.customer_id = '".$search_criteria['customer_id']."'";
				
		}

		if (isset($search_criteria['status'])){
				
			if ($search_criteria['status'] == -1){

				$str_query = $str_query. " AND cb.status IN (1, 2, 3, 4)";

			} else if ($search_criteria['status'] == -2){

				$str_query = $str_query. " AND cb.status IN (3, 4)";

			} else if ($search_criteria['status'] == -3) {

				$str_query = $str_query. " AND cb.status IN (5, 6, 7)";

			} else {
					
				$str_query = $str_query. " AND cb.status = '".$search_criteria['status']."'";
					
			}
		}

		if (isset($search_criteria['user_id'])){
				
			$str_query = $str_query. " AND cb.user_id = '".$search_criteria['user_id']."'";
				
		}

		if (isset($search_criteria['approve_status'])){
				
			$str_query = $str_query. " AND cb.approve_status = '".$search_criteria['approve_status']."'";
				
		}

		if (isset($search_criteria['city'])){
				
			$str_query = $str_query. " AND c.destination_id = '".$search_criteria['city']."'";
				
		}

		if (isset($search_criteria['booking_site'])){
				
			$str_query = $str_query. " AND cb.booking_site = ".$search_criteria['booking_site'];
				
		}

		if (isset($search_criteria['customer_type'])){
				
			$str_query = $str_query. " AND cb.customer_type = ".$search_criteria['customer_type'];
				
		}

		if (isset($search_criteria['request_type'])){
				
			$str_query = $str_query. " AND cb.request_type = ".$search_criteria['request_type'];
				
		}


		if (isset($search_criteria['source'])){
				
			$str_query = $str_query. " AND cb.booking_source_id = ".$search_criteria['source'];
				
		}

		if (isset($search_criteria['medium'])){
				
			$str_query = $str_query. " AND cb.medium = ".$search_criteria['medium'];
				
		}

		if (isset($search_criteria['keyword'])){
				
			$str_query = $str_query. " AND cb.keyword LIKE '%".$search_criteria['keyword']."%'";
				
		}

		if (isset($search_criteria['landing_page'])){
				
			$str_query = $str_query. " AND cb.landing_page LIKE '%".$search_criteria['landing_page']."%'";
				
		}

		if (isset($search_criteria['campaign'])){
				
			$str_query = $str_query. " AND cb.campaign_id = ".$search_criteria['campaign'];
				
		}

		if (isset($search_criteria['duplicated_cb'])){
				
			$duplicated_ids = $this->_get_duplicate_cb();
				
			if (count($duplicated_ids) > 0){

				$str_duplicated_ids = "(".implode(",", $duplicated_ids).")";
					
				$str_query = $str_query. " AND c.id IN ".$str_duplicated_ids;
					
			}
		}

		if (isset($search_criteria['name'])){
				
			$str_query = $str_query." AND(";
			
			$str_query = $str_query. " c.full_name LIKE '%".$search_criteria['name']."%'";
				
			$str_query = $str_query. " OR c.email LIKE '%".$search_criteria['name']."%'";
			
			$str_query = $str_query. " OR c.phone LIKE '%".$search_criteria['name']."%'";
				
			$str_query = $str_query. " OR u.username LIKE '%".$search_criteria['name']."%'";
			
			$str_query = $str_query. " OR cb.id = '".$search_criteria['name']."'";
				
			$ids = $this->_getCustomerBookingByServiceName($search_criteria['name']);

			if ($ids != ""){
				$str_query = $str_query. " OR cb.id IN ".$ids;
			}
			
			$ids = $this->_get_customer_booking_ids_by_passenger_name($search_criteria['name']);
			
			if ($ids != ""){
				$str_query = $str_query. " OR cb.id IN ".$ids;
			}
				
			
				
			$str_query = $str_query.")";
		}

		if (isset($search_criteria['partner_id'])){
				
			$str_query = $str_query." AND(";
				
			$ids = $this->_getCustomerBookingByPartner($search_criteria['partner_id']);

			if ($ids != ""){
				$str_query = $str_query. " cb.id IN ".$ids;
			}
				
			$str_query = $str_query.")";
		}

		if (isset($search_criteria['booking_filter']) && count($search_criteria['booking_filter']) > 0){
				
			$near_future_config = $this->config->item('near_future_day');
				
			//$current_date = date('Y-m-d');
				
			$current_date = $this->_getCurrentDate();
				
			$limit_date = strtotime($current_date . " +". $near_future_config. " day");

			$limit_date = date('Y-m-d', $limit_date);
				
			$tomorrow = strtotime($current_date . " +1 day");

			$tomorrow = date('Y-m-d', $tomorrow);
				
			$str_query = $str_query." AND(";
				
			$or_flag = false;
				
			// current booking
			if (in_array(1, $search_criteria['booking_filter'])){

				$or_flag = true;

				$str_query = $str_query. "(cb.start_date <= '".$current_date."' AND cb.end_date >= '".$current_date."') OR cb.start_date = '".$tomorrow."'";
			}
				
			// near future booking
			if (in_array(2, $search_criteria['booking_filter'])){

				if ($or_flag){
					$str_query = $str_query. " OR ";
				}

				$or_flag = true;

				$str_query =$str_query. "(cb.start_date <= '".$limit_date."' AND cb.start_date > '".$tomorrow."')";
			}
				
			// long future booking
			if (in_array(3, $search_criteria['booking_filter'])){

				if ($or_flag){
					$str_query = $str_query. " OR ";
				}

				$or_flag = true;

				$str_query = $str_query. "(cb.start_date > '".$limit_date."')";
			}
				
			// finished booking
			if (in_array(4, $search_criteria['booking_filter'])){

				if ($or_flag){
					$str_query = $str_query. " OR ";
				}

				$str_query = $str_query."(cb.end_date < '".$current_date."')";
			}
				
			$str_query = $str_query." )";
		}

		if (isset($search_criteria['booking_status'])){
				
			$str_status = "";
				
			foreach ($search_criteria['booking_status'] as $value){
				$str_status = $str_status .$value.",";
			}
				
			if (strlen($str_status) > 1){
				$str_status = substr($str_status, 0, strlen($str_status) - 1);
			}
				
			if ($str_status != ""){
				$str_status = "(" . $str_status. ")";
			}
				
			if ($str_status != ""){
					
				$str_query = $str_query." AND cb.status IN ".$str_status;
					
			}
		}

		if (isset($search_criteria['date_field'])){

			if (in_array(1, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND cb.request_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND cb.request_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(2, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND cb.start_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND cb.start_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(3, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND cb.end_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND cb.end_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(4, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND cb.meeting_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND cb.meeting_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(5, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND cb.payment_due >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND cb.payment_due <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(6, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND cb.booking_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
						
					$str_query = $str_query. " AND cb.booking_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
						
				}
			}
				
		}
		
		if (isset($search_criteria['payment_method'])){
		
			$str_query = $str_query. " AND cb.payment_method = ".$search_criteria['payment_method'];
		
		}

		return $str_query;
	}

	function searchCustomerBooking($search_criteria = '', $num = -1, $offset = 0)
	{
		$str_query = "SELECT cb.*, c.full_name, c.email, c.phone, d.name as city, c.gender as title, c.address, u.username  FROM customer_bookings cb ".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id".
				" LEFT OUTER JOIN destinations d ON d.id = c.destination_id";

		$str_query = $this->_getSearchCBQueryStrCondition($search_criteria, $str_query);

		$str_query = $str_query. " ORDER BY " . $search_criteria['sort_column']." ".$search_criteria['sort_order'];
		
		$str_query = $str_query. ", cb.id DESC";

		if ($num == -1) {
			//
		} else {
			//this->db->limit($num, $offset);
				
			$str_query = $str_query. " LIMIT ".$offset.", ".$num;
		}
		

		$query = $this->db->query($str_query);


		$cbs = $query->result_array();

		$cb_ids = $this->_getIdsArr($cbs);

		$srs = $this->_getServiceReservations($cb_ids);

		$cbs = $this->_setServiceReservations($cbs, $srs);


		// set duplicated booking flag

		if (isset($search_criteria['duplicated_cb']) && $search_criteria['duplicated_cb'] == 1){
				
			foreach ($cbs as $key=>$value){

				$value['is_duplicate'] = true;

				$cbs[$key] = $value;
			}
				
		} else {

			$duplicate_customers = $this->_get_duplicate_cb();
				
			$cbs = $this->_set_duplicate_flag($cbs, $duplicate_customers);

		}

		return $cbs;
	}

	function getNumCustomerBooking($search_criteria = '')
	{
		$str_query = "SELECT cb.*, c.full_name, c.email, c.phone, c.destination_id, c.gender as title, c.address, u.username  FROM customer_bookings cb ".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id".
				" LEFT OUTER JOIN destinations d ON d.id = c.destination_id";

		$str_query = $this->_getSearchCBQueryStrCondition($search_criteria, $str_query);

		$query = $this->db->query($str_query);

		return $query->num_rows();
	}

	function countTotalMoney($search_criteria = '', $current_user = ''){
		$str_query = "SELECT SUM(cb.net_price) net_price, SUM(cb.selling_price) selling_price, SUM(cb.actual_selling) actual_selling , SUM(cb.payment_amount) payment_amount FROM customer_bookings cb ".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id";

		$str_query = $this->_getSearchCBQueryStrCondition($search_criteria, $str_query);

		if ($current_user != ''){
				
			if ($current_user->is_admin_name() || $current_user->is_accounting() || $current_user->is_sale_manager() || $current_user->is_developer_team()){
				// donothing
			} else {

				$share_permission = $current_user->share_permission;

				if (empty($share_permission)){

					$str_query = $str_query. " AND cb.user_id = ". $current_user->id;

				} else {
						
					$str_query = $str_query. " AND (cb.user_id = ". $current_user->id;
						
					foreach ($share_permission as $cb_id){

						$str_query = $str_query. " OR cb.user_id = ".$cb_id;

					}
						
					$str_query = $str_query. ')';
				}

			}
				
		}

		$query = $this->db->query($str_query);

		return $query->result_array();
	}

	function  _set_duplicate_flag($cbs, $duplicate_customers){

		foreach ($cbs as $key=>$cb){

			if (count($duplicate_customers) > 0 && in_array($cb['customer_id'], $duplicate_customers)){

				$cb['is_duplicate'] = true;

			} else {
				$cb['is_duplicate'] = false;
			}
				
			$cbs[$key] = $cb;
				
		}

		return $cbs;
	}

	function _get_duplicate_cb($cb_ids = '') {

		$ret = array();
			
		$this->db->select('c.phone, count(cb.id) as count_id', false);
			
		$this->db->from('customer_bookings as cb');
		
		$this->db->join('customers as c', 'c.id = cb.customer_id');
			
		$this->db->where('cb.deleted !=', DELETED);
			
		$this->db->group_by('c.phone');
			
		$this->db->having('count_id >', 1);
			
		$query = $this->db->get();
			
		$results = $query->result_array();
		
		$email_arr = array();
			
		foreach ($results as $c){
			$email_arr[] = $c['phone'];
		}
		
		if(count($email_arr) > 0){
		
			$this->db->select('id');
			
			$this->db->from('customers');
			
			$this->db->where_in('phone', $email_arr);
			
			$query = $this->db->get();
			
			$results = $query->result_array();
			
			foreach ($results as $c){
				
				$ret[] = $c['id'];
				
			}
		
		}
		
		
		$this->db->select('customer_id, count(id) as count_id');
			
		$this->db->from('customer_bookings');
			
		$this->db->where('deleted !=', DELETED);
			
		$this->db->group_by('customer_id');
			
		$this->db->having('count_id >', 1);
			
		$query = $this->db->get();
			
		$results = $query->result_array();
			
		foreach ($results as $cb){
			$ret[] = $cb['customer_id'];
		}
		

		return $ret;

	}

	function _getIdsArr($customer_bookings){
		$ret = array();

		foreach ($customer_bookings as $cb){
			$ret[] = $cb['id'];
		}

		return $ret;
	}

	function _getServiceReservations($cb_ids) {
		$ret = array();

		if (count($cb_ids) > 0){
				
			$this->db->select('sr.*, p.payment_type');
				
			$this->db->from('service_reservations sr');
				
			$this->db->join('partners p', 'p.id = sr.partner_id', 'left outer');
				
			$this->db->where_in('sr.customer_booking_id', $cb_ids);
				
			$this->db->where('sr.deleted !=', DELETED);
				
			$this->db->order_by('sr.start_date');
			
			$this->db->order_by('sr.id');
				
			//$this->db->order_by('sr.service_name');
				
			$query = $this->db->get();
				
			$ret = $query->result_array();

		}

		return $ret;
	}

	function _getServiceReservationsByBooking($cb_id, $deleted_only = false) {
		$ret = array();

		$this->db->select('sr.*, p.payment_type');
			
		$this->db->from('service_reservations sr');
	
		$this->db->join('partners p', 'p.id = sr.partner_id', 'left outer');
			
		$this->db->where('sr.customer_booking_id', $cb_id);

		if($deleted_only) {
			$this->db->where('sr.deleted =', DELETED);
		} else {
			$this->db->where('sr.deleted !=', DELETED);
		}

		$this->db->order_by('sr.start_date');
			
		$this->db->order_by('sr.id');

		$query = $this->db->get();

		$ret = $query->result_array();

		return $ret;
	}

	function _setServiceReservations($customer_bookings, $service_reservations){

		foreach ($customer_bookings as $key=>$value){
				
			$srs = array();
				
			foreach ($service_reservations as $v1) {
				if ($v1['customer_booking_id'] == $value['id']){
					$srs[] = $v1;
				}
			}
				
			$value['service_reservations'] = $srs;
				
			$customer_bookings[$key] = $value;
		}

		return $customer_bookings;
	}

	function _getCustomerBookingByServiceName($service_name){

		$ret = "";

		$str_query = "SELECT DISTINCT customer_booking_id FROM service_reservations WHERE deleted != 1 AND (service_name LIKE '%".$service_name."%'";
		
		$str_query .= " OR flight_pnr LIKE '%".$service_name."%')";

		$query = $this->db->query($str_query);

		$results = $query->result_array();

		foreach ($results as $value){
			$ret = $ret .$value['customer_booking_id'].",";
		}

		if (strlen($ret) > 1){
			$ret = substr($ret, 0, strlen($ret) - 1);
		}

		if ($ret != ""){
			$ret = "(" . $ret. ")";
		}

		return $ret;

	}
	
	function _get_customer_booking_ids_by_passenger_name($pas_name){
	
		$ret = "";
	
		$str_query = "SELECT customer_booking_id FROM flight_users WHERE full_name LIKE '%".$pas_name."%'";
	
		$query = $this->db->query($str_query);
	
		$results = $query->result_array();
	
		foreach ($results as $value){
			$ret = $ret .$value['customer_booking_id'].",";
		}
	
		if (strlen($ret) > 1){
			$ret = substr($ret, 0, strlen($ret) - 1);
		}
	
		if ($ret != ""){
			$ret = "(" . $ret. ")";
		}
	
		return $ret;
	
	}

	function _getCustomerBookingByPartner($partner_id){

		$ret = "";

		$str_query = "SELECT DISTINCT customer_booking_id FROM service_reservations WHERE deleted != 1 AND partner_id = ".$partner_id;

		$query = $this->db->query($str_query);

		$results = $query->result_array();

		foreach ($results as $value){
			$ret = $ret .$value['customer_booking_id'].",";
		}

		if (strlen($ret) > 1){
			$ret = substr($ret, 0, strlen($ret) - 1);
		}

		if ($ret != ""){
			$ret = "(" . $ret. ")";
		}

		return $ret;

	}

	function getCustomerBooking($id){

		$this->db->select('cb.*,c.id as c_id, c.full_name as customer_name,c.gender as title, c.email, c.phone, c.ip_address, u.username');
			
		$this->db->from('customer_bookings cb');
			
		$this->db->join('customers c', 'c.id = cb.customer_id');
		
		$this->db->join('users u', 'u.id = cb.user_id');

		$this->db->where('cb.id', $id);
			
		$query = $this->db->get();

		$results = $query->result_array();
			
		if (count($results) > 0){
			$customer_booking = $results[0];
			/*
			$customer_booking['date_modified'] = get_format_date($customer_booking['date_modified']);
			$customer_booking['user_modified'] = get_user_modified($customer_booking['user_modified_id']);
			*/
			return $customer_booking;
		} else {
			return '';
		}


	}

	function getCustomerBookingByCustomer($customer_id){

		$this->db->select('cb.*,c.id as c_id, c.full_name as customer_name, c.email, c.phone');
			
		$this->db->from('customer_bookings cb');
			
		$this->db->join('customers c', 'c.id = cb.customer_id');

		$this->db->where('c.id', $customer_id);

		$this->db->where('cb.status !=', 5); // status cancel

		$this->db->where('cb.status !=', 7); // status closelost

		$this->db->where('cb.deleted !=', DELETED);

		$this->db->order_by('cb.request_date', 'desc');
			
		$query = $this->db->get();

		$results = $query->result_array();
			
		if (count($results) > 0){
			return $results[0];
		} else {
			return '';
		}


	}


	function createCustomerBooking(){

		$customer_id = $this->input->post('customer');

		$request_date = $this->input->post('request_date');

		$start_date = date(DB_DATE_FORMAT); // today

		$end_date = date(DB_DATE_FORMAT); // today

		$status = $this->input->post('status');

		$user_id = $this->input->post('sale');

		$description = $this->input->post('description');

		$note = $this->input->post('note');
		
		$payment_method = $this->input->post('payment_method');

		$adults = $this->input->post('adults');

		$children = $this->input->post('children');

		$infants = $this->input->post('infants');


		$booking_date = $this->input->post('booking_date');

		$close_reason = $this->input->post('close_reason');


		$this->db->set('user_id', $user_id);

		$this->db->set('customer_id', $customer_id);

		$this->db->set('status', $status);

		$this->db->set('request_date', bpv_format_date($request_date. ' ' .date('H:i:s'), DB_DATE_TIME_FORMAT));

		$this->db->set('start_date', bpv_format_date($start_date, DB_DATE_FORMAT));

		$this->db->set('end_date', bpv_format_date($end_date, DB_DATE_FORMAT));

		$this->db->set('adults', $adults);

		$this->db->set('children', $children);

		$this->db->set('infants', $infants);
		
		if($payment_method != ''){
			$this->db->set('payment_method', $payment_method);
		}

		$this->db->set('description', $description);

		$this->db->set('note', $note);

		/*
		$onepay = format_rate_input($this->input->post('onepay'), 1);
			
		$cash = format_rate_input($this->input->post('cash'), 1);

		$pos = format_rate_input($this->input->post('pos'), 1);

		$this->db->set('onepay', $onepay);

		$this->db->set('cash', $cash);

		$this->db->set('pos', $pos);
		
		$actual_selling = $this->_calculateActualSelling($onepay, $cash, $pos, $payment_method);
		
		$this->db->set('actual_selling', $actual_selling);
		
		$this->db->set('actual_profit', $actual_selling);
		
		if (is_administrator() || is_accounting()){
				
			$approve_status = $this->input->post('approve_status');
				
			$approve_note = $this->input->post('approve_note');
				
			$this->db->set('approve_status', $approve_status);
				
			$this->db->set('approve_note', $approve_note);
		}
		
		*/

		$this->db->set('user_created_id', get_user_id());
		$this->db->set('date_created', date(DB_DATE_TIME_FORMAT));
		$this->db->set('user_modified_id', get_user_id());
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));


		if ($status == BOOKING_CANCEL || $status == BOOKING_CLOSE_LOST){
				
			$this->db->set('close_reason', $close_reason);
		}

		if ($booking_date != ""){
				
			$this->db->set('booking_date', bpv_format_date($booking_date, DB_DATE_FORMAT));
				
		} elseif($status == BOOKING_DEPOSIT || $status == BOOKING_FULL_PAID || $status == BOOKING_CLOSE_WIN){
				
			$this->db->set('booking_date', date(DB_DATE_FORMAT));
				
		}

		// customer_type, request_type, booking_site, source, medium, keyword

		$customer_type = $this->input->post('customer_type');

		$request_type = $this->input->post('request_type');

		$booking_site = $this->input->post('booking_site');

		$source = $this->input->post('source');

		$medium = $this->input->post('medium');

		$this->db->set('customer_type', $customer_type);

		$this->db->set('request_type', $request_type);

		$this->db->set('booking_site', $booking_site);

		// only admin & dev team have the right of updating booking source
		if (is_administrator() || is_dev_team()){

			$this->db->set('booking_source_id', $source);
				
			$this->db->set('medium', $medium);
				
		} else {
				
			$this->db->set('booking_source_id', 5); // direct
				
			$this->db->set('medium', 5); // email
				
		}

		$this->db->insert('customer_bookings');

		$id = $this->db->insert_id();

		return $id;
	}

	function _calculateActualSelling($onepay, $cash, $pos, $payment_method){
		
		if($payment_method == PAYMENT_METHOD_DOMESTIC_CARD){
			return $onepay*(1 - 0.011) + $cash + $pos*(1 - 0.0275);
		}
		
		if($payment_method == PAYMENT_METHOD_CREDIT_CARD){
			return $onepay*(1 - 0.033) + $cash + $pos*(1 - 0.0275);
		}
		
		return $onepay*(1 - 0.033) + $cash + $pos*(1 - 0.0275);
	}


	function updateCustomerBooking($id){

		$this->db->trans_start();

		$cb = $this->getCustomerBooking($id);

		$request_date = $this->input->post('request_date');

		$status = $this->input->post('status');

		$user_id = $this->input->post('sale');

		$adults = $this->input->post('adults');

		$children = $this->input->post('children');

		$infants = $this->input->post('infants');

		$description = $this->input->post('description');

		$note = $this->input->post('note');

		
		$payment_method = $this->input->post('payment_method');

		$booking_date = $this->input->post('booking_date');

		$close_reason = $this->input->post('close_reason');

		$send_review = $this->input->post('send_review');

		$this->db->set('adults', $adults);

		$this->db->set('children', $children);

		$this->db->set('infants', $infants);

		$this->db->set('user_id', $user_id);

		$this->db->set('status', $status);

		if (bpv_format_date($cb['request_date'], DB_DATE_FORMAT) != bpv_format_date($request_date, DB_DATE_FORMAT)){
			$this->db->set('request_date', bpv_format_date($request_date, DB_DATE_FORMAT));
		}

		
		$this->db->set('payment_method', $payment_method);

		$this->db->set('description', $description);

		$this->db->set('note', $note);

		$this->db->set('status', $status);


		$onepay = format_rate_input($this->input->post('onepay'), 1);
			
		$cash = format_rate_input($this->input->post('cash'), 1);

		$pos = format_rate_input($this->input->post('pos'), 1);

		$this->db->set('onepay', $onepay);

		$this->db->set('cash', $cash);

		$this->db->set('pos', $pos);
		
		$actual_selling = $this->_calculateActualSelling($onepay, $cash, $pos, $payment_method);
		

		$this->db->set('actual_selling', $actual_selling);

		$this->db->set('actual_profit', $actual_selling - $cb['net_price']);

		if (is_administrator() || is_accounting()){
				
			$approve_status = $this->input->post('approve_status');
				
			$approve_note = $this->input->post('approve_note');
				
			$this->db->set('approve_status', $approve_status);
				
			$this->db->set('approve_note', $approve_note);
				
		} else {
				
			$this->db->set('approve_status', 0);
				
		}

		$this->db->set('user_modified_id', get_user_id());

		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));


		$is_update_reservation_date = FALSE;

		$this->db->set('send_review', $send_review);

		if ($status == BOOKING_CANCEL || $status == BOOKING_CLOSE_LOST){
				
			$this->db->set('close_reason', $close_reason);
		}

		$b_date = date(DB_DATE_FORMAT);

		if ($booking_date != ""){
				
			$b_date = bpv_format_date($booking_date, DB_DATE_FORMAT);
				
			$this->db->set('booking_date', bpv_format_date($booking_date, DB_DATE_FORMAT));
				
			$is_update_reservation_date = true;
				
		} elseif ($status == BOOKING_DEPOSIT || $status == BOOKING_FULL_PAID || $status == BOOKING_CLOSE_WIN){
				
			if ($cb['status'] != BOOKING_DEPOSIT && $cb['status'] != BOOKING_FULL_PAID && $cb['status'] != BOOKING_CLOSE_WIN){

				$this->db->set('booking_date', date(DB_DATE_FORMAT));

				$is_update_reservation_date = TRUE;
			}
				
		}


		// customer_type, request_type, booking_site, source, medium, keyword

		$customer_type = $this->input->post('customer_type');

		$request_type = $this->input->post('request_type');

		$booking_site = $this->input->post('booking_site');

		$source = $this->input->post('source');

		$medium = $this->input->post('medium');

		$this->db->set('customer_type', $customer_type);

		$this->db->set('request_type', $request_type);

		$this->db->set('booking_site', $booking_site);

		
		$this->db->set('booking_source_id', $source);
		$this->db->set('medium', $medium);


		$this->db->where('id', $id);

		$this->db->update('customer_bookings');

		if ($is_update_reservation_date){
				
			$reserved_date_nr = $this->config->item('reserved_date_nr');
				
			$reserved_date = strtotime($b_date.' +'.$reserved_date_nr.' day');
				
			$reserved_date = date(DB_DATE_FORMAT, $reserved_date);
				
			$this->db->set('reserved_date', $reserved_date);
				
			$this->db->where('customer_booking_id', $id);
				
			$this->db->where('deleted !=', DELETED);
				
			$this->db->where('reserved_date', NULL);
				
			$this->db->update('service_reservations');
				
		}

		$this->db->trans_complete();

		return $this->db->trans_status();

	}

	function deleteCustomerBooking($id){


		// --- Modify delete function: 6/12/2012

		/* $this->db->where('customer_booking_id', $id);

		$this->db->delete('service_reservations');

		$this->db->where('id', $id);

		$this->db->delete('customer_bookings'); */

		$this->db->where('customer_booking_id', $id);
		$this->db->set('deleted', DELETED);
		$this->db->set('user_modified_id', get_user_id());
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
		$this->db->update('service_reservations');

		$this->db->where('id', $id);
		$this->db->set('deleted', DELETED);
		$this->db->set('user_modified_id', get_user_id());
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
		$this->db->update('customer_bookings');

		// --- End modify

	}

	function getUsers(){

		$this->db->select('id, username, status');

		//$this->db->where('status', STATUS_ACTIVE);

		$this->db->where('deleted !=', DELETED);

		$this->db->order_by('username', 'asc');

		$query = $this->db->get('users');

		return $query->result_array();
	}

	function getUser($user_id){

		$this->db->select('id, username, email');

		$this->db->where('id', $user_id);

		$this->db->where('status', STATUS_ACTIVE);

		$this->db->where('deleted !=', DELETED);

		$query = $this->db->get('users');

		$results = $query->result_array();

		if (count($results) > 0){
				
			return $results[0];
				
		} else {
				
			return '';
				
		}
	}

	function getCustomers(){

		$this->db->select('id, full_name');

		$this->db->where('deleted !=', DELETED);

		$this->db->order_by('full_name', 'asc');

		$query = $this->db->get('customers');

		$results = $query->result_array();

		foreach ($results as $key => $value){
			if (strlen($value['full_name']) > 15){
				$value['full_name'] = substr($value['full_name'], 0, 15);
			}
			$results[$key] = $value;
		}

		return $results;
	}

	function searchCustomer($search_criteria = '', $num = -1, $offset = 0)
	{
		$this->db->where('deleted !=', DELETED);
		if (isset($search_criteria['name']) && $search_criteria['name'] != ''){
				
			$where_query = "(";
				
			$where_query = $where_query. "full_name like '%".$search_criteria['name']."%' OR email like '%".$search_criteria['name']."%'"
					." OR phone like '%".$search_criteria['name']."%' OR fax like '%".$search_criteria['name']."%'"
							." OR city like '%".$search_criteria['name']."%'"
									." OR ip_address like '".$search_criteria['name']."%'";
				
			$where_query = $where_query . ")";
				
			$this->db->where($where_query);
		}

		$this->db->order_by('full_name', 'asc');

		if ($num == -1) {
			$query = $this->db->get('customers');
		} else {
			$query = $this->db->get('customers', $num, $offset);
		}

		$results = $query->result_array();
		foreach ($results as $key=>$customer) {
			$customer['date_modified'] = get_format_date($customer['date_modified'], DATE_TIME_MOD_FORMAT);
			$customer['user_modified'] = get_user_modified($customer['user_modified_id']);
			$results[$key] = $customer;
		}

		return $results;
	}

	function getNumCustomer($search_criteria = '')
	{
		$this->db->where('deleted !=', DELETED);
		if (isset($search_criteria['name']) && $search_criteria['name'] != ''){
			$where_query = "full_name like '%".$search_criteria['name']."%' OR email like '%".$search_criteria['name']."%'"
					." OR phone like '%".$search_criteria['name']."%' OR fax like '%".$search_criteria['name']."%'";
				
			$this->db->where($where_query);
		}

		return $this->db->count_all_results('customers');
	}

	function getCustomer($id){

		$this->db->where('id', $id);

		$query = $this->db->get('customers');

		$results = $query->result_array();

		if (count($results) > 0){
			return $results[0];
		} else {
			return '';
		}

	}


	function createCustomer($context){

		$title = $this->input->post('title');

		$full_name = $this->input->post('full_name');

		$email = $this->input->post('email');

		$phone = $this->input->post('phone');

		$fax = $this->input->post('fax');

		$country = $this->input->post('country');

		$city = $this->input->post('city');

		$this->db->set('title', $title);

		$this->db->set('full_name', $full_name);

		$this->db->set('email', $email);

		$this->db->set('phone', $phone);

		$this->db->set('fax', $fax);

		$this->db->set('country', $country);

		$this->db->set('city', $city);

		// generate password
		$pass = gen_md5_password();
		$this->db->set('password', $pass['encrypt']);

		$this->db->set('user_created_id', $context->current_user->id);
		$this->db->set('date_created', date(DB_DATE_TIME_FORMAT));
		$this->db->set('user_modified_id', $context->current_user->id);
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));

		$this->db->insert('customers');

		$id = $this->db->insert_id();

		return $id;
	}

	function updateCustomer($id, $context){

		$this->db->trans_start();

		$title = $this->input->post('title');

		$full_name = $this->input->post('full_name');

		$email = $this->input->post('email');

		$phone = $this->input->post('phone');

		$fax = $this->input->post('fax');

		$country = $this->input->post('country');

		$city = $this->input->post('city');

		$this->db->set('title', $title);

		$this->db->set('full_name', $full_name);

		$this->db->set('email', $email);

		$this->db->set('phone', $phone);

		$this->db->set('fax', $fax);

		$this->db->set('country', $country);

		$this->db->set('city', $city);

		$this->db->set('user_modified_id', $context->current_user->id);
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));

		$this->db->where('id', $id);

		$this->db->update('customers');

		$this->db->trans_complete();

		return $this->db->trans_status();

	}

	function deleteCustomer($id, $app_context){
		$this->db->where('id', $id);
		$this->db->set('user_modified_id', $app_context->current_user->id);
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
		$this->db->set('deleted', DELETED);
		$this->db->update('customers');
		//$this->db->delete('customers');

	}

	function _setSRQueryString($search_criteria, $str_query=''){

		// Eleminate deleted booking [6-12-2012]
		$str_query = $str_query. " WHERE sr.deleted != ". DELETED;

		if(isset($search_criteria['customer_booking_id'])){
				
			$str_query = $str_query." AND cb.id= ".$search_criteria['customer_booking_id'];

		}

		if (isset($search_criteria['reservation_status'])){
				
			$str_query = $str_query. " AND sr.reservation_status = ".$search_criteria['reservation_status'];
				
		}

		if (isset($search_criteria['user_id'])){
				
			$str_query = $str_query. " AND cb.user_id = ".$search_criteria['user_id'];
				
		}

		if (isset($search_criteria['destination_id'])){
				
			$str_query = $str_query. " AND sr.destination_id = ".$search_criteria['destination_id'];
				
		}

		if (isset($search_criteria['reservation_type'])){

			$str_query = $str_query. " AND sr.reservation_type = ".$search_criteria['reservation_type'];
				
		}

		if (isset($search_criteria['partner_id'])){
				
			$str_query = $str_query. " AND sr.partner_id = ".$search_criteria['partner_id'];
				
		}

		if (isset($search_criteria['country'])){
				
			$str_query = $str_query. " AND c.country = '".$search_criteria['country']."'";
				
		}

		if (isset($search_criteria['booking_type'])){
				
			if ($search_criteria['booking_type'] == 1){ // book seperate
				$str_query = $str_query. " AND sr.origin_id = 0";
			} else {
				$str_query = $str_query. " AND sr.origin_id > 0";
			}
				
		}

		if (isset($search_criteria['customer_id'])){
				
			$str_query = $str_query. " AND c.id = ".$search_criteria['customer_id'];
				
		}


		if (isset($search_criteria['booking_site'])){
				
			$str_query = $str_query. " AND cb.booking_site = ".$search_criteria['booking_site'];
				
		}

		if (isset($search_criteria['customer_type'])){
				
			$str_query = $str_query. " AND cb.customer_type = ".$search_criteria['customer_type'];
				
		}

		if (isset($search_criteria['request_type'])){
				
			$str_query = $str_query. " AND cb.request_type = ".$search_criteria['request_type'];
				
		}


		if (isset($search_criteria['source'])){
				
			$str_query = $str_query. " AND cb.booking_source_id = ".$search_criteria['source'];
				
		}

		if (isset($search_criteria['medium'])){
				
			$str_query = $str_query. " AND cb.medium = ".$search_criteria['medium'];
				
		}

		if (isset($search_criteria['keyword'])){
				
			$str_query = $str_query. " AND cb.keyword LIKE '%".$search_criteria['keyword']."%'";
				
		}

		if (isset($search_criteria['landing_page'])){
				
			$str_query = $str_query. " AND cb.landing_page LIKE '%".$search_criteria['landing_page']."%'";
				
		}

		if (isset($search_criteria['campaign'])){
				
			$str_query = $str_query. " AND cb.campaign_id = ".$search_criteria['campaign'];
				
		}

		if (isset($search_criteria['name'])){
				
			$str_query = $str_query." AND(";

			$str_query = $str_query. " c.full_name LIKE '%".$search_criteria['name']."%'";
				
			$str_query = $str_query. " OR c.email LIKE '%".$search_criteria['name']."%'";
				
			$str_query = $str_query. " OR u.username LIKE '%".$search_criteria['name']."%'";
				
			$str_query = $str_query. " OR sr.service_name LIKE '%".$search_criteria['name']."%'";
				
			$str_query = $str_query.")";
		}

		if (isset($search_criteria['arr_reservation_status'])){
				
			$str_status = "";
				
			foreach ($search_criteria['arr_reservation_status'] as $value){
				$str_status = $str_status .$value.",";
			}
				
			if (strlen($str_status) > 1){
				$str_status = substr($str_status, 0, strlen($str_status) - 1);
			}
				
			if ($str_status != ""){
				$str_status = "(" . $str_status. ")";
			}
				
			if ($str_status != ""){
					
				$str_query = $str_query." AND sr.reservation_status IN ".$str_status;
					
			}
		}

		if (isset($search_criteria['date_field'])){

			if (in_array(1, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND sr.start_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND sr.start_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(2, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND sr.end_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND sr.end_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(3, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND sr.1_payment_due >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND sr.1_payment_due <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(4, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND sr.1_payment_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND sr.1_payment_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(5, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND sr.2_payment_due >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND sr.2_payment_due <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(6, $search_criteria['date_field'])){
				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND sr.2_payment_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND sr.2_payment_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
			if (in_array(7, $search_criteria['date_field'])){

				if (isset($search_criteria['start_date'])){
						
					$str_query = $str_query. " AND cb.booking_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
						
				}

				if (isset($search_criteria['end_date'])){
					$str_query = $str_query. " AND cb.booking_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			}
				
		}

		if (isset($search_criteria['booking_status'])){
				
			$str_status = "";
				
			foreach ($search_criteria['booking_status'] as $value){
				$str_status = $str_status .$value.",";
			}
				
			if (strlen($str_status) > 1){
				$str_status = substr($str_status, 0, strlen($str_status) - 1);
			}
				
			if ($str_status != ""){
				$str_status = "(" . $str_status. ")";
			}
				
			if ($str_status != ""){
					
				$str_query = $str_query." AND cb.status IN ".$str_status;
					
			}
		}

		if (isset($search_criteria['multi_partners'])){
				
			$partner_ids = "";
				
			foreach ($search_criteria['multi_partners'] as $value){
				$partner_ids = $partner_ids .$value.",";
			}
				
			if (strlen($partner_ids) > 1){
				$partner_ids = substr($partner_ids, 0, strlen($partner_ids) - 1);
			}
				
			if ($partner_ids != ""){
				$partner_ids = "(" . $partner_ids. ")";
			}
				
			if ($partner_ids != ""){
					
				$str_query = $str_query." AND sr.partner_id IN ".$partner_ids;
					
			}
		}

		return $str_query;
	}

	function countTotalSRMoney($search_criteria = '', $current_user = ''){
		$str_query =  "SELECT sum(sr.net_price) net_price, sum(sr.selling_price) selling_price FROM service_reservations sr".
				" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id";

		$str_query = $this->_setSRQueryString($search_criteria, $str_query);

		if ($current_user != ''){
				
			if ($current_user->is_admin_name() || $current_user->is_accounting() || $current_user->is_sale_manager() || $current_user->is_developer_team()){
				// donothing
			} else {

				$share_permission = $current_user->share_permission;

				if (empty($share_permission)){

					$str_query = $str_query. " AND cb.user_id = ". $current_user->id;

				} else {
						
					$str_query = $str_query. " AND (cb.user_id = ". $current_user->id;
						
					foreach ($share_permission as $cb_id){

						$str_query = $str_query. " OR cb.user_id = ".$cb_id;

					}
						
					$str_query = $str_query. ')';
				}
			}
				
		}

		$query = $this->db->query($str_query);

		return $query->result_array();
	}

	function countRemainingPayment($search_criteria = ''){

		$str_query =  "SELECT sum(sr.1_payment) 1_payment, sum(sr.2_payment) 2_payment FROM service_reservations sr".
				" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id";

		$str_query = $this->_setSRQueryString($search_criteria, $str_query);

		$str_query = $str_query. " AND sr.reservation_status = 3"; // reserved

		$query = $this->db->query($str_query);

		$total1 = $query->result_array();


		$str_query =  "SELECT sum(sr.2_payment) 2_payment FROM service_reservations sr".
				" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id";

		$str_query = $this->_setSRQueryString($search_criteria, $str_query);

		$str_query = $str_query. " AND sr.reservation_status = 5"; // deposited

		$query = $this->db->query($str_query);

		$total2 = $query->result_array();

		return ($total1[0]['1_payment'] + $total1[0]['2_payment'] + $total2[0]['2_payment']);
	}

	/**
	 * Service Reservation
	 */
	function searchServiceReservation($search_criteria = '', $num = -1, $offset = 0)
	{
		$str_query = "SELECT sr.*, org.service_name as origin, u.username, c.full_name, c.title, c.id as customer_id, c.email, c.phone, c.country, c.city, p.name as partner_name,cb.user_id FROM service_reservations sr".
				" LEFT OUTER JOIN partners p ON p.id = sr.partner_id".
				" LEFT OUTER JOIN service_reservations org ON org.id = sr.origin_id".
				" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id";


		$str_query = $this->_setSRQueryString($search_criteria, $str_query);

		$str_query = $str_query. " ORDER BY " . $search_criteria['sort_column']." ".$search_criteria['sort_order'];

		$str_query = $str_query. ', sr.service_name asc';

		if ($num == -1) {
			//
		} else {

			$str_query = $str_query. " LIMIT ".$offset.", ".$num;
		}

		$query = $this->db->query($str_query);

		return $query->result_array();
	}

	function getNumServiceReservation($search_criteria = '')
	{
		$str_query = "SELECT sr.*, u.username FROM service_reservations sr".
				" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id";

		$str_query = $this->_setSRQueryString($search_criteria, $str_query);

		$query = $this->db->query($str_query);

		return count($query->result_array());
	}

	function getServiceReservation($id){
		
		$this->db->select('sr.*, c.full_name as customer_name, c.id as customer_id, cb.user_id, c.gender as title, c.email, c.phone, p.name as partner_name, d.name as des_name');
			
		$this->db->from('service_reservations sr');
		
		$this->db->join('partners p', 'p.id = sr.partner_id','left outer');
		
		$this->db->join('destinations d', 'd.id = sr.destination_id','left outer');
		
		$this->db->join('customer_bookings cb', 'cb.id = sr.customer_booking_id');

		$this->db->join('customers c', 'c.id = cb.customer_id');

		$this->db->where('sr.id', $id);

		$query = $this->db->get();

		$results = $query->result_array();

		if (count($results) > 0){
			
			$service_reservation = $results[0];
			
			return $service_reservation;
			
		} else {
			
			return '';
		}


	}


	function createServiceReservation($customer_booking_id){

		$reservation_type = $this->input->post('reservation_type');

		$origin_id = $this->input->post('origin');

		$service_name = $this->input->post('service_name');
		
		$flight_pnr = $this->input->post('flight_pnr');

		$service_id = $this->input->post('service_id');

		$partner_id = $this->input->post('partner');

		$start_date = $this->input->post('start_date');

		$end_date = $this->input->post('end_date');

		$name = $this->input->post('name');

		$net_price = format_rate_input($this->input->post('net_price'), 1);

		$selling_price = format_rate_input($this->input->post('selling_price'), 1);

		$reservation_status = $this->input->post('reservation_status');

		$payment_1 = format_rate_input($this->input->post('1_payment'), 1);

		$payment_due_1 = $this->input->post('1_payment_due');

		$payment_date_1 = $this->input->post('1_payment_date');

		$payment_2 = format_rate_input($this->input->post('2_payment'), 1);

		$payment_due_2 = $this->input->post('2_payment_due');

		$payment_date_2 = $this->input->post('2_payment_date');

		$description = $this->input->post('description');

		$detail_reservation = $this->input->post('detail_reservation');

		$cabin_booked = $this->input->post('cabin_booked');

		$cabin_incentive = $this->input->post('cabin_incentive');

		$reserved_date = $this->input->post('reserved_date');

		$reviewed = $this->input->post('reviewed');

		$destination = $this->input->post('destination');

		$type_of_visa = $this->input->post('type_of_visa');

		$processing_time = $this->input->post('processing_time');


		$this->db->set('customer_booking_id', $customer_booking_id);

		$this->db->set('origin_id', $origin_id);

		$this->db->set('reservation_type', $reservation_type);

		$this->db->set('service_name', $service_name);
		
		$this->db->set('flight_pnr', $flight_pnr);

		$this->db->set('service_id', $service_id);

		$this->db->set('start_date',  bpv_format_date($start_date, DB_DATE_FORMAT));

		$this->db->set('end_date', bpv_format_date($end_date, DB_DATE_FORMAT));

		$this->db->set('name', $name);

		$this->db->set('net_price', $net_price);

		$this->db->set('selling_price', $selling_price);

		$this->db->set('profit', $net_price - $selling_price);

		$this->db->set('reservation_status', $reservation_status);

		$this->db->set('1_payment', $payment_1);

		if ($payment_due_1 != ''){

			$this->db->set('1_payment_due', bpv_format_date($payment_due_1, DB_DATE_FORMAT));

		}

		if ($payment_date_1 != ''){

			$this->db->set('1_payment_date', bpv_format_date($payment_date_1, DB_DATE_FORMAT));

		}

		$this->db->set('2_payment', $payment_2);

		if ($payment_due_2 != ''){

			$this->db->set('2_payment_due', bpv_format_date($payment_due_2, DB_DATE_FORMAT));

		}

		if ($payment_date_2 != ''){

			$this->db->set('2_payment_date', bpv_format_date($payment_date_2, DB_DATE_FORMAT));

		}

		if ($reserved_date != ''){
				
			$this->db->set('reserved_date', bpv_format_date($reserved_date, DB_DATE_FORMAT));
				
		} else {
				
			$cb = $this->getCustomerBooking($customer_booking_id);
				
			if ($cb['booking_date'] != ''){
					
				$reserved_date_nr = $this->config->item('reserved_date_nr');

				$reserved_date = strtotime($cb['booking_date'].' +'.$reserved_date_nr.' day');

				$reserved_date = date(DB_DATE_FORMAT, $reserved_date);

				$this->db->set('reserved_date', $reserved_date);
					
			}
		}


		$this->db->set('description', $description);

		$detail_reservation = $this->input->post('detail_reservation');

		$this->db->set('detail_reservation', $detail_reservation);

		$this->db->set('partner_id', $partner_id);

		$this->db->set('cabin_incentive', $cabin_incentive);

		$this->db->set('cabin_booked', $cabin_booked);

		$this->db->set('reviewed', $reviewed);

		$this->db->set('type_of_visa', $type_of_visa);

		$this->db->set('processing_time', $processing_time);

		$this->db->set('destination_id', $destination);

		$this->db->set('user_created_id', get_user_id());
		$this->db->set('date_created', date(DB_DATE_TIME_FORMAT));
		$this->db->set('user_modified_id',  get_user_id());
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));



		$this->db->insert('service_reservations');

		$id = $this->db->insert_id();

		$this->updatePrice($customer_booking_id);

		$this->updateDate($customer_booking_id);


		return $id;
	}

	function updateServiceReservation($id){

		$this->db->trans_start();

		$reservation_type = $this->input->post('reservation_type');

		$origin_id = $this->input->post('origin');

		$service_name = $this->input->post('service_name');
		
		$flight_pnr = $this->input->post('flight_pnr');

		$service_id = $this->input->post('service_id');

		$partner_id = $this->input->post('partner');

		$start_date = $this->input->post('start_date');

		$end_date = $this->input->post('end_date');

		$name = $this->input->post('name');

		$net_price = format_rate_input($this->input->post('net_price'), 1);

		$selling_price = format_rate_input($this->input->post('selling_price'), 1);

		$reservation_status = $this->input->post('reservation_status');

		$description = $this->input->post('description');


		$cabin_booked = $this->input->post('cabin_booked');

		$cabin_incentive = $this->input->post('cabin_incentive');

		$reserved_date = $this->input->post('reserved_date');

		$reviewed = $this->input->post('reviewed');

		$destination = $this->input->post('destination');

		$type_of_visa = $this->input->post('type_of_visa');

		$processing_time = $this->input->post('processing_time');
		
		// flight data
		
		$flight_code = $this->input->post('flight_code');
		$departure_time = $this->input->post('departure_time');
		$arrival_time = $this->input->post('arrival_time');
		$fare_rule_short = $this->input->post('fare_rule_short');
		
		$this->db->set('flight_code', $flight_code);
		$this->db->set('departure_time', $departure_time);
		$this->db->set('arrival_time', $arrival_time);
		$this->db->set('fare_rule_short', $fare_rule_short);


		$this->db->set('reservation_type', $reservation_type);

		$this->db->set('origin_id', $origin_id);

		$this->db->set('service_name', $service_name);
		
		$this->db->set('flight_pnr', $flight_pnr);

		$this->db->set('service_id', $service_id);

		$this->db->set('start_date',  bpv_format_date($start_date, DB_DATE_FORMAT));

		$this->db->set('end_date', bpv_format_date($end_date, DB_DATE_FORMAT));

		$this->db->set('name', $name);

		$this->db->set('net_price', $net_price);

		$this->db->set('selling_price', $selling_price);

		$this->db->set('profit', $net_price - $selling_price);

		$this->db->set('reservation_status', $reservation_status);

		$payment_1 = format_rate_input($this->input->post('1_payment'), 1);

		$payment_due_1 = $this->input->post('1_payment_due');

		$payment_date_1 = $this->input->post('1_payment_date');

		$payment_2 = format_rate_input($this->input->post('2_payment'), 1);

		$payment_due_2 = $this->input->post('2_payment_due');

		$payment_date_2 = $this->input->post('2_payment_date');

		$this->db->set('1_payment', $payment_1);

		if ($payment_due_1 != ''){

			$this->db->set('1_payment_due', bpv_format_date($payment_due_1, DB_DATE_FORMAT));

		} else {
			$this->db->set('1_payment_due', NULL);
		}

		if ($payment_date_1 != ''){

			$this->db->set('1_payment_date', bpv_format_date($payment_date_1, DB_DATE_FORMAT));

		} else {
			$this->db->set('1_payment_date', NULL);
		}

		$this->db->set('2_payment', $payment_2);

		if ($payment_due_2 != ''){

			$this->db->set('2_payment_due', bpv_format_date($payment_due_2, DB_DATE_FORMAT));

		} else {
			$this->db->set('2_payment_due', NULL);
		}

		if ($payment_date_2 != ''){

			$this->db->set('2_payment_date', bpv_format_date($payment_date_2, DB_DATE_FORMAT));

		} else {
			$this->db->set('2_payment_date', NULL);
		}


		if ($reserved_date != ''){
				
			$this->db->set('reserved_date', bpv_format_date($reserved_date, DB_DATE_FORMAT));
				
		} else {
				
			$this->db->set('reserved_date', NULL);
		}

		$this->db->set('description', $description);

		$detail_reservation = $this->input->post('detail_reservation');

		$this->db->set('detail_reservation', $detail_reservation);

		if (!empty($partner_id)){

			$this->db->set('partner_id', $partner_id);

		}

		$this->db->set('cabin_incentive', $cabin_incentive);

		$this->db->set('cabin_booked', $cabin_booked);

		$this->db->set('reviewed', $reviewed);

		$this->db->set('type_of_visa', $type_of_visa);

		$this->db->set('processing_time', $processing_time);

		$this->db->set('destination_id', $destination);

		$this->db->set('user_modified_id', get_user_id());

		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));

		$this->db->where('id', $id);

		$this->db->update('service_reservations');

		$sr = $this->getServiceReservation($id);

		$this->updatePrice($sr['customer_booking_id']);

		$this->updateDate($sr['customer_booking_id']);


		$this->db->trans_complete();

		return $this->db->trans_status();

	}

	function updatePrice($cb_id){

		$cb = $this->getCustomerBooking($cb_id);

		$actual_selling = $this->_calculateActualSelling($cb['onepay'], $cb['cash'], $cb['pos'], $cb['payment_method']);

		$this->db->select_sum('net_price', 'net');

		$this->db->where('deleted !=', DELETED);

		$this->db->where('customer_booking_id', $cb_id);

		$query = $this->db->get('service_reservations');

		$results = $query->result_array();

		$net = count($results) > 0 ? $results[0]['net'] : 0;


		$this->db->select_sum('selling_price', 'sel');

		$this->db->where('deleted !=', DELETED);

		$this->db->where('customer_booking_id', $cb_id);

		$query = $this->db->get('service_reservations');

		$results = $query->result_array();

		$sel = count($results) > 0 ? $results[0]['sel'] : 0;

		$this->db->set('net_price', $net);

		$this->db->set('selling_price', $sel);

		$this->db->set('profit', $sel - $net);

		$this->db->set('approve_status', 0);

		$this->db->set('actual_profit', $actual_selling - $net);

		$this->db->where('id', $cb_id);

		$this->db->update('customer_bookings');

	}

	function updateDate($cb_id){

		$this->db->select_min('start_date');

		$this->db->where('deleted !=', DELETED);

		$this->db->where('customer_booking_id', $cb_id);

		$query = $this->db->get('service_reservations');

		$results = $query->result_array();

		$start_date = count($results) > 0 ? $results[0]['start_date'] : "";


		$this->db->select_max('end_date');

		$this->db->where('deleted !=', DELETED);

		$this->db->where('customer_booking_id', $cb_id);

		$query = $this->db->get('service_reservations');

		$results = $query->result_array();

		$end_date = count($results) > 0 ? $results[0]['end_date'] : "";

		if ($start_date != ""){
			$this->db->set('start_date', $start_date);
		}

		if ($end_date != ""){
			$this->db->set('end_date', $end_date);
		}

		$this->db->where('id', $cb_id);

		$this->db->update('customer_bookings');

	}

	function deleteServiceReservation($id){

		$sr = $this->getServiceReservation($id);

		// --- Modify delete function: 6/12/2012

		/* $this->db->where('id', $id);

		$this->db->delete('service_reservations'); */

		$this->db->where('id', $id);
		$this->db->set('deleted', DELETED);
		$this->db->set('user_modified_id', get_user_id());
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
		$this->db->update('service_reservations');

		// --- End modify

		$this->updatePrice($sr['customer_booking_id']);

		$this->updateDate($sr['customer_booking_id']);

	}
	
	function search_partners($str_query){
		
		$str_query = urldecode($str_query);
		
		$this->db->select('id, name');
	
		$this->db->where('deleted !=', DELETED);
		
		$this->db->like('name', $str_query, 'both');
	
		$this->db->order_by('name', 'asc');
	
		$query = $this->db->get('partners');
	
		return $query->result_array();
	}

	function search_destinations($str_query){
		
		$str_query = urldecode($str_query);
		
		$this->db->select('d.id, d.name, p.name as parent_name');
		
		$this->db->from('destinations d');
	
		$this->db->join('destinations p', 'p.id = d.parent_id');
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->like('d.name', $str_query, 'both');
	
		$this->db->order_by('d.name', 'asc');
	
		$query = $this->db->get();
	
		return $query->result_array();
	}

	function search_hotels($str_query){
		
		$str_query = urldecode($str_query);
		
		$this->db->select('h.id, h.name, d.id as des_id, d.name as des_name, p.id as partner_id, p.name as partner_name');
		
		$this->db->from('hotels h');
		
		$this->db->join('destinations d','d.id = h.destination_id');
		
		$this->db->join('partners p','p.id = h.partner_id');
	
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->like('h.name', $str_query, 'both');
	
		$this->db->order_by('h.name', 'asc');
	
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	function search_customers($str_query){
		
		$str_query = urldecode($str_query);
		
		$this->db->select('id, full_name, email, phone');
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->like('full_name', $str_query, 'both');
		
		$this->db->or_like('email', $str_query, 'both');
		
		$this->db->or_like('phone', $str_query, 'both');
		
		$this->db->order_by('full_name', 'asc');
		
		$query = $this->db->get('customers');
		
		return $query->result_array();
		
	}



	function getCustomerServiceReservations($customer_booking_id){

		$this->db->where('customer_booking_id', $customer_booking_id);

		$this->db->where('deleted !=', DELETED);

		$this->db->order_by('start_date', 'asc');

		$query = $this->db->get('service_reservations');

		return $query->result_array();
	}

	function getAllCars(){

		$this->db->where('deleted !=', DELETED);

		$this->db->where('status', STATUS_ACTIVE);

		$this->db->order_by('nr_seat', 'asc');

		$query = $this->db->get('transfer_services');

		return $query->result_array();
	}



	/*
	 * For creating old customer request
	*/

	function create_old_cb_data(){

		$this->create_old_customer();

		$this->create_old_contact();
	}

	function create_old_customer(){

		$all_tour_bookings = $this->getAllTourBookingDetails();

		foreach ($all_tour_bookings as $key => $value) {
				
			$current_customer_booking = $this->getCustomerBookingByEmail($value['email']);
				
			$start_date = $value['departure_date'];
				
			$end_date = strtotime($start_date . " +". ($value['duration'] -1). " day");

			$end_date = date('Y-m-d', $end_date);
				
			$customer_booking_id = 0;
				
			if ($current_customer_booking != ''){

				if ($current_customer_booking['start_date'] >= $start_date){
						
					$current_customer_booking['start_date'] = $start_date;
						
				}


				if ($current_customer_booking['end_date'] <= $end_date){
						
					$current_customer_booking['end_date'] = $end_date;
						
				}
				$this->db->where('id', $current_customer_booking['id']);

				$this->db->update('customer_bookings', $current_customer_booking);

				$customer_booking_id = $current_customer_booking['id'];

			} else {

				$customer_booking = array();

				$customer_booking['customer_id'] = $value['customer_id'];

				$admin = $this->getAdmin();

				if ($admin != ''){
					$customer_booking['user_id'] = $admin['id'];
				}

				$customer_booking['request_date'] = $value['booking_date'];

				$customer_booking['start_date'] = $start_date;

				$customer_booking['end_date'] = $end_date;

				$customer_booking['selling_price'] = $value['total_payment'];

				$this->db->insert('customer_bookings', $customer_booking);

				$customer_booking_id = $this->db->insert_id();
			}
				
			$service_reservation = array();
				
			$service_reservation['customer_booking_id'] = $customer_booking_id;
				
			$service_reservation['reservation_type'] = 4; // tour
				
			$config_services = $this->config->item('class_services');
				
			$service_reservation['name'] = '';
				
			$service_reservation['service_id'] = $value['id'];
				
			$service_reservation['service_name'] = $value['name'];
				
			$service_reservation['partner_id'] = $value['partner_id'];
				
			$service_reservation['partner_name'] = $value['partner_name'];
				
			$service_reservation['start_date'] = $start_date;
				
			$service_reservation['end_date'] = $end_date;
				
			$service_reservation['selling_price'] = $value['total_payment'];
				
			$service_reservation['description'] = lang($config_services[$value['class_service']]);
				
			if ($value['special_requests'] != ''){

				$special_request = $value['special_requests'];

				$pos1 = strpos($special_request, '<br>') + 4;

				$special_request = substr($special_request, $pos1, strlen($special_request) - 1);

				$service_reservation['description'] = $service_reservation['description']."\n".$special_request;
			}
				
			$this->db->insert('service_reservations', $service_reservation);
				
			$this->updatePrice($customer_booking_id);
				
		}
	}

	function create_old_contact(){

		$contacts = $this->getAllContact();

		foreach ($contacts as $key=>$value){
				
			$customer_id = $this->create_or_update_customer($value);
				
			$current_customer_booking = $this->getCustomerBookingByEmail($value['email']);

			if ($current_customer_booking == ''){

				$customer_booking = array();

				$customer_booking['customer_id'] = $customer_id;

				$admin = $this->getAdmin();

				if ($admin != ''){
					$customer_booking['user_id'] = $admin['id'];
				}

				$customer_booking['request_date'] = $value['date_issue'];

				$customer_booking['start_date'] = $value['date_issue'];

				$customer_booking['end_date'] = $value['date_issue'];

				$customer_booking['selling_price'] = 0;

				$customer_booking['description'] = $value['message'];

				$this->db->insert('customer_bookings', $customer_booking);

			} else {
				if ($current_customer_booking['description'] != ''){
					$current_customer_booking['description'] = $current_customer_booking['description']."\n".$value['message'];
				} else {
					$current_customer_booking['description'] = $value['message'];
				}
				$this->db->where('id', $current_customer_booking['id']);

				$this->db->update('customer_bookings', $current_customer_booking);
			}
				
		}

	}

	function getAllTourBookingDetails(){

		$this->db->select('tb.customer_id, c.email, tb.booking_date, tb.departure_date, tb.special_requests, tb.class_service, tb.total_payment, t.id, t.name, t.duration, p.id partner_id, p.name partner_name');

		$this->db->from('bookings tb');

		$this->db->join('tours t', 't.id = tb.tour_id');

		$this->db->join('partners p', 'p.id = t.partner_id');

		$this->db->join('customers c', 'c.id = tb.customer_id');

		$query = $this->db->get();

		$results = $query->result_array();

		return $results;
	}

	function getCustomerBookingByEmail($email){

		$this->db->select('cb.*');

		$this->db->from('customer_bookings cb');

		$this->db->join('customers c', 'c.id = cb.customer_id');

		$this->db->where('c.email', $email);

		$this->db->where('cb.deleted !=', DELETED);

		$query = $this->db->get();

		$results = $query->result_array();

		if (count($results) > 0){
			return $results[0];
		} else {
			return '';
		}
	}

	function getAdmin(){

		$this->db->where('is_admin', 1);

		$this->db->where('status', STATUS_ACTIVE);

		$this->db->where('deleted !=', DELETED);

		$query = $this->db->get('users');

		$results = $query->result_array();

		if (count($results) > 0){
			return $results[0];
		} else {
			return '';
		}
	}

	function getAllContact(){

		$query = $this->db->get('contacts');

		$results = $query->result_array();

		return $results;
	}

	function create_or_update_customer($contact){

		$this->db->where('email', $contact['email']);

		$nr = $this->db->count_all_results('customers');

		$cus_id = 0;

		$cus['title'] = $contact['title'];

		$cus['full_name'] = $contact['full_name'];

		$cus['email'] = $contact['email'];

		$cus['phone'] = $contact['phone'];

		$cus['country'] = $contact['country'];

		$cus['city'] = $contact['city'];

		$cus['ip_address'] = $contact['ip_address'];

		if ($nr == 0){
				
			$this->db->insert('customers', $cus);
				
			$cus_id = $this->db->insert_id();

		} else {
				
			$this->db->where('email', $contact['email']);
				
			$this->db->update('customers', $cus);
				
				
			$this->db->where('email', $cus['email']);
				
			$query = $this->db->get('customers');

			$results = $query->result_array();

			$cus_id = $results[0]['id'];
				
		}

		return $cus_id;
	}

	function _getCurrentDate(){

		date_default_timezone_set("Asia/Saigon");

		return date('Y-m-d', time());
	}

	function get_cruise_id_from_tour($tour_id){

		$this->db->where('id', $tour_id);
			
		$query = $this->db->get('tours');

		$results = $query->result_array();

		if (count($results) > 0){
			return $results[0]['cruise_id'];
		} else {
			return 0;
		}
	}

	function get_group($cruise_id, $start_date){

		$this->db->where('cruise_id', $cruise_id);

		$this->db->where('start_date <=', $start_date);

		$this->db->where('end_date >=', $start_date);
			
		$query = $this->db->get('groups');

		$results = $query->result_array();

		if (count($results) > 0){
			return $results[0];
		} else {
			return false;
		}
	}

	function update_group_info($group_id){

		$str_query = "SELECT sr.pax_booked, sr.cabin_booked, t.duration FROM service_reservations sr ".
				" INNER JOIN tours t ON t.id = sr.service_id ".
				" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id ".
				" AND sr.reservation_type = 1 ". // cruise tour booking
				" AND (cb.status = 3 OR cb.status = 4 OR cb.status = 6) ". // deposit or full paid or close win
				" AND sr.deleted = 0 ".
				" AND sr.group_id = ".$group_id;

		$query = $this->db->query($str_query);

		$results = $query->result_array();

		$total_pax_booked = 0;

		$total_room_night = 0;

		foreach ($results as $value){
				
			$total_pax_booked += $value['pax_booked'];
				
			$total_room_night += $value['cabin_booked'] * $value['duration'];
		}


		$this->db->set('pax_booked', $total_pax_booked);

		$this->db->set('room_night', $total_room_night);

		$this->db->where('id', $group_id);

		$this->db->update('groups');


		$this->db->where('id', $group_id);

		$query = $this->db->get('groups');

		$results = $query->result_array();

		$group = $results[0];



		$per = ($group['pax_booked'] * 100.0)/$group['pax_best_price'];

		$this->db->set('per_best_price', $per);

		$this->db->where('id', $group_id);

		$this->db->update('groups');
	}

	function updatePaxBooked($customer_booking_id, $groups=null) {

		// get all group id of this customer booking
		if($groups == null) {
			$groups = $this->getGroupByBooking($customer_booking_id);
		}

		// update pax book each group
		foreach ($groups as $group_id) {
			$this->update_group_info($group_id);
		}

		return true;
	}

	// get all group id of this customer booking
	function getGroupByBooking($customer_booking_id) {

		$this->db->where('customer_booking_id', $customer_booking_id);

		$this->db->where('reservation_type', 1); // type: cruise

		$this->db->where('group_id !=', 0); // has group

		$queryService = $this->db->get('service_reservations');

		$service_reservations = $queryService->result_array();

		$groups = array();

		foreach ($service_reservations as $service) {
				
			$group_id = $service['group_id'];
				
			array_push($groups, $group_id);
		}

		return $groups;
	}

	function get_service_reservation_email($id){

		$this->db->select('sr.*, cb.adults, cb.children, cb.infants, c.title, c.full_name, c.country, cb.special_request, u.signature, u.email as sale_email, u.email_password, u.sale_name, p.email1 as email_partner');

		$this->db->from('service_reservations as sr');

		$this->db->join('customer_bookings as cb', 'cb.id = sr.customer_booking_id');

		$this->db->join('customers as c', 'c.id = cb.customer_id');

		$this->db->join('users as u', 'u.id = cb.user_id');

		$this->db->join('partners as p', 'p.id = sr.partner_id', 'left outer');

		$this->db->where('sr.id', $id);

		$query = $this->db->get();

		$results = $query->result_array();

		if(count($results) > 0){
				
			$sr = $results[0];
				
			$this->db->where('service_reservation_id', $id);
				
			$this->db->order_by('id', 'asc');

			$query = $this->db->get('visa_users');
				
			$visa_users = $query->result_array();
				
			$sr['visa_users'] = $visa_users;
				
			return $sr;
				
		} else {
				
			return false;
				
		}
	}

	function get_all_service_reservation_of_partner($customer_booking_id, $partner_id, $type = '', $status = ''){

		$this->db->select('sr.*, cb.adults, cb.children, cb.infants, c.title, c.full_name, c.country, u.signature, u.email as sale_email, u.sale_name, u.email_password, p.email1 as email_partner');

		$this->db->from('service_reservations as sr');

		$this->db->join('customer_bookings as cb', 'cb.id = sr.customer_booking_id');

		$this->db->join('customers as c', 'c.id = cb.customer_id');

		$this->db->join('users as u', 'u.id = cb.user_id');

		$this->db->join('partners as p', 'p.id = sr.partner_id', 'left outer');

		$this->db->where('sr.deleted !=', DELETED);

		if ($status != ''){

			$this->db->where('sr.reservation_status', $status);

		} else {
				
			$this->db->where('sr.reservation_status !=', RESERVATION_NEW);
		}

		$this->db->where('cb.id', $customer_booking_id);

		$this->db->where('p.id !=', 1); // best price vietnam

		if ($type != ''){
			$this->db->where('sr.reservation_type', $type);
		}

		$this->db->where('p.id', $partner_id);

		$this->db->order_by('sr.start_date','asc');

		$query = $this->db->get();

		$results = $query->result_array();


		foreach ($results as $key=>$value){
				
			$value['email'] = $this->get_email($value['email_id']);
				
			$results[$key] = $value;
				
		}

		return $results;

	}

	function get_related_services($customer_booking_id, $service_reservation_id, $partner_id){

		$this->db->where('customer_booking_id', $customer_booking_id);

		$this->db->where('id !=', $service_reservation_id);

		$this->db->where('partner_id', $partner_id);

		$this->db->where('partner_id !=', 1); // best price vietnam

		$this->db->where('reservation_status', 1); // new

		$this->db->where('deleted !=', DELETED);

		$query = $this->db->get('service_reservations');

		$results = $query->result_array();

		return $results;
	}

	function get_tours_of_booking($customer_booking_id){

		$this->db->where('customer_booking_id', $customer_booking_id);

		$this->db->where('deleted !=', DELETED);

		$this->db->where_in('reservation_type', array(1,4)); // cruise tour or land tour


		$this->db->order_by('start_date', 'asc'); // new

		$query = $this->db->get('service_reservations');

		$results = $query->result_array();

		return $results;
	}

	function get_email($id){

		$this->db->where('id', $id);

		$query = $this->db->get('emails');

		$results = $query->result_array();

		if (count($results) > 0){
				
			$email = $results[0];
				
			$this->db->where('email_id', $id);
				
			$this->db->order_by('id', 'asc');

			$query = $this->db->get('visa_users');
				
			$visa_users = $query->result_array();
				
			$email['visa_users'] = $visa_users;
				
			return $email;
				
		} else {
				
			return false;
				
		}
	}

	function create_email($email){

		// create email
		$this->db->set('type', $email['type']);

		$this->db->set('send_to', $email['send_to']);

		$this->db->set('subject', $email['subject']);

		$this->db->set('dear', $email['dear']);

		$this->db->set('request', $email['request']);

		$this->db->set('special_note', $email['special_note']);

		$this->db->set('signature', $email['signature']);

		$this->db->set('send_date', date(DB_DATE_TIME_FORMAT));

		if (isset($email['tour_name'])){
				
			$this->db->set('tour_name', $email['tour_name']);
		}

		if (isset($email['guest_name'])){
				
			$this->db->set('guest_name', $email['guest_name']);
		}

		if (isset($email['guest_number'])){
				
			$this->db->set('guest_number', $email['guest_number']);
		}

		if (isset($email['start_date']) && $email['start_date'] != ''){
				
			$this->db->set('start_date', bpv_format_date($email['start_date'], DB_DATE_FORMAT));
		}

		if (isset($email['end_date']) && $email['end_date'] != ''){
				
			$this->db->set('end_date', bpv_format_date($email['end_date'], DB_DATE_FORMAT));
		}

		if (isset($email['services'])){
				
			$this->db->set('services', $email['services']);
		}


		if (isset($email['total_price'])){
				
			$this->db->set('total_price', $email['total_price']);
		}

		if (isset($email['guest_information'])){
				
			$this->db->set('guest_information', $email['guest_information']);
		}

		if (isset($email['pick_up'])){
				
			$this->db->set('pick_up', $email['pick_up']);
		}
		/*
		 if (isset($email['payment'])){
			
		$this->db->set('payment', $email['payment']);
		}*/

		if (isset($email['special_request'])){
				
			$this->db->set('special_request', $email['special_request']);
		}

		if (isset($email['route'])){
				
			$this->db->set('route', $email['route']);
		}

		if (isset($email['car'])){
				
			$this->db->set('car', $email['car']);
		}

		if (isset($email['flight_name'])){
				
			$this->db->set('flight_name', $email['flight_name']);
		}

		if (isset($email['drop_off'])){
				
			$this->db->set('drop_off', $email['drop_off']);
		}

		if (isset($email['night_number'])){
				
			$this->db->set('night_number', $email['night_number']);
		}

		if (isset($email['room_rate'])){
				
			$this->db->set('room_rate', $email['room_rate']);
		}

		if (isset($email['pick_up_hour'])){
				
			$this->db->set('pick_up_hour', $email['pick_up_hour']);
		}

		if (isset($email['pick_up_minute'])){
				
			$this->db->set('pick_up_minute', $email['pick_up_minute']);
		}

		if (isset($email['drop_off_hour'])){
				
			$this->db->set('drop_off_hour', $email['drop_off_hour']);
		}

		if (isset($email['drop_off_minute'])){
				
			$this->db->set('drop_off_minute', $email['drop_off_minute']);
		}

		if (isset($email['type_of_visa'])){
				
			$this->db->set('type_of_visa', $email['type_of_visa']);
		}

		if (isset($email['processing_time'])){
				
			$this->db->set('processing_time', $email['processing_time']);
		}

		// for deposit email

		if (isset($email['term_cond'])){
				
			$this->db->set('term_cond', $email['term_cond']);
		}

		if (isset($email['deposit'])){
				
			$this->db->set('deposit', $email['deposit']);
		}

		if (isset($email['final_payment'])){
				
			$this->db->set('final_payment', $email['final_payment']);
		}

		if (isset($email['payment_link'])){
				
			$this->db->set('payment_link', $email['payment_link']);
		}

		if (isset($email['payment_suggestion'])){
				
			$this->db->set('payment_suggestion', $email['payment_suggestion']);
		}

		$this->db->insert('emails');

		$id = $this->db->insert_id();

		if ($id != null){
				
			if(isset($email['service_reservation_id'])){
				$ids = array();

				$ids[] = $email['service_reservation_id'];

				if (isset($email['related_services'])){
						
					foreach ($email['related_services'] as $value){

						$ids[] = $value['id'];
					}
						
				}

				$this->db->set('email_id', $id);

				$this->db->set('reservation_status', 2);//block or waiting

				$this->db->where_in('id', $ids);

				$this->db->update('service_reservations');
			}
				
			if (isset($email['visa_users'])){

				$this->db->where('service_reservation_id', $email['service_reservation_id']);

				$this->db->delete('visa_users');
					
				foreach ($email['visa_users'] as $user){
						
					$user['email_id'] = $id;
						
					$user['service_reservation_id'] = $email['service_reservation_id'];
						
					$user['birth_day'] = bpv_format_date($user['birth_day'], DB_DATE_FORMAT);
						
					//$user['passport_expiry'] = bpv_format_date($user['passport_expiry'], DB_DATE_FORMAT);
						
					if (!empty($user['passport_expiry'])){
							
						$user['passport_expiry'] = bpv_format_date($user['passport_expiry'], DB_DATE_FORMAT);
							
					} else {

						$user['passport_expiry'] = NULL;
					}
						
					$this->db->insert('visa_users',$user);
				}

			}
				
			if(isset($email['cb_id'])){

				$this->db->set('email_id', $id);

				$this->db->where('id', $email['cb_id']);

				$this->db->update('customer_bookings');


				$this->db->set('status', BOOKING_PENDING);

				$this->db->where('id', $email['cb_id']);

				$this->db->where('status', BOOKING_NEW);

				$this->db->update('customer_bookings');

			}
		}

		return $id;
	}

	function update_email($email){

		$this->db->trans_start();

		// create email
		//$this->db->set('type', $email['type']);

		$this->db->set('send_to', $email['send_to']);

		$this->db->set('subject', $email['subject']);

		$this->db->set('dear', $email['dear']);

		$this->db->set('request', $email['request']);

		$this->db->set('special_note', $email['special_note']);

		$this->db->set('signature', $email['signature']);
			
		$this->db->set('send_date', date(DB_DATE_TIME_FORMAT));

		if (isset($email['tour_name'])){
				
			$this->db->set('tour_name', $email['tour_name']);
		}

		if (isset($email['guest_name'])){
				
			$this->db->set('guest_name', $email['guest_name']);
		}

		if (isset($email['guest_number'])){
				
			$this->db->set('guest_number', $email['guest_number']);
		}

		if (isset($email['start_date']) && $email['start_date'] != ''){
				
			$this->db->set('start_date', bpv_format_date($email['start_date'], DB_DATE_FORMAT));
		}

		if (isset($email['end_date']) && $email['end_date'] != ''){
				
			$this->db->set('end_date', bpv_format_date($email['end_date'], DB_DATE_FORMAT));
		} else {
			$this->db->set('end_date', null);
		}

		if (isset($email['services'])){
				
			$this->db->set('services', $email['services']);
		}


		if (isset($email['total_price'])){
				
			$this->db->set('total_price', $email['total_price']);
		}

		if (isset($email['guest_information'])){
				
			$this->db->set('guest_information', $email['guest_information']);
		}

		if (isset($email['pick_up'])){
				
			$this->db->set('pick_up', $email['pick_up']);
		}

		/*if (isset($email['payment'])){
		 	
		$this->db->set('payment', $email['payment']);
		}*/

		if (isset($email['special_request'])){
				
			$this->db->set('special_request', $email['special_request']);
		}

		if (isset($email['route'])){
				
			$this->db->set('route', $email['route']);
		}

		if (isset($email['car'])){
				
			$this->db->set('car', $email['car']);
		}

		if (isset($email['flight_name'])){
				
			$this->db->set('flight_name', $email['flight_name']);
		}

		if (isset($email['drop_off'])){
				
			$this->db->set('drop_off', $email['drop_off']);
		}

		if (isset($email['night_number'])){
				
			$this->db->set('night_number', $email['night_number']);
		}

		if (isset($email['room_rate'])){
				
			$this->db->set('room_rate', $email['room_rate']);
		}

		if (isset($email['pick_up_hour'])){
				
			$this->db->set('pick_up_hour', $email['pick_up_hour']);
		}

		if (isset($email['pick_up_minute'])){
				
			$this->db->set('pick_up_minute', $email['pick_up_minute']);
		}

		if (isset($email['drop_off_hour'])){
				
			$this->db->set('drop_off_hour', $email['drop_off_hour']);
		}

		if (isset($email['drop_off_minute'])){
				
			$this->db->set('drop_off_minute', $email['drop_off_minute']);
		}

		if (isset($email['type_of_visa'])){
				
			$this->db->set('type_of_visa', $email['type_of_visa']);
		}

		if (isset($email['processing_time'])){
				
			$this->db->set('processing_time', $email['processing_time']);
		}

		// for deposit email

		if (isset($email['term_cond'])){
				
			$this->db->set('term_cond', $email['term_cond']);
		}

		if (isset($email['deposit'])){
				
			$this->db->set('deposit', $email['deposit']);
		}

		if (isset($email['final_payment'])){
				
			$this->db->set('final_payment', $email['final_payment']);
		}

		if (isset($email['payment_link'])){
				
			$this->db->set('payment_link', $email['payment_link']);
		}

		if (isset($email['payment_suggestion'])){
				
			$this->db->set('payment_suggestion', $email['payment_suggestion']);
		}


		$this->db->where('id', $email['id']);

		$this->db->update('emails');

		if (isset($email['visa_users'])){
				
			$this->db->where('email_id', $email['id']);
				
			$this->db->delete('visa_users');
				
			$this->db->where('service_reservation_id', $email['service_reservation_id']);
				
			$this->db->delete('visa_users');
				
			foreach ($email['visa_users'] as $user){

				$user['email_id'] = $email['id'];

				$user['service_reservation_id'] = $email['service_reservation_id'];

				$user['birth_day'] = bpv_format_date($user['birth_day'], DB_DATE_FORMAT);

				if (!empty($user['passport_expiry'])){

					$user['passport_expiry'] = bpv_format_date($user['passport_expiry'], DB_DATE_FORMAT);

				} else {
						
					$user['passport_expiry'] = NULL;
				}

				$this->db->insert('visa_users',$user);
			}
				
		}

		$this->db->trans_complete();

		if($this->db->trans_status()){
				
			return $email['id'];
				
		} else {
				
			return null;
				
		}
	}

	function count_room_night($search_criteria = ''){

		$str_query =  "SELECT sum(sr.cabin_booked * (t.duration -1)) as room_night, sum(sr.cabin_incentive * (t.duration -1)) as room_night_incentive FROM service_reservations sr".
				" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id".
				" INNER JOIN tours t ON t.id = sr.service_id";

		$str_query = $this->_setSRQueryString($search_criteria, $str_query);

		$str_query = $str_query. " AND (sr.reservation_type = 1 OR sr.reservation_type = 4)"; // cruise tour and land tour

		$query = $this->db->query($str_query);

		$total = $query->result_array();

		return $total[0];
	}

	function customer_is_in_use($id) {
		$this->db->where('customer_id', $id);
		$query = $this->db->get('customer_bookings');
		$results = $query->result_array();

		if(count($results) > 0) return TRUE;

		return FALSE;
	}

	function get_destination_of_services(){
		$str_query = "(SELECT DISTINCT d.id, d.name FROM tours as t JOIN destinations as d ON t.main_destination_id = d.id ".
				" AND t.status = 1 AND t.deleted != 1 AND d.deleted != 1)";

		$str_query = $str_query . " UNION ";

		$str_query = $str_query . " (SELECT DISTINCT d.id, d.name FROM hotels as h JOIN destinations as d ON h.destination_id = d.id ".
				" AND h.status = 1 AND h.deleted != 1 AND d.deleted != 1)";

		$str_query = $str_query . ' ORDER BY name ASC ';

		$query = $this->db->query($str_query);

		$results = $query->result_array();

		return $results;

	}

	function countTotalPax($search_criteria = ''){
		$str_query = "SELECT SUM(cb.adults) adults, SUM(cb.children) children, SUM(cb.infants) infants, SUM(cb.send_review) send_review FROM customer_bookings cb ".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id";

		$str_query = $this->_getSearchCBQueryStrCondition($search_criteria, $str_query);

		$query = $this->db->query($str_query);

		return $query->result_array();
	}

	function countTotalPaxSR($search_criteria = ''){

		$str_query =  "SELECT cb.id, cb.adults adults, cb.children children, cb.infants infants, cb.send_review send_review FROM service_reservations sr".
				" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id";

		$str_query = $this->_setSRQueryString($search_criteria, $str_query);

		$str_query = $str_query. " GROUP BY cb.id";

		$query = $this->db->query($str_query);

		$results = $query->result_array();

		$ret['adults'] = 0;

		$ret['children'] = 0;

		$ret['infants'] = 0;

		$ret['send_review'] = 0;

		foreach ($results as $key=>$value){
			$ret['adults'] = $ret['adults'] + $value['adults'];
			$ret['children'] = $ret['children'] + $value['children'];
			$ret['infants'] = $ret['infants'] + $value['infants'];
			$ret['send_review'] = $ret['send_review'] + $value['send_review'];
		}

		return $ret;
	}

	function searchTasks($search_criteria = '', $type, $is_count = false)
	{
		if($type == TASK_CUSTOMER_MEETING || $type == TASK_CUSTOMER_PAYMENT) {
			$str_query = "SELECT cb.*, c.full_name, c.email, c.phone, c.country, c.title, c.city, u.username  FROM customer_bookings cb ".
					" INNER JOIN customers c ON c.id = cb.customer_id".
					" INNER JOIN users u ON u.id = cb.user_id";

			$str_query = $this->_getSearchTaskCondition($search_criteria, $str_query, $type, $is_count);

			if($type == TASK_CUSTOMER_MEETING) {
				$str_query = $this->get_task_query($str_query, TASK_CUSTOMER_MEETING);
			}

			if($type == TASK_CUSTOMER_PAYMENT) {
				$str_query = $this->get_task_query($str_query, TASK_CUSTOMER_PAYMENT);
			}

			$query = $this->db->query($str_query);

			$cbs = $query->result_array();

			$cb_ids = $this->_getIdsArr($cbs);

			$srs = $this->_getServiceReservations($cb_ids);

			$cbs = $this->_setServiceReservations($cbs, $srs);

			$duplicate_customers = $this->_get_duplicate_cb($cb_ids);

			$cbs = $this->_set_duplicate_flag($cbs, $duplicate_customers);

			return $cbs;
		} else {
			$str_query = "SELECT sr.*,cb.customer_id, c.full_name, c.email, c.phone, c.country, c.title, c.city, u.username, p.payment_type  FROM service_reservations sr ".
					" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id".
					" INNER JOIN customers c ON c.id = cb.customer_id".
					" INNER JOIN users u ON u.id = cb.user_id".
					" LEFT OUTER JOIN partners p ON p.id = sr.partner_id";

			$str_query = $this->_getSearchTaskCondition($search_criteria, $str_query, $type, $is_count);
				
			if(isset($search_criteria['partner'])) {
				if($search_criteria['partner'] == 0) {
					$str_query = $str_query. " AND sr.partner_id IS NULL";
				} else {
					$str_query = $str_query. " AND sr.partner_id = ". $search_criteria['partner'];
				}
			}

			$str_query = $str_query. " AND sr.deleted != ". DELETED;

			switch ($type) {
				case TASK_SERVICE_RESERVATION:
					$str_query = $this->get_task_query($str_query, TASK_SERVICE_RESERVATION);
					break;
				case TASK_TRANSFER_REMINDER:
					$str_query = $this->get_task_query($str_query, TASK_TRANSFER_REMINDER);
					break;
				case TASK_SERVICE_PAYMENT:
					$str_query = $this->get_task_query($str_query, TASK_SERVICE_PAYMENT);
					break;
			}

			$query = $this->db->query($str_query);

			$ret = $query->result_array();
				
			if($type == TASK_SERVICE_PAYMENT) {
				$service_payments = $this->get_service_payment($ret, $search_criteria, $is_count);

				return $service_payments;
			}
				

			return $ret;
		}

	}

	function _getSearchTaskCondition($search_criteria ='', $str_query='', $type, $is_count = false){

		$from_date = 'cb.start_date';

		switch ($type) {
			case TASK_CUSTOMER_MEETING:
				$from_date = 'cb.meeting_date';
				break;
			case TASK_CUSTOMER_PAYMENT:
				$from_date = 'cb.payment_due';
				break;
			case TASK_SERVICE_RESERVATION:
				$from_date = 'sr.reserved_date';
				break;
			case TASK_TRANSFER_REMINDER:
				$from_date = 'sr.start_date';
				break;
			case TASK_SERVICE_PAYMENT:
				$from_date = '';
				break;
			default:
				break;
		}

		// Eleminate deleted booking [6-12-2012]
		$str_query = $str_query. " WHERE cb.deleted != ". DELETED;

		if (isset($search_criteria['user_id'])){

			$str_query = $str_query. " AND cb.user_id = '".$search_criteria['user_id']."'";

		}

		if (isset($search_criteria['name'])){

			$str_query = $str_query." AND(";

			$str_query = $str_query. " c.full_name LIKE '%".$search_criteria['name']."%'";

			$str_query = $str_query. " OR c.email LIKE '%".$search_criteria['name']."%'";

			$str_query = $str_query. " OR u.username LIKE '%".$search_criteria['name']."%'";

			if($type == TASK_CUSTOMER_MEETING || $type == TASK_CUSTOMER_PAYMENT) {
				$ids = $this->_getCustomerBookingByServiceName($search_criteria['name']);
				if ($ids != ""){
					$str_query = $str_query. " OR cb.id IN ".$ids;
				}
			} else {
				$str_query = $str_query. " OR sr.service_name LIKE '%".$search_criteria['name']."%'";
			}
				

			$str_query = $str_query.")";
		}

		if(!empty($from_date)) {
			if((!empty($search_criteria['start_date']) || !empty($search_criteria['end_date'])) && !$is_count) {
				if(!empty($search_criteria['start_date'])) {
					$str_query = $str_query. " AND ".$from_date." >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
				}
					
				if(!empty($search_criteria['end_date'])) {
					$str_query = $str_query. " AND ".$from_date." <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				}
			} else {
				if (isset($search_criteria['task_filter']) && count($search_criteria['task_filter']) > 0){

					$near_future_config = $this->config->item('near_future_day');

					$current_date = $this->_getCurrentDate();

					$limit_date = strtotime($current_date . " +". $near_future_config. " day");

					$limit_date = date('Y-m-d', $limit_date);

					$tomorrow = strtotime($current_date . " +1 day");

					$tomorrow = date('Y-m-d', $tomorrow);

					$str_query = $str_query." AND (";

					$or_flag = false;

					// current booking
					if (in_array(1, $search_criteria['task_filter'])){
							
						$or_flag = true;
							
						$str_query = $str_query. "(".$from_date." <= '".$current_date;
							
						$str_query = $str_query."') OR ".$from_date." = '".$tomorrow."'";
					}

					// near future booking
					if (in_array(2, $search_criteria['task_filter'])){
							
						if ($or_flag){
							$str_query = $str_query. " OR ";
						}
							
						$or_flag = true;
							
						$str_query =$str_query. "(".$from_date." <= '".$limit_date."' AND ".$from_date." > '".$tomorrow."')";
					}
						
					// overdue
					if (in_array(3, $search_criteria['task_filter'])){

						$str_query = $str_query.$from_date." < '".$current_date."'";
					}

					$str_query = $str_query." )";
				}
			}
		}

		return $str_query;
	}

	function number_of_task($app_context, $search_criteria='', $txt_only = false, $near_future = false, $overdue = false) {

		$task_type = '';
		if(empty($search_criteria)) {
			$search_criteria = array();
			if($txt_only) {
				$search_criteria['user_id'] = $app_context->current_user->id;
			}
		} else {
			if(isset($search_criteria['task_type'])) {
				$task_type = $search_criteria['task_type'];
			}
		}

		if($overdue) {
			$search_criteria['task_filter'] = array(3);
		} else {
			if($near_future) {
				$search_criteria['task_filter'] = array(2);
			} else {
				$search_criteria['task_filter'] = array(1);
			}
		}


		$today_task_count = 0;

		$cb_query = "SELECT COUNT(*) as num  FROM customer_bookings cb ".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id";

		// TASK_CUSTOMER_MEETING
		if((!empty($search_criteria) && $task_type == TASK_CUSTOMER_MEETING) || empty($task_type)) {
			$str_query = $this->_getSearchTaskCondition($search_criteria, $cb_query, TASK_CUSTOMER_MEETING, true);

			$str_query = $this->get_task_query($str_query, TASK_CUSTOMER_MEETING);
			$query = $this->db->query($str_query);
			$ret = $query->result_array();

			$meeting = 0;
			if(!empty($ret)) {
				$meeting = $ret[0]['num'];
				$today_task_count += $meeting;
			}
		}


		// TASK_CUSTOMER_PAYMENT
		if((!empty($search_criteria) && $task_type == TASK_CUSTOMER_PAYMENT) || empty($task_type)) {
			$str_query = $this->_getSearchTaskCondition($search_criteria, $cb_query, TASK_CUSTOMER_PAYMENT, true);

			$str_query = $this->get_task_query($str_query, TASK_CUSTOMER_PAYMENT);
			$query = $this->db->query($str_query);
			$ret = $query->result_array();

			$customer_payment = 0;
			if(!empty($ret)) {
				$customer_payment = $ret[0]['num'];
				$today_task_count += $customer_payment;
			}
		}

		$sr_query = "SELECT COUNT(*) as num  FROM service_reservations sr ".
				" INNER JOIN customer_bookings cb ON cb.id = sr.customer_booking_id".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id".
				" LEFT OUTER JOIN partners p ON p.id = sr.partner_id";

		// TASK_SERVICE_RESERVATION
		if((!empty($search_criteria) && $task_type == TASK_SERVICE_RESERVATION) || empty($task_type)) {
			$str_query = $this->_getSearchTaskCondition($search_criteria, $sr_query, TASK_SERVICE_RESERVATION, true);
				
			$str_query = $this->get_task_query($str_query, TASK_SERVICE_RESERVATION);
			$query = $this->db->query($str_query);
			$ret = $query->result_array();
				
			$service_reservation = 0;
			if(!empty($ret)) {
				$service_reservation = $ret[0]['num'];
				$today_task_count += $service_reservation;
			}
		}

		// TASK_TRANSFER_REMINDER
		if((!empty($search_criteria) && $task_type == TASK_TRANSFER_REMINDER) || empty($task_type)) {
			$str_query = $this->_getSearchTaskCondition($search_criteria, $sr_query, TASK_TRANSFER_REMINDER, true);
				
			$str_query = $this->get_task_query($str_query, TASK_TRANSFER_REMINDER);
			$query = $this->db->query($str_query);
			$ret = $query->result_array();
				
			$transfer = 0;
			if(!empty($ret)) {
				$transfer = $ret[0]['num'];
				$today_task_count += $transfer;
			}
		}

		// TASK_SERVICE_PAYMENT
		if((!empty($search_criteria) && $task_type == TASK_SERVICE_PAYMENT) || empty($task_type)) {
			$ret = $this->searchTasks($search_criteria, TASK_SERVICE_PAYMENT, true);
			//$service_payments = $this->get_service_payment($ret, $search_criteria);
			$service_payment = count($ret);
			//print_r($service_payment);exit();

			$today_task_count += $service_payment;
		}

		// TO-DO
		if((!empty($search_criteria) && $task_type == TASK_TO_DO) || empty($task_type)) {
			if(!empty($search_criteria)) {
				$to_do = $this->searchTodo($search_criteria, true);
				$today_task_count += count($to_do);
			} else {
				$str_query = "SELECT COUNT(*) as num  FROM task_todo";
				$str_query .= " WHERE user_id=".$app_context->current_user->id;
				$str_query .= " AND (status = 0 OR status = 1)";
				if($near_future) {
					$str_query .= " AND (start_date > '".$tomorrow."' AND start_date <= '".$limit_date."')";
				} else {
					$str_query .= " AND start_date  <= '".$tomorrow."'";
				}
					
				$query = $this->db->query($str_query);
				$ret = $query->result_array();
					
				$to_do = 0;
				if(!empty($ret)) {
					$to_do = $ret[0]['num'];
					$today_task_count += $to_do;
				}
			}
		}

		if($txt_only) {
			return "(".$today_task_count.")";
		}

		return $today_task_count;
	}

	function get_service_payment($ret, $search_criteria, $is_count = false) {
		$service_payments = array();

		foreach($ret as $service){
			// If the service was already deposit then ignore 1st payment
			if($service['reservation_status'] != RESERVATION_DEPOSIT
			&& $this->is_valid_task_date($search_criteria, $service['1_payment_due'], $is_count)) {
				$service['payment_due'] = $service['1_payment_due'];
				$service['payment'] = $service['1_payment'];
				$service['second_payment'] = '';

				if(!$is_count && (!empty($service['2_payment_due'])
						&& $this->is_valid_task_date($search_criteria, $service['2_payment_due'], $is_count))) {
					$service['second_payment'] = 1;
				}

				$service_payments[] = $service;
			}
				
			if($service['reservation_status'] == RESERVATION_DEPOSIT && !empty($service['2_payment_due'])
			&& $this->is_valid_task_date($search_criteria, $service['2_payment_due'], $is_count)) {
				$service['payment'] = $service['2_payment'];
				$service['payment_due'] = $service['2_payment_due'];
				$service['second_payment'] = 1;
				$service_payments[] = $service;
			}
		}

		return $service_payments;
	}

	function is_valid_task_date($search_criteria, $task_date, $is_count = false) {

		// get all
		if(!isset($search_criteria['task_filter']) && empty($search_criteria['start_date'])
		&& empty($search_criteria['end_date'])) {
			return true;
		}

		// get by specific time
		if((!empty($search_criteria['start_date']) || !empty($search_criteria['end_date'])) && !$is_count) {
			if(!empty($search_criteria['start_date'])
			&& ($this->timedate->date_compare($search_criteria['start_date'], $task_date) == 1)) {
				return false;
			}

			if(!empty($search_criteria['end_date'])
			&& ($this->timedate->date_compare($task_date, $search_criteria['end_date']) == 1)) {
				return false;
			}
				
			return true;
		} else {
			if (isset($search_criteria['task_filter']) && count($search_criteria['task_filter']) > 0){
				$near_future_config = $this->config->item('near_future_day');
					
				$current_date = $this->_getCurrentDate();
					
				$limit_date = date('Y-m-d', strtotime($current_date . " +". $near_future_config. " day"));
					
				$tomorrow = date('Y-m-d', strtotime($current_date . " +1 day"));

				// current task
				if (in_array(1, $search_criteria['task_filter'])){

					if(($this->timedate->date_compare($tomorrow, $task_date) >= 0)) {
						return true;
					}
				}

				// near future task
				if (in_array(2, $search_criteria['task_filter'])){
					if(($this->timedate->date_compare($task_date, $tomorrow) == 1)
					&& ($this->timedate->date_compare($limit_date, $task_date) >= 0)) {
						return true;
					}
				}

				// overdue
				if (in_array(3, $search_criteria['task_filter'])){

					if(($this->timedate->date_compare($current_date, $task_date) == 1)) {
						return true;
					}
				}
			}
		}

		return false;
	}

	function get_task_query($str_query, $type, $is_counting = false) {

		switch ($type) {
			case TASK_CUSTOMER_MEETING:
				$str_query = $str_query. " AND met != 1 AND cb.meeting_date IS NOT NULL";
				if(!$is_counting) {
					//$str_query = $str_query. " ORDER BY cb.meeting_date ASC, c.full_name ASC";
					$str_query = $str_query. " ORDER BY cb.request_date DESC, cb.id DESC";
				}

				break;
			case TASK_CUSTOMER_PAYMENT:
				$str_query = $str_query. " AND cb.status = 3 AND cb.payment_due IS NOT NULL"; // deposit
				if(!$is_counting) {
					//$str_query = $str_query. " ORDER BY cb.payment_due ASC, c.full_name ASC";
					$str_query = $str_query. " ORDER BY cb.request_date DESC, cb.id DESC";
				}

				break;
			case TASK_SERVICE_RESERVATION:
				$str_query = $str_query. " AND sr.deleted != 1 AND sr.reserved_date IS NOT NULL";
				$str_query = $str_query. " AND (cb.status = 3 OR cb.status = 4)";
				$str_query = $str_query. " AND (sr.reservation_status = ".RESERVATION_NEW;
				$str_query = $str_query. " OR sr.reservation_status = ".RESERVATION_SENDING.")";
				//$str_query = $str_query. " ORDER BY sr.reserved_date ASC, sr.service_name ASC";
				$str_query = $str_query. " ORDER BY cb.request_date DESC, cb.id DESC, sr.start_date ASC, sr.service_name ASC";
				break;
			case TASK_SERVICE_PAYMENT:
				$str_query = $str_query. " AND sr.deleted != 1 AND (sr.1_payment_due IS NOT NULL OR sr.2_payment_due IS NOT NULL)";
				$str_query = $str_query. " AND (sr.reservation_status = ".RESERVATION_RESERVED;
				$str_query = $str_query. " OR sr.reservation_status = ".RESERVATION_DEPOSIT.")";
				$str_query = $str_query. " AND (cb.status = 3 OR cb.status = 4 OR cb.status = 6)";
				$str_query = $str_query. " AND p.payment_type = 2"; // per booking
				//$str_query = $str_query. " ORDER BY sr.1_payment_due ASC, sr.customer_booking_id ASC";
				$str_query = $str_query. " ORDER BY cb.request_date DESC, cb.id DESC, sr.start_date ASC, sr.service_name ASC";
				break;
			case TASK_TRANSFER_REMINDER:
				$str_query = $str_query. " AND sr.deleted != 1 AND sr.start_date IS NOT NULL";
				$str_query = $str_query. " AND sr.reviewed != 1"; // not complete yet
				$str_query = $str_query. " AND sr.reservation_type = 3"; // transfer only
				$str_query = $str_query. " AND (cb.status = 3 OR cb.status = 4)";
				$str_query = $str_query. " AND (sr.reservation_status = ".RESERVATION_NEW;
				$str_query = $str_query. " OR sr.reservation_status = ".RESERVATION_SENDING;
				$str_query = $str_query. " OR sr.reservation_status = ".RESERVATION_RESERVED;
				$str_query = $str_query. " OR sr.reservation_status = ".RESERVATION_DEPOSIT;
				$str_query = $str_query. " OR sr.reservation_status = ".RESERVATION_FULL_PAID.")";
				//$str_query = $str_query. " ORDER BY sr.start_date ASC, sr.service_name ASC";
				$str_query = $str_query. " ORDER BY cb.request_date DESC, cb.id DESC, sr.start_date ASC, sr.service_name ASC";
				break;
		}

		return $str_query;
	}

	function searchTodo($search_criteria, $is_count = false) {

		$is_current_or_near = isset($search_criteria['task_filter']) && count($search_criteria['task_filter']) > 0;

		$str_query = "SELECT *  FROM task_todo";

		// status new, process when task filter is avaiable
		if ($is_current_or_near){
			$str_query .= " WHERE (status = 0 OR status = 1)";
				
		} else {
			$str_query .= " WHERE 1=1";
		}

		if (isset($search_criteria['user_id'])){

			$str_query = $str_query. " AND user_id = '".$search_criteria['user_id']."'";

		}

		if (isset($search_criteria['name'])){

			$str_query = $str_query." AND";

			$str_query = $str_query. " name LIKE '%".$search_criteria['name']."%'";
		}

		if (isset($search_criteria['to_do_status'])){
			$str_query = $str_query. " AND status = '".$search_criteria['to_do_status']."'";
		}


		if((!empty($search_criteria['start_date']) || !empty($search_criteria['end_date'])) && !$is_count) {
			if(!empty($search_criteria['start_date']) && empty($search_criteria['end_date'])) {
				$str_query = $str_query. " AND (due_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
				$str_query = $str_query. " OR start_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."')";
			}

			if(!empty($search_criteria['end_date']) && empty($search_criteria['start_date'])) {
				$str_query = $str_query. " AND (due_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'";
				$str_query = $str_query. " OR start_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."')";
			}
				
			if(!empty($search_criteria['start_date']) && !empty($search_criteria['end_date'])) {
				$str_query = $str_query. " AND ((due_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
				$str_query = $str_query. " AND due_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."')";
				$str_query = $str_query. " OR (start_date >= '".date('Y-m-d', strtotime($search_criteria['start_date']))."'";
				$str_query = $str_query. " AND start_date <= '".date('Y-m-d', strtotime($search_criteria['end_date']))."'))";
			}
		} else {
			if ($is_current_or_near){
					
				$near_future_config = $this->config->item('near_future_day');
					
				$current_date = $this->_getCurrentDate();
					
				$limit_date = strtotime($current_date . " +". $near_future_config. " day");
					
				$limit_date = date('Y-m-d', $limit_date);
					
				$tomorrow = strtotime($current_date . " +1 day");
					
				$tomorrow = date('Y-m-d', $tomorrow);
					
				$or_flag = false;
					
				// current booking
				if (in_array(1, $search_criteria['task_filter'])){

					$or_flag = true;

					$str_query = $str_query. " AND ( due_date <= '".$tomorrow."'";
				}
					
				// near future booking
				if (in_array(2, $search_criteria['task_filter'])){

					if ($or_flag){
						$str_query = $str_query. " OR ";
					} else {
						$str_query = $str_query. " AND ( ";
					}

					$or_flag = true;

					$str_query = $str_query. "due_date > '".$tomorrow."'";
				}

				// overdue
				if (in_array(3, $search_criteria['task_filter'])){
					$str_query = $str_query. " AND ( due_date <= '".$current_date."'";
				}

				$str_query = $str_query." )";
			}
		}

		$str_query = $str_query. " ORDER BY due_date ASC";

		//print_r($str_query);exit();

		$query =  $this->db->query($str_query);

		$tasks = $query->result_array();

		foreach ($tasks as $key => $task) {
			$task['username'] = get_user_modified($task['user_id']);
			$task['start_date'] = date(DATE_FORMAT, strtotime($task['start_date']));
			$task['due_date'] = date(DATE_FORMAT, strtotime($task['due_date']));
			$tasks[$key] = $task;
		}

		return $tasks;
	}

	function mark_as_done($id, $task_type, $context) {

		$status = false;
		switch ($task_type) {
			case TASK_CUSTOMER_MEETING:
				$this->db->set('met', 1);

				$this->db->set('user_modified_id', $context->current_user->id);

				$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));

				$this->db->where('id', $id);

				$this->db->update('customer_bookings');

				$status = true;
				break;
			case TASK_CUSTOMER_PAYMENT:
				$this->db->set('status', BOOKING_FULL_PAID); // full paid

				$this->db->set('user_modified_id', $context->current_user->id);

				$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));

				$this->db->where('id', $id);

				$this->db->update('customer_bookings');

				$status = true;
				break;
			case TASK_SERVICE_RESERVATION:
				$this->db->set('reservation_status', RESERVATION_RESERVED);
					
				$this->db->set('user_modified_id', $context->current_user->id);
					
				$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
					
				$this->db->where('id', $id);
					
				$this->db->update('service_reservations');
					
				$status = true;
				break;
			case TASK_TRANSFER_REMINDER:
				$this->db->set('reviewed', 1);
					
				$this->db->set('user_modified_id', $context->current_user->id);
					
				$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
					
				$this->db->where('id', $id);
					
				$this->db->update('service_reservations');
					
				$status = true;
				break;
			case TASK_SERVICE_PAYMENT:
				$service = $this->getServiceReservation($id);
				if(!empty($service)) {
					$second_payment = $this->input->post('second_payment');
					// if the service avaiable onyl 1-Payment & 1-Payment Due then change status to full paid
					// OR mark as done on the 2-Payment
					if(empty($service['2_payment_due']) || $second_payment == 1
					|| $service['reservation_status'] == RESERVATION_DEPOSIT) {
						$this->db->set('reservation_status', RESERVATION_FULL_PAID);
					} else {
						// else change to deposit
						$this->db->set('reservation_status', RESERVATION_DEPOSIT);
					}

					$this->db->set('user_modified_id', $context->current_user->id);

					$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));

					$this->db->where('id', $id);

					$this->db->update('service_reservations');

					$status = true;
				}

				break;
					
			default:
				break;
		}

		return $status;
	}

	function getTodo($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('task_todo');

		$tasks = $query->result_array();
		if (count($tasks) > 0) {
			$todo = $tasks[0];
			$todo['start_date'] =  bpv_format_date($todo['start_date'], DATE_FORMAT);
			$todo['due_date'] =  bpv_format_date($todo['due_date'], DATE_FORMAT);
			$todo['date_modified'] = get_format_date($todo['date_modified']);
			$todo['user_modified'] = get_user_modified($todo['user_modified_id']);
			return $todo;
		}
		return FALSE;
	}

	function createTodo($name, $details, $start_date, $due_date, $todo_user='', $app_context) {
		$name = trim($name);
		$details = trim($details);

		$this->db->set('name', $name);
		$this->db->set('details', $details);
		$this->db->set('start_date',  bpv_format_date($start_date, DB_DATE_FORMAT));
		$this->db->set('due_date', bpv_format_date($due_date, DB_DATE_FORMAT));
		if(!empty($todo_user)) {
			$this->db->set('user_id', $todo_user);
		} else {
			$this->db->set('user_id', $app_context->current_user->id);
		}

		$this->db->set('user_created_id', $app_context->current_user->id);
		$this->db->set('date_created', date(DB_DATE_TIME_FORMAT));
		$this->db->set('user_modified_id', $app_context->current_user->id);
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));

		$this->db->insert('task_todo');

		$id = $this->db->insert_id();

		return $id;
	}

	function updateTodo($id, $name, $details, $start_date, $due_date, $status, $todo_user='', $app_context) {
		$name = trim($name);
		$details = trim($details);

		$this->db->set('name', $name);
		$this->db->set('details', $details);
		$this->db->set('start_date',  bpv_format_date($start_date, DB_DATE_FORMAT));
		$this->db->set('due_date', bpv_format_date($due_date, DB_DATE_FORMAT));
		$this->db->set('status', $status);

		$this->db->set('user_modified_id', $app_context->current_user->id);
		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));

		if(!empty($todo_user)) {
			$this->db->set('user_id', $todo_user);
		}

		$this->db->where('id', $id);
			
		// execute update into db
		$this->db->update('task_todo');

		return $id;
	}

	function deleteTodo($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('task_todo');
	}

	function update_customer_payment($id, $onepay, $cash, $pos, $context) {

		$this->db->trans_start();

		$cb = $this->getCustomerBooking($id);

		$this->db->set('onepay', $onepay);

		$this->db->set('cash', $cash);

		$this->db->set('pos', $pos);

		$this->db->set('status', BOOKING_FULL_PAID); // full paid

		$this->db->set('actual_selling', $this->_calculateActualSelling($onepay, $cash, $pos, $cb['payment_method']));

		$this->db->set('actual_profit', $this->_calculateActualSelling($onepay, $cash, $pos, $cb['payment_method']) - $cb['net_price']);

		$this->db->set('user_modified_id', $context->current_user->id);

		$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));

		$this->db->where('id', $id);

		$this->db->update('customer_bookings');

		$this->db->trans_complete();

		$cb = $this->getCustomerBooking($id);

		if(is_profit_warning($cb['selling_price'], $cb['net_price'], $cb['actual_profit'], $cb['status'])) {
			return true;
		}

		return false;
	}


	function get_booking_sources(){

		$this->db->order_by('id','asc');

		$query = $this->db->get('booking_sources');

		$sources = $query->result_array();

		return $sources;
	}

	function get_campaign($id){

		$this->db->where('id', $id);

		$query = $this->db->get('campaigns');

		$campaigns = $query->result_array();

		if (count($campaigns) > 0){
			return $campaigns[0]['name'];
		}

		return '';

	}

	function get_campaigns(){

		$query = $this->db->get('campaigns');

		$campaigns = $query->result_array();

		return $campaigns;

	}

	function search_customer_autocomplete($cus_name){

		$this->db->select('id, full_name, title, country, city');

		$this->db->where('deleted !=', DELETED);

		$this->db->like('full_name', $cus_name, 'both');

		$this->db->order_by('full_name', 'asc');

		$query = $this->db->get('customers');

		$results = $query->result_array();

		return json_encode($results);
	}

	function get_origin_rs($customer_booking_id){

		$this->db->select('id, service_name');

		$this->db->where('deleted !=', DELETED);

		$this->db->where('customer_booking_id', $customer_booking_id);

		$this->db->where('origin_id', STATUS_INACTIVE);

		$this->db->where_in('reservation_type', array(RESERVATION_TYPE_CRUISE_TOUR, RESERVATION_TYPE_LAND_TOUR));

		$this->db->order_by('start_date', 'asc');

		$query = $this->db->get('service_reservations');

		$results = $query->result_array();

		return $results;
	}

	function get_cb_email($id){

		$this->db->select('cb.id, cb.status, cb.selling_price, cb.email_id, cb.adults, cb.children, cb.infants, cb.start_date, cb.end_date, cb.booking_site, c.title, c.full_name, c.country, c.email as customer_email, u.signature, u.email as sale_email, u.email_password, u.sale_name');

		$this->db->from('customer_bookings as cb');

		$this->db->join('customers as c', 'c.id = cb.customer_id');

		$this->db->join('users as u', 'u.id = cb.user_id');

		$this->db->where('cb.id', $id);

		$query = $this->db->get();

		$results = $query->result_array();

		if(count($results) > 0){
				
			$cb = $results[0];
				
			$cb['services'] = $this->get_cb_services($cb['id']);
				
			return $cb;
				
		} else {
				
			return false;
				
		}
	}

	function get_cb_services($cb_id){

		$this->db->select('id, service_name, origin_id, service_id, reservation_type, booking_services, selling_price, start_date');

		$this->db->from('service_reservations');

		$this->db->where('customer_booking_id', $cb_id);

		$this->db->where('deleted !=', DELETED);

		$this->db->order_by('start_date', 'asc');

		$query = $this->db->get();

		$results = $query->result_array();

		return $results;
	}

	function get_cruise_name($tour_id){

		$this->db->select('c.name');

		$this->db->from('tours as t');

		$this->db->join('cruises as c', 'c.id = t.cruise_id');

		$this->db->where('t.id', $tour_id);

		$query = $this->db->get();

		$results = $query->result_array();

		if (count($results) > 0){
				
			return $results[0]['name'];
				
		}

		return '';
	}

	function search_partner_autocomplete($partner_name, $selected_ids){

		$this->db->select('id, name');

		$this->db->where('deleted !=', DELETED);

		if ($selected_ids != ''){
				
			$selected_ids = explode(',', $selected_ids);
				
			if (count($selected_ids) > 0){

				$this->db->where_not_in('id',$selected_ids);

			}
				
		}

		$this->db->like('name', $partner_name, 'after');

		$this->db->order_by('name', 'asc');

		$query = $this->db->get('partners');

		$results = $query->result_array();

		return json_encode($results);

	}

	function get_lasted_invoice_of_cb($cb_id){

		$this->db->select('id, invoice_reference, status');

		$this->db->where('customer_booking_id', $cb_id);

		$this->db->order_by('date_modified', 'desc');

		$query = $this->db->get('invoices');

		$results = $query->result_array();

		if (count($results) > 0){
				
			return $results[0];
				
		} else {
			return FALSE;
		}
	}

	function get_flight_users($cb_id){
		
		$this->db->select('fu.*, u.username as last_modified_by');
		
		$this->db->from('flight_users fu');
		
		$this->db->join('users u', 'u.id = fu.user_modified_id', 'left outer');
		
		$this->db->where('fu.customer_booking_id', $cb_id);
		
		$this->db->where('fu.deleted !=', DELETED);

		$this->db->order_by('fu.id', 'asc');

		$query = $this->db->get();

		$results = $query->result_array();

		return $results;
	}
	
	function has_flight_users($cb_id){
	
		$this->db->where('customer_booking_id', $cb_id);
	
		return $this->db->count_all_results('flight_users');
	}
	
	function search_cb_for_export_excels(){
		
		$this->db->select('cb.*, c.full_name, c.email, d.name as city, u.username as sale');
		
		$this->db->from('customer_bookings as cb');
		
		$this->db->join('customers as c','c.id = cb.customer_id');
		
		$this->db->join('users as u','u.id = cb.user_id');
		
		$this->db->join('destinations as d','d.id = c.destination_id', 'left outer');
		
		$this->db->where('cb.deleted != ', DELETED);
		
		$this->db->where('cb.request_type', STATUS_ACTIVE); // Reservation
		
		$this->db->order_by('cb.id','desc');
		
		$query = $this->db->get();
				
		$cbs = $query->result_array();
		
		$cb_ids = $this->_getIdsArr($cbs);
		
		$srs = $this->_getServiceReservations($cb_ids);
		
		$cbs = $this->_setServiceReservations($cbs, $srs);
		
		return $cbs;
	}
	
	/**
	 * Return all the valid customer
	 */
	
	function search_customer_data_for_marketing($invalid_emails, $invalid_phones){
		
		$this->db->select('id, full_name, gender, email, phone, count(*) as nr_duplicate');
		
		$this->db->from('customers');
		
		$this->db->where('email LIKE "%_@__%.__%"');
		
		$this->db->where('phone IS NOT NULL AND phone REGEXP "^[\+]?[0-9]+$"');
		
		$this->db->where_not_in('phone', $invalid_phones);
		
		$this->db->where_not_in('email', $invalid_emails);
		
		$this->db->where('full_name LIKE "%__%"');
		
		$this->db->where('gender > 0');
		
		$this->db->group_by('phone');
		
		$this->db->order_by('full_name', 'asc');
		
		$query = $this->db->get();
		
		$result = $query->result_array();
		
		return $result;
	}
	
	/**
	 * Functions for Booking Overviews
	 */
	function get_booking_contact_info($cb_id){
		
		$this->db->select('c.id, c.gender, c.full_name, c.phone, c.email, c.address, d.name as city');
		
		$this->db->from('customer_bookings cb');
		
		$this->db->join('customers c', 'c.id = cb.customer_id');
		
		$this->db->join('destinations d', 'd.id = c.destination_id', 'left outer');
		
		$this->db->where('cb.id', $cb_id);
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		if (count($results) > 0) return $results[0];
		
		return FALSE;
	}
	
	function get_cb_overview($id){
		
		$this->db->select('cb.id, cb.adults, cb.children, cb.infants, cb.flight_from, cb.flight_to, cb.flight_depart, cb.flight_return, cb.is_flight_domistic, cb.vnisc_booking_code, cb.user_id, u.hotline_name, u.hotline_number, u.email');
		
		$this->db->from('customer_bookings cb');
		
		$this->db->join('users u','u.id = cb.user_id');
		
		$this->db->where('cb.id', $id);
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		if (count($results) > 0){
			
			$cb = $results[0];
			
			if ($cb['user_id'] == 1){ // admin - the booking has not been assigned yet
				
				$user_login_id = get_user_id();
				
				$this->db->select('hotline_name, hotline_number, email');
				
				$this->db->where('id', $user_login_id);
				
				$query = $this->db->get('users');
				
				$results = $query->result_array();
				
				if (count($results) > 0){
					
					$user = $results[0];
					
					$cb['hotline_name'] = $user['hotline_name'];
					
					$cb['hotline_number'] = $user['hotline_number'];
					
					$cb['email'] = $user['email'];
				}
				
			}
			
			return  $cb;
		}
		
		return FALSE;
	}
	
	function get_sr_overview($cb_id){
		
		$this->db->from('service_reservations');

		$this->db->where('customer_booking_id', $cb_id);
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->order_by('id', 'asc');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
	}
	
	/**
	 * Flight Passenger Modules
	 */
	
	function get_passenger($id){
		
		$this->db->where('id', $id);
		
		$this->db->where('deleted !=', DELETED);
		
		$query = $this->db->get('flight_users');
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			return $results[0];
		} 
		
		return FALSE;
		
	}
	
	function create_passenger($passenger){
		
		$passenger['date_created'] = date(DB_DATE_TIME_FORMAT);
		
		$passenger['user_created_id'] = get_user_id();
		
		$this->db->insert('flight_users', $passenger);
		
		$id = $this->db->insert_id();
		
		$this->update_number_passenger($passenger['customer_booking_id']);
		
		return $id;
	}
	
	function update_passenger($id, $passenger){
		
		$pre_passenger = $this->get_passenger($id);
		
		$this->db->where('id', $id);
		
		$this->db->update('flight_users', $passenger);
		
		$this->update_number_passenger($pre_passenger['customer_booking_id']);
		
		return true;
	}
	
	function update_number_passenger($cb_id){
		
		$passengers = $this->get_flight_users($cb_id);
		
		$adt_nr = 0;
		
		$chd_nr = 0;
		
		$inf_nr = 0;
		
		foreach($passengers as $value){
			
			if($value['type'] == 1) $adt_nr++;
			
			if($value['type'] == 2) $chd_nr++;
			
			if($value['type'] == 3) $inf_nr++;
			
		}
		
		$this->db->where('id', $cb_id);
		
		$this->db->set('adults', $adt_nr);
		
		$this->db->set('children', $chd_nr);
		
		$this->db->set('infants', $inf_nr);
		
		$this->db->update('customer_bookings');
		
	}

}

?>