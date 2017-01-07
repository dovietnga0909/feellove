<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['sort_by'] = array(
	'popular' => array(
			'label' => lang('sort_popular'),
			'value' => 'popular',
			'selected' => true
		),
	
	'price' => array(
				'label' => lang('sort_price'),
				'value' => 'price',
				'selected' => false
		),
	
	'review' => array(
				'label' => lang('sort_review'),
				'value' => 'review',
				'selected' => false
		),

	'name' => array(
				'label' => lang('sort_name'),
				'value' => 'name',
				'selected' => false
		),
	'star' => array(
				'label' => lang('sort_star'),
				'value' => 'star',
				'selected' => false
		)
);

$config['filter_price'] = array(
	1 => array(
		'label' => lang('price_1'),
		'value' => 1,
		'selected' => false,
		'number' => 0
			
	),
	2=> array(
		'label' => lang('price_2'),
		'value' => 2,
		'selected' => false,
		'number' => 0
			
	),
	
	3=> array(
		'label' => lang('price_3'),
		'value' => 3,
		'selected' => false,
		'number' => 0
				
	),
		
	4=> array(
		'label' => lang('price_4'),
		'value' => 4,
		'selected' => false,
		'number' => 0
	
	),
	
	5=> array(
			'label' => lang('price_5'),
			'value' => 5,
			'selected' => false,
			'number' => 0
	
	)
);

$config['filter_star'] = array(
	5=> array(
		'label' => lang('star_5'),
		'value' => 5,
		'selected' => false,
		'number' => 0
	),
		
	4=> array(
		'label' => lang('star_4'),
		'value' => 4,
		'selected' => false,
		'number' => 0
	),
		
	3=> array(
		'label' => lang('star_3'),
		'value' => 3,
		'selected' => false,
		'number' => 0
	),
		
	2=> array(
		'label' => lang('star_2'),
		'value' => 2,
		'selected' => false,
		'number' => 0
	),
		
	1=> array(
		'label' => lang('star_1'),
		'value' => 1,
		'selected' => false,
		'number' => 0
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
);

$config['facility_groups'] = array(
		1 => lang('cruise_fa_fa'),
		2 => lang('cruise_fa_service'),
		3 => lang('cruise_fa_entertaiment')
);

$config['cabin_limit'] = 5;

$config['filter_limit'] = 10;

$config['destination_types'] = array(
		DESTINATION_TYPE_COUNTRY => lang('des_type_country'),
		DESTINATION_TYPE_CITY => lang('des_type_city'),
		DESTINATION_TYPE_DISTRICT => lang('des_type_district'),
		DESTINATION_TYPE_AREA => lang('des_type_area'),
		DESTINATION_TYPE_ATTRACTION => lang('des_type_attraction'),
		DESTINATION_TYPE_AIRPORT => lang('des_type_airport'),
		DESTINATION_TYPE_TRAIN_STATION => lang('des_type_train_station'),
		DESTINATION_TYPE_BUS_STOP => lang('des_type_bus_stop'),
		DESTINATION_TYPE_SHOPPING_AREA => lang('des_type_shopping_area'),
		DESTINATION_TYPE_HERITAGE => lang('des_type_heritage'),
		DESTINATION_TYPE_LAND_MARK => lang('des_type_land_mark'),
);

$config['tour_meals']  = array(
		1=>'Bữa sáng',
		2=>'Bữa trưa',
		//6=>'Brunch',
		//3=>'Picnic Lunch',
		4=>'Bữa tối',
		5=>'Không có',
);