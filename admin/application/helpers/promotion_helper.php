<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_promotion_step_class($step, $current_step, $pro = array()){
	$saved_step = 1;
	if(!empty($pro) && !empty($pro['step'])){
		$saved_step = $pro['step'];
	}
	
	if($step == $current_step) return 'active';
	
	if(!empty($pro) && isset($pro['id'])){ // for edit view: show all step link
		return '';
	}
	
	if($step <= $saved_step) return '';
	
	if($step > $saved_step) return 'disabled';
}

function get_promotion_step_link($step, $current_step, $object_id, $pro = array(), $pro_type = HOTEL)
{
    switch ($pro_type)
    {
        case CRUISE:
            $site = 'cruises';
            break;
        case TOUR:
            $site = 'tours';
            break;
        default:
            $site = 'hotels';
            break;
    }
    
    if (get_promotion_step_class($step, $current_step, $pro) == 'disabled')
    {
        return 'javascript:void(0)';
    }
    
    if (! empty($pro['id']))
    {
        return site_url($site . '/promotions/' . $object_id . '/edit/' . $pro['id'] . '/' . $step) . '/';
    }
    else
    {
        return site_url($site . '/promotions/' . $object_id . '/create/' . $step) . '/';
    }
}

function is_room_type_promotion($pro, $room_type_id){
	
	$ret = FALSE;
	
	if(isset($pro['pro_room_types'])){
		
		foreach ($pro['pro_room_types'] as $value){
			
			if($value['room_type_id'] == $room_type_id) return TRUE;
			
			
		}
		
	}
	
	return $ret;
	
}

function is_tour_promotion($pro, $tour_id){

	$ret = FALSE;

	if(isset($pro['pro_tours'])){

		foreach ($pro['pro_tours'] as $value){
				
			if($value['tour_id'] == $tour_id) return TRUE;
				
				
		}

	}

	return $ret;

}

function get_tour_offer_note_pro($pro, $tour_id){

    $offer_note = '';

    if (isset($pro['pro_tours']))
    {

        foreach ($pro['pro_tours'] as $value)
        {
            if (!empty($tour_id) && $value['tour_id'] == $tour_id)
                return $value['offer_note'];
        }
    }


    return $offer_note;

}

function is_tour_departure_promotion($pro, $tour_departure_id){

    $ret = FALSE;

    if(isset($pro['pro_tours'])){

        foreach ($pro['pro_tours'] as $value){

            if($value['tour_departure_id'] == $tour_departure_id) return TRUE;


        }

    }

    return $ret;

}

function get_tour_departure_offer_note_pro($pro, $tour_departure_id){

    $offer_note = '';

    if (isset($pro['pro_tours']))
    {

        foreach ($pro['pro_tours'] as $value)
        {
            if (!empty($tour_departure_id) && $value['tour_departure_id'] == $tour_departure_id)
                return $value['offer_note'];
        }
    }


    return $offer_note;

}

function get_acc_offer_note($pro, $acc_id, $tour_departure_id = null){

	$get = '';

	if(isset($pro['pro_tours'])){

		foreach ($pro['pro_tours'] as $value){
			
			if(isset($value['tour_pro_details'])){
				
				foreach ($value['tour_pro_details'] as $tour_pro_detail){
				    
				    if(!empty($tour_departure_id) && $tour_departure_id != $tour_pro_detail['tour_departure_id']) {
				        continue;
				    }
					
					if($tour_pro_detail['accommodation_id'] == $acc_id){
						
						return $tour_pro_detail['offer_note'];
						
					}
					
				}
				
			}
	
		}

	}


	return $get;

}

function get_acc_get($pro, $acc_id, $tour_departure_id = null){

	$get = '';

	if(isset($pro['pro_tours'])){

		foreach ($pro['pro_tours'] as $value){
				
			if(isset($value['tour_pro_details'])){

				foreach ($value['tour_pro_details'] as $tour_pro_detail){
				    
				    if(!empty($tour_departure_id) && $tour_departure_id != $tour_pro_detail['tour_departure_id']) {
				        continue;
				    }
						
					if($tour_pro_detail['accommodation_id'] == $acc_id){

						if(!isset($tour_pro_detail['get']) || is_null($tour_pro_detail['get'])){

							return '';

						} else {

							return $tour_pro_detail['get'];
						}

					}
						
				}

			}

		}

	}


	return $get;

}


