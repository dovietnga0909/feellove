<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabin_Settings extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Cruise_Model');
		$this->load->model('Cabin_Model');
		
		$this->load->helper('cruise');
		$this->load->helper('search');
		
		$this->load->library('form_validation');
		
		$this->load->language('cruise');
		
		$this->config->load('cabin_meta');
		$this->config->load('cruise_meta');
	}

	public function index()
	{
		$data = _get_cruise_data();
		$data = _get_navigation($data, 5, MNU_CRUISE_PROFILE);
		
		$data['cabins'] = $this->Cabin_Model->get_cabins($data['cruise']['id'], true);
		
		$data['max_occupancy'] 	= $this->config->item('max_occupancy_on_existing_bedding');
		$data['max_extra_beds'] = $this->config->item('max_extra_beds');
		$data['max_children'] 	= $this->config->item('max_children');
		
		$cabins = $data['cabins'];
		
		$this->form_validation->set_message('is_natural_no_zero', lang('is_natural_no_zero'));
		$this->form_validation->set_message('less_than', lang('less_than'));
		$this->form_validation->set_error_delimiters('<span class="error">', '</span><br>');
		
		foreach ($cabins as $k => $r_type) {
			$this->form_validation->set_rules('number_of_cabins_'.$k, lang('cabin_settings_field_numb_of_cabins'), 'required|is_natural_no_zero|less_than[1000]');
			$this->form_validation->set_rules('max_occupancy_'.$k, lang('cabin_settings_field_max_occupancy'), 'required|is_natural_no_zero');
			$this->form_validation->set_rules('max_extra_beds_'.$k, lang('cabin_settings_field_max_extra_beds'));
			$this->form_validation->set_rules('max_children_'.$k, lang('cabin_settings_field_max_children'));
			$this->form_validation->set_rules('included_vat_'.$k, lang('cabin_settings_field_included_vat'));
			/* $this->form_validation->set_rules('rack_rate_'.$k, lang('cabin_settings_field_rack_rate'), 'is_natural');
			$this->form_validation->set_rules('min_rate_'.$k, lang('cabin_settings_field_min_rate'), 'is_natural'); */
		}
		
		if ($this->form_validation->run() == true) {
			
			foreach ($cabins as $k => $r_type) {
				$cabin = array(
						'id'  				=> $r_type['id'],
						'number_of_cabins'	=> trim($this->input->post('number_of_cabins_'.$k)),
						'max_occupancy'  	=> trim($this->input->post('max_occupancy_'.$k)),
						'max_extra_beds' 	=> trim($this->input->post('max_extra_beds_'.$k)),
						'max_children'  	=> trim($this->input->post('max_children_'.$k)),
				        'included_vat'  	=> trim($this->input->post('included_vat_'.$k)),
						/* 'rack_rate'  		=> trim($this->input->post('rack_rate_'.$k)),
						'min_rate'  		=> trim($this->input->post('min_rate_'.$k)), */
				);
				
				$save_status = $this->Cabin_Model->update_cabin($cabin);
			}
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("cruises/cabin_settings/".$data['cruise']['id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		// render view
		$data['site_title'] = lang('cabin_settings_type_title');
		
		_render_view('cruises/cabin_settings/list_cabin_settings', $data);
	}
}
