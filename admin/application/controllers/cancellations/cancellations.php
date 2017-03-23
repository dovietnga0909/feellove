<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cancellations extends BP_Controller {

	public function __construct()
    {
    
       	parent::__construct();	
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Cancellation_Model');
		$this->load->language('cancellation');
		
		$this->load->library('form_validation');
		$this->load->library('pagination');	
		$this->load->config('cancellation_meta');
	}
	
	
	public function index()
	{
		$data = $this->_set_common_data();
		
		$data = $this->_get_list_cancellations($data);
		
		$data = $this->_set_paging_info($data);
		
		$data['search_frm'] = $this->load->view('cancellations/search_cancellation', $data, TRUE);
		
		$data['content'] = $this->load->view('cancellations/list_cancellation', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	
	public function _set_common_data($data = array()){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_CANCELLATION);
		
		$data['site_title'] = lang('title_cancellations');
		
		$data['service_type'] = $this->config->item('service_type');
		
		$data = get_library('tinymce', $data);
		
		return $data;
	}
	
	public function _get_list_cancellations($data = array()){
		
		$search_criteria = $this->_build_search_criteria();		
		
		$data['search_criteria'] = $search_criteria;
		
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
		
		$per_page = $this->config->item('per_page');

		// for display correct order on the column # of table list
		$data['offset'] = $offset;
		
		$data['cancellations'] = $this->Cancellation_Model->search_cancellation($search_criteria, $per_page, $offset);
		
		$data['cancellations'] = $this->_set_ad_pages($data['cancellations']);
		
		$data['fits'] = $this->Cancellation_Model->get_can_search_filter_data('fit');
		
		$data['fit_cutoffs'] = $this->Cancellation_Model->get_can_search_filter_data('fit_cutoff');
		
		$data['git_cutoffs'] = $this->Cancellation_Model->get_can_search_filter_data('git_cutoff');
		
		return $data;
	}
	
	public function _set_ad_pages($advertises){
		
		$ad_pages = $this->config->item('service_type');
		
		foreach($advertises as $key=>$value){
			
			$display_on_val = array();
			
			$display_on = $value['service_type'];
			
			foreach ($ad_pages as $k=>$v){
				
				if(is_bit_value_contain($display_on, $k)){
					
					$display_on_val[] = $v;		
					
				}
				
			}
			
			$txt = implode(", ", $display_on_val);
			
			$value['display_on_txt'] = $txt;
			
			$advertises[$key] = $value;
		}
		
		return $advertises;
	}
	
	public function _build_search_criteria(){
		
		$submit_action = $this->input->post('submit_action');
		
		// access the cancellation tab without search action
		if(empty($submit_action)){
			
			$search_criteria = $this->session->userdata(CANCELLATION_SEARCH_CRITERIA);

			if(empty($search_criteria)){
				
				$search_criteria = array();
				
			}
			
		} else {
			
			if($submit_action == ACTION_RESET){
				
				$search_criteria = array();
				
			} elseif($submit_action == ACTION_SEARCH){
				
				$search_criteria['name'] = $this->input->post('name');
				
				$search_criteria['fit'] = $this->input->post('fit');
				
				$search_criteria['fit_cutoff'] = $this->input->post('fit_cutoff');
				
				$search_criteria['git_cutoff'] = $this->input->post('git_cutoff');
				
				$search_criteria['service_type'] = $this->input->post('service_type');
				
			}
			
			$this->session->set_userdata(CANCELLATION_SEARCH_CRITERIA, $search_criteria);
			
		}
		
		
		return $search_criteria;
	}
	
	public function create(){
		
		$data = $this->_set_common_data();
		$data['site_title'] = lang('create_cancellation');
		
		$action = $this->input->post('action');
		
		if($action == 'save'){
			
			$save_status = $this->_save();

			if($save_status){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('create_successful'));
				
				redirect(site_url('cancellations').'/');
				
			} else {
				
				$data['save_status'] = $save_status;
				
			}
		}		
		
		$data['fit_nr'] = $this->config->item('can_fit_nr');		
		$data['fit_cutoff'] = $this->config->item('can_fit_cutoff');		
		$data['git_cutoff'] = $this->config->item('can_git_cutoff');
		
		$data['content'] = $this->load->view('cancellations/create_cancellation', $data, TRUE);		
		$this->load->view('_templates/template', $data);
	}
	
	public function edit(){
		
		$data = $this->_set_common_data();
		$data['site_title'] = lang('edit_cancellation');
		
		$action = $this->input->post('action');
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$cancellation = $this->Cancellation_Model->get_cancellation($id);
		
		if($cancellation !== FALSE){
			
			$data['can'] = $cancellation;
			
		}
		
		if($action == 'save'){
			
			$save_status = $this->_save(true);

			if($save_status === TRUE){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect(site_url('cancellations').'/');
				
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}	
			}
		}
		
		$data['fit_nr'] = $this->config->item('can_fit_nr');		
		$data['fit_cutoff'] = $this->config->item('can_fit_cutoff');		
		$data['git_cutoff'] = $this->config->item('can_git_cutoff');
		
		
		$data['content'] = $this->load->view('cancellations/edit_cancellation', $data, TRUE);		
		$this->load->view('_templates/template', $data);
		
	}
	
	public function delete(){
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$status = $this->Cancellation_Model->delete_cancellation($id);
		
		if($status){
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
			
		} else {
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
		
		redirect(site_url('cancellations').'/');
	}
	
	public function _save($is_edit = false){
		
		if($this->_validate()){
			
			$can = $this->_get_post_data();
			
			if($is_edit){
				
				$id = $this->uri->segment(NORMAL_ID_SEGMENT);
				
				$save_status = $this->Cancellation_Model->update_cancellation($id, $can);
				
			} else {
				
				$save_status = $this->Cancellation_Model->create_cancellation($can);
					
				
			}
			
			return $save_status;
			
			
		}
		
		return NULL;
		
	}
	
	
	public function _get_post_data(){
		
		$can['name'] = $this->input->post('name');
		
		$can['fit'] = $this->input->post('fit');
		
		$can['fit_cutoff'] = $this->input->post('fit_cutoff');
		
		$can['git_cutoff'] = $this->input->post('git_cutoff');
		
		$can['content'] = $this->input->post('content');
		
		$service_type = $this->input->post('service_type');
				
		$can['service_type'] = calculate_list_value_to_bit($service_type);
		
		return $can;
	}
	
	public function _set_validation_rules()
	{
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_rules($this->config->item('cancellation'));
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
		
		$total_rows = $this->Cancellation_Model->count_total_cancellation($search_criteria);
		
		$offset = $this->uri->segment(PAGING_SEGMENT);
		
		$paging_config = get_paging_config($total_rows,'cancellations/',PAGING_SEGMENT);		
		// initialize pagination
		$this->pagination->initialize($paging_config);
		
		$paging_info['paging_text'] = get_paging_text($total_rows, $offset);
		
		$paging_info['paging_links'] = $this->pagination->create_links();
		
		$data['paging_info'] = $paging_info;
		
		return $data;
		
	}
	
	public function can_name_check($str)
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$is_exist = $this->Cancellation_Model->is_can_name_exist($str, $id);
		
		if ($is_exist)
		{
			$this->form_validation->set_message('can_name_check', lang('cancellation_name_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/cancellation.php */