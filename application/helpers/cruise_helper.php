<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Get search data posted from search form
 */
function get_cruise_search_criteria($des = '')
{
    $CI = & get_instance();
    
    // get from session
    $search_criteria = $CI->session->userdata(CRUISE_SEARCH_CRITERIA);
    
    // set default value
    if (empty($search_criteria))
    {
        
        $today = date('d-m-Y');
        $tommorow = date('d/m/Y', strtotime($today . " +1 day"));
        $after_tommorow = date('d/m/Y', strtotime($today . " +2 day"));
        
        $search_criteria = array();
        
        $search_criteria['startdate'] = $tommorow;
        $search_criteria['duration'] = '';
        $search_criteria['enddate'] = $after_tommorow;
        
        $search_criteria['destination'] = 'Hạ Long';
        $search_criteria['oid'] = DESTINATION_HALONG;
        
        $search_criteria['is_default'] = true;
    }
    else
    {
        $search_criteria['is_default'] = false;
    }
    
    if (! empty($des))
    {
        $search_criteria['destination'] = $des['name'];
        $search_criteria['oid'] = 'd-' . $des['id'];
        
        $search_criteria['selected_des'] = $des;
    }
    
    return $search_criteria;
}

function cruise_build_search_criteria()
{
    $CI = & get_instance();
    
    $destination = $CI->input->get_post('destination', true);
    
    $start_date = $CI->input->get_post('startdate', true);
    
    $end_date = $CI->input->get_post('enddate', true);
    
    $duration = $CI->input->get_post('duration', true);
    
    $adults = $CI->input->get_post('adults', true);
    
    $children = $CI->input->get_post('children', true);
    
    $infants = $CI->input->get_post('infants', true);
    
    $oid = $CI->input->get_post('oid', true);
    
    $sort = $CI->input->get_post('sort', true);
    $price = $CI->input->get_post('price', true);
    $star = $CI->input->get_post('star', true);
    $area = $CI->input->get_post('area', true);
    $facility = $CI->input->get_post('facility', true);
    $page = $CI->input->get_post('page', true);
    
    $search_criteria['destination'] = $destination;
    
    $search_criteria['startdate'] = $start_date;
    
    $search_criteria['enddate'] = $end_date;
    
    $search_criteria['duration'] = $duration;
    
    $search_criteria['adults'] = $adults;
    $search_criteria['children'] = $children;
    $search_criteria['infants'] = $infants;
    
    $search_criteria['oid'] = $oid;
    
    $search_criteria['is_default'] = true;
    
    if ($sort != '')
        $search_criteria['sort'] = $sort;
    if ($price != '')
        $search_criteria['price'] = $price;
    if ($star != '')
        $search_criteria['star'] = $star;
    if ($area != '')
        $search_criteria['area'] = $area;
    if ($facility != '')
        $search_criteria['facility'] = $facility;
    if ($page != '')
        $search_criteria['page'] = $page;
    
    $CI->session->set_userdata(CRUISE_SEARCH_CRITERIA, $search_criteria);
    
    return $search_criteria;
}

function is_correct_cruise_search($search_criteria)
{
    if (empty($search_criteria['destination']) || trim($search_criteria['destination']) == '')
    {
        return false;
    }
    
    if (empty($search_criteria['startdate']) || ! check_bpv_date($search_criteria['startdate']))
    {
        return false;
    }
    
    /*
     * if(empty($search_criteria['enddate']) || !check_bpv_date($search_criteria['enddate'])){ return false; } if(empty($search_criteria['duration'])){ return false; }
     */
    
    return true;
}

function load_recent_cruise_items($data, $startdate, $cruise_id = null, $is_small_layout = false, $title = null)
{
    $CI = & get_instance();
    
    $recent_items = $CI->session->userdata(RECENT_ITEMS);
    
    if (! empty($cruise_id) && ! empty($recent_items['cruise']))
    {
        $recent_cruises = array();
        
        foreach ($recent_items['cruise'] as $cruise)
        {
            if ($cruise['cruise_id'] != $cruise_id)
            {
                $recent_cruises[] = $cruise;
            }
        }
        
        // $recent_items['cruise'] = $recent_cruises;
    }
    
    $data['is_small_layout'] = $is_small_layout;
    
    $data['recent_items_title'] = ! empty($title) ? $title : lang('recent_items');
    
    $data['recent_cruises'] = $CI->Cruise_Model->get_recent_cruises($recent_items, $startdate);
    
    $data['bpv_recent_cruise'] = $CI->load->view('cruises/common/bpv_recent_cruise', $data, TRUE);
    
    return $data;
}

