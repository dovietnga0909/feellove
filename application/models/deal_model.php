<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Deal_Model extends CI_Model {	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_hotel_top_des(){
		
		$this->db->select('id, name');
		
		$this->db->from('destinations');
		
		$this->db->where('is_top_hotel', STATUS_ACTIVE);
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->order_by('position','asc');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
	}
	
	function get_hotel_in_des_has_promotion($des_id, $limit=''){
		
		$today = date(DB_DATE_FORMAT); // today
		
		$this->db->select('h.id, h.name, h.url_title, h.star, h.address, h.description, h.picture, h.latitude, h.longitude, h.facilities');
		
		$this->db->from('destination_hotels as dh');
		
		$this->db->join('hotels as h','h.id = dh.hotel_id');
		
		$sql_cond = '(EXISTS (SELECT 1 FROM promotions as p WHERE p.hotel_id = h.id AND ';
		
		$sql_cond .= 'p.book_date_from <= "'.$today.'" AND p.book_date_to >= "'.$today.'"'.
					' AND p.stay_date_to >= "'.$today.'"'.
					' AND p.show_on_web = '.STATUS_ACTIVE.
					' AND p.deleted != '.DELETED;
		$sql_cond .= '))';
		
		$this->db->where($sql_cond);
		
		$this->db->where('dh.destination_id', $des_id);
		
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->where('h.status', STATUS_ACTIVE);
		
		if($limit != ''){
			
			$this->db->limit($limit);
			
		}

		$this->db->order_by('h.position','asc');
		
		$query = $this->db->get();
		
		$hotels = $query->result_array();
		
		$hotels = $this->get_promotion_of_hotels($hotels);
		
		return $hotels;
	}
	
	function get_promotion_of_hotels($hotels){
		
		$hotel_ids = array();
		
		foreach ($hotels as $value){
			
			$hotel_ids[] = $value['id'];
			
		}
		
		if(count($hotel_ids) > 0){
			
			$today = date(DB_DATE_FORMAT); // today
			
			// get promotions for list hotels
			$this->db->select('id, name, offer, stay_date_from, stay_date_to, check_in_on, book_date_from, book_date_to, hotel_id, discount_type, get_1, minimum_stay');
			
			$this->db->from('promotions');
			
			$sql_cond = 'book_date_from <= "'.$today.'" AND book_date_to >= "'.$today.'"'.
					' AND stay_date_to >= "'.$today.'"'.
					' AND show_on_web = '.STATUS_ACTIVE.
					' AND deleted != '.DELETED;
			
			$this->db->where($sql_cond);
			
			$this->db->where_in('hotel_id', $hotel_ids);
			
		
			$this->db->order_by('get_1', 'desc');
			
			$query = $this->db->get();
			
			$promotions = $query->result_array();
			
			$pro_ids = array();
			
			$dates = array();
			
			foreach ($promotions as $pro){
				$pro_ids[] = $pro['id'];
				$dates[] = $pro['stay_date_from'];
			}
			
			
			// get hotel price from for list of hotel
			if(count($pro_ids) > 0){
				
				$this->db->select('price_origin, price_from, hotel_id, promotion_id, date');
				
				$this->db->where_in('hotel_id', $hotel_ids);
				
				$this->db->where_in('promotion_id', $pro_ids);
				
				$this->db->where_in('date', $dates);
				
				$query = $this->db->get('hotel_price_froms');
					
				$price_froms = $query->result_array();
				
				// set for list of hotel
				
				foreach ($hotels as $key=>$hotel){
					
					$pros = array();
					
					$pro_h_ids = array();
					
					$pro_h_dates = array();
					
					foreach ($promotions as $pro){
						
						if($pro['hotel_id'] == $hotel['id']) {
							$pros[] = $pro;
							
							$pro_h_ids[] = $pro['id'];
							
							$pro_h_dates[] = $pro['stay_date_from'];
						}
						
					}
					
					$hotel['promotions'] = $pros;
					
					
					$p_f_s = array();
					
					foreach ($price_froms as $price_from){
						
						if($price_from['hotel_id'] == $hotel['id'] 
						&& (count($pro_h_ids) > 0) &&  in_array($price_from['promotion_id'], $pro_h_ids)
						&&in_array($price_from['date'], $pro_h_dates)) 
						{
							$p_f_s[] = $price_from;
						}
						
					}
					
					//if($hotel['id'] == 118) {print_r($p_f_s);exit();}
					
					// find min price-from
					if(count($p_f_s) > 0){
						$min_pf = $p_f_s[0];
						
						foreach ($p_f_s as $pf){
							
							if($min_pf['price_from'] > $pf['price_from']){
								
								$min_pf = $pf;
								
							}
							
						}
						
						
						$hotel['price_origin'] = $min_pf['price_origin'];
						
						$hotel['price_from'] = $min_pf['price_from'];
						
						$hotel['stay_date_from'] = $min_pf['date'];
						
					}
					
					$hotels[$key] = $hotel;
					
				}
				
			}
			
		}
		
		return $hotels;
	}
	
	function get_important_facilities(){
		$this->db->where('deleted !=', DELETED);
		$this->db->where('is_important', STATUS_ACTIVE);
		$this->db->where('status', STATUS_ACTIVE);
		$query = $this->db->get('facilities');
		
		$results = $query->result_array();
		
		return $results;
	}
	
	function get_hotel_bpv_promotion($code, $hotel_id){
		
		$today = date(DB_DATE_FORMAT);
		
		$this->db->select('b.*');
		
		$this->db->from('bpv_promotion_hotels bh');
		
		$this->db->join('bpv_promotions b','b.id = bh.bpv_promotion_id');
		
		$this->db->where('bh.hotel_id', $hotel_id);
		
		$code_sql = '(b.code = \''.$code . '\' OR b.code LIKE \'%-'.$code.'-%\')';
		
		$this->db->where($code_sql);
		
		$this->db->where('b.status', STATUS_ACTIVE);
		
		$this->db->where('b.deleted !=', DELETED);
		
		$this->db->where('b.expired_date >=', $today);
		
		$query = $this->db->get('bpv_promotions');
		
		$results = $query->result_array();
		
		if(count($results) > 0){
		
			return $results[0];
				
		}
		
		return FALSE;
		
	}
	
	function get_voucher($code){
		
		$today = date(DB_DATE_FORMAT);
		
		$this->db->select('id, code, amount');
		$this->db->where('code', $code);
		$this->db->where('status', 0); // not been used (NEW status)
		$this->db->where('expired_date >=', $today);
		
		$query = $this->db->get('vouchers');
		
		$results = $query->result_array();
		
		if(count($results) > 0){

			return $results[0];
			
		}
		
		return FALSE;
	}
	
	function get_bpv_promotion($code){
	
		$today = date(DB_DATE_FORMAT);
		
		$code_sql = '(code = \''.$code . '\' OR code LIKE \'%-'.$code.'-%\')';
		
		$this->db->where($code_sql);
	
		$this->db->where('status', STATUS_ACTIVE);
	
		$this->db->where('deleted !=', DELETED);
	
		$this->db->where('expired_date >=', $today);
	
		$query = $this->db->get('bpv_promotions');
	
		$results = $query->result_array();
	
		if(count($results) > 0){
	
			return $results[0];
	
		}
	
		return FALSE;
	
	}

	function get_cruise_bpv_promotion($code, $cruise_id){
	
		$today = date(DB_DATE_FORMAT);
	
		$this->db->select('b.*, bh.cruise_get as specific_cruise_get');
	
		$this->db->from('bpv_promotion_cruises bh');
	
		$this->db->join('bpv_promotions b','b.id = bh.bpv_promotion_id');
	
		$this->db->where('bh.cruise_id', $cruise_id);
	
		$code_sql = '(b.code = \''.$code . '\' OR b.code LIKE \'%-'.$code.'-%\')';
		
		$this->db->where($code_sql);
	
		$this->db->where('b.status', STATUS_ACTIVE);
	
		$this->db->where('b.deleted !=', DELETED);
	
		$this->db->where('b.expired_date >=', $today);
	
		$query = $this->db->get('bpv_promotions');
	
		$results = $query->result_array();
	
		if(count($results) > 0){
	
			return $results[0];
	
		}
	
		return FALSE;
	
	}
	
	/**
	 * Get Tour Promotion code
	 * Khuyenpv: 17.09.2014
	 */
	function get_tour_bpv_promotion($code, $tour_id){
	
		$today = date(DB_DATE_FORMAT);
	
		$this->db->select('b.*, bt.tour_get as specific_tour_get');
	
		$this->db->from('bpv_promotion_tours bt');
	
		$this->db->join('bpv_promotions b','b.id = bt.bpv_promotion_id');
	
		$this->db->where('bt.tour_id', $tour_id);
	
		$code_sql = '(b.code = \''.$code . '\' OR b.code LIKE \'%-'.$code.'-%\')';
	
		$this->db->where($code_sql);
	
		$this->db->where('b.status', STATUS_ACTIVE);
	
		$this->db->where('b.deleted !=', DELETED);
	
		$this->db->where('b.expired_date >=', $today);
		
		$this->db->order_by('bt.tour_get','desc');
	
		$query = $this->db->get('bpv_promotions');
	
		$results = $query->result_array();
	
		if(count($results) > 0){
	
			return $results[0];
	
		}
	
		return FALSE;
	
	}
	
	/**
	 * is_pro_code_available
	 *
	 * @author toanlk
	 * @since  May 27, 2015
	 */
	function is_pro_code_available($code, $phone, $bpv_promotion_id) {

	    $this->db->select('*');
	    
	    $this->db->join('customers c','c.id = bpu.customer_id');
	    
	    $this->db->where('bpu.code', trim($code));
	    
	    $this->db->where('c.phone', trim($phone));
	    
	    $this->db->where('bpu.bpv_promotion_id', $bpv_promotion_id);
	    
		$results = $this->db->count_all_results('bpv_promotion_users bpu');
		
	    if($results > 0){
	    
	        return FALSE;
	    
	    }
	    
	    return TRUE;
	}
}
