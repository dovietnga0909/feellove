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

$route['default_controller'] 	= 'auth/auth';
$route['404_override'] 			= '';
$route['error'] 				= 'common/error';

// Authentication
$route['login'] 				= 'auth/auth';
$route['logout'] 				= 'auth/auth/logout';
$route['change_password'] 		= 'auth/auth/change_password';

// Users
$route['users'] 					= 'users/users';
$route['users/:num']				= 'users/users/index/$1';
$route['users/create'] 				= 'users/users/create';
$route['users/edit/:num'] 			= 'users/users/edit/$1';
$route['users/activate/:num'] 		= 'users/users/activate/$1';
$route['users/deactivate/:num'] 	= 'users/users/deactivate/$1';
$route['users/hotline/(:num)'] 		= 'users/users/hotline/$1';
$route['users/schedules'] 			=  'users/users/schedule';
$route['users/delete/:num'] 		= 'users/users/delete/$1';

$route['users/schedules/create'] 				=  'users/users/create_schedule';
$route['users/schedules/edit/(:num)'] 			=  'users/users/edit_schedule/$1';
$route['users/schedules/delete/(:num)'] 		=  'users/users/delete_schedule/$1';

// Account
$route['accounts']					= 'accounts/accounts';
$route['accounts/edit/:num']		= 'accounts/accounts/edit/$1';
$route['accounts/delete/:num'] 		= 'accounts/accounts/delete/$1';
$route['accounts/create'] 			= 'accounts/accounts/create';
$route['accounts/reset/:num'] 		= 'accounts/accounts/reset/$1';

$route['accounts/:num']				= 'accounts/accounts/index/$1';

// Newletter
$route['newsletters']				= 'newsletters/newsletters';

$route['newsletters/search_hotel'] 		= 'newsletters/newsletters/search_hotel/$1';
$route['newsletters/search_tour'] 		= 'newsletters/newsletters/search_tour/$1';
$route['newsletters/search_category'] 	= 'newsletters/newsletters/search_category/$1';

$route['newsletters/hotel']				= 'newsletters/newsletters/hotel_html/';

$route['newsletters/create'] 				= 'newsletters/newsletters/create/';
$route['newsletters/create/(:num)'] 		= 'newsletters/newsletters/create/$1';


$route['newsletters/show_newsletter/:num'] 		= 'newsletters/newsletters/show_newsletter/$1';
$route['newsletters/delete/:num'] 				= 'newsletters/newsletters/delete/$1';

$route['newsletters/edit/(:num)'] 				= 'newsletters/newsletters/edit/$1';
$route['newsletters/edit/(:num)/(:num)'] 			= 'newsletters/newsletters/edit/$1/$2';

$route['newsletters/photos/:num'] 				= 'newsletters/newsletters/photos/$1';
$route['newsletters/delete-photo/(:num)/(:num)']	= 'newsletters/newsletters/delete_photo/$1/$2';
$route['newsletters/review'] 		= 'newsletters/newsletters/review/';

$route['newsletters/send-email']	= 'newsletters/newsletters/send_email/';
$route['newsletters/resend-email']	= 'newsletters/newsletters/resend_email/';
$route['newsletters/newsletter-status']	= 'newsletters/newsletters/newsletter_status/';


// Roles
$route['roles'] 					= 'roles/roles';
$route['roles/:num']				= 'roles/roles/index/$1';
$route['roles/create'] 				= 'roles/roles/create';
$route['roles/edit/:num'] 			= 'roles/roles/edit/$1';
$route['roles/delete/:num'] 		= 'roles/roles/delete/$1';

// Flights
$route['flights'] 					= 'flights/flights';
$route['flights/:num']				= 'flights/flights/index/$1';
$route['flights/create'] 			= 'flights/flights/create';
$route['flights/edit/:num'] 		= 'flights/flights/edit/$1';
$route['flights/delete/:num'] 		= 'flights/flights/delete/$1';
$route['flights/activate/:num'] 	= 'flights/flights/activate/$1';
$route['flights/deactivate/:num'] 	= 'flights/flights/deactivate/$1';
$route['flights/re-order'] 			= 'flights/flights/re_order';


