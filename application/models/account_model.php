<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Account_Model extends CI_Model {	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
			
		$this->load->database();
	}
	function add_account($data){
		$this->db->insert('accounts',$data);
	}
	
	function update_account($email,$data){
		$this->db->where('email',$email);
		$this->db->update('accounts',$data);
	}
	
	function check_account($email){
		$this->db->select('email');
		$this->db->where('email',$email);
		$this->db->where('phone',NULL);
		$query		= $this->db->get('accounts');
		$results	= $query->result_array();
		if(count($results) >0 ){
			return 1;
		}else{
			return 0;
		}
	}
	
	function check_email($email){
		$this->db->select('email');
		$this->db->where('email',$email);
		$query		= $this->db->get('accounts');
		$results	= $query->result_array(); // change to count all results
		if(count($results) >0 ){
			return 1;
		}else{
			return 0;
		}
	}
	
	function check_sign_in($email, $password){
		$this->db->where('email',$email);
		$this->db->where('password',$password);
		$query		=	$this->db->get('accounts');
		$results	=	$query->result_array();
		// if(count($results) ==1){
			// return 1;
		// }else{
			// return 0;
		// }
		return count($results);
	}
	
	function check_active($authorize){
		$this->db->select('active');
		$this->db->where('authorize', $authorize);
		$query	=	$this->db->get('accounts');
		$results 	=	$query->result_array();
		return $results;
	}
	
	function authorize($data, $hash){
		$this->db->where('authorize', $hash);
		$this->db->update('accounts', $data);
	}
}