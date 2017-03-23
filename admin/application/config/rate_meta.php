<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['rate_nav_panel'] = array(
		array(
			'link' 	=> '/hotels/rates/',
			'title' => 'rate_mnu_rate_control',
		),
		
		array(
			'link' 	=> '/hotels/change-room-rates/',
			'title' => 'rate_mnu_change_room_rates',
		),
		
		array(
			'link' 	=> '/hotels/room-rate-action/',
			'title' => 'rate_mnu_room_rate_actions',
		),
	
);

$config['change_room_rate'] = array(

	'all_day' => array (
	    'field'		=> 'all_day',
	    'label' 	=> 'lang:all_day',
	    'rules'		=> '',
	),
	
	'all_room' => array (
	    'field'		=> 'all_room',
	    'label' 	=> 'lang:all_room',
	    'rules'		=> '',
	),

	'week_day' => array (
	    'field'		=> 'week_day[]',
	    'label' 	=> 'lang:rate_week_day',
	    'rules'		=> 'required',
	),
	
	'room_types' => array (
	    'field'		=> 'room_types[]',
	    'label' 	=> 'lang:rate_room_types',
	    'rules'		=> 'required',
	),
	
	'start_date' => array (
	    'field'		=> 'start_date',
	    'label' 	=> 'lang:field_start_date',
	    'rules'		=> 'required|callback_rate_date_check',
	),
	
	'end_date' => array (
	    'field'		=> 'end_date',
	    'label' 	=> 'lang:field_end_date',
	    'rules'		=> 'required|callback_rate_date_check',
	),
	
	'full_occupancy' => array (
	    'field'		=> 'full_occupancy',
	    'label' 	=> 'lang:rate_full_occupancy',
	    'rules'		=> '',
	),
	
	'triple' => array (
	    'field'		=> 'triple',
	    'label' 	=> 'lang:rate_triple',
	    'rules'		=> '',
	),
	
	'double' => array (
	    'field'		=> 'double',
	    'label' 	=> 'lang:rate_double',
	    'rules'		=> '',
	),
	
	'single' => array (
	    'field'		=> 'single',
	    'label' 	=> 'lang:rate_single',
	    'rules'		=> '',
	),
	
	'extra_bed' => array (
	    'field'		=> 'extra_bed',
	    'label' 	=> 'lang:rate_extra_bed',
	    'rules'		=> '',
	),
);

$config['hotel_rate_action'] = array(

		'start_date' => array(
				'field' => 'start_date',
				'label' => 'lang:rra_field_start_date',
				'rules' => 'required|callback_rra_date_check',
		),

		'end_date' => array(
				'field' => 'end_date',
				'label' => 'lang:rra_field_end_date',
				'rules' => 'required|callback_rra_date_check',
		),

		'week_day' => array(
				'field' => 'week_day[]',
				'label' => 'lang:rra_field_week_day',
				'rules' => 'required',
		)
);