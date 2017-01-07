<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['nav_panel'] = array(
		array(
			'link' 	=> '/tours/profiles',
			'title' => 'tour_mnu_profile',
			'icon'	=> 'fa-edit',
		),
		array(
			'link' 	=> '/tours/photos',
			'title' => 'tour_mnu_photo',
			'icon'	=> 'fa-photo',
		),
		array(
			'link' 	=> '/tours/accommodations',
			'title' => 'tour_mnu_accommodations',
			'icon'	=> 'fa-home',
		),
		array(
			'link' 	=> '/tours/itinerary',
			'title' => 'tour_mnu_itinerary',
			'icon'	=> 'fa-file-text-o',
		),
        array(
            'link' 	=> '/tours/category',
            'title' => 'tour_mnu_category',
            'icon'	=> 'fa-check-square-o',
        ),
        array(
            'link' 	=> '/tours/departure',
            'title' => 'tour_mnu_departure',
            'icon'	=> 'fa-flag',
        ),
        array(
            'link' 	=> '/tours/tour_settings',
            'title' => 'tour_mnu_settings',
            'icon'	=> 'fa-cogs',
        ),
);

$config['nav_panel_tour'] = array(
		array(
				'link' 	=> '/tours/',
				'title' => 'mnu_tours',
				'icon'	=> 'fa-suitcase',
		),
		array(
				'link' 	=> '/categories/',
				'title' => 'mnu_categories',
				'icon'	=> 'fa-th-large',
		),
);

$config['max_infant_age'] 	= 6;
$config['max_children_age'] = 18;

$config['tour_rules'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:tours_field_name',
				'len'		=> 200,
				'rules'		=> 'required|trim|xss_clean|max_length[200]|callback_tour_name_check',
		),
		'short_name' => array (
				'field'		=> 'short_name',
				'label' 	=> 'lang:tours_field_short_name',
				'len'		=> 200,
				'rules'		=> '',
		),
		'code' => array (
				'field'		=> 'code',
				'label' 	=> 'lang:tours_field_code',
				'len'		=> 100,
				'rules'		=> 'trim|xss_clean|max_length[100]|callback_tour_code_check',
		),
		'route_ids' => array (
				'field'		=> 'route_ids',
				'label' 	=> 'lang:tours_field_route',
				'rules'		=> 'required',
		),
		'route_hidden_ids' 	=> array (
				'field'		=> 'route_hidden_ids',
				'label' 	=> 'lang:tours_field_route',
				'rules'		=> '',
		),
        'land_tour_ids' 	=> array (
            'field'		=> 'land_tour_ids',
            'label' 	=> 'lang:tours_field_route',
            'rules'		=> '',
        ),
		'cruise_id' => array (
				'field'		=> 'cruise_id',
				'label' 	=> 'lang:tours_field_cruise',
				'rules'		=> '',
		),
		'duration' => array (
				'field'		=> 'duration',
				'label' 	=> 'lang:tours_field_duration',
				'rules'		=> 'required|greater_than[0]',
		),
        'night' => array (
            'field'		=> 'night',
            'label' 	=> 'lang:tours_field_night',
            'rules'		=> 'is_natural|callback_tour_night_check',
        ),
		'departure_type' => array (
				'field'		=> 'departure_type',
				'label' 	=> 'lang:tours_field_departure_type',
				'rules'		=> 'required',
		),
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> '',
		),
		'description' => array (
				'field'		=> 'description',
				'label' 	=> 'lang:tours_field_description',
				'len'		=> 5000,
				'rules'		=> 'required|max_length[5000]',
		),
		'tour_highlight' => array (
				'field'		=> 'tour_highlight',
				'label' 	=> 'lang:tours_field_tour_highlight',
				'len'		=> 5000,
				'rules'		=> 'required|max_length[5000]',
		),
		'service_includes' => array (
				'field'		=> 'service_includes',
				'label' 	=> 'lang:tours_field_service_includes',
				'len'		=> 5000,
				'rules'		=> 'required|max_length[5000]',
		),
		'service_excludes' => array (
				'field'		=> 'service_excludes',
				'label' 	=> 'lang:tours_field_service_excludes',
				'len'		=> 5000,
				'rules'		=> 'required|max_length[5000]',
		),
		'notes' => array (
				'field'		=> 'notes',
				'label' 	=> 'lang:tours_field_notes',
				'len'		=> 5000,
				'rules'		=> '',
		),
);

$config['tour_rules_addition'] = array(
		'partner_id' => array (
				'field'		=> 'partner_id',
				'label' 	=> 'lang:tours_field_partner',
				'rules'		=> 'required',
		),
);

