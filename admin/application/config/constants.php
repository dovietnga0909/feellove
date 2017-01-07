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


/*
|--------------------------------------------------------------------------
| Menues
|--------------------------------------------------------------------------
*/
define('MNU_ACCOUNT', 		'MNU_ACCOUNT');
define('MNU_USER', 			'MNU_USER');
define('MNU_CUSTOMER', 		'MNU_CUSTOMER');
define('MNU_HOTEL', 		'MNU_HOTEL');
define('MNU_PARTNER', 		'MNU_PARTNER');
define('MNU_DESTINATION', 	'MNU_DESTINATION');
define('MNU_FLIGHTS', 		'MNU_FLIGHTS');
define('MNU_SYSTEM', 		'MNU_SYSTEM');
define('MNU_LOGS', 			'MNU_LOGS');
define('MNU_CANCELLATION', 	'MNU_CANCELLATION');
define('MNU_FACILITY', 		'MNU_FACILITY');
define('MNU_ROLE', 			'MNU_ROLE');
define('MNU_NEWS', 			'MNU_NEWS');
define('MNU_CRUISES', 		'MNU_CRUISES');
define('MNU_TOURS', 		'MNU_TOURS');
define('MNU_DASHBOARD', 	'MNU_DASHBOARD');

define('MNU_BOOKING', 		'MNU_BOOKING');

define('MNU_HOTEL_SURCHARGE', 	'MNU_HOTEL_SURCHARGE');
define('MNU_ADVERTISE', 		'MNU_ADVERTISE');
define('MNU_HOTEL_PROMOTION', 	'MNU_HOTEL_PROMOTION');
define('MNU_HOTEL_PROFILE', 	'MNU_HOTEL_PROFILE');
define('MNU_HOTEL_REVIEWS', 	'MNU_HOTEL_REVIEWS');
define('MNU_HOTEL_PARTNER', 	'MNU_HOTEL_PARTNER');
define('MNU_HOTEL_CONTRACT', 	'MNU_HOTEL_CONTRACT');

define('MNU_HOTEL_RATE_AVAILABILITY', 'MNU_HOTEL_RATE_AVAILABILITY');

define('MNU_CRUISE_PROFILE', 	'MNU_CRUISE_PROFILE');
define('MNU_CRUISE_SURCHARGE', 	'MNU_CRUISE_SURCHARGE');
define('MNU_CRUISE_PROMOTION', 	'MNU_CRUISE_PROMOTION');
define('MNU_CRUISE_REVIEWS', 	'MNU_CRUISE_REVIEWS');
define('MNU_CRUISE_CONTRACT', 	'MNU_CRUISE_CONTRACT');

define('MNU_TOUR_PROFILE', 		'MNU_TOUR_PROFILE');
define('MNU_TOUR_REVIEWS', 		'MNU_TOUR_REVIEWS');
define('MNU_TOUR_CONTRACT', 	'MNU_TOUR_CONTRACT');

define('MNU_DESTINATION_PROFILE',	'MNU_DESTINATION_PROFILE');

define('MNU_TOUR_RATE_AVAILABILITY', 'MNU_TOUR_RATE_AVAILABILITY');

define('MNU_MARKETING', 		'MNU_MARKETING');
define('MNU_NEWSLETTER',		'MNU_NEWSLETTER');

define('MNU_TOUR_CATEGORY', 	'MNU_TOUR_CATEGORY');

define('MNU_TOUR_PROMOTION', 	'MNU_TOUR_PROMOTION');

/*
|--------------------------------------------------------------------------
| URL
|--------------------------------------------------------------------------
*/
define('URL_HOTEL', 		'hotels');
define('URL_CRUISE', 		'cruises');
define('URL_USER', 			'users');
define('URL_TOUR', 			'tours');
define('URL_ACCOUNT',		'accounts');
define('URL_NEWSLETTER',	'newsletters');
define('URL_TEMPLATE',		'templates');
define('URL_TOUR_CATEGORY', 'categories');
define('URL_ADVERTISE', 	'advertises');


