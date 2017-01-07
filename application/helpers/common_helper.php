<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_selected_menu($menu_id) {
	$CI =& get_instance();
	$current_menu = $CI->session->userdata('MENU');
	$obj = array('class'=> '', 'arrow'=> '');

	if($current_menu == $menu_id) {
		$obj['class'] = 'class="active"';
		$obj['arrow'] = '<span class="arrow-bottom"><span class="arrow-before"></span><span class="arrow-after"></span></span>';
		
		return $obj;
	}
}

/*
* Get static resources from cdn
*
* $file_names		: file name or array of file names
* $custom_folder	: specify folder path
*/
function get_static_resources($file_names, $custom_folder = '', $link_only = false) {

	$CI =& get_instance();

	$content = '';
	$file_type = 0;
	$CSS_FOLDER = 'css/';
	$JS_FOLDER  = 'js/';

	$resource_path = $CI->config->item('resource_path');
	
	$file_names = trim($file_names);

	if(!$link_only) {
		// If specify folder path
		if(!empty($custom_folder)) {
			$CSS_FOLDER = $JS_FOLDER = $custom_folder;
		}

		// --- Check file types

		// CSS, JS
		if(stripos($file_names, '.css') !== false) {
			$file_type = 1;
		} else if(stripos($file_names, '.js') !== false) {
			$file_type = 2;
		}

		// --- Get content
		if($file_type == 1) {
			$files = explode(',', $file_names);

			foreach ($files as $file) {
				$file = trim($file);
				if(empty($file)) continue;

				$full_path = base_url().str_replace("//", "/", '/'.$CSS_FOLDER . $file);
				
				//$full_path = $resource_path.str_replace("//", "/", '/'.$CSS_FOLDER . $file);

				$full_path = '<link rel="stylesheet" type="text/css" href="'.$full_path.'"/>'."\n\t\t";

				$content .= $full_path;
			}
		}if($file_type == 2) {
			$files = explode(',', $file_names);

			foreach ($files as $file) {
				$file = trim($file);
				if(empty($file)) continue;

				$full_path = base_url().str_replace("//", "/", '/'.$JS_FOLDER . $file);
				//$full_path = $resource_path.str_replace("//", "/", '/'.$JS_FOLDER . $file);

				$full_path =  '<script type="text/javascript" src="'.$full_path.'"></script>'."\n\t\t";

				$content .= $full_path;
			}
		}
	}

	if(empty($content))  {
		$content = $resource_path . $file_names;
	}
	

	// replace duplicate splash
	$content = str_replace("//", "/", $content);
	$content = str_replace("http:/", "http://", $content);

	return $content;
}

function get_core_theme($lib_css = null, $lib_js = null, $page_css = null, $page_js = null, $is_mobile = false) {
	
	// bootstrap
	$system_css  =  get_static_resources('bootstrap.min.06052014.css', '/libs/bootstrap-3.1.1/css/');
	
	// jquery-ui css for calendar
	$system_css .= get_static_resources('jquery-ui.min.css', '/libs/jquery-ui-1.11.0.datepicker/');

	$system_css .= $lib_css;
	
	if($is_mobile){
		$system_css .= get_static_resources('mobile.min.101020141539.css','css/mobile/');
	} else {
		$system_css .= get_static_resources('main.min.120220151442.css');
	}


	$system_css .= $page_css;
	
	$system_js 	= get_static_resources('jquery-1.10.2.min.js', '/libs/');
	$system_js .= get_static_resources('bootstrap.min.06052014.js', '/libs/bootstrap-3.1.1/js/');
	
	$system_js .= $lib_js;
	$system_js .= get_static_resources('main.min.280520151453.js');

	
	$system_js .= $page_js;
	
	return $system_css . $system_js;
}

