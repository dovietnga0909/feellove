<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function __construct()
    {
       	parent::__construct();
		$this->load->language('home');
		$this->load->language('common');
		$this->load->helper('login');
		$this->load->library('email');
		$this->load->model('Account_Model');
	}
	
	function news_letter_request(){
		$email	=	$this->input->post('news_letter_to', true);
		$email	=   trim($email);
		
		$subject	= lang('subject_news_letter');
		// $attachement = 'media/banners/home_banner_07052014.jpg';
		$content 	= $this->load->view('login/mail_letter',$email, TRUE);
		
		$request = 0;
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			$check_email	= $this->Account_Model->check_email($email); 

			if($check_email == 0){
				$data = array(
					'email'			=>$email,
					'register'		=>NEWS_LETTER,
					'date_modified'	=> date(DB_DATE_TIME_FORMAT),
					'date_created'	=> date(DB_DATE_TIME_FORMAT),
				);
				$this->Account_Model->add_account($data);
				$request = 1;
				$this->sendMail($subject, $content ,$email, 'sales@snotevn.com:8888');
			}else{
				$request =2;
				$this->sendMail($subject, $content ,$email, 'sales@snotevn.com:8888');
			}
		}else{
			$request = 3;
		}
		echo $request;
	}
	
	function get_sign_up_popup(){
		$html	=	load_sign_up_popup('btn_sign_up');
		echo $html;
	}
	
	function sign_up_request(){
		$code = EMAIL_VALIDATE_CODE;
		$email	=	$this->input->post('email');
		$phone	=	$this->input->post('phone');
		
		$request = 0;
		
		$hash = $email.$code;
		$md5_hash = md5($hash);
		$password = rand(999,9999);
		$password_hash = md5($password);
		// $active	=	0;
		$data = array(
			'email'		=>$email,
			'phone'		=>$phone,
			'password'	=>$password_hash,
			'authorize'	=>$md5_hash,
			// 'active'	=>$active
		);
		
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			$check_email	=	$this->Account_Model->check_email($email);
			$check_account	=	$this->Account_Model->check_account($email);
			
			$subject	= lang_arg('subject_sign_up', $email);
			
			$link =site_url()."login/active_account/".$md5_hash."/";
			$data_email = array(
				'email'		=>$email,
				'link'		=>$link,
				'password'	=>$password
			);
			$content = $this->load->view("login/mail_signup",$data_email, TRUE);
			
			if($check_email == 0){
				$data['register']		= SIGN_IN;
				$data['date_modified']	= date(DB_DATE_TIME_FORMAT);
				$data['date_created']	= date(DB_DATE_TIME_FORMAT);
				$this->sendMail($subject, $content, $email, 'sales@snotevn.com:8888');
				$this->Account_Model->add_account($data);
				$request = 1;
				
			}else{
				if($check_account ==1){
					
					$data['register']		= LETTER_TO_SIGN;
					$data['date_modified']	= date(DB_DATE_TIME_FORMAT);
					$data['date_created']	= date(DB_DATE_TIME_FORMAT);
					
					$this->sendMail($subject, $content, $email, 'sales@snotevn.com:8888');
					$this->Account_Model->update_account($email,$data);
					$request = 2;
				}else{
					$request = 3;
				}
			}
		}else{
			$request = 4;
		}
		echo $request;
	}
	
	function get_sign_in_popup(){
		$html	=	load_sign_in_popup('btn_sign_in');
		echo $html;
	}
	
	function sign_in_request(){
		
		$email		=	$this->input->post('email');
		$password	=	$this->input->post('password');
		
		$request = 0;
		
		$password_hash = md5($password);
		$check_sign_in = $this->Account_Model->check_sign_in($email, $password_hash);
		if($check_sign_in == 1){
			$newdata = array(
                'email'     	=> $email,
                'logged_in' 	=> TRUE
			);
			$this->session->set_userdata(LOGIN_USER, $newdata);
			
			$request = 1;
			
		}else{
			$request = 0;
		}
		echo $request;
	}
	
	function active_account(){
		$hash	=	$this->uri->segment(3);
		$active = 	$this->Account_Model->check_active($hash);
		if($active != NULL){
			
			
			if($active[0]['active'] == NULL){
				$data = array(
					"active"	=>1
				);
				$this->Account_Model->authorize($data, $hash);
				redirect('/');
			}else{
				redirect('/');
			}
		}else{
			echo "Link xac thuc sai";
		}
	}
	
	function sign_out(){
		$this->session->unset_userdata(LOGIN_USER);
		echo 1;
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
			log_message('Login - sendMail(): cannot send email!');
		}
		
	} 
}