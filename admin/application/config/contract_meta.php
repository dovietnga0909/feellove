<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['upload_contract'] = array(
		'upload_path' 	=> '../documents/hotels/',
		'allowed_types' => 'doc|docx|xl|xlsx|pdf|word|jpg|txt|rtf|msg',
		'max_size' 		=> '10240', // 10 Mb
        'remove_spaces' => FALSE,
        'overwrite'     => FALSE,
);