function get_library($name, $data) {
	
	$css_lib = ''; $js_lib = '';

	$store_lib_css = isset($data['lib_css']) ? $data['lib_css'] : '';
	$store_lib_js = isset($data['lib_js']) ? $data['lib_js'] : '';
	
	if($name == 'image-gallery'){
		$css_lib 	= get_static_resources('blueimp-gallery.min.css', '/libs/bs-image-gallery-3.1.0/css/');
		$css_lib    .= get_static_resources('bootstrap-image-gallery.min.css', '/libs/bs-image-gallery-3.1.0/css/');
	}
	
	if($name == 'flexsilder'){
		$css_lib 	= get_static_resources('flexslider.css', '/libs/flexsilder-2.2.0/');
	}
	
	
	$data['lib_css']	= $store_lib_css . $css_lib;
	$data['lib_js'] 	= $store_lib_js. $js_lib;

	return $data;
}

/**
 * Get JS library Asynchronous Links
 * @param unknown $lib_name
 * @param string $callback
 * @return string
 */

function get_libary_asyn_js($lib_name, $callback = ''){
	$js_link = array();
	
	$CI =& get_instance();
	$resource_path = site_url().'/';//$CI->config->item('resource_path');
	
	if($lib_name == 'jquery-ui-datepicker'){
		
		$js_link[] = $resource_path.'libs/jquery-ui-1.11.0.datepicker/jquery-ui.min.js';
		
	}
	
	if($lib_name == 'typehead'){
		$js_link[] = $resource_path.'libs/typeahead.min.06052014.js';
	}
	
	if($lib_name == 'map'){
		$js_link[] = $resource_path.'js/map.min.06082014.js';
	}
	
	if($lib_name == 'google-map-api'){
		
		$link = '//maps.googleapis.com/maps/api/js?sensor=false&language=vi';
		
		if($callback != ''){
			
			$link .= '&callback='.$callback;
		}
		
		
		$js_link[] = $link;
	}
	
	
	if($lib_name == 'image-gallery'){
		
		$js_link[] = $resource_path.'libs/bs-image-gallery-3.1.0/js/jquery.blueimp-gallery.min.js';
		
	}
	
	if($lib_name == 'image-gallery-setup'){
	
		$js_link[] = $resource_path.'libs/bs-image-gallery-3.1.0/js/bootstrap-image-gallery.min.js';
	
	}
	
	if($lib_name == 'flexsilder'){
		
		$js_link[] = $resource_path.'libs/flexsilder-2.2.0/jquery.flexslider-min.js';
		
	}
	
	if($lib_name == 'lofslidernews'){
	
	    $js_link[] = $resource_path.'libs/lofslidernews/lofslidernews.min.js';
	
	}
	
	
	return json_encode($js_link);
}