// Airlines
$route['airlines'] 					= 'flights/airlines';
$route['airlines/:num']				= 'flights/airlines/index/$1';
$route['airlines/create'] 			= 'flights/airlines/create';
$route['airlines/edit/:num'] 		= 'flights/airlines/edit/$1';
$route['airlines/delete/(:num)'] 		= 'flights/airlines/delete/$1';
$route['airlines/re-order'] 		= 'flights/airlines/re_order';
$route['airlines/photo/(:num)']		= 'flights/airlines/photo/$1';
$route['airlines/delete-photo/(:num)/(:num)']		= 'flights/airlines/delete_photo/$1/$2';


// Categories
$route['flight-categories'] 					= 'flights/categories';
$route['flight-categories/:num']				= 'flights/categories/index/$1';
$route['flight-categories/create'] 				= 'flights/categories/create';
$route['flight-categories/edit/(:num)'] 			= 'flights/categories/edit/$1';
$route['flight-categories/delete/(:num)'] 		= 'flights/categories/delete/$1';
$route['flight-categories/re-order'] 			= 'flights/categories/re_order';
$route['flight-categories/photo/(:num)']					= 'flights/categories/photo/$1';
$route['flight-categories/delete-photo/(:num)/(:num)']		= 'flights/categories/delete_photo/$1/$2';


// Hotels
$route['hotels']					= 'hotels/hotels';
$route['hotels/:num']				= 'hotels/hotels/index/$1';
$route['hotels/create'] 			= 'hotels/hotels/create';
//$route['hotels/edit/:any'] 		= 'hotels/hotels/edit/$1';
$route['hotels/delete/:num'] 		= 'hotels/hotels/delete/$1';
$route['hotels/activate/:num'] 		= 'hotels/hotels/activate/$1';
$route['hotels/deactivate/:num'] 	= 'hotels/hotels/deactivate/$1';
$route['hotels/re-order'] 			= 'hotels/hotels/re_order';
$route['hotels/clear-cache/:num']	= 'hotels/hotels/clear_cache';

// Hotel Profile
$route['hotels/profiles/:num']			= 'hotels/profiles';
$route['hotels/hotel_settings/:num']	= 'hotels/profiles/hotel_settings';
$route['hotels/map/:num']				= 'hotels/profiles/map';

// Hotel Facilities
$route['hotels/facilities/:num']		= 'hotels/hotel_facilities';
$route['hotels/create_facility/:num'] 	= 'hotels/hotel_facilities/create/$1';
$route['hotels/edit_facility/:num'] 	= 'hotels/hotel_facilities/edit/$1';
$route['hotels/delete_facility/:num'] 	= 'hotels/hotel_facilities/delete/$1';
$route['hotels/facilities/update'] 		= 'hotels/hotel_facilities/update';

// Hotel Photo
$route['hotels/photos/:num']			= 'hotels/hotel_photos';
$route['hotels/photo_upload/:num']		= 'hotels/photo_upload';
$route['hotels/photo_uploaded/:num']	= 'hotels/photo_upload/uploaded';
$route['crop_image'] 					= 'hotels/hotel_photos/crop_image';

// Hotel Partner
$route['hotels/partner/edit/:num']		= 'partners/partners/edit/$1';
$route['hotels/partner/payment/:num'] 	= 'partners/partners/payment/$1';
$route['hotels/partner/contacts/:num'] 	= 'partners/partners/contacts/$1';

// Rooms
$route['hotels/rooms/:num']				= 'hotels/rooms';
$route['hotels/create_room/:num'] 		= 'hotels/rooms/create/$1';
$route['hotels/edit_room/:num'] 		= 'hotels/rooms/edit/$1';
$route['hotels/activate_room/:num'] 	= 'hotels/rooms/activate/$1';
$route['hotels/deactivate_room/:num'] 	= 'hotels/rooms/deactivate/$1';
$route['hotels/delete_room/:num'] 		= 'hotels/rooms/delete/$1';
$route['hotels/settings_room/:num'] 	= 'hotels/rooms/settings/$1';
$route['hotels/re-order-room'] 			= 'hotels/rooms/re_order';

// Rooms
$route['hotels/room_settings/:num']		= 'hotels/room_settings';

