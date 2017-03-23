<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['nav_panel'] = array(
		array(
				'link' 	=> '/hotels/profiles',
				'title' => 'hotel_mnu_profile',
				'icon'	=> 'fa-info',
		),
		array(
				'link' 	=> '/hotels/hotel_settings',
				'title' => 'hotel_mnu_hotel_settings',
				'icon'	=> 'fa-cog',
		),
		array(
				'link' 	=> '/hotels/map',
				'title' => 'hotel_mnu_map',
				'icon'	=> 'fa-map-marker',
		),
		array(
				'link' 	=> '/hotels/facilities',
				'title' => 'hotel_mnu_facility',
				'icon'	=> 'fa-check-square-o',
		),
		array(
				'link' 	=> '/hotels/photos',
				'title' => 'hotel_mnu_photo',
				'icon'	=> 'fa-photo',
		),
		array(
				'link' 	=> '/hotels/rooms',
				'title' => 'hotel_mnu_rooms',
				'icon'	=> 'fa-home',
		),
		array(
				'link' 	=> '/hotels/room_settings',
				'title' => 'hotel_mnu_room_settings',
				'icon'	=> 'fa-cogs',
		),
);

$config['hotel_star'] = array(5, 4.5, 4, 3.5, 3, 2.5, 2, 1.5, 1);

$config['max_infant_age'] 	= 6;
$config['max_children_age'] = 18;

$config['hotel_rules'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:hotels_field_name',
				'len'		=> 200,
				'rules'		=> 'required|trim|xss_clean|max_length[200]|callback_hotel_name_check',
		),
		'address' => array (
				'field'		=> 'address',
				'label' 	=> 'lang:hotels_field_address',
				'len'		=> 200,
				'rules'		=> 'required|max_length[200]',
		),
		'destination_id' => array (
				'field'		=> 'destination_id',
				'label' 	=> 'lang:hotels_field_hotel_area',
				'rules'		=> 'required',
		),
		'status' => array (
				'field'		=> 'status',
				'label' 	=> 'lang:field_status',
				'rules'		=> '',
		),
		'star' => array (
				'field'		=> 'star',
				'label' 	=> 'lang:hotels_field_star',
				'rules'		=> 'required',
		),
		'description' => array (
				'field'		=> 'description',
				'label' 	=> 'lang:hotels_field_description',
				'len'		=> 5000,
				'rules'		=> 'required|max_length[5000]',
		),
);

$config['create_hotel_rules'] = array('partner_id' => array (
            'field'		=> 'partner_id',
            'label' 	=> 'lang:hotels_field_partner',
            'rules'		=> 'required',
        ),
);

$config['hotel_rules_addition'] = array(
        'keywords' => array (
            'field'		=> 'keywords',
            'label' 	=> 'lang:hotels_field_keywords',
            'rules'		=> 'required',
        ),
        'partner_id' => array (
            'field'		=> 'partner_id',
            'label' 	=> 'lang:hotels_field_partner',
            'rules'		=> 'required',
        ),
);

$config['hotel_settings'] = array(
		'check_in' 	=> array (
				'field'		=> 'check_in',
				'label' 	=> 'lang:hotels_field_check_in',
				'rules'		=> 'required',
		),
		'check_out' 	=> array (
				'field'		=> 'check_out',
				'label' 	=> 'lang:hotels_field_check_out',
				'rules'		=> 'required',
		),
		'default_cancellation' 	=> array (
				'field'		=> 'default_cancellation',
				'label' 	=> 'lang:hotels_field_default_cancellcation',
				'rules'		=> 'required',
		),
		'infant_age_util' 	=> array (
				'field'		=> 'infant_age_util',
				'label' 	=> 'lang:hotels_field_infant_age_util',
				'rules'		=> 'required',
		),
		'children_age_to' 	=> array (
				'field'		=> 'children_age_to',
				'label' 	=> 'lang:hotels_field_children_age_from',
				'rules'		=> 'required|callback_children_age_check',
		),
		'extra_bed_requires_from' => array (
				'field'		=> 'extra_bed_requires_from',
				'label' 	=> 'lang:hotels_field_extra_bed_requires_from',
				'rules'		=> 'required',
		),
		'children_stay_free' => array (
				'field'		=> 'children_stay_free',
				'label' 	=> 'lang:hotel_settings_children_stay_free',
				'rules'		=> '',
		),
		'infants_policy' => array (
				'field'		=> 'infants_policy',
				'label' 	=> 'lang:hotels_field_infants',
				'len'		=> 500,
				'rules'		=> 'required|max_length[500]',
		),
		'children_policy' => array (
				'field'		=> 'children_policy',
				'label' 	=> 'lang:hotels_field_children',
				'len'		=> 500,
				'rules'		=> 'required|max_length[500]',
		),
		'extra_cancellation'=> array (
				'field'		=> 'extra_cancellation',
				'label' 	=> 'lang:hotels_field_extra_cancellation',
				'rules'		=> '',
		),
);

$config['hotel_map'] = array(
		'latitude' 	=> array (
				'field'		=> 'latitude',
				'label' 	=> 'lang:hotels_field_latitude',
				'rules'		=> 'required',
		),
		'longitude' 	=> array (
				'field'		=> 'longitude',
				'label' 	=> 'lang:hotels_field_longitude',
				'rules'		=> 'required',
		),
);

$config['create_facility'] = array(
		'name' => array (
				'field'		=> 'name',
				'label' 	=> 'lang:facilities_field_name',
				'len'		=> 200,
				'rules'		=> 'required|xss_clean|max_length[200]',
		),
		'group_id' => array (
				'field'		=> 'group_id',
				'label' 	=> 'lang:facilities_field_group',
				'rules'		=> '',
		),
);

$config['hotel_photo'] = array(
		'upload_path' 	=> '../images/hotels/uploads/',
		'allowed_types' => 'gif|jpg|jpeg|png',
		'max_width' 	=> '2048',
		'max_height' 	=> '1536',
		'max_size' 		=> '2048',
);

$config['hotel_photo_size'] = array(
		'large' => array (
				'path'	=> '../images/hotels/large/',
				'size' 	=> array(
							//array('width' => 800, 'height' => 600),
							array('width' => 416, 'height' => 312)
						),
		),
		'medium' => array (
				'path'	=> '../images/hotels/medium/',
				'size' 	=> array(
							array('width' => 268, 'height' => 201),
							array('width' => 200, 'height' => 150)
						),
		),
		'small' => array (
				'path'	=> '../images/hotels/small/',
				'size' 	=> array(
							array('width' => 160, 'height' => 120),
							array('width' => 120, 'height' => 90)
						),
		),
);

$config['hotel_photo_type'] = array(
		1 	=> 'Photo Gallery',
		2 	=> 'Hotel Main Photo',
		3 	=> 'Room Type Photo',
);