function get_in_page_theme($page, $data = array(), $is_mobile = false) {
	
    if($is_mobile)
    {
        $cruise_css = 'mobile/cruise.min.300720141414.css';
        $cruise_detail_css = 'mobile/cruise_detail.min.300720141415.css';
        $cruise_js = 'cruise.min.110920141208.js';
        
        $hotel_booking_css = 'mobile/hotel_booking.min.300720141415.css';
        
        $contact_css = 'mobile/contact.min.140220150850.css';
        
        $news_css = 'mobile/news.min.021020141055.css';
        
        $tour_css = 'mobile/tour.min.011020141724.css';
        
        $tour_details_css = 'mobile/tour_detail.min.171020141113.css';
        
        $tour_destination_css = "mobile/destination.min.011020141725.css";
        
        $tour_js = 'tour.min.041120140958.js';
    }
    else 
    {
        $cruise_css = 'cruise.min.130820141526.css';
        $cruise_detail_css = 'cruise_detail.min.261120141540.css';
        $cruise_js = 'cruise.min.110920141208.js';

        $hotel_booking_css = 'hotel_booking.min.261120141541.css';
        
        $contact_css = 'contact.min.060220151011.css';
        
        $news_css = 'news.min.020120151013.css';
        
        $tour_css = 'tour.min.020120151136.css';
        
        $tour_details_css = 'tour_detail.min.291220141102.css';
        
        $tour_destination_css = 'destination.min.16102014.css';
        
        $tour_js = 'tour.min.041120140958.js';
    }
	
	switch ($page) {
		
		case CRUISE_HL_HOME_PAGE:
			$data['page_css'] = get_static_resources($cruise_css);
			$data['page_js'] = get_static_resources($cruise_js);
			break;
			
		case CRUISE_HL_SEARCH_PAGE:
			$data['page_css'] = get_static_resources($cruise_css);
			$data['page_js'] = get_static_resources($cruise_js);
			break;
		
		case CRUISE_HL_DETAIL_PAGE:
			$data['page_css'] = get_static_resources($cruise_detail_css);
			$data['page_js'] = get_static_resources($cruise_js);
			
			if ($is_mobile)
			{
			    $data = get_library('flexsilder', $data);
			}
			else
			{
			    $data = get_library('image-gallery', $data);
			}
			
			break;
			
		case CRUISE_HL_BOOKING_PAGE:
			$data['page_css'] = get_static_resources($hotel_booking_css);
			$data['page_js'] = get_static_resources($cruise_js.','.$tour_js);
			break;
			
		case TOUR_HL_DETAIL_PAGE:
			$data['page_css'] = get_static_resources($cruise_detail_css);
			$data['page_js'] = get_static_resources($cruise_js);
			
			if ($is_mobile)
            {
                $data = get_library('flexsilder', $data);
            }
            else
            {
                $data = get_library('image-gallery', $data);
            }
			
			break;
			
		case TOUR_HOME_PAGE:
		    $data['page_css'] = get_static_resources($tour_css);
		    $data['page_js'] = get_static_resources($tour_js);
		    break;
	    case TOUR_SEARCH_PAGE:
	        $data['page_css'] = get_static_resources($tour_css);
	        $data['page_js'] = get_static_resources($tour_js);
	        break;
        case TOUR_DETAIL_PAGE:
            $data['page_css'] = get_static_resources($tour_css.','.$tour_details_css);
            $data['page_js'] = get_static_resources($tour_js);
            
            if ($is_mobile)
            {
                $data = get_library('flexsilder', $data);
            }
            
            break;
        case TOUR_DESTINATION_DETAIL_PAGE:
        	$data['page_css'] = get_static_resources($tour_css.','.$tour_destination_css);
            $data['page_js'] = get_static_resources($tour_js);
			if ($is_mobile) {
			    $data = get_library('flexsilder', $data);
			} else {
                $data = get_library('image-gallery', $data);
            }
            break;
		case TOUR_DOMESTIC_PAGE:
		    $data['page_css'] = get_static_resources($tour_css);
		    $data['page_js'] = get_static_resources($tour_js);
		    break;
		case TOUR_OUTBOUND_PAGE:
		    $data['page_css'] = get_static_resources($tour_css);
		    $data['page_js'] = get_static_resources($tour_js);
		    break;    
		case TOUR_DESTINATION_PAGE:
		    $data['page_css'] = get_static_resources($tour_css);
		    $data['page_js'] = get_static_resources($tour_js);
		    break;
	    case NEWS_HOME_PAGE:
	        $data['page_css'] = get_static_resources($news_css);
	        break;
		case THANK_YOU_PAGE:
		    $data['page_css'] = get_static_resources($contact_css);
		    break;
	}
	
	return $data;
}

function getDefaultDate() {
	return date(DATE_FORMAT_JS);
}

