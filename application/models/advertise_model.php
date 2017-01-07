<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advertise_Model extends CI_Model {	

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();	
        
		$this->load->database();
    }
    
    function get_advertises($ad_page, $ad_area = AD_AREA_DEFAULT, $des_id = '', $cat_id = '', $is_mobile = false) {
    	
    	$today = date(DB_DATE_FORMAT);
    	
    	$apply_on = date('w', strtotime($today));
    	
    	$this->db->where('ad.status', STATUS_ACTIVE);
    	$this->db->where('ad.deleted !=', DELETED);
    	
    	$this->db->where('ad.start_date <=', $today);
    	
    	$this->db->where('ad.end_date >=', $today);
		
    	$this->db->where('ad.display_on & '.pow(2,$ad_page).'>',0);
    	
    	$this->db->where('ad.ad_area & '.pow(2,$ad_area).'>',0);
    
    	$this->db->where('ad.week_day & '.pow(2,$apply_on).'>',0);
    	
    	if(($ad_page == AD_PAGE_HOTEL_DESTINATION || $ad_page == AD_PAGE_FLIGHT_DESTINATION || $ad_page == AD_PAGE_TOUR_DESTINATION) && !empty($des_id)){
    		
    		$module = $ad_page == AD_PAGE_HOTEL_DESTINATION ? HOTEL : FLIGHT;
    		
    		$module = $ad_page == AD_PAGE_TOUR_DESTINATION ? TOUR : $module;
    		
    		$sql_cond = '(EXISTS (SELECT 1 FROM ad_destinations ad_des WHERE ad_des.advertise_id = ad.id AND ad_des.module = '.$module. ' AND ad_des.destination_id = '.$des_id.'))';
    		
    		$this->db->where($sql_cond);
    	}
    	
    	if($ad_page == AD_PAGE_TOUR_CATEGORY && !empty($cat_id)){
    		
    		$sql_cond = '(EXISTS (SELECT 1 FROM ad_tour_categories ad_tour_cat WHERE ad_tour_cat.advertise_id = ad.id AND ad_tour_cat.category_id = '.$cat_id.'))';
    		
    		$this->db->where($sql_cond);
    	}
    	
    	$this->db->order_by('ad.position','asc');
    	 
    	$query = $this->db->get('advertises ad');
    	
    	$results = $query->result_array();
    	
    	foreach ($results as $key => $value) {
    		
    		$value['photos'] = $this->get_ad_photos($value['id'], $ad_page, $is_mobile);
    		
    		$results[$key] = $value;
    	}
    
    	 
    	return $results;
    }
    
    function get_ad_photos($ad_id, $ad_page, $is_mobile){
    	
    	$version = $is_mobile ? STATUS_ACTIVE : STATUS_INACTIVE;
    	
    	$this->db->select('id, name, advertise_id, display_on');
    	
    	$this->db->where('advertise_id', $ad_id);
    	
    	$this->db->where('display_on & '.pow(2,$ad_page).'>',0);
    	 
    	$this->db->where('status', STATUS_ACTIVE);
    	
    	$this->db->where('version', $version);
    	
    	$this->db->order_by('id','asc');
    	
    	$query = $this->db->get('ad_photos');
    
    	$rerults = $query->result_array();

    	return $rerults;
    }
}