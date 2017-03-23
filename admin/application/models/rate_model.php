<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rate_Model extends CI_Model {	

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();	
        
		$this->load->database();
		$this->load->helper('rate');
    }
    
    function get_room_types($hotel_id){
    	
    	$this->db->select('id, name, max_occupancy, max_extra_beds, max_children');
    	
    	$this->db->where('hotel_id',$hotel_id);
    	    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->order_by('id', 'asc');
    	
    	$query = $this->db->get('room_types');
    	
    	$rerults = $query->result_array();
    	
    	return $rerults;
    	
    }
    
    function change_room_rates($hotel_id, $change_rates){
    	
    	$changed_rate_dates = array();
    	
    	$start_date = $change_rates['start_date'];
    	
    	$end_date = $change_rates['end_date'];
    	
    	$rate_dates = get_days_between_2_dates($start_date, $end_date);
    	
    	$room_types = $change_rates['room_types'];
    	
    	$week_days = $change_rates['week_day'];
    	
    	if(count($rate_dates)> 0 && count($room_types) > 0 && count($week_days) > 0){
    		
    		foreach ($rate_dates as $date){
    			
    			$wd = date('w', strtotime($date));
    			
    			if(in_array($wd, $week_days)){
    			
	    			foreach ($room_types as $room_type_id){
						
	    				
	    				$room_rate['hotel_id'] = $hotel_id;
	    				
	    				$room_rate['room_type_id'] = $room_type_id;
	    				
	    				$room_rate['date'] = $date;
	    				
	    				if(!empty($change_rates['full_occupancy'])){
	    					
	    					$room_rate['full_occupancy_rate'] = $change_rates['full_occupancy'];
	    					
	    				}
	    				
	    				if(!empty($change_rates['triple'])){
	    					
	    					$room_rate['triple_rate'] = $change_rates['triple'];
	    				}
	    				
	    				if(!empty($change_rates['double'])){
	    					
	    					$room_rate['double_rate'] = $change_rates['double'];
	    				}
	    				
	    				if(!empty($change_rates['single'])){
	    					
	    					$room_rate['single_rate'] = $change_rates['single'];
	    				}
	    				
	    				if(!empty($change_rates['extra_bed'])){
	    					
	    					$room_rate['extra_bed_rate'] = $change_rates['extra_bed'];
	    				}
	    				
	    				$room_rate['has_surcharge'] = $this->rate_has_surcharge($hotel_id, $date);
	    				
	    				$this->create_or_update_room_rate($room_rate);
	    				
	    			}
	    			
	    			$changed_rate_dates[] = $date;
    			}
    			
    		}
    		
    		// update hotel price from
    		$this->update_hotel_price_from($hotel_id, $changed_rate_dates);
    		
    	}
    	
    }
    
    function update_room_rate_in_range($room_rate, $dates){
    	
    	$room_rate['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	$room_rate['user_modified_id'] = get_user_id();
    	
    	$this->db->where('room_type_id', $room_rate['room_type_id']);
    	$this->db->where('hotel_id', $room_rate['hotel_id']);
    	$this->db->where_in('date', $dates);
    	
    	$this->db->update('room_rates', $room_rate);
    }
    
    function create_room_rate($room_rate){
    	$room_rate['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$room_rate['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    	$login_user_id = get_user_id();
    	$room_rate['user_created_id'] = $login_user_id;
    	$room_rate['user_modified_id'] = $login_user_id;
    	
    	$this->db->insert('room_rates', $room_rate);
    }
    
    function delete_room_rate_in_range($room_rate, $dates){
    	
    	$this->db->where('room_type_id', $room_rate['room_type_id']);
    	$this->db->where('hotel_id', $room_rate['hotel_id']);
    	$this->db->where_in('date', $dates);
    	 
    	$this->db->delete('room_rates');
    }
    
    
    function create_or_update_room_rate($room_rate, $is_edit = true){
		
    	$this->db->where('room_type_id', $room_rate['room_type_id']);
    	$this->db->where('hotel_id', $room_rate['hotel_id']);
    	$this->db->where('date', $room_rate['date']);
    	
    	$cnt = $this->db->count_all_results('room_rates');
    	
    	if($cnt == 0){
    		$room_rate['date_created'] = date(DB_DATE_TIME_FORMAT);
    		$room_rate['date_modified'] = date(DB_DATE_TIME_FORMAT);
    		
    		$login_user_id = get_user_id();
    		$room_rate['user_created_id'] = $login_user_id;
    		$room_rate['user_modified_id'] = $login_user_id;
    		
    		$this->db->insert('room_rates', $room_rate);
    	} else {
    		
    		if($is_edit){
    		
	    		$room_rate['date_modified'] = date(DB_DATE_TIME_FORMAT);
	    		$room_rate['user_modified_id'] = get_user_id();
	    		
	    		$this->db->where('room_type_id', $room_rate['room_type_id']);
		    	$this->db->where('hotel_id', $room_rate['hotel_id']);
		    	$this->db->where('date', $room_rate['date']);
		    	
		    	$this->db->update('room_rates', $room_rate);
	    	
    		}
    		
    	}
    	
    }
    
    function get_room_rate_in_range($room_type_ids, $start_date, $end_date){
    	
    	if(count($room_type_ids) == 0) return array();
    	
    	$start_date = date(DB_DATE_FORMAT, strtotime($start_date));
    	$end_date = date(DB_DATE_FORMAT, strtotime($end_date));
    	
    	$this->db->where_in('room_type_id', $room_type_ids);
    	$this->db->where('date >=', $start_date);
    	$this->db->where('date <=', $end_date);
    	
    	$query = $this->db->get('room_rates');
    	
    	return $query->result_array();
    	
    }
    
    function rate_has_surcharge($hotel_id, $date){
    	
    	$date = date(DB_DATE_FORMAT, strtotime($date));
    	
    	$this->db->where('deleted !=', DELETED);
    	$this->db->where('hotel_id', $hotel_id);    	
    	$this->db->where('start_date <= ', $date);
    	$this->db->where('end_date >=', $date);
    	$this->db->where('week_day &'.pow(2,date('w',strtotime($date))).' > 0');
    	
    	$cnt = $this->db->count_all_results('surcharges');
    	
    	return $cnt > 0;
    }
    
    function get_hotel_surcharge_in_date($hotel_id, $date){

    	$date = date(DB_DATE_FORMAT, strtotime($date));
    	
    	$this->db->where('deleted !=', DELETED);
    	$this->db->where('hotel_id', $hotel_id);    	
    	$this->db->where('start_date <= ', $date);
    	$this->db->where('end_date >=', $date);
    	$this->db->where('week_day &'.pow(2,date('w',strtotime($date))).' >', 0);
    	
    	$query = $this->db->get('surcharges');
    	
    	return $query->result_array();
    }
    
    function update_hotel_rate_has_surcharge($hotel_id){
    	
    	// reset has_surchage flag
    	$this->db->where('hotel_id', $hotel_id);
    	$this->db->set('has_surcharge', STATUS_INACTIVE);
    	$this->db->update('room_rates');
    	
    	// get all hotel surcharges
    	$this->db->where('hotel_id', $hotel_id);
    	$this->db->where('deleted != ', DELETED);
    	$query = $this->db->get('surcharges');
    	$surcharges = $query->result_array();
    	
    	foreach ($surcharges as $sur){
    		
    		$start_date = $sur['start_date'];
    		
    		$end_date = $sur['end_date'];
    	
	    	$this->db->select('id, hotel_id, date, has_surcharge');
	    	
	    	$this->db->where('hotel_id', $hotel_id);
	    	
	    	$this->db->where('date >=', $start_date);
	    	$this->db->where('date <=', $end_date);
	    	
	    	$query = $this->db->get('room_rates');
	    	
	    	$results = $query->result_array();
	    	
	    	foreach ($results as $value){
	    		 
	    		$has_surcharge = $this->rate_has_surcharge($hotel_id, $value['date']);
	    		 
	    		$this->db->set('has_surcharge', $has_surcharge);
	    		$this->db->where('id', $value['id']);
	    		$this->db->update('room_rates');
	    		 
	    	}
    	}
    }
    
    function update_hotel_price_from($hotel_id, $changed_rate_dates){
    	
    	if(count($changed_rate_dates) == 0) return null;
    	
    	// 1. delete the old price from
    	$this->db->where('hotel_id', $hotel_id);
    	
    	$this->db->where_in('date', $changed_rate_dates);
    	
    	$this->db->delete('hotel_price_froms');
	
    	// 2. then create new price from
    	
    	$start_date = $changed_rate_dates[0];
    	$end_date = $changed_rate_dates[count($changed_rate_dates) - 1];
    	
    	$promotions = $this->_get_hotel_promotions_in_date_range($hotel_id, $start_date, $end_date);
    	
    	$room_type_ids = $this->_get_room_type_ids($hotel_id);
    	
    	if(empty($room_type_ids)) return null;
    	
    	
    	$chunk_dates = array_chunk($changed_rate_dates, 60);
    	
    	foreach ($chunk_dates as $changed_dates){
    	
	    	$room_rates = $this->_get_room_rates_in_date_range($room_type_ids, $changed_dates);
	    	
	    	
	    	foreach ($changed_dates as $date){
	    		
	    		$room_rates_in_date = get_room_rates_in_date($room_rates, $date);
	    		
	    		$promotions_in_date = get_promotions_in_date($promotions, $date);
	    		
	    		if(count($room_rates_in_date) > 0){
	    			
	    			
	    			// no promotion -> create hotel price from room_rates
	    			if(count($promotions_in_date) == 0){
	    		
	    				$min_rate = get_minimum_room_rate($room_rates_in_date);
	    		
	    				if(!empty($min_rate) && $min_rate['price_from'] > 0){
	    						
	    					$hotel_price_from['hotel_id'] = $hotel_id;
	    						
	    					$hotel_price_from['date'] = $date;
	    						
	    					$hotel_price_from['room_type_id'] = $min_rate['room_type_id'];
	    						
	    					$hotel_price_from['price_origin'] = $min_rate['price_origin'];
	    						
	    					$hotel_price_from['price_from'] = $min_rate['price_from'];
	    					
	    					$hotel_price_from['range_index'] = get_range_index($min_rate['price_from']);
	    						
	    					$this->db->insert('hotel_price_froms', $hotel_price_from);
	    						
	    				}
	    		
	    			} else {
	    				 
	    				foreach ($promotions_in_date as $promotion){
	    						
	    					$min_rate = get_minimum_room_rate($room_rates_in_date, $promotion);
	    						
	    					if(!empty($min_rate) && $min_rate['price_from'] > 0){
	    		
	    						$hotel_price_from['hotel_id'] = $hotel_id;
	    		
	    						$hotel_price_from['date'] = $date;
	    							
	    						$hotel_price_from['promotion_id'] = $promotion['id'];
	    		
	    						$hotel_price_from['room_type_id'] = $min_rate['room_type_id'];
	    		
	    						$hotel_price_from['price_origin'] = $min_rate['price_origin'];
	    		
	    						$hotel_price_from['price_from'] = $min_rate['price_from'];
	    						
	    						$hotel_price_from['range_index'] = get_range_index($min_rate['price_from']);
	    		
	    						$this->db->insert('hotel_price_froms', $hotel_price_from);
	    		
	    					}
	    						
	    				}
	    			}
	    		
	    		}
	    		
	    		
	    	}
	    	
    	}
    	
    }
    
    function _get_hotel_promotions_in_date_range($hotel_id, $start_date, $end_date){
    	
    	$this->db->select('id, name, stay_date_from, stay_date_to, check_in_on, discount_type, apply_on, get_1, get_2, get_3, get_4, get_5, get_6, get_7, room_type, minimum_stay');
    	
    	$this->db->where('hotel_id', $hotel_id);
 
    	$this->db->where('stay_date_from <=', $end_date);
    	
    	$this->db->where('stay_date_to >=', $start_date);
    	
    	//$this->db->where('check_in_on &'.pow(2,date('w',strtotime($date))).' >', 0);
    
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->order_by('id','asc');

    	$query = $this->db->get('promotions');
    	
    	$results = $query->result_array();
    	
    	foreach ($results as $key=>$value){
    		
    		$value['room_types'] = $this->_get_promotion_room_types($value['id']);
    		
    		$results[$key] = $value;
    	}
    	
    	return $results;
    	
    }
    
    function _get_promotion_room_types($promotion_id){
    	
    	$this->db->where('promotion_id', $promotion_id);
    	
    	$query = $this->db->get('promotion_room_types');
    	 
    	$results = $query->result_array();
    	
    	return $results;
    	
    }
    
    function _get_room_rates_in_date_range($room_type_ids, $changed_rate_dates){
    
    	$this->db->select('room_type_id, hotel_id, date, full_occupancy_rate, triple_rate, double_rate, single_rate');
    	 
    	$this->db->where_in('date', $changed_rate_dates);
    	
    	$this->db->where_in('room_type_id', $room_type_ids);
    	 
    	$query = $this->db->get('room_rates');
    	 
    	$results = $query->result_array();
    	
    	return $results;
    }
    
    function _get_room_type_ids($hotel_id){
    	
    	$ret = array();
    
    	$this->db->select('id');
    
    	$this->db->where('hotel_id', $hotel_id);
    	 
    	$this->db->where('status', STATUS_ACTIVE);
    	
    	$this->db->where('deleted !=', DELETED);
    
    	$query = $this->db->get('room_types');
    
    	$results = $query->result_array();
    	
    	foreach ($results as $value){
    		$ret[] = $value['id'];
    	}
    	 
    	return $ret;
    }
    
    function create_hr_action($hr_action){
    	
    	$rras = $hr_action['rras'];
    	
    	$login_user_id = get_user_id();
    	 
    	$hr_action['user_created_id'] = $login_user_id;
    	$hr_action['user_modified_id'] = $login_user_id;
    	 
    	$hr_action['date_created'] = date(DB_DATE_TIME_FORMAT);
    	$hr_action['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	
    	unset($hr_action['rras']);
    	
    	$this->db->insert('hotel_rate_actions', $hr_action);
    	
    	$hotel_rate_action_id = $this->db->insert_id();
    	
    	foreach ($rras as $value){
    		$value['hotel_rate_action_id'] = $hotel_rate_action_id;
    		$this->db->insert('hotel_room_rate_actions', $value);
    	}
    	
    	$this->update_room_rates_by_action($hr_action, $rras);
    	
    	return true;
    }
    
    function update_hr_action($id, $hr_action){
    	 
    	$rras = $hr_action['rras'];
    	 
    	$login_user_id = get_user_id();
   
    	$hr_action['user_modified_id'] = $login_user_id;
    	$hr_action['date_modified'] = date(DB_DATE_TIME_FORMAT);
    	 
    	unset($hr_action['rras']);
		
    	$this->db->where('id', $id);
    	$this->db->update('hotel_rate_actions', $hr_action);
    	
    	
    	$this->db->where('hotel_rate_action_id', $id);
    	$this->db->delete('hotel_room_rate_actions');
    	 
    	foreach ($rras as $value){
    		$value['hotel_rate_action_id'] = $id;
    		$this->db->insert('hotel_room_rate_actions', $value);
    	}
    	
    	$this->update_room_rates_by_action($hr_action, $rras);
    	 
    	return true;
    }
    
    
    function get_all_hr_actions($hotel_id){
    	
    	$this->db->select('hr.id, hr.hotel_id, hr.start_date, hr.end_date, hr.week_day, hr.date_created, hr.date_modified, hr.user_created_id, hr.user_modified_id, u.username as last_modified_by');
    	
    	$this->db->where('hr.hotel_id', $hotel_id);
    	
    	$this->db->join('users u', 'u.id = hr.user_modified_id', 'left outer');
    	
    	$this->db->order_by('hr.id');
    	
    	$query = $this->db->get('hotel_rate_actions hr');
    	
    	$results = $query->result_array();
    	 
    	return $results;
    }
    
    function get_hra($id){
    	
    	$this->db->where('id', $id);
    	 
    	$this->db->order_by('id');
    	 
    	$query = $this->db->get('hotel_rate_actions');
    	 
    	$results = $query->result_array();
    	
    	if(count($results) > 0){
    		
    		$hra = $results[0];
    		
    		$hra['rras'] = $this->get_rras($id);
    		
    		return $hra;
    	}
    	
    	return '';
    }
    
    function get_rras($hotel_rate_action_id){
    	
    	$this->db->where('hotel_rate_action_id', $hotel_rate_action_id);
    	
    	$query = $this->db->get('hotel_room_rate_actions');
    	
    	$results = $query->result_array();
    	
    	return $results;
    }
    
    function delete_hra($id){
    	$this->db->where('id', $id);
    	$this->db->delete('hotel_rate_actions');
    	
    	$this->db->where('hotel_rate_action_id', $id);
    	$this->db->delete('hotel_room_rate_actions');
    	return true;
    }
    
    function update_room_rates_by_action($hra, $rras){
    
    	$changed_rate_dates = array();
    	 
    	$start_date = $hra['start_date'];
    	 
    	$end_date = $hra['end_date'];
    	 
    	$rate_dates = get_days_between_2_dates($start_date, $end_date);
    	 
    	$hotel_id = $hra['hotel_id'];
    	 
    	$week_day = $hra['week_day'];
    	
    	if(count($rate_dates)> 0 && count($rras) > 0 && $week_day > 0){
    	
    		foreach ($rate_dates as $date){
    			 
    			$wd = date('w', strtotime($date));
    			 
    			if(is_bit_value_contain($week_day, $wd)){
    				 
    				$changed_rate_dates[] = $date;
    			}
    			 
    		}
    		
    		if(count($changed_rate_dates) > 0){
    			$t1 = microtime(true);
    			foreach ($rras as $rra){
    				 
    				$room_rate['hotel_id'] = $hotel_id;
    			
    				$room_rate['room_type_id'] = $rra['room_type_id'];
    		
    				$room_rate['full_occupancy_rate'] = $rra['full_occupancy_rate'];
    			
    				$room_rate['triple_rate'] = $rra['triple_rate'];
    			
    				$room_rate['double_rate'] = $rra['double_rate'];
    			
    				$room_rate['single_rate'] = $rra['single_rate'];
    			
    				$room_rate['extra_bed_rate'] = $rra['extra_bed_rate'];
    				
    				// net rate
    				$room_rate['full_occupancy_net'] = $rra['full_occupancy_net'];
    				 
    				$room_rate['triple_net'] = $rra['triple_net'];
    				 
    				$room_rate['double_net'] = $rra['double_net'];
    				 
    				$room_rate['single_net'] = $rra['single_net'];
    			
    				//$this->update_room_rate_in_range($room_rate, $changed_rate_dates);
    				
    				$this->delete_room_rate_in_range($room_rate, $changed_rate_dates);
    			
    			}
    			
    			$t2 = microtime(true);
    			
    			foreach ($changed_rate_dates as $date){
    				
    				foreach ($rras as $rra){
    						
    					$room_rate['hotel_id'] = $hotel_id;
    					 
    					$room_rate['room_type_id'] = $rra['room_type_id'];
    					
    					$room_rate['date'] = $date;
    				
    					$room_rate['full_occupancy_rate'] = $rra['full_occupancy_rate'];
    					 
    					$room_rate['triple_rate'] = $rra['triple_rate'];
    					 
    					$room_rate['double_rate'] = $rra['double_rate'];
    					 
    					$room_rate['single_rate'] = $rra['single_rate'];
    					 
    					$room_rate['extra_bed_rate'] = $rra['extra_bed_rate'];
    					
    					// net rate
    					$room_rate['full_occupancy_net'] = $rra['full_occupancy_net'];
    						
    					$room_rate['triple_net'] = $rra['triple_net'];
    						
    					$room_rate['double_net'] = $rra['double_net'];
    						
    					$room_rate['single_net'] = $rra['single_net'];
    					 
    					//$this->create_or_update_room_rate($room_rate, false);
    					
    					$this->create_room_rate($room_rate);
    					 
    				}
    				
    			}
    			
    			$t3 = microtime(true);
  
    			
    		}
    		
    		// update hotel price from
    		$this->update_hotel_price_from($hotel_id, $changed_rate_dates);
    		
    		$t4 = microtime(true);
    		
    		/*echo 'Time 1 = '.($t2 - $t1).'<br>';
    		echo 'Time 2 = '.($t3 - $t2).'<br>';
    		echo 'Time 3 = '.($t4 - $t3).'<br>'; exit();*/
    	}
    	
    }
   
}