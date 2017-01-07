<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_Reservation extends CI_Controller {
	
	public function __construct()
    {
        
       	parent::__construct();			
		$this->load->model(array('CustomerModel','PartnerModel'));
		$this->load->helper('url');
		$this->load->language(array('customer','partner'));
		$this->load->library('form_validation');
		$this->load->library('pagination');	
		$this->load->helper('form');
		$this->load->helper('common');
		$this->auth->checkLogin();
		$this->auth->checkPermission(false);
		
		//$this->output->enable_profiler(TRUE);
	}
		
		
	function _setValidationRules()
	{
		$this->form_validation->set_error_delimiters('<br><label class="error">', '</label></br>');
		
		$this->form_validation->set_rules($this->config->item('service_reservation'));
		
		$re_type = $this->input->post('reservation_type');
		
		if ($re_type == RESERVATION_TYPE_CRUISE_TOUR){
			
			$this->form_validation->set_rules('cabin_booked', 'lang:cabin_booked', 'required');
		}
		
		if ($re_type == RESERVATION_TYPE_VISA){
			
			$this->form_validation->set_rules('type_of_visa', 'lang:type_of_visa', 'required');
			
			$this->form_validation->set_rules('processing_time', 'lang:processing_time', 'required');
		}
		
		$origin = $this->input->post('origin');
		
		if ($origin != ''){
			$this->form_validation->set_rules('selling_price', 'lang:selling_price', 'numeric|callback_sell_check_origin');
		} elseif($re_type != RESERVATION_TYPE_OTHER){
			$this->form_validation->set_rules('selling_price', 'lang:selling_price', 'numeric|callback_sell_check_normal');
		}
	
	}
	
	
	function index()
	{		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_CUSTOMER_REQUEST);
		
		$action = $this->input->post('action_type');
	
		$service_reservation = '';
		
		if ($action == 'save_create') {
			
			$id = $this->_create();
			
			if ($id != ''){

				$customer_booking_id = $this->input->post('customer_booking_id');
				
				redirect(site_url('customer/service_reservation/index/'.$customer_booking_id));
			}
			
		} else if ($action == 'save_edit'){
						
			$id = $this->_edit();
			
			if ($id != ''){
				
				$customer_booking_id = $this->input->post('customer_booking_id');
				
				redirect(site_url('customer/service_reservation/index/'.$customer_booking_id));
				
			} else {
			
				$service_reservation = $this->_getServiceReservation();
			
			}
		} else if ($action == 'view' || $action == 'edit'){
			
			$service_reservation = $this->_getServiceReservation();
			
		} else if ($action == 'reset'){
						
			$this->_reset();
			
		} else if ($action == 'delete'){
			
			$this->_delete();
			
		}
		
		$data = array();
	
		if ($action == 'advanced_search' || $action == 'search' || $action == 'reset' || $action == 'delete' || $action=='')
		{	
			$data = $this->_listServiceReservations($action);
		}
		
		$this->_setDataForm($data, $action, $service_reservation);
	}
	
	function _listServiceReservations($action){		
		
		$data = array();
		
		$data['sales'] = $this->CustomerModel->getUsers();
		
		$data['countries'] = $this->config->item('countries');
		
		$customer_booking_id = (int)$this->uri->segment(4);
		
		$customer_booking = "";
		
		if ($customer_booking_id != ""){
			
			$customer_booking = $this->CustomerModel->getCustomerBooking($customer_booking_id);
			
			$data['customer_booking'] = $customer_booking;
		}
	
		
		$search_criteria = $this->_buildSearchCriteria($action, $customer_booking);
		
		if (!isset($search_criteria['customer_booking_id']) && $customer_booking_id != "" && $customer_booking_id != 0){
			redirect(site_url('customer/service_reservation/index/'));
		}
		
		$data['search_criteria'] = $search_criteria;
		
	
		// set data for pagination		
		$data['total_rows'] = $this->CustomerModel->getNumServiceReservation($search_criteria);			
		
		$offset = $this->uri->segment(5);
		
		// do searching for customer reviews
		$data['service_reservations'] = $this->CustomerModel->searchServiceReservation(
						$search_criteria
						, $this->config->item('cb_per_page')
						, (int)$offset);
		
		$data['service_reservations'] = $this->_setStatusColor($data['service_reservations']);
		
		// initialize pagination
		$this->pagination->initialize(
						get_paging_config_cb($data['total_rows']
							, 'customer/service_reservation/index/'.$customer_booking_id
							, 5));
		$data['paging_text'] = get_paging_text_cb($data['total_rows'], $offset);
		
		
		$data = $this->_setSUM($data);
		
		return $data;
	}
	
	function _setStatusColor($service_reservations){
		
		$near_future_config = $this->config->item('near_future_day');
			
		//$current_date = date('Y-m-d');
		
		$current_date = $this->_getCurrentDate();
		
		$limit_date = strtotime($current_date . " +". $near_future_config. " day");
	
		$limit_date = date('Y-m-d', $limit_date);
		
		$tomorrow = strtotime($current_date . " +1 day");
		
		$tomorrow = date('Y-m-d', $tomorrow);
		
		foreach ($service_reservations as $key => $value){			
			
			/*
			 * Set start-date color
			 */
			$value['start_date_color'] = "";
			
			if ($value['end_date'] < $current_date){
				
				$value['start_date_color'] = "past_booking";
				
			} else if ($value['start_date'] <= $current_date  && $value['end_date'] >= $current_date){
				
				$value['start_date_color'] = "current_booking";
				
			} else if ($value['start_date'] > $current_date && $value['start_date'] <= $limit_date){
				
				$value['start_date_color'] = "near_booking";
				
				if ($value['start_date'] == $tomorrow){
					
					$value['start_date_color'] = "current_booking";
					
				}
			}
			
			/**
			 * payment due color
			 */
			$value['p_due_color_1'] = "";
			
			if ($value['1_payment_due'] != ""){
				
				if ($value['1_payment_due'] < $current_date){
					
					$value['p_due_color_1'] = "past_booking";
					
				} else if ($value['1_payment_due'] == $current_date){
					
					$value['p_due_color_1'] = "current_booking";
					
				} else if ($value['1_payment_due'] > $current_date && $value['1_payment_due'] <= $limit_date){
					
					$value['p_due_color_1'] = "near_booking";
					
					if ($value['1_payment_due'] == $tomorrow){
						
						$value['p_due_color_1'] = "current_booking";
						
					}
				}
			}
			
			/**
			 * payment due color
			 */
			$value['p_due_color_2'] = "";
			
			if ($value['2_payment_due'] != ""){
				
				if ($value['2_payment_due'] < $current_date){
					
					$value['p_due_color_2'] = "past_booking";
					
				} else if ($value['2_payment_due'] == $current_date){
					
					$value['p_due_color_2'] = "current_booking";
					
				} else if ($value['2_payment_due'] > $current_date && $value['2_payment_due'] <= $limit_date){
					
					$value['p_due_color_2'] = "near_booking";
					
					if ($value['2_payment_due'] == $tomorrow){
						
						$value['p_due_color_2'] = "current_booking";
						
					}
				}
			}
			
			/**
			 * set status color 
			 */
			
			$value['status_color'] = "";
			
			switch ($value['reservation_status']) {
				case 1:
					$value['status_color'] = "new";
					break;						
				case 2:
					$value['status_color'] = "deposited";
					break;				
				case 3:
					$value['status_color'] = "fully_paid";
					break;				
				case 4:
					$value['status_color'] = "cancelled";					
					break;				
				case 5:
					$value['status_color'] = "deposited";
					break;
				case 6:
				case 7:
					$value['status_color'] = "fully_paid";
					break;
				default:
					$value['status_color'] = "";
					break;
			}
			
			$service_reservations[$key] = $value;
			
		}
		
		return $service_reservations;
	}
	
	function _setSUM($data){	
		
		$current_user = $this->app_context->current_user;
		
		$total_money = $this->CustomerModel->countTotalSRMoney($data['search_criteria'], $current_user);
		
		$data['net'] = $total_money[0]['net_price'];
		$data['sel'] = $total_money[0]['selling_price'];
		
		$data['r_payment'] = $this->CustomerModel->countRemainingPayment($data['search_criteria']);
		
		$room_night = $this->CustomerModel->count_room_night($data['search_criteria']);
		
		$data['room_night'] = $room_night['room_night'];
		
		$data['room_night_incentive'] = $room_night['room_night_incentive'];
		
		
		$data['total_sr'] = $data['total_rows'];
		
		$total_pax = $this->CustomerModel->countTotalPaxSR($data['search_criteria']);
		
		$data['total_pax'] = $total_pax['adults'] + $total_pax['children'] + $total_pax['infants'];
		
		$data['total_adults'] = $total_pax['adults'];
		
		$data['total_children'] = $total_pax['children'];
		
		$data['total_infants'] = $total_pax['infants'];
		
		$data['total_review'] = $total_pax['send_review'];
		
		return $data;
	}
	
	function _setDataForm($data, $action, $service_reservation)
	{		
		$customer_booking_id = $this->uri->segment(4);
		
		$data['atts'] = get_popup_config('email');
		
		if($action == 'view') {
			
			$data['site_title'] = $this->lang->line('view_service_reservation');
			
			$data['sub_header'] = $this->lang->line('view_service_reservation');
			
			$service_reservation_id = $this->input->post('service_reservation_id');
			
			$data['service_reservation_id'] = $service_reservation_id;
			
		} elseif ($action == 'edit'){
			
			$data['site_title'] = $this->lang->line('edit_service_reservation');
			
			$data['sub_header'] = $this->lang->line('edit_service_reservation');
			
			$service_reservation_id = $this->input->post('service_reservation_id');
			
			$data['service_reservation_id'] = $service_reservation_id;
			
			
			
			if ($customer_booking_id != ""){
				
				$customer_booking = $this->CustomerModel->getCustomerBooking($customer_booking_id);
				
				$this->_setCustomerData($customer_booking);
			}
	
			
		} elseif ($action == 'create'){
			
			$search_criteria = $this->_buildSearchCriteria($action);
		
			$data['search_criteria'] = $search_criteria;
			
			$data['site_title'] = $this->lang->line('create_service_reservation');
			
			$data['sub_header'] = $this->lang->line('create_service_reservation');
		
		} else {
			
			$search_criteria = $this->session->userdata("service_reservation_search_criteria");
			
			$data['search_criteria'] = $search_criteria;
			
			$data['site_title'] = $this->lang->line('list_service_reservation');
			
			$data['sub_header'] = $this->lang->line('list_service_reservation');
			
			$data['booked_countries'] = $this->CustomerModel->get_country_of_booking_customer();
			
			$data['countries'] = $this->config->item('countries');
		}
		
		if ($service_reservation != ''){
			
			$data['service_reservation_selected'] = $service_reservation;
			
		}
		
		$data['origins'] = $this->CustomerModel->get_origin_rs($customer_booking_id);
		
		$data['customers'] = $this->CustomerModel->getCustomers();
		
		$data['reservation_type'] = $this->config->item('reservation_type');
		
		$data['reservation_status'] = $this->config->item('reservation_status');

		$data['booking_status'] = $this->config->item('booking_status');
		
		$data['payment_status'] = $this->config->item('payment_status');

		$data['payment_type'] = $this->config->item('payment_type');
		
		$data['cabin_booked'] = $this->config->item('cabin_booked');
		
		$data['pax_booked'] = $this->config->item('pax_booked');
		
		$data['cruises'] = json_encode($this->CustomerModel->getAllCruiseTours());
		
		$data['hotels'] = json_encode($this->CustomerModel->getAllHotels());
		
		$data['tours'] = json_encode($this->CustomerModel->getAllLandTours());
		
		$data['cars'] = json_encode($this->CustomerModel->getAllCars());
		
		$data['partners'] = $this->CustomerModel->getAllPartners();
		
		$data['destinations'] = $this->CustomerModel->get_destination_of_services();
		
		$data['visa_types'] = $this->config->item('visa_types');
		
		$data['visa_processing_times'] = $this->config->item('visa_processing_times');
		
		
		
		$data['booking_sites'] = $this->config->item('booking_sites');
		
		$data['customer_types'] = $this->config->item('customer_types');
		
		$data['request_types'] = $this->config->item('request_types');
		
		$data['booking_sources'] = $this->CustomerModel->get_booking_sources();
		
		$data['mediums'] = $this->config->item('mediums');
		
		$data['campaigns'] = $this->CustomerModel->get_campaigns();
		
		
		$data['action'] = $action;
		
		$data['is_advanced_search'] = $action == 'advanced_search';	
		
		$data['is_accounting'] = $this->app_context->current_user->is_accounting();

		$data['header'] = $this->load->view('template/header', $data, TRUE);
		
		$data['navigation'] = $this->load->view('template/navigation', $data, TRUE);
		
		$data['search'] = $this->load->view('customer/search_service_reservation', $data, TRUE);
			
		$data['advanced_search'] = $this->load->view('customer/advanced_search_sr', $data, TRUE);
			
		
		$data['create_view'] = $this->load->view('customer/create_service_reservation', $data, TRUE);
		
		$data['edit_view'] = $this->load->view('customer/edit_service_reservation', $data, TRUE);
		
		$data['list_view'] = $this->load->view('customer/list_service_reservation', $data, TRUE);
		
		$data['main'] = $this->load->view('customer/service_reservation_main', $data, TRUE);
		
		$data['include_css'] = get_static_resources('jquery-ui.css');
		$data['include_js'] = get_static_resources('jquery-ui.js');
			
		$data['include_js_ext'] = get_static_resources('jquery.qtip.min.js');
		
		$this->load->view('template/template' ,$data);
	}
	
	function _validate()
	{
		$this->_setValidationRules();
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;
	}
	
	function _create()
	{
		if ($this->_validate()) {
			
			$customer_booking_id = $this->input->post('customer_booking_id');
			
			$customer_booking = $this->CustomerModel->getCustomerBooking($customer_booking_id);
			
			if ($customer_booking != ''){
				
				$this->_setCustomerData($customer_booking);
			
				$id = $this->CustomerModel->createServiceReservation($customer_booking['id'], $this->app_context);
				
				return $id;
			
			} else {
				return '';
			}
		} else {
			return '';
		}
	}
	
	
	function _edit()
	{
		
		$status = false;
		
		$id = $this->input->post('service_reservation_id');
		
		if ($this->_validate()) {
			
			$status = $this->CustomerModel->updateServiceReservation($id, $this->app_context);
			
			if ($status){
				$customer_booking_id = (int)$this->uri->segment(4);
			
				//$this->sendEmailNotify($customer_booking_id);
			}
		} 
		
		if ($status) {
			
			return $id;
			
		} else { 
			return '';
		}
	}
	
	function _setCustomerData($customer_booking){
		
		$search_criteria = array();	
		
		if ($this->session->userdata("service_reservation_search_criteria")){
			
			$search_criteria = $this->session->userdata("service_reservation_search_criteria");
			
		}
		
		if ($customer_booking != '') {
			
			$search_criteria['customer_booking_id'] = $customer_booking['id'];
			
			$search_criteria['customer_id'] = $customer_booking['customer_id'];
			
		}
		
		$this->session->set_userdata("service_reservation_search_criteria", $search_criteria);
	}
	
	function _getServiceReservation(){
		
		$service_reservation_id = $this->input->post('service_reservation_id');
		
		$service_reservation = $this->CustomerModel->getServiceReservation($service_reservation_id);
		
		return $service_reservation;
	}

	function _buildSearchCriteria($action, $customer_booking = '') {
		
		$search_criteria = array();	
		
		if ($this->session->userdata("service_reservation_search_criteria")){
			
			$search_criteria = $this->session->userdata("service_reservation_search_criteria");
			
		} 
		
		if (!isset($search_criteria['sort_column'])){
			$search_criteria['sort_column'] = "sr.start_date";
				
			$search_criteria['sort_order'] = "desc";
		}
			
		if ($customer_booking != '') {
			
			$search_criteria['customer_booking_id'] = $customer_booking['id'];
			
			$search_criteria['customer_id'] = $customer_booking['customer_id'];
			
			unset($search_criteria['user_id']);
			
			unset($search_criteria['partner_id']);
			
			unset($search_criteria['arr_reservation_status']);
			
			unset($search_criteria['start_date']);
			
			unset($search_criteria['end_date']);
			
			unset($search_criteria['date_field']);
			
		}
		
		if ($action == 'reset'){
			unset($search_criteria['customer_id']);
			unset($search_criteria['customer_booking_id']);
		}
		
		
		if ($action == 'search') { // build search criteria from _POST
			
			$name = trim($this->input->post('name'));
			if ($name != '') {
				$search_criteria['name'] = $name;
			} else {
				unset($search_criteria['name']);
			}
	
			
			$customer = $this->input->post('customer');			
			if ($customer != '') {								
				$search_criteria['customer_id'] = $customer;						
			} else {
				unset($search_criteria['customer_id']);
			}
			
			$customer_name = trim($this->input->post('customer_auto'));
				
			if ($customer_name != '') {
				$search_criteria['customer_name'] = $customer_name;
			} else {
				unset($search_criteria['customer_name']);
			}
			
			
			$reservation_status = $this->input->post('reservation_status');
			if ($reservation_status != '') {
				
				$search_criteria['reservation_status'] = $reservation_status;
				
			} else {
				unset($search_criteria['reservation_status']);
			}
			
			$user_id =$this->input->post('sale');				
			if ($user_id != '') {
				$search_criteria['user_id'] = $user_id;
			} else {
				unset($search_criteria['user_id']);
			}
			
			//unset($search_criteria['partner_id']);
			
			$sort_column = $this->input->post('sort_column');				
			if ($sort_column != ""){			
				$search_criteria['sort_column'] = $sort_column;			
			} else {
				$search_criteria['sort_column'] = 'sr.start_date';
			}	
			
			$sort_order = $this->input->post('sort_order');				
			if ($sort_order != ""){			
				$search_criteria['sort_order'] = $sort_order;			
			} else {
				$search_criteria['sort_order'] = 'desc';
			}
			
		}
		
		if ($action == "advanced_search"){
			
			//unset($search_criteria['name']);
			
			$name = trim($this->input->post('name_advanced'));
			if ($name != '') {
				$search_criteria['name'] = $name;
			} else {
				unset($search_criteria['name']);
			}
				
			
			unset($search_criteria['customer_id']);
			
			unset($search_criteria['customer_booking_id']);
			
			unset($search_criteria['reservation_status']);
			
			$user_id =$this->input->post('sale_advanced');
			
			if ($user_id != '') {
				$search_criteria['user_id'] = $user_id;
			} else {
				unset($search_criteria['user_id']);
			}
			
			$destination_id =$this->input->post('destination');				
			if ($destination_id != '') {
				$search_criteria['destination_id'] = $destination_id;
			} else {
				unset($search_criteria['destination_id']);
			}
			
			$reservation_type =$this->input->post('reservation_type');				
			if ($reservation_type != '') {
				$search_criteria['reservation_type'] = $reservation_type;
			} else {
				unset($search_criteria['reservation_type']);
			}
			
			$partner_id =$this->input->post('partner');				
			if ($partner_id != '') {
				$search_criteria['partner_id'] = $partner_id;
			} else {
				unset($search_criteria['partner_id']);
			}
			
			
			$country =$this->input->post('country');				
			if ($country != '') {
				$search_criteria['country'] = $country;
			} else {
				unset($search_criteria['country']);
			}
			
			$booking_type =$this->input->post('booking_type');				
			if ($booking_type != '') {
				$search_criteria['booking_type'] = $booking_type;
			} else {
				unset($search_criteria['booking_type']);
			}
			
			
			$arr_reservation_status = $this->input->post('arr_reservation_status');
			
			if ($arr_reservation_status != ""){
			
				$search_criteria['arr_reservation_status'] = $arr_reservation_status;
			
			} else {
				unset($search_criteria['arr_reservation_status']);
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
			
			// booking status & multi partner
			
			$booking_status = $this->input->post('booking_status');
			
			if ($booking_status != '') {
				$search_criteria['booking_status'] = $booking_status;
			} else {
				unset($search_criteria['booking_status']);
			}
			
			$multi_partners = $this->input->post('multi_partners');
			
			if ($multi_partners != '') {
				$search_criteria['multi_partners'] = $multi_partners;
				unset($search_criteria['partner_id']);
			} else {
				unset($search_criteria['multi_partners']);
			}
		}
		
		$this->session->set_userdata("service_reservation_search_criteria", $search_criteria);
		return $search_criteria;
	}
	
	function _reset() {
		
		$this->session->unset_userdata('service_reservation_search_criteria');
		
	}
	
	function _delete()
	{
		$service_reservation_id = $this->input->post('service_reservation_id');
		
		$service_reservation = $this->CustomerModel->getServiceReservation($service_reservation_id); 
		
		if(!empty($service_reservation) && 
				is_allow_deletion($service_reservation['user_created_id'], DATA_SERVICE_RESERVATION, $service_reservation['user_id'])) {
			$this->CustomerModel->deleteServiceReservation($service_reservation_id, $this->app_context);
		} else {
			message_alert(1);	
		}
	}
	
	function sendEmailNotify($cb_id){
		
		$customer_booking = $this->CustomerModel->getCustomerBooking($cb_id);
		
		$login_user_id = $this->app_context->current_user->id;
		
		$login_user_name = $this->app_context->current_user->user_name;
		
		if ($login_user_id != $customer_booking['user_id']){
			
			$data = array();
			
			$data['customer_booking'] = $customer_booking;
			
			$data['login_user_name'] = $login_user_name;
			
			
			$user = $this->CustomerModel->getUser($customer_booking['user_id']);
			
			if ($user != ''){
			
				$data['user'] = $user;
				
				$headers = 'From: ' . 'service@bestpricevn.com' . "\r\n";
			
				$headers .= "Content-type: text/html\r\n";
				
		        $subject = 'Modify: ' . $customer_booking['customer_name'];	
			
				$content = $this->load->view('customer/email_notify', $data, TRUE);
				
				//$this->_sendEmailByGmailAcc($user, $subject, $content);
				
				mail($user['email'], $subject, $content, $headers);
			}
			
		}
	}
	
	function _sendEmailByGmailAcc($user, $subject, $content){
		
		$email_config = $this->config->item('email_config');
		
		$this->load->library('email');
		
		$this->email->initialize($email_config);
		
		$this->email->from('service@bestpricevn.com');
		
		$this->email->to($user['email']);
		
		$this->email->subject($subject);
		
		$this->email->message($content);
		
		$this->email->send();
		
	
	}
	
	function _getCurrentDate(){
		
		date_default_timezone_set("Asia/Saigon");
		
		return date('Y-m-d', time());
	}
	
	function showHideBsSource() {
		
		$search = $this->session->userdata('service_reservation_search_criteria');
		
		$td_column = $this->input->post('td_column');
		
		$td_show = $this->input->post('td_show');
		
		$search[$td_column] = $td_show;
		
		$this->session->set_userdata("service_reservation_search_criteria", $search);
		
		//echo $td_column.' '.$td_show;
	}
	
	function sell_check_origin($sell)
	{
		if ((int)$sell != 0)
		{
			$this->form_validation->set_message('sell_check_origin', 'The %s field must be 0 if the request is included in a package');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function sell_check_normal($sell)
	{
		if ((int)$sell < 0)
		{
			$this->form_validation->set_message('sell_check_normal', 'The %s field must be greater or equal than 0');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function search_partner(){
		
		$partner_name = $this->input->post('partner_name');
		
		$selected_ids = $this->input->post('selected_ids');
		
		echo $this->CustomerModel->search_partner_autocomplete($partner_name, $selected_ids);
		
	}
}

?>
