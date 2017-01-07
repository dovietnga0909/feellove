<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
*  get_land_tour_search_criteria()
*
*  Get search data posted from search form
*
*  @author toanlk
*  @since  Sep 12, 2014
*/
function get_land_tour_search_criteria()
{
    $CI = & get_instance();

    // get from session
    $search_criteria = $CI->session->userdata(TOUR_SEARCH_CRITERIA);

    // set default value
    if (empty($search_criteria))
    {

        $today = date('d-m-Y');
        $tommorow = date('d/m/Y', strtotime($today . " +1 day"));

        $search_criteria = array();

        $search_criteria['startdate']   = $tommorow;
        $search_criteria['duration']    = '';
        
        $search_criteria['dep_id']      = '';
        $search_criteria['departure']   = '';

        $search_criteria['is_default']  = true;
        
        $search_criteria['is_default_date']  = true;
    }
    
    if(empty($search_criteria['is_default']))
    {
        $search_criteria['is_default']  = false;
    }
    
    if(empty($search_criteria['is_default_date']))
    {
        $search_criteria['is_default_date']  = false;
    }

    return $search_criteria;
}

function get_tour_search_data($data)
{
    $CI = & get_instance();
    
    $data['duration_search'] = $CI->config->item('tour_duration_search');
    
    $data['departure_destinations'] = $CI->Land_Tour_Model->get_tour_departing_from();
    
    $data['domestic_destinations'] = $CI->Land_Tour_Model->get_tour_departure_destinations();
    
    $data['outbound_destinations'] = $CI->Land_Tour_Model->get_tour_departure_destinations(true);
    
    $data['destination_suggestion'] = $CI->load->view('tours/common/destination_suggestion', $data, TRUE);
    
    return $data;
}

function get_tour_pro_value($tour){
    
    if(!empty($tour['promotions'])){
        
        $pro = $tour['promotions'][0];
        
        if($pro['get_1'] == 0) return '';

        if($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT){
            	
            return lang_arg('deal_value_off', $pro['get_1']);
            	
        }

        if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_BOOKING){
            return lang_arg('deal_value_off', bpv_format_currency($pro['get_1']));
        }
    }

    return '';
}

function tour_build_search_criteria()
{
    $CI = & get_instance();
    
    // Get POST data 
    
    $departure = $CI->input->get_post('departure', true);

    $destination = $CI->input->get_post('destination', true);

    $start_date = $CI->input->get_post('startdate', true);

    $duration = $CI->input->get_post('duration', true);

    $des_id = $CI->input->get_post('des_id', true);
    
    $dep_id = $CI->input->get_post('dep_id', true);
    
    $is_outbound = $CI->input->get_post('is_outbound', true);

    $sort = $CI->input->get_post('sort', true);
    $price = $CI->input->get_post('price', true);
    $from = $CI->input->get_post('from', true);
    $category = $CI->input->get_post('category', true);
    $page = $CI->input->get_post('page', true);
    
    // filter
    $f_departing = $CI->input->get_post('f_departing', true);
    $f_duration = $CI->input->get_post('f_duration', true);
    
    $search_criteria['departure'] = $departure;

    $search_criteria['destination'] = $destination;

    $search_criteria['startdate'] = $start_date;

    $search_criteria['duration'] = $duration;

    $search_criteria['des_id'] = $des_id;
    
    $search_criteria['dep_id'] = $dep_id;
    
    $search_criteria['is_outbound'] = $is_outbound;
    
    $search_criteria['f_departing'] = $f_departing;
    
    $search_criteria['f_duration'] = $f_duration;

    $search_criteria['is_default'] = true;
    
    $search_criteria['is_default_date']  = true;
    
    // Set search_criteria data
    if(empty($search_criteria['startdate']))
    {
        $today = date('d-m-Y');
        $tommorow = date('d/m/Y', strtotime($today . " +1 day"));
    
        $search_criteria['startdate'] = $tommorow;
    } else {
        $search_criteria['is_default_date']  = false;
    }

    if ($sort != '')
        $search_criteria['sort'] = $sort;
    if ($price != '')
        $search_criteria['price'] = $price;
    if ($from != '')
        $search_criteria['from'] = $from;
    if ($category != '')
        $search_criteria['category'] = $category;
    if ($page != '')
        $search_criteria['page'] = $page;

    $CI->session->set_userdata(TOUR_SEARCH_CRITERIA, $search_criteria);

    return $search_criteria;
}

