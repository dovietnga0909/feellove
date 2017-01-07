<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Search CB
 */

$config['booking_filter'] = array(
		1 => 'current_booking',
		2 => 'near_booking',
		3 => 'long_booking',
		4 => 'past_booking'
);

$config['approve_status'] = array(
		0 => 'not_approved',
		1 => 'approved'
);

$config['booking_status'] = array(
		1 => 'new',
		2 => 'pending',
		3 => 'deposited',
		4 => 'fully_paid',
		5 => 'cancelled',
		6 => 'close_win',
		7 => 'close_lost'
);

$config['booking_sites'] = array(
		1 => 'Bestviettravel.xyz',
		2 => 'Mobile m.Bestviettravel.xyz',
);

$config['customer_types'] = array(
		1 => 'new',
		2 => 'return',
		3 => 'recommended'
);

$config['request_types'] = array(
		1 => 'reservation',
		2 => 'request'
);

$config['mediums'] = array(
		1 => 'cpc',
		2 => 'organic',
		3 => 'referral',
		4 => 'none',
		5 => 'email',
		6 => 'banner'
);

$config['cb_per_page'] = 100;


/*
 |--------------------------------------------------------------------------
| Popup config
|--------------------------------------------------------------------------
|
*/
$config['popup'] = array(
		'default' => array(
				'width'      => '650',
				'height'     => '400',
				'scrollbars' => 'yes',
				'status'     => 'yes',
				'resizable'  => 'yes',
				'screenx'    => '50',
				'screeny'    => '80'
		),
		'email' => array(
				'width'      => '800',
				'height'     => '600',
				'scrollbars' => 'yes',
				'status'     => 'yes',
				'resizable'  => 'yes',
				'screenx'    => '50',
				'screeny'    => '80'
		)
);

$config['customer_booking'] = array(
	'customer_type' => array(
			'field' => 'customer_type',
			'label' => 'lang:customer_type',
			'rules' => 'required',
	),
		
	'request_type' => array(
			'field' => 'request_type',
			'label' => 'lang:request_type',
			'rules' => '',
	),
		
	'request_date' => array(
		'field' => 'request_date',
		'label' => 'lang:request_date',
		'rules' => 'required',		
	),
	
	'booking_date' => array(
		'field' => 'booking_date',
		'label' => 'lang:booking_date',
		'rules' => '',
	),
		
	'payment_method' => array(
		'field' => 'payment_method',
		'label' => 'lang:payment_method',
		'rules' => '',
	),
		
	'status' => array(
			'field' => 'status',
			'label' => 'lang:status',
			'rules' => '',
	),
	
	'sale' => array(
		'field' => 'sale',
		'label' => 'lang:sale',
		'rules' => '',		
	),
		
	'send_review' => array(
			'field' => 'send_review',
			'label' => 'lang:send_review',
			'rules' => '',
	),
	
	'description' => array(
		'field' => 'description',
		'label' => 'lang:description',
		'rules' => '',		
	),
	
	'note' => array(
		'field' => 'note',
		'label' => 'lang:note',
		'rules' => '',		
	),
		
	'adults' => array(
			'field' => 'adults',
			'label' => 'lang:adults',
			'rules' => '',
	),
	'children' => array(
			'field' => 'children',
			'label' => 'lang:children',
			'rules' => '',
	),
	'infants' => array(
			'field' => 'infants',
			'label' => 'lang:infants',
			'rules' => '',
	),
		
	'onepay' => array(
		'field' => 'onepay',
		'label' => 'lang:onepay',
		'rules' => '',		
	),
	
	'cash' => array(
		'field' => 'cash',
		'label' => 'lang:cash',
		'rules' => '',		
	),

	'pos' => array(
		'field' => 'pos',
		'label' => 'lang:pos',
		'rules' => '',		
	),	
	
	'approve_status' => array(
		'field' => 'approve_status',
		'label' => 'lang:approve_status',
		'rules' => '',		
	),
	
	'approve_note' => array(
		'field' => 'approve_note',
		'label' => 'lang:approve_note',
		'rules' => '',		
	),
		
	'close_reason' => array(
		'field' => 'close_reason',
		'label' => 'lang:close_reason',
		'rules' => '',		
	),
	
	'booking_site' => array(
		'field' => 'booking_site',
		'label' => 'lang:booking_site',
		'rules' => '',		
	),
	
	'source' => array(
		'field' => 'source',
		'label' => 'lang:source',
		'rules' => ''
	),
	
	'medium' => array(
		'field' => 'medium',
		'label' => 'lang:medium',
		'rules' => ''	
	),
);


