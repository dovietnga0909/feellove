<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Customer_Model');
		$this->load->model('Destination_Model');
		
		$this->load->language('customers');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->load->helper('search');
		
		$this->config->load('customer_meta');
	}

	public function index()
	{
		$this->session->set_userdata('MENU', MNU_CUSTOMER);
		
		$data['site_title'] = lang('list_customer_title');
		
		$data = _get_navigation($data, 0, MNU_CUSTOMER, 'nav_panel_customer');

		$data = $this->_get_list_data($data);

		_render_view('customers/list_customers', $data, 'customers/search_customer');
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_CUSTOMER, $data);
	
		$search_criteria = $data['search_criteria'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['customers'] = $this->Customer_Model->search_customers($search_criteria, $per_page, $offset);
		
		$total_rows = $this->Customer_Model->get_numb_customers($search_criteria);
		
		$data = set_paging_info($data, $total_rows, 'customers');
		
		$data['customer_destinations'] = $this->Customer_Model->get_customer_destinations();
		
		$data['customer_budget'] = $this->config->item('customer_budget');
		
		$data['travel_types'] = $this->config->item('travel_types');
	
		return $data;
	}
	
	// create a new customer
	public function create(){
		$customer_config = $this->config->item('create_customer');
		$this->form_validation->set_rules($customer_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
				
			$customer = array(
					'full_name'		=> trim($this->input->post('full_name')),
					'gender'		=> trim($this->input->post('gender')),
					'phone'			=> trim($this->input->post('phone')),
					'email'			=> trim($this->input->post('email')),
					'address'    	=> trim($this->input->post('address')),
					'destination_id'=> $this->input->post('destination_id'),
					'birthday'    	=> $this->input->post('birthday'),
					'happy_or_not'  => $this->input->post('happy_or_not'),
					'budget'  		=> $this->input->post('customer_budget'),
					'travel_type'   => $this->input->post('travel_types'),
			);
	
			$save_status = $this->Customer_Model->create_customer($customer);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("customers");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['genders'] = $this->config->item('gender');
		
		$data['customer_budget'] = $this->config->item('customer_budget');
		
		$data['travel_types'] = $this->config->item('travel_types');
		
		$data['destinations'] = $this->Destination_Model->get_customer_cities();
	
		// render view
		$data = get_library('datepicker', $data);
		
		$data['site_title'] = lang('create_customer_title');
		
		$data = _get_navigation($data, 0, MNU_CUSTOMER, 'nav_panel_customer');
	
		_render_view('customers/create_customer', $data);
	}
	
	// edit the customer
	public function edit(){
		
		$data = $this->_get_customer_data();
		
		$customer_config = $this->config->item('edit_customer');
		$this->form_validation->set_rules($customer_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$customer = array(
					'id'			=> $data['customer']['id'],
					'full_name'		=> trim($this->input->post('full_name')),
					'gender'		=> trim($this->input->post('gender')),
					'phone'			=> trim($this->input->post('phone')),
					'email'			=> trim($this->input->post('email')),
					'address'    	=> trim($this->input->post('address')),
					'destination_id'=> $this->input->post('destination_id'),
					'birthday'    	=> $this->input->post('birthday'),
					'happy_or_not'  => $this->input->post('happy_or_not'),
					'budget'  		=> $this->input->post('customer_budget'),
					'travel_type'   => $this->input->post('travel_types'),
			);
		
			$save_status = $this->Customer_Model->update_customer($customer);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("customers");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['genders'] = $this->config->item('gender');
		
		$data['customer_budget'] = $this->config->item('customer_budget');
		
		$data['travel_types'] = $this->config->item('travel_types');
		
		$data['destinations'] = $this->Destination_Model->get_customer_cities();
		
		// render view
		$data = get_library('datepicker', $data);
		
		$data['site_title'] = lang('edit_customer_title');
		
		$data = _get_navigation($data, 0, MNU_CUSTOMER, 'nav_panel_customer');
		
		_render_view('customers/edit_customer', $data);
	}
	
	function _get_customer_data($data = array()) {
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
		
		$customer = $this->Customer_Model->get_customer($id);
		
		$data['customer'] = $customer;
		
		return $data;
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$status = $this->Customer_Model->delete_customer($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect('customers');
	}
	
	function customer_email_check($str)
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$is_exist = $this->Customer_Model->is_unique_field_value($str, $id, 'email');
	
		if ($is_exist)
		{
			$this->form_validation->set_message('customer_email_check', lang('customer_email_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}
