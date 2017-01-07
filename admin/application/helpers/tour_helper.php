<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function _get_tour_data($data = array(), $id = null){
	
	$CI =& get_instance();

	if(empty($id)) {
		$id = (int)$CI->uri->segment(NORMAL_ID_SEGMENT);
	}	

	$CI->load->model('Tour_Model');
	$tour = $CI->Tour_Model->get_tour($id);
	$tour_des = $CI->Tour_Model->get_tour_route($id);
	
	$route_ids = array();
	$route_hidden_ids = array();
	$land_tour_ids = array();
	
	foreach ($tour_des as $d) {
		$route_ids[] = $d['destination_id'];
		
		if($d['hidden'] == 1) {
			$route_hidden_ids[] = $d['destination_id'];
		}

		if($d['is_land_tour'] == 1) {
		    $land_tour_ids[] = $d['destination_id'];
		}
	}
	
	$str = '';
	foreach ($route_ids as $element) {
		$str .= $element.'-';
	}
		
	$tour['route_ids'] = rtrim($str, "-");
	
	$str = '';
	foreach ($route_hidden_ids as $element) {
		$str .= $element.'-';
	}
	
	$tour['route_hidden_ids'] = rtrim($str, "-");
	
	$str = '';
	foreach ($land_tour_ids as $element) {
	    $str .= $element.'-';
	}
	
	$tour['land_tour_ids'] = rtrim($str, "-");
	
	if ($tour == false) {
		_show_error_page(lang('tour_notfound'));
		exit;
	}
	
	$tour['tour_destinations'] = $tour_des;

	$data['tour'] = $tour;

	return $data;
}

function is_tour_detail() {
	$CI =& get_instance();
	$current_menu = $CI->session->userdata('MENU');

	if($current_menu == MNU_TOUR_PROFILE
		|| $current_menu == MNU_TOUR_RATE_AVAILABILITY
		|| $current_menu == MNU_TOUR_REVIEWS
		|| $current_menu == MNU_TOUR_CONTRACT
	    || $current_menu == MNU_TOUR_PROMOTION) {
		return true;
	}

	return false;
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
        $txt = $txt . ' ' . $tour['night'] . ' đêm';
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

    if (empty($tour['departure_type']))
    {
        return $txt;
    }

    // Single departure
    if ($tour['departure_type'] == SINGLE_DEPARTING_FROM)
    {
        $txt = _get_departure_date_lang($tour);
    }
    else
    { // Multiple departure
        foreach ($tour['tour_departures'] as $departure)
        {
            $date_lang = _get_departure_date_lang($departure);
            $date_lang = !empty($date_lang) ? $date_lang : lang('label_updating');
            $row = '<b>'.$departure['name'] .'</b>: '. $date_lang;
            $txt .= $row.'<br>';
        }
    }

    if(empty($txt)) $txt = lang('label_updating');

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
            $txt = lang('label_daily');
            break;

        case DEPARTURE_SPECIFIC_WEEKDAYS:
            // get near search deaparture date
            if(!empty($obj['dates']))
            {
                $txt = _get_weekdays_lang($obj['dates'][0]['weekdays']);
            }

            break;

        case DEPARTURE_SPECIFIC_DATES:
            $txt = $obj['departure_specific_date'];
            break;
    }

    return $txt;
}

/**
 *  get tour transportation
 *
 *  @author toanlk
 *  @since  Sep 23, 2014
 */
function get_tour_transportation($transportations, $itinerary)
{
    $txt= '';

    foreach ($transportations as $key=>$value)
    {
        if( in_array($key, explode('-', $itinerary['transportations'])) )
        {
            $txt .= $value.', ';
        }
    }

    $txt = rtrim(trim($txt), ',');

    return $txt;
}

/**
  *  get tour meals
  *
  *  @author toanlk
  *  @since  Sep 23, 2014
  */
function get_tour_meals($txtMeals)
{
    if (! empty($txtMeals))
    {
        $CI = & get_instance();
        $c_config = $CI->config->item('tour_meals');
        $str = '';
        $meals = explode('-', $txtMeals);
        foreach ($meals as $meal)
        {
            if (! empty($meal) && isset($c_config[$meal]))
            {
                $str .= $c_config[$meal] . ' / ';
            }
        }
        $str = substr($str, 0, strlen($str) - 3);
        return $str;
    }
    
    return '';
}

/**
 * Get tour route as plain text or hyperlink
 */
function get_route($tour)
{
    $txt = '';
    $route = explode('#', $tour['routes']);
    
    foreach ($route as $value)
    {
        if(empty($value)) continue;
    
        $arr = explode(';', $value);
        if (! empty($arr))
        {
            $txt .= $arr[0] . ' - ';
        }
    }
    
    $tour['route'] = trim(rtrim(trim($txt), '-'));
    
    return $tour;
}

/**
 *  create itinerary pdf
 *
 *  @author toanlk
 *  @since  Sep 23, 2014
 */
function create_itinerary_pdf($id)
{
    $CI = & get_instance();
    
    // load library
    $CI->load->helper('file');
    $CI->load->library('dompdf_gen');
    $CI->config->load('itinerary_meta');
    
    $CI->load->model('Tour_Model');
    $CI->load->model('Tour_Departure_Model');
     
    $directory_path = str_replace('system/', $CI->config->item('itinerary_path'), BASEPATH);

    // get data
    $tour = $CI->Tour_Model->get_tour($id);
     
    if(empty($tour)) return false;
     
    $tour_departures = $CI->Tour_Departure_Model->get_tour_departures($tour['id']);
     
    if(empty($tour_departures)) return false;

    $tour = get_route($tour);
     
    $data['tour'] = $tour;

    $data['transportations'] = $CI->config->item('tour_transportations');
    
    //$CI->load->library('');

    // get itinerary
    foreach ($tour_departures as $depart)
    {
        // set file name
        $filename = $tour['url_title'] .'-khoi-hanh-'. $depart['url_title'] .'.pdf';
         
        $file_path = $directory_path . $filename;

        $full_itinerary = $CI->Tour_Model->get_itineraries($tour['id'], $depart['id']);
       
        foreach ($full_itinerary as $k => $itinerary) {
            
            $itinerary['content'] = remove_certain_tag($itinerary['content']);
            
            $itinerary['activities'] = remove_certain_tag($itinerary['activities']);
            
            $full_itinerary[$k] = $itinerary;
        }
        
        
        $data['tour']['full_itinerary'] = $full_itinerary;

        $html = $CI->load->view('tours/itinerary/pdf_template', $data, TRUE);

        $pdf = $CI->dompdf_gen->pdf_create($html, '', false);

        $status = write_file($file_path, $pdf);
        
        if(!$status) {
            log_message('error', '[DEBUG] Failed create itinerary PDF: '.$file_path);
        }
    }
}

function remove_certain_tag($html, $tag = 'des') {
    
    $html = str_replace('&lt;', '<', $html);
    $html = str_replace('&gt;', '>', $html);
    
    $html = preg_replace('/<\/?' . $tag . '(.|\s)*?>/', '', $html);
    
    return $html;
}

?>