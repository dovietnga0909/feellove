<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * logged_in
 *
 * @return bool
 **/
function logged_in()
{
	$CI =& get_instance();
	return (bool) $CI->session->userdata('username');
}


/**
 * Get user_id
 *
 * @return	string
 */
function get_user_id()
{
	$CI =& get_instance();
	return $CI->session->userdata('user_id');
}

/**
 * Get username
 *
 * @return	string
 */
function get_username()
{
	$CI =& get_instance();
	return $CI->session->userdata('username');
}

function is_administrator(){
	$user_name = get_username();
	return $user_name == 'admin' || $user_name == 'khuyen' || $user_name == 'toanlk';
}

function is_accounting(){
	$user_name = get_username();
	return $user_name == 'trang' || $user_name == 'thuynguyen' || $user_name == 'tannguyen';
}

function is_dev_team(){
	$user_name = get_username();
	return $user_name == 'khuyen' || $user_name == 'toanlk';
}

function is_marketing_team(){
	$user_name = get_username();
	return $user_name == 'becki' || $user_name == 'tubui';
}

function is_sale_manager(){
	$user_name = get_username();
	return $user_name == 'hiennguyen' || $user_name == 'datnguyen' || $user_name == 'hangminh';
}

/**
 * is_admin
 *
 * @return bool
 **/
function is_admin($id=false)
{
	$CI =& get_instance();
	if(logged_in() && get_user_id() == 1
			&& get_username() == 'admin') {
			
		return TRUE;
	}
	
	if(is_administrator()){
		return TRUE;
	}

	return FALSE;
}

/*
 * Get static resources from cdn
*
* $file_names		: file name or array of file names
* $custom_folder	: specify folder path
*/
function get_static_resources($file_names, $custom_folder = '', $link_only = false) {

	$CI =& get_instance();

	$content = '';
	$file_type = 0;
	$CSS_FOLDER = 'css/';
	$JS_FOLDER  = 'js/';

	$file_names = trim($file_names);

	if(!$link_only) {
		// If specify folder path
		if(!empty($custom_folder)) {
			$CSS_FOLDER = $JS_FOLDER = $custom_folder;
		}

		// --- Check file types

		// CSS, JS
		if(stripos($file_names, '.css') !== false) {
			$file_type = 1;
		} else if(stripos($file_names, '.js') !== false) {
			$file_type = 2;
		}

		// --- Get content
		if($file_type == 1) {
			$files = explode(',', $file_names);

			foreach ($files as $file) {
				$file = trim($file);
				if(empty($file)) continue;

				$full_path = base_url().str_replace("//", "/", '/'.$CSS_FOLDER . $file);

				$full_path = '<link rel="stylesheet" type="text/css" href="'.$full_path.'"/>'."\n\t\t";

				$content .= $full_path;
			}
		}if($file_type == 2) {
			$files = explode(',', $file_names);

			foreach ($files as $file) {
				$file = trim($file);
				if(empty($file)) continue;

				$full_path = base_url().str_replace("//", "/", '/'.$JS_FOLDER . $file);

				$full_path =  '<script type="text/javascript" src="'.$full_path.'"></script>'."\n\t\t";

				$content .= $full_path;
			}
		}
	}

	if(empty($content))  {
		$resource_path = $CI->config->item('resource_path');
		$content = $resource_path . $file_names;
	}

	// replace duplicate splash
	$content = str_replace("//", "/", $content);
	$content = str_replace("http:/", "http://", $content);

	return $content;
}

function get_core_theme($lib_css = null, $lib_js = null, $page_css = null, $page_js = null) {
	$system_css = get_static_resources('bootstrap.min.css', '/libs/bootstrap-3.2.0/css/');
	$system_css .= get_static_resources('font-awesome.min.css', '/libs/font-awesome/css/');
	$system_css .= $lib_css;
	$system_css .= get_static_resources('main.css');
	$system_css .= $page_css;
	
	$system_js = get_static_resources('jquery-1.10.2.min.js', '/libs/');
	$system_js .= get_static_resources('bootstrap.min.js', '/libs/bootstrap-3.2.0/js/');
	$system_js .= $lib_js;
	$system_js .= get_static_resources('main.js');
	$system_js .= $page_js;
	
	return $system_css . $system_js;
}

