<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['nav_panel'] = array(
		array(
				'link' 	=> '/cruises/profiles',
				'title' => 'cruise_mnu_profile',
				'icon'	=> 'fa-info',
		),
		array(
				'link' 	=> '/cruises/cruise_settings',
				'title' => 'cruise_mnu_cruise_settings',
				'icon'	=> 'fa-cog',
		),
		/*
		array(
				'link' 	=> '/cruises/map',
				'title' => 'cruise_mnu_map',
				'icon'	=> 'fa-map-marker',
		),*/
		array(
				'link' 	=> '/cruises/facilities',
				'title' => 'cruise_mnu_facility',
				'icon'	=> 'fa-check-square-o',
		),
		array(
				'link' 	=> '/cruises/photos',
				'title' => 'cruise_mnu_photo',
				'icon'	=> 'fa-photo',
		),
		array(
				'link' 	=> '/cruises/cabins',
				'title' => 'cruise_mnu_cabins',
				'icon'	=> 'fa-home',
		),
		array(
				'link' 	=> '/cruises/cabin_settings',
				'title' => 'cruise_mnu_cabin_settings',
				'icon'	=> 'fa-cogs',
		),
		array(
				'link' 	=> '/cruises/tours',
				'title' => 'cruise_mnu_cruise_tours',
				'icon'	=> 'fa-suitcase',
		),
);

$config['cruise_star'] = array(5, 4.5, 4, 3.5, 3, 2.5, 2, 1.5, 1);

$config['cruise_type'] = array(
		1 => 'cruise_sharing',
		2 => 'cruise_private',
		3 => 'cruise_day',
);

$config['max_infant_age'] 	= 6;
$config['max_children_age'] = 18;

$config['cruise_rules'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:cruises_field_name',
				'len'		=> 200,
				'rules'		=> 'required|trim|xss_clean|max_length[200]|callback_cruise_name_check',
		),
		'address' => array (
				'field'		=> 'address',
				'label' 	=> 'lang:cruises_field_address',
				'len'		=> 200,
				'rules'		=> 'required|max_length[200]',
		),
		/*
		'destination_id' => array (
				'field'		=> 'destination_id',
				'label' 	=> 'lang:cruises_field_cruise_area',
				'rules'		=> 'required',
		),
		*/
		'cruise_type' => array (
				'field'		=> 'cruise_type',
				'label' 	=> 'lang:cruises_field_cruise_type',
				'rules'		=> 'required',
		),
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> '',
		),
		'star' => array (
				'field'		=> 'star',
				'label' 	=> 'lang:cruises_field_star',
				'rules'		=> 'required',
		),
		'description' => array (
				'field'		=> 'description',
				'label' 	=> 'lang:cruises_field_description',
				'len'		=> 5000,
				'rules'		=> 'required|max_length[5000]',
		),
);

$config['cruise_rules_addition'] = array(
		'partner_id' => array (
				'field'		=> 'partner_id',
				'label' 	=> 'lang:cruises_field_partner',
				'rules'		=> 'required',
		),
);

