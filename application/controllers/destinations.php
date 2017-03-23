<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Destinations extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->language(array('tour', 'deal', 'destination','hotel','cruise'));
        $this->load->helper(array(
            'tour',
            'land_tour',
            'rate',
            'cookie',
            'form',
            'text',
            'display',
            'booking',
        	'hotel',
        	'cruise',
        ));
        
        $this->load->model(array(
            'Land_Tour_Model',
            'Destination_Model',
            'Tour_Model',
        	'Hotel_Model',
        	'Cruise_Model'
        ));
       
        $this->load->config('tour_meta');
        $this->load->config('hotel_meta');
        $this->load->config('cruise_meta');
        
        // $this->output->enable_profiler(TRUE);
    }

  
	/*
     * Tour Destination Details
     */
    public function destination_details($destination_id) {
    	
    	set_cache_html();
    	
    	$is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_TOURS);
        
        // Set flag hide search form
    	$data['search_visible'] = false;
        
        // build search form
        $data = build_search_criteria($data, MODULE_TOUR, $is_mobile);
        
        $data['meta'] = get_header_meta(TOUR_DESTINATION_DETAIL_PAGE);
        
        // get domestic and ourbound tour destinations     
        $startdate = $data['tour_search_criteria']['startdate'];
        
        $data['tour_destination_detail'] = $this->Destination_Model->get_destination($destination_id);
       	
        $data['photos'] = $this->Destination_Model->get_destination_photos($destination_id);
        
        $data['activities'] = $this->Destination_Model->get_activities($destination_id);
        
        $data['destination_travel'] = $this->Destination_Model->get_destination_travel($destination_id);
        
        $is_land_tour = TRUE;
        
        $land_tour_destination['tours']	= $this->Land_Tour_Model->get_land_tour_destination($destination_id, $startdate, $is_land_tour);
        
        $data['count_land_tour'] = count($land_tour_destination['tours']);
        
        $data['tour_land'] = $this->load->view($mobile_view.'tours/common/tour_list', $land_tour_destination, TRUE);
        
        $land_tour_over_destination['tours'] = $this->Land_Tour_Model->get_land_tour_destination($destination_id, $startdate);
        
        $data['tour_over_land'] = $this->load->view($mobile_view.'tours/common/tour_list', $land_tour_over_destination, TRUE);
        
        $data['count_over_land_tour'] = count($land_tour_over_destination['tours']);
        
        $option_contact = array(
    		"mode"		=> RIGHT_TOUR_CONTACT,
    	);
    
    	$data['tour_contact']  	= load_tour_contact($option_contact);
    	
    	$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
        
        $data = get_in_page_theme(TOUR_DESTINATION_DETAIL_PAGE, $data, $is_mobile);
        
        _render_view($mobile_view . 'destinations/destination_details', $data, $is_mobile);
        
    	
    }
    
    /**
     * Get the popup overview of Destination/Cruise/Hotel/Tour
     */
    function show_data_overview(){
    	
    	$id = $this->input->post('id');
    	
    	$data_type = $this->input->post('data_type');
    	
    	$data = array();
    	
    	if($data_type == 'des'){
 			
    		$destination = $this->Destination_Model->get_destination($id);
    		
    		$data['photos'] = $this->Destination_Model->get_destination_photos($id);
        	
        	$activities  = $this->Destination_Model->get_activities($id);
        	
        	$data['destination_travel'] = $this->Destination_Model->get_destination_travel($id);
    		
    		$data['tour_destination_detail'] = $this->Destination_Model->get_destination($id);
    		
    		if($destination !== FALSE){
    			$data['destination'] = $destination;
    		}
    		if($activities !== FALSE){
    			$data['activities'] = $activities;
    		}
    		
    		$this->load->view('common/bpv_destination_overview', $data);
    		
    	}elseif($data_type == 'hotel'){
    		
    		$search_criteria = $this->session->userdata(HOTEL_SEARCH_CRITERIA);
    		
    		$startdate = date(DATE_FORMAT);
    		
    		// get start-date from the session
    		if(!empty($search_criteria) && !empty($search_criteria['startdate'])){
    			
    			$startdate = $search_criteria['startdate'];
    			
    		}
    		
    		// get hotel with price on the start-date
    		
    		$hotel = $this->Hotel_Model->get_hotel_detail($id, $startdate);
    		
    		$data['photos'] = $this->Hotel_Model->get_hotel_photos($id);
        	
        	if($hotel !== ""){
        		
        		$data['hotel'] = $hotel;
        	}
			
			
			$data['hotel_room_types'] = $this->Hotel_Model->get_hotel_room_types($hotel['id'], true);
    		// load the view
    		
    		$this->load->view('common/bpv_hotel_overview', $data);
    		
    	}elseif($data_type == 'cruise'){
    		
    		$search_criteria = $this->session->userdata(CRUISE_SEARCH_CRITERIA);
    		
    		$startdate = date(DATE_FORMAT);
    		
    		// get start-date from the session
    		if(!empty($search_criteria) && !empty($search_criteria['startdate'])){
    			
    			$startdate = $search_criteria['startdate'];
    		}
    		
    		// get cruise with price on the start-date
    		
    		$cruise = $this->Cruise_Model->get_cruise_detail($id, $startdate);
    		
    		$data['photos'] = $this->Cruise_Model->get_cruise_photos($id);
    		
    		if($cruise !== ""){
        		
        		$data['cruise'] = $cruise;
        	}
        	
        	$data['cruise_cabins'] = $this->Cruise_Model->get_cruise_cabins($id);
        	
    		$this->load->view('common/bpv_cruise_overview', $data);
    		
    	}elseif($data_type == 'tour'){
    		
    		$this->load->view('common/bpv_tour_overview', $data);
    		
    	}
    	
    	return '';
    	
    }

}