$config['service_reservation'] = array(

	'reservation_type' => array (
	    'field'		=> 'reservation_type',
	    'label' 	=> 'lang:reservation_type',
	    'rules'		=> '',
	),	
	'service_name' => array (
	    'field'		=> 'service_name',
	    'label' 	=> 'lang:service_name',
	    'rules'		=> 'required',
	),
	
	'origin' => array (
	    'field'		=> 'origin',
	    'label' 	=> 'lang:origin',
	    'rules'		=> '',
	),
								
	'start_date' => array (
	    'field'		=> 'start_date',
	    'label' 	=> 'lang:start_date',
	    'rules'		=> 'required',
	),					
	'end_date' => array (
	    'field'		=> 'end_date',
	    'label' 	=> 'lang:end_date',
	    'rules'		=> 'required',
	),

	'partner' => array (
	    'field'		=> 'partner',
	    'label' 	=> 'lang:partner',
	    'rules'		=> 'required',
	),
		
	'partner_autocomplete' => array (
		'field'		=> 'partner_autocomplete',
		'label' 	=> 'lang:partner',
		'rules'		=> '',
	),
	
	'description' => array (
	    'field'		=> 'description',
	    'label' 	=> 'lang:description',
	    'rules'		=> '',
	),
	
	'net_price' 	=> array (
	    'field'		=> 'net_price',
	    'label' 	=> 'lang:net_price',
	    'rules'		=> 'required',
	),						
	'selling_price' 	=> array (
	    'field'		=> 'selling_price',
	    'label' 	=> 'lang:selling_price',
	    'rules'		=> 'required',
	),
	
	'reservation_status' => array (
	    'field'		=> 'reservation_status',
	    'label' 	=> 'lang:reservation_status',
	    'rules'		=> '',
	),
	
	'best_price' 	=> array (
		'field'		=> 'best_price',
		'label' 	=> 'lang:best_price',
		'rules'		=> '',
	),
	
	'1_payment' 	=> array (
	    'field'		=> '1_payment',
	    'label' 	=> 'lang:1_payment',
	    'rules'		=> '',
	),	
	
	'2_payment' 	=> array (
	    'field'		=> '2_payment',
	    'label' 	=> 'lang:2_payment',
	    'rules'		=> '',
	),	
	'pax_best_price' 	=> array (
		'field'		=> 'pax_best_price',
		'label' 	=> 'lang:pax_best_price',
		'rules'		=> '',
	),
	
	'pax_booked' => array (
	    'field'		=> 'pax_booked',
	    'label' 	=> 'lang:pax_booked',
	    'rules'		=> '',
	),
	
	'cabin_booked' => array (
	    'field'		=> 'cabin_booked',
	    'label' 	=> 'lang:cabin_booked',
	    'rules'		=> '',
	),
	
	'detail_reservation' => array (
	    'field'		=> 'detail_reservation',
	    'label' 	=> 'lang:detail_reservation',
	    'rules'		=> '',
	),
	
	'1_payment_due' => array (
	    'field'		=> '1_payment_due',
	    'label' 	=> 'lang:1_payment_due',
	    'rules'		=> '',
	),
	
	'1_payment_date' => array (
	    'field'		=> '1_payment_date',
	    'label' 	=> 'lang:1_payment_date',
	    'rules'		=> '',
	),
	
	'2_payment_due' => array (
	    'field'		=> '2_payment_due',
	    'label' 	=> 'lang:1_payment_due',
	    'rules'		=> '',
	),
	
	'2_payment_date' => array (
	    'field'		=> '2_payment_date',
	    'label' 	=> 'lang:2_payment_date',
	    'rules'		=> '',
	),
	
	'service_id' => array (
	    'field'		=> 'service_id',
	    'label' 	=> '',
	    'rules'		=> '',
	),
	
	'serserved_date' => array (
	    'field'		=> 'serserved_date',
	    'label' 	=> 'lang:reserved_date',
	    'rules'		=> '',
	),
	
	'destination' => array (
	    'field'		=> 'destination',
	    'label' 	=> 'lang:destinaton',
	    'rules'		=> 'required',
	),
		
	'des_autocomplete' => array (
		'field'		=> 'des_autocomplete',
		'label' 	=> 'lang:destination',
		'rules'		=> '',
	),
	
	'reviewed' => array (
	    'field'		=> 'reviewed',
	    'label' 	=> 'lang:reviewed',
	    'rules'		=> '',
	),
	
	'cabin_incentive' => array (
	    'field'		=> 'cabin_incentive',
	    'label' 	=> 'lang:cabin_incentive',
	    'rules'		=> '',
	),
	
	'type_of_visa' => array (
	    'field'		=> 'type_of_visa',
	    'label' 	=> 'lang:type_of_visa',
	    'rules'		=> '',
	),
	
	'processing_time' => array (
	    'field'		=> 'processing_time',
	    'label' 	=> 'lang:processing_time',
	    'rules'		=> '',
	),
	'flight_pnr' => array (
			'field'		=> 'flight_pnr',
			'label' 	=> 'lang:pnr',
			'rules'		=> '',
	),
	'flight_code' => array (
		'field'		=> 'flight_code',
		'label' 	=> 'lang:flight_code',
		'rules'		=> '',
	),
		
	'departure_time' => array (
		'field'		=> 'departure_time',
		'label' 	=> 'lang:departure_time',
		'rules'		=> '',
	),
	
	'arrival_time' => array (
		'field'		=> 'arrival_time',
		'label' 	=> 'lang:arrival_time',
		'rules'		=> '',
	),
	'fare_rule_short' => array (
		'field'		=> 'fare_rule_short',
		'label' 	=> 'lang:fare_rule_short',
		'rules'		=> '',
	),
);


