<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Bpt Display Helper
 *
 * - Support format number: currency, etc ... 
 * - Support format url
 * - Support render view 
 */

// ------------------------------------------------------------------------

function get_url($page, $object = array(), $search_criteria = array()) {
	$url = $page;
	switch ($page) {
		case CRUISE_HL_HOME_PAGE:
		case FLIGHT_HOME_PAGE:
		case HOTEL_HOME_PAGE:
			$url = $page;
			break;

		case FLIGHT_EXCEPTION_PAGE:
			$url = FLIGHT_EXCEPTION_PAGE.'?code='.$object['code'].'&message='.$object['message'];
			break;	
		case FLIGHT_DESTINATION_PAGE:
			$url = FLIGHT_DESTINATION_PAGE. $object['url_title'] .'-'. $object['id'] . URL_SUFFIX;
			break;
			
		case FLIGHT_AIRLINE_PAGE:
			$url = FLIGHT_AIRLINE_PAGE. $object['url_title'] .'-'. $object['id'] . URL_SUFFIX;
			break;
			
		case FLIGHT_CATEGORY_PAGE:
			$url = FLIGHT_CATEGORY_PAGE. $object['url_title'] .'-'. $object['id'] . URL_SUFFIX;
			break;
			
		case NEWS_DETAILS_PAGE:
			$url = NEWS_DETAILS_PAGE. $object['url_title'] .'-'. $object['id'] . URL_SUFFIX;
			break;
			
		case NEWS_CATEGORY_PAGE:
		    $url = NEWS_CATEGORY_PAGE. $object['url_title'] .'-'. $object['id'] . URL_SUFFIX;
		    break;
			
		case HOTEL_DETAIL_PAGE:
			$url = HOTEL_HOME_PAGE.'/'.$object['url_title'] .'-'. $object['id'] . URL_SUFFIX;
			
			break;
		case HOTEL_DESTINATION_PAGE:
			$url = HOTEL_DESTINATION_PAGE. $object['url_title'] .'-'. $object['id'] . URL_SUFFIX;
			break;
		
		case HOTEL_SEARCH_PAGE:
			$url = HOTEL_SEARCH_PAGE.'#'.http_build_query($object);
			break;
			
		case HOTEL_BOOKING_PAGE:
			// code later
			break;

		/*
		 * For Cruise
		 */
		case CRUISE_HL_DETAIL_PAGE:
			$url = CRUISE_HL_DETAIL_PAGE.$object['url_title'] .'-'. $object['id'] . URL_SUFFIX;
				
			break;
		case CRUISE_HL_SEARCH_PAGE:
			$url = CRUISE_HL_SEARCH_PAGE.'#'.http_build_query($object);
			break;

		/**
		 * Contact & Confirm Page
		 */
		case CONTACT_US_PAGE:
		case CONFIRM_PAGE:
			$url = $page;
			if(!empty($object)){
				$url .= '?'.http_build_query($object);
			}
			break;
			
		case TOUR_DESTINATION_PAGE:
		    
		    $url = TOUR_DESTINATION_PAGE . $object['url_title'] . '-' . $object['id'] . URL_SUFFIX;
		    
		    break;
		    
	    case TOUR_DESTINATION_DETAIL_PAGE:
	    
	        $url = TOUR_DESTINATION_DETAIL_PAGE . $object['url_title'] . '-' . $object['id'] . URL_SUFFIX;
	    
	        break;
		    
	    case TOUR_CATEGORY_DETAIL_PAGE:
	    
	    	if(empty($object['link']))
	    	{
	    		$url = TOUR_CATEGORY_DETAIL_PAGE . $object['url_title'] . '-' . $object['id'] . URL_SUFFIX;
	    	} else {
	    		$url = $object['link'];
	    	}
	        
	    
	        break;
	   	case TOUR_CATEGORY_PAGE:
	    
	        $url = TOUR_CATEGORY_PAGE . $object['link'] . '-' . $object['id'] . URL_SUFFIX;
	    
	        break;
	        
        case TOUR_DOWNLOAD:
            
            $url = TOUR_DOWNLOAD . $object['url_title'] . '-' . $object['id'] . URL_SUFFIX;
            
            break;
			
		case TOUR_DETAIL_PAGE:
		    
		    // normal tour
		    if (empty($object['cruise_id']))
            {
                $url = TOUR_DETAIL_PAGE . $object['url_title'] . '-' . $object['id'] . URL_SUFFIX;
                
                if (! empty($search_criteria))
                {
                    
                    $check_rate_info['startdate'] = $search_criteria['startdate'];
                    
                    if (! empty($search_criteria['adults']))
                    {
                        $check_rate_info['adults'] = $search_criteria['adults'];
                    }
                    
                    if (! empty($search_criteria['children']))
                    {
                        $check_rate_info['children'] = $search_criteria['children'];
                    }
                    
                    if (! empty($search_criteria['infants']))
                    {
                        $check_rate_info['infants'] = $search_criteria['infants'];
                    }
                    
                    if (! empty($search_criteria['enddate']))
                    {
                        $check_rate_info['enddate'] = $search_criteria['enddate'];
                    }
                    
                    if (! empty($search_criteria['action']))
                    {
                        $check_rate_info['action'] = $search_criteria['action'];
                    }
                    
                    $url = $url . '?' . http_build_query($check_rate_info);
                }
            }
            else
            {
                // cruise tour
                $url = TOUR_HL_DETAIL_PAGE . $object['url_title'] . '-' . $object['id'] . URL_SUFFIX;
                
                if (count($search_criteria) > 0)
                {
                    
                    if (! isset($search_criteria['is_default']) || ! $search_criteria['is_default'])
                    {
                        
                        $check_rate_info['startdate'] = $search_criteria['startdate'];
                        
                        if (! empty($search_criteria['adults']))
                        {
                            $check_rate_info['adults'] = $search_criteria['adults'];
                        }
                        
                        if (! empty($search_criteria['children']))
                        {
                            $check_rate_info['children'] = $search_criteria['children'];
                        }
                        
                        if (! empty($search_criteria['infants']))
                        {
                            $check_rate_info['infants'] = $search_criteria['infants'];
                        }
                        
                        if (! empty($search_criteria['enddate']))
                        {
                            $check_rate_info['enddate'] = $search_criteria['enddate'];
                        }
                        
                        if (! empty($search_criteria['action']))
                        {
                            $check_rate_info['action'] = $search_criteria['action'];
                        }
                        
                        $url = $url . '?' . http_build_query($check_rate_info);
                    }
                }
            }
		
		    break;
	
	}
	
	return site_url($url);
}

