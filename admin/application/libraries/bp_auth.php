<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class bp_auth
{
	private $ci;
	
	 /**
      * Constructor
      */
	function __construct()
	{

		// Assign CodeIgniter object to $this->ci
		$this->ci =& get_instance();
		
		// Load models
		$this->ci->load->model('auth_model');

		// Load libraries
		$this->ci->load->library('session');
		
		$this->ci->load->helper('cookie');

		//auto-login the user if they are remembered
		if (!$this->logged_in() && get_cookie('username') && get_cookie('remember_code'))
		{
			$this->ci->auth_model->login_remembered_user();
		}
	}
	
	
	/**
	 * logout
	 *
	 * @return void
	 **/
	public function logout()
	{
		$this->ci->session->unset_userdata('username');
	
		//delete the remember me cookies if they exist
		if (get_cookie('username'))
		{
			delete_cookie('username');
		}
	
		//Destroy the session
		$this->ci->session->sess_destroy();
	
		//Recreate the session
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->ci->session->sess_create();
		}
	
		return TRUE;
	}
	
	/**
	 * logged_in
	 *
	 * @return bool
	 **/
	function logged_in()
	{
		return (bool) $this->ci->session->userdata('username');
	}
}
?>