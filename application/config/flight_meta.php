<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['flight_data_url'] = "http://flightbooking.bestpricevn.com/Misc.aspx";

$config['flight_vnisc_iframe_url'] = "http://flightbooking.bestpricevn.com/Booking_Redirect.aspx";

//$config['flight_data_url'] = "http://oth2.muadi.vn/Misc.aspx";

//$config['flight_vnisc_iframe_url'] = "http://oth2.muadi.vn/Booking_Redirect.aspx";


$config['flight_booking_url'] = "";

// $config['sid_curl_options'] = array(
// 	CURLOPT_RETURNTRANSFER => true,         // return web page
//    	CURLOPT_HEADER         => true,        // don't return headers
//     CURLOPT_FOLLOWLOCATION => true,         // follow redirects
//     CURLOPT_ENCODING       => "",           // handle all encodings
//     CURLOPT_USERAGENT      => "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",     // who am i
//     CURLOPT_AUTOREFERER    => true,         // set referer on redirect
//     CURLOPT_CONNECTTIMEOUT => 20,          // timeout on connect - 20s
//     CURLOPT_TIMEOUT        => 30,          // timeout on response - 30s
//     CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects
//     CURLOPT_POST            => true,            // i am sending post data
//     CURLOPT_POSTFIELDS     => array(),    // this are my post vars
//  	CURLOPT_DNS_CACHE_TIMEOUT => 300, 	  // default 120 seconds
//  	CURLOPT_MAXCONNECTS => 50,				// maximum persistent connections
//  	CURLOPT_COOKIE	=> '',
// );

// $config['flight_data_curl_options'] = array(
// 	CURLOPT_RETURNTRANSFER => true,         // return web page
//    	CURLOPT_HEADER         => false,        // don't return headers
//     CURLOPT_FOLLOWLOCATION => false,         // follow redirects
//     CURLOPT_ENCODING       => "",           // handle all encodings
//     CURLOPT_USERAGENT      => "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)",     // who am i
//     CURLOPT_CONNECTTIMEOUT => 300,          // timeout on connect - 5 minutes
//     CURLOPT_TIMEOUT        => 600,          // timeout on response - 10 minutes
//     CURLOPT_POST            => true,            // i am sending post data
//     CURLOPT_POSTFIELDS     => array(),    // this are my post vars
//  	CURLOPT_DNS_CACHE_TIMEOUT => 300, 	  // default 120 seconds
//  	CURLOPT_MAXCONNECTS => 50,				// maximum persistent connections
//  	CURLOPT_COOKIE	=> 'vniscLang=vn',
// );

$config['departure_times'] = array(
	1 => lang('flight_morning'),
	2 => lang('flight_after_noon'),
	3 => lang('flight_evening'),
	4 => lang('flight_night')
);

$config['valid_airline_codes'] = array('VN'=>'Vietnam Airlines','VJ'=>'Vietjet Air','BL' => 'Jestar');

$config['domistic_airlines'] = array('VN'=>'Vietnam Airlines','VJ'=>'Vietjet Air','BL' => 'Jestar');

$config['limit_hold_seats'] = array('VJ'=>36,'BL'=>36,'VN'=>36); // add more 12 hours for processing

$config['limit_hold_seats_inter'] = 48;// 2 days for international flights

$config['flight_data_timeout'] = 60 * 15; // time-out 15 miute from passenger detail page to payment page

$config['flight_search_timeout'] = 60; // time-out search flight data 45s


$config['flight_search_timeout_inter'] = 90; // time-out search flight data 60 for flight international

$config['sort_by'] = array(

		'price' => array(
				'label' => lang('sort_by_prices'),
				'value' => 'price',
				'selected' => true
		),

		'airline' => array(
				'label' => lang('sort_by_airlines'),
				'value' => 'airline',
				'selected' => false
		),

		'departure' => array(
				'label' => lang('sort_by_departure'),
				'value' => 'departure',
				'selected' => false
		),
);

$config['flight_ticket_fee'] = 80000; // 80 K vnd

$config['urgent_flight_ticket_fee'] = 100000;// urgent ticket fee 100 K

$config['flight_ticket_fee_inter'] = 120000;

$config['urgent_flight_ticket_fee_inter'] = 120000;


$config['baggage_fees'] = array(
	'VN'=>array(
		'hand'=>lang('hand_baggage_note'),
		'send'=>lang('send_baggage_vna')
	),
	'BL'=>array(
		'hand'=>lang('hand_baggage_note'),
		'send'=>array(
			15=>155000,
			20=>175000,
			25=>230000,
			30=>280000,
			35=>330000,
			40=>380000
		)
	),
	'VJ'=>array(
		'hand'=>lang('hand_baggage_note'),
		'send'=>array(
			15=>155000,
			20=>175000,
			25=>230000,
			30=>340000,
		)
			
	)
);

$config['baggage_vnisc_options'] = array(
	'BL'=>array(
		0=>7,
		15=>8,
		20=>9,
		25=>10,
		30=>11,
		35=>12,
		40=>13
	),
	'VJ'=>array(
		0=>1,
		15=>2,
		20=>4,
		25=>5,
		30=>6
	)
);

$config['in_card_fee'] = 3.3; // 3.3% bank fee
$config['do_card_fee'] = 0; // no bank fee for domistic card

$config['inter_flight_discounts'] = array(
	'start_date' => '08-09-2014',
	'end_date' => '15-09-2014',
	'CX' => array(
		'from' => array('HAN','SGN','DAD','HUI','CXR','VCA','CAH','VCS','VCL','BMV','DLI','DIN','VDH','HPH','THD','TBB','PQC','UIH','PXU','VKG','VII'),
		'to'   => array(),
		'discount' => 2.5
	),
	'QR' => array(
		'from' => array('HAN','SGN','DAD','HUI','CXR','VCA','CAH','VCS','VCL','BMV','DLI','DIN','VDH','HPH','THD','TBB','PQC','UIH','PXU','VKG','VII'),
		'to'   => array(),
		'discount' => 2
	),
	'TG' => array(
		'from' => array('HAN','SGN','DAD','HUI','CXR','VCA','CAH','VCS','VCL','BMV','DLI','DIN','VDH','HPH','THD','TBB','PQC','UIH','PXU','VKG','VII'),
		'to'   => array(),
		'discount' => 5
	),
	'EY' => array(
		'from' => array('HAN','SGN','DAD','HUI','CXR','VCA','CAH','VCS','VCL','BMV','DLI','DIN','VDH','HPH','THD','TBB','PQC','UIH','PXU','VKG','VII'),
		'to'   => array(),
		'discount' => 2.5
	),
	'VN' => array(
		'from' => array('HAN'),
		'to' => array('PUS','SEL','BJS','CTU','CAN','HKG','SHA','KHH','TPE','FUK','NGO','OSA','TYO'),
		'discount' => '3',
	),		
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
?>
