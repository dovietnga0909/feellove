<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['create_accommodation'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:tour_accommodations_field_name',
				'len'		=> 200,
				'rules'		=> 'required|trim|xss_clean|max_length[200]|callback_accommodationname_check',
		),
		'content' => array (
				'field'		=> 'content',
				'label' 	=> 'lang:tours_field_description',
				'len'		=> 1000,
				'rules'		=> 'max_length[1000]',
		),
		'cruise_cabin_id' => array (
				'field'		=> 'cruise_cabin_id',
				'label' 	=> 'lang:tours_field_cruise_cabin',
				'rules'		=> '',
		),
);