/*
|--------------------------------------------------------------------------
| Module
|--------------------------------------------------------------------------
*/
define('MODULE_USER', 			'MODULE_USER');
define('MODULE_CUSTOMER', 		'MODULE_CUSTOMER');
define('MODULE_HOTEL', 			'MODULE_HOTEL');
define('MODULE_PARTNER', 		'MODULE_PARTNER');
define('MODULE_DESTINATION', 	'MODULE_DESTINATION');
define('MODULE_FACILITY', 		'MODULE_FACILITY');
define('MODULE_NEWS', 			'MODULE_NEWS');
define('MODULE_SYSTEM', 		'MODULE_SYSTEM');
define('MODULE_ROOM_TYPES', 	'MODULE_ROOM_TYPES');
define('MODULE_FLIGHTS', 		'MODULE_FLIGHTS');
define('MODULE_AIRLINES', 		'MODULE_AIRLINES');
define('MODULE_FLIGHT_CATEGORIES', 		'MODULE_FLIGHT_CATEGORIES');
define('MODULE_CRUISES', 		'MODULE_CRUISES');
define('MODULE_CABINS', 		'MODULE_CABINS');
define('MODULE_TOURS', 			'MODULE_TOURS');
define('MODULE_ACCOMMODATIONS', 'MODULE_ACCOMMODATIONS');
define('MODULE_ITINERARY', 		'MODULE_ITINERARY');
define('MODULE_HOTEL_REVIEWS', 	'MODULE_HOTEL_REVIEWS');
define('MODULE_ACTIVITIES', 	'MODULE_ACTIVITIES');
define('MODULE_TOUR_CATEGORY', 	'MODULE_TOUR_CATEGORY');
define('MODULE_TOUR_DEPARTURE', 'MODULE_TOUR_DEPARTURE');
define('MODULE_ADVERTISES', 	'MODULE_ADVERTISES');

/*
define('FLIGHT', 1);
define('HOTEL', 2);
*/


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
define('DESTINATION_HANOI', 2);
define('DESTINATION_HALONG', 87);
define('DESTINATION_QUANGNINH', 257);


/*
|--------------------------------------------------------------------------
| Define System parameter
|--------------------------------------------------------------------------
*/

define('STATUS_ACTIVE',	1);
define('STATUS_INACTIVE',	0);
define('STATUS_AVAIABLE',	1);

define('GO_UP',		'up');
define('GO_DOWN',	'down');

define('DELETED', 1);

define('NORMAL_ID_SEGMENT', 3);
define('PAGING_SEGMENT',2);
define('ACTION_MESSAGE', 'message');

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
define('AD_PAGE_CRUISE_HOME',7);
define('AD_PAGE_TOUR_HOME',8);
define('AD_PAGE_TOUR_DOMISTIC',9);
define('AD_PAGE_TOUR_OUTBOUND',10);
define('AD_PAGE_TOUR_DESTINATION',11);
define('AD_PAGE_TOUR_CATEGORY',12);



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

define('CAT_BESTPRICE', 1);
define('CAT_OUTSOURCE', 2);
define('CAT_MARKETING', 3);
define('CAT_NEWSPAPER', 4);

/*
|--------------------------------------------------------------------------
| Photos
|--------------------------------------------------------------------------
*/
define('UPLOAD_FILE_LIMIT', 20);

/*
|--------------------------------------------------------------------------
| DATE TIME FORMAT
|--------------------------------------------------------------------------
*/
define('DATE_FORMAT',				'd-m-Y'); // not support dd/mm/yyyy
define('DATE_TIME_FORMAT',			'd-m-Y H:i:s');
define('DB_DATE_FORMAT',			'Y-m-d');
define('DB_DATE_TIME_FORMAT',		'Y-m-d H:i:s');
define('DATE_FORMAT_CALLENDAR',		'dd-mm-yyyy');
define('DATE_TIME_SHORT_FORMAT',	'd-m-y H:i');

define('DATE_FORMAT_LBL', 'dd-mm-yyyy');

define('CURRENCY_DECIMAL',0);

define('DECIMAL_HUNDRED', 100);
define('DECIMAL_THOUSAND', 1000);


/*
|--------------------------------------------------------------------------
| SEARCH CRITERIA NAME
|--------------------------------------------------------------------------
*/
define('CANCELLATION_SEARCH_CRITERIA', 'cancellation_search_criteria');
define('SURCHARGE_SEARCH_CRITERIA', 'surcharge_search_criteria');
define('ADVERTISE_SEARCH_CRITERIA', 'advertise_search_criteria');
define('PROMOTION_SEARCH_CRITERIA', 'promotion_search_criteria');
define('HOTEL_RATE_SEARCH_CRITERIA', 'hotel_rate_search_criteria');
define('TOUR_RATE_SEARCH_CRITERIA', 'tour_rate_search_criteria');

define('CRUISE_PROMOTION_SEARCH_CRITERIA', 'promotion_search_criteria');
define('TOUR_PROMOTION_SEARCH_CRITERIA', 'tour_promotion_search_criteria');

define('HOTLINE_SCHEDULE_SEARCH_CRITERIA', 'HOTLINE_SCHEDULE_SEARCH_CRITERIA');

define('VOUCHER_SEARCH_CRITERIA', 'voucher_search_criteria');
define('BPV_PROMOTION_SEARCH_CRITERIA', 'bpv_promotion_search_criteria');


