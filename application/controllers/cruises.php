<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Cruises extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->language('cruise');
        $this->load->helper(array(
            'cruise',
            'tour',
            'flight',
            'rate',
            'cookie',
            'form',
            'text',
            'display',
            'booking',
            'hotel'
        ));
        
        $this->load->model(array(
            'Cruise_Model',
            'Destination_Model',
            'Hotel_Model',
            'News_Model'
        ));
        $this->load->model('Flight_Model');
        $this->load->model('Advertise_Model');
        $this->load->config('cruise_meta');
        
        // $this->output->enable_profiler(TRUE);
    }

    /**
     * Hotel Home Page
     */
    public function index()
    {
        set_cache_html();
        
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_CRUISES);
        
        // build search form
        $data = build_search_criteria(array(), MODULE_CRUISE, $is_mobile);
        
        $data['meta'] = get_header_meta(CRUISE_HL_HOME_PAGE);
        
        $startdate = $data['cruise_search_criteria']['startdate'];
        
        // Get popular tours
        $halong_des = $this->Destination_Model->get_destination(DESTINATION_HALONG);
        
        $url_params['startdate'] = $startdate;
        $url_params['destination'] = $halong_des['name'];
        $url_params['oid'] = 'd-' . $halong_des['id'];
        $halong_des['url_params'] = $url_params;
        
        $data['halong_des'] = $halong_des;
        
        $data['popular_tours'] = $this->Cruise_Model->get_popular_halongbay_tours($startdate);
        
        // Get recent view items
        $data = load_recent_cruise_items($data, $startdate);
        
        // get advertises
        $data['bpv_ads'] = load_bpv_ads(AD_PAGE_CRUISE_HOME);
        
        $data['bpv_why_us'] = load_bpv_why_us(HOME_PAGE);
        
        $data['n_book_together'] = $this->News_Model->get_news_details(10);
        
        // Right Side: Best cruises in Halong
        $data['popular_cruises'] = $this->Cruise_Model->get_all_halong_bay_cruises();
        
        $data['halong_hotels'] = $this->Hotel_Model->get_hotel_by_destination(DESTINATION_HALONG, null, null, false);
        
        // render view
        $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
        
        $data = get_in_page_theme(CRUISE_HL_HOME_PAGE, $data, $is_mobile);
        
        if($is_mobile){
        	 
        	$data = get_library('flexsilder', $data);
        }
        
        $data['bpv_popular_tours'] = $this->load->view($mobile_view . 'cruises/common/bpv_popular_tours', $data, TRUE);
        
        _render_view($mobile_view . 'cruises/home/cruise_home', $data, $is_mobile);
    }

    public function search()
    {
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_CRUISES);
        
        $data = get_in_page_theme(CRUISE_HL_SEARCH_PAGE, array(), $is_mobile);
        
        $data['meta'] = get_header_meta(CRUISE_HL_SEARCH_PAGE);
        
        $data['duration_search'] = $this->config->item('duration_search');
        
        $search_criteria = cruise_build_search_criteria();
        
        $data['search_criteria'] = $search_criteria;
        
        $data['popular_cruises'] = $this->Cruise_Model->get_all_halong_bay_cruises();
        
        $action = $this->input->post('action');
        
        // user click on search button
        if ($action == ACTION_SEARCH)
        {
            
            $redirect_url = http_build_query($search_criteria);
            
            redirect(site_url(CRUISE_HL_SEARCH_PAGE . '#' . $redirect_url));
        }
        else
        { // user access from the browser link
            
            $is_ajax = $this->input->get('isAjax');
            
            $is_correct_search = is_correct_cruise_search($search_criteria);
            
            // ajax geting data
            if ($is_ajax)
            {
                
                if ($is_correct_search)
                {
                    
                    if (! empty($search_criteria['oid']))
                    {
                        
                        $oid = $search_criteria['oid'];
                        
                        // auto complete select Hotel
                        if (strpos($oid, 'c-') !== FALSE)
                        {
                            
                            $cruise_id = str_replace('c-', '', $oid);
                            
                            $selected_cruise = $this->Cruise_Model->get_search_cruise($cruise_id);
                            
                            if (! empty($selected_cruise))
                            {
                                
                                $data['selected_cruise'] = $selected_cruise;
                                
                                // $this->_go_to_cruise_detail_page($data);
                                
                                $this->_go_search_tours_page($data);
                            }
                            else
                            {
                                
                                $this->_go_to_search_suggestion_page($data);
                            }
                        }
                        elseif (strpos($oid, 'd-') !== FALSE)
                        { // autocomplete select cruise
                            
                            $destination_id = str_replace('d-', '', $oid);
                            
                            $selected_des = $this->Destination_Model->get_search_destination($destination_id);
                            
                            if (! empty($selected_des))
                            {
                                
                                $data['selected_des'] = $selected_des;
                                
                                $this->_go_search_tours_page($data);
                            }
                            else
                            {
                                
                                $this->_go_to_search_suggestion_page($data);
                            }
                        }
                        else
                        {
                            
                            $this->_go_to_search_suggestion_page($data);
                        }
                    }
                    else
                    {
                        
                        $this->_go_to_search_suggestion_page($data);
                    }
                }
                else
                {
                    $this->load->view($mobile_view . 'cruises/common/cruise_search_top', $data);
                }
            }
            else
            {
                _render_view($mobile_view . 'cruises/cruise_search/search_waiting', $data, $is_mobile);
                
                // $data['bpv_content'] = $this->load->view('cruises/cruise_search/search_waiting', $data, TRUE);
                
                // $this->load->view('_templates/bpv_layout', $data);
            }
        }
    }

    public function search_suggestion()
    {
        $is_mobile = is_mobile();
        
        $data = get_in_page_theme(CRUISE_HL_SEARCH_PAGE, array(), $is_mobile);
        
        $data['destination_types'] = $this->config->item('destination_types');
        
        $search_criteria = cruise_build_search_criteria();
        
        $data['popular_cruises'] = $this->Cruise_Model->get_all_halong_bay_cruises();
        
        $data['search_criteria'] = $search_criteria;
        
        // load destination suggestion
        $destination_suggestions = $this->Cruise_Model->search_destination_suggestion($search_criteria['destination']);
        
        // load cruise suggestions
        $cruise_suggestions = $this->Cruise_Model->search_cruise_suggestion($search_criteria['destination']);
        
        if (count($destination_suggestions) == 0 && count($cruise_suggestions) == 0)
        {
            
            $data['no_suggestion'] = 1;
            
            $cruise_suggestions = $this->Cruise_Model->search_main_cruise_suggestion();
        }
        else
        {
            // only 1 destinations
            if (count($destination_suggestions) >= 1 && count($cruise_suggestions) == 0 && is_first_match($destination_suggestions, $search_criteria['destination']))
            {
                
                $selected_des = $destination_suggestions[0];
                
                redirect(cruise_search_destination_url($selected_des, $search_criteria));
                
                // only 1 cruise
            }
            elseif (count($destination_suggestions) == 0 && count($cruise_suggestions) >= 1 && is_first_match($cruise_suggestions, $search_criteria['destination'], array(
                'du thuyen'
            )))
            {
                
                $selected_cruise = $cruise_suggestions[0];
                
                redirect(cruise_build_url($selected_cruise, $search_criteria));
            }
        }
        
        $data['destination_suggestions'] = $this->_re_structure_destination_suggestions($destination_suggestions);
        
        $data['cruise_suggestions'] = $cruise_suggestions;
        
        if ($is_mobile)
        {
            $data = build_search_criteria($data, MODULE_CRUISE, $is_mobile);
        }
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        _render_view( $mobile_view . 'cruises/cruise_search/search_suggestion', $data, $is_mobile);
    }

    function _load_search_form($data, $is_mobile = false)
    {
        $search_criteria = $data['search_criteria'];
        
        $data['search_criteria'] = $search_criteria;
        
        $data['max_nights'] = $this->config->item('max_nights');
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['cruise_search_overview'] = $this->load->view( $mobile_view.'cruises/common/search_overview', $data, TRUE);
        
        $data['cruise_search_form'] = $this->load->view( $mobile_view.'cruises/common/search_form', $data, TRUE);
        
        return $data;
    }

    function _load_sort_by($data)
    {
        $search_criteria = $data['search_criteria'];
        
        $sort_by = $this->config->item('sort_by');
        
        if (! empty($search_criteria['sort']) && isset($sort_by[$search_criteria['sort']]))
        {
            
            $sort_by['popular']['selected'] = false;
            
            $sorted = $sort_by[$search_criteria['sort']];
            
            $sorted['selected'] = true;
            
            $sort_by[$search_criteria['sort']] = $sorted;
        }
        
        $data['sort_by'] = $sort_by;
        
        return $data;
    }

    function _load_prices_for_filter($data)
    {
        $search_criteria = $data['search_criteria'];
        
        $filter_price = $this->config->item('filter_price');
        
        $count_prices = $this->Cruise_Model->get_search_filter_prices($search_criteria);
        
        foreach ($count_prices as $value)
        {
            if ($value['range_index'] > 0)
            {
                $filter_price[$value['range_index']]['number'] = $value['number'];
            }
        }
        
        $data['filter_price'] = cruise_get_selected_filter($search_criteria, 'price', $filter_price);
        
        return $data;
    }

    function _load_stars_for_filter($data)
    {
        $search_criteria = $data['search_criteria'];
        
        $filter_star = $this->config->item('filter_star');
        
        $count_stars = $this->Cruise_Model->get_search_filter_stars($search_criteria);
        
        foreach ($filter_star as $key => $value)
        {
            
            $cnt = 0;
            
            foreach ($count_stars as $v)
            {
                if ($v['star'] == $key || $v['star'] == ($key + 0.5))
                {
                    $cnt += $v['number'];
                }
            }
            
            $value['number'] = $cnt;
            
            $filter_star[$key] = $value;
        }
        
        $data['filter_star'] = cruise_get_selected_filter($search_criteria, 'star', $filter_star);
        
        return $data;
    }

    function _load_facilities_for_filter($data)
    {
        $search_criteria = $data['search_criteria'];
        
        $filter_facility = $this->Cruise_Model->get_search_filter_facilities($search_criteria);
        
        $data['filter_limit'] = $this->config->item('filter_limit');
        
        if (count($filter_facility) > 0)
        {
            
            foreach ($filter_facility as $key => $value)
            {
                
                $value['value'] = $value['id'];
                $value['label'] = $value['name'];
                $value['selected'] = FALSE;
                // $value['number'] = 0;
                
                $filter_facility[$key] = $value;
            }
            
            $filter_facility = cruise_get_selected_filter($search_criteria, 'facility', $filter_facility);
            
            $data['filter_facility'] = $filter_facility;
        }
        
        return $data;
    }

    public function _go_to_cruise_detail_page($data)
    {
        $selected_cruise = $data['selected_cruise'];
        
        $search_criteria = $data['search_criteria'];
        
        $check_rate_params['startdate'] = $search_criteria['startdate'];
        $check_rate_params['enddate'] = $search_criteria['enddate'];
        $check_rate_params['duration'] = $search_criteria['duration'];
        
        echo '[cruise-detail-page]' . site_url(CRUISE_HL_DETAIL_PAGE . $selected_cruise['url_title'] . '-' . $selected_cruise['id'] . '.html?' . http_build_query($check_rate_params));
    }

    public function _go_to_search_suggestion_page($data)
    {
        $search_criteria = $data['search_criteria'];
        
        echo '[search-suggestion]' . site_url(CRUISE_HL_HOME_PAGE . '/ho-tro-tim-kiem.html?' . http_build_query($search_criteria));
    }

    public function _go_search_tours_page($data)
    {
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $search_criteria = $data['search_criteria'];
        
        if (! empty($data['selected_cruise']))
        { // search tours by cruise
            
            $search_criteria['selected_cruise'] = $data['selected_cruise'];
        }
        else
        {
            // filter area
            if (! empty($search_criteria['area']))
            { // search tours by destination
                
                $area_des_id = $search_criteria['area'];
                
                $area_des = $this->Destination_Model->get_search_destination($area_des_id);
                
                $search_criteria['area_des'] = $area_des;
            }
            
            $selected_des = $data['selected_des'];
            
            $search_criteria['selected_des'] = $selected_des;
        }
        
        $search_criteria['is_default'] = false;
        
        $data['search_criteria'] = $search_criteria;
        
        // ------------- LOAD FILTER
        if (empty($data['selected_cruise']))
        {
            $data = $this->_load_prices_for_filter($data);
            
            $data = $this->_load_stars_for_filter($data);
            
            $data = $this->_load_facilities_for_filter($data);
        }
        
        // ------------- Load form
        $data = $this->_load_search_form($data, $is_mobile);
        
        $data = $this->_load_sort_by($data);
        
        $data = $this->_load_search_cruises($data);
        
        $data = $this->_set_paging_info($data);
        
        $data['cruise_list_results'] = $this->load->view($mobile_view . 'cruises/common/cruise_list', $data, TRUE);
        
        $data['cruise_search_filters'] = $this->load->view($mobile_view . 'cruises/cruise_search/search_filters', $data, TRUE);
        
        $data['cruise_search_sorts'] = $this->load->view($mobile_view . 'cruises/cruise_search/search_sorts', $data, TRUE);
        
        $this->load->view($mobile_view . 'cruises/cruise_search/search_results', $data);
    }

    function _load_search_cruises($data)
    {
        $search_criteria = $data['search_criteria'];
        
        $data['count_results'] = $this->Cruise_Model->count_search_tours($search_criteria);
        
        $tours = $this->Cruise_Model->search_tours($search_criteria);
        
        $facilities = isset($data['filter_facility']) ? $data['filter_facility'] : array();
        
        foreach ($tours as $key => $tour)
        {
            
            $tour = get_important_cruise_facility($tour, $facilities);
            $tours[$key] = $tour;
        }
        
        $data['tours'] = $tours;
        
        return $data;
    }

    function _re_structure_destination_suggestions($destination_suggestions)
    {
        $ret = array();
        
        foreach ($destination_suggestions as $value)
        {
            
            $ret[$value['type']][] = $value;
        }
        
        return $ret;
    }

    public function _set_paging_info($data)
    {
        $this->load->library('pagination');
        
        $search_criteria = $data['search_criteria'];
        
        $offset = ! empty($search_criteria['page']) ? $search_criteria['page'] : 0;
        
        $paging_config = $this->config->item('paging_config');
        
        $total_rows = $data['count_results'];
        
        $paging_config = get_paging_config($total_rows, site_url(CRUISE_HL_SEARCH_PAGE), 1);
        // initialize pagination
        $this->pagination->initialize($paging_config);
        
        $paging_info['paging_text'] = get_paging_text($total_rows, $offset);
        
        $paging_info['paging_links'] = $this->pagination->create_links();
        
        $data['paging_info'] = $paging_info;
        
        return $data;
    }

    function suggest_cruises()
    {
        $term = $this->input->get('query');
        
        $cruises = $this->Cruise_Model->suggest_cruises($term);
        
        foreach ($cruises as $k => $cruise)
        {
            $cruises[$k]['type'] = 'c';
        }
        
        echo json_encode($cruises);
    }

    function suggest_destinations()
    {
        $term = $this->input->get('query');
        
        $destinations = $this->Cruise_Model->search_destination_suggestion($term);
        
        foreach ($destinations as $k => $des)
        {
            $destinations[$k]['type'] = 'd';
        }
        
        echo json_encode($destinations);
    }
}