function is_correct_tour_search($search_criteria)
{
    if($search_criteria['is_outbound'] == 0  || $search_criteria['is_outbound'] == 1)
    {
        return true;
    }
    
    if (empty($search_criteria['destination']) || trim($search_criteria['destination']) == '')
    {
        return false;
    }

    return true;
}

function has_land_tour_search_filter($search_criteria)
{
    return ! empty($search_criteria['price']) || ! empty($search_criteria['star']) || ! empty($search_criteria['facility']);
}

/**
  *  get search result title
  *
  *  @author toanlk
  *  @since  Sep 24, 2014
  */
function get_search_result_title($search_criteria, $count)
{
    $txt = '';
    
    if (! empty($search_criteria['departure']))
    {
        $txt = lang('tour_found_of');
        
        $txt = str_replace('<dep>', $search_criteria['departure'], $txt);
        $txt = str_replace('<des>', $search_criteria['destination'], $txt);
    }
    
    if (! empty($search_criteria['departure']) && empty($search_criteria['destination']))
    {
        $txt = lang('tour_depart_found_of');
        $txt = str_replace('<dep>', $search_criteria['departure'], $txt);
    }
    
    if (empty($search_criteria['departure']) && !empty($search_criteria['destination']))
    {
        $txt = lang('tour_destination_found_of');
        $txt = str_replace('<des>', $search_criteria['destination'], $txt);
    }
    
    if (empty($txt))
    {
        if(isset($search_criteria['is_outbound']) && is_numeric($search_criteria['is_outbound']))
        {
            if ($search_criteria['is_outbound'] == 0)
            {
                $txt = lang('tour_found_domestic');
            }
            elseif ($search_criteria['is_outbound'] == 1)
            {
                $txt = lang('tour_found_outbound');
            }
        }
        else 
        {
            $txt = lang('tour_found');
        }
    }

    $txt = str_replace('<nr>', $count, $txt);

    return $txt;
}

function tour_get_selected_filter($search_criteria, $filter_name, $filter_values)
{
    if (! empty($search_criteria[$filter_name]))
    {

        $filter_selected = $search_criteria[$filter_name];

        $filter_selected = explode(',', $filter_selected);

        foreach ($filter_values as $key => $value)
        {

            if (in_array($value['value'], $filter_selected))
            {

                $value['selected'] = true;

                $filter_values[$key] = $value;
            }
        }
    }

    return $filter_values;
}

/**
  *  get tour search overview text
  *
  *  @author toanlk
  *  @since  Sep 24, 2014
  */
function get_tour_search_overview($search_criteria, $type = 'departing')
{
    $txt = '';
    
    if ($type == 'departing')
    {
        // when destination or departing parametera are't empty
        if (!empty($search_criteria['departure']) && !empty($search_criteria['destination']))
        {
            $txt = lang_arg('overview_full_text', $search_criteria['departure'], $search_criteria['destination']);
            
        } elseif (!empty($search_criteria['destination']))
        {
            $txt = lang_arg('overview_destination', $search_criteria['destination']);
        } elseif (!empty($search_criteria['departure']))
        {
            $txt = lang_arg('overview_departing_from', $search_criteria['departure']);
        }
        
        // when is_outbound is avaiable
        if (isset($search_criteria['is_outbound']) && is_numeric($search_criteria['is_outbound']) && empty($txt))
        {
            if ($search_criteria['is_outbound'] == 0)
            {
                $txt = lang('overview_domestic_tour');
            }elseif ($search_criteria['is_outbound'] == 1)
            {
                $txt = lang('overview_outbound_tour');
            }
        }
    }
    else
    {
        if (! empty($search_criteria['duration']))
        {
            $txt = lang('tour_search_overview');
            
            $txt = str_replace('<duration>', $search_criteria['duration'], $txt);
            
            $txt = str_replace('<start>', $search_criteria['startdate'], $txt);
        }
        elseif (! $search_criteria['is_default'] && ! $search_criteria['is_default_date'])
        {
            $txt = lang('tour_search_short_overview');
            $txt = str_replace('<start>', $search_criteria['startdate'], $txt);
        }
    }

    return $txt;
}

