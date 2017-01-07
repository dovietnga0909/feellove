<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bookings extends BP_Controller {

	public function __construct()
    {
    
       	parent::__construct();	
       	
       	$this->load->language('booking');
       	
		$this->load->helper(array('url','form','booking','flight'));

		$this->load->model(array('Booking_Model', 'Destination_Model', 'Marketing_Model'));
		
		
		$this->load->library('form_validation');
		$this->load->library('pagination');	
		$this->load->config('booking_meta');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	
	public function index()
	{
		
		$data = $this->_set_common_data();
		
		$action = $this->input->post('action');
		
		$data['action'] = $action;
		
		if($action == ACTION_RESET){
			$this->_reset();	
		}
		
		$data['search_criteria'] = $this->_build_seach_cb_criteria();
		
		$data = $this->_search_cb($data);
		
		$data = $this->_load_cb_search_forms($data);
		
		$data['content'] = $this->load->view('bookings/cb/list_cb', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	function search_customers($str_query){
		
		$customers = $this->Booking_Model->search_customers($str_query);
		
		echo json_encode($customers);
		
	}
	
	function sr($id){
		
		$data = $this->_set_common_data();
		
		$data = $this->_set_data_for_edit($data, $id, 1);
		
		$data['site_title'] = lang('booking_mnu_sr');
		
		$customer_booking = $this->Booking_Model->getCustomerBooking($id);
			
		if ($customer_booking != ''){
		
			$customer_booking['campaign'] = $this->Booking_Model->get_campaign($customer_booking['campaign_id']);
		
			// get list of reservation services
			$customer_booking['service_reservations'] = $this->Booking_Model->_getServiceReservationsByBooking($id);
			$customer_booking = $this->set_reservation_services_color($customer_booking, false);
		
		
			//$customer_booking['lasted_invoice'] = $this->Booking_Model->get_lasted_invoice_of_cb($id);
		
			//$customer_booking['flight_users'] = $this->Booking_Model->get_flight_users($id);
		
			$data['customer_booking'] = $customer_booking;
				
		} else {
			redirect(site_url('bookings'));
		}
		
		$data['content'] = $this->load->view('bookings/cb/list_sr', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	function create(){
		
		$action = $this->input->post('action');
		
		if($action == ACTION_CANCEL){
				
			redirect(site_url('bookings'));
				
		}
		
		if($action == ACTION_SAVE){
			$saved = $this->_save_cb();
				
			if($saved){
				redirect(site_url('bookings'));
			}
		}
		
		$data = $this->_set_common_data();
		
		$data['site_title'] = lang('create_customer_booking');
		
		$data['content'] = $this->load->view('bookings/cb/create_cb', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	function edit($id){
		
		$action = $this->input->post('action');
		
		if($action == ACTION_CANCEL){
			
			redirect(site_url('bookings'));
			
		}
		
		if($action == ACTION_SAVE){
			$saved = $this->_save_cb($id);
			
			if($saved){
				redirect(site_url('bookings'));
			}
				
		}
		
		
		$data = $this->_set_common_data();
		
		$data = $this->_set_data_for_edit($data, $id);
		
		$customer_booking = $this->Booking_Model->getCustomerBooking($id);
			
		if ($customer_booking != ''){
		
			$customer_booking['campaign'] = $this->Booking_Model->get_campaign($customer_booking['campaign_id']);
		
			// get list of reservation services
			$customer_booking['service_reservations'] = $this->Booking_Model->_getServiceReservationsByBooking($id);
			$customer_booking = $this->set_reservation_services_color($customer_booking);
		
		
			$customer_booking['lasted_invoice'] = $this->Booking_Model->get_lasted_invoice_of_cb($id);
		
			$customer_booking['flight_users'] = $this->Booking_Model->get_flight_users($id);
		
			$data['customer_booking'] = $customer_booking;
			
		} else {
			redirect(site_url('bookings'));
		}
		
		$data['content'] = $this->load->view('bookings/cb/edit_cb', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	function delete($id){

		$customer_booking = $this->Booking_Model->getCustomerBooking($id);
		
		if(!empty($customer_booking) &&
		is_allow_deletion($customer_booking['user_created_id'], DATA_CUSTOMER_BOOKING, $customer_booking['user_id'], $customer_booking['approve_status'])) {
			
			$this->Booking_Model->deleteCustomerBooking($id);
			
		} else {
			message_alert(1);
		}
		
		redirect(site_url('bookings'));
	}
	
	function _save_cb($id =''){
		if ($this->_validate_cb($id)) {
			
			if($id != ''){ // edit
			
				$status = $this->Booking_Model->updateCustomerBooking($id);
			
			} else { // create
				
				$status = $this->Booking_Model->createCustomerBooking();
			}
			
			return $status;
		}
		return false;
	}
	
	function _set_cb_rules($id)
	{
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		$this->form_validation->set_rules($this->config->item('customer_booking'));
	
		$status = $this->input->post('status');
	
		if ($status == BOOKING_CANCEL || $status == BOOKING_CLOSE_LOST){
				
			$this->form_validation->set_rules('close_reason', 'lang:close_reason', 'required');
			
			$this->form_validation->set_rules('note', 'lang:note', 'callback_close_lost_check');
		}
	
		if (($status == BOOKING_FULL_PAID || $status == BOOKING_CLOSE_WIN) && !is_accounting()){
				
			$this->form_validation->set_rules('booking_date', 'lang:booking_date', 'required');
				
		}
	
		if ($status == BOOKING_DEPOSIT || $status == BOOKING_FULL_PAID || $status == BOOKING_CLOSE_WIN){
				
			$this->form_validation->set_rules('adults', 'lang:adults', 'required');
			
			$this->form_validation->set_rules('payment_method', 'lang:payment_method', 'required');
				
		}
	
		if ($status != BOOKING_CLOSE_WIN && $status != BOOKING_CLOSE_LOST && $status != BOOKING_CANCEL){
				
			$this->form_validation->set_rules('request_type', 'lang:request_type', 'required');
				
		}
	
		if ($status != BOOKING_CLOSE_WIN && $status != BOOKING_CLOSE_LOST && $status != BOOKING_CANCEL){
				
			$this->form_validation->set_rules('booking_site', 'lang:booking_site', 'required');
				
		}
		
		if($id == ''){ //create cb
			$this->form_validation->set_rules('customer', 'lang:customer', 'required');
			$this->form_validation->set_rules('cus_autocomplete', 'lang:customer', '');
		}
	
	}
	
	function _validate_cb($id)
	{
		$this->_set_cb_rules($id);
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;
	}
	
	public function close_lost_check($str)
	{
	
		$sale = $this->input->post('sale');
	
		if($sale == 1) return true; // don't check for admin
	
		$id = $this->uri->segment(3);
		

		$cb = $this->Booking_Model->getCustomerBooking($id);
	
		if($cb['status'] == BOOKING_CANCEL || $cb['status'] == BOOKING_CLOSE_LOST){
			return true; // don't check if the booking is currently close lost or cancel
		}
	
	
		$is_checked = false;
	
		$note = $this->input->post('note');
	
		$reason = str_replace($cb['note'], '', $note);
	
		$reason = trim($reason);
		$arr_reason_words = explode(' ', $reason);
		if(count($arr_reason_words) >= 5){
			$is_checked = true;
		}
	
		if (!$is_checked)
		{
			$this->form_validation->set_message('close_lost_check', 'You must enter at least 5 words for Close Lost/Cancel Reason');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	
	public function _set_common_data($data = array()){
	
		// set session for menues
		$this->session->set_userdata('MENU', MNU_BOOKING);
	
		$data['site_title'] = lang('title_bookings');
		
		$data['atts'] = get_popup_config('email');
		
		$data = get_library('datepicker', $data);
		$data = get_library('mask', $data);
		$data = get_library('typeahead', $data);
		
		$data['page_css'] = get_static_resources('booking.css');
		$data['page_js'] = get_static_resources('booking.js');
	
		
		$data['status'] = $this->config->item('booking_status');
		
		$data['reservation_status'] = $this->config->item('reservation_status');
		
		$data['close_reason'] = $this->config->item('close_reason');
		
		$data['booking_sites'] = $this->config->item('booking_sites');
		
		$data['customer_types'] = $this->config->item('customer_types');
		
		$data['request_types'] = $this->config->item('request_types');
		
		$data['booking_sources'] = $this->Booking_Model->get_booking_sources();
		
		$data['mediums'] = $this->config->item('mediums');
		
		$data['campaigns'] = $this->Booking_Model->get_campaigns();
		
		$data['sales'] = $this->Booking_Model->getUsers();
		
		$data['approve_status'] = $this->config->item('approve_status');
		
		$data['payment_methods'] = $this->config->item('payment_methods');
		
		
		
		$data['booked_cities'] = $this->Booking_Model->get_booked_customer_cities();
			
		$data['partners'] = $this->Booking_Model->get_all_partners();
		
		$data['c_titles'] = $this->config->item('c_titles');
	
		return $data;
	}
	
	function _set_data_for_edit($data, $id, $mnu_index = 0){
		
		if($id != ''){
		
			$nav_panel = $this->config->item('cb_nav_panel');
			
			if($this->Booking_Model->has_flight_users($id)){
				
				$nav_panel[] = array('link' => '/bookings/passenger/', 'title' => 'flight_passengers');
				
				$nav_panel[] = array('link' => '/bookings/overview/', 'title' => 'title_booking_overview');
				
				$nav_panel[] = array('link' => '/bookings/tform/', 'title' => 'tf_ticket_form');
				
			}
				
			foreach ($nav_panel as $key => $value){
					
				$value['link'] .= $id.'/';
					
				$nav_panel[$key] = $value;
					
			}
			
			$data['side_mnu_index'] = $mnu_index;
			
			$data['nav_panel'] = $nav_panel;
			
			$data['site_title'] = lang('edit_customer_booking');
		
		}
		
		return $data;
	}
	
	function _load_cb_search_forms($data){
		
		/**
		 * Data for Normal Search Form
		 */
		
		$data['booking_filter'] = $this->config->item('booking_filter');
		
		/**
		 * Data for Advanced Search Form
		 *
		 */
			
		$data['search'] = $this->load->view('bookings/cb/search_form', $data, TRUE);
			
		$data['advanced_search'] = $this->load->view('bookings/cb/search_form_advanced', $data, TRUE);
		
		return $data;
	}
	
	function _build_seach_cb_criteria(){
		
		$action = $this->input->post('action');
		
		$search_criteria = array();
		
		if ($this->session->userdata(CB_SEARCH_CRITERIA)){
		
			$search_criteria = $this->session->userdata(CB_SEARCH_CRITERIA);
		
		} else {
		
			$search_criteria['user_id'] = get_user_id();
		
			$search_criteria['booking_filter'] = array();
		
			$search_criteria['sort_column'] = "cb.request_date";
		
			$search_criteria['sort_order'] = "desc";
		
			$search_criteria['status'] = "-1";
		}
			
		if ($action == ACTION_SEARCH){
		
			$name = trim($this->input->post('name'));
			if ($name != '') {
				$search_criteria['name'] = $name;
			} else {
				unset($search_criteria['name']);
			}
		
			$customer_id = trim($this->input->post('customer'));
		
			if ($customer_id != '') {
				$search_criteria['customer_id'] = $customer_id;
			} else {
				unset($search_criteria['customer_id']);
			}
		
			$customer_name = trim($this->input->post('customer_auto'));
		
			if ($customer_name != '') {
				$search_criteria['customer_name'] = $customer_name;
			} else {
				unset($search_criteria['customer_name']);
			}
		
			$status = $this->input->post('status');
			if ($status != '') {
				$search_criteria['status'] = $status;
			} else {
				unset($search_criteria['status']);
			}
		
			$user_id =$this->input->post('sale');
		
			if ($user_id != '') {
				$search_criteria['user_id'] = $user_id;
			} else {
				unset($search_criteria['user_id']);
			}
		
			$approve_status =$this->input->post('approve_status');
		
			if ($approve_status != '') {
				$search_criteria['approve_status'] = $approve_status;
			} else {
				unset($search_criteria['approve_status']);
			}
		
			$booking_filter = $this->input->post('booking_filter');
		
			if ($booking_filter != ""){
		
				$search_criteria['booking_filter'] = $booking_filter;
		
			} else {
				unset($search_criteria['booking_filter']);
			}
		
			$sort_column = $this->input->post('sort_column');
			if ($sort_column != ""){
				$search_criteria['sort_column'] = $sort_column;
			} else {
				$search_criteria['sort_column'] = 'cb.request_date';
			}
		
			$sort_order = $this->input->post('sort_order');
			if ($sort_order != ""){
				$search_criteria['sort_order'] = $sort_order;
			} else {
				$search_criteria['sort_order'] = 'desc';
			}
		}
			
		if ($action == ACTION_ADVANCED_SEARCH){
		
			//unset($search_criteria['name']);
		
			$name = trim($this->input->post('name_advanced'));
			if ($name != '') {
				$search_criteria['name'] = $name;
			} else {
				unset($search_criteria['name']);
			}
		
			unset($search_criteria['customer_id']);
		
			unset($search_criteria['status']);
		
			$user_id =$this->input->post('sale_advanced');
		
			if ($user_id != '') {
				$search_criteria['user_id'] = $user_id;
			} else {
				unset($search_criteria['user_id']);
			}
		
			$approve_status =$this->input->post('approve_status_advanced');
		
			if ($approve_status != '') {
				$search_criteria['approve_status'] = $approve_status;
			} else {
				unset($search_criteria['approve_status']);
			}
		
			$city =$this->input->post('city_advanced');
		
			if ($city != '') {
				$search_criteria['city'] = $city;
			} else {
				unset($search_criteria['city']);
			}
		
			$duplicated_cb =$this->input->post('duplicated_cb');
		
			if ($duplicated_cb == '1') {
				$search_criteria['duplicated_cb'] = $duplicated_cb;
			} else {
				unset($search_criteria['duplicated_cb']);
			}
		
			unset($search_criteria['booking_filter']);
		
			$booking_status = $this->input->post('booking_status');
		
			if ($booking_status != ""){
		
				$search_criteria['booking_status'] = $booking_status;
		
			} else {
				unset($search_criteria['booking_status']);
			}
		
			$start_date = trim($this->input->post('start_date'));
			if ($start_date != '') {
				$search_criteria['start_date'] = $start_date;
			} else {
				unset($search_criteria['start_date']);
			}
		
			$end_date = trim($this->input->post('end_date'));
			if ($end_date != '') {
				$search_criteria['end_date'] = $end_date;
			} else {
				unset($search_criteria['end_date']);
			}
		
			$date_field = $this->input->post('date_field');
		
			if ($date_field != ""){
		
				$search_criteria['date_field'] = $date_field;
		
			} else {
				unset($search_criteria['date_field']);
			}
		
			$sort_column = $this->input->post('sort_column');
			if ($sort_column != ""){
				$search_criteria['sort_column'] = $sort_column;
			} else {
				$search_criteria['sort_column'] = 'cb.request_date';
			}
		
			$sort_order = $this->input->post('sort_order');
			if ($sort_order != ""){
				$search_criteria['sort_order'] = $sort_order;
			} else {
				$search_criteria['sort_order'] = 'desc';
			}
		
			/**
			 * Booking Source
			 */
		
			$booking_site = $this->input->post('booking_site');
		
			if ($booking_site != '') {
				$search_criteria['booking_site'] = $booking_site;
			} else {
				unset($search_criteria['booking_site']);
			}
		
			$customer_type = $this->input->post('customer_type');
			if ($customer_type != '') {
				$search_criteria['customer_type'] = $customer_type;
			} else {
				unset($search_criteria['customer_type']);
			}
		
		
			$request_type = $this->input->post('request_type');
			if ($request_type != '') {
				$search_criteria['request_type'] = $request_type;
			} else {
				unset($search_criteria['request_type']);
			}
		
			$source = $this->input->post('source');
			if ($source != '') {
				$search_criteria['source'] = $source;
			} else {
				unset($search_criteria['source']);
			}
		
			$medium = $this->input->post('medium');
			if ($medium != '') {
				$search_criteria['medium'] = $medium;
			} else {
				unset($search_criteria['medium']);
			}
		
			$keyword = $this->input->post('keyword');
			if ($keyword != '') {
				$search_criteria['keyword'] = $keyword;
			} else {
				unset($search_criteria['keyword']);
			}
		
		
			$landing_page = $this->input->post('landing_page');
			if ($landing_page != '') {
				$search_criteria['landing_page'] = $landing_page;
			} else {
				unset($search_criteria['landing_page']);
			}
		
			$campaign = $this->input->post('campaign');
			if ($campaign != '') {
				$search_criteria['campaign'] = $campaign;
			} else {
				unset($search_criteria['campaign']);
			}
		
			$partner_id =$this->input->post('partner');
			if ($partner_id != '') {
				$search_criteria['partner_id'] = $partner_id;
			} else {
				unset($search_criteria['partner_id']);
			}
			
			
			/**Paymetn Method
			 */
			
			$payment_method = $this->input->post('payment_method');
			
			if ($payment_method != '') {
				$search_criteria['payment_method'] = $payment_method;
			} else {
				unset($search_criteria['payment_method']);
			}
				
		}
			
		$this->session->set_userdata(CB_SEARCH_CRITERIA, $search_criteria);
		return $search_criteria;
		
	}
	
	function _search_cb($data){
		
		$search_criteria = $data['search_criteria'];
			
		$data['total_rows'] = $total_rows = $this->Booking_Model->getNumCustomerBooking($search_criteria);
		
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
		
		$per_page = $this->config->item('cb_per_page');
		
		$data['customer_bookings'] = $this->Booking_Model->searchCustomerBooking(
				$search_criteria
				, $per_page
				, (int)$offset);
		
		$data = $this->_set_price($data);
		
		$data = $this->_set_color_class($data);
		
		$data = $this->_set_report_info($data);
		
		$data = $this->_set_cb_paging_info($total_rows, $data);
		
		return $data;
		
	}
	
	function _set_price($data){
	
		$total_money = $this->Booking_Model->countTotalMoney($data['search_criteria']);
		/*
			$net = 0;
		$sel = 0;
		foreach ($data['customer_bookings'] as $key => $value) {
		$net = $net + $value['net_price'];
		$sel = $sel + $value['selling_price'];
		}
		*/
		$data['net'] = $total_money[0]['net_price'];
		$data['sel'] = $total_money[0]['selling_price'];
	
		$data['actual_sel'] = $total_money[0]['actual_selling'];
	
		$data['amount'] = $total_money[0]['payment_amount'];
	
		return $data;
	}
	
	function _set_color_class($data){
	
		$near_future_config = $this->config->item('near_future_day');
			
		$current_date = date(DB_DATE_FORMAT);
	
		$limit_date = strtotime($current_date . " +". $near_future_config. " day");
	
		$limit_date = date('Y-m-d', $limit_date);
	
		$tomorrow = strtotime($current_date . " +1 day");
	
		$tomorrow = date('Y-m-d', $tomorrow);
			
		foreach ($data['customer_bookings'] as $key => $value) {
				
			/*
			 * Set start-date color
			*/
			$value['color'] = "";
				
			if ($value['end_date'] < $current_date){
	
				$value['color'] = "past_booking";
	
			} else if ($value['start_date'] <= $current_date  && $value['end_date'] >= $current_date){
	
				$value['color'] = "current_booking";
	
			} else if ($value['start_date'] > $current_date && $value['start_date'] <= $limit_date){
	
				$value['color'] = "near_booking";
	
				if ($value['start_date'] == $tomorrow){
						
					$value['color'] = "current_booking";
						
				}
				/* khuyenpv 15.07.2015 -> don't need to set payment warning for domistic sales
				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'normal';
				}*/
				
			}
				
			/**
			 * Set meeting date color
			 *
			 */
			$value['meeting_color'] = "";
				
			if ($value['meeting_date'] < $current_date){
	
				$value['meeting_color'] = "past";
	
			} else if ($value['meeting_date'] == $current_date){
	
				$value['meeting_color'] = "current";
	
			} else if ($value['meeting_date'] > $current_date && $value['meeting_date'] <= $limit_date){
	
				$value['meeting_color'] = "near";
	
				if ($value['meeting_date'] == $tomorrow){
					$value['meeting_color'] = "current";
				}
			}
				
			$value['p_due_color'] = "";
				
			$value['payment_warning'] = "";
				
			if ($value['payment_due'] < $current_date){
	
				$value['p_due_color'] = "past";
				
				/* khuyenpv 15.07.2015 -> don't need to set payment warning for domistic sales
				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'high';
				}*/
	
			} else if ($value['payment_due'] == $current_date){
	
				$value['p_due_color'] = "current";
				
				/* khuyenpv 15.07.2015 -> don't need to set payment warning for domistic sales
				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'high';
				}*/
	
			} else if ($value['payment_due'] > $current_date && $value['payment_due'] <= $limit_date){
	
				$value['p_due_color'] = "near";
	
				if ($value['payment_due'] == $tomorrow){
						
					$value['p_due_color'] = "current";
						
				}
				
				/* khuyenpv 15.07.2015 -> don't need to set payment warning for domistic sales
				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'normal';
				}*/
			}

			/* khuyenpv 15.07.2015 -> don't need to set payment warning for domistic sales
			if ($value['payment_warning'] != ""){
	
				$value['payment_warning_message'] = '<table><tr><td class="current"><b>'.bpv_format_date($value['payment_due'], DATE_FORMAT).'</b>:</td>'. '<td class="current"><b>$'.number_format($value['payment_amount'], CURRENCY_DECIMAL).'</b></td></tr></table>';
	
			}*/
				
			$value['status_color'] = "";
				
			switch ($value['status']) {
				case 1:
					$value['status_color'] = "new";
					break;
				case 2:
					$value['status_color'] = "pending";
					break;
				case 3:
					$value['status_color'] = "deposited";
					break;
				case 4:
					$value['status_color'] = "fully_paid";
					break;
				case 5:
					$value['status_color'] = "cancelled";
					break;
				case 6:
					$value['status_color'] = "close_win";
					break;
				case 7:
					$value['status_color'] = "close_lost";
					break;
				default:
					$value['status_color'] = "";
					break;
			}
	
			/**
			 * Set service reservation color
			 */
			$value = $this->set_reservation_services_color($value);

			/**
			 * Khuyenpv 15.07.2015
			 * if the flight booking is Paid & the ticket is not booked until 20h after booking time -> show warning for sale to book the ticket
			 */
			$value['ticket_booked_warnign'] = false;
			if ($value['status'] == BOOKING_DEPOSIT || $value['status'] == BOOKING_FULL_PAID || $value['status'] == BOOKING_CLOSE_WIN){
				
				$time_now = time();
				$time_book = strtotime($value['request_date']);
				
				if($time_now - $time_book >= 20 * 60 * 60){
					
					foreach($value['service_reservations'] as $k=>$v){

						if($v['reservation_type'] == RESERVATION_TYPE_FLIGHT && !in_array($v['reservation_status'], array(RESERVATION_DEPOSIT, RESERVATION_FULL_PAID, RESERVATION_CLOSE_WIN))){
							$value['ticket_booked_warnign'] = true;
							break;
						}
						
					}
					
				}

			}
			
			/**
			 * Khuyenpv 15.07.2015
			 * ticket customer payment warning: after 20h, if the booking is still new or pending => show warning on Request Date
			 * Sale have to ask customer for payment
			 */
			$value['ticket_payment_color'] = "";
			if ($value['status'] == BOOKING_NEW || $value['status'] == BOOKING_PENDING){
			
				$time_now = time();
				$time_book = strtotime($value['request_date']);
				
				// after 12h - 20h => yellow warning
				if($time_now - $time_book >= 12 * 60 * 60 && $time_now - $time_book < 20 * 60 * 60){
				
					foreach($value['service_reservations'] as $k=>$v){
							
						if($v['reservation_type'] == RESERVATION_TYPE_FLIGHT){
							$value['ticket_payment_color'] = "deposited";
							break;
						}
							
					}
				
				}
				
				// after 20h => red warning
				if($time_now - $time_book >= 20 * 60 * 60){
					foreach($value['service_reservations'] as $k=>$v){
			
						if($v['reservation_type'] == RESERVATION_TYPE_FLIGHT){
							$value['ticket_payment_color'] = "pending";
							break;
						}
			
					}
						
				}
			}
				
			
				
			$value['reservation_color'] = $this->_getReservationColor($value['service_reservations']);
				
			$data['customer_bookings'][$key] = $value;
		}
		return $data;
	}
	
	function set_reservation_services_color($value, $show_short_sr = true) {
	
		$near_future_config = $this->config->item('near_future_day');
	
		$current_date = date(DB_DATE_FORMAT);
		$limit_date = strtotime($current_date . " +". $near_future_config. " day");
	
		$limit_date = date('Y-m-d', $limit_date);
	
		$tomorrow = strtotime($current_date . " +1 day");
	
		$tomorrow = date('Y-m-d', $tomorrow);
	
	
		foreach($value['service_reservations'] as $k=>$v){
			$v['color'] = "";
	
			if ($v['end_date'] < $current_date){
	
				$v['color'] = "past";
	
			} else if ($v['start_date'] <= $current_date  && $v['end_date'] >= $current_date){
	
				$v['color'] = "current";
	
			} else if ($v['start_date'] > $current_date && $v['start_date'] <= $limit_date){
	
				$v['color'] = "near";
	
				if ($v['start_date'] == $tomorrow){
	
					$v['color'] = "current";
	
				}
			}
	
			$v['warning'] = "no";
				
			$v['warning_message'] = "";
				
				
			// transfer remider
			if (($value['status'] == BOOKING_DEPOSIT || $value['status'] == BOOKING_FULL_PAID) &&
			($v['reservation_status'] == RESERVATION_NEW || $v['reservation_status'] == RESERVATION_SENDING
					|| $v['reservation_status'] == RESERVATION_RESERVED || $v['reservation_status'] == RESERVATION_DEPOSIT || $v['reservation_status'] == RESERVATION_FULL_PAID)){
	
				if ($v['reservation_type'] == RESERVATION_TYPE_TRANSFER && $v['reviewed'] == 0 && $v['start_date'] <= $tomorrow){
						
					$v['warning'] = "high";
						
					$v['warning_message'] = "<b class='current'>Transfer Remider: ". bpv_format_date($v['start_date'], DATE_FORMAT).'</b>';
						
				}
			}
				
				
				
				
			if ($v['payment_type'] == 2 || $v['payment_type'] == 0){ // per-booking payment or has not been set
					
				if ($v['reservation_status'] == RESERVATION_RESERVED){// reserved
						
					if ($v['1_payment_due'] > $tomorrow && $v['1_payment_due'] <= $limit_date){
						$v['warning'] = "normal";
					}
						
					if ($v['2_payment_due'] > $tomorrow && $v['2_payment_due'] <= $limit_date){
						$v['warning'] = "normal";
					}
						
					if ($v['1_payment_due'] != '' && $v['1_payment_due'] <= $tomorrow){
						$v['warning'] = "high";
					}
						
					if ($v['2_payment_due'] != '' && $v['2_payment_due'] <= $tomorrow){
						$v['warning'] = "high";
					}
				}
					
				if ($v['reservation_status'] == RESERVATION_DEPOSIT){// deposit
						
					if ($v['2_payment_due'] > $tomorrow && $v['2_payment_due'] <= $limit_date){
						$v['warning'] = "normal";
							
						$is_second_bold = true;
					}
						
					if ($v['2_payment_due'] != '' && $v['2_payment_due'] <= $tomorrow){
						$v['warning'] = "high";
					}
				}
					
					
					
				$is_first_bold = $v['1_payment_due'] >= $current_date;
					
				$is_second_bold = $v['1_payment_due'] < $current_date && $v['2_payment_due'] >= $current_date;
					
				$first_color = $this->_getColorClassByTime($v['1_payment_due'], $current_date, $limit_date, $tomorrow);
					
				$second_color = $this->_getColorClassByTime($v['2_payment_due'], $current_date, $limit_date, $tomorrow);
					
				$v['warning_message'] = "<table><tr><td class='".$first_color."'>";
					
				if ($is_first_bold){
					$v['warning_message'] = $v['warning_message']."<b>";
				}
					
				$v['warning_message'] = $v['warning_message'].bpv_format_date($v['1_payment_due']);
					
				if ($is_first_bold){
					$v['warning_message'] = $v['warning_message']."</b>";
				}
					
				$v['warning_message'] = $v['warning_message'].":</td><td class='".$first_color."'>";
					
				if ($is_first_bold){
					$v['warning_message'] = $v['warning_message']."<b>";
				}
					
				$v['warning_message'] = $v['warning_message']."$ ".number_format($v['1_payment'], CURRENCY_DECIMAL);
					
				if ($is_first_bold){
					$v['warning_message'] = $v['warning_message']."</b>";
				}
					
				$v['warning_message'] = $v['warning_message']."</td></tr>";
					
				if($v['2_payment_due'] != "" && $v['2_payment'] != 0){
						
					$v['warning_message'] = $v['warning_message']."<tr><td class='".$second_color."'>";
						
					if ($is_second_bold){
						$v['warning_message'] = $v['warning_message']."<b>";
					}
						
					$v['warning_message'] = $v['warning_message'].bpv_format_date($v['2_payment_due']);
						
					if ($is_second_bold){
						$v['warning_message'] = $v['warning_message']."</b>";
					}
						
					$v['warning_message'] = $v['warning_message'].":</td><td class='".$second_color."'>";
						
					if ($is_second_bold){
						$v['warning_message'] = $v['warning_message']."<b>";
					}
					$v['warning_message'] = $v['warning_message']."$ ".number_format($v['2_payment'], CURRENCY_DECIMAL);
						
					if ($is_second_bold){
						$v['warning_message'] = $v['warning_message']."</b>";
					}
						
					$v['warning_message'] = $v['warning_message']."</td></tr>";
				}
					
				$v['warning_message'] = $v['warning_message']."</table>";
					
				if ($v['1_payment_due'] == "" && $v['1_payment'] == 0 && $v['2_payment_due'] == "" && $v['2_payment'] == 0){
					$v['warning_message'] = "";
				}
					
			}
				
			// reservation warning
			if (($value['status'] == BOOKING_DEPOSIT || $value['status'] == BOOKING_FULL_PAID || $value['status'] == BOOKING_CLOSE_WIN) && ($v['reservation_status'] == RESERVATION_NEW || $v['reservation_status'] == RESERVATION_SENDING)){
	
				if ($v['reserved_date'] != ''){
	
					if ($v['reserved_date'] <= $tomorrow){
							
						$v['warning'] = "high";
	
						$v['warning_message'] = "<b class='current'>R-Due: ". bpv_format_date($v['reserved_date'], DATE_FORMAT).'</b>';
	
					}
				}
			}
			
			// if the flight booking is Paid => warning if the SR has not change
			if ($value['status'] == BOOKING_DEPOSIT || $value['status'] == BOOKING_FULL_PAID || $value['status'] == BOOKING_CLOSE_WIN){
				
				if($v['reservation_type'] == RESERVATION_TYPE_FLIGHT && !in_array($v['reservation_status'], array(RESERVATION_DEPOSIT, RESERVATION_FULL_PAID, RESERVATION_CLOSE_WIN))){
					$v['warning'] = "high";
					
					$v['warning_message'] = "<b class='current'>".lang('ticket_booked_now')."</b>";
				}
			}
	
			$v['re_color'] = "r_new";
			if ($v['reservation_status'] == 1 || $v['reservation_status'] == 2 || $v['reservation_status'] == 3){
	
			} else if ($v['reservation_status'] == 5){
				$v['re_color'] = "blocked";
			} else if ($v['reservation_status'] == 6 || $v['reservation_status'] == 7){
				$v['re_color'] = "reserved";
			} else if ($v['reservation_status'] == 4){
				$v['re_color'] = "r_cancelled";
			}
	
			if (strlen($v['service_name']) > 37 && $show_short_sr){
				$v['service_name'] = substr($v['service_name'], 0, 47);
				$v['service_name'] = $v['service_name'].'...';
			}
	
			$value['service_reservations'][$k] = $v;
		}
	
		return $value;
	}
	
	function _getColorClassByTime($date, $current_date, $limit_date, $tomorrow){
	
		$ret = "";
	
		if ($date < $current_date){
				
			$ret = "past";
				
		} else if ($date == $current_date  || $date == $tomorrow){
				
			$ret = "current";
				
		} else if ($date > $tomorrow && $date <= $limit_date){
				
			$ret = "near";
		}
	
		return $ret;
	}
	
	function _getReservationColor($service_reservations){
	
		$css_class = "";
	
		$is_new = true;
	
		foreach ($service_reservations as $value){
				
			if ($value['reservation_status'] != 1){
	
				$is_new = false;
	
				break;
			}
		}
	
		$is_reserved = true;
	
		foreach ($service_reservations as $value){
				
			if ($value['reservation_status'] != 3 && $value['reservation_status'] != 4){
	
				$is_reserved = false;
	
				break;
			}
		}
	
		if ($is_new){
			$css_class = "";
		} else if ($is_reserved){
			$css_class = "reserved";
		} else {
			$css_class = "blocked";
		}
	
		return $css_class;
	}
	
	function _set_report_info($data){
		$data['total_cb'] = $data['total_rows'];
		
		$total_paxs = $this->Booking_Model->countTotalPax($data['search_criteria']);
		
		if(count($total_paxs) > 0){
		
			$total_pax = $total_paxs[0];
		
			$data['total_pax'] = $total_pax['adults'] + $total_pax['children'] + $total_pax['infants'];
		
			$data['total_adults'] = $total_pax['adults'];
		
			$data['total_children'] = $total_pax['children'];
		
			$data['total_infants'] = $total_pax['infants'];
		
			$data['total_review'] = $total_pax['send_review'];
		
		} else {
			$data['total_pax'] = 0;		
				
			$data['total_adults'] = 0;
			
			$data['total_children'] = 0;
			
			$data['total_infants'] = 0;
			
			$data['total_review'] = 0;
		}
		
		return $data;
	}
	
	function _reset() {
	
		$this->session->unset_userdata(CB_SEARCH_CRITERIA);
	
	}
	
	function showHideActualSellProfit() {
	
		$search = $this->session->userdata(CB_SEARCH_CRITERIA);
	
		$td_column = $this->input->post('td_column');
	
		$td_show = $this->input->post('td_show');
	
		$search[$td_column] = $td_show;
	
		$this->session->set_userdata(CB_SEARCH_CRITERIA, $search);
	}
	
	public function _set_cb_paging_info($total_rows, $data = array()){
	
		$offset = $this->uri->segment(PAGING_SEGMENT);
		
		$per_page = $this->config->item('cb_per_page');
	
		$paging_config = get_paging_config($total_rows,'bookings/',PAGING_SEGMENT, $per_page);
		// initialize pagination
		$this->pagination->initialize($paging_config);
	
	
		$paging_info['paging_text'] = get_paging_text($total_rows, $offset, $per_page);
	
		$paging_info['paging_links'] = $this->pagination->create_links();
	
		$data['paging_info'] = $paging_info;
	
		return $data;
	
	}
	
	public function export_excel(){
		
		$status = $this->config->item('booking_status');
		
		$payment_methods = $this->config->item('payment_methods');
		
		$this->load->library('excel');
			
		$customer_bookings = $this->Booking_Model->search_cb_for_export_excels();
		
		$customer_bookings = $this->_filter_duplicate_cb($customer_bookings);
		
		
		$this->load->library('excel');
		$sheet = new PHPExcel();
		
		
		$sheet->getProperties()->setTitle('Customer Bookings')->setDescription('Customer Bookings');
		$sheet->setActiveSheetIndex(0);
		
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(0, 1, '#');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Customer');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'City');
		
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Service Reservation');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'Request Date');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Start Date');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'Status');
	
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'Sale');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'Payment Method');
		
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'NET');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'SELL');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'PROFIT');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'Note');
		
		$row = 2;
		foreach ($customer_bookings as $cb) {
			
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $cb['id']);
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $cb['full_name'] .' ('.$cb['email'].')');
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $cb['city']);
			
			$sr_str ='';
			foreach ($cb['service_reservations'] as $key => $sr){
				$sr_str .= bpv_format_date($sr['start_date'], DATE_FORMAT);
				$sr_str .= ': '.$sr['service_name']."\n";
				
			}
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $sr_str);
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(4, $row, bpv_format_date($cb['request_date'], DATE_TIME_FORMAT));
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(5, $row, bpv_format_date($cb['start_date']));
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(6, $row, lang($status[$cb['status']]));
			
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $cb['sale']);
			
			if(isset($payment_methods[$cb['payment_method']])){
				$sheet->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $payment_methods[$cb['payment_method']]);
			}
			
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(9, $row, $cb['net_price']);
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(10, $row, $cb['selling_price']);
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(11, $row, $cb['selling_price'] - $cb['net_price']);
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(12, $row, $cb['note']);
			
			$row++;
		}
		
		
		$sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
		
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="customer_bookings_'.date('dmY').'.xls"');
		header('Cache-Control: max-age=0');
		$sheet_writer->save('php://output');
		
	}
	
	function _filter_duplicate_cb($cbs){
		
		$ret = array();
		
		$emails = array();
		
		foreach ($cbs as $cb){
			
			$emails[trim($cb['email'])] = array();
			
		}
		
		foreach ($emails as $email=>$arr){
			
			$cb_arr = array();
			
			foreach ($cbs as $cb){
				if(trim($cb['email']) == $email){
					$cb_arr[] = $cb;	
				}
			}
			
			if(count($cb_arr) == 1){ // only one booking
				
				$ret[] = $cb_arr[0];
				
			}elseif(count($cb_arr) > 1){
				
				$booked = array();
				
				$has_city = array();
			
				
				foreach ($cb_arr as $cb){
					if(in_array($cb['status'], array(3, 4, 6))){ // deposit, full paid, close win
						$booked[] = $cb;
					}
				}
				
				foreach ($cb_arr as $cb){	
					if(!empty($cb['city'])){ // has city
						$has_city[] = $cb;
					}		
				}
				
				if(count($booked) == 1){ // only one booked
					
					$ret[] = $booked[0];
					
				} elseif(count($booked) > 1){
					
					$has_city_booked = false;
					
					foreach ($booked as $cb){
						
						if(!empty($cb['city'])){ // has city
							
							$ret[] = $cb;
							
							$has_city_booked = true;
							
							break;
						}
						
					}
					
					if(!$has_city_booked){
						
						$ret[] = $booked[0];
						
					}
				
				} else {
					
					if(count($has_city) > 0){
						
						$ret[] = $has_city[0];
						
					} else {
						
						$ret[] = $cb_arr[0];
					}
					
				}
				
				
			}
			
		}
		
		
		return $ret;
		
	}

	
	function overview($id){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_BOOKING);
		
		$data['page_css'] = get_static_resources('booking.css');
		$data['page_js'] = get_static_resources('booking.js');
		
		
		$data = $this->_set_data_for_edit($data, $id, 3);
		
		
		
		$data['c_titles'] = $this->config->item('c_titles');
		
		$data['contact'] = $this->Booking_Model->get_booking_contact_info($id); 
		
		$data['passengers'] = $this->Booking_Model->get_flight_users($id);
		
		$data['cb'] = $this->Booking_Model->get_cb_overview($id);
		
		$data['srs'] = $this->Booking_Model->get_sr_overview($id);
			
		
		$data['site_title'] = lang('title_booking_overview'). ' ('.$id . ' - '.$data['cb']['vnisc_booking_code'] .')';
		
		$data['content'] = $this->load->view('bookings/ticket/flight_booking_overview', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	
	function tform($id){
		
		$this->load->library('dompdf_gen');
		$this->load->helper('file');
		$this->load->helper('email');
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_BOOKING);
		
		$data['page_css'] = get_static_resources('booking.css');
		$data['page_js'] = get_static_resources('booking.js');
		
		
		$data = $this->_set_data_for_edit($data, $id, 4);
		
		
		
		$data['c_titles'] = $this->config->item('c_titles');
		
		$data['contact'] = $this->Booking_Model->get_booking_contact_info($id);
		
		$data['passengers'] = $this->Booking_Model->get_flight_users($id);
		
		$data['cb'] = $this->Booking_Model->get_cb_overview($id);
		
		$data['srs'] = $this->Booking_Model->get_sr_overview($id);
			
		
		
		$action = $this->input->post('action');
		
		$data['action'] = $action;
		
		$data['tform_content'] = $this->load->view('bookings/ticket/flight_ticket_form', $data, TRUE);
		
		$pdf_file_name = 'Ve_may_bay_Bestprice_'.$id;
		
		if ($action == 'download'){
			
			$html = $this->load->view('bookings/ticket/flight_ticket_pdf_wraper', $data, TRUE);
			
			//echo $html; exit();
			
			$this->dompdf_gen->pdf_create($html, $pdf_file_name);
			
		}
		
		if ($action == 'email'){
			
			$email = $data['contact']['email'];
			
			if (valid_email($email)){
					
				$html = $this->load->view('bookings/ticket/flight_ticket_pdf_wraper', $data, TRUE);
				
				//$directory_path = FCPATH . 'ticket/';
				 
				//$directory_path = str_replace('/admin', '', $directory_path);
				
				$directory_path = '/home/bestprice/public_html/ticket/';
				
				//echo $directory_path; exit();
				
				$pdf_name = $directory_path. $pdf_file_name.'.pdf';
				
				$pdf_file = $this->dompdf_gen->pdf_create($html, $pdf_name, false);
					
				$status = write_file($pdf_name, $pdf_file);
				
				//echo $pdf_name; exit();
				
				if ($status){
					
					$status_send = $this->send_flight_ticket($data, $pdf_name);
					
					if ($status_send){

						$send_message['type'] = 1; // success
						
						$send_message['message'] = lang('tf_send_email_sucess').$email;
						
					} else {
						
						$send_message['type'] = 2; // error
						
						$send_message['message'] = lang('tf_email_failed'). $email;
					}
				} else {
					
					$send_message['type'] = 2; // error
						
					$send_message['message'] = lang('tf_email_failed'). $email;
				}
				
				
			} else {
				
				$send_message['type'] = 2; // error
				
				$send_message['message'] = lang('tf_email_invalid');
				
			}
			
			$data['send_message'] = $send_message;
		}
		
		$data['site_title'] = lang('tf_ticket_form'). ' ('.$id . ' - '.$data['cb']['vnisc_booking_code'] .')';
		
		$data['content'] = $this->load->view('bookings/ticket/flight_ticket_fc_wrapper', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	function send_flight_ticket($data, $pdf_name){
		
		$contact = $data['contact'];
		
		$cb = $data['cb'];
		
		$this->load->library('email');
		
		$config = array();
		
		$config['protocol']='smtp';
		$config['smtp_host']='ssl://smtp.googlemail.com';
		$config['smtp_port']='465';
		$config['smtp_timeout']='30';
		$config['smtp_user']='booking@Bestviettravel.xyz';
		$config['smtp_pass']='Bpt052010';
		
		$config['charset']='utf-8';
		$config['newline']="\r\n";
		$config['mailtype'] = 'html';
		
		// send to customer
		$this->email->initialize($config);
		
		$this->email->from($cb['email'], 'BestPrice Vietnam');
		
		$this->email->reply_to($cb['email']);
		
		$this->email->to($contact['email']);
		
		$this->email->bcc($cb['email']);
		
		$this->email->attach($pdf_name);
		
		$subject = lang_arg('tf_email_subject', $cb['flight_from'], $cb['flight_to']);
		
		if (!empty($cb['flight_return'])){
			$subject .= lang('tf_email_type_return');
		}
		
		$subject .= ' - '.lang_arg('tf_email_depart', bpv_format_date($cb['flight_depart'], 'd/m/Y', true));
		
		if (!empty($cb['flight_return'])){
			$subject .= ' - '.lang_arg('tf_email_return', bpv_format_date($cb['flight_return'], 'd/m/Y', true));
		}
		
		$subject .= ' - BestPrice';
		
		
		$data['action'] = 'email_content';
		$data['tform_content'] = $this->load->view('bookings/ticket/flight_ticket_form', $data, TRUE);
		
		
		
		$content = $this->load->view('bookings/ticket/flight_ticket_email_wrapper', $data, TRUE);
		
		$this->email->subject($subject);
		
		$this->email->message($content);
		
		//$this->email->attach('../media/bestpricevn-logo.31052014.png','inline');
		
		if (!$this->email->send()){
			
			log_message('error', '[ERROR]SEND TICKET EMAIL: Can not send email to '.$contact['email']);
			
			return false;
		}
		
		return true;
		
	}
	
	function export_customer_data(){
		
		$invalid_emails = $this->config->item('invalid_customer_emails');
		
		$invalid_phones = $this->config->item('invalid_customer_phones');
		
		
		// 1. get all the valid customer
		
		$customers = $this->Booking_Model->search_customer_data_for_marketing($invalid_emails, $invalid_phones);
		
		
		$nr_customer = count($customers);
		
		
		$nr_voucher_new_in_db = $this->Marketing_Model->get_nr_voucher_new();
		
		
		$nr_voucher_new_create = $nr_customer - $nr_voucher_new_in_db; 
		
		
		if($nr_voucher_new_create > 0){
			
			$vocher_data['amount'] = 100000; // 100,000 VND
			
			$vocher_data['expired_date'] = '2014-12-31';
			
			$vocher_data['number_voucher'] = $nr_voucher_new_create;
			
			$this->Marketing_Model->create_voucher($vocher_data);
			
		}
			
		$vouchers = $this->Marketing_Model->get_new_added_vouchers();
		
		
		// 5. Export to excel, assign each customer has 1 voucher code
		$this->load->library('excel');
		$sheet = new PHPExcel();
		
		
		$sheet->getProperties()->setTitle('List BPV Customers')->setDescription('List BPV Customers');
		$sheet->setActiveSheetIndex(0);
		
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'ID');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'Full Name');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Last Name');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Email');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'Phone');
		$sheet->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Voucher Code');

		
		$row = 2;
		foreach ($customers as $key => $cus) {
			
			$array_name = explode(" ", trim($cus['full_name']));
			$last_name = $array_name[count($array_name)-1];
			
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $key + 1);
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(1, $row, convert_unicode($cus['full_name']));
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(2, $row, convert_unicode($last_name));
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $cus['email']);
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $cus['phone']);
			
			$sheet->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $vouchers[$key]['code']);
			
			$row++;
		}
		
		$sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
		
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="list_bpv_customers_'.date('dmY').'.xls"');
		header('Cache-Control: max-age=0');
		$sheet_writer->save('php://output');
		
	}
}


/* End of file welcome.php */
/* Location: ./application/controllers/bookings.php */