function get_library($name, $data) {
	
	$css_lib = ''; $js_lib = '';

	$cr_lib_css = isset($data['lib_css']) ? $data['lib_css'] : '';
	$cr_lib_js = isset($data['lib_js']) ? $data['lib_js'] : '';

	if($name == 'jquery-ui') {
		$css_lib 	= get_static_resources('jquery-ui-1.10.3.custom.min.css', '/libs/jquery-ui-1.10.3.custom/css/ui-lightness/');
		$js_lib 	= get_static_resources('jquery-ui-1.10.3.custom.min.js', '/libs/jquery-ui-1.10.3.custom/js/');
	}

	if($name == 'google-map') {
		$js_lib = '<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false&language=vi"></script>';
	}
	
	if($name == 'datepicker'){
		$css_lib 	= get_static_resources('datepicker.css', '/libs/datepicker/css/');
		$js_lib 	= get_static_resources('bootstrap-datepicker.js', '/libs/datepicker/js/');
	}
	
	if($name == 'timepicker'){
		$css_lib 	= get_static_resources('bootstrap-timepicker.min.css', '/libs/timepicker/css/');
		$js_lib 	= get_static_resources('bootstrap-timepicker.min.js', '/libs/timepicker/js/');
	}
	
	if($name == 'sortable') {
		$js_lib 	= get_static_resources('jquery.sortable.js', '/libs/sortable/');
	}
	
	if($name == 'maskmoney') {
		$js_lib 	= get_static_resources('jquery.maskMoney.min.js', '/libs/maskmoney-3.0.2/');
	}
	
	if($name == 'mask') {
		$js_lib 	= get_static_resources('jquery.mask.min.js', '/libs/mask/');
	}
	
	if($name == 'imagecrop') {
		$css_lib 	= get_static_resources('imagecrop.css', '/libs/imagecrop/');
		$js_lib 	= get_static_resources('imagecrop.js', '/libs/imagecrop/');
	}
	
	if($name == 'tinymce') {
		$js_lib 	= get_static_resources('tinymce.min.js', '/libs/tinymce/');
	}
	
	if($name == 'typeahead') {
		$js_lib 	= get_static_resources('handlebars.js', '/libs/');
		$js_lib 	.= get_static_resources('typeahead.js', '/libs/');
	}
	

	$data['lib_css'] = $cr_lib_css . $css_lib;
	$data['lib_js'] = $cr_lib_js. $js_lib;

	return $data;
}

function get_selected_menu($menu_id) {
	$CI =& get_instance();
	$current_menu = $CI->session->userdata('MENU');

	if($current_menu == $menu_id) {
		return 'class="active"';
	}
}

function is_hotel_detail() {
	$CI =& get_instance();
	$current_menu = $CI->session->userdata('MENU');
	
	if($current_menu == MNU_HOTEL_PROFILE 
			|| $current_menu == MNU_HOTEL_SURCHARGE
			|| $current_menu == MNU_HOTEL_PROMOTION 
			|| $current_menu == MNU_HOTEL_RATE_AVAILABILITY
			|| $current_menu == MNU_HOTEL_REVIEWS
			|| $current_menu == MNU_HOTEL_PARTNER
			|| $current_menu == MNU_HOTEL_CONTRACT) {
		return true;
	}
	
	return false;
}

function get_paging_config($total_rows, $uri, $uri_segment, $per_page = '') {
	
	$CI =& get_instance();

	$config['base_url'] = site_url($uri);
	$config['total_rows'] = $total_rows;
	$config['per_page'] = $per_page == '' ? $CI->config->item('per_page') : $per_page;
	$config['uri_segment'] = $uri_segment;
	$config['num_links'] = $CI->config->item('num_links');
	
	//$config['first_link'] = $CI->lang->line('common_paging_first');
	//$config['next_link'] = $CI->lang->line('common_paging_next');
	//$config['prev_link'] = $CI->lang->line('common_paging_previous');
	//$config['last_link'] = $CI->lang->line('common_paging_last');
	
	// for boostrap pagingnation
	$config['full_tag_open'] = '<ul class="pagination pull-right">';
	$config['full_tag_close'] = '</ul>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	
	$config['first_link'] = '&laquo;';
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';
	
	$config['last_link'] = '&raquo;';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';
	
	$config['next_link'] = '&gt;';
	$config['next_tag_open'] = '<li>';	
	$config['next_tag_close'] = '</li>';
	
	$config['prev_link'] = '&lt;';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	
	$config['cur_tag_open'] = '<li class="active"><span>';
	$config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
	

	return $config;
}