$config['reservation_status'] = array(
	1 => 'new',
	2 => 'blocked',
	3 => 'reservated',
	5 => 'deposited',
	6 => 'fully_paid',
	7 => 'close_win',
	4 => 'cancelled',
);

$config['payment_status'] = array(
	1 => 'new',
	2 => 'paid',
	3 => 'cancelled',
);

$config['reservation_type'] = array(
	2 => 'hotel_reservation',
	8 => 'flight_reservation',
	1 => 'cruise_reservation',
	4 => 'tour_reservation',
	3 => 'transfer_reservation',
	7 => 'visa_reservation',
	5 => 'other_reservation'
);
$config['payment_type'] = array(
	1 => 'payment_type_monthly',
	2 => 'payment_type_specific',
);



$config['near_future_day'] = 7;

$config['reserved_date_nr'] = 3; // reserved date = booking date + 3

$config['class_services']  = array(
	1 => 'class_service_standard',
	2 => 'class_service_superior',
	3 => 'class_service_deluxe',
	4 => 'class_service_suite',
	5 => 'class_service_premium',	
);



$config['cabin_booked'] = array(1,1.5,2,2.5,3,3.5,4,4.5,5,5.5,6,6.5,7,7.5,8,8.5,9,9.5,10);

$config['pax_booked'] = array(1,2,3,4,5,6,7,8,9,10);

$config['close_reason'] = array(
	1 => 'price_problem',
	2 => 'not_available',
	3 => 'change_plan',
	4 => 'no_answer',
	5 => 'dealing_problem',
	6 => 'other_reason'
);

$config['task_filter'] = array(
		1 => 'current_task',
		2 => 'near_task',
		3 => 'overdue'
);

