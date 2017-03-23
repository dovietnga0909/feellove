<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function _get_hotel_data($data = array(), $id = null){
	
	$CI =& get_instance();

	if(empty($id)) {
		$id = (int)$CI->uri->segment(NORMAL_ID_SEGMENT);
	}	

	$CI->load->model('Hotel_Model');
	$hotel = $CI->Hotel_Model->get_hotel($id);
	if ($hotel == false) {
		_show_error_page(lang('hotel_notfound'));
		exit;
	}

	$data['hotel'] = $hotel;

	return $data;
}

function _get_partner_info($hotel) {
	
	$CI =& get_instance();
	
	$data['hotel'] = $hotel;
	
	$partner_info = $CI->load->view('hotels/cp/partner_info', $data, TRUE);
	
	return $partner_info;
}

?>