<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Search option
|--------------------------------------------------------------------------
|
*/ 

$config['max_nights']	= 15;
$config['max_rooms']	= 10;

$config['resource_path'] = 'http://snotevn.com:8888';


$config['paging_config'] = array(
	'per_page' => 10,
	'num_links' => 6
);

$config['paging_config_mobile'] = array(
		'per_page' => 10,
		'num_links' => 1
);

// time-cache-html
$config['cache_html'] = 60 * 8;

/**
 *  Title
 */
$config['c_titles'] = array(1=>'title_mr',2=>'title_ms',3=>'title_mr_y',4=>'title_ms_y');

$config['week_days'] = array(
		1 => 'mon',
		2 => 'tue',
		3 => 'wed',
		4 => 'thu',
		5 => 'fri',
		6 => 'sat',
		0 => 'sun'
);

$config['mediums'] = array(
		1 => 'cpc',
		2 => 'organic',
		3 => 'referral',
		4 => 'none',
		5 => 'email',
		6 => 'banner'
);

/**
 *  Payment
 */
$config['invoice_path'] = '/invoice/';


/**
 *  Bank Transfer
 */
$config['bank_transfer'] = array(
		array(
				'bank_id' 			=> 'vietcombank',
				'bank_name' 		=> 'Ngân hàng Vietcombank (VCB)',
				'branch_name' 		=> 'Chi nhánh Thành phố Hà Nội',
				'account_name' 		=> 'Công ty Cổ phần Công Nghệ Du Lịch BestPrice',
				'account_number' 	=> '0011 0036 97817'
		),
		
		array(
				'bank_id' 			=> 'viettinbank',
				'bank_name' 		=> 'Ngân hàng Viettinbank',
				'branch_name' 		=> 'Chi nhánh Hai Bà Trưng Hà Nội',
				'account_name' 		=> 'Công ty Cổ phần Công Nghệ Du Lịch BestPrice',
				'account_number' 	=> '10201 000 1979 712'
		),
		
		array(
				'bank_id' 			=> 'agribank',
				'bank_name' 		=> 'Ngân hàng Agribank',
				'branch_name' 		=> 'Chi nhánh Hà Thành',
				'account_name' 		=> 'Khuc Tan Dung',
				'account_number' 	=> '1303 2062 66324'
		),
		array(
				'bank_id' 			=> 'sacombank',
				'bank_name' 		=> 'Ngân hàng Sacombank',
				'branch_name' 		=> 'Chi nhánh 8 tháng 3 Hà Nội',
				'account_name' 		=> 'Khuc Tan Dung',
				'account_number' 	=> '0200 2901 8430'
		),
		
		array(
				'bank_id' 			=> 'techcombank',
				'bank_name' 		=> 'Ngân hàng Techcombank',
				'branch_name' 		=> 'Chi nhánh Phương Mai, Hà Nội',
				'account_name' 		=> 'Khuc Tan Dung',
				'account_number' 	=> '1052 2787 437014'
		),
		
		array(
				'bank_id' 			=> 'dongabank',
				'bank_name' 		=> 'Ngân hàng Đông Á Bank',
				'branch_name' 		=> 'Phòng giao dịch Bạch Mai, Hà Nội',
				'account_name' 		=> 'Khuc Tan Dung',
				'account_number' 	=> '0108 851 561'
		),
		
		array(
		    'bank_id' 			=> 'maritimebank',
		    'bank_name' 		=> 'Maritime Bank',
		    'branch_name' 		=> 'Phòng Giao dịch Bà Triệu (chi nhánh Sở Giao dịch)',
		    'account_name' 		=> 'Khuc Tan Dung',
		    'account_number' 	=> '1100 1016 907710'
		),
		
		array(
		    'bank_id' 			=> 'acb',
		    'bank_name' 		=> 'Ngân hàng thương mại cổ phần Á Châu',
		    'branch_name' 		=> 'ACB - Chi nhánh Hà Nội',
		    'account_name' 		=> 'Khuc Tan Dung',
		    'account_number' 	=> '184 844 799'
		),
		
		array(
		    'bank_id' 			=> 'bidv',
		    'bank_name' 		=> 'Ngân hàng TMCP Đầu tư và Phát triển Việt Nam',
		    'branch_name' 		=> 'BIDV - Chi nhánh Sở Giao dịch 1',
		    'account_name' 		=> 'Khuc Tan Dung',
		    'account_number' 	=> '1201 0006 131346'
		)
);

