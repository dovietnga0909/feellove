<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cabins extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Cruise_Model');
		$this->load->model('Cabin_Model');
		$this->load->model('Facility_Model');
		
		$this->load->helper('cruise');
		$this->load->helper('search');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->load->language('cruise');
		
		$this->config->load('cabin_meta');
		$this->config->load('cruise_meta');
	}

	public function index()
	{
		$data = _get_cruise_data();
		$data = _get_navigation($data, 4, MNU_CRUISE_PROFILE);
		
		$data = $this->_get_list_data($data);
		
		// render view
		$data['site_title'] = lang('cruise_cabins_title');
		
		_render_view('cruises/cabins/list_cabins', $data);
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_CABINS, $data);
	
		$search_criteria = $data['search_criteria'];
		
		// set cruise id
		$search_criteria['cruise_id'] = $data['cruise']['id'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['cabins'] = $this->Cabin_Model->search_cabins($search_criteria, $per_page, $offset);
		
		$total_rows = $this->Cabin_Model->get_numb_cabins($search_criteria);
		
		$data = set_paging_info($data, $total_rows, 'cruises/cabins/');
		
		$data = set_max_min_pos($data, MODULE_CABINS, $search_criteria);
	
		return $data;
	}
	
	// create a new cabin
	public function create(){
		$data = _get_cruise_data();
		$data = _get_navigation($data, 4, MNU_CRUISE_PROFILE);
		
		$cabin_config = $this->config->item('create_cabin');
		$this->form_validation->set_rules($cabin_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
			
			$b_config = $this->input->post('bed_config');
			
			$facilities = $this->input->post('facilities');
	
			$cabin = array(
					'name'				=> trim($this->input->post('name')),
					'description'  		=> trim($this->input->post('description')),
					'minimum_cabin_size' => trim($this->input->post('cabin_size')),
					'bed_config'  		=> calculate_list_value_to_bit($b_config),
					'facilities'  		=> _array_to_string($facilities),
					'cruise_id'  		=> $data['cruise']['id'],
			);
	
			$save_status = $this->Cabin_Model->create_cabin($cabin);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("cruises/cabins/".$data['cruise']['id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
	
		// render view
		$data['site_title'] = lang('create_cabin_title');
		
		$data['bed_configuration'] = $this->config->item('bed_configuration');
		
		$data['facilities'] = $this->Facility_Model->get_facilities_by_type(null, null, FACILITY_CABIN);
		
		$data = get_library('tinymce', $data);
	
		_render_view('cruises/cabins/create_cabin', $data);
	}
	
	// edit the cabin type
	public function edit(){
	
		$data = $this->_get_cabin();
		
		$cabin_config = $this->config->item('create_cabin');
		$this->form_validation->set_rules($cabin_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
	
			$b_config = $this->input->post('bed_config');
			
			$facilities = $this->input->post('facilities');
	
			$cabin = array(
					'id'				=> $data['cabin']['id'],
					'name'				=> trim($this->input->post('name')),
					'description'  		=> trim($this->input->post('description')),
					'minimum_cabin_size' => trim($this->input->post('cabin_size')),
					'bed_config'  		=> calculate_list_value_to_bit($b_config),
					'facilities'  		=> _array_to_string($facilities),
					'status'  			=> trim($this->input->post('status')),
			);
	
			$save_status = $this->Cabin_Model->update_cabin($cabin);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("cruises/cabins/".$data['cabin']['cruise_id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['status_config'] = $this->config->item('status_config');
	
		// render view
		$data['site_title'] = lang('edit_cabin_title');
		
		$data = get_library('tinymce', $data);
	
		_render_view('cruises/cabins/edit_cabin', $data);
	}
	
	function cabinname_check()
	{
		$cruise_id = trim($this->input->post('cruise_id'));
		$cabin_name = trim($this->input->post('name'));
		$cabin_id = trim($this->input->post('cabin_id'));
		
		$is_unique = $this->Cabin_Model->is_unique_cabin_name($cruise_id, $cabin_name, $cabin_id);
		
		if (!$is_unique)
		{
			$this->form_validation->set_message('cabinname_check', lang('cabin_name_is_unique'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function _get_cabin($data = array()) {
		
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
		
		$cabin = $this->Cabin_Model->get_cabin($id);
		if ($cabin == false) {
			_show_error_page(lang('cruise_notfound'));
			exit;
		}
		
		$data['cabin'] = $cabin;
		
		$data['bed_configuration'] = $this->config->item('bed_configuration');
		
		$data['facilities'] = $this->Facility_Model->get_facilities_by_type(null, null, FACILITY_CABIN);
		
		$data = _get_cruise_data($data, $cabin['cruise_id']);
		$data = _get_navigation($data, 4, MNU_CRUISE_PROFILE);
		
		return $data;
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data = $this->_get_cabin();
	
		$status = $this->Cabin_Model->delete_cabin($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect("cruises/cabins/".$data['cabin']['cruise_id']);
	}
	
	function settings() {
		$data = $this->_get_cabin();
		
		$data['max_occupancy'] 	= $this->config->item('max_occupancy_on_existing_bedding');
		$data['max_extra_beds'] = $this->config->item('max_extra_beds');
		$data['max_children'] 	= $this->config->item('max_children');
		
		$cabin_config = $this->config->item('cabin_settings');
		$this->form_validation->set_rules($cabin_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_message('is_natural_no_zero', lang('is_natural_no_zero'));
		$this->form_validation->set_message('less_than', lang('less_than'));
		
		if ($this->form_validation->run() == true) {
		
			$cabin = array(
					'id'  				=> $data['cabin']['id'],
					'number_of_cabins'	=> trim($this->input->post('number_of_cabins')),
					'max_occupancy'  	=> trim($this->input->post('max_occupancy')),
					'max_extra_beds' 	=> trim($this->input->post('max_extra_beds')),
					'max_children'  	=> trim($this->input->post('max_children')),
			);
		
			$save_status = $this->Cabin_Model->update_cabin($cabin);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("cruises/cabins/".$data['cabin']['cruise_id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		// render view
		$data['site_title'] = lang('cabin_settings_type_title');
		
		_render_view('cruises/cabins/cabin_settings', $data);
	}
	
	function re_order() {
		if(isset($_GET["id"]) && isset($_GET["act"]) && isset($_GET["c_id"])) {
			$id 	= $_GET["id"];
			$action = $_GET["act"];
			$cruise_id = $_GET["c_id"];
	
			if(is_numeric($id) && is_numeric($cruise_id)) {
				
				$search_criteria = array();
				$search_criteria['cruise_id'] = $cruise_id;
	
				$status = bp_re_order($id, $action, MODULE_CABINS, $search_criteria);
					
				if ($status)
				{
					$this->session->set_flashdata('message', lang('update_successful'));
					redirect("cruises/cabins/".$cruise_id);
				}
			}
				
			if(!is_null($status)){
				$data['save_status'] = $status;
			}
		}
	}
	
	function facilities_check() {
		
		$facilities = $this->input->post('facilities');
		
		$cnt = $this->Facility_Model->get_numb_facilities();
		
		if( !empty($cnt) && empty($facilities)) {
			$this->form_validation->set_message('facilities_check', lang('facilities_is_required'));
			return false;
		}
		
		return true;
	}
}
