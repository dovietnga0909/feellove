<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cruise_Facilities extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Facility_Model');
		$this->load->model('Cruise_Model');
		
		$this->load->language('facility');
		$this->load->language('cruise');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->load->helper('cruise');
		
		$this->config->load('facility_meta');
		$this->config->load('cruise_meta');
	}

	public function index()
	{
		$data = _get_cruise_data();
		$data = _get_navigation($data, 2, MNU_CRUISE_PROFILE);
		
		$data['site_title'] = lang('list_facility_title');

		$data = $this->_get_list_data($data);

		_render_view('/cruises/facilities/cruise_facilities', $data);
	}
	
	function _get_list_data($data = array()){

		$group_id = 0;
		if(isset($_GET["g_id"]) && is_numeric($_GET["g_id"])) {
			$group_id = $_GET["g_id"];
		}
		$data['group_id'] = $group_id;

		// get facilities by cruise
		$facilities = $this->Facility_Model->get_facilities_by_type($data['cruise']['id'], $group_id, FACILITY_CRUISE);
		
		$cruise_facilities = explode('-', $data['cruise']['facilities']);
		
		$numb_avaiable = 0;
		
		foreach ($facilities as $k => $facility) {
			if(in_array($facility['id'], $cruise_facilities)) {
				$facility['avaiable'] = STATUS_AVAIABLE;
				$numb_avaiable++;
			} else {
				$facility['avaiable'] = 0;
			}
			
			$facilities[$k] = $facility;
		}
		
		$data['facilities'] = $facilities;
		
		$data['numb_avaiable'] = $numb_avaiable;
		
		$data['facility_types'] = $this->config->item('facility_types');
		
		$data['facility_groups'] = $this->config->item('facility_groups');
		
		$data['facility_count'] = $this->Facility_Model->count_facilities($data['cruise']['id'], $data['facility_groups'], FACILITY_CRUISE);

		return $data;
	}
	
	// activate or deactivate the facility
	function update() {
		
		//sleep(1);
		$avaiable = 0;
		
		if(isset($_GET["c_id"]) && isset($_GET["f_id"])) {
			$cruise_id = $_GET["c_id"];
			$facility_id = $_GET["f_id"];
			
			$group_id = isset($_GET["g_id"]) ? $_GET["g_id"] : '';
			
			$data = _get_cruise_data(array(), $cruise_id);
			
			if(is_numeric($cruise_id) && is_numeric($facility_id)) {
				
				$facilities = $data['cruise']['facilities'];
				
				if(!empty($facilities)) {
					$h_facilities = explode('-', $facilities);
					
					if(in_array($facility_id, $h_facilities)) {
						// remove facility
						$facilities = _array_to_string($h_facilities, $facility_id);
					} else {
						// add facility
						$facilities = $facilities . '-' . $facility_id;
						$avaiable = STATUS_AVAIABLE;
					}
				} else {
					$facilities = $facility_id;
					$avaiable = STATUS_AVAIABLE;
				}

				$cruise = array(
					'id'			=> $data['cruise']['id'],
					'facilities'	=> $facilities,
				);
				
				
				$status = $this->Cruise_Model->update_cruise($cruise);
					
				if ($status)
				{
					echo $avaiable;
					/*
					$this->session->set_flashdata('message', lang('edit_cruise_successful'));
					$param = !empty($group_id) ? '?g_id='.$group_id : '';
					redirect("cruises/facilities/".$cruise_id . $param);
					*/
				} else {
					//$this->set_error('edit_cruise_unsuccessful');
					echo lang('edit_cruise_unsuccessful');
				}
			}
		}
	}
	
	// create a new facility
	public function create(){
		
		$data = _get_cruise_data();
		$data = _get_navigation($data, 3);
		
		$facility_config = $this->config->item('create_facility');
		$this->form_validation->set_rules($facility_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
				
			$facility = array(
					'name'			=> trim($this->input->post('name')),
					'type_id'    	=> calculate_list_value_to_bit(array(FACILITY_CRUISE)), // cruise facility
					'group_id'    	=> trim($this->input->post('group_id')),
					'cruise_id'    	=> $data['cruise']['id'],
					'is_important'  => $this->input->post('is_important'),
			);
	
			$id = $this->Facility_Model->create_facility($facility);
	
			if ($id)
			{
				$this->session->set_flashdata('message', lang('create_facility_successful'));
				redirect("cruises/facilities/".$data['cruise']['id']);
			} else {
				$this->set_error('create_facility_unsuccessful');
			}
		}
		
		$data['facility_groups'] = $this->config->item('facility_groups');
	
		// render view
		$data['site_title'] = lang('create_facility_title');
	
		_render_view('cruises/facilities/create_facility', $data);
	}
	
	public function _get_facility_data($data = array()){
	
		$id = (int)$this->uri->segment(3);
	
		$facility = $this->Facility_Model->get_facility($id);
		if ($facility == false) {
			_show_error_page(lang('facility_notfound'));
			exit;
		}
	
		$data['facility'] = $facility;
	
		return $data;
	}
	
	// edit the facility
	public function edit(){
		
		$data = $this->_get_facility_data();
		$data = _get_cruise_data($data, $data['facility']['cruise_id']);
		$data = _get_navigation($data, 3);
		
		$facility_config = $this->config->item('create_facility');
		$this->form_validation->set_rules($facility_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$facility = array(
					'id'			=> $data['facility']['id'],
					'type_id'    	=> calculate_list_value_to_bit(array(FACILITY_CRUISE)), // cruise facility
					'name'			=> trim($this->input->post('name')),
					'group_id'    	=> trim($this->input->post('group_id')),
					'is_important'  => $this->input->post('is_important'),
			);
		
			$status = $this->Facility_Model->update_facility($facility);
		
			if ($status)
			{
				$this->session->set_flashdata('message', lang('edit_facility_successful'));
				redirect("cruises/facilities/".$data['facility']['cruise_id']);
			} else {
				$this->set_error('edit_facility_unsuccessful');
			}
		}
		
		$data['facility_groups'] = $this->config->item('facility_groups');
		
		// render view
		$data['site_title'] = lang('edit_facility_title');
		
		_render_view('cruises/facilities/edit_facility', $data);
	}
	
	function delete() {
		
		$data = $this->_get_facility_data();
			
		$status = $this->Facility_Model->delete_facility($data['facility']['id']);
		
		if($status){
		
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
		
		} else {
		
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
		
		redirect("cruises/facilities/".$data['facility']['cruise_id']);
	}
}
