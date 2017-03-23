<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();			
		$this->load->model('CustomerModel');
		$this->load->helper('url');
		$this->load->language('email');
		$this->load->library('form_validation');
		$this->load->helper('form');
		
		$this->config->load('email_meta');
		
		$this->auth->checkLogin();
		$this->auth->checkPermission(false);
		
		//$this->output->enable_profiler(TRUE);
	}
	
	function _setValidationRules($type, $sr_email_infos='')
	{
		$this->form_validation->set_error_delimiters('<br><label class="error">', '</label></br>');
		
		if ($type == EMAIL_BOOKING_TOUR){ // type tour booking
		
			$this->form_validation->set_rules($this->config->item('email_tour_booking'));
		
		} elseif ($type == EMAIL_BOOKING_TRANSFER){ // transfer booking
			
			$this->form_validation->set_rules($this->config->item('email_transfer_booking'));
			
			if ($sr_email_infos != ''){
				
				foreach ($sr_email_infos as $key=>$value){
					
					$name_surfix = "_".$key;
					
					$this->form_validation->set_rules('route'.$name_surfix, lang('route'), 'required');
					
					$this->form_validation->set_rules('car'.$name_surfix, lang('car_type'), 'required');
					
					$this->form_validation->set_rules('tour_name'.$name_surfix, lang('tour'), '');
					
					$this->form_validation->set_rules('flight_name'.$name_surfix, lang('flight_name'), '');
					
					$this->form_validation->set_rules('guest_name'.$name_surfix, lang('guest_name'), 'required');
					
					$this->form_validation->set_rules('guest_number'.$name_surfix, lang('guest_number'), 'required');
					
					$this->form_validation->set_rules('pick_up'.$name_surfix, lang('pick_up_transfer'), 'required');
					
					$this->form_validation->set_rules('drop_off'.$name_surfix, lang('drop_off_transfer'), 'required');
					
					$this->form_validation->set_rules('special_request'.$name_surfix, lang('special_request_transfer'), '');
					
					$this->form_validation->set_rules('pick_up_hour'.$name_surfix, lang('hour'), 'required');
					
					$this->form_validation->set_rules('pick_up_minute'.$name_surfix, lang('hour'), 'required');
					
					$this->form_validation->set_rules('drop_off_hour'.$name_surfix, lang('hour'), '');
					
					$this->form_validation->set_rules('drop_off_minute'.$name_surfix, lang('hour'), '');
					
					$this->form_validation->set_rules('start_date'.$name_surfix, lang('day'), 'required');
					
					$this->form_validation->set_rules('end_date'.$name_surfix, lang('day'), '');
					
				}
				
			}
			
			/*
			 * 
			$tour_name = $this->input->post('tour_name');
			
			$flight_name = $this->input->post('flight_name');
			
			if (trim($tour_name) == '' && trim($flight_name == '')){
				$this->form_validation->set_rules('tour_name', lang('flight_name').'/'.lang('tour'), 'required');
			}*/
			
		} elseif ($type == EMAIL_BOOKING_HOTEL){
			
			$this->form_validation->set_rules($this->config->item('email_hotel_booking'));
			
		} elseif ($type == EMAIL_BOOKING_VISA){
			
			$this->form_validation->set_rules($this->config->item('email_visa_booking'));
			
			$guest_number = $this->input->post('guest_number');
			
			for ($i = 1; $i <= $guest_number; $i++) {
				
				$this->form_validation->set_rules('name_'.$i,lang('name') , 'required');
				
				$this->form_validation->set_rules('gender_'.$i,lang('gender') , 'required');
				
				$this->form_validation->set_rules('birth_day_'.$i,lang('birth_day') , 'required|callback_visa_date_check');
				
				$this->form_validation->set_rules('country_'.$i,lang('nationality') , 'required');
				
				$this->form_validation->set_rules('passport_'.$i,lang('passport') , 'required');
				
				//$this->form_validation->set_rules('passport_expiry_'.$i,lang('passport_expiry') , 'callback_visa_date_check');
			}
		}elseif ($type == EMAIL_BOOKING_DEPOSIT){ // type tour booking
		
			$this->form_validation->set_rules($this->config->item('email_deposit_booking'));
		
		}
	}

	function index(){
		
		$sr_id = $this->uri->segment(4);
		
		$sr_email_info = $this->_get_sr_email_info($sr_id);// CustomerModel->get_service_reservation_email($sr_id);
		
		$data['sr_email_info'] = $sr_email_info;
		
		$action = $this->input->post('action');
		
		$data['is_edit_mode'] = $action == 'edit';
		
		$data['email'] = $this->CustomerModel->get_email($sr_email_info['email_id']);
		
		$email = $data['email'];
		
		$sr_email_info['email'] = $email;
		
		if ($sr_email_info['reservation_status'] == 1 || ($email && ($action == 'edit' || $action == 'send'))){// new			
			
			if ($email['type'] == EMAIL_BOOKING_TOUR || $sr_email_info['reservation_type'] == RESERVATION_TYPE_CRUISE_TOUR || $sr_email_info['reservation_type'] == RESERVATION_TYPE_LAND_TOUR){ // cruise tour or land tour
		
				$related_services = $this->CustomerModel->get_related_services($sr_email_info['customer_booking_id'], $sr_email_info['id'], $sr_email_info['partner_id']);
				
				$sr_email_info['related_services'] = $related_services;
				
				$data['sr_email_info'] = $sr_email_info;
				
				$data['site_title'] = $this->lang->line('email_booking_tour');
				
				$data['sub_header'] = $this->lang->line('email_booking_tour'). ': '.$sr_email_info['service_name'];

				if ($action == 'send'){
					
					$id = $this->_send_email_booking_tour($sr_email_info);
					
					if ($id != null) {
						
						$data['sending_status'] = "ok";
						
					} else {
						
						$data['sending_status'] = "not_ok";
						
					}
					
				}
			
				$data['main'] = $this->load->view('customer/email_booking_tour_form', $data, TRUE);
				
			} elseif ($email['type'] == EMAIL_BOOKING_HOTEL || $sr_email_info['reservation_type'] == RESERVATION_TYPE_HOTEL){ // hotel
				
				$data['site_title'] = $this->lang->line('email_booking_hotel');
				
				$data['sub_header'] = $this->lang->line('email_booking_hotel'). ': '.$sr_email_info['service_name'];
				
				if ($action == 'send'){
					
					$id = $this->_send_email_booking_hotel($sr_email_info);
					
					if ($id != null) {
						
						$data['sending_status'] = "ok";
						
					} else {
						
						$data['sending_status'] = "not_ok";
						
					}
					
				}
				
				$data['main'] = $this->load->view('customer/email_booking_hotel_form', $data, TRUE);
				
			} elseif ($email['type'] == EMAIL_BOOKING_TRANSFER || $sr_email_info['reservation_type'] == RESERVATION_TYPE_TRANSFER){ // transfer
				
				if ($sr_email_info['partner_id'] != 0 && $sr_email_info['partner_id'] != 1){
					
					$sr_email_infos = $this->CustomerModel->get_all_service_reservation_of_partner($sr_email_info['customer_booking_id'], $sr_email_info['partner_id'], 3, RESERVATION_NEW); // transfer
					
				} else {
					$sr_email_infos = array();
					
					$sr_email_infos[] = $sr_email_info;
				}
				
				$data['sr_email_infos'] = $sr_email_infos;
				
				$data['transfer_types'] = $this->config->item('transfer_types');
				
				$data['hours'] = $this->config->item('hours');
				
				$data['tours'] = $this->CustomerModel->get_tours_of_booking($sr_email_info['customer_booking_id']);
				
				$data['site_title'] = $this->lang->line('email_booking_transfer');
				
				$booking_service_name = $this->_get_booking_service_name($sr_email_infos);
				
				$data['sub_header'] = $this->lang->line('email_booking_transfer'). ': '.$booking_service_name;
				
				if ($action == 'send'){
					
					$id = $this->_send_email_booking_transfer($sr_email_infos);
					
					if ($id != null) {
						
						$data['sending_status'] = "ok";
						
					} else {
						
						$data['sending_status'] = "not_ok";
					}
					
				}
				
				$data['main'] = $this->load->view('customer/email_booking_transfer_form', $data, TRUE);
				
			} elseif ($email['type'] == EMAIL_BOOKING_VISA || $sr_email_info['reservation_type'] == RESERVATION_TYPE_VISA){
				
				$data['countries'] = $this->config->item('countries');
				
				$data['visa_types'] = $this->config->item('visa_types');
				
				$data['visa_processing_times'] = $this->config->item('visa_processing_times');
				
				$data['country'] = $this->_get_customer_country($data['countries'], $sr_email_info['country']);
				
				$data['site_title'] = $this->lang->line('email_booking_visa');
				
				$data['sub_header'] = $this->lang->line('email_booking_visa'). ': '.$sr_email_info['service_name'];
				
				if ($action == 'send'){
					
					$id = $this->_send_email_booking_visa($sr_email_info);
					
					if ($id != null) {
						
						$data['sending_status'] = "ok";
						
					} else {
						
						$data['sending_status'] = "not_ok";
						
					}
					
				}
				
				$data['main'] = $this->load->view('customer/email_booking_visa_form', $data, TRUE);	
			} else {
				$data['site_title'] = 'No email data';
			
				$data['sub_header'] = 'No email data';
				
				$data['main'] = '';
			}
			
			$data['include_css'] = get_static_resources('jquery-ui.css');
			$data['include_js'] = get_static_resources('jquery-ui.js');
			
		} elseif($email){
			
			$type = $email['type'];
			
			$data['site_title'] = $email['subject'];
			
			$data['sub_header'] = $email['subject'];
			
			$email['action'] = "view";
			
			if ($type == EMAIL_BOOKING_TOUR){					
				
				$data['main'] = $this->load->view('customer/email_booking_tour_content', $email, TRUE);		
				
			} elseif($type == EMAIL_BOOKING_HOTEL){
				
				$data['main'] = $this->load->view('customer/email_booking_hotel_content', $email, TRUE);	
				
			} elseif($type == EMAIL_BOOKING_TRANSFER){
				
				if ($sr_email_info['partner_id'] != 0 && $sr_email_info['partner_id'] != 1){
					
					$sr_email_infos = $this->CustomerModel->get_all_service_reservation_of_partner($sr_email_info['customer_booking_id'], $sr_email_info['partner_id'], 3); // transfer
					
					$emails = $this->_get_saving_transfer_emails($sr_email_infos);
					
				} else {
					$emails = array();
					
					$emails[] = $email;
				}
				
				
				$transfer_email_data['emails'] = $emails;
				
				$transfer_email_data['action'] = 'view';
				
				$data['main'] = $this->load->view('customer/email_booking_transfer_content', $transfer_email_data, TRUE);	
				
			} elseif($type == EMAIL_BOOKING_VISA){
				
				$email['visa_types'] = $this->config->item('visa_types');
				
				$email['visa_processing_times'] = $this->config->item('visa_processing_times');
				
				$data['main'] = $this->load->view('customer/email_booking_visa_content', $email, TRUE);	
				
			}
			
		} else {
			
			$data['site_title'] = 'No email data';
			
			$data['sub_header'] = 'No email data';
			
			$data['main'] = '';
		}
		
		
		$this->load->view('template/popup_template', $data);
	}
	
	function deposit(){
		
		$cb_id = $this->uri->segment(4);
		
		//$lasted_invoice = $this->CustomerModel->get_lasted_invoice_of_cb();		
		
		
		$cb_email_info = $this->CustomerModel->get_cb_email($cb_id);		
		
		$cruise_services = get_main_services_by_type($cb_email_info['services'], RESERVATION_TYPE_CRUISE_TOUR);
	
		$land_tour_services = get_main_services_by_type($cb_email_info['services'], RESERVATION_TYPE_LAND_TOUR);
		
		$hotel_services = get_main_services_by_type($cb_email_info['services'], RESERVATION_TYPE_HOTEL);
	
		if (count($hotel_services) > 0){
			
			$cb_email_info['booking_type'] = RESERVATION_TYPE_HOTEL;
			
			$cb_email_info['main_service_name'] = $hotel_services[0]['service_name'];
			
			$cb_email_info['main_service_start_date'] = $hotel_services[0]['start_date'];
		}
		
		if (count($land_tour_services) > 0){
			
			$cb_email_info['booking_type'] = RESERVATION_TYPE_LAND_TOUR;
			
			$cb_email_info['main_service_name'] = $land_tour_services[0]['service_name'];
			$cb_email_info['main_service_start_date'] = $land_tour_services[0]['start_date'];
		}
		
		if (count($cruise_services) > 0){
			
			$cb_email_info['booking_type'] = RESERVATION_TYPE_CRUISE_TOUR;
			
			$cb_email_info['main_service_name'] = $cruise_services[0]['service_name'];
			
			$cb_email_info['main_service_start_date'] = $cruise_services[0]['start_date'];
			
			$cb_email_info['main_service_cruise_name'] = $this->CustomerModel->get_cruise_name($cruise_services[0]['service_id']);
		}
		
		
		$data['cb_email_info'] = $cb_email_info;
		
		$action = $this->input->post('action');
		
		$data['is_edit_mode'] = $action == 'edit';
		
		$data['email'] = $this->CustomerModel->get_email($cb_email_info['email_id']);
		
		$email = $data['email'];
		
		$cb_email_info['email'] = $email;
		
		
		$temp_cond = $cb_email_info['status'] == BOOKING_NEW || $cb_email_info['status'] == BOOKING_PENDING || ($email !== FALSE && ($action == 'edit' || $action == 'send'));

		$temp_cond = $temp_cond && isset($cb_email_info['booking_type']);
		
		
		if($email !== FALSE && $action != 'edit' && $action != 'send'){			
			
			$data['site_title'] = $email['subject'];
			
			$data['sub_header'] = $email['subject'];
			
			$email['action'] = "view";
			
			$email['booking_type'] = $cb_email_info['booking_type'];
			
			$data['main'] = $this->load->view('customer/email_deposit_content', $email, TRUE);	
		
		}elseif ($temp_cond){		
			
			$data['site_title'] = $this->lang->line('email_booking_deposit');
			
			$str_title = $cb_email_info['title'] == 0 ? "Ms.": "Mr.";
			
			$data['sub_header'] = $this->lang->line('email_booking_deposit'). ': '.$str_title.$cb_email_info['full_name'];

			if ($action == 'send'){
				
				$id = $this->_send_email_booking_deposit($cb_email_info);
				
				if ($id != null) {
					
					$data['sending_status'] = "ok";
					
				} else {
					
					$data['sending_status'] = "not_ok";
					
				}
				
			}
			
			$data['booking_sites'] = $this->config->item('booking_sites');
		
			$data['main'] = $this->load->view('customer/email_deposit_form', $data, TRUE);
			
		} else {
			
			$data['site_title'] = 'No email data';
			
			$data['sub_header'] = 'No email data';
			
			$data['main'] = '';
		}
		
		$data['include_css'] = get_static_resources('jquery-ui.css');
		$data['include_js'] = get_static_resources('jquery-ui.js,nicEdit/nicEdit.js');
		$this->load->view('template/popup_template', $data);
	}

	function payment(){
		
	}
	
	function _get_customer_country($countries, $ct){
		
		$ret = "";
		
		foreach ($countries as $key => $country){
			
			if ($ct == $key){
				return $country[0];	
			}
			
		}
		return "";							
	}
	
	function _send_email_booking_tour($sr_email_info){
		
		if ($this->_validate(EMAIL_BOOKING_TOUR)){
			
			$email['type'] = EMAIL_BOOKING_TOUR;
			
			$email['send_to'] = $this->input->post('send_to');
			
			$email['subject'] = $this->input->post('subject');
			
			$email['dear'] = $this->input->post('dear');
			
			$email['request'] = $this->input->post('request');
			
			$email['special_note'] = $this->input->post('special_note');
			
			$email['tour_name'] = $this->input->post('tour_name');
			
			$email['guest_name'] = $this->input->post('guest_name');
			
			$email['guest_number'] = $this->input->post('guest_number');
			
			$email['start_date'] = $this->input->post('start_date');
			
			$email['end_date'] = $this->input->post('end_date');
			
			$email['services'] = $this->input->post('services');
			
			$email['total_price'] = $this->input->post('total_price');
			
			$email['guest_information'] = $this->input->post('guest_information');
			
			$email['pick_up'] = $this->input->post('pick_up');
			
			$email['special_request'] = $this->input->post('special_request');
			
			$email['signature'] = $sr_email_info['signature'];
			
			$email['send_from'] = $sr_email_info['sale_email'];
			
			$email['email_password'] = $sr_email_info['email_password'];
			
			$email['sale_name'] = $sr_email_info['sale_name'];
			
			
			$email['related_services'] = $sr_email_info['related_services'];
			
			$email['service_reservation_id'] = $sr_email_info['id'];
			
			$email['id'] = $sr_email_info['email_id'];
			
			$content = $this->load->view('customer/email_booking_tour_content', $email, TRUE);
			
			if ($this->_send_email($email, $content)){
				
				if ($sr_email_info['email_id'] == 0){
					
					$id = $this->CustomerModel->create_email($email);
					
				} else {
					
					$id = $this->CustomerModel->update_email($email);
					
				}
				
				return $id;
			}
			
		}
		
		return null;
	}
	
	function _send_email_booking_transfer($sr_email_infos){
		
		if ($this->_validate(EMAIL_BOOKING_TRANSFER, $sr_email_infos)){
			
			$emails = $this->_get_multi_email_transfer($sr_email_infos);			
			
			$transfer_emails['emails'] = $emails;
			
			$content = $this->load->view('customer/email_booking_transfer_content', $transfer_emails, TRUE);
			
			$email = $emails[0];
			
			//echo $content;
			
			if ($this->_send_email($email, $content)){
				
				foreach ($emails as $value){
				
					if ($value['id'] == 0){
		
						$id = $this->CustomerModel->create_email($value);
						
					} else {
						
						$id = $this->CustomerModel->update_email($value);
						
					}
				
				}
				
				return $id;
			}
			
		}
		
		return null;
		
	}
	
	function _send_email_booking_hotel($sr_email_info){
		
		if ($this->_validate(EMAIL_BOOKING_HOTEL)){
			
			$email['type'] = EMAIL_BOOKING_HOTEL;
			
			$email['send_to'] = $this->input->post('send_to');
			
			$email['subject'] = $this->input->post('subject');
			
			$email['dear'] = $this->input->post('dear');
			
			$email['request'] = $this->input->post('request');
			
			$email['special_note'] = $this->input->post('special_note');
			
			$email['signature'] = $sr_email_info['signature'];
			
			$email['send_from'] = $sr_email_info['sale_email'];
			
			$email['email_password'] = $sr_email_info['email_password'];
			
			$email['sale_name'] = $sr_email_info['sale_name'];
			
			
			$email['guest_name'] = $this->input->post('guest_name');
			
			$email['guest_number'] = $this->input->post('guest_number');
			
			$email['start_date'] = $this->input->post('start_date');
			
			$email['end_date'] = $this->input->post('end_date');
			
			$email['night_number'] = $this->input->post('night_number');
			
			$email['services'] = $this->input->post('services');
			

			$email['room_rate'] = $this->input->post('room_rate');
			
			$email['special_request'] = $this->input->post('special_request');
			
			$email['service_reservation_id'] = $sr_email_info['id'];
			
			$email['id'] = $sr_email_info['email_id'];
			
			$content = $this->load->view('customer/email_booking_hotel_content', $email, TRUE);
			
			//echo $content;
			
			if ($this->_send_email($email, $content)){
				
				if ($sr_email_info['email_id'] == 0){

					$id = $this->CustomerModel->create_email($email);
					
				} else {
					
					$id = $this->CustomerModel->update_email($email);
					
				}
				
				return $id;
			}
		}
		
		return null;
	}
	
	function _send_email_booking_visa($sr_email_info){
		
		if ($this->_validate(EMAIL_BOOKING_VISA)){
			
			$email['type'] = EMAIL_BOOKING_VISA;
			
			$email['send_to'] = $this->input->post('send_to');
			
			$email['subject'] = $this->input->post('subject');
			
			$email['dear'] = $this->input->post('dear');
			
			$email['request'] = $this->input->post('request');
			
			$email['special_note'] = $this->input->post('special_note');
			
			$email['signature'] = $sr_email_info['signature'];
			
			$email['send_from'] = $sr_email_info['sale_email'];
			
			$email['email_password'] = $sr_email_info['email_password'];
			
			$email['sale_name'] = $sr_email_info['sale_name'];
			
			
			$email['guest_number'] = $this->input->post('guest_number');
			
			$email['start_date'] = $this->input->post('start_date');
			
			$email['type_of_visa'] = $this->input->post('type_of_visa');
			
			$email['processing_time'] = $this->input->post('processing_time');
			
			$visa_users = array();
			
			for ($i = 1; $i <= $email['guest_number']; $i++) {
				
				$user['name'] = $this->input->post('name_'.$i);
				
				$user['gender'] = $this->input->post('gender_'.$i);
				
				$user['birth_day'] = $this->input->post('birth_day_'.$i);
				
				$user['nationality'] = $this->input->post('country_'.$i);
				
				$user['passport'] = $this->input->post('passport_'.$i);
				
				$user['passport_expiry'] = $this->input->post('passport_expiry_'.$i);
				
				$visa_users[] = $user;
			}
			
			$email['visa_users'] = $visa_users;
			
			$email['service_reservation_id'] = $sr_email_info['id'];
			
			$email['id'] = $sr_email_info['email_id'];
			
			$email['visa_types'] = $this->config->item('visa_types');
			
			$email['visa_processing_times'] = $this->config->item('visa_processing_times');
			
			$content = $this->load->view('customer/email_booking_visa_content', $email, TRUE);
			
			//echo $content;
			
			if ($this->_send_email($email, $content)){
				
				if ($sr_email_info['email_id'] == 0){

					$id = $this->CustomerModel->create_email($email);
					
				} else {
					
					$id = $this->CustomerModel->update_email($email);
					
				}
				
				return $id;
			}
		}
		
		return null;
	}
	
	
	function _send_email_booking_deposit($cb_email_info){
		
		if ($this->_validate(EMAIL_BOOKING_DEPOSIT)){
			
			$email['type'] = EMAIL_BOOKING_DEPOSIT;
			
			$email['send_to'] = $this->input->post('send_to');
			
			$email['subject'] = $this->input->post('subject');
			
			$email['dear'] = $this->input->post('dear');
			
			$email['request'] = $this->input->post('request');
			
			$email['special_note'] = $this->input->post('special_note');
			
			$email['tour_name'] = $this->input->post('tour_name');
			
			$email['guest_name'] = $this->input->post('guest_name');
			
			$email['guest_number'] = $this->input->post('guest_number');
			
			$email['start_date'] = $this->input->post('start_date');
			
			$email['end_date'] = $this->input->post('end_date');
			
			$email['services'] = $this->input->post('services');
			
			$email['total_price'] = $this->input->post('total_price');
			
			
			$email['term_cond'] = $this->input->post('term_cond');
			
			$email['deposit'] = $this->input->post('deposit');
			
			$email['final_payment'] = $this->input->post('final_payment');
			
			$email['payment_link'] = $this->input->post('payment_link');
			
			$email['payment_suggestion'] = $this->input->post('payment_suggestion');
			
			
			$email['signature'] = $cb_email_info['signature'];
			
			$email['send_from'] = $cb_email_info['sale_email'];
			
			$email['email_password'] = $cb_email_info['email_password'];
			
			$email['sale_name'] = $cb_email_info['sale_name'];
			
		
			$email['cb_id'] = $cb_email_info['id'];
			
			$email['id'] = $cb_email_info['email_id'];
			
			
			$email['booking_type'] = $cb_email_info['booking_type'];
			
			$content = $this->load->view('customer/email_deposit_content', $email, TRUE);
			
			unset($email['booking_type']);
			
			if ($this->_send_email($email, $content, false)){
				
				if ($cb_email_info['email_id'] == 0){
					
					$id = $this->CustomerModel->create_email($email);
					
				} else {
					
					$id = $this->CustomerModel->update_email($email);
					
				}
				
				return $id;
			}
			
		}
		
		return null;
		
	}
	
	function _validate($type, $sr_email_infos = '')
	{
		$this->_setValidationRules($type, $sr_email_infos);
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;
	}
	
	function _send_email($email, $content, $send_accounting = true){
		
		$this->load->library('email');
		
		$config['protocol']='smtp';  
		$config['smtp_host']='ssl://smtp.googlemail.com';  
		$config['smtp_port']='465';  
		$config['smtp_timeout']='30';  
		$config['charset']='utf-8';  
		$config['newline']="\r\n";
		$config['mailtype'] = 'html';
	
		
		$config['smtp_user']='bestpricevn@gmail.com';  
		$config['smtp_pass']='Bpt20112008';
		
		$accounting = "acc@bestpricevn.com";

		if (!empty($email['email_password'])){
			
			$config['smtp_user'] = $email['send_from'];
			  
			$config['smtp_pass'] = $email['email_password'];
			
		}
		
		$this->email->initialize($config);
		
		/**
		 * Send email by google account
		 */
		
		if (!empty($email['email_password'])){
			
			$this->email->from($email['send_from'], $email['sale_name']);
			
		} else {
						
			$this->email->from('bestpricevn@gmail.com',BRANCH_NAME);
			
		}
		
		$this->email->to($email['send_to']);
		
		if ($send_accounting){
			$this->email->cc(array($email['send_from'], $accounting));
		} else {
			$this->email->cc($email['send_from']);
		}
		$this->email->reply_to($email['send_from']);
		$this->email->subject($email['subject']);
		$this->email->message($content);
		
		//attach logo		
		$this->email->attach('../media/bestpricevn-logo.png','inline');
		
		if (!$this->email->send()){			
			log_message('error', 'Email Reservation: Can not send email to '.$email['send_to']);
			
			return false;
			
		}
		
		return true;
		
	}
	
	function _get_sr_email_info($sr_id){
		
		$sr_email_info = $this->CustomerModel->get_service_reservation_email($sr_id);

		// include with the tour
		if (($sr_email_info['reservation_type'] == RESERVATION_TYPE_TRANSFER || $sr_email_info['reservation_type'] == RESERVATION_TYPE_OTHER) && $sr_email_info['email_id'] == 0){ // transfer
			
			$related_services = $this->CustomerModel->get_related_services($sr_email_info['customer_booking_id'], $sr_id, $sr_email_info['partner_id']);
			
			foreach($related_services as $value){
				
				if ($value['reservation_type'] == 1 || $value['reservation_type'] == 4){ // cruise tour or land tour
					
					$sr_email_info = $this->CustomerModel->get_service_reservation_email($value['id']);
					
					return $sr_email_info;
				}
				
			}
		
		}
		
		
		return $sr_email_info;
	}
	
	function _get_booking_service_name($sr_email_infos){
		
		$ret = '';
		
		foreach ($sr_email_infos as $key=>$value){
			
			if ($key == 0){
			
				$ret = $value['service_name'];
			
			} else {
				
				$ret = $ret.' & '.$value['service_name'];
			}
		}
		
		return $ret;
		
	}
	
	function _get_multi_email_transfer($sr_email_infos){
		
		$emails = array();
		
		foreach ($sr_email_infos as $key=>$sr_email_info){
			
			$name_surfix = "_".$key;
			
			$email['type'] = EMAIL_BOOKING_TRANSFER;
			
			$email['send_to'] = $this->input->post('send_to');
			
			$email['subject'] = $this->input->post('subject');
			
			$email['dear'] = $this->input->post('dear');
			
			$email['request'] = $this->input->post('request');
			
			$email['special_note'] = $this->input->post('special_note');
			
			$email['signature'] = $sr_email_info['signature'];
			
			$email['send_from'] = $sr_email_info['sale_email'];
			
			$email['email_password'] = $sr_email_info['email_password'];
			
			$email['sale_name'] = $sr_email_info['sale_name'];
			
			
			$email['tour_name'] = $this->input->post('tour_name'.$name_surfix);
			
			$email['flight_name'] = $this->input->post('flight_name'.$name_surfix);
			
			$email['route'] = $this->input->post('route'.$name_surfix);
			
			$email['car'] = $this->input->post('car'.$name_surfix);
			
			$email['guest_name'] = $this->input->post('guest_name'.$name_surfix);
			
			$email['guest_number'] = $this->input->post('guest_number'.$name_surfix);
			
			$email['pick_up'] = $this->input->post('pick_up'.$name_surfix);
			
			$email['drop_off'] = $this->input->post('drop_off'.$name_surfix);
			
			$email['special_request'] = $this->input->post('special_request'.$name_surfix);
			
			$email['start_date'] = $this->input->post('start_date'.$name_surfix);
			
			$email['end_date'] = $this->input->post('end_date'.$name_surfix);
			
			$email['pick_up_hour'] = $this->input->post('pick_up_hour'.$name_surfix);
			
			$email['pick_up_minute'] = $this->input->post('pick_up_minute'.$name_surfix);
			
			$email['drop_off_hour'] = $this->input->post('drop_off_hour'.$name_surfix);
			
			$email['drop_off_minute'] = $this->input->post('drop_off_minute'.$name_surfix);
			
			
			$email['service_reservation_id'] = $sr_email_info['id'];
			
			$email['id'] = $sr_email_info['email_id'];
			
			$emails[] = $email;
		}
		
		return $emails;
	}
	
	function _get_saving_transfer_emails($sr_email_infos){
		
		$emails = array();
		
		foreach ($sr_email_infos as $value){
			
			$emails[] = $value['email'];
			
		}
		
		return $emails;

	}
	
	function _email_check() {
		
		$this->load->helper('email');
		
		$send_to = $this->input->post('send_to');
		
		$emails = explode(";", $send_to);
		
		foreach ($emails as $email){
			
			if (!valid_email($email)){
				
				$this->form_validation->set_message('_email_check', $this->lang->line('invalid_email'));
				
				return false;
			}
			
		}
		
		return TRUE;
	}
	
	function _payment_link_check() {
		
		$payment_link = $this->input->post('payment_link');
		
		if (strpos($payment_link, 'https://onepay.vn/invoice/payment.op?session_id=') === false){
		
			$this->form_validation->set_message('_payment_link_check', $this->lang->line('insert_payment_link'));
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	function visa_date_check($str)
	{
		
		if(trim($str) == '') return TRUE;
		
		$ret = FALSE;
		
		if (substr_count($str, '-') == 2) {
	        list($d, $m, $y) = explode('-', $str);
	        $ret =  checkdate($m, $d, sprintf('%04u', $y));
	    }
	    
	    if ($ret === FALSE){
	    	$this->form_validation->set_message('visa_date_check', 'The %s field must be in dd-mm-yyyy format');
	    }
	
	    return $ret;
	}
	
	function check_br_tag($str){
		
		if(trim($str) == '<br>'){
			$this->form_validation->set_message('check_br_tag', 'The %s field is required');
			return FALSE;
		}
		
		return TRUE;
	}
}
?>