<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Tour_Rate_Model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
        $this->load->helper('rate');
        $this->load->config('tour_rate_meta');
    }

    function get_accommodations($tour_id)
    {
        $this->db->select('id, name');
        
        $this->db->where('tour_id', $tour_id);
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->order_by('id', 'asc');
        
        $query = $this->db->get('accommodations');
        
        $rerults = $query->result_array();
        
        return $rerults;
    }

    function change_room_rates($tour_id, $change_rates)
    {
        $changed_rate_dates = array();
        
        $start_date = $change_rates['start_date'];
        
        $end_date = $change_rates['end_date'];
        
        $rate_dates = get_days_between_2_dates($start_date, $end_date);
        
        $room_types = $change_rates['room_types'];
        
        $week_days = $change_rates['week_day'];
        
        if (count($rate_dates) > 0 && count($room_types) > 0 && count($week_days) > 0)
        {
            
            foreach ($rate_dates as $date)
            {
                
                $wd = date('w', strtotime($date));
                
                if (in_array($wd, $week_days))
                {
                    
                    foreach ($room_types as $room_type_id)
                    {
                        
                        $room_rate['tour_id'] = $tour_id;
                        
                        $room_rate['room_type_id'] = $room_type_id;
                        
                        $room_rate['date'] = $date;
                        
                        if (! empty($change_rates['full_occupancy']))
                        {
                            
                            $room_rate['full_occupancy_rate'] = $change_rates['full_occupancy'];
                        }
                        
                        if (! empty($change_rates['triple']))
                        {
                            
                            $room_rate['triple_rate'] = $change_rates['triple'];
                        }
                        
                        if (! empty($change_rates['double']))
                        {
                            
                            $room_rate['double_rate'] = $change_rates['double'];
                        }
                        
                        if (! empty($change_rates['single']))
                        {
                            
                            $room_rate['single_rate'] = $change_rates['single'];
                        }
                        
                        if (! empty($change_rates['extra_bed']))
                        {
                            
                            $room_rate['extra_bed_rate'] = $change_rates['extra_bed'];
                        }
                        
                        $room_rate['has_surcharge'] = $this->rate_has_surcharge($tour_id, $date);
                        
                        $this->create_or_update_room_rate($room_rate);
                    }
                    
                    $changed_rate_dates[] = $date;
                }
            }
            
            // update tour price from
            $this->update_tour_price_from($tour_id, $changed_rate_dates);
        }
    }

    function update_room_rate_in_range($room_rate, $dates)
    {
        
        $room_rate['date_modified'] = date(DB_DATE_TIME_FORMAT);
        $room_rate['user_modified_id'] = get_user_id();
        
        $this->db->where('room_type_id', $room_rate['room_type_id']);
        $this->db->where('tour_id', $room_rate['tour_id']);
        $this->db->where_in('date', $dates);
        
        $this->db->update('room_rates', $room_rate);
        
    }

    function create_tour_rate($tour_rates, $tour_departures)
    {
        
        $room_rate['date_created'] = date(DB_DATE_TIME_FORMAT);
        $room_rate['date_modified'] = date(DB_DATE_TIME_FORMAT);
        
        $room_rate['user_created_id'] = get_user_id();
        $room_rate['user_modified_id'] = get_user_id();
        
        if (! empty($tour_departures))
        {
            foreach ($tour_departures as $value)
            {
                $tour_rates['tour_departure_id'] = $value;
                $this->db->insert('tour_rates', $tour_rates);
            }
        }
        else
        {
            $this->db->insert('tour_rates', $tour_rates);
        }
   
    }

    function delete_tour_rate_in_range($room_rate, $dates, $tour_departures = null) {
    	
    	if (! empty ( $tour_departures )) {
			$this->db->where_in ( 'tour_departure_id', $tour_departures );
		}
    	
    	$this->db->where ( 'accommodation_id', $room_rate ['accommodation_id'] );
    	$this->db->where ( 'tour_id', $room_rate ['tour_id'] );
    	$this->db->where_in ( 'date', $dates );
    		
    	$this->db->delete ( 'tour_rates' );
		
	}

    function create_or_update_room_rate($room_rate, $is_edit = true)
    {
        $this->db->where('room_type_id', $room_rate['room_type_id']);
        $this->db->where('tour_id', $room_rate['tour_id']);
        $this->db->where('date', $room_rate['date']);
        
        $cnt = $this->db->count_all_results('room_rates');
        
        if ($cnt == 0)
        {
            $room_rate['date_created'] = date(DB_DATE_TIME_FORMAT);
            $room_rate['date_modified'] = date(DB_DATE_TIME_FORMAT);
            
            $login_user_id = get_user_id();
            $room_rate['user_created_id'] = $login_user_id;
            $room_rate['user_modified_id'] = $login_user_id;
            
            $this->db->insert('room_rates', $room_rate);
        }
        else
        {
            
            if ($is_edit)
            {
                
                $room_rate['date_modified'] = date(DB_DATE_TIME_FORMAT);
                $room_rate['user_modified_id'] = get_user_id();
                
                $this->db->where('room_type_id', $room_rate['room_type_id']);
                $this->db->where('tour_id', $room_rate['tour_id']);
                $this->db->where('date', $room_rate['date']);
                
                $this->db->update('room_rates', $room_rate);
            }
        }
    }

    function get_tour_rate_in_range($accommodation_ids, $start_date, $end_date)
    {
        if (count($accommodation_ids) == 0)
            return array();
        
        $start_date = date(DB_DATE_FORMAT, strtotime($start_date));
        $end_date = date(DB_DATE_FORMAT, strtotime($end_date));
        
        $this->db->where_in('accommodation_id', $accommodation_ids);
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        
        $query = $this->db->get('tour_rates');
        
        return $query->result_array();
    }

    function rate_has_surcharge($tour_id, $date)
    {
        $date = date(DB_DATE_FORMAT, strtotime($date));
        
        $this->db->where('deleted !=', DELETED);
        $this->db->where('tour_id', $tour_id);
        $this->db->where('start_date <= ', $date);
        $this->db->where('end_date >=', $date);
        $this->db->where('week_day &' . pow(2, date('w', strtotime($date))) . ' > 0');
        
        $cnt = $this->db->count_all_results('surcharges');
        
        return $cnt > 0;
    }

    function get_tour_surcharge_in_date($tour_id, $date)
    {
        $date = date(DB_DATE_FORMAT, strtotime($date));
        
        $this->db->where('deleted !=', DELETED);
        $this->db->where('tour_id', $tour_id);
        $this->db->where('start_date <= ', $date);
        $this->db->where('end_date >=', $date);
        $this->db->where('week_day &' . pow(2, date('w', strtotime($date))) . ' >', 0);
        
        $query = $this->db->get('surcharges');
        
        return $query->result_array();
    }

    function update_tour_rate_has_surcharge($tour_id)
    {
        
        // reset has_surchage flag
        $this->db->where('tour_id', $tour_id);
        $this->db->set('has_surcharge', STATUS_INACTIVE);
        $this->db->update('tour_rates');
        
        // get all tour surcharges
        $this->db->where('tour_id', $tour_id);
        $query = $this->db->get('surcharge_tours');
        $surcharges = $query->result_array();
        
        foreach ($surcharges as $sur)
        {
            
            $start_date = $sur['start_date'];
            
            $end_date = $sur['end_date'];
            
            $this->db->select('id, tour_id, date, has_surcharge');
            
            $this->db->where('tour_id', $tour_id);
            
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
            
            $query = $this->db->get('tour_rates');
            
            $results = $query->result_array();
            
            foreach ($results as $value)
            {
                
                $has_surcharge = $this->rate_has_surcharge($tour_id, $value['date']);
                
                $this->db->set('has_surcharge', $has_surcharge);
                $this->db->where('id', $value['id']);
                $this->db->update('tour_rates');
            }
        }
    }

    function update_tour_price_from($tour_id, $changed_rate_dates, $tour_departures = null)
    {
        
        $this->db->trans_start();
        
        if (count($changed_rate_dates) == 0)
            return null;
            
        // 1. delete the old price from
        $this->db->where('tour_id', $tour_id);
        
        $this->db->where_in('date', $changed_rate_dates);
        
        $this->db->delete('tour_price_froms');
        
        // 2. then create new price from
        
        $start_date = $changed_rate_dates[0];
        $end_date = $changed_rate_dates[count($changed_rate_dates) - 1];
        
        $promotions = $this->_get_tour_promotions_in_date_range($tour_id, $start_date, $end_date);
        
        // 3. Check accommodations
        $accommodation_ids = $this->_get_accommodation_ids($tour_id);
        
        if (empty($accommodation_ids))
            return null;
        
        $chunk_dates = array_chunk($changed_rate_dates, 60);
        
        // 4. Get list date changes
        foreach ($chunk_dates as $changed_dates)
        {
            
            $accommodations = $this->_get_accommodations_in_date_range($accommodation_ids, $changed_dates);
            
            foreach ($changed_dates as $date)
            {
                
                $accommodations_in_date = get_room_rates_in_date($accommodations, $date);
                
                // ignore empty tour rates
                if (empty($accommodations_in_date))
                {
                    continue;
                }
                
                $promotions_in_date = get_cruise_promotions_in_date($promotions, $date);
                
                // 5. no promotion -> create tour price from
                if (empty($promotions_in_date))
                {
                    
                    $min_rate = get_minimum_accommodation_rate($accommodations_in_date);
                    
                    if (! empty($min_rate) && $min_rate['price_from'] > 0)
                    {
                        
                        $tour_price_from['tour_id'] = $tour_id;
                        
                        $tour_price_from['date'] = $date;
                        
                        $tour_price_from['accommodation_id'] = $min_rate['accommodation_id'];
                        
                        $tour_price_from['price_origin'] = $min_rate['price_origin'];
                        
                        $tour_price_from['price_from'] = $min_rate['price_from'];
                        
                        $tour_price_from['range_index'] = get_range_index($min_rate['price_from']);
                        
                        $tour_price_from['mid_range_index'] = get_tour_range_index($min_rate['price_from']);
                        
                        if (! empty($tour_departures))
                        {
                            foreach ($tour_departures as $value)
                            {
                                $tour_price_from['tour_departure_id'] = $value;
                                $this->db->insert('tour_price_froms', $tour_price_from);
                            }
                        }
                        else
                        {
                            $this->db->insert('tour_price_froms', $tour_price_from);
                        }
                        
                    }
                }
                else // 6. has promotion
                {
                    
                    foreach ($promotions_in_date as $promotion)
                    {
                        
                        $min_rate = get_minimum_accommodation_rate($accommodations_in_date, $promotion);
                        
                        if (! empty($min_rate) && $min_rate['price_from'] > 0)
                        {
                            
                            $tour_price_from['promotion_id'] = $promotion['id'];
                            
                            $tour_price_from['tour_id'] = $tour_id;
                            
                            $tour_price_from['date'] = $date;
                            
                            $tour_price_from['accommodation_id'] = $min_rate['accommodation_id'];
                            
                            $tour_price_from['price_origin'] = $min_rate['price_origin'];
                            
                            $tour_price_from['price_from'] = $min_rate['price_from'];
                            
                            $tour_price_from['range_index'] = get_range_index($min_rate['price_from']);
                            
                            $tour_price_from['mid_range_index'] = get_tour_range_index($min_rate['price_from']);
                            
                            
                            if (! empty($tour_departures))
                            {
                                foreach ($tour_departures as $value)
                                {
                                    $tour_price_from['tour_departure_id'] = $value;
                                    $this->db->insert('tour_price_froms', $tour_price_from);
                                }
                            }
                            else
                            {
                                $this->db->insert('tour_price_froms', $tour_price_from);
                            }
                            
                        }
                    }
                }
            }
        }
        
        $this->db->trans_complete();
    }

    function _get_tour_promotions_in_date_range($tour_id, $start_date, $end_date)
    {
        $this->db->select('p.id, p.name, p.stay_date_from, p.stay_date_to, p.book_date_from, p.book_date_to, pt.get, p.discount_type');
        
        $this->db->join('promotions p', 'p.id = pt.promotion_id', 'left outer');
        
        $this->db->where('pt.tour_id', $tour_id);
        
        $this->db->where('p.stay_date_from <=', $end_date);
        
        $this->db->where('p.stay_date_to >=', $start_date);
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->order_by('id', 'asc');
        
        $query = $this->db->get('promotion_tours pt');
        
        $promotions = $query->result_array();
        
        foreach ($promotions as $k => $promotion)
        {
            $promotions[$k]['promotion_tours'] = $this->get_promotion_tours($promotion['id']);
        }
        
        return $promotions;
    }

    function get_promotion_tours($promotion_id)
    {
        $this->db->where('promotion_id', $promotion_id);
        
        $query = $this->db->get('promotion_tours');
        
        $rerults = $query->result_array();
        
        foreach ($rerults as $key => $value)
        {
            
            $value['tour_pro_details'] = $this->get_promotion_tour_details($value['id']);
            
            $rerults[$key] = $value;
        }
        
        return $rerults;
    }

    function get_promotion_tour_details($promotion_tour_id)
    {
        
        $this->db->where('tour_promotion_id', $promotion_tour_id);
        
        $query = $this->db->get('tour_promotion_details');
        
        $rerults = $query->result_array();
        
        return $rerults;
        
    }

    function _get_promotion_room_types($promotion_id)
    {
        
        $this->db->where('promotion_id', $promotion_id);
        
        $query = $this->db->get('promotion_room_types');
        
        $results = $query->result_array();
        
        return $results;
        
    }

    function _get_accommodations_in_date_range($accommodation_ids, $changed_rate_dates)
    {
        
        $select_fields = 'accommodation_id, tour_id, date, 1_pax_rate, 2_pax_rate, 3_pax_rate, 4_pax_rate, 5_pax_rate, children_rate, ';
        $select_fields .= 'infant_rate, 1_pax_net, 2_pax_net, 3_pax_net, 4_pax_net, 5_pax_net, children_net, infant_net';
        
        $this->db->select($select_fields);
        
        $this->db->where_in('date', $changed_rate_dates);
        
        $this->db->where_in('accommodation_id', $accommodation_ids);
        
        $query = $this->db->get('tour_rates');
        
        $results = $query->result_array();
        
        return $results;
        
    }

    function _get_accommodation_ids($tour_id)
    {
        $ret = array();
        
        $this->db->select('id');
        
        $this->db->where('tour_id', $tour_id);
        
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('accommodations');
        
        $results = $query->result_array();
        
        foreach ($results as $value)
        {
            $ret[] = $value['id'];
        }
        
        return $ret;
    }

    function create_tour_rate_action($tour_rate_action)
    {
        $this->db->trans_start();
        
        $tour_departures = null;
        
        if (isset($tour_rate_action['tour_departures']))
        {
            $tour_departures = $tour_rate_action['tour_departures'];
            unset($tour_rate_action['tour_departures']);
        }
        
        // --- Create new tour rate action
        
        $rras = $tour_rate_action['rras'];
        
        $tour_rate_action['user_created_id'] = get_user_id();
        $tour_rate_action['user_modified_id'] = get_user_id();
        
        $tour_rate_action['date_created'] = date(DB_DATE_TIME_FORMAT);
        $tour_rate_action['date_modified'] = date(DB_DATE_TIME_FORMAT);
        
        unset($tour_rate_action['rras']);
        
        $this->db->insert('tour_rate_actions', $tour_rate_action);
        
        $tour_rate_action_id = $this->db->insert_id();
        
        // --- Create tour room rate action
        
        foreach ($rras as $value)
        {
            $value['tour_rate_action_id'] = $tour_rate_action_id;
            $this->db->insert('tour_room_rate_actions', $value);
        }
        
        $this->update_tour_rates_by_action($tour_rate_action, $rras, $tour_departures);
        
        // --- Update tour departure rates
        
        if (! empty($tour_departures))
        {
            $this->update_tour_departure_rate($tour_departures, $tour_rate_action_id);
        }
        
        $this->db->trans_complete();
        
        return true;
    }

    function update_tour_rate_action($id, $tour_rate_action)
    {
        $this->db->trans_start();
        
        $tour_departures = null;
        
        if (isset($tour_rate_action['tour_departures']))
        {
            $tour_departures = $tour_rate_action['tour_departures'];
            unset($tour_rate_action['tour_departures']);
        }
        
        // --- Update tour rate actions
        
        $rras = $tour_rate_action['rras'];
        
        $hr_action['user_modified_id'] = get_user_id();
        $hr_action['date_modified'] = date(DB_DATE_TIME_FORMAT);
        
        unset($tour_rate_action['rras']);
        
        $this->db->where('id', $id);
        $this->db->update('tour_rate_actions', $tour_rate_action);
        
        // --- Update tour room rate actions
        $this->db->where('tour_rate_action_id', $id);
        $this->db->delete('tour_room_rate_actions');
        
        foreach ($rras as $value)
        {
            $value['tour_rate_action_id'] = $id;
            $this->db->insert('tour_room_rate_actions', $value);
        }
        
        // --- Update tour departure rates
        
        if (! empty($tour_departures))
        {
            $this->update_tour_departure_rate($tour_departures, $id);
        
            unset($tour_rate_action['tour_departures']);
        }
        
        // --- Update tour departure rates
        
        $this->update_tour_rates_by_action($tour_rate_action, $rras, $tour_departures);
        
        $this->db->trans_complete();
        
        return true;
    }

    function get_all_hr_actions($tour_id)
    {
    	$this->db->select('tr.id, tr.tour_id, tr.cruise_id, tr.start_date, tr.end_date, tr.week_day, tr.date_created, tr.date_modified, tr.user_created_id, tr.user_modified_id, u.username as last_modified_by');
    	
        $this->db->where('tr.tour_id', $tour_id);
        
        $this->db->join('users u','u.id = tr.user_modified_id', 'left outer');
        
        $this->db->order_by('tr.id');
        
        $query = $this->db->get('tour_rate_actions tr');
        
        $results = $query->result_array();
        
        return $results;
    }

    function get_tour_rate_action($id)
    {
        $this->db->where('id', $id);
        
        $this->db->order_by('id');
        
        $query = $this->db->get('tour_rate_actions');
        
        $results = $query->result_array();
        
        if (count($results) > 0)
        {
            
            $hra = $results[0];
            
            $hra['rras'] = $this->get_rras($id);
            
            return $hra;
        }
        
        return '';
    }

    function get_rras($tour_rate_action_id)
    {
        $this->db->where('tour_rate_action_id', $tour_rate_action_id);
        
        $query = $this->db->get('tour_room_rate_actions');
        
        $results = $query->result_array();
        
        return $results;
    }

    function delete_hra($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tour_rate_actions');
        
        $this->db->where('tour_rate_action_id', $id);
        $this->db->delete('tour_room_rate_actions');
        return true;
    }

    function update_tour_rates_by_action($hra, $rras, $tour_departures)
    {
        
        $this->db->trans_start();
        
        $changed_rate_dates = array();
        
        $start_date = $hra['start_date'];
        
        $end_date = $hra['end_date'];
        
        $rate_dates = get_days_between_2_dates($start_date, $end_date);
        
        $tour_id = $hra['tour_id'];
        
        $week_day = $hra['week_day'];
        
        $group_size = $this->config->item('group_size');
        
        if (count($rate_dates) > 0 && count($rras) > 0 && $week_day > 0)
        {
            
            foreach ($rate_dates as $date)
            {
                
                $wd = date('w', strtotime($date));
                
                if (is_bit_value_contain($week_day, $wd))
                {
                    
                    $changed_rate_dates[] = $date;
                }
            }
            
            if (count($changed_rate_dates) > 0)
            {
                $t1 = microtime(true);
                
                foreach ($rras as $rra)
                {
                    
                    $room_rate['tour_id'] = $tour_id;
                    
                    $room_rate['accommodation_id'] = $rra['accommodation_id'];
                    
                    // sell price
                    foreach ($group_size as $value)
                    {
                        $room_rate[$value . '_rate'] = $rra[$value . '_rate'];
                    }
                    
                    // net price
                    foreach ($group_size as $value)
                    {
                        $room_rate[$value . '_net'] = $rra[$value . '_net'];
                    }
                    
                    $this->delete_tour_rate_in_range($room_rate, $changed_rate_dates, $tour_departures);
                }
                
                $t2 = microtime(true);
                
                foreach ($changed_rate_dates as $date)
                {
                    
                    foreach ($rras as $rra)
                    {
                        
                        $room_rate['tour_id'] = $tour_id;
                        
                        $room_rate['accommodation_id'] = $rra['accommodation_id'];
                        
                        $room_rate['date'] = $date;
                        
                        // sell price
                        foreach ($group_size as $value)
                        {
                            $room_rate[$value . '_rate'] = $rra[$value . '_rate'];
                        }
                        
                        // net price
                        foreach ($group_size as $value)
                        {
                            $room_rate[$value . '_net'] = $rra[$value . '_net'];
                        }
                        
                        $this->create_tour_rate($room_rate, $tour_departures);
                    }
                }
                
                $t3 = microtime(true);
            }
            
            // update tour price from
            $this->update_tour_price_from($tour_id, $changed_rate_dates, $tour_departures);
            
            $t4 = microtime(true);
        }
        
        $this->db->trans_complete();
        
    }
    
    function update_tour_departure_rate($tour_departures, $tour_rate_action_id)
    {

        $this->db->trans_start();
    
        // create or update to tour_departure_rates table
        $this->db->where('tour_rate_action_id', $tour_rate_action_id);
        $this->db->delete('tour_departure_rates');
    
        if (! empty($tour_departures))
        {
            foreach ($tour_departures as $value)
            {
                $tour_rate_data = array(
                    'tour_rate_action_id' => $tour_rate_action_id,
                    'tour_departure_id' => $value
                );
    
                $this->db->insert('tour_departure_rates', $tour_rate_data);
            }
        }
    
        $this->db->trans_complete();
    
        $error_nr = $this->db->_error_number();
    
        return ! $error_nr;
    }
    
    function get_tour_departure_rates($tour_rate_action_id) {
        
        $this->db->select('tour_departure_id');
        
        $this->db->where('tour_rate_action_id', $tour_rate_action_id);
        
        $query = $this->db->get('tour_departure_rates');
        
        return $query->result_array();
        
    }
}