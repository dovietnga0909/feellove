<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config = array();

$config['create_role'] = array(
  		'name' => array (
  			'field'		=> 'name',
  			'label' 	=> 'lang:roles_field_name',
  			'rules'		=> 'required|trim|max_length[200]|is_unique[roles.name]',
  		),
);


$config['roles'] = array(
		1 => 'admin',
		2 => 'sales',
		3 => 'marketing',
		4 => 'accountant',
		5 => 'developer',
		6 => 'sale manager',
);

$config['privileges'] = array(
		1 => 'full',
		2 => 'edit',
		3 => 'view'
);

$config['data_types'] = array(
		1  => 'HOTEL',
		2  => 'PARTNER',
		3  => 'DESTINATION',
		4  => 'PROMOTION',
		5  => 'FLIGHT',
		6  => 'USER',
		7  => 'ROLE',
		8  => 'SYSTEM',
		9  => 'CANCELLATIONS',
		10 => 'ADVERTISES',
		11 => 'FACILITY',
		12 => 'CUSTOMER',
		13 => 'CUSTOMER BOOKING',
		14 => 'ASSIGN REQUEST',
);
