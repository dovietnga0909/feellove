<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function set_paging_info($data, $total_rows, $url) {
	
	$CI =& get_instance();
	
	$offset = $CI->uri->segment(PAGING_SEGMENT);
	
	$paging_config = get_paging_config($total_rows, $url.'/',PAGING_SEGMENT);
	// initialize pagination
	$CI->pagination->initialize($paging_config);
	
	$paging_info['paging_text'] = get_paging_text($total_rows, $offset);
	
	$paging_info['paging_links'] = $CI->pagination->create_links();
	
	$data['paging_info'] = $paging_info;
	
	return $data;
}

function build_search_criteria($module, $data = array()) {
	$CI =& get_instance();
	$search_criteria = array();
	
	$submit_action = $CI->input->post('submit_action');
	
	// access the cancellation tab without search action
	if(empty($submit_action)){
			
		$search_criteria = $CI->session->userdata($module."_SEARCH_CRITERIA");
	
		if(empty($search_criteria)){
	
			$search_criteria = array();
			
		}
			
	} else {
			
		if($submit_action == ACTION_RESET){
	
			$search_criteria = array();
	
		} elseif($submit_action == ACTION_SEARCH){
	
			foreach ($_POST as $key => $value) {
				$value = trim($CI->input->post($key));
				if ($value != "") {
					$search_criteria[$key] = $value;
				}
			}
		}
			
		$CI->session->set_userdata($module."_SEARCH_CRITERIA", $search_criteria);
			
	}
	
	$data['search_criteria'] = $search_criteria;
	
	return $data;
}

function _get_module_config($name) {
	
	$module = array();
	
	switch ($name) {
		case MODULE_HOTEL :
			$module['order_url'] 	= '/hotels/re-order';
			$module['table'] 		= 'hotels';
			$module['mask_name'] 	= 'h.';
			
			break;
			
		case MODULE_ROOM_TYPES :
			$module['order_url'] 	= '/hotels/re-order-room';
			$module['table'] 		= 'room_types';
			$module['mask_name'] 	= 'r.';
			break;
			
		case MODULE_CRUISES :
			$module['order_url'] 	= '/cruises/re-order';
			$module['table'] 		= 'cruises';
			$module['mask_name'] 	= 'c.';
		
			break;
				
		case MODULE_CABINS :
			$module['order_url'] 	= '/cruises/re-order-cabin';
			$module['table'] 		= 'cabins';
			$module['mask_name'] 	= 'cb.';
		
			break;
		case MODULE_TOURS :
			$module['order_url'] 	= '/tours/re-order';
			$module['table'] 		= 'tours';
			$module['mask_name'] 	= 't.';
		
			break;
			
		case MODULE_ACCOMMODATIONS :
			$module['order_url'] 	= '/tours/re-order-accommodation';
			$module['table'] 		= 'accommodations';
			$module['mask_name'] 	= 'a.';
		
			break;
		
		case MODULE_ITINERARY :
			$module['order_url'] 	= '/tours/re-order-itinerary';
			$module['table'] 		= 'itineraries';
			$module['mask_name'] 	= 'i.';
		
			break;
			
		case MODULE_DESTINATION :
			$module['order_url'] 	= '/destinations/re-order';
			$module['table'] 		= 'destinations';
			$module['mask_name'] 	= 'd.';
			break;
			
		case MODULE_FLIGHTS :
			$module['order_url'] 	= '/flights/re-order';
			$module['table'] 		= 'flight_routes';
			break;

		case MODULE_AIRLINES :
			$module['order_url'] 	= '/airlines/re-order';
			$module['table'] 		= 'airlines';
			break;
		
		case MODULE_FLIGHT_CATEGORIES :
			$module['order_url'] 	= '/flight-categories/re-order';
			$module['table'] 		= 'flight_categories';
			break;
			
		case MODULE_FACILITY :
			$module['order_url'] 	= '/facilities/re-order';
			$module['table'] 		= 'facilities';
			$module['mask_name'] 	= 'f.';
			break;
			
		case MODULE_NEWS :
			$module['order_url'] 	= '/news/re-order';
			$module['table'] 		= 'news';
			$module['mask_name'] 	= 'n.';
			break;
			
		case MODULE_TOUR_CATEGORY :
		    $module['order_url'] 	= '/categories/re-order';
		    $module['table'] 		= 'categories';
		    $module['mask_name'] 	= 'tc.';
		    break;
		    
	    case MODULE_TOUR_DEPARTURE :
	        $module['order_url'] 	= '/tours/departure/re-order';
	        $module['table'] 		= 'tour_departures';
	        $module['mask_name'] 	= 'td.';
	        break;
	   	case MODULE_ADVERTISES :
	        $module['order_url'] 	= '/advertises/re-order';
	        $module['table'] 		= 'advertises';
	        $module['mask_name'] 	= 'ad.';
	        break;
	}
	
	return $module;
}

