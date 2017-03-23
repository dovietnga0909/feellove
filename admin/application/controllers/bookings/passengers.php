<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Passengers extends BP_Controller {

	public function __construct()
    {
    
       	parent::__construct();	
       	
       	$this->load->language('booking');
       	
		$this->load->helper(array('url','form','booking','flight'));

		$this->load->model(array('Booking_Model'));
		
		
		$this->load->library('form_validation');
		$this->load->library('pagination');	
		$this->load->config('booking_meta');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	public function index($id)
	{
		
		$data = $this->_set_common_data();
		
		$data = $this->_set_data_for_edit($data, $id, 2);
		
		
		$data = $this->_set_data_for_edit($data, $id, 2);
		
		$data['site_title'] = lang('flight_passengers');
		
		$customer_booking = $this->Booking_Model->getCustomerBooking($id);
			
		if ($customer_booking != ''){
		
			$customer_booking['flight_users'] = $this->Booking_Model->get_flight_users($id);
		
			$data['customer_booking'] = $customer_booking;
		
		} else {
			redirect(site_url('bookings'));
		}
		
		$data['content'] = $this->load->view('bookings/passenger/list_passenger', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	function create($cb_id){
		
		$data = $this->_set_common_data();
		
		$data = $this->_set_data_for_edit($data, $cb_id, 2);
		
		$action = $this->input->post('action');
		
		if($action == ACTION_CANCEL){
			redirect(site_url('bookings/passenger/'.$passenger['customer_booking_id']));
		}
		
		if($action == ACTION_SAVE){
		
			$saved = $this->_save_passenger('', $cb_id);
		
			$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				
			if($saved){
				redirect(site_url('bookings/passenger/'.$cb_id));
			}
		
		}
		
		
		$data['site_title'] = lang('title_create_passenger');
	
		$data['content'] = $this->load->view('bookings/passenger/create_passenger', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
		
	}
	
	function edit($id){
	
		$data = $this->_set_common_data();
		
		$passenger = $this->Booking_Model->get_passenger($id);
		
		$data = $this->_set_data_for_edit($data, $passenger['customer_booking_id'], 2);
	
		$action = $this->input->post('action');
	
		if($action == ACTION_CANCEL){
			redirect(site_url('bookings/passenger/'.$passenger['customer_booking_id']));
		}
	
		if($action == ACTION_SAVE){
				
			$saved = $this->_save_passenger($id);

			$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
			
			if($saved){
				redirect(site_url('bookings/passenger/'.$passenger['customer_booking_id']));
			}
	
		}

	
		$data['site_title'] = lang('title_edit_passenger');
	
		$data['passenger'] = $passenger;
	
		$data['content'] = $this->load->view('bookings/passenger/edit_passenger', $data, TRUE);
	
		$this->load->view('_templates/template', $data);
	}
	
	function delete($id){
		
		$passenger = $this->Booking_Model->get_passenger($id);
		
		$update_passenger['deleted'] = DELETED;
		
		$saved = $this->Booking_Model->update_passenger($id, $update_passenger);
		
		redirect(site_url('bookings/passenger/'.$passenger['customer_booking_id']));
	}
	
	
	function _save_passenger($id = '', $cb_id = ''){
		
		if($this->_validate_passenger()){
		
			$passenger = $this->_get_post_data($cb_id);
			
			if ($id == ''){ // create passenger
				
				$saved = $this->Booking_Model->create_passenger($passenger);
				
			} else {	// edit passenger
				
				$saved = $this->Booking_Model->update_passenger($id, $passenger);
			}
					
			return $saved;
		
		} else {
			return false;
		}
	}
	
	function _validate_passenger()
	{
		
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		$this->form_validation->set_rules($this->config->item('passenger'));
		
		$type = $this->input->post('type');
		
		if (in_array($type, array(2,3))){ // children or infant
		
			$this->form_validation->set_rules('birth_day', 'lang:passenger_birthday', 'required');
		}
		
		if ($this->form_validation->run() == false) {
			
			return false;
			
		}
		
		return true;
	}
	
	function _set_common_data($data = array()){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_BOOKING);
		
		$data['page_css'] = get_static_resources('booking.css');
		$data['page_js'] = get_static_resources('booking.js');
	
		$data = get_library('datepicker', $data);
		
		
		$data['passenger_age_types'] = $this->config->item('passenger_age_type');
		$data['passenger_genders'] = $this->config->item('passenger_gender');
		
		$data['passenger_nationalities'] = $this->config->item('passenger_nationalities');
	
		return $data;
	}
	
	function _get_post_data($cb_id = ''){
		
		$passenger['type'] = $this->input->post('type');
		$passenger['gender'] = $this->input->post('gender');
		$birth_day = $this->input->post('birth_day');
		
		if(!empty($birth_day)){
			$passenger['birth_day'] = date(DB_DATE_FORMAT, strtotime($birth_day));
		}
		
		$passenger['checked_baggage'] = $this->input->post('checked_baggage');
		
		$passenger['ticket_number'] = $this->input->post('ticket_number');
		
		$passenger['full_name'] = $this->input->post('full_name');
		
		$name_explode = $this->_explode_name($passenger['full_name']);
		
		$passenger['first_name'] = $name_explode['first_name'];
		
		$passenger['last_name'] = $name_explode['last_name'];
		
		$passenger['nationality'] = $this->input->post('nationality');
		
		$passenger['passport'] = $this->input->post('passport');
		
		$passportexp = $this->input->post('passportexp');
		if(!empty($passportexp)){
			$passenger['passportexp'] = date(DB_DATE_FORMAT, strtotime($passportexp));
		}
		
		$passenger['date_modified'] = date(DB_DATE_TIME_FORMAT);
		
		$passenger['user_modified_id'] = get_user_id();
		
		if(!empty($cb_id)) $passenger['customer_booking_id'] = $cb_id;
		
		return $passenger;
		
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
	
	
	function _explode_name($name){
		
		$name = trim($name);
		
		$names = explode(' ', $name);
		
		$first_name = $names[0];
		
		$last_name = '';
		
		for($i = 1; $i < count($names); $i++){
			
			$last_name .= $names[$i] . ' ';
		
		}
		
		$last_name = trim($last_name);

		return array('first_name'=>$first_name, 'last_name'=>$last_name);
	}
}