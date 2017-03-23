<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

function get_end_date_show_rates($start_date)
{
    $end_date = date(DATE_FORMAT, strtotime($start_date . ' +' . (RATE_DAYS_SHOW - 1) . ' days'));
    
    return $end_date;
}

function get_date_rate_show($start_date)
{
    $ret[] = $start_date;
    
    for ($i = 1; $i < RATE_DAYS_SHOW; $i ++)
    {
        
        $end_date = date(DATE_FORMAT, strtotime($start_date . ' +' . $i . ' days'));
        
        $ret[] = $end_date;
    }
    
    return $ret;
}

function is_weekend($date)
{
    $index = date('w', strtotime($date));
    
    return $index == 0 || $index == 6;
}

function get_next_rate_date($start_date, $action)
{
    if ($action == ACTION_NEXT)
    {
        $start_date = date(DATE_FORMAT, strtotime($start_date . ' +' . RATE_DAYS_SHOW . ' days'));
    }
    elseif ($action == ACTION_BACK)
    {
        $start_date = date(DATE_FORMAT, strtotime($start_date . ' -' . RATE_DAYS_SHOW . ' days'));
    }
    
    return $start_date;
}

function get_month_rate_show($start_date)
{
    $month['date'] = $start_date;
    
    $col = 1;
    
    for ($i = 1; $i < RATE_DAYS_SHOW; $i ++)
    {
        
        $date = date(DATE_FORMAT, strtotime($start_date . ' +' . $i . ' days'));
        
        if (date('m', strtotime($date)) == date('m', strtotime($start_date)))
        {
            
            $col ++;
        }
        else
        {}
    }
    
    $month['col'] = $col;
    $ret[] = $month;
    
    if (RATE_DAYS_SHOW - $col > 0)
    {
        $end_date = get_end_date_show_rates($start_date);
        $month['date'] = $end_date;
        $month['col'] = RATE_DAYS_SHOW - $col;
        
        $ret[] = $month;
    }
    
    return $ret;
}

function is_hotel_has_tripple_room($room_types)
{
    if (! empty($room_types))
    {
        
        foreach ($room_types as $value)
        {
            
            if ($value['max_occupancy'] == TRIPLE)
            {
                return TRUE;
            }
        }
    }
    
    return FALSE;
}

function is_hotel_has_family_room($room_types)
{
    if (! empty($room_types))
    {
        
        foreach ($room_types as $value)
        {
            
            if ($value['max_occupancy'] > TRIPLE)
            {
                return TRUE;
            }
        }
    }
    
    return FALSE;
}

function is_hotel_has_room_extra_bed($room_types)
{
    if (! empty($room_types))
    {
        
        foreach ($room_types as $value)
        {
            
            if ($value['max_extra_beds'] > 0)
            {
                return TRUE;
            }
        }
    }
    
    return FALSE;
}

function get_room_names_by_type($room_types)
{
    $names['full_occupancy'] = '';
    $names['triple'] = '';
    $names['extra_bed'] = '';
    
    if (! empty($room_types))
    {
        
        foreach ($room_types as $value)
        {
            
            if ($value['max_occupancy'] > TRIPLE)
            {
                
                $full_occupany[] = $value['name'];
            }
            
            if ($value['max_occupancy'] >= TRIPLE)
            {
                
                $triple[] = $value['name'];
            }
            
            if ($value['max_extra_beds'] > 0)
            {
                
                $extra_bed[] = $value['name'];
            }
            
            if (isset($full_occupany))
            {
                
                $names['full_occupancy'] = implode(', ', $full_occupany);
            }
            
            if (isset($triple))
            {
                
                $names['triple'] = implode(', ', $triple);
            }
            
            if (isset($extra_bed))
            {
                
                $names['extra_bed'] = implode(', ', $extra_bed);
            }
        }
    }
    
    return $names;
}

function get_max_occupancy_text($room_type)
{
    $str = lang('max_occupancy') . ': ';
    $str .= $room_type['max_occupancy'] . ' ' . lang('adults');
    if ($room_type['max_children'] > 0)
    {
        $str .= ' + ' . $room_type['max_children'] . ' ' . ($room_type['max_children'] > 1 ? lang('children') : lang('child'));
    }
    
    return $str;
}

