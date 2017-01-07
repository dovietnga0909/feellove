<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flights extends CI_Controller {
	
	public function __construct()
    {
        
       	parent::__construct();
       	
       	$this->load->helper(array('form','flight','hotel','booking','payment','land_tour'));
       	
		$this->load->language('flight');	
		
		$this->load->model(array('Flight_Model', 'Hotel_Model', 'Land_Tour_Model'));
		$this->load->model('Advertise_Model');
		$this->load->model('News_Model');
		$this->load->model('Booking_Model');
		$this->load->model('Destination_Model');
		
		$this->config->load('flight_meta');
		
		//$this->output->enable_profiler(TRUE);
	
	}
	
	function index()
	{	
		//set_cache_html();
		
		$is_mobile = is_mobile();
		
		$data = $this->_set_common_data(array(), $is_mobile);
		
		$data['meta'] = get_header_meta(FLIGHT_HOME_PAGE);
		
		// Set data
		$data = build_search_criteria($data, MODULE_FLIGHT, $is_mobile);
		
		$data['popular_routes'] = $this->Flight_Model->get_popular_fights();
		
		// get advertises
		$data['bpv_ads'] = load_bpv_ads(AD_PAGE_FLIGHT);
		
		$data['bpv_why_us'] = load_bpv_why_us(FLIGHT_HOME_PAGE);
		
		// get news
		$data['lst_news'] = $this->News_Model->get_news(M_FLIGHT);
		
		$data['n_book_together'] = $this->News_Model->get_news_details(10);
		
		// Footer flight links
		$data = load_footer_links($data);
		
		
		if($is_mobile){
			$data = get_library('flexsilder', $data);
			_render_view('mobile/flights/flight_home', $data, $is_mobile);
			
		} else {
			
			// Render view
			$data['search_dialog'] = $this->load->view('flights/flight_search/flight_search_dialog', $data, TRUE);
			
			$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
			
			_render_view('flights/flight_home', $data);
		}
	
	}
	
	function search(){
		
		$is_mobile = is_mobile();
		$mobile_view = $is_mobile ? 'mobile/': '';
	
		$search_criteria = flight_build_search_criteria();
	
		if(!$search_criteria){
			redirect(get_url(FLIGHT_HOME_PAGE));
			exit();
		}
		
		
		$data = $this->_set_common_data(array(), $is_mobile);
		
		$data['meta'] = get_header_meta(FLIGHT_SEARCH_PAGE);
		
		$data['search_criteria'] = $search_criteria;
	
		$this->session->set_userdata(FLIGHT_SEARCH_CRITERIA, $search_criteria); // save current search to session
		
		/**
		 * 
		 * 02.07.2014: save the search information to the Flight Booking Session
		 * 
		 */
		$sid = get_flight_booking_sid($search_criteria);
		
		// save search criteria of this session
		set_flight_session_data($sid, FLIGHT_SEARCH_CRITERIA, $search_criteria);
		
		
		$data['sid'] = $sid; // session id of the current search
		$data['flight_type'] = FLIGHT_TYPE_DEPART;

		
		$exception_code = $this->_get_search_exception_code($data);
		
		$data['exception_code'] = $exception_code;
		
		
		$data['flight_search_overview'] = $this->load->view($mobile_view.'flights/flight_search/flight_search_overview', $data, TRUE);
		
		$data['flight_search_form'] = $this->load->view($mobile_view.'flights/flight_search/flight_search_form', $data, TRUE);
		
		$data = $this->_load_filter_data($data, $is_mobile);
		
		if($exception_code == 0){
		
			$data['bpv_content'] = $this->load->view($mobile_view.'flights/flight_search/search_results', $data, TRUE);
		
		} else {
			$object['code'] = $exception_code;
			
			if($exception_code == 1){
				$object['message'] = 'Over-9-Passengers';
			}
			if($exception_code == 2){
				$object['message'] = 'Infants-Over-Adults';
			}
			if($exception_code == 3){
				$object['message'] = 'Internaltinal Flights';
			}
			
			if($exception_code == 4){
				$object['message'] = 'No-Flight-Route';
			}
			
			if($exception_code == 5){
				$object['message'] = 'Departure-In-The-Past';
			}
			
			if($exception_code == 6){
				$object['message'] = 'Return-Date-Over-Depart';
			}
			
			redirect(get_url(FLIGHT_EXCEPTION_PAGE, $object));
			
		}
		
		$this->load->view($mobile_view.'_templates/bpv_layout', $data);

	}
	
	function exception(){
		
		$is_mobile = is_mobile();
		$mobile_view = $is_mobile ? 'mobile/': '';
		
		$exception_code = $this->input->get('code');
		
		$data = $this->_set_common_data();
		
		$search_criteria = get_flight_search_criteria();
		
		$data['exception_code'] = $exception_code;
		
		$data['search_criteria'] = $search_criteria;
		
		$data['flight_search_form'] = $this->load->view($mobile_view.'flights/flight_search/flight_search_form', $data, TRUE);
		
		$data['exception_message'] = lang('exception_message');
		
		if($exception_code == 1){ // over 9 passengers
			$data['exception_message'] = lang_arg('exception_message_1', FLIGHT_PASSENGER_LIMIT);
		}
		if($exception_code == 2){ // infants > adults
			$data['exception_message'] = lang('exception_message_2');
		}
		if($exception_code == 3){ // book international flights
			$data['exception_message'] = lang('exception_message_3');
		}
		
		if($exception_code == 4){ // No Flight Route
			$data['exception_message'] = lang_arg('exception_message_4', $search_criteria['From'], $search_criteria['To']);
		}
		
		if($exception_code == 5){ // Departure In The Past
			$data['exception_message'] = lang_arg('exception_message_5', $search_criteria['Depart']);
		}
		
		if($exception_code == 6){ // Return Date < Departure Date
			$data['exception_message'] = lang_arg('exception_message_6', $search_criteria['Return'], $search_criteria['Depart']);
		}
		
			
		$request = get_flight_exception_short_req($search_criteria);
			
		$data['contact_form'] = load_contact_form(true, $request,'',$is_mobile);
			
		$data['bpv_content'] = $this->load->view($mobile_view.'flights/flight_search/search_exceptions', $data, TRUE);
		
		$this->load->view($mobile_view.'_templates/bpv_layout', $data);
	}
	
	
	function get_flight_data(){
		

		$is_mobile = is_mobile();
		$mobile_view = $is_mobile ? 'mobile/' : '';
		
		// type of the flight: depart or return
		$flight_type = $this->input->post('flight_type');
		
		// session id of the current search
		$sid = $this->input->post('sid');
		
		// day_index (from the search calendar)
		$day_index = $this->input->post('day_index');
		
		// selected departure flight
		$departure_flight = $this->input->post('departure_flight');
		
		
		$is_domistic = true;

		// get search criteria in the flight search session structure
		$search_criteria = get_flight_session_data($sid, FLIGHT_SEARCH_CRITERIA);
		
		if(!empty($search_criteria)){
			
			$is_domistic = $search_criteria['is_domistic'];
			
			// get search criteria in the flight search session structure
			$vnisc_sid = $this->_get_vnisc_sid($sid, $search_criteria, $flight_type);
		
			if(!empty($vnisc_sid)){
				
				if($day_index != 0){
					$err_code = $this->_update_search_data_by_change_day($sid, $flight_type, $search_criteria, $vnisc_sid, $day_index);
					if($err_code != 0){
						$data['error_code'] = 2; // fail to get data
					}
					
					$search_criteria = get_flight_session_data($sid, FLIGHT_SEARCH_CRITERIA);
				}
				
				// save the VNISC SID into session for later use
				set_flight_session_data($sid, FLIGHT_VNISC_SID, $vnisc_sid);
					
				$flight_data_url = $this->config->item('flight_data_url');
					
				$flight_data_url .= '?sid='.$vnisc_sid;
					
					
				if ($is_domistic){
					$flight_data_url .= '&Do=GetFlightData';
				} else {
					$flight_data_url .= '&Do=GetFlightInternational';
				}
				
				
					
				$flight_data_url .= '&type='.$flight_type;
				$flight_data_url .= '&sort=price';
				$flight_data_url .= '&Output=JSON';
					
				
				if($is_domistic){
					
					// search domistic flights
					$flight_data = $this->_get_flight_domistic_data($flight_data_url, $flight_type, $search_criteria);
					
					// ok get flight data
					if(strpos($flight_data, FLIGHT_PROCESS_CONTINUE) !== false || strpos($flight_data, FLIGHT_PROCESS_COMPLETED) !== false){
						
						// remove <continue> or <complete> message before decoding JSON data
						$flight_data = str_replace(FLIGHT_PROCESS_CONTINUE, "", $flight_data);
						$flight_data = str_replace(FLIGHT_PROCESS_COMPLETED, "", $flight_data);
						
						$flight_data = json_decode($flight_data, true);
						
						$data['flight_data'] = $this->_restructure_domictic_flight_data($flight_data);
						
						// get all the airlines for creating filter by airlines
						$data['airlines'] = get_domistic_flight_airlines($flight_data);
						
						/*
						 * when the user change the date of return flight (on quick date calendar)
						 * the Flight-ID of the selected departure flight will be changed automatically on VNISC
						 * that why we need to search the departure flights again to update the Flight-ID of the selected
						 * dearture flight
						 * 
						 * Only apply for domistic flights
						 */ 
						
						
						if($flight_type == FLIGHT_TYPE_RETURN && $day_index != 0 && $departure_flight != ''){
							$t1 = microtime(true);
							$selected_departure = $this->_update_selected_departure_flight($vnisc_sid, $departure_flight, $search_criteria);
							$t2 = microtime(true);
							
							//echo ($t2 - $t1);exit();
							
							if($selected_departure != ''){
								
								$data['selected_departure'] = $selected_departure;
								
							}
							
						}
						
						
					} else{
						$data['error_code'] = 2; // fail to get data
						
						if($flight_data == FLIGHT_NO_FLIGHT){
							
							$data['error_code'] = 3; // all flight are sold out
							
						}
					}
					
				} else {
					
					// search internatinal flights
					$flight_data = $this->_get_flight_international_data($flight_data_url, $flight_type, $search_criteria);
					
					// ok get flight data
					if(is_valid_flight_data($flight_data)){
						
						$flight_data = json_decode($flight_data, true);
						
						// save the search data to session for later used
						set_flight_session_data($sid, FLIGHT_SEARCH_DATA, $flight_data);
						
						// restructure flight search data for easy diplaying on the view
						$data['flight_data'] = $this->_restructure_international_flight_data($flight_data, $search_criteria);
						
						// get all the airlines for creating filter by airlines
						$data['airlines'] = get_inter_flight_airlines($flight_data);
						
					} else {
						
						$data['error_code'] = 2; // fail to get data
						
						if($flight_data == FLIGHT_NO_FLIGHT){
								
							$data['error_code'] = 3; // all flight are sold out
									
						}
						
					}
				}
				
			} else {
				
				$data['error_code'] = 2; // fail to get data
				
				$this->load->library('user_agent');
				$agent = $this->agent->agent_string();
				
				$error_message = '[ERROR]get_flight_data(): Fail to get VNISC-ID from Session';
				
				$error_message .= '. Agent String = '.$agent;
				
				if($this->agent->is_mobile()){
					$error_message .= '<br> Mobile = '.$this->agent->mobile();
				}
					
				if($this->agent->is_browser()){
					$error_message .= '<br> Browser = '.$this->agent->browser().'; Version = '. $this->agent->version();
				}
					
				$error_message .= '<br> Platform = '.$this->agent->platform();
				
				log_message('error', $error_message);
				
				send_email_flight_error_notify($error_message, 2);
			} 
		
		} else {

			$data['error_code'] = 2; // fail to get data
			
			$this->load->library('user_agent');
			$agent = $this->agent->agent_string();
			
			$error_message = '[ERROR]get_flight_data(): Fail to get Search-Criteria from Session';
			
			$error_message .= '. Agent String = '.$agent;
			
			if($this->agent->is_mobile()){
				$error_message .= '<br> Mobile = '.$this->agent->mobile();
			}
			
			if($this->agent->is_browser()){
				$error_message .= '<br> Browser = '.$this->agent->browser().'; Version = '. $this->agent->version();
			}
			
			$error_message .= '<br> Platform = '.$this->agent->platform();
			
			log_message('error', $error_message);
			
			send_email_flight_error_notify($error_message, 2);
			
		}

		$data['is_mobile'] = $is_mobile;
	
		$data['domistic_airlines'] = $this->config->item('domistic_airlines');
	
		$data['is_domistic'] = $is_domistic;
	
		$data['flight_type'] = $flight_type;
		
		$data['sid'] = $sid;
	
		$data['search_criteria'] = $search_criteria;
		
		$data['sort_by'] = $this->config->item('sort_by');
		
		$data = $this->_load_filter_data($data, $is_mobile);
		
		$data['sort_by_view'] = $this->load->view($mobile_view.'flights/flight_search/search_sorts', $data, TRUE);
		
		if($is_domistic){
		
			$this->load->view($mobile_view.'flights/flight_search/flight_data_content', $data);
			
		} else {
			
			$this->load->view($mobile_view.'flights/flight_search/flight_data_content_international', $data);
			
		}
	
	}
	
	function _get_flight_domistic_data($flight_data_url, $flight_type, $search_criteria){
		
		$call_times = 0;
		$t1 = microtime(true);
			
		$flight_search_timeut = $this->config->item('flight_search_timeout');
		
		// store previous not-empty flight data
		$previous_flight_data = '';
		
		do{
			$flight_data = get_flight_data($flight_data_url, $flight_type);
		
			$is_continue = is_continue_get_data($flight_data);
		
			$t_search = microtime(true);
		
			// time-out on search flight data
			if($t_search - $t1 > $flight_search_timeut){
				$is_continue = false;
					
				log_message('error', '[ERROR]_get_flight_domistic_data(): Time Out on getting flight data');
			}
		
		
			// store the previous sucessfull get-ting flight data
		
			$flight_data = trim($flight_data);
		
			// only save the data that <continue> or <complete>
			if(strpos($flight_data, FLIGHT_PROCESS_CONTINUE) !== false || strpos($flight_data, FLIGHT_PROCESS_COMPLETED) !== false){
		
				$previous_flight_data = $flight_data;
		
				$previous_flight_data = str_replace(FLIGHT_PROCESS_CONTINUE, "", $previous_flight_data);
				$previous_flight_data = str_replace(FLIGHT_PROCESS_COMPLETED, "", $previous_flight_data);
		
			}
		
		
			$call_times++;
		
			if($is_continue){
				
				if($call_times <= 2){
					sleep(4);
				} else {
					sleep(3);
				}
			}
				
		}while ($is_continue);
		$t2 = microtime(true);

		log_message('error', '[INFO]_get_flight_domistic_data(): Number of calls = '. $call_times .'; Time Get Data = '.($t2 - $t1).' seconds; Submit URL = '.$flight_data_url. '; Search URL = '.get_flight_url($search_criteria));
		
		// Get JSON data from Vnisc
		$is_valid_flight_data = strpos($flight_data, FLIGHT_PROCESS_CONTINUE) !== false || strpos($flight_data, FLIGHT_PROCESS_COMPLETED) !== false;
		
		if(!$is_valid_flight_data){
		
			if ($flight_data == FLIGHT_CURL_ERROR){
				// do nothing, already log in the curl calling function
			} elseif($flight_data == FLIGHT_ERROR_TM){
				log_message('error', '[ERROR]_get_flight_domistic_data(): get Error-TM from VNISC');
		
			}elseif($flight_data == FLIGHT_ERROR_UN){
				log_message('error', '[ERROR]_get_flight_domistic_data(): get Error-UN from VNISC');
		
			}elseif($flight_data == FLIGHT_NO_FLIGHT){
					
				log_message('error', '[ERROR]_get_flight_domistic_data(): get NO_FLIGHT from VNISC');
					
			}elseif(strpos($flight_data, 'ERROR-') !== false){
				log_message('error', '[ERROR]_get_flight_domistic_data(): get '.$flight_data.' from VNISC');
			} else {
				log_message('error', '[ERROR]_get_flight_domistic_data(): get unexpected-error. Content return =  '.$flight_data.'');
			}
		
			if($previous_flight_data != ''){
				
				// get the previous-called flight data
				$flight_data = $previous_flight_data;
		
				log_message('error', '[ERROR]_get_flight_domistic_data(): Error getting data from VNISC But get previous data sucessfully');
			} else {
		
				$error_message = '[ERROR]_get_flight_domistic_data(): Error getting data from VNISC with empty data returned';
		
				log_message('error', $error_message);
		
				$error_message .= ' - Error Message = '.$flight_data;
		
				send_email_flight_error_notify($error_message);
		
			}
		}
		
		
		return $flight_data;
	}
	
	function _get_flight_international_data($flight_data_url, $flight_type, $search_criteria){
	
		$call_times = 0;
		$t1 = microtime(true);
			
		$flight_search_timeut = $this->config->item('flight_search_timeout_inter');
		
		do{
			$flight_data = get_flight_data($flight_data_url, $flight_type);
		
			$is_continue = is_continue_get_international_data($flight_data);
		
			$t_search = microtime(true);
		
			// time-out on search flight data
			if($t_search - $t1 > $flight_search_timeut){
				$is_continue = false;
					
				log_message('error', '[ERROR]_get_flight_international_data(): Time Out on getting flight data');
			}
		
		
			// store the previous sucessfull get-ting flight data
		
			$flight_data = trim($flight_data);
		
			$call_times++;
		
			if($is_continue){
				if($call_times <= 2){
					sleep(4);
				} else {
					sleep(3);
				}
			}
		
		}while ($is_continue);
		$t2 = microtime(true);
			
		log_message('error', '[INFO]_get_flight_international_data(): Number of calls = '. $call_times .'; Time Get Data = '.($t2 - $t1).' seconds; Submit URL = '.$flight_data_url. '; Search URL = '.get_flight_url($search_criteria));
		
		// Get JSON data from Vnisc
		if(!is_valid_flight_data($flight_data)){
		
			if ($flight_data == FLIGHT_CURL_ERROR){
				// do nothing, already log in the curl calling function
			} elseif($flight_data == FLIGHT_ERROR_TM){
				log_message('error', '[ERROR]_get_flight_international_data(): get Error-TM from VNISC');
		
			}elseif($flight_data == FLIGHT_ERROR_UN){
				log_message('error', '[ERROR]_get_flight_international_data(): get Error-UN from VNISC');
		
			}elseif($flight_data == FLIGHT_NO_FLIGHT){
					
				log_message('error', '[ERROR]_get_flight_international_data(): get NO_FLIGHT from VNISC');
					
			}elseif(strpos($flight_data, 'ERROR-') !== false || strpos($flight_data, 'ERROR_') !== false){
				
				log_message('error', '[ERROR]_get_flight_international_data(): get '.$flight_data.' from VNISC');
				
			} else {
				
				log_message('error', '[ERROR]_get_flight_international_data(): get unexpected-error. Content return =  '.$flight_data.'');
			}
		
			
			$error_message = '[ERROR]_get_flight_international_data(): Error getting data from VNISC with no Flight data returned';
		
			log_message('error', $error_message);
	
			$error_message .= ' - Error Message = '.$flight_data;
	
			send_email_flight_error_notify($error_message);
			
		}
		
		return $flight_data;
	}
	
	function _get_vnisc_sid($sid, $search_criteria, $flight_type){
		
		$vnisc_sid = '';
		
		// get in the current session only for return flight
		/*
		 * Comment on 27.08.2014 by Khuyenpv: $sid is unique -> always get Vnisc SID from Session first
		 * 
		if($flight_type == FLIGHT_TYPE_RETURN){
		
			$vnisc_sid = get_flight_session_data($sid, FLIGHT_VNISC_SID);
			
		}*/
		
		$vnisc_sid = get_flight_session_data($sid, FLIGHT_VNISC_SID);
		
		//echo $vnisc_sid; exit();
		
		// if the vnisc-sid is not in the session
		if($vnisc_sid == ''){
				
			$flight_url = get_flight_url($search_criteria);
			
			//echo $flight_url;exit();
		
			$vnisc_sid = get_flight_vnisc_sid($flight_url);
				
		}
	
		return $vnisc_sid;
	
	}
	
	/**
	 * Update Search Data & Flight Data by Change Day
	 * 1. Call to Vnisc Link to synchonize search data
	 * 2. Update Search-Criteria for the change of Depart or Return Date
	 */
	function _update_search_data_by_change_day($sid, $flight_type, $search_criteria, $vnisc_sid, $day_index){
		
		if($day_index == 0) return 0; // do nothing
		
		
		$flight_submit_url = $this->config->item('flight_vnisc_iframe_url');
		
		$flight_submit_url .= '?sid='.$vnisc_sid;
		
		if($flight_type == FLIGHT_TYPE_DEPART){
			
			$flight_submit_url .= '&go_day='.$day_index;
			
		} else {
			
			$flight_submit_url .= '&back_day='.$day_index;
		}
		
		
		$submit_status = update_change_day_to_vnisc($flight_submit_url);
		
		
		if($flight_type == FLIGHT_TYPE_DEPART){
				
			$depart = $search_criteria['Depart'];
			
			$depart = format_bpv_date($depart);
			
			if($day_index > 0){
				$depart = strtotime($depart. ' +'.$day_index.' days');
			} else {
				$depart = strtotime($depart. ' -'.(0 - $day_index).' days');
			}
			
			$depart = date(DATE_FORMAT, $depart);
			
			$search_criteria['Depart'] = $depart;
				
		} else {
				
			$return = $search_criteria['Return'];
				
			$return = format_bpv_date($return);
				
			if($day_index > 0){
				$return = strtotime($return. ' +'.$day_index.' days');
			} else {
				$return = strtotime($return. ' -'.(0 - $day_index).' days');
			}
				
			$return = date(DATE_FORMAT, $return);
				
			$search_criteria['Return'] = $return;
		}
		
		set_flight_session_data($sid, FLIGHT_SEARCH_CRITERIA, $search_criteria);
		
		$this->session->set_userdata(FLIGHT_SEARCH_CRITERIA, $search_criteria); // save current search to session
		
		if($submit_status){
			
			return 0; // 0 means everything is successful
			
		} else {
			
			return 2; // 2 means error to connect to VNISC
		}
	}
	
	/**
	 * Search the Departure Flight again and update the selected departure-flight
	 *
	 */
	function _update_selected_departure_flight($vnisc_sid, $departure_flight, $search_criteria){
		
		$ret = '';
		
		$flight_data_url = $this->config->item('flight_data_url');
		$flight_data_url .= '?sid='.$vnisc_sid;
		$flight_data_url .= '&Do=GetFlightData';
			
		$flight_data_url .= '&type='.FLIGHT_TYPE_DEPART;
		$flight_data_url .= '&sort=price';
		$flight_data_url .= '&Output=JSON';
		
		// search domistic flights
		$flight_data = $this->_get_flight_domistic_data($flight_data_url, FLIGHT_TYPE_DEPART, $search_criteria);
			
		// ok get flight data
		if(strpos($flight_data, FLIGHT_PROCESS_CONTINUE) !== false || strpos($flight_data, FLIGHT_PROCESS_COMPLETED) !== false){
		
			// remove <continue> or <complete> message before decoding JSON data
			$flight_data = str_replace(FLIGHT_PROCESS_CONTINUE, "", $flight_data);
			$flight_data = str_replace(FLIGHT_PROCESS_COMPLETED, "", $flight_data);
		
			$flight_data = json_decode($flight_data, true);
			
			if(!empty($flight_data)){
				
				foreach ($flight_data as $flight){
					
					if($flight['FlightCode'] == $departure_flight){
						
						$ret = $flight['Seg'].';'.$flight['Airlines'].';'.$flight['FlightCode'].';0;';
						
						$ret .= $flight['TimeFrom'].';'.$flight['TimeTo'].';';
						
						$flight['Class'] = !empty($flight['PriceInfo'][0]['Class'])? $flight['PriceInfo'][0]['Class'] : '';
						
						$flight['RClass'] = !empty($flight['PriceInfo'][0]['RClass'])? $flight['PriceInfo'][0]['RClass'] : '';
						
						$ret .= $flight['Class'].';'.$flight['RClass'];
						
						break;
					}
					
				}
				
			}
			
		}
		
		
		return $ret;
	}
	
	
	/**
	 * Restructure domistic flight data for easy diplaying on the view
	 * @param unknown $flight_data
	 * @return multitype:number
	 */
	function _restructure_domictic_flight_data($flight_data){
	
		$ret = array();

		if(!empty($flight_data)){
			foreach ($flight_data as $flight){
		
				if ($flight['PriceInfo'][0]['ADT_Fare'] > 0){
						
					$flight['departure_time_index'] = get_departure_time_index($flight);
				
					$flight['Stop'] = 0;
				
					$flight['Class'] = !empty($flight['PriceInfo'][0]['Class'])? $flight['PriceInfo'][0]['Class'] : '';
				
					$flight['RClass'] = !empty($flight['PriceInfo'][0]['RClass'])? $flight['PriceInfo'][0]['RClass'] : '';
				
					$flight['Seat'] = !empty($flight['PriceInfo'][0]['Seat'])? $flight['PriceInfo'][0]['Seat'] : 0;
						
					$ret[] = $flight;
						
				}	
				
			}
		}
	
		if(count($ret) > 0){
			usort($ret, array($this, 'sort_price_asc'));
		}
	
		return $ret;
	}
	
	/**
	 * Restructure international flight data for easy diplaying on the view
	 * @param unknown $flight_data
	 * @return multitype:unknown
	 */
	function _restructure_international_flight_data($flight_data, $search_criteria){
	
		$ret = array();
	
		if(!empty($flight_data)){
			foreach ($flight_data as $flight){
	
				if ($flight['PriceInfo'][0]['ADT_Fare'] > 0){
	
					$flight['departure_time_index'] = get_departure_time_index($flight);
					
					$first_route = $flight['RouteInfo'][0];
					// used for sort flight by Flight Company
					$flight['FlightCode'] = $first_route['Airlines'].'-'.$first_route['FlightCodeNum'];
						
					// set information of depart flightss
					$depart_routes = get_inter_flight_routes($flight['RouteInfo'], FLIGHT_TYPE_DEPART);
					$flight_depart['TimeFrom'] = $depart_routes[0]['TimeFrom']; // first route time-from
					$flight_depart['DayFrom'] = $depart_routes[0]['DayFrom']; // first route day-from
					$flight_depart['MonthFrom'] = $depart_routes[0]['MonthFrom']; // first route month-from
					
					$flight_depart['TimeTo'] =  $depart_routes[count($depart_routes)-1]['TimeTo']; // last rout time-to
					$flight_depart['DayTo'] = $depart_routes[count($depart_routes)-1]['DayTo']; // first route day-to
					$flight_depart['MonthTo'] = $depart_routes[count($depart_routes)-1]['MonthTo']; // first route month-to
					
					$flight_depart['Airlines'] = $depart_routes[0]['Airlines'];
					$flight_depart['FlightCode'] = $depart_routes[0]['FlightCode'];
					
					$flight_depart['Stop'] = count($depart_routes) - 1;
					$flight_depart['StopTxt'] = $flight_depart['Stop'] == 0 ? lang('direct') : $flight_depart['Stop'].' '.lang('stop');
					$flight_depart['RouteInfo'] = $depart_routes;
					
					$flight['flight_depart'] = $flight_depart;
					
					
					// set information of depart flightss
					$return_routes = get_inter_flight_routes($flight['RouteInfo'], FLIGHT_TYPE_RETURN);
					
					if(count($return_routes) > 0){
					
						$flight_return['TimeFrom'] = $return_routes[0]['TimeFrom']; // first route time-from
						$flight_return['DayFrom'] = $return_routes[0]['DayFrom']; // first route day-from
						$flight_return['MonthFrom'] = $return_routes[0]['MonthFrom']; // first route month-from
						
						$flight_return['TimeTo'] =  $return_routes[count($return_routes)-1]['TimeTo']; // last rout time-to
						$flight_return['DayTo'] = $return_routes[count($return_routes)-1]['DayTo']; // first route day-to
						$flight_return['MonthTo'] = $return_routes[count($return_routes)-1]['MonthTo']; // first route month-to
						
						$flight_return['Airlines'] = $return_routes[0]['Airlines'];
						$flight_return['FlightCode'] = $return_routes[0]['FlightCode'];
						
						$flight_return['Stop'] = count($return_routes) - 1;
						$flight_return['StopTxt'] = $flight_return['Stop'] == 0 ? lang('direct') : $flight_return['Stop'].' '.lang('stop');
						$flight_return['RouteInfo'] = $return_routes;
							
						$flight['flight_return'] = $flight_return;
					
					}
					
					$flight['Airlines'] = get_airline_codes_of_flight($flight['RouteInfo']);
					
					$fare = $flight['PriceInfo'][0]['ADT_Fare'];
					$from_code = $search_criteria['From_Code'];
					$to_code = $search_criteria['To_Code'];
					
					$flight['PriceFrom'] = calculate_discount_fare($flight['Airlines'], $from_code, $to_code, $fare);
					
					if($fare != $flight['PriceFrom']){
					
						$flight['PriceOrigin'] = $fare;
						
						$adt = $search_criteria['ADT'];
						$chd = $search_criteria['CHD'];
						$inf = $search_criteria['INF'];
						
						$total_fare = $flight['PriceInfo'][0]['ADT_Fare'] * $adt + $flight['PriceInfo'][0]['CHD_Fare'] * $chd + $flight['PriceInfo'][0]['INF_Fare'] * $inf;
						
						$total_discount = $total_fare - calculate_discount_fare($flight['Airlines'], $from_code, $to_code, $total_fare);
						
						$total_discount = bpv_format_currency($total_discount, false);
						
						$flight['DiscountNote'] = lang_arg('fare_discount', $total_discount, ($adt + $chd + $inf));
					}
				
					$ret[] = $flight;
	
				}
	
			}
		}
	
		if(count($ret) > 0){
			usort($ret, array($this, 'sort_price_inter_asc'));
		}
	
		return $ret;
	}
	
	function sort_price_inter_asc($f1, $f2){
		if($f1['PriceFrom'] == $f2['PriceFrom']) {
			return ($f1['FlightCode'] < $f2['FlightCode']) ? -1: 1;
		}
		return ($f1['PriceFrom'] < $f2['PriceFrom']) ? -1: 1;
	}
	
	function sort_price_asc($f1, $f2){
		if($f1['PriceInfo'][0]['ADT_Fare'] == $f2['PriceInfo'][0]['ADT_Fare']) {
			return ($f1['FlightCode'] < $f2['FlightCode']) ? -1: 1;
		}
		return ($f1['PriceInfo'][0]['ADT_Fare'] < $f2['PriceInfo'][0]['ADT_Fare']) ? -1: 1;
	}
	
	
	/**
	 * Get flight detail of domistic flight
	 * Call to VNISC to get flight detail (VNISC call to the airlines)
	 */
	function get_flight_detail(){
		
		$is_mobile = is_mobile();
		
		$mobile_view = $is_mobile ? 'mobile/' : '';
		
		/*
		 * Paramters Posted from Ajax function
		 */
		$sid = $this->input->post('sid');
		
		$flight_id = $this->input->post('flight_id');
		
		$flight_class = $this->input->post('flight_class');
		
		$flight_stop = $this->input->post('flight_stop');
		
		$flight_type = $this->input->post('flight_type');
		
		// get search criteria from session
		$search_criteria = get_flight_session_data($sid, FLIGHT_SEARCH_CRITERIA);
		
		$vnisc_sid = get_flight_session_data($sid, FLIGHT_VNISC_SID);

		if(empty($search_criteria) || empty($vnisc_sid)){ // fail to get information from the session
			
			echo '';
			
		} else {
	
			$is_domistic = $search_criteria['is_domistic'];
			
			$flight_detail_url = get_flight_detail_url($vnisc_sid, $flight_id, $flight_class, $flight_type, $is_domistic);
			
			//echo $flight_detail_url; exit();
			
			$flight_detail = get_flight_detail($flight_detail_url);
			
			if($flight_detail != ''){
			
				$flight_detail_info = get_flight_detail_info($flight_detail, $is_domistic, $flight_stop, $search_criteria);
			
				$this->load->view($mobile_view.'flights/flight_search/flight_detail', $flight_detail_info);
			
				//echo $flight_detail;
			
			} else {
				
				echo '';
				
			}
			
			
		}
	
	}
	
	/**
	 * Get flight detail of internaltional flight
	 * Get the flight detail from the session
	 */
	function get_flight_detail_inter(){
		
		$is_mobile = is_mobile();
		
		$mobile_view = $is_mobile ? 'mobile/' : '';
		
		/*
		* Paramters Posted from Ajax function
		*/
		$sid = $this->input->post('sid');
		
		$flight_id = $this->input->post('flight_id');
		
		$flight_data = get_flight_session_data($sid, FLIGHT_SEARCH_DATA);
		
		$search_criteria = get_flight_session_data($sid, FLIGHT_SEARCH_CRITERIA);
		
		$selected_flight = '';
		
		if($flight_data != '' && $search_criteria !=''){
		
			foreach ($flight_data as $flight){
				
				if($flight['Seg'] == $flight_id){
					
					$selected_flight = $flight;
					
					break;
				}
				
			}
		
		} 
		
		if($selected_flight != ''){
			
			$selected_flight['depart_routes'] = get_inter_flight_routes($selected_flight['RouteInfo'], FLIGHT_TYPE_DEPART);
			
			$selected_flight['return_routes'] = get_inter_flight_routes($selected_flight['RouteInfo'], FLIGHT_TYPE_RETURN);
			
			$prices = get_flight_prices_inter($selected_flight, $search_criteria);
			
			$data['flight'] = $selected_flight;
			$data['search_criteria'] = $search_criteria;
			$data['prices'] = $prices;
			
			$this->load->view($mobile_view.'flights/flight_search/flight_detail_inter', $data);
			
		} else {
			echo '';
		}
	
	}

	
	function flight_detail(){
		
		$is_mobile = is_mobile();
		$mobile_view = $is_mobile ? 'mobile/' : '';
		
		$data = $this->_set_common_data(array(), $is_mobile);
		
		// get $sid from the link
		$sid = $this->input->get('sid');
		
		// get search-criteria in the session
		$search_criteria = get_flight_session_data($sid, FLIGHT_SEARCH_CRITERIA);
		
		// get vnisc-id from the session
		$vnisc_sid = get_flight_session_data($sid, FLIGHT_VNISC_SID);
		
		// check if fail to get data from the session
		if($search_criteria == '' || $vnisc_sid == ''){
			
			log_message('error', '[ERROR]flight_detail(): Fail to get Search Criteria or VNISC-ID from the Session');
			
			redirect(get_url(FLIGHT_HOME_PAGE));exit();
		}

		// get the flight-booking of the flight
		$flight_booking = $this->get_flight_booking($sid, $vnisc_sid, $search_criteria);
		
		// if empty result -> redirect to Flight Home Page
		if (empty($flight_booking)){
				
			redirect(get_url(FLIGHT_HOME_PAGE));exit();
		}
		
		// save the flight-booking into the session
		set_flight_session_data($sid, FLIGHT_BOOKING_INFO, $flight_booking);
		
	
		$action = $this->input->post('action');
	
		if($action == ACTION_NEXT || $action=='change-passenger'){
				
			redirect(get_url(FLIGHT_BOOKING_PAGE.'?sid='.$sid));exit();
	
		}
		
		$data['is_mobile'] = $is_mobile;
		
		$data['meta'] = get_header_meta(FLIGHT_DETAIL_PAGE);
		
		$data['search_criteria'] = $search_criteria;
		$is_domistic = $search_criteria['is_domistic'];
		
		$data['domistic_airlines'] = $this->config->item('domistic_airlines');
		$data['passenger_nationalities'] = $this->config->item('passenger_nationalities');
	
		
		if($flight_booking['is_unavailable']){
			$data['exception_code'] = 1;
			$data['flight_search_form'] = $this->load->view($mobile_view.'flights/flight_search/flight_search_form', $data, TRUE);
		}
	
		$data['flight_booking'] = $flight_booking;
		
		$data['step'] = 2;
		$data['step_booking'] = $this->load->view($mobile_view.'flights/common/step_booking', $data, TRUE);
		
		
		$data['flight_summary'] = $this->load->view($mobile_view.'flights/flight_booking/flight_summary', $data, TRUE);
	
		$data['flight_passenger'] = $this->load->view($mobile_view.'flights/flight_booking/flight_passenger', $data, TRUE);
		
		// domistic flights
		if($is_domistic){
			
			$data['flight_itinerary'] = $this->load->view($mobile_view.'flights/flight_booking/flight_itinerary', $data, TRUE);
			
			$data['flight_baggage_fees'] = $this->load->view($mobile_view.'flights/flight_booking/flight_baggage_fees', $data, TRUE);
			
		} else { // international flights
			
			$data['flight_itinerary'] = $this->load->view($mobile_view.'flights/flight_booking/flight_itinerary_inter', $data, TRUE);
		}
		
		
		
		$data['flight_pro_code'] = $this->load->view($mobile_view.'flights/flight_booking/flight_pro_code', $data, TRUE);
		
	
		$data['bpv_content'] = $this->load->view($mobile_view.'flights/flight_booking/flight_detail', $data, TRUE);
	
		$this->load->view($mobile_view.'_templates/bpv_layout', $data);
	
	}
	
	function flight_payment(){
		
		$is_mobile = is_mobile();
		$mobile_view = $is_mobile ? 'mobile/' : '';
	
		$data = $this->_set_common_data(array(), $is_mobile);
		
		
		// get $sid from the link
		$sid = $this->input->get('sid');
		
		// get search-criteria in the session
		$search_criteria = get_flight_session_data($sid, FLIGHT_SEARCH_CRITERIA);
		
		// get vnisc-id from the session
		$vnisc_sid = get_flight_session_data($sid, FLIGHT_VNISC_SID);
		
		// check if fail to get data from the session
		if($search_criteria == '' || $vnisc_sid == ''){
				
			log_message('error', '[ERROR]flight_payment(): Fail to get Search Criteria Or VNISC-ID from the Session');
				
			redirect(get_url(FLIGHT_HOME_PAGE));exit();
		}
		
		// get the flight-booking of the flight
		$flight_booking = $this->get_flight_booking($sid, $vnisc_sid, $search_criteria);
		
		// if empty result -> redirect to Flight Home Page
		if (empty($flight_booking)){
			
			redirect(get_url(FLIGHT_HOME_PAGE));exit();
			
		}elseif(count($flight_booking['adults']) == 0){
				
			redirect(get_url(FLIGHT_DETAIL_PAGE.'?sid='.$sid));exit();
		}
		
		$data['meta'] = get_header_meta(FLIGHT_BOOKING_PAGE);
		
		$data['search_criteria'] = $search_criteria;
		$data['sid'] = $sid;
		$data['is_mobile'] = $is_mobile;
		
		$data['domistic_airlines'] = $this->config->item('domistic_airlines');
		$data['passenger_nationalities'] = $this->config->item('passenger_nationalities');
	
		$current_time = microtime(true);
	
		$flight_booking['is_timeout'] = false;
		
		// check if the flight-booking-data is timed out
		if(isset($flight_booking['time_check_flight'])){
				
			$delta_time = $current_time - $flight_booking['time_check_flight'];
				
			$flight_data_timeout = $this->config->item('flight_data_timeout');
				
			log_message('error', '[INFO]flight_payment(): DELTA TIME = '. $delta_time. ' and TimeOut Threshold = '.$flight_data_timeout. '. Search URL = '.get_current_flight_search_url($search_criteria));
				
			if($delta_time > $flight_data_timeout){
	
				$flight_booking['is_timeout'] = true;
	
				log_message('error', '[ERROR ]flight_payment(): TIME-OUT on Payment page, DELTA TIME = '. $delta_time .' and Timeout Threshold = '.$flight_data_timeout. '. Search URL = '.get_current_flight_search_url($search_criteria));
			}
				
		} else {
			$flight_booking['is_timeout'] = true;
				
			log_message('error', '[ERROR]flight_payment(): TIME-OUT on Payment page, [time_check_flight] not set. Search URL = '.get_current_flight_search_url($search_criteria));
		}
	
	
		$action = $this->input->post('action');
		
		// book action when the data has not been time-out
		if ($action == ACTION_MAKE_BOOKING && !$flight_booking['is_unavailable'] && !$flight_booking['is_timeout'] && contact_validation()){
				
			$submit_status_nr = $this->_book($sid, $flight_booking, $search_criteria);
				
			if(!is_null($submit_status_nr)){
	
				$data['submit_status_nr'] = $submit_status_nr;
	
			}
		}
		
		$data['flight_booking'] = $flight_booking;
		

		$data['flight_summary'] = $this->load->view($mobile_view.'flights/flight_booking/flight_summary', $data, TRUE);
		
		if($search_criteria['is_domistic']){
			$data['flight_itinerary'] = $this->load->view($mobile_view.'flights/flight_booking/flight_itinerary', $data, TRUE);
		} else {
			$data['flight_itinerary'] = $this->load->view($mobile_view.'flights/flight_booking/flight_itinerary_inter', $data, TRUE);
		}
	
	
		$data['flight_passenger'] = $this->load->view($mobile_view.'flights/flight_booking/flight_passenger', $data, TRUE);
		
		$data['flight_review'] = $this->load->view($mobile_view.'flights/flight_booking/flight_review', $data, TRUE);
		
		
		/**
		 * Load contact form
		 */
		$booking_note = '';
		$hold_status = check_pre_hold_flight($flight_booking, $search_criteria);
		if(!$hold_status['is_allow_hold']){
			if($hold_status['code'] == 1){ // departure to close
				$booking_note = lang('time_limit_booking');
				$data['booking_note'] = $booking_note;
			} else {
				// international flights
			}
		}
		
		$data['hold_status'] = $hold_status;
		
		if($flight_booking['is_unavailable'] || $flight_booking['is_timeout'] || isset($submit_status_nr)){
			
			$data['flight_search_form'] = $this->load->view($mobile_view.'flights/flight_search/flight_search_form', $data, TRUE);
		
		} else {
			
			$data['contact_form'] = load_contact_form(false, '', '', $is_mobile);
			
			$data['payment_method'] = load_payment_method(FLIGHT, $is_mobile);
		}
		
		/**
         * Load Step Booking
		 */
		$data['step'] = 3;
		$data['step_booking'] = $this->load->view($mobile_view.'flights/common/step_booking', $data, TRUE);
		
	
		$data['bpv_content'] = $this->load->view($mobile_view.'flights/flight_booking/flight_submit', $data, TRUE);
	
		$this->load->view($mobile_view.'_templates/bpv_layout', $data);
	
	}
	
	/**
	 * Get Flight Booking Information
	 * @param unknown $sid
	 * @param unknown $vnisc_sid
	 * @param unknown $search_criteria
	 * 
	 */
	function get_flight_booking($sid, $vnisc_sid, $search_criteria){
		
		$is_domistic = $search_criteria['is_domistic'];
	
		$flight_booking = array();
		
		// for domistic flight
		if($is_domistic){
		
			$flight_departure_str = $this->input->post('flight_departure');
			$flight_return_str = $this->input->post('flight_return');
	
		
			// if access from the link, get from cookie
			if(empty($flight_departure_str)){
				
				$flight_booking = get_flight_session_data($sid, FLIGHT_BOOKING_INFO);
				
			}else{
					
				$flight_booking['flight_departure'] = get_flight_for_booking($vnisc_sid, $flight_departure_str, $search_criteria, FLIGHT_TYPE_DEPART);
					
				$flight_booking['flight_return'] = get_flight_for_booking($vnisc_sid, $flight_return_str, $search_criteria, FLIGHT_TYPE_RETURN);
					
				$flight_booking['nr_adults'] = $search_criteria['ADT'];
					
				$flight_booking['nr_children'] = $search_criteria['CHD'];
		
				$flight_booking['nr_infants'] = $search_criteria['INF'];
					
				$flight_booking['time_check_flight'] = microtime(true);
					
			}
		
		} else {
			
			// for international flights
			$flight_id = $this->input->post('flight_inter_id');
			
			// if access from the link, get from the session
			if(empty($flight_id)){
				
				$flight_booking = get_flight_session_data($sid, FLIGHT_BOOKING_INFO);
				
			} else {
				
				// get flight-data saved in the session on the Search Step
				$flight_data = get_flight_session_data($sid, FLIGHT_SEARCH_DATA);
				
				$selected_flight = '';
				
				if($flight_data != ''){
				
					foreach ($flight_data as $flight){
				
						if($flight['Seg'] == $flight_id){
								
							$selected_flight = $flight;
								
							break;
						}
				
					}
				
				}
				
				if($selected_flight != ''){
					// store the selected flight in the FLIGHT BOOKING INFO
					$selected_flight['depart_routes'] = get_inter_flight_routes($selected_flight['RouteInfo'], FLIGHT_TYPE_DEPART);
						
					$selected_flight['return_routes'] = get_inter_flight_routes($selected_flight['RouteInfo'], FLIGHT_TYPE_RETURN);
					
					$flight_booking['selected_flight'] = $selected_flight;
					

					$flight_booking['nr_adults'] = $search_criteria['ADT'];
						
					$flight_booking['nr_children'] = $search_criteria['CHD'];
					
					$flight_booking['nr_infants'] = $search_criteria['INF'];
						
					$flight_booking['time_check_flight'] = microtime(true);
					
					
				} else {
					
					log_message('error', '[ERROR]get_flight_booking(): Fail to get Selected Flight in the Session');
				}
				
			}
			
		}
		
		if(!empty($flight_booking)){
			
			$action = $this->input->post('action');
		
			if($action == ACTION_NEXT || $action == 'change-passenger'){		
				$flight_booking = $this->get_passenger_details($flight_booking);
			}
			
			if($action == ACTION_NEXT || $action == 'change-baggage'){
					
				$baggage_fees = $this->get_baggage_fees($flight_booking, $search_criteria);
			
				$flight_booking['baggage_fees'] = $baggage_fees;
					
			}
			
			if($action == ACTION_NEXT){
				
				$promotion_code = $this->input->post('promotion_code');
				
				if(!empty($promotion_code)){
				
					$flight_booking['promotion_code'] = $promotion_code;
				
				}
			}
		
			$flight_booking = $this->get_flight_booking_price($flight_booking, $search_criteria);
			
		}

	
		return $flight_booking;
	}
	
	/**
	 * Get flight Booking Price
	 * @param unknown $flight_booking
	 * @param unknown $search_criteria
	 * @return boolean
	 */
	function get_flight_booking_price($flight_booking, $search_criteria){
		
		$is_unavailable = false;
		
		$is_domistic = $search_criteria['is_domistic'];
		
		if($is_domistic){
		
			$prices['adult_fare_total'] = 0;
		
			$prices['children_fare_total'] = 0;
		
			$prices['infant_fare_total'] = 0;
		
			$prices['total_tax'] = 0;
		
			$prices['total_price'] = 0;
			
			$prices['baggage_fee'] = 0;
		
			$prices['bank_fee'] = 0;
		
			$prices['total_payment'] = 0;
	
		
			$flight_departure = $flight_booking['flight_departure'];
		
			$flight_return = $flight_booking['flight_return'];
		
			if(!empty($flight_departure) && !empty($flight_departure['detail'])){
					
				$detail = $flight_departure['detail']['prices'];
					
				$prices['adult_fare_total'] = !empty($detail['adult_fare_total'])? $detail['adult_fare_total'] : 0;
					
				$prices['children_fare_total'] = !empty($detail['children_fare_total'])? $detail['children_fare_total'] : 0;
					
				$prices['infant_fare_total'] = !empty($detail['infant_fare_total'])? $detail['infant_fare_total'] : 0;
					
				$prices['total_tax'] = $detail['total_tax'];
					
				$prices['total_price'] = $detail['total_price'];
					
				if($detail['total_price'] == 0){
		
					$is_unavailable = true;
		
				}
					
			}
		
			if(!empty($flight_return) && !empty($flight_return['detail'])){
					
				$detail = $flight_return['detail']['prices'];
					
				$prices['adult_fare_total'] += !empty($detail['adult_fare_total'])? $detail['adult_fare_total'] : 0;
					
				$prices['children_fare_total'] += !empty($detail['children_fare_total'])? $detail['children_fare_total'] : 0;
					
				$prices['infant_fare_total'] += !empty($detail['infant_fare_total'])? $detail['infant_fare_total'] : 0;
					
				$prices['total_tax'] += $detail['total_tax'];
					
				$prices['total_price'] += $detail['total_price'];
					
				if($detail['total_price'] == 0){
		
					$is_unavailable = true;
		
				}
					
			}
			
			
			$baggage_fees = isset($flight_booking['baggage_fees'])?$flight_booking['baggage_fees']:array();
			$total_baggage_fee = isset($baggage_fees['total_fee'])?$baggage_fees['total_fee']:0;
			
			$prices['baggage_fee'] = $total_baggage_fee;
			
			/*
			 * 
			 * Not applied bank fee for Vietnameses customers
			 * 
			$bank_fee = $this->config->item('bank_fee');
			
			$prices['bank_fee'] = round(($prices['total_price'] + $total_baggage_fee) * $bank_fee/100,2);
			*/
			
			$prices['total_payment'] = $prices['total_price'] + $total_baggage_fee;
		
		} else {
			
			$selected_flight = $flight_booking['selected_flight'];
			
			$prices = get_flight_prices_inter($selected_flight, $search_criteria);
		}
	
		$flight_booking['prices'] = $prices;
	
		$flight_booking['is_unavailable'] = $is_unavailable;
	
		return $flight_booking;
	}
	
	function get_passenger_details($flight_booking){
	
		$nr_adults = $flight_booking['nr_adults'];
	
		$nr_children = $flight_booking['nr_children'];
			
		$nr_infants = $flight_booking['nr_infants'];
	
	
		$adults = array();
	
		$children = array();
	
		$infants = array();
	
		for ($i = 1; $i <= $nr_adults; $i++){
				
			$gender = $this->input->post('adults_gender_'.$i);
			$full_name = $this->input->post('adults_full_'.$i);
			$full_name = trim($full_name);
			
			$adult['gender'] = $gender;
			$adult['full_name'] = $full_name;
			$first_last = seperate_first_last_from_full_name($full_name);
			
			$adult['first_name'] = $first_last['first_name'];
			$adult['middle_name'] = '';
			$adult['last_name'] = $first_last['last_name'];
			
			// for international flight: adult birthday, nationality, passport & passport expired date
			$day = $this->input->post('adult_day_'.$i);
			$month = $this->input->post('adult_month_'.$i);
			$year = $this->input->post('adult_year_'.$i);
			if(!empty($day) && !empty($month) && !empty($year)){
				$adult['birth_day'] = date(DATE_FORMAT, mktime(0,0,0,$month,$day,$year));
			}
			
			$adult['nationality'] = $this->input->post('country_orgin_adt_'.$i);
			
			// passport
			$adult['passport'] = $this->input->post('passport_adt_'.$i);
			
			// passport expired date
			$day = $this->input->post('passportexp_adt_day_'.$i);
			$month = $this->input->post('passportexp_adt_month_'.$i);
			$year = $this->input->post('passportexp_adt_year_'.$i);
			if(!empty($day) && !empty($month) && !empty($year)){
				$adult['passportexp'] = date(DATE_FORMAT, mktime(0,0,0,$month,$day,$year));
			}
				
			$adults[] = $adult;
		}
	
		for ($i = 1; $i <= $nr_children; $i++){
				
			$gender = $this->input->post('children_gender_'.$i);
				
			$full_name = $this->input->post('children_full_'.$i);
			$full_name = trim($full_name);
				
			$day = $this->input->post('children_day_'.$i);
				
			$month = $this->input->post('children_month_'.$i);
				
			$year = $this->input->post('children_year_'.$i);
				
				
			$child['gender'] = $gender;
			$child['full_name'] = $full_name;
			$first_last = seperate_first_last_from_full_name($full_name);
			
			$child['first_name'] = $first_last['first_name'];
			$child['middle_name'] = '';
			$child['last_name'] = $first_last['last_name'];
				
			$child['birth_day'] = date(DATE_FORMAT, mktime(0,0,0,$month,$day,$year));
			
			// for international flight: children nationality, passport & passport expired date
			$child['nationality'] = $this->input->post('country_orgin_chd_'.$i);
				
			// passport
			$child['passport'] = $this->input->post('passport_chd_'.$i);
				
			// passport expired date
			$day = $this->input->post('passportexp_chd_day_'.$i);
			$month = $this->input->post('passportexp_chd_month_'.$i);
			$year = $this->input->post('passportexp_chd_year_'.$i);
			if(!empty($day) && !empty($month) && !empty($year)){
				$child['passportexp'] = date(DATE_FORMAT, mktime(0,0,0,$month,$day,$year));
			}
				
			$children[] = $child;
				
		}
	
		for ($i = 1; $i <= $nr_infants; $i++){
				
			$gender = $this->input->post('infants_gender_'.$i);
				
			$full_name = $this->input->post('infants_full_'.$i);
			$full_name = trim($full_name);
			$first_last = seperate_first_last_from_full_name($full_name);
				
			$day = $this->input->post('infants_day_'.$i);
				
			$month = $this->input->post('infants_month_'.$i);
				
			$year = $this->input->post('infants_year_'.$i);
				
			$infant['gender'] = $gender;
			$infant['full_name'] = $full_name;
			
			$infant['first_name'] = $first_last['first_name'];
			$infant['middle_name'] = '';
			$infant['last_name'] = $first_last['last_name'];
				
			$infant['birth_day'] = date(DATE_FORMAT, mktime(0,0,0,$month,$day,$year));
			
			// for international flight: infant nationality, passport & passport expired date
			$infant['nationality'] = $this->input->post('country_orgin_inf_'.$i);
			
			// passport
			$infant['passport'] = $this->input->post('passport_inf_'.$i);
			
			// passport expired date
			$day = $this->input->post('passportexp_inf_day_'.$i);
			$month = $this->input->post('passportexp_inf_month_'.$i);
			$year = $this->input->post('passportexp_inf_year_'.$i);
			if(!empty($day) && !empty($month) && !empty($year)){
				$infant['passportexp'] = date(DATE_FORMAT, mktime(0,0,0,$month,$day,$year));
			}
				
			$infants[] = $infant;
		}
	
	
		$flight_booking['adults'] = $adults;
	
		$flight_booking['children'] = $children;
	
		$flight_booking['infants'] = $infants;
	
		return $flight_booking;
	}
	
	function get_baggage_fees($flight_booking, $search_criteria){
		
		if(!$search_criteria['is_domistic']) return array(); // no baggage fee for internaltional flights
		
		$baggage_fees['depart'] = array();
		$baggage_fees['return'] = array();
		
		$total_kg = 0;
		$total_fee = 0;
		
		$baggage_fees_cnf = $this->config->item('baggage_fees');	
		$nr_passengers = $search_criteria['ADT'] + $search_criteria['CHD'] + $search_criteria['INF'];
		
		// get baggage fee for depart 
		$flight_departure = $flight_booking['flight_departure'];
		$fees = $baggage_fees_cnf[$flight_departure['airline']];
		if(is_array($fees['send'])){
			for ($i=1; $i<= $nr_passengers; $i++){
				$kg = $this->input->post('baggage_depart_'.$i);
				
				if($kg != ''){
					$bg_item = array('kg'=>$kg, 'money'=>$fees['send'][$kg]);
					$baggage_fees['depart'][$i] = $bg_item;
					
					$total_kg += $kg;
					$total_fee += $fees['send'][$kg];
				}
			}
		}
		
		// get baggage fee for return
		
		if($search_criteria['Type'] == FLIGHT_TYPE_ROUNDWAY && !empty($flight_booking['flight_return'])){
			
			$flight_return = $flight_booking['flight_return'];
			$fees = $baggage_fees_cnf[$flight_return['airline']];
			if(is_array($fees['send'])){
				for ($i=1; $i<= $nr_passengers; $i++){
					$kg = $this->input->post('baggage_return_'.$i);
			
					if($kg != ''){
						$bg_item = array('kg'=>$kg, 'money'=>$fees['send'][$kg]);
						$baggage_fees['return'][$i] = $bg_item;
						
						$total_kg += $kg;
						$total_fee += $fees['send'][$kg];
					}
				}
			}
		
		}
		
		$baggage_fees['total_kg'] = $total_kg;
		$baggage_fees['total_fee'] = $total_fee;
		
		return $baggage_fees;
	}

	
function flight_to_destination($des_id) {
		
		//set_cache_html();
		
	    $is_mobile = is_mobile();
	
		$data = $this->_set_common_data();
		
		// get flight destination data
		$data['destination'] = $this->Destination_Model->get_destination($des_id);
		
		$data['meta'] = get_header_meta(FLIGHT_DESTINATION_PAGE, $data['destination']);
		
		$data['exception_code'] = 1;
		
		$data['popular_routes'] = $this->Flight_Model->get_flights_of_destiantion($des_id);
		
		// Set data
		$data = build_search_criteria($data, MODULE_FLIGHT_DESTINATION, $is_mobile);
		
		$data['n_book_together'] = $this->News_Model->get_news_details(10);
		
		// Render view
		if ($is_mobile)
        {
            $data['search_dialog'] = $this->load->view('mobile/flights/flight_search/flight_search_dialog', $data, TRUE);
            
            _render_view('mobile/flights/flight_destination/flight_to_destination', $data, $is_mobile);
        }
        else
        {
            // Footer flight links
            $data = load_footer_links($data);
            
            $data['search_dialog'] = $this->load->view('flights/flight_search/flight_search_dialog', $data, TRUE);
            
            $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
            
            _render_view('flights/flight_destination/flight_to_destination', $data);
        }
	}
	
	
	function _load_filter_data($data, $is_mobile = false){
		
		$mobile_view = $is_mobile ? 'mobile/' : '';
	
		$data['departure_times'] = $this->config->item('departure_times');
	
		$data['flight_search_filters'] = $this->load->view($mobile_view.'flights/flight_search/search_filters', $data, TRUE);
	
		return $data;
	}
	
	function _set_common_data($data = array(), $is_mobile = false){
		
		$this->session->set_userdata('MENU', MNU_FLIGHTS);
		
		if($is_mobile){
			
			$data['page_css'] = get_static_resources('flight.min.29082014.css','css/mobile/');
			
		} else {
			
			$data['page_css'] = get_static_resources('flight.min.091020141112.css');
		}
		
		$data['page_js'] = get_static_resources('flight.min.13112014.js');
		
		$data['baggage_fee_cnf'] = $this->config->item('baggage_fees');
		
		$data['flight_destinations']	= $this->Flight_Model->get_all_flight_destinations();
	
		return $data;
	}
	
	function _get_search_exception_code($data){
		
		$search_criteria = $data['search_criteria'];
		
		if($search_criteria['ADT'] + $search_criteria['CHD'] > FLIGHT_PASSENGER_LIMIT){
		
			log_message('error', '[EXCEPTION]search(): passenger limited (adt + chd > 9), adt = '.$search_criteria['ADT'].', chd = '.$search_criteria['CHD']);
			
			return 1; // over limited passenger
		
		}elseif($search_criteria['INF'] > $search_criteria['ADT']){
		
			log_message('error', '[EXCEPTION]search(): infants limited (inf > adt) , inf = '.$search_criteria['INF'].', adt = '.$search_criteria['ADT']);
				
			return 2; // too much infants
		
		}
		
		/*
		if(!$search_criteria['is_domistic']){
			log_message('error', '[EXCEPTION]search(): Search International Flights with Search Data = '.get_flight_exception_short_req($search_criteria));
				
			return 3; // international flights
		}*/
		
		$has_flight_route = $this->Flight_Model->has_flight_route($search_criteria['From_Code'], $search_criteria['To_Code']);
		
		if($search_criteria['is_domistic'] && !$has_flight_route){
			
			log_message('error', '[EXCEPTION]search(): No Flight Route , From = '.$search_criteria['From'].', To = '.$search_criteria['To']);
			
			return 4; // no flight route
		}
		
		
		$today = date(DB_DATE_FORMAT);
		$depart = format_bpv_date($search_criteria['Depart']);
		
		if($depart < $today){
			
			log_message('error', '[EXCEPTION]search(): Invalid Search Date: Departure Date < Today, Departure = '.format_bpv_date($search_criteria['Depart'], DATE_FORMAT));
				
			return 5; // departure in the past
			
		}
		
		if(!empty($search_criteria['Return'])){

			$return = format_bpv_date($search_criteria['Return']);
			
			if($return < $depart){
				
				$message = '[EXCEPTION]search(): Invalid Search Date: Return Date < Depart Date, Departure = '.format_bpv_date($search_criteria['Depart'], DATE_FORMAT).
							'; Return = '.format_bpv_date($search_criteria['Return'], DATE_FORMAT);
				
				log_message('error', '[EXCEPTION]search(): Invalid Search Date: Return Date < Depart Date, Departure = '.format_bpv_date($search_criteria['Depart'], DATE_FORMAT));
				
				return 6; // departure in the past
				
			}
			
		}

		
		
		return 0;
	}
	
	function _book($sid, $flight_booking, $search_criteria){
		
		$is_domistic = $search_criteria['is_domistic'];
		
		$customer = get_contact_post_data();
		
		// promotion code
		$promotion_code = $this->input->post('promotion_code');
		$code_discount_info = get_pro_code_discount_info($promotion_code, FLIGHT, '', '', '', '', $customer['phone']);
		
		
		// payment method
		$payment_info['method'] = $this->input->post('payment_method');
		$payment_info['bank'] = $this->input->post('payment_bank');
		
		$customer_id = $this->Booking_Model->create_or_update_customer($customer);
		
		$special_request = $customer['special_request'];
		
		$customer_booking = get_flight_customer_booking($flight_booking, $search_criteria, $customer_id, $special_request, $payment_info, $code_discount_info);
		
		$service_reservations = get_flight_service_reservations($flight_booking, $search_criteria, $code_discount_info);
		
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
		
		// create invoice
		$invoice_id = $this->Booking_Model->create_invoice($customer_id, $customer_booking_id, FLIGHT);
		
		// get invoice detail and call payment module
		$invoice = $this->Booking_Model->get_invoice_4_payment($invoice_id);
		
		$invoice_ref = !empty($invoice) ? $invoice['invoice_reference'] : '';
		
		if($customer_booking_id !== FALSE){

			$hold_status = check_pre_hold_flight($flight_booking, $search_criteria);
				
			if ($invoice_id === FALSE){ // false to create invoice
			
				log_message('error', '[INFO]Flight->_book(): FAIL to create Invoice. Go Thank_you Page');
				
				$this->_send_mail($search_criteria, $flight_booking, $customer, $payment_info, $code_discount_info, $customer_booking_id, $invoice_ref);
				
				// clear flight session data
				unset_flight_session_data($sid);
				$this->session->unset_userdata(FLIGHT_SEARCH_CRITERIA);
					
				// show thank you page as normal submit
				redirect(get_url(CONFIRM_PAGE, array('type'=>'flight')));
			
				exit();
					
			} else {
				
				// submit booking to VNISC
				$submit_status_nr = submit_flight_booking_to_vnisc($sid, $flight_booking, $search_criteria, $customer, $customer_booking_id);

				if($submit_status_nr == 1){ // OK to submit data to VNISC
					
					// send flight booking to Customer
					$this->_send_mail($search_criteria, $flight_booking, $customer, $payment_info, $code_discount_info, $customer_booking_id, $invoice_ref);
					
					// clear flight session data
					unset_flight_session_data($sid);
					$this->session->unset_userdata(FLIGHT_SEARCH_CRITERIA);
					
					if($hold_status['is_allow_hold']){
					
						if($payment_info['method'] == PAYMENT_METHOD_CREDIT_CARD){
							
							log_message('error', '[INFO]Flight->_book(): Submit Booking Data to VNISC Sucessfully & Go to Onepay Credit Card Payment');
							
							$pay_url = get_international_payment_url($invoice, $customer['ip_address']);
							
							redirect($pay_url);
							
						}elseif($payment_info['method'] == PAYMENT_METHOD_DOMESTIC_CARD){
							
							log_message('error', '[INFO]Flight->_book(): Submit Booking Data to VNISC Sucessfully & Go to Onepay ATM Card Payment');
							
							$pay_url = get_domestic_payment_url($invoice, $customer['ip_address']);
							
							redirect($pay_url);
							
						} else {
							
							log_message('error', '[INFO]Flight->_book(): Submit Booking Data to VNISC Sucessfully & Go to Confirm Page');
							
							redirect(get_url(CONFIRM_PAGE, array('type'=>'flight')));
							exit();
							
						}
					
					} else {
						
						log_message('error', '[INFO]Flight->_book(): Submit Booking Data to VNISC Sucessfully - But time too close departure - Do not allow online payment - Go to Confirm Page');
						
						redirect(get_url(CONFIRM_PAGE, array('type'=>'flight')));
						exit();
						
					}

				} else{ // FAIL to submit
						
					log_message('error', '[ERROR]Flight->_book(): FAIL to Submit Booking Data to VNISC, Show Message to Customer');
						
					return  $submit_status_nr;
				}
			
			} 

		}
	}

	
	function _send_mail($search_criteria, $flight_booking, $customer, $payment_info, $code_discount_info, $customer_booking_id, $invoce_ref=''){
		
		$CI =& get_instance();
		
		$data['flight_booking'] = $flight_booking;
	
		$data['search_criteria'] = $search_criteria;
	
		$data['valid_airline_codes'] = $this->config->item('valid_airline_codes');
		
		$data['customer'] = $customer;
		
		$data['payment_info'] = $payment_info;
		
		$data['code_discount_info'] = $code_discount_info;
		
		$data['customer_booking_id'] = $customer_booking_id;
		
		$data['invoice_ref'] = $invoce_ref;
		$data['bank_transfer'] = $this->config->item('bank_transfer');
		
		$data['hold_status'] = check_pre_hold_flight($flight_booking, $search_criteria);
		
		$short_flight_desc = get_flight_short_desc($search_criteria);
		
		$content = $this->load->view('flights/common/flight_booking_mail', $data, TRUE);
		
		//echo $content;exit();
		
		$CI->load->library('email');
		
		$config = array();
		/*
		$config['protocol']='smtp';
		$config['smtp_host']='74.220.207.140';
		$config['smtp_port']='25';
		$config['smtp_timeout']='30';
		$config['smtp_user']='booking@snotevn.com:8888';
		$config['smtp_pass']='Bpt052010';
		*/
		
		
		$config['protocol'] = 'mail';
		$config['charset']='utf-8';
		$config['newline']="\r\n";
		$config['mailtype'] = 'html';
		
		// send to Best Price Booking Email
		$CI->email->initialize($config);
		
		$CI->email->from($customer['email'], $customer['full_name']);
		
		$CI->email->to('bestpricebooking@gmail.com');
		
		$subject = lang('flight_email_reply').': ' . $short_flight_desc . ' - '. $customer['full_name'];
		$CI->email->subject($subject);
		
		$CI->email->message($content);
		
		if (!$CI->email->send()){
			log_message('error', '[ERROR]flight_boooking: Can not send email to bestpricevn@gmail.com');
		}
		
		
		$t1 = microtime(true);
		
		$config = array();
		/*
		$config['protocol']='smtp';
		$config['smtp_host']='74.220.207.140';
		$config['smtp_port']='25';
		$config['smtp_timeout']='30';
		$config['smtp_user']='booking@snotevn.com:8888';
		$config['smtp_pass']='Bpt052010';
		*/
		
		$config['protocol']='smtp';
		$config['smtp_host']='ssl://smtp.googlemail.com';
		$config['smtp_port']='465';
		$config['smtp_timeout']='30';
		
		$config['smtp_user']='bestpricebooking@gmail.com';
		$config['smtp_pass']='Bpt20112008';
		
		
		/*
		$config['smtp_user']='booking@snotevn.com:8888';
		$config['smtp_pass']='Bpt052010';
		*/
		
		//$config['protocol'] = 'mail';
		$config['charset']='utf-8';
		$config['newline']="\r\n";
		$config['mailtype'] = 'html';
		
		// send to customer
		$CI->email->initialize($config);
		
		$CI->email->from('booking@snotevn.com:8888', BRANCH_NAME);
		
		$CI->email->reply_to('booking@snotevn.com:8888');
		
		$CI->email->to($customer['email']);
		
		$subject = lang('flight_email_reply').': ' . $short_flight_desc . ' - '. BRANCH_NAME;
		$CI->email->subject($subject);
		
		$CI->email->message($content);
		
		if (!$CI->email->send()){
			log_message('error', '[ERROR]flight_boooking: Can not send email to '.$customer['email']);
		}
		
		$t2 = microtime(true);
		
		//echo 'Connect SMTP email time = '.($t2 - $t1);exit();
		
		log_message('error', '[INFO]Connect SMTP email time = '.($t2 - $t1));
		
		return true;
	}
	
	/**
	 * Flight Bay
	 * @param unknown $id
	 */
	function flight_airline($id){
		
		$data = $this->_set_common_data();
		
		// get flight destination data
		$data['airline'] = $this->Flight_Model->get_airline($id);
		
		if($data['airline'] === FALSE){
			redirect(get_url(FLIGHT_HOME_PAGE));
			exit();
		}
		
		$data['meta'] = get_header_meta(FLIGHT_AIRLINE_PAGE, $data['airline']);
	
		// Set data
		$data = build_search_criteria($data, MODULE_FLIGHT_DESTINATION);
		
		
		// Footer flight links
		$data = load_footer_links($data);
		
		// Render view
		$data['search_dialog'] = $this->load->view('flights/flight_search/flight_search_dialog', $data, TRUE);
		
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		
		_render_view('flights/flight_category/flight_airline', $data);
		
	}
	
	function flight_category($id){
		
		$data = $this->_set_common_data();
		
		// get flight destination data
		$data['category'] = $this->Flight_Model->get_category($id);
		
		if($data['category'] === FALSE){
			redirect(get_url(FLIGHT_HOME_PAGE));
			exit();
		}
		
		$data['meta'] = get_header_meta(FLIGHT_CATEGORY_PAGE, $data['category']);
		
		// Set data
		$data = build_search_criteria($data, MODULE_FLIGHT_DESTINATION);
		
		
		// Footer flight links
		$data = load_footer_links($data);
		
		// Render view
		$data['search_dialog'] = $this->load->view('flights/flight_search/flight_search_dialog', $data, TRUE);
		
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		
		_render_view('flights/flight_category/flight_category', $data);
	}
	
	/**
	 * Customer Booked Link
	 */
	function customer_booked(){
		
		$vnisc_booking_id = $this->input->get('id');
		
		echo '<center><br><br>VNISC booking saved! <br><br> <a href="http://www.snotevn.com:8888" target="blank_">www.snotevn.com:8888</a><center>';
		
	}
	
	/**
	 * Ticket Booked Link
	 * Save PNR to DB
	 */
	function ticket_booked(){
		
		$productcode = $this->input->get('productcode');
		
		$pnr = $this->input->get('pnr');
		
		$status = $this->input->get('status');
		
		$error = $this->input->get('error');
		
		$hash = $this->input->get('hash');
		
		// check for
		if ($hash == md5($productcode.$pnr.$status.$error.FLIGHT_WEB_SERVICE_SECURITY_CODE)){
			
			$pnr_info = explode('-', $pnr);
			
			$flight_from_code = isset($pnr_info[0]) ? trim($pnr_info[0]) : '';
			
			$flight_to_code = isset($pnr_info[1]) ? trim($pnr_info[1]) : '';
			
			$flying_date = isset($pnr_info[2]) ? trim($pnr_info[2]) : '';
			
			$pnr = isset($pnr_info[3]) ? trim($pnr_info[3]) : '';
			
			$this->Flight_Model->update_pnr_for_sr($productcode, $flight_from_code, $flight_to_code, $flying_date, $pnr, $status, $error);
		
		}
		
		echo '<br><br><center>'.
				'<table>'.
					'<tr><td>Productcode:</td><td>'.$productcode.'</td></tr>'.
					'<tr><td>PNR:</td><td>'.$pnr.'</td></tr>'.
					'<tr><td>Status:</td><td>'.$status.'</td></tr>'.
					'<tr><td>Hash:</td><td>'.$hash.'</td></tr>'.
				'</table>'.
				'<br><br> <a href="http://www.snotevn.com:8888" target="blank_">www.snotevn.com:8888</a>'.
			'</center>';
	}
}

?>