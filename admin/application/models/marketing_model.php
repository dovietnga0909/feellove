<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketing_Model extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();

		$this->load->database();
    }

    function create_voucher($voucher){

    	$nubmer_voucher = $voucher['number_voucher'];

    	unset($voucher['number_voucher']);

    	for ($i=0; $i<$nubmer_voucher; $i++){

    		do {

    			$code = generate_random_code();

    			$already_generated = $this->is_generated_vc_code($code) || $this->is_generated_pro_code($code);

    		} while ($already_generated);

    		$voucher['user_created_id'] = get_user_id();
    		$voucher['user_modified_id'] = get_user_id();

    		$voucher['date_created'] = date(DB_DATE_TIME_FORMAT);
    		$voucher['date_modified'] = date(DB_DATE_TIME_FORMAT);

    		$voucher['code'] = $code;


    		$this->db->insert('vouchers', $voucher);
    		$id = $this->db->insert_id();

    	}

    	$error_nr = $this->db->_error_number();

    	return !$error_nr;

    }

    function update_voucher($id, $voucher){

    	unset($voucher['number_voucher']);
 		$voucher['date_modified'] = date(DB_DATE_TIME_FORMAT);

 		$voucher['user_modified_id'] = get_user_id();
 		$username = get_username();

 		$data_update_old = $this->Marketing_Model->get_voucher($id);
    	$this->db->where('id', $id);
    	$query=$this->db->update('vouchers', $voucher);

    	$error_nr = $this->db->_error_number();
   		//$error_ms = $this->db->_error_message();

		return !$error_nr;

    }


    function delete_voucher($id){

    	$this->db->where('id', $id);
    	$this->db->delete('vouchers');

    	$error_nr = $this->db->_error_number();
    	//$error_ms = $this->db->_error_message();

    	return !$error_nr;
    }

    function is_generated_vc_code($code){

    	$this->db->where('code', $code);

    	$cnt = $this->db->count_all_results('vouchers');

    	return $cnt > 0;
    }

    function _build_search_vc_condition($search_criteria){

    	if(!empty($search_criteria)){

    		if(!empty($search_criteria['code'])){

    			$this->db->like('v.code', $search_criteria['code']);

    		}

    		if(!empty($search_criteria['customer_id'])){

    			$this->db->where('v.customer_id', $search_criteria['customer_id']);

    		}

    		if(!empty($search_criteria['start_date'])){

    			$this->db->where('v.expired_date >=', bpv_format_date($search_criteria['start_date'], DB_DATE_FORMAT));

    		}

    		if(!empty($search_criteria['end_date'])){

    			$this->db->where('v.expired_date <=', bpv_format_date($search_criteria['end_date'], DB_DATE_FORMAT));

    		}

    		if(!empty($search_criteria['delivered'])){

    			$this->db->where('v.delivered', $search_criteria['delivered']);

    		}


    		if (isset($search_criteria['status']) && $search_criteria['status'] != '') {

    			$this->db->where('v.status', $search_criteria['status']);
    		}

    	}

    }

    function _build_search_pro_condition($search_criteria){

    	$this->db->where('deleted !=', DELETED);

    	if(!empty($search_criteria)){


    		if(!empty($search_criteria['start_date'])){

    			$this->db->where('expired_date >=', bpv_format_date($search_criteria['start_date'], DB_DATE_FORMAT));

    		}

    		if(!empty($search_criteria['end_date'])){

    			$this->db->where('expired_date <=', bpv_format_date($search_criteria['end_date'], DB_DATE_FORMAT));

    		}


    		if(isset($search_criteria['status'])){

    			$this->db->where('status', $search_criteria['status']);

    		}

    	}

    }

    function count_total_vouchers($search_criteria){

    	$this->db->from('vouchers v');

    	$this->db->join('customers c', 'c.id = v.customer_id', 'left outer');

    	$this->_build_search_vc_condition($search_criteria);

    	return $this->db->count_all_results();

    }

    function search_vouchers($search_criteria, $per_page, $offset){

    	$this->db->select('v.*, c.full_name as customer_name, cu.full_name as customer_used_name');

    	$this->db->from('vouchers v');

    	$this->db->join('customers c', 'c.id = v.customer_id', 'left outer');

    	$this->db->join('customers cu', 'cu.id = v.customer_used_id', 'left outer');

    	$this->_build_search_vc_condition($search_criteria);

    	$this->db->order_by('v.id','asc');

    	$query = $this->db->get('', $per_page, $offset);

    	return $query->result_array();

    }

    function get_voucher($id){

    	$this->db->select('v.*, c.full_name as customer_name, cu.full_name as customer_used_name');

    	$this->db->from('vouchers v');

    	$this->db->join('customers c', 'c.id = v.customer_id', 'left outer');

    	$this->db->join('customers cu', 'cu.id = v.customer_used_id', 'left outer');

    	$this->db->where('v.id', $id);

    	$query = $this->db->get();

    	$results =  $query->result_array();

    	if(count($results) > 0){

    		return $results[0];

    		}

    	return FALSE;
    }


    function create_promotion($promotion){

    	do {

    		$alphabet = "0123456789";

    		$code = generate_random_code($alphabet, 3);

    		$code = 'BPV'.$code;

    		$already_generated = $this->is_generated_pro_code($code) || $this->is_generated_vc_code($code);

    	} while ($already_generated);


    	$promotion['user_created_id'] = get_user_id();
    	$promotion['user_modified_id'] = get_user_id();

    	$promotion['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$promotion['date_modified'] = date(DB_DATE_TIME_FORMAT);

    	$promotion['code'] = $code;


    	$this->db->insert('bpv_promotions', $promotion);
    	$id = $this->db->insert_id();

    	$error_nr = $this->db->_error_number();
    	//$error_ms = $this->db->_error_message();

    	return !$error_nr;
    }


    function is_generated_pro_code($code){

    	$this->db->where('code', $code);

    	$this->db->where('deleted !=', DELETED);

    	$cnt = $this->db->count_all_results('bpv_promotions');

    	return $cnt > 0;
    }

    function count_total_promotions($search_criteria){

    	$this->_build_search_pro_condition($search_criteria);

    	return $this->db->count_all_results('bpv_promotions');

    }

    function search_promotions($search_criteria, $per_page, $offset){

    	$this->_build_search_pro_condition($search_criteria);

    	$this->db->order_by('id');

    	$query = $this->db->get('bpv_promotions', $per_page, $offset);

    	return $query->result_array();

    }

    function get_promotion($id){

    	$this->db->where('id', $id);
    	$this->db->where('deleted !=', DELETED);

    	$query = $this->db->get('bpv_promotions');

    	$results =  $query->result_array();

    	if(count($results) > 0){

    		return $results[0];

    	}

    	return FALSE;
    }
    
    /**
     * get_promotion_code_used
     *
     * @author toanlk
     * @since  May 28, 2015
     */
    function get_promotion_code_used($id){
        
        $this->db->select('bpu.code, bpu.customer_id, c.full_name, c.phone');
        
        $this->db->join('customers c','c.id = bpu.customer_id');
        
        $this->db->where('bpu.bpv_promotion_id', $id);
        
        $query = $this->db->get('bpv_promotion_users bpu');
        
        return  $query->result_array();
    }

    function update_promotion($id, $promotion){

    	$promotion['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	$promotion['user_modified_id'] = get_user_id();

    	$this->db->where('id', $id);
    	$this->db->update('bpv_promotions', $promotion);

    	$error_nr = $this->db->_error_number();
    	//$error_ms = $this->db->_error_message();

    	return !$error_nr;

    }

    function delete_promotion($id){

    	$promotion['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	$promotion['user_modified_id'] = get_user_id();
    	$promotion['deleted'] = DELETED;

    	$this->db->where('id', $id);
    	$this->db->update('bpv_promotions', $promotion);

    	$error_nr = $this->db->_error_number();
    	//$error_ms = $this->db->_error_message();

    	return !$error_nr;
    }

    function get_all_hotel_in_des($des_id){

    	if(empty($des_id)) return array();

    	$this->db->select('h.id, h.name');

    	$this->db->from('destination_hotels dh');

    	$this->db->join('hotels h','h.id = dh.hotel_id');

    	$this->db->where('dh.destination_id', $des_id);

    	$this->db->where('h.deleted !=', DELETED);

    	$this->db->where('h.status', STATUS_ACTIVE);

    	$this->db->order_by('h.position','asc');

    	$query = $this->db->get();

    	$results =  $query->result_array();

    	return $results;
    }


    function get_pro_hotels($pro_id, $destination_id){

    	$this->db->where('bpv_promotion_id', $pro_id);

    	//$this->db->where('destination_id', $destination_id);

    	$query = $this->db->get('bpv_promotion_hotels');

    	$results =  $query->result_array();

    	return $results;
    }

    function update_pro_hotels($pro_id, $destination_id, $hotels){

    	$hotel_id_in_des = array();

    	$hotel_in_des = $this->get_all_hotel_in_des($destination_id);

    	foreach ($hotel_in_des as $value){
    		$hotel_id_in_des[] = $value['id'];
    	}

    	if(count($hotel_id_in_des) > 0){

    		$this->db->where_in('hotel_id', $hotel_id_in_des);

    		$this->db->where('bpv_promotion_id', $pro_id);

    		$this->db->delete('bpv_promotion_hotels');

    	}

    	if(!empty($hotels)){

	    	foreach ($hotels as $hotel_id){

	    		$bpv_promotion_hotel['hotel_id'] = $hotel_id;

	    		$bpv_promotion_hotel['destination_id'] = $destination_id;

	    		$bpv_promotion_hotel['bpv_promotion_id'] = $pro_id;

	    		$this->db->insert('bpv_promotion_hotels', $bpv_promotion_hotel);

	    	}

    	}

    	$error_nr = $this->db->_error_number();
    	//$error_ms = $this->db->_error_message();

    	return !$error_nr;

    }

    function get_selected_pro_hotels($pro_id, $destination_id){

    	$ret = array();

    	$this->db->where('destination_id', $destination_id);

    	$this->db->where('bpv_promotion_id', $pro_id);

    	$query = $this->db->get('bpv_promotion_hotels');

    	$results =  $query->result_array();

    	foreach ($results as $value){

    		$ret[] = $value['hotel_id'];

    	}

    	return $ret;
    }

    function get_all_cruises() {

    	$this->db->where('deleted !=', DELETED);

    	$query = $this->db->get('cruises');

    	$results = $query->result_array();

    	return $results;
    }

    function get_pro_cruises($pro_id){

    	$this->db->where('bpv_promotion_id', $pro_id);

    	$query = $this->db->get('bpv_promotion_cruises');

    	$results =  $query->result_array();

    	return $results;
    }

    function update_pro_cruises($pro_id, $pro_cruise_post_data){

    	// delete old data
    	$this->db->where('bpv_promotion_id', $pro_id);

    	$this->db->delete('bpv_promotion_cruises');

    	// create new cruise promotions
    	if( !empty($pro_cruise_post_data) ){

    		foreach ($pro_cruise_post_data as $cruise){

    			$bpv_promotion_cruise['cruise_id'] = $cruise['cruise_id'];

    			$bpv_promotion_cruise['bpv_promotion_id'] = $pro_id;

    			$bpv_promotion_cruise['cruise_get'] = $cruise['cruise_get'];

    			$this->db->insert('bpv_promotion_cruises', $bpv_promotion_cruise);

    		}

    	}

    	$error_nr = $this->db->_error_number();
    	//$error_ms = $this->db->_error_message();

    	return !$error_nr;

    }

    /**
     * Khuyenpv 18.09.2014
     * Tour-Promotions Functions
     */
    function get_all_tour_in_des($des_id){

    	if(empty($des_id)) return array();

    	$this->db->select('t.id, t.name');

    	$this->db->from('destination_tours dt');

    	$this->db->join('tours t','t.id = dt.tour_id');

    	$this->db->where('dt.destination_id', $des_id);
    	//$this->db->where('dt.is_land_tour', STATUS_ACTIVE);

    	$this->db->where('t.deleted !=', DELETED);

    	$this->db->where('t.status', STATUS_ACTIVE);

    	//$this->db->where('t.cruise_id', STATUS_INACTIVE);

    	$this->db->order_by('t.position','asc');

    	$query = $this->db->get();

    	$results =  $query->result_array();

    	return $results;
    }


    function get_pro_tours($pro_id, $destination_id){

    	$this->db->where('bpv_promotion_id', $pro_id);

    	$this->db->where('destination_id', $destination_id);

    	$query = $this->db->get('bpv_promotion_tours');

    	$results =  $query->result_array();

    	return $results;
    }

    function update_pro_tours($pro_id, $destination_id, $pro_tour_post_data){

    	$tour_id_in_des = array();

    	$tour_in_des = $this->get_all_tour_in_des($destination_id);

    	foreach ($tour_in_des as $value){
    		$tour_id_in_des[] = $value['id'];
    	}

    	if(count($tour_id_in_des) > 0){

    		$this->db->where_in('tour_id', $tour_id_in_des);

    		$this->db->where('bpv_promotion_id', $pro_id);

    		$this->db->delete('bpv_promotion_tours');

    	}

    	if(!empty($pro_tour_post_data)){

    		foreach ($pro_tour_post_data as $pt){

    			$bpv_promotion_tour['tour_id'] = $pt['tour_id'];

    			$bpv_promotion_tour['destination_id'] = $destination_id;

    			$bpv_promotion_tour['bpv_promotion_id'] = $pro_id;

    			$bpv_promotion_tour['tour_get'] = $pt['tour_get'];

    			$this->db->insert('bpv_promotion_tours', $bpv_promotion_tour);

    		}

    	}

    	$error_nr = $this->db->_error_number();
    	//$error_ms = $this->db->_error_message();

    	return !$error_nr;

    }


    /*
     * TinVM 14/10/2014
     * Get multiple voucher for list customer
     */
    function get_new_added_vouchers()
    {
    	$this->db->select('code');

    	$this->db->where('status', VOUCHER_STATUS_NEW);

    	$this->db->where('expired_date', '2014-12-31');

    	$query = $this->db->get_where('vouchers');

    	return $query->result_array();

    }

    /**
     * Count all the voucher new created expired on 31-12-2014
     * TinVM 14.10.2014
     */
    function get_nr_voucher_new(){

    	$this->db->where('status', VOUCHER_STATUS_NEW);

    	$this->db->where('expired_date', '2014-12-31');


    	$nr_voucher_new = $this->db->count_all_results('vouchers');

    	return $nr_voucher_new;
    }


}