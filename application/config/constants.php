<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define('SITE_NAME',	'Bestviettravel.xyz');
define('BRANCH_NAME',	'Best Price Vietnam');

/*
|--------------------------------------------------------------------------
| Define System parameter
|--------------------------------------------------------------------------
*/

define('STATUS_ACTIVE',	1);
define('STATUS_INACTIVE',	0);
define('STATUS_AVAIABLE',	1);
define('DELETED', 1);

define('DESC_WORD_LIMIT', 20);
define('NEAR_DISTANCE',5); // near mean < 5km

define('BEST_HOTEL_LIMIT', 6);

define('TOUR_DURATION_LIMIT', 4);

define('CUSTOMER_REVIEW_LIMIT', 300);

/*
|--------------------------------------------------------------------------
| Menues
|--------------------------------------------------------------------------
*/
define('MNU_HOME', 			'MNU_HOME');
define('MNU_FLIGHTS', 		'MNU_FLIGHTS');
define('MNU_HOTELS', 		'MNU_HOTELS');
define('MNU_CRUISES', 		'MNU_CRUISES');
define('MNU_DEALS', 		'MNU_DEALS');
define('MNU_TOURS', 		'MNU_TOURS');

/*
|--------------------------------------------------------------------------
| URL
|--------------------------------------------------------------------------
*/
define('URL_SUFFIX', 		'.html');

/*
|--------------------------------------------------------------------------
| Service Types
|--------------------------------------------------------------------------
*/
define('HOTEL', 1);
define('FLIGHT', 2);
define('CRUISE', 3);
define('TOUR', 4);

/*
|--------------------------------------------------------------------------
| Photo Types
|--------------------------------------------------------------------------
*/
define('DESTINATION', 5);
define('CRUISE_TOUR', 6);
define('NEWS_PHOTO', 7);
define('USER_PHOTO', 8);

/*
|--------------------------------------------------------------------------
| BPV Discount types
|--------------------------------------------------------------------------
*/
define('BPV_DISCOUNT_PERCENTAGE', 1);
define('BPV_DISCOUNT_AMOUNT', 2);
define('BPV_DISCOUNT_AMOUNT_TICKET', 3);
define('BPV_DISCOUNT_AMOUNT_PAX', 4);

/*
|--------------------------------------------------------------------------
| Invoice Status
|--------------------------------------------------------------------------
*/
define('INVOICE_NOT_PAID', 0);

define('INVOICE_SUCCESSFUL', 1);

define('INVOICE_PENDING', 2);

define('INVOICE_FAILED', 3);

define('INVOICE_UNKNOWN', 4);


/*
|--------------------------------------------------------------------------
| MODULE & PAGE TYPE
|--------------------------------------------------------------------------
*/
define('MODULE_HOTEL', 					'MODULE_HOTEL');
define('MODULE_HOTEL_DESTINATION', 		'MODULE_HOTEL_DESTINATION');
define('MODULE_HOTEL_SEARCH', 			'MODULE_HOTEL_SEARCH');

define('MODULE_FLIGHT', 				'MODULE_FLIGHT');
define('MODULE_FLIGHT_DESTINATION',     'MODULE_FLIGHT_DESTINATION');

define('MODULE_CRUISE', 				'MODULE_CRUISE');

define('MODULE_TOUR', 				    'MODULE_TOUR');


define('HOME_PAGE', 'HOME_PAGE');
define('HOTEL_HOME_PAGE', 'khach-san');
define('HOTEL_DETAIL_PAGE', HOTEL_HOME_PAGE.'/');
define('HOTEL_SEARCH_PAGE', HOTEL_HOME_PAGE.'/tim-kiem'.URL_SUFFIX);
define('HOTEL_DESTINATION_PAGE', 'khach-san-');
define('HOTEL_BOOKING_PAGE',HOTEL_HOME_PAGE.'/dat-phong/');