function get_room_type_offer_note_pro($pro, $room_type_id){
	
	$offer_note = '';
	
	if(isset($pro['pro_room_types'])){
		
		foreach ($pro['pro_room_types'] as $value){
			
			if($value['room_type_id'] == $room_type_id) return $value['offer_note'];
			
			
		}
		
	}
	
	
	return $offer_note;
	
}

function get_room_type_get($pro, $room_type_id){
	
	$get = '';
	
	if(isset($pro['pro_room_types'])){
	
		foreach ($pro['pro_room_types'] as $value){
				
			if($value['room_type_id'] == $room_type_id){
				
				if(!isset($value['get']) || is_null($value['get'])){
					
					return '';
					
				} else {
					
					return $value['get'];
				}
				
			}
				
				
		}
	
	}
	
	
	return $get;
	
}


function is_show_apply_on($pro){
	return $pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT || $pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_NIGHT;
}

function is_show_apply_on_free_night($pro){
	return $pro['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT;
}

function is_show_recurring_benefit($pro){
	return $pro['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT || $pro['apply_on'] == APPLY_ON_SPECIFIC_NIGHT;
}

function is_show_get($pro, $index){
	
	if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_BOOKING || $pro['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT
        || $pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_PAX){
		
		if($index == 1) return TRUE; else return FALSE;
		
	}
	
	if($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT || $pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_NIGHT){
		
		if($pro['apply_on'] == APPLY_ON_SPECIFIC_DAY || $pro['apply_on'] == APPLY_ON_SPECIFIC_NIGHT){
			
			return true;
			
		} else {
			return $index == 1;
		}
		
	}
	
	
	return TRUE;
}

function get_unit_label($pro, $index){
	
	if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_BOOKING){
		return lang('pro_discount_amount_per_booking');
	}
	
	if($pro['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT){
		
		return lang('pro_free_night');
	}
	
	if($pro['apply_on'] == APPLY_ON_EVERY_NIGHT || $pro['apply_on'] == APPLY_ON_FIRST_NIGHT || $pro['apply_on'] == APPLY_ON_LAST_NIGHT){
		
		if($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT){
			
			return lang('pro_discount');
			
		}
		
		if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_NIGHT){
			return lang('pro_discount_amount_per_night');
		}
		
	}
	
	$CI =& get_instance();
	$pro_nights = $CI->config->item('pro_nights');
	$pro_week_days = $CI->config->item('pro_week_days');
	
	if($pro['apply_on'] == APPLY_ON_SPECIFIC_NIGHT){
		
		if($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT){
			
			return lang('pro_discount_on') .' '.$pro_nights[$index];
			
		}
		
		if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_NIGHT){
			return lang('pro_discount_amount_per_night_on').' '.$pro_nights[$index];;
		}
		
	}
	
	if($pro['apply_on'] == APPLY_ON_SPECIFIC_DAY){
		
		if($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT){
			
			return lang('pro_discount_on') .' '.$pro_week_days[$index];
			
		}
		
		if($pro['discount_type'] == DISCOUNT_TYPE_AMOUNT_PER_NIGHT){
			return lang('pro_discount_amount_per_night_on').' '.$pro_week_days[$index];;
		}
		
	}
	
}

function is_show_room_type_get($pro){
	$ret = false;
	
	if($pro['discount_type'] == DISCOUNT_TYPE_DISCOUNT){
		
		if($pro['apply_on'] == APPLY_ON_EVERY_NIGHT || $pro['apply_on'] == APPLY_ON_FIRST_NIGHT || $pro['apply_on'] == APPLY_ON_LAST_NIGHT){
			
			return true;
			
		}
		
	}
	
	if($pro['discount_type'] == DISCOUNT_TYPE_FREE_NIGHT){
	
		return true;
	
	}
	
	
	return $ret;
}