/**
 * 
 * Set header meta for each page
 * 
 */
function get_header_meta($page, $object = array()){
	
	$robots 		= 'index,follow';
	$title 			= '';
	$description 	= '';
	$canonical 		= '';
	
	$search 		= true;
	
	switch ($page) {
		
		case HOME_PAGE:
			
			$title 			= lang('home_title');
			$description 	= lang('home_description');
			break;
		
		case FLIGHT_HOME_PAGE:
			
			$title 			= lang('flight_title');
			$description 	= lang('flight_description');
			break;
			
		case FLIGHT_DESTINATION_PAGE:
				
			$title 			= lang_arg('flight_destination_title', $object['name']);
			$description 	= lang_arg('flight_destination_description', $object['name']);
			break;
			
		case FLIGHT_AIRLINE_PAGE:
		
			$title 			= lang_arg('flight_airline_title', $object['name']);
			$description 	= lang_arg('flight_airline_description', $object['name']);
			break;

		case FLIGHT_CATEGORY_PAGE:
			$title 			= $object['name'];
			$description 	= $object['name'];
			break;
			
		case FLIGHT_SEARCH_PAGE:
		
			$robots 		= 'noindex,nofollow';
			$title 			= lang('flight_search_title');
			$description 	= lang('flight_search_description');
			break;
			
		case FLIGHT_DETAIL_PAGE:
		
			$robots 		= 'noindex,nofollow';
			$title 			= lang('flight_passenger_title');
			$description 	= lang('flight_passenger_description');
			$search 		= false;
			break;
			
		case FLIGHT_BOOKING_PAGE:
		
			$robots 		= 'noindex,nofollow';
			$title 			= lang('flight_booking_title');
			$description 	= lang('flight_booking_description');
			$search 		= false;
			break;
			
		case HOTEL_HOME_PAGE:
			
			$title 			= lang('hotel_title');
			$description 	= lang('hotel_description');
			
			break;
			
		case HOTEL_DESTINATION_PAGE:
			
			$title 			= lang_arg('hotel_destination_title', $object['name']);
			$description 	= lang_arg('hotel_destination_description', $object['name']);
			break;
			
		case HOTEL_DETAIL_PAGE:
			
			$title 			= lang_arg('hotel_detail_title', $object['name'], $object['destination_name']);
			$description 	= lang_arg('hotel_detail_description', $object['name'], $object['destination_name']);
			
			$canonical 		= isset($object['canonical']) ? $object['canonical'] : '';
			break;
			
		case HOTEL_BOOKING_PAGE:
		    	
		    $robots 		= 'noindex,nofollow';
		    $title 			= lang_arg('hotel_booking_title', $object['name']);
		    $description 	= lang_arg('hotel_booking_description', $object['name']);
		    
		    $search         = false;
		    break;

		case HOTEL_SEARCH_PAGE:
			
			$robots 		= 'noindex,nofollow';
			$title 			= lang('hotel_search_title');
			$description 	= lang('hotel_search_description');
			
			break;

		case HOT_DEAL_PAGE:
				
			$title 			= lang('deals_title');
			$description 	= lang('deals_description');
			break;
			
		case CRUISE_HL_HOME_PAGE:
		
			$title 			= lang('cruise_hl_title');
			$description 	= lang('cruise_hl_description');
		
			break;
				
		case CRUISE_HL_DETAIL_PAGE:
		
			$title 			= lang_arg('cruise_hl_detail_title', $object['name']);
			$description 	= lang_arg('cruise_hl_detail_description', $object['name']);
		
			$canonical 		= isset($object['canonical']) ? $object['canonical'] : '';
			break;
			
		case CRUISE_HL_SEARCH_PAGE:
		    	
		    $robots 		= 'noindex,nofollow';
		    $title 			= lang('cruise_hl_search_title');
		    $description 	= lang('cruise_hl_search_description');
		    break;
			
		case CRUISE_HL_BOOKING_PAGE:
		
		    $robots 		= 'noindex,nofollow';
		    $title 			= lang_arg('cruise_hl_booking_title', $object['name']);
		    $description 	= lang_arg('cruise_hl_booking_description', $object['name']);
		    
		    $search         = false;
		    break;
		    
	    case TOUR_HOME_PAGE:
	    
	        $title 			= lang('tour_home_title');
	        $description 	= lang('tour_home_description');
	    
	        break;
	        
        case TOUR_DOMESTIC_PAGE:
        	 
        	$title 			= lang('label_domestic_tours').' - '.lang('marketing_domestic_tours');
        	$description 	= $title;
        	 
        	break;
        	
        case TOUR_OUTBOUND_PAGE:	
        	$title 			= lang('label_outbound_tours').' - '.lang('marketing_outbound_tours');
        	$description 	= $title;
        	
        	break;
        	
        case TOUR_DESTINATION_PAGE:
        	
        	$title 			= $object['name'].(!empty($object['marketing_title']) ? ' - '.$object['marketing_title'] : '');
        	$description 	= $title;
        		 
        	break;
        	
        case TOUR_CATEGORY_PAGE:
        	$title 			= lang('label_category_tours').' - '.lang('marketing_category_tours');
        	$description 	= $title;
        		 
        	break;
        	
        case TOUR_CATEGORY_DETAIL_PAGE:
        	$title 			= $object['name'];
        	$description 	= $object['description'];
        		 
        	break;
	        
        case TOUR_SEARCH_PAGE:
             
            $robots 		= 'noindex,nofollow';
            $title 			= lang('tour_search_page_title');
            $description 	= lang('tour_search_page_description');
            break;
            
        case TOUR_DETAIL_PAGE:
        
            $title 			= $object['name'];
            $description 	= $object['name'];

            $canonical 		= isset($object['canonical']) ? $object['canonical'] : '';
            break;
			
		case NEWS_DETAILS_PAGE:
		
		    $robots 		= 'index,nofollow';
			$title 			= $object['name'];
			$description 	= $object['name'];
			break;
			
		case NEWS_HOME_PAGE:
		
		    $robots 		= 'index,follow';
		    $title 			= lang('news_home_title');
			$description 	= lang('news_home_description');
			
			$canonical 		= isset($object['canonical']) ? $object['canonical'] : '';
		    break;
		    
	    case NEWS_CATEGORY_PAGE:
	    
	        $robots 		= 'index,nofollow';
	        $title 			= $object['name'];
	        $description 	= $object['name'];
	        break;
		
		case ABOUT_US_PAGE:
		
		    $robots 		= 'index,nofollow';
			$title 			= lang('about_us_title');
			$description 	= lang('about_us_description');
			
			$search         = false;
			break;
			
		case TERM_CONDITION_PAGE:
		
		    $robots 		= 'noindex,nofollow';
			$title 			= lang('term_condition_title');
			$description 	= lang('term_condition_description');
			
			$search         = false;
			break;
			
		case PRIVACY_PAGE:
		
		    $robots 		= 'noindex,nofollow';
			$title 			= lang('privacy_title');
			$description 	= lang('privacy_description');
			
			$search         = false;
			break;
		
		case FAQS_PAGE:
		
			$title 			= lang('faq_title');
			$description 	= lang('faq_description');
			
			$search         = false;
			break;
			
		case CONTACT_US_PAGE:
		
			$title 			= lang('contact_us_title');
			$description 	= lang('contact_us_description');
			
			$search         = false;
			break;
		
		case PAYMENT_METHODS_PAGE:
		
			$title 			= lang('payment_methods_title');
			$description 	= lang('payment_methods_description');
			
			$search         = false;
			break;
			
		case BOOK_TOGETHER_PAGE:
		
			$title 			= lang('book_together_title');
			$description 	= lang('book_together_description');
			break;
				
		case BEST_PRICE_PAGE:
		
			$title 			= lang('best_price_title');
			$description 	= lang('best_price_description');
			break;
			
		case THANK_YOU_PAGE:
		
		    $title 			= lang_arg('thank_you_title', $object['name']);
		    $description 	= lang_arg('thank_you_description', $object['name']);
		    
		    $search         = false;
		    break;
	    case ACCOMPLISHMENT_PAGE:
	    
	        $title 			= lang('accomplishment_title');
	        $description 	= lang('accomplishment_description');
	        	
	        $search         = false;
	        break;
        case TESTIMONIAL_PAGE:
             
            $title 			= lang('testimonial_title');
            $description 	= lang('testimonial_description');
        
            $search         = false;
            break;
        case BESTPRICE_WITH_PRESS_PAGE:

            $robots 		= 'noindex,nofollow';
            $title 			= lang('bestprice_with_press_title');
            $description 	= lang('bestprice_with_press_description');
        
            $search         = false;
            break;
	}
	
	$header_meta['title'] 		= $title;
	$header_meta['description'] = $description;
	$header_meta['robots'] 		= $robots;
	$header_meta['canonical'] 	= $canonical;
	$header_meta['search'] 	    = $search;
	
	return $header_meta;
}

