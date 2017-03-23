<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = '';

$route['suggest_flight_destinations'] 	= "home/suggest_flight_destinations";
$route['suggest_hotel_destinations'] 	= "home/suggest_hotel_destinations";
$route['suggest_hotels'] 				= "home/suggest_hotels";

$route['suggest_cruises'] 				= "cruises/suggest_cruises";
$route['suggest_cruise_destinations'] 	= "cruises/suggest_destinations";

$route['suggest_tour_destinations'] 	= "tours/suggest_destinations";

// Ajax request
$route['remove_recent_item'] 			= "home/remove_recent_item";
$route['groupon_request'] 				= "contacts/groupon_request";
$route['get-current-search/(:num)']		= "home/get_current_search/$1";

$route['tour_request'] 					= "contacts/tour_request";

$route['get-hot-line/(:num)'] 			= "home/get_hot_line/$1";
$route['get-hotline-popup'] 			= "home/get_hotline_popup";
$route['get-contact-popup'] 			= "home/get_contact_popup";
$route['get-hotline-box'] 			    = "home/get_hotline_box";

$route['news-letter-request']			= "login/news_letter_request";
$route['get-sign-up-popup']				= "login/get_sign_up_popup";
$route['get-sign-in-popup']				= "login/get_sign_in_popup";

$route['sign-up-request'] 				= "login/sign_up_request";
$route['sign-in-request'] 				= "login/sign_in_request";
$route['sign-out-request']				= "login/sign_out";

$route['get-marketing-popup']			= "home/get_marketing_popup";

$route['review_voting'] 				= "reviews/review_voting";


/**
 * Route for Vietnam Flight
 * 
 */

$route['(?i)'.FLIGHT_HOME_PAGE] = "flights";
$route['(?i)'.FLIGHT_SEARCH_PAGE.'?:any$'] = 'flights/search';

$route['(?i)get-flight-data'] = "flights/get_flight_data/";

$route['(?i)get-flight-detail'] = "flights/get_flight_detail/";

$route['(?i)get-flight-detail-inter'] = "flights/get_flight_detail_inter/";

$route['(?i)'.FLIGHT_DESTINATION_PAGE.'([^\/]*)-(:num).html$'] = 'flights/flight_to_destination/$2';

$route['(?i)'.FLIGHT_AIRLINE_PAGE.'([^\/]*)-(:num).html$'] = 'flights/flight_airline/$2';

$route['(?i)'.FLIGHT_CATEGORY_PAGE.'([^\/]*)-(:num).html$'] = 'flights/flight_category/$2';

$route['(?i)'.FLIGHT_DETAIL_PAGE] 	= "flights/flight_detail";

$route['(?i)'.FLIGHT_BOOKING_PAGE] = "flights/flight_payment";

$route['(?i)^'.FLIGHT_EXCEPTION_PAGE.'?:any$'] = "flights/exception";

$route['(?i)'.FLIGHT_HOME_PAGE.'/customer-booked.html?:any'] = "flights/customer_booked";

$route['(?i)'.FLIGHT_HOME_PAGE.'/ticket-booked.html?:any'] = "flights/ticket_booked";

/**
 * Route for News
 */
$route['(?i)'.NEWS_HOME_PAGE] = "news";
$route['(?i)'.NEWS_DETAILS_PAGE.'([^\/]*)-(:num).html$'] = "news/details/$2";
$route['(?i)'.NEWS_CATEGORY_PAGE.'([^\/]*)-(:num).html$'] = "news/category/$2";

$route['(?i)khuyen-mai/'.'([^\/]*)-(:num).html$'] = 'news/get_news/$2';

$route['(?i)khuyen-mai/tuan-le-vang.html$'] = 'news/get_news/13';
$route['(?i)khuyen-mai/cang-dong-cang-vui.html$'] = 'news/get_news/15';
$route['(?i)tin-tuc/mua-chung-doi.html$'] = 'news/details/16';
$route['(?i)tin-tuc/tri-an-khach-hang.html$'] = 'news/details/28';
$route['(?i)tin-tuc/minh-di-choi-nhe.html$'] = 'news/details/37';
$route['(?i)tin-tuc/hop-tac-nam-a-bank.html$'] = 'news/details/39';
$route['(?i)tin-tuc/hop-tac-fpt-microsoft-store.html$'] = 'news/details/56';
$route['(?i)tin-tuc/trai-nghiem-ha-long.html$'] = 'news/details/66';


/**
 * Route for Vietnam Hotels
 */
$route['(?i)khach-san'] = "hotels";

$route['(?i)^khach-san-([^\/]*)-(:num).html$'] = 'hotels/hotel_destination/$2';

$route['(?i)^khach-san/tim-kiem.html?:any$'] = 'hotels/search';
$route['(?i)^khach-san/ho-tro-tim-kiem.html?:any$'] = 'hotels/search_suggestion';