function get_paging_text($total_rows, $offset, $per_page = '') {
	$CI =& get_instance();
	
	if($per_page == '') $per_page = $CI->config->item('per_page');

	$paging_text = $CI->lang->line('common_paging_display');
	$next_offset = $offset + $per_page;
	if ($next_offset > $total_rows) {
		$next_offset = $total_rows;
	}
	$paging_text .= ': ' . ($offset + 1)
	. ' - ' . $next_offset
	. ' ' . $CI->lang->line('common_paging_of') . ' '
	. $total_rows;
	return $paging_text;
}

/**
 * Render main view bases on the template
 * 
 * @param $view
 * @param $data
 * @param $render
 */
function _render_view($main_view, $data=null, $search_view=null)
{
	$CI =& get_instance();
	
	if (!isset($CI->data)) $CI->data = array();
	
	$CI->viewdata = (empty($data)) ? $CI->data: $data;
	
	if(!empty($search_view)) {
		$CI->viewdata['search_frm'] = $CI->load->view($search_view, $data, TRUE);
	}

	$CI->viewdata['content'] = $CI->load->view($main_view, $CI->viewdata, TRUE);

	$view_html = $CI->load->view('_templates/template', $CI->viewdata);

	return $view_html;
}

function saving_errors() {
	$msg = '';
	$CI =& get_instance();
	
	if(empty($CI->errors)) return $msg;
	
	foreach ($CI->errors as $err) {
		$msg = lang($err).'<br>';
	}
	
	return $msg;
}

function _show_error_page($error='') {
	$CI =& get_instance();
	$CI->session->set_flashdata('error', $error);
	redirect('error');
}

function get_message() {
	$CI =& get_instance();
	$message = $CI->session->flashdata(ACTION_MESSAGE);
	
	if(isset($message) && !empty($message)) {
		$CI->data['message'] = $message;
		return $CI->load->view('_templates/message', $CI->data);
	}
}

function mark_required() {
	return '<span style="color:red">*</span>';
}

function ago($time)
{
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");

	$now = time();

	$difference     = $now - $time;
	$tense         = "ago";

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}

	$difference = round($difference);

	if($difference != 1) {
		$periods[$j].= "s";
	}

	return "$difference $periods[$j] ago ";
}

function get_last_update($datetime, $author = null) {
	
	if (strtotime($datetime) >= time() - 24 * 60 * 60) {
		$txt = ago(strtotime($datetime));
	} else {
		$txt = date('d-m-y', strtotime($datetime));
	}
	
	if( !empty($author) ) {
		$txt .= ' '. lang('by') .' '. $author;
	}
	
	return $txt;
}

function calculate_list_value_to_bit($list_values){
	
	$bit_val = 0;
	
	foreach ($list_values as $value){
		
		$bit_val = $bit_val | pow(2, $value);
		
	}
	
	return $bit_val;
	
}

function is_bit_value_contain($bit_value, $nr_index){
	
	$nr = pow(2,$nr_index) & $bit_value;
	
	return $nr > 0;
}

function display_user($user){
	
	$text_list = array();
	
	if(is_bit_value_contain($user['display_on'], HOTEL)){
		
		$text_list[] = lang('hotel');
	}
	if(is_bit_value_contain($user['display_on'], FLIGHT)){
		
		$text_list[] = lang('flight');
	}
	if(is_bit_value_contain($user['display_on'], CRUISE)){
		
		$text_list[] = lang('cruise');
	}
	if(is_bit_value_contain($user['display_on'], TOUR)){
		
		$text_list[] = lang('tour');
	}
	
	return implode(", ", $text_list);
	
}

function convert_unicode($str) {

	// lower case
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);

	// upper case
	$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);

	return $str;
}

function bpv_format_date($str, $format = DATE_FORMAT, $is_show_week_day = false){
	
	if(empty($str)) return '';
	
	$CI =& get_instance();

	$str = str_replace('/', '-', $str);

	$wd = date('w', strtotime($str));

	$str = date($format, strtotime($str));

	if($is_show_week_day){

		$week_days = $CI->config->item('week_days_vn');

		$wd = $week_days[$wd];

		$str = lang($wd).', '.$str;

	}

	return $str;
}