function format_currency($numb) {
	return number_format($numb, 0, ',', '.').lang('vnd');
}

/**
 * Render main view bases on the template
 *
 * @param $view
 * @param $data
 * @param $render
 */
function _render_view($main_view, $data=null, $is_mobile = false)
{
	$CI =& get_instance();
	
	if (!isset($CI->data)) $CI->data = array();

	$CI->viewdata = (empty($data)) ? $CI->data: $data;

	$CI->viewdata['bpv_content'] = $CI->load->view($main_view, $CI->viewdata, TRUE);
	
	if($is_mobile){
		$view_html = $CI->load->view('mobile/_templates/bpv_layout', $CI->viewdata);
	} else {
		$view_html = $CI->load->view('_templates/bpv_layout', $CI->viewdata);
	}

	return $view_html;
}

/**
 * Fetch a single line of text from the language array. Takes variable number
 * of arguments and supports wildcards in the form of '%1', '%2', etc.
 * Overloaded function.
 *
 * @access public
 * @return mixed false if not found or the language string
 */
function lang_arg()
{
	$CI =& get_instance();
	//get the arguments passed to the function
	$args = func_get_args();

	//count the number of arguments
	$c = count($args);

	//if one or more arguments, perform the necessary processing
	if ($c)
	{
		//first argument should be the actual language line key
		//so remove it from the array (pop from front)
		$line = array_shift($args);

		//check to make sure the key is valid and load the line
		$line = lang($line);

		//if the line exists and more function arguments remain
		//perform wildcard replacements
		if ($line && $args)
		{
			$i = 1;
			foreach ($args as $arg)
			{
				$line = preg_replace('/\%'.$i.'/', $arg, $line);
				$i++;
			}
		
		}
	
	}
	else
	{
		//if no arguments given, no language line available
		$line = false;
	}

	return $line;
}

