<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function _load_cruise_cabin_rates($tour_id, $cruise_id, $check_rate_info, $startdate, $enddate){

	$CI =& get_instance();
	
	$startdate = format_bpv_date($startdate);
	$enddate = format_bpv_date($enddate);
	
	$accommodations = $CI->Tour_Model->get_accommodations($tour_id);
	
	$cruise_cabins = $CI->Cruise_Model->get_cruise_cabins($cruise_id, true);
	
	foreach ($accommodations as $k => $acc) {
		if( !empty($acc['cruise_cabin_id'])) {
			foreach ($cruise_cabins as $cabin) {
				if($cabin['id'] == $acc['cruise_cabin_id']) {
					$acc['cabin'] = $cabin;
	
					$accommodations[$k] = $acc;
				}
			}
		}
	}
	
	$accommodation_rates = $CI->Tour_Model->get_accommodation_rates($tour_id, $startdate, $enddate);

	$tour_promotions = $CI->Tour_Model->get_tour_promotions($tour_id, $startdate, $enddate);
	$default_cancellation = $CI->Tour_Model->get_cancellation_of_tour($tour_id);
	$non_refundable_cancellation = $CI->Tour_Model->get_cancellation_by_id(CANCELLATION_NO_REFUND);

	$accommodation_rates = _calculate_accommodation_rates($check_rate_info, $accommodations, $accommodation_rates, $tour_promotions, $default_cancellation, $non_refundable_cancellation);

	return $accommodation_rates;
}

function _calculate_accommodation_rates($check_rate_info, $accommodations, $accommodation_rates, $tour_promotions, $default_cancellation, $non_refundable_cancellation){

	$ret = array();

	$departure_date = format_bpv_date($check_rate_info['startdate']);
	
	$num_adult = $check_rate_info['adults'];
	$num_children = $check_rate_info['children'];
	$num_infant = $check_rate_info['infants'];
	
	foreach ($accommodations as $k => $accommodation) {
		
		$accom_rate = _get_accommodation_rate_in_date($accommodation['id'], $accommodation_rates, $departure_date, $check_rate_info);
		
		$accommodation['adult_rate']		= $accommodation['adult_basic_rate'] = $accom_rate['adult_rate'];
		
		$accommodation['children_rate'] 	= $accommodation['children_basic_rate'] = $accom_rate['children_rate'];
		
		$accommodation['infant_rate'] 		= $accommodation['infant_basic_rate'] = $accom_rate['infant_rate'];
		
		$accommodation['single_sup_rate'] 	= $accommodation['single_sup_basic_rate'] = $accom_rate['single_sup_rate'];
		
		// Rate = adults_price + children_price + infants_price + single_sup(* if it's avaiable)
		
		$basic_rate	=  ($accom_rate['adult_rate'] * $num_adult);
		$basic_rate += ($accom_rate['children_rate'] * $num_children) + ($accom_rate['infant_rate'] * $num_infant);
		$basic_rate += $accom_rate['single_sup_rate'];
		
		$pro_apply = array();
		
		if( !empty($tour_promotions) ) {
				
			foreach ($tour_promotions as $pro) {
				
				$pro_rate = _calculate_tour_promotion_rate($basic_rate, $pro, $departure_date, $accommodation, $num_adult, $num_children, $num_infant);
				
				if( !empty($pro_rate) ){
					$pro_rt = $pro_rate['accommodation'];
					
					$pro_rt['sell_rate'] = $pro_rate['sell_rate'];
					
					$pro_rt['promotion'] = $pro;
					
					$pro_rt['basic_rate'] = $basic_rate;
					
					$can['id'] = $pro['cancellation_id'];
					$can['name'] = $pro['cancellation_name'];
					$can['fit'] = $pro['fit'];
					$can['fit_cutoff'] = $pro['fit_cutoff'];
					$can['content'] = $pro['content'];
					
					$pro_rt['cancellation'] = $can;
					
					$ret[] = $pro_rt;
					
					$pro_apply[] = $pro;
				}
			}
		}
		
		$accommodation['basic_rate'] = $basic_rate;
		
		$accommodation['sell_rate'] = $basic_rate;
		
		$accommodation['cancellation'] = $default_cancellation;
		
		if( !_has_tour_promotion_same_condition($default_cancellation, $pro_apply) ) {
			$ret[] = $accommodation;
		}
	}

	return $ret;
}