$config['task_type'] = array(
		1 => 'customer_meeting',
		2 => 'customer_payment',
		3 => 'service_reservation',
		4 => 'transfer_reminder',
		5 => 'service_payment',
		6 => 'to-do'
);

$config['todo_status'] = array(
		0 => 'New',
		1 => 'Process',
		2 => 'Finished',
		3 => 'Cancel',
);

$config['cb_nav_panel'] = array(
		array(
				'link' 	=> '/bookings/edit/',
				'title' => 'booking_mnu_cb',
		),

		array(
				'link' 	=> '/bookings/sr/',
				'title' => 'booking_mnu_sr',
		)
);

// config for type of visa
$config['visa_types'] = array(
		'1' => '1_month_single_entry',
		'2' => '3_months_single_entry',
		'3' => '1_month_multiple_entry',
		'4' => '3_months_multiple_entry',
);

$config['visa_processing_times'] = array(
		'1' => 'visa_normal',
		'2' => 'visa_urgent'
);

$config['payment_methods'] = array(
	PAYMENT_METHOD_AT_OFFICE => lang('payment_method_office'),
	PAYMENT_METHOD_AT_HOME => lang('payment_method_home'),
	PAYMENT_METHOD_CREDIT_CARD => lang('payment_method_credit_card'),
	PAYMENT_METHOD_DOMESTIC_CARD => lang('payment_method_domistic_card'),
	PAYMENT_METHOD_BANK_TRANSFER => lang('payment_method_bank_transfer'),
);

//config gender
$config['genders'] = array(
	1 => 	lang('male'),
	2 =>	lang('female'),
	3 => 	lang('male_y'),
	4 =>  	lang('female_y')
);

$config['passenger_age_type'] = array(
	1 => lang('passenger_adult'),
	2 => lang('passenger_children'),
	3 => lang('passenger_infant'),
);

$config['passenger_gender'] = array(
	1 => lang('passenger_gender_male'),
	2 => lang('passenger_gender_female'),
);

/**
 * 
 * Configuration for Export Customer Functions
 * 
 */
$config['invalid_customer_emails'] = array(
	'khuyenpv@gmail.com',
	'khuyenpv@Bestviettravel.xyz',
	'khanhtoan1187@gmail.com',
	'toanlk@Bestviettravel.xyz',
	'111@gmail.com',
	'abc2@abc.com',
	'aa@gmail.com',
	'haominhnk2014@gmail.com',
	'abag@gmail.com',
	'7878@gmail.com',
	'ktdung@gmail.com',
	'777@gmail.com',
	'555@gmail.com',
	'7979@gmail.com',
	'9898@gmail.com',
	'222@gmail.com',
	'abc@gmail.com',
	'abc@abc.com',
	'114@gmail.com',
	'121@gmail.com'
);

$config['invalid_customer_phones'] = array(
	'0936179428',
	'0904879428',
	'0936379428',
	'0936129428',
	'0936259428',
	'0936.089.426',
	'093 232 9428',
	'0904744147',
	'0904744148',
	'909',
	'8978978',
	'545454'
);


$config['passenger'] = array(

	'type' => array (
		'field'		=> 'type',
		'label' 	=> 'lang:passenger_age_type',
		'rules'		=> 'required',
	),
		
	'gender' => array (
		'field'		=> 'gender',
		'label' 	=> 'lang:passenger_gender',
		'rules'		=> 'required',
	),
		
	'full_name' => array (
		'field'		=> 'full_name',
		'label' 	=> 'lang:passenger_name',
		'rules'		=> 'required',
	),

	'birth_day' => array (
		'field'		=> 'birth_day',
		'label' 	=> 'lang:passenger_birthday',
		'rules'		=> '',
	),

	'checked_baggage' => array (
		'field'		=> 'checked_baggage',
		'label' 	=> 'lang:passenger_baggage',
		'rules'		=> '',
	),
		
	'ticket_number' => array (
		'field'		=> 'ticket_number',
		'label' 	=> 'lang:ticket_number',
		'rules'		=> '',
	),

	'nationality' => array (
		'field'		=> 'nationality',
		'label' 	=> 'lang:passenger_nationality',
		'rules'		=> '',
	),
		
	'passport' => array (
		'field'		=> 'passport',
		'label' 	=> 'lang:passenger_passport',
		'rules'		=> '',
	),
	
	'passportexp' => array (
		'field'		=> 'passportexp',
		'label' 	=> 'lang:passenger_passportexp',
		'rules'		=> '',
	)
);

