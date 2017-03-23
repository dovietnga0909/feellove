<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Task_Management extends CI_Controller {
	
	var $t1, $t2;

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

	function index()
	{
		$data = array();
		$action = $this->input->post('action_type');
			
		if ($action == 'reset'){
			$this->_reset();
		} else if ($action == 'save'){
			$this->mark_as_done();
			$data['reload_opener'] = true;
		}

		$this->_setDataForm($action, $data);
	}

	function _setDataForm($action, $data)
	{
		$this->t1 = microtime(true);
		
		$data['status'] = $this->config->item('booking_status');

		$data['sales'] = $this->CustomerModel->getUsers();

		$data['partners'] = $this->CustomerModel->getAllPartners();

		$data['reservation_status'] = $this->config->item('reservation_status');

		$data['task_filter'] = $this->config->item('task_filter');

		$data['countries'] = $this->config->item('countries');

		$data['task_type'] = $this->config->item('task_type');

		$data['todo_status'] = $this->config->item('todo_status');

		$data['approve_status'] = $this->config->item('approve_status');
		
		$data['atts'] = get_popup_config('email');
			
		$data['site_title'] = "Task Management";
			
		$data['sub_header'] = "Task Management";
		
		$search_criteria = $this->_buildSearchCriteria($action);
		
		$data['search_criteria'] = $search_criteria;
		
		$data = $this->get_task_data($data);
			
		$data['main'] = $this->load->view('customer/view_task_management', $data, TRUE);
			
		$data['include_css'] = get_static_resources('jquery-ui.css,task_management.css');
		$data['include_js'] = get_static_resources('jquery-ui.js,jquery.qtip.min.js,task_management.js');
		
		$this->t2 = microtime(true);
		//$data['time_exe'] =  $this->t2 - $this->t1;

		$data['header'] = $this->load->view('template/header', $data, TRUE);
		$this->load->view('template/popup_template' ,$data);
	}

	function mark_as_done() {
		$id = $this->input->post('id');
		$task_type = $this->input->post('task_done_type');

		if(is_numeric($id) && !empty($task_type)) {
			$result = $this->CustomerModel->mark_as_done($id, $task_type, $this->app_context);
				
			return $result;
		}
	}

	function get_task_data($data) {

		$REVIEWED = 1; // task done

		$search_criteria = $data['search_criteria'];

		$task_type = '';
		if(isset($search_criteria['task_type'])) {
			$task_type = $search_criteria['task_type'];
		}
		
		$total = 0;
		$page_info = '';
		
		if($task_type == '' || $task_type == TASK_CUSTOMER_MEETING){
		
			/*---------- CUSTOMER MEETING -----------*/
			$data['customer_bookings'] = $this->CustomerModel->searchTasks($data['search_criteria'], TASK_CUSTOMER_MEETING);
				
			$data = $this->_setColorClass($data);
		
				
			$customer_meetings = $data['customer_bookings'];
	
			if($task_type == TASK_CUSTOMER_MEETING || (empty($task_type) && !empty($customer_meetings))) {
				$data['customer_meetings'] = $customer_meetings;
				
				$page_info .= '<label class="highlight">'.count($customer_meetings).'</label> c.m';
				$total += count($customer_meetings);
			}
		
		}
		
		
		if($task_type == '' || $task_type == TASK_CUSTOMER_PAYMENT){
			/*---------- CUSTOMER PAYMENT -----------*/
			$data['customer_bookings'] = $this->CustomerModel->searchTasks($data['search_criteria'], TASK_CUSTOMER_PAYMENT);
	
			$data = $this->_setColorClass($data);
	
			$customer_payments = $data['customer_bookings'];
	
			if($task_type == TASK_CUSTOMER_PAYMENT || (empty($task_type) && !empty($customer_payments))) {
				$data['customer_payments'] = $customer_payments;
				
				if(!empty($page_info)) $page_info .= ' - ';
				$page_info .= '<label class="highlight">'.count($customer_payments).'</label> c.p';
				$total += count($customer_payments);
			}
		
		}
		
		if($task_type == '' || $task_type == TASK_SERVICE_RESERVATION){
			/*---------- SERVICE RESERVATION -----------*/
			$service_reservations = $this->CustomerModel->searchTasks($data['search_criteria'], TASK_SERVICE_RESERVATION);
				
			$service_reservations = $this->set_services_color($service_reservations, 'reserved_date', TASK_SERVICE_RESERVATION);
	
			if($task_type == TASK_SERVICE_RESERVATION || (empty($task_type) && !empty($service_reservations))) {
				$data['reservation_services'] = $service_reservations;
				
				if(!empty($page_info)) $page_info .= ' - ';
				$page_info .= '<label class="highlight">'.count($service_reservations).'</label> s.r';
				$total += count($service_reservations);
			}
		
		}
		
		if($task_type == '' || $task_type == TASK_TRANSFER_REMINDER){
			/*---------- TRANSFER REMINDER -----------*/
			$transfer_services = $this->CustomerModel->searchTasks($data['search_criteria'], TASK_TRANSFER_REMINDER);
				
			$transfer_services = $this->set_services_color($transfer_services, 'start_date', TASK_TRANSFER_REMINDER);
	
			if($task_type == TASK_TRANSFER_REMINDER || (empty($task_type) && !empty($transfer_services))) {
				$data['transfer_services'] = $transfer_services;
				
				if(!empty($page_info)) $page_info .= ' - ';
				$page_info .= '<label class="highlight">'.count($transfer_services).'</label> t.r';
				$total += count($transfer_services);
			}
		}

		if($task_type == '' || $task_type == TASK_SERVICE_PAYMENT){
			/*---------- SERVICE PAYMENT -----------*/
			$service_payments = $this->CustomerModel->searchTasks($data['search_criteria'], TASK_SERVICE_PAYMENT);
				
			$service_payments = $this->set_services_color($service_payments, 'payment_due', TASK_SERVICE_PAYMENT);
	
			if($task_type == TASK_SERVICE_PAYMENT || (empty($task_type) && !empty($service_payments))) {
				$data['service_payments'] = $service_payments;
				
				if(!empty($page_info)) $page_info .= ' - ';
				$page_info .= '<label class="highlight">'.count($service_payments).'</label> s.p';
				$total += count($service_payments);
			}
		}
		
		
		
		/*---------- TO-DO -----------*/
		$cnt_todo = 0;
		if(empty($task_type) || $task_type == TASK_TO_DO) {
			$todo_notes = $this->CustomerModel->searchTodo($data['search_criteria']);
				
			$todo_notes = $this->set_services_color($todo_notes, 'due_date');
			$todo_notes = $this->set_services_color($todo_notes, 'due_date', TASK_TO_DO);
			
			$cnt_todo = count($todo_notes);
			if(!empty($page_info)) $page_info .= ' - ';
			$page_info .= '<label class="highlight">'.$cnt_todo.'</label> t.d';
			$total += $cnt_todo;
				
			$data['todo_notes'] = $todo_notes;
		}
		
		$data['page_info'] = ' Tasks: <b>'.$total.'</b> ('.$page_info.')';
		
		// Counting task
		$data['current_task'] = $this->CustomerModel->number_of_task($this->app_context, $data['search_criteria']);
		$data['near_future'] = $this->CustomerModel->number_of_task($this->app_context, $data['search_criteria'], false, true);
		$data['overdue'] = $this->CustomerModel->number_of_task($this->app_context, $data['search_criteria'], false, false, true);

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

				$value['meeting_color'] = "current_booking";

			} else if ($value['meeting_date'] == $current_date){

				$value['meeting_color'] = "current_booking";

			} else if ($value['meeting_date'] > $current_date && $value['meeting_date'] <= $limit_date){

				$value['meeting_color'] = "near_booking";

				if ($value['meeting_date'] == $tomorrow){
					$value['meeting_color'] = "current_booking";
				}
			}
				
			$value['p_due_color'] = "";
				
			$value['payment_warning'] = "";
				
			if ($value['payment_due'] < $current_date){

				$value['p_due_color'] = "current_booking";

				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'high';
				}

			} else if ($value['payment_due'] == $current_date){

				$value['p_due_color'] = "current_booking";

				if ($value['status'] == 3){//deposited
					$value['payment_warning'] = 'high';
				}

			} else if ($value['payment_due'] > $current_date && $value['payment_due'] <= $limit_date){

				$value['p_due_color'] = "near_booking";

				if ($value['payment_due'] == $tomorrow){
						
					$value['p_due_color'] = "current_booking";
						
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
				
			if ($v['reservation_status'] == RESERVATION_NEW || $v['reservation_status'] == RESERVATION_SENDING){

				if ($v['reserved_date'] != ''){

					if ($v['reserved_date'] <= $tomorrow){
							
						$v['warning'] = "high";

						$v['warning_message'] = "<b class='current'>R-Due: ". $this->timedate->format($v['reserved_date'], DATE_FORMAT).'</b>';

					}
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

		if ($this->session->userdata("task_management_search_criteria")){
				
			$search_criteria = $this->session->userdata("task_management_search_criteria");
				
		} else {
				
			$search_criteria['user_id'] = $this->app_context->current_user->id;
				
			$search_criteria['task_filter'] = array(1);
				
			$search_criteria['task_type'] = "";
				
			$search_criteria['start_date'] = '';
			$search_criteria['end_date'] = '';
		}

		if($action == "search") {
			$name = trim($this->input->post('name'));
			if ($name != '') {
				$search_criteria['name'] = $name;
			} else {
				unset($search_criteria['name']);
			}
				
			$user_id =$this->input->post('sale');
				
			if ($user_id != '') {
				$search_criteria['user_id'] = $user_id;
			} else {
				unset($search_criteria['user_id']);
			}
				
			$partner =$this->input->post('partner');
				
			if ($partner != '') {
				$search_criteria['partner'] = $partner;
			} else {
				unset($search_criteria['partner']);
			}
				
			$task_filter = $this->input->post('task_filter');
				
			if ($task_filter != ""){
					
				$search_criteria['task_filter'] = $task_filter;
					
			} else {
				unset($search_criteria['task_filter']);
			}
				
			$task_type = $this->input->post('task_type');
				
			if ($task_type != ""){
					
				$search_criteria['task_type'] = $task_type;
					
			} else {
				unset($search_criteria['task_type']);
			}
				
			$start_date = trim($this->input->post('startDate'));
			if ($start_date != '') {
				$search_criteria['start_date'] = $start_date;
			} else {
				unset($search_criteria['start_date']);
			}
				
			$end_date = trim($this->input->post('endDate'));
			if ($end_date != '') {
				$search_criteria['end_date'] = $end_date;
			} else {
				unset($search_criteria['end_date']);
			}
				
			$to_do_status = trim($this->input->post('to_do_status'));
			if ($to_do_status != '') {
				$search_criteria['to_do_status'] = $to_do_status;
			} else {
				unset($search_criteria['to_do_status']);
			}
				
			// check unspecific time filter, if it's not set then set to current
			/* if(!isset($start_date) && !isset($end_date) && !isset($task_filter)) {
				$search_criteria['task_filter'] = array(1);
			} */
		}
			
		$this->session->set_userdata("task_management_search_criteria", $search_criteria);
		return $search_criteria;
	}

	function _reset() {

		$this->session->unset_userdata('task_management_search_criteria');

	}


	function _is_date($str) {
		if (!$this->timedate->is_date($str)) {
			return FALSE;
		}

		return TRUE;
	}

	function _validate()
	{
		$start_date = $this->input->post('start_date');
		$due_date = $this->input->post('due_date');
		if (!$this->_is_date($start_date)) {
			echo 'You must enter a valid start date !';exit();
			return false;
		}
		if (!$this->_is_date($due_date)) {
			echo 'You must enter a valid due date !';exit();
			return false;
		}

		if ($this->timedate->date_compare($start_date, $due_date) == 1) {
			echo 'Due date must greater than start date !';exit();
			return false;
		}
		return true;
	}

	function _getCurrentDate(){

		date_default_timezone_set("Asia/Saigon");

		return date('Y-m-d', time());
	}

	function create_update_to_do() {
		if ($this->_validate()) {
			$id = $this->input->post('id');
			$name = $this->input->post('name');
			$details = $this->input->post('details');
			$start_date = $this->input->post('start_date');
			$due_date = $this->input->post('due_date');
			$status = $this->input->post('status');
			$todo_user = '';
			if(is_administrator()) {
				$todo_user = $this->input->post('todo_user');
			}
				
			if(!empty($id) && is_numeric($id)) {
				echo $this->CustomerModel->updateTodo($id, $name, $details, $start_date, $due_date, $status, $todo_user, $this->app_context);
			} else {
				echo $this->CustomerModel->createTodo($name, $details, $start_date, $due_date, $todo_user, $this->app_context);
			}
		}
	}

	function edit_to_do() {
		$id = $this->input->post('id');
		echo json_encode($this->CustomerModel->getTodo($id));
	}

	function delete_to_do() {
		$id = $this->input->post('id');
		if(is_numeric($id)) {
			echo $this->CustomerModel->deleteTodo($id);
		}
	}

	function get_task_info() {

		$info = $this->CustomerModel->number_of_task($this->app_context, '',true);

		echo $info;
	}

	function set_services_color($services, $date_field, $type = '') {

		$near_future_config = $this->config->item('near_future_day');
		
		$current_date = $this->_getCurrentDate();
		
		$limit_date = date('Y-m-d', strtotime($current_date . " +". $near_future_config. " day"));
		
		$tomorrow = date('Y-m-d', strtotime($current_date . " +1 day"));
		
		if($type == TASK_SERVICE_RESERVATION || $type == TASK_TRANSFER_REMINDER || $type == TASK_SERVICE_PAYMENT) {
			foreach ($services as $key => $value) {
				$value['re_color'] = "r_new";
				if ($value['reservation_status'] == 1 || $value['reservation_status'] == 2 || $value['reservation_status'] == 3){
						
				} else if ($value['reservation_status'] == 5){
					$value['re_color'] = "blocked";
				} else if ($value['reservation_status'] == 6 || $v['reservation_status'] == 7){
					$value['re_color'] = "reserved";
				} else if ($value['reservation_status'] == 4){
					$value['re_color'] = "r_cancelled";
				}
				
				$value['color'] = "";
				if ($value['end_date'] < $current_date){
					$value['color'] = "past";
				} else if ($value['start_date'] <= $current_date  && $value['end_date'] >= $current_date){
					$value['color'] = "current";
				} else if ($value['start_date'] > $current_date && $value['start_date'] <= $limit_date){
					$value['color'] = "near";
					if ($value['start_date'] == $tomorrow){
						$value['color'] = "current";
					}
				}
					
				$services[$key] = $value;
			}
		}
		
		if($type == TASK_TO_DO) {
			foreach ($services as $key => $value) {
				$value['todo_color'] = "r_new";
				if ($value['status'] == 1){
					$value['todo_color'] = "deposited";
				} else if ($value['status'] == 2){
					$value['todo_color'] = "fully_paid";
				} else if ($value['status'] == 3){
					$value['todo_color'] = "close_lost";
				}
					
				$services[$key] = $value;
			}
		}
		
		$css_extend = "_booking";
		$class_name = "reserved_color";
		if($type == TASK_TO_DO) {
			$css_extend = "";
			$class_name = "r_color";
		}

		foreach ($services as $key => $value) {
			$value[$class_name] = "";
			
			$compare_date = $this->timedate->format($value[$date_field], DB_DATE_FORMAT);

			if ($compare_date <= $tomorrow){
					
				$value[$class_name] = "current".$css_extend;
					
			} else if ($compare_date > $current_date && $compare_date <= $limit_date){

				$value[$class_name] = "near".$css_extend;

			}
				
			$services[$key] = $value;
		}

		return $services;
	}
	
	function get_customer_booking() {
		$id = $this->input->post('id');
		$cb = $this->CustomerModel->getCustomerBooking($id);
		
		echo json_encode($cb);
	}
	
	function update_payment() {
		$id = $this->input->post('id');
		$onepay = $this->input->post('onepay');
		$cash = $this->input->post('cash');
		$pos = $this->input->post('pos');
		$action = $this->input->post('action');
		
		if((empty($onepay) && empty($cash) && empty($pos)) ||
				((!empty($onepay) && !is_numeric($onepay)) 
						|| (!empty($cash) && !is_numeric($cash)) 
						|| (!empty($pos) && !is_numeric($pos)))) {
			echo 2; return;
		}
		
		if($onepay + $cash + $pos == 0) {
			echo 1; return;
		}
		
		$is_warning = $this->CustomerModel->update_customer_payment($id, $onepay, $cash, $pos, $this->app_context);
		
		if($is_warning && $action != "Confirm") {
			echo "Warning: there maybe a problem with this customer payment !!!";
		}
	}
}

?>