define('FLIGHT_HOME_PAGE', 've-may-bay');
define('FLIGHT_DESTINATION_PAGE', FLIGHT_HOME_PAGE.'/ve-may-bay-di-');
define('FLIGHT_SEARCH_PAGE', FLIGHT_HOME_PAGE.'/tim-kiem'.URL_SUFFIX);
define('FLIGHT_DETAIL_PAGE', FLIGHT_HOME_PAGE.'/thong-tin-hanh-khach'.URL_SUFFIX);
define('FLIGHT_BOOKING_PAGE', FLIGHT_HOME_PAGE.'/dat-ve'.URL_SUFFIX);
define('FLIGHT_EXCEPTION_PAGE', FLIGHT_HOME_PAGE.'/exception'.URL_SUFFIX);
define('FLIGHT_AIRLINE_PAGE', FLIGHT_HOME_PAGE.'/ve-may-bay-theo-hang-');
define('FLIGHT_CATEGORY_PAGE', FLIGHT_HOME_PAGE.'/ve-may-bay-theo-loai/');

define('NEWS_HOME_PAGE', 'tin-tuc.html');
define('NEWS_DETAILS_PAGE', 'tin-tuc/');
define('NEWS_CATEGORY_PAGE', 'tin-tuc/cat/');

define('CRUISE_HL_HOME_PAGE','du-thuyen-ha-long');

define('TOUR_HL_DETAIL_PAGE', CRUISE_HL_HOME_PAGE.'/tour-');

define('CRUISE_HL_DETAIL_PAGE', CRUISE_HL_HOME_PAGE.'/');
define('CRUISE_HL_SEARCH_PAGE', CRUISE_HL_HOME_PAGE.'/tim-kiem'.URL_SUFFIX);
define('CRUISE_HL_BOOKING_PAGE', CRUISE_HL_HOME_PAGE.'/dat-du-thuyen/');

define('TOUR_HOME_PAGE', 'tour');
define('TOUR_DETAIL_PAGE', TOUR_HOME_PAGE.'/');
define('TOUR_DOWNLOAD', TOUR_HOME_PAGE.'/tai-lich-trinh/');
define('TOUR_BOOKING_PAGE', TOUR_HOME_PAGE.'/dat-tour/');
define('TOUR_SEARCH_PAGE', TOUR_HOME_PAGE.'/tim-kiem'.URL_SUFFIX);
define('TOUR_DOMESTIC_PAGE', TOUR_HOME_PAGE.'/du-lich-trong-nuoc'.URL_SUFFIX);
define('TOUR_OUTBOUND_PAGE', TOUR_HOME_PAGE.'/du-lich-nuoc-ngoai'.URL_SUFFIX);
define('TOUR_CATEGORY_PAGE', TOUR_HOME_PAGE.'/du-lich-theo-chu-de'.URL_SUFFIX);
define('TOUR_DESTINATION_PAGE', TOUR_HOME_PAGE.'/du-lich-');

define('TOUR_CATEGORY_DETAIL_PAGE', TOUR_HOME_PAGE.'/chu-de-');
define('TOUR_DESTINATION_DETAIL_PAGE', 'diem-den/');


define('ABOUT_US_PAGE', 'gioi-thieu'.URL_SUFFIX);
define('FAQS_PAGE', 'cau-hoi'.URL_SUFFIX);
define('TERM_CONDITION_PAGE', 'dieu-khoan'.URL_SUFFIX);
define('PRIVACY_PAGE', 'chinh-sach-rieng-tu'.URL_SUFFIX);
define('CONTACT_US_PAGE', 'lien-he'.URL_SUFFIX);
define('CONFIRM_PAGE', 'xac-nhan'.URL_SUFFIX);
define('COMPANY_ADDRESS_PAGE', 'dia-chi'.URL_SUFFIX);
define('PAYMENT_METHODS_PAGE', 'hinh-thuc-thanh-toan'.URL_SUFFIX);
define('ACCOMPLISHMENT_PAGE', 'thanh-tuu-va-giai-thuong'.URL_SUFFIX);
define('TESTIMONIAL_PAGE', 'khach-hang-noi-ve-chung-toi'.URL_SUFFIX);
define('BESTPRICE_WITH_PRESS_PAGE', 'bestprice-voi-bao-chi'.URL_SUFFIX);

define('HOT_DEAL_PAGE', 'khuyen-mai'.URL_SUFFIX);

define('BOOK_TOGETHER_PAGE', 'dat-kem-dich-vu'.URL_SUFFIX);
define('BEST_PRICE_PAGE', 'dam-bao-gia-tot-nhat'.URL_SUFFIX);