$config['cruise_settings'] = array(
		'check_in' 	=> array (
				'field'		=> 'check_in',
				'label' 	=> 'lang:cruises_field_check_in',
				'rules'		=> 'required',
		),
		'check_out' 	=> array (
				'field'		=> 'check_out',
				'label' 	=> 'lang:cruises_field_check_out',
				'rules'		=> 'required',
		),
		'shuttle_bus' 	=> array (
				'field'		=> 'shuttle_bus',
				'label' 	=> 'lang:cruises_field_shuttle_bus',
				'rules'		=> '',
		),
		'guide' 		=> array (
				'field'		=> 'guide',
				'label' 	=> 'lang:cruises_field_guide',
				'rules'		=> '',
		),
		'default_cancellation' 	=> array (
				'field'		=> 'default_cancellation',
				'label' 	=> 'lang:cruises_field_default_cancellcation',
				'rules'		=> 'required',
		),
		'infant_age_util' 	=> array (
				'field'		=> 'infant_age_util',
				'label' 	=> 'lang:cruises_field_infant_age_util',
				'rules'		=> 'required',
		),
		'children_age_to' 	=> array (
				'field'		=> 'children_age_to',
				'label' 	=> 'lang:cruises_field_children_age_from',
				'rules'		=> 'required|callback_children_age_check',
		),
		'extra_bed_requires_from' => array (
				'field'		=> 'extra_bed_requires_from',
				'label' 	=> 'lang:cruises_field_extra_bed_requires_from',
				'rules'		=> '',
		),
		'children_stay_free' => array (
				'field'		=> 'children_stay_free',
				'label' 	=> 'lang:cruise_settings_children_stay_free',
				'rules'		=> '',
		),
		'infants_policy' => array (
				'field'		=> 'infants_policy',
				'label' 	=> 'lang:cruises_field_infants',
				'len'		=> 500,
				'rules'		=> 'required|max_length[500]',
		),
		'children_policy' => array (
				'field'		=> 'children_policy',
				'label' 	=> 'lang:cruises_field_children',
				'len'		=> 500,
				'rules'		=> 'required|max_length[500]',
		),
		'extra_cancellation'=> array (
				'field'		=> 'extra_cancellation',
				'label' 	=> 'lang:cruises_field_extra_cancellation',
				'rules'		=> '',
		),
);

$config['cruise_map'] = array(
		'latitude' 	=> array (
				'field'		=> 'latitude',
				'label' 	=> 'lang:cruises_field_latitude',
				'rules'		=> 'required',
		),
		'longitude' 	=> array (
				'field'		=> 'longitude',
				'label' 	=> 'lang:cruises_field_longitude',
				'rules'		=> 'required',
		),
);

$config['create_facility'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:facilities_field_name',
				'len'		=> 200,
				'rules'		=> 'required|xss_clean|max_length[200]',
		),
		'group_id' => array (
				'field'		=> 'group_id',
				'label' 	=> 'lang:facilities_field_group',
				'rules'		=> '',
		),
);

$config['cruise_photo'] = array(
		'upload_path' 	=> '../images/cruises/uploads/',
		'allowed_types' => 'gif|jpg|jpeg|png',
		'max_width' 	=> '2048',
		'max_height' 	=> '1536',
		'max_size' 		=> '2048',
);

$config['cruise_photo_size'] = array(
		'large' => array (
				'path'	=> '../images/cruises/large/',
				'size' 	=> array(
							//array('width' => 800, 'height' => 600),
							array('width' => 416, 'height' => 312)
						),
		),
		'medium' => array (
				'path'	=> '../images/cruises/medium/',
				'size' 	=> array(
							array('width' => 268, 'height' => 201),
							array('width' => 200, 'height' => 150)
						),
		),
		'small' => array (
				'path'	=> '../images/cruises/small/',
				'size' 	=> array(
							array('width' => 160, 'height' => 120),
							array('width' => 120, 'height' => 90)
						),
		),
);

$config['cruise_photo_type'] = array(
		1 	=> 'Photo Gallery',
		2 	=> 'Cruise Main Photo',
		3 	=> 'Cabin Photo',
);

$config['cruise_tour_surcharges'] = array(
		1 => lang('sur_all_tours'),
		2 => lang('sur_specific_tours')
);

$config['cruise_surcharge'] = array(
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

		'adult_amount' => array(
				'field' => 'adult_amount',
				'label' => 'lang:sur_field_adult_amount',
				'rules' => 'required',
		),
		'children_amount' => array(
				'field' => 'children_amount',
				'label' => 'lang:sur_field_children_amount',
				'rules' => '',
		),

		'apply_all' => array(
				'field' => 'apply_all',
				'label' => 'lang:sur_field_apply_all_tours',
				'rules' => '',
		),
		'description' => array(
				'field' => 'description',
				'label' => 'lang:sur_field_description',
				'rules' => 'xss_clean',
		),

);