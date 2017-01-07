<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends BP_Controller {

	public $fields = array();
	public $field_maxlengths = array();
	
	var $upload_error = '';

	function __construct()
	{
		parent::__construct();
		$this->load->model('User_Model');
		$this->load->model('Partner_Model');
		$this->load->model('Role_Model');
		
		$this->load->language('user');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('image_lib');

		$this->load->helper('search');
		$this->load->helper('photo');
		
		$this->config->load('user_meta');
	}

	public function index()
	{
		// set session for menues
		$this->session->set_userdata('MENU', MNU_USER);

		$data['site_title'] = lang('list_user_title');

		$data = $this->_get_list_data($data);
		
		$data['nav_panel'] = $this->config->item('hotline_schedule_nav');
		
		// var_dump($data['nav_panel']);die;
		$data['side_mnu_index'] = 0;

		_render_view('users/list_users', $data, 'users/search_user');
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_USER, $data);
		
		$search_criteria = $data['search_criteria'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['users'] = $this->User_Model->search_users($search_criteria, $per_page, $offset);
		
		$total_rows = $this->User_Model->get_numb_users($search_criteria);
		
		$data = set_paging_info($data, $total_rows, URL_USER);
	
		return $data;
	}

	// create a new user
	public function create(){

		$user_config = $this->config->item('create_user');
		$acc_config = $this->config->item('create_account');
		$user_config = array_merge($user_config, $acc_config);
		$this->form_validation->set_rules($user_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		if ($this->form_validation->run() == true) {
			
			$user = array(
					'username'		=> strtolower(trim($this->input->post('username'))),
					'password'    	=> md5(trim($this->input->post('password'))),
					'email'     	=> strtolower(trim($this->input->post('email'))),
			
					'full_name'     => trim($this->input->post('full_name')),
					'partner_id'    => $this->input->post('partner_id'),
					'signature'    	=> trim($this->input->post('signature')),
			);

			$save_status = $this->User_Model->create_user($user);

			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("users");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}

		// render view
		$data['site_title'] = lang('create_user_title');
		
		$data['partners'] = $this->Partner_Model->get_all_partners();

		_render_view('users/create_user', $data);
	}

	// edit the user
	public function edit(){
		
		$id = (int)$this->uri->segment(3);
		
		$user = $this->User_Model->get_user($id);

		//validate form input
		$user_config = $this->config->item('create_user');
		$this->form_validation->set_rules($user_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
				
			$update_user = array(
					'id'			=> $user['id'],
					'email'     	=> strtolower(trim($this->input->post('email'))),
					'full_name'     => trim($this->input->post('full_name')),
					'partner_id'    => $this->input->post('partner_id'),
					'signature'    	=> trim($this->input->post('signature')),
					'status'  		=> trim($this->input->post('status')),
			);
			
			if($user['id'] == 1) {
				unset($update_user['status']);
			}
			
			$new_roles = $this->input->post('roles');
		
			$save_status = $this->User_Model->update_user($update_user, $user['roles'], $new_roles);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("users");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['status_config'] = $this->config->item('status_config');
		
		$data['allow_assign_request_config'] = $this->config->item('allow_assign_request_config');
		
		$data['user'] = $user;
		
		$data['roles'] = $this->Role_Model->get_roles();
		
		$data['nav_panel'] = $this->_load_user_nav_panel($id);
		$data['side_mnu_index'] = 0;
		
		
		// render view
		$data['site_title'] = lang('edit_user_title');
		
		$data['partners'] = $this->Partner_Model->get_all_partners();
		
		_render_view('users/edit_user', $data);
	}
	
	// activate the user
	function activate()
	{
		$this->_set_user_status(STATUS_ACTIVE);
	}
	
	// deactivate the user
	function deactivate()
	{
		$this->_set_user_status(STATUS_INACTIVE);
	}
	
	function _set_user_status($status) {
		$id = (int)$this->uri->segment(3);
		
		$user = $this->User_Model->get_user($id);
		if ($user == false) {
			_show_error_page(lang('user_notfound'));
			exit;
		}
		
		$user = array(
				'id'			=> $user['id'],
				'status'     	=> $status,
		);
		
		$save_status = $this->User_Model->update_user($user);
		
		if ($save_status)
		{
			$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
			redirect("users");
		} else {
			if(!is_null($save_status)){
				$data['save_status'] = $save_status;
			}
		}
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$status = $this->User_Model->delete_user($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect('users');
	}
	
	public function _load_user_nav_panel($id){
		
		$nav_panel = $this->config->item('user_nav_panel');
		
		foreach ($nav_panel as $key=>$value){
			
			$value['link'] .= $id;
			
			$nav_panel[$key] = $value;
			
		}
		
		return $nav_panel;
	}
	
	function hotline($id){
		
		$data['display_on']	= $this->config->item('display_on');
		
		$user = $this->User_Model->get_user($id);
		
		if($user !== FALSE){
				
			$data['user'] = $user;
		}
		
		$data['site_title'] = lang('hotline_user_mnu');
		
		$data['nav_panel'] = $this->_load_user_nav_panel($id);
		
		$data['side_mnu_index'] = 1;
		
		$action = $this->input->post('action');
		
		$data['upload_error'] = $this->upload_error;
		
		if($action == ACTION_SAVE){
			
			//validate form input
			$hotline_setting = $this->config->item('hotline_setting');
			$this->form_validation->set_rules($hotline_setting);
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			if ($this->form_validation->run() == true) {
				
				$display_on = $this->input->post('display_on');
				
				$display_on = calculate_list_value_to_bit($display_on);
			
			
			
				$update_user = array(
						'id'			=> $user['id'],
						'hotline_name'  => $this->input->post('hotline_name'),
						'hotline_number'=> $this->input->post('hotline_number'),
						'yahoo_acc'  	=> $this->input->post('yahoo_acc'),
						'skype_acc'		=> $this->input->post('skype_acc'),
						'display_on'	=> $display_on,
				);
				
				$config = $this->config->item('user_photo');
				$photo_folder = $this->config->item('user_photo_size');
				
				if(isset($_FILES['avatar']) && !empty($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {
					
	        		$file_type = ltrim($_FILES['avatar']['type'], 'image/');
	        		
	        		$name = strtolower(convert_unicode($update_user['id']). ' ' . uniqid());
					
					$fileName = url_title($name);
					
					$config['file_name'] = $fileName.'.'.$file_type;
	            
		            $this->upload->initialize($config);
		        	
					if (!$this->upload->do_upload('avatar')){
						
						$upload_error = $this->upload->display_errors('<br><label class="error">', '</label></br>');
						
						$data['upload_error'] = $upload_error;
						
					}else{
						
						$data = $this->upload->data();
						
						$update_user['avatar'] = $config['file_name'];
					    
						resize_and_crop($data, $photo_folder, 90);
					}
	        	}
	        	
	        	if($data['upload_error'] == ""){
	        		
		        	$save_status = $this->User_Model->update_user($update_user, '', '');
							
					if ($save_status){
							
						$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
						
						redirect("users");
						
					}else {
						
						if(!is_null($save_status)){
							
							$data['save_status'] = $save_status;
						}
					}
        		}
			}
			
		}
		
		$data['content'] = $this->load->view('users/hotline_setting', $data, TRUE);
		$this->load->view('_templates/template', $data);
		
	}
	

	public function schedule()
	{
		// set session for menues
		$this->session->set_userdata('MENU', MNU_USER);
		$data['site_title'] = lang('hotline_schedule_mnu');

		$data = $this->_set_common_schedule_data($data);
		
		$data = $this->_get_list_schedules($data);
		
		$data = $this->_set_schedule_paging_info($data);
		
		$data['search_frm'] = $this->load->view('users/search_schedule', $data, TRUE);
		
		$data['content'] = $this->load->view('users/list_schedules', $data, TRUE);
		
		$this->load->view('_templates/template', $data);

	}
	
	public function create_schedule(){
		
		$data['site_title'] = lang('create_schedule');
		$data = $this->_set_common_schedule_data($data);
		
		
		$action = $this->input->post('action');
		
		if($action == ACTION_SAVE){
			
			//validate form input
			$hotline_schedule = $this->config->item('hotline_schedule');
			$this->form_validation->set_rules($hotline_schedule);
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			if ($this->form_validation->run() == true) {
				
				$week_day = $this->input->post('week_day');
				
				$week_day = calculate_list_value_to_bit($week_day);
			
				$hotline_schedule = array(
						'user_id'  => $this->input->post('user_id'),
						'start_date'=> date(DB_DATE_FORMAT, strtotime($this->input->post('start_date'))),
						'end_date'  => date(DB_DATE_FORMAT, strtotime($this->input->post('end_date'))),
						'week_day'=> $week_day,
						'status' => $this->input->post('status')
				);
			
				$save_status = $this->User_Model->create_schedule($hotline_schedule);
			
				if ($save_status)
				{
					$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
					redirect("users/schedules");
				} else {
					if(!is_null($save_status)){
						$data['save_status'] = $save_status;
					}
				}
			}
		}		
		
		$data['content'] = $this->load->view('users/create_schedule', $data, TRUE);		
		$this->load->view('_templates/template', $data);
	}
	
	public function edit_schedule($id){
		
		$data['site_title'] = lang('edit_schedule');
		$data = $this->_set_common_schedule_data($data);
		
		$data['schedule'] = $this->User_Model->get_hotline_schedule($id);
		
		$action = $this->input->post('action');
		
		if($action == ACTION_SAVE){
			
			//validate form input
			$hotline_schedule = $this->config->item('hotline_schedule');
			$this->form_validation->set_rules($hotline_schedule);
			$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
			
			if ($this->form_validation->run() == true) {
				
				$week_day = $this->input->post('week_day');
				
				$week_day = calculate_list_value_to_bit($week_day);
			
				$hotline_schedule = array(
						'user_id'  => $this->input->post('user_id'),
						'start_date'=> date(DB_DATE_FORMAT, strtotime($this->input->post('start_date'))),
						'end_date'  => date(DB_DATE_FORMAT, strtotime($this->input->post('end_date'))),
						'week_day'=> $week_day,
						'status' => $this->input->post('status')
				);
			
				$save_status = $this->User_Model->edit_schedule($hotline_schedule, $id);
			
				if ($save_status)
				{
					$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
					redirect("users/schedules");
				} else {
					if(!is_null($save_status)){
						$data['save_status'] = $save_status;
					}
				}
			}
		}		
		
		$data['content'] = $this->load->view('users/edit_schedule', $data, TRUE);		
		$this->load->view('_templates/template', $data);
	}
	
	public function delete_schedule($id){
		
		$status = $this->User_Model->delete_hotline_schedule($id);
		
		if($status){
		
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
		
		} else {
		
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
		
		redirect('users/schedules');
		
	}
	
	public function _build_search_schedule_criteria(){
	
		$submit_action = $this->input->post('submit_action');

		// access the Surcharge tab without search action
		if(empty($submit_action)){
				
			$search_criteria = $this->session->userdata(HOTLINE_SCHEDULE_SEARCH_CRITERIA);
	
			if(empty($search_criteria)){
	
				$search_criteria = array();
	
			}
				
		} else {
				
			if($submit_action == ACTION_RESET){
	
				$search_criteria = array();
	
			} elseif($submit_action == ACTION_SEARCH){
	
				$search_criteria['user_id'] = $this->input->post('user_id');
	
				$search_criteria['status'] = $this->input->post('status');
				
				$search_criteria['display_on'] = $this->input->post('display_on');
	
			}
			
			$this->session->set_userdata(HOTLINE_SCHEDULE_SEARCH_CRITERIA, $search_criteria);
				
		}
	
	
		return $search_criteria;
	}
	
	public function _get_list_schedules($data = array()){
	
		$search_criteria = $this->_build_search_schedule_criteria();
	
		$data['search_criteria'] = $search_criteria;
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT + 1);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['schedules'] = $this->User_Model->search_hotline_schedules($search_criteria, $per_page, $offset);
	
	
		return $data;
	}
	
	public function _set_schedule_paging_info($data = array()){
	
		$search_criteria = isset($data['search_criteria'])? $data['search_criteria'] : array();
	
		$total_rows = $this->User_Model->count_total_hotline_schedule($search_criteria);
	
		$offset = $this->uri->segment(PAGING_SEGMENT + 1);
	
		$paging_config = get_paging_config($total_rows,'/users/schedules/',PAGING_SEGMENT + 1);
		// initialize pagination
		$this->pagination->initialize($paging_config);
	
		$paging_info['paging_text'] = get_paging_text($total_rows, $offset);
	
		$paging_info['paging_links'] = $this->pagination->create_links();
	
		$data['paging_info'] = $paging_info;
	
		return $data;
	
	}
	
	function _set_common_schedule_data($data = array()){

		$data['hotline_users'] = $this->User_Model->get_hotline_users();
		
		$data['nav_panel'] = $this->config->item('hotline_schedule_nav');
		$data['side_mnu_index'] = 1;
		
		
		$data['week_days'] = $this->config->item('week_days');
		
		$data = get_library('datepicker', $data);
		
		return $data;
	}
	
	function _get_post_data(){
		
		$user['hotline_name'] 	= $this->input->post('hotline_name');
		
		$user['hotline_number'] = $this->input->post('hotline_number');
		
		$display_on			 	= $this->input->post('display_on');
		
		$user['display_on'] 	= calculate_list_value_to_bit($display_on);
		
		return $user;
	}
	
}