/**
 *
 * Get distance between 2 cordination
 *
 */
function distance($lat1, $lon1, $lat2, $lon2, $unit = "K") {
	$theta = $lon1 - $lon2; 
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
	$dist = acos($dist); 
	$dist = rad2deg($dist); 
	$miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);
 
	if($unit == "K")
	{	
		return number_format($miles * 1.609344, 2) . ' km'; 
	}
	elseif($unit == "N") {
		return ($miles * 0.8684);
	}
	else
	{
		return $miles;
	}
}

function getDrivingDistance($inLatitude, $inLongitude, $outLatitude, $outLongitude) {
	if (empty ( $inLatitude ) || empty ( $inLongitude ) || empty ( $outLatitude ) || empty ( $outLongitude ))
		return 0;
		
		// Generate URL
	$url = "http://maps.googleapis.com/maps/api/directions/json?origin=$inLatitude,$inLongitude&destination=$outLatitude,$outLongitude&sensor=false";
	
	// Retrieve the URL contents
	$c = curl_init ();
	curl_setopt ( $c, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $c, CURLOPT_URL, $url );
	$jsonResponse = curl_exec ( $c );
	curl_close ( $c );
	
	$dataset = json_decode ( $jsonResponse );
	if (! $dataset)
		return 0;
	if (! isset ( $dataset->routes [0]->legs [0]->distance->text ))
		return 0;
	$distance = $dataset->routes [0]->legs [0]->distance->text;
	
	return $distance;
}


function mark_required() {
	return '<span class="bpv-color-warning">(*)</span>';
}

function limit_description($str){
	$str = str_replace(array('<br>','<br/>','</br>','<p>','</p>'), "\n", $str);
	$str = strip_tags($str);
	$str = word_limiter($str, DESC_WORD_LIMIT);
	$str = str_replace("\n\n", '<br>', $str);
	$str = str_replace("\n", '<br>', $str);
	return $str;
}

function load_search_waiting($message, $mode="waiting", $please_wait_txt = ''){
	$CI =& get_instance();
	$data['message'] = $message;
	$data['please_wait_txt'] = $please_wait_txt;
	$data['mode'] = $mode;
	
	$mobile_view = is_mobile() ? 'mobile/' : '';
	
	return $CI->load->view($mobile_view.'common/bpv_search_waiting', $data, TRUE);
}

function load_bpv_call_us($display_on = HOTEL){
	$CI =& get_instance();

	if(is_working_time() || is_hotline_time()){
		
		$CI->load->model('User_Model');
		
		if(is_hotline_time()){
		
			$data['hotline_users'] = $CI->User_Model->get_hotline_users($display_on);
			
			$data['hotline_users'] = setting_hotline_time($data['hotline_users']);
		
		}
		
		$data['display_on'] = $display_on;
		
		$mobile_view = is_mobile() ? 'mobile/' : '';
		
		return $CI->load->view($mobile_view.'common/bpv_call_us', $data, TRUE);
	
	} else {
		return '';
	}
}

function load_bpv_call_us_number($display_on = HOTEL){
	
	$phone_number = '';
	
	$phone = load_phone_support();
	
	if(is_working_time() || is_hotline_time()){
		
		if(is_working_time()){
			
			$phone_number = $phone;
			
		} else {

			$current_hotlines = load_current_main_hotline($display_on);
			
			$current_hotline = !empty($current_hotlines) ? $current_hotlines[0] : '';
			
			if(!empty($current_hotline)){
				
				$phone_number = $current_hotline['hotline_number'];
			} else {
				
				$phone_number = $phone;
			}
			
		}
		
	} 
	
	return $phone_number;
}

function load_flight_booking_exception($search_criteria, $code = 1, $is_mobile = FALSE){
	
	$message = lang('flight_seat_unavailable');		
	$link = get_current_flight_search_url($search_criteria);
	$link_label = lang('search_flight_again');
	
	if($code == 2){ // fail to get VNISC Sid
		
		$message = lang('fail_to_get_flights');
		
	} elseif($code == 3){ // flight sold out
		
		$message = lang('all_flight_sold_out');
		
		$link = get_url(FLIGHT_HOME_PAGE);
		
	} elseif($code == 4){// time-out
		
		$message = lang('flight_timeout');
	}

	
	$data['message'] = $message;
	
	$data['link'] = $link;
	
	$data['link_label'] = $link_label;
	
	$request = get_flight_exception_short_req($search_criteria);
				
	$data['contact_form'] = load_contact_form(true, $request, '', $is_mobile);
	
	$mobile_view = $is_mobile ? 'mobile/' : '';
	
	$CI =& get_instance();
	return $CI->load->view($mobile_view.'flights/common/flight_booking_exception', $data, TRUE);
}

function load_contact_form($show_button = true, $request = '', $custom_css = '', $is_mobile = false){

	$CI =& get_instance();

	$CI->load->model('Destination_Model');

	$data['c_titles'] = $CI->config->item('c_titles');

	$data['c_cities'] = $CI->Destination_Model->get_customer_cities();

	$data['request'] = $request;
	
	$data['show_button'] = $show_button;
	
	$data['custom_css'] = $custom_css;
	
	if($is_mobile){
		$contact_form = $CI->load->view('mobile/common/bpv_customer_contact', $data, TRUE);
	} else {
		$contact_form = $CI->load->view('common/bpv_customer_contact', $data, TRUE);
	}

	return $contact_form;
}

