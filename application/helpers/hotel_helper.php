<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Get search data posted from search form
 */
function hotel_build_search_criteria(){
	
	$CI =& get_instance();
	
	$destination = $CI->input->get_post('destination', true);
	
	$start_date = $CI->input->get_post('startdate', true);
	
	$end_date = $CI->input->get_post('enddate', true);
	
	$night = $CI->input->get_post('night', true);
	
	$oid = $CI->input->get_post('oid', true);
	
	$sort = $CI->input->get_post('sort', true);
	$price = $CI->input->get_post('price', true);
	$star = $CI->input->get_post('star', true);
	$area = $CI->input->get_post('area', true);
	$facility = $CI->input->get_post('facility', true);
	$page = $CI->input->get_post('page', true);
	
	
	$search_criteria['destination'] = $destination;
	
	$search_criteria['startdate'] = $start_date;
	
	$search_criteria['enddate'] = $end_date;
	
	$search_criteria['night'] = $night;
	
	$search_criteria['oid'] = $oid;
	
	if($sort != '') $search_criteria['sort'] = $sort;
	if($price != '') $search_criteria['price'] = $price;
	if($star != '') $search_criteria['star'] = $star;
	if($area != '') $search_criteria['area'] = $area;
	if($facility != '') $search_criteria['facility'] = $facility;
	if($page != '') $search_criteria['page'] = $page;

	
	$CI->session->set_userdata(HOTEL_SEARCH_CRITERIA, $search_criteria);
	
	return $search_criteria;
}