function build_search_criteria($data, $module = null, $is_mobile  = false) {
	$CI =& get_instance();
	
	$search_criteria = array();
	
	$des = isset($data['destination']) ? $data['destination'] : '';
	
	if ($module == MODULE_CRUISE)
    {
        
        // $data['cruise_list'] = $CI->Cruise_Model->get_all_cruises();
        
        $data['duration_search'] = $CI->config->item('duration_search');
        
        $data['cruise_search_criteria'] = get_cruise_search_criteria($des);
        
        $data['popular_cruises'] = $CI->Cruise_Model->get_all_halong_bay_cruises();
    }
    elseif ($module == MODULE_TOUR)
    {
        $data['duration_search'] = $CI->config->item('tour_duration_search');
        
        $data['tour_search_criteria'] = get_land_tour_search_criteria();
        
        $data['departure_destinations'] = $CI->Land_Tour_Model->get_tour_departing_from();
        
        $data['domestic_destinations'] = $CI->Land_Tour_Model->get_tour_departure_destinations();
        
        $data['outbound_destinations'] = $CI->Land_Tour_Model->get_tour_departure_destinations(true);
        
        $data['destination_suggestion'] = $CI->load->view('tours/common/destination_suggestion', $data, TRUE);
    }
    else
    {
        
        $data['max_nights'] = $CI->config->item('max_nights');
        
        $view = 'common/bpv_search';
        
        // Hotel
        $data['hotel_search_criteria'] = get_hotel_search_criteria($des);
        
        $data['suggestion_destinations'] = $CI->Hotel_Model->get_top_hotel_destinations();
        
        // Flight
        $data['flight_search_criteria'] = get_flight_search_criteria($data);
        
        $data['flight_destinations'] = $CI->Flight_Model->get_all_flight_destinations();
        
        // Tour
        $data['duration_search'] = $CI->config->item('tour_duration_search');
        
        $data['tour_search_criteria'] = get_land_tour_search_criteria();
        
        $data['departure_destinations'] = $CI->Land_Tour_Model->get_tour_departing_from();
        
        $data['domestic_destinations'] = $CI->Land_Tour_Model->get_tour_departure_destinations();
        
        $data['outbound_destinations'] = $CI->Land_Tour_Model->get_tour_departure_destinations(true);
        
        $data['destination_suggestion'] = $CI->load->view('tours/common/destination_suggestion', $data, TRUE);
    }
	
	switch ($module) {
		case MODULE_FLIGHT:
			
			if($is_mobile){
			
				$view = 'mobile/common/bpv_search_flight';
			}
			
			$data['search_small'] = true;
			$data['search_criteria'] = $data['flight_search_criteria'];
			
			break;
		case MODULE_FLIGHT_DESTINATION:
			$view = 'flights/flight_search/flight_search_form';
			
			if($is_mobile){
				$view = 'mobile/flights/flight_search/flight_search_form';
			}
			
			$data['search_criteria'] = $data['flight_search_criteria'];

			break;
		case MODULE_HOTEL:
			
			if($is_mobile){
				
				$view = 'mobile/common/bpv_search_hotel';
			}
			
			$data['search_small'] = true;
			$data['search_criteria'] = $data['hotel_search_criteria'];
			
			break;
		case MODULE_HOTEL_DESTINATION:
			
			if($is_mobile){
			
				$view = 'mobile/common/bpv_search_hotel';
			}
			
			$data['search_small'] = true;
			$data['search_criteria'] = $data['hotel_search_criteria'];
			
			break;
		case MODULE_CRUISE:
		    if($is_mobile){
		        $view = 'mobile/cruises/common/bpv_search';
		    } else {
		        $view = 'cruises/common/bpv_search';
		    }
			
			$data['search_criteria'] = $data['cruise_search_criteria'];
			
			break;
		case MODULE_TOUR:
		    if($is_mobile){
		        $view = 'mobile/tours/common/bpv_search';
		    } else {
		        $view = 'tours/common/bpv_search';
		    }
		    	
		    $data['search_criteria'] = $data['tour_search_criteria'];
		    	
		    break;
		default:
			$data['search_criteria'] = $data['hotel_search_criteria'];
			
			break;
	}
	
	$data['bpv_search'] = $CI->load->view($view, $data, TRUE);
	
	return $data;
}


function check_bpv_date($str){
	
	$ret = false;
	
	$str_arr = explode('/', $str);
	
	if(count($str_arr) == 3){
		
		return checkdate($str_arr[1], $str_arr[0], $str_arr[2]);
	}
	
	return $ret;
}

function format_bpv_date($str, $format = DB_DATE_FORMAT, $is_show_week_day = false){
	
	$CI =& get_instance();
	
	$str = str_replace('/', '-', $str);
	
	$wd = date('w', strtotime($str));
	
	$str = date($format, strtotime($str));
	
	if($is_show_week_day){
		
		$week_days = $CI->config->item('week_days');
		
		$wd = $week_days[$wd];
		
		$str = lang($wd).', '.$str;
	
	}
	
	return $str;	
}

