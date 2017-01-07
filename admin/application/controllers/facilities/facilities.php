<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facilities extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model(array('Facility_Model'));
		$this->load->language(array('facility'));
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->load->helper('search');
		$this->config->load('facility_meta');
	}

	public function index()
	{
		//$this->Facility_Model->initFacilities();
		$this->session->set_userdata('MENU', MNU_FACILITY);
		
		$data['site_title'] = lang('list_facility_title');

		$data = $this->_get_list_data($data);

		_render_view('/facilities/list_facilities', $data, '/facilities/search_facility');
	}

	function _get_list_data($data = array()){

		$data = build_search_criteria(MODULE_FACILITY, $data);

		$search_criteria = $data['search_criteria'];

		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
		
		$per_page = $this->config->item('per_page');
		
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
		
		$data['facilities'] = $this->Facility_Model->search_facilities($search_criteria, $per_page, $offset);
		
		$total_rows = $this->Facility_Model->get_numb_facilities($search_criteria);
		
		$data = set_paging_info($data, $total_rows, 'facilities');
		
		$data = set_max_min_pos($data, MODULE_FACILITY);
		
		$data['facility_types'] = $this->config->item('facility_types');
		
		$data['facility_groups'] = $this->config->item('facility_groups');

		return $data;
	}
	
	// activate the facility
	function activate()
	{
		$this->_set_facility_status(STATUS_ACTIVE);
	}
	
	// deactivate the facility
	function deactivate()
	{
		$this->_set_facility_status(STATUS_INACTIVE);
	}
	
	function _set_facility_status($status) {
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
	
		$facility = $this->Facility_Model->get_facility($id);
		if ($facility == false) {
			_show_error_page(lang('facility_notfound'));
			exit;
		}
	
		$facility = array(
				'id'			=> $facility['id'],
				'status'     	=> $status,
		);
	
		$save_status = $this->Facility_Model->update_facility($facility);
	
		if ($save_status)
		{
			$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
			redirect("facilities");
		} else {
			if(!is_null($save_status)){
				$data['save_status'] = $save_status;
			}
		}
	}
	
	// create a new facility
	public function create(){
		
		$facility_config = $this->config->item('create_facility');
		$this->form_validation->set_rules($facility_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
			
			$types = $this->input->post('type_id');
				
			$facility = array(
					'name'			=> trim($this->input->post('name')),
					'type_id'    	=> calculate_list_value_to_bit($types),
					'group_id'    	=> $this->input->post('group_id'),
					'is_important'  => $this->input->post('is_important'),
			);
	
			$save_status = $this->Facility_Model->create_facility($facility);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("facilities/");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['facility_types'] = $this->config->item('facility_types');
		
		$data['facility_groups'] = $this->config->item('facility_groups');
	
		// render view
		$data['site_title'] = lang('create_facility_title');
	
		_render_view('facilities/create_facility', $data);
	}
	
	public function _get_facility_data($data = array()){
	
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
	
		$facility = $this->Facility_Model->get_facility($id);
	
		$data['facility'] = $facility;
	
		return $data;
	}
	
	// edit the facility
	public function edit(){
		
		$data = $this->_get_facility_data();
		
		$facility_config = $this->config->item('create_facility');
		$this->form_validation->set_rules($facility_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
			
			$types = $this->input->post('type_id');
		
			$facility = array(
					'id'			=> $data['facility']['id'],
					'name'			=> trim($this->input->post('name')),
					'type_id'    	=> calculate_list_value_to_bit($types),
					'group_id'    	=> $this->input->post('group_id'),
					'status'  		=> $this->input->post('status'),
					'is_important'  => $this->input->post('is_important'),
			);
		
			$save_status = $this->Facility_Model->update_facility($facility);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("facilities/");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['status_config'] = $this->config->item('status_config');
		
		$data['facility_types'] = $this->config->item('facility_types');
		
		$data['facility_groups'] = $this->config->item('facility_groups');
		
		// render view
		$data['site_title'] = lang('edit_facility_title');
		
		_render_view('facilities/edit_facility', $data);
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$status = $this->Facility_Model->delete_facility($id);
	
		if($status){
				
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
				
		} else {
				
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect('facilities');
	}
	
	function facility_name_check($str)
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$is_exist = $this->Facility_Model->is_unique_field_value($str, $id, 'name');
	
		if ($is_exist)
		{
			$this->form_validation->set_message('facility_name_check', lang('facility_name_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function re_order() {
		if(isset($_GET["id"]) && isset($_GET["act"])) {
			$id 	= $_GET["id"];
			$action = $_GET["act"];
	
			if(is_numeric($id)) {
	
				$status = bp_re_order($id, $action, MODULE_FACILITY);
					
				if ($status)
				{
					$this->session->set_flashdata('message', lang('update_successful'));
					redirect("facilities");
				}
			}
				
			if(!is_null($status)){
				$data['save_status'] = $status;
			}
		}
	}
}
