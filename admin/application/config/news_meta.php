<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['nav_panel'] = array(
    array(
        'link' => '/news/edit/',
        'title' => 'news_mnu_edit',
        'icon' => 'fa-edit'
    ),
    array(
        'link' => '/news/photos/',
        'title' => 'news_mnu_upload_photos',
        'icon' => 'fa-photo'
    )
);

$config['news_types'] = array(
    M_GENERAL => 'news_type_general',
    M_HOTEL => 'news_type_hotel',
    M_FLIGHT => 'news_type_flight',
    M_CRUISE => 'news_type_cruise',
    M_TOUR => 'news_type_tour'
);

$config['create_news'] = array(
    'name' => array(
        'field' => 'name',
        'label' => 'lang:news_field_name',
        'len' => 200,
        'rules' => 'required|trim|xss_clean|max_length[200]|callback_news_name_check'
    ),
    'category' => array(
        'field' => 'category',
        'label' => 'lang:news_field_category',
        'rules' => 'required'
    ),
    'type' => array(
        'field' => 'type',
        'label' => 'lang:news_field_type',
        'rules' => 'required'
    ),
    'status' => array(
        'field' => 'status',
        'label' => 'lang:field_status',
        'rules' => ''
    ),
    'link' => array(
        'field' => 'link',
        'label' => 'lang:field_link',
        'rules' => 'trim'
    ),
    'source' => array(
        'field' => 'source',
        'label' => 'lang:field_source',
        'rules' => 'trim'
    ),
    'short_description' => array(
        'field' => 'short_description',
        'label' => 'lang:news_field_short_description',
        'rules' => 'required|max_length[500]'
    ),
    'content' => array(
        'field' => 'content',
        'label' => 'lang:news_field_content',
        'rules' => 'required'
    ),
    'start_date' => array(
        'field' => 'start_date',
        'label' => 'lang:news_field_start_date',
        'rules' => 'required|callback_date_check'
    ),
    'end_date' => array(
        'field' => 'end_date',
        'label' => 'lang:news_field_end_date',
        'rules' => 'required|callback_date_check'
    )
);

$config['news_photo'] = array(
    'upload_path' => '../images/news/',
    'allowed_types' => 'gif|jpg|jpeg|png',
    'max_width' => '2048',
    'max_height' => '1536',
    'max_size' => '2048'
);

$config['news_photo_size'] = array(
    'small' => array (
        'path'	=> '../images/news/small/',
        'size' 	=> array(
            array('width' => 120, 'height' => 80)
        ),
    ),
);

$config['news_categories'] = array(
    CAT_BESTPRICE => 'category_bestprice',
    CAT_OUTSOURCE => 'category_outsource',
    CAT_MARKETING => 'category_marketing',
    CAT_NEWSPAPER => 'category_newspaper'
);