function get_days_between_2_dates($start_date, $end_date)
{
    $start_date = date(DB_DATE_FORMAT, strtotime($start_date));
    
    $end_date = date(DB_DATE_FORMAT, strtotime($end_date));
    
    if ($start_date > $end_date)
        return array();
    
    $ret[] = $start_date;
    
    $tmp_date = $start_date;
    
    while ($tmp_date < $end_date)
    {
        
        $tmp_date = date(DB_DATE_FORMAT, strtotime($tmp_date . ' +1 day'));
        
        $ret[] = $tmp_date;
    }
    
    return $ret;
}

function get_room_rate_record($room_rates, $room_type_id, $date)
{
    $date = date(DB_DATE_FORMAT, strtotime($date));
    
    foreach ($room_rates as $value)
    {
        
        if ($value['room_type_id'] == $room_type_id && $value['date'] == $date)
        {
            
            return $value;
        }
    }
    
    return NULL;
}

/**
 * Helper functions for calculate hotel price from
 */
function get_room_rates_in_date($room_rates, $date)
{
    $ret = array();
    
    foreach ($room_rates as $rr)
    {
        if ($rr['date'] == $date)
        {
            $ret[] = $rr;
        }
    }
    return $ret;
}

function get_promotions_in_date($promotions, $date)
{
    $ret = array();
    
    foreach ($promotions as $pro)
    {
        if ($pro['stay_date_from'] <= $date && $pro['stay_date_to'] >= $date)
        {
            if (is_bit_value_contain($pro['check_in_on'], date('w', strtotime($date))))
            {
                $ret[] = $pro;
            }
        }
    }
    return $ret;
}

function get_minimum_room_rate($room_rates, $promotion = '')
{
    if (count($room_rates) > 0)
    {
        
        $room_rates = update_room_rates_by_promotion($room_rates, $promotion);
        
        $min_rates = array();
        
        foreach ($room_rates as $rr)
        {
            
            $price_origin = 0;
            
            $price_from = 0;
            
            if ($rr['single_rate'] > 0)
            {
                $price_origin = $rr['single_rate_origin'];
                $price_from = $rr['single_rate'];
            }
            
            if ($rr['double_rate'] > 0)
            {
                
                if ($price_from == 0)
                {
                    
                    $price_origin = $rr['double_rate_origin'];
                    $price_from = $rr['double_rate'];
                }
                elseif ($rr['double_rate'] < $price_from)
                {
                    $price_origin = $rr['double_rate_origin'];
                    $price_from = $rr['double_rate'];
                }
            }
            
            if ($rr['triple_rate'] > 0)
            {
                
                if ($price_from == 0)
                {
                    
                    $price_origin = $rr['triple_rate_origin'];
                    $price_from = $rr['triple_rate'];
                }
                elseif ($rr['triple_rate'] < $price_from)
                {
                    
                    $price_origin = $rr['triple_rate_origin'];
                    $price_from = $rr['triple_rate'];
                }
            }
            
            if ($rr['full_occupancy_rate'] > 0)
            {
                
                if ($price_from == 0)
                {
                    
                    $price_origin = $rr['full_occupancy_rate_origin'];
                    $price_from = $rr['full_occupancy_rate'];
                }
                elseif ($rr['full_occupancy_rate'] < $price_from)
                {
                    
                    $price_origin = $rr['full_occupancy_rate_origin'];
                    $price_from = $rr['full_occupancy_rate'];
                }
            }
            
            $min_rates[$rr['room_type_id']] = array(
                'price_origin' => $price_origin,
                'price_from' => $price_from
            );
        }
        
        $room_type_id = $room_rates[0]['room_type_id'];
        
        $min_rate = $min_rates[$room_type_id];
        
        foreach ($min_rates as $key => $value)
        {
            
            if ($value['price_from'] < $min_rate['price_from'])
            {
                
                $min_rate = $value;
                
                $room_type_id = $key;
            }
        }
        
        return array(
            'room_type_id' => $room_type_id,
            'price_origin' => $min_rate['price_origin'],
            'price_from' => $min_rate['price_from']
        );
    }
    else
    {
        return '';
    }
}

function update_room_rates_by_promotion($room_rates, $promotion = '')
{
    foreach ($room_rates as $key => $value)
    {
        
        $value['single_rate_origin'] = $value['single_rate'];
        
        $value['double_rate_origin'] = $value['double_rate'];
        
        $value['triple_rate_origin'] = $value['triple_rate'];
        
        $value['full_occupancy_rate_origin'] = $value['full_occupancy_rate'];
        
        if ($promotion != '')
        {
            
            $value = calculate_room_rates_by_promotion($value, $promotion);
        }
        
        $room_rates[$key] = $value;
    }
    
    return $room_rates;
}