function bpv_round_rate($rate){
	$rate = $rate/1000;
	$rate = round($rate) * 1000;
	return $rate;
}
function bpv_format_currency($rate, $small_end = true){
	
	$rate = bpv_round_rate($rate);
	
	if($rate <= 0) return '<span>0 ' .lang('vnd').'</span>';
	
	$rate = number_format($rate);
	$rate = str_replace(',', '.', $rate);
	
	$rate_2 = substr($rate, -3);
	
	$rate_1 = substr($rate, 0, strlen($rate) - 3);
	
	if($small_end){
		$rate = '<span>'.$rate_1.'<small>'.$rate_2.' '.lang('vnd').'</small></span>';
	} else {
		$rate = '<span>'.$rate_1.''.$rate_2.' '.lang('vnd').'</span>';
	}
	
	return $rate;
}


function save_recent_data($value, $type = MODULE_HOTEL, $start_date = null, $end_date = null) {
	
	$CI =& get_instance();
	
	$recent_items = $CI->session->userdata(RECENT_ITEMS);
	
	if (empty($recent_items)) $recent_items = array();
	if ( empty($recent_items['hotel']) ) $recent_items['hotel'] = array();
	if ( empty($recent_items['flight']) ) $recent_items['flight'] = array();
	if ( empty($recent_items['cruise']) ) $recent_items['cruise'] = array();
	
	if ($type == MODULE_HOTEL) {
		
		if (count($recent_items['hotel']) <= MAX_RECENT_ITEMS) {
			$new_item = array(
					'id' 			=> uniqid(),
					'type' 			=> $type,
					'start_date' 	=> $start_date,
					'end_date' 		=> $end_date,
					'hotel_id' 		=> $value['id'],
			);
			
			$recent_items['hotel'][] = $new_item;
		}

	} elseif ($type == MODULE_CRUISE) {
		
		if (count($recent_items['cruise']) <= MAX_RECENT_ITEMS) {
			$new_item = array(
					'id' 			=> uniqid(),
					'type' 			=> $type,
					'start_date' 	=> $start_date,
					'end_date' 		=> $end_date,
					'cruise_id' 	=> $value['id'],
			);
				
			$recent_items['cruise'][] = $new_item;
		}
	} else {
		
		if (count($recent_items['flight']) <= MAX_RECENT_ITEMS) {
			$new_item = array(
					'id' 			=> uniqid(),
					'type' 			=> $type,
					'start_date' 	=> $start_date,
					'end_date' 		=> $end_date,
					'from' 			=> $value['from'],
					'to' 			=> $value['to'],
					'from_name' 	=> $value['from_name'],
					'to_name' 		=> $value['to_name'],
			);
			
			$recent_items['flight'][] = $new_item;
		}
	}

	$CI->session->set_userdata(RECENT_ITEMS, $recent_items);
}

function remove_recent_item($item_id) {
	
	$CI =& get_instance();
	
	$recent_items = $CI->session->userdata(RECENT_ITEMS);
	
	if (!empty($recent_items)) {
		
		$updated_recent_items = array();
		
		if( !empty($recent_items['hotel']) ) {
			foreach ($recent_items['hotel'] as $item) {
				if($item['id'] != $item_id) {
					$updated_recent_items['hotel'][] = $item;
				}
			}
		}
		
		if( !empty($recent_items['flight'])) {
			foreach ($recent_items['flight'] as $item) {
				if($item['id'] != $item_id) {
					$updated_recent_items['flight'][] = $item;
				}
			}
		}
		
		if( !empty($recent_items['cruise'])) {
			foreach ($recent_items['cruise'] as $item) {
				if($item['id'] != $item_id) {
					$updated_recent_items['cruise'][] = $item;
				}
			}
		}
		
		$CI->session->set_userdata(RECENT_ITEMS, $updated_recent_items);
	}
	
}

function is_bit_value_contain($bit_value, $nr_index){

	$nr = pow(2,$nr_index) & $bit_value;

	return $nr > 0;
}

function get_days_between_2_dates($start_date, $end_date){

	$start_date = date(DB_DATE_FORMAT, strtotime($start_date));

	$end_date = date(DB_DATE_FORMAT, strtotime($end_date));
	 
	if($start_date > $end_date) return array();
	 
	$ret[] = $start_date;

	$tmp_date = $start_date;

	while ($tmp_date < $end_date){
		 
		$tmp_date = date(DB_DATE_FORMAT, strtotime($tmp_date. ' +1 day'));
		 
		$ret[] = $tmp_date;
		 
	}

	return $ret;
}

