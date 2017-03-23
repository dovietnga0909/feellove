<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cruise_Tour_Details extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();
        
        $this->load->language(array(
            'tour',
            'cruise'
        ));
        $this->load->helper(array(
            'tour',
            'display',
            'cruise',
            'form'
        ));
        
        $this->load->model(array(
            'Cruise_Model',
            'Tour_Model',
            'Review_Model'
        ));
        $this->load->config('cruise_meta');
        
        // $this->output->enable_profiler(TRUE);
    }
	
	public function index($id)
    {
        $is_mobile = is_mobile();
        
        // only cache the hotel page if there-is no action on page
        $startdate = $this->input->get('startdate');
        
        if (empty($startdate))
        {
            set_cache_html();
        }
        
        // get tour details
        $tour = $this->Tour_Model->get_tour_details($id);
        
        if (empty($tour))
        {
            exit();
        }
        
        // get page theme
        $this->session->set_userdata('MENU', MNU_CRUISES);
        
        $data = get_in_page_theme(TOUR_HL_DETAIL_PAGE, array(), $is_mobile);
        
        // set canonical to cruise
        $cruise = array(
            'id'        => $tour['cruise_id'],
            'name'      => $tour['cruise_name'],
            'url_title' => $tour['cruise_url_title'],
        );
        $canonical_link = get_url(CRUISE_HL_DETAIL_PAGE, $cruise);
        $tour['canonical'] = '<link rel="canonical" href="' . $canonical_link . '" />';
        
        // set meta
        $data['meta'] = get_header_meta(CRUISE_HL_DETAIL_PAGE, $tour);
        
        // get tour price
        $check_rate_info = get_tour_check_rate_info();
        
        $data = $this->_get_tour_price($tour, $check_rate_info, $data);
        
        // get tour basic information, recent search and similar tours
        $data = $this->_load_tour_recent_search($data, $is_mobile);
        
        $data = $this->_load_tour_basic_info($data, $is_mobile);
        
        $data = $this->_load_similar_tours($data, $is_mobile);
        
        // get rate forms
        $data = $this->_load_check_rate_form($data, $is_mobile);
        
        $data = $this->_load_accommodations($data, $is_mobile);
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['bpv_register'] = $this->load->view('common/bpv_register', array(), TRUE);
        
        _render_view( $mobile_view.'cruises/cruise_tours/tour_details', $data, $is_mobile);
    }

    function _load_tour_recent_search($data, $is_mobile)
    {
        $tour = $data['tour'];
        
        $data['duration_search'] = $this->config->item('duration_search');
        
        $data['search_criteria'] = get_cruise_search_criteria();
        
        if (isset($tour['cruise_name']))
        {
            $data['search_criteria']['selected_cruise'] = array(
                'id' => $tour['cruise_id'],
                'name' => $tour['cruise_name']
            );
        }
        
        $data['popular_cruises'] = $this->Cruise_Model->get_all_halong_bay_cruises();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['cruise_search_overview'] = $this->load->view( $mobile_view.'cruises/common/search_overview', $data, TRUE);
        
        $data['cruise_search_form'] = $this->load->view( $mobile_view.'cruises/common/search_form', $data, TRUE);
        
        return $data;
    }

    function _load_check_rate_form($data, $is_mobile = false)
    {
        $data['action'] = $this->input->get('action');
        
        $data['check_rate_info'] = get_tour_check_rate_info();
        
        if ($is_mobile)
        {
            $data['check_rate_form'] = $this->load->view('mobile/cruises/cruise_detail/check_rate_form', $data, TRUE);
        }
        else
        {
            $data['check_rate_form'] = $this->load->view('cruises/cruise_detail/check_rate_form', $data, TRUE);
        }
        
        return $data;
    }

    function _load_tour_basic_info($data, $is_mobile)
    {
        $data['cruise'] = $this->Cruise_Model->get_cruise_detail($data['tour']['cruise_id']);
        
        $data['default_cancellation'] = $this->Cruise_Model->get_cancellation_of_cruise($data['cruise']['id']);
        
        $data['facility_groups'] = $facility_groups = $this->config->item('facility_groups');
        
        $cruise_facilites = $this->Cruise_Model->get_cruise_facilities($data['cruise']['facilities']);
        
        $cruise_facilites = $this->_restructure_facilities($cruise_facilites, $facility_groups);
        
        $data['cruise_facilities'] = $cruise_facilites;
        
        $tab = $this->input->get('tab', true);
        if (! empty($tab))
        {
            $data['tab'] = $tab;
        }
        
        $data['tour_photos'] = $this->Tour_Model->get_tour_photos($data['tour']['id']);
        
        // get last review
        $data['last_review'] = $this->Review_Model->get_last_review(array(
            'tour_id' => $data['tour']['id']
        ));
        
        if ($is_mobile)
        {
            $data['tour_basic_info'] = $this->load->view('mobile/cruises/cruise_tours/tour_basic_info', $data, TRUE);
            
            $data['tour_photos'] = $this->load->view('mobile/cruises/cruise_tours/tour_photos', $data, TRUE);
            $data['tour_detail_info'] = $this->load->view('mobile/cruises/cruise_detail/cruise_detail_info', $data, TRUE);
            
            $data['tour_itinerary'] = $this->load->view('mobile/cruises/cruise_tours/tour_itinerary', $data, TRUE);
        }
        else
        {
            $data['tour_basic_info'] = $this->load->view('cruises/cruise_tours/tour_basic_info', $data, TRUE);
            
            $data['tour_photos'] = $this->load->view('cruises/cruise_tours/tour_photos', $data, TRUE);
            $data['tour_detail_info'] = $this->load->view('cruises/cruise_detail/cruise_detail_info', $data, TRUE);
            
            $data['tour_itinerary'] = $this->load->view('cruises/cruise_tours/tour_itinerary', $data, TRUE);
        }
        
        return $data;
    }

    function _load_accommodations($data, $is_mobile)
    {
        $accommodations = $this->Tour_Model->get_accommodations($data['tour']['id']);
        
        $data['cabin_limit'] = $this->config->item('cabin_limit');
        
        $cruise_cabins = $this->Cruise_Model->get_cruise_cabins($data['tour']['cruise_id'], true);
        
        foreach ($accommodations as $k => $acc)
        {
            if (! empty($acc['cruise_cabin_id']))
            {
                foreach ($cruise_cabins as $cabin)
                {
                    if ($cabin['id'] == $acc['cruise_cabin_id'])
                    {
                        $acc['cabin'] = $cabin;
                        
                        $accommodations[$k] = $acc;
                    }
                }
            }
        }
        
        $data['accommodations'] = $accommodations;
        
        if ($is_mobile)
        {
            $data['tour_accommodations'] = $this->load->view('mobile/cruises/cruise_tours/accommodations', $data, TRUE);
        }
        else
        {
            $data['tour_accommodations'] = $this->load->view('cruises/cruise_tours/accommodations', $data, TRUE);
        }
        
        return $data;
    }

    function _load_similar_tours($data, $is_mobile)
    {
        $data['s_tours'] = $this->Cruise_Model->get_similar_tours($data['tour'], $data['search_criteria']['startdate'], $is_mobile);
        
        if ($is_mobile)
        {
            $data['similar_tours'] = $this->load->view('mobile/cruises/cruise_tours/similar_tours', $data, TRUE);
        }
        else
        {
            $data['similar_tours'] = $this->load->view('cruises/cruise_tours/similar_tours', $data, TRUE);
        }
        
        return $data;
    }

    function _restructure_facilities($cruise_facilites, $facility_groups)
    {
        $ret = $facility_groups;
        
        foreach ($facility_groups as $key => $value)
        {
            $ret[$key] = array();
        }
        
        foreach ($cruise_facilites as $facility)
        {
            $ret[$facility['group_id']][] = $facility;
        }
        
        $fas = array();
        foreach ($ret as $key => $value)
        {
            if (count($value) > 0)
                $fas[$key] = $value;
        }
        
        return $fas;
    }
    
    function _get_tour_price($tour, $check_rate_info, $data) 
    {
        $tour_price = $this->Tour_Model->get_tour_price_from_4_list(array($tour), $check_rate_info['startdate']);
        
        if (! empty($tour_price))
        {
            $tour = $tour_price[0];
        }
        
        $tour_promotions = $this->Tour_Model->get_tour_promotions_4_list(array($tour), $check_rate_info['startdate'], $check_rate_info['enddate']);
        
        if (! empty($tour_promotions))
        {
            $tour = $tour_promotions[0];
        }
        
        $tour_bpv_promotions = $this->Tour_Model->get_tour_bpv_promotion_4_list(array($tour));
        
        if (! empty($tour_bpv_promotions))
        {
            $tour = $tour_bpv_promotions[0];
        }
        
        $data['tour'] = $tour;
        
        return $data;
    }
}
