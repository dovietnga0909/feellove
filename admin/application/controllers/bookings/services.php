<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends BP_Controller {

	public function __construct()
	{

		parent::__construct();
		$this->load->helper(array('url','form','booking','flight'));

		$this->load->model(array('Booking_Model', 'Destination_Model'));
		$this->load->language('booking');

		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->config('booking_meta');
		$this->load->config('partner_meta');

		//$this->output->enable_profiler(TRUE);
	}
	
	function index(){
		
	}
	
	function create($cb_id){
		
		$data = $this->_set_common_data();
		
		$data = $this->_set_mnu($data, $cb_id);
		
		$action = $this->input->post('action');
		
		if($action == ACTION_CANCEL){
			redirect(site_url('bookings/sr/'.$cb_id));
		}
		
		if($action == ACTION_SAVE){
				
			$saved = $this->_save_sr('', $cb_id);
				
			if($saved){
				redirect(site_url('bookings/sr/'.$cb_id));
			}
		
		}
		
		$data = $this->_set_data_for_edit($data, $cb_id);
		
		$customer_booking = $this->Booking_Model->getCustomerBooking($cb_id);
		
		$data['customer_booking'] = $customer_booking; 
		
		
		$data['site_title'] = lang('create_service_reservation');
		
		$data['content'] = $this->load->view('bookings/sr/create_sr', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
		
	}
	
	function edit($id){
		
		$data = $this->_set_common_data();
		
		$sr = $this->Booking_Model->getServiceReservation($id);
		
		$action = $this->input->post('action');
		
		if($action == ACTION_CANCEL){
			redirect(site_url('bookings/sr/'.$sr['customer_booking_id']));
		}
		
		if($action == ACTION_SAVE){
			
			$saved = $this->_save_sr($id);
			
			if($saved){
				redirect(site_url('bookings/sr/'.$sr['customer_booking_id']));
			}
		
		}
		
		
		$data = $this->_set_mnu($data, $sr['customer_booking_id']);
		
		$data = $this->_set_data_for_edit($data, $sr['customer_booking_id']);
		
		$data['site_title'] = lang('edit_service_reservation');
		
		$data['sr'] = $sr;
		
		$data['content'] = $this->load->view('bookings/sr/edit_sr', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	function delete($id){
		
		$service_reservation = $this->Booking_Model->getServiceReservation($id);
		
		if(!empty($service_reservation) &&
		is_allow_deletion($service_reservation['user_created_id'], DATA_SERVICE_RESERVATION, $service_reservation['user_id'])) {
			$this->Booking_Model->deleteServiceReservation($id);
		} else {
			message_alert(1);
		}
		
		redirect(site_url('bookings/sr/'.$service_reservation['customer_booking_id']));
		
	}
	
	function _set_common_data($data = array()){
		
		$data['page_css'] = get_static_resources('booking.css');
		$data['page_js'] = get_static_resources('booking.js');
		
		$data = get_library('datepicker', $data);
		
		$data = get_library('typeahead', $data);
		
		$data = get_library('mask', $data);
		
		$data['c_titles'] = $this->config->item('c_titles');
		
		return $data;
	}
	
	function _set_data_for_edit($data, $cb_id){
		
		$data['origins'] = $this->Booking_Model->get_origin_rs($cb_id);
		
		$data['reservation_type'] = $this->config->item('reservation_type');
		
		$data['reservation_status'] = $this->config->item('reservation_status');
		
		$data['visa_types'] = $this->config->item('visa_types');
		
		$data['visa_processing_times'] = $this->config->item('visa_processing_times');
		
		$data['cabin_booked'] = $this->config->item('cabin_booked');
		
		$data['pax_booked'] = $this->config->item('pax_booked');
		
		return $data;
	}
	
	function _set_mnu($data, $id = '', $mnu_index = 1){
	
		$nav_panel = $this->config->item('cb_nav_panel');
		
		if($this->Booking_Model->has_flight_users($id)){
		
			$nav_panel[] = array('link' => '/bookings/passenger/', 'title' => 'flight_passengers');
		
		}
	
		foreach ($nav_panel as $key => $value){
				
			$value['link'] .= $id.'/';
				
			$nav_panel[$key] = $value;
				
		}
			
		$data['side_mnu_index'] = $mnu_index;
			
		$data['nav_panel'] = $nav_panel;
	
		return $data;
	}
	
	function _set_sr_validation_rules()
	{
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		$this->form_validation->set_rules($this->config->item('service_reservation'));
	
		$re_type = $this->input->post('reservation_type');
	
		if ($re_type == RESERVATION_TYPE_CRUISE_TOUR){
				
			$this->form_validation->set_rules('cabin_booked', 'lang:cabin_booked', 'required');
		}
	
		if ($re_type == RESERVATION_TYPE_VISA){
				
			$this->form_validation->set_rules('type_of_visa', 'lang:type_of_visa', 'required');
				
			$this->form_validation->set_rules('processing_time', 'lang:processing_time', 'required');
		}
		
		if ($re_type == RESERVATION_TYPE_FLIGHT){
			
			$this->form_validation->set_rules('destination', 'lang:destination', '');
			
			$re_status = $this->input->post('reservation_status');
			
			if($re_status == RESERVATION_FULL_PAID || $re_status == RESERVATION_CLOSE_WIN){
				
				$this->form_validation->set_rules('flight_pnr', 'lang:pnr', 'required');
				
				$this->form_validation->set_rules('description', 'lang:description', 'callback_check_ticket_booked');
			}
			
		}
		
		if($re_type == RESERVATION_TYPE_OTHER){
			$this->form_validation->set_rules('partner', 'lang:partner', '');
			$this->form_validation->set_rules('destination', 'lang:destination', '');
		}
	
		$origin = $this->input->post('origin');
	
		if ($origin != ''){
			$this->form_validation->set_rules('selling_price', 'lang:selling_price', 'callback_sell_check_origin');
		} elseif($re_type != RESERVATION_TYPE_OTHER){
			$this->form_validation->set_rules('selling_price', 'lang:selling_price', 'callback_sell_check_normal');
		}
	
	}
	
	function _validate_sr()
	{
		$this->_set_sr_validation_rules();
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;
	}
	
	function _save_sr($id = '', $cb_id = ''){
		if ($this->_validate_sr()) {
			
			if($id != ''){
				$status = $this->Booking_Model->updateServiceReservation($id);
			} else {
				$status = $this->Booking_Model->createServiceReservation($cb_id);
			}
		
			return $status;
		}
		return false;
	}
	
	function search_partners($str_query){
		
		$partners = $this->Booking_Model->search_partners($str_query);
		
		echo json_encode($partners);
		
	}
	
	function search_destinations($str_query){
	
		$destinations = $this->Booking_Model->search_destinations($str_query);
	
		echo json_encode($destinations);
	
	}
	
	
	function search_hotels($str_query){
	
		$hotels = $this->Booking_Model->search_hotels($str_query);
	
		echo json_encode($hotels);
	
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
	
	function check_ticket_booked(){
		
		$description = $this->input->post('description');
		
		$is_ticket_booked = false;
		
		if(!empty($description)){
			
			$description = mb_strtolower($description);
			
			if (strpos($description, mb_strtolower(lang('ticket_confirm_1'))) !== false) {
				$is_ticket_booked = true;
			}
			if (strpos($description, mb_strtolower(lang('ticket_confirm_2'))) !== false) {
				$is_ticket_booked = true;
			}
			if (strpos($description, mb_strtolower(lang('ticket_confirm_3'))) !== false) {
				$is_ticket_booked = true;
			}
			
			if (strpos($description, mb_strtolower(lang('ticket_confirm_4'))) !== false) {
				$is_ticket_booked = true;
			}
		}
		
		if($is_ticket_booked){
			
			return true;
			
		} else {
			
			$this->form_validation->set_message('check_ticket_booked', lang('ticked_book_required'));
			
			return false;
			
		}
	}
	
}