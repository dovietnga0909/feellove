<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surcharges extends BP_Controller {

	public function __construct()
    {
    
       	parent::__construct();	
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('cruise');
		$this->load->helper('rate');
		$this->load->model('Surcharge_Model');
		$this->load->model('Tour_Model');
		$this->load->language('surcharge');
		$this->load->language('cruise');
		
		$this->load->library('form_validation');
		$this->load->library('pagination');	
		$this->load->config('surcharge_meta');
		$this->load->config('cruise_meta');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	
	public function index()
	{
		$data = $this->_set_common_data();
		
		$data = $this->_get_list_surcharges($data);
		
		$data = $this->_set_paging_info($data);
		
		$data['search_frm'] = $this->load->view('cruises/surcharges/search_surcharge', $data, TRUE);
		
		$data['content'] = $this->load->view('cruises/surcharges/list_surcharge', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	
	public function _set_common_data($data = array()){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_CRUISE_SURCHARGE);
		
		$data['site_title'] = lang('title_surcharges');
		
		$data = get_library('datepicker', $data);
		
		$cruise_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data['cruise_id'] = $cruise_id;
		
		$data = _get_cruise_data($data, $cruise_id);
		
		$data['cruise_tours'] = $this->Tour_Model->get_tours_of_cruise($cruise_id);
		
		$data['charge_types'] = $this->config->item('cruise_charge_types');
		
		$data['apply_on_tours'] = $this->config->item('cruise_tour_surcharges');
		
		$data['week_days'] = $this->config->item('week_days');
		
		$data = get_library('tinymce', $data);
		
		$data = get_library('mask', $data);
		
		return $data;
	}
	
	public function _get_list_surcharges($data = array()){
		
		$search_criteria = $this->_build_search_criteria();		
		
		$data['search_criteria'] = $search_criteria;
		
		$offset = (int)$this->uri->segment(PAGING_SEGMENT + 2);
		
		$per_page = $this->config->item('per_page');

		// for display correct order on the column # of table list
		$data['offset'] = $offset;
		
		$data['surcharges'] = $this->Surcharge_Model->search_surcharge($search_criteria, $per_page, $offset);
	
		
		return $data;
	}
	
	public function _build_search_criteria(){
		
		$submit_action = $this->input->post('submit_action');
		
		$cruise_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		// access the Surcharge tab without search action
		if(empty($submit_action)){			
			
			$search_criteria = $this->session->userdata(SURCHARGE_SEARCH_CRITERIA.$cruise_id);

			if(empty($search_criteria)){
				
				$search_criteria = array();
				
			}
			
			$search_criteria['cruise_id'] = $cruise_id;
			
		} else {
			
			if($submit_action == ACTION_RESET){
				
				$search_criteria = array();
				
			} elseif($submit_action == ACTION_SEARCH){
				
				$search_criteria['name'] = $this->input->post('name');
				
				$search_criteria['start_date'] = $this->input->post('start_date');
				
				$search_criteria['end_date'] = $this->input->post('end_date');
				
				$search_criteria['charge_type'] = $this->input->post('charge_type');
				
			}
			
			$search_criteria['cruise_id'] = $cruise_id;
			
			$this->session->set_userdata(SURCHARGE_SEARCH_CRITERIA.$cruise_id, $search_criteria);
			
		}
		
		
		return $search_criteria;
	}
	
	public function create(){
		
		$data = $this->_set_common_data();
		$data['site_title'] = lang('create_surcharge');
		
		$action = $this->input->post('action');
		
		$cruise_id = isset($data['cruise_id']) ? $data['cruise_id'] : 0;
		
		if($action == 'save'){
			
			$save_status = $this->_save($data);

			if($save_status){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('create_successful'));
				
				redirect(site_url('cruises/surcharges/'.$cruise_id).'/');
				
			} else {
				
				$data['save_status'] = $save_status;
				
			}
		}		
		
		$data = get_library('tinymce', $data);
		
		$data['content'] = $this->load->view('cruises/surcharges/create_surcharge', $data, TRUE);		
		$this->load->view('_templates/template', $data);
	}
	
	public function edit(){
		
		$data = $this->_set_common_data();
		$data['site_title'] = lang('edit_surcharge');
		
		$action = $this->input->post('action');
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
		
		$cruise_id = isset($data['cruise_id']) ? $data['cruise_id'] : 0;
		
		$surcharge = $this->Surcharge_Model->get_surcharge($id);
		
		$surcharge_tours = $this->Surcharge_Model->get_tour_surcharge($id);
		
		$surcharge['surcharge_tours'] = $surcharge_tours;
		
		if($surcharge !== FALSE){
			
			$data['sur'] = $surcharge;
			
		}
		
		if($action == 'save'){
			
			$save_status = $this->_save($data, true);

			if($save_status === TRUE){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect(site_url('cruises/surcharges/'.$cruise_id).'/');
				
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}	
			}
		}
	
		$data = get_library('tinymce', $data);
		
		$data['content'] = $this->load->view('cruises/surcharges/edit_surcharge', $data, TRUE);		
		$this->load->view('_templates/template', $data);
		
	}
	
	public function delete(){
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
		
		$cruise_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$status = $this->Surcharge_Model->delete_surcharge($id);
		
		if($status){
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
			
		} else {
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
		
		redirect(site_url('cruises/surcharges/'.$cruise_id).'/');
	}
	
	public function _save($data, $is_edit = false){
		
		if($this->_validate()){
			
			$sur = $this->_get_post_data($data);
			
			if($is_edit){
				
				$sur['id'] = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
				
				$save_status = $this->Surcharge_Model->update_cruise_surcharge($sur);
				
			} else {
				
				$cruise_id = $this->uri->segment(NORMAL_ID_SEGMENT);
				
				$sur['cruise_id'] = $cruise_id;
				
				$save_status = $this->Surcharge_Model->create_cruise_surcharge($sur);
					
				
			}
			
			return $save_status;
			
		}
		
		return NULL;
		
	}
	
	
	public function _get_post_data($data){
		
		$sur['name'] = $this->input->post('name');
		
		$sur['start_date'] = $this->input->post('start_date');
		
		$sur['start_date'] = date(DB_DATE_FORMAT, strtotime($sur['start_date']));
		
		$sur['end_date'] = $this->input->post('end_date');
		
		$sur['end_date'] = date(DB_DATE_FORMAT, strtotime($sur['end_date']));
		
		$week_day = $this->input->post('week_day');
		
		$week_day_nr = calculate_list_value_to_bit($week_day);
		
		$sur['week_day'] = $week_day_nr;
		
		$sur['description'] = $this->input->post('description');		
		
		$sur['charge_type'] = $this->input->post('charge_type');
		
		$sur['apply_all'] = $this->input->post('apply_all');
		
		if($sur['charge_type'] == 2) { // %room price
			$sur['adult_amount'] = format_rate_input($this->input->post('adult_amount'), 1);
			$sur['children_amount'] = format_rate_input($this->input->post('children_amount'), 1);
		} else {
			$sur['adult_amount'] = format_rate_input($this->input->post('adult_amount'), DECIMAL_HUNDRED);
			$sur['children_amount'] = format_rate_input($this->input->post('children_amount'), DECIMAL_HUNDRED);
		}
		
		$surcharge_tours = $this->input->post('surcharge_tours');
		
		$apply_on_tours = array();
		
		if($sur['apply_all'] == 1) {
			foreach ($data['cruise_tours'] as $tour) {
				$apply_on_tours[] = array(
					'id' => $tour['id'],
					'adult_amount' 		=> $sur['adult_amount'],
					'children_amount' 	=> $sur['children_amount']
				);
			}
		} elseif(!empty($surcharge_tours)) {
			foreach ($surcharge_tours as $tour) {
					
				$adult_amount = $this->input->post('get_adult_'.$tour);
				$children_amount = $this->input->post('get_children_'.$tour);
					
				if($sur['charge_type'] == 2) { // %room price
					$adult_amount = format_rate_input($adult_amount, 1);
					$children_amount = format_rate_input($children_amount, 1);
				} else {
					$adult_amount = format_rate_input($adult_amount, DECIMAL_HUNDRED);
					$children_amount = format_rate_input($children_amount, DECIMAL_HUNDRED);
				}
			
				$apply_on_tours[] = array(
						'id' => $tour,
						'adult_amount' => $adult_amount,
						'children_amount' => $children_amount
				);
			}	
		}
		
		$sur['apply_on_tours'] = $apply_on_tours;
		
		return $sur;
	}
	
	public function _set_validation_rules()
	{
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_rules($this->config->item('cruise_surcharge'));
	}
	
	public function _validate()
	{
		$this->_set_validation_rules();
		
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;

	}
	
	public function _set_paging_info($data = array()){
		
		$search_criteria = isset($data['search_criteria'])? $data['search_criteria'] : array();
		
		$cruise_id = isset($data['hotel_id']) ? $data['hotel_id'] : 0;
		
		$total_rows = $this->Surcharge_Model->count_total_surcharge($search_criteria);
		
		$offset = $this->uri->segment(PAGING_SEGMENT + 2);
		
		$paging_config = get_paging_config($total_rows,'/cruises/surcharges/'.$cruise_id.'/',PAGING_SEGMENT + 2);		
		// initialize pagination
		$this->pagination->initialize($paging_config);
		
		$paging_info['paging_text'] = get_paging_text($total_rows, $offset);
		
		$paging_info['paging_links'] = $this->pagination->create_links();
		
		$data['paging_info'] = $paging_info;
		
		return $data;
		
	}
	
	public function sur_name_check($str)
	{
		return true; // for quick changed 
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
		
		$is_exist = $this->Surcharge_Model->is_sur_name_exist($str, $id);
		
		if ($is_exist)
		{
			$this->form_validation->set_message('sur_name_check', lang('surcharge_name_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function sur_date_check($str)
	{
		
		$ret = FALSE;
		
		if (substr_count($str, '-') == 2) {
	        list($d, $m, $y) = explode('-', $str);
	        $ret =  checkdate($m, $d, sprintf('%04u', $y));
	    }
	    
	    // check format
	    if ($ret === FALSE){
	    	$this->form_validation->set_message('sur_date_check', lang('sur_date_valid_format'));
	    } else {
	    	// check endate > start date
	    	
	    	$start_date = $this->input->post('start_date');
	    	
	    	$end_date = $this->input->post('end_date');
	    	
	    	$start_date = strtotime($start_date);
	    	
	    	$end_date = strtotime($end_date);
	    	
	    	if($start_date > $end_date){
	    		
	    		$ret = FALSE;
	    		
	    		$this->form_validation->set_message('sur_date_check', lang('sur_date_valid_valud'));
	    	}
	    }
	
	    return $ret;
	}
}
