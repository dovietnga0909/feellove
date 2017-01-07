<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->language('home');
        $this->load->language('hotel');
        $this->load->language('tour');
        
        $this->load->helper(array('form','cookie','rate','hotel','flight','display','land_tour'));
        
        $this->load->model('Flight_Model');
        $this->load->model('Hotel_Model');
        $this->load->model('News_Model');
        $this->load->model('Land_Tour_Model');
        
        // $this->output->enable_profiler(TRUE);
    }

    /**
     * Home Page Controller
     */
    public function index()
    {   
        $is_mobile = is_mobile();
        
        $this->session->set_userdata('MENU', MNU_HOME);
        
        $data = array();
        
        $data['meta'] = get_header_meta(HOME_PAGE);
        
        // load advertise
        $data['bpv_ads'] = load_bpv_ads(AD_PAGE_HOME);
        
        if ($is_mobile)     // mobile view
        { 
            // marketing 
            $marketing_cfg = $this->config->item('mua-chung-doi');
            
            $start_date = strtotime($marketing_cfg['start_date']);
            
            $end_date = strtotime($marketing_cfg['end_date']);
            
            $today = strtotime(date(DATE_FORMAT_STANDARD));
            
            if ($today >= $start_date && $today <= $end_date)
            {
                $data['marketing_cfg'] = $marketing_cfg;
            }
            
            // load view
            $data = build_search_criteria($data, MODULE_HOTEL, $is_mobile);
            
            $data = get_library('flexsilder', $data);
            
            _render_view('mobile/home/home', $data, $is_mobile);
        }
        else    // desktop view
        {  
            set_cache_html();
                 
            // get data
            $data = build_search_criteria($data);
            
            $startdate = $data['hotel_search_criteria']['startdate'];
            
            // load best hotel
            $data = load_best_hotel($data, $data['hotel_search_criteria'], $startdate, 5);
            
            
            $data['bpv_why_us'] = load_bpv_why_us(HOME_PAGE);
            
            // Get recent view items
            $data = load_recent_items($data, $startdate);
            
            $data['n_book_together'] = $this->News_Model->get_news_details(10);
            
            // render view
            $data = load_footer_links($data, true, true, true);
            
            $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
            
            $data['page_css'] = get_static_resources('home.min.05062014.css');
            
            _render_view('home/home', $data);
        }
    }

    function suggest_hotel_destinations()
    {
        $term = $this->input->get('query');
        
        $destinations = $this->Hotel_Model->suggest_destinations($term);
        
        foreach ($destinations as $k => $des)
        {
            $destinations[$k]['type'] = 'd';
        }
        
        echo json_encode($destinations);
    }

    function suggest_hotels()
    {
        $term = $this->input->get('query');
        
        $destinations = $this->Hotel_Model->suggest_hotels($term);
        
        foreach ($destinations as $k => $des)
        {
            $destinations[$k]['type'] = 'h';
        }
        
        echo json_encode($destinations);
    }

    function suggest_flight_destinations()
    {
        $term = $this->input->get('query');
        
        $origin = $this->input->get('origin');
        
        $flight_destinations = array();
        
        if (! empty($origin))
        {
            $flight_routes = $this->Flight_Model->get_all_flight_routes($origin);
            
            if (! empty($flight_routes))
            {
                foreach ($flight_routes as $route)
                {
                    $flight_destinations[] = array(
                        'name' => $route['to_des'],
                        'code' => $route['to_code'],
                        'label' => $route['to_des'] . ' (' . $route['to_code'] . ')',
                        'tokens' => array(
                            $route['to_des'],
                            $route['to_code']
                        )
                    );
                }
            }
        }
        else
        {
            $flight_destinations = $this->Flight_Model->suggest_flight_destinations($term);
        }
        
        foreach ($flight_destinations as $k => $des)
        {
            $des['label'] = $des['name'] . ' (' . $des['code'] . ')';
            $des['tokens'] = array(
                $des['name'],
                $des['code']
            );
            
            $flight_destinations[$k] = $des;
        }
        
        echo json_encode($flight_destinations);
    }

    function remove_recent_item()
    {
        $item_id = $this->input->post('item_id');
        
        remove_recent_item($item_id);
    }

    function get_current_search($module = HOTEL)
    {
        $search_criteria = array();
        
        if ($module == HOTEL)
        {
            
            $search_criteria = get_hotel_search_criteria();
        }
        
        if ($module == CRUISE)
        {
            $this->load->helper('cruise');
            $search_criteria = get_cruise_search_criteria();
        }
        
        if ($module == FLIGHT)
        {
            
            $tmp = $this->session->userdata(FLIGHT_SEARCH_CRITERIA);
            if (! empty($tmp))
            {
                
                $search_criteria = $this->session->userdata(FLIGHT_SEARCH_CRITERIA);
            }
            else
            {
                
                $search_criteria = set_default_flight_search_criteria();
            }
        }
        
        echo json_encode($search_criteria);
    }

    function get_hot_line($display_on)
    {
        if (is_hotline_time())
        {
            
            $today_hotlines = load_current_main_hotline($display_on);
            $current_hotline = !empty($today_hotlines[0]) ? $today_hotlines[0] : ''; // the first hotline
            $sd_hotline = !empty($today_hotlines[1]) ? $today_hotlines[1] : '';	// the second hotline
            
            $hotel_hotlines = load_current_main_hotline(HOTEL);
            $hotel_hotline = !empty($hotel_hotlines) ? $hotel_hotlines[0] : '';
            
            $flight_hotlines = load_current_main_hotline(FLIGHT);
            $flight_hotline = !empty($flight_hotlines) ? $flight_hotlines[0] : '';
            
            
            if (! empty($current_hotline))
            {
                
                $detect = new Mobile_Detect();
                
                $is_mobile = $detect->isMobile();
                
                $data = array(
                	
                	'is_mobile' => $is_mobile,
                	'is_working_time' => is_working_time(),
                	
                	// fist hotline
                    'number' => $current_hotline['hotline_number'],
                    'name' => $current_hotline['hotline_name'],
                    'number_formated' => format_phone_number($current_hotline['hotline_number']),

                	//second hotline
                	'sd_number' => !empty($sd_hotline) ? $sd_hotline['hotline_number'] : '',
                    'sd_name' => !empty($sd_hotline) ? $sd_hotline['hotline_name'] : '',
                    'sd_number_formated' => !empty($sd_hotline) ? format_phone_number($sd_hotline['hotline_number']) : '',
                
                	'f_hotline_nr' => !empty($flight_hotline) ? $flight_hotline['hotline_number'] : '',
                	'f_hotline_nr_formated' => !empty($flight_hotline) ? format_phone_number($flight_hotline['hotline_number']) : '',
					'f_hotline_name' => !empty($flight_hotline) ? $flight_hotline['hotline_name'] : '',
                		
                	'h_hotline_nr' => !empty($hotel_hotline) ? $hotel_hotline['hotline_number'] : '',
                	'h_hotline_nr_formated' => !empty($hotel_hotline) ? format_phone_number($hotel_hotline['hotline_number']) : '',
                	'h_hotline_name' => !empty($hotel_hotline) ? $hotel_hotline['hotline_name'] : ''
                	
                );
                
                echo json_encode($data);
            }
            else
            {
                
                echo '';
            }
        }
        else
        {
            
            echo '';
        }
    }

    function get_hotline_popup()
    {
        if (is_hotline_time())
        {
            echo load_online_support();
        }
        else
        {
            echo load_contact_popup('bpv_support_popup', 'contact', lang('offline_time'));
        }
    }

    function get_contact_popup()
    {
        $html = load_contact_popup('btn_groupon');
        
        $html .= load_contact_popup('btn_contact_popup', 'contact');
        
        echo $html;
    }
    
    function get_marketing_popup() {
        
        $html = '';
        
        $is_mobile = $this->input->post('is_mobile');
        
        $config = $this->input->post('cfg');
        
        if (empty($config))
        {
            $marketing_cfg = $this->config->item('minh-di-choi-nhe');
            
            $start_date = strtotime($marketing_cfg['start_date']);
            
            $end_date = strtotime($marketing_cfg['end_date']);
            
            $today = strtotime(date(DATE_FORMAT_STANDARD));
            
            // only show popup when user's first visit and it meets marketing conditions
            
            if ($today >= $start_date && $today <= $end_date && is_new_visitor())
            {
                if (! empty($is_mobile) && $is_mobile == 1)
                {
                    $html = '<a href="' . $marketing_cfg['link'] . '">';
                    $html .= '<img class="img-responsive" src="'.get_static_resources('/media/mobile/tri-an-banner.jpg').'"></a>';
                }
                else
                {
                    $html = '<div class="popup-bg"></div>
                	 <div class="popup-wrap">
                        <div class="popup-container">
                		      <div class="popup-content">
                		          <a href="' . $marketing_cfg['link'] . '"><img src="'.get_static_resources('/media/banners/minh-di-choi-nhe.png').'"></a>
                		          <span class="popup-close"></span>
                		      </div>
                	    </div>
                	 </div>';
                }
            }
        }
        else
        {
            $marketing_cfg = $this->config->item($config);
            
            if(empty($marketing_cfg)) {
                exit();
            }
            
            $start_date = strtotime($marketing_cfg['start_date']);
            
            $end_date = strtotime($marketing_cfg['end_date']);
            
            $today = strtotime(date(DATE_FORMAT_STANDARD));
            
            if ($today >= $start_date && $today <= $end_date)
            {
                $html = '<div class="marketing-box">
                         <span class="mk-box-close"></span>
                         <a href="' . $marketing_cfg['link'] . '"><img src="'.get_static_resources('/media/ads/cang-dong-cang-vui-pop-up.14082014.png').'"></a>
                         <div>';
            }
        }
        
        echo $html;
    }
    
    /**
     * Get hotline box ajax
     *
     * @author toanlk
     * @since  Dec 10, 2014
     */
    function get_hotline_box()
    {
        $hotline_support = '&nbsp;';
        
        $display_on = $this->input->post('display_on');
        $on_sidebar = $this->input->post('on_sidebar');
        
        if (! empty($display_on) && is_numeric($display_on))
        {
            if (is_working_time() || is_hotline_time())
            {
                $this->load->model('User_Model');
                
                if ($on_sidebar)
                {
                    $data['on_sidebar'] = $on_sidebar;
                }
                
                $data['hotline_users'] = $this->User_Model->get_hotline_users($display_on, true);
                
                $data['hotline_users'] = setting_hotline_time($data['hotline_users']);
                
                $hotline_users = array();
                foreach ($data['hotline_users'] as $user)
                {
                    if ($user['show_hotline'])
                    {
                        $hotline_users[] = $user;
                    }
                }
                
                $data['hotline_users'] = $hotline_users;
                
                $hotline_support = $this->load->view('common/bpv_hotline_support', $data, TRUE);
            }
        }

        echo $hotline_support;
    }
}