// Destination
$route['destinations']					= 'destinations/destinations';
$route['destinations/:num']				= 'destinations/destinations/index/$1';
$route['destinations/create'] 			= 'destinations/destinations/create';
$route['destinations/edit/:num'] 		= 'destinations/destinations/edit/$1';
$route['destinations/map/:num'] 		= 'destinations/destinations/map/$1';
$route['destinations/flight/:num'] 		= 'destinations/destinations/flight/$1';
$route['destinations/tour/:num'] 		= 'destinations/destinations/tour/tour/$1';
$route['destinations/delete/:num'] 		= 'destinations/destinations/delete/$1';
$route['destinations/re-order'] 		= 'destinations/destinations/re_order';
$route['destinations/clear-cache/:num']	= 'destinations/destinations/clear_cache';

// Activity
$route['destinations/activities'] 		= 'destinations/activities';
$route['destinations/activities/:num'] 	= 'destinations/activities/index/$1';
$route['destinations/create_activity/:num']		= 'destinations/activities/create/$1';
$route['destinations/activities/photos/:num']	= 'destinations/activities/photos/$1';
$route['destinations/edit_activity/:num']		= 'destinations/activities/edit/$1';
$route['destinations/delete_activity/:num']		= 'destinations/activities/delete/$1';


// Destination Photo
$route['destinations/photos/:num']			= 'destinations/destinations_photos';
$route['destinations/photo_upload/:num']	= 'destinations/photo_upload';
$route['destinations/photo_uploaded/:num']	= 'destinations/photo_upload/uploaded';
$route['destinations_crop_image'] 			= 'destinations/destinations_photos/crop_image';

// Customers
$route['customers']						= 'customers/customers';
$route['customers/:num']				= 'customers/customers/index/$1';
$route['customers/create'] 				= 'customers/customers/create';
$route['customers/edit/:num'] 			= 'customers/customers/edit/$1';
$route['customers/delete/:num'] 		= 'customers/customers/delete/$1';

// Facilities
$route['facilities']					= 'facilities/facilities';
$route['facilities/:num']				= 'facilities/facilities/index/$1';
$route['facilities/create'] 			= 'facilities/facilities/create';
$route['facilities/edit/:num'] 			= 'facilities/facilities/edit/$1';
$route['facilities/activate/:num'] 		= 'facilities/facilities/activate/$1';
$route['facilities/deactivate/:num'] 	= 'facilities/facilities/deactivate/$1';
$route['facilities/delete/:num'] 		= 'facilities/facilities/delete/$1';
$route['facilities/re-order'] 			= 'facilities/facilities/re_order';

// News
$route['news']							= 'news/news';
$route['news/:num']						= 'news/news/index/$1';
$route['news/create'] 					= 'news/news/create';
$route['news/edit/:num'] 				= 'news/news/edit/$1';
$route['news/delete/:num'] 				= 'news/news/delete/$1';
$route['news/re-order'] 				= 'news/news/re_order';
$route['news/photos/:num'] 				= 'news/news/photos/index/$1';
$route['news/delete-photo/(:num)/(:num)'] = 'news/news/delete_photo/$1/$2';

// Partners
$route['partners']					= 'partners/partners';
$route['partners/:num']				= 'partners/partners/index/$1';
$route['partners/create'] 			= 'partners/partners/create';
$route['partners/edit/:num'] 		= 'partners/partners/edit/$1';
$route['partners/payment/:num'] 	= 'partners/partners/payment/$1';
$route['partners/contacts/:num'] 	= 'partners/partners/contacts/$1';
$route['partners/delete/:num'] 		= 'partners/partners/delete/$1';

// Cancellations
$route['cancellations']	= 'cancellations/cancellations';
$route['cancellations/:num']	= 'cancellations/cancellations/index/$1';
$route['cancellations/create']	= 'cancellations/cancellations/create';
$route['cancellations/edit/:num']	= 'cancellations/cancellations/edit/$1';
$route['cancellations/delete/:num'] = 'cancellations/cancellations/delete/$1';


// Surcharges
$route['hotels/surcharges/:num']	= 'hotels/surcharges';
$route['hotels/surcharges/:num/:num']	= 'hotels/surcharges/index/$1';
$route['hotels/surcharges/:num/create']	= 'hotels/surcharges/create';
$route['hotels/surcharges/:num/edit/:num']	= 'hotels/surcharges/edit/$1';
$route['hotels/surcharges/:num/delete/:num']	= 'hotels/surcharges/delete/$1';