function get_tour_duration($tour)
{
    $txt = $tour['duration'] . ' ' . lang('day') . ' ' . ($tour['duration'] - 1) . ' ' . lang('night');
    
    return $txt;
}

function get_cruise_overview_txt_1($search_criteria)
{
    $txt = '';
    
    if (isset($search_criteria['selected_des']))
    {
        
        $selected_des = $search_criteria['selected_des'];
        
        $txt .= lang('cruise_at') . ' ' . $selected_des['name'];
    }
    
    if (isset($search_criteria['selected_cruise']))
    {
        $txt = $search_criteria['selected_cruise']['name'];
    }
    
    return $txt;
}

function get_cruise_overview_txt_2($search_criteria)
{
    if (! empty($search_criteria['duration']))
    {
        $txt = lang('cruise_search_overview');
        
        $txt = str_replace('<duration>', $search_criteria['duration'], $txt);
        
        $txt = str_replace('<start>', $search_criteria['startdate'], $txt);
        
        $txt = str_replace('<end>', $search_criteria['enddate'], $txt);
    }
    else
    {
        $txt = lang('cruise_search_short_overview');
        $txt = str_replace('<start>', $search_criteria['startdate'], $txt);
    }
    
    return $txt;
}

function get_cruise_selected_date_txt($search_criteria)
{
    $txt = '';
    if (! isset($search_criteria['is_default']) || ! $search_criteria['is_default'])
    {
        
        if (! empty($search_criteria['duration']))
        {
            $txt = lang('cruise_selected_date');
            
            $txt = str_replace('<start>', format_bpv_date($search_criteria['startdate'], DATE_FORMAT, true), $txt);
            
            $txt = str_replace('<duration>', $search_criteria['duration'], $txt);
            
            $txt = str_replace('<end>', format_bpv_date($search_criteria['enddate'], DATE_FORMAT, true), $txt);
        }
        else
        {
            $txt = lang('cruise_short_selected_date');
            
            $txt = str_replace('<start>', format_bpv_date($search_criteria['startdate'], DATE_FORMAT, true), $txt);
        }
    }
    
    return $txt;
}

function cruise_build_url($cruise, $search_criteria = array())
{
    $url = site_url(CRUISE_HL_HOME_PAGE . '/' . $cruise['url_title'] . '-' . $cruise['id'] . '.html');
    
    if (! empty($search_criteria))
    {
        
        $check_rate_info['startdate'] = $search_criteria['startdate'];
        
        if (! empty($search_criteria['duration']))
        {
            $check_rate_info['duration'] = $search_criteria['duration'];
        }
        
        if (! empty($search_criteria['action']))
        {
            $check_rate_info['action'] = $search_criteria['action'];
        }
        
        $url = $url . '?' . http_build_query($check_rate_info);
    }
    
    return $url;
}

function get_cruise_occupancy_text($cabin)
{
    $occupancy = isset($cabin['occupancy']) ? $cabin['occupancy'] : $cabin['max_occupancy'];
    
    $txt = '';
    
    if ($occupancy > 0)
    {
        $txt = lang_arg('occupancy_text', $occupancy);
    }
    
    return $txt;
}

function get_cruise_cabin_square_m2($cabin)
{
    $CI = & get_instance();
    
    $txt = lang('m2_label');
    
    $txt .= ' ' . $cabin['minimum_cabin_size'] . 'm<sup>2</sup>';
    
    $bed_configuration = $CI->config->item('bed_configuration');
    
    if ($cabin['bed_config'] != '')
    {
        $is_first = true;
        foreach ($bed_configuration as $key => $value)
        {
            if ($cabin['bed_config'] & pow(2, $key))
            {
                if ($is_first)
                {
                    $txt .= ', ' . lang($value);
                    $is_first = false;
                }
                else
                {
                    $txt .= ' ' . lang('or_label') . ' ' . lang($value);
                }
            }
        }
    }
    
    return $txt;
}

function get_contact_params($cruise, $search_criteria, $cabin = null)
{
    $CI = & get_instance();
    
    $params = array(
        'type' => 'cruise',
        'cruise' => $cruise['name'],
        'startdate' => $search_criteria['startdate'],
        'enddate' => $search_criteria['enddate']
    );
    
    if (! empty($cabin))
    {
        $params['room'] = $cabin['name'];
    }
    
    if (! empty($search_criteria['duration']))
    {
        $params['room'] = $search_criteria['duration'];
    }
    
    return $params;
}

