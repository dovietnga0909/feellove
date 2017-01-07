<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Bpt login Helper
 * - Support  load sign-up and load sign-in popup
 */

// ------------------------------------------------------------------------

/**
 * load_sign_up_popup
 * 
 */
function load_sign_up_popup($btn_popup, $type = 'groupon', $custom_text = ''){

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
	
	$contact_popup = $CI->load->view('login/sign_up', $data, TRUE);

	return $contact_popup;
}

/**
 * load_sign_in_popup
 * 
 */
function load_sign_in_popup($btn_popup, $type = 'groupon', $custom_text = ''){

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
	
	$contact_popup = $CI->load->view('login/sign_in', $data, TRUE);

	return $contact_popup;
}
  