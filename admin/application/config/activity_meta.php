<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['create_activity'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:activity_field_name',
				'len'		=> 255,
				'rules'		=> 'trim|xss_clean|max_length[255]',
		),
		'description' => array (
				'field'		=> 'description',
				'label' 	=> 'lang:activity_field_description',
				'len'		=> 500,
				'rules'		=> 'required',
		),
		'photos' => array (
				'field'		=> 'photos',
				'label' 	=> 'lang:activity_field_photo',
				'rules'		=> '',
		),
);
