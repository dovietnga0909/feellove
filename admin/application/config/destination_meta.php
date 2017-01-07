<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['nav_panel'] = array(
		array(
			'link' 	=> 'destinations/edit',
			'title' => 'destination_mnu_details',
		),
		array(
			'link' 	=> 'destinations/map',
			'title' => 'destination_mnu_map',
		),
		array(
			'link' 	=> 'destinations/flight',
			'title' => 'destination_mnu_flight_destination',
		),
		array(
			'link'	=> 'destinations/tour',
			'title'	=> 'destination_mnu_tour_destination',
		),
		array(
			'link'	=> 'destinations/activities',
			'title'	=> 'destination_mnu_activity',
		),
		array(
			'link'	=> 'destinations/photos',
			'title'	=> 'destination_mnu_photo',
		)
);

$config['create_destination'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:destinations_field_name',
				'len'		=> 200,
				'rules'		=> 'required|xss_clean|max_length[200]|callback_destination_name_check',
		),
		'marketing_title' => array(
				'field'		=> 'marketing_title',
				'label' 	=> 'lang:marketing_title',
				'len'		=> 500,
				'rules'		=> 'xss_clean|max_length[200]|callback_marketing_title',
		),
		'type' => array (
				'field'		=> 'type',
				'label' 	=> 'lang:destinations_field_type',
				'rules'		=> 'required',
		),
		'parent_id' => array (
				'field'		=> 'parent_id',
				'label' 	=> 'lang:destinations_field_parent_destination',
				'rules'		=> '',
		),
		'description_short' =>array(
				'field'		=> 'description_short',
				'label' 	=> 'lang:description_short',
				'len'		=> 5000,
				'rules'		=> 'max_length[5000]',
		),
		'description' => array (
				'field'		=> 'description',
				'label' 	=> 'lang:hotels_field_description',
				'len'		=> 5000,
				'rules'		=> 'max_length[5000]',
		),
		'is_top_hotel' => array (
				'field'		=> 'is_top_hotel',
				'label' 	=> 'lang:destinations_field_is_hotel_top',
				'rules'		=> '',
		),
		
);

$config['destination_rules_addition'] = array(
    'keywords' => array (
        'field'		=> 'keywords',
        'label' 	=> 'lang:destinations_field_keywords',
        'rules'		=> 'required',
    ),
);

$config['destination_map'] = array(
		'latitude' 	=> array (
				'field'		=> 'latitude',
				'label' 	=> 'lang:destinations_field_latitude',
				'rules'		=> 'required',
		),
		'longitude' 	=> array (
				'field'		=> 'longitude',
				'label' 	=> 'lang:destinations_field_longitude',
				'rules'		=> 'required',
		),
);

$config['destination_types'] = array(
		array('label'=>'continent', 'value' =>1),
		array('label'=>'region', 'value' =>2),
		 
		array('label'=>'country', 'value' =>3),
		array('label'=>'province/city', 'value' =>4),
		array('label'=>'district', 'value' =>5),
		array('label'=>'area', 'value' =>6),
		array('label'=>'attraction', 'value' =>7),
		 
		array('label'=>'transportation', 'value'=>array(
				10    => 'airport',
				11    => 'train_station',
				12    => 'bus_stop',
		)),
		 
		array('label'=>'place_of_interest', 'value'=>array(
				20    => 'shopping_areas',
				21    => 'heritage',
				22    => 'landmark',
		)),
);

$config['flight_destination'] = array(
		'destination_code' 	=> array (
				'field'		=> 'destination_code',
				'label' 	=> 'lang:destinations_field_latitude',
				'rules'		=> 'callback_destination_code_check',
		),
		'is_flight_destination' 	=> array (
				'field'		=> 'is_flight_destination',
				'label' 	=> 'lang:destinations_field_is_flight_destination',
				'rules'		=> '',
		),
);

$config['tour_destination'] = array(
		'is_tour_highlight_destination' 	=> array (
				'field'		=> 'is_tour_highlight_destination',
				'label' 	=> 'lang:destinations_field_is_tour_highlight_destination',
				'rules'		=> '',
		),
		'is_tour_destination_group' 		=> array (
				'field'		=> 'is_tour_destination_group',
				'label' 	=> 'lang:destinations_field_is_tour_destination_group',
				'rules'		=> '',
		),
		'is_tour_includes_all_children_destination' 	=> array (
				'field'		=> 'is_tour_includes_all_children_destination',
				'label' 	=> 'lang:destinations_field_is_tour_includes_all_children_destination',
				'rules'		=> '',
		),
		'is_tour_departure_destination' 	=> array (
				'field'		=> 'is_tour_departure_destination',
				'label' 	=> 'lang:destinations_field_is_tour_departure_destination',
				'rules'		=> '',
		),
		'travel_tip' => array(
				'field'		=> 'travel_tip',
				'label' 	=> 'lang:travel_tip',
				'len'		=> 5000,
				'rules'		=> 'max_length[5000]',
		),
);

$config['create_activity']			= array(
		'name'			=> array(
				'field'		=> 'name',
				'label'		=> 'lang:destinations_field_activity_name',
				'rules'		=> '',
		),
		'destination'	=> array(
				'field'		=> 'destination',
				'label'		=> 'lang:destination_field_activity_destination',
				'rules'		=> '',
		),
		'status'		=> array(
				'field'		=> 'status',
				'label'		=> 'lang:destination_field_activity_status',
				'rules'		=> '',
		),
); 

$config['destination_photo'] = array(
		'upload_path' 	=> '../images/destinations/uploads/',
		'allowed_types' => 'gif|jpg|jpeg|png',
		'max_width' 	=> '2048',
		'max_height' 	=> '1536',
		'max_size' 		=> '2048',
);

$config['destination_photo_size'] = array(
		'large' => array (
				'path'	=> '../images/destinations/large/',
				'size' 	=> array(
							//array('width' => 800, 'height' => 600),
							array('width' => 416, 'height' => 312)
						),
		),
		'medium' => array (
				'path'	=> '../images/destinations/medium/',
				'size' 	=> array(
							array('width' => 268, 'height' => 201),
							array('width' => 200, 'height' => 150)
						),
		),
		'small' => array (
				'path'	=> '../images/destinations/small/',
				'size' 	=> array(
							array('width' => 160, 'height' => 120),
							array('width' => 120, 'height' => 90)
						),
		),
);



$config['destination_photo_type'] = array(
		1 	=> 'Photo Gallery',
		2 	=> 'Destination Main Photo',
);



