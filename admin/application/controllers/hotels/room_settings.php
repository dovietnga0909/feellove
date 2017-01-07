<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Room_Settings extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Hotel_Model');
		$this->load->model('Room_Type_Model');
		
		$this->load->helper('hotel');
		$this->load->helper('search');
		
		$this->load->library('form_validation');
		
		$this->load->language('hotel');
		
		$this->config->load('room_meta');
		$this->config->load('hotel_meta');
	}

	public function index()
	{
		$data = _get_hotel_data();
		$data = _get_navigation($data, 6, MNU_HOTEL_PROFILE);
		
		$data['room_types'] = $this->Room_Type_Model->get_room_types($data['hotel']['id'], true);
		
		$data['max_occupancy'] 	= $this->config->item('max_occupancy_on_existing_bedding');
		$data['max_extra_beds'] = $this->config->item('max_extra_beds');
		$data['max_children'] 	= $this->config->item('max_children');
		
		$room_types = $data['room_types'];
		
		$this->form_validation->set_message('is_natural_no_zero', lang('is_natural_no_zero'));
		$this->form_validation->set_message('less_than', lang('less_than'));
		$this->form_validation->set_error_delimiters('<span class="error">', '</span><br>');
		
		foreach ($room_types as $k => $r_type) {
			$this->form_validation->set_rules('number_of_rooms_'.$k, lang('room_settings_field_numb_of_rooms'), 'required|is_natural_no_zero|less_than[1000]');
			$this->form_validation->set_rules('max_occupancy_'.$k, lang('room_settings_field_max_occupancy'), 'required|is_natural_no_zero');
			$this->form_validation->set_rules('max_extra_beds_'.$k, lang('room_settings_field_max_extra_beds'));
			$this->form_validation->set_rules('max_children_'.$k, lang('room_settings_field_max_children'));
			$this->form_validation->set_rules('rack_rate_'.$k, lang('room_settings_field_rack_rate'), 'is_natural');
			$this->form_validation->set_rules('min_rate_'.$k, lang('room_settings_field_min_rate'), 'is_natural');
			$this->form_validation->set_rules('included_breakfast_'.$k, lang('room_settings_field_included_breakfast'));
			$this->form_validation->set_rules('included_vat_'.$k, lang('room_settings_field_included_vat'));
		}
		
		if ($this->form_validation->run() == true) {
			
			foreach ($room_types as $k => $r_type) {
				$room_type = array(
						'id'  				=> $r_type['id'],
						'number_of_rooms'	=> trim($this->input->post('number_of_rooms_'.$k)),
						'max_occupancy'  	=> trim($this->input->post('max_occupancy_'.$k)),
						'max_extra_beds' 	=> trim($this->input->post('max_extra_beds_'.$k)),
						'max_children'  	=> trim($this->input->post('max_children_'.$k)),
						'rack_rate'  		=> trim($this->input->post('rack_rate_'.$k)),
						'min_rate'  		=> trim($this->input->post('min_rate_'.$k)),
						'included_breakfast'=> $this->input->post('included_breakfast_'.$k),
						'included_vat'=> $this->input->post('included_vat_'.$k),
				);
				
				$save_status = $this->Room_Type_Model->update_room_type($room_type);
			}
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("hotels/room_settings/".$data['hotel']['id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		// render view
		$data['site_title'] = lang('room_settings_type_title');
		
		_render_view('hotels/room_settings/list_room_settings', $data);
	}
}