function calculate_room_rates_by_promotion($room_rate, $promotion)
{
    $room_type_get = NULL;
    
    if ($promotion['room_type'] == 2)
    { // specific room type
        
        $has_pro = false;
        
        foreach ($promotion['room_types'] as $pro_room_type)
        {
            if ($pro_room_type['room_type_id'] == $room_rate['room_type_id'])
            {
                $has_pro = true;
                
                $room_type_get = $pro_room_type['get'];
                
                break;
            }
        }
        
        if (! $has_pro)
            return $room_rate;
    }
    
    $discount = $promotion['get_1'];
    
    if ($promotion['apply_on'] == APPLY_ON_SPECIFIC_DAY)
    {
        
        $day = date('w', strtotime($room_rate['date']));
        
        if ($day == 0)
            $day = 7;
        
        $discount = $promotion['get_' . $day];
    }
    
    if ($promotion['discount_type'] == DISCOUNT_TYPE_DISCOUNT)
    {
        
        // overide the GET value from the specifi room type
        if (! is_null($room_type_get))
        {
            $discount = $room_type_get;
        }
        
        if ($room_rate['single_rate'] > 0)
        {
            $room_rate['single_rate'] = $room_rate['single_rate'] * (100 - $discount) / 100;
        }
        
        if ($room_rate['double_rate'] > 0)
        {
            $room_rate['double_rate'] = $room_rate['double_rate'] * (100 - $discount) / 100;
        }
        
        if ($room_rate['triple_rate'] > 0)
        {
            $room_rate['triple_rate'] = $room_rate['triple_rate'] * (100 - $discount) / 100;
        }
        
        if ($room_rate['full_occupancy_rate'] > 0)
        {
            $room_rate['full_occupancy_rate'] = $room_rate['full_occupancy_rate'] * (100 - $discount) / 100;
        }
    }
    
    if ($promotion['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_BOOKING || $promotion['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_NIGHT)
    {
        
        if ($promotion['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_BOOKING)
        {
            $discount = $promotion['get_1'];
        }
        
        if ($room_rate['single_rate'] > $discount)
        {
            $room_rate['single_rate'] = $room_rate['single_rate'] - $discount;
        }
        else
        {
            $room_rate['single_rate'] = 0;
        }
        
        if ($room_rate['double_rate'] > $discount)
        {
            $room_rate['double_rate'] = $room_rate['double_rate'] - $discount;
        }
        else
        {
            $room_rate['double_rate'] = 0;
        }
        
        if ($room_rate['triple_rate'] > $discount)
        {
            $room_rate['triple_rate'] = $room_rate['triple_rate'] - $discount;
        }
        else
        {
            $room_rate['triple_rate'] = 0;
        }
        
        if ($room_rate['full_occupancy_rate'] > $discount)
        {
            $room_rate['full_occupancy_rate'] = $room_rate['full_occupancy_rate'] - $discount;
        }
        else
        {
            $room_rate['full_occupancy_rate'] = 0;
        }
    }
    
    if ($promotion['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT)
    {
        
        $free_nights = $promotion['get_1'];
        // overide the GET value from the specific room type
        if (! is_null($room_type_get))
        {
            $free_nights = $room_type_get;
        }
        
        $minimum_stay = $promotion['minimum_stay'];
        
        if ($free_nights > 0 && $free_nights < $minimum_stay)
        {
            
            if ($room_rate['single_rate'] > 0)
            {
                $room_rate['single_rate'] = $room_rate['single_rate'] * ($minimum_stay - $free_nights) / $minimum_stay;
            }
            
            if ($room_rate['double_rate'] > 0)
            {
                $room_rate['double_rate'] = $room_rate['double_rate'] * ($minimum_stay - $free_nights) / $minimum_stay;
            }
            
            if ($room_rate['triple_rate'] > 0)
            {
                $room_rate['triple_rate'] = $room_rate['triple_rate'] * ($minimum_stay - $free_nights) / $minimum_stay;
            }
            
            if ($room_rate['full_occupancy_rate'] > 0)
            {
                $room_rate['full_occupancy_rate'] = $room_rate['full_occupancy_rate'] * ($minimum_stay - $free_nights) / $minimum_stay;
            }
        }
    }
    
    return $room_rate;
}

function get_range_index($price_from)
{
    if ($price_from <= 500000)
    {
        return 1;
    }
    if ($price_from <= 1000000)
    {
        return 2;
    }
    if ($price_from <= 2000000)
    {
        return 3;
    }
    if ($price_from <= 3000000)
    {
        return 4;
    }
    if ($price_from > 3000000)
    {
        return 5;
    }
    
    return 0;
}

function get_tour_range_index($price_from)
{
    if ($price_from <= 1000000)
    {
        return 1;
    }
    if ($price_from <= 5000000)
    {
        return 2;
    }
    if ($price_from <= 10000000)
    {
        return 3;
    }
    if ($price_from <= 15000000)
    {
        return 4;
    }
    if ($price_from > 15000000)
    {
        return 5;
    }

    return 0;
}

function get_rra_by_room_type($rt_id, $rras)
{
    foreach ($rras as $value)
    {
        if ($value['room_type_id'] == $rt_id)
        {
            return $value;
        }
    }
    return null;
}

function get_cruise_promotions_in_date($promotions, $date)
{
    $ret = array();
    
    foreach ($promotions as $pro)
    {
        if ($pro['stay_date_from'] <= $date && $pro['stay_date_to'] >= $date)
        {
            $ret[] = $pro;
        }
    }
    return $ret;
}

function get_accommodation_rate_record($tour_rates, $accommodation_id, $date)
{
    $date = date(DB_DATE_FORMAT, strtotime($date));
    
    foreach ($tour_rates as $value)
    {
        
        if ($value['accommodation_id'] == $accommodation_id && $value['date'] == $date)
        {
            
            return $value;
        }
    }
    
    return NULL;
}


/**
 * 
 * Get minimum accommodation rate for tour
 */
function get_minimum_accommodation_rate($room_rates, $promotion = null)
{
    if (! empty($room_rates))
    {
        
        $min_rates = array();
        
        $rate_opts = array(
            '2_pax',
            '3_pax',
            '4_pax',
            '5_pax'
        );
        
        foreach ($room_rates as $rr)
        {
 
            foreach ($rate_opts as $opt)
            {
                $price_origin = $rr[$opt . '_rate'];
                
                if (! empty($promotion))
                {
                    $promotion_off = get_accommodation_promotion_rate($promotion, $rr['accommodation_id'], $rr['tour_id']);
                    
                    if ($promotion['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_PAX)
                    {
                        $price_from = $rr[$opt . '_rate'] - $promotion_off;
                    }
                    else
                    {
                        $price_from = $rr[$opt . '_rate'] - ($rr[$opt . '_rate'] * $promotion_off / 100);
                    }

                }
                else
                {
                    $price_from = $rr[$opt . '_rate'];
                }
                
                if($price_from < 0) $price_from = 0;
                
                $min_rates[$rr['accommodation_id']] = array(
                    'price_origin' => $price_origin,
                    'price_from' => $price_from
                );
                
                if (! empty($price_origin))
                    break;
            }
        }
        
        $accommodation_id = $room_rates[0]['accommodation_id'];
        
        $min_rate = $min_rates[$accommodation_id];
        
        foreach ($min_rates as $key => $value)
        {
            
            if ( ($value['price_origin'] < $min_rate['price_origin'] && $value['price_origin'] > 0)
                
                || ($min_rate['price_origin'] == 0 && $value['price_origin'] > 0 ) )
            {
                
                $min_rate = $value;
                
                $accommodation_id = $key;
            }
        }

        return array(
            'accommodation_id' => $accommodation_id,
            'price_origin' => $min_rate['price_origin'],
            'price_from' => $min_rate['price_from']
        );
    }
    
    return null;
}

/**
 * Get promotion for specific tour accomodation 
 * 
 * @return promotion rate
 */
function get_accommodation_promotion_rate($promotion, $accommodation_id, $tour_id)
{
    if (! empty($promotion['promotion_tours']))
    {
        $promotion_tour = null;
        
        // get promotion tour
        foreach ($promotion['promotion_tours'] as $pro_tours)
        {
            if($tour_id == $pro_tours['tour_id']) {
                $promotion_tour = $pro_tours;
                break;
            }
            
        }
        
        // get promotion accommodation
        if (! empty($promotion_tour))
        {
            foreach ($promotion_tour['tour_pro_details'] as $tour_pro_details)
            {
                if ($tour_pro_details['accommodation_id'] == $accommodation_id)
                {
                    return $tour_pro_details['get'];
                    break;
                }
            }
        }
        
    }
    
    return $promotion['get'];
}
    
    