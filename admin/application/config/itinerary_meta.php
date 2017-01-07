<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['create_itinerary'] = array(
		'label' => array (
				'field'		=> 'label',
				'label' 	=> 'lang:tour_itineraries_field_label',
				'len'		=> 50,
				'rules'		=> 'trim|xss_clean|max_length[50]',
		),
		'title' => array (
				'field'		=> 'title',
				'label' 	=> 'lang:tour_itineraries_field_title',
				'len'		=> 500,
				'rules'		=> 'required|trim|xss_clean|max_length[500]',
		),
		'meals' => array (
				'field'		=> 'meals[]',
				'label' 	=> 'lang:tour_itineraries_field_meals',
				'rules'		=> 'required',
		),
        'transportations' => array (
            'field'		=> 'transportations[]',
            'label' 	=> 'lang:tour_itineraries_field_transportations',
            'rules'		=> 'required',
        ),
		'content' => array (
				'field'		=> 'content',
				'label' 	=> 'lang:tour_itineraries_field_content',
				'rules'		=> 'required',
		),
		'accommodation' => array (
				'field'		=> 'accommodation',
				'label' 	=> 'lang:tour_itineraries_field_accommodation',
				'rules'		=> '',
		),
		'activities' => array (
				'field'		=> 'activities',
				'label' 	=> 'lang:tour_itineraries_field_activities',
				'rules'		=> '',
		),
		'notes' => array (
				'field'		=> 'notes',
				'label' 	=> 'lang:tour_itineraries_field_notes',
				'rules'		=> '',
		),
		'photos' => array (
				'field'		=> 'photos',
				'label' 	=> 'lang:tour_itineraries_field_photos',
				'rules'		=> '',
		),
);

$config['tour_meals']  = array(
		1=>'Bữa sáng',
		2=>'Bữa trưa',
		//6=>'Brunch',
		//3=>'Picnic Lunch',
		4=>'Bữa tối',
		5=>'Không có',
);

$config['tour_transportations']  = array(
    1   =>  'Máy bay',
    2   =>  'Ô tô',
    3   =>  'Tàu hoả',
    4   =>  'Du thuyền',
    5   =>  'Xe gắn máy',
    6   =>  'Xe đạp',
    7   =>  'Đi bộ',
);