define('THANK_YOU_PAGE', 'xac-nhan.html');
/*
|--------------------------------------------------------------------------
| Define Date format
|--------------------------------------------------------------------------
*/
define('DATE_FORMAT',				'd/m/Y'); // not support dd/mm/yyyy
define('DATE_FORMAT_JS',			'd/m/Y'); // use in js calendar - must the same DATE_FORMAT
define('DATE_FORMAT_LBL',			'(dd-mm-yyyy)');
define('DATE_TIME_FORMAT',			'd/m/Y H:i:s');
// don't change this value for db date format
define('DB_DATE_FORMAT',			'Y-m-d');
define('DB_DATE_TIME_FORMAT',		'Y-m-d H:i:s');
define('DATE_FORMAT_STANDARD',		'd-m-Y'); // not support dd/mm/yyyy
define('DATE_FORMAT_SHORT',			'd M y'); // not suppmort dd/mm/yyyy
define('DATE_FORMAT_DISPLAY',		'd M Y'); // not suppmort dd/mm/yyyy

define('FLIGHT_DATE_FORMAT',		'D, d M Y');

define('NEWS_DATE_FORMAT',		'd/m/Y | H:i');

/*
|--------------------------------------------------------------------------
| Ad Pages
|--------------------------------------------------------------------------
*/

define('AD_PAGE_HOME', 1);
define('AD_PAGE_HOTEL_HOME', 2);
define('AD_PAGE_HOTEL_DESTINATION', 3);
define('AD_PAGE_FLIGHT', 4);
define('AD_PAGE_FLIGHT_DESTINATION', 5);
define('AD_PAGE_DEAL',6);
define('AD_PAGE_CRUISE_HOME', 7);
define('AD_PAGE_TOUR_HOME',8);
define('AD_PAGE_TOUR_DOMISTIC',9);
define('AD_PAGE_TOUR_OUTBOUND',10);
define('AD_PAGE_TOUR_DESTINATION',11);
define('AD_PAGE_TOUR_CATEGORY',12);


define('AD_AREA_DEFAULT', 0);
define('AD_AREA_2', 1);
define('AD_AREA_3', 2);



/*
|--------------------------------------------------------------------------
| News
|--------------------------------------------------------------------------
*/
define('M_GENERAL', 1);
define('M_HOTEL', 	2);
define('M_FLIGHT', 	3);
define('M_CRUISE', 	4);
define('M_TOUR', 	5);

/*
 * view mode tour contact
 * 
 */

define('FULL_TOUR_CONTACT', 1);
define('RIGHT_TOUR_CONTACT', 2);


/*
|--------------------------------------------------------------------------
| COOKIE DATA
|--------------------------------------------------------------------------
*/
define('RECENT_ITEMS', 'RECENT_ITEMS');
define('MAX_RECENT_ITEMS', 3);

/*
 |--------------------------------------------------------------------------
| DESTINATION TYPE
|--------------------------------------------------------------------------
*/
define('DESTINATION_TYPE_CONTINENT', 1);
define('DESTINATION_TYPE_REGION', 2);
define('DESTINATION_TYPE_COUNTRY', 3);
define('DESTINATION_TYPE_CITY', 4);
define('DESTINATION_TYPE_DISTRICT', 5);
define('DESTINATION_TYPE_AREA', 6);
define('DESTINATION_TYPE_ATTRACTION', 7);
define('DESTINATION_TYPE_AIRPORT', 10);
define('DESTINATION_TYPE_TRAIN_STATION', 11);
define('DESTINATION_TYPE_BUS_STOP', 12);
define('DESTINATION_TYPE_SHOPPING_AREA', 20);
define('DESTINATION_TYPE_HERITAGE', 21);
define('DESTINATION_TYPE_LAND_MARK', 22);

/*
|--------------------------------------------------------------------------
| Define Destination ID
|--------------------------------------------------------------------------
*/
define('DESTINATION_VIETNAM', 1);
define('DESTINATION_HALONG', 87);


/*
|--------------------------------------------------------------------------
| News
|--------------------------------------------------------------------------
*/
define('CAT_BESTPRICE', 1);
define('CAT_OUTSOURCE', 2);
define('CAT_MARKETING', 3);
define('CAT_NEWSPAPER', 4);