/*
|--------------------------------------------------------------------------
| FORM ACTION
|--------------------------------------------------------------------------
*/
define('ACTION_SEARCH', 'search');
define('ACTION_ADVANCED_SEARCH', 'advanced_search');
define('ACTION_RESET', 'reset');
define('ACTION_SAVE', 'save');
define('ACTION_UPLOAD', 'upload');
define('ACTION_BACK', 'back');
define('ACTION_NEXT', 'next');
define('ACTION_CANCEL', 'cancel');

/*
|--------------------------------------------------------------------------
| Promotion Types
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

define('PROMOTION_CREATE_TEMP','promotion_create_temp');
define('PROMOTION_EDIT_TEMP','promotion_edit_temp');

/*
|--------------------------------------------------------------------------
| HOTEL RATES
|--------------------------------------------------------------------------
*/

define('RATE_DAYS_SHOW', 10);
define('TRIPLE', 3);
define('DOUBLE', 2);
define('SINGLE', 1);


/*
|--------------------------------------------------------------------------
| API Keys
|--------------------------------------------------------------------------
*/
define('GOOGLE_API_KEY',			'AIzaSyBZmSZcW_u9_MO_Xfj81HRNhGBdtZCu5Yc');


define('CB_SEARCH_CRITERIA', 'cb_search_criteria');

define('ADULT_LIMIT', 50);
define('CHILDREN_LIMIT', 10);

/*
|--------------------------------------------------------------------------
| Service Types
|--------------------------------------------------------------------------
*/
define('HOTEL', 1);
define('FLIGHT', 2);
define('CRUISE', 3);
define('TOUR', 4);

define('EMAIL_BOOKING_TOUR', 1);
define('EMAIL_BOOKING_HOTEL', 2);
define('EMAIL_BOOKING_TRANSFER', 3);
define('EMAIL_BOOKING_VISA', 4);
define('EMAIL_BOOKING_OTHER', 5);
define('EMAIL_BOOKING_DEPOSIT', 6);

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


define('RESERVATION_NEW', 1);
define('RESERVATION_SENDING', 2);
define('RESERVATION_RESERVED', 3);
define('RESERVATION_DEPOSIT', 5);
define('RESERVATION_FULL_PAID', 6);
define('RESERVATION_CLOSE_WIN', 7);
define('RESERVATION_CANCEL', 4);


define('RESERVATION_TYPE_CRUISE_TOUR',1);
define('RESERVATION_TYPE_HOTEL',2);
define('RESERVATION_TYPE_TRANSFER',3);
define('RESERVATION_TYPE_LAND_TOUR',4);
define('RESERVATION_TYPE_OTHER',5);
define('RESERVATION_TYPE_VISA',7);
define('RESERVATION_TYPE_FLIGHT',8);


define('TASK_CUSTOMER_MEETING', 1);
define('TASK_CUSTOMER_PAYMENT', 2);
define('TASK_SERVICE_RESERVATION', 3);
define('TASK_TRANSFER_REMINDER', 4);
define('TASK_SERVICE_PAYMENT', 5);
define('TASK_TO_DO', 6);


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


/**
 *
 * FACILITY TYPE
 *
 */
define('FACILITY_HOTEL', 1);
define('FACILITY_ROOM_TYPE', 2);
define('FACILITY_CRUISE', 3);
define('FACILITY_CABIN', 4);


/**
 * ROLE - RIGHT
 */
define('FULL_PRIVILEGE', 1);  // read, edit & 'delete'
define('EDIT_PRIVILEGE', 2);
define('VIEW_PRIVILEGE', 3);


/**
 * PHOTO CATEGORY
 */
define('PHOTO_HOTEL', 	1);
define('PHOTO_CRUISE', 	2);
define('PHOTO_TOUR', 	3);
define('PHOTO_DESTINATION', 4);

/*
|--------------------------------------------------------------------------
| Data Types : Roles and Rights
|--------------------------------------------------------------------------
*/
define('DATA_FLIGHT',				'FLIGHT');
define('DATA_TOUR',					'TOUR');
define('DATA_HOTEL',				'HOTEL');
define('DATA_CRUISE',				'CRUISE');
define('DATA_PROMOTION',			'PROMOTION');
define('DATA_REVIEW',				'REVIEW');
define('DATA_DESTINATION',			'DESTINATION');
define('DATA_CUSTOMER',		    	'CUSTOMER');
define('DATA_PARTNER',				'PARTNER');
define('DATA_FAQs',					'FAQs');
define('DATA_VISA',					'VISA');
define('DATA_OPTIONAL_SERVICE',		'OPTIONAL SERVICE');
define('DATA_FACILITY',				'FACILITY');
define('DATA_ACTIVITY',				'ACTIVITY');
define('DATA_CUSTOMER_BOOKING',		'CUSTOMER BOOKING');
define('DATA_SERVICE_RESERVATION',	'SERVICE RESERVATION');
define('DATA_RIGHTS',				'RIGHTS');
define('DATA_SYSTEM',				'SYSTEM');
define('DATA_USER',					'USER');
define('DATA_ASSIGN_REQUEST',		'ASSIGN REQUEST');
define('DATA_CANCELLATIONS',		'CANCELLATIONS');
define('DATA_ADVERTISES',			'ADVERTISES');
define('DATA_NEWS',					'NEWS');
define('DATA_MARKETING',			'MARKETING');


