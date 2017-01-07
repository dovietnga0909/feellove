<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photo_Upload extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Destination_Model');
		$this->load->model('Photo_Model');
		
		$this->load->language('destination');
		
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('image_lib');
		
		$this->load->helper('photo');
		$this->load->helper('destination');
		
		$this->config->load('destination_meta');
	}

	public function index()
	{
		$data = $this->_set_common_data();

		if( isset($_GET["action"]) && $_GET["action"] == ACTION_CANCEL) {
			
			$uploaded_photos = $this->session->userdata('uploaded_photo');
			
			$this->_clear_photos($uploaded_photos);
			// redirect
			redirect('destinations/photo_upload/'.$data['destination']['id']);
		
		}
	
		
		if ( isset($_POST["action"]) && $_POST["action"] == ACTION_UPLOAD ) {
			// upload photos
			$data = $this->_upload_photo($data);
		}
	
		
		
		// render view
		_render_view('destinations/photo/photo_upload_form', $data);
	}
	
	function _set_common_data() {
		
		$data = _get_destination_data();
		$data = _get_navigation($data, 5, MNU_DESTINATION_PROFILE);
		
		$data['site_title'] = lang('destination_photo_upload_title');
		
		$data['destination_photo_type'] = $this->config->item('destination_photo_type');
		
		return $data;
	}
	
	/*
	 * Upload photo:
	 * 
	 * Resize and crop photos
	 */
	function _upload_photo($data)
	{
		
		// clear uploaded photos in session
		$this->session->unset_userdata('uploaded_photo');
		
		if( !check_file_upload_limit() ) {
			
			$data['error'][] = lang('error_upload_file_limit');
			
		} else {
			
			$photo_config = get_photo_config($data['destination']['name'], 'destination_photo');
			
			$this->upload->initialize($photo_config);
			
			
			if ( ! $this->upload->do_multi_upload("photos"))
			{
				$error = array('error' => $this->upload->display_errors());
			
				$data['error'] = $error;
				
			}
			else
			{	
				
				$upload_data = $this->upload->get_multi_upload_data();
				
				$upload_photos = array();
				
				foreach ($upload_data as $upload) {
					$photo = array();
					//$photo['type'] 		= 1;
					$photo['type'] 		= 1;
					$photo['destination_id'] 	= $data['destination']['id'];
					$photo['name'] 				= $upload['file_name'];
					$photo['width'] 			= $upload['image_width'];
					$photo['height'] 			= $upload['image_height'];
					$photo['caption'] 			= get_file_upload_caption($upload['client_name']);
					
					$upload_photos[] 			= $photo;
			
					$photo_folder = $this->config->item('destination_photo_size');
					resize_and_crop($upload, $photo_folder);
				}
					
				$uploaded_photos = $this->Photo_Model->create_photo($upload_photos, PHOTO_DESTINATION, true);
					
				// store uploaded photos in session
				$this->session->set_userdata('uploaded_photo', $uploaded_photos);
					
				redirect("destinations/photo_uploaded/".$data['destination']['id']);
			}	
		}
		
		return $data;
	}
	
	function uploaded() {
		
		// get common data: tour detail, cabin types
		$data = $this->_set_common_data();
		
		// get uploaded photos
		$uploaded_photo = $this->session->userdata('uploaded_photo');
		
		if(empty($uploaded_photo)) {
			redirect("destinations/photos/".$data['destination']['id']);
		}
		
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		foreach ($uploaded_photo as $k => $photo) {
			$this->form_validation->set_rules('caption_'.$k, 'lang:destination_field_caption','required|max_length[200]');
		}
		
		if ($this->form_validation->run() == true) {
			
			foreach ($uploaded_photo as $k => $photo) {
				//$photo['type'] 				= $this->input->post('type_'.$k);
				$photo['type'] 				= $this->input->post('type_'.$k);
				$photo['caption'] 			= $this->input->post('caption_'.$k);
				
				$uploaded_photo[$k] = $photo;
			}
			
			$save_status = $this->Photo_Model->update_photo_destination($uploaded_photo);
			
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				
				// clear session
				$this->session->unset_userdata('uploaded_photo');
				
				redirect("destinations/photos/".$data['destination']['id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}	
		}
		
		$data['photos'] = $uploaded_photo;
		
		// render view
		$data['page_js'] = get_static_resources('tour.js');
			
		_render_view('destinations/photo/photo_uploaded', $data);
	}
	
	/*
	 * Remove photos in session and uploads fodler
	 */
	function _clear_photos($uploaded_photo) {
		
		foreach ($uploaded_photo as $photo) {
			// remove photo in db
			$this->Photo_Model->delete_photo($photo['id'], PHOTO_TOUR);
					
			// remove photo in uploads folder
			delete_config_photo($photo['name'], MODULE_TOURS);
		}
			
		// clear session
		$this->session->unset_userdata('uploaded_photo');
	}
}