/*
|--------------------------------------------------------------------------
| FORM ACTION
|--------------------------------------------------------------------------
*/
define('ACTION_SEARCH', 'search');
define('ACTION_BOOK_NOW', 'book_now');
define('ACTION_CHECK_RATE', 'check_rate');
define('ACTION_BACK', 'back');
define('ACTION_NEXT', 'next');
define('ACTION_CANCEL', 'cancel');
define('ACTION_MAKE_BOOKING', 'make_booking');
define('ACTION_SUBMIT_REQUEST', 'submit_request');


define('TRIPLE', 3);
define('DOUBLE', 2);
define('SINGLE', 1);

// cancellation non-refundable id 
define('CANCELLATION_NO_REFUND', 1);
define('HOTEL_MAX_NIGHTS', 10);

/*
|--------------------------------------------------------------------------
| VIETNAM HOTELS
|--------------------------------------------------------------------------
|
| Vietnam Hotel Constant
|
*/

define('HOTEL_SEARCH_CRITERIA', 'hotel_search_criteria');
define('HOTEL_ROOM_RATE_SELECTED', 'hotel_room_rate_selected');


/*
|--------------------------------------------------------------------------
| HALONG CRUISES
|--------------------------------------------------------------------------
|
| Halong Cruises Constant
|
*/
define('CRUISE_SEARCH_CRITERIA', 'cruise_search_criteria');
define('TOUR_ACCOMMODATION_RATE_SELECTED', 'tour_accommodation_rate_selected');
define('CRUISE_TOUR_MAX_ADULTS', 20);
define('CRUISE_TOUR_MAX_CHILDREN', 10);
define('CRUISE_TOUR_MAX_INFANTS', 5);

define('TOUR_SEARCH_CRITERIA', 'tour_search_criteria');


/*
|--------------------------------------------------------------------------
| VIETNAM TOURS
|--------------------------------------------------------------------------
|
| Vietnam Tour Constant
|
*/
define('TOUR_MAX_DURATION', 15);

define('NAV_VIEW_DOMESTIC', 0);
define('NAV_VIEW_OUTBOUND', 1);
define('NAV_VIEW_CATEGORY', 2);

define('TOUR_DOMESTIC', 0);
define('TOUR_OUTBOUND', 1);

/*
 * TOUR_DEPARTURE_DATE_TYPE
 */
define('DEPARTURE_DAILY', 1);
define('DEPARTURE_SPECIFIC_WEEKDAYS', 2);
define('DEPARTURE_SPECIFIC_DATES', 3);


define('SINGLE_DEPARTING_FROM', 1);
define('MULTIPLE_DEPARTING_FROM', 2);


/*
|--------------------------------------------------------------------------
| SORT BY
|--------------------------------------------------------------------------
*/
define('SORT_BY_POPULAR', 'popular');
define('SORT_BY_PRICE', 'price');
define('SORT_BY_NAME', 'name');
define('SORT_BY_REVIEW', 'review');
define('SORT_BY_STAR', 'star');
define('SORT_BY_DURATION', 'duration');


/*
|--------------------------------------------------------------------------
| Promotion Types && Surcharge Types
|--------------------------------------------------------------------------
*/

define('PROMOTION_TYPE_CUSTOMIZED', 1);
define('PROMOTION_TYPE_EARLY_BIRD', 2);
define('PROMOTION_TYPE_LAST_MINUTE', 3);

define('DISCOUNT_TYPE_DISCOUNT', 1);
define('DISCOUNT_TYPE_AMOUNT_PER_BOOKING', 2);
define('DISCOUNT_TYPE_AMOUNT_PER_NIGHT', 3);
define('DISCOUNT_TYPE_FREE_NIGHT', 4);
define('DISCOUNT_TYPE_AMOUNT_PER_PAX', 5);

define('APPLY_ON_EVERY_NIGHT', 1);
define('APPLY_ON_SPECIFIC_NIGHT', 2);
define('APPLY_ON_SPECIFIC_DAY', 3);
define('APPLY_ON_FIRST_NIGHT', 4);
define('APPLY_ON_LAST_NIGHT', 5);


define('MULTIPLE_TIME_PER_BOOKING', 1);
define('ONCE_TIME_PER_BOOKING', 2);