function load_payment_method($type = FLIGHT, $is_mobile = false){
	$CI =& get_instance();
	
	$data['type'] = $type; // 1 show all, 2 show online only, 3 show offline only
	
	$data['bank_transfer'] = $CI->config->item('bank_transfer');
	
	$mobile_view = $is_mobile ? 'mobile/' : '';
	
	if($type == FLIGHT){
		
		$payment_method = $CI->load->view($mobile_view.'flights/common/flight_payment_methods', $data, TRUE);
	
	}
	
	if($type == HOTEL){
		$payment_method = $CI->load->view($mobile_view.'hotels/common/hotel_payment_methods', $data, TRUE);
	}
	
	if($type == CRUISE){
		$payment_method = $CI->load->view($mobile_view.'cruises/common/cruise_payment_methods', $data, TRUE);
	}
	
	if($type == TOUR){
	    $payment_method = $CI->load->view($mobile_view.'tours/common/payment_methods', $data, TRUE);
	}
	
	return $payment_method;
}

function load_contact_mnu($active_index, $is_mobile = false){
    
    $is_mobile = is_mobile();
    $mobile_view = $is_mobile ? 'mobile/' : '';

	$CI =& get_instance();

	$data['active_index'] = $active_index;

	$contact_mnu = $CI->load->view( $mobile_view.'contacts/mnu_contacts', $data, TRUE);

	return $contact_mnu;
}

function load_list_hotel_deals($hotels){
	$CI =& get_instance();
	
	$data['hotels'] = $hotels;
	
	$list_view = $CI->load->view('deals/hotel_list', $data, TRUE);
	
	return $list_view;
}

function load_bpv_ads($ad_page, $ad_area = AD_AREA_DEFAULT, $des_id = '', $cat_id = ''){
	$CI =& get_instance();
	$CI->load->model('Advertise_Model');
	
	$is_mobile = is_mobile();
	
	// get advertises
	$data['advertises'] = $CI->Advertise_Model->get_advertises($ad_page, $ad_area, $des_id, $cat_id, $is_mobile);
	$data['ad_page'] = $ad_page;
	
	if($is_mobile){
		$bpv_ads = $CI->load->view('mobile/common/bpv_ads', $data, TRUE);
	} else {
		$bpv_ads = $CI->load->view('common/bpv_ads', $data, TRUE);
	}
	
	return $bpv_ads;
}


/**
 * 
 * Load random ad on a page and on a specific area
 * return Empty if there's no Ad
 * 
 * Khuyenpv 02.10.2014
 * 
 */
function load_random_ad($ad_page, $ad_area = AD_AREA_DEFAULT, $des_id = '', $cat_id = ''){
	
	$CI =& get_instance();
	$CI->load->model('Advertise_Model');
	$is_mobile = is_mobile();
	
	// get advertises
	$advertises = $CI->Advertise_Model->get_advertises($ad_page, $ad_area, $des_id, $cat_id, $is_mobile);
	$data['ad_page'] = $ad_page;
	
	if(count($advertises) > 0){
		
		$rand_key = array_rand($advertises);
		
		$ad = $advertises[$rand_key];
		
		$photos = $ad['photos'];
		
		if(count($photos) > 0){
			
			$photo = $photos[array_rand($photos)];
			
			$ad['photo'] = $photo;
			
			$data['ad'] = $ad;
			
			$random_ad_view = $CI->load->view('common/bpv_random_ad', $data, TRUE);
			
			return $random_ad_view;
			
		} else {
			
			return '';
			
		}
		
	} else {
		
		return '';
	}
	
}


function load_bpv_why_us($page){
	$CI =& get_instance();
	
	$data['page'] = $page;
	
	$bpv_why_us = $CI->load->view('common/bpv_why_us', $data, TRUE);

	return $bpv_why_us;
}

function load_recent_items($data, $startdate, $module = MODULE_HOTEL, $hotel_id = null, $is_small_layout = false, $title = null) {

	$CI =& get_instance();

	$recent_items = $CI->session->userdata(RECENT_ITEMS);
	
	if ($module == MODULE_HOTEL) {
		
		if (!empty($hotel_id) && !empty($recent_items['hotel'])) {
			$recent_hotels = array();
		
			foreach ($recent_items['hotel'] as $hotel) {
				if($hotel['hotel_id'] != $hotel_id) {
					$recent_hotels[] = $hotel;
				}
			}
		
			//$recent_items['hotel'] = $recent_hotels;
		}
		
		
		$data['is_small_layout'] = $is_small_layout;
		
		$data['recent_items_title'] = !empty($title) ? $title : lang('recent_items');
		
		$data['recent_hotels'] = $CI->Hotel_Model->get_recent_hotels($recent_items, $startdate);
		
		$data['bpv_recent_hotel'] = $CI->load->view('common/bpv_recent_hotel', $data, TRUE);
		
	} elseif ($module == MODULE_CRUISE) {
		
		if (!empty($hotel_id) && !empty($recent_items['cruise'])) {
			$recent_hotels = array();
		
			foreach ($recent_items['cruise'] as $hotel) {
				if($hotel['cruise_id'] != $hotel_id) {
					$recent_hotels[] = $hotel;
				}
			}
		}
		
		
		$data['is_small_layout'] = $is_small_layout;
		
		$data['recent_items_title'] = !empty($title) ? $title : lang('recent_items');
		
		$data['recent_cruises'] = $CI->Cruise_Model->get_recent_cruises($recent_items, $startdate);
		
		$data['bpv_recent_cruise'] = $CI->load->view('cruises/common/bpv_recent_cruise', $data, TRUE);
	}
	

	return $data;
}

