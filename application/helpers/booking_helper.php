<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_contact_post_data(){
	
	$CI =& get_instance();
	
	$customer['gender'] = $CI->input->post('gender');
	
	$customer['full_name'] = $CI->input->post('name');
	
	$customer['email'] = $CI->input->post('email');
	
	$c_day = $CI->input->post('day');
	
	$c_month = $CI->input->post('month');
	
	$c_year = $CI->input->post('year');
	
	if ($c_day != '' && $c_month != '' && $c_year != ''){
		$customer['birthday'] = date(DB_DATE_FORMAT, mktime(0,0,0, $c_month, $c_day, $c_year));
	}
	
	$customer['phone'] = $CI->input->post('phone');
	
	$customer['ip_address'] = $CI->input->ip_address();
	
	$city = $CI->input->post('city');
	
	if($city != ''){
	
		$customer['destination_id'] = $CI->input->post('city');
	
	}
	
	$customer['address'] = $CI->input->post('address');
	
	$customer['special_request'] = $CI->input->post('special_request');
	
	return $customer;
}

function get_hotel_customer_booking($hotel_id, $startdate, $enddate, $customer_id, $special_request, $payment_info, $code_discount_info){
	
	$CI =& get_instance();
	$CI->load->model('Booking_Model');
	
	$customer_booking['user_id'] = ADMIN_USER_ID;// assign to admin by default
	
	$customer_booking['hotel_id'] = $hotel_id;
	
	$customer_booking['customer_id'] = $customer_id;
	
	$customer_booking['request_date'] = date(DB_DATE_TIME_FORMAT);
	$customer_booking['date_created'] = date(DB_DATE_TIME_FORMAT);
	$customer_booking['date_modified'] = date(DB_DATE_TIME_FORMAT);
	
	$customer_booking['start_date'] = format_bpv_date($startdate, DB_DATE_FORMAT);
	
	$customer_booking['end_date'] = format_bpv_date($enddate, DB_DATE_FORMAT);
	
	$customer_booking['booking_site'] = is_mobile() ? SITE_MOBILE_BESTPRICE_VN : SITE_BESTPRICE_VN;
		
	$customer_booking['request_type'] = REQUEST_TYPE_RESERVATION;
		
	$customer_booking['customer_type'] = $CI->Booking_Model->get_customer_type($customer_id);
	
	$customer_booking['special_request'] = $special_request;
	
	$booking_desc = '';
	
	if(!empty($code_discount_info)){
		
		if($code_discount_info['pro_type'] == 2){ // 2 means Voucher Code
			$booking_desc = 'Voucher Code: ';
		} else {
			$booking_desc = 'Promotion Code: ';	
		}
		
		$booking_desc .= $code_discount_info['code'];
		
		$booking_desc .= ' - Discount: ';
		
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT){
			$booking_desc .= number_format($code_discount_info['get']). 'VND';
		}
		
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_PERCENTAGE){
			$booking_desc .= $code_discount_info['get'].' %';
		}
		
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT_TICKET){
			$booking_desc .= $code_discount_info['get']. lang('per_ticket');
		}
		
		$booking_desc .= "\n";
		
	}
	
	if($payment_info['method'] == PAYMENT_METHOD_BANK_TRANSFER && !empty($payment_info['bank'])){
		$booking_desc .= 'Bank selected: ' . $payment_info['bank'];
		$booking_desc .= "\n";
	}
	
	$booking_desc .= $special_request;
	
	
	$customer_booking['description'] = $booking_desc;
	
	$customer_booking['payment_method'] = $payment_info['method'];
	
	return $customer_booking;
	
}

function get_room_conditon_content($room_rate, $startdate){

	$cond = lang('hb_max_stay').': '.get_room_type_max_person($room_rate);

	if($room_rate['included_breakfast']){
		$cond .= '<br>'.lang('breakfast_included');
	}

	$cond .= '<br>'.get_room_conditon_text($room_rate['cancellation'], $startdate, true);

	$cond .= '<br>'.$room_rate['cancellation']['content'];

	return $cond;
}

