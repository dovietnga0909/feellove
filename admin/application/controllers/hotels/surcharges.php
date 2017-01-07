<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surcharges extends BP_Controller {

	public function __construct()
    {
    
       	parent::__construct();	
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('hotel');
		$this->load->helper('rate');
		$this->load->model('Surcharge_Model');
		$this->load->language('surcharge');
		
		$this->load->library('form_validation');
		$this->load->library('pagination');	
		$this->load->config('surcharge_meta');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	
	public function index()
	{
		$data = $this->_set_common_data();
		
		$data = $this->_get_list_surcharges($data);
		
		$data = $this->_set_paging_info($data);
		
		$data['search_frm'] = $this->load->view('hotels/surcharges/search_surcharge', $data, TRUE);
		
		$data['content'] = $this->load->view('hotels/surcharges/list_surcharge', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	
	public function _set_common_data($data = array()){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_HOTEL_SURCHARGE);
		
		$data['site_title'] = lang('title_surcharges');
		
		$data = get_library('datepicker', $data);
		
		$hotel_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data['hotel_id'] = $hotel_id;
		
		$data = _get_hotel_data($data, $hotel_id);
		
		$data['charge_types'] = $this->config->item('charge_types');
		
		$data['week_days'] = $this->config->item('week_days');
		
		$data = get_library('tinymce', $data);
		
		$data = get_library('maskmoney', $data);
		
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
		
		$hotel_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		// access the Surcharge tab without search action
		if(empty($submit_action)){			
			
			$search_criteria = $this->session->userdata(SURCHARGE_SEARCH_CRITERIA.$hotel_id);

			if(empty($search_criteria)){
				
				$search_criteria = array();
				
			}
			
			$search_criteria['hotel_id'] = $hotel_id;
			
		} else {
			
			if($submit_action == ACTION_RESET){
				
				$search_criteria = array();
				
			} elseif($submit_action == ACTION_SEARCH){
				
				$search_criteria['name'] = $this->input->post('name');
				
				$search_criteria['start_date'] = $this->input->post('start_date');
				
				$search_criteria['end_date'] = $this->input->post('end_date');
				
				$search_criteria['charge_type'] = $this->input->post('charge_type');
				
			}
			
			$search_criteria['hotel_id'] = $hotel_id;
			
			$this->session->set_userdata(SURCHARGE_SEARCH_CRITERIA.$hotel_id, $search_criteria);
			
		}
		
		
		return $search_criteria;
	}
	
	public function create(){
		
		$data = $this->_set_common_data();
		$data['site_title'] = lang('create_surcharge');
		
		$action = $this->input->post('action');
		
		$hotel_id = isset($data['hotel_id']) ? $data['hotel_id'] : 0;
		
		if($action == 'save'){
			
			$save_status = $this->_save();

			if($save_status){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('create_successful'));
				
				redirect(site_url('hotels/surcharges/'.$hotel_id).'/');
				
			} else {
				
				$data['save_status'] = $save_status;
				
			}
		}		
		
		$data = get_library('tinymce', $data);
		
		$data['content'] = $this->load->view('hotels/surcharges/create_surcharge', $data, TRUE);		
		$this->load->view('_templates/template', $data);
	}
	
	public function edit(){
		
		$data = $this->_set_common_data();
		$data['site_title'] = lang('edit_surcharge');
		
		$action = $this->input->post('action');
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
		
		$hotel_id = isset($data['hotel_id']) ? $data['hotel_id'] : 0;
		
		$surcharge = $this->Surcharge_Model->get_surcharge($id);
		
		if($surcharge !== FALSE){
			
			$data['sur'] = $surcharge;
			
		}
		
		if($action == 'save'){
			
			$save_status = $this->_save(true);

			if($save_status === TRUE){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect(site_url('hotels/surcharges/'.$hotel_id).'/');
				
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}	
			}
		}
	
		$data = get_library('tinymce', $data);
		
		$data['content'] = $this->load->view('hotels/surcharges/edit_surcharge', $data, TRUE);		
		$this->load->view('_templates/template', $data);
		
	}
	
	public function delete(){
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
		
		$hotel_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$status = $this->Surcharge_Model->delete_surcharge($id);
		
		if($status){
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
			
		} else {
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
		
		redirect(site_url('hotels/surcharges/'.$hotel_id).'/');
	}
	
	public function _save($is_edit = false){
		
		if($this->_validate()){
			
			$sur = $this->_get_post_data();
			
			if($is_edit){
				
				$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
				/*
				$hotel_id = $this->uri->segment(NORMAL_ID_SEGMENT);
				
				$sur['hotel_id'] = $hotel_id;
				*/
				$save_status = $this->Surcharge_Model->update_surcharge($id, $sur);
				
			} else {
				
				$hotel_id = $this->uri->segment(NORMAL_ID_SEGMENT);
				
				$sur['hotel_id'] = $hotel_id;
				
				$save_status = $this->Surcharge_Model->create_surcharge($sur);
					
				
			}
			
			return $save_status;
			
			
		}
		
		return NULL;
		
	}
	
	
	public function _get_post_data(){
		
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
		
		if($sur['charge_type'] == 5){ // %room price
			$sur['amount'] = format_rate_input($this->input->post('amount'), 1);
		} else {
			$sur['amount'] = format_rate_input($this->input->post('amount'), DECIMAL_HUNDRED);
		}
			
		return $sur;
	}
	
	public function _set_validation_rules()
	{
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_rules($this->config->item('surcharge'));
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
		
		$hotel_id = isset($data['hotel_id']) ? $data['hotel_id'] : 0;
		
		$total_rows = $this->Surcharge_Model->count_total_surcharge($search_criteria);
		
		$offset = $this->uri->segment(PAGING_SEGMENT + 2);
		
		$paging_config = get_paging_config($total_rows,'/hotels/surcharges/'.$hotel_id.'/',PAGING_SEGMENT + 2);		
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

/* End of file welcome.php */
/* Location: ./application/controllers/Surcharge.php */