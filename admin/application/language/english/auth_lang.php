<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Errors
$lang['error_csrf'] = 'This form post did not pass our security checks.';

// Login
$lang['login_remember_label']  = 'Stay signed in ';
$lang['login_submit_btn']      = 'Login';
$lang['login_forgot_password'] = 'Need help?';
$lang['login_subheading']      = 'Please login with your username and password below.';

// Create User
$lang['create_user_heading']                           = 'Create User';
$lang['create_user_subheading']                        = 'Please enter the users information below.';
$lang['create_user_username_label']                    = 'Username';
$lang['create_user_full_name_label']                   = 'Full Name';
$lang['create_user_email_label']                       = 'Email';
$lang['create_user_phone_label']                       = 'Phone';
$lang['create_user_password_label']                    = 'Password';
$lang['create_user_password_confirm_label']            = 'Confirm Password';
$lang['create_user_submit_btn']                        = 'Create User';


// Change Password
$lang['change_password_heading']                               = 'Change Password';
$lang['change_password_old_password_label']                    = 'Old Password';
$lang['change_password_new_password_label']                    = 'New Password';
$lang['change_password_new_password_confirm_label']            = 'Confirm New Password';
$lang['change_password_submit_btn']                            = 'Change';
$lang['change_password_validation_old_password_label']         = 'Old Password';
$lang['change_password_validation_new_password_label']         = 'New Password';
$lang['change_password_validation_new_password_confirm_label'] = 'Confirm New Password';
$lang['change_password_min_length'] = ' (at least %s characters long)';

// Forgot Password
$lang['forgot_password_heading']                 = 'Forgot Password';
$lang['forgot_password_subheading']              = 'Please enter your %s so we can send you an email to reset your password.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'Submit';
$lang['forgot_password_validation_email_label']  = 'Email Address';
$lang['forgot_password_username_identity_label'] = 'Username';
$lang['forgot_password_email_identity_label']    = 'Email';
$lang['forgot_password_email_not_found']         = 'No record of that email address.';

// Reset Password
$lang['reset_password_heading']                               = 'Change Password';
$lang['reset_password_new_password_label']                    = 'New Password (at least %s characters long)';
$lang['reset_password_new_password_confirm_label']            = 'Confirm New Password';
$lang['reset_password_submit_btn']                            = 'Change';
$lang['reset_password_validation_new_password_label']         = 'New Password';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirm New Password';

// Login / Logout
$lang['login_successful'] 		  	         = 'Logged In Successfully';
$lang['login_unsuccessful'] 		  	     = 'The username or password entered is incorrect.';
$lang['login_unsuccessful_not_active'] 		 = 'Account is inactive';
$lang['login_timeout']                       = 'Temporarily Locked Out.  Try again later.';
$lang['logout_successful'] 		 	         = 'Logged Out Successfully';

// Account Creation
$lang['account_creation_successful'] 	  	 = 'Account Successfully Created';
$lang['account_creation_unsuccessful'] 	 	 = 'Unable to Create Account';
$lang['account_creation_duplicate_email'] 	 = 'Email Already Used or Invalid';
$lang['account_creation_duplicate_username'] = 'Username Already Used or Invalid';

// Password
$lang['password_change_successful'] 	 	 = 'Password Successfully Changed';
$lang['password_change_unsuccessful'] 	  	 = 'Unable to Change Password';
$lang['forgot_password_successful'] 	 	 = 'Password Reset Email Sent';
$lang['forgot_password_unsuccessful'] 	 	 = 'Unable to Reset Password';