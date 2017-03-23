<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advertises extends BP_Controller {

	public function __construct()
    {
    
       	parent::__construct();	
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Advertise_Model');
		$this->load->language('advertise');
		
		$this->load->library('form_validation');
		$this->load->library('pagination');	
		$this->load->config('advertise_meta');
	
		$this->load->library('upload');		
		$this->load->helper('photo');
		$this->load->helper('search');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	
	public function index()
	{
		$data = $this->_set_common_data();
		
		$data = $this->_get_list_advertises($data);
		
		$data = $this->_set_paging_info($data);
		
		$data['search_frm'] = $this->load->view('advertises/search_advertise', $data, TRUE);
		
		$data['content'] = $this->load->view('advertises/list_advertise', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	
	public function _set_common_data($data = array()){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_ADVERTISE);
		
		$data['site_title'] = lang('title_advertises');
		
		$data['data_types'] = $this->config->item('ad_data_types');
		
		$data['week_days'] = $this->config->item('week_days');
		
		$data['ad_pages'] = $this->config->item('ad_pages');
		
		$data['ad_areas'] = $this->config->item('ad_areas');
		
		$data = get_library('datepicker', $data);
		
		//$data = get_library('timepicker', $data);
		
		return $data;
	}
	
	public function _get_list_advertises($data = array()){
		
		$search_criteria = $this->_build_search_criteria();		
		
		$data['search_criteria'] = $search_criteria;
		
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
		
		$per_page = $this->config->item('per_page');

		// for display correct order on the column # of table list
		$data['offset'] = $offset;
		
		$data['advertises'] = $this->Advertise_Model->search_advertise($search_criteria, $per_page, $offset);
		
		$data['advertises'] = $this->_set_ad_pages($data['advertises']);
		
		$total_rows = $this->Advertise_Model->count_total_advertise($data['search_criteria']);
		
		$data = set_paging_info($data, $total_rows, URL_ADVERTISE);
		
		$data = set_max_min_pos($data, MODULE_ADVERTISES);
		
		return $data;
	}
	
	public function _build_search_criteria(){
		
		$submit_action = $this->input->post('submit_action');
		
		// access the cancellation tab without search action
		if(empty($submit_action)){			
			
			$search_criteria = $this->session->userdata(ADVERTISE_SEARCH_CRITERIA);

			if(empty($search_criteria)){
				
				$search_criteria = array();
				
			}
			
		} else {
			
			if($submit_action == ACTION_RESET){
				
				$search_criteria = array();
				
			} elseif($submit_action == ACTION_SEARCH){
				
				$search_criteria['name'] = $this->input->post('name');

				$search_criteria['data_type'] = $this->input->post('data_type');
				
				$search_criteria['display_on'] = $this->input->post('display_on');
				
				$search_criteria['status'] = $this->input->post('status');
								
			}
			
			$this->session->set_userdata(ADVERTISE_SEARCH_CRITERIA, $search_criteria);
			
		}
		
		
		return $search_criteria;
	}
	
	public function create(){
		
		$data = $this->_set_common_data();
		$data['site_title'] = lang('create_advertise');		
		$action = $this->input->post('action');
		
		if($action == 'save'){
			
			$save_status = $this->_save();

			if($save_status){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('create_successful'));
				
				redirect(site_url('advertises').'/');
				
			} else {
				
				$data['save_status'] = $save_status;
				
			}
		}
		
	
		$data['content'] = $this->load->view('advertises/create_advertise', $data, TRUE);		
		$this->load->view('_templates/template', $data);
	}
	
	public function edit(){
		
		$data = $this->_set_common_data();
		
		$data['site_title'] = lang('edit_advertise');
		
		$action = $this->input->post('action');
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data = $this->_load_nav_menu($data, $id);
		
		$advertise = $this->Advertise_Model->get_advertise($id);
		
		if($advertise !== FALSE){
			
			$data['ad'] = $advertise;
			
		}
		
		if($action == 'save'){
			
			$save_status = $this->_save(true);

			if($save_status === TRUE){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect(site_url('advertises').'/');
				
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}	
			}
		}
	
	
		
		$data['content'] = $this->load->view('advertises/edit_advertise', $data, TRUE);		
		$this->load->view('_templates/template', $data);
		
	}
	
	public function display(){
		
		$data = $this->_set_common_data();
		
		$data['site_title'] = lang('ad_mnu_display_setting');
		
		$action = $this->input->post('action');
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data = $this->_load_nav_menu($data, $id, 1);
		
		
		$hotel_destinations	 = $this->Advertise_Model->get_destinations(HOTEL);
		
		$flight_destinations = $this->Advertise_Model->get_destinations(FLIGHT);
		
		$tour_destinations	 = $this->Advertise_Model->get_destinations(TOUR);
		
		$domistic_destinations = $this->Advertise_Model->get_tour_destinations(STATUS_INACTIVE);
		
		$outbound_destinations = $this->Advertise_Model->get_tour_destinations(STATUS_ACTIVE);
		
		$categories	 =	$this->Advertise_Model->get_categories();
		
		$ad_destinations = $this->Advertise_Model->get_ad_destinations($id);
		
		$ad_tour_categories 	 = $this->Advertise_Model->get_ad_tour_categories($id);
		
		$data['hotel_des'] = $this->_set_selected_des($hotel_destinations, $ad_destinations, HOTEL);
		
		$data['flight_des'] = $this->_set_selected_des($flight_destinations, $ad_destinations, FLIGHT);
		
		$data['domistic_destinations'] = $this->_set_selected_des($domistic_destinations, $ad_destinations, TOUR);
		
		$data['outbound_destinations'] = $this->_set_selected_des($outbound_destinations, $ad_destinations, TOUR);
		
		
		$data['tour_cat_des'] = $this->_selected_categories($categories, $ad_tour_categories);
		
		$advertise = $this->Advertise_Model->get_advertise($id);
		
		if($advertise !== FALSE){
			
			$data['ad'] = $advertise;
			
		}
		
		if($action == 'save'){
			
			$save_status = $this->_save_display_setting();

			if($save_status === TRUE){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect(site_url('advertises').'/');
				
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}	
			}
		}
		
		$data['content'] = $this->load->view('advertises/ad_display_setting', $data, TRUE);		
		$this->load->view('_templates/template', $data);
		
	}
	
	public function photo(){
		
		$data = $this->_set_common_data();
		
		$data['site_title'] = lang('ad_mnu_photo');
		
		$action = $this->input->post('submit_action');
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data = $this->_load_nav_menu($data, $id, 2);
		
		$advertise = $this->Advertise_Model->get_advertise($id, true);
	
		
		if($action == ACTION_UPLOAD){
			
			$data = $this->_upload_photos($advertise, $data);
			
		}
		
		if($action == ACTION_SAVE){
			$this->_save_photos($advertise);
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
			
			redirect(site_url('advertises').'/photo/'.$id);
		}

		if($advertise !== FALSE){
			
			$advertise['photos'] = $this->Advertise_Model->get_ad_photos($advertise['id']);
			
			$data['ad'] = $advertise;
			
		}
		
		$data['content'] = $this->load->view('advertises/ad_photos', $data, TRUE);		
		$this->load->view('_templates/template', $data);
		
	}
	
	function delete_photo($ad_id, $photo_id){
		
		if(!empty($photo_id)){
			
			$photo = $this->Advertise_Model->get_photo($photo_id);
			
			$this->Advertise_Model->delete_photo($photo_id);
			
			delete_file_photo($photo['name'], 'images/advertises/');
		}
		
		redirect(site_url('advertises/photo/'.$ad_id).'/');
	}
	
	public function delete(){
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$status = $this->Advertise_Model->delete_advertise($id);
		
		if($status){
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
			
		} else {
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
		
		redirect(site_url('advertises').'/');
	}
	
	public function _save($is_edit = false){
		
		if($this->_validate()){
			
			$ad = $this->_get_post_data();
			
			if($is_edit){
				
				$id = $this->uri->segment(NORMAL_ID_SEGMENT);
				
				$save_status = $this->Advertise_Model->update_advertise($id, $ad);
				
			} else {
				
				$save_status = $this->Advertise_Model->create_advertise($ad);
					
				
			}
			
			return $save_status;
			
			
		}
		
		return NULL;
		
	}
	
	public function _save_display_setting(){
		
		if($this->_validate_display_setting()){
			
			$display_on = $this->input->post('display_on');
			$ad['display_on'] = calculate_list_value_to_bit($display_on);
			
			$ad_area = $this->input->post('ad_area');
			$ad['ad_area'] = calculate_list_value_to_bit($ad_area);
			
			$ad['all_hotel_des'] 	= $this->input->post('all_hotel_des');
			$ad['all_flight_des'] 	= $this->input->post('all_flight_des');
			$ad['all_tour_des'] 	= $this->input->post('all_tour_des');
			$ad['all_tour_cat_des'] = $this->input->post('all_tour_cat_des');
			
			$hotel_des 		= $this->input->post('hotel_des');
			$flight_des 	= $this->input->post('flight_des');
			$tour_des		= $this->input->post('tour_des');
			$tour_cat_des	= $this->input->post('tour_cat_des');	
			
			$id = $this->uri->segment(NORMAL_ID_SEGMENT);
				
			$save_status = $this->Advertise_Model->update_ad_display_setting($id, $ad, $hotel_des, $flight_des, $tour_des, $tour_cat_des);
			
			return $save_status;
			
			
		}
		
		return NULL;
		
	}
	
	
	public function _get_post_data(){
		
		$ad['name'] = $this->input->post('name');
		$ad['link'] = $this->input->post('link');		
		$ad['status'] = $this->input->post('status');
		$ad['data_type'] = $this->input->post('data_type');
		
		$ad['start_date'] = $this->input->post('start_date');
		$ad['end_date'] = $this->input->post('end_date');
		
		$ad['start_date'] = date(DB_DATE_FORMAT, strtotime($ad['start_date']));
		$ad['end_date'] = date(DB_DATE_FORMAT, strtotime($ad['end_date']));
		
		$week_day = $this->input->post('week_day');
		$week_day_nr = calculate_list_value_to_bit($week_day);
		$ad['week_day'] = $week_day_nr;
		
		/*$display_on = $this->input->post('display_on');
		$ad['display_on'] = calculate_list_value_to_bit($display_on);*/
		
		
		return $ad;
	}
	
	public function _set_validation_rules()
	{
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_rules($this->config->item('advertise'));
	}
	
	public function _validate()
	{
		$this->_set_validation_rules();
		
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;

	}
	
	public function _validate_display_setting(){
		
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		$this->form_validation->set_rules($this->config->item('display_setting'));
		
		$display_on = $this->input->post('display_on');
		
		
		if(!empty($display_on) && in_array(AD_PAGE_HOTEL_DESTINATION, $display_on)){
			
			$this->form_validation->set_rules('hotel_des[]', 'lang:ad_field_hotel_des', 'required');
		}
		
		if(!empty($display_on) && in_array(AD_PAGE_FLIGHT_DESTINATION, $display_on)){
			
			$this->form_validation->set_rules('flight_des[]', 'lang:ad_field_flight_des', 'required');
		}
		if(!empty($display_on) && in_array(AD_PAGE_TOUR_CATEGORY, $display_on)){
			
			$this->form_validation->set_rules('tour_cat_des[]', 'lang:ad_field_tour_cat_des', 'required');
		}
		
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;
	}
	
	public function _set_paging_info($data = array()){
		
		$search_criteria = isset($data['search_criteria'])? $data['search_criteria'] : array();
		
		$total_rows = $this->Advertise_Model->count_total_advertise($search_criteria);
		
		$offset = $this->uri->segment(PAGING_SEGMENT);
		
		$paging_config = get_paging_config($total_rows,'advertises/',PAGING_SEGMENT);		
		// initialize pagination
		$this->pagination->initialize($paging_config);
		
		$paging_info['paging_text'] = get_paging_text($total_rows, $offset);
		
		$paging_info['paging_links'] = $this->pagination->create_links();
		
		$data['paging_info'] = $paging_info;
		
		return $data;
		
	}
	
	public function ad_name_check($str)
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$is_exist = $this->Advertise_Model->is_ad_name_exist($str, $id);
		
		if ($is_exist)
		{
			$this->form_validation->set_message('ad_name_check', lang('ad_name_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function ad_date_check($str)
	{
		
		$ret = FALSE;
		
		if (substr_count($str, '-') == 2) {
	        list($d, $m, $y) = explode('-', $str);
	        $ret =  checkdate($m, $d, sprintf('%04u', $y));
	    }
	    
	    // check format
	    if ($ret === FALSE){
	    	$this->form_validation->set_message('ad_date_check', lang('ad_date_valid_format'));
	    } else {
	    	// check endate > start date
	    	
	    	$start_date = $this->input->post('start_date');
	    	
	    	$end_date = $this->input->post('end_date');
	    	
	    	$start_date = strtotime($start_date);
	    	
	    	$end_date = strtotime($end_date);
	    	
	    	if($start_date > $end_date){
	    		
	    		$ret = FALSE;
	    		
	    		$this->form_validation->set_message('ad_date_check', lang('ad_date_valid_value'));
	    	}
	    }
	
	    return $ret;
	}
	
	public function _set_ad_pages($advertises){
		
		$ad_pages = $this->config->item('ad_pages');
		
		foreach($advertises as $key=>$value){
			
			$display_on_val = array();
			
			$display_on = $value['display_on'];
			
			foreach ($ad_pages as $k=>$v){
				
				if(is_bit_value_contain($display_on, $k)){
					
					$display_on_val[] = $v;		
					
				}
				
			}
			
			$txt = implode(", ", $display_on_val);
			
			$value['display_on_txt'] = $txt;
			
			$advertises[$key] = $value;
		}
		
		return $advertises;
	}
	
	public function _load_nav_menu($data, $id, $mnu_index = 0){
		
		$nav_panel = $this->config->item('ad_nav_panel');
		
		foreach ($nav_panel as $key => $value){
			
			$value['link'] .= $id.'/';
			
			$nav_panel[$key] = $value;
			
		}
		
		$data['side_mnu_index'] = $mnu_index;
		
		$data['nav_panel'] = $nav_panel;
		
		return $data;
	}
	
	function _set_selected_des($destinatios, $ad_destinations, $module){
		if(!empty($destinatios)){
			foreach ($destinatios as $key=>$des){
				
				$is_selected = false;
				
				foreach ($ad_destinations as $ad_des){
					
					if($ad_des['destination_id'] == $des['id'] && $ad_des['module'] == $module){
						
						$is_selected = true;
						
						break;
						
					}
					
				}
				
				$des['is_selected'] = $is_selected;
				
				if(!empty($des['destinations'])){
					
					$des['destinations'] = $this->_set_selected_des($des['destinations'], $ad_destinations, $module);
					
				}
				$destinatios[$key] = $des;
			}
		}
		return $destinatios;
	}
	
	function _selected_categories($categories, $ad_tour_categories){
		
		if(!empty($categories)){
			foreach ($categories as $key=>$cat){
				
				$is_selected = false;
				
				foreach ($ad_tour_categories as $ad_tour){
					
					if($ad_tour['category_id'] == $cat['id']){
						
						$is_selected = true;
						
						break;
						
					}
					
				}
				
				$cat['is_selected'] = $is_selected;
				
				$categories[$key] = $cat;
				
			}
		}
		return $categories;
		
	}
	
	function _upload_photos($ad, $data = array()){
		
		if(empty($ad)) return;
		
		$this->upload->initialize(get_advertise_photo_config($ad));
		
		if ( ! $this->upload->do_multi_upload("photos"))
		{
			
			$data['uploaded_errors'] = $this->upload->display_errors('<p class="text-danger">','</p>');
		}
		else
		{
			$upload_data = $this->upload->get_multi_upload_data();
			
			$photos = array();
			
			foreach ($upload_data as $upload) {
				
				$photo = array();
				$photo['advertise_id'] 	= $ad['id'];
								
				$photo['status'] 	= STATUS_ACTIVE;				
				$photo['name'] 		= $upload['file_name'];
				
				$photo['date_created'] = date(DB_DATE_TIME_FORMAT);
				$photo['date_modified'] = date(DB_DATE_TIME_FORMAT);
				
				$login_user_id = get_user_id();    	
    			$photo['user_created_id'] = $login_user_id;
    			$photo['user_modified_id'] = $login_user_id;
				
				$photos[] 	= $photo;
			}
			
			
			$this->Advertise_Model->create_ad_photos($photos);
			
		}
		
		return $data;
		
	}
	
	function _save_photos($ad){
		
		if(!empty($ad['photos'])){
			
			foreach ($ad['photos'] as $photo){
				
				$status = $this->input->post('status_'.$photo['id']);
				
				$version = $this->input->post('version_'.$photo['id']);
				
				$display_on = $this->input->post($photo['id'].'_display_on');
				
				$display_on = calculate_list_value_to_bit($display_on);
				
				$p['status'] = $status;
				$p['display_on'] = $display_on;
				$p['date_modified'] = date(DB_DATE_TIME_FORMAT);
				$p['version'] = $version;
				
				$login_user_id = get_user_id();   			
    			$p['user_modified_id'] = $login_user_id;
				
				$this->Advertise_Model->update_photo($photo['id'], $p);
				
			}
			
		}
		
	}
	
	function re_order() {
        if(isset($_GET["id"]) && isset($_GET["act"])) {
            $id 	= $_GET["id"];
            $action = $_GET["act"];
    
            if(is_numeric($id)) {
    
                $status = bp_re_order($id, $action, MODULE_ADVERTISES);
                	
                if ($status)
                {
                    $this->session->set_flashdata('message', lang('edit_advertise_successful'));
                    redirect("advertises");
                }
            }
            	
            if(!is_null($status)){
                $data['save_status'] = $status;
            }
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/cancellation.php */