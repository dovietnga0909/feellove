<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['max_occupancy_on_existing_bedding'] = 6;
$config['max_extra_beds'] = 5;
$config['max_children'] = 5;

$config['create_cabin'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:cruise_cabins_field_name',
				'len'		=> 200,
				'rules'		=> 'required|trim|xss_clean|max_length[200]|callback_cabinname_check',
		),
		'description' => array (
				'field'		=> 'description',
				'label' 	=> 'lang:cruises_field_description',
				'len'		=> 5000,
				'rules'		=> 'max_length[5000]',
		),
		'cabin_size' => array (
				'field'		=> 'cabin_size',
				'label' 	=> 'lang:cruise_cabins_field_cabin_size',
				'rules'		=> 'greater_than[0]',
		),
		'bed_config' => array (
				'field'		=> 'bed_config',
				'label' 	=> 'lang:cruise_cabins_field_bed_config',
				'rules'		=> 'required',
		),
		'facilities' => array (
				'field'		=> 'facilities',
				'label' 	=> 'lang:cruise_cabins_field_facilities',
				'rules'		=> 'callback_facilities_check',
		),
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> '',
		),
);

$config['cabin_settings'] = array(
		'number_of_cabins' => array (
				'field'		=> 'number_of_cabins',
				'label' 	=> 'lang:cabin_settings_field_numb_of_cabins',
				'rules'		=> 'is_natural_no_zero|less_than[1000]',
		),
		'max_occupancy' => array (
				'field'		=> 'max_occupancy',
				'label' 	=> 'lang:cabin_settings_field_max_occupancy',
				'rules'		=> '',
		),
		'max_extra_beds' => array (
				'field'		=> 'max_extra_beds',
				'label' 	=> 'lang:cabin_settings_field_max_extra_beds',
				'rules'		=> '',
		),
		'max_children' => array (
				'field'		=> 'max_children',
				'label' 	=> 'lang:cabin_settings_field_max_children',
				'rules'		=> '',
		),
);


$config['bed_configuration'] = array(
		1 	=> 'bed_config_1_single_bed',
		2 	=> 'bed_config_2_single_beds',
		3 	=> 'bed_config_1_double_bed',
		4 	=> 'bed_config_2_double_beds',
		5 	=> 'bed_config_3_single_beds',
		6 	=> 'bed_config_3_double_beds',
		7 	=> 'bed_config_1_queen_bed',
		8 	=> 'bed_config_2_queen_beds',
		9 	=> 'bed_config_1_king_bed',
		10 	=> 'bed_config_2_king_beds',
		11 	=> 'bed_config_bunk_bed',
		12 	=> 'bed_config_sofa_bed',
		13 	=> 'bed_config_1_double_1_single_bed',
		14 	=> 'bed_config_1_double_2_single_beds',
		15  => 'bed_config_2_double_1_twin_bed'
);

