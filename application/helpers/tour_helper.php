<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function sql_join_tour_promotion($startdate){
	$today = date(DB_DATE_FORMAT);

	// calculate day-before-staying date
	$today_time = strtotime($today);

	$stay_time = strtotime($startdate);

	$day_before = $stay_time - $today_time;

	$day_before =  round($day_before/(60*60*24));

	$str_cond = '(p.book_date_from <= "'.$today .'" AND p.book_date_to >= "'.$today.'")';

	$str_cond .= ' AND (p.stay_date_from <= "'.$startdate .'" AND p.stay_date_to >= "'.$startdate.'")';

	$str_cond .= ' AND (p.display_on & '.pow(2,date('w',strtotime($today))).' > 0)';

	$str_cond .= ' AND (p.check_in_on & '.pow(2,date('w',strtotime($startdate))).' > 0)';

	$str_cond .= ' AND (p.promotion_type = ' . PROMOTION_TYPE_CUSTOMIZED . ' OR (p.promotion_type = '.PROMOTION_TYPE_EARLY_BIRD.' AND p.day_before_check_in <= '.$day_before.')'.

			' OR (p.promotion_type = '.PROMOTION_TYPE_LAST_MINUTE.' AND p.day_before_check_in >= '.$day_before.'))';

	$str_cond .= ' AND p.deleted != 1';

	//$str_cond .= ' AND p.show_on_web = 1';

	return $str_cond;
		
}

function get_tour_check_rate_info(){

	$CI =& get_instance();

	$start_date = $CI->input->get_post('startdate', true);

	$end_date = $CI->input->get_post('enddate', true);
	
	$adults = $CI->input->get_post('adults', true);
	
	$children = $CI->input->get_post('children', true);
	
	$infants = $CI->input->get_post('infants', true);
	
	$tour_id = $CI->input->get_post('tour_id', true);

	if( !empty($start_date)) {

		$check_rate_info['startdate'] = $start_date;

		$check_rate_info['enddate'] = $end_date;
		
		$check_rate_info['adults'] = $adults;
		
		if( !empty($children)) {
			$check_rate_info['children'] = $children;
		}
		
		if( !empty($infants)) {
			$check_rate_info['infants'] = $infants;
		}

		
		$check_rate_info['tour_id'] = $tour_id;

		$check_rate_info['is_default'] = false;

	} else {

		$search_criteria = get_tour_search_criteria();

		$check_rate_info['startdate'] = $search_criteria['startdate'];

		$check_rate_info['enddate'] = $search_criteria['enddate'];
		
		$check_rate_info['adults'] = $search_criteria['adults'];
		
		$check_rate_info['children'] = $search_criteria['children'];
		
		$check_rate_info['infants'] = $search_criteria['infants'];
		
		if(!empty($search_criteria['tour_id'])) {
			$check_rate_info['tour_id'] = $search_criteria['tour_id'];
		} else {
			$check_rate_info['tour_id'] = null;
		}
		
		$check_rate_info['is_default'] = true;
	}
	
	if( empty($check_rate_info['adults'])) {
		$check_rate_info['adults'] = 2;
	}

	return $check_rate_info;

}

function get_tour_search_criteria($des=''){

	$CI =& get_instance();

	// get from session
	$search_criteria = $CI->session->userdata(CRUISE_SEARCH_CRITERIA);

	// set default value
	if(empty($search_criteria)){

		$today = date('d-m-Y');
		$tommorow = date('d/m/Y', strtotime($today . " +1 day"));
		$after_tommorow = date('d/m/Y', strtotime($today . " +2 day"));

		$search_criteria = array();

		$search_criteria['startdate'] = $tommorow;
		$search_criteria['duration'] = 0;
		$search_criteria['enddate'] = $after_tommorow;
		
		$search_criteria['adults'] = 2;
		$search_criteria['children'] = 0;
		$search_criteria['infants'] = 0;

		$search_criteria['destination'] = '';
		$search_criteria['oid'] = '';

		$search_criteria['is_default'] = true;

	} else {
		$search_criteria['is_default'] = false;
	}

	if(!empty($des)){
		$search_criteria['destination'] = $des['name'];
		$search_criteria['oid'] = 'd-'.$des['id'];
			
		$search_criteria['selected_des'] = $des;
	}

	return $search_criteria;

}

function cruise_tour_build_url($tour, $search_criteria = array()){

	$url = site_url(TOUR_HL_DETAIL_PAGE.$tour['url_title'].'-'.$tour['id'].'.html');

	if(count($search_criteria) > 0){
		
		if(!isset($search_criteria['is_default']) || !$search_criteria['is_default']){
				
			$check_rate_info['startdate'] = $search_criteria['startdate'];

			if(!empty($search_criteria['adults'])) {
				$check_rate_info['adults'] = $search_criteria['adults'];
			}
			
			if(!empty($search_criteria['children'])) {
				$check_rate_info['children'] = $search_criteria['children'];
			}
			
			if(!empty($search_criteria['infants'])) {
				$check_rate_info['infants'] = $search_criteria['infants'];
			}
			
			if(!empty($search_criteria['enddate'])) {
				$check_rate_info['enddate'] = $search_criteria['enddate'];
			}

			if(!empty($search_criteria['action'])){
				$check_rate_info['action'] = $search_criteria['action'];
			}

			$url = $url.'?'.http_build_query($check_rate_info);
				
		}
	}

	return $url;
}

function get_tour_selected_date_txt($search_criteria, $tour){
	$txt = '';
	if(!isset($search_criteria['is_default'])  || !$search_criteria['is_default']){

		$txt = lang('tour_selected_date');

		$txt = str_replace('<start>', format_bpv_date($search_criteria['startdate'], DATE_FORMAT, true), $txt);

		$txt = str_replace('<duration>', $tour['duration'], $txt);

		$txt = str_replace('<end>', format_bpv_date($search_criteria['enddate'], DATE_FORMAT, true), $txt);

	}

	return $txt;
}

function get_tour_info($check_rate_info) {
	
	$txt = $check_rate_info['adults'].' '. lang('adult_label');
	
	if (!empty($check_rate_info['children'])) {
		$txt .= ', '.$check_rate_info['children'].' '. lang('children_label');
	}
	
	if (!empty($check_rate_info['infants'])) {
		$txt .= ', '.$check_rate_info['infants'].' '. lang('infant_label');
	}
	
	return $txt;
}

function get_tour_meals($txtMeals) {
	if (!empty($txtMeals)) {
		$CI =& get_instance();
		$c_config = $CI->config->item('tour_meals');
		$str = '';
		$meals = explode('-', $txtMeals);
		foreach ($meals as $meal) {
			if(!empty($meal) && isset($c_config[$meal])) {
				$str .= $c_config[$meal] . ' / ';
			}
		}
		$str = substr($str, 0, strlen($str) - 3);
		return $str;
	}

	return '';
}