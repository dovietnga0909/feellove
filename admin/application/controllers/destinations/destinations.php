<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Destinations extends BP_Controller {
	
	var $upload_error = '';
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Destination_Model');
		$this->load->language('destination');
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->load->helper('search');
		$this->load->helper('destination');
		
		$this->config->load('destination_meta');
		//$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		$this->session->set_userdata('MENU', MNU_DESTINATION);
		
		$data['site_title'] = lang('list_destination_title');
		
		// for update DB
		$this->Destination_Model->update_is_outbound_of_all_destinations();

		$data = $this->_get_list_data($data);

		_render_view('destinations/list_destinations', $data, 'destinations/search_destination');
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_DESTINATION, $data);
	
		$search_criteria = $data['search_criteria'];
		
		$data['pos_name'] = $this->_get_order_by($search_criteria);
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['destinations'] = $this->Destination_Model->search_destinations($search_criteria, $per_page, $offset);
		
		$data['destination_types'] = $this->config->item('destination_types');
		
		$data['parent_destinations'] = $this->Destination_Model->get_parent_destinations();
		
		$total_rows = $this->Destination_Model->get_numb_destinations($search_criteria);
		
		$data = set_paging_info($data, $total_rows, 'destinations');
		
		$data = set_max_min_pos($data, MODULE_DESTINATION);
	
		return $data;
	}
	
	// create a new destination
	public function create(){
		$destination_config = $this->config->item('create_destination');
		$map_config = $this->config->item('destination_map');
		$destination_config = array_merge($destination_config, $map_config);
		$this->form_validation->set_rules($destination_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
			
			$photo_config = $this->config->item('destination_photo');
				
			$destination = array(
					'name'			=> trim($this->input->post('name')),
					'marketing_title'	=> trim($this->input->post('marketing_title')),
					'type'    		=> trim($this->input->post('type')),
					'parent_id'    	=> trim($this->input->post('parent_id')),
					'description_short'	=> trim($this->input->post('description_short')),
					'description'   => trim($this->input->post('description')),
					'latitude'		=> trim($this->input->post('latitude')),
					'longitude'    	=> trim($this->input->post('longitude')),
					'is_top_hotel'	=> $this->input->post('is_top_hotel'),
			);
			
			/*	
			if (isset($_FILES['picture']) && !empty($_FILES['picture']) && !empty($_FILES['picture']['name'])) {
				$this->load->library('upload', $photo_config);
					
				if ( ! $this->upload->do_upload('picture'))
				{
					$this->upload_error = $this->upload->display_errors('<br><label class="error">', '</label></br>');
					return false;
				}
					
				$data = $this->upload->data();
			
				$destination['picture'] = $data['file_name'];
			}
			*/
			
			$save_status = $this->Destination_Model->create_destination($destination);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("destinations");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		// $data['upload_error'] = $this->upload_error;
		
		$data['destination_types'] = $this->config->item('destination_types');
		
		$data['parent_destinations'] = $this->Destination_Model->get_all_destinations();
	
		// render view
		$data['site_title'] = lang('create_destination_title');
		
		$data = get_library('google-map', $data);
		$data = get_library('tinymce', $data);
	
		_render_view('destinations/create_destination', $data);
	}
	/*
	public function _get_destination_data($data = array()){
	
		$id = (int)$this->uri->segment(3);
	
		$destination = $this->Destination_Model->get_destination($id);
		if ($destination == false) {
			_show_error_page(lang('destination_notfound'));
			exit;
		}
	
		$data['destination'] = $destination;
		
		$nav_panel = $this->config->item('nav_panel');
		
		foreach ($nav_panel as $k => $mnu) {
			$mnu['link'] = $mnu['link'].'/'.$data['destination']['id'];
			$nav_panel[$k] = $mnu;
		}
		
		$data['nav_panel'] = $nav_panel;
	
		return $data;
	}
	*/
	// edit the destination
	public function edit(){
		
		$data = _get_destination_data();
		
		$destination_config = $this->config->item('create_destination');
		
		if(is_admin()) {
		    $des_rules_addition = $this->config->item('destination_rules_addition');
		    $destination_config = array_merge($destination_config, $des_rules_addition);
		}
		
		$this->form_validation->set_rules($destination_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
			
			$photo_config = $this->config->item('destination_photo');
			
			$destination = array(
					'id'			=> $data['destination']['id'],
					'name'			=> trim($this->input->post('name')),
					'marketing_title'	=> trim($this->input->post('marketing_title')),
					//'url_title'		=> strtolower(trim($this->input->post('url_title'))),
					'type'    		=> trim($this->input->post('type')),
					'parent_id'    	=> trim($this->input->post('parent_id')),
					'description_short'   => trim($this->input->post('description_short')),
					'description'   => trim($this->input->post('description')),
					//'travel_tip'   	=> trim($this->input->post('travel_tip')),
					'is_top_hotel'	=> $this->input->post('is_top_hotel'),
			);
			
			if(is_admin()) {
			    $destination['keywords'] = $this->input->post('keywords');
			}
			
			$old_parent_id = $data['destination']['parent_id'];
			
			/*
			if ($_FILES['picture']['name'] != '') {
				$this->load->library('upload', $photo_config);
					
				if ( ! $this->upload->do_upload('picture'))
				{
					$this->upload_error = $this->upload->display_errors('<br><label class="error">', '</label></br>');
					return false;
				}
			
				$data = $this->upload->data();
				
				$destination['picture'] = $data['file_name'];
			}
			*/
			
			$save_status = $this->Destination_Model->update_destination($destination, $old_parent_id);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("destinations");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['upload_error'] = $this->upload_error;
		
		$data['destination_types'] = $this->config->item('destination_types');
		
		$data['parent_destinations'] = $this->Destination_Model->get_all_destinations();
		
		$data['side_mnu_index'] = 0;
		
		// render view
		$data['site_title'] = lang('edit_destination_title');
		
		$data = get_library('tinymce', $data);
		
		_render_view('destinations/edit_destination', $data);
	}
	
	// map
	public function map(){
		
		$data = _get_destination_data();
		
		$destination_config = $this->config->item('destination_map');
		$this->form_validation->set_rules($destination_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		if ($this->form_validation->run() == true) {
		
			$destination = array(
					'id'			=> $data['destination']['id'],
					'latitude'		=> trim($this->input->post('latitude')),
					'longitude'    	=> trim($this->input->post('longitude')),
			);
		
			$save_status = $this->Destination_Model->update_destination($destination);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("destinations");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['side_mnu_index'] = 1;
		
		$data = get_library('google-map', $data);
		
		// render view
		$data['site_title'] = lang('map_destination_title');
		
		_render_view('destinations/map', $data);
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$status = $this->Destination_Model->delete_destination($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect('destinations');
	}
	
	
	public function flight(){
	
		$data = _get_destination_data();
		
		$data = get_library('tinymce', $data);
	
		$destination_config = $this->config->item('flight_destination');
		$this->form_validation->set_rules($destination_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
	
			$destination = array(
					'id'					=> $data['destination']['id'],
					'is_flight_destination'	=> trim($this->input->post('is_flight_destination')),
					'destination_code'    	=> trim($this->input->post('destination_code')),
					'is_flight_group'		=> trim($this->input->post('is_flight_group')),
					'flight_tips'			=> trim($this->input->post('flight_tips')),
			);
	
			$save_status = $this->Destination_Model->update_destination($destination);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("destinations");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
	
		$data['side_mnu_index'] = 2;
	
		// render view
		$data['site_title'] = lang('flight_destination_title');
	
		_render_view('destinations/flight', $data);
	}
	
	public function tour(){
	
		$data = _get_destination_data();
	
		$destination_config = $this->config->item('tour_destination');
		$this->form_validation->set_rules($destination_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
	
			$destination = array(
					'id'								=> $data['destination']['id'],
					'is_tour_highlight_destination'		=> trim($this->input->post('is_tour_highlight_destination')),
					'is_tour_destination_group'    		=> trim($this->input->post('is_tour_destination_group')),
					'is_tour_includes_all_children_destination'		=> trim($this->input->post('is_tour_includes_all_children_destination')),
					'is_tour_departure_destination'					=> trim($this->input->post('is_tour_departure_destination')),
					'travel_tip'						=> trim($this->input->post('travel_tip')),
			);
	
			$save_status = $this->Destination_Model->update_destination($destination);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("destinations");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
	
		$data['side_mnu_index'] = 3;
	
		// render view
		$data['site_title'] = lang('tour_destination_title');
	
		_render_view('destinations/tour/tour', $data);
	}
	
	function destination_code_check($code) {
	
		$is_flight_destination = $this->input->post('is_flight_destination');
		if($is_flight_destination == 1) {
				
			if(empty($code)) {
				$this->form_validation->set_message('destination_code_check', lang('destination_code_required'));
				return FALSE;
			}
				
			$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
			if ( ! $this->Destination_Model->is_unique_code($code, $id)) {
				$this->form_validation->set_message('destination_code_check', sprintf($this->lang->line('common_unique_error'), $code));
				return FALSE;
			}
		}
	}
	
	function destination_name_check($str)
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$is_exist = $this->Destination_Model->is_unique_destination_name($str, $id);
	
		if ($is_exist)
		{
			$this->form_validation->set_message('destination_name_check', lang('destination_name_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function re_order() {
		if(isset($_GET["id"]) && isset($_GET["act"])) {
			$id 	= $_GET["id"];
			$action = $_GET["act"];
	
			if(is_numeric($id)) {
				
				$data = build_search_criteria(MODULE_DESTINATION, array());
				
				$pos_name = $this->_get_order_by($data['search_criteria']);
	
				$status = bp_re_order($id, $action, MODULE_DESTINATION, null, $pos_name);
					
				if ($status)
				{
					$this->session->set_flashdata('message', lang('update_successful'));
					redirect("destinations");
				}
			}
				
			if(!is_null($status)){
				$data['save_status'] = $status;
			}
		}
	}
	
	function _get_order_by($search_criteria) {
		
		$pos_name = 'position';
		
		foreach ($search_criteria as $key => $value) {
			if($key == 'is_flight_destination') {
				$pos_name = 'position_flight';
			}
		}
		
		return $pos_name;
	}
	
	function clear_cache() {
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
	
		$destination = $this->Destination_Model->get_destination($id);
	
		if(!empty($destination)) {
			deleteCache($destination['url_title'].'-'.$destination['id'], CACHE_HOTEL_DESTINATION_PAGE);
			deleteCache($destination['url_title'].'-'.$destination['id'], CACHE_TOUR_DESTINATION_PAGE);
			deleteCache($destination['url_title'].'-'.$destination['id'], CACHE_DESTINATION_PAGE);
	
			message_alert('', 'Clear cache '.$destination['name'].' completed!');
		}
	}
}