function get_hotel_search_criteria($des=''){
	
	$CI =& get_instance();
	
	// get from session
	$search_criteria = $CI->session->userdata(HOTEL_SEARCH_CRITERIA);
	
	// set default value
	if(empty($search_criteria)){
		
		$today = date('d-m-Y');
		$tommorow = date('d/m/Y', strtotime($today . " +1 day"));
		$after_tommorow = date('d/m/Y', strtotime($today . " +2 day"));
		
		$search_criteria = array();
		
		$search_criteria['startdate'] = $tommorow;
		$search_criteria['night'] = 1;
		$search_criteria['enddate'] = $after_tommorow;
		
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

function update_search_criteria_by_checkrate($startdate, $night, $enddate){
	
	$CI =& get_instance();
	
	$search_criteria = get_hotel_search_criteria();
	
	if(!empty($startdate) && !empty($night) && !empty($enddate)){
		$search_criteria['startdate'] = $startdate;
		$search_criteria['night'] = $night;
		$search_criteria['enddate'] = $enddate;
	}
	
	$CI->session->set_userdata(HOTEL_SEARCH_CRITERIA, $search_criteria);
	
	return $search_criteria;
	
}

function is_correct_hotel_search($search_criteria){
	
	if(empty($search_criteria['destination']) || trim($search_criteria['destination']) == ''){
		return false;
	}
	
	if(empty($search_criteria['startdate']) || !check_bpv_date($search_criteria['startdate'])){
		return false;
	}
	
	if(empty($search_criteria['enddate']) || !check_bpv_date($search_criteria['enddate'])){
		return false;
	}
	
	if(empty($search_criteria['night'])){
		return false;
	}
	
	return true;
}

function has_hotel_search_filter($search_criteria){
	return !empty($search_criteria['price']) || !empty($search_criteria['star']) || !empty($search_criteria['area']) || !empty($search_criteria['facility']);
}

function hotel_get_selected_filter($search_criteria, $filter_name, $filter_values){
	
	if(!empty($search_criteria[$filter_name])){
		
		$filter_selected = $search_criteria[$filter_name];
		
		$filter_selected = explode(',', $filter_selected);
		
		foreach ($filter_values as $key => $value){
			
			if(in_array($value['value'], $filter_selected)){
				
				$value['selected'] = true;
				
				$filter_values[$key] = $value;
				
			}
			
		}
		
	}
	
	return $filter_values;
	
}

function hotel_build_url($hotel, $search_criteria = array()){

	$url = site_url('khach-san/'.$hotel['url_title'].'-'.$hotel['id'].'.html');
	
	if(count($search_criteria) > 0){
		
		if(!empty($search_criteria['night']) && !empty($search_criteria['startdate']) && !empty($search_criteria['enddate'])){
			
			if(!isset($search_criteria['is_default']) || !$search_criteria['is_default']){
			
				$check_rate_info['startdate'] = $search_criteria['startdate'];
				
				$check_rate_info['night'] = $search_criteria['night'];
				
				$check_rate_info['enddate'] = $search_criteria['enddate'];
				
				if(!empty($search_criteria['action'])){
					$check_rate_info['action'] = $search_criteria['action'];
				}
				
				$url = $url.'?'.http_build_query($check_rate_info);
			
			}
			
		}
	
	}
	
	return $url;
}

function hotel_search_destination_url($destination, $search_criteria = array()){
	$url = site_url(HOTEL_SEARCH_PAGE);
	
	if(count($search_criteria) > 0){
		unset($search_criteria['destination']);
		unset($search_criteria['oid']);
		$url = $url.'#destination='.$destination['name'].'&'.http_build_query($search_criteria).'&oid=d-'.$destination['id'];
	}
	
	return $url;
}

function hotel_booking_url($hotel, $search_criteria){
	
	$url = site_url(HOTEL_BOOKING_PAGE.$hotel['url_title'].'-'.$hotel['id'].'.html');
	
	$params['startdate'] = $search_criteria['startdate'];
	
	$params['night'] = $search_criteria['night'];
	
	$params['enddate'] = $search_criteria['enddate'];
	
	$url = $url.'?'.http_build_query($params);
	
	return $url;
}

function get_hotel_overview_txt_1($search_criteria){
	
	$txt = lang('hotel_at').' '.$search_criteria['destination'];
	
	if(isset($search_criteria['selected_des'])){
		
		$selected_des = $search_criteria['selected_des'];
		
		if($selected_des['type'] > DESTINATION_TYPE_AREA){
			$txt = lang('hotel_near');
		} else {
			$txt = lang('hotel_at');
		}
		
		$txt .= ' '.$selected_des['name'];
	}
	
	
	return $txt;
}

function get_hotel_overview_txt_2($search_criteria){

	$txt = lang('hotel_search_overview');

	$txt = str_replace('<night>', $search_criteria['night'], $txt);
	
	$txt = str_replace('<start>', $search_criteria['startdate'], $txt);
	
	$txt = str_replace('<end>', $search_criteria['enddate'], $txt);
	
	return $txt;
}

function hotel_get_search_result_txt($search_criteria, $count, $selected_des){
	
	if($selected_des['type'] > DESTINATION_TYPE_AREA){
		$txt = lang('hotel_found_near');
	} else {
		$txt = lang('hotel_found_at');
	}

	$txt = str_replace('<des>', $search_criteria['destination'], $txt);
	$txt = str_replace('<nr>', $count, $txt);
	$txt = str_replace('<start>', $search_criteria['startdate'], $txt);
	$txt = str_replace('<end>', $search_criteria['enddate'], $txt);

	return $txt;
}

function sql_join_hotel_promotion($startdate){
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

function sql_join_hpf($startdate){
	$str_cond = '('.
		' SELECT hpf2.id FROM `hotel_price_froms` as hpf2 '.
		' LEFT OUTER JOIN promotions as p ON p.id = hpf2.promotion_id AND '.sql_join_hotel_promotion($startdate).
		' WHERE hpf2.hotel_id = h.id AND hpf2.date = "'.$startdate.'"'.
		' ORDER BY (IF(p.id IS NOT NULL, hpf2.price_from, hpf2.price_origin))'.
		' LIMIT 1'.
	')';
	
	return $str_cond;
}

/**
 * HOTEL DETAIL HELPER FUNCTIONS
 *
 */

function get_hotel_selected_date_txt($search_criteria){
	$txt = '';
	if(!isset($search_criteria['is_default'])  || !$search_criteria['is_default']){
	
		$txt = lang('hotel_selected_date');
		
		$txt = str_replace('<start>', format_bpv_date($search_criteria['startdate'], DATE_FORMAT, true), $txt);
		
		$txt = str_replace('<night>', $search_criteria['night'], $txt);
		
		$txt = str_replace('<end>', format_bpv_date($search_criteria['enddate'], DATE_FORMAT, true), $txt);
	
	}

	return $txt;
}

function get_hotel_check_rate_info(){
	
	$CI =& get_instance();
	
	$start_date = $CI->input->get_post('startdate', true);
	
	$end_date = $CI->input->get_post('enddate', true);
	
	$night = $CI->input->get_post('night', true);
	
	if(!empty($start_date) && !empty($night) && !empty($end_date)){
		
		$check_rate_info['startdate'] = $start_date;
		
		$check_rate_info['enddate'] = $end_date;
		
		$check_rate_info['night'] = $night;
		
		$check_rate_info['is_default'] = false;
		
	} else {
		
		$search_criteria = get_hotel_search_criteria();
		
		$check_rate_info['startdate'] = $search_criteria['startdate'];
		
		$check_rate_info['enddate'] = $search_criteria['enddate'];
		
		$check_rate_info['night'] = $search_criteria['night'];
		
		$check_rate_info['is_default'] = true;
		
	}
	
	return $check_rate_info;
	
}

function get_room_type_max_person($room_type, $is_children = true){
	
	$occupancy = isset($room_type['occupancy']) ? $room_type['occupancy'] : $room_type['max_occupancy'];
	
	$txt = '';
	
	if($occupancy > 0){
		$txt .= $occupancy.' '.lang('adult_label');
	}
	
	if($room_type['max_children'] > 0 && $is_children){
		$txt .= ' + '.$room_type['max_children'].' '.lang('children_label');
	}
	
	return $txt;
	
}

function get_room_type_occupancy_text($room_type){

	$occupancy = isset($room_type['occupancy']) ? $room_type['occupancy'] : $room_type['max_occupancy'];

	$txt = '';

	if($occupancy > 0){
		$txt = lang_arg('occupancy_text', $occupancy);
	}

	return $txt;

}

function get_room_type_square_m2($room_type){
	
	$txt = lang('m2_label');
	
	$txt .= ' '.$room_type['minimum_room_size']. 'm<sup>2</sup>';
	
	$CI =& get_instance();
	
	$bed_configuration = $CI->config->item('bed_configuration');
	
	if($room_type['bed_config'] != ''){
		$is_first = true;
		foreach ($bed_configuration as $key=>$value){
			if($room_type['bed_config'] & pow(2,$key)){
				if($is_first){
					$txt .= ', '. lang($value);
					$is_first = false;
				} else {
					$txt .= ' ' . lang('or_label').' '. lang($value);
				}
			}
		}
	}
	
	return $txt;
}

function get_room_rate_name($room_rate){

	$name = $room_rate['name'];
	
	$name_lower = strtolower($name);
	
	$sell_rates = $room_rate['sell_rate'];
	
	$max_occupancy = $room_rate['max_occupancy'];
	
	if($room_rate['occupancy'] == SINGLE && strpos($name_lower, 'single') === false){
		if(!empty($sell_rates[DOUBLE]) || !empty($sell_rates[TRIPLE]) || ($max_occupancy > TRIPLE && !empty($sell_rates[$max_occupancy]))){
			$name .= ' Single';
		}
	}
	
	if($room_rate['occupancy'] == DOUBLE && strpos($name_lower, 'double') === false && strpos($name_lower, 'twin') === false){
		// only show Doule/Twin if the room has single or triple price
		if(!empty($sell_rates[SINGLE]) || !empty($sell_rates[TRIPLE]) || ($max_occupancy > TRIPLE && !empty($sell_rates[$max_occupancy]))){
			$name .= ' Double/Twin';
		}
	}
	
	if($room_rate['occupancy'] == TRIPLE && strpos($name_lower, 'triple') === false){
		if(!empty($sell_rates[SINGLE]) || !empty($sell_rates[DOUBLE]) || ($max_occupancy > TRIPLE && !empty($sell_rates[$max_occupancy]))){
			$name .= ' Triple';
		}
	}
	
	return $name;
}

function get_breakfast_vat_txt($room_rate){
	$txt = lang('include_text');
	
	if($room_rate['included_breakfast']){
		$txt .= ' '.lang('include_breakfast');
		
		if($room_rate['included_vat']){
			
			$txt .= ', '.lang('include_vat');
		}
		
		$txt .= ' '. lang('include_and'). ' '.lang('include_service_fee');
		
	}elseif($room_rate['included_vat']){
		
		$txt .= ' '.lang('include_vat');
		
		$txt .= ' '. lang('include_and'). ' '.lang('include_service_fee');
		
	}else{
		$txt .= ' '.lang('include_service_fee');
	}
	
	$txt .= '.';
	
	if(!$room_rate['included_breakfast'] || !$room_rate['included_vat']){
		
		$txt .= ' '.lang('not_include_text');
		
		if(!$room_rate['included_breakfast']){
			
			$txt .= ' '.lang('include_breakfast');
			
		}
		
		if(!$room_rate['included_vat']){

			if(!$room_rate['included_breakfast']){
				$txt .= ' '. lang('include_and'). ' ';
			}
			
			$txt .= ' '.lang('include_vat');
				
		}
		
		$txt .= '.';
		
	}

	return $txt;
}

/**
 * Get important facility of Hotel
 */
function get_important_hotel_facility($hotel, $facilities){
	
	$important_facilities = array();
	
	$hotel_facilities = explode('-', $hotel['facilities']);
	
	$hotel_facilities = array_unique($hotel_facilities);
	
	foreach ($hotel_facilities as $id){
		
		foreach ($facilities as $value){
			
			if($value['id'] == $id && $value['is_important'] > 0){
				$important_facilities[] = $value;
			}
			
		}
		
	}
	
	$hotel['important_facilities'] = $important_facilities;
	
	return $hotel;
}

function is_root_destination($destination) {
	
	if ($destination['type'] == DESTINATION_TYPE_CITY 
			|| $destination['type'] == DESTINATION_TYPE_DISTRICT 
			|| $destination['type'] == DESTINATION_TYPE_AREA) {
		return true;
	} else {
		return false;
	}
}

/**
 * Get css and js
 * 
 * @param string $map_api
 * @return mixed
 */
function get_page_theme($data = array(), $is_mobile = false) {
	
	if($is_mobile){
		
		$data = get_library('flexsilder', $data);
		
		$data['page_css'] = get_static_resources('hotel.min.30072014.css','css/mobile/');
		
	} else {
		
		$data['page_css'] = get_static_resources('hotel.min.260620141105.css');
		
	}
	
	$data['page_js'] = get_static_resources('hotel.min.30072014.js');
	
	return $data;
}

function get_hotel_pro_value($hotel){	

	if(!empty($hotel['promotions'])){

		$pro = $hotel['promotions'][0];
		
		if($pro['get_1'] == 0) return '';
		
		if($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT){
			
			return lang_arg('deal_value_off', $pro['get_1']);
			
		}
	
		if($pro['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT){
			return lang_arg('deal_value_free_night', $pro['minimum_stay'], $pro['minimum_stay'] - $pro['get_1']);
		}
		
		if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_BOOKING){
			return lang_arg('deal_value_off', bpv_format_currency($pro['get_1']));
		}
		
		if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_NIGHT){
			return lang_arg('deal_value_amount_dc_night', bpv_format_currency($pro['get_1']));
		}
	}
	
	return '';
}

