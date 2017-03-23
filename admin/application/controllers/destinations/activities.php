<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activities extends BP_Controller {
	
	var $upload_error = '';
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Destination_Model');
		$this->load->model('Activity_Model');
		$this->load->model('Photo_Model');
		
		$this->load->language('destination');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->load->helper('destination');
		$this->load->helper('search');
		
		$this->config->load('destination_meta');
		$this->config->load('activity_meta');
	}

	public function index() {
		
		$data = _get_destination_data();
		$data = _get_navigation($data, 4, MNU_DESTINATION_PROFILE);
		
		
		$data = $this->_get_list_data($data);
		
		// render view
		$data['site_title'] = lang('destination_activity_title');
		
		_render_view('destinations/activity/list_activity', $data);
		
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_ACTIVITIES, $data);
		
		$search_criteria = $data['search_criteria'];
		
		// set tour id
		$search_criteria['destination_id'] = $data['destination']['id'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
		
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['activities'] = $this->Activity_Model->search_activities($search_criteria, $per_page, $offset);
		
		$total_rows = $this->Activity_Model->get_numb_activities($search_criteria);
		
		$data = set_paging_info($data, $total_rows, 'destinations/activities/');
		
		return $data;
		
	}
	
	// create a new activity
	public function create(){
		$data = _get_destination_data();
		
		$data = _get_navigation($data, 4, MNU_DESTINATION_PROFILE);
		
		$this->form_validation->set_rules($this->config->item('create_activity'));
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
			
			$meals = $this->input->post('meals');
			$meals = array_to_string($meals);
	
			$activity = array(
				'destination_id'  	=> $data['destination']['id'],
				'name'				=> trim($this->input->post('name')),
				'description'  		=> trim($this->input->post('description')),
				'photos'  			=> trim($this->input->post('photos')),
			);

			$save_status = $this->Activity_Model->create_activity($activity);
	
			if ($save_status){
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				
				redirect("destinations/activities/".$data['destination']['id']);
			}else{
				
				if(!is_null($save_status)){
					
					$data['save_status'] = $save_status;
					
				}
			}
		}
	
		// render view
		$data['site_title'] = lang('create_activity_title');
		
		$data = get_library('tinymce', $data);
	
		_render_view('destinations/activity/create_activity', $data);
	}
	
	// edit the itinerary type
	
	function photos() {
		
		$id = (int)$this->uri->segment(4);
		// $data = _get_tour_data(array(), $id);
		
		$data = array();
		
		$data['destination']['id']	= $id;
		
		$data['destination_photos'] 	= $this->Photo_Model->get_photos_destination($data['destination']['id']);
		
		$this->load->view('destinations/activity/photos', $data);
	}
	
	// edit the itinerary type
	public function edit(){
	
		$data = $this->_get_activity();
		
		$data['status_config']	= $this->config->item('status_config');
		/*
		print_r('<pre>');
		print_r($data);
		print_r('</pre>');
		die;
		*/
		
		$this->form_validation->set_rules($this->config->item('create_activity'));
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
	
			$activity = array(
				'id'				=> $data['activity']['id'],
				'name'				=> trim($this->input->post('name')),
				'description'		=> trim($this->input->post('description')),
				'status'			=> trim($this->input->post('status')),
				'photos'  			=> trim($this->input->post('photos')),
			);
			
			// var_dump($activity);die;
			
			$save_status = $this->Activity_Model->update_activity($activity);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("destinations/activities/".$data['activity']['destination_id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
	
		// render view
		$data['site_title'] = lang('edit_activity_title');
		
		$data = get_library('tinymce', $data);
	
		_render_view('destinations/activity/edit_activity', $data);
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data = $this->_get_activity();
	
		$status = $this->Activity_Model->delete_activity($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect("destinations/activities/".$data['activity']['destination_id']);
	}
	
	function _get_activity($data = array(), $seg = NORMAL_ID_SEGMENT) {
		
		$id = (int)$this->uri->segment($seg);
		
		$activity = $this->Activity_Model->get_activity($id);
		if ($activity == false) {
			_show_error_page(lang('no_activity_found'));
			exit;
		}
		
		$data['activity'] = $activity;

		$data = _get_destination_data($data, $activity['destination_id']);
		$data = _get_navigation($data, 4, MNU_DESTINATION_PROFILE);
		
		return $data;
	}
	
}