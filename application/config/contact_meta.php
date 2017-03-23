<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['contact_rules'] = array(
    'gender' => array (
        'field'		=> 'gender',
        'label' 	=> 'lang:c_title',
        'rules'		=> 'required',
    ),
    'name' => array (
        'field'		=> 'name',
        'label' 	=> 'lang:c_name',
        'rules'		=> 'required',
    ),
    'phone' => array (
        'field'		=> 'phone',
        'label' 	=> 'lang:c_phone',
        'rules'		=> 'required|is_natural',
    ),
    'phone_cf' => array (
        'field'		=> 'phone_cf',
        'label' 	=> 'lang:c_phone_cf',
        'rules'		=> 'required|matches[phone]',
    ),
    'email' => array (
        'field'		=> 'email',
        'label' 	=> 'lang:c_email',
        'rules'		=> 'required|valid_email',
    ),
    'day' => array (
        'field'		=> 'day',
        'label' 	=> 'lang:c_day',
        'rules'		=> '',
    ),
    'month' => array (
        'field'		=> 'month',
        'label' 	=> 'lang:c_month',
        'rules'		=> '',
    ),
    'year' => array (
        'field'		=> 'year',
        'label' 	=> 'lang:c_year',
        'rules'		=> '',
    ),
    'city' => array (
        'field'		=> 'city',
        'label' 	=> 'lang:c_city',
        'rules'		=> '',
    ),
    'address' => array (
        'field'		=> 'address',
        'label' 	=> 'lang:c_address',
        'rules'		=> '',
    ),
    'special_request' => array (
        'field'		=> 'special_request',
        'label' 	=> 'lang:c_special_request',
        'rules'		=> '',
    ),
);
