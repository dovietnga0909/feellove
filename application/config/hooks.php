<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = array(
		'class'    => 'extend_core',
		'function' => 'custom_cache_override',
		'filename' => 'extend_core.php',
		'filepath' => 'hooks',
		'params'   => array()
);

$hook['post_controller_constructor'][] = array(
		'class'    => 'extend_core',
		'function' => 'save_landing_page',
		'filename' => 'extend_core.php',
		'filepath' => 'hooks',
		'params'   => array()
);
 
$hook['cache_override'] = array(
		'class'    => 'extend_core',
		'function' => 'display_cache_override',
		'filename' => 'extend_core.php',
		'filepath' => 'hooks',
		'params'   => array()
);


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */