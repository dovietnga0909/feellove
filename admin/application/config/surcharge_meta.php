<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['surcharge'] = array(
	'name' => array (
	    'field'		=> 'name',
	    'label' 	=> 'lang:sur_field_name',
	    'rules'		=> 'required|xss_clean|callback_sur_name_check',
	),
	
	'start_date' => array(
		'field' => 'start_date',
		'label' => 'lang:sur_field_start_date',
		'rules' => 'required|callback_sur_date_check',
	),
	
	'end_date' => array(
		'field' => 'end_date',
		'label' => 'lang:sur_field_end_date',
		'rules' => 'required|callback_sur_date_check',		
	),
	
	'week_day' => array(
		'field' => 'week_day[]',
		'label' => 'lang:sur_field_week_day',
		'rules' => 'required',		
	),
	
	'charge_type' => array(
		'field' => 'charge_type',
		'label' => 'lang:sur_field_charge_type',
		'rules' => 'required',		
	),
	
	'amount' => array(
		'field' => 'amount',
		'label' => 'lang:sur_field_amount',
		'rules' => 'required',		
	),
	
	'description' => array(
		'field' => 'description',
		'label' => 'lang:sur_field_description',
		'rules' => 'xss_clean',		
	),
	
);

$config['charge_types'] = array(
	1 => lang('charge_type_per_adt_per_booking'),
	2 => lang('charge_type_per_night'),
	3 => lang('charge_type_per_room'),
	4 => lang('charge_type_per_room_night'),
	5 => lang('charge_type_per_room_price'),
);

$config['cruise_charge_types'] = array(
	1 => lang('charge_type_per_pax_per_booking'),
	5 => lang('charge_type_per_total_price'),
);





