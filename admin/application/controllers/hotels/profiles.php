<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profiles extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Hotel_Model');
		$this->load->model('Partner_Model');
		$this->load->model('Destination_Model');
		$this->load->model('Cancellation_Model');
		
		$this->load->helper('hotel');
		
		$this->load->language(array('hotel', 'partner'));
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->config->load('hotel_meta');
	}

	public function index()
	{
		$data = _get_hotel_data();
		$data = _get_navigation($data, 0, MNU_HOTEL_PROFILE);
		
		$data['hotel']['partner'] = $this->Hotel_Model->get_partner_of_hotel($data['hotel']['partner_id']);
		
		$hotel_config = $this->config->item('hotel_rules');
		
		if(is_admin()) {
		    $hotel_rules_addition = $this->config->item('hotel_rules_addition');
		    $hotel_config = array_merge($hotel_config, $hotel_rules_addition);
		}
		
		$this->form_validation->set_rules($hotel_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$hotel = array(
					'id'			=> $data['hotel']['id'],
					'name'			=> trim($this->input->post('name')),
					'address'    	=> trim($this->input->post('address')),
					'star'    		=> trim($this->input->post('star')),
					'description'  	=> trim($this->input->post('description')),
					'status'  		=> trim($this->input->post('status')),
					
					'destination_id'		=> trim($this->input->post('destination_id')),
			);
			
			$old_destination_id = $hotel['destination_id'] != $data['hotel']['destination_id'] ? $data['hotel']['destination_id'] : null;
			
			if(is_admin()) {
				$hotel['partner_id'] = $this->input->post('partner_id');
				$hotel['keywords'] = $this->input->post('keywords');
			}
		
			$status = $this->Hotel_Model->update_hotel($hotel, $old_destination_id);
		
			if ($status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('edit_hotel_successful'));
				redirect("hotels");
			} else {
				$this->set_error('edit_hotel_unsuccessful');
			}
		}
		
		$data['hotel_star'] = $this->config->item('hotel_star');
		
		$data['status_config'] = $this->config->item('status_config');
		
		$data['partners'] = $this->Partner_Model->get_all_partners();
		
		$data['destinations'] = $this->Hotel_Model->get_all_destinations();
		
		// render view
		$data['site_title'] = lang('hotel_profile_title');
		
		$data = get_library('tinymce', $data);
		
		_render_view('hotels/profiles/profiles_view', $data);
	}
	
	public function hotel_settings(){
		$data = _get_hotel_data();
		$data = _get_navigation($data, 1, MNU_HOTEL_PROFILE);
		
		$hotel_config = $this->config->item('hotel_settings');
		$this->form_validation->set_rules($hotel_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$hotel = array(
					'id'						=> $data['hotel']['id'],
					'cancellation_id'			=> trim($this->input->post('default_cancellation')),
					'infant_age_util'			=> trim($this->input->post('infant_age_util')),
					'children_age_to'			=> trim($this->input->post('children_age_to')),
					'extra_bed_requires_from'   => trim($this->input->post('extra_bed_requires_from')),
					'children_stay_free'    	=> trim($this->input->post('children_stay_free')),
					'infants_policy'    		=> trim($this->input->post('infants_policy')),
					'children_policy'  			=> trim($this->input->post('children_policy')),
					'check_in'  				=> trim($this->input->post('check_in')),
					'check_out'  				=> trim($this->input->post('check_out')),
					'extra_cancellation'  		=> trim($this->input->post('extra_cancellation')),
			);
		
			$status = $this->Hotel_Model->update_hotel($hotel);
		
			if ($status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('edit_hotel_successful'));
				redirect("hotels");
			} else {
				$this->set_error('edit_hotel_unsuccessful');
			}
		}
		
		$data = get_library('tinymce', $data);
		
		$data['max_infant_age'] 	= $this->config->item('max_infant_age');
		
		$data['max_children_age'] 	= $this->config->item('max_children_age');
		
		$data['cancellations'] 		= $this->Cancellation_Model->get_all_cancellations(HOTEL);
		
		// render view
		$data['site_title'] = lang('hotel_settings_title');
		
		_render_view('hotels/profiles/hotel_settings', $data);
	}
	
	public function map(){
		
		$data = _get_hotel_data();
		$data = _get_navigation($data, 2, MNU_HOTEL_PROFILE);
		
		$hotel_config = $this->config->item('hotel_map');
		$this->form_validation->set_rules($hotel_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$hotel = array(
					'id'				=> $data['hotel']['id'],
					'latitude'			=> trim($this->input->post('latitude')),
					'longitude'			=> trim($this->input->post('longitude')),
					//'destination_id'	=> trim($this->input->post('destination_id')),
			);
		
			$status = $this->Hotel_Model->update_hotel($hotel);
		
			if ($status)
			{
				$this->session->set_flashdata('message', lang('edit_hotel_successful'));
				redirect("hotels");
			} else {
				$this->set_error('edit_hotel_unsuccessful');
			}
		}
		
		$data = get_library('google-map', $data);
		
		// render view
		$data['site_title'] = lang('hotel_map_title');
		
		_render_view('hotels/profiles/map', $data);
	}
	
	function hotel_name_check($str)
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$is_exist = $this->Hotel_Model->is_unique_hotel_name($str, $id);
	
		if ($is_exist)
		{
			$this->form_validation->set_message('hotel_name_check', lang('hotel_name_is_unique'));
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
}
