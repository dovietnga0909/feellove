<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['advertise'] = array(
	'name' => array (
	    'field'	=> 'name',
	    'label' => 'lang:ad_field_name',
	    'rules'	=> 'required|xss_clean|callback_ad_name_check',
	),
	
	'status' => array(
		'field' => 'status',
		'label' => 'lang:field_status',
		'rules' => 'required',		
	),
	
	'data_type' => array(
		'field' => 'data_type',
		'label' => 'lang:ad_field_data_type',
		'rules' => 'required',		
	),
	
	'start_date' => array(
		'field' => 'start_date',
		'label' => 'lang:field_start_date',
		'rules' => 'required|callback_ad_date_check',		
	),
		
	'end_date' => array(
		'field' => 'end_date',
		'label' => 'lang:field_end_date',
		'rules' => 'required|callback_ad_date_check',		
	),
	
	'week_day' => array(
		'field' => 'week_day[]',
		'label' => 'lang:ad_field_week_day',
		'rules' => 'required',		
	),
	/*
	'show_time_from' => array(
		'field' => 'show_time_from',
		'label' => 'lang:ad_field_show_time_from',
		'rules' => '',		
	),
	
	'show_time_to' => array(
		'field' => 'show_time_to',
		'label' => 'lang:ad_field_show_time_to',
		'rules' => '',		
	),

	'display_on' => array(
		'field' => 'display_on[]',
		'label' => 'lang:ad_field_display_on',
		'rules' => '',		
	),*/
	
	'link' => array(
		'field' => 'link',
		'label' => 'lang:ad_field_link',
		'rules' => 'required',		
	)
);

$config['display_setting'] = array(
	'display_on' => array(
		'field' => 'display_on[]',
		'label' => 'lang:ad_field_display_on',
		'rules' => 'required',		
	),

	'ad_area' => array(
		'field' => 'ad_area[]',
		'label' => 'lang:ad_field_ad_area',
		'rules' => 'required',
	),
		
	'all_hotel_des' => array(
		'field' => 'all_hotel_des',
		'label' => 'lang:ad_select_all_hotel_des',
		'rules' => '',		
	),
	'all_flight_des' => array(
		'field' => 'all_flight_des',
		'label' => 'lang:ad_select_all_flight_des',
		'rules' => '',		
	),
	'all_tour_des' => array(
		'field' => 'all_tour_des',
		'label' => 'lang:ad_select_all_tour_des',
		'rules' => '',		
	),
	'all_tour_cat_des'	=> array(
		'field' => 'all_tour_cat_des',
		'label' => 'lang:ad_select_all_tour_cat_des',
		'rules' => '',	
	),
	'hotel_des' => array(
		'field' => 'hotel_des[]',
		'label' => 'lang:ad_field_hotel_des',
		'rules' => '',		
	),
	
	'flight_des' => array(
		'field' => 'flight_des[]',
		'label' => 'lang:ad_field_flight_des',
		'rules' => '',		
	),
	'tour_des' => array(
		'field' => 'tour_des[]',
		'label' => 'lang:ad_field_tour_des',
		'rules' => '',		
	),
	'tour_cat_des'	=> array(
		'field'	=> 'tour_cat_des[]',
		'label'	=> 'lang:ad_field_tour_cat_des',
		'rules'	=> '',
	),
);

$config['ad_data_types'] = array(
	1 => lang('ad_type_general'),
	2 => lang('ad_type_hotel'),
	3 => lang('ad_type_flight'),
	4 => lang('ad_type_package'),
	5 => lang('ad_type_tour'),
);

$config['ad_areas'] = array(
	0 => lang('ad_area_1'),
	1 => lang('ad_area_2'),
	2 => lang('ad_area_3')
	/*
	3 => lang('ad_area_4'),
	4 => lang('ad_area_5'),*/
);

$config['ad_pages'] = array(
	AD_PAGE_HOME 				=> lang('ad_page_home'),
	AD_PAGE_HOTEL_HOME 			=> lang('ad_page_hotel_home'),
	AD_PAGE_HOTEL_DESTINATION 	=> lang('ad_page_hotel_destination'),
	AD_PAGE_FLIGHT 				=> lang('ad_page_flight_home'),
	AD_PAGE_FLIGHT_DESTINATION 	=> lang('ad_page_flight_destination'),
	AD_PAGE_DEAL 				=> lang('ad_page_deal'),
	AD_PAGE_CRUISE_HOME 		=> lang('ad_page_cruise_home'),
	AD_PAGE_TOUR_HOME			=> lang('ad_page_tour_home'),
	AD_PAGE_TOUR_DOMISTIC		=> lang('ad_page_tour_domistic'),
	AD_PAGE_TOUR_OUTBOUND		=> lang('ad_page_tour_outbound'),
	AD_PAGE_TOUR_DESTINATION	=> lang('ad_page_tour_destination'),
	AD_PAGE_TOUR_CATEGORY		=> lang('ad_page_tour_category'),
);

$config['ad_nav_panel'] = array(
		array(
			'link' 	=> '/advertises/edit/',
			'title' => 'ad_mnu_edit_basic',
		),
		
		array(
			'link' 	=> '/advertises/display/',
			'title' => 'ad_mnu_display_setting',
		),
		
		array(
			'link' 	=> '/advertises/photo/',
			'title' => 'ad_mnu_photo',
		),
	
);

$config['advertise_photo'] = array(
		'upload_path' 	=> '../images/advertises/',
		'allowed_types' => 'gif|jpg|jpeg|png',
		'max_width' 	=> '2048',
		'max_height' 	=> '1536',
		'max_size' 		=> '2048',
); 