// Advertises
$route['advertises']	= 'advertises/advertises';
$route['advertises/:num']	= 'advertises/advertises/index/$1';
$route['advertises/create']	= 'advertises/advertises/create';
$route['advertises/edit/:num']	= 'advertises/advertises/edit/$1';
$route['advertises/delete/:num'] = 'advertises/advertises/delete/$1';
$route['advertises/display/:num'] = 'advertises/advertises/display/$1';
$route['advertises/photo/:num'] = 'advertises/advertises/photo/$1';
$route['advertises/delete-photo/(:num)/(:num)'] = 'advertises/advertises/delete_photo/$1/$2';
$route['advertises/re-order'] 	= 'advertises/advertises/re_order';

// Promotions
$route['hotels/promotions/:num']	= 'hotels/promotions';
$route['hotels/promotions/:num/:num']	= 'hotels/promotions/index/';
$route['hotels/promotions/:num/create'] = 'hotels/promotions/create/';
$route['hotels/promotions/:num/create/(:num)']	= 'hotels/promotions/create/$1';

$route['hotels/promotions/:num/edit/(:num)']	= 'hotels/promotions/edit/$1';
$route['hotels/promotions/:num/edit/(:num)/(:num)']	= 'hotels/promotions/edit/$1/$2';

$route['hotels/promotions/:num/delete/(:num)']	= 'hotels/promotions/delete/$1';
$route['hotels/promotions/:num/view/(:num)']	= 'hotels/promotions/view/$1';
$route['hotels/promotions/run_data'] = 'hotels/promotions/run_update_pro';

// Hotel Rates
$route['hotels/rates/:num']	= 'hotels/rates';
$route['hotels/change-room-rates/:num']	= 'hotels/rates/change_room_rates/';
$route['show-surcharge-info'] =  'hotels/rates/show_surcharge_info/';

$route['hotels/room-rate-action/:num']	= 'hotels/rates/room_rate_action/';
$route['hotels/room-rate-action/:num/create']	= 'hotels/rates/create_room_rate_action/';
$route['hotels/room-rate-action/:num/edit/:num']	= 'hotels/rates/edit_room_rate_action/';
$route['hotels/room-rate-action/:num/delete/:num']	= 'hotels/rates/delete_room_rate_action/';

// Bookings
$route['bookings']						= 'bookings/bookings';
$route['bookings/:num']					= 'bookings/bookings';
$route['bookings/delete/(:num)']		= 'bookings/bookings/delete/$1';
$route['bookings/sr/(:num)']			= 'bookings/bookings/sr/$1';
$route['bookings/passenger/(:num)']		= 'bookings/passengers/index/$1';
$route['bookings/overview/(:num)']		= 'bookings/bookings/overview/$1';
$route['bookings/tform/(:num)']			= 'bookings/bookings/tform/$1';

$route['bookings/edit/(:num)']			= 'bookings/bookings/edit/$1';
$route['bookings/create']				= 'bookings/bookings/create';

$route['bookings/export_customer']		= 'bookings/bookings/export_customer_data';

// Service Reservations
$route['bookings/edit-sr/(:num)']		= 'bookings/services/edit/$1';
$route['bookings/create-sr/(:num)']		= 'bookings/services/create/$1';
$route['bookings/delete-sr/(:num)']		= 'bookings/services/delete/$1';

$route['bookings/edit-p/(:num)']		= 'bookings/passengers/edit/$1';
$route['bookings/create-p/(:num)']		= 'bookings/passengers/create/$1';
$route['bookings/delete-p/(:num)']		= 'bookings/passengers/delete/$1';

$route['bookings/search-partners/(:any)'] 	= 'bookings/services/search_partners/$1';
$route['bookings/search-destinations/(:any)'] 	= 'bookings/services/search_destinations/$1';
$route['bookings/search-hotels/(:any)'] 	= 'bookings/services/search_hotels/$1';
$route['bookings/search-customers/(:any)'] 	= 'bookings/bookings/search_customers/$1';
$route['bookings/excel'] 	= 'bookings/bookings/export_excel';