function get_hotel_service_reservations($hotel, $startdate, $enddate, $selected_room_rates, $surcharges, $code_discount_info){
	
	$CI =& get_instance();
	
	$service_reservations = array();
	
	$room_index = 0;
	foreach ($selected_room_rates as $value){
		for ($i = 0; $i < $value['nr_room']; $i++){
			$room_index++;
			
			$room_rate = $value['room_rate_info'];
			
			$occupancy = $room_rate['occupancy'];
			
			$basic_rate = $room_rate['basic_rate'][$occupancy];
			
			$sell_rate = $value['sell_rate'];
			
			$total_rate_origin = count_total_room_rate($basic_rate);
			
			$total_rate = count_total_room_rate($sell_rate);
	
			$sr['start_date'] = format_bpv_date($startdate, DB_DATE_FORMAT);
			$sr['end_date'] = format_bpv_date($enddate, DB_DATE_FORMAT);
			$sr['date_created'] = date(DB_DATE_FORMAT);
			$sr['date_modified'] = date(DB_DATE_FORMAT);
			
			$extra_bed_nr = $CI->input->post($room_index.'_extra_bed_'.get_room_rate_id($room_rate));
		
			$sr['service_id'] = $hotel['id']; // hotel id
			
			
			$price_note = '';
			if($occupancy == SINGLE){
				$price_note = "Single";
			}
			if($occupancy == DOUBLE){
				$price_note = "Double/Twin";
			}
			if($occupancy == TRIPLE){
				$price_note = "Triple";
			}
			if($occupancy > TRIPLE){
				$price_note = "Full Occupancy";
			}
			
			
			
			$sr['service_name'] = 'Room '.$room_index.': '.$room_rate['name'];
			$sr['service_name'] .= ' - '.$price_note;
			
			if($extra_bed_nr > 0){
				$sr['service_name'] .= ' - '.$extra_bed_nr.' extra bed';
			}
			
			$sr['service_name'] .= ' - '.$hotel['name'];
			
			
			$sr['partner_id'] = $hotel['partner_id'];
				
			$sr['selling_price'] = $total_rate; // set later
			
			$sr_desc = 'Destination: '.$hotel['destination_name'];
			$sr_desc .= "\n".'Hotel: '.$hotel['name'];
			$sr_desc .= "\n".'Room: '.$room_rate['name'].' - '.$price_note.' - '.number_format($total_rate);
			
			if($extra_bed_nr > 0){
				$extrabed_rate = count_total_room_rate($room_rate['extrabed_rate']);
				
				$total_rate = $total_rate + $extra_bed_nr * $extrabed_rate;
				
				$sr['selling_price'] = $total_rate;
				
				$sr_desc .= "\n".$extra_bed_nr.' Extra-bed: '.number_format($extra_bed_nr * $extrabed_rate);
			}
		
			$sr['reservation_type'] = RESERVATION_TYPE_HOTEL;
				
			$sr['destination_id'] = $hotel['destination_id'];
			
			$sr['condition'] = get_room_conditon_content($room_rate, $startdate);
			
			$sr['description'] = $sr_desc;
			
			
			$service_reservations[] = $sr;
		}
	}
	
	if(!empty($surcharges)){
		
		foreach ($surcharges as $sur){
			
			$sr['start_date'] = format_bpv_date($startdate, DB_DATE_FORMAT);
			$sr['end_date'] = format_bpv_date($enddate, DB_DATE_FORMAT);
			$sr['date_created'] = date(DB_DATE_FORMAT);
			$sr['date_modified'] = date(DB_DATE_FORMAT);
			
			$sr['service_id'] = $hotel['id']; // hotel room type id
			$sr['service_name'] = $sur['name'];
			$sr['partner_id'] = $hotel['partner_id'];
			
			$sr['selling_price'] = $sur['total_charge']; 
			
			$sr['reservation_type'] = RESERVATION_TYPE_HOTEL;
			$sr['destination_id'] = $hotel['destination_id'];

			// already creating description from load surchage function
			$sr['description'] = $sur['sr_desc'];
			
			$service_reservations[] = $sr;
		}
	
	}
	
	if(!empty($code_discount_info)){
		
		$total_booking = 0;
		
		foreach ($service_reservations as $sr){
			$total_booking += $sr['selling_price'];
		}
		
		$code_discount = calculate_pro_code_discount($code_discount_info, $total_booking);
		
		$sr['start_date'] = format_bpv_date($startdate, DB_DATE_FORMAT);
		$sr['end_date'] = format_bpv_date($enddate, DB_DATE_FORMAT);
		$sr['date_created'] = date(DB_DATE_FORMAT);
		$sr['date_modified'] = date(DB_DATE_FORMAT);
		
		if($code_discount_info['pro_type'] == 2){
			$sr['service_name'] = 'Voucher Code '.$code_discount_info['code'];
		} else {
			$sr['service_name'] = 'Promotion Code '.$code_discount_info['code'];
		}
		
		$sr['partner_id'] = 1; // Best Price Vietnam Partner ID
			
		$sr['selling_price'] = 0 - $code_discount;
			
		$sr['reservation_type'] = RESERVATION_TYPE_OTHER;
		$sr['destination_id'] = $hotel['destination_id'];
			

		$sr_desc = 'Discount: ';
			
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT){
			$sr_desc .= number_format($code_discount_info['get']). 'VND';
		}
			
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_PERCENTAGE){
			$sr_desc .= $code_discount_info['get'].' %';
		}
			
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT_TICKET){
			$sr_desc .= $code_discount_info['get']. lang('per_ticket');
		}
		
		if(!empty($code_discount_info['discount_note'])){
			$sr_desc .= "\n".$code_discount_info['discount_note'];
		}
		
		$sr['description'] = $sr_desc;
			
		$service_reservations[] = $sr;
		
		
	}
	
	return $service_reservations;
	
}

/**
 * Get CB for contact booking
 * @param unknown $customer_id
 * @param unknown $special_request
 * @return unknown
 */

function get_contact_customer_booking($customer_id, $special_request){

	$CI =& get_instance();
	$CI->load->model('Booking_Model');

	$customer_booking['user_id'] = ADMIN_USER_ID;// assign to admin by default
	$customer_booking['customer_id'] = $customer_id;

	$customer_booking['request_date'] = date(DB_DATE_TIME_FORMAT);
	$customer_booking['date_created'] = date(DB_DATE_TIME_FORMAT);
	$customer_booking['date_modified'] = date(DB_DATE_TIME_FORMAT);

	$customer_booking['start_date'] = date(DB_DATE_FORMAT);

	$customer_booking['end_date'] = date(DB_DATE_FORMAT);

	$customer_booking['booking_site'] = is_mobile() ? SITE_MOBILE_BESTPRICE_VN : SITE_BESTPRICE_VN;

	$customer_booking['request_type'] = REQUEST_TYPE_REQUEST;

	$customer_booking['customer_type'] = $CI->Booking_Model->get_customer_type($customer_id);

	$customer_booking['special_request'] = $special_request;

	$customer_booking['description'] = $special_request;

	return $customer_booking;

}

function get_contact_service_reservations($special_request, $sr_name = 'Customer Request'){
	
	$sr['start_date'] = date(DB_DATE_FORMAT);;
	$sr['end_date'] = date(DB_DATE_FORMAT);;
	$sr['date_created'] = date(DB_DATE_FORMAT);
	$sr['date_modified'] = date(DB_DATE_FORMAT);
		
	$sr['service_name'] = $sr_name;
	
	$sr['selling_price'] = 0;
	
	$sr['reservation_type'] = RESERVATION_TYPE_OTHER;
		
	//$sr['description'] = $special_request;
	
	return array($sr);
}

/**
 * For Flights Booking
 */

