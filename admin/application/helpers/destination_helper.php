<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function _get_destination_data($data = array(), $id = null){
	
	$CI =& get_instance();
	
	if(empty($id)) {
	    $id = (int)$CI->uri->segment(NORMAL_ID_SEGMENT);
	}

	$destination = $CI->Destination_Model->get_destination($id);
	if ($destination == false) {
		
		_show_error_page(lang('destination_notfound'));
		exit;
	}

	$data['destination'] = $destination;
	
	$nav_panel = $CI->config->item('nav_panel');
	
	foreach ($nav_panel as $k => $mnu) {
		
		$mnu['link'] = $mnu['link'].'/'.$data['destination']['id'];
		$nav_panel[$k] = $mnu;
	}
	
	$data['nav_panel'] = $nav_panel;

	return $data;
}


function get_type_name($type_id) {
	$CI =& get_instance();

	$destination_types = $CI->config->item('destination_types');

	foreach ($destination_types as $type) {
		if(is_array($type['value'])) {
			
			foreach ($type['value'] as $key => $value) {
				if($key == $type_id) {
					return lang($value);
				}
			}
			
		} else {
			if($type['value'] == $type_id) {
				return lang($type['label']);
			}
		}
	}

	return '';
}

?>