// Marketing
$route['marketings']	= 'marketings/marketings';
$route['marketings/(:num)']	= 'marketings/marketings/index/$1';
$route['marketings/create-pro']	= 'marketings/marketings/create_pro';
$route['marketings/edit-pro/(:num)']	= 'marketings/marketings/edit_pro/$1';
$route['marketings/delete-pro/(:num)'] = 'marketings/marketings/delete_pro/$1';

$route['marketings/vouchers'] = 'marketings/marketings/vouchers';
$route['marketings/vouchers/(:num)'] = 'marketings/marketings/vouchers/$1';
$route['marketings/create-voucher']	= 'marketings/marketings/create_voucher';
$route['marketings/edit-voucher/(:num)']	= 'marketings/marketings/edit_voucher/$1';
$route['marketings/delete-voucher/(:num)'] = 'marketings/marketings/delete_voucher/$1';

$route['marketings/hotel-pro/(:num)'] = 'marketings/marketings/hotel_pro/$1';
$route['marketings/hotel-pro/(:num)/(:num)'] = 'marketings/marketings/hotel_pro/$1/$2';

$route['marketings/cruise-pro/(:num)'] = 'marketings/marketings/cruise_pro/$1';
$route['marketings/cruise-pro/(:num)/(:num)'] = 'marketings/marketings/cruise_pro/$1/$2';

$route['marketings/tour-pro/(:num)'] = 'marketings/marketings/tour_pro/$1';
$route['marketings/tour-pro/(:num)/(:num)'] = 'marketings/marketings/tour_pro/$1/$2';
$route['marketings/show-log-voucher'] = 'marketings/marketings/show_log_voucher';

// Cruises
$route['cruises']					= 'cruises/cruises';
$route['cruises/:num']				= 'cruises/cruises/index/$1';
$route['cruises/create'] 			= 'cruises/cruises/create';
$route['cruises/delete/:num'] 		= 'cruises/cruises/delete/$1';
$route['cruises/activate/:num'] 	= 'cruises/cruises/activate/$1';
$route['cruises/deactivate/:num'] 	= 'cruises/cruises/deactivate/$1';
$route['cruises/re-order'] 			= 'cruises/cruises/re_order';
$route['cruises/clear-cache/:num']	= 'cruises/cruises/clear_cache';

// Cruise Profile
$route['cruises/profiles/:num']			= 'cruises/profiles';
$route['cruises/cruise_settings/:num']	= 'cruises/profiles/cruise_settings';
$route['cruises/map/:num']				= 'cruises/profiles/map';
$route['cruises/tours/:num']			= 'cruises/profiles/tours';

// Cruise Facilities
$route['cruises/facilities/:num']		= 'cruises/cruise_facilities';
$route['cruises/create_facility/:num'] 	= 'cruises/cruise_facilities/create/$1';
$route['cruises/edit_facility/:num'] 	= 'cruises/cruise_facilities/edit/$1';
$route['cruises/delete_facility/:num'] 	= 'cruises/cruise_facilities/delete/$1';
$route['cruises/facilities/update'] 	= 'cruises/cruise_facilities/update';

// Cruise Photo
$route['cruises/photos/:num']			= 'cruises/cruise_photos';
$route['cruises/photo_upload/:num']		= 'cruises/photo_upload';
$route['cruises/photo_uploaded/:num']	= 'cruises/photo_upload/uploaded';
$route['cruise_crop_image'] 			= 'cruises/cruise_photos/crop_image';

// Cabins
$route['cruises/cabins/:num']			= 'cruises/cabins';
$route['cruises/create_cabin/:num'] 	= 'cruises/cabins/create/$1';
$route['cruises/edit_cabin/:num'] 		= 'cruises/cabins/edit/$1';
$route['cruises/activate_cabin/:num'] 	= 'cruises/cabins/activate/$1';
$route['cruises/deactivate_cabin/:num'] = 'cruises/cabins/deactivate/$1';
$route['cruises/delete_cabin/:num'] 	= 'cruises/cabins/delete/$1';
$route['cruises/settings_cabin/:num'] 	= 'cruises/cabins/settings/$1';
$route['cruises/re-order-cabin'] 		= 'cruises/cabins/re_order';

// Cabin Settings
$route['cruises/cabin_settings/:num']	= 'cruises/cabin_settings';