function get_flight_customer_booking($flight_booking, $search_criteria, $customer_id, $special_request, $payment_info, $code_discount_info){
	
	$CI =& get_instance();
	$CI->load->model('Booking_Model');
	
	$customer_booking['user_id'] = ADMIN_USER_ID;// assign to admin by default
	
	$customer_booking['customer_id'] = $customer_id;
	
	$customer_booking['request_date'] = date(DB_DATE_TIME_FORMAT);
	$customer_booking['date_created'] = date(DB_DATE_TIME_FORMAT);
	$customer_booking['date_modified'] = date(DB_DATE_TIME_FORMAT);
	
	$start_end_date = get_start_end_date_of_flight_booking($flight_booking, $search_criteria);
	
	$customer_booking['start_date'] = $start_end_date['start_date'];
	
	$customer_booking['end_date'] = $start_end_date['end_date'];
	
	
	$customer_booking['booking_site'] = is_mobile() ? SITE_MOBILE_BESTPRICE_VN : SITE_BESTPRICE_VN;
	
	
	$customer_booking['request_type'] = REQUEST_TYPE_RESERVATION;
	
	$customer_booking['customer_type'] = $CI->Booking_Model->get_customer_type($customer_id);
	
	$customer_booking['special_request'] = $special_request;
	
	/**
     * Set flight booking information
     * Khuyenpv 21.10.2014
	 */
	$customer_booking['flight_short_desc'] = get_flight_short_desc_4_cb($search_criteria);
	
	$customer_booking['flight_from'] = $search_criteria['From'];
	
	$customer_booking['flight_to'] = $search_criteria['To'];
	
	$customer_booking['flight_depart'] = format_bpv_date($search_criteria['Depart']);
	
	if($search_criteria['Type'] == FLIGHT_TYPE_ROUNDWAY){
		
		$customer_booking['flight_return'] = format_bpv_date($search_criteria['Return']);
		
	}
	
	$customer_booking['is_flight_domistic'] = $search_criteria['is_domistic'];
	
	/**
     * End of flight setting for c.booking
	 */
	
	
	$customer_booking['selling_price'] = $flight_booking['prices']['total_price'];
	
	$customer_booking['adults'] = $flight_booking['nr_adults'];
	
	$customer_booking['children'] = $flight_booking['nr_children'];
	
	$customer_booking['infants'] = $flight_booking['nr_infants'];
	
	$flight_booking = set_checked_baggage($flight_booking);
	
	$flight_users['adults'] = $flight_booking['adults'];
	$flight_users['children'] = $flight_booking['children'];
	$flight_users['infants'] = $flight_booking['infants'];
	
	
	$customer_booking['flight_users'] = $flight_users;
	
	
	$booking_desc = '';
	/*
	$booking_desc .= $search_criteria['ADT'].' Adults: '.number_format($flight_booking['prices']['adult_fare_total']).' VND';
	if($search_criteria['CHD'] > 0){
		$booking_desc .= "\n".$search_criteria['CHD'].' Children: '.number_format($flight_booking['prices']['children_fare_total']).' VND';
	}
	if($search_criteria['INF'] > 0){
		$booking_desc .= "\n".$search_criteria['INF'].' Infants: '.number_format($flight_booking['prices']['infant_fare_total']).' VND';
	}
	$booking_desc .= "\n".'Tax & Fee: '.number_format($flight_booking['prices']['total_tax']).' VND'."\n";
	*/
	
	// discount form marketing promotion
	if(isset($flight_booking['prices']['total_discount']) && $flight_booking['prices']['total_discount'] > 0){
		$booking_desc .= "\n".'Marketing Discount: '.number_format($flight_booking['prices']['total_discount']).' VND'."\n";
	}
	
	if(!empty($code_discount_info)){
		
		$booking_desc .= "\n";
		
		if($code_discount_info['pro_type'] == 2){ // 2 means Voucher Code
			$booking_desc .= 'Voucher Code: ';
		} else {
			$booking_desc .= 'Promotion Code: ';
		}
	
		$booking_desc .= $code_discount_info['code'];
	
		$booking_desc .= ' - Discount: ';
	
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT){
			$booking_desc .= number_format($code_discount_info['get']). 'VND';
		}
	
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_PERCENTAGE){
			$booking_desc .= $code_discount_info['get'].' %';
		}
	
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT_TICKET){
			$booking_desc .= $code_discount_info['get']. lang('per_ticket');
		}
	
		$booking_desc .= "\n";
	
	}
	
	if($payment_info['method'] == PAYMENT_METHOD_BANK_TRANSFER && !empty($payment_info['bank'])){
		$booking_desc .= 'Bank Selected: ' . $payment_info['bank'];
		$booking_desc .= "\n";
	}
	
	$booking_desc .= $special_request;
	
	$customer_booking['description'] = $booking_desc;
	
	$customer_booking['payment_method'] = $payment_info['method'];
	
	
	return $customer_booking;
	
}

