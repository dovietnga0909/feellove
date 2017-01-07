<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Booking extends CI_Controller {
	
	 public function __construct()
       {
        
       	parent::__construct();			
		$this->load->model('CustomerModel');
		$this->load->helper('url');
		$this->load->language(array('customer','user','partner'));
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
		
		$this->form_validation->set_rules($this->config->item('customer_booking'));
		
		$status = $this->input->post('status');
		
		$met = $this->input->post('met');
		
		if ($status == BOOKING_CANCEL || $status == BOOKING_CLOSE_LOST){
			
			$this->form_validation->set_rules('close_reason', 'lang:close_reason', 'required');
		}
		
		if (($status == BOOKING_FULL_PAID || $status == BOOKING_CLOSE_WIN) && !is_accounting()){
			
			$this->form_validation->set_rules('booking_date', 'lang:booking_date', 'required');
			
		}
		
		if ($met == 1 && !is_accounting()){
			$this->form_validation->set_rules('meeting_date', 'lang:meeting_date', 'required');
		}
		
		if ($status == BOOKING_DEPOSIT || $status == BOOKING_FULL_PAID || $status == BOOKING_CLOSE_WIN){
			
			$this->form_validation->set_rules('adults', 'lang:adults', 'required');
			
		}
		
		if ($status != BOOKING_CLOSE_WIN && $status != BOOKING_CLOSE_LOST && $status != BOOKING_CANCEL){
			
			$this->form_validation->set_rules('request_type', 'lang:request_type', 'required');
			
		}
		
		if ($status != BOOKING_CLOSE_WIN && $status != BOOKING_CLOSE_LOST && $status != BOOKING_CANCEL){
			
			$this->form_validation->set_rules('booking_site', 'lang:booking_site', 'required');
			
		}
	
	}
	
	
	function index()
	{	
		//$this->CustomerModel->create_old_cb_data();
			
		// set session for menues
		$this->session->set_userdata('MENU', MNU_CUSTOMER_REQUEST);
		
		$action = $this->input->post('action_type');
			
		if ($action == 'save_create') {
			
			$id = $this->_create();
			
			if ($id != ''){
				redirect(site_url('customer/customer_booking/index'));
			}
			
		} else if ($action == 'save_edit'){
			
			$id = $this->_edit();
				
			if ($id != ''){
				redirect(site_url('customer/customer_booking/index'));
			}
	
		} else if ($action == 'reset'){
						
			$this->_reset();
		
			
		} else if ($action == 'delete'){
			
			$this->_delete();
	
		}
		$data = '';
		if ($action == 'advanced_search' || $action == 'search' || $action == 'reset' || $action == 'delete' || $action=='')
		{	
			$data = $this->_list($action);
		}
		
		$this->_setDataForm($action, $data);
		
		
	}
	
	function _setDataForm($action, $data='')
	{		
		$id = $this->input->post('cb_id');
		
		if ($id != ''){
			
			$customer_booking = $this->CustomerModel->getCustomerBooking($id);
			
			if ($customer_booking != ''){
				
				$customer_booking['campaign'] = $this->CustomerModel->get_campaign($customer_booking['campaign_id']);
				
				// get list of reservation services
				$customer_booking['service_reservations'] = $this->CustomerModel->_getServiceReservationsByBooking($id);
				$customer_booking = $this->set_reservation_services_color($customer_booking);
				
				// get deleted reservation services
				$customer_booking_del['status'] = $customer_booking['status'];
				$customer_booking_del['service_reservations'] = $this->CustomerModel->_getServiceReservationsByBooking($id, true);
				$customer_booking_del = $this->set_reservation_services_color($customer_booking_del);
				$customer_booking['service_reservations_deleted'] = $customer_booking_del['service_reservations'];
				
				
				$customer_booking['lasted_invoice'] = $this->CustomerModel->get_lasted_invoice_of_cb($id);
				
				$customer_booking['flight_users'] = $this->CustomerModel->get_flight_users($id);
				
				$data['customer_booking'] = $customer_booking;
			}
		}
		
		$data['status'] = $this->config->item('booking_status');

		$data['sales'] = $this->CustomerModel->getUsers();
		
		$data['customers'] = $this->CustomerModel->getCustomers();
		
		$data['reservation_status'] = $this->config->item('reservation_status');
		
		$data['booking_filter'] = $this->config->item('booking_filter');
		
		$data['countries'] = $this->config->item('countries');
		
		$data['approve_status'] = $this->config->item('approve_status');
		
		$data['close_reason'] = $this->config->item('close_reason');
		
		
		$data['booking_sites'] = $this->config->item('booking_sites');
		
		$data['customer_types'] = $this->config->item('customer_types');
		
		$data['request_types'] = $this->config->item('request_types');
		
		$data['booking_sources'] = $this->CustomerModel->get_booking_sources();
		
		$data['mediums'] = $this->config->item('mediums');
		
		$data['campaigns'] = $this->CustomerModel->get_campaigns();
		
		$data['atts'] = get_popup_config('email');	
		
		if($action == 'view') {
			
			$data['site_title'] = $this->lang->line('view_customer_booking');
			
			$data['sub_header'] = $this->lang->line('view_customer_booking');
			
			$data['main'] = $this->load->view('customer/view_customer_booking', $data, TRUE);
			
		} elseif ($action == 'edit' || $action == 'save_edit'){
			
			$data['site_title'] = $this->lang->line('edit_customer_booking');
			
			$data['sub_header'] = $this->lang->line('edit_customer_booking');
			
			$data['main'] = $this->load->view('customer/edit_customer_booking', $data, TRUE);
			
			$data['include_css'] = get_static_resources('jquery-ui.css');
			$data['include_js'] = get_static_resources('jquery-ui.js');
			
			$data['include_js_ext'] = get_static_resources('jquery.qtip.min.js');
			
		} elseif ($action == 'create' || $action == 'save_create') {
		
			$data['site_title'] = $this->lang->line('create_customer_booking');
			
			$data['sub_header'] = $this->lang->line('create_customer_booking');
			
			$data['main'] = $this->load->view('customer/create_customer_booking', $data, TRUE);
			
			$data['include_css'] = get_static_resources('jquery-ui.css');
			$data['include_js'] = get_static_resources('jquery-ui.js');
		
		} elseif ($action == 'view_advanced_search') {
		
			$data['site_title'] = $this->lang->line('advanced_search');
			
			$data['sub_header'] = $this->lang->line('advanced_search');
			
			$search_criteria = $this->_buildSearchCriteria($action);
		
			$data['search_criteria'] = $search_criteria;
		
			$data['main'] = $this->load->view('customer/advanced_search_cb', $data, TRUE);
		
		} else {
			
			$data['booked_countries'] = $this->CustomerModel->get_country_of_booking_customer();
			
			$data['partners'] = $this->CustomerModel->getAllPartners();
			
			$data['is_advanced_search'] = $action == 'advanced_search';
			
			$data['site_title'] = $this->lang->line('list_customer_booking');
			
			$data['sub_header'] = $this->lang->line('list_customer_booking');
			
			$data['search'] = $this->load->view('customer/search_customer_booking', $data, TRUE);
			
			$data['advanced_search'] = $this->load->view('customer/advanced_search_cb', $data, TRUE);
			
			$data['main'] = $this->load->view('customer/list_customer_booking', $data, TRUE);
			
			$data['include_css'] = get_static_resources('jquery-ui.css');
			$data['include_js'] = get_static_resources('jquery-ui.js');
			
			$data['include_js_ext'] = get_static_resources('jquery.qtip.min.js');
		}

		
		$data['navigation'] = $this->load->view('template/navigation', $data, TRUE);
		
		if ($action == 'view' || $action == 'edit' || $action == 'save_edit' || $action == 'create' || $action == 'save_create' || $action == 'view_advanced_search'){
			$data['header'] = $this->load->view('template/header', $data, TRUE);
			$this->load->view('template/template' ,$data);
		} else {
			$data['header'] = $this->load->view('template/header', $data, TRUE);
			$this->load->view('template/template' ,$data);
		}	
	}
	
	function _validate()
	{
		$this->_setValidationRules();
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;
	}
	
	function _list($action){
		
		$search_criteria = $this->_buildSearchCriteria($action);
		
		$data['search_criteria'] = $search_criteria;
			
		$data['total_rows'] = $this->CustomerModel->getNumCustomerBooking($search_criteria);			
		
		$offset = $this->uri->segment(4);
		
		$data['customer_bookings'] = $this->CustomerModel->searchCustomerBooking(
						$search_criteria
						, $this->config->item('cb_per_page')
						, (int)$offset);
		
		// initialize pagination
		$this->pagination->initialize(
						get_paging_config_cb($data['total_rows']
							, 'customer/customer_booking/index/'
							, 4));
							
		$data['paging_text'] = get_paging_text_cb($data['total_rows'], $offset);
		
		$data = $this->_setPrice($data);
		
		$data = $this->_setColorClass($data);
		
		$data = $this->_set_report_info($data);
		
		// retset service_reservation by origin request
		//$data = $this->_reset_rs_by_origin($data);
		
		return $data;
	}
	
	function _create()
	{
		if ($this->_validate()) {
			
			$id = $this->CustomerModel->createCustomerBooking($this->app_context);
			
			//$this->sendEmailNotify($id);
			
			return $id;
		} else {
			return '';
		}
	}
	
	
	function _edit()
	{
		
		$status = false;
		
		$id = $this->input->post('cb_id');
		
		if ($this->_validate()) {
			
			$status = $this->CustomerModel->updateCustomerBooking($id, $this->app_context);
			
			if ($status){
				//$this->sendEmailNotify($id);
			}
		} 
	
		return $status? $id : '';
	}
	
	function _setPrice($data){	
		
		$current_user = $this->app_context->current_user;
		
		$total_money = $this->CustomerModel->countTotalMoney($data['search_criteria'], $current_user);
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
	
	function _setColorClass($data){
		
		$near_future_config = $this->config->item('near_future_day');
			
		//$current_date = date('Y-m-d');
		
		$current_date = $this->_getCurrentDate();
		
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
				
				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'normal';
				}
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
				
				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'high';
				}
				
			} else if ($value['payment_due'] == $current_date){
				
				$value['p_due_color'] = "current";
				
				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'high';
				}
				
			} else if ($value['payment_due'] > $current_date && $value['payment_due'] <= $limit_date){
				
				$value['p_due_color'] = "near";
				
				if ($value['payment_due'] == $tomorrow){
					
					$value['p_due_color'] = "current";
					
				}
				
				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'normal';
				}
			}
			
			if ($value['payment_warning'] != ""){
				
				$value['payment_warning_message'] = '<table><tr><td class="current"><b>'.$this->timedate->format($value['payment_due'], DATE_FORMAT).'</b>:</td>'. '<td class="current"><b>$'.number_format($value['payment_amount'], CURRENCY_DECIMAL).'</b></td></tr></table>';
				
			}
			
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
			
			
			$value['reservation_color'] = $this->_getReservationColor($value['service_reservations']);
			
			$data['customer_bookings'][$key] = $value;
		}
		return $data;
	}
	
	function set_reservation_services_color($value) {
		
		$near_future_config = $this->config->item('near_future_day');
		
		$current_date = $this->_getCurrentDate();
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
					
					$v['warning_message'] = "<b class='current'>Transfer Remider: ". $this->timedate->format($v['start_date'], DATE_FORMAT).'</b>';
					
				}
			}
			
			
			
			
			if ($v['payment_type'] == 2){ // per-booking payment
			
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
			
				$v['warning_message'] = $v['warning_message'].$this->timedate->format($v['1_payment_due'], DATE_FORMAT);
			
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
			
					$v['warning_message'] = $v['warning_message'].$this->timedate->format($v['2_payment_due'], DATE_FORMAT);
			
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
						
						$v['warning_message'] = "<b class='current'>R-Due: ". $this->timedate->format($v['reserved_date'], DATE_FORMAT).'</b>';
						
					}
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
		
			if (strlen($v['service_name']) > 37){
				$v['service_name'] = substr($v['service_name'], 0, 37);
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
	
	function _buildSearchCriteria($action) {
		
			$search_criteria = array();	
		
			if ($this->session->userdata("customer_booking_search_criteria")){
				
				$search_criteria = $this->session->userdata("customer_booking_search_criteria");
				
			} else {
				
				$search_criteria['user_id'] = $this->app_context->current_user->id;
				
				$search_criteria['booking_filter'] = array();
				
				$search_criteria['sort_column'] = "cb.request_date";
				
				$search_criteria['sort_order'] = "desc";
				
				$search_criteria['status'] = "-1";
			}
			
			if ($action == 'search'){
				
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
			
			if ($action == 'advanced_search'){
				
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
				
				$country =$this->input->post('country_advanced');
				
				if ($country != '') {
					$search_criteria['country'] = $country;
				} else {
					unset($search_criteria['country']);
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
					unset($search_criteria['$date_field']);
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
					
				}
			
		$this->session->set_userdata("customer_booking_search_criteria", $search_criteria);
		return $search_criteria;
	}
	
	function _reset() {
		
		$this->session->unset_userdata('customer_booking_search_criteria');
		
	}
	
	function showHideActualSellProfit() {
		
		$search = $this->session->userdata('customer_booking_search_criteria');
		
		$td_column = $this->input->post('td_column');
		
		$td_show = $this->input->post('td_show');
		
		$search[$td_column] = $td_show;
		
		$this->session->set_userdata("customer_booking_search_criteria", $search);
		
		//echo $td_column.' '.$td_show;
	}
	
	function _delete()
	{
		$id = $this->input->post('cb_id');
		$customer_booking = $this->CustomerModel->getCustomerBooking($id);
		if(!empty($customer_booking) &&
				is_allow_deletion($customer_booking['user_created_id'], DATA_CUSTOMER_BOOKING, $customer_booking['user_id'], $customer_booking['approve_status'])) {
			$this->CustomerModel->deleteCustomerBooking($id, $this->app_context);
		} else {
			message_alert(1);
		}
	}
	
	function show_calendar(){
		
		$cb_id = $this->uri->segment(4);
		
		$customer_booking = $this->CustomerModel->getCustomerBooking($cb_id);
		
		$data['customer_booking'] = $customer_booking;
		
		$customer = $this->CustomerModel->getCustomer($customer_booking['customer_id']);
		
		$data['reservation_status'] = $this->config->item('reservation_status');
		
		
		$data['site_title'] = $customer['full_name'];
			
		$data['sub_header'] = $customer['full_name'];
		
		$data['header'] = '';
		
		$data['navigation'] = '';
		
		$data['service_reservations'] = $this->CustomerModel->getCustomerServiceReservations($customer_booking['id']);
		
		$data['main'] = $this->load->view('customer/customer_booking_calendar', $data, TRUE);
		
		
		$this->load->view('template/popup_template' ,$data);
	}
	
	
	function _getCurrentDate(){
		
		date_default_timezone_set("Asia/Saigon");
		
		return date('Y-m-d', time());
	}
	
	function _set_report_info($data){
		$data['total_cb'] = $data['total_rows'];
		
		$total_paxs = $this->CustomerModel->countTotalPax($data['search_criteria']);
		
		$total_pax = $total_paxs[0];
		
		$data['total_pax'] = $total_pax['adults'] + $total_pax['children'] + $total_pax['infants'];
		
		$data['total_adults'] = $total_pax['adults'];
		
		$data['total_children'] = $total_pax['children'];
		
		$data['total_infants'] = $total_pax['infants'];
		
		$data['total_review'] = $total_pax['send_review'];
		
		return $data;
	}
	
	function search_customer(){
		$cus_name = $this->input->post('customer_name');
		echo $this->CustomerModel->search_customer_autocomplete($cus_name);
	}
	
	function _re_arrange_rs_by_origin($service_reservations){
		
		$ret = array();
		
		foreach ($service_reservations as $value){
			
			if ($value['origin_id'] == 0){
				
				$ret[] = $value;
			}
		}
		
		foreach ($ret as $key=>$value){
			
			$includes = array();
			
			foreach ($service_reservations as $sr){
				
				if($sr['origin_id'] == $value['id']){
					
					$includes[] = $sr;
					
				}
				
			}
			
			$value['includes'] = $includes;
			
			$ret[$key] = $value;
			
		}
		
		
		$excludes = array();
		
		foreach ($service_reservations as $value){
			
			$is_included = false;
			
			foreach ($ret as $sr){
				
				if ($sr['id'] == $value['id']) {
					
					$is_included = true;
					
					break;
				}
				
				foreach ($sr['includes'] as $v){
					
					if($v['id'] == $value['id']){
						
						$is_included = true;
						
						break;
						
					}
				}
				
			}
			
			if (!$is_included){
				$excludes[] = $value;
			}
			
		}
		
		if(count($excludes) > 0){
			
			foreach ($excludes as $value){
				
				$ret[] = $value;
			}
			
		}
			
		
		return $ret;
		
	}
	
	function _reset_rs_by_origin($data){
		
		$customer_bookings = $data['customer_bookings'];
		
		foreach ($customer_bookings as $key=>$value){
			
			$value['service_reservations'] = $this->_re_arrange_rs_by_origin($value['service_reservations']);
			
			$customer_bookings[$key] = $value;
			
		}
		
		$data['customer_bookings'] = $customer_bookings;
		
		return $data;
	}
}

?>