$route['(?i)^khach-san/([^\/]*)-(:num).html?:any$'] = 'hotel_details/index/$2';

$route['(?i)^khach-san/dat-phong/([^\/]*)-(:num).html?:any$'] = 'hotel_details/booking/$2';

/**
 * Contact & Confirm Booking
 */

$route['(?i)^'.CONFIRM_PAGE.'?:any$'] = 'contacts/confirm';
$route['(?i)^'.CONTACT_US_PAGE.'?:any$'] = 'contacts/index';

/**
 * About us & Term-condition & 
 */

$route['(?i)^'.ABOUT_US_PAGE.'?:any$'] = 'contacts/about_us';
$route['(?i)^'.TERM_CONDITION_PAGE.'?:any$'] = 'contacts/term_condition';
$route['(?i)^'.PRIVACY_PAGE.'?:any$'] = 'contacts/privacy';
$route['(?i)^'.FAQS_PAGE.'?:any$'] = 'contacts/faq';
$route['(?i)^'.PAYMENT_METHODS_PAGE.'?:any$'] = 'contacts/payment_methods';
$route['(?i)^'.ACCOMPLISHMENT_PAGE.'?:any$'] = 'contacts/accomplishment';
$route['(?i)^'.TESTIMONIAL_PAGE.'?:any$'] = 'contacts/testimonial';
$route['(?i)^'.BESTPRICE_WITH_PRESS_PAGE.'?:any$'] = 'contacts/press';

$route['(?i)^'.COMPANY_ADDRESS_PAGE.'?:any$'] = 'contacts/company_address';

$route['(?i)^'.HOT_DEAL_PAGE.'?:any$'] = 'deals/index';

$route['(?i)^apply-promotion-code$'] = 'deals/apply_promotion_code';

/**
 * Payment
 */
$route['(?i)thanh-toan/dang-xu-ly.html'] 		= "payment/pending";
$route['(?i)thanh-toan/that-bai.html?:any$'] 	= "payment/unsuccess";
$route['(?i)thanh-toan/hoa-don.html?:any$'] 	= "payment/invoice";

$route['(?i)thanh-toan?:any$'] = "payment";


/**
 * Route for Halong Cruise
 */
$route['(?i)'.TOUR_HL_DETAIL_PAGE.'([^\/]*)-(:num).html?:any$'] = 'cruise_tour_details/index/$2';

$route['(?i)'.CRUISE_HL_HOME_PAGE] = "cruises";
$route['(?i)'.CRUISE_HL_SEARCH_PAGE.'?:any$'] = "cruises/search";
$route['(?i)'.CRUISE_HL_HOME_PAGE.'/ho-tro-tim-kiem.html?:any$'] = 'cruises/search_suggestion';
$route['(?i)'.CRUISE_HL_DETAIL_PAGE.'([^\/]*)-(:num).html?:any$'] = 'cruise_details/index/$2';
$route['(?i)'.CRUISE_HL_BOOKING_PAGE.'([^\/]*)-(:num).html?:any$'] = 'cruise_details/booking/$2';

/**
 * Route for Tours
 */
$route['(?i)'.TOUR_HOME_PAGE] = "tours";

$route['(?i)'.TOUR_SEARCH_PAGE.'?:any$'] = "tours/search";
$route['(?i)'.TOUR_HOME_PAGE.'/ho-tro-tim-kiem.html?:any$'] = 'tours/search_suggestion';

$route['(?i)'.TOUR_DOMESTIC_PAGE] = "tours/domestic_tours";
$route['(?i)'.TOUR_OUTBOUND_PAGE] = "tours/outbound_tours";
$route['(?i)'.TOUR_CATEGORY_PAGE] = "tours/category_tours";

$route['(?i)'.TOUR_DESTINATION_PAGE.'([^\/]*)-(:num).html?:any$'] = "tours/destination_tours/$2";
$route['(?i)'.TOUR_DESTINATION_DETAIL_PAGE.'([^\/]*)-(:num).html?:any$'] = "destinations/destination_details/$2";

$route['(?i)'.TOUR_CATEGORY_DETAIL_PAGE.'([^\/]*)-(:num).html?:any$'] = "tours/category_tours_details/$2";

$route['(?i)'.TOUR_DOWNLOAD.'([^\/]*)-(:num).html?:any$'] = 'tour_details/download_itinerary/$2';

$route['(?i)'.TOUR_DETAIL_PAGE.'([^\/]*)-(:num).html?:any$'] = 'tour_details/index/$2';

$route['(?i)'.TOUR_BOOKING_PAGE.'([^\/]*)-(:num).html?:any$'] = 'tour_details/booking/$2';

/**
 * Route for get data overview
 */
$route['show-data-overview'] = 'destinations/show_data_overview';


/* End of file routes.php */
/* Location: ./application/config/routes.php */