function get_flight_service_reservations($flight_booking, $search_criteria, $code_discount_info){
	
	$is_domistic = $search_criteria['is_domistic'];
	
	$service_reservations = array();
	
	
	if($is_domistic){ // for domistic flights
	
		$flight_departure = $flight_booking['flight_departure'];
		
		$sr_arr = get_reservation_from_flight_route_info($flight_departure, $flight_booking, FLIGHT_TYPE_DEPART);
		
		if(!empty($sr_arr)){
		
			$service_reservations = $sr_arr;
		}
		
		
		$flight_return = $flight_booking['flight_return'];
		
		$sr_arr = get_reservation_from_flight_route_info($flight_return, $flight_booking, FLIGHT_TYPE_RETURN);
		
		if(!empty($sr_arr)){
		
			foreach ($sr_arr as $rs){
		
				$service_reservations[] = $rs;
			}
		}
		
		$sr_arr = get_reservation_from_baggage_fess($flight_booking, $search_criteria);
	
		if(!empty($sr_arr)){
		
			foreach ($sr_arr as $rs){
		
				$service_reservations[] = $rs;
			}
		}
	
	} else { // for International Flights
		
		$sr_arr = get_reservation_of_international_flights($flight_booking, $search_criteria);
		
		if(!empty($sr_arr)){
		
			$service_reservations = $sr_arr;
		}
		
	}
	
	
	if(!empty($code_discount_info)){
	
		$total_booking = 0;
	
		foreach ($service_reservations as $sr){
			$total_booking += $sr['selling_price'];
		}
		
		$nr_ticket = $search_criteria['ADT'] + $search_criteria['CHD'] + $search_criteria['INF'];
		$nr_ticket = $search_criteria['Type'] == FLIGHT_TYPE_ROUNDWAY ? $nr_ticket * 2 : $nr_ticket;
	
		$code_discount = calculate_pro_code_discount($code_discount_info, $total_booking, $nr_ticket);
		
		$sr = array();
		
		$sr['start_date'] = format_bpv_date($search_criteria['Depart'], DB_DATE_FORMAT);
		$sr['end_date'] = format_bpv_date($search_criteria['Depart'], DB_DATE_FORMAT);
		$sr['date_created'] = date(DB_DATE_FORMAT);
		$sr['date_modified'] = date(DB_DATE_FORMAT);
	
		if($code_discount_info['pro_type'] == 2){
			$sr['service_name'] = 'Voucher Code '.$code_discount_info['code'];
		} else {
			$sr['service_name'] = 'Promotion Code '.$code_discount_info['code'];
		}
	
		$sr['partner_id'] = 1; // Best Price Vietnam Partner ID
			
		$sr['selling_price'] = 0 - $code_discount;
			
		$sr['reservation_type'] = RESERVATION_TYPE_OTHER;
		$sr['destination_id'] = DESTINATION_VIETNAM;
			
	
		$sr_desc = 'Discount: ';
			
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT){
			$sr_desc .= number_format($code_discount_info['get']). 'VND';
		}
			
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_PERCENTAGE){
			$sr_desc .= $code_discount_info['get'].' %';
		}
			
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT_TICKET){
			$sr_desc .= $code_discount_info['get']. lang('per_ticket');
		}
		
		if(!empty($code_discount_info['discount_note'])){
			$sr_desc .= "\n".$code_discount_info['discount_note'];
		}
	
		$sr['description'] = $sr_desc;
			
		$service_reservations[] = $sr;
	
	
	}
	
	return $service_reservations;

}


function get_start_end_date_of_flight_booking($flight_booking, $search_criteria){
	
	$is_domistic = $search_criteria['is_domistic'];

	$start_date = format_bpv_date($search_criteria['Depart']);

	$end_date = $start_date;

	if($search_criteria['Type'] == FLIGHT_TYPE_ROUNDWAY){

		$end_date = format_bpv_date($search_criteria['Return']);
		
		if($is_domistic){
		
			if(!empty($flight_booking['flight_return'])){
	
				$flight_return = $flight_booking['flight_return'];
					
				if(!empty($flight_return['detail']) && !empty($flight_return['detail']['routes'])){
	
					$routes = $flight_return['detail']['routes'];
	
					$last_route = $routes[count($routes) - 1];
	
					if(!empty($last_route['to']) && !empty($last_route['to']['date'])){
							
						$end_date = format_bpv_date($last_route['to']['date']);
							
					}
				}
	
			}
		
		} else {
			
			$selected_flight = $flight_booking['selected_flight'];
			
			if(!empty($selected_flight['return_routes'])){
				
				$last_route = $selected_flight['return_routes'][count($selected_flight['return_routes']) - 1];
				
				$end_date = flight_date($last_route['DayTo'], $last_route['MonthTo'], DB_DATE_FORMAT);
				
			}
		}

	} else {
		
		if($is_domistic){
			
			if(!empty($flight_booking['flight_departure'])){
	
				$flight_departure = $flight_booking['flight_departure'];
					
				if(!empty($flight_departure['detail']) && !empty($flight_departure['detail']['routes'])){
	
					$routes = $flight_departure['detail']['routes'];
	
					$last_route = $routes[count($routes) - 1];
	
					if(!empty($last_route['to']) && !empty($last_route['to']['date'])){
							
						$end_date = format_bpv_date($last_route['to']['date']);
							
					}
				}
	
			}
		
		} else {
			
			$selected_flight = $flight_booking['selected_flight'];
				
			if(!empty($selected_flight['depart_routes'])){
			
				$last_route = $selected_flight['depart_routes'][count($selected_flight['depart_routes']) - 1];
			
				$end_date = flight_date($last_route['DayTo'], $last_route['MonthTo'], DB_DATE_FORMAT);
			
			}
			
		}

	}


	return array('start_date' => $start_date, 'end_date' => $end_date);

}

