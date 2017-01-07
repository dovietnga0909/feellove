<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['create_flight_route'] = array(
		'from_destination_id' => array (
				'field'		=> 'from_destination_id',
				'label' 	=> 'lang:flights_field_from',
				'rules'		=> 'required',
		),
		'to_destination_id' => array (
				'field'		=> 'to_destination_id',
				'label' 	=> 'lang:flights_field_to',
				'rules'		=> 'required|callback_route_check',
		),
		'is_show_vietnam_flight_page' => array (
				'field'		=> 'is_show_vietnam_flight_page',
				'label' 	=> 'lang:flights_field_show_on_vf',
				'rules'		=> '',
		),
		'is_show_flight_destination_page' => array (
				'field'		=> 'is_show_flight_destination_page',
				'label' 	=> 'lang:flights_field_show_to_des',
				'rules'		=> '',
		),
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> '',
		),
);

$config['flight_nav_panel'] = array(
		array(
				'link' 	=> '/flights/',
				'title' => 'mnu_flight_route',
		),

		array(
				'link' 	=> '/airlines/',
				'title' => 'mnu_airline',
		),
		
		array(
				'link' 	=> '/flight-categories/',
				'title' => 'mnu_flight_category',
		),

);


$config['airlines'] = array(
	'name' => array (
			'field'		=> 'name',
			'label' 	=> 'lang:field_name',
			'rules'		=> 'required|xss_clean|callback_airline_name_check',
	),
	'code' => array (
			'field'		=> 'code',
			'label' 	=> 'lang:field_code',
			'rules'		=> 'required|xss_clean|callback_airline_code_check',
	),
	'is_domistic' => array (
			'field'		=> 'is_domistic',
			'label' 	=> 'lang:airline_field_is_domistic',
			'rules'		=> '',
	),
	'description' => array (
			'field'		=> 'description',
			'label' 	=> 'lang:field_description',
			'rules'		=> '',
	)
);

$config['flight_categories'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:field_name',
				'rules'		=> 'required|xss_clean|callback_category_name_check',
		),
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> '',
		),
		'start_date' => array (
				'field'		=> 'start_date',
				'label' 	=> 'lang:field_start_date',
				'rules'		=> 'required',
		),
		'end_date' => array (
				'field'		=> 'end_date',
				'label' 	=> 'lang:field_end_date',
				'rules'		=> 'required',
		),
		'description' => array (
				'field'		=> 'description',
				'label' 	=> 'lang:field_description',
				'rules'		=> '',
		)
);

$config['flight_photo'] = array(
		'upload_path' 	=> '../images/flights/',
		'allowed_types' => 'gif|jpg|jpeg|png',
		'max_width' 	=> '2048',
		'max_height' 	=> '1536',
		'max_size' 		=> '2048',
);


