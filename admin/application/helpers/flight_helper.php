<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Get time delay between 2 times
 */
function calculate_flying_delay($sr_1, $sr_2){

	$date_time_1 = $sr_1['end_date'].' '.$sr_1['arrival_time'].':00';

	$date_time_2 = $sr_2['start_date'].' '.$sr_2['departure_time'].':00';

	$date_time_1 = strtotime($date_time_1);

	$date_time_2 = strtotime($date_time_2);

	$seconds = $date_time_2 - $date_time_1;

	$hours = round($seconds/(60 * 60));

	$minutes = ($seconds / 60) % 60;

	$ret['h'] = $hours;
	$ret['m'] = $minutes;

	return $ret;
}

function select_fare_rule_short($sr, $i){
	
	if(!empty($sr['fare_rule_short'])) return $sr['fare_rule_short'] == lang('tf_fare_rule_'.$i);
	/*
	if($sr['airline'] == 'VN'){
		if(in_array($sr['flight_class'], array('P','E','A','T'))) return ($i == 1);
		
		if(in_array($sr['flight_class'], array('C','J'))) return ($i == 3);
	}
	
	return ($i == 2);'*/
	
	return false;
}