function get_order_arrow($object, $max_pos, $min_pos, $module_name, $custom_param = '', $lstObjects = -1, $pos_name = 'position') {

	$html = '';
	
	if( is_array($lstObjects) && count($lstObjects) == 1) {
		return $html;
	}
	
	$module = _get_module_config($module_name);

	$url_up 	= site_url($module['order_url'] . '?id=' . $object['id']. '&act=' . GO_UP . $custom_param);
	$url_down 	= site_url($module['order_url'] . '?id=' . $object['id']. '&act=' . GO_DOWN . $custom_param);

	$go_up 		= '<a href="' .$url_up. 	'"><span class="fa fa-arrow-circle-up"></span></a>';
	$go_down 	= '<a href="' .$url_down. 	'"><span class="fa fa-arrow-circle-down "></span></a>';

	if($object[$pos_name] == $min_pos) {
		$html = $go_down;
	} else if($object[$pos_name] == $max_pos) {
		$html = $go_up;
	} else {
		$html = $go_up . $go_down;
	}

	return $html;
}

function bp_re_order($id, $action, $module_name, $callback = null, $pos_name = 'position') {

	$CI =& get_instance();

	$CI->db->trans_start();
	
	$module = _get_module_config($module_name);
	$table = $module['table'];
	
	$mask_name = isset($module['mask_name']) ? $module['mask_name'] : '';

	// get object position
	$object = bp_get_object($id, $table, $pos_name);
	
	// validation object and action
	if(empty($object) || !($action == GO_UP || $action == GO_DOWN)) {
		return FALSE;
	}
	
	$short_table_name = str_replace('.', '', $mask_name);
	
	// get sibling
	$CI->db->select($mask_name.'id, '. $mask_name.$pos_name);
	
	if($short_table_name != ''){
		$CI->db->from($table.' as '.$short_table_name);
	} else {
		$CI->db->from($table);
	}
	
	bp_set_search_criteria($module_name, $mask_name, $callback);
	
	//$CI->db->where($mask_name.'deleted !=', DELETED);
	
	if ($action == GO_DOWN) {
		$CI->db->where($mask_name.$pos_name.' > ', $object[$pos_name]);
		$CI->db->order_by($mask_name.$pos_name, 'asc');
	} else if ($action == GO_UP) {
		$CI->db->where($mask_name.$pos_name.' < ', $object[$pos_name]);
		$CI->db->order_by($mask_name.$pos_name, 'desc');
	}

	$CI->db->limit(1);

	$query = $CI->db->get();
	$results = $query->result_array();
	
	//echo $CI->db->last_query();exit();

	if (count($results) > 0) {
		$sibling = $results[0];

		// re-order
		$updated_object = array($pos_name => $sibling[$pos_name]);
		$CI->db->update($table, $updated_object, array('id' => $id));

		$updated_object = array($pos_name => $object[$pos_name]);
		$CI->db->update($table, $updated_object, array('id' => $sibling['id']));
	}

	$CI->db->trans_complete();

	$error_nr = $CI->db->_error_number();

	return !$error_nr;
}


