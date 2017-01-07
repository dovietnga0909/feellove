<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function _get_cruise_data($data = array(), $id = null){
	
	$CI =& get_instance();

	if(empty($id)) {
		$id = (int)$CI->uri->segment(NORMAL_ID_SEGMENT);
	}	

	$CI->load->model('Cruise_Model');
	$cruise = $CI->Cruise_Model->get_cruise($id);
	if ($cruise == false) {
		_show_error_page(lang('cruise_notfound'));
		exit;
	}

	$data['cruise'] = $cruise;

	return $data;
}

function is_cruise_detail() {
	$CI =& get_instance();
	$current_menu = $CI->session->userdata('MENU');

	if($current_menu == MNU_CRUISE_PROFILE
		|| $current_menu == MNU_CRUISE_SURCHARGE || $current_menu == MNU_CRUISE_PROMOTION
		|| $current_menu == MNU_CRUISE_REVIEWS
		|| $current_menu == MNU_CRUISE_CONTRACT) {
		
		return true;
	}

	return false;
}

function _get_cruise_partner_info($cruise) {

    $CI =& get_instance();

    $data['cruise'] = $cruise;

    $partner_info = $CI->load->view('cruises/cp/partner_info', $data, TRUE);

    return $partner_info;
}

?>