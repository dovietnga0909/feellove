<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();

$config['nav_panel'] = array(
		array(
				'link' 	=> '/partners/edit',
				'title' => 'partner_mnu_edit',
				'icon'	=> 'fa-edit',
		),
		array(
				'link' 	=> '/partners/payment',
				'title' => 'partner_mnu_payment',
				'icon'	=> 'fa-credit-card',
		),
		array(
				'link' 	=> '/partners/contacts',
				'title' => 'partner_mnu_contact',
				'icon'	=> 'fa-user',
		),
);

$config['partner_types'] = array(
		1 => 'partner_fields_cruise',
		4 => 'partner_fields_transfer',
		2 => 'partner_fields_tour',
		3 => 'partner_fields_hotel',
		5 => 'partner_fields_visa',
);

$config['create_partner'] = array(
  		'name' => array (
  			'field'		=> 'name',
  			'label' 	=> 'lang:partners_field_name',
  			'rules'		=> 'required|max_length[200]|callback_partner_name_check',
  		),
		'service_type' => array (
			'field'		=> 'service_type',
			'label' 	=> 'lang:partner_fields_service_types',
			'rules'		=> 'required',
		),
  		'joining_date' 	=> array (
  			'field'		=> 'joining_date',
  			'label' 	=> 'lang:partners_field_joining_date',
  			'rules'		=> 'required',
  		),
		'phone' 		=> array (
		    'field'		=> 'phone',
		    'label' 	=> 'lang:partners_field_phone',
		    'rules'		=> 'required|max_length[20]|trim|callback_partner_phone_check',
		),
  		'fax' 		=> array (
  			'field'		=> 'fax',
  			'label' 	=> 'lang:partners_field_fax',
  			'rules'		=> 'required',
  		),
		'email' 		=> array (
		    'field'		=> 'email',
		    'label' 	=> 'lang:partners_field_email',
		    'rules'		=> 'required|trim|valid_email|callback_partner_email_check',
		),
		'address' => array (
		    'field'		=> 'address',
		    'label' 	=> 'lang:partners_field_address',
		    'rules'		=> 'required|trim',
		),
);

$config['partner_payment'] = array(
		'bank_account_name' => array (
				'field'		=> 'bank_account_name',
				'label' 	=> 'lang:partners_field_bank_acc_name',
				'rules'		=> 'required|xss_clean',
		),
		'bank_account_number' => array (
				'field'		=> 'bank_account_number',
				'label' 	=> 'lang:partners_field_bank_acc_number',
				'rules'		=> 'required|xss_clean',
		),
		'bank_branch_name' => array (
				'field'		=> 'bank_branch_name',
				'label' 	=> 'lang:partners_field_bank_branch_name',
				'rules'		=> 'required|xss_clean',
		),
		'payment_type' => array (
				'field'		=> 'payment_type',
				'label' 	=> 'lang:partners_field_payment_type',
				'rules'		=> 'required',
		),
);

$config['partner_contact'] = array(
		'reservation_name' => array (
				'field'		=> 'reservation_name',
				'label' 	=> 'lang:partner_contact_email',
				'rules'		=> '',
		),
		'reservation_phone' => array (
				'field'		=> 'reservation_phone',
				'label' 	=> 'lang:partner_contact_email',
				'rules'		=> '',
		),
		'reservation_email' => array (
				'field'		=> 'reservation_email',
				'label' 	=> 'lang:partner_contact_email',
				'rules'		=> 'valid_email',
		),
		'sale_name' => array (
				'field'		=> 'sale_name',
				'label' 	=> 'lang:partner_contact_email',
				'rules'		=> '',
		),
		'sale_phone' => array (
				'field'		=> 'sale_phone',
				'label' 	=> 'lang:partner_contact_email',
				'rules'		=> '',
		),
		'sale_email' => array (
				'field'		=> 'sale_email',
				'label' 	=> 'lang:partner_contact_email',
				'rules'		=> 'valid_email',
		),
		'yahoo_contact' => array (
				'field'		=> 'yahoo_contact',
				'label' 	=> 'lang:partner_contact_email',
				'rules'		=> '',
		),
		'skype_contact' => array (
				'field'		=> 'skype_contact',
				'label' 	=> 'lang:partner_contact_email',
				'rules'		=> '',
		),
);

$config['payment_types'] = array(
		1 => 'partner_payment_type_monthly',
		2 => 'partner_payment_type_per_booking',
);
