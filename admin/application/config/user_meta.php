<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();

$config['create_account'] = array(
		'username' 	=> array (
				'field'		=> 'username',
				'label' 	=> 'lang:users_field_username',
				'rules'		=> 'required|trim|xss_clean|alpha_numeric|min_length[3]|max_length[30]|is_unique[users.username]',
		),
		'password' 	=> array (
				'field'		=> 'password',
				'label' 	=> 'lang:users_field_password',
				'rules'		=> 'required|matches[passconf]|min_length[6]|max_length[30]',
		),
		'passconf' 	=> array (
				'field'		=> 'passconf',
				'label' 	=> 'lang:users_field_passconf',
				'rules'		=> 'required',
		),
);

$config['create_user'] = array(
  		'full_name' => array (
  			'field'		=> 'full_name',
  			'label' 	=> 'lang:users_field_full_name',
  			'rules'		=> 'required|trim|xss_clean|max_length[200]',
  		),
  		'email' 	=> array (
  			'field'		=> 'email',
  			'label' 	=> 'lang:users_field_email',
  			'rules'		=> 'required|trim|valid_email|max_length[200]',
  		),	
		'partner_id' => array (
		    'field'		=> 'partner_id',
		    'label' 	=> 'lang:users_field_partner',
		    'rules'		=> 'required',
		),
		'signature' => array (
		    'field'		=> 'signature',
		    'label' 	=> 'lang:users_field_signature',
		    'rules'		=> 'max_length[500]',
		),
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> '',
		),
);

$config['change_password'] = array(
		'old_password' 	=> array (
				'field'		=> 'old_password',
				'label' 	=> 'lang:change_password_old_password_label',
				'rules'		=> 'required|trim',
		),
		'new_password' 	=> array (
				'field'		=> 'new_password',
				'label' 	=> 'lang:change_password_new_password_label',
				'rules'		=> 'required|trim|min_length[6]|max_length[12]|matches[new_password_confirm]',
		),
		'new_password_confirm' 	=> array (
				'field'		=> 'new_password_confirm',
				'label' 	=> 'lang:change_password_new_password_confirm_label',
				'rules'		=> 'required',
		),
);

$config['user_nav_panel'] = array(
		array(
				'link' 	=> '/users/edit/',
				'title' => 'edit_user_mnu',
		),
	
		array(
				'link' 	=> '/users/hotline/',
				'title' => 'hotline_user_mnu',
		),

);

$config['hotline_schedule_nav'] = array(
		array(
				'link' 	=> '/users/',
				'title' => 'user_mngt_mnu',
		),

		array(
				'link' 	=> '/users/schedules/',
				'title' => 'hotline_schedule_mnu',
		),

);

$config['accounts_type_nav'] = array(
		array(
				'link'	=> '/accounts/',
				'title'	=> 'account_mngt_mnu',
		),
);

$config['account_create'] = array(
  		'email' 	=> array (
  			'field'		=> 'email',
  			'label' 	=> 'lang:account_field_email',
  			'rules'		=> 'required|trim|valid_email|max_length[200]',
  		),
  		'username' 	=> array (
  			'field'		=> 'username',
  			'label' 	=> 'lang:account_field_username',
  			'rules'		=> '',
  		),
		'phone' => array (
		    'field'		=> 'phone',
		    'label' 	=> 'lang:account_field_phone',
		    'rules'		=> 'numeric',
		),
		'active' => array (
		    'field'		=> 'active',
		    'label' 	=> 'lang:account_field_active',
		    'rules'		=> '',
		),
);


$config['hotline_setting'] = array(
		'hotline_name' => array (
				'field'		=> 'hotline_name',
				'label' 	=> 'lang:hotline_name',
				'rules'		=> 'required|trim|xss_clean|max_length[200]',
		),
		'hotline_number' => array (
				'field'		=> 'hotline_number',
				'label' 	=> 'lang:hotline_number',
				'rules'		=> 'required|trim|xss_clean|max_length[200]',
		),
		'display_on' => array(
				'field' => 'display_on',
				'label' => 'lang:display_on',
				'rules' => '',		
		),
		
		'yahoo_acc' => array (
				'field'		=> 'yahoo_acc',
				'label' 	=> 'lang:yahoo_acc',
				'rules'		=> 'trim|xss_clean|max_length[200]',
		),
		
		'skype_acc' => array (
				'field'		=> 'skype_acc',
				'label' 	=> 'lang:skype_acc',
				'rules'		=> 'trim|xss_clean|max_length[200]',
		),
);

$config['hotline_schedule'] = array(
		'user_id' => array (
				'field'		=> 'user_id',
				'label' 	=> 'lang:h_user',
				'rules'		=> 'required',
		),
		
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> 'required',
		),
		
		'start_date' => array (
				'field'		=> 'start_date',
				'label' 	=> 'lang:field_start_date',
				'rules'		=> 'required',
		),
		
		'end_date' => array (
				'field'		=> 'end_date',
				'label' 	=> 'lang:field_end_date',
				'rules'		=> 'required',
		),
		
		'week_day' => array (
				'field'		=> 'week_day[]',
				'label' 	=> 'lang:field_week_day',
				'rules'		=> 'required',
		),
		
);

$config['user_photo'] = array(
	'upload_path' 	=> '../images/users/',
	'allowed_types' => 'gif|jpg|jpeg|png',
	'max_width' 	=> '800',
	'max_height' 	=> '800',
	'max_size' 		=> '1024',
);

$config['user_photo_size'] = array(
    'small' => array (
        'path'	=> '../images/users/small/',
        'size' 	=> array(
            array('width' => 90, 'height' => 90)
        ),
    ),
);

$config['display_on']	= array(
	HOTEL 		=> lang('hotel'),
	CRUISE 		=> lang('cruise'),
	TOUR 		=> lang('tour'),
	FLIGHT		=> lang('flight'),
);

$config['allow_assign_request_config'] = array(
	YES 	=> 'yes',
	NO 		=> 'no',
);
