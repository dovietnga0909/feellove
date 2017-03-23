<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extend_Core {
	
	public function __construct()
	{
		// do something in the future
	}
	
	public function display_cache_override(){
 		// do nothing for defautl cache override
	}
	
	public function custom_cache_override(){
		
		$CI =& get_instance();
	
		$CFG =& load_class('Config');
	
		$URI =& load_class('URI');
	
		$OUT =& load_class('Output');
	
		$startdate = $CI->input->get('startdate');
		
		//echo 'go here';exit();
		 
		// only get cache if the page don't have startdate parameter
		if ($startdate == ''){
	
			if ($OUT->_display_cache($CFG, $URI) == TRUE)
			{
				exit;
			}
	
		}
	
	}
	
	public function save_landing_page(){
		
		$CI =& get_instance();
		
		if ( ! $CI->input->is_ajax_request())
		{
			// New visit by session expired
			if (!isset($_COOKIE["__utmb"])){
		
				$landing_page = isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI'] : '';
					
				if ($landing_page != ''){
		
					$CI->session->set_userdata('landing_page', $landing_page);
		
				}
		
			} else {
				// new visit by changing search campaign
				 
		     
		     
			}
		
		}
		
	}
	
}