define('SUR_PER_ADULT_PER_BOOKING', 1);
define('SUR_PER_NIGHT', 2);
define('SUR_PER_ROOM', 3);
define('SUR_PER_ROOM_PER_NIGHT', 4);
define('SUR_PER_ROOM_PRICE', 5);
/*
|--------------------------------------------------------------------------
| Map Module
|--------------------------------------------------------------------------
|
|
*/
define('SEARCH_DISTANCE', 5); // in km

/*
|--------------------------------------------------------------------------
| Chat Module
|--------------------------------------------------------------------------
|
|
*/
define('YAHOO', 'YAHOO');
define('SKYPE', 'SKYPE');
define('PHONE_SUPPORT','(04) 3978 1425');
define('PHONE_SUPPORT_FLIGHT','(04) 3978 1805');
define('HOTLINE_SUPPORT','0936 179 428');
define('EMAIL_SUPPORT','sales@Bestviettravel.xyz');


define('ADMIN_USER_ID', 1);

/**
 *
 * Booking Site
 *
 */

define('SITE_BESTPRICE_VN', 1);

define('SITE_MOBILE_BESTPRICE_VN', 2);

/**
 * REQUEST TYPE
*/

define('REQUEST_TYPE_RESERVATION', 1);

define('REQUEST_TYPE_REQUEST', 2);


/**
 *
 * CUSTOME TYPE
 *
*/
define('CUSTOMER_TYPE_NEW', 1);

define('CUSTOMER_TYPE_RETURN', 2);

define('CUSTOMER_TYPE_RECOMMENDED', 3);

/**
 * RESERVATION TYPE
 */

define('RESERVATION_TYPE_CRUISE_TOUR',1);

define('RESERVATION_TYPE_HOTEL',2);

define('RESERVATION_TYPE_TRANSFER',3);

define('RESERVATION_TYPE_LAND_TOUR',4);

define('RESERVATION_TYPE_OTHER',5);

define('RESERVATION_TYPE_ADDITONAL_CHARGE',6);

define('RESERVATION_TYPE_VISA',7);

define('RESERVATION_TYPE_FLIGHT',8);

define('RESERVATION_TYPE_NONE',-1);


/*
|--------------------------------------------------------------------------
| Vietnam Flight Module
|--------------------------------------------------------------------------
|
| Flight ticket: VNA, VJ, BL
|
*/
define('FLIGHT_HOME', 'flights/');
define('FLIGHT_DESTINATION', 'flights/flight-to');
define('FLIGHT_SEARCH', 'flight-search/');
define('FLIGHT_DETAIL','flights/flight-details.html');
define('FLIGHT_PAYMENT','flights/payment.html');

/**
 * CONSTANT FOR FLIGHT MODULE
 */
define('FLIGHT_AGENT_CODE', "BSP");

define('FLIGHT_SECURITY_CODE', "PMxca4v5wm366LwMbqCVwI26aywDXoLiyjx1BMnebo");

define('FLIGHT_V_HASH', 2);

define('FLIGHT_LANG', 'vn');

define('FLIGHT_TYPE_DEPART', 'depart');

define('FLIGHT_TYPE_RETURN', 'return');

define('FLIGHT_SEARCH_CRITERIA', 'flight_search_criteria_front_end');

/*
 * For storing flight search & booking information
 */
define('FLIGHT_BOOKING_SESSISON_DATA', 'flight_booking_session_data');

define('FLIGHT_SEARCH_DATA', 'flight_search_data');

define('FLIGHT_BOOKING_INFO','flight_booking_info');

define('FLIGHT_TIMESTAMP', 'flight_timestamp');

define('FLIGHT_VNISC_SID', 'flight_vnisc_sid');


define('FLIGHT_TYPE_ROUNDWAY','roundway');

define('FLIGHT_TYPE_ONEWAY','oneway');


define('VNISC_VIEW_STATE','/wEPDwUJMTU5NzMyNTQxZGQ=');


define('FLIGHT_WEB_SERVICE_URL', 'http://webservice.muadi.vn/OTHBookingProcess.asmx?WSDL');

define('FLIGHT_WEB_SERVICE_AGENT', 'WS_BSP');

define('FLIGHT_WEB_SERVICE_SECURITY_CODE', 'iyMr95mX2NVYa1VnOG3kECOWR74u//YVYV61Zne704');

