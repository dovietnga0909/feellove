<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_model extends CI_Model{
	
	function __construct(){
        parent::__construct();	
		$this->load->database();
	}
	
	function get_numb_accounts($search_criteria = ''){
		
		$this->_set_search_criteria($search_criteria);
		
		return $this->db->count_all_results('accounts a');
	}
	
	function search_accounts($search_criteria = '', $num, $offset, $order_field = 'a.date_created', $order_type = 'a.desc'){
		
		$this->db->select('a.id, a.email, a.phone, a.active, a.username, a.register,a.date_created, a.date_modified, a.user_created_id, a.user_modified_id, u.username as last_modified_by, uc.username as created_account_by ');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->where('a.deleted !=', DELETED);
		
		$this->db->join('users u', 'a.user_modified_id = u.id', 'left outer');
		$this->db->join('users uc', 'a.user_created_id = uc.id', 'left outer');
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('accounts a', $num, $offset);
		
		return $query->result_array();
	}
	
	private function _set_search_criteria($search_criteria = ''){
		
		if ($search_criteria == '')	{
					
			return;
		}
		
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				case 'search_text' :
					$value =  $this->db->escape_like_str($value);
					if(!empty($value)){
						$this->db->like('a.email', $value, 'both');
					
						$this->db->or_like('a.phone', $value, 'both');
					}
					
					
					break;
					
				case 'display_on' :
					if($value !=''){
						// $this->db->where('a.email !=', NULL);
						$this->db->where('a.register', $value);
					}
					break;
			}
		}
	}
	
	function get_account($id) {
		
		if(empty($id)) {
			return FALSE;
		}
		
		$this->db->where('id', $this->db->escape_str($id));
		
		$query = $this->db->get('accounts');
		
		$result = $query->result_array();
		
		if (count($result) === 1)
		{
			$account 			= $result[0];
			return $account;
		}
		
		return FALSE;
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
	
	function check_account($email){
		$this->db->select('email');
		$this->db->where('email',$email);
		$this->db->where('password',NULL);
		$query		= $this->db->get('accounts');
		$results	= $query->result_array();
		if(count($results) >0 ){
			return 1;
		}else{
			return 0;
		}
	}
	
	function create_account($account){
		
		$addition_data = array(
			'user_created_id'		=> get_user_id(),
			'user_modified_id'		=> get_user_id(),
			'date_created'			=> date(DB_DATE_TIME_FORMAT),
			'date_modified'			=> date(DB_DATE_TIME_FORMAT),
			'register'				=> SYSTEM,
		);
		
		$account_data = array_merge($account, $addition_data);
		
		$this->db->insert('accounts', $account_data);
			
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function update_account($account){
		
		$this->db->trans_start();
		
		$account['user_modified_id'] = get_user_id();
		$account['date_modified'] = date(DB_DATE_TIME_FORMAT);
		
		$this->db->update('accounts', $account, array('id' => $account['id']));

		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_account($id){
		
		$account['deleted']	= DELETED;
		
		$this->db->update('accounts', $account, array('id'=>$id));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
}