/**
  *  get duration text
  *
  *  @author toanlk
  *  @since  Sep 23, 2014
  */
function get_duration_text($tour)
{
    $CI = & get_instance();
    
    $duration = $tour['duration'];
    
    $tour_duration = $CI->config->item('tour_duration_search');
    
    $txt = '';
    
    if ($duration > TOUR_MAX_DURATION)
    {
        $txt = lang($tour_duration[TOUR_MAX_DURATION + 1]);
    }
    else
    {
        foreach ($tour_duration as $key => $value)
        {
            if ($key == $duration)
            {
                $txt = lang($value);
                break;
            }
        }
    }
    
    if(!empty($tour['night']))
    {
        $txt = $txt . ' ' . $tour['night'] . ' '. lang('unit_night');
    }
    
    return $txt;
}

/**
  *  get departure date text
  *
  *  @author toanlk
  *  @since  Sep 18, 2014
  */
function get_departure_date_text($tour) {
    
    $txt = '';
    
    if (! empty($tour['tour_departures']))
    {
        foreach ($tour['tour_departures'] as $departure)
        {
            $date_lang = _get_departure_date_lang($departure);
            $date_lang = ! empty($date_lang) ? $date_lang : lang('label_updating');
            $row = '<b>' . lang('lbl_departure_date') . $departure['name'] . '</b>: ' . $date_lang;
            $txt .= $row . '<br>';
        }
    }
    
    if (empty($txt))
    {
        $txt = '<b>' . lang('departure_date_short') . '</b>' . lang('label_updating');
    }
    
    return $txt;
}

/**
  *  _get_departure_date_lang
  *
  *  @author toanlk
  *  @since  Sep 18, 2014
  */
function _get_departure_date_lang($obj)
{
    $txt = '';
    
    switch ($obj['departure_date_type'])
    {
        case DEPARTURE_DAILY:
            $txt = lang('daily');
            break;
            
        case DEPARTURE_SPECIFIC_WEEKDAYS:
            // get near search deaparture date
            if(!empty($obj['dates']))
            {
                $txt = _get_weekdays_lang($obj['dates'][0]['weekdays']).' '.lang('weekly');
            }
            
            break;
            
        case DEPARTURE_SPECIFIC_DATES:
            
            // convert format 8-10-2014 to 8/10/2014 and remove the year
            $arr_dates = explode(';', $obj['departure_specific_date']);
            
            if (! empty($arr_dates))
            {
                foreach ($arr_dates as $str_date)
                {
                    $ex_date = explode('-', $str_date);
                    if (count($ex_date) > 1)
                    {
                        $txt .= $ex_date[0] . '/' . $ex_date[1] . '; ';
                    }
                }
                $txt = rtrim(trim($txt), ';');
            }            
            
            break;
    }
    
    return $txt;
}

/**
  *  _get_weekdays_lang
  *
  *  @author toanlk
  *  @since  Sep 18, 2014
  */
function _get_weekdays_lang($depart_weekdays)
{
    $txt = '';
    
    $CI = & get_instance();
    
    $week_days = $CI->config->item('week_days');
    
    foreach($week_days as $k=>$wd)
    {
        if(is_bit_value_contain($depart_weekdays, $k))
        {
            $txt .= strtolower(lang($wd)).', ';
        }
    }
    
    $txt = rtrim(trim($txt), ',');
    
    return $txt;
}

