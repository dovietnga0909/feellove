<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partners extends BP_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Partner_Model');
		$this->load->language('partner');
		$this->load->library('pagination');
		$this->load->library('form_validation');

		$this->load->helper(array('search', 'hotel'));
		$this->config->load('partner_meta');
	}

	public function index()
	{
		// set session for menues
		$this->session->set_userdata('MENU', MNU_PARTNER);

		$data['site_title'] = lang('list_partner_title');

		$data = $this->_get_list_data($data);

		_render_view('partners/list_partners', $data, 'partners/search_partner');
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_PARTNER, $data);
	
		$search_criteria = $data['search_criteria'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['partners'] = $this->Partner_Model->search_partners($search_criteria, $per_page, $offset);
		
		$total_rows = $this->Partner_Model->get_numb_partners($search_criteria);
		
		$data = set_paging_info($data, $total_rows, 'partners');
		
		$data['payment_types'] = $this->config->item('payment_types');
		
		$data['partner_types'] = $this->config->item('partner_types');
	
		return $data;
	}

	// create a new partner
	public function create(){

		$partner_config = $this->config->item('create_partner');
		$this->form_validation->set_rules($partner_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		if ($this->form_validation->run() == true) {
			
			$service_type = $this->input->post('service_type');
			
			$partner = array(
					'name'			=> trim($this->input->post('name')),
					'phone'    		=> trim($this->input->post('phone')),
					'fax'    		=> trim($this->input->post('fax')),
					'email'     	=> strtolower(trim($this->input->post('email'))),
					'website'     	=> trim($this->input->post('website')),
					'address'    	=> trim($this->input->post('address')),
					'joining_date'  => trim($this->input->post('joining_date')),
					'service_type'  => calculate_list_value_to_bit($service_type),
			);

			$save_status = $this->Partner_Model->create_partner($partner);

			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("partners");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}

		// render view
		$data['site_title'] = lang('create_partner_title');
		
		$data['partner_types'] = $this->config->item('partner_types');
		
		$data = get_library('datepicker', $data);

		_render_view('partners/create_partner', $data);
	}

	// edit the partner
	public function edit(){
		
		$data = $this->_set_common_data();
		$data = $this->_get_navigation($data, 0);
		
		//validate form input
		$partner_config = $this->config->item('create_partner');
		$this->form_validation->set_rules($partner_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
			
			$service_type = $this->input->post('service_type');
				
			$partner = array(
					'id'			=> $data['partner']['id'],
					'name'			=> trim($this->input->post('name')),
					'phone'    		=> trim($this->input->post('phone')),
					'fax'    		=> trim($this->input->post('fax')),
					'email'     	=> strtolower(trim($this->input->post('email'))),
					'website'     	=> trim($this->input->post('website')),
					'address'    	=> trim($this->input->post('address')),
					'joining_date'  => trim($this->input->post('joining_date')),
					'service_type'  => calculate_list_value_to_bit($service_type),
			);
		
			$save_status = $this->Partner_Model->update_partner($partner);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				$this->remap_redirect($data);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		// render view
		$data['site_title'] = lang('edit_partner_title');
		
		$data['partner_types'] = $this->config->item('partner_types');
		
		$data = get_library('datepicker', $data);
		
		_render_view('partners/edit_partner', $data);
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$status = $this->Partner_Model->delete_partner($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect('partners');
	}

	// partner payment
	public function payment(){
		
		$data = $this->_set_common_data();
		$data = $this->_get_navigation($data, 1);
		
		//validate form input
		$partner_config = $this->config->item('partner_payment');
		$this->form_validation->set_rules($partner_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$partner = array(
					'id'					=> $data['partner']['id'],
					'payment_type'			=> trim($this->input->post('payment_type')),
					'bank_account_name'    	=> trim($this->input->post('bank_account_name')),
					'bank_account_number'   => trim($this->input->post('bank_account_number')),
					'bank_branch_name'     	=> trim($this->input->post('bank_branch_name')),
			);
		
			$save_status = $this->Partner_Model->update_partner($partner);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				$this->remap_redirect($data);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		// render view
		$data['site_title'] = lang('partner_mnu_payment');
		
		$data['payment_types'] = $this->config->item('payment_types');
		
		_render_view('partners/partner_payment', $data);
	}
	
	// partner payment
	public function contacts(){
	
		$data = $this->_set_common_data();
		$data = $this->_get_navigation($data, 2);
	
		//validate form input
		$partner_config = $this->config->item('partner_contact');
		$this->form_validation->set_rules($partner_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
	
			$partner = array(
					'id'							=> $data['partner']['id'],
					'reservation_contact_name'		=> trim($this->input->post('reservation_name')),
					'reservation_contact_phone'    	=> trim($this->input->post('reservation_phone')),
					'reservation_contact_email'   	=> trim($this->input->post('reservation_email')),
					
					'sale_contact_name'     	=> trim($this->input->post('sale_name')),
					'sale_contact_phone'     	=> trim($this->input->post('sale_phone')),
					'sale_contact_email'     	=> trim($this->input->post('sale_email')),
					
					'skype_contact'     		=> trim($this->input->post('skype_contact')),
					'yahoo_contact'     		=> trim($this->input->post('yahoo_contact')),
			);
	
			$save_status = $this->Partner_Model->update_partner($partner);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				$this->remap_redirect($data);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		// render view
		$data['site_title'] = lang('partner_mnu_contact');
	
		_render_view('partners/partner_contacts', $data);
	}
	
	function partner_name_check($str)
	{
		$id = $this->get_partner_id();
	
		$is_exist = $this->Partner_Model->is_unique_field_value($str, $id, 'name');
	
		if ($is_exist)
		{
			$this->form_validation->set_message('partner_name_check', lang('partner_name_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function partner_email_check($str)
	{
		$id = $this->get_partner_id();
	
		$is_exist = $this->Partner_Model->is_unique_field_value($str, $id, 'email');
	
		if ($is_exist)
		{
			$this->form_validation->set_message('partner_email_check', lang('partner_email_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function partner_phone_check($str)
	{
		$id = $this->get_partner_id();
	
		$is_exist = $this->Partner_Model->is_unique_field_value($str, $id, 'phone');
	
		if ($is_exist)
		{
			$this->form_validation->set_message('partner_phone_check', lang('partner_phone_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function get_seg_id($seg_name) {
	
		$segs = $this->uri->segment_array();
	
		foreach ($segs as $segment)
		{
			if($segment == $seg_name) {
				$hotel_id = $this->uri->segment(4);
	
				return $hotel_id;
			}
		}
	
		return null;
	}
	
	public function _set_common_data($data = array()) {
	
		$hotel_id = $this->get_seg_id('hotels');
		
		if(!empty($hotel_id)) {
			$data = _get_hotel_data(array(), $hotel_id);
		
			$id = $data['hotel']['partner_id'];
			
			// set session for menues
			$this->session->set_userdata('MENU', MNU_HOTEL_PARTNER);
		} else {
			$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
		}
	
		$partner = $this->Partner_Model->get_partner($id);
		if ($partner == false) {
			_show_error_page(lang('partner_notfound'));
			exit;
		}
	
		$data['partner'] = $partner;
	
		return $data;
	}
	
	function _get_navigation($data, $selected_id) {
	
		$nav_panel = $this->config->item('nav_panel');
	
		foreach ($nav_panel as $k => $mnu) {
			
			if(!empty($data['hotel'])) {
				$link = str_replace('partners', 'hotels/partner', $mnu['link']);
				$mnu['link'] = $link.'/'.$data['hotel']['id'];
			} else {
				$mnu['link'] = $mnu['link'].'/'.$data['partner']['id'];
			}
			
			$nav_panel[$k] = $mnu;
		}
	
		$data['nav_panel'] = $nav_panel;
	
		$data['side_mnu_index'] = $selected_id;
	
		return $data;
	}
	
	function get_partner_id() {
		$hotel_id = $this->get_seg_id('hotels');
		
		if(!empty($hotel_id)) {
			$data = _get_hotel_data(array(), $hotel_id);
		
			$id = $data['hotel']['partner_id'];
			
			// set session for menues
			$this->session->set_userdata('MENU', MNU_HOTEL_PARTNER);
		} else {
			$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
		}
		
		return $id;
	}
	
	function remap_redirect($data) {
		if(!empty($data['hotel'])) {
			redirect("hotels/profiles/".$data['hotel']['id']);
		} else {
			redirect("partners");
		}
	}
}