function get_paging_config($total_rows, $uri, $uri_segment) {

	$CI =& get_instance();
	
	$is_mobile = is_mobile();
	
	$paging_config = $is_mobile ? $CI->config->item('paging_config_mobile') : $CI->config->item('paging_config');

	$config['base_url'] = site_url($uri);
	$config['total_rows'] = $total_rows;
	$config['per_page'] = $paging_config['per_page'];
	$config['uri_segment'] = $uri_segment;
	$config['num_links'] = $paging_config['num_links'];
	
	$config['page_query_string'] = true;
	$config['query_string_segment'] = 'page';
	

	//$config['first_link'] = $CI->lang->line('common_paging_first');
	//$config['next_link'] = $CI->lang->line('common_paging_next');
	//$config['prev_link'] = $CI->lang->line('common_paging_previous');
	//$config['last_link'] = $CI->lang->line('common_paging_last');

	// for boostrap pagingnation
	if($is_mobile){
		$config['full_tag_open'] = '<ul class="pagination">';
	} else {
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
	}
	
	$config['full_tag_close'] = '</ul>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';

	$config['first_link'] = $CI->lang->line('common_paging_first');
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';

	$config['last_link'] = $CI->lang->line('common_paging_last');
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';

	$config['next_link'] = $CI->lang->line('common_paging_next');
	$config['next_tag_open'] = '<li>';
	$config['next_tag_close'] = '</li>';

	$config['prev_link'] = $CI->lang->line('common_paging_previous');
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';

	$config['cur_tag_open'] = '<li class="active"><span>';
	$config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';


	return $config;
}

function get_paging_text($total_rows, $offset, $lang_result = 'common_paging_result') {
	$CI =& get_instance();
	
	$paging_config = $CI->config->item('paging_config');

	$paging_text = $CI->lang->line('common_paging_display');
	$next_offset = $offset + $paging_config['per_page'];
	if ($next_offset > $total_rows) {
		$next_offset = $total_rows;
	}
	$paging_text .= ' ' . ($offset + 1)
	. ' - ' . $next_offset
	. ' ' . $CI->lang->line('common_paging_of') . ' '
			. $total_rows .' '.lang($lang_result);
	return $paging_text;
}

function fit_content_shortening($str, $maxLength, $allow_tags = true) {
	$content_length = strlen($str);
	if($allow_tags) {
		$content_length = strlen(strip_tags($str));
	}

	// Hidden content length > maxLength/3
	if ($content_length < $maxLength)
	{
		return false;
	}

	if ($content_length > $maxLength && ($content_length - $maxLength) < round($maxLength/3))
	{
		return false;
	}

	return true;
}

function content_shorten($str, $maxLength, $allow_tags = true, $end_char = '&#8230;') {

	if(!fit_content_shortening($str, $maxLength, true)) {
		return $str;
	}

	$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

	if (strlen($str) <= $maxLength)
	{
		return $str;
	}

	$out = "";
	foreach (explode(' ', trim($str)) as $val)
	{
		$out .= $val.' ';

		$content_length = strlen($out);
		
		if($allow_tags) {
			// a bug with strip_tags function on break html tag
			$content_length = strlen(strip_tags($out));
		}

		if ($content_length >= $maxLength)
		{
			$out = trim($out);
			return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
		}
	}

	return $str;
}

function format_phone_number($str) {
	
	$str = preg_replace("/[^0-9]/","",$str);
	return $str;
}

function get_page_meta($meta) {
	
	$content  = '<meta http-equiv="content-type" content="text/html; charset=utf-8" />'."\n\t\t";
	
	$content  .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">'."\n\t\t";
	
	if (!empty($meta)) {
		$content .= '<meta name="robots" content="'.$meta['robots'].'" />'."\n\t\t";
		$content .= '<meta name="description" content="'.$meta['description'].'" />'."\n\n\t\t";
	}
	
	$title = !empty($meta['title']) ? $meta['title'] : lang('default_title');
	
	$title .=' - Bestviettravel.xyz';
	
	$content .= '<title>'.$title.'</title>'."\n\t\t";
	
	if(!empty($meta['canonical'])){
		$content .= $meta['canonical']."\n\t\t";
	}
	
	return $content;
}