function get_tour_contact_params($tour, $search_criteria)
{
    $CI = & get_instance();

    $params = array(
        'type' => 'tour',
        'tour' => $tour['name'],
        'startdate' => $search_criteria['startdate'],
    );

    if (! empty($search_criteria['destination']))
    {
        $params['destination'] = $search_criteria['destination'];
    }

    if (! empty($search_criteria['duration']))
    {
        $params['duration'] = $search_criteria['duration'];
    }

    return $params;
}

function get_tour_support() {
    
    $CI = & get_instance();
    
    echo $CI->load->view('tours/common/support');
}

function tour_search_destination_url($destination, $search_criteria = array())
{
    $url = site_url(TOUR_SEARCH_PAGE);

    if (count($search_criteria) > 0)
    {
        unset($search_criteria['destination']);
        unset($search_criteria['des_id']);
        $url = $url . '#destination=' . $destination['name'] . '&' . http_build_query($search_criteria) . '&des_id='.$destination['id'];
    }

    return $url;
}

function get_land_tour_check_rate_info(){

    $CI =& get_instance();

    $start_date = $CI->input->get_post('startdate', true);

    $end_date = $CI->input->get_post('enddate', true);

    $adults = $CI->input->get_post('adults', true);

    $children = $CI->input->get_post('children', true);

    $infants = $CI->input->get_post('infants', true);

    $departure_id = $CI->input->get_post('departure_id', true);

    if( !empty($start_date)) {

        $check_rate_info['startdate'] = $start_date;

        $check_rate_info['enddate'] = $end_date;

        $check_rate_info['adults'] = $adults;

        if( !empty($children)) {
            $check_rate_info['children'] = $children;
        }

        if( !empty($infants)) {
            $check_rate_info['infants'] = $infants;
        }


        $check_rate_info['departure_id'] = $departure_id;

        $check_rate_info['is_default'] = false;
        
        $check_rate_info['is_default_date'] = false;

    } else {

        $search_criteria = get_tour_search_criteria();

        $check_rate_info['startdate'] = $search_criteria['startdate'];

        $check_rate_info['enddate'] = $search_criteria['enddate'];

        $check_rate_info['adults'] = $search_criteria['adults'];

        $check_rate_info['children'] = $search_criteria['children'];

        $check_rate_info['infants'] = $search_criteria['infants'];

        if(!empty($search_criteria['dep_id'])) {
            $check_rate_info['departure_id'] = $search_criteria['dep_id'];
        } else {
            $check_rate_info['departure_id'] = null;
        }

        $check_rate_info['is_default'] = true;
        
        $check_rate_info['is_default_date'] = true;
    }

    if( empty($check_rate_info['adults'])) {
        $check_rate_info['adults'] = 2;
    }

    return $check_rate_info;

}


/**
 * Khuyenpv: 11.09.2014
 * Load Popular Destinations
 */
function load_popular_tour_destinations($popular_destinations, $is_domistic = true, $is_mobile = false){

	$CI =& get_instance();

	$data['popular_destinations'] = $popular_destinations;

	$data['is_domistic'] = $is_domistic;
	
	if($is_mobile){
		
		$popular_des_view = $CI->load->view('mobile/tours/destination/popular_destinations', $data, true);
	}else{
		
		$popular_des_view = $CI->load->view('tours/destination/popular_destinations', $data, true);
	}
	return $popular_des_view;
}

/**
 * Khuyenpv: 11.09.2014
 * Load Recommended Tour By Category
 */
function load_recommended_tour_by_category($categories){

	$CI =& get_instance();

	$data['categories'] = $categories;

	$tour_cat_view = $CI->load->view('tours/destination/recommended_tour_by_category', $data, true);

	return $tour_cat_view;

}


/**
 * Khuyenpv 12.09.2013
 * Get Search-Link based on Search Options Parameter (is_outbound, destination_id, category_id)
 */

function get_tour_search_link($options){
	
	$url = site_url(TOUR_SEARCH_PAGE);
	
	$url = $url.'#'.http_build_query($options);
	
	return $url;
}


/**
 * Khuyenpv: 11.09.2014
 * Load Recommended Tour By Category
 */

