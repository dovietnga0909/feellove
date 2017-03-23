<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotel_Details extends CI_Controller {
	
	public function __construct()
    {
       	parent::__construct();
       	
		$this->load->language('hotel');
		$this->load->helper(array('hotel','form','cookie','rate','booking','display'));
		$this->load->model(array('Hotel_Model','Destination_Model','Booking_Model', 'Review_Model'));
		$this->load->config('hotel_meta');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	
	public function index($id){
		
		$is_mobile = is_mobile();
		
		$this->session->set_userdata('MENU', MNU_HOTELS);
		
		$startdate = $this->input->get('startdate');
		
	
		if(empty($startdate)){ // only cache the hotel page if there-is no action on page
			set_cache_html();
		}
		$data = array();
		if($is_mobile){
			
			$data = get_library('flexsilder', $data);
			
			$data['page_css'] = get_static_resources('hotel_detail.min.30072014.css', 'css/mobile/');
			
		} else {
			
			$data = get_library('image-gallery', $data);
			
			$data['page_css'] = get_static_resources('hotel_detail.min.261120141540.css');
		}
		
		$data['page_js'] = get_static_resources('hotel.min.30072014.js');
		
		
		
		$hotel = $this->Hotel_Model->get_hotel_detail($id);
		
		if(empty($hotel)){
			
			// go to 404 page & exit
			exit();
		}
		
		// store in session
		save_recent_data($hotel);
		
		$data['hotel'] = $hotel;
		
		$data = $this->_load_hotel_recent_search($data, $is_mobile);
		
		$data = $this->_load_hotel_basic_info($data, $is_mobile);
		
		$data = $this->_load_check_rate_form($data, $is_mobile);
		
		/**
		 * Set Hotel Meta & Canonical
		 */
		
		$check_rate_info = $data['check_rate_info'];
		if(!$check_rate_info['is_default']){
			$canonical_link = get_url(HOTEL_DETAIL_PAGE, $hotel);
			$hotel['canonical'] = '<link rel="canonical" href="'.$canonical_link.'" />';
		}
		
		$data['meta'] = get_header_meta(HOTEL_DETAIL_PAGE, $hotel);
		
		
		$data = $this->_load_room_types($data, $is_mobile);
				
		$data = $this->_load_similar_hotels($data, $is_mobile);
		
		
		// Get recent view items
		$data = load_recent_items($data, $data['search_criteria']['startdate'], MODULE_HOTEL, $hotel['id'], true, lang('recent_hotel_items'));
		
		if($is_mobile){
		
			$data['bpv_content'] = $this->load->view('mobile/hotels/hotel_detail/hotel_detail', $data, TRUE);
			
			$this->load->view('mobile/_templates/bpv_layout', $data);
		
		} else {
			
			$data['bpv_register'] = $this->load->view('common/bpv_register', array(), TRUE);
			$data['bpv_content'] = $this->load->view('hotels/hotel_detail/hotel_detail', $data, TRUE);
				
			$this->load->view('_templates/bpv_layout', $data);
			
		}
		
	}
	
	public function check_rates(){
		
		$is_mobile = is_mobile();
		
		$startdate = $this->input->get('startdate', true);
		$enddate = $this->input->get('enddate', true);
		$night = $this->input->get('night', true);
		$hotel_id = $this->input->get('hotel_id', true);
		
		$search_criteria = update_search_criteria_by_checkrate($startdate, $night, $enddate);
		
		$check_rate_info['startdate'] = $startdate;
		$check_rate_info['night'] = $night;
		$check_rate_info['enddate'] = $enddate;
	
		
		$data['hotel'] = $this->Hotel_Model->get_search_hotel($hotel_id);
		
		$data['search_criteria'] = $search_criteria;
		
		$data['check_rate_info'] = $check_rate_info;
		
		$data['max_rooms'] = $this->config->item('max_rooms');
		
		$data['room_type_limit'] = $this->config->item('room_type_limit');
		
		$data['room_rates'] = $this->_load_hotel_room_rates($hotel_id, $startdate, $night, $enddate);
		
		$data['rate_rows'] = $this->_count_rate_rows($data['room_rates']);
		
		if($is_mobile){
			$this->load->view('mobile/hotels/hotel_detail/room_rates',$data);
		} else {
			$this->load->view('hotels/hotel_detail/room_rates',$data);
		}
	}
	
	public function booking($hotel_id){
		
		$is_mobile = is_mobile();
		
		$this->session->set_userdata('MENU', MNU_HOTELS);
		
		$startdate = $this->input->get('startdate', true);
		$enddate = $this->input->get('enddate', true);
		$night = $this->input->get('night', true);
		
		$check_rate_info['startdate'] = $startdate;
		$check_rate_info['night'] = $night;
		$check_rate_info['enddate'] = $enddate;	
		$check_rate_info['action'] = ACTION_CHECK_RATE;
		$data['check_rate_info'] = $check_rate_info;
		
		$action = $this->input->post('action');
		
		$hotel = $this->Hotel_Model->get_selected_hotel($hotel_id);
		
		$data['meta'] = get_header_meta(HOTEL_BOOKING_PAGE, $hotel);
		
		if(empty($action)){ // go from book-marked link
			
			$selected_rooms = $this->session->userdata(HOTEL_ROOM_RATE_SELECTED);
				
			if(empty($selected_rooms)){ // no session selected room available
					
				$this->_go_back_to_check_rate($hotel, $check_rate_info);
					
			} else {
			
				$currrent_time = microtime(true);
			
				if($currrent_time - $selected_rooms['timestamp'] > 60*60*12){ // only save check-rate-info in 12 hours

					$this->session->unset_userdata(HOTEL_ROOM_RATE_SELECTED);
					
					$this->_go_back_to_check_rate($hotel, $check_rate_info);
						
				} else {
						
					$nr_rooms = $selected_rooms['nr_rooms'];
				}
			
			}
			
		} else { // go from post form action
			
			$nr_rooms = $this->input->post(NULL, TRUE);
			
			$selected_rooms['timestamp'] = microtime(true);
			$selected_rooms['nr_rooms'] = $nr_rooms;

			if($action == ACTION_BOOK_NOW){ // we know that user go from check-rate page
				$this->session->set_userdata(HOTEL_ROOM_RATE_SELECTED, $selected_rooms);
			}
			
		}
		
		//load room rates
		$room_rates = $this->_load_hotel_room_rates($hotel_id, $startdate, $night, $enddate);
		
		$selected_room_rates = $this->_get_selected_room_rates($room_rates, $nr_rooms);
		
		$room_pax_total = $this->_calculate_room_pax_total($selected_room_rates);
		
		$surcharges = $this->_load_hotel_sucharges($hotel_id, $startdate, $enddate, $room_pax_total);
		
		if($action == ACTION_MAKE_BOOKING && contact_validation()){
			
			$this->_make_booking($hotel, $startdate, $enddate, $selected_room_rates, $surcharges, $room_pax_total, $check_rate_info);
			
			$this->session->unset_userdata(HOTEL_ROOM_RATE_SELECTED);
			
			redirect(site_url('xac-nhan.html?type=hotel'));
			exit();
			
		} else {

			$data['parents'] = $this->Hotel_Model->get_hotel_parent_destinations($hotel_id);
			$data['hotel'] = $hotel;
			
			
			$hotel_price_from = $this->Hotel_Model->get_hotel_price_from($hotel_id, $check_rate_info['startdate']);
			$data['hotel_price_from'] = $hotel_price_from;
			
			$data['selected_room_rates'] = $selected_room_rates;
			
			$data['room_pax_total'] = $room_pax_total;
			
			$data['surcharges'] = $surcharges;
			
			$data['payment_detail'] = $this->_count_total_payments($selected_room_rates, $surcharges);
			
			$data['page_js'] = get_static_resources('hotel.min.30072014.js');
			
			if($is_mobile){
				
				$data['page_css'] = get_static_resources('hotel_booking.min.300720141415.css','css/mobile/');
				
				$data['step_booking'] = $this->load->view('mobile/hotels/common/step_booking', $data, TRUE);
					
				$data['selected_rooms'] = $this->load->view('mobile/hotels/hotel_booking/selected_rooms', $data, TRUE);
					
				$data['surcharge_detail'] = $this->load->view('mobile/hotels/hotel_booking/surcharges', $data, TRUE);
					
				$data['payment_detail'] = $this->load->view('mobile/hotels/hotel_booking/payment_detail', $data, TRUE);
				
			} else {
				
				$data['page_css'] = get_static_resources('hotel_booking.min.15052014.css');
				
				$data['step_booking'] = $this->load->view('hotels/common/step_booking', $data, TRUE);
					
				$data['selected_rooms'] = $this->load->view('hotels/hotel_booking/selected_rooms', $data, TRUE);
					
				$data['surcharge_detail'] = $this->load->view('hotels/hotel_booking/surcharges', $data, TRUE);
					
				$data['payment_detail'] = $this->load->view('hotels/hotel_booking/payment_detail', $data, TRUE);
				
			}
			
			
			$data['customer_contact'] = load_contact_form(false, '','data-area', $is_mobile);
			
			$data['payment_method'] = load_payment_method(HOTEL, $is_mobile);
			
			if($is_mobile){
				
				$data['hotel_pro_code'] = $this->load->view('mobile/hotels/hotel_booking/hotel_pro_code', $data, TRUE);
				
				$data['bpv_content'] = $this->load->view('mobile/hotels/hotel_booking/hotel_booking', $data, TRUE);
				
				$this->load->view('mobile/_templates/bpv_layout', $data);
				
			} else {
			
				$data['hotel_pro_code'] = $this->load->view('hotels/hotel_booking/hotel_pro_code', $data, TRUE);
				
				$data['bpv_content'] = $this->load->view('hotels/hotel_booking/hotel_booking', $data, TRUE);
				
				$this->load->view('_templates/bpv_layout', $data);
			
			}
		
		}
	}
	
	function _load_hotel_recent_search($data, $is_mobile = false){
		$hotel = $data['hotel'];
		$des['id'] = $hotel['destination_id'];
		$des['name'] = $hotel['destination_name'];
		$des['type'] = $hotel['destination_type'];
		
		$search_criteria = get_hotel_search_criteria();
		
		if($search_criteria['is_default'] || empty($search_criteria['destination'])){ // no search session before
			$search_criteria = get_hotel_search_criteria($des); // the default destination is the parent destination of the hotel
		} else { // already search before
			
			$des_id = $search_criteria['oid'];
			$des_id = str_replace('d-', '', $des_id);
			$des = $this->Destination_Model->get_search_destination($des_id);
			$search_criteria = get_hotel_search_criteria($des);
		}
		
		
		$data['search_criteria'] = $search_criteria;
			
		$data['max_nights'] = $this->config->item('max_nights');
		
		if($is_mobile){
			
			$data['hotel_search_form'] = $this->load->view('mobile/hotels/common/search_form', $data, TRUE);
			
		} else {
		    
		    $data['suggestion_destinations']= $this->Hotel_Model->get_top_hotel_destinations();

			$data['hotel_search_overview'] = $this->load->view('hotels/common/search_overview', $data, TRUE);
			
			$data['hotel_search_form'] = $this->load->view('hotels/common/search_form', $data, TRUE);
			
			
		}

		return $data;
	}
	
	function _load_hotel_basic_info($data, $is_mobile = false){
		
		$id = $data['hotel']['id'];
		
		$hotel = $data['hotel'];
		
		$data['parents'] = $this->Hotel_Model->get_hotel_parent_destinations($id);
		
		$data['is_hotel_detail'] = 1;
		
		$data['search_criteria'] = get_hotel_search_criteria();
		
		$data['max_nights'] = $this->config->item('max_nights');
		
		$tab = $this->input->get('tab', true);
		if( !empty($tab)) {
			$data['tab'] = $tab;
		}
		
		$check_rate_info = get_hotel_check_rate_info();
		
		$hotel_price_from = $this->Hotel_Model->get_hotel_price_from($id, $check_rate_info['startdate']);
		
		if(!empty($hotel_price_from)){
			$data['hotel_price_from'] = $hotel_price_from;
		}
		// promotions from the hotel
		$hotel_promotions = $this->Hotel_Model->get_all_available_hotel_promotions($id);
		$data['hotel_promotions'] = $hotel_promotions;
		
		// promotions from BestPrice
		$bpv_promotions = $this->Hotel_Model->get_hotel_bpv_promotions($id);
		$data['bpv_promotions'] = $bpv_promotions;
		
		
		$hotel_facilites = $this->Hotel_Model->get_hotel_facilities($hotel['facilities']);
		
		$data['highlight_facilities'] = $this->_get_highlight_facilities($hotel_facilites);
		
		$data['facility_groups'] = $facility_groups = $this->config->item('facility_groups');
		
		$hotel_facilites = $this->_restructure_facilities($hotel_facilites, $facility_groups);
		
		$data['hotel_facilities'] = $hotel_facilites;
		
		$data['default_cancellation'] = $this->Hotel_Model->get_cancellation_of_hotel($id);
		
		// get last review
		$data['last_review'] = $this->Review_Model->get_last_review(array('hotel_id' => $id));
		
		// get destinations around hotels
		$m_des = null;
		foreach ($data['parents'] as $parent_des) {
			if($parent_des['is_top_hotel'] || $parent_des['type'] == DESTINATION_TYPE_CITY) {
				$m_des = $parent_des;
			}
		}
		
		if( !empty($m_des) ) {
			$data['des_around'] = $this->Destination_Model->get_in_and_around_destination($m_des['id']);
		}
		
		$data['hotel_photos'] = $this->Hotel_Model->get_hotel_photos($id);
		
		if($is_mobile){

			$data['hotel_basic_info'] = $this->load->view('mobile/hotels/hotel_detail/hotel_basic_info', $data, TRUE);
			
			$data['hotel_photos'] = $this->load->view('mobile/hotels/hotel_detail/hotel_photos', $data, TRUE);
			$data['hotel_detail_info'] = $this->load->view('mobile/hotels/hotel_detail/hotel_detail_info', $data, TRUE);
			
			
		} else {
			
			$data['hotel_basic_info'] = $this->load->view('hotels/hotel_detail/hotel_basic_info', $data, TRUE);
			
			$data['hotel_photos'] = $this->load->view('hotels/hotel_detail/hotel_photos', $data, TRUE);
			$data['hotel_detail_info'] = $this->load->view('hotels/hotel_detail/hotel_detail_info', $data, TRUE);
			
			
		}
		
		
		return $data;
	}
	
	function _load_room_types($data, $is_mobile = false){
		
		$hotel = $data['hotel'];
		
		$data['room_type_limit'] = $this->config->item('room_type_limit');
		
		$data['hotel_room_types'] = $this->Hotel_Model->get_hotel_room_types($hotel['id'], true);
		
		if($is_mobile){
			
			$data['room_types'] = $this->load->view('mobile/hotels/hotel_detail/room_types', $data, TRUE);
			
		} else {
			
			$data['room_types'] = $this->load->view('hotels/hotel_detail/room_types', $data, TRUE);
			
		}
		
		return $data;
	}
	
	function _load_hotel_room_rates($hotel_id, $startdate, $night, $enddate){
		
		$startdate = format_bpv_date($startdate);
		$enddate = format_bpv_date($enddate);
		
		$enddate = date(DB_DATE_FORMAT, strtotime($enddate . " -1 day"));
		
		$check_rate_info['startdate'] = $startdate;
		$check_rate_info['night'] = $night;
		$check_rate_info['enddate'] = $enddate;
		
		
		$room_types = $this->Hotel_Model->get_hotel_room_types($hotel_id, true);
		$room_rates = $this->Hotel_Model->get_hotel_room_rates($hotel_id, $startdate, $enddate);
		$hotel_promotions = $this->Hotel_Model->get_hotel_promotions($hotel_id, $startdate, $night, $enddate);

		
		$default_cancellation = $this->Hotel_Model->get_cancellation_of_hotel($hotel_id);
		$non_refundable_cancellation = $this->Hotel_Model->get_cancellation_by_id(CANCELLATION_NO_REFUND);
		
		$room_rates = $this->_calculate_room_rates($room_types, $room_rates, $default_cancellation, $non_refundable_cancellation, $hotel_promotions, $check_rate_info);
		
		return $room_rates;
	}
	
	function _load_hotel_sucharges($hotel_id, $startdate, $enddate, $room_pax_total){
		$startdate = format_bpv_date($startdate);
		$enddate = format_bpv_date($enddate);
		
		$enddate = date(DB_DATE_FORMAT, strtotime($enddate . " -1 day"));
		
		$surcharges = $this->Hotel_Model->get_hotel_surcharges($hotel_id, $startdate, $enddate);
		
		$stay_dates = get_days_between_2_dates($startdate, $enddate);
		
		$ret = array();
		
		foreach ($surcharges as $value){
			
			$apply_dates = array();
			
			foreach ($stay_dates as $s_date){
				
				if($s_date >= $value['start_date'] && $s_date <= $value['end_date']){
					
					if(is_bit_value_contain($value['week_day'], date('w',strtotime($s_date)))){
						
						$apply_dates[] = $s_date;
						
					}
					
				}
				
			}
			
			if(count($apply_dates) > 0){
				
				$value['apply_dates'] = $apply_dates;
				
				if($value['charge_type'] == SUR_PER_ADULT_PER_BOOKING){
					
					$value['total_charge'] = $value['amount'] * ($room_pax_total['max_adults'] + $room_pax_total['max_children']);
					
				}
				
				if($value['charge_type'] == SUR_PER_NIGHT){
						
					$value['total_charge'] = $value['amount'] * count($apply_dates);
						
				}
				
				if($value['charge_type'] == SUR_PER_ROOM){
						
					$value['total_charge'] = $value['amount'] * $room_pax_total['nr_rooms'];
						
				}
				
				if($value['charge_type'] == SUR_PER_ROOM_PER_NIGHT){
						
					$value['total_charge'] = $value['amount'] * count($apply_dates) * $room_pax_total['nr_rooms'];
						
				}
				
				if($value['charge_type'] == SUR_PER_ROOM_PRICE){ // % room-price
				
					$value['total_charge'] = bpv_round_rate($value['amount'] / 100 * $room_pax_total['total_room_rate']);
				
				}
				
				// store for saving Service Reservation to DB
				$sr_desc = 'Charge: '.number_format($value['amount']).'/'.get_surcharge_unit($value);
				$sr_desc .= "\n".'For: '.get_surcharge_apply($value, $room_pax_total);
				$value['sr_desc'] = $sr_desc;
				
				$ret[] = $value;
				
			}
			
		}
		
		return $ret;
		
	}
	
	function _load_check_rate_form($data, $is_mobile = false){
		
		$data['max_nights'] = $this->config->item('max_nights');
		
		$data['action'] = $this->input->get('action');
		
		$data['check_rate_info'] = get_hotel_check_rate_info();
		
		if($is_mobile){
			$data['check_rate_form'] = $this->load->view('mobile/hotels/hotel_detail/check_rate_form', $data, TRUE);
		} else {
			$data['check_rate_form'] = $this->load->view('hotels/hotel_detail/check_rate_form', $data, TRUE);
		}
		
		return $data;
		
	}
	
	function _load_similar_hotels($data, $is_mobile = false){
		
		$hotel = $data['hotel'];
		
		$check_rate_info = $data['check_rate_info'];
		
		/**
		 * Khuyenpv: set for url params in View More Link
		 */
		$url_params['destination'] = $hotel['destination_name'];
		$url_params['startdate'] = $check_rate_info['startdate'];
		$url_params['night'] = $check_rate_info['night'];
		$url_params['enddate'] = $check_rate_info['enddate'];
		$url_params['oid'] = 'd-'.$hotel['destination_id'];
		$data['url_params'] = $url_params;
		
		
		$data['s_hotels'] = $this->Hotel_Model->get_similar_hotels($hotel, $check_rate_info['startdate']);
		
		if($is_mobile){

			$data['similar_hotels'] = $this->load->view('mobile/hotels/hotel_detail/similar_hotels', $data, TRUE);
			
		} else {
			
			$data['similar_hotels'] = $this->load->view('hotels/hotel_detail/similar_hotels', $data, TRUE);
			
			$data['same_class_hotels'] = $this->load->view('hotels/hotel_detail/similar_hotels_on_left', $data, TRUE);
				
		}
		
		return $data;
	}
	
	function _restructure_facilities($hotel_facilites, $facility_groups){
		
		$ret = $facility_groups;
		
		foreach ($facility_groups as $key=>$value){
			$ret[$key] = array();
		}
		
		foreach ($hotel_facilites as $facility){
			$ret[$facility['group_id']][] = $facility;
		}
		
		$fas = array();
		foreach ($ret as $key=>$value){
			if(count($value) > 0) $fas[$key] = $value;
		}
		
		return $fas;
		
	}
	
	function _get_highlight_facilities($hotel_facilites){
		$ret = array();
		foreach ($hotel_facilites as $value){
			if($value['is_important']){
				$ret[] = $value;
			}
		}
		return $ret;
	}
	
	function _calculate_room_rates($room_types, $room_rates, $default_cancellation, $non_refundable_cancellation, $hotel_promotions, $check_rate_info){
		
		$ret = array();
		
		$startdate = $check_rate_info['startdate'];
		$enddate = $check_rate_info['enddate'];
		
		$stay_dates = get_days_between_2_dates($startdate, $enddate);
		
		foreach ($room_types as $room_type){
			
			$basic_rate = $this->_calculate_basic_rate($room_type, $room_rates, $stay_dates);
			
			$extrabed_rate = $this->_calculate_extrabed_rate($room_type, $room_rates, $stay_dates);
			
			$room_type['extrabed_rate'] = $extrabed_rate;
			
			if(empty($basic_rate)){
				
				$room_type['has_basic_price'] = false;
				
				$room_type['basic_rate'] = array($room_type['max_occupancy']=>array());
				$room_type['sell_rate'] = array($room_type['max_occupancy']=>array());
				
				$room_type['cancellation'] = $default_cancellation;
				
				$ret[] = $room_type;
			
			} else {
				/*
				 * Khuyenpv: 1/7/2014 
				 * Show each promotion has 'show_on_web' is a seperated rate
				 * Apply other promotions on date-range
				 * 
				$pro_apply = array();
					
				foreach ($hotel_promotions as $pro){
					
					$pro_rate = $this->_calculate_promotion_rate($basic_rate, $pro, $stay_dates, $room_type['id']);
					
					if(!empty($pro_rate)){
				
						
						$pro_rt = $room_type;
						
						$pro_rt['sell_rate'] = $pro_rate;
						
						$pro_rt['promotion'] = $pro;
						
						$pro_rt['has_basic_price'] = true;
						
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
					
				}*/
				
				$pro_apply = $this->_restructure_promotion_rate($hotel_promotions, $basic_rate, $stay_dates, $room_type['id']);
				
				foreach ($pro_apply as $pro){
					
					$pro_rt = $room_type;
					
					$pro_rt['sell_rate'] = $pro['pro_rate'];
					
					$pro_rt['promotion'] = $pro;
					
					$pro_rt['has_basic_price'] = true;
					
					$pro_rt['basic_rate'] = $basic_rate;
					
					$can['id'] = $pro['cancellation_id'];
					$can['name'] = $pro['cancellation_name'];
					$can['fit'] = $pro['fit'];
					$can['fit_cutoff'] = $pro['fit_cutoff'];
					$can['content'] = $pro['content'];
					
					$pro_rt['cancellation'] = $can;
					
					$ret[] = $pro_rt;
					
				}
				
				$rt = $room_type;
				
				$rt['has_basic_price'] = true;
				
				$rt['basic_rate'] = $basic_rate;
				
				$rt['sell_rate'] = $basic_rate;
				
				$rt['cancellation'] = $default_cancellation;
				
				if(!$this->_has_promotion_same_condition($default_cancellation, $pro_apply)){
					$ret[] = $rt;
				}
				
			}

		}
		/*
		 * 04.06.2014: Khuyenpv: Don't check promotion cancellation by date booking
		 * 
		foreach ($ret as $key => $value){
			
			$value['cancellation'] = is_non_refundable_by_date($value['cancellation'], $startdate) ? $non_refundable_cancellation : $value['cancellation'];
			
			$ret[$key] = $value;
		}*/
		
		return $ret;
	}
	
	/**
	 * Restructure hotel promotions for rate display on the check-rate form
	 * Each 'show_on_website = yes' promotion is a rate
	 * Each 'show_on_website = no' promotion with different cencallation is a rate
	 * @param unknown $hotel_promotions
	 */
	function _restructure_promotion_rate($hotel_promotions, $basic_rate, $stay_dates, $room_type_id){
		
		$pro_apply = array();
		
		$tmp_hot_deal_pro = array();
		$tmp_pulic_off_pro = array();
		$tmp_pro = array();
		
		foreach ($hotel_promotions as $pro){
			$pro_rate = $this->_calculate_promotion_rate($basic_rate, $pro, $stay_dates, $room_type_id);
			if(!empty($pro_rate)){
				
				$pro['pro_rate'] = $pro_rate;
				
				if($pro['show_on_web'] == STATUS_ACTIVE){
					
					$tmp_hot_deal_pro[] = $pro;
					
				} else {
					
					$tmp_pulic_off_pro[] = $pro;
					
				}
				
				$tmp_pro[] = $pro;
			
			}
		}
		
		// each hot-deal promotion is a seperate rate
		foreach ($tmp_hot_deal_pro as $pro){
			
			$pro_apply[] = $pro;
		}
		
		// the promotion with min diffrent cancellation is a seperated rate
		if(count($tmp_pulic_off_pro) > 0){
			
			$tmp_cans = array();
			
			foreach ($tmp_pulic_off_pro as $pro){
				
				if(count($tmp_cans) == 0 || !in_array($pro['cancellation_id'], $tmp_cans)){
					
					$pro_apply[] = $pro;
					
					$tmp_cans[] = $pro['cancellation_id'];
				}
	
			}		
		}
		
		foreach ($pro_apply as $key=>$pro){
			
			$pro_rate = $pro['pro_rate'];
			
			foreach ($pro_rate as $occupancy => $rates){
				
				foreach ($rates as $stay_date => $room_rate_in_date){
					
					// if the date not in the promotion, get the min rate from other promotions
					if(!$this->_has_promotion_in_date($pro, $stay_date)){
						
						$min_room_rate_in_date = $room_rate_in_date;
						
						foreach ($tmp_pro as $other_pro){
							
							if($other_pro['id'] != $pro['id'] && $this->_has_promotion_in_date($other_pro, $stay_date)){
								
								// only get from the promotion with more easy cancellation
								if($other_pro['fit_cutoff'] >= $pro['fit_cutoff']){
									
									$other_pro_rate = $other_pro['pro_rate'];
									
									if(isset($other_pro_rate[$occupancy])){
										
										$other_rates = $other_pro_rate[$occupancy];
										
										if(isset($other_rates[$stay_date]) && $min_room_rate_in_date > $other_rates[$stay_date]){
											
											$min_room_rate_in_date = $other_rates[$stay_date];
										}
										
									}
									
								}
								
							}
							
						}
						
						$rates[$stay_date] = $min_room_rate_in_date;
						
					}
					
				}
				
				$pro_rate[$occupancy] = $rates;
			}
			
			$pro['pro_rate'] = $pro_rate;
			
			$pro_apply[$key] = $pro;
		}
		
		
		return $pro_apply;
	}
	
	function _calculate_extrabed_rate($room_type, $room_rates, $stay_dates){
		
		$extrabed_rate = array();
		
		foreach ($stay_dates as $stay_date){
			
			$rate = $this->_get_room_rate_in_date($room_type['id'], $stay_date, $room_rates);
			
			if(!empty($rate) && !is_null($rate['extra_bed_rate'])){
				
				$extrabed_rate[$stay_date] = $rate['extra_bed_rate'];
				
			}
		}
		
		return $extrabed_rate;
	}
	
	function _calculate_basic_rate($room_type, $room_rates, $stay_dates){
		
		$basic_rate = array();
		
		if($room_type['max_occupancy'] >= SINGLE){
		
			$single_rates = array();
		
			foreach ($stay_dates as $stay_date){
		
				$rate = $this->_get_room_rate_in_date($room_type['id'], $stay_date, $room_rates);
		
				if(!empty($rate) && !empty($rate['single_rate'])){
		
					$single_rates[$stay_date] =  $rate['single_rate'];
		
				} else {
		
					$single_rates = array();
		
					break;
				}
		
			}
				
			if(count($single_rates) > 0){
					
				$basic_rate[SINGLE] = $single_rates;
			}
		
		}
		
		if($room_type['max_occupancy'] >= DOUBLE){
		
			$double_rates = array();
		
			foreach ($stay_dates as $stay_date){
		
				$rate = $this->_get_room_rate_in_date($room_type['id'], $stay_date, $room_rates);
		
				if(!empty($rate) && !empty($rate['double_rate'])){
		
					$double_rates[$stay_date] = $rate['double_rate'];
		
				} else {
		
					$double_rates = array();
		
					break;
				}
		
			}
			
			if(count($double_rates) > 0){
					
				$basic_rate[DOUBLE] = $double_rates;
			}
		
		}
		
		if($room_type['max_occupancy'] >= TRIPLE){
		
			$tripple_rates = array();
		
			foreach ($stay_dates as $stay_date){
		
				$rate = $this->_get_room_rate_in_date($room_type['id'], $stay_date, $room_rates);
		
				if(!empty($rate) && !empty($rate['triple_rate'])){
		
					$tripple_rates[$stay_date] =  $rate['triple_rate'];
		
				} else {
		
					$tripple_rates = array();
		
					break;
				}
		
			}
				
			if(count($tripple_rates) > 0){
					
				$basic_rate[TRIPLE] = $tripple_rates;
			}
		
		}
		
		if($room_type['max_occupancy'] > TRIPLE){
				
			$full_occupancy_rates = array();
				
			foreach ($stay_dates as $stay_date){
		
				$rate = $this->_get_room_rate_in_date($room_type['id'], $stay_date, $room_rates);
		
				if(!empty($rate) && !empty($rate['full_occupancy_rate'])){
						
					$full_occupancy_rates[$stay_date] = $rate['full_occupancy_rate'];
						
				} else {
						
					$full_occupancy_rates = array();
						
					break;
				}
		
			}
				
			if(count($full_occupancy_rates) > 0){
		
				$basic_rate[$room_type['max_occupancy']] = $full_occupancy_rates;
			}
				
		}
		
		
		return $basic_rate;
		
	}
	
	function _calculate_promotion_rate($basic_rate, $pro, $stay_dates, $room_type_id){
		
		$pro_rate = array();
		
		$apply_for_room_type = false;
		
		$room_type_get = NULL;
		
		if($pro['room_type'] == 1){ // apply for all room type
			$apply_for_room_type = true;
		} else {
			if(count($pro['room_types']) > 0){
				
				foreach ($pro['room_types'] as $pro_room_type){
					
					if($pro_room_type['room_type_id'] == $room_type_id){
						$apply_for_room_type = true;
						
						$room_type_get = $pro_room_type['get'];
						
						break;
					}
					
				}
				
			}
		}
		
		if(!$apply_for_room_type){
			
			return $pro_rate;
		}
		
		
		$cnt_night = 0;
		foreach ($stay_dates as $stay_date){
			
			if($this->_has_promotion_in_date($pro, $stay_date)){
				
				$cnt_night++;
				
			}
			
		}
		
		// number of night condition
		if($cnt_night > 0 && $cnt_night >= $pro['minimum_stay'] && (is_null($pro['maximum_stay']) || $pro['maximum_stay'] == 0 || $cnt_night <= $pro['maximum_stay'])){
			
			$pro_rate = $this->_get_promotion_rate($basic_rate, $pro, $cnt_night, $room_type_get);
		}
		
		return $pro_rate;
	}
	
	function _get_room_rate_in_date($room_type_id, $stay_date, $room_rates){
		
		$rate = '';
		
		foreach ($room_rates as $value){
		
			if($value['date'] == $stay_date && $value['room_type_id'] == $room_type_id){
				
				return $value;
			}
			
		}
		
		return '';
		
	}
	
	function _has_promotion_in_date($pro, $stay_date){
		
		if($pro['stay_date_from'] <= $stay_date && $pro['stay_date_to'] >= $stay_date){
		
			if(is_bit_value_contain($pro['check_in_on'], date('w',strtotime($stay_date)))){
					
				return true;
					
			}
		
		}
		
		return false;
		
	}
	
	function _get_promotion_rate($basic_rate, $pro, $cnt_night, $room_type_get = NULL){
		$pro_rate = array();
		
		if($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT){

			$pro_rate = $this->_get_discount_rate($basic_rate, $pro, false, $room_type_get);
		}
		
		if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_BOOKING){
			if($pro['get_1'] > 0){
				
				$pro_rate = $basic_rate;
			}
		}
		
		
		if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_NIGHT){
			
			$pro_rate = $this->_get_discount_rate($basic_rate, $pro, true);
			
		}
		
		// stay ($pro['minimum_stay']) nights get ($pro['get_1']) free nights
		if($pro['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT){
			
			$pro_rate = $this->_get_free_night_rate($basic_rate, $pro, $cnt_night, $room_type_get);
		}
		
		return $pro_rate;
	}
	
	function _get_free_night_rate($basic_rate, $pro, $cnt_night, $room_type_get = NULL){
		
		$pro_rate = array();
		
		$free_night = $pro['get_1'];
		
		// specific room type get
		if(!is_null($room_type_get)){
			$free_night = $room_type_get;
		}
		
			
		// correct setting
		if($free_night > 0 && $pro['minimum_stay'] > 0 && $free_night < $pro['minimum_stay'] && $cnt_night >= $pro['minimum_stay']){
		
			$pro_rate = $basic_rate;
		
			foreach ($pro_rate as $key=>$rates){
					
				$apply_dates = array();
					
				$free_dates = array();
					
				foreach ($rates as $date=>$rate_value){
		
					if($this->_has_promotion_in_date($pro, $date)){
						$apply_dates[] = $date;
					}
				}
				
				// only get free night when number of night is greater or equal minimum stays
				$cnt_night = count($apply_dates);
				if($cnt_night >= $pro['minimum_stay']){
					
					$block_dates = array_chunk($apply_dates, $pro['minimum_stay']);
					
					foreach ($block_dates as $block){
						
						// get free night when block greater or equal minnimum stays
						if(count($block) == $pro['minimum_stay']){
							
							if($pro['apply_on'] == APPLY_ON_EVERY_NIGHT){ // any night: free on first nights
								
								for ($i=0; $i < $free_night; $i++){
									
									if(isset($block[$i])){
										
										$free_dates[] = $block[$i];
									}
									
								}
						
							}elseif($pro['apply_on'] == APPLY_ON_LAST_NIGHT){ // free on last night of each block
								
								for ($i=0; $i < $free_night; $i++){
									$index = count($block) - $i - 1;
									if(isset($block[$index])){
								
										$free_dates[] = $block[$index];
									}
										
								}
								
							}
							
							if($pro['recurring_benefit'] == ONCE_TIME_PER_BOOKING){
								break;
							}
							
						}
						
					}
					
				}

				
					
				foreach ($rates as $date=>$rate_value){
						
					if(count($free_dates) > 0 && in_array($date, $free_dates)){
							
						$rates[$date] = 0; //free night
							
					}
		
				}
		
					
				$pro_rate[$key] = $rates;
			}
		
		}
		
		
		return $pro_rate;
		
	}
	
	function _get_discount_rate($basic_rate, $pro, $is_ammount_discount = false, $room_type_get = NULL){
		 
		$pro_rate = $basic_rate;
		
		foreach ($pro_rate as $key=>$rates){

			$apply_dates = array();
			
			foreach ($rates as $date=>$rate_value){
		
				if($this->_has_promotion_in_date($pro, $date)){
					
					$apply_dates[] = $date;
						
				}
			}
			
			
			$discount = $pro['get_1'];
			
			//overide the discount value from the specific room type
			if($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT && !is_null($room_type_get)){
				if($pro['apply_on'] == APPLY_ON_EVERY_NIGHT || $pro['apply_on'] == APPLY_ON_FIRST_NIGHT || $pro['apply_on'] == APPLY_ON_LAST_NIGHT){
					$discount = $room_type_get;
				}
			}
			
			
			if(count($apply_dates) > 0){
				
				if($pro['apply_on'] == APPLY_ON_FIRST_NIGHT || $pro['apply_on'] == APPLY_ON_LAST_NIGHT){
					
					if($pro['apply_on'] == APPLY_ON_FIRST_NIGHT){
						$a_date = $apply_dates[0];
					} else {
						$a_date = $apply_dates[count($apply_dates) - 1];
					}
					
					$rate_value = $rates[$a_date];
					
					if($discount > 0){
						
						if($is_ammount_discount){
							
							$rate_value = $rate_value - $discount;
							
							if($rate_value < 0) $rate_value = 0;
							
						} else {
							
							$rate_value = $rate_value * (100 - $discount)/100;
							
						}
						
						$rate_value = bpv_round_rate($rate_value);
					}
					
					$rates[$a_date] = $rate_value;
					
				}
				
				if($pro['apply_on'] == APPLY_ON_EVERY_NIGHT || $pro['apply_on'] == APPLY_ON_SPECIFIC_DAY){
					
					foreach ($apply_dates as $a_date){
						
						if($pro['apply_on'] == APPLY_ON_SPECIFIC_DAY){
							
							$nr_day = date('w',strtotime($a_date));
							
							if($nr_day == 0) $nr_day = 7;
							
							$discount = $pro['get_'.$nr_day];
						}
						
						$rate_value = $rates[$a_date];
						if($discount > 0){
						
							if($is_ammount_discount){
								
								$rate_value = $rate_value - $discount;
								
								if($rate_value < 0) $rate_value = 0;
								
							} else {
								
								$rate_value = $rate_value * (100 - $discount)/100;
								
							}
						
							$rate_value = bpv_round_rate($rate_value);
						}
							
						$rates[$a_date] = $rate_value;
						
					}
					
				}
				
				if($pro['apply_on'] == APPLY_ON_SPECIFIC_NIGHT){
					$index = 1;
					foreach ($apply_dates as $a_date){
						
						$discount = $pro['get_'.$index];
						
						$index++;
						
						if($pro['recurring_benefit'] == ONCE_TIME_PER_BOOKING){
							if($index > 7){
								break;
							}
						} else {
							if($index > 7){
								$index = 1;
							}
						}
						
						$rate_value = $rates[$a_date];
						if($discount > 0){
						
							if($is_ammount_discount){
								
								$rate_value = $rate_value - $discount;
								
								if($rate_value < 0) $rate_value = 0;
								
							} else {
								
								$rate_value = $rate_value * (100 - $discount)/100;
								
							}
						
							$rate_value = bpv_round_rate($rate_value);
						}
							
						$rates[$a_date] = $rate_value;
					}
				}
				
			}
			
			
			$pro_rate[$key] = $rates;
		}

		return $pro_rate;
	}
	
	function _has_promotion_same_condition($default_cancellation, $hotel_promotions){
		
		$ret = false;
		
		if(!empty($default_cancellation)){
		
			foreach ($hotel_promotions as $pro){
				if($pro['cancellation_id'] == $default_cancellation['id']) return true;	
			}
		
		}
		
		return $ret;
		
	}
	
	function _count_rate_rows($room_rates){
		
		$index = 0;
		
		foreach ($room_rates as $room_rate){
			
			foreach ($room_rate['sell_rate'] as $rate){
				
				$index++;
				
			}
			
		}
		
		
		return $index;
		
	}
	
	/**
	 *  HOTEL - BOOKING - FUNCTIONS
	 * 
	 */
	
	function _go_back_to_check_rate($hotel, $check_rate_info){
		
		$check_rate_info['action'] = ACTION_CHECK_RATE;
		redirect(hotel_build_url($hotel, $check_rate_info));
		exit();
		
	}
	
	function _get_selected_room_rates($room_rates, $nr_rooms){
		
		$ret = array();
		
		foreach ($room_rates as $key=>$room_rate){
			
			
			$sell_rates = $room_rate['sell_rate'];
		
			foreach ($sell_rates as $occupancy => $rate){
				$room_rate['occupancy'] = $occupancy;
				$id = 'nr_room_'.get_room_rate_id($room_rate);
				
				if(isset($nr_rooms[$id]) && $nr_rooms[$id] > 0){
					
					$selected_rate['room_rate_info'] = $room_rate;
					
					$selected_rate['sell_rate'] = $rate;
					
					$selected_rate['nr_room'] = $nr_rooms[$id];
					
					$ret[] = $selected_rate;
					
				}
			}
		}
		
		return $ret;
		
	}
	
	function _calculate_room_pax_total($selected_room_rates){
		
		$max_adults = 0;
		$max_children = 0;
		$number_rooms = 0;
		$max_extra_beds = 0;
		
		$total_room_rate = 0;
	
		foreach ($selected_room_rates as $value){
			
			$room_rate = $value['room_rate_info'];
				
			$occupancy = $room_rate['occupancy'];
			
			$nr_room = $value['nr_room'];
			
			$sell_rate = $value['sell_rate'];
			
			$max_adults += $occupancy * $nr_room;
			
			$max_children += $room_rate['max_children'] * $nr_room;
			
			$max_extra_beds += $room_rate['max_extra_beds'] * $nr_room;
			
			$number_rooms += $nr_room;
			
			$total_room_rate += count_total_room_rate($sell_rate) * $nr_room;
		}
		
		$ret['max_adults'] = $max_adults;
		$ret['max_children'] = $max_children;
		$ret['max_extra_beds'] = $max_extra_beds;
		$ret['nr_rooms'] = $number_rooms;
		
		$ret['total_room_rate'] = $total_room_rate;
		
		
		$action = $this->input->post('action');
		if($action == ACTION_MAKE_BOOKING){
			// get number of adults from user input
			$adults = $this->input->post('adults');
			$children = $this->input->post('children');
			$infants = $this->input->post('infants');
			
			$ret['max_adults'] = $adults;
			$ret['max_children'] = $children;
		}
		
		return $ret;
	}
	
	function _count_total_payments($selected_room_rates, $surcharges){
		
		/**
 		 * Count total payment
		 */
		
		$total_payment_origin = 0;
		$total_payment = 0;
		
		foreach ($selected_room_rates as $value){
			
			$room_rate = $value['room_rate_info'];
			
			$occupancy = $room_rate['occupancy'];
			
			$basic_rate = $room_rate['basic_rate'][$occupancy];
			
			$sell_rate = $value['sell_rate'];
			
			$nr_room = $value['nr_room'];
			
			$total_rate_origin = count_total_room_rate($basic_rate) * $nr_room;
			
			$total_rate = count_total_room_rate($sell_rate) * $nr_room;
			
			
			$total_payment_origin += $total_rate_origin;
			
			$total_payment += $total_rate;
		}
		
		foreach ($surcharges as $value){
			$total_payment_origin += $value['total_charge'];
				
			$total_payment += $value['total_charge'];
		}
		
		$payment_detail['total_payment_origin'] = $total_payment_origin;
		$payment_detail['total_discount'] = $total_payment_origin - $total_payment;
		$payment_detail['total_payment'] = $total_payment;
		
		
		/**
		 * Count total price of each room-type
		 */
		
		$temp_arr = array();
		
		foreach ($selected_room_rates as $value){
			$room_rate = $value['room_rate_info'];
			
			$temp_arr[$room_rate['id'].'_'.$room_rate['occupancy']][] = $value;
		}
		
		$room_types = array();
		
		foreach ($temp_arr as $temp_value){
			
			$total_nr_room = 0;
			$total_rate_origin = 0;
			$total_rate = 0;
			
			foreach ($temp_value as $value){
			
				$room_rate = $value['room_rate_info'];
					
				$occupancy = $room_rate['occupancy'];
					
				$basic_rate = $room_rate['basic_rate'][$occupancy];
					
				$sell_rate = $value['sell_rate'];
					
				$nr_room = $value['nr_room'];
					
				$total_rate_origin += count_total_room_rate($basic_rate) * $nr_room;
					
				$total_rate += count_total_room_rate($sell_rate) * $nr_room;
				
				$total_nr_room += $nr_room;
				
				$name  = get_room_rate_name($room_rate);

			}
			
			$rt['name'] = $name;
			$rt['nr_room'] = $total_nr_room;
			$rt['total_rate_origin'] = $total_rate_origin;
			$rt['total_rate'] = $total_rate;
			
			$room_types[] = $rt;
			
		}
		
		$payment_detail['room_types'] = $room_types;
		
		
		$payment_detail['surcharges'] = $surcharges;
		
		
		return $payment_detail;
	}
	
	function _make_booking($hotel, $startdate, $enddate, $selected_room_rates, $surcharges, $room_pax_total, $check_rate_info){
		
		$customer = get_contact_post_data();
		
		$customer_id = $this->Booking_Model->create_or_update_customer($customer);
		
		$special_request = $customer['special_request'];
		
		
		// payment method
		$payment_info['method'] = $this->input->post('payment_method');
		$payment_info['bank'] = $this->input->post('payment_bank');
		
		// promotion code
		$promotion_code = $this->input->post('promotion_code');
		$code_discount_info = get_pro_code_discount_info($promotion_code, HOTEL, $hotel['id'], '', '', '', $customer['phone']);
		
		$customer_booking = get_hotel_customer_booking($hotel['id'], $startdate, $enddate, $customer_id, $special_request, $payment_info, $code_discount_info);
		
		$service_reservations = get_hotel_service_reservations($hotel, $startdate, $enddate, $selected_room_rates, $surcharges, $code_discount_info);
		
		$customer_booking_id = $this->Booking_Model->save_customer_booking($customer_booking, $service_reservations);
		
		// set voucher used
		if (! empty($code_discount_info) && $code_discount_info['pro_type'] == 2)
        {
            $this->Booking_Model->update_voucher_code_used($promotion_code, $customer_id);
        }
        elseif (! empty($code_discount_info)) // promotion
        { 
            $this->Booking_Model->update_pro_code_used($promotion_code, $code_discount_info, $customer_id);
        }
		
		$this->_send_mail($check_rate_info, $hotel, $selected_room_rates, $surcharges, $room_pax_total, $customer, $code_discount_info, $customer_booking_id);
	
	}
	
	function _send_mail($check_rate_info, $hotel, $selected_room_rates, $surcharges, $room_pax_total, $customer, $code_discount_info, $customer_booking_id){
	
		$CI =& get_instance();
	
		
		$data['check_rate_info'] = $check_rate_info;
		
		$data['hotel'] = $hotel;
		
		$data['selected_room_rates'] = $selected_room_rates;
		
		$data['surcharges'] = $surcharges;
		
		$data['room_pax_total'] = $room_pax_total;
		
		$data['customer'] = $customer;
		
		$data['code_discount_info'] = $code_discount_info;
		
		$data['customer_booking_id'] = $customer_booking_id;
	
	
		$content = $this->load->view('hotels/common/hotel_booking_mail', $data, TRUE);
		
		//echo $content; exit();
		
		$CI->load->library('email');
		/*
		$config['protocol']='smtp';
		$config['smtp_host']='host140.hostmonster.com';//'74.220.207.140';
		$config['smtp_port']='25';
		$config['smtp_timeout']='30';
		$config['smtp_user']='booking@snotevn.com:8888';
		$config['smtp_pass']='Bpt052010';
		*/
		
		$config['protocol'] = 'mail';
		$config['charset']='utf-8';
		$config['newline']="\r\n";
		$config['mailtype'] = 'html';
		
		// send to customer
		$CI->email->initialize($config);
	
		$CI->email->from('booking@'.strtolower(SITE_NAME), BRANCH_NAME);
	
		$CI->email->to($customer['email']);
	
		$subject = lang('hb_email_reply').': ' . $hotel['name'] . ' - '. BRANCH_NAME;
		$CI->email->subject($subject);
	
		$CI->email->message($content);
	
		if (!$CI->email->send()){
			log_message('error', '[ERROR]hotel_boooking: Can not send email to '.$customer['email']);
		}
	
			
		$config = array();
		$config['protocol'] = 'mail';
		$config['charset']='utf-8';
		$config['newline']="\r\n";
		$config['mailtype'] = 'html';
		
		// send to customer
		$CI->email->initialize($config);
	
		$CI->email->from($customer['email'], $customer['full_name']);
	
		$CI->email->to('bestpricebooking@gmail.com');
	
		$subject = lang('hb_email_reply').': ' . $hotel['name']. ' - '. $customer['full_name'];
		$CI->email->subject($subject);
	
		$CI->email->message($content);
	
		if (!$CI->email->send()){
			log_message('error', '[ERROR]hotel_boooking: Can not send email to bestpricevn@gmail.com');
		}
	
		return true;
	}

}

/* End of file welcome.php 
/* Location: ./application/controllers/home.php */