<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Cruise_Details extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->language('cruise');
        $this->load->helper(array(
            'cruise',
            'form',
            'cookie',
            'cruise_rate',
            'booking',
            'display',
            'tour'
        ));
        $this->load->model(array(
            'Cruise_Model',
            'Destination_Model',
            'Booking_Model',
            'Review_Model'
        ));
        $this->load->config('cruise_meta');
        
        // $this->output->enable_profiler(TRUE);
    }

    public function index($id)
    {
        $is_mobile = is_mobile();
        
        $startdate = $this->input->get('startdate');
        
        // only cache the hotel page if there-is no action on page
        if (empty($startdate))
        {
            set_cache_html();
        }
        
        $data = get_in_page_theme(CRUISE_HL_DETAIL_PAGE, array(), $is_mobile);
        
        $cruise = $this->Cruise_Model->get_cruise_detail($id);
        
        if (empty($cruise))
        {
            exit();
        }
        
        // Get cruise details
        
        $data['cruise'] = $cruise;
        
        $data = $this->_load_cruise_recent_search($data, $is_mobile);
        
        $data = $this->_load_cruise_tours($data, $is_mobile);
        
        $data = $this->_load_check_rate_form($data, $is_mobile);
        
        $data = $this->_load_cruise_basic_info($data, $is_mobile);
        
        $data = $this->_load_cabins($data, $is_mobile);
        
        $data = $this->_load_similar_cruises($data, $is_mobile);
        
        // Get cruise Meta & Canonical
        
        $this->session->set_userdata('MENU', MNU_CRUISES);
        
        $check_rate_info = $data['check_rate_info'];
        if (! $check_rate_info['is_default'])
        {
            $canonical_link = get_url(CRUISE_HL_DETAIL_PAGE, $cruise);
            $cruise['canonical'] = '<link rel="canonical" href="' . $canonical_link . '" />';
        }
        
        $data['meta'] = get_header_meta(CRUISE_HL_DETAIL_PAGE, $cruise);
        
        if ($is_mobile)
        {
            _render_view('mobile/cruises/cruise_detail/cruise_detail', $data, $is_mobile);
        }
        else
        {
            
            // store in session
            save_recent_data($cruise, MODULE_CRUISE);
            
            // Get recent view items
            $data = load_recent_items($data, $data['search_criteria']['startdate'], MODULE_CRUISE, $cruise['id'], true);
            
            $data['bpv_register'] = $this->load->view('common/bpv_register', array(), TRUE);
            
            _render_view('cruises/cruise_detail/cruise_detail', $data);
        }
    }

    function _load_cruise_recent_search($data, $is_mobile)
    {
        $cruise = $data['cruise'];
        $des['id'] = $cruise['destination_id'];
        $des['name'] = $cruise['destination_name'];
        $des['type'] = $cruise['destination_type'];
        
        $data['duration_search'] = $this->config->item('duration_search');
        
        $data['search_criteria'] = get_cruise_search_criteria($des);
        
        $data['popular_cruises'] = $this->Cruise_Model->get_all_halong_bay_cruises();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['cruise_search_overview'] = $this->load->view( $mobile_view.'cruises/common/search_overview', $data, TRUE);
        
        $data['cruise_search_form'] = $this->load->view( $mobile_view.'cruises/common/search_form', $data, TRUE);
        
        $data['check_rate_info'] = get_tour_check_rate_info();
        
        return $data;
    }

    function _load_check_rate_form($data, $is_mobile = false)
    {
        $data['action'] = $this->input->get('action');
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['check_rate_form'] = $this->load->view( $mobile_view.'cruises/cruise_detail/check_rate_form', $data, TRUE);
        
        return $data;
    }

    function _load_cruise_basic_info($data, $is_mobile = false)
    {
        $id = $data['cruise']['id'];
        
        $cruise = $data['cruise'];
        
        $data['is_cruise_detail'] = 1;
        
        $data['search_criteria'] = get_cruise_search_criteria();
        
        $tab = $this->input->get('tab', true);
        if (! empty($tab))
        {
            $data['tab'] = $tab;
        }
        
        // get price from
        $data['cruise_price_from'] = $this->Cruise_Model->get_cruise_price_from($data['tours'], $data['check_rate_info']['startdate']);
        
        // promotions from the cruise
        $data['cruise_promotions'] = $this->Cruise_Model->get_all_available_cruise_promotions($id);
        
        $data['bpv_promotions'] = $this->Cruise_Model->get_cruise_bpv_promotions($id);
        
        $cruise_facilites = $this->Cruise_Model->get_cruise_facilities($cruise['facilities']);
        
        $data['highlight_facilities'] = $this->_get_highlight_facilities($cruise_facilites);
        
        $data['facility_groups'] = $facility_groups = $this->config->item('facility_groups');
        
        $cruise_facilites = $this->_restructure_facilities($cruise_facilites, $facility_groups);
        
        $data['cruise_facilities'] = $cruise_facilites;
        
        $data['default_cancellation'] = $this->Cruise_Model->get_cancellation_of_cruise($id);
        
        $data['cruise_photos'] = $this->Cruise_Model->get_cruise_photos($id);
        
        // get last review
        $data['last_review'] = $this->Review_Model->get_last_review(array(
            'cruise_id' => $id
        ));
        
        if ($is_mobile)
        {
            $data['cruise_basic_info'] = $this->load->view('mobile/cruises/cruise_detail/cruise_basic_info', $data, TRUE);
            
            $data['cruise_photos'] = $this->load->view('mobile/cruises/cruise_detail/cruise_photos', $data, TRUE);
            $data['cruise_detail_info'] = $this->load->view('mobile/cruises/cruise_detail/cruise_detail_info', $data, TRUE);
        }
        else
        {
            $data['cruise_basic_info'] = $this->load->view('cruises/cruise_detail/cruise_basic_info', $data, TRUE);
            
            $data['cruise_photos'] = $this->load->view('cruises/cruise_detail/cruise_photos', $data, TRUE);
            $data['cruise_detail_info'] = $this->load->view('cruises/cruise_detail/cruise_detail_info', $data, TRUE);
        }
        
        return $data;
    }

    function _get_highlight_facilities($cruise_facilites)
    {
        $ret = array();
        foreach ($cruise_facilites as $value)
        {
            if ($value['is_important'])
            {
                $ret[] = $value;
            }
        }
        return $ret;
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

    function _load_similar_cruises($data, $is_mobile = false)
    {
        $cruise = $data['cruise'];
        
        if ($is_mobile)
        {
            $data['s_cruises'] = $this->Cruise_Model->get_similar_cruises($cruise, $data['search_criteria']['startdate'], $is_mobile);
            
            $data['similar_cruises'] = $this->load->view('mobile/cruises/cruise_detail/similar_cruises', $data, TRUE);
        }
        else
        {
            $data['s_cruises'] = $this->Cruise_Model->get_similar_cruises($cruise, $data['search_criteria']['startdate']);
            
            $data['similar_cruises'] = $this->load->view('cruises/cruise_detail/similar_cruises', $data, TRUE);
            
            $data['is_small_layout'] = true;
            $data['similar_cruises_side'] = $this->load->view('cruises/cruise_detail/similar_cruises', $data, TRUE);
        }
        
        return $data;
    }

    function _load_cabins($data, $is_mobile = false)
    {
        $data['cabin_limit'] = $this->config->item('cabin_limit');
        
        $data['cruise_cabins'] = $this->Cruise_Model->get_cruise_cabins($data['cruise']['id'], true);
        
        if ($is_mobile)
        {
            $data['cabins'] = $this->load->view('mobile/cruises/cruise_detail/cabins', $data, TRUE);
        }
        else
        {
            $data['cabins'] = $this->load->view('cruises/cruise_detail/cabins', $data, TRUE);
        }
        
        return $data;
    }

    function _load_cruise_tours($data, $is_mobile)
    {
        $data['tours'] = $this->Cruise_Model->get_cruise_tours($data['cruise']['id']);
        
        if ($is_mobile)
        {
            $data['cruise_tours'] = $this->load->view('mobile/cruises/cruise_detail/cruise_tours', $data, TRUE);
        }
        else
        {
            $data['cruise_tours'] = $this->load->view('cruises/cruise_detail/cruise_tours', $data, TRUE);
        }
        
        return $data;
    }

    function _load_cruise_sucharges($tour, $startdate, $enddate, $check_rate_info, $selected_cabin_rates)
    {
        $startdate = format_bpv_date($startdate);
        $enddate = format_bpv_date($enddate);
        
        $enddate = date(DB_DATE_FORMAT, strtotime($enddate . " -1 day"));
        
        $surcharges = $this->Cruise_Model->get_cruise_tour_surcharges($tour, $startdate, $enddate);
        
        $ret = array();
        
        foreach ($surcharges as $value)
        {
            
            // check surcharge apply on weekday
            if (! is_apply_surcharge($value, $startdate, $enddate))
            {
                continue;
            }
            
            if ($value['charge_type'] == SUR_PER_ADULT_PER_BOOKING)
            {
                $value['total_charge'] = $value['adult_amount'] * $check_rate_info['adults'];
                
                if (! empty($value['children_amount']) & ! empty($check_rate_info['children']))
                {
                    $value['total_charge'] += ($value['children_amount'] * $check_rate_info['children']);
                }
                
                // store for saving Service Reservation to DB
                $sr_desc = 'Charge: ' . number_format($value['adult_amount']) . '/' . strtolower(lang('adult_label'));
                
                if (! empty($value['children_amount']) && ! empty($check_rate_info['children']))
                {
                    $sr_desc .= ', ' . number_format($value['children_amount']) . '/' . strtolower(lang('children_label'));
                }
            }
            
            if ($value['charge_type'] == SUR_PER_ROOM_PRICE)
            {
                $value['total_charge'] = ($value['adult_amount'] / 100) * $selected_cabin_rates['sell_rate'];
                
                // store for saving Service Reservation to DB
                $sr_desc = 'Charge: ' . $value['adult_amount'] . '%/' . get_cruise_surcharge_unit($value);
            }
            
            $sr_desc .= "\n" . 'For: ' . $check_rate_info['adults'] . ' ' . strtolower(lang('adult_label'));
            if (! empty($check_rate_info['children']))
            {
                $sr_desc .= ', ' . $check_rate_info['children'] . ' ' . strtolower(lang('children_label'));
            }
            $value['sr_desc'] = $sr_desc;
            
            $ret[] = $value;
        }
        
        return $ret;
    }

    public function check_rates()
    {
        $startdate = $this->input->get('startdate', true);
        $enddate = $this->input->get('enddate', true);
        $adults = $this->input->get('adults', true);
        $children = $this->input->get('children', true);
        $infants = $this->input->get('infants', true);
        $tour_id = $this->input->get('tour_id', true);
        
        $search_criteria = update_cruise_search_criteria_by_checkrate($startdate, $enddate, $adults, $children, $infants);
        
        $check_rate_info['startdate'] = $startdate;
        $check_rate_info['tour_id'] = $tour_id;
        $check_rate_info['enddate'] = $enddate;
        $check_rate_info['adults'] = $adults;
        $check_rate_info['children'] = $children;
        $check_rate_info['infants'] = $infants;
        
        $data['tour'] = $this->Tour_Model->get_search_tour($tour_id);
        
        $data['search_criteria'] = $search_criteria;
        
        $data['check_rate_info'] = $check_rate_info;
        
        $data['cabin_limit'] = $this->config->item('cabin_limit');
        
        $data['cruise'] = $this->Cruise_Model->get_cruise_detail($data['tour']['cruise_id']);
        
        $data['bpv_promotions'] = $this->Cruise_Model->get_cruise_bpv_promotions($data['tour']['cruise_id']);
        
        $data['accommodations'] = _load_cruise_cabin_rates($tour_id, $data['tour']['cruise_id'], $check_rate_info, $startdate, $enddate);
        
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['price_include_exclude'] = $this->load->view( $mobile_view.'cruises/common/price_include_exclude', $data, TRUE);
        
        $this->load->view( $mobile_view.'cruises/cruise_detail/cabin_rates', $data);
    }

    public function booking($tour_id)
    {
        $is_mobile = is_mobile();
        
        $this->session->set_userdata('MENU', MNU_CRUISES);
        
        $startdate = $this->input->get('startdate', true);
        $enddate = $this->input->get('enddate', true);
        $adults = $this->input->get('adults', true);
        $children = $this->input->get('children', true);
        $infants = $this->input->get('infants', true);
        
        $check_rate_info['startdate'] = $startdate;
        $check_rate_info['enddate'] = $enddate;
        $check_rate_info['adults'] = $adults;
        $check_rate_info['children'] = $children;
        $check_rate_info['infants'] = $infants;
        $check_rate_info['action'] = ACTION_CHECK_RATE;
        
        $data['check_rate_info'] = $check_rate_info;
        
        $action = $this->input->post('action');
        
        $tour = $this->Tour_Model->get_tour_details($tour_id);
        
        $data['cruise'] = $this->Cruise_Model->get_cruise_detail($tour['cruise_id']);
        
        $data['meta'] = get_header_meta(CRUISE_HL_BOOKING_PAGE, $data['cruise']);
        
        $tour['cruise'] = $data['cruise'];
        
        if (empty($action))
        { // go from book-marked link
            
            $selected_accommodations = $this->session->userdata(TOUR_ACCOMMODATION_RATE_SELECTED);
            
            if (empty($selected_accommodations))
            { // no session selected room available
                
                $this->_go_back_to_check_rate($tour, $check_rate_info);
            }
            else
            {
                
                $currrent_time = microtime(true);
                
                if ($currrent_time - $selected_accommodations['timestamp'] > 60 * 60 * 12)
                { // only save check-rate-info in 12 hours
                    
                    $this->session->unset_userdata(TOUR_ACCOMMODATION_RATE_SELECTED);
                    
                    $this->_go_back_to_check_rate($tour, $check_rate_info);
                }
                else
                {
                    
                    $nr_rooms = $selected_accommodations['nr_rooms'];
                }
            }
        }
        else
        { // go from post form action
            
            $nr_rooms = $this->input->post(NULL, TRUE);
            
            $selected_accommodations['timestamp'] = microtime(true);
            $selected_accommodations['nr_rooms'] = $nr_rooms;
            
            if ($action == ACTION_BOOK_NOW)
            { // we know that user go from check-rate page
                $this->session->set_userdata(TOUR_ACCOMMODATION_RATE_SELECTED, $selected_accommodations);
            }
        }
        
        // load accommodation rates
        $accommodation_rates = _load_cruise_cabin_rates($tour_id, $tour['cruise_id'], $check_rate_info, $startdate, $enddate);
        
        $selected_cabin_rates = $this->_get_selected_accommodation_rates($accommodation_rates, $nr_rooms);
        
        $data['selected_cabin'] = $selected_cabin_rates;
        
        $surcharges = $this->_load_cruise_sucharges($tour, $startdate, $enddate, $check_rate_info, $selected_cabin_rates);

        if ($action == ACTION_MAKE_BOOKING && contact_validation())
        {
            $this->_make_booking($tour, $startdate, $enddate, $selected_cabin_rates, $check_rate_info, $surcharges);
            
            $this->session->unset_userdata(TOUR_ACCOMMODATION_RATE_SELECTED);
            
            redirect(site_url('xac-nhan.html?type=cruise'));
            exit();
        }
        else
        {
            
            $data['tour'] = $tour;
            
            $data = get_in_page_theme(CRUISE_HL_BOOKING_PAGE, $data, $is_mobile);
            
            $data['surcharges'] = $surcharges;
            
            $data['payment_detail'] = $this->_count_total_payments($selected_cabin_rates, $surcharges);
            
            if ($is_mobile)
            {
                $data['step_booking'] = $this->load->view('mobile/cruises/common/step_booking', $data, TRUE);
                
                $data['selected_cabins'] = $this->load->view('mobile/cruises/cruise_booking/selected_cabins', $data, TRUE);
                
                $data['surcharge_detail'] = $this->load->view('mobile/cruises/cruise_booking/surcharges', $data, TRUE);
                
                $data['payment_detail'] = $this->load->view('mobile/cruises/cruise_booking/payment_detail', $data, TRUE);
                
                $data['customer_contact'] = load_contact_form(false, '', 'data-area', $is_mobile);
                
                $data['payment_method'] = load_payment_method(CRUISE, $is_mobile);
                
                // $data['cruise_pro_code'] = $this->load->view('mobile/cruises/cruise_booking/cruise_pro_code', $data, TRUE);
                
                _render_view('mobile/cruises/cruise_booking/cruise_booking', $data, $is_mobile);
            }
            else
            {
                $data['step_booking'] = $this->load->view('cruises/common/step_booking', $data, TRUE);
                
                $data['selected_cabins'] = $this->load->view('cruises/cruise_booking/selected_cabins', $data, TRUE);
                
                $data['surcharge_detail'] = $this->load->view('cruises/cruise_booking/surcharges', $data, TRUE);
                
                $data['payment_detail'] = $this->load->view('cruises/cruise_booking/payment_detail', $data, TRUE);
                
                $data['customer_contact'] = load_contact_form(false, '', 'data-area');
                
                $data['payment_method'] = load_payment_method(CRUISE);
                
                $data['cruise_pro_code'] = $this->load->view('cruises/cruise_booking/cruise_pro_code', $data, TRUE);
                
                _render_view('cruises/cruise_booking/cruise_booking', $data);
            }
            
            // $data['bpv_content'] = $this->load->view('cruises/cruise_booking/cruise_booking', $data, TRUE);
            
            // $this->load->view('_templates/bpv_layout', $data);
        }
    }

    function _go_back_to_check_rate($tour, $check_rate_info)
    {
        $check_rate_info['action'] = ACTION_CHECK_RATE;
        redirect(cruise_tour_build_url($tour, $check_rate_info));
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
     * Count total payment
     */
    function _count_total_payments($selected_cabin_rates, $surcharges)
    {
        $total_payment_origin = $selected_cabin_rates['basic_rate'];
        $total_payment = $selected_cabin_rates['sell_rate'];
        
        foreach ($surcharges as $value)
        {
            
            $total_payment_origin += $value['total_charge'];
            
            $total_payment += $value['total_charge'];
        }
        
        $payment_detail['surcharges'] = $surcharges;
        
        $payment_detail['total_payment_origin'] = $total_payment_origin;
        $payment_detail['total_discount'] = $total_payment_origin - $total_payment;
        $payment_detail['total_payment'] = $total_payment;
        
        $payment_detail['accommodation'] = $selected_cabin_rates['cabin_rate_info'];
        
        return $payment_detail;
    }

    function _make_booking($tour, $startdate, $enddate, $selected_cabin_rates, $check_rate_info, $surcharges)
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
        $code_discount_info = get_pro_code_discount_info($promotion_code, CRUISE, '', $tour['cruise_id'], '', $nr_passengers, $customer['phone']);
        
        $customer_booking = get_cruise_tour_customer_booking($tour['id'], $startdate, $enddate, $customer_id, $special_request, $payment_info, $code_discount_info);
        
        $service_reservations = get_cruise_tour_service_reservations($tour, $startdate, $enddate, $selected_cabin_rates, $check_rate_info, $surcharges, $code_discount_info);
        
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
        
        $this->_send_mail($check_rate_info, $tour, $selected_cabin_rates, $customer, $customer_booking_id, $surcharges, $code_discount_info);
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
        
        $content = $this->load->view('cruises/common/cruise_booking_mail', $data, TRUE);
        
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
        
        $subject = lang('hb_email_reply') . ': ' . $tour['name'] . ' - ' . BRANCH_NAME;
        $CI->email->subject($subject);
        
        $CI->email->message($content);
        
        if (! $CI->email->send())
        {
            log_message('error', 'cruise_boooking: Can not send email to ' . $customer['email']);
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
        
        $subject = lang('hb_email_reply') . ': ' . $tour['name'] . ' - ' . $customer['full_name'];
        $CI->email->subject($subject);
        
        $CI->email->message($content);
        
        if (! $CI->email->send())
        {
            log_message('error', 'cruise_boooking: Can not send email to bestpricevn@gmail.com');
        }
        
        return true;
    }
}