function load_tour_departure_from($departure_destinations, $options){
	
	$CI =& get_instance();
	
	$count_depatures = $CI->Land_Tour_Model->count_tour_departure($options);
	
	foreach ($departure_destinations as $key=>$des){
	
		$des['nr_tour'] = 0;
		
		foreach ($count_depatures as $cd){
			
			if($cd['destination_id'] == $des['id']){

				$des['nr_tour'] = $cd['nr_tour'];
				
				break;
			}
		}
		
		$options['dep_id'] = $des['id'];
		$options['departure'] = $des['name'];
		
		$des['search_link'] = get_tour_search_link($options);
		
		$departure_destinations[$key] = $des;
		
	}
	
	$data['departure_destinations'] = $departure_destinations;
	
	$departure_from_view = $CI->load->view('tours/common/departure_from', $data, true);
	

	return $departure_from_view;
}

/**
 * Khuyenpv: 12.09.2014
 * Load Filter Duration
 */
function load_filter_duration($options){
	
	$CI =& get_instance();
	
	$count_durations = $CI->Land_Tour_Model->count_tour_durations($options);
	
	$tour_durations = $CI->config->item('tour_duration_search');
	
	$filter_durations = array();
	
	foreach ($tour_durations as $key => $value){
		
		$nr_tour = 0;
		
		
		foreach($count_durations as $cd){
			
			if($key <= 15){
				
				if($cd['duration'] == $key){
					$nr_tour = $cd['nr_tour'];
					break;
				}
				
			} else {
				
				if($cd['duration'] > $key){
					$nr_tour += $cd['nr_tour'];
				}
				
			}
			
		}
		
		$options['duration'] = $key;
		
		$filter_durations[] = array(
			'label' => lang($value),
			'value' => $key,
			'search_link' => get_tour_search_link($options),
			'nr_tour' => $nr_tour,
		);
	}
	
	$data['filter_durations'] = $filter_durations;
	
	$tour_durations  = $CI->load->view('tours/common/tour_durations', $data, TRUE);
	
	return $tour_durations;
}

/**
  *  update_tour_search_criteria_by_checkrate
  *
  *  @author toanlk
  *  @since  Sep 16, 2014
  */
function update_tour_search_criteria_by_checkrate($startdate, $enddate, $adults, $children, $infants, $departure_id)
{
    $CI = & get_instance();

    $search_criteria = get_land_tour_search_criteria();

    if (! empty($startdate) && ! empty($enddate))
    {
        $search_criteria['startdate'] = $startdate;
        $search_criteria['enddate'] = $enddate;
        $search_criteria['adults'] = $adults;
    }

    if (! empty($children))
    {
        $search_criteria['children'] = $children;
    }

    if (! empty($infants))
    {
        $search_criteria['infants'] = $infants;
    }
    
    if (! empty($departure_id))
    {
        $search_criteria['dep_id'] = $departure_id;
    }

    $CI->session->set_userdata(TOUR_SEARCH_CRITERIA, $search_criteria);

    return $search_criteria;
}

/**
  *  tour_booking_url
  *
  *  @author toanlk
  *  @since  Sep 16, 2014
  */
function land_tour_booking_url($tour, $search_criteria)
{
    $url = site_url(TOUR_BOOKING_PAGE . $tour['url_title'] . '-' . $tour['id'] . '.html');

    $params['startdate'] = $search_criteria['startdate'];

    $params['enddate'] = $search_criteria['enddate'];

    $params['adults'] = $search_criteria['adults'];

    $params['children'] = $search_criteria['children'];

    $params['infants'] = $search_criteria['infants'];

    $url = $url . '?' . http_build_query($params);

    return $url;
}

/**
  *  get_land_tour_accommodation_cancellation
  *
  *  @author toanlk
  *  @since  Sep 16, 2014
  */
