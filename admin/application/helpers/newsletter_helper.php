<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_lang_config($config, $config_value){
	
	$CI =& get_instance();
	$config_list = $CI->config->item($config);
	
	$config_name = array();
	
	foreach($config_list as $key =>$value){
		
		if($key == $config_value){
		
			$config_name[0] = lang($value);
			
			break;
		}
	}
	
	if(count($config_name)>0){
		
		return $config_name[0];
	}
}


function bpv_format_currency($rate, $small_end = true){
	
	$rate = bpv_round_rate($rate);
	
	if($rate <= 0) return '<span>0 ' .lang('vnd').'</span>';
	
	$rate = number_format($rate);
	$rate = str_replace(',', '.', $rate);
	
	$rate_2 = substr($rate, -3);
	
	$rate_1 = substr($rate, 0, strlen($rate) - 3);
	
	if($small_end){
		$rate = '<span>'.$rate_1.'<small>'.$rate_2.' '.lang('vnd').'</small></span>';
	} else {
		$rate = '<span>'.$rate_1.''.$rate_2.' '.lang('vnd').'</span>';
	}
	
	return $rate;
}

function hotel_build_url($promotion){

	$url = 'http://www.snotevn.com:8888/khach-san/'.$promotion['url_title'].'-'.$promotion['hotel_id'].'.html';
	
	return $url;
}

function cruise_build_url($promotion){
	
    $url = 'http://www.snotevn.com:8888/'. CRUISE_HL_HOME_PAGE . '/' . $promotion['url_title'] . '-' . $promotion['cruise_id'] . '.html';
    
    return $url;
}

function tour_build_url($promotion){
	
    $url = 'http://www.snotevn.com:8888/'. TOUR_DETAIL_PAGE . '/' . $promotion['url_title'] . '-' . $promotion['tour_id'] . '.html';
    
    return $url;
}

function format_bpv_date($str, $format = DB_DATE_FORMAT, $is_show_week_day = false){
	
	$CI =& get_instance();
	
	$str = str_replace('/', '-', $str);
	
	$wd = date('w', strtotime($str));
	
	$str = date($format, strtotime($str));
	
	if($is_show_week_day){
		
		$week_days = $CI->config->item('week_days');
		
		$wd = $week_days[$wd];
		
		$str = lang($wd).', '.$str;
	
	}
	
	return $str;	
}

/**
  *  get image path for everything :-)
  *  
  *  $type: HOTEL, TOUR, CRUISE, DESTINATION
  *
  *  @author toanlk
  *  @since  Sep 23, 2014
  */
function get_image_path($type, $image_name, $size = ''){
    $directory = '';
    
    $CI =& get_instance();
    
    switch ($type)
    {
        case TOUR:
            $directory = 'tours';
            break;
        case HOTEL:
            $directory = 'hotels';
            break;
        case CRUISE:
            $directory = 'cruises';
            break;
        case CRUISE_TOUR:
            
            if (strpos($image_name, '[cruise]') !== false)
            {
                $image_name = str_replace('[cruise]', '', $image_name);
                $directory = 'cruises';
            }
            else
            {
                $directory = 'tours';
            }
            
            break;
        case DESTINATION:
            $directory = 'destinations';
            break;
    }
    
    // Resource path
    //$resource_path = $CI->config->item('resource_path');
    $resource_path = "http://www.snotevn.com:8888/";
    
    // Photo directory
    $origin_path = 'images/'.$directory.'/uploads/';
    
    $large_path = 'images/'.$directory.'/large/';
    
    $medium_path = 'images/'.$directory.'/medium/';
    
    $small_path = 'images/'.$directory.'/small/';

    // get photo path
    $image_path = $origin_path;
    
    if ($size != '')
    {
        if ($size == '800x600' || $size == '416x312' || $size == '400x300')
        {
            if ($size == '400x300') $size = '416x312';
    
            $image_path = $large_path;
        }
    
        if ($size == '268x201' || $size == '200x150')
        {
            $image_path = $medium_path;
        }
    
        if ($size == '160x120' || $size == '120x90')
        {
            $image_path = $small_path;
        }
    
        $image_names = explode('.', $image_name);
    
        if (count($image_names) > 1)
        {
            $image_name = $image_names[0] . '-' . $size . '.' . $image_names[1];
        }
    }
    
    $image_path = $resource_path . $image_path . $image_name;
    
    return $image_path;
}

function get_newsletter_step_link($step, $current_step, $pro = array()){
    
    if (get_promotion_step_class($step, $current_step, $pro) == 'disabled') {
        return 'javascript:void(0)';
    }
    if (! empty($pro['id'])) {
        return site_url('/newsletters/edit/' . $pro['id'] . '/' . $step) . '/';
    }
    else {
        return site_url('/newsletters/create/' . $step) . '/';
    }
}

?>