function load_best_hotel($data, $search_criteria, $startdate, $hotel_litmit = 10) {
	$CI =& get_instance();
	
	// get best hotels by destinations
	$best_hotel_destinations = $CI->Hotel_Model->get_best_hotel_destinations($startdate, BEST_HOTEL_LIMIT, $hotel_litmit);
	
	foreach ($best_hotel_destinations as $k => $des) {

		$url_params['destination'] = $des['name'];
		$url_params['startdate'] = $search_criteria['startdate'];
		$url_params['night'] = $search_criteria['night'];
		$url_params['enddate'] = $search_criteria['enddate'];
		$url_params['oid'] = 'd-'.$des['id'];
		$des['url_params'] = $url_params;
		
		$best_hotel_destinations[$k] = $des;
	}
	
	$data['best_hotel_destinations'] = $best_hotel_destinations;
	
	$data['bpv_best_hotel'] = $CI->load->view('common/bpv_best_hotel', $data, TRUE);
	
	return $data;
}

function load_promotion_tooltip($pro, $obj_id = '', $position = 'bottom', $is_mobile = FALSE){
	
	$CI =& get_instance();
	
	$data['pro'] = $pro;
	
	$data['week_days'] = $CI->config->item('week_days');
	
	$data['position'] = $position;
	
	$data['obj_id'] = $obj_id;
	
	$mobile_view = $is_mobile ? 'mobile/' : '';
	
	$pro_detail = $CI->load->view($mobile_view.'common/bpv_pro_detail', $data, TRUE);
	
	return $pro_detail;
}

function load_marketing_pro_tooltip($bpv_pro, $hotel_id, $position = 'bottom', $is_mobile = FALSE){

	$CI =& get_instance();

	$data['bpv_pro'] = $bpv_pro;

	$data['week_days'] = $CI->config->item('week_days');

	$data['position'] = $position;
	
	$data['hotel_id'] = $hotel_id;
	
	$mobile_view = $is_mobile ? 'mobile/' : '';

	$pro_detail = $CI->load->view($mobile_view.'common/bpv_marketing_pro_detail', $data, TRUE);

	return $pro_detail;
}

function load_hotel_room_cancellation($hotel, $room_rate, $startdate){
	
	$CI =& get_instance();
	
	$data['hotel'] = $hotel;
	
	$data['room_rate'] = $room_rate;
	
	$data['startdate'] = $startdate;
	
	$room_cancellation = $CI->load->view('hotels/common/room_cancellation_info', $data, TRUE);
	
	return $room_cancellation;
}

function load_hotel_room_price_detail($room_rate, $rate, $room_index = ''){

	$CI =& get_instance();

	$data['room_rate'] = $room_rate;
	
	$data['rate'] = $rate;
	
	$data['room_index'] = $room_index;

	$room_price_detail = $CI->load->view('hotels/common/room_price_info', $data, TRUE);

	return $room_price_detail;
}

/**
 * For Hotline Support
 */
function load_online_support(){
	
	$CI =& get_instance();
	
	$CI->load->model('User_Model');
	
	$data['hotline_users'] = $CI->User_Model->get_hotline_users('', true);
	
	$data['hotline_users'] = setting_hotline_time($data['hotline_users']);
	
	$online_support = $CI->load->view('common/bpv_online_support', $data, TRUE);
	
	return $online_support;
}

// ------------------------------------------------------------------------

/**
  *  load hotline sales including avatar
  *
  *  @author toanlk
  *  @since  Oct 9, 2014
  */
function load_hotline_suport($display_on = TOUR, $on_sidebar = true)
{
    $on_sidebar = $on_sidebar ? 1 : 0;
    echo '<div id="hotline_support_box">
        <div class="bpv-search-waiting">
        <img width="30" height="30" class="margin-right-10" src="'.get_static_resources('media/icon/loading.gif').'">
        </div>
        </div>
        <script>get_hotline_box('.$display_on.','.$on_sidebar.');</script>';
}

// ------------------------------------------------------------------------

/**
 * load_contact_popup
 * 
 * $btn_popup: id of action button
 * @param string $btn_popup
 * @param string $type
 * @return html
 */
function load_contact_popup($btn_popup, $type = 'groupon', $custom_text = ''){

	$CI =& get_instance();
	
	$data = array();
	
	switch ($type) {
		case 'contact':
			$data['popup_title'] = lang('contact_us');
			break;
		default:
			$data['popup_title'] = lang('groupon');
			break;
	}
	
	$data['btn_popup'] = $btn_popup;
	
	$data['popup_type'] = $type;
	
	$data['message'] = lang('groupon_sending');

	$data['custom_text'] = $custom_text;
	
	$contact_popup = $CI->load->view('common/bpv_contact_popup', $data, TRUE);

	return $contact_popup;
}

// ------------------------------------------------------------------------
/**
 * Contact form validation
 *
 * @author toanlk
 * @since  Dec 8, 2014
 */
function contact_validation()
{
    $CI =& get_instance();
    
    $CI->load->config('contact_meta');
    $CI->load->library('form_validation');
    $contact_config = $CI->config->item('contact_rules');
    $CI->form_validation->set_rules($contact_config);
    $CI->form_validation->set_error_delimiters('<div class="bpv-color-warning warning-message" style="display:block">', '</div>');
    
    $CI->form_validation->set_message('required', lang('required'));
    $CI->form_validation->set_message('matches', lang('invalid_input'));
    $CI->form_validation->set_message('valid_email', lang('invalid_input'));
    
    return $CI->form_validation->run();
}

// ------------------------------------------------------------------------

/**
 * Is working time
 * @return boolean
 */
function is_working_time(){

	$current_hour = date('H:i');
	
	if(date('w') == 0){ // Sunday
		$is_working_time = false;
	} elseif(date('w') == 6){ // Sartuday
		$is_working_time = $current_hour >= '08:00' &&  $current_hour <= '12:00';
	} else {
		$is_working_time = $current_hour >= '08:00' && $current_hour <= '17:30';
	}
	
	return $is_working_time;
}

