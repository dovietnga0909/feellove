<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer_Model extends CI_Model {	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
			
		$this->load->database();
		
	}
	
}