function get_reservation_from_flight_route_info($flight_route_info, $flight_booking, $flight_way){

	if(empty($flight_route_info)) return '';
	
	$CI =& get_instance();
	
	$domistic_airlines = $CI->config->item('domistic_airlines');
	
	$ret = array();

	$detail = $flight_route_info['detail'];

	$class = $flight_route_info['class'];
	
	$prices = $detail['prices'];

	if(!empty($detail['routes'])){

		foreach ($detail['routes'] as $key=>$route){
				
			$flight_classes = explode("-", $class);
				
			$service_reservation = array();
				
			$service_reservation['service_id'] = 0;

			$service_reservation['service_name'] = $route['airline'];
			
			if(isset($flight_classes[$key])){
				$service_reservation['service_name'] .= ' - Class '.$flight_classes[$key];
			}
				
			$service_reservation['service_name'] .= ' - '.$route['from']['city'].' -> '.$route['to']['city'];

			$service_reservation['partner_id'] = 0;
				
			$service_reservation['start_date'] = format_bpv_date($route['from']['date']);

			$service_reservation['end_date'] = format_bpv_date($route['to']['date']);
				
			$service_reservation['reservation_type'] = RESERVATION_TYPE_FLIGHT;
				
			$service_reservation['destination_id'] = DESTINATION_VIETNAM;
				
				
			$service_reservation['airline'] = $flight_route_info['airline'];
			
			$service_reservation['airline_name'] = $domistic_airlines[$service_reservation['airline']];
			
			$service_reservation['flight_code'] = $route['airline'];
			$service_reservation['flight_from'] = $route['from']['city'];
			$service_reservation['flight_to'] = $route['to']['city'];
			$service_reservation['departure_time'] = $route['from']['time'];
			$service_reservation['arrival_time'] = $route['to']['time'];
				
			$service_reservation['fare_rules'] = $detail['fare_rules'];
			if(isset($flight_classes[$key])){
				$service_reservation['flight_class'] = $flight_classes[$key];
			}
			
			$service_reservation['airport_from'] = $route['from']['airport'];
			$service_reservation['airport_to'] = $route['to']['airport'];
			
			$service_reservation['flight_from_code'] = get_str_bsetween($route['from']['airport'], '(', ')');
			
			$service_reservation['flight_to_code'] = get_str_bsetween($route['to']['airport'], '(', ')');
			
			$service_reservation['flight_way'] = $flight_way;
			
			$service_reservation['adt_price'] = $prices['adult_fare_total'];
			$service_reservation['chd_price'] = isset($prices['children_fare_total']) ? $prices['children_fare_total'] : 0;
			$service_reservation['inf_price'] = isset($prices['infant_fare_total']) ? $prices['infant_fare_total'] : 0;
			$service_reservation['tax_fee'] = $prices['total_tax'];
			
			$service_reservation['fare_rule_short'] = set_fare_rule_short($service_reservation['airline'], $service_reservation['flight_class']);
				
				
			if($key == 0){
				//set the total flight for the first route
				$service_reservation['selling_price'] = $detail['prices']['total_price'];
			} else{
				$service_reservation['selling_price'] = 0;
			}
				
			$booking_services = get_passenger_text($flight_booking['nr_adults'], $flight_booking['nr_children'], $flight_booking['nr_infants']);
				
			$service_reservation['booking_services'] = $booking_services;
				
				
			//$service_reservation['description'] = '';
				
			$ret[] =  $service_reservation;
				
		}

	}

	return $ret;
}

/**
 * Get Reservation of International Flights
 * @param unknown $flight_booking
 * @param unknown $search_criteria
 */
function get_reservation_of_international_flights($flight_booking, $search_criteria){
	
	$ret = array();
	
	$selected_flight = $flight_booking['selected_flight'];
	
	foreach ($selected_flight['RouteInfo'] as $key => $route){
		
		$service_reservation = array();
		
		$service_reservation['service_id'] = 0;
		
		$service_reservation['service_name'] = $route['Airlines'].'-'.$route['FlightCodeNum'];
		
		$service_reservation['service_name'] .= ' - Class '.$route['Class'];
		
		$service_reservation['service_name'] .= ' - '.$route['From'].' -> '.$route['To'];
		
		$service_reservation['partner_id'] = 0;
		
		$service_reservation['start_date'] = flight_date($route['DayFrom'], $route['MonthFrom'], DB_DATE_FORMAT);
		
		$service_reservation['end_date'] = flight_date($route['DayTo'], $route['MonthTo'], DB_DATE_FORMAT);
		
		$service_reservation['reservation_type'] = RESERVATION_TYPE_FLIGHT;
		
		$service_reservation['destination_id'] = DESTINATION_VIETNAM;
		
		
		$service_reservation['airline'] = $route['Airlines'];
		$service_reservation['airline_name'] = $route['FlightCode'];
		$service_reservation['flight_code'] = $route['Airlines'].'-'.$route['FlightCodeNum'];
		$service_reservation['flight_from'] = $route['From'];
		$service_reservation['flight_to'] = $route['To'];
		$service_reservation['departure_time'] = flight_time_format($route['TimeFrom']);
		$service_reservation['arrival_time'] = flight_time_format($route['TimeTo']);
		
		$service_reservation['fare_rules'] = '';
		
		$service_reservation['flight_class'] = $route['Class'];
		
		
		$service_reservation['airport_from'] = $route['AirportFrom'];
		$service_reservation['airport_to'] = $route['AirportTo'];
		$service_reservation['flight_way'] = $route['FlightWay'];
		$service_reservation['flight_type'] = $route['FlightType'];
		
		$service_reservation['fare_rule_short'] = set_fare_rule_short($service_reservation['airline'], $service_reservation['flight_class']);
		
		
		if($key == 0){
			//set the total flight for the first route
			$service_reservation['selling_price'] = $flight_booking['prices']['total_price'];
			
			$service_reservation['adt_price'] = $flight_booking['prices']['adult_fare_total'];
			$service_reservation['chd_price'] = isset($flight_booking['prices']['children_fare_total']) ? $flight_booking['prices']['children_fare_total'] : 0;
			$service_reservation['inf_price'] = isset($flight_booking['prices']['infant_fare_total']) ? $flight_booking['prices']['infant_fare_total'] : 0;
			$service_reservation['tax_fee'] = $flight_booking['prices']['total_tax'];
			
		} else{
			
			$service_reservation['selling_price'] = 0;
		}
		
		$booking_services = get_passenger_text($flight_booking['nr_adults'], $flight_booking['nr_children'], $flight_booking['nr_infants']);
		
		$service_reservation['booking_services'] = $booking_services;
		
		
		//$service_reservation['description'] = $booking_services;
		
		$ret[] =  $service_reservation;
		
		
	}
	
	return $ret;
}


