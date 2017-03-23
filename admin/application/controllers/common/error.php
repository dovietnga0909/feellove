<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->data['error'] = $this->session->flashdata('error');
	
		$this->load->view('_templates/error', $this->data);
	}
}
