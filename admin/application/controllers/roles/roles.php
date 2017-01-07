<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends BP_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Role_Model');
		
		$this->load->language('role');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');

		$this->load->helper('search');
		
		$this->config->load('role_meta');
	}

	public function index()
	{
		// set session for menues
		$this->session->set_userdata('MENU', MNU_ROLE);

		$data['site_title'] = lang('list_role_title');

		$data = $this->_get_list_data($data);

		_render_view('roles/list_roles', $data, 'roles/search_role');
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_FACILITY, $data);
	
		$search_criteria = $data['search_criteria'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['roles'] = $this->Role_Model->search_roles($search_criteria, $per_page, $offset);
		
		$total_rows = $this->Role_Model->get_numb_roles($search_criteria);
		
		$data = set_paging_info($data, $total_rows, 'roles');
	
		return $data;
	}

	// create a new user
	public function create(){

		$role_config = $this->config->item('create_role');
		$this->form_validation->set_rules($role_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		if ($this->form_validation->run() == true) {
			
			$role = array(
				'name'		=> trim($this->input->post('name')),
			);

			$save_status = $this->Role_Model->create_role($role);

			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("roles");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}

		// render view
		$this->data['site_title'] = lang('create_role_title');

		_render_view('roles/create_role', $this->data);
	}

	// edit the user
	public function edit(){
		
		$id = (int)$this->uri->segment(3);
		
		$role = $this->Role_Model->get_role($id);

		//validate form input
		$role_config = $this->config->item('create_role');
		$this->form_validation->set_rules($role_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
				
			$update_role = array(
				'name'		=> trim($this->input->post('name')),
				'id'		=> $role['id']
			);
		
			$save_status = $this->Role_Model->update_role($update_role);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("roles");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$this->data['role'] = $role;
		
		// render view
		$this->data['site_title'] = lang('edit_role_title');
		
		_render_view('roles/edit_role', $this->data);
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$status = $this->Role_Model->delete_role($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect('roles');
	}
}