define('RIGHT_ASSIGN_REQUEST', 16);

/*
|--------------------------------------------------------------------------
| Payment Method
|--------------------------------------------------------------------------
*/
define('PAYMENT_METHOD_AT_OFFICE', 		1);
define('PAYMENT_METHOD_AT_HOME', 		2);
define('PAYMENT_METHOD_CREDIT_CARD', 	3);
define('PAYMENT_METHOD_DOMESTIC_CARD', 	4);
define('PAYMENT_METHOD_BANK_TRANSFER', 	5);


/*
|--------------------------------------------------------------------------
| CACHE MANAGEMENT
|--------------------------------------------------------------------------
*/
define('CACHE_HOTEL_PAGE', 1);

define('CACHE_CRUISE_PAGE', 2);

define('CACHE_TOUR_PAGE', 3);

define('CACHE_HOTEL_DESTINATION_PAGE', 4);

define('CACHE_CRUISE_TOUR_PAGE', 5);

define('CACHE_TOUR_DESTINATION_PAGE', 6);

define('CACHE_DESTINATION_PAGE', 7);

define('CACHE_TOUR_CATEGORY_PAGE', 8);

/*
 * Define score type
*/
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
 *	ACCOUNTS DEFINE 
 */

define('NEWS_LETTER', 1);
define('SIGN_IN', 2);
define('LETTER_TO_SIGN', 3);
define('SYSTEM', 0);

define('EMAIL_VALIDATE_CODE', 'Bpv@Email');
define('SITE_NAME',	'snotevn.com:8888');
define('BRANCH_NAME',	'Best Price Vietnam');


/*
|--------------------------------------------------------------------------
| NEWSLETTERS
|--------------------------------------------------------------------------
*/

// send limit , resend limit on day 
define('SEND_EMAIL_LIMITED', 1000);
define('RESEND_EMAIL_LIMITED', 500);

// email send limit of account on day
define('EMAIL_LIMITED_ACCOUNT', 50);

// email of snotevn.com:8888
define('EMAIL_BESTPRICE', 'snotevn.com:8888');

// session newsletter
define('NEWSLETTER_TEMP', 'newsletter_temp');
define('NEWSLETTER_EDIT_TEMP', 'newsletter_edit_temp');

// template html
define('HOTEL_HTML', 0);

define('CRUISE_HTML', 1);

define('TOUR_HTML', 2);

define('GENERAL_HTML', 3);

// page url
define('CRUISE_HL_HOME_PAGE','du-thuyen-ha-long');
define('TOUR_DETAIL_PAGE', 'tour');

/*
 * Status newsletter
 */

define('STATUS_NEW', 		0);
define('STATUS_SENDING', 	1);
define('STATUS_SENT', 		2);
define('STATUS_STOP', 		3);

/*
 * Status log
 */

define('LOG_INIT',	 	0); // init status
define('LOG_SUCCESS', 	1);	// send ok
define('LOG_FALSE',		2);	// send false

define('ARRAY_LIMITED',	1);

/*
|--------------------------------------------------------------------------
| VIETNAM TOURS
|--------------------------------------------------------------------------
|
| Vietnam Tour Constant
|
*/
define('TOUR_MAX_DURATION', 15);

/*
 * TOUR_DEPARTURE_DATE_TYPE
*/
define('DEPARTURE_DAILY', 1);
define('DEPARTURE_SPECIFIC_WEEKDAYS', 2);
define('DEPARTURE_SPECIFIC_DATES', 3);


define('SINGLE_DEPARTING_FROM', 1);
define('MULTIPLE_DEPARTING_FROM', 2);

/*
 * USER ALLOW ASSIGN REQUEST
 */
define('YES', 1);
define('NO', 0);
/**
 * VOUCHER STATUS
 */
define('VOUCHER_STATUS_NEW', 0);
define('VOUCHER_STATUS_PENDING', 1);
define('VOUCHER_STATUS_USED', 2);

/*
 * VOUCHER DELIVERED
 */
define('VOUCHER_DELIVERED_YES', 1);
define('VOUCHER_DELIVERED_NO', 0);

/* End of file constants.php */
/* Location: ./application/config/constants.php */