function get_land_tour_accommodation_cancellation($tour, $accommodation_rate, $startdate)
{
    $CI = & get_instance();

    $data['tour'] = $tour;

    $data['accommodation_rate'] = $accommodation_rate;

    $data['startdate'] = $startdate;

    $accommodation_cancellation = $CI->load->view('tours/common/cancellation_info', $data, TRUE);

    return $accommodation_cancellation;
}

/**
  *  load view tour destinations
  *  
  *  type = 0:domestic destinations, 1:outbound destinations, 2:category
  *
  *  @author toanlk
  *  @since  Sep 16, 2014
  */
function load_view_tour_destination($type = NAV_VIEW_DOMESTIC)
{
    $CI = & get_instance();
    
    $data['type'] = $type;
    
    $is_mobile = is_mobile();
    
    $mobile_view = $is_mobile ? 'mobile/' : '';
    
    switch ($type)
    {
        case NAV_VIEW_OUTBOUND:
            $data['tour_destinations'] = $CI->Land_Tour_Model->get_tour_departure_destinations(true);
            echo $CI->load->view($mobile_view.'tours/common/tour_destinations', $data, TRUE);
            break;
            
        case NAV_VIEW_CATEGORY:
            $data['tour_categories'] = $CI->Land_Tour_Model->get_categories();
            echo $CI->load->view($mobile_view.'tours/common/tour_destinations', $data, TRUE);
            break;
        
        default:
            
            $data['tour_destinations'] = $CI->Land_Tour_Model->get_tour_departure_destinations();
            echo $CI->load->view($mobile_view.'tours/common/tour_destinations', $data, TRUE);
            break;
    }
}

/**
  *  get tour transportation
  *
  *  @author toanlk
  *  @since  Sep 23, 2014
  */
function get_tour_transportation($transportations, $is_first_only = false)
{
    $txt= '';
    
    $CI = & get_instance();
    
    $trans_config = $CI->config->item('transportations');
    
    if(!empty($transportations))
    {
        foreach ($trans_config as $key=>$value)
        {
            if( in_array($key, explode('-', $transportations)) )
            {
                $value = str_replace('cruise', 'boat', $value);
                $txt .= '<span class="icon '.$value.'"></span>';
        
                // get only first icon
                if($is_first_only) break;
            }
        }
        
        $txt = rtrim(trim($txt), ',');
    }
    
    return $txt;
}


/**
 * load_tour_contact
 *
 * 	option[mode]  :1 = full ? right, option[tour_name] : !="" = tour_name ? hidden
 *
 * @author toanlk
 * @since  Dec 9, 2014
 */
function load_tour_contact($option_contact){
	
	$CI = & get_instance();
	
	$mobile_view = is_mobile() ? 'mobile/' : '';
	
	// submit contact
	$CI->load->library('form_validation');
	
	$CI->form_validation->set_rules('t_email', lang('tour_contact_us_email'), 'required|trim|valid_email');
	$CI->form_validation->set_rules('t_phone', lang('tour_contact_us_phone'), 'required|trim|is_natural');
	$CI->form_validation->set_rules('t_request', lang('tour_contact_us_email'), 'trim');
	$CI->form_validation->set_error_delimiters('<div style="padding: 5px 0;" class="bpv-color-warning warning-message" style="display:block">', '</div>');
	
	$CI->form_validation->set_message('required', lang('required'));
	$CI->form_validation->set_message('is_natural', lang('invalid_input'));
	$CI->form_validation->set_message('valid_email', lang('invalid_input'));
	
	$action = $CI->input->post('action');
	
	if($action == ACTION_SUBMIT_REQUEST && $CI->form_validation->run() == true)
	{
	    $customer = array(
	        'full_name'			=> trim($CI->input->post('t_email')),
	        'email'				=> trim($CI->input->post('t_email')),
	        'phone'				=> trim($CI->input->post('t_phone')),
	    );
	    
	    $tour_name  = trim($CI->input->post('t_tour_name'));
	    
	    $special_request = '';
	    
	    if(!empty($tour_name)){
	        $special_request .= "Tour name: ". $tour_name. "\n";
	    }
	    
	    $special_request .= trim($CI->input->post('t_request'));
	    
	    $sr_name = 'Quick Tour Request';
	    
	    $CI->load->model('Booking_Model');
	    
	    $customer_id = $CI->Booking_Model->create_or_update_customer($customer);
	    
	    $customer_booking = get_contact_customer_booking($customer_id, $special_request);
	    
	    $service_reservations = get_contact_service_reservations($special_request, $sr_name);
	    
	    $CI->Booking_Model->save_customer_booking($customer_booking, $service_reservations);
	    
	    // redirect to confirm page
	    redirect(get_url(CONFIRM_PAGE,array('type'=>'tour')));
	}
	
	// load contact view
	$data['$tour_search_criteria'] = get_land_tour_search_criteria();
	
	$data['option_contact'] = $option_contact;
	
	// repopulate action for scroll
	if($action == ACTION_SUBMIT_REQUEST)
	{
	    $data['action'] = $action;
	}
	
	$view_tour_contact = $CI->load->view($mobile_view.'tours/common/tour_contact', $data, TRUE);
	
	return $view_tour_contact;
	
}

