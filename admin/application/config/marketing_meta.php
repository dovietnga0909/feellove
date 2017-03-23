<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['mk_nav_panel'] = array(
	array(
			'link' 	=> '/marketings/',
			'title' => 'mk_mn_pro',
	),

	array(
			'link' 	=> '/marketings/vouchers/',
			'title' => 'mk_mn_voucher',
	),
	
	array(
			'link' 	=> '/newsletters/',
			'title' => 'mk_mn_newsletter',
	),
);

$config['pro_nav_panel'] = array(
		array(
				'link' 	=> '/marketings/edit-pro/',
				'title' => 'mk_mn_edit_pro',
		),

		array(
				'link' 	=> '/marketings/hotel-pro/',
				'title' => 'mk_mn_hotel_pro',
		),
		
		array(
				'link' 	=> '/marketings/cruise-pro/',
				'title' => 'mk_mn_cruise_pro',
		),
		
		array(
				'link' 	=> '/marketings/tour-pro/',
				'title' => 'mk_mn_tour_pro',
		),
);


$config['number_vouchers'] = array(1, 5, 10, 20, 50);

$config['voucher'] = array(
	
		'customer_name' => array (
				'field'	=> 'customer_name',
				'label' => 'lang:voucher_field_customer',
				'rules'	=> '',
		),
		
		'customer_id' => array (
				'field'	=> 'customer_id',
				'label' => 'lang:voucher_field_customer',
				'rules'	=> '',
		),
	
		'expired_date' => array(
				'field' => 'expired_date',
				'label' => 'lang:field_expired_date',
				'rules' => 'required',
		),
		
		'amount' => array(
				'field' => 'amount',
				'label' => 'lang:voucher_field_amount',
				'rules' => 'required',
		),
		
		'number_voucher' => array(
				'field' => 'number_voucher',
				'label' => 'lang:voucher_field_number',
				'rules' => '',
		),
		
		'delivered' => array(
				'field' => 'delivered',
				'label' => 'lang:voucher_field_delivered',
				'rules' => '',
		),
		
		'status' => array(
				'field' => 'status',
				'label' => 'lang:field_status',
				'rules' => '',
		),
		
);

$config['voucher_status'] = array(
	0 => lang('voucher_status_new'),
	1 => lang('voucher_status_pending'),
	2 => lang('voucher_status_used')
);

$config['voucher_delivered'] = array(
	0 => lang('voucher_delivered_no'),
	1 => lang('voucher_delivered_yes'),
);

$config['voucher_edit'] = array(
	0 => lang('voucher_field_code'),
	1 => lang('voucher_field_amount'),
	2 => lang('field_expired_date'),
	3 => lang('voucher_field_status'),
	4 => lang('voucher_field_used'),
	5 => lang('voucher_log')
);

$config['hotel_discount_types'] = array(
	1 => lang('discount_percentage'),
	2 => lang('discount_amount'),
);

$config['flight_discount_types'] = array(
	3 => lang('discount_amount_ticket'),
	2 => lang('discount_amount'),
	1 => lang('discount_percentage'),
);

$config['cruise_discount_types'] = array(
	1 => lang('discount_percentage'),
	2 => lang('discount_amount'),
	4 => lang('discount_amount_pax')
);

$config['tour_discount_types'] = array(
	1 => lang('discount_percentage'),
	2 => lang('discount_amount'),
	4 => lang('discount_amount_pax')
);

$config['promotions'] = array(

	'name' => array (
			'field'	=> 'name',
			'label' => 'lang:pro_field_name',
			'rules'	=> 'required',
	),
		
	'code' => array (
			'field'	=> 'code',
			'label' => 'lang:pro_field_code',
			'rules'	=> '',
	),

	'status' => array (
			'field'	=> 'status',
			'label' => 'lang:pro_field_status',
			'rules'	=> '',
	),
	
	'public' => array (
			'field'	=> 'public',
			'label' => 'lang:pro_field_public',
			'rules'	=> '',
	),
		
	'public' => array (
			'field'	=> 'apply_all',
			'label' => 'lang:pro_field_apply_all',
			'rules'	=> '',
	),
    
    'is_multiple_time' => array (
        'field'	=> 'is_multiple_time',
        'label' => 'lang:pro_field_is_multiple_time',
        'rules'	=> '',
    ),
	
	'expired_date' => array(
			'field' => 'expired_date',
			'label' => 'lang:pro_field_expired_date',
			'rules' => 'required',
	),
		
	'discount_note' => array(
			'field' => 'discount_note',
			'label' => 'lang:pro_field_discount_note',
			'rules' => '',
	),

	'description' => array(
			'field' => 'description',
			'label' => 'lang:pro_field_description',
			'rules' => '',
	),

	'max_nr_booked' => array(
			'field' => 'max_nr_booked',
			'label' => 'lang:pro_field_max_booked',
			'rules' => 'required|is_natural_no_zero',
	),

	'init_nr_booked' => array(
			'field' => 'init_nr_booked',
			'label' => 'lang:pro_field_init_booked',
			'rules' => 'is_natural',
	),
		
	
	'hotel_discount_type' => array(
			'field' => 'hotel_discount_type',
			'label' => 'lang:pro_field_hotel_discount_type',
			'rules' => '',
	),
		
	'hotel_get' => array(
			'field' => 'hotel_get',
			'label' => 'lang:pro_field_hotel_get',
			'rules' => '',
	),
		
	'hotel_get_max' => array(
			'field' => 'hotel_get_max',
			'label' => 'lang:pro_field_hotel_get_max',
			'rules' => '',
	),
		
		
	'flight_discount_type' => array(
			'field' => 'flight_discount_type',
			'label' => 'lang:pro_field_flight_discount_type',
			'rules' => '',
	),
	
	'flight_get' => array(
			'field' => 'flight_get',
			'label' => 'lang:pro_field_flight_get',
			'rules' => '',
	),
	
	'flight_get_max' => array(
			'field' => 'flight_get_max',
			'label' => 'lang:pro_field_flight_get_max',
			'rules' => '',
	),
		
	'cruise_discount_type' => array(
			'field' => 'cruise_discount_type',
			'label' => 'lang:pro_field_cruise_discount_type',
			'rules' => '',
	),
	
	'cruise_get' => array(
			'field' => 'cruise_get',
			'label' => 'lang:pro_field_cruise_get',
			'rules' => '',
	),
	
	'cruise_get_max' => array(
			'field' => 'cruise_get_max',
			'label' => 'lang:pro_field_cruise_get_max',
			'rules' => '',
	),
	'tour_discount_type' => array(
			'field' => 'tour_discount_type',
			'label' => 'lang:pro_field_tour_discount_type',
			'rules' => '',
	),
	'tour_get' => array(
			'field' => 'tour_get',
			'label' => 'lang:pro_field_tour_get',
			'rules' => '',
	),
	
	'tour_get_max' => array(
			'field' => 'tour_get_max',
			'label' => 'lang:pro_field_tour_get_max',
			'rules' => '',
	),
);