// Cruise Surcharge
$route['cruises/surcharges/:num']				= 'cruises/surcharges';
$route['cruises/surcharges/:num/:num']			= 'cruises/surcharges/index/$1';
$route['cruises/surcharges/:num/create']		= 'cruises/surcharges/create';
$route['cruises/surcharges/:num/edit/:num']		= 'cruises/surcharges/edit/$1';
$route['cruises/surcharges/:num/delete/:num']	= 'cruises/surcharges/delete/$1';

// Tours
$route['tours']							= 'tours/tours';
$route['tours/:num']				    = 'tours/tours/index/$1';
$route['tours/create'] 					= 'tours/tours/create';
$route['tours/delete/:num'] 			= 'tours/tours/delete/$1';
$route['tours/re-order'] 				= 'tours/tours/re_order';
$route['tours/clear-cache/:num']		= 'tours/tours/clear_cache';

$route['tours/auto-suggestion']			= 'tours/tours/auto_suggestion';
$route['tours/search-destinations/(:any)']		= 'tours/tours/search_destinations/$1';

// Edit Tour
$route['tours/profiles/:num']			= 'tours/profiles';
$route['tours/tour_settings/:num']	    = 'tours/profiles/tour_settings';
$route['tours/itinerary/:num']			= 'tours/profiles/itinerary';

// Tour Photo
$route['tours/photos/:num']				= 'tours/tour_photos';
$route['tours/photo_upload/:num']		= 'tours/photo_upload';
$route['tours/photo_uploaded/:num']		= 'tours/photo_upload/uploaded';
$route['tour_crop_image'] 				= 'tours/tour_photos/crop_image';

// Accommodations
$route['tours/accommodations/:num']			= 'tours/accommodations';
$route['tours/create_accommodation/:num'] 	= 'tours/accommodations/create/$1';
$route['tours/edit_accommodation/:num'] 	= 'tours/accommodations/edit/$1';
$route['tours/delete_accommodation/:num'] 	= 'tours/accommodations/delete/$1';
$route['tours/re-order-accommodation'] 		= 'tours/accommodations/re_order';

// Itinerary
$route['tours/itinerary/:num']			= 'tours/itinerary';
$route['tours/create_itinerary/:num'] 	= 'tours/itinerary/create/$1';
$route['tours/edit_itinerary/:num'] 	= 'tours/itinerary/edit/$1';
$route['tours/delete_itinerary/:num'] 	= 'tours/itinerary/delete/$1';
$route['tours/re-order-itinerary'] 		= 'tours/itinerary/re_order';

// Tour Rates
$route['tours/rates/:num']				= 'tours/rates';

$route['tours/room-rate-action/:num']				= 'tours/rates/room_rate_action/';
$route['tours/room-rate-action/:num/create']		= 'tours/rates/create_room_rate_action/';
$route['tours/room-rate-action/:num/edit/:num']		= 'tours/rates/edit_room_rate_action/';
$route['tours/room-rate-action/:num/delete/:num']	= 'tours/rates/delete_room_rate_action/';

// Tour Category
$route['tours/category/:num']			= 'tours/tour_category';

// Tour Departure
$route['tours/departure/:num']			    = 'tours/tour_departure';
$route['tours/departure/re-order'] 			= 'tours/tour_departure/re_order';
$route['tours/departure/create/:num']	    = 'tours/tour_departure/create_departure';
$route['tours/departure/edit/:num']	        = 'tours/tour_departure/edit_departure';
$route['tours/departure/delete/:num']	    = 'tours/tour_departure/delete_departure';

// Tour Departure Date
$route['tours/departure/create_date/:num']	= 'tours/tour_departure_date/create_departure_date';
$route['tours/departure/edit_date/:num']	= 'tours/tour_departure_date/edit_departure_date';
$route['tours/departure/delete_date/:num']	= 'tours/tour_departure_date/delete_departure_date';

// Tour Promotions
$route['tours/promotions/:num']	            = 'tours/promotions';
$route['tours/promotions/:num/:num']	    = 'tours/promotions/index/';
$route['tours/promotions/:num/create']      = 'tours/promotions/create/';
$route['tours/promotions/:num/create/(:num)']	    = 'tours/promotions/create/$1';

$route['tours/promotions/:num/edit/(:num)']	        = 'tours/promotions/edit/$1';
$route['tours/promotions/:num/edit/(:num)/(:num)']	= 'tours/promotions/edit/$1/$2';