$config['passenger_nationalities'] = array(
	'vn' => array('Vietnam','AS'),
	'ar' => array('Argentina','NA'),
	'au' => array('Australia','OC'),
	'at' => array('Austria','EU'),
	'by' => array('Belarus','EU'),
	'be' => array('Belgium','EU'),
	'bt' => array('Bhutan','AS'),
	'br' => array('Brazil','SA'),
	'bn' => array('Brunei','AS'),
	'bg' => array('Bulgaria','EU'),
	'kh' => array('Cambodia','AS'),
	'ca' => array('Canada','NA'),
	'cl' => array('Chile','SA'),
	'cn' => array('China','AS'),
	'co' => array('Colombia','SA'),
	'cr' => array('Costa Rica','NA'),
	'hr' => array('Croatia','EU'),
	'cu' => array('Cuba','NA'),
	'cy' => array('Cyprus','AS'),
	'cz' => array('Czech Republic','EU'),
	'dk' => array('Denmark','EU'),
	'dm' => array('Dominica','NA'),
	'do' => array('Dominican Republic','NA'),
	'tl' => array('East Timor','AS'),
	'ec' => array('Ecuador','SA'),
	'eg' => array('Egypt','EU'),
	'fi' => array('Finland','EU'),
	'fr' => array('France','EU'),
	'de' => array('Germany','EU'),
	'gr' => array('Greece','EU'),
	'hk' => array('Hong Kong','AS'),
	'hu' => array('Hungary','EU'),
	'is' => array('Iceland','EU'),
	'in' => array('India','AS'),
	'id' => array('Indonesia','AS'),
	'ie' => array('Ireland','EU'),
	'il' => array('Israel','AS'),
	'it' => array('Italy','EU'),
	'jm' => array('Jamaica','NA'),
	'jp' => array('Japan','AS'),
	'kp' => array('Korea, North','AS'),
	'kr' => array('Korea, South','AS'),
	'la' => array('Laos','AS'),
	'lv' => array('Latvia','EU'),
	'li' => array('Liechtenstein','EU'),
	'lt' => array('Lithuania','EU'),
	'lu' => array('Luxembourg','EU'),
	'mo' => array('Macau','AS'),
	'mk' => array('Macedonia','EU'),
	'my' => array('Malaysia','AS'),
	'mx' => array('Mexico','NA'),
	'md' => array('Moldova','EU'),
	'mc' => array('Monaco','AS'),
	'mm' => array('Myanmar','AS'),
	'np' => array('Nepal','AS'),
	'nl' => array('Netherlands','EU'),
	'an' => array('Netherlands Antilles','EU'),
	'nz' => array('New Zealand','OC'),
	'ng' => array('Nigeria','AF'),
	'no' => array('Norway','EU'),
	'py' => array('Paraguay','SA'),
	'pe' => array('Peru','SA'),
	'ph' => array('Philippines','AS'),
	'pl' => array('Poland','EU'),
	'pt' => array('Portugal','EU'),
	'qa' => array('Qatar','AS'),
	'ro' => array('Romania','EU'),
	'ru' => array('Russia','EU'),
	'sg' => array('Singapore','AS'),
	'sk' => array('Slovakia','EU'),
	'si' => array('Slovenia','EU'),
	'za' => array('South Africa','AF'),
	'es' => array('Spain','EU'),
	'se' => array('Sweden','EU'),
	'ch' => array('Switzerland','EU'),
	'tw' => array('Taiwan','AS'),
	'th' => array('Thailand','AS'),
	'ua' => array('Ukraine','EU'),
	'uk' => array('United Kingdom','EU'),
	'us' => array('United States','NA'),
	'uy' => array('Uruguay','SA')
);
