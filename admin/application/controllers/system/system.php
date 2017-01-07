<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * System
 *
 * @author toanlk
 * @since  May 16, 2015
 */
class System extends BP_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('System_Model');
        
        $this->load->language('system');
        
        $this->load->library('form_validation');
    }

    /**
     * index
     *
     * @author toanlk
     * @since  May 16, 2015
     */
    public function index()
    {
        $this->session->set_userdata('MENU', MNU_SYSTEM);

        //$this->resize_new_photos();
        //$this->get_customer_email();
        
        $action = $this->input->post('action');
        
        if (! empty($action) && $action == 'clear_cache')
        {
            $cache_link = $this->input->post('cache_link');
            
            $data['msg'] = $this->_clear_cache($cache_link);
        }
  
        $data['site_title'] = lang('system_title');
        
        _render_view('system/system_view', $data);
    }

    /**
     * _clear_cache
     *
     * @author toanlk
     * @since  May 16, 2015
     */
    function _clear_cache($cache_url = null)
    {
        if(!empty($cache_url)) {
            
            // default: with slash at the end
            $file_name = md5(rtrim(trim($cache_url), '/'));
            
            $status = deleteCache($file_name);
            
            $file_deleted = $file_name;
            
            // without slash at the end
            $file_name_without_slash = md5(rtrim(trim($cache_url)));
            
            if(!$status) {
                $status = deleteCache($file_name_without_slash);
                
                $file_deleted = $file_name_without_slash;
            }
            
            if ($status)
            {
                return 'Deleted files successfull !!!';
            }
            else
            {
                return 'Failed to delete file: ' . $file_deleted;
            }
            
        } else {
            $common_pages = array();
            
            // get base url
            $base_url = str_replace('admin', '', site_url());
            
            // reduce double slashes
            $base_url = preg_replace("#(^|[^:])//+#", "\\1/", $base_url);
            
            $landing_pages = array(
                'khach-san',
                've-may-bay',
                'tour',
                'du-thuyen-ha-long',
                'khuyen-mai.html',
                'tour/du-lich-nuoc-ngoai.html',
                'tour/du-lich-trong-nuoc.html'
            );
            
            // home
            $home_page = $base_url;
            $common_pages[] = $home_page;
            
            foreach ($landing_pages as $page) {
                $common_pages[] = $base_url.$page;
            }
            
            foreach ($common_pages as $page)
            {
                deleteCache(md5($page));
            }
            
            return 'Clear all cache files completed!';
        }
    }
    
    function get_valid_return_customers() {
        
        $this->load->model('Booking_Model');
        
        $search_criteria = array(
            'sort_column' => 'cb.request_date',
            'sort_order' => 'desc',
            'booking_status' => array(6),
            'duplicated_cb' => 1
        );
        
        $bookings = $this->Booking_Model->searchCustomerBooking($search_criteria, -1, 0);
        
        //echo("<pre>");print_r($bookings);echo("</pre>");exit();
        
        $content = '';

        foreach ($bookings as  $k => $booking)
        {
            $service_name = '';
            
            foreach ($booking['service_reservations'] as $service) {
                $service_name .= $service['service_name'].'<br>';
            }
            
            $content .= '<tr>
            		        <td>'.($k + 1).'</td>
            		        <td>'.$booking['full_name'].'</td>
            		        <td>'.$service_name.'</td>
            		        <td>'.$booking['phone'].'</td>
            		        <td>'.$booking['email'].'</td>
            		        <td>'.$booking['username'].'</td>
            		        <td>'.date('d-m-Y H:i:s', strtotime($booking['request_date'])).'</td>
            		        <td>'.date(DATE_FORMAT, strtotime($booking['start_date'])).'</td>
        		            <td>'.$booking['net_price'].'</td>
    		                <td>'.$booking['selling_price'].'</td>
		                    <td>'.$booking['profit'].'</td>
        		          </tr>';
        }
        
        $msg = '<table border="1">
		            <thead>
		                 <tr>
	                        <td>No.</td>
                		    <td>Name</td>
                            <td>Service Name</td>
                		    <td>Phone</td>
                		    <td>Email</td>
                            <td>Sale</td>
                            <td>Request Date</td>
                            <td>Start Date</td>
                            <td>NET</td>
                            <td>SELL</td>
                            <td>Profit</td>
		                 </tr>
		            </thead>
                	<tbody>
                		'.$content.'
                	</tbody>
                </table>';
        header('Content-Type: text/html; charset=utf-8');
        echo $msg;
        
        exit();
    }

    function get_customer_email()
    {
        $this->load->database();
        
        //$this->db->select('c.id');
        $this->db->select('c.id, c.full_name as name, c.email, c.phone, cb.status');
    	$this->db->from('customer_bookings cb');
    	$this->db->join('customers c', 'c.id = cb.customer_id');
    	$this->db->where('cb.deleted != 1');
    	//$this->db->where('cb.request_date >= ', '2014-05-01');
    	//$this->db->where('cb.request_date <= ', '2015-05-20');
    	//$this->db->where_in('cb.status', array(6));
    	$this->db->group_by('c.phone');
    	$query = $this->db->get();
        
        $close_win_bookings = $query->result_array();
        
        $content = '';
        $ignore_emails = array(
            /* 'facebook',
            '@gmali.com',
            'gmail.com',
            'yahho.com.vn',
            '@gmai.com',
            '@gmal.com',
            '@gmall.com',
            '@yhahoo.com.vn',
            '@yaho.com.vn',
            '@gmil.com',
            '@gamail.com',
            '@gamil.com',
            '@ymai.com',
            '@yshoo.com',
            '@yahho.com',
            '@gail.com',
            '@gmaik.com',
            '@gmali.com',
            '@yahoo',
            '@gmail',
            '@hotmail',
            '@yahoo.con',
            '@yahoo.de',
            '@gmail.com',
            '@yahoo.com',
            '@yahoo.com.vn',
            '@live.com',
            '@ymail.com',
            '@yahoo.com.de',
            '@yahoo.com.au',
            '@hotmail.com', */
            
            '@Bestviettravel.xyz',
            '@bestpricevn.com',
            'noemail@gmail.com',
            'flightbestprice@gmail.com',
            'lananh301@gmail.com',
            'noemail@bestprice.com',
            'nomail@gmail.com'
        );
        
        $cnt = 1;
        $customer_emails = array();
        foreach ($close_win_bookings as $booking)
        {
            $email_check = true;
            foreach ($ignore_emails as $email) {
                if(strpos(strtolower($booking['email']), trim($email)) !== false || empty($booking['email'])) {
                    $email_check = false;
                    break;
                }
            }
            
            if(!$email_check || in_array($booking['email'], $customer_emails)) continue;
            
            $customer_emails[] = $booking['email'];
            
            $booking_status = '';
            
            switch ($booking['status']) {
                case -1:
                    $booking_status = 'Process';
                    break;
                case -2:
                    $booking_status = 'Booked';
                    break;
                case -3:
                    $booking_status = 'Finished';
                    break;
                case 1:
                    $booking_status = 'New';
                    break;
                case 2:
                    $booking_status = 'Pending';
                    break;
                case 3:
                    $booking_status = 'Deposit';
                    break;
                case 4:
                    $booking_status = 'FullPaid';
                    break;
                case 5:
                    $booking_status = 'Cancel';
                    break;
                case 6:
                    $booking_status = 'CloseWin';
                    break;
                case 7:
                    $booking_status = 'CloseLost';
                    break;
            }

            $content .= '<tr>
            		        <td>'.$cnt.'</td>
            		        <td>'.$booking['name'].'</td>
            		        <td>'.$booking['phone'].'</td>
            		        <td>'.$booking['email'].'</td>
            		        <td>'.$booking_status.'</td>
        		          </tr>';
            $cnt++;
        }
        
        $msg = '<table border="1">
		            <thead>
		                 <tr>
	                        <td>No.</td>
                		    <td>Name</td>
                		    <td>Phone</td>
                		    <td>Email</td>
                            <td>Status</td>
		                 </tr>
		            </thead>
                	<tbody>
                		'.$content.'
                	</tbody>
                </table>';
        header('Content-Type: text/html; charset=utf-8');
        echo $msg;
        
        exit();
    }
    
    function get_return_customers() {
        $sql_query = "SELECT name, phone, email, sale, DATE_FORMAT(booking_date, '%d/%m/%Y') as booking_date
	           FROM booking_report
               GROUP BY phone HAVING COUNT(*) >= 3
	           ORDER BY name ASC";
        
        $query = $this->db->query($sql_query);
        
        $results = $query->result_array();
        
        $bookings = array();
        
        foreach ($results as $value) {
        
            $sql_query = "SELECT DATE_FORMAT(booking_date, '%d/%m/%Y') as booking_date
	           FROM booking_report
               WHERE phone=".$value['phone'];
        
            $query = $this->db->query($sql_query);
        
            $booking_dates = $query->result_array();
        
            $arr_date = array();
        
            foreach ($booking_dates as $b_date) {
                if(!in_array($b_date['booking_date'], $arr_date)) {
                    $arr_date[] = $b_date['booking_date'];
                }
            }
        
            if(count($arr_date) >= 2) {
                $bookings[] = $value;
            }
        }
        
        $content = '';
        
        $cnt = 1;
        foreach ($bookings as $booking)
        {
            $content .= '<tr>
            		        <td>'.$cnt.'</td>
            		        <td>'.$booking['name'].'</td>
            		        <td>'.$booking['email'].'</td>
            		        <td>'.$booking['phone'].'</td>
        		            <td>'.$booking['sale'].'</td>
        		            <td>'.$booking['booking_date'].'</td>
        		          </tr>';
            $cnt++;
        }
        
        $msg = '<table border="1">
		            <thead>
		                 <tr>
	                        <td>No.</td>
                		    <td>Name</td>
                		    <td>Email</td>
                		    <td>Phone</td>
                		    <td>Sale</td>
                            <td>Booking Date</td>
		                 </tr>
		            </thead>
                	<tbody>
                		'.$content.'
                	</tbody>
                </table>';
        header('Content-Type: text/html; charset=utf-8');
        echo $msg;
        
        exit();
    }
    
    function get_booking_report(){
    
    	$this->db->select('c.phone, count(*) as nr_booking');
    	$this->db->from('customer_bookings cb');
    	$this->db->join('customers c', 'c.id = cb.customer_id');
    	$this->db->where('cb.deleted != 1');
    	$this->db->where('cb.start_date >= ', '2014-01-01');
    	$this->db->where('cb.start_date <= ', '2014-12-31');
    	//$this->db->where('cb.request_type', '1');
    	$this->db->where_in('cb.status', array(3, 4, 6));
    	$this->db->group_by('c.phone');
    	//$this->db->having('nr_booking >','1');
    	$query = $this->db->get();
    	$results = $query->result_array();
    
    	$cnt = count($results);
    
    	echo("<pre>");print_r('All Close Win '.$cnt);echo("</pre>");
    
    	echo("<pre>");print_r('Query: '.$this->db->last_query());echo("</pre>");
    	
    	
    	$phones = array();
    	foreach ($results as $obj){
    		$phones[] = $obj['phone'];
    	}
    
    	
    	$this->db->select('c.phone, count(*) as nr_booking');
    	$this->db->from('customer_bookings cb');
    	$this->db->join('customers c', 'c.id = cb.customer_id');
    	$this->db->where('cb.deleted != 1');
    	$this->db->where('cb.start_date >= ', '2014-01-01');
    	$this->db->where('cb.start_date <= ', '2014-12-31');
    	$this->db->where_in('cb.request_type', array(2));
    	$this->db->where_in('cb.status', array(5));
    	$this->db->where_not_in('c.phone',$phones);
    	$this->db->group_by('c.phone');
    	$query = $this->db->get();
    	$results = $query->result_array();
    
    	$cnt = count($results);
    
    	echo("<pre>");print_r('Close Lost '.$cnt);echo("</pre>");
    
    	echo("<pre>");print_r('Query: '.$this->db->last_query());echo("</pre>");exit();
    
    
    	
    	$this->db->select('cb.id, c.phone, cb.start_date, cb.request_type');
    	$this->db->from('customer_bookings cb');
    	$this->db->join('customers c', 'c.id = cb.customer_id');
    	$this->db->where('cb.deleted != 1');
    	$this->db->where('cb.start_date >= ', '2014-01-01');
    	$this->db->where('cb.start_date <= ', '2014-12-31');
    	
    	$this->db->where_in('cb.status', array(3, 4, 6));
    	$this->db->where_in('c.phone',$phones);
    	$this->db->order_by('c.phone, cb.start_date');
    	$query = $this->db->get();
    	$results = $query->result_array();
    
    	$cnt = 0;
    	$pre_phone = '';
    	$pre_date = '2013-01-01';
    	foreach ($results as $key=>$value){
    		
    	if($value['request_type'] == 1){
    		
    		//if($value['request_type'] == 1){
    		
		    	if($value['phone'] != $pre_phone){
		    		$cnt++;
		    	} else{
			    	$nr_date = $this->count_date($pre_date, $value['start_date']);
			    		
			    	if($nr_date > 7){
			    	$cnt++;
		    	}
		    		
		    	}
		    
		    	$pre_phone = $value['phone'];
		    
		    	$pre_date = $value['start_date'];
		    	
    		//}
    		
    	}
    		
    	}
    
    	echo("<pre>");print_r('Nr booking: '.$cnt);echo("</pre>");
    
    	echo("<pre>");print_r('Query: '.$this->db->last_query());echo("</pre>");exit();
    	
    }
    
    function count_date($start_date, $end_date){
    	$startTimeStamp = strtotime($start_date);
    	$endTimeStamp = strtotime($end_date);
    
    	$timeDiff = abs($endTimeStamp - $startTimeStamp);
    
    	$numberDays = $timeDiff/86400;  // 86400 seconds in one day
    
    	// and you might want to convert to integer
    	$numberDays = intval($numberDays);
    
    	return $numberDays;
    }
    
    function resize_new_photos() {
        
        // set time limit, could take up to 10 mins
        set_time_limit(60*10);
    
        $this->load->helper('photo');
        $this->load->library('image_lib');
        
        /* $this->config->load('news_meta');
        $photo_folder = $this->config->item('news_photo_size');
        
		$query = $this->db->get('news_photos');
		$results = $query->result_array();
		
		$cnt = 0;
        foreach ($results as $value) {
            
            list($t_width, $t_height) = @getimagesize('../images/news/'.$value['name']);
            
            if (! empty($t_width))
            {
                $resize_data = array(
                    'file_name'    => $value['name'],
                    'full_path'    => '../images/news/' . $value['name'],
                    'image_width'  => $t_width,
                    'image_height' => $t_height,
                );
                
                resize_and_crop($resize_data, $photo_folder, 90);
                
                $cnt++;
            }
        } */
        
        $this->config->load('user_meta');
        $photo_folder = $this->config->item('user_photo_size');
        
        $query = $this->db->get('users');
        $results = $query->result_array();
        
        $cnt = 0;
        foreach ($results as $value) {
        
            list($t_width, $t_height) = @getimagesize('../images/users/'.$value['avatar']);
        
            if (! empty($t_width))
            {
                $resize_data = array(
                    'file_name'    => $value['avatar'],
                    'full_path'    => '../images/users/' . $value['avatar'],
                    'image_width'  => $t_width,
                    'image_height' => $t_height,
                );
        
                resize_and_crop($resize_data, $photo_folder, 90);
        
                $cnt++;
            }
        }
        
        echo('<pre>');
        print_r('Resize '.$cnt.'/'.count($results).' news photos completed!');
        echo('</pre>');
        exit();
    }
}