$route['tours/promotions/:num/delete/(:num)']	    = 'tours/promotions/delete/$1';
$route['tours/promotions/:num/view/(:num)']	        = 'tours/promotions/view/$1';


// Tour Categories
$route['categories']			        = 'tours/categories';
$route['categories/create'] 	        = 'tours/categories/create';
$route['categories/edit/:num'] 	        = 'tours/categories/edit/$1';
$route['categories/delete/:num'] 	    = 'tours/categories/delete/$1';
$route['categories/re-order'] 			= 'tours/categories/re_order';
$route['categories/clear-cache/:num']	= 'tours/categories/clear_cache';

// Cruise Promotions
$route['cruises/promotions/:num']	= 'cruises/promotions';
$route['cruises/promotions/:num/:num']	= 'cruises/promotions/index/';
$route['cruises/promotions/:num/create'] = 'cruises/promotions/create/';
$route['cruises/promotions/:num/create/(:num)']	= 'cruises/promotions/create/$1';

$route['cruises/promotions/:num/edit/(:num)']	= 'cruises/promotions/edit/$1';
$route['cruises/promotions/:num/edit/(:num)/(:num)']	= 'cruises/promotions/edit/$1/$2';

$route['cruises/promotions/:num/delete/(:num)']	= 'cruises/promotions/delete/$1';
$route['cruises/promotions/:num/view/(:num)']	= 'cruises/promotions/view/$1';


// Reviews
//$route['reviews/reviews/:num']			= 'reviews/reviews';
$route['reviews/create_review'] 		= 'reviews/reviews/create';
$route['reviews/edit_review/:num'] 		= 'reviews/reviews/edit/$1';
$route['reviews/delete_review/:num'] 	= 'reviews/reviews/delete/$1';
$route['reviews/suggest-hotels'] 		= 'reviews/reviews/suggest_hotels';
$route['reviews/suggest-tours']  		= 'reviews/reviews/suggest_tours';

// Hotel Reviews
$route['hotels/reviews/:num']			= 'reviews/reviews';
// Tour Reviews
$route['tours/reviews/:num']			= 'reviews/reviews';
// Tour Reviews
$route['cruises/reviews/:num']			= 'reviews/reviews';


$route['contracts/rename']			    = 'contracts/contracts/rename/';
$route['contracts/update']			    = 'contracts/contracts/update/';

// Hotel Contracts
$route['hotels/contracts/:num']			= 'contracts/contracts';
$route['hotels/contract_upload/:num']	= 'contracts/contract_upload/index/$1';
$route['hotels/contract_uploaded/:num']	= 'contracts/contract_upload/uploaded/$1';
$route['hotels/contract_download/:num']	= 'contracts/contracts/force_download/$1';
$route['hotels/contract_delete/:num']	= 'contracts/contracts/delete/$1';
$route['hotels/contract_view/:num']	    = 'contracts/contracts/view/$1';

// Cruise Contracts
$route['cruises/contracts/:num']			= 'contracts/contracts';
$route['cruises/contract_upload/:num']		= 'contracts/contract_upload/index/$1';
$route['cruises/contract_uploaded/:num']	= 'contracts/contract_upload/uploaded/$1';
$route['cruises/contract_download/:num']	= 'contracts/contracts/force_download/$1';
$route['cruises/contract_delete/:num']		= 'contracts/contracts/delete/$1';
$route['cruises/contract_view/:num']	    = 'contracts/contracts/view/$1';

// Tours Contracts
$route['tours/contracts/:num']			    = 'contracts/contracts';
$route['tours/contract_upload/:num']		= 'contracts/contract_upload/index/$1';
$route['tours/contract_uploaded/:num']	    = 'contracts/contract_upload/uploaded/$1';
$route['tours/contract_download/:num']	    = 'contracts/contracts/force_download/$1';
$route['tours/contract_delete/:num']		= 'contracts/contracts/delete/$1';
$route['tours/contract_view/:num']	        = 'contracts/contracts/view/$1';


// System
$route['system']						= 'system/system';
$route['logs']							= 'system/logs';
$route['dashboard']						= 'system/dashboard';

/* End of file routes.php */
/* Location: ./application/config/routes.php */