function get_reservation_from_baggage_fess($flight_booking, $search_criteria){
	
	$CI =& get_instance();
	
	$domistic_airlines = $CI->config->item('domistic_airlines');
	
	$baggage_fees = isset($flight_booking['baggage_fees']) ? $flight_booking['baggage_fees'] : array();
	
	$flight_departure = $flight_booking['flight_departure'];
	
	$flight_return = $flight_booking['flight_return'];

	$ret = array();
	
	if(!empty($baggage_fees['depart'])){
		
		foreach ($baggage_fees['depart'] as $key=>$value){
			
			$passenger = get_passenger_by_index($flight_booking, $key);
				
			$pas_name = !empty($passenger) ? $passenger['first_name']. ' '.$passenger['last_name'] : 'Pas.'.$key;
			
			
			$rs['service_id'] = 0;
			
			$rs['service_name'] = "Baggage Depart (".$flight_departure['airline'].") - ".$pas_name.": ".$value['kg']." Kg";
			
			$rs['start_date'] = format_bpv_date($search_criteria['Depart']);
			
			$rs['end_date'] = format_bpv_date($search_criteria['Depart']);
			
			$rs['reservation_type'] = RESERVATION_TYPE_FLIGHT;
			
			$rs['destination_id'] = DESTINATION_VIETNAM;
			
			$rs['selling_price'] = $value['money'];
			
			$rs['description'] = $pas_name.": ".$value['kg']." Kg - ".number_format($value['money']);
			
			
			$rs['flight_way'] = FLIGHT_TYPE_DEPART;
			
			$rs['baggage_kg'] = $value['kg'];
			
			$rs['airline'] = $flight_departure['airline'];
			
			$rs['airline_name'] = $domistic_airlines[$rs['airline']];
			
			$rs['flight_code'] = $flight_departure['code'];
			
			$ret[] = $rs;
		}
		
	}
	
	if(!empty($baggage_fees['return']) && !empty($flight_return)){
	
		foreach ($baggage_fees['return'] as $key=>$value){
			
			$passenger = get_passenger_by_index($flight_booking, $key);
			
			$pas_name = !empty($passenger) ? $passenger['first_name']. ' '.$passenger['last_name'] : 'Pas.'.$key;
				
			$rs['service_id'] = 0;
				
			$rs['service_name'] = "Baggage Return (".$flight_return['airline'].") - ".$pas_name.": ".$value['kg']." Kg";
				
			$rs['start_date'] = format_bpv_date($search_criteria['Return']);
				
			$rs['end_date'] = format_bpv_date($search_criteria['Return']);
				
			$rs['reservation_type'] = RESERVATION_TYPE_FLIGHT;
				
			$rs['destination_id'] = DESTINATION_VIETNAM;
				
			$rs['selling_price'] = $value['money'];
				
			$rs['description'] = $pas_name.": ".$value['kg']." Kg - ".number_format($value['money']);

			
			$rs['flight_way'] = FLIGHT_TYPE_RETURN;
				
			$rs['baggage_kg'] = $value['kg'];
				
			$rs['airline'] = $flight_return['airline'];
				
			$rs['airline_name'] = $domistic_airlines[$rs['airline']];
				
			$rs['flight_code'] = $flight_return['code'];
			
			$ret[] = $rs;
		}
	
	}
	
	
	
	return $ret;
}

function set_checked_baggage($flight_booking){

	$adults = $flight_booking['adults'];
	$children = $flight_booking['children'];
	$infants = $flight_booking['infants'];

	foreach ($adults as $key=>$value){

		$index = $key + 1;

		$value['checked_baggage'] = get_checked_baggage_by_index($flight_booking, $index);

		$adults[$key] = $value;

	}

	foreach ($children as $key=>$value){

		$index = $key + 1 + count($adults);

		$value['checked_baggage'] = get_checked_baggage_by_index($flight_booking, $index);

		$children[$key] = $value;

	}

	foreach ($infants as $key=>$value){

		$index = $key + 1 + count($adults) + count($children);

		$value['checked_baggage'] = get_checked_baggage_by_index($flight_booking, $index);

		$infants[$key] = $value;

	}

	$flight_booking['adults'] = $adults;
	$flight_booking['children'] = $children;
	$flight_booking['infants'] = $infants;

	return $flight_booking;

}

