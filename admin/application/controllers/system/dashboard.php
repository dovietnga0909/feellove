<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dashboard
 *
 * @author toanlk
 * @since  May 14, 2015
 */
class Dashboard extends BP_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Booking_Model');
		
		$this->load->library('form_validation');
	}

	public function index()
	{
		// set session for menues
		$this->session->set_userdata('MENU', MNU_DASHBOARD);

		$data['site_title'] = lang('mnu_dashboard');
		
		$data = get_library('datepicker', $data);
		
		$data = $this->get_data($data);

		_render_view('system/dashboard', $data);
	}
	
	function get_data($data)
    {
        $bookings = null;
        
        $data['booking_status'] = $this->config->item('booking_status');
        
        $action = $this->input->post('action');
        
        if ($action == 'apply')
        {
            $search_criteria = array(
                'sort_column' => 'cb.request_date',
                'sort_order' => 'desc',
                //'booking_status' => array(6),
            );
            
            $start_date = $this->input->post('start_date');
            
            if (! empty($start_date))
            {
                $search_criteria['start_date'] = $start_date;
                
                $search_criteria['date_field'] = array(2);
            }
            
            $end_date = $this->input->post('end_date');
            
            if (! empty($end_date))
            {
                $search_criteria['end_date'] = $end_date;
            }
            
            $booking_status = $this->input->post('booking_status');
            
            if (! empty($booking_status))
            {
                $search_criteria['booking_status'] = array($booking_status);
            }
            
            $number_of_return = $this->input->post('number_of_return');
            
            if (! empty($number_of_return))
            {
                $search_criteria['duplicated_cb'] = $number_of_return;
            }
            
            //if(empty($search_criteria['duplicated_cb'])) $search_criteria['duplicated_cb'] = 3;
            
            $bookings = $this->search_booking($search_criteria, null, 0);
            
            $data['search_criteria'] = $search_criteria;
        }
        
        $data['bookings'] = $bookings;
        
        return $data;
    }
    
    function search_booking($search_criteria = null, $num = null, $offset = 0)
    {
        $str_query = "SELECT cb.*, c.full_name, c.email, c.phone, d.name as city, c.gender as title, c.address, u.username  FROM customer_bookings cb ".
				" INNER JOIN customers c ON c.id = cb.customer_id".
				" INNER JOIN users u ON u.id = cb.user_id".
				" LEFT OUTER JOIN destinations d ON d.id = c.destination_id";
        
		$str_query = $this->Booking_Model->_getSearchCBQueryStrCondition($search_criteria, $str_query);
		
		// $str_query = $str_query .' GROUP BY c.phone HAVING COUNT(*) >= '.$search_criteria['duplicated_cb'];

		$str_query = $str_query. " ORDER BY " . $search_criteria['sort_column']." ".$search_criteria['sort_order'];
		
		$str_query = $str_query. ", cb.id DESC";
        
        if (! empty($num))
        {
            $str_query = $str_query . " LIMIT " . $offset . ", " . $num;
        }
        
        $query = $this->db->query($str_query);
        
        $cbs = $query->result_array();
        
        $cb_ids = $this->Booking_Model->_getIdsArr($cbs);
        
        $srs = $this->Booking_Model->_getServiceReservations($cb_ids);
        
        $cbs = $this->Booking_Model->_setServiceReservations($cbs, $srs);
        
        return $cbs;
    }
    
    /* foreach ($cbs as $value) {
    
    $sql_query = "SELECT cb.start_date
    FROM customer_bookings cb
    LEFT OUTER JOIN customers c ON c.id = cb.customer_id
    WHERE c.phone='".$value['phone']."' AND cb.deleted !=".DELETED.' ORDER BY cb.start_date asc';
    
    $query = $this->db->query($sql_query);
    
    $booking_dates = $query->result_array();
    
    $arr_date = array();
    
    foreach ($booking_dates as $b_date) {
    if(!in_array($b_date['start_date'], $arr_date)) {
    $arr_date[] = $b_date['start_date'];
    }
    }
    
    // count request dates have a week different
    $cnt_date = 1;
    
    for ($i=0; $i < count($arr_date); $i++) {
    
    if($i + 1 == count($arr_date)) break;
    
    $d1=new DateTime($arr_date[$i]);
    $d2=new DateTime($arr_date[$i + 1]);
    $interval= $d2->diff($d1)->format('%a');
    
    if($interval >= 7) {
    $cnt_date++;
    }
    }
    
    if(count($cnt_date) >= $search_criteria['duplicated_cb']) {
    $value['booking_dates'] = $arr_date;
    $bookings[] = $value;
    }
    } */
}