/**
 * _calculate_tour_promotion_rate
 * 
 * Promotions apply for adults only
 * 
 */
function _calculate_tour_promotion_rate($basic_rate, $pro, $departure_date, $accommodation, $num_adult, $num_children, $num_infant) {
	
	//$pro_rate['sell_rate'] = $basic_rate;
	$pro_rate = null;
	
	foreach ($pro['accommodations'] as $value) {
		
		//if($value['accommodation_id'] == $accommodation['id'] && !empty($value['get'])) { accept 0% off toanlk 11/02/2015
	    if($value['accommodation_id'] == $accommodation['id']) {
			
			$accommodation['adult_rate']	= bpv_round_rate($accommodation['adult_rate'] * (100 - $value['get'])/100);
			
			//$accommodation['children_rate']	= bpv_round_rate($accommodation['children_rate'] * (100 - $value['get'])/100);
			
			//$accommodation['infant_rate'] 	= bpv_round_rate($accommodation['infant_rate'] * (100 - $value['get'])/100);
			
			$accommodation['single_sup_rate'] = bpv_round_rate($accommodation['single_sup_rate'] * (100 - $value['get'])/100);
			
			$sell_rate	=  ($accommodation['adult_rate'] * $num_adult);
			$sell_rate += ($accommodation['children_rate'] * $num_children) + ($accommodation['infant_rate'] * $num_infant);
			$sell_rate += $accommodation['single_sup_rate'];
			
			$pro_rate['sell_rate'] = $sell_rate;
			
			$pro_rate['accommodation'] = $accommodation;
			break;
		}
	}
	
	return $pro_rate;
}


function _has_tour_promotion_same_condition($default_cancellation, $tour_promotions) {
	$ret = false;
	
	if(!empty($default_cancellation)){
	
		foreach ($tour_promotions as $pro){
			if($pro['cancellation_id'] == $default_cancellation['id']) return true;
		}
	
	}
	
	return $ret;
}


function _get_accommodation_rate_in_date($accommodation_id, $accommodation_rates, $departure_date, $check_rate_info) {
	
	$adults = $check_rate_info['adults'];
	$children = $check_rate_info['children'];
	$infants = $check_rate_info['infants'];

	foreach ($accommodation_rates as $value){
		
		if($value['date'] == $departure_date && $value['accommodation_id'] == $accommodation_id) {
			
			$adult_rate = $adults < 5 ? $value[$adults.'_pax_rate'] : $value['5_pax_rate'];

			$single_sup_rate = ($adults%2) == 0 ? 0 : $value['single_sup_rate'];
			
			
			return array(
					'adult_rate' 		=> $adult_rate,
					'children_rate' 	=> $value['children_rate'],
					'infant_rate' 		=> $value['infant_rate'],
					'single_sup_rate' 	=> $single_sup_rate,
			);
		}
			
	}

	return null;

}

function get_cruise_surcharge_unit($sur){
	if($sur['charge_type'] == SUR_PER_ADULT_PER_BOOKING) return lang('cb_sur_per_pax_per_booking');
	if($sur['charge_type'] == SUR_PER_ROOM_PRICE) return lang('cb_sur_percentage_per_total');
	return '';
}

function get_cruise_surcharge_apply_for($check_rate_info, $sur) {
	
	$txt = $check_rate_info['adults'].' '.strtolower(lang('adult_label'));
	
	if(!empty($check_rate_info['children']) 
		&& ( !empty($sur['children_amount']) || $sur['charge_type'] == SUR_PER_ROOM_PRICE ) ) {
		
		$txt .= ', '.$check_rate_info['children'].' '.strtolower(lang('children_label'));
	}
	
	return $txt;
}

function get_tour_rate_id($tour_rate){
	$id = $tour_rate['id'].'_';

	if(isset($tour_rate['promotion'])){
		$id .= $tour_rate['promotion']['id'];
	}

	return $id;
}

function is_apply_surcharge($surcharge, $startdate, $enddate) {
	
	$stay_dates = get_days_between_2_dates($startdate, $enddate);
	
	foreach ($stay_dates as $s_date){
	
		if($s_date >= $surcharge['start_date'] && $s_date <= $surcharge['end_date']){
				
			if(is_bit_value_contain($surcharge['week_day'], date('w',strtotime($s_date)))){

				return true;

			}
				
		}

	}
	
	return false;
}