<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends BP_Controller {
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('Account_model');
		
		$this->load->language('user');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		$this->load->library('email');
		
		$this->load->helper('search');
		
		$this->config->load('user_meta');
		$this->config->load('customer_meta');
		
	}
	
	function index(){
		
		// set session for menues
		//$this->session->set_userdata('MENU', MNU_ACCOUNT);
		
		$this->session->set_userdata('MENU', MNU_CUSTOMER);
		
		$data['site_title'] = lang('list_account_title');
		
		$data = _get_navigation($data, 1, MNU_ACCOUNT, 'nav_panel_customer');
		
		$data = $this->_get_list_data($data);
		
		// $data['nav_panel'] = $this->config->item('accounts_type_nav');
		$data['side_mnu_index'] = 0;

		_render_view('accounts/list_accounts', $data, 'accounts/search_account');
		
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MNU_ACCOUNT, $data);
		
		$search_criteria = $data['search_criteria'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
		
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['accounts'] = $this->Account_model->search_accounts($search_criteria, $per_page, $offset);
		
		$total_rows = $this->Account_model->get_numb_accounts($search_criteria);
		
		$data = set_paging_info($data, $total_rows, URL_ACCOUNT);
	
		return $data;
	}
	
	
	public function create(){
		
		$account_config = $this->config->item('account_create');
		
		$this->form_validation->set_rules($account_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		if ($this->form_validation->run() == true) {
			
			$account = array(
					'username'		=> strtolower(trim($this->input->post('username'))),
					'email'     	=> strtolower(trim($this->input->post('email'))),
					'phone'     	=> strtolower(trim($this->input->post('phone'))),
			);
			
			$email = $account['email'];
			$code = EMAIL_VALIDATE_CODE;
			$hash = $email.$code;
			$md5_hash = md5($hash);
			$password = rand(999,9999);
			$password_hash = md5($password);
			
			$account['password']	= $password_hash;
			$account['authorize']	= $md5_hash;
			
			$check_email	=	$this->Account_model->check_email($email);
			
			$subject	= lang_arg('subject_account_create', $email);
			
			$site = site_url();
			$site = str_replace('/admin', '', $site);
			$link = $site."login/active_account/".$md5_hash."/";
			$data_email = array(
				'email'		=>$email,
				'link'		=>$link,
				'password'	=>$password,
				'site'		=>$site
			);
			$content = $this->load->view("accounts/template_mail",$data_email, TRUE);
			
			$save_status = false;
			if($check_email == 0 ){
				
				$this->sendMail($subject, $content, $email, 'sales@snotevn.com:8888');
				$save_status = $this->Account_model->create_account($account);
			}
			
			if ($save_status){
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("accounts");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}

		// render view
		$data['site_title'] = lang('create_account_title');
		
		$data = _get_navigation($data, 1, MNU_ACCOUNT, 'nav_panel_customer');

		_render_view('accounts/create_account', $data);
	}
	
	public function edit(){
		
		$id = (int)$this->uri->segment(3);
		$account = $this->Account_model->get_account($id);
		
		//validate form input
		$account_config = $this->config->item('account_create');
		
		$this->form_validation->set_rules($account_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
				
			$update_account = array(
				'id'			=> $account['id'],
				'email'     	=> trim($this->input->post('email')),
				'username'     	=> trim($this->input->post('username')),
				'phone'     	=> trim($this->input->post('phone')),
				'active'  		=> trim($this->input->post('active')),
			);
			
			$save_status = $this->Account_model->update_account($update_account);
			
			if ($save_status){
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("accounts");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['status_config'] = $this->config->item('status_config');
		
		$data['account'] = $account;
		
		$data['side_mnu_index'] = 0;
		
		// render view
		$data['site_title'] = lang('edit_account_title');
		
		$data = _get_navigation($data, 1, MNU_ACCOUNT, 'nav_panel_customer');
		
		_render_view('accounts/edit_account', $data);
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$status = $this->Account_model->delete_account($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect('accounts');
	}
	
	function reset(){
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$account_data = $this->Account_model->get_account($id);
		
		$email 		=  $account_data['email'];
		
		$check_account	=	$this->Account_model->check_account($email);
		
		if($check_account){
			// echo "here";die;
			$this->session->set_flashdata(ACTION_MESSAGE, lang('register_letter'));
			
		}else{
			
			$password = rand(999,9999);
			$password_hash = md5($password);
			
			$account = array(
				'id'			=> $id,
				'password'		=> $password_hash,
			);
			
			$subject	= lang('subject_reset_password');
			
			
			$site = site_url();
			$site = str_replace('/admin', '', $site);
			
			$data_email = array(
				'site'		=>$site,
				'email'		=>$email,
				'password'	=>$password,
			);
			
			$content = $this->load->view("accounts/reset_password",$data_email, TRUE); 
			
			$this->sendMail($subject, $content, $email, 'sales@snotevn.com:8888');
			$save_status = $this->Account_model->update_account($account);
			
			if ($save_status){
				$this->session->set_flashdata(ACTION_MESSAGE, lang('reset_successful'));
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		redirect("accounts");
	}
	
	function sendMail($subject, $content, $mail_to, $mail_bcc = '', $attachment = ''){
		$CI =& get_instance();
		$CI->load->library('email');
		$config = array();
		
		$config['protocol']='smtp';
		$config['smtp_host']='ssl://smtp.googlemail.com';
		$config['smtp_port']='465';
		$config['smtp_timeout']='30';
		$config['smtp_user']='bestpricebooking@gmail.com';
		$config['smtp_pass']='Bpt052010';
		
		$config['charset']='utf-8';
		$config['newline']="\r\n";
		$config['mailtype'] = 'html';
		
		$CI->email->initialize($config);
		$CI->email->from('bestpricebooking@gmail.com');
		/*
		if($attachment != ''){
			
			//$CI->email->attach($attachment,'inline');
		}
		*/
		$CI->email->to($mail_to);
		
		if($mail_bcc != ''){
			// $CI->email->bcc($mail_bcc);
		}
		
		$CI->email->subject($subject);
		
		$CI->email->message($content);
		
		if (!$CI->email->send()){
			log_message('error', 'Login - sendMail(): cannot send email!');
		}
		
	}
	
}