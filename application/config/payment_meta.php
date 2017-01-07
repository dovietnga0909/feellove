<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Onepay test account
$config['international_pay_parameter'] = array(
		
	'Title' 				=> 'Bestprice Vietnam Payment',
	
	'AgainLink'				=> '',
		
	'vpc_Merchant' 			=> 'BESTPRICEVND', 	// TESTONEPAY
	
	'vpc_AccessCode' 		=> 'F4C02D19', 		// 6BEB2546
	
	'vpc_MerchTxnRef' 		=> '',
	
	'vpc_OrderInfo' 		=> '',
	'vpc_Amount' 			=> '0',
	
	'vpc_ReturnURL' 		=> '',
		
	'vpc_Version' 			=> '2',
	'vpc_Command' 			=> 'pay',
	'vpc_Locale' 			=> 'vn',
	
	'vpc_TicketNo' 			=> '10.36.74.105',
);


$config['domestic_pay_parameter'] = array(

		'Title' 				=> 'Bestprice Vietnam Payment',

		'AgainLink'				=> '',

		'vpc_Merchant' 			=> 'BESTPRICE', 	// ONEPAY

		'vpc_AccessCode' 		=> '2B02566C', 		// D67342C2

		'vpc_MerchTxnRef' 		=> '',

		'vpc_OrderInfo' 		=> '',
		'vpc_Amount' 			=> '0',

		'vpc_ReturnURL' 		=> '',

		'vpc_Version' 			=> '2',
		'vpc_Command' 			=> 'pay',
		'vpc_Locale' 			=> 'vn',
		'vpc_Currency'			=> 'VND',

		'vpc_TicketNo' 			=> '10.36.74.105',
);