$config['tour_photo'] = array(
		'upload_path' 	=> '../images/tours/uploads/',
		'allowed_types' => 'gif|jpg|jpeg|png',
		'max_width' 	=> '2048',
		'max_height' 	=> '1536',
		'max_size' 		=> '2048',
);

$config['tour_photo_size'] = array(
		'large' => array (
				'path'	=> '../images/tours/large/',
				'size' 	=> array(
							//array('width' => 800, 'height' => 600),
							array('width' => 416, 'height' => 312)
						),
		),
		'medium' => array (
				'path'	=> '../images/tours/medium/',
				'size' 	=> array(
							array('width' => 268, 'height' => 201),
							array('width' => 200, 'height' => 150)
						),
		),
		'small' => array (
				'path'	=> '../images/tours/small/',
				'size' 	=> array(
							array('width' => 160, 'height' => 120),
							array('width' => 120, 'height' => 90)
						),
		),
);

$config['tour_photo_type'] = array(
		1 	=> 'Photo Gallery',
		2 	=> 'Tour Main Photo',
);

$config['departure_type'] = array(
    SINGLE_DEPARTING_FROM 	    => 'single_departing_from',
    MULTIPLE_DEPARTING_FROM 	=> 'multiple_departing_from',
);

$config['domistic_outbound'] = array(
	0 => 'domistic_tour',
	1 => 'outbound_tour',
);

$config['tour_departure_date_type'] = array(
    DEPARTURE_DAILY                 => 'daily',
    DEPARTURE_SPECIFIC_WEEKDAYS     => 'specific_weekdays',
    DEPARTURE_SPECIFIC_DATES 	    => 'specific_dates',
);

$config['tour_departure_rules'] = array(
    'destination_id' => array (
        'field'		=> 'destination_id',
        'label' 	=> 'lang:tours_field_departing_from',
        'rules'		=> 'required',
    ),
    'departure_date_type' => array (
        'field'		=> 'departure_date_type',
        'label' 	=> 'lang:tours_field_departure_date_type',
        'rules'		=> 'required',
    ),
    'service_includes' => array (
        'field'		=> 'service_includes',
        'label' 	=> 'lang:tours_field_service_includes',
        'rules'		=> '',
    ),
    'service_excludes' => array (
        'field'		=> 'service_excludes',
        'label' 	=> 'lang:tours_field_service_excludes',
        'rules'		=> '',
    ),
);

$config['weekdays_departure_rules'] = array(
    'start_date' => array (
	    'field'		=> 'start_date',
	    'label' 	=> 'lang:tours_field_start_date',
	    'rules'		=> 'required|callback_rate_date_check',
	),
	
	'end_date' => array (
	    'field'		=> 'end_date',
	    'label' 	=> 'lang:tours_field_end_date',
	    'rules'		=> 'required|callback_rate_date_check',
	),
    'week_day' => array (
        'field'		=> 'week_day[]',
        'label' 	=> 'lang:tours_field_weekdays',
        'rules'		=> 'required',
    ),
);

$config['tour_settings'] = array(
    'default_cancellation' 	=> array (
        'field'		=> 'default_cancellation',
        'label' 	=> 'lang:tours_field_default_cancellcation',
        'rules'		=> 'required',
    ),
    'infant_age_util' 	=> array (
        'field'		=> 'infant_age_util',
        'label' 	=> 'lang:tours_field_infant_age_util',
        'rules'		=> 'required',
    ),
    'children_age_to' 	=> array (
        'field'		=> 'children_age_to',
        'label' 	=> 'lang:tours_field_children_age_from',
        'rules'		=> 'required|callback_children_age_check',
    ),
    'infants_policy' => array (
        'field'		=> 'infants_policy',
        'label' 	=> 'lang:tours_field_infants',
        'len'		=> 500,
        'rules'		=> 'required|max_length[500]',
    ),
    'children_policy' => array (
        'field'		=> 'children_policy',
        'label' 	=> 'lang:tours_field_children',
        'len'		=> 500,
        'rules'		=> 'required|max_length[500]',
    ),
    'extra_cancellation'=> array (
        'field'		=> 'extra_cancellation',
        'label' 	=> 'lang:tours_field_extra_cancellation',
        'rules'		=> '',
    ),
);


$config['tour_duration_search']  = array(
    0 => 'all_duration',
    1 => '1day',
    2 => '2days',
    3 => '3days',
    4 => '4days',
    5 => '5days',
    6 => '6days',
    7 => '7days',
    8 => '8days',
    9 => '9days',
    10 => '10days',
    11 => '11days',
    12 => '12days',
    13 => '13days',
    14 => '14days',
    15 => '15days',
    16 => 'over15days',
);

$config['itinerary_path'] = 'itinerary/';
