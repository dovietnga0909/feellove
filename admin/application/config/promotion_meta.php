<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['promotion_1'] = array(
	'name' => array (
	    'field'		=> 'name',
	    'label' 	=> 'lang:pro_field_name',
	    'rules'		=> 'required|xss_clean',
	),
	
	'promotion_type' => array (
	    'field'		=> 'promotion_type',
	    'label' 	=> 'lang:pro_field_type',
	    'rules'		=> 'required',
	),
		
	'show_on_web' => array (
		'field'		=> 'show_on_web',
		'label' 	=> 'lang:pro_field_show_on_web',
		'rules'		=> 'required',
	),
	
	'offer' => array(
		'field' => 'offer',
		'label' => 'lang:pro_field_offer',
		'rules' => 'xss_clean',		
	),
	
);

$config['promotion_2'] = array(
	'minimum_stay' => array (
	    'field'		=> 'minimum_stay',
	    'label' 	=> 'lang:pro_field_minimum_stay',
	    'rules'		=> '',
	),
	
	'book_date_from' => array (
	    'field'		=> 'book_date_from',
	    'label' 	=> 'lang:pro_field_book_date_from',
	    'rules'		=> 'required|callback_book_date_check',
	),
	
	'book_date_to' => array (
	    'field'		=> 'book_date_to',
	    'label' 	=> 'lang:pro_field_book_date_to',
	    'rules'		=> 'required|callback_book_date_check',
	),
	
	'stay_date_from' => array (
	    'field'		=> 'stay_date_from',
	    'label' 	=> 'lang:pro_field_stay_date_from',
	    'rules'		=> 'required|callback_stay_date_check',
	),
	
	'stay_date_to' => array (
	    'field'		=> 'stay_date_to',
	    'label' 	=> 'lang:pro_field_stay_date_to',
	    'rules'		=> 'required|callback_stay_date_check',
	),
	
	'display_on' => array (
	    'field'		=> 'display_on[]',
	    'label' 	=> 'lang:pro_field_displayed_on',
	    'rules'		=> 'required',
	),
	
	'check_in_on' => array (
	    'field'		=> 'check_in_on[]',
	    'label' 	=> 'lang:pro_field_check_in_on',
	    'rules'		=> 'required',
	),
	
	'maximum_stay' => array (
	    'field'		=> 'maximum_stay',
	    'label' 	=> 'lang:pro_field_maximum_stay',
	    'rules'		=> '',
	),
	
	'book_time_from' => array (
	    'field'		=> 'book_time_from',
	    'label' 	=> 'lang:pro_field_book_time_from',
	    'rules'		=> '',
	),
	
	'book_time_to' => array (
	    'field'		=> 'book_time_to',
	    'label' 	=> 'lang:pro_field_book_time_to',
	    'rules'		=> '',
	),
);


$config['promotion_3'] = array(
	'discount_type' => array (
	    'field'		=> 'discount_type',
	    'label' 	=> 'lang:pro_field_discount_type',
	    'rules'		=> '',
	),
	
	'apply_on' => array (
	    'field'		=> 'apply_on',
	    'label' 	=> 'lang:pro_field_apply_on',
	    'rules'		=> '',
	),
	
	'apply_on_free_night' => array (
	    'field'		=> 'apply_on_free_night',
	    'label' 	=> 'lang:pro_field_apply_on',
	    'rules'		=> '',
	),
	
	'minimum_room' => array (
	    'field'		=> 'minimum_room',
	    'label' 	=> 'lang:pro_field_minimum_room',
	    'rules'		=> '',
	),
	
	'recurring_benefit' => array (
	    'field'		=> 'recurring_benefit',
	    'label' 	=> 'lang:pro_field_recurring_benefit',
	    'rules'		=> '',
	),
);

$config['promotion_4'] = array(
	'room_type' => array (
	    'field'		=> 'room_type',
	    'label' 	=> 'lang:pro_field_room_type',
	    'rules'		=> '',
	),
	
	'cancellation_policy' => array (
	    'field'		=> 'cancellation_policy',
	    'label' 	=> 'lang:pro_field_cancellation_policy',
	    'rules'		=> 'required',
	),
	
	'pro_room_types' => array (
	    'field'		=> 'pro_room_types[]',
	    'label' 	=> '',
	    'rules'		=> '',
	),

);



$config['promotion_types'] = array(
	1 => lang('pro_customize'),
	2 => lang('pro_early_bird'),
	3 => lang('pro_last_minute')
);

$config['promotion_night_limit'] = 9;

$config['discount_types'] = array(
	1 => lang('pro_discount'),
	2 => lang('pro_amount_discount_per_booking'),
	3 => lang('pro_amount_discount_per_night'),
	4 => lang('pro_free_night')
);

$config['tour_discount_types'] = array(
    1 => lang('pro_discount'),
    5 => lang('pro_amount_discount_per_pax'),
);

$config['apply_on'] = array(
	1 => lang('pro_every_night'),
	2 => lang('pro_specific_night'),
	3 => lang('pro_specific_day'),
	4 => lang('pro_first_night'),
	5 => lang('pro_last_night')
);

$config['apply_on_free_night'] = array(
	1 => lang('pro_any_night'),
	5 => lang('pro_last_night')
);

$config['pro_recurring_benefit'] = array(
	1 => lang('pro_multiple_times'),
	2 => lang('pro_once')
);

$config['pro_nights'] = array(
	1 => lang('pro_night_1'),
	2 => lang('pro_night_2'),
	3 => lang('pro_night_3'),
	4 => lang('pro_night_4'),
	5 => lang('pro_night_5'),
	6 => lang('pro_night_6'),
	7 => lang('pro_night_7'),
);

$config['pro_week_days'] = array(
	1 => lang('pro_week_day_1'),
	2 => lang('pro_week_day_2'),
	3 => lang('pro_week_day_3'),
	4 => lang('pro_week_day_4'),
	5 => lang('pro_week_day_5'),
	6 => lang('pro_week_day_6'),
	7 => lang('pro_week_day_7'),
);

$config['pro_room_types'] = array(
	1 => lang('pro_all_room_types'),
	2 => lang('pro_specific_types')
);

$config['pro_cruise_tours'] = array(
	1 => lang('pro_all_tours'),
	2 => lang('pro_specific_tours')
);

$config['pro_tour_departures'] = array(
    1 => lang('pro_all_tour_departures'),
    2 => lang('pro_specific_tour_departure')
);

$config['pro_room_limit'] = 5;


