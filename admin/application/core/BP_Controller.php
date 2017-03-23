<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BP_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();

		if ( !logged_in()) {
			redirect(site_url());
		}
	}
}
?>