<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_Model extends CI_Model {	

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();	
        
		$this->load->database();
    }
    
    function create_or_update_customer($customer){
    	
    	unset($customer['special_request']);
    	
    	$customer['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$customer['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	 
    	$customer['user_created_id'] = 0;
    	$customer['user_modified_id'] = 0;
    	
    	$this->db->insert('customers', $customer);
    	
    	$id = $this->db->insert_id();
    	
    	return $id;
    	
    	/*
    	$this->db->where('email', $customer['email']);
    	$this->db->where('deleted !=', DELETED);
   
    	$query = $this->db->get('customers');
    	
    	$customers = $query->result_array();
    	 
    	if(count($customers) == 0){
    		$customer['date_created'] = date(DB_DATE_TIME_FORMAT);
    		$customer['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    		$customer['user_created_id'] = 0;
    		$customer['user_modified_id'] = 0;
    	
    		$this->db->insert('customers', $customer);
    		
    		$id = $this->db->insert_id();
    		
    		return $id;
    	} else {
    		
    		$db_cus = $customers[0];
    		
    		$customer['date_modified'] = date(DB_DATE_TIME_FORMAT);

    		$this->db->where('id', $db_cus['id']);
    	
    		$this->db->update('customers', $customer);
    		
    		return $db_cus['id'];
    	
    	}*/
    	
    }
    
    function update_price($cb_id){
    
    	$this->db->select_sum('net_price', 'net');
    
    	$this->db->where('customer_booking_id', $cb_id);
    
    	$this->db->where('deleted !=', DELETED);
    
    	$query = $this->db->get('service_reservations');
    
    	$results = $query->result_array();
    
    	$net = count($results) > 0 ? $results[0]['net'] : 0;
    
    
    	$this->db->select_sum('selling_price', 'sel');
    
    	$this->db->where('customer_booking_id', $cb_id);
    
    	$this->db->where('deleted !=', DELETED);
    
    	$query = $this->db->get('service_reservations');
    
    	$results = $query->result_array();
    
    	$sel = count($results) > 0 ? $results[0]['sel'] : 0;
    
    	$this->db->set('net_price', $net);
    
    	$this->db->set('selling_price', $sel);
    
    	$this->db->where('id', $cb_id);
    
    	$this->db->update('customer_bookings');
    
    }
    
    
    
    function save_customer_booking($customer_booking, $service_reservations){
    	
    	if(isset($customer_booking['flight_users'])){

    		$flight_users = $customer_booking['flight_users'];
    		
    		unset($customer_booking['flight_users']);
    	}
    	
    	//$customer_booking = $this->set_booking_source_data($customer_booking);
    	
    	$customer_booking['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$customer_booking['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    	$this->db->insert('customer_bookings', $customer_booking);
    		
    	$customer_booking_id = $this->db->insert_id();
    	
    	foreach ($service_reservations as $sr) {
    		
    		$sr['customer_booking_id'] = $customer_booking_id;
    		
    		$sr['date_created'] = date(DB_DATE_TIME_FORMAT);
    		$sr['date_modified'] = date(DB_DATE_TIME_FORMAT);
    		
    		// insert main service
    		$this->db->insert('service_reservations', $sr);
    			
    		$rs_id = $this->db->insert_id();
    	}
    	
    	$this->update_price($customer_booking_id);
    	
    	if(isset($flight_users)){
    		
    		$this->save_flight_users($flight_users, $customer_booking_id);
    	}
    	
    	return $customer_booking_id;
    }
    
    function get_customer_type($customer_id){
    
    	$this->db->where('customer_id', $customer_id);
    
    	$this->db->where('status', 6); // close win
    
    	$this->db->where('deleted !=', DELETED);
    
    	$nr = $this->db->count_all_results('customer_bookings');
    
    	if ($nr > 0){
    			
    		return CUSTOMER_TYPE_RETURN;
    			
    	} else {
    			
    		return CUSTOMER_TYPE_NEW;
    			
    	}
    
    }
    
    function save_flight_users($flight_users, $customer_booking_id){
    
    	if(!empty($flight_users)){
    			
    		$adults = $flight_users['adults'];
    			
    		$children = $flight_users['children'];
    			
    		$infants = $flight_users['infants'];
    			
    
    		foreach ($adults as $value){
    
    			$value['customer_booking_id'] = $customer_booking_id;
    
    			$value['type'] = 1; // 1 for adult
    			
    			$value['date_created'] = date(DB_DATE_TIME_FORMAT);
    			
    			if(!empty($value['birth_day'])){
    				$value['birth_day'] = format_bpv_date($value['birth_day']);
    			}
    			
    			if(!empty($value['passportexp'])){
    				$value['passportexp'] = format_bpv_date($value['passportexp']);
    			}
    				
    			$this->db->insert('flight_users',$value);
    
    		}
    			
    		foreach ($children as $value){
    
    			$value['customer_booking_id'] = $customer_booking_id;
    
    			$value['type'] = 2; // 2 for children
    
    			$value['birth_day'] = format_bpv_date($value['birth_day']);
    			
    			$value['date_created'] = date(DB_DATE_TIME_FORMAT);
    			
    			if(!empty($value['passportexp'])){
    				$value['passportexp'] = format_bpv_date($value['passportexp']);
    			}
    				
    			$this->db->insert('flight_users',$value);
    
    		}
    			
    		foreach ($infants as $value){
    
    			$value['customer_booking_id'] = $customer_booking_id;
    
    			$value['type'] = 3; // 3 for infants
    
    			$value['birth_day'] = format_bpv_date($value['birth_day']);
    			
    			$value['date_created'] = date(DB_DATE_TIME_FORMAT);
    			
    			if(!empty($value['passportexp'])){
    				$value['passportexp'] = format_bpv_date($value['passportexp']);
    			}
    				
    			$this->db->insert('flight_users',$value);
    
    		}
    			
    	}
    
    }
    
    
    /**
     * 
     * Functions for Flight Invoice 
     * 
     */
    
    function create_invoice($customer_id, $customer_booking_id, $type = FLIGHT){
    
    	$customer_booking = $this->get_customer_booking_selling_price($customer_booking_id);
    
    	$booking_services = $this->get_services_of_invoice($customer_booking_id);
    
    	$invoice_desc = '';
    	if (count($booking_services) > 0){
    			
    		foreach ($booking_services as $value) {
    			
    			if($type == FLIGHT){
    				
    				if(!empty($value['flight_code'])){
    					$invoice_desc .= $value['flight_code'].', '.$value['flight_from'].' -> '.$value['flight_to'].', '.format_bpv_date($value['start_date'], DATE_FORMAT, true);
    					$invoice_desc .= ' ('.$value['booking_services'].')';
    					$invoice_desc .= "\n";
    				}
    				
    				
    			} else {
    				
    				$invoice_desc .= $value['service_name'] . ' - ' . $value['booking_services'].', '.format_bpv_date($value['start_date'], DATE_FORMAT, true);
    				$invoice_desc .= "\n";
    				
    			}
    		}
    			
    	}
    
   
    	if ($customer_booking !== FALSE){
    			
    		$amount = $customer_booking['selling_price'];
    			
    		$invoice['status'] = INVOICE_NOT_PAID;
    			
    		$invoice['date_created'] = date(DB_DATE_TIME_FORMAT);
    			
    		$invoice['date_modified'] = date(DB_DATE_TIME_FORMAT);
    			
    		$invoice['amount'] = $amount;
    			
    		$invoice['customer_id'] = $customer_id;
    			
    		$invoice['customer_booking_id'] = $customer_booking_id;
    			
    		$invoice['description'] = $invoice_desc;
    			
    		$invoice['type'] = $type;
    			
    		$this->db->insert('invoices', $invoice);
    			
    		$id = $this->db->insert_id();
    			
    		$invoice_reference = 'BPV_'.time().'_'.$id;
    			
    		// update invoice reference
    			
    		$this->db->set('invoice_reference', $invoice_reference);
    			
    		$this->db->where('id', $id);
    			
    		$this->db->update('invoices');
    			
    		return $id;
    			
    	} else {
    			
    		return FALSE;
    	}
    
    }
    
    function get_invoice_by_reference($invoice_reference){
    	
    	// get the last updated invoiced
    	$this->db->select('id, invoice_reference, customer_booking_id, status, amount, type');
    	
    	$this->db->where('invoice_reference', $invoice_reference);
    	
    	$this->db->order_by('date_modified');
    	
    	$query = $this->db->get('invoices');
    	
    	$results = $query->result_array();
    	
    	if (count($results) > 0){
    		 
    		$invoice = $results[0];
    		
    		return $invoice;
    	}
    	
    	return '';
    	
    }
    
    function get_current_cb_note($customer_booking_id){
    	
    	// get the last updated invoiced
    	$this->db->select('note');
    	 
    	$this->db->where('id', $customer_booking_id);
    	 
    	$query = $this->db->get('customer_bookings');
    	 
    	$results = $query->result_array();
    	 
    	if (count($results) > 0){
    		 
    		$cb = $results[0];
    	
    		return $cb['note'];
    	}
    	 
    	return '';
    }
    
    function update_invoice_status($status, $invoice_reference, $payment_method = PAYMENT_METHOD_DOMESTIC_CARD, $txnResponseCode = ''){

    	$invoice = $this->get_invoice_by_reference($invoice_reference);
    	
    	if(!empty($invoice) && $invoice['status'] != INVOICE_SUCCESSFUL){ // if the invoice status is Successfull, don't update the invoice status again
    		
	    	// update the invoice status
	    	$this->db->set('status', $status);
	    
	    	$this->db->set('date_modified', date(DB_DATE_TIME_FORMAT));
	    
	    	$this->db->where('invoice_reference', $invoice_reference);
	    
	    	$this->db->update('invoices');
	    
	    
	    	// get the last updated invoiced
	    	$invoice = $this->get_invoice_by_reference($invoice_reference);
	    
	    	if (!empty($invoice)){
	    			
	    		$status_text = '';
	    		
	    		$card_type = $payment_method == PAYMENT_METHOD_DOMESTIC_CARD ? '(ATM Card)' : '(Credit Card)';
	    			
	    		if($invoice['status'] == INVOICE_NOT_PAID){
	    
	    			$status_text = 'Not Paid';
	    
	    		}elseif($invoice['status'] == INVOICE_PENDING){
	    
	    			$status_text = 'Payment Pending';
	    
	    		}elseif($invoice['status'] == INVOICE_SUCCESSFUL){
	    
	    			$status_text = 'Payment Successful';
	    
	    		}elseif($invoice['status'] == INVOICE_FAILED){
	    
	    			$status_text = 'Payment Failed';
	    		}elseif($invoice['status'] == INVOICE_UNKNOWN){
	    			$status_text = 'Unknown';
	    		}
	    		
	    		$status_text = $status_text.$card_type;
	    		
	    		// check response mesage from the Onepay
	    		if($txnResponseCode != ''){
	    			$respon_desc = getResponseDescription($txnResponseCode, $payment_method);
	    			
	    			$status_text .= "\n".'Message from Onepay: '.$respon_desc;
	    		}
	    		
	    		// current customer booking note
	    		$current_cb_note = $this->get_current_cb_note($invoice['customer_booking_id']);
	    		
	    		if(!empty($current_cb_note)){
	    			
	    			$status_text .= "\n".'-----------------'."\n".$current_cb_note;
	    		}
	    		
	    			
	    		// update the customer booking
	    		$this->db->set('note', $status_text);
	    			
	    		if($invoice['status'] == INVOICE_SUCCESSFUL){
	    			
	    			$invoice['amount'] = apply_bank_fee_online_payment($invoice['amount'], $invoice['type'], $payment_method);
	    			
	    			$this->db->set('onepay', $invoice['amount']);
	    			$this->db->set('booking_date', date(DB_DATE_FORMAT));
	    		}
	    			
	    		$this->db->where('id', $invoice['customer_booking_id']);
	    			
	    		$this->db->update('customer_bookings');
	    			
	    	}
    	
    	}
    }
    
    function get_invoice_info($invoice_reference){
    
    	$this->db->select('id, invoice_reference, amount, status, customer_id, customer_booking_id, date_created, type, description, date_modified');
    
    	$this->db->where('invoice_reference', $invoice_reference);
    
    	$this->db->order_by('date_modified','desc');
    
    	$query = $this->db->get('invoices');
    
    	$results = $query->result_array();
    
    	if (count($results) > 0){
    			
    		$invoice = $results[0];
    			
    		$invoice['customer'] = $this->get_customer($invoice['customer_id']);
    			
    		$invoice['customer_booking'] = $this->get_customer_booking_of_invoice($invoice['customer_booking_id']);
    			
    		return $invoice;
    			
    	} else {
    			
    		return FALSE;
    			
    	}
    
    }
    
    function get_invoice_4_payment($id){
    
    	$this->db->select('id, invoice_reference, amount, customer_id, type, description, date_modified');
    
    	$this->db->where('id', $id);
    
    	$this->db->order_by('date_modified','desc');
    
    	$query = $this->db->get('invoices');
    
    	$results = $query->result_array();
    
    	if (count($results) > 0){
    			
    		$invoice = $results[0];
    			
    		$invoice['customer'] = $this->get_customer($invoice['customer_id']);
    			
    		return $invoice;
    			
    	} else {
    			
    		return FALSE;
    			
    	}
    }
    
    function get_customer_booking_of_invoice($customer_booking_id){
    
    	$this->db->select('start_date, end_date, selling_price, booking_date, flight_short_desc, adults, children, infants, status, deleted');
    
    	$this->db->where('id', $customer_booking_id);
    
    	$query = $this->db->get('customer_bookings');
    
    	$results = $query->result_array();
    
    	if(count($results) > 0){
    			
    		$customer_booking = $results[0];
    			
    		$customer_booking['service_reservations'] = $this->get_services_of_invoice($customer_booking_id);
    			
    		$customer_booking['flight_users'] = $this->get_flight_users_of_cb($customer_booking_id);
    			
    		return $customer_booking;
    			
    	} else {
    			
    		return FALSE;
    	}
    
    }
    
    function get_services_of_invoice($customer_booking_id){
    
    	$this->db->select('id, start_date, end_date, service_name, selling_price, booking_services, unit, reservation_type, description,
		airline, flight_code, flight_class, flight_from, flight_to, departure_time, arrival_time, fare_rules');
    
    	$this->db->where('customer_booking_id', $customer_booking_id);
    
    	$this->db->where('deleted !=', DELETED);
    
    	$this->db->order_by('id','asc');
    
    	$query = $this->db->get('service_reservations');
    
    	$results = $query->result_array();
    
    	return $results;
    }
    
    function get_flight_users_of_cb($customer_booking_id){
    
    	$this->db->where('customer_booking_id', $customer_booking_id);
    
    	$this->db->order_by('id', 'asc');
    
    	$query = $this->db->get('flight_users');
    
    	$results = $query->result_array();
    
    	return $results;
    }
    
    function get_customer_booking_selling_price($customer_booking_id){
    
    	$this->db->select('selling_price');
    
    	$this->db->where('id', $customer_booking_id);
    
    	$query = $this->db->get('customer_bookings');
    
    	$results = $query->result_array();
    
    	if(count($results) > 0){
    			
    		return $results[0];
    			
    	} else {
    			
    		return FALSE;
    	}
    
    }
    
    function get_customer($customer_id){
    
    	$this->db->select('c.id, c.gender, c.full_name, c.email, c.phone, c.address, c.ip_address, d.name as city');
    	
    	$this->db->from('customers c');
    	
    	$this->db->join('destinations d', 'd.id = c.destination_id', 'left outer');
    
    	$this->db->where('c.id', $customer_id);
    
    	$query = $this->db->get();
    
    	$results = $query->result_array();
    
    	if(count($results) > 0){
    			
    		return $results[0];
    			
    	} else {
    			
    		return FALSE;
    	}
    
    }
    
    function update_voucher_code_used($code, $customer_id){
    	$this->db->where('code', $code);
    	$this->db->set('used', STATUS_ACTIVE);
    	$this->db->set('status', 1); // 0. New, 1. Pending, 2. Used
    	$this->db->set('customer_used_id', $customer_id);
    	$this->db->set('date_used', date(DB_DATE_TIME_FORMAT));
    	$this->db->update('vouchers');
    }
    
    function update_pro_code_used($code, $code_discount_info, $customer_id){
    	$this->db->like('code', $code, 'both');
    	$query = $this->db->get('bpv_promotions');
    	$results = $query->result_array();
    	
    	if(count($results) > 0){
    		
    		$pro = $results[0];
    		
    		$current_nr_booked = $pro['current_nr_booked']; 
    		$current_nr_booked = $current_nr_booked + 1;
    		
    		$code_sql = '(code = \''.$code . '\' OR code LIKE \'%-'.$code.'-%\')';
			$this->db->where($code_sql);
			
    		$this->db->set('current_nr_booked', $current_nr_booked);
    		$this->db->update('bpv_promotions');
    	}
    	
    	// the promotion code is single used 
    	if(!empty($code_discount_info['bpv_promotion_id'])) {
    	    
    	    $promotion_user = array(
                'code'             => $code,
                'customer_id'      => $customer_id,
                'bpv_promotion_id' => $code_discount_info['bpv_promotion_id'],
    	     );
    	    
    	    $this->db->insert('bpv_promotion_users', $promotion_user);
    	}
    }
    
    /**
     * Functions For Tracking Booking Source
     * 
     */
    
    function set_booking_source_data($customer_booking){
    	
    	return $customer_booking; // added by Khuyenpv for NOT get Booking Source DATA
    
    	if (!isset($_COOKIE["__utma"]) || !isset($_COOKIE["__utmz"])) {
    			
    		return $customer_booking;
    	}
    
    	$this->load->library('GA_Parse', $_COOKIE);
    
    	$source = $this->ga_parse->campaign_source;
    
    	$medium = $this->ga_parse->campaign_medium;
    
    	$keyword = $this->ga_parse->campaign_term;
    
    	$landing_page = $this->session->userdata('landing_page');
    
    	$campaign = $this->ga_parse->campaign_name;
    
    	$ad_content = $this->ga_parse->campaign_content;
    
    	$first_visist = $this->ga_parse->first_visit;
    
    	$previsous_visist = $this->ga_parse->previous_visit;
    
    	$current_visist = $this->ga_parse->current_visit_started;
    
    	$times_visited = $this->ga_parse->times_visited;
    
    	$pages_viewed = $this->ga_parse->pages_viewed;
    
    
    	$customer_booking['booking_source_id'] = $this->create_or_get_booking_source($source);
    
    	$customer_booking['medium'] = $this->get_medium($medium);
    
    	$customer_booking['keyword'] = $keyword;
    
    	if(!empty($landing_page)){
    
    		$customer_booking['landing_page'] = $landing_page;
    
    	}
    
    	$customer_booking['campaign_id'] = $this->create_or_get_campaign($campaign);
    
    	$customer_booking['ad_content'] = $ad_content;
    
    	$customer_booking['date_first_visit'] = $first_visist;
    
    	$customer_booking['date_previous_visit'] = $previsous_visist;
    
    	$customer_booking['date_current_visit'] = $current_visist;
    
    	$customer_booking['times_visited'] = $times_visited;
    
    	$customer_booking['current_pages_viewed'] = $pages_viewed;
    
    	return $customer_booking;
    
    }
    
    
    // booking source
    function create_or_get_booking_source($source){
    
    	if ($source == NULL) return 0;
    
    	$this->db->where('name', $source);
    
    	$query = $this->db->get('booking_sources');
    
    	$results = $query->result_array();
    
    	if (count($results) > 0){
    			
    		return $results[0]['id'];
    			
    	} else {
    			
    		$src['name'] = $source;
    
    		$this->db->insert('booking_sources', $src);
    			
    		$src_id = $this->db->insert_id();
    			
    		return $src_id;
    			
    	}
    
    }
    
    function create_or_get_campaign($campaign){
    
    	if ($campaign == NULL) return 0;
    
    	$this->db->where('name', $campaign);
    
    	$query = $this->db->get('campaigns');
    
    	$results = $query->result_array();
    
    	if (count($results) > 0){
    			
    		return $results[0]['id'];
    			
    	} else {
    			
    		$src['name'] = $campaign;
    
    		$this->db->insert('campaigns', $src);
    			
    		$src_id = $this->db->insert_id();
    			
    		return $src_id;
    			
    	}
    }
    
    function get_medium($medium){
    
    	$mediums = $this->config->item('mediums');
    
    	foreach($mediums as $key => $value){
    			
    		if ($medium == lang($value)){
    
    			return $key;
    
    		}
    			
    	}
    
    	return 0;
    
    }
}