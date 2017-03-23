<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function count_total_room_rate($rates){
	return array_sum($rates);
}

function count_average_room_rate($rates){
	$total = count_total_room_rate($rates);
	$average = $total /count($rates);
	$average = bpv_round_rate($average);
	return $average;
}

function is_no_cancel_no_refund($cancellation, $startdate){
	
	if($cancellation['id'] == CANCELLATION_NO_REFUND){
		return true;
	} else {
		return is_non_refundable_by_date($cancellation, $startdate);
	}

}

function get_room_conditon_text($cancellation, $startdate, $show_date = false){ 
	
	if(is_no_cancel_no_refund($cancellation, $startdate)){
		
		return '<b>'.lang('no_cancel').'</b>';
	} else {
		
		if($startdate == '' || !$show_date) return '<b>'.lang('free_cancel').'</b>';
		
		$startdate = format_bpv_date($startdate);
		$free_cancel_date = date('d/m/Y', strtotime($startdate . ' -'.$cancellation['fit_cutoff'].'day'));
		
		return ('<b>'.lang('free_cancel_before').' '.$free_cancel_date.'</b>');	
	}
	
}

function get_ids_from_hotel_list($hotels){
	
	$ret = array();
	
	foreach ($hotels as $hotel){
		$ret[] = $hotel['id'];
	}
	
	return $ret;
}

function get_room_rate_id($room_rate){
	$id = $room_rate['id'].'_'.$room_rate['occupancy'].'_';
	
	if(isset($room_rate['promotion'])){
		$id .= $room_rate['promotion']['id'];
	}
	
	return $id;
}

function is_non_refundable_by_date($cancellation, $startdate){
	
	if(empty($cancellation)){
		return false;
	}
	
	$startdate = format_bpv_date($startdate);
	$free_cancel_date = date(DB_DATE_FORMAT, strtotime($startdate . ' -'.$cancellation['fit_cutoff'].'day'));
	
	$today = date(DB_DATE_FORMAT);
	
	return ($today >= $free_cancel_date);
}

function get_room_rate_detail($hotel, $room_rate, $rate){
	
	$mobile_view = is_mobile() ? 'mobile/' : '';
	
	$CI =& get_instance();
	$data['room_rate'] = $room_rate;
	$data['rate'] = $rate;
	$data['hotel'] = $hotel;
	return $CI->load->view($mobile_view.'hotels/hotel_detail/room_rate_detail', $data, true);
}

function get_room_detail($hotel, $room_type){
	
	$mobile_view = is_mobile() ? 'mobile/' : '';
	
	$CI =& get_instance();
	$data['room_type'] = $room_type;
	$data['hotel'] = $hotel;
	return $CI->load->view($mobile_view.'hotels/hotel_detail/room_type_detail', $data, true);
}

function get_surcharge_detail($sur){
	$CI =& get_instance();
	$data['sur'] = $sur;
	return $CI->load->view('hotels/hotel_booking/surcharge_detail', $data, true);
}
function get_surcharge_unit($sur){
	if($sur['charge_type'] == SUR_PER_ADULT_PER_BOOKING) return '1 '.lang('hb_sur_per_adult');
	if($sur['charge_type'] == SUR_PER_NIGHT) return '1 '.lang('hb_sur_per_night');
	if($sur['charge_type'] == SUR_PER_ROOM) return '1 '.lang('hb_sur_per_room');
	if($sur['charge_type'] == SUR_PER_ROOM_PER_NIGHT) return '1 '.lang('hb_sur_per_room').' x 1 '.lang('hb_sur_per_night');
	if($sur['charge_type'] == SUR_PER_ROOM_PRICE) return lang_arg('hb_room_price_charge', $sur['amount']);
	return '';
}

function get_surcharge_apply($sur, $room_pax_total){
	if($sur['charge_type'] == SUR_PER_ADULT_PER_BOOKING) return lang_arg('hb_sur_pax', $room_pax_total['max_adults'], $room_pax_total['max_children']);
	if($sur['charge_type'] == SUR_PER_NIGHT) return count($sur['apply_dates']).' '.lang('hb_sur_per_night');
	if($sur['charge_type'] == SUR_PER_ROOM) return $room_pax_total['nr_rooms'].' '.lang('hb_sur_per_room');
	
	if($sur['charge_type'] == SUR_PER_ROOM_PER_NIGHT){
		return $room_pax_total['nr_rooms'].' '.lang('hb_sur_per_room').' x '.count($sur['apply_dates']).' '.lang('hb_sur_per_night');
	}
	
	if($sur['charge_type'] == SUR_PER_ROOM_PRICE) return lang('hb_total_room_rate').' <b>'.format_currency($room_pax_total['total_room_rate']).'</b>';
	return '';
}

function get_bpv_promotion_desc($bpv_promotion, $type = HOTEL){
	
	$code = $bpv_promotion['code'];
	
	$discount_type = BPV_DISCOUNT_PERCENTAGE;
	$get = '0';
	
	if($type == HOTEL){
		$discount_type = $bpv_promotion['hotel_discount_type'];
		$get = $bpv_promotion['hotel_get'];
	}
	
	if($type == FLIGHT){
		$discount_type = $bpv_promotion['flight_discount_type'];
		$get = $bpv_promotion['flight_get'];
	}
	
	if($discount_type == BPV_DISCOUNT_PERCENTAGE){
		
		$get .= '%';
	}
	
	if($discount_type == BPV_DISCOUNT_AMOUNT){
	
		$get = bpv_format_currency($get, false);
	}
	
	if($discount_type == BPV_DISCOUNT_AMOUNT_TICKET){
	
		$get = bpv_format_currency($get, false).lang('per_ticket');
	}
	
	return lang_arg('bpv_pro_discount', $code, $get);
}
