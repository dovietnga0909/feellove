<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['sort_by'] = array(
    'popular' => array(
        'label' => lang('sort_popular'),
        'value' => 'popular',
        'selected' => true
    ),

    'price' => array(
        'label' => lang('sort_price'),
        'value' => 'price',
        'selected' => false
    ),

    'review' => array(
        'label' => lang('sort_review'),
        'value' => 'review',
        'selected' => false
    ),

    'duration' => array(
        'label' => lang('sort_duration'),
        'value' => 'duration',
        'selected' => false
    ),
);

$config['filter_price'] = array(
    1 => array(
        'label' => lang('price_1'),
        'value' => 1,
        'selected' => false,
        'number' => 0
        	
    ),
    2=> array(
        'label' => lang('price_2'),
        'value' => 2,
        'selected' => false,
        'number' => 0
        	
    ),

    3=> array(
        'label' => lang('price_3'),
        'value' => 3,
        'selected' => false,
        'number' => 0

    ),

    4=> array(
        'label' => lang('price_4'),
        'value' => 4,
        'selected' => false,
        'number' => 0

    ),

    5=> array(
        'label' => lang('price_5'),
        'value' => 5,
        'selected' => false,
        'number' => 0

    )
);

$config['tour_meals']  = array(
    1=>'Bữa sáng',
    2=>'Bữa trưa',
    //6=>'Brunch',
    //3=>'Picnic Lunch',
    4=>'Bữa tối',
    5=>'Không có',
);

$config['transportations']  = array(
    1   =>  'icon-airplane',
    2   =>  'icon-car',
    3   =>  'icon-train',
    4   =>  'icon-cruise',
    5   =>  'icon-motorbike',
    6   =>  'icon-bicycle',
    7   =>  'icon-trekking',
);

$config['accommodation_limit'] = 5;

$config['filter_limit'] = 10;

$config['nr_recommended_categories'] = 3;

$config['popular_tour_limit'] = 10;

$config['itinerary_path'] = 'itinerary/';