function bp_set_search_criteria($module_name, $mask_name = '', $callback = null) {
	$CI =& get_instance();
	
	switch ($module_name) {
		case MODULE_DESTINATION :
			$data = build_search_criteria(MODULE_DESTINATION);
			$CI->Destination_Model->_set_search_criteria($data['search_criteria'], $mask_name);
			break;
		case MODULE_HOTEL :
			$data = build_search_criteria(MODULE_HOTEL);
			$CI->Hotel_Model->_set_search_criteria($data['search_criteria'], $mask_name);
			break;
		case MODULE_CRUISES :
			$data = build_search_criteria(MODULE_CRUISES);
			$CI->Cruise_Model->_set_search_criteria($data['search_criteria'], $mask_name);
			break;
		case MODULE_TOURS :
		    $data = build_search_criteria(MODULE_TOURS);
		    $CI->Tour_Model->_set_search_criteria($data['search_criteria'], $mask_name);
		    break;
		case MODULE_CABINS :
		    $data = build_search_criteria(MODULE_CABINS);
		    
		    // Callback parameter : additional search_criteria params
		    if (!empty($callback)) {
		        foreach ($callback as $key => $value) {
		            if (!empty($value)) {
		                $data['search_criteria'][$key] = $value;
		            }
		        }
		    }
		    $CI->Cabin_Model->_set_search_criteria($data['search_criteria'], $mask_name);
		    break;
		case MODULE_FACILITY :
			$data = build_search_criteria(MODULE_FACILITY);
			$CI->Facility_Model->_set_search_criteria($data['search_criteria'], $mask_name);
			break;
		case MODULE_FLIGHTS :
			$data = build_search_criteria(MODULE_FLIGHTS);
			$CI->Flight_Model->_set_search_criteria($data['search_criteria'], $mask_name);
			break;

		case MODULE_AIRLINES :
			$data = build_search_criteria(MODULE_AIRLINES);
			$CI->Flight_Model->_set_airline_search_criteria($data['search_criteria'], $mask_name);
			break;
		
		case MODULE_FLIGHT_CATEGORIES :
			$data = build_search_criteria(MODULE_FLIGHT_CATEGORIES);
			$CI->Flight_Model->_set_category_search_criteria($data['search_criteria'], $mask_name);
			break;
			
		case MODULE_NEWS :
			$data = build_search_criteria(MODULE_NEWS);
			$CI->News_Model->_set_search_criteria($data['search_criteria'], $mask_name);
			break;

		case MODULE_ACCOMMODATIONS :
		    $data = build_search_criteria(MODULE_ACCOMMODATIONS);
		    
		    // Callback parameter : additional search_criteria params
		    if (!empty($callback)) {
		        foreach ($callback as $key => $value) {
		            if (!empty($value)) {
		                $data['search_criteria'][$key] = $value;
		            }
		        }
		    }
		    
		    $CI->Accommodation_Model->_set_search_criteria($data['search_criteria'], $mask_name);
		    break;
		
		case MODULE_ITINERARY :
		    $data = build_search_criteria(MODULE_ITINERARY);
		    
		    // Callback parameter : additional search_criteria params
		    if (!empty($callback)) {
		        foreach ($callback as $key => $value) {
		            if (!empty($value)) {
		                $data['search_criteria'][$key] = $value;
		            }
		        }
		    }
		    
		    $CI->Itinerary_Model->_set_search_criteria($data['search_criteria'], $mask_name);
		    break;
		case MODULE_ROOM_TYPES :
			$data = build_search_criteria(MODULE_ROOM_TYPES);
			
			// Callback parameter : additional search_criteria params
			if (!empty($callback)) {
				foreach ($callback as $key => $value) {
					if (!empty($value)) {
						$data['search_criteria'][$key] = $value;
					}
				}
			}
			
			$CI->Room_Type_Model->_set_search_criteria($data['search_criteria'], $mask_name);
			break;
		case MODULE_TOUR_DEPARTURE :
		    $data = build_search_criteria(MODULE_TOUR_DEPARTURE);
		    
		    // Callback parameter : additional search_criteria params
		    if (!empty($callback)) {
		        foreach ($callback as $key => $value) {
		            if (!empty($value)) {
		                $data['search_criteria'][$key] = $value;
		            }
		        }
		    }
		    
		    $CI->Tour_Departure_Model->_set_search_criteria($data['search_criteria'], $mask_name);
		    break;
	    case MODULE_ADVERTISES :
	        $data = build_search_criteria(MODULE_ADVERTISES);
	        	
	        // Callback parameter : additional search_criteria params
	        if (!empty($callback)) {
	            foreach ($callback as $key => $value) {
	                if (!empty($value)) {
	                    $data['search_criteria'][$key] = $value;
	                }
	            }
	        }
	        	
	        $CI->Advertise_Model->_build_search_advertise_condition($data['search_criteria']);
	        break;
	}
}