function bpv_round_rate($rate, $decimal = 1000){
	$rate = $rate/$decimal;
	$rate = round($rate) * $decimal;
	return $rate;
}

function format_rate_input($rate, $decimal = DECIMAL_HUNDRED){
	 
	if(!empty($rate)){

		$rate = str_replace(',', '', $rate);
		
		//$rate = str_replace('.', '', $rate);

		$rate = bpv_round_rate($rate, $decimal);

	}
	 
	return $rate;
}



function get_right( $data_type, $user_created_id ='' , $assign_user_id = '', $approve_status= '', $logged_user = '' ) {
	
	if(is_administrator()) return FULL_PRIVILEGE;
	
	return EDIT_PRIVILEGE;
}

function is_allow_to_edit($user_created_id, $data = '', $assign_user_id = '') {
	return true;
}

function is_allow_deletion($user_created_id, $data = '', $assign_user_id = '', $approve_status= '') {
	
	return true;
}

function message_alert($type = '', $content = '', $confirm = false) {
	if(!empty($type)) {
		switch ($type) {
			case 1:
				$content = lang('common_message_no_permission');
				break;
			case 2:
				$content = lang('common_message_data_in_use');
				break;
			case -1:
				break;
			default:
				$content = lang('common_message_no_permission');
				break;
		}
	}
	header('Content-Type: text/html; charset=utf-8');

	$script_ret = '<script language="javascript">';
	$script_ret .= 'alert("' . $content . '");';
	if(!$confirm) {
		$script_ret .= 'window.history.back();';
	}
	$script_ret .= '</script>';
	echo $script_ret;

	// confirm action or not
	if(!$confirm) exit();
}


function generate_random_code($alphabet = "ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789", $code_len = 6) {
	
	$pass = array(); //remember to declare $pass as an array
	
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	
	for ($i = 0; $i < $code_len; $i++) {
		
		$n = rand(0, $alphaLength);
		
		$pass[] = $alphabet[$n];
		
	}
	
	
	return implode($pass); //turn the array into a string
}

// delete cache function for admin user and dev team ONLY
function deleteCache($file_name, $type = '-1') {
	$status = false;

	if(empty($file_name)) return $status;

	$CACHE_FOLDER_PATH = array('../application/cache', '../application/cachemobile');

	$base_url = str_replace("admin/", '', site_url());

	switch ($type) {
		case CACHE_CRUISE_TOUR_PAGE:
			$file_name = md5($base_url.'du-thuyen-ha-long/tour-'.$file_name.'.html');
			break;
		case CACHE_TOUR_PAGE:
		    $file_name = md5($base_url.'tour/'.$file_name.'.html');
		    break;
		case CACHE_HOTEL_PAGE:
			$file_name = md5($base_url.'khach-san/'.$file_name.'.html');
			break;
		case CACHE_CRUISE_PAGE:
			$file_name = md5($base_url.'du-thuyen-ha-long/'.$file_name.'.html');
			break;
		case CACHE_HOTEL_DESTINATION_PAGE:
			$file_name = md5($base_url.'khach-san-'.$file_name.'.html');
			break;
		case CACHE_TOUR_DESTINATION_PAGE:
		    $file_name = md5($base_url.'tour/du-lich-'.$file_name.'.html');
		    break;
	    case CACHE_TOUR_CATEGORY_PAGE:
	        $file_name = md5($base_url.'tour/chu-de-'.$file_name.'.html');
	        break;
		default:
			break;
	}

	if (! empty ( $file_name )) {
		foreach($CACHE_FOLDER_PATH as $folder_path) {
			$real_path = realpath ( $folder_path );
			$full_path = $real_path . '/' . $file_name;

			if (file_exists ( $full_path )) {
				@unlink ( $full_path );
				$status = true;
			}
		}
	}

	return $status;
}