// ------------------------------------------------------------------------

/**
 * is_hotline_time
 * @return boolean
 */
function is_hotline_time(){
	
	$current_hour = date('H:i');
	
	$is_hotline_time = $current_hour >= '08:00' && $current_hour <= '21:00';
	
	return $is_hotline_time;
}

function setting_hotline_time($hotline_users){
	
	$CI =& get_instance();
	
	$current_hour = date('H:i');
	
	$is_working_time = is_working_time();
	
	$show_hotline = is_hotline_time();
	
	$CI->load->model('User_Model');
	
	$today_schedules = $CI->User_Model->get_today_hotline_schedules();
	
	foreach ($hotline_users as $key=> $user){
		
		$user['show_hotline'] = $is_working_time;
		
		if(!$is_working_time){
			
			if($show_hotline){

				if(count($today_schedules) > 0){
					
					foreach ($today_schedules as $schedule){
						
						if($schedule['user_id'] == $user['id']){
							
							$user['show_hotline'] = true;
							
							break;
							
						}
						
					}
					
				}
				
			}
		}
		
		$hotline_users[$key] = $user;
	}
	
	return $hotline_users;
}

function load_phone_support(){
	
	if(!is_working_time()) return '';
	
	$CI =& get_instance();
	
	$mnu = $CI->session->userdata('MENU');

	if($mnu == MNU_FLIGHTS){
		return PHONE_SUPPORT_FLIGHT;
	} else {
		return PHONE_SUPPORT;
	}
}

function load_current_main_hotline($display_on = ''){
	
	$CI =& get_instance();
	
	
	if($display_on == ''){
	
		$mnu = $CI->session->userdata('MENU');
	
		if($mnu == MNU_FLIGHTS){
			
			$display_on = FLIGHT;
			
		} else {
			
			$display_on = HOTEL;
			
		}
	
	}
	
	
	$CI->load->model('User_Model');
	
	$users = $CI->User_Model->get_today_hotline_users($display_on);
	
	return $users;
}

function load_footer_links($data, $flight_links = true, $hotel_links = false, $tour_links =  false) {
	
	$CI =& get_instance();
	
	if ($flight_links) {
		
		$data['airlines'] = $CI->Flight_Model->get_airlines();
		
		$data['domestic_flights'] = $CI->Flight_Model->get_flight_destinations(1);
		
		$data['international_flights'] = $CI->Flight_Model->get_international_flights();
		
		// get news
		$data['flight_categories'] = $CI->Flight_Model->get_flight_categories();
	}
	
	// Bottom: Hotel by destinations
	if( $hotel_links ) {
		$data['footer_hotel_destinations'] = $CI->Hotel_Model->get_all_hotel_destinations();
	}
	
	if( $tour_links ){
		if(!isset($data['domestic_destinations'])){
			$data['domestic_destinations'] 	= $CI->Land_Tour_Model->get_tour_departure_destinations();
		}
		
		if(!isset($data['outbound_destinations'])){
		
			$data['outbound_destinations'] 	= $CI->Land_Tour_Model->get_tour_departure_destinations(true);
		}
		
		if(!isset($data['tour_categories'])){
			
			$data['tour_categories'] 		= $CI->Land_Tour_Model->get_categories();
		
		}
	}
	
	$data['hotel_links']	= $hotel_links;
	$data['flight_links']	= $flight_links;
	$data['tour_links']		= $tour_links;
	
	$data['footer_links'] = $CI->load->view('_layouts/footer_links', $data, TRUE);
	
	return $data;
}

function load_hotel_map(){
	
	$CI =& get_instance();
	
	$map_view = $CI->load->view('hotels/common/hotel_map');
	
	return $map_view;
}

/*
 * show_review
 */
function show_review($obj, $link, $is_search_list = false, $is_mobile = false) {
	$txt = '&nbsp;';
	
	if(empty($obj['review_number'])) return $txt;
	
	if($is_mobile) {
	    $review_text = get_review_text($obj['review_score']);
	    $txt = '<b>'.$review_text.'&nbsp;&nbsp;' . $obj['review_score'] .'</b> - '.$obj['review_number'].' '.lang('rev_txt');
	    
	    return $txt;
	}
	
	if( !$is_search_list ) { // For normal list
		$icon = get_review_text($obj['review_score'], true);
		
		$txt = '<p class="item-review">';
		$txt .= '<span class="icon ' . $icon .'"><span>'.$obj['review_score'].'</span></span>';
		$txt .= get_review_text($obj['review_score']);
		$txt .= '<a href="'.$link.'?tab=tab_reviews">'.$obj['review_number'].' '.lang('rev_txt').'</a>';
	} else { // For search list
		$col_1_width = $obj['review_score'] * 10;
		$col_2_width = 100 - $col_1_width;
		
		$txt = '<div class="item-review-text">';
		$txt .= get_review_text($obj['review_score']);
		$txt .= '<span class="bpv-color-title">'.$obj['review_score'].'</span></div>';
		$txt .= '<div class="item-review-bar center-block">
				<div class="col-1" style="width: '.$col_1_width.'%"></div>
				<div class="col-2" style="width: '.$col_2_width.'%"></div></div>';
		$txt .= '<div class="text-center"><a href="'.$link.'?tab=tab_reviews">';
		$txt .= $obj['review_number'].' '.lang('rev_txt').'</a></div>';
	}
	
	return $txt;
}