function get_pro_code_discount_info($code, $service_type, $hotel_id = '', $cruise_id = '', $tour_id = '', $nr_passengers = '', $phone = '', 
    $is_client_check = false)
{
	
	$CI =& get_instance();
	$CI->load->model('Deal_Model');
	
	$code = trim($code);
	
	$pro = '';
	
	$discount_info = '';
	
	$pro = $CI->Deal_Model->get_bpv_promotion($code);
	
	if($hotel_id != ''){
		
		if($pro['apply_all'] == STATUS_INACTIVE){ // if the promotion is not applied for all, check by hotel applied
			
			$pro = $CI->Deal_Model->get_hotel_bpv_promotion($code, $hotel_id);

		}
	}

	if($cruise_id != ''){
	
		if($pro['apply_all'] == STATUS_INACTIVE){ // if the promotion is not applied for all, check by cruise applied
				
			$pro = $CI->Deal_Model->get_cruise_bpv_promotion($code, $cruise_id);
	
		}
	}
	
	if($tour_id != ''){
		
		if($pro['apply_all'] == STATUS_INACTIVE){ // if the promotion is not applied for all, check by cruise applied
		
			$pro = $CI->Deal_Model->get_tour_bpv_promotion($code, $tour_id); 
			
			//print_r($pro);exit();
		
		}
		
	}
	
	if(!empty($pro)){ // from best price promotion

	    // ---------------------------------
	    // check promotion is multiple time : toanlk - 27/05/2015
	    if (! $pro['is_multiple_time'])
        {
            
            if (empty($phone) && $is_client_check)
            {
                return array('invalid_phone' => lang('bpv_pro_phone_invalid'));
            }
            else
            {
                $is_code_available = $CI->Deal_Model->is_pro_code_available($code, $phone, $pro['id']);
                
                if (! $is_code_available)
                {
                    if($is_client_check) {
                        return array('invalid_phone' => lang('bpv_pro_code_in_used'));
                    } else {
                        return null;
                    }
                } else {
                    if(!$is_client_check) {
                        $discount_info['bpv_promotion_id'] = $pro['id'];
                    }
                }
            }
        }
	    
	    // ---------------------------------
		if($service_type == HOTEL){
	
			$discount_type = $pro['hotel_discount_type'];
	
			if($discount_type > 0){

				$discount_info['pro_type'] = 1; // 1 means marketing promotion
				
				$discount_info['discount_type'] = $discount_type;
	
				$discount_info['get'] = $pro['hotel_get'];
	
				$discount_info['get_max'] = $pro['hotel_get_max']; 
				
				$discount_info['code'] = $code;
				
				$discount_info['discount_note'] = !empty($pro['discount_note']) ? trim($pro['discount_note']) : '';
					
			}
	
		}
			
		if($service_type == FLIGHT){
	
			$discount_type = $pro['flight_discount_type'];
	
			if($discount_type > 0){
				
				$discount_info['pro_type'] = 1; // 1 means marketing promotion
				
				$discount_info['discount_type'] = $discount_type;
	
				$discount_info['get'] = $pro['flight_get'];
	
				$discount_info['get_max'] = -1; // -1 mean no maximum discount 
				
				$discount_info['code'] = $code;
				
				$discount_info['discount_note'] = !empty($pro['discount_note']) ? trim($pro['discount_note']) : '';
					
			}
				
		}
			
		if($service_type == CRUISE){
			
			$discount_type = $pro['cruise_discount_type'];
			
			if($discount_type > 0){

				$discount_info['pro_type'] = 1; // 1 means marketing promotion
				
				$discount_info['discount_type'] = $discount_type;
				
				$discount_info['get'] = $pro['cruise_get'];
				
				if (isset($pro['specific_cruise_get']) && $pro['specific_cruise_get'] > 0){
						
					$discount_info['get'] = $pro['specific_cruise_get'];
				
				}
				
				$discount_info['get_max'] = $pro['cruise_get_max'];
			
				$discount_info['code'] = $code;
			
				$discount_info['discount_note'] = !empty($pro['discount_note']) ? trim($pro['discount_note']) : '';
					
				// overidde from fixed marketing promotion campain conditions
				if($discount_type == BPV_DISCOUNT_AMOUNT && !empty($nr_passengers) && $nr_passengers > 0){
					
					$cnf_more_people_more_save = $CI->config->item('pro-codes-more-people-more-save');
					
					$cnf_codes = $cnf_more_people_more_save['codes'];
					
					$cnf_discounts = $cnf_more_people_more_save['discounts'];
					
					if(in_array(strtolower($code),$cnf_codes)){
						
						foreach ($cnf_discounts as $value){
							
							if($nr_passengers >= $value['min_passenger'] && $nr_passengers <= $value['max_passeger']){
								
								$discount_info['get'] = $value['discount'];
								
								break;
							}
							
						}
						
					}
				}
			}
		}
		
		if($service_type == TOUR){
			
			$discount_type = $pro['tour_discount_type'];
			
			if($discount_type > 0){
			
				$discount_info['pro_type'] = 1; // 1 means marketing promotion
			
				$discount_info['discount_type'] = $discount_type;
			
				$discount_info['get'] = $pro['tour_get'];
				
				if (isset($pro['specific_tour_get']) && $pro['specific_tour_get'] > 0){
					
					$discount_info['get'] = $pro['specific_tour_get'];
				
				}
			
				$discount_info['get_max'] = $pro['tour_get_max'];
			
				$discount_info['code'] = $code;
			
				$discount_info['discount_note'] = !empty($pro['discount_note']) ? trim($pro['discount_note']) : '';
					
			}
		}
			
			
	} else {
			
		$pro = $CI->Deal_Model->get_voucher($code); // get voucher code
			
		if(!empty($pro)){
			
			$discount_info['pro_type'] = 2; // 2 means voucher
			
			$discount_info['discount_type'] = BPV_DISCOUNT_AMOUNT;
	
			$discount_info['get'] = $pro['amount'];
	
			$discount_info['get_max'] = -1; // -1 mean no maximum discount
			
			$discount_info['code'] = $code;
			
			$discount_info['discount_note'] = !empty($pro['discount_note']) ? trim($pro['discount_note']) : '';
				
		}
	}
	
	return $discount_info;
}

function calculate_pro_code_discount($code_discount_info, $total_booking, $nr_ticket = 0){
	$discount = 0;
	
	if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT){
		$discount = $code_discount_info['get'];
	}
	
	if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT_TICKET){
		$discount = $code_discount_info['get'] * $nr_ticket;
	}
	
	if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT_PAX){
		$discount = $code_discount_info['get'] * $nr_ticket;
	}
	
	if($code_discount_info['discount_type'] == BPV_DISCOUNT_PERCENTAGE){
		$discount = $code_discount_info['get'] * $total_booking / 100;
		
		if($code_discount_info['get_max'] > 0 && $discount > $code_discount_info['get_max']){
			$discount = $code_discount_info['get_max'];
		}
	}
	
	
	return $discount;
}

function get_cruise_tour_customer_booking($tour_id, $startdate, $enddate, $customer_id, $special_request, $payment_info, $code_discount_info){

	$CI =& get_instance();
	$CI->load->model('Booking_Model');

	$customer_booking['user_id'] = ADMIN_USER_ID;// assign to admin by default

	$customer_booking['tour_id'] = $tour_id;

	$customer_booking['customer_id'] = $customer_id;

	$customer_booking['request_date'] = date(DB_DATE_TIME_FORMAT);
	$customer_booking['date_created'] = date(DB_DATE_TIME_FORMAT);
	$customer_booking['date_modified'] = date(DB_DATE_TIME_FORMAT);

	$customer_booking['start_date'] = format_bpv_date($startdate, DB_DATE_FORMAT);

	$customer_booking['end_date'] = format_bpv_date($enddate, DB_DATE_FORMAT);

	$customer_booking['booking_site'] = is_mobile() ? SITE_MOBILE_BESTPRICE_VN : SITE_BESTPRICE_VN;

	$customer_booking['request_type'] = REQUEST_TYPE_RESERVATION;

	$customer_booking['customer_type'] = $CI->Booking_Model->get_customer_type($customer_id);

	$customer_booking['special_request'] = $special_request;

	$booking_desc = '';
	
	if(!empty($code_discount_info)){
	
		if($code_discount_info['pro_type'] == 2){ // 2 means Voucher Code
			$booking_desc = 'Voucher Code: ';
		} else {
			$booking_desc = 'Promotion Code: ';
		}
	
		$booking_desc .= $code_discount_info['code'];
	
		$booking_desc .= ' - Discount: ';
	
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT){
			$booking_desc .= number_format($code_discount_info['get']). 'VND';
		}
	
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_PERCENTAGE){
			$booking_desc .= $code_discount_info['get'].' %';
		}
	
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT_TICKET){
			$booking_desc .= $code_discount_info['get']. lang('per_ticket');
		}
	
		$booking_desc .= "\n";
	
	}

	if($payment_info['method'] == PAYMENT_METHOD_BANK_TRANSFER && !empty($payment_info['bank'])){
		$booking_desc .= 'Bank selected: ' . $payment_info['bank'];
		$booking_desc .= "\n";
	}

	$booking_desc .= $special_request;


	$customer_booking['description'] = $booking_desc;

	$customer_booking['payment_method'] = $payment_info['method'];

	return $customer_booking;

}