/**
 * Apply Bank Fee for each booking
 */
function apply_bank_fee_online_payment($amount, $booking_type = FLIGHT, $payment_method  = PAYMENT_METHOD_DOMESTIC_CARD){
	
	$CI =& get_instance();
	
	if($booking_type == FLIGHT){
		
		$fee = 0;
		
		if($payment_method == PAYMENT_METHOD_DOMESTIC_CARD){
			$fee = $CI->config->item('do_card_fee');
		}
		
		if($payment_method == PAYMENT_METHOD_CREDIT_CARD){
			$fee = $CI->config->item('in_card_fee');
		}
		
		if(empty($fee)) $fee = 0;
		
		$amount = round($amount * (1 + $fee/100));
		
	}
	
	if($booking_type == HOTEL){
		
		// process later
	}

	return $amount;
}

function convert_unicode($str) {

	// lower case
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
	$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
	$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
	$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
	$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
	$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
	$str = preg_replace("/(đ)/", 'd', $str);

	// upper case
	$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
	$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
	$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
	$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
	$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
	$str = preg_replace("/(Đ)/", 'D', $str);

	return $str;
}

/**
 * Check suggestions 
 * 
 * @param array $suggestions
 * @param string $keyword
 * @param array $stopwords
 */
function is_first_match($suggestions, $keyword, $stopwords = null) {
	
	$is_match = false;
	
	if(count($suggestions) > 1) {
		
		// remove accents
		$first_result = strtolower(trim(convert_unicode($suggestions[0]['name'])));
		
		$term = strtolower(trim(convert_unicode($keyword)));
		
		// ignore stopwords
		if (!empty($stopwords)) {
			foreach ($stopwords as $word) {
				$first_result = str_replace($word, '', $first_result);
				$term = str_replace($word, '', $term);
			}
		}
		
		// compare
		if($first_result == $term) {
			$is_match = true;
		}
		
	} else if(count($suggestions) == 1) {
		$is_match = true;
	}
	
	return $is_match;
}

/**
 * Remove vietnamese tones in search term or add full text search operators
 * 
 * @param unknown $term
 * @param string $with_operator
 * @return string
 */
function search_term_pre_process($term, $with_operator = false, $with_stopwords = true)
{
    $stopwords = array(
        'khach san',
        'khachsan'
    );
    
    // remove vietnamese tones
    $term = convert_unicode($term);
    
    $term = trim(strtolower($term));
    
    // replace two or more whitespace with a single space
    $term = preg_replace('/\s+/', ' ', $term);
    
    // eliminate stopwords
    if (! $with_stopwords)
    {
        foreach ($stopwords as $word)
        {
            
            if ($term != $word && strpos($term, $word) !== false)
            {
                $term = str_replace($word, '', $term);
            }
        }
    }
    
    // with boolean operator
    if ($with_operator)
    {
        $new_term = '';
        
        $strs = explode(' ', $term);
        
        foreach ($strs as $str)
        {
            $new_term = $new_term . ' *' . $str . '*';
        }
        $term = trim($new_term);
    }
    
    $term = trim(strtolower($term));
    
    return $term;
}

/**
 * Get best match result in suggestion data (hotel, cruise)
 */
function is_best_match($results, $term, $with_stopwords = true)
{
    $term = search_term_pre_process($term, false, $with_stopwords);
    
    foreach ($results as $result)
    {
        
        $keywords = explode(',', $result['keywords']);
        
        foreach ($keywords as $keyword)
        {
            if (trim($term) == trim($keyword))
            {
                return true;
            }
        }

    }
    
    return false;
}


function set_cache_html(){
	
	$CI =& get_instance();

	$CI->output->cache($CI->config->item('cache_html'));
}

