<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Pagination config
|--------------------------------------------------------------------------
|
*/ 
$config['per_page'] = 30;
$config['num_links'] = 5;

$config['resource_path'] = "http://bestviettravel.xyz/";


/*
| -------------------------------------------------------------------------
| Authentication options.
| -------------------------------------------------------------------------
| maximum_login_attempts: This maximum is not enforced by the library, but is
| used by $this->ion_auth->is_max_login_attempts_exceeded().
| The controller should check this function and act
| appropriately. If this variable set to 0, there is no maximum.
*/
$config['remember_users']             = TRUE;                // Allow users to be remembered and enable auto-login
$config['min_password_length']        = 6;                   // Minimum Required Length of Password
$config['user_expire']                = 86500;               // How long to remember the user (seconds). Set to zero for no expiration
$config['user_extend_on_login']       = FALSE;               // Extend the users cookies every time they auto-login
$config['track_login_attempts']       = FALSE;               // Track the number of failed login attempts for each user or ip.
$config['track_login_ip_address']     = FALSE;               // Track login attempts by IP Address, if FALSE will track based on identity. (Default: TRUE)
$config['maximum_login_attempts']     = 3;                   // The maximum number of failed login attempts.
$config['lockout_time']               = 600;                 // The number of seconds to lockout an account due to exceeded attempts


/*
| -------------------------------------------------------------------------
| Week Days Configurations.
| -------------------------------------------------------------------------
| 
*/

$config['week_days'] = array(
	1 => 'mon',
	2 => 'tue',
	3 => 'wed',
	4 => 'thu',
	5 => 'fri',
	6 => 'sat',
	0 => 'sun'
);

$config['week_days_vn'] = array(
	1 => 'mon_vn',
	2 => 'tue_vn',
	3 => 'wed_vn',
	4 => 'thu_vn',
	5 => 'fri_vn',
	6 => 'sat_vn',
	0 => 'sun_vn'
);


$config['status_config'] = array(
		STATUS_ACTIVE 	=> 'active',
		STATUS_INACTIVE => 'inactive',
);

$config['c_titles'] = array(
	1 => 'c_mr',
	2 => 'c_mrs',
	3 => 'c_mr_y',
	4 => 'c_ms'
);