function get_review_text($score, $is_icon = false) {
	$score_lang = '';
    $icon = 'icon-review';
    
    if ($score >= 9)
    {
        $score_lang = '<span class="bpv-color-excellent">' . lang('rev_excellent') . '</span>';
    }
    else if ($score >= 8 && $score < 9)
    {
        $score_lang = '<span class="bpv-color-very-good">' . lang('rev_very_good') . '</span>';
        $icon = 'icon-review-very-good';
    }
    else if ($score >= 5 && $score < 8)
    {
        $score_lang = '<span class="bpv-color-average">' . lang('rev_average') . '</span>';
        $icon = 'icon-review-average';
    }
    else if ($score >= 4 && $score < 5)
    {
        $score_lang = '<span class="bpv-color-poor">' . lang('rev_poor') . '</span>';
        $icon = 'icon-review-poor';
    }
    else if ($score < 4)
    {
        $score_lang = '<span class="bpv-color-good">' . lang('rev_terrible') . '</span>';
        $icon = 'icon-review-good';
    }
    
    if ($is_icon)
    {
        return $icon;
    }
    
    return $score_lang;
}

/**
 * Get promotion off
 *
 * @param unknown $obj
 */
function get_pro_off($obj)
{
    $txt = '';

    $off = (($obj['price_origin'] - $obj['price_from']) / $obj['price_origin']) * 100;
    
    $off = round($off, 0);

    if ($off > 0)
    {
        $txt = '-' . $off . '%';
    }

    return $txt;
}

/**
  *  Get tour route as plain text or hyperlink
  *
  *  @author toanlk
  *  @since  Sep 23, 2014
  */
function get_route($tours) 
{
    foreach ($tours as $k => $tour)
    {
        
        /*if (empty($tour['cruise_id']))
        {*/
            $txt = '';
            $route = explode('#', $tour['routes']);
            
            foreach ($route as $value)
            {
                if(empty($value)) continue;
                
                $arr = explode(';', $value);
                if (! empty($arr))
                {
                    $obj = array(
                        'id' => $arr[1],
                        'name' => $arr[0],
                        'url_title' => url_title(convert_unicode($arr[0]), '-', true),
                    );
                    
                    $txt .= '<a target="_blank" href="'.get_url(TOUR_DESTINATION_DETAIL_PAGE, $obj).'">'. $obj['name'] .'</a> - ';
                }
            }
            
            $tour['route'] = trim(rtrim(trim($txt), '-'));
        /*}
        else
        {
            $txt = '';
            $route = explode('-', $tour['routes']);
            
            foreach ($route as $value)
            {
                if(empty($value)) continue;
                
                $arr = explode(';', $value);
                if (! empty($arr))
                {
                    $txt .= $arr[0] . ' - ';
                }
            }
            
            $tour['route'] = trim(rtrim(trim($txt), '-'));
        }*/
        
        $tours[$k] = $tour;
    }
    
    return $tours;
}

/**
 * Khuyenpv: 15.09.2014
 * Get Advertise Link
 */
function generate_advertise_link($ad, $ad_page){
	
	$link = $ad['link'];
	
	if (strpos($link,'utm_content') !== false) {
	    // already specified utm_content: do nothing
	} else {
		
		$CI =& get_instance();
		
		$ad_utm_content = $CI->config->item('ad_utm_content');
		
		$utm_content = isset($ad_utm_content[$ad_page]) ?  $ad_utm_content[$ad_page] : '';

		if (strpos($link,'?') !== false) {
			$link .= '&utm_content='.$utm_content;
		} else {
			$link .= '?utm_content='.$utm_content;
		}
	}
	
	return $link;
}

/**
 * Load modal for showing data overview
 * @return unknown
 */
function load_data_overview_modal(){

	$CI =& get_instance();

	$data_overview = $CI->load->view('common/bpv_data_overview_popup');

	return $data_overview;
}


/**
  *  get image path for everything :-)
  *  
  *  $type: HOTEL, TOUR, CRUISE, DESTINATION
  *
  *  @author toanlk
  *  @since  Sep 23, 2014
  */
function get_image_path($type, $image_name, $size = '')
{
    $directory = '';
    
    $CI =& get_instance();
    
    switch ($type)
    {
        case TOUR:
            $directory = 'tours';
            break;
        case HOTEL:
            $directory = 'hotels';
            break;
        case CRUISE:
            $directory = 'cruises';
            break;
        case CRUISE_TOUR:
            
            if (strpos($image_name, '[cruise]') !== false)
            {
                $image_name = str_replace('[cruise]', '', $image_name);
                $directory = 'cruises';
            }
            else
            {
                $directory = 'tours';
            }
            
            break;
        case DESTINATION:
            $directory = 'destinations';
            break;
        case NEWS_PHOTO:
            $directory = 'news';
            break;
        case USER_PHOTO:
            $directory = 'users';
            break;
    }
    
    // Resource path
    // $resource_path = $CI->config->item('resource_path');
    $resource_path = 'http://bestprice.vn/';
    
    // Photo directory
    $origin_path = 'images/'.$directory.'/uploads/';
    
    $large_path = 'images/'.$directory.'/large/';
    
    $medium_path = 'images/'.$directory.'/medium/';
    
    $small_path = 'images/'.$directory.'/small/';

    // get photo path
    $image_path = $origin_path;
    
    if ($size != '')
    {
        if ($size == '800x600' || $size == '416x312' || $size == '400x300')
        {
            if ($size == '400x300') $size = '416x312';
    
            $image_path = $large_path;
        }
    
        if ($size == '268x201' || $size == '200x150')
        {
            $image_path = $medium_path;
        }
    
        if ($size == '160x120' || $size == '120x90' || $size == '120x80' || $size == '90x90')
        {
            $image_path = $small_path;
        }
    
        $image_names = explode('.', $image_name);
    
        if (count($image_names) > 1)
        {
            $image_name = $image_names[0] . '-' . $size . '.' . $image_names[1];
        }
    }
    
    $image_path = $resource_path . $image_path . $image_name;
    
    return $image_path;
}