function is_mobile(){
	
	$CI =& get_instance();
	
	$mobile_on_off = $CI->input->get('mobile');
	
	if(!empty($mobile_on_off)){
		$CI->session->set_userdata(MOBILE_ON_OFF, $mobile_on_off);
	}
	
	$mobile_on_off = $CI->session->userdata(MOBILE_ON_OFF);
	if(empty($mobile_on_off)) $mobile_on_off = 'on';
	
	$mobile_detect = new Mobile_Detect();
	$is_mobile = ($mobile_detect->isMobile() && !$mobile_detect->isTablet()) && $mobile_on_off == 'on';
	return $is_mobile;
}

function is_new_visitor()
{
    $CI = & get_instance();
    $key = $CI->session->userdata('visitor_key');

    if ( empty($key) )
    {
        $key = uniqid();
        $CI->session->set_userdata('visitor_key', $key);
        return true;
    }
    return false;
}

/**
 * Khuyenpv 17.09.2014
 * Replace the link on the text based on specific format
 * <des id="des-id">Dest Name</des>
 * @param unknown $str
 * @return mixed
 */
function insert_data_link($str){
	
	$is_mobile = is_mobile();

	if(!empty($str)){
		$str = str_replace('&lt;', '<', $str);
		$str = str_replace('&gt;', '>', $str);
		
		$CI =& get_instance();
	
		$CI->load->library('simple_html_dom');
	
		$html = str_get_html($str);
		
		// replace destination overview
		$des_tags = $html->find("des");
		
		foreach ($des_tags as $tag){
	
			$id = $tag->id;
			
			if($is_mobile){
				
				$destination = $CI->Destination_Model->get_destination($id);
			
				$tag->href = get_url(TOUR_DESTINATION_DETAIL_PAGE, $destination);
				
			}else{
			
				$tag->href = 'javascript:void(0)';
	
				$tag->onclick = "show_link_data_overview(". $id . ",'des')";
			}
			$tag->id = null;
	
		}
	
	
		// replace cruise overview
		$cruise_tags = $html->find('cruise');
	
		foreach ($cruise_tags as $tag){
	
			$id = $tag->id;
			
			if($is_mobile){
				
				$cruise = $CI->Cruise_Model->get_cruise_detail($id);
			
				$tag->href = get_url(CRUISE_HL_DETAIL_PAGE, $cruise);
				
			}else{
	
				$tag->href = 'javascript:void(0)';
		
				$tag->onclick = "show_link_data_overview(". $id . ",'cruise')";
		
				$tag->id = null;
			}
	
		}
	
	
		// replace hotel overview
		$hotel_tags = $html->find('hotel');
	
		foreach ($hotel_tags as $tag){
	
			$id = $tag->id;
			
			if($is_mobile){
				
				$hotel = $CI->Hotel_Model->get_hotel_detail($id);
			
				$tag->href = get_url(HOTEL_DETAIL_PAGE, $hotel);
				
			}else{
	
				$tag->href = 'javascript:void(0)';
		
				$tag->onclick = "show_link_data_overview(". $id . ",'hotel')";
		
				$tag->id = null;
			}
	
		}
	
	
		// replace hotel overview
		$tour_tags = $html->find('tour');
	
		foreach ($tour_tags as $tag){
	
			$id = $tag->id;
			
			if($is_mobile){
				
				$hotel = $CI->Hotel_Model->get_hotel_detail($id);
			
				$tag->href = get_url(HOTEL_DETAIL_PAGE, $hotel);
				
			}else{
	
				$tag->href = 'javascript:void(0)';
		
				$tag->onclick = "show_link_data_overview(". $id . ",'tour')";
		
				$tag->id = null;
			}
	
		}
		
		
		$str = strval($html);
	
		$str = str_replace('<des', '<a', $str);
		$str = str_replace('</des>', '</a>', $str);
	
		$str = str_replace('<cruise', '<a', $str);
		$str = str_replace('</cruise>', '</a>', $str);
	
		$str = str_replace('<hotel', '<a', $str);
		$str = str_replace('</hotel>', '</a>', $str);
	
		$str = str_replace('<tour', '<a', $str);
		$str = str_replace('</tour>', '</a>', $str);
	}
	
	return $str;
}

function get_str_bsetween($content,$start,$end){
	$r = explode($start, $content);
	if (isset($r[1])){
		$r = explode($end, $r[1]);
		return $r[0];
	}
	return '';
}