function bp_get_object($id, $table, $pos_name = 'position') {
	
	$CI =& get_instance();
	$CI->db->select('id, '.$pos_name);
	$CI->db->where('deleted !=', DELETED);
	$CI->db->where('id', $id);
	
	$query = $CI->db->get($table);
	$objects = $query->result_array();
	
	$object = null;
	if (!empty($objects)) {
		$object = $objects[0];
	} 
	
	return $object;
}

function bp_get_max_pos($module_name, $type = 0, $callback = null) {
	
	$CI =& get_instance();
	
	$module = _get_module_config($module_name);
	$table = $module['table'];
	$mask_name = isset($module['mask_name']) ? $module['mask_name'] : '';
	
	if($type == 0) {
		$CI->db->select_max($mask_name.'position');
	} else {
		$CI->db->select_min($mask_name.'position');
	}
	
	bp_set_search_criteria($module_name, $mask_name, $callback);
	
	$CI->db->where($mask_name.'deleted !=', DELETED);
	
	$query = $CI->db->get($table. ' '.str_replace('.', '', $mask_name));
	
	$results = $query->result_array();
	if (!empty($results)) {
	
		return $results[0]['position'];
	}
	
	return 0;
}

function set_max_min_pos($data, $module_name, $callback = null) {
	
	$data['max_pos'] = bp_get_max_pos($module_name, 0, $callback);
	
	$data['min_pos'] = bp_get_max_pos($module_name, 1, $callback);
	
	return $data;
}

function array_to_string($arr, $remove_str = "") {

	$str = '';

	if(!empty($arr)) {
		foreach ($arr as $element) {
			if($element != $remove_str) {
				$str .= $element.'-';
			}
		}

		// add "-" character in the begin of string for searching
		$str = '-'.$str;
	}

	return $str;
}

function init_order($table) {
	$CI =& get_instance();
	
	$CI->db->where('deleted !=', DELETED);
	$CI->db->order_by('name', 'asc');
	
	$query = $CI->db->get($table);
	
	$result = $query->result_array();
	
	foreach ($result as $k => $hotel) {
	
		$updated_hotel = array();
		$updated_hotel['position'] = $k+1;
		$CI->db->update($table, $updated_hotel, array('id' => $hotel['id']));
	}
	
	echo 'Init-oder completed !!!'; exit();
}

function name_register($register){
	if($register ==1){
		
		return lang('news-letter');
		
	}else if($register ==2){
		
		return lang('sign-in');
		
	}else if($register ==3){
		
		return lang('letter-sign');
		
	}else if($register ==0){
		
		return lang('system');
		
	}else{
		
		return $register;
		
	}
}

?>