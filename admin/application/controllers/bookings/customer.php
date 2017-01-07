<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();			
		$this->load->model('CustomerModel');
		$this->load->helper('url');
		$this->load->language('customer');
		$this->load->library('form_validation');
		$this->load->library('pagination');	
		$this->load->helper('form');
		$this->load->helper('common');
		$this->auth->checkLogin();
		$this->auth->checkPermission(false);
	}
		
		
	function _setValidationRules()
	{
		$this->form_validation->set_error_delimiters('<br><label class="error">', '</label></br>');
		
		$this->form_validation->set_rules($this->config->item('customer'));
	}
	
	
	function index()
	{		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_MY_CUSTOMERS);
		
		$action = $this->input->post('action_type');
		
		if ($action == 'save_create') {
			
			$id = $this->_create();
			
			if ($id != ''){
				$action = 'search';
			}
			
		} else if ($action == 'save_edit'){
			
			$id = $this->_edit();
			
			if ($id != ''){
				$action = 'search';
			}
	
		} else if ($action == 'reset'){
						
			$this->_reset();
			
			$action = 'search';
			
		} else if ($action == 'delete'){
			
			$this->_delete();
			
			$action = 'search';
		}
		$data = '';
		
		if ($action == 'search' || $action == '')
		{	
			$data = $this->_list($action);
		}
		
		$this->_setDataForm($action, $data);
	}
	
	function _setDataForm($action, $data='')
	{		
		$id = $this->input->post('c_id');
		
		if ($id != ''){
			
			$customer = $this->CustomerModel->getCustomer($id);
			
			if ($customer != ''){
			
				$data['customer'] = $customer;
			
			}
		}
		
		$data['countries'] = $this->config->item('countries');
		
		if($action == 'view') {
			
			$data['site_title'] = $this->lang->line('view_customer');
			
			$data['sub_header'] = $this->lang->line('view_customer');
			
			$data['main'] = $this->load->view('customer/view_customer', $data, TRUE);
			
		} elseif ($action == 'edit' || $action == 'save_edit'){
			
			$data['site_title'] = $this->lang->line('edit_customer');
			
			$data['sub_header'] = $this->lang->line('edit_customer');
			
			$data['main'] = $this->load->view('customer/edit_customer', $data, TRUE);
			
		} elseif ($action == 'create' || $action == 'save_create') {
		
			$data['site_title'] = $this->lang->line('create_customer');
			
			$data['sub_header'] = $this->lang->line('create_customer');
			
			$data['main'] = $this->load->view('customer/create_customer', $data, TRUE);
		
		} else {
			
			$data['site_title'] = $this->lang->line('list_customer');
			
			$data['sub_header'] = $this->lang->line('list_customer');
			
			$data['search'] = $this->load->view('customer/search_customer', $data, TRUE);
			
			$data['main'] = $this->load->view('customer/list_customer', $data, TRUE);
			
		}

		$data['header'] = $this->load->view('template/header', $data, TRUE);
		$data['navigation'] = $this->load->view('template/navigation', $data, TRUE);
		
		$data['include_css'] = get_static_resources('jquery-ui.css');
		$data['include_js'] = get_static_resources('jquery-ui.js');
		
		$this->load->view('template/template' ,$data);
	}
	
	function _validate()
	{
		$this->_setValidationRules();
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;
	}

	function _list($action=''){
		
		$search_criteria = $this->_buildSearchCriteria($action);
		
		$data['search_criteria'] = $search_criteria;
			
		$data['total_rows'] = $this->CustomerModel->getNumCustomer($search_criteria);			
		
		$offset = $this->uri->segment(4);
		
		$data['customers'] = $this->CustomerModel->searchCustomer(
						$search_criteria
						, $this->config->item('per_page')
						, (int)$offset);
		
		// initialize pagination
		$this->pagination->initialize(
						get_paging_config($data['total_rows']
							, 'customer/customer/index/'
							, 4));
							
		$data['paging_text'] = get_paging_text($data['total_rows'], $offset);
	

		return $data;
	}
	
	function _create()
	{
		if ($this->_validate()) {
			
			$id = $this->CustomerModel->createCustomer($this->app_context);
			
			return $id;
		} else {
			return '';
		}
	}
	
	
	function _edit()
	{
		
		$status = false;
		
		$id = $this->input->post('c_id');
		
		if ($this->_validate()) {
			
			$status = $this->CustomerModel->updateCustomer($id, $this->app_context);
		
		} 
	
		return $status? $id : '';
	}

	
	function _buildSearchCriteria($action) {
		
		$search_criteria = array();	
		
		if ($this->session->userdata("customer_search_criteria")){
			$search_criteria = $this->session->userdata("customer_search_criteria");
		}
		
		if ($action == 'search') { // build search criteria from _POST
			 
			$name = trim($this->input->post('name'));
			
			if ($name != '') {
				$search_criteria['name'] = $name;
			} else {
				unset($search_criteria['name']);
			}
		}
		
		$this->session->set_userdata("customer_search_criteria", $search_criteria);
		return $search_criteria;
	}
	
	function _reset() {
		
		$this->session->unset_userdata('customer_search_criteria');
		
	}
	
	function _delete()
	{
		$id = $this->input->post('c_id');
		$customer = $this->CustomerModel->getCustomer($id);
		if(!empty($customer) && is_allow_deletion($customer['user_created_id'], DATA_CUSTOMER)) {
			if($this->CustomerModel->customer_is_in_use($id)) {
				message_alert(2);
			} else {
				$this->CustomerModel->deleteCustomer($id, $this->app_context);
			}
		} else {
			message_alert(1);	
		}
	}
}

?>
