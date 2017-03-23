<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_Model extends CI_Model {	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_hotline_users($display_on='', $is_random_records = false){
		
		$this->db->where('status', STATUS_ACTIVE);
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('display_on > ', 0);
		
		if($display_on != ''){
	
			$this->db->where('display_on & '.pow(2, $display_on) .' > 0');
				
		}
		/*
		if($is_random_records)
		{
		    //toanlk  Dec 8, 2014: random ordering
		    $this->db->order_by('RAND()');
		} else {
		    $this->db->order_by('email','asc');
		}*/
		
		$this->db->order_by('RAND()');
	
		$query = $this->db->get('users');
		
		return $query->result_array();
		
	}
	
	function get_today_hotline_schedules(){
		
		$today = date(DB_DATE_FORMAT);
		
		$this->db->where('status', STATUS_ACTIVE);
		$this->db->where('start_date <=', $today);
		$this->db->where('end_date >=', $today);
		
		$this->db->where('week_day & '.pow(2,date('w',strtotime($today))).' > 0');
		
		$query = $this->db->get('hotline_schedules');
		
		return $query->result_array();
	}
	
	function get_today_hotline_users($display_on){
		
		$today = date(DB_DATE_FORMAT);
		
		$this->db->select('u.username, u.hotline_name, u.hotline_number');
		$this->db->from('hotline_schedules hs');
		$this->db->join('users u','u.id = hs.user_id');
		
		$this->db->where('hs.status', STATUS_ACTIVE);
		$this->db->where('hs.start_date <=', $today);
		$this->db->where('hs.end_date >=', $today);
		$this->db->where('hs.week_day & '.pow(2,date('w',strtotime($today))).' > 0');
		
		
		$this->db->where('u.status', STATUS_ACTIVE);
		$this->db->where('u.deleted !=', DELETED);
		$this->db->where('u.display_on & '.pow(2, $display_on) .' > 0');
		//$this->db->order_by('u.email','asc');
		$this->db->order_by('RAND()');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
		
		/*
		if(count($results) > 0){
			return $results[0];
		}
		
		return '';
		*/
	}
	
}