/**
  *  load slider
  *
  *  @author toanlk
  *  @since  Sep 22, 2014
  */
function load_slider($photos, $page = TOUR)
{
    $CI = & get_instance();
    
    $mobile_view = is_mobile() ? 'mobile/' : '';
    
    $data['photos'] = $photos;
    
    $data['page'] = $page;
    
    $data['slider_id'] = uniqid('slider-');
    
    echo $CI->load->view($mobile_view.'tours/common/slider', $data, TRUE);
}

// ------------------------------------------------------------------------

/**
 * Get tour departure lang
 *
 * Get tour departure text and returns it as a string.
 *
 * @author toanlk
 * @since  Sep 22, 2014
 */
if ( ! function_exists('get_tour_departure_lang'))
{
    function get_tour_departure_lang($rate_action, $week_days)
    {
        $txt = '';
        if ($rate_action['week_day'] == 127)
        {
            $txt = lang('daily');
        }
        else
        {
            foreach ($week_days as $k => $wd)
            {
                if (is_bit_value_contain($rate_action['week_day'], $k))
                {
                    $txt .= lang($wd) . ', ';
                }
            }
            $txt = str_replace(lang('day_of_week'), '', $txt);
            $txt = rtrim(trim($txt), ',');
            $txt = lang('day_of_week') . ' ' . $txt . ' ' . lang('every_week');
        }
        
        return $txt;
    }
}

// ------------------------------------------------------------------------

/**
 * Get day of week
 *
 * @author toanlk
 * @since  Oct 07, 2014
 */
if ( ! function_exists('get_day_of_week'))
{
	function get_day_of_week($date)
	{
		$CI = & get_instance();
		
		$txt = '';
		
		$week_days = $CI->config->item('week_days');
		
		foreach ($week_days as $k => $wd)
		{
			$day = date('w', strtotime($date));
			if ($day == $k)
			{
				$txt = lang($wd);
				break;
			}
		}
		
		return $txt;
	}
}

// ------------------------------------------------------------------------

/**
  *  get_specific_dates
  *
  *  @author toanlk
  *  @since  Oct 8, 2014
  */
if ( ! function_exists('get_specific_dates'))
{
    function get_specific_dates($departure_specific_dates)
    {
        $departure_dates = array();
        
        $today = date(DATE_FORMAT_STANDARD);
        
        $arr_dates = explode(';', $departure_specific_dates);
        
        foreach ($arr_dates as $value)
        {
            
            $s_dates = explode('-', $value);
            
            // valid date and ignore dates in the past
            if (count($s_dates) > 2 && strtotime($value) >= strtotime($today))
            {
                $day = strlen($s_dates[0]) > 1 ? $s_dates[0] : '0' . $s_dates[0];
                $month = strlen($s_dates[1]) > 1 ? $s_dates[1] : '0' . $s_dates[1];
                $year = $s_dates[2];
                
                $departure_dates[] = $day . '/' . $month . '/' . $year;
            }
        }
        
        return $departure_dates;
    }
}
