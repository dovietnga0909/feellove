<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['create_customer'] = array(
		'full_name' => array (
				'field'		=> 'full_name',
				'label' 	=> 'lang:customers_field_full_name',
				'len'		=> 200,
				'rules'		=> 'required|xss_clean|max_length[200]',
		),
		'gender' => array (
				'field'		=> 'gender',
				'label' 	=> 'lang:customers_field_gender',
				'rules'		=> 'required',
		),
		'birthday' => array (
				'field'		=> 'birthday',
				'label' 	=> 'lang:customers_field_birthday',
				'rules'		=> '',
		),
		'destination_id' => array (
				'field'		=> 'destination_id',
				'label' 	=> 'lang:customers_field_destination',
				'rules'		=> '',
		),
		'phone' => array (
				'field'		=> 'phone',
				'label' 	=> 'lang:customers_field_phone',
				'rules'		=> 'required',
		),
		'email' => array (
				'field'		=> 'email',
				'label' 	=> 'lang:customers_field_email',
				'rules'		=> 'required|valid_email',
		),
		'address' => array (
				'field'		=> 'address',
				'label' 	=> 'lang:customers_field_address',
				'len'		=> 500,
				'rules'		=> 'max_length[500]',
		),
);

$config['edit_customer'] = array(
		'full_name' => array (
				'field'		=> 'full_name',
				'label' 	=> 'lang:customers_field_full_name',
				'len'		=> 200,
				'rules'		=> 'required|xss_clean|max_length[200]',
		),
		'gender' => array (
				'field'		=> 'gender',
				'label' 	=> 'lang:customers_field_gender',
				'rules'		=> 'required',
		),
		'birthday' => array (
				'field'		=> 'birthday',
				'label' 	=> 'lang:customers_field_birthday',
				'rules'		=> '',
		),
		'destination_id' => array (
				'field'		=> 'destination_id',
				'label' 	=> 'lang:customers_field_destination',
				'rules'		=> '',
		),
		'phone' => array (
				'field'		=> 'phone',
				'label' 	=> 'lang:customers_field_phone',
				'rules'		=> 'required',
		),
		'email' => array (
				'field'		=> 'email',
				'label' 	=> 'lang:customers_field_email',
				'rules'		=> 'required|valid_email',
		),
		'address' => array (
				'field'		=> 'address',
				'label' 	=> 'lang:customers_field_address',
				'len'		=> 500,
				'rules'		=> 'max_length[500]',
		),
);


$config['gender'] = array(
		1 => 'cus_male',
		2 => 'cus_female',
		3 => 'cus_male_y',
		4 => 'cus_female_y'
);

$config['customer_budget'] = array(
		1 => 'luxury',
		2 => 'mid_range',
		3 => 'budget',
);

$config['travel_types'] = array(
		1 => 'family',
		2 => 'individual',
		3 => 'group',
		4 => 'couple',
);


// nav_panel_customer

$config['nav_panel_customer'] = array(
		array(
				'link' 	=> '/customers/',
				'title' => 'mnu_customers',
				'icon'	=> 'fa-meh-o',
		),
		array(
				'link' 	=> '/accounts/',
				'title' => 'mnu_accounts',
				'icon'	=> 'fa-user',
		),
);


