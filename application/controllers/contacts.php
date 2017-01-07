<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends CI_Controller {
	
	public function __construct()
    {
        
       	parent::__construct();
       	
       	$this->load->helper(array('form','booking'));
       	
       	$this->load->model(array('Booking_Model', 'News_Model'));
	
	}
	
	function index()
	{
	    $is_mobile = is_mobile();
	    $mobile_view = $is_mobile ? 'mobile/' : '';
	    
		$data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
		
		$data['meta'] = get_header_meta(CONTACT_US_PAGE);
		
		$action = $this->input->post('action');
		
		if($action == ACTION_MAKE_BOOKING){
			
			if($this->_send_request()) {
			    redirect(get_url(CONFIRM_PAGE,array('type'=>'contact')));
			}
		}
		
		$btn_lang = '';//lang('c_send_request');
		
		$contact_params = $this->_get_contact_params();
		
		$data['contact_form'] = load_contact_form(true, $contact_params, '', $is_mobile);
		
		$data['mnu_contacts'] = load_contact_mnu(1, $is_mobile);
		
		$data['bpv_register'] = $this->load->view('common/bpv_register', array(), TRUE);
	
		_render_view($mobile_view.'contacts/contact', $data, $is_mobile);
		
		/* $data['bpv_content'] = $this->load->view( $mobile_view.'contacts/contact', $data, TRUE);
		
		$this->load->view( $mobile_view.'_templates/bpv_layout', $data); */
	}
	
	function confirm(){
		
		$is_mobile = is_mobile();
		$mobile_view = $is_mobile ? 'mobile/' : '';
		
		$data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
		
		$type = $this->input->get('type');
		
		$object = array();
        if ($type == 'hotel')
        {
            $data['mess_success'] = lang('cf_hotel_success');
            $data['mess_check'] = lang('cf_hotel_check');
            $data['email_check'] = lang('cf_hotel_email_check');
            
            $object['name'] = lang('cf_hotel_success');
        }
        elseif ($type == 'flight')
        {
            $data['mess_success'] = lang('cf_flight_success');
            $data['mess_check'] = lang('cf_flight_check');
            $data['email_check'] = lang('cf_flight_email_check');
            
            $object['name'] = lang('cf_flight_success');
        }
        elseif ($type == 'cruise')
        {
            $data['mess_success'] = lang('cf_tour_success');
            $data['mess_check'] = lang('cf_tour_check');
            $data['email_check'] = lang('cf_tour_email_check');
            
            $object['name'] = lang('cf_tour_success');
        }elseif($type == 'tour')
        {
        	$data['mess_success'] = lang('cf_tour_success');
            $data['mess_check'] = lang('cf_tour_check');
            $data['email_check'] = lang('cf_tour_email_check');
            
            $object['name'] = lang('cf_tour_success');
        }
        else
        {
            $data['mess_success'] = lang('cf_contact_success');
            $data['mess_check'] = lang('cf_contact_check');
            
            $object['name'] = lang('cf_contact_success');
        }
		
		$data['meta'] = get_header_meta(THANK_YOU_PAGE, $object);
		
		$data['bpv_register'] = $this->load->view('common/bpv_register', array(), TRUE);
		
		_render_view($mobile_view.'contacts/confirm', $data, $is_mobile);
		
		/* $data['bpv_content'] = $this->load->view( $mobile_view.'contacts/confirm', $data, TRUE);
		
		$this->load->view($mobile_view.'_templates/bpv_layout', $data); */
	}
	
	function _send_request(){
		
	    if (contact_validation()) {
	        
	        $customer = get_contact_post_data();
	        
	        $customer_id = $this->Booking_Model->create_or_update_customer($customer);
	        
	        $special_request = $customer['special_request'];
	        
	        $customer_booking = get_contact_customer_booking($customer_id, $special_request);
	        
	        $service_reservations = get_contact_service_reservations($special_request);
	        
	        $this->Booking_Model->save_customer_booking($customer_booking, $service_reservations);
	        
	        return true;
	    }
	    
	    return false;
	}
	
	function about_us(){
	    
	    $this->session->unset_userdata('MENU');
	    
	    $is_mobile = is_mobile();
	    $mobile_view = $is_mobile ? 'mobile/' : '';
		
		$data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
		
		$data['meta'] = get_header_meta(ABOUT_US_PAGE);
		
		$data['mnu_contacts'] = load_contact_mnu(2);
		
		$search_criteria['category'] = 4;
		
		$data['search_news'] = $this->News_Model->search_news($search_criteria);
		
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		
		_render_view($mobile_view.'contacts/about_us', $data, $is_mobile);
	}
	
	function term_condition(){
	    
	    $this->session->unset_userdata('MENU');
	    
	    $is_mobile = is_mobile();
	    $mobile_view = $is_mobile ? 'mobile/' : '';
	
		$data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
		
		$data['meta'] = get_header_meta(TERM_CONDITION_PAGE);
	
		$data['mnu_contacts'] = load_contact_mnu(3);
	
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		
		_render_view($mobile_view.'contacts/term_condition', $data, $is_mobile);
	}
	
	function privacy(){
	    
	    $this->session->unset_userdata('MENU');
	    
	    $is_mobile = is_mobile();
	    $mobile_view = $is_mobile ? 'mobile/' : '';
	    
		$data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
		
		$data['meta'] = get_header_meta(PRIVACY_PAGE);
		
		$data['mnu_contacts'] = load_contact_mnu(4);
		
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		
		_render_view($mobile_view.'contacts/privacy', $data, $is_mobile);
	}
	
	function faq(){
	    
	    $this->session->unset_userdata('MENU');
	    
	    $is_mobile = is_mobile();
	    $mobile_view = $is_mobile ? 'mobile/' : '';
	    
		$data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
		
		$data['meta'] = get_header_meta(FAQS_PAGE);
	
		$data['mnu_contacts'] = load_contact_mnu(5);
	
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		
		_render_view($mobile_view.'contacts/faq', $data, $is_mobile);
	}
	
	function accomplishment(){
	    
	    $this->session->unset_userdata('MENU');
	     
	    $is_mobile = is_mobile();
	    $mobile_view = $is_mobile ? 'mobile/' : '';
	     
	    $data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
	
	    $data['meta'] = get_header_meta(ACCOMPLISHMENT_PAGE);
	
	    $data['mnu_contacts'] = load_contact_mnu(7);
	
	    $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
	
	    _render_view($mobile_view.'contacts/accomplishment', $data, $is_mobile);
	}
	
	function testimonial(){
	    
	    $this->session->unset_userdata('MENU');
	
	    $is_mobile = is_mobile();
	    $mobile_view = $is_mobile ? 'mobile/' : '';
	
	    $data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
	
	    $data['meta'] = get_header_meta(TESTIMONIAL_PAGE);
	
	    $data['mnu_contacts'] = load_contact_mnu(8);
	
	    $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
	
	    _render_view($mobile_view.'contacts/testimonial', $data, $is_mobile);
	}
	
	function press(){
	     
	    $this->session->unset_userdata('MENU');
	    
	    $this->load->language('news');
	
	    $is_mobile = is_mobile();
	    $mobile_view = $is_mobile ? 'mobile/' : '';
	
	    $data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
	
	    $data['meta'] = get_header_meta(BESTPRICE_WITH_PRESS_PAGE);
	
	    $data['mnu_contacts'] = load_contact_mnu(9);
	
	    $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
	    
	    $search_criteria['category'] = 4;
	    
	    $page = $this->input->get_post('page', true);
	     
	    if(!empty($page) && is_numeric($page)) {
	        $search_criteria['page'] = $page;       
	    }
	    
	    $data['count_results'] = $this->News_Model->count_search_news($search_criteria);
	    
	    $data['search_news'] = $this->News_Model->search_news($search_criteria);
	    
	    $data = $this->_set_paging_info($data);
	
	    _render_view($mobile_view.'contacts/press', $data, $is_mobile);
	}
	
	/**
	 * _set_paging_info()
	 *
	 * set paging information
	 *
	 * @author toanlk
	 * @since Sep 12, 2014
	 */
	public function _set_paging_info($data)
	{
	    $this->load->library('pagination');
	
	    $offset = !empty($search_criteria['page']) ? $search_criteria['page'] : 0;
	    
	    $paging_config = $this->config->item('paging_config');
	
	    $total_rows = $data['count_results'];
	
	    // paging url
	    $paging_config = get_paging_config($total_rows, BESTPRICE_WITH_PRESS_PAGE, 1);
	
	    // initialize pagination
	    $this->pagination->initialize($paging_config);
	
	    $paging_info['paging_text'] = get_paging_text($total_rows, $offset);
	
	    $paging_info['paging_links'] = $this->pagination->create_links();
	
	    $data['paging_info'] = $paging_info;
	
	    return $data;
	}
	
	function company_address(){
	    
	    $is_mobile = is_mobile();
	    
	    if($is_mobile) {
	        $this->load->view('mobile/contacts/company_address');
	    } else {
	        $this->load->view('contacts/company_address');
	    }
	}
	
	function _get_contact_params(){
		$ret= '';
		
		$des = $this->input->get('des', true);
		$hotel = $this->input->get('hotel', true);
		$room = $this->input->get('room', true);
		$startdate = $this->input->get('startdate', true);
		$night = $this->input->get('night', true);
		
		$cruise = $this->input->get('cruise', true);
		$duration = $this->input->get('duration', true);
		
		$tour = $this->input->get('tour', true);
		
		if(!empty($hotel)){
			$ret .= $hotel;
			
			if(!empty($des)){
				$ret .= ' ('.$des.')';
			}
		}
		
		if(!empty($cruise)){
			$ret .= $cruise;
		}
		
		if(!empty($tour)){
		    $ret .= $tour;
		}
		
		if(!empty($room)){
			$ret .= "\n".$room;
		}
		
		if(!empty($startdate) && empty($tour) ){
			$ret .= "\n".lang('check_in_date').': '.$startdate;
		}
		
		if(!empty($startdate) && !empty($tour) ){
			$ret .= "\n".lang('departure_in_date').': '.$startdate;
		}
		
		if(!empty($night)){
			$ret .= "\n".lang('number_of_nights').': '.$night;
		}
		
		if(!empty($duration)){
			$ret .= "\n".lang('tour_duration').': '.$duration . ' ' . lang('unit_day');
		}
		
		if(!empty($ret)){
			$ret .= "\n".'----------------------------';
		}
		
		return $ret;
	}
	
	function groupon_request() {
			
		$customer = array(
			'special_request' 	=> trim($this->input->post('groupon_request')),
			'email'				=> trim($this->input->post('groupon_email')),
			'phone'				=> trim($this->input->post('groupon_phone_number')),
			'full_name'			=> trim($this->input->post('groupon_email')),
		);
		
		$popup_type = trim($this->input->post('popup_type'));
		
		$sr_name = $popup_type == 'groupon' ? 'Group Travel Request' :'Quick Customer Request';
		
		$customer_id = $this->Booking_Model->create_or_update_customer($customer);
		
		$special_request = $customer['special_request'];
		
		$customer_booking = get_contact_customer_booking($customer_id, $special_request);
		
		$service_reservations = get_contact_service_reservations($special_request, $sr_name);
		
		$this->Booking_Model->save_customer_booking($customer_booking, $service_reservations);
		
		echo 'sended';
	}
	
	/**
	 * Khuyenpv 17.09.2014
	 * Get Request of Tour and Save to Customer Booking
	 */
	function tour_request(){
		
		$customer = array(
			'full_name'			=> trim($this->input->post('t_email')),
			'email'				=> trim($this->input->post('t_email')),
			'phone'				=> trim($this->input->post('t_phone')),
		);
		
		// $start_date = trim($this->input->post('t_start_date'));
		$tour_name  = trim($this->input->post('t_tour_name')); 
		/*
		$adults = trim($this->input->post('t_adults'));
		$children = trim($this->input->post('t_children'));
		$infants = trim($this->input->post('t_infants'));
		*/
		$special_request = '';
		
		if(!empty($tour_name)){
			$special_request .= "Tour name: ". $tour_name. "\n";
		}
		
		/*
		if(!empty($start_date)){
			$special_request .= "Start date: ". $start_date. "\n";
		}
		
		if($adults !== ''){
			$special_request .= "Adults: ". $adults. "\n";
		}
		
		if(!empty($children)){
			$special_request .= "Children: ". $children. "\n";
		}
		
		if(!empty($infants)){
			$special_request .= "Infants: ". $infants. "\n";
		}*/
		
		$special_request .= trim($this->input->post('t_request'));
		
		$sr_name = 'Quick Tour Request';
		
		$customer_id = $this->Booking_Model->create_or_update_customer($customer);
		
		$customer_booking = get_contact_customer_booking($customer_id, $special_request);
		
		$service_reservations = get_contact_service_reservations($special_request, $sr_name);
		
		$this->Booking_Model->save_customer_booking($customer_booking, $service_reservations);
		
		// redirect to confirm page
		redirect(get_url(CONFIRM_PAGE,array('type'=>'tour')));
	}
	
	function payment_methods() {
	    
	    $is_mobile = is_mobile();
	    $mobile_view = $is_mobile ? 'mobile/' : '';
	    
	    $data = get_in_page_theme(THANK_YOU_PAGE, array(), $is_mobile);
		
		$data['meta'] = get_header_meta(PAYMENT_METHODS_PAGE);
		
		$data['bank_transfer'] = $this->config->item('bank_transfer');
		
		$data['mnu_contacts'] = load_contact_mnu(6);
		
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		
		_render_view($mobile_view.'contacts/payment_methods', $data, $is_mobile);
		
		/* $data['bpv_content'] = $this->load->view('contacts/payment_methods', $data, TRUE);
		
		$this->load->view('_templates/bpv_layout', $data); */
	}
}
