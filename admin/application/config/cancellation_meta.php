<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['cancellation'] = array(
	'name' => array (
	    'field'		=> 'name',
	    'label' 	=> 'lang:can_field_name',
	    'rules'		=> 'required|xss_clean|callback_can_name_check',
	),
	'service_type' => array (
	    'field'		=> 'service_type',
	    'label' 	=> 'lang:can_service_type',
	    'rules'		=> 'required',
	),
	'fit' => array(
		'field' => 'fit',
		'label' => 'lang:can_field_fit',
		'rules' => 'required',		
	),
	
	'fit_cut_off' => array(
		'field' => 'fit_cutoff',
		'label' => 'lang:can_field_fit_cutoff',
		'rules' => 'required',		
	),
	
	'git_cut_off' => array(
		'field' => 'git_cutoff',
		'label' => 'lang:can_field_git_cutoff',
		'rules' => 'required',		
	),
	
	'content' => array(
		'field' => 'content',
		'label' => 'lang:can_field_content',
		'rules' => 'required|xss_clean',		
	),
	
);

$config['service_type'] = array(
	HOTEL 		=> lang('hotel'),
	CRUISE 		=> lang('cruise'),
	TOUR 		=> lang('tour'),
);

$config['can_fit_nr'] = array(1,2,3,4,5,6,7,8,9,10);
$config['can_fit_cutoff'] = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30);
$config['can_git_cutoff'] = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,45);

