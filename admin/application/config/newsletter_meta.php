<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array();

$config['newsletter_create_1'] = array(
	'name' => array(
        'field' => 'name',
        'label' => 'lang:newsletters',
        'len' => 255,
        'rules' => 'required|trim|xss_clean|max_length[200]|callback_news_name_check'
    ),
    'display_name'	=> array(
        'field' => 'display_name',
        'label' => 'lang:display_name',
        'len' => 255,
        'rules' => 'required',
    ),
    'template_type' => array(
        'field' => 'template_type',
        'label' => 'lang:template_type',
        'rules' => 'required',
    ),
    'customer_gender' => array(
        'field' => 'customer_gender',
        'label' => 'lang:field_customer_gender',
    	'rules' => 'required',
    ),
    'customer_type' => array(
        'field' => 'customer_type',
        'label' => 'lang:field_customer_type',
    	'rules' => 'required',
    ),
);

$config['newsletter_create_2'] = array(
	'promotions' => array (
	    'field'		=> 'promotions',
	    'label' 	=> 'lang:promotions',
	    'rules'		=> 'xss_clean',
	),
);

$config['newsletter_create_3'] = array(
	'content' => array(
        'field' => 'content',
        'label' => 'lang:newsletter_field_content',
        'rules' => 'xss_clean'
    ),
);


// reservation_type config
$config['customer_type'] = array(
	
	2 	=> 'hotel_reservation',
	
	8 	=> 'flight_reservation',
	
	1 	=> 'cruise_reservation',
	
	4 	=> 'tour_reservation',
	
	9	=> 'account',
);


$config['customer_gender'] = array(
	
	1	=> "customer_male",
		
	2	=> "customer_female",
);

$config['newsletters_photo'] = array(
	
	'upload_path' => '../images/newsletters/',
	
    'allowed_types' => 'gif|jpg|jpeg|png',
	
    'max_width' => '2048',
	
    'max_height' => '1536',
	
    'max_size' => '2048'
);

$config['newsletter_status'] = array(
	
	0	=> 'new',
	
	1	=> 'sending',
	
	2	=> 'sent',
	
	3	=> 'stop',
);


$config['template_type']	= array(
	
	0	=> 'hotel',
	
	1	=> 'cruise',
	
	2	=> 'tour',
	
	3	=> 'general',
	
	4	=> 'flight',
	
	5	=> 'news',
	
	7	=> 'blank',
);

$config['gmail_account']	= array(
	
	0	=> array(
    			'gmail'	=> 'tour@Bestviettravel.xyz',
    			'pass'	=>	'a@123456$',
    		),
    1	=> array(
    			'gmail'	=> 'trananhphuc.1610@gmail.com',
    			'pass'	=>	'antigod2004',
    		),
    2	=> array(
    			'gmail'	=> 'cruise@Bestviettravel.xyz',
    			'pass'	=>	'a@123456$',
    		),
    3	=> array(
    			'gmail'	=> 'newsletter@Bestviettravel.xyz',
    			'pass'	=>	'a@123456$',
    		),
    4	=> array(
	    		'gmail'	=> 'bestpricebooking@gmail.com',
				'pass'	=> 'Bpt052010',
    		),
);