function get_cabin_detail($cruise, $cabin, $acc = null, $is_mobile = false)
{
    $CI = & get_instance();
    $data['cabin'] = $cabin;
    $data['cruise'] = $cruise;
    
    if (! empty($acc))
    {
        $data['acc'] = $acc;
    }
    
    if ($is_mobile)
    {
        return $CI->load->view('mobile/cruises/cruise_detail/cabin_detail', $data, true);
    }
    else
    {
        return $CI->load->view('cruises/cruise_detail/cabin_detail', $data, true);
    }
}

function load_cruise_cabin_cancellation($cruise, $room_rate, $startdate)
{
    $CI = & get_instance();
    
    $data['cruise'] = $cruise;
    
    $data['room_rate'] = $room_rate;
    
    $data['startdate'] = $startdate;
    
    $room_cancellation = $CI->load->view('cruises/common/cabin_cancellation_info', $data, TRUE);
    
    return $room_cancellation;
}

function cruise_get_selected_filter($search_criteria, $filter_name, $filter_values)
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

function get_important_cruise_facility($cruise, $facilities)
{
    $important_facilities = array();
    
    $cruise_facilities = explode('-', $cruise['facilities']);
    
    $cruise_facilities = array_unique($cruise_facilities);
    
    foreach ($cruise_facilities as $id)
    {
        
        foreach ($facilities as $value)
        {
            
            if ($value['id'] == $id && $value['is_important'] > 0)
            {
                $important_facilities[] = $value;
            }
        }
    }
    
    $cruise['important_facilities'] = $important_facilities;
    
    return $cruise;
}

function get_search_tours_result_txt($search_criteria, $count, $selected_des = null, $selected_cruise = null)
{
    if (! empty($selected_des))
    {
        $txt = lang('cruise_found_at');
        $txt = str_replace('<des>', $search_criteria['destination'], $txt);
    }
    else
    {
        $txt = lang('tour_found_of');
        $txt = str_replace('<des>', $search_criteria['destination'], $txt);
    }
    
    $txt = str_replace('<nr>', $count, $txt);
    $txt = str_replace('<start>', $search_criteria['startdate'], $txt);
    $txt = str_replace('<end>', $search_criteria['enddate'], $txt);
    
    return $txt;
}

function has_tour_search_filter($search_criteria)
{
    return ! empty($search_criteria['price']) || ! empty($search_criteria['star']) || ! empty($search_criteria['facility']);
}

function cruise_search_destination_url($destination, $search_criteria = array())
{
    $url = site_url(CRUISE_HL_SEARCH_PAGE);
    
    if (count($search_criteria) > 0)
    {
        unset($search_criteria['destination']);
        unset($search_criteria['oid']);
        $url = $url . '#destination=' . $destination['name'] . '&' . http_build_query($search_criteria) . '&oid=d-' . $destination['id'];
    }
    
    return $url;
}

function tour_booking_url($tour, $search_criteria)
{
    $url = site_url(CRUISE_HL_BOOKING_PAGE . $tour['url_title'] . '-' . $tour['id'] . '.html');
    
    $params['startdate'] = $search_criteria['startdate'];
    
    $params['enddate'] = $search_criteria['enddate'];
    
    $params['adults'] = $search_criteria['adults'];
    
    $params['children'] = $search_criteria['children'];
    
    $params['infants'] = $search_criteria['infants'];
    
    $url = $url . '?' . http_build_query($params);
    
    return $url;
}

function update_cruise_search_criteria_by_checkrate($startdate, $enddate, $adults, $children, $infants)
{
    $CI = & get_instance();
    
    $search_criteria = get_tour_search_criteria();
    
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
    
    $CI->session->set_userdata(CRUISE_SEARCH_CRITERIA, $search_criteria);
    
    return $search_criteria;
}

function load_tour_accommodation_cancellation($tour, $accommodation_rate, $startdate)
{
    $CI = & get_instance();
    
    $data['tour'] = $tour;
    
    $data['accommodation_rate'] = $accommodation_rate;
    
    $data['startdate'] = $startdate;
    
    $accommodation_cancellation = $CI->load->view('cruises/common/cabin_cancellation_info', $data, TRUE);
    
    return $accommodation_cancellation;
}

function get_cruise_breakfast_vat_txt($cabin)
{
    if ($cabin['included_vat'])
    {
        $txt = lang('include_text').lang('include_meals').', '.lang('include_vat').' '.lang('include_and').' '.lang('include_service_fee');
    } else {
        $txt = lang('include_text').lang('include_meals').' '.lang('include_and').' '.lang('include_service_fee');
        $txt .= '. '.lang('not_include_text').lang('include_vat');
    }
    
    return $txt;
}