function get_cruise_tour_service_reservations($tour, $startdate, $enddate, $selected_cabin, $check_rate_info, $surcharges, $code_discount_info){

	$CI =& get_instance();

	$service_reservations = array();
	
	$sr['start_date'] = format_bpv_date($startdate, DB_DATE_FORMAT);
	$sr['end_date'] = format_bpv_date($enddate, DB_DATE_FORMAT);
	$sr['date_created'] = date(DB_DATE_FORMAT);
	$sr['date_modified'] = date(DB_DATE_FORMAT);
	
	$sr['service_id'] = $tour['id']; // hotel id
	
	$accommodation = $selected_cabin['cabin_rate_info'];
	
	$total_rate = bpv_round_rate($selected_cabin['cabin_rate_info']['sell_rate']);
	
	$sr['service_name'] = $tour['name'];
	
	$sr['partner_id'] = $tour['partner_id'];
	
	$sr['selling_price'] = $total_rate; // set later
	
	$sr_desc = $check_rate_info['adults'] . lang('booking_adults');
	
	if(!empty($check_rate_info['children'])) {
		$sr_desc .= ' - '.$check_rate_info['children'] . lang('booking_children');
	}
	
	if(!empty($check_rate_info['infants'])) {
		$sr_desc .= ' - '.$check_rate_info['infants'] . lang('booking_infants');
	}
	
	$class = empty($tour['cruise_id']) ? 'Hạng tour' : 'Cabin';
	
	$sr_desc .= "\n".$class.': '.$accommodation['name'].' - '.number_format($total_rate);
	
	$sr['reservation_type'] = RESERVATION_TYPE_CRUISE_TOUR;
	
	$sr['destination_id'] = $tour['destination_id'];
	
	//$sr['condition'] = get_room_conditon_content($room_rate, $startdate);
	
	$sr['description'] = $sr_desc;
	
	$service_reservations[] = $sr;

	if(!empty($surcharges)){

		foreach ($surcharges as $sur){
				
			$sr['start_date'] = format_bpv_date($startdate, DB_DATE_FORMAT);
			$sr['end_date'] = format_bpv_date($enddate, DB_DATE_FORMAT);
			$sr['date_created'] = date(DB_DATE_FORMAT);
			$sr['date_modified'] = date(DB_DATE_FORMAT);
				
			$sr['service_id'] = $tour['id']; // hotel room type id
			$sr['service_name'] = $sur['name'];
			$sr['partner_id'] = $tour['partner_id'];
				
			$sr['selling_price'] = $sur['total_charge'];
				
			$sr['reservation_type'] = RESERVATION_TYPE_CRUISE_TOUR;
			$sr['destination_id'] = $tour['destination_id'];

			// already creating description from load surchage function
			$sr['description'] = $sur['sr_desc'];
				
			$service_reservations[] = $sr;
		}

	}
	
	if(!empty($code_discount_info)){
	
		$total_booking = 0;
	
		foreach ($service_reservations as $sr){
			$total_booking += $sr['selling_price'];
		}
	
		$code_discount = calculate_pro_code_discount($code_discount_info, $total_booking, $check_rate_info['adults']);
	
		$sr['start_date'] = format_bpv_date($startdate, DB_DATE_FORMAT);
		$sr['end_date'] = format_bpv_date($enddate, DB_DATE_FORMAT);
		$sr['date_created'] = date(DB_DATE_FORMAT);
		$sr['date_modified'] = date(DB_DATE_FORMAT);
	
		if($code_discount_info['pro_type'] == 2){ // 2 for voucher code
			$sr['service_name'] = 'Voucher Code '.$code_discount_info['code'];
		} else {
			$sr['service_name'] = 'Promotion Code '.$code_discount_info['code'];
		}
	
		$sr['partner_id'] = 1; // Best Price Vietnam Partner ID
			
		$sr['selling_price'] = 0 - $code_discount;
			
		$sr['reservation_type'] = RESERVATION_TYPE_OTHER;
			
	
		$sr_desc = 'Discount: ';
			
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT){
			$sr_desc .= number_format($code_discount_info['get']). 'VND';
		}
			
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_PERCENTAGE){
			$sr_desc .= $code_discount_info['get'].' %';
		}
			
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT_TICKET){
			$sr_desc .= $code_discount_info['get']. lang('per_ticket');
		}
		
		
		if($code_discount_info['discount_type'] == BPV_DISCOUNT_AMOUNT_PAX){
			$sr_desc .= $code_discount_info['get']. lang('per_pax');
		}
	
		if(!empty($code_discount_info['discount_note'])){
			$sr_desc .= "\n".$code_discount_info['discount_note'];
		}
	
		$sr['description'] = $sr_desc;
			
		$service_reservations[] = $sr;
	
	
	}

	return $service_reservations;

}