// Duration
$config['duration_search']  = array(
		0 => 'all_duration',
		1 => '1day',
		2 => '2days',
		3 => '3days',
		//4 => '4-7days',
		//5 => 'over7days',
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

$config['review_customer_types']  = array(
	1 	=> 'family',
	2 	=> 'couple',
	3 	=> 'solo',
	4 	=> 'business',
);

$config['review_score_breakdown']  = array(
	1 	=> 'rev_excellent',
	2 	=> 'rev_very_good',
	3 	=> 'rev_average',
	4 	=> 'rev_poor',
	5 	=> 'rev_terrible',
);

$config['ad_utm_content'] = array(
	AD_PAGE_HOME => 'home_page',
	AD_PAGE_HOTEL_HOME => 'hotel_home_page',
	AD_PAGE_HOTEL_DESTINATION => 'hotel_destination_page',
	AD_PAGE_CRUISE_HOME => 'cruise_home_page',
	AD_PAGE_DEAL => 'deal_offer_page',
	AD_PAGE_FLIGHT => 'flight_home_page',
	AD_PAGE_FLIGHT_DESTINATION => 'flight_destination_page',
	AD_PAGE_TOUR_HOME => 'tour_home_page',
	AD_PAGE_TOUR_DOMISTIC => 'tour_domistic_page',
	AD_PAGE_TOUR_OUTBOUND => 'tour_outbound_page',
	AD_PAGE_TOUR_CATEGORY => 'tour_category_page',
	AD_PAGE_TOUR_DESTINATION => 'tour_destination_page'
);

$config['golden-week-pop'] = array(
	'start_date' => '31-07-2014',
	'end_date'	 => '08-08-2014',
	'link'		 => 'http://www.snotevn.com:8888/khuyen-mai/tuan-le-vang.html?utm_source=bestprice&utm_medium=banner&utm_content=popup_image&utm_campaign=tuan-le-vang'
);

$config['marketing-more-people-more-save'] = array(
	'start_date' => '13-08-2014',
	'end_date'	 => '31-08-2014',
	'link'		 => 'http://www.snotevn.com:8888/khuyen-mai/cang-dong-cang-vui.html?utm_source=bestprice&utm_medium=banner&utm_content=popup_image&utm_campaign=cang-dong-cang-vui'
);

$config['tri-an-khach-hang'] = array(
	'start_date' => '16-10-2014',
	'end_date'	 => '31-10-2014',
	'link'		 => 'http://www.snotevn.com:8888/tin-tuc/tri-an-khach-hang.html?utm_source=bestprice&utm_medium=banner&utm_content=popup_image&utm_campaign=tri-an-khach-hang'
);

$config['mua-chung-doi'] = array(
    'start_date' => '01-11-2014',
    'end_date'	 => '30-11-2014',
    'link'		 => 'http://www.snotevn.com:8888/tin-tuc/mua-chung-doi.html?utm_source=bestprice&utm_medium=banner&utm_content=popup_image&utm_campaign=mua-chung-doi'
);

$config['minh-di-choi-nhe'] = array(
    'start_date' => '29-11-2014',
    'end_date'	 => '21-12-2014',
    'link'		 => 'http://www.snotevn.com:8888/tin-tuc/minh-di-choi-nhe.html?utm_source=bestprice&utm_medium=banner&utm_content=popup_image&utm_campaign=minh-di-choi-nhe'
);

$config['pro-codes-more-people-more-save'] = array(
	'codes' => array('tno29','ns29','vnn29','fb29','sms29','em29','bpv29'),
	'discounts' => array(
		array(
			'min_passenger' => 1,
			'max_passeger' => 3,
			'discount' => 0
		),
		array(
			'min_passenger' => 4,
			'max_passeger' => 5,
			'discount' => 400000
		),
		array(
			'min_passenger' => 6,
			'max_passeger' => 7,
			'discount' => 700000
		),
		array(
			'min_passenger' => 8,
			'max_passeger' => 100, 
			'discount' => 1000000
		)
	) 
		
);
