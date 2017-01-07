<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['create_category'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:tour_category_field_name',
				'len'		=> 500,
				'rules'		=> 'required|trim|xss_clean|max_length[500]|callback_tour_category_name_check',
		),
		'is_hot' => array (
				'field'		=> 'is_hot',
				'label' 	=> 'lang:tour_category_field_is_hot',
				'rules'		=> '',
		),
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> '',
		),
        'link' => array (
            'field'		=> 'link',
            'label' 	=> 'lang:tour_category_field_link',
            'len'		=> 500,
            'rules'		=> 'max_length[500]',
        ),
		'description' => array (
				'field'		=> 'description',
				'label' 	=> 'lang:tour_category_field_description',
				'len'		=> 5000,
				'rules'		=> 'max_length[5000]',
		),
);

$config['category_photo'] = array(
		'upload_path' 	=> '../images/categories/',
		'allowed_types' => 'gif|jpg|jpeg|png',
		'max_width' 	=> '1024',
		'max_height' 	=> '768',
		'max_size' 		=> '1024',
);