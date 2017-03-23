<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['create_review'] = array(
		'customer_id' => array (
				'field'		=> 'customer_id',
				'label' 	=> 'lang:reviews_field_cus_name',
				'rules'		=> '',
		),
		'customer_name' => array (
				'field'		=> 'customer_name',
				'label' 	=> 'lang:reviews_field_cus_name',
				'rules'		=> 'required',
		),
		'customer_type' => array (
				'field'		=> 'customer_type',
				'label' 	=> 'lang:reviews_field_cus_type',
				'rules'		=> 'required',
		),
		'customer_city' => array (
				'field'		=> 'customer_city',
				'label' 	=> 'lang:reviews_field_cus_city',
				'rules'		=> 'required',
		),
		'review_date' => array (
				'field'		=> 'review_date',
				'label' 	=> 'lang:reviews_field_date',
				'rules'		=> 'required',
		),
		'review_for' => array (
				'field'		=> 'review_for',
				'label' 	=> 'lang:reviews_field_for',
				'len'		=> 200,
				'rules'		=> '',
		),
		'title' => array (
				'field'		=> 'title',
				'label' 	=> 'lang:reviews_field_title',
				'rules'		=> '',
		),
		'review_content' => array (
				'field'		=> 'review_content',
				'label' 	=> 'lang:reviews_field_review_content',
				'rules'		=> 'required',
		),
		'hotel_id' => array (
				'field'		=> 'hotel_id',
				'label' 	=> 'lang:reviews_field_cus_name',
				'rules'		=> '',
		),
		'tour_id' => array (
				'field'		=> 'tour_id',
				'label' 	=> 'lang:reviews_field_cus_name',
				'rules'		=> '',
		),
);

$config['customer_types'] = array(
		1 => 'family',
		2 => 'couple',
		3 => 'solo',
		4 => 'business',
);

$config['score_types']  = array(
				 
		HOTEL => array(
				TYPE_LOCATION 		=> 'rev_location',
				TYPE_COMFORT 		=> 'rev_comfort',
				TYPE_SERVICES 		=> 'rev_services',
				TYPE_STAFF 			=> 'rev_staff',
				TYPE_CLEAN 			=> 'rev_clean',
		),

		CRUISE => array(
				TYPE_CABIN_QUALITY 			=> 'rev_cabin_quality',
				TYPE_SERVICES 				=> 'rev_services',
				TYPE_DINING_FOOD 			=> 'rev_dining_food',
				TYPE_ENTERTAIMENT_ACTIVITY 	=> 'rev_entertainment_activity',
				TYPE_STAFF_QUALITY 			=> 'rev_guide_quality',
		),
);