function _get_navigation($data, $selected_id, $session_id = null, $config_nav = 'nav_panel') {

	$CI = & get_instance();
    if ($config_nav == 'nav_panel' && ! empty($session_id))
    {
        $CI->session->set_userdata('MENU', $session_id);
    }
    
    $nav_panel = $CI->config->item($config_nav);
    
    switch ($session_id)
    {
        case MNU_TOUR_PROFILE:
            $id = $data['tour']['id'];
            break;
        case MNU_HOTEL_PROFILE:
            $id = $data['hotel']['id'];
            break;
        case MNU_CRUISE_PROFILE:
            $id = $data['cruise']['id'];
            break;
        case MNU_DESTINATION_PROFILE:
            $id = $data['destination']['id'];
        default:
            break;
    }
    
    foreach ($nav_panel as $k => $mnu)
    {
        $mnu['link'] = $mnu['link'];
        if (! empty($id))
        {
            $mnu['link'] .= '/' . $id;
        }
        $nav_panel[$k] = $mnu;
    }
    
    $data['nav_panel'] = $nav_panel;
    
    $data['side_mnu_index'] = $selected_id;
    
    return $data;
}

function _array_to_string($arr, $remove_str = "") {

	$str = '';

	if(!empty($arr)) {
		foreach ($arr as $element) {
			if($element != $remove_str) {
				$str .= $element.'-';
			}
		}

		//remove "-" character in the end of string
		$str = rtrim($str, "-");
	}

	return $str;
}
/**
 * Fetch a single line of text from the language array. Takes variable number
 * of arguments and supports wildcards in the form of '%1', '%2', etc.
 * Overloaded function.
 *
 * @access public
 * @return mixed false if not found or the language string
 */
function lang_arg(){
	$CI =& get_instance();
	//get the arguments passed to the function
	$args = func_get_args();

	//count the number of arguments
	$c = count($args);

	//if one or more arguments, perform the necessary processing
	if ($c)
	{
		//first argument should be the actual language line key
		//so remove it from the array (pop from front)
		$line = array_shift($args);

		//check to make sure the key is valid and load the line
		$line = lang($line);

		//if the line exists and more function arguments remain
		//perform wildcard replacements
		if ($line && $args)
		{
			$i = 1;
			foreach ($args as $arg)
			{
				$line = preg_replace('/\%'.$i.'/', $arg, $line);
				$i++;
			}
		
		}
	
	}
	else
	{
		//if no arguments given, no language line available
		$line = false;
	}

	return $line;
}

function get_keywords($name, $type = HOTEL)
{
    if ($type == HOTEL)
    {
        $stopwords = array(
            'khach san'
        );
        
        // remove vietnamese tones and make a string lowercase
        $str_no_tone = strtolower(convert_unicode($name));
        
        // replace two or more whitespace with a single space
        $str_no_tone = preg_replace('/\s+/', ' ', $str_no_tone);
        
        // eliminate stopwords
        foreach ($stopwords as $word)
        {
            $short_name = str_replace($word, '', $str_no_tone);
        }
        
        // keywords = string with whitespace + string without stopwords
        $keywords = $str_no_tone . ',' . $short_name;
    }
    else
    {
        // remove vietnamese tones and make a string lowercase
        $str_no_tone = strtolower(convert_unicode($name));
        
        // remove all whitespace
        $string_no_space = preg_replace('/\s+/', '', $str_no_tone);
        
        // keywords = string with space + string without space
        $keywords = $str_no_tone . ',' . $string_no_space;
    }
    
    return $keywords;
}

/**
 * Remove vietnamese tones in search term or add full text search operators
 *
 * @param unknown $term
 * @param string $with_operator
 * @return string
 */
function search_term_pre_process($term, $with_operator = false, $with_stopwords = true)
{
    $stopwords = array(
        'khach san',
        'khachsan'
    );

    // remove vietnamese tones
    $term = convert_unicode($term);

    $term = trim(strtolower($term));

    // replace two or more whitespace with a single space
    $term = preg_replace('/\s+/', ' ', $term);

    // eliminate stopwords
    if (! $with_stopwords)
    {
        foreach ($stopwords as $word)
        {

            if ($term != $word && strpos($term, $word) !== false)
            {
                $term = str_replace($word, '', $term);
            }
        }
    }

    // with boolean operator
    if ($with_operator)
    {
        $new_term = '';

        $strs = explode(' ', $term);

        foreach ($strs as $str)
        {
            $new_term = $new_term . ' *' . $str . '*';
        }
        $term = trim($new_term);
    }

    $term = trim(strtolower($term));

    return $term;
}
?>