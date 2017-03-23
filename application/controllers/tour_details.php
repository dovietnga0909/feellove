<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*  Tour Details
*
*  Loads the tour details and executes the request.
*
*  @author toanlk
*  @since  Sep 12, 2014
*/
class Tour_Details extends CI_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->language('tour');
        $this->load->helper(array(
            'tour',
            'display',
            'land_tour',
            'form',
            'text',
            'tour_rate',
            'booking'
        ));
        
        $this->load->model(array(
            'Land_Tour_Model',
            'Tour_Model',
            'Review_Model',
            'Booking_Model',
        	'Destination_Model',
        	'Cruise_Model',
        	'Hotel_Model'
        ));
        $this->load->config('tour_meta');
        
        // $this->output->enable_profiler(TRUE);
    }

    /**
	 * Index function
	 *
	 * @access	public
	 * @return	void
	 */
    public function index($id)
    {
        $data = array();
        
        $is_mobile = is_mobile();
        
        // only cache the t page if there-is no action on page
        $startdate = $this->input->get('startdate');
        
        if (empty($startdate))
        {
            set_cache_html();
        }
        
        // get tour details
        $tour = $this->Land_Tour_Model->get_tour_details($id, $startdate);
        
        if (empty($tour))
        {
            exit();
        }
        
        // get page theme
        $this->session->set_userdata('MENU', MNU_TOURS);
        
        $data = get_in_page_theme(TOUR_DETAIL_PAGE, $data, $is_mobile);
        
        // canonical
        if (!empty($startdate))
        {
            $canonical_link = get_url(TOUR_DETAIL_PAGE, $tour);
            $tour['canonical'] = '<link rel="canonical" href="' . $canonical_link . '"/>';
        }
            
        // set meta
        $data['meta'] = get_header_meta(TOUR_DETAIL_PAGE, $tour);
        
        $data['tour'] = $tour;
        
        // load recent search
        $data = $this->_load_search_form($data, $is_mobile);
        
        // get tour price
        $data = $this->_get_tour_price($tour, $data);
        
        
        // get tour basic information and similar tours
        $data = $this->_load_tour_info($data, $is_mobile);
       
      	
        $data = $this->_load_similar_tours($data, $is_mobile);
        
        // load check rate forms
        $data = $this->_load_check_rate_form($data, $is_mobile);
        
        // tour contact
        
        $option_contact = array(
    		"mode"		=> RIGHT_TOUR_CONTACT,
        	"tour_name"	=> $data['tour']['name'],
    	);
        
        $data['tour_contact']	= load_tour_contact($option_contact);	
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        _render_view($mobile_view . 'tours/details/layout', $data, $is_mobile);
    }

    // --------------------------------------------------------------------
    
    /**
     * Load search form and set data from session if it's available
     *
     * @access	private
     * @return	data
     */
    function _load_search_form($data, $is_mobile)
    {
        $data['search_criteria'] = get_land_tour_search_criteria();
        
        $data = get_tour_search_data($data);
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        if (! empty($data['search_criteria']['destination']))
        {
            $data['search_overview'] = $this->load->view( 'tours/common/search_overview', $data, TRUE);
        }
        
        $data['search_form'] = $this->load->view( $mobile_view . 'tours/common/search_form', $data, TRUE);
        
        return $data;
    }

    function _load_tour_info($data, $is_mobile)
    {
        $data['tab'] = 0;
        
        $tab = $this->input->get('tab', true);
        
        if (! empty($tab))
        {
            $data['tab'] = $tab;
        }
        
        $tour_id = $data['tour']['id'];
        
        $data['transportations'] = $this->config->item('transportations');
        
        $data['week_days'] = $this->config->item('week_days');
        
        $data['photos'] = $this->Tour_Model->get_tour_photos($tour_id);
        
        // get last review
        $data['last_review'] = $this->Review_Model->get_last_review(array(
            'tour_id' => $data['tour']['id']
        ));
        
        // get default cancellation
        $data['default_cancellation'] = $this->Land_Tour_Model->get_cancellation_of_tour($tour_id);
        
        $data['tour_departures'] = $this->Land_Tour_Model->get_tour_departures($tour_id);
        
        // get itinerary
        foreach ($data['tour_departures'] as $key => $value)
        {
            $data['tour']['departure_itinerary'][$value['id']] = $this->Tour_Model->get_itineraries($tour_id, $value['id']);
            
            if ($key == 0)
            {
                $data['tour']['itinerary'] = $data['tour']['departure_itinerary'][$value['id']];
            }
        }
        
        // get price table
        $data['check_rate_info'] = get_land_tour_check_rate_info();
        
        $startdate = !empty($data['check_rate_info']['startdate']) ? $data['check_rate_info']['startdate'] : null;
        
        $data['tour_rate_actions'] = $this->Land_Tour_Model->get_tour_rate_actions($data['tour'], $data['tour_departures'], $startdate);
        
        $data['accommodations'] = $this->Land_Tour_Model->get_accommodations($tour_id);
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
        
        $data['basic_info'] = $this->load->view($mobile_view . 'tours/details/basic_info', $data, TRUE);
        
        $data['photos'] = $this->load->view($mobile_view . 'tours/details/photos', $data, TRUE);
        
        $data['itinerary'] = $this->load->view($mobile_view. 'tours/details/itinerary', $data, TRUE);
        
        $data['price_table'] = $this->load->view($mobile_view. 'tours/details/price_table', $data, TRUE);
        
        $data['important_info'] = $this->load->view($mobile_view. 'tours/details/important_info', $data, TRUE);
        
        return $data;
    }

    /**
      *  _load_similar_tours
      *
      *  @author toanlk
      *  @since  Sep 18, 2014
      */
    function _load_similar_tours($data, $is_mobile)
    {
        $data['s_tours'] = $this->Land_Tour_Model->get_similar_tours($data['tour'], $data['search_criteria']['startdate'], $is_mobile);
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['similar_tours'] = $this->load->view($mobile_view. 'tours/details/similar_tours', $data, TRUE);
        
        return $data;
    }

    /**
      *  _get_tour_price
      *
      *  @author toanlk
      *  @since  Sep 18, 2014
      */
    function _get_tour_price($tour, $data)
    {
        $tour_price = $this->Land_Tour_Model->get_tour_price_from_4_list(array($tour), $data['search_criteria']['startdate']);
        
        if (! empty($tour_price))
        {
            $tour = $tour_price[0];
        }
        
        $tour_promotions = $this->Land_Tour_Model->get_tour_promotions_4_list(array($tour), $data['search_criteria']['startdate']);
        
        if (! empty($tour_promotions))
        {
            $tour = $tour_promotions[0];
        }
        
        $tour_bpv_promotions = $this->Land_Tour_Model->get_tour_bpv_promotion_4_list(array($tour));
        
        if (! empty($tour_bpv_promotions))
        {
            $tour = $tour_bpv_promotions[0];
        }
        
        $data['tour'] = $tour;
        
        return $data;
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Load check rate form and set data from session if it's available
     *
     * @access	private
     * @return	data
     */
    function _load_check_rate_form($data, $is_mobile = false)
    {
        $data['action'] = $this->input->get('action');
        
        if($data['action'] == ACTION_CHECK_RATE) {
            $data['tab'] = 4;
        }
    
        $data['check_rate_info'] = get_land_tour_check_rate_info();
    
        $accommodations = $this->Tour_Model->get_accommodations($data['tour']['id']);
    
        $data['accommodation_limit'] = $this->config->item('accommodation_limit');
    
        $data['accommodations'] = $accommodations;
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
    
        $data['check_rate_form'] = $this->load->view($mobile_view . 'tours/book/check_rate_form', $data, TRUE);
    
        $data['accommodations'] = $this->load->view($mobile_view . 'tours/book/accommodations', $data, TRUE);
    
        return $data;
    }

    // --------------------------------------------------------------------
    
    /**
     * Get tour rates: accommodation rates, inclusion and exclusion services, cancellation policy
     *
     * @access	private
     * @return	data
     */
    function check_rates()
    {
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        // get tour
        $tour_id = $this->input->get('tour_id', true);
        
        $departure_id = $this->input->get('departure_id', true);
        
        $data['tour'] = $this->Land_Tour_Model->get_tour_details_check_rate($tour_id, $departure_id);
        
        if(empty($data['tour']))
        {
            exit();
        }
        
        // get post data
        $startdate = $this->input->get('startdate', true);
        $enddate = $this->input->get('enddate', true);
        $adults = $this->input->get('adults', true);
        $children = $this->input->get('children', true);
        $infants = $this->input->get('infants', true);  
        
        $search_criteria = update_tour_search_criteria_by_checkrate($startdate, $enddate, $adults, $children, $infants, $departure_id);
        
        $check_rate_info['startdate'] = $startdate;
        $check_rate_info['enddate'] = $enddate;
        $check_rate_info['adults'] = $adults;
        $check_rate_info['children'] = $children;
        $check_rate_info['infants'] = $infants;
        $check_rate_info['tour_id'] = $tour_id;
        $check_rate_info['departure_id'] = $departure_id;   
        
        $data['search_criteria'] = $search_criteria;
        
        $data['check_rate_info'] = $check_rate_info;
        
        $data['accommodation_limit'] = $this->config->item('accommodation_limit');
        
        $data['accommodations'] = _load_tour_accommodation_rates($tour_id, $check_rate_info);
        
        // get default cancellation
        $data['default_cancellation'] = $this->Land_Tour_Model->get_cancellation_of_tour($tour_id);
        
        $data['price_include_exclude'] = $this->load->view( $mobile_view.'tours/book/price_include_exclude', $data, TRUE );
        
        $this->load->view( $mobile_view.'tours/book/accom_rates', $data);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Display tour booking page
     *
     * @access	private
     * @return	data
     */
    function booking($tour_id)
    {
        // get tour details
        $tour = $this->Land_Tour_Model->get_tour_details($tour_id);
        
        if (empty($tour))
        {
            exit();
        }
        
        $data['tour'] = $tour;
        
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $this->session->set_userdata('MENU', MNU_TOURS);
        
        $startdate = $this->input->get('startdate', true);
        $enddate = $this->input->get('enddate', true);
        $adults = $this->input->get('adults', true);
        $children = $this->input->get('children', true);
        $infants = $this->input->get('infants', true);
        $departure_id = $this->input->get('departure_id', true);
        
        $check_rate_info['startdate'] = $startdate;
        $check_rate_info['enddate'] = $enddate;
        $check_rate_info['adults'] = $adults;
        $check_rate_info['children'] = $children;
        $check_rate_info['infants'] = $infants;
        $check_rate_info['departure_id'] = $departure_id;
        $check_rate_info['action'] = ACTION_CHECK_RATE;
        
        $data['check_rate_info'] = $check_rate_info;
        
        $action = $this->input->post('action');
        
        // go from book-marked link
        if (empty($action))
        {
            
            $selected_accommodations = $this->session->userdata(TOUR_ACCOMMODATION_RATE_SELECTED);
            
            // no session selected room available
            if (empty($selected_accommodations))
            { 
                
                $this->_go_back_to_check_rate($tour, $check_rate_info);
            }
            else
            {
                
                $currrent_time = microtime(true);
                
                // only save check-rate-info in 12 hours
                if ($currrent_time - $selected_accommodations['timestamp'] > 60 * 60 * 12)
                { 
                    
                    $this->session->unset_userdata(TOUR_ACCOMMODATION_RATE_SELECTED);
                    
                    $this->_go_back_to_check_rate($tour, $check_rate_info);
                }
                else
                {
                    
                    $nr_rooms = $selected_accommodations['nr_rooms'];
                }
            }
        }
        else // go from post form action
        { 
            
            $nr_rooms = $this->input->post(NULL, TRUE);
            
            $selected_accommodations['timestamp'] = microtime(true);
            $selected_accommodations['nr_rooms'] = $nr_rooms;
            
            // we know that user go from check-rate page
            if ($action == ACTION_BOOK_NOW)
            { 
                $this->session->set_userdata(TOUR_ACCOMMODATION_RATE_SELECTED, $selected_accommodations);
            }
        }
        
        // load accommodation rates
        $accommodation_rates = _load_tour_accommodation_rates($tour_id, $check_rate_info);
        
        $selected_accommodation_rates = $this->_get_selected_accommodation_rates($accommodation_rates, $nr_rooms);
        
        $data['selected_cabin'] = $selected_accommodation_rates;

        if ($action == ACTION_MAKE_BOOKING && contact_validation())
        {
            $this->_make_booking($tour, $startdate, $enddate, $selected_accommodation_rates, $check_rate_info);
        
            $this->session->unset_userdata(TOUR_ACCOMMODATION_RATE_SELECTED);
        
            redirect(site_url('xac-nhan.html?type=tour'));

        }
        else
        {
            $data = get_in_page_theme(CRUISE_HL_BOOKING_PAGE, $data, $is_mobile);
            
            $data['payment_detail'] = $this->_count_total_payments($selected_accommodation_rates, null);
            
            $data['step_booking'] = $this->load->view($mobile_view . 'tours/common/step_booking', $data, TRUE);
            
            $data['selected_accommodation'] = $this->load->view($mobile_view . 'tours/book/selected_accommodation', $data, TRUE);
            
            $data['payment_detail'] = $this->load->view($mobile_view . 'tours/book/payment_details', $data, TRUE);
            
            $data['customer_contact'] = load_contact_form(false, '', 'data-area', $is_mobile);
            
            $data['payment_method'] = load_payment_method(TOUR, $is_mobile);
            
            $data['pro_code'] = $this->load->view($mobile_view . 'tours/book/pro_code', $data, TRUE);
            
            _render_view($mobile_view . 'tours/book/tour_booking', $data, $is_mobile);
        }
         
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Go back to check rate page
     *
     * @access	private
     * @return	data
     */
    function _go_back_to_check_rate($tour, $check_rate_info)
    {
        $check_rate_info['action'] = ACTION_CHECK_RATE;
        
        redirect(get_url(TOUR_DETAIL_PAGE, $tour, $check_rate_info));
        
        exit();
    }
    
    function _get_selected_accommodation_rates($accommodation_rates, $nr_rooms)
    {
        $selected_rate = null;
        
        foreach ($accommodation_rates as $key => $accommodation_rate)
        {
            
            $id = get_tour_rate_id($accommodation_rate);
            
            if (isset($nr_rooms['selected_cabin']) && $nr_rooms['selected_cabin'] == $id)
            {
                
                $selected_rate['cabin_rate_info'] = $accommodation_rate;
                
                $selected_rate['sell_rate'] = $accommodation_rate['sell_rate'];
                
                $selected_rate['basic_rate'] = $accommodation_rate['basic_rate'];
            }
        }
        
        return $selected_rate;
    }
    
    /**
      *  Count total payment
      *
      *  @author toanlk
      *  @since  Sep 15, 2014
      */
    function _count_total_payments($selected_accommodation_rates, $surcharges = null)
    {
        $total_payment_origin = $selected_accommodation_rates['basic_rate'];
        
        $total_payment = $selected_accommodation_rates['sell_rate'];
        
        if(!empty($surcharges))
        {
            foreach ($surcharges as $value)
            {
                $total_payment_origin += $value['total_charge'];
            
                $total_payment += $value['total_charge'];
            }
        }
    
        $payment_detail['surcharges'] = $surcharges;
    
        $payment_detail['total_payment_origin'] = $total_payment_origin;
        
        $payment_detail['total_discount'] = $total_payment_origin - $total_payment;
        
        $payment_detail['total_payment'] = $total_payment;
    
        $payment_detail['accommodation'] = $selected_accommodation_rates['cabin_rate_info'];
    
        return $payment_detail;
    }
    
    /**
      *  Create new booking and send emails
      *
      *  @author toanlk
      *  @since  Sep 15, 2014
      */
    function _make_booking($tour, $startdate, $enddate, $selected_accommodation_rates, $check_rate_info, $surcharges = null)
    {
        $customer = get_contact_post_data();
        
        $customer_id = $this->Booking_Model->create_or_update_customer($customer);
        
        $special_request = $customer['special_request'];
        
        // payment method
        $payment_info['method'] = $this->input->post('payment_method');
        $payment_info['bank'] = $this->input->post('payment_bank');
        
        // promotion code
        $promotion_code = $this->input->post('promotion_code');
        $nr_passengers = $check_rate_info['adults'];
        $code_discount_info = get_pro_code_discount_info($promotion_code, TOUR, '', $tour['cruise_id'], '', $nr_passengers, $customer['phone']);
        
        $customer_booking = get_cruise_tour_customer_booking($tour['id'], $startdate, $enddate, $customer_id, $special_request, $payment_info, $code_discount_info);
        
        $service_reservations = get_cruise_tour_service_reservations($tour, $startdate, $enddate, $selected_accommodation_rates, $check_rate_info, $surcharges, $code_discount_info);
        
        $customer_booking_id = $this->Booking_Model->save_customer_booking($customer_booking, $service_reservations);
        
        // set voucher used
        if (! empty($code_discount_info) && $code_discount_info['pro_type'] == 2)
        {
            $this->Booking_Model->update_voucher_code_used($promotion_code, $customer_id);
        }
        elseif (! empty($code_discount_info))
        {
            // promotion
            $this->Booking_Model->update_pro_code_used($promotion_code, $code_discount_info, $customer_id);
        }
        
        $this->_send_mail($check_rate_info, $tour, $selected_accommodation_rates, $customer, $customer_booking_id, $surcharges, $code_discount_info);
    }
    
    function _send_mail($check_rate_info, $tour, $selected_cabin_rates, $customer, $customer_booking_id, $surcharges, $code_discount_info)
    {
        $CI = & get_instance();
    
        $data['check_rate_info'] = $check_rate_info;
    
        $data['tour'] = $tour;
    
        $data['selected_cabin_rates'] = $selected_cabin_rates;
    
        $data['surcharges'] = $surcharges;
    
        $data['week_days'] = $CI->config->item('week_days');
    
        $data['total_payment'] = $selected_cabin_rates['cabin_rate_info']['sell_rate'];
    
        $data['customer'] = $customer;
    
        $data['code_discount_info'] = $code_discount_info;
    
        $data['customer_booking_id'] = $customer_booking_id;
    
        $content = $this->load->view('tours/common/booking_mail', $data, TRUE);
    
        // echo $content;exit();
    
        $CI->load->library('email');
    
        $config['protocol'] = 'mail';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';
    
        // send to customer
        $CI->email->initialize($config);
    
        $CI->email->from('booking@' . strtolower(SITE_NAME), BRANCH_NAME);
    
        $CI->email->to($customer['email']);
    
        $subject = lang('tb_email_reply') . ': ' . $tour['name'] . ' - ' . BRANCH_NAME;
        $CI->email->subject($subject);
    
        $CI->email->message($content);
    
        if (! $CI->email->send())
        {
            log_message('error', 'tour_boooking: Can not send email to ' . $customer['email']);
        }
    
        $config = array();
        $config['protocol'] = 'mail';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html';
    
        // send to bestprice
        $CI->email->initialize($config);
    
        $CI->email->from($customer['email'], $customer['full_name']);
    
        $CI->email->to('bestpricebooking@gmail.com');
    
        $subject = lang('tb_email_reply') . ': ' . $tour['name'] . ' - ' . $customer['full_name'];
        $CI->email->subject($subject);
    
        $CI->email->message($content);
    
        if (! $CI->email->send())
        {
            log_message('error', 'tour_boooking: Can not send email to bestpricevn@gmail.com');
        }
    
        return true;
    }
    
    /**
      *  download itinerary
      *
      *  @author toanlk
      *  @since  Sep 17, 2014
      */
    function download_itinerary($id)
    {
        // only cache the t page if there-is no action on page
        $startdate = $this->input->get('startdate');
        
        $tour = $this->Land_Tour_Model->get_tour_details($id, $startdate);
        
        if (empty($tour))
        {
            exit();
        }
        
        $this->load->helper('file');
        $this->load->helper('download');
        
        // validation departing from
        $departure_id = $this->input->get('dep_id');
        
        $tour_departures = $this->Land_Tour_Model->get_tour_departures($tour['id']);
        
        $depart = null;
        foreach ($tour_departures as $value)
        {
            if ($value['id'] == $departure_id)
            {
                $depart = $value;
            }
        }
        
        $status = false;
        
        if (! empty($depart))
        {
            $filename = $tour['url_title'] . '-khoi-hanh-' . $depart['url_title'] . '.pdf';
            
            $pdf = @file_get_contents(str_replace('system/', $this->config->item('itinerary_path'), BASEPATH) . $filename);
            
            force_download($filename, $pdf);
            
            $status = true;
        }
        
        if(!$status)
        {
            //$this->session->set_flashdata('download_failed', $status);
            log_message('error', '[DEBUG] Failed download tour itinerary: '.$tour['name']);
        }

        redirect(get_url(TOUR_DETAIL_PAGE, $tour));
    }
}
