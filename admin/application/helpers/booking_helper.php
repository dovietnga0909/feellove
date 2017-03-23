<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function get_popup_config($page = '') {
	$CI =& get_instance();
	$c_popup = $CI->config->item('popup');
	if ($page == '') {
		$atts = $c_popup['default'];
	} else {
		$atts = $c_popup[$page];
	}
	$att_str = '';
	foreach ($atts as $key => $value) {
		$att_str .= $key . '=' . $value . ',';
	}
	$att_str = substr($att_str, 0, strlen($att_str) - 1);
	return $att_str;
}

function get_option_group_partner($partners) {

	$CI =& get_instance();
	$partner_types = $CI->config->item('partner_types');

	array_push($partner_types, 'Nontype');

	$results = array();
	$cnt = 0;
	foreach ($partner_types as $key => $partner_t) {
		foreach ($partners as $partner) {
			if($partner['service_type'] & $key) {
				$results[lang($partner_t)][] = $partner;
			}
			if (empty($partner['service_type']) && count($partner_types) == $key) {
				$results['Nontype'][] = $partner;
			}
		}
	}

	return array('partners' => $results, 'partner_types' => $partner_types);
}

function is_show_net_sell_profit($user_name, $user_id){
	return true;
}
/*
function is_show_net_sell_profit($user_name, $user_id){
	//return true;
	return is_administrator() || is_accounting() || is_sale_manager() ||  is_dev_team() || is_user_login($user_name) || is_login_user_has_permission_of_cb_user($user_id);
}*/

function is_profit_warning($sel, $net, $actual_profit, $status){

	if ($status == 4 || $status == 6) // fullpaid & closewin
	{

		/*if ($sel == 0){
		 	
		return true;
		}

		$cond1 = $actual_profit/$sel < 0.8 ||  $actual_profit/$sel > 15.0;*/

		$a_p_min = round($sel * 1.03 * (1 - 0.033) - $net, 2) ;

		$a_p_max = round($sel * 1.03 * (1 - 0.0275) - $net, 2);

		return $actual_profit < $a_p_min || $actual_profit > $a_p_max;

	}

	return false;
}

function has_email_data($customer_booking){

	if ($customer_booking['email_id'] > 0) return true;

	if ($customer_booking['status'] == BOOKING_NEW || $customer_booking['status'] == BOOKING_PENDING) return true;

	return false;
}

function format_tip_conntent($content){
	
	$content = str_replace("\"","&quot;",$content);
	
	$content = str_replace("\n", "<br>", $content);
	
	$content = str_replace("\r", "", $content);
	
	return $content;
}

function get_customer_unique_id($cb){
	return $cb['customer_id'].'_'.$cb['start_date'];
}
function get_customer_tip_content($cb){
	
	$content = '* '.$cb['email'].'<br>'.'* '.$cb['phone'].'<br> * '.$cb['city'].'<br> * '.format_tip_conntent($cb['address']);
	
	return $content;
}
function get_sr_tip_content($service_reservation, $cb=''){
	
	$str_description = "";
	
	if ($service_reservation['detail_reservation'] != ""){
			
		$service_reservation['detail_reservation'] = "[i].".$service_reservation['detail_reservation'];
			
	}
	
	$str_description = str_replace("\n", "<br>", $service_reservation['detail_reservation']);
	
	if ($str_description != '') $str_description = $str_description."<br><br>";
	
	if (!empty($service_reservation['flight_pnr'])){
		
		$str_description .= '<b>PNR:</b> '.$service_reservation['flight_pnr'].'<br>';
	}
	
	if (!empty($cb)){
		$str_description .= '<b>Passenger:</b> '.get_passenger_text($cb['adults'], $cb['children'], $cb['infants']).'<br><br>';
	}
	
	if ($service_reservation['description'] != ''){
		
		$str_description = $str_description. "<b>Description: </b><br>";
		
		$str_description .= format_tip_conntent($service_reservation['description']);
		
	}
	
	if(!empty($cb['description'])){
		
		if ($service_reservation['description'] != ''){
		
			$str_description .= '<br>-------------------------------------</br>';
		
		}
		
		$str_description .= "<b>Customer Request:</b><br>";
		
		$str_description .= format_tip_conntent($cb['description']);
	}

	return $str_description;
}

function get_invoice_link($invoice_reference){

	$site_url = site_url();
	$site_url = str_replace("/admin/", "", $site_url);
	$site_url = str_replace("/admin", "", $site_url);
	$site_url = $site_url. '/thanh-toan/hoa-don.html?ref='.$invoice_reference;

	return $site_url;
}

function get_invoice_status_text($status){

	$status_text = '';

	if ($status == INVOICE_NOT_PAID){
		$status_text = "Not Paid";
	}

	if ($status == INVOICE_PENDING){
		$status_text = "Payment Pending";
	}

	if ($status == INVOICE_SUCCESSFUL){
		$status_text = "Payment Successful";
	}

	if ($status == INVOICE_FAILED){
		$status_text = "Payment Failed";
	}

	if ($status == INVOICE_UNKNOWN){
		$status_text = "Payment Unknown";
	}

	return $status_text;

}

function get_passenger_text($adults, $children, $infants){
	$txt = '';

	if($adults > 0){

		$txt = $adults . ' adults';

	}

	if($children > 0){
		$txt .= ', '.$children.' children';
	}

	if($infants > 0){
		$txt .= ', '.$infants.' infants';
	}


	return $txt;
}