define('FLIGHT_PROCESS_CONTINUE', '<!--ProcessContinue-->');

define('FLIGHT_PROCESS_COMPLETED', '<!--ProcessCompleted-->');

define('FLIGHT_PROCESS_WAITING', 'WAITING');

define('FLIGHT_CURL_ERROR', 'FLIGHT_CURL_ERROR');

define('FLIGHT_ERROR_TM','ERROR-TM');

define('FLIGHT_ERROR_UN', 'ERROR-UN');

define('FLIGHT_ERROR_INTERNAL', 'ERROR_INTERNAL');

define('FLIGHT_NO_FLIGHT', 'NO_FLIGHT');

define('FLIGHT_PASSENGER_LIMIT', 9);
define('FLIGHT_MAX_ADULTS', 20);
define('FLIGHT_MAX_CHILDREN', 10);
define('FLIGHT_MAX_INFANTS', 5);

define('NEWS_MAX_LENGTH', 240);

define('FLIGHT_NEWS_LIMIT', 7);


/*
|--------------------------------------------------------------------------
| Payment data config
|--------------------------------------------------------------------------
|
*/
define('INTERNATIONAL_PAYMENT_SECURE_SECRET', "7F31BD5720F0C9C671D369ABED4BBB27");			// 6D0870CDE5F24F34F3915FB0045120DB
define('INTERNATIONAL_PAYMENT_VIRTUAL_CLIENT_URL', "https://onepay.vn/vpcpay/vpcpay.op");	// http://mtf.onepay.vn/vpcpay/vpcpay.op

define('DOMESTIC_PAYMENT_SECURE_SECRET', "A8C8FF114E3353DFAB5C113CEF460431");				// A3EFDFABA8653DF2342E8DAC29B51AF0
define('DOMESTIC_PAYMENT_VIRTUAL_CLIENT_URL', "https://onepay.vn/onecomm-pay/vpc.op");		// http://mtf.onepay.vn/onecomm-pay/vpc.op

define('PAYMENT_METHOD_AT_OFFICE', 		1);
define('PAYMENT_METHOD_AT_HOME', 		2);
define('PAYMENT_METHOD_CREDIT_CARD', 	3);
define('PAYMENT_METHOD_DOMESTIC_CARD', 	4);
define('PAYMENT_METHOD_BANK_TRANSFER', 	5);

define('FLIGHT_PAYMENT_NOTIFICATION_EMAIL', 'flightbestprice@gmail.com');

/**
 * Booking Status & Reservation Status
 */

define('BOOKING_NEW', 1);
define('BOOKING_PENDING', 2);
define('BOOKING_DEPOSIT', 3);
define('BOOKING_FULL_PAID', 4);
define('BOOKING_CANCEL', 5);
define('BOOKING_CLOSE_WIN', 6);
define('BOOKING_CLOSE_LOST', 7);

/*
|--------------------------------------------------------------------------
| Review config
|--------------------------------------------------------------------------
|
*/

/* Define score type */
define('TYPE_CLEAN', 0);
define('TYPE_COMFORT', 1);
define('TYPE_LOCATION', 2);
define('TYPE_SERVICES', 3);
define('TYPE_STAFF', 4);
define('TYPE_VALUE_MONEY', 5);
define('TYPE_CRUISE_QUALITY', 6);
define('TYPE_DINING_FOOD', 7);
define('TYPE_CABIN_QUALITY', 8);
define('TYPE_STAFF_QUALITY', 9);
define('TYPE_ENTERTAIMENT_ACTIVITY', 10);
define('TYPE_ITINERARY', 11);

/*
|--------------------------------------------------------------------------
| SIGN-UP/SIGN-IN/NEWSLETTER
|--------------------------------------------------------------------------
|
*/

define('LOGIN_USER', 'login_user');
define('EMAIL_VALIDATE_CODE', 'Bpv@Email');

define('NEWS_LETTER',1);
define('SIGN_IN',2);
define('LETTER_TO_SIGN',3);


/*
|--------------------------------------------------------------------------
| MOBILE ON OFF
|--------------------------------------------------------------------------
|
*/

define('MOBILE_ON_OFF', 'MOBILE_ON_OFF');


/* End of file constants.php */
/* Location: ./application/config/constants.php */