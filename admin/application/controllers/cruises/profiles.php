<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profiles extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Cruise_Model');
		$this->load->model('Partner_Model');
		$this->load->model('Destination_Model');
		$this->load->model('Cancellation_Model');
		$this->load->model('Tour_Model');
		
		$this->load->helper('cruise');
		
		$this->load->language('cruise');
		$this->load->language('tour');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->config->load('cruise_meta');
	}

	public function index()
	{
		$data = _get_cruise_data();
		$data = _get_navigation($data, 0, MNU_CRUISE_PROFILE);
		
		$cruise_config = $this->config->item('cruise_rules');
		$this->form_validation->set_rules($cruise_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$cruise_types = $this->input->post('cruise_type');
			
			$cruise = array(
					'id'			=> $data['cruise']['id'],
					'name'			=> trim($this->input->post('name')),
					'address'    	=> trim($this->input->post('address')),
					'cruise_type'   => calculate_list_value_to_bit($cruise_types),
					'star'    		=> trim($this->input->post('star')),
					'description'  	=> trim($this->input->post('description')),
					'status'  		=> trim($this->input->post('status')),
					
					//'destination_id'		=> trim($this->input->post('destination_id')),
			);
			
			if(is_admin()) {
				$cruise['partner_id'] = $this->input->post('partner_id');
			}
		
			$status = $this->Cruise_Model->update_cruise($cruise);
		
			if ($status)
			{
				$this->session->set_flashdata('message', lang('edit_cruise_successful'));
				redirect("cruises");
			} else {
				$this->set_error('edit_cruise_unsuccessful');
			}
		}
		
		$data['cruise_star'] = $this->config->item('cruise_star');
		
		$data['cruise_type'] = $this->config->item('cruise_type');
		
		$data['status_config'] = $this->config->item('status_config');
		
		// get service type cruise
		$data['partners'] = $this->Partner_Model->get_all_partners(1);
		
		//$data['destinations'] = $this->Cruise_Model->get_all_destinations();
		
		// render view
		$data['site_title'] = lang('cruise_profile_title');
		
		$data = get_library('tinymce', $data);
		
		_render_view('cruises/profiles/profiles_view', $data);
	}
	
	public function cruise_settings(){
		$data = _get_cruise_data();
		$data = _get_navigation($data, 1, MNU_CRUISE_PROFILE);
		
		$cruise_config = $this->config->item('cruise_settings');
		$this->form_validation->set_rules($cruise_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$cruise = array(
					'id'						=> $data['cruise']['id'],
					'cancellation_id'			=> trim($this->input->post('default_cancellation')),
					'infant_age_util'			=> trim($this->input->post('infant_age_util')),
					'children_age_to'			=> trim($this->input->post('children_age_to')),
					'extra_bed_requires_from'   => trim($this->input->post('extra_bed_requires_from')),
					'children_stay_free'    	=> trim($this->input->post('children_stay_free')),
					'infants_policy'    		=> trim($this->input->post('infants_policy')),
					'children_policy'  			=> trim($this->input->post('children_policy')),
					'check_in'  				=> trim($this->input->post('check_in')),
					'check_out'  				=> trim($this->input->post('check_out')),
					'shuttle_bus'  				=> trim($this->input->post('shuttle_bus')),
					'guide'  					=> trim($this->input->post('guide')),
					'extra_cancellation'  					=> trim($this->input->post('extra_cancellation')),
			);
		
			$status = $this->Cruise_Model->update_cruise($cruise);
		
			if ($status)
			{
				$this->session->set_flashdata('message', lang('edit_cruise_successful'));
				redirect("cruises");
			} else {
				$this->set_error('edit_cruise_unsuccessful');
			}
		}
		
		$data = get_library('tinymce', $data);
		
		$data['max_infant_age'] 	= $this->config->item('max_infant_age');
		
		$data['max_children_age'] 	= $this->config->item('max_children_age');
		
		$data['cancellations'] 		= $this->Cancellation_Model->get_all_cancellations();
		
		// render view
		$data['site_title'] = lang('cruise_settings_title');
		
		_render_view('cruises/profiles/cruise_settings', $data);
	}
	
	function cruise_name_check($str)
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$is_exist = $this->Cruise_Model->is_unique_cruise_name($str, $id);
	
		if ($is_exist)
		{
			$this->form_validation->set_message('cruise_name_check', lang('cruise_name_is_unique'));
			return false;
		}
	
		return true;
	}
	
	function children_age_check($children_age_to) {
	
		$infant_age_util = $this->input->post('infant_age_util');
	
		if($children_age_to <= ($infant_age_util + 1) ) {
			$this->form_validation->set_message('children_age_check', lang('children_age_check'));
			return false;
		}
	
		return true;
	}
	
	public function map(){
		
		$data = _get_cruise_data();
		$data = _get_navigation($data, 2, MNU_CRUISE_PROFILE);
		
		$cruise_config = $this->config->item('cruise_map');
		$this->form_validation->set_rules($cruise_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$cruise = array(
					'id'				=> $data['cruise']['id'],
					'latitude'			=> trim($this->input->post('latitude')),
					'longitude'			=> trim($this->input->post('longitude')),
					//'destination_id'	=> trim($this->input->post('destination_id')),
			);
		
			$status = $this->Cruise_Model->update_cruise($cruise);
		
			if ($status)
			{
				$this->session->set_flashdata('message', lang('edit_cruise_successful'));
				redirect("cruises");
			} else {
				$this->set_error('edit_cruise_unsuccessful');
			}
		}
		
		$data = get_library('google-map', $data);
		
		// render view
		$data['site_title'] = lang('cruise_map_title');
		
		_render_view('cruises/profiles/map', $data);
	}
	
	public function tours(){
	
		$data = _get_cruise_data();
		$data = _get_navigation($data, 6, MNU_CRUISE_PROFILE);
		
		$this->config->load('tour_meta');
		
		$data['tours'] = $this->Tour_Model->get_cruise_tours($data['cruise']['id']);
	
		// render view
		$data['site_title'] = lang('cruise_tours_title');
	
		_render_view('cruises/profiles/cruise_tours', $data);
	}
}
