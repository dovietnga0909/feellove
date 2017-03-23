<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['facility_types'] = array(
		1 => 'facilities_type_hotel',
		2 => 'facilities_type_room',
		3 => 'facilities_type_cruise',
		4 => 'facilities_type_cabin',
);
$config['facility_groups'] = array(
		1 => 'facilities_group_general',
		2 => 'facilities_group_serivce',
		3 => 'facilities_group_entertainment',
);

$config['create_facility'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:facilities_field_name',
				'len'		=> 200,
				'rules'		=> 'required|trim|xss_clean|max_length[200]|callback_facility_name_check',
		),
		'type_id' => array (
				'field'		=> 'type_id',
				'label' 	=> 'lang:facilities_field_type',
				'rules'		=> 'required',
		),
		'group_id' => array (
				'field'		=> 'group_id',
				'label' 	=> 'lang:facilities_field_group',
				'rules'		=> '',
		),
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> '',
		),
		'is_important' => array (
				'field'		=> 'is_important',
				'label' 	=> 'lang:facilities_field_is_important',
				'rules'		=> '',
		),
);

