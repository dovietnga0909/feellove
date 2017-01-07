<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Tours extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->language(array('deal','destination','home','hotel','tour'));
        $this->load->helper(array(
            'tour',
            'land_tour',
            'rate',
            'cookie',
            'form',
            'text',
            'display',
            'booking',
        ));
        
        $this->load->model(array(
            'Land_Tour_Model',
            'Destination_Model',
            'Tour_Model',
        	'Hotel_Model'
        ));
       
        $this->load->config('tour_meta');
        
        // $this->output->enable_profiler(TRUE);
    }

    /**
    *  index()
    *
    *  tour home page
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    public function index()
    {
        set_cache_html();
        
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_TOURS);
        
        // build search form
        $data = build_search_criteria(array(), MODULE_TOUR, $is_mobile);
       	
        $data['meta'] = get_header_meta(TOUR_HOME_PAGE);
        
        // get advertises
        $data['bpv_ads'] = load_bpv_ads(AD_PAGE_TOUR_HOME);
     
        // tour destinations
        $data['domestic_destinations_view'] = $this->_load_view_tour_destination();
        
        $data['outbound_destinations_view'] = $this->_load_view_tour_destination(NAV_VIEW_OUTBOUND);
        
        $data['tour_categories_view'] = $this->_load_view_tour_destination(NAV_VIEW_CATEGORY);
        
        // get popular domestic tours
        
        $startdate = $data['tour_search_criteria']['startdate'];
        
        // get popular outbound tours
        
        $data['domestic_tours_view'] = $this->_get_popular_tours(NAV_VIEW_DOMESTIC, $data, $startdate);
        
        $data['outbound_tours_view'] = $this->_get_popular_tours(NAV_VIEW_OUTBOUND, $data, $startdate);
        
        // get hot tour category
        
        $data['hot_category'] = $this->_get_hot_category($data, $startdate);
        
        // render view
        $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
        
        $option_contact = array(
    		"mode"		=> FULL_TOUR_CONTACT,
    	);
    
    	$data['tour_contact']  	= load_tour_contact($option_contact);
    	
    	$data = load_footer_links($data, false, false, true);
        
        $data = get_in_page_theme(TOUR_HOME_PAGE, $data, $is_mobile);
        
        if($is_mobile){
        	
        	$data = get_library('flexsilder', $data);
        }
        
        _render_view($mobile_view . 'tours/home/tour_home', $data, $is_mobile);
    }
    
    /**
      *  _get_popular_tours
      *
      *  @author toanlk
      *  @since  Oct 3, 2014
      */
    function _get_popular_tours($type, $data, $startdate)
    {
        $mobile_view = is_mobile() ? 'mobile/' : '';
        
        if ($type == NAV_VIEW_DOMESTIC)
        {
            $data['popular_tours'] = $this->Land_Tour_Model->popular_domestic_tour($startdate);
            $data['has_promotion'] = $this->_has_item($data['popular_tours']);
            $data['has_price_off'] = $this->_has_item($data['popular_tours'], 'price_off');
        }
        else
        {
            $data['popular_tours'] = $this->Land_Tour_Model->popular_outbound_tour($startdate);
            $data['has_promotion'] = $this->_has_item($data['popular_tours']);
            $data['has_price_off'] = $this->_has_item($data['popular_tours'], 'price_off');
        }
        
        $view = $this->load->view($mobile_view . 'tours/home/popular_tours', $data, TRUE);
        
        return $view;
    }
    
    /**
      *  check tours has item or not
      *
      *  @author toanlk
      *  @since  Oct 23, 2014
      */
    function _has_item($tours, $type = 'promotion')
    {
        foreach ($tours as $tour)
        {
            if(!empty($tour['promotions']) && $type =='promotion')
            {
                return true;
            }
            
            if(isset($tour['price_origin']) && $tour['price_origin'] != $tour['price_from'] && $type =='price_off')
            {
                return true;
            }
        }
        
        return false;
    }
    
    /**
      *  _get_hot_category
      *
      *  @author toanlk
      *  @since  Oct 3, 2014
      */
    function _get_hot_category($data, $startdate)
    {
        $tour_categories = $this->Land_Tour_Model->get_categories();
        
        if (! empty($tour_categories))
        {
            // get first hot category
            foreach ($tour_categories as $cat)
            {
                if ($cat['is_hot'])
                {
                    $hot_cat = $cat;
                    break;
                }
            }
            
            // if hot cat isn't avaiable get the first one
            if(empty($hot_cat)) $hot_cat = $tour_categories[0];
            
            // get tours
            $hot_cat['tours'] = $this->Land_Tour_Model->get_tour_categories($hot_cat['id'], $startdate, '', 5);
            
            return $hot_cat;
        }
        
        return null;
    }
    
    /**
      *  load_view_tour_destination
      *
      *  @author toanlk
      *  @since  Oct 3, 2014
      */
    function _load_view_tour_destination($type = NAV_VIEW_DOMESTIC)
    {   
        $data['type'] = $type;
    
        $is_mobile = is_mobile();
    
        $mobile_view = $is_mobile ? 'mobile/' : '';
    
        switch ($type)
        {
            case NAV_VIEW_OUTBOUND:
                $data['tour_destinations'] = $this->Land_Tour_Model->get_tour_departure_destinations(true);
                $view = $this->load->view('tours/home/tour_destinations', $data, TRUE);
                break;
    
            case NAV_VIEW_CATEGORY:
                $data['tour_categories'] = $this->Land_Tour_Model->get_categories();
                $view = $this->load->view('tours/home/tour_destinations', $data, TRUE);
                break;
    
            default:
    
                $data['tour_destinations'] = $this->Land_Tour_Model->get_tour_departure_destinations();
                $view = $this->load->view('tours/home/tour_destinations', $data, TRUE);
                break;
        }
        
        return $view;
    }
   
    /**
    *  suggest_destinations()
    *
    *  Suggest tour destinations
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function suggest_destinations() {
        
        $term = $this->input->get('query');
        
        $destinations = $this->Land_Tour_Model->search_destination_suggestion($term);
        
        echo json_encode($destinations);
    }
   
    /**
    *  search()
    *
    *  Search tours
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    public function search() {
        
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_TOURS);
        
        $data = get_in_page_theme(TOUR_SEARCH_PAGE, array(), $is_mobile);
        
        $data['meta'] = get_header_meta(TOUR_SEARCH_PAGE);
        
        $search_criteria = tour_build_search_criteria();
        
        $data = get_tour_search_data($data);
        
        $data['search_criteria'] = $search_criteria;
        
        $action = $this->input->post('action');
        
        // user click on search button
        if ($action == ACTION_SEARCH)
        {
            if(isset($search_criteria['is_outbound']) && !is_numeric($search_criteria['is_outbound']))
            {
                unset($search_criteria['is_outbound']);
            }
            
            $redirect_url = http_build_query($search_criteria);

            redirect(site_url(TOUR_SEARCH_PAGE . '#' . $redirect_url));
        }
        else
        { // user access from the browser link
            $is_ajax = $this->input->get('isAjax');

            $is_correct_search = is_correct_tour_search($search_criteria);
            
            // ajax geting data
            if ($is_ajax)
            {
            
                if ($is_correct_search)
                {
                    
                    if (! empty($search_criteria['des_id']))
                    {
            
                        $destination_id = $search_criteria['des_id'];
            
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
                    elseif($this->_is_not_empty_search_criteria($search_criteria))
                    {
                        $this->_go_search_tours_page($data);
                    }
                    else
                    {

                        $this->_go_to_search_suggestion_page($data);
                    }
                }
                else
                {
                    $this->load->view($mobile_view . 'tours/search/search_top', $data);
                }
            }
            else
            {
                _render_view($mobile_view . 'tours/search/search_waiting', $data, $is_mobile);
            }
        }
    }
    
    /**
      *  check search parameters are not empty
      *
      *  @author toanlk
      *  @since  Nov 4, 2014
      */
    function _is_not_empty_search_criteria($search_criteria)
    {
        if(isset($search_criteria['is_outbound']) && is_numeric($search_criteria['is_outbound']))
        {
            return true;
        }
        
        if(isset($search_criteria['category']) && !empty($search_criteria['category']))
        {
            return true;
        }
        
        if(!empty($search_criteria['des_id']) && !empty($search_criteria['destination']))
        {
            return true;
        }
        
        if(!empty($search_criteria['dep_id']) && !empty($search_criteria['departure']))
        {
            return true;
        }
        
        if(!empty($search_criteria['duration']))
        {
            return true;
        }
        
        return false;
    }
    
    /**
    *  search_suggestion()
    *
    *  search suggestion
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    public function search_suggestion()
    {
        $is_mobile = is_mobile();
    	
        $data = get_in_page_theme(TOUR_SEARCH_PAGE, array(), $is_mobile);
    
        $search_criteria = tour_build_search_criteria();
        
        // remove is_outbound when search
        unset($search_criteria['is_outbound']);

        $data['search_criteria'] = $search_criteria;
    
        // load destination suggestion
        $destination_suggestions = $this->Land_Tour_Model->search_destination_suggestion($search_criteria['destination']);
    
        if (count($destination_suggestions) == 0)
        {
    
            $data['no_suggestion'] = 1;
    
            // ignore results
        }
        elseif (count($destination_suggestions) >= 1 && is_first_match($destination_suggestions, $search_criteria['destination']))
        {
            // only 1 destination
            
            $selected_des = $destination_suggestions[0];
            
            redirect(tour_search_destination_url($selected_des, $search_criteria));
            
        }
    
        if ($is_mobile)
        {
            $data = build_search_criteria($data, MODULE_TOUR, $is_mobile);
        }
        
        // get tour categories
        $data['tour_categories'] = $this->Land_Tour_Model->get_categories();
        
        $data['domestic_destinations'] = $this->Land_Tour_Model->get_tour_departure_destinations();
        
        $data['outbound_destinations'] = $this->Land_Tour_Model->get_tour_departure_destinations(true);
    
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
    
        _render_view( $mobile_view . 'tours/search/search_suggestion', $data, $is_mobile);
    }
    
    /**
    *  _go_search_tours_page()
    *
    *  redirect tour search page
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    public function _go_search_tours_page($data)
    {
        $is_mobile = is_mobile();
    
        $mobile_view = $is_mobile ? 'mobile/' : '';
    
        $search_criteria = $data['search_criteria'];
    
        $data['search_criteria'] = $search_criteria;
    
        // ------------- LOAD FILTER
        $data = $this->_load_tour_filter($data);
    
        // ------------- Load form
        $data = $this->_load_search_form($data, $is_mobile);
    
        $data = $this->_load_sort_by($data);
        
        $data = $this->_load_search_tours($data);
    
        $data = $this->_set_paging_info($data);
    
        $data['list_results'] = $this->load->view($mobile_view . 'tours/common/tour_list', $data, TRUE);
    
        $data['search_filters'] = $this->load->view($mobile_view . 'tours/search/search_filters', $data, TRUE);
    
        $data['search_sorts'] = $this->load->view($mobile_view . 'tours/search/search_sorts', $data, TRUE);
    
        $this->load->view($mobile_view . 'tours/search/search_results', $data);
    }
    
    /**
    *  _load_tour_filter()
    *
    *  get tour filter
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function _load_tour_filter($data)
    {
        $search_criteria = $data['search_criteria'];
        
        // --- Filter Price
    
        $filter_price = $this->config->item('filter_price');
    
        $count_prices = $this->Land_Tour_Model->get_search_filter_prices($search_criteria);
    
        foreach ($count_prices as $value)
        {
            if ($value['mid_range_index'] > 0)
            {
                $filter_price[$value['mid_range_index']]['number'] = $value['number'];
            }
        }
    
        $data['filter_price'] = tour_get_selected_filter($search_criteria, 'price', $filter_price);
        
        // --- Filter Duration : only show the filter when empty duration search
        
        if ( empty($search_criteria['duration']))
        {
            $tour_duration = $this->config->item('tour_duration_search');
            
            $filter_duration = array();
            
            foreach ($tour_duration as $key => $value)
            {
                if($key == 0) continue;
            
                $filter_duration[] = array(
                    'label' => lang($value),
                    'value' => $key,
                    'selected' => false,
                    'number' => 0
                );
            }
            
            $count_durations = $this->Land_Tour_Model->get_search_filter_durations($search_criteria);
            
            $count_above = 0;
            foreach ($count_durations as $value)
            {
                foreach ($filter_duration as $k => $d) {
                    if($d['value'] == $value['duration']) {
                        $filter_duration[$k]['number'] = $value['number'];
                    }
                }
            
                if($value['duration'] > TOUR_MAX_DURATION) {
                    $count_above += $value['number'];
                    $filter_duration[$k]['number'] = $count_above;
                }
            }
            
            $data['filter_duration'] = tour_get_selected_filter($search_criteria, 'f_duration', $filter_duration);
        }
        
        // --- Filter Departing : only show the filter when empty tour departure
        
        if ( empty($search_criteria['dep_id']))
        {
            $filter_departing = array();
            
            foreach ($data['departure_destinations'] as $value)
            {
                $filter_departing[] = array(
                    'label' => $value['name'],
                    'value' => $value['id'],
                    'selected' => false,
                    'number' => 0
                );
            }
            
            $count_departing = $this->Land_Tour_Model->get_search_filter_departing($search_criteria);
            
            foreach ($count_departing as $value)
            {
                foreach ($filter_departing as $k => $d) {
                    if($d['value'] == $value['id']) {
                        $filter_departing[$k]['number'] = $value['number'];
                    }
                }
            }
            
            $data['filter_departing'] = tour_get_selected_filter($search_criteria, 'f_departing', $filter_departing);
        }
        
        // --- Filter Tour Category
        
        $tour_categories = $this->Land_Tour_Model->get_categories();
        
        $filter_categories = array();
        
        foreach ($tour_categories as $value)
        {
            $filter_categories[] = array(
                'label' => $value['name'],
                'value' => $value['id'],
                'selected' => false,
                'number' => 0
            );
        }
        
        $count_categories = $this->Land_Tour_Model->get_search_filter_categories($search_criteria);
        
        foreach ($count_categories as $value)
        {
            foreach ($filter_categories as $k => $d) {
                if($d['value'] == $value['category_id']) {
                    $filter_categories[$k]['number'] = $value['number'];
                }
            }
        }
        
        $data['filter_categories'] = tour_get_selected_filter($search_criteria, 'category', $filter_categories);
    
        return $data;
    }
    
    /**
    *  _load_search_form()
    *
    *  get search form
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function _load_search_form($data, $is_mobile = false)
    {
        $search_criteria = $data['search_criteria'];
    
        $data['search_criteria'] = $search_criteria;
    
        $mobile_view = $is_mobile ? 'mobile/' : '';
    
        $data['search_overview'] = $this->load->view( 'tours/common/search_overview', $data, TRUE);
    
        $data['search_form'] = $this->load->view( $mobile_view.'tours/common/search_form', $data, TRUE);
    
        return $data;
    }
    
    /**
    *  _load_search_tours()
    *
    *  get tours
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function _load_search_tours($data)
    {
        $search_criteria = $data['search_criteria'];
    
        $data['count_results'] = $this->Land_Tour_Model->count_search_tours($search_criteria);
    
        $tours = $this->Land_Tour_Model->search_tours($search_criteria);
    
        $data['tours'] = $tours;
    
        return $data;
    }
    
    /**
    *  _load_sort_by()
    *
    *  get sort by
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
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
    
    /**
    *  _set_paging_info()
    *
    *  set paging information
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    public function _set_paging_info($data)
    {
        $this->load->library('pagination');
    
        $search_criteria = $data['search_criteria'];
    
        $offset = ! empty($search_criteria['page']) ? $search_criteria['page'] : 0;
    
        $paging_config = $this->config->item('paging_config');
    
        $total_rows = $data['count_results'];
        
        $link = site_url(TOUR_SEARCH_PAGE);

        if (isset($search_criteria['is_outbound']) && is_numeric($search_criteria['is_outbound']))
        {
            $link = site_url(TOUR_SEARCH_PAGE . '?is_outbound=' . $search_criteria['is_outbound']);
        }
    
        $paging_config = get_paging_config($total_rows, $link, 1);
        
        // initialize pagination
        $this->pagination->initialize($paging_config);
    
        $paging_info['paging_text'] = get_paging_text($total_rows, $offset);
    
        $paging_info['paging_links'] = $this->pagination->create_links();
    
        $data['paging_info'] = $paging_info;
    
        return $data;
    }
    
    /**
    *  _go_to_search_suggestion_page()
    *
    *  redirect tour search results page
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function _go_to_search_suggestion_page($data) {
        
        $search_criteria = $data['search_criteria'];
        
        echo '[search-suggestion]' . site_url(TOUR_HOME_PAGE . '/ho-tro-tim-kiem.html?' . http_build_query($search_criteria));
    }
   
    
    /*
     * Domestic Tours
     */
    public function domestic_tours() {
    	
    	set_cache_html();
    	
    	$is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_TOURS);
        
        // build search form
        $data = build_search_criteria(array(), MODULE_TOUR, $is_mobile);
        
        $data['meta'] = get_header_meta(TOUR_DOMESTIC_PAGE);
        
        // get advertises
        $data['bpv_ads'] = load_bpv_ads(AD_PAGE_TOUR_DOMISTIC);
        
        if($is_mobile){
        	$data = get_library('flexsilder', $data);
        }
        
     
        // render view
        $data['bpv_register']	= $this->load->view('common/bpv_register', $data, TRUE);
		
        $data['popular_destinations'] = load_popular_tour_destinations($data['domestic_destinations'], true, $is_mobile);
        
        $data = $this->_get_recommended_tour_by_categories($data, "", $is_mobile);
        
        // load tour-departure-from view
        $options['is_outbound'] = STATUS_INACTIVE;
        $data['departure_from'] = load_tour_departure_from($data['departure_destinations'], $options);
        
        $data['tour_durations'] = load_filter_duration($options);
        
        $option_contact = array(
    		"mode"		=> RIGHT_TOUR_CONTACT,
    	);
    	
    	$data['tour_contact']  	= load_tour_contact($option_contact);
    	
    	$data = load_footer_links($data, false, false, true);
        
        $data = get_in_page_theme(TOUR_DOMESTIC_PAGE, $data, $is_mobile);
        
        _render_view($mobile_view . 'tours/destination/destination_domestic', $data, $is_mobile);
    	
    
    	
    }
    
    /*
     * Outbound Tours
     */
    public function outbound_tours() {
    	
    	set_cache_html();
    	
    	$is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_TOURS);
        
        // build search form
        $data = build_search_criteria(array(), MODULE_TOUR, $is_mobile);
        
        $data['meta'] = get_header_meta(TOUR_OUTBOUND_PAGE);
        
        // get advertises
        $data['bpv_ads'] = load_bpv_ads(AD_PAGE_TOUR_OUTBOUND);
        
        
        
        // render view
        $data['bpv_register']	= $this->load->view('common/bpv_register', $data, TRUE);
       	
        $data['popular_destinations'] = load_popular_tour_destinations($data['outbound_destinations'], false, $is_mobile);
        
        $data = $this->_get_recommended_tour_by_categories($data, STATUS_ACTIVE, $is_mobile);
        
        // load tour-departure-from view
        $options['is_outbound'] = STATUS_ACTIVE;
        $data['departure_from'] = load_tour_departure_from($data['departure_destinations'], $options);
        
        $data['tour_durations'] = load_filter_duration($options);
        
        $option_contact = array(
    		"mode"		=> RIGHT_TOUR_CONTACT,
    	);
    
    	$data['tour_contact']  	= load_tour_contact($option_contact);
    	
    	$data = load_footer_links($data, false, false, true);
    	
        $data = get_in_page_theme(TOUR_OUTBOUND_PAGE, $data, $is_mobile);
        
        if($is_mobile){
        	$data = get_library('flexsilder', $data);
        }
        _render_view($mobile_view . 'tours/destination/destination_outbound', $data, $is_mobile);
        
    	
    }
    
    /*
     * Tour Categories
     */
    public function category_tours() {
    	
    	set_cache_html();
    	
    	$is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_TOURS);
        
        // build search form
        $data = build_search_criteria(array(), MODULE_TOUR, $is_mobile);
        
        $data['meta'] = get_header_meta(TOUR_CATEGORY_PAGE);
        
        // get advertises
        $data['bpv_ads'] = load_bpv_ads(AD_PAGE_TOUR_CATEGORY);
        
     
       
        // render view
        $data['bpv_register']	= $this->load->view('common/bpv_register', $data, TRUE);
        
        $data = $this->_get_recommended_tour_by_categories($data, '', $is_mobile);
        
        // load tour-departure-from view
        $options = array();
        $data['departure_from'] = load_tour_departure_from($data['departure_destinations'], $options);
        
        $data['tour_durations'] = load_filter_duration($options);
        
        $option_contact = array(
    		"mode"		=> RIGHT_TOUR_CONTACT,
    	);
    
    	$data['tour_contact']  	= load_tour_contact($option_contact);
    	
    	$data = load_footer_links($data, false, false, true);
    	
        $data = get_in_page_theme(TOUR_DOMESTIC_PAGE, $data, $is_mobile);
        
        if($is_mobile){
        	$data = get_library('flexsilder', $data);
        }
        
        _render_view($mobile_view . 'tours/category/category', $data, $is_mobile);
        
    }
    
    /**
     *
     * Tour Categories Details
     */
    
	public function category_tours_details($category_id) {
    	
		set_cache_html();
		
		$is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_TOURS);
        
        // Set flag hide search form
    	$data['search_visible'] = false;
        
        // build search form
        $data = build_search_criteria($data, MODULE_TOUR, $is_mobile);
        
        
        
        
        // get domestic and ourbound tour destinations     
        $startdate = $data['tour_search_criteria']['startdate'];
        
        $category  = $this->Land_Tour_Model->get_category($category_id);
        
	    if($category === FALSE){
        	
        	redirect(get_url(TOUR_HOME_PAGE)); // return tour home page if category not found
        	
        	exit();
        }
        
        $data['meta'] = get_header_meta(TOUR_CATEGORY_DETAIL_PAGE, $category);
        
        // get advertises
        $data['bpv_ads'] = load_bpv_ads(AD_PAGE_TOUR_CATEGORY, AD_AREA_DEFAULT, '', $category_id);
        
        $data['category'] = $category;
        
    	$opt['startdate']   = $startdate;
    	$opt['category']      = $category_id;
    	
    	$data['more_link']	 = get_tour_search_link($opt);
        
        
        // load tour-departure-from view
        $options['category'] = $category_id;
        
        $data['departure_from'] = load_tour_departure_from($data['departure_destinations'], $options);
        
        $data['tour_durations'] = load_filter_duration($options);
        
        $data['tours'] = $this->Land_Tour_Model->get_tours_by_category($category_id, $startdate);
        $data['category_tours'] = $this->load->view($mobile_view.'tours/common/tour_list', $data, TRUE);
        
        $data['num_tour_category'] = $this->Land_Tour_Model->count_tour_category($category_id);
        
        // render view
        $data['bpv_register']	= $this->load->view('common/bpv_register', $data, TRUE);
               
        $option_contact = array(
    		"mode"		=> RIGHT_TOUR_CONTACT,
    	);
    
    	$data['tour_contact']  	= load_tour_contact($option_contact);
    	
    	$data = load_footer_links($data, false, false, true);
        
        $data = get_in_page_theme(TOUR_DOMESTIC_PAGE, $data, $is_mobile);
        
        if($is_mobile){
        	$data = get_library('flexsilder', $data);
        }
        
        _render_view($mobile_view . 'tours/category/category_details', $data, $is_mobile);
		
    }
    
    /*
     * Tour Destinations
    */
    public function destination_tours($destination_id) {
    	
    	set_cache_html();
    	 
    	$is_mobile = is_mobile();
    
    	$mobile_view = $is_mobile ? 'mobile/' : '';
    
    	$this->session->set_userdata('MENU', MNU_TOURS);
    	
    	// Set flag hide search form
    	$data['search_visible'] = false;
    
    	// build search form
    	$data = build_search_criteria($data, MODULE_TOUR, $is_mobile);
    
    	// get domestic and ourbound tour destinations
    	$startdate = $data['tour_search_criteria']['startdate'];
    
    	$destination = $this->Destination_Model->get_destination($destination_id);
    
    	if($destination === FALSE){
    		 
    		redirect(get_url(TOUR_HOME_PAGE)); // return tour home page if destination not found
    		 
    		exit();
    	}
    	
    	$data['meta'] = get_header_meta(TOUR_DESTINATION_PAGE, $destination);
    	
    	// get advertises
    	$data['bpv_ads'] = load_bpv_ads(AD_PAGE_TOUR_DESTINATION, AD_AREA_DEFAULT, $destination['id']);
    	
    	// get parent destinations for navigation
    	if($destination['type'] != DESTINATION_TYPE_CONTINENT)
    	{
    	    $destination['nav_destinations'] = $this->Destination_Model->get_nav_parent_destinations($destination);
    	}
    
    	$data['destination'] = $destination;
    
    	$opt['destination'] = $data['destination']['name'];
    	$opt['startdate']   = $startdate;
    	$opt['des_id']      = $destination_id;
    
    	$data['more_link']	 = get_tour_search_link($opt);
    
    	$data = $this->_get_popular_tours_in_destination($data ,$is_mobile);
    
    	// load tour-departure-from view
    	$options['des_id'] = $destination_id;
    	$options['destination'] = $data['destination']['name'];
    	$data['departure_from'] = load_tour_departure_from($data['departure_destinations'], $options);
    	$data['tour_durations'] = load_filter_duration($options);
    
    	// render view
    	$data['bpv_register']	= $this->load->view('common/bpv_register', $data, TRUE);
    
    	if($destination['is_tour_destination_group'] == STATUS_ACTIVE){
    		$sub_highlight_dess = $this->Destination_Model->get_children_highlight_des($destination_id);
    		$data['sub_destinations'] = $sub_highlight_dess;
    		$data['popular_sub_destination']  	= $this->load->view($mobile_view.'tours/common/popular_sub_destinations', $data, TRUE);
    	}
    	
    	$option_contact = array(
    		"mode"		=> RIGHT_TOUR_CONTACT,
    	);
    
    	$data['tour_contact']  	= load_tour_contact($option_contact);
    	
    	$data = load_footer_links($data, false, false, true);
    	
    	$data = get_in_page_theme(TOUR_DESTINATION_PAGE, $data, $is_mobile);
    	
    	if($is_mobile){
    		 
    		$data = get_library('flexsilder', $data);
    	}
    
    	_render_view($mobile_view . 'tours/destination/destination_tours', $data, $is_mobile);
    	 
    	 
    }
    
    
    /**
     * Khuyenpv
     * 
     * Load Recommended Tour By Categories
     * 
     */
    function _get_recommended_tour_by_categories($data, $is_outbound = STATUS_INACTIVE, $is_mobile = false){
    	
    	$startdate = $data['tour_search_criteria']['startdate'];
    	
    	$nr_recommended_categories = $this->config->item('nr_recommended_categories');
    	
    	$categories = $this->Land_Tour_Model->get_categories();
    	
    	foreach ($categories as $key => $cat){
    		
    		if($key < $nr_recommended_categories){
    			
    			$cat['tours'] = $this->Land_Tour_Model->get_tour_categories($cat['id'], $startdate, $is_outbound);
    			
    		}
    		
    		$categories[$key] = $cat;
    		
    	}
    	
    	
    	$data['categories'] = $categories;
    	
    	if($is_mobile){
    		
    		$data['recommended_tours_by_cat'] = $this->load->view('mobile/tours/destination/recommended_tour_by_category', $data, true);
    		
    	}else{
    	
    		$data['recommended_tours_by_cat'] = $this->load->view('tours/destination/recommended_tour_by_category', $data, true);
    	}
    	
    	
    	return $data;
    }
    
    /**
     * Khuyenpv 12.09.2014
     * Load Tours Destinations Data
     */
    function _get_popular_tours_in_destination($data, $is_mobile = false){
    	
    	$destination = $data['destination'];
    	
    	$nr_limit = $this->config->item('popular_tour_limit');
    	
    	$startdate = $data['tour_search_criteria']['startdate'];
    	
    	if($destination['is_tour_includes_all_children_destination'] == STATUS_INACTIVE){
    		$data['tours']	= $this->Land_Tour_Model->get_popular_tours_in_destination($destination['id'], $startdate, $nr_limit);
    	} else {
    		$data['tours']	= $this->Land_Tour_Model->get_popular_tours_pass_all_children_des($destination['id'], $startdate, $nr_limit);
    	}
    	
    	if($is_mobile){
    		
    		$data['destination_tours'] = $this->load->view('mobile/tours/common/tour_list', $data, TRUE);
    	}else{
    		
    		$data['destination_tours'] = $this->load->view('tours/common/tour_list', $data, TRUE);
    	}
    	
    	return $data;
    }
    
   	
    
}
