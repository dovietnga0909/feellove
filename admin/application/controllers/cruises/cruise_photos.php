<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cruise_Photos extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Cruise_Model');
		$this->load->model('Photo_Model');
		$this->load->model('Cabin_Model');
		
		$this->load->language('cruise');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		$this->load->library('image_lib');
		
		$this->load->helper('photo');
		$this->load->helper('cruise');
		
		$this->config->load('cruise_meta');
		
		//$this->output->enable_profiler(TRUE);
	}

	public function index()
	{
		$data = _get_cruise_data();
		$data = _get_navigation($data, 3, MNU_CRUISE_PROFILE);
		
		$data = $this->_get_common_data($data);
		
		if(isset($_GET["action"]) && $_GET["action"] == 'remove') {
			$data = $this->_delete_photo($data);
		}
		
		if(isset($_GET["action"]) && $_GET["action"] == 're_order') {
			$data = $this->re_order();
		}
		
		$cruise_photos = $data['photos'];
		
		$this->form_validation->set_error_delimiters('<span class="error">', '</span><br>');
		foreach ($cruise_photos as $k => $photo) {
			$this->form_validation->set_rules('caption_'.$k, 'lang:cruises_field_caption','required|trim|max_length[200]');
			$this->form_validation->set_rules('type_'.$k, '','callback_cabin_check['.$k.']');
			$this->form_validation->set_rules('identity_'.$k, '','');
			$this->form_validation->set_rules('position_'.$k, '','');
			$this->form_validation->set_rules('room_type_'.$k, '','');
			$this->form_validation->set_rules('room_type__main_photo_'.$k, '','');
		}
		
		if ($this->form_validation->run() == true) {
			
			$updated_photos = array();
				
			foreach ($cruise_photos as $k => $value) {
				
				$photo = array();
				$photo['type'] 			= $this->input->post('type_'.$k);
				$photo['caption'] 		= trim($this->input->post('caption_'.$k));
				$photo['id'] 			= $this->input->post('identity_'.$k);
				$photo['position'] 		= $this->input->post('position_'.$k);
				
				// Cabin Photo
				if($photo['type'] == 3) {
					$photo['room_ids'] 			= $this->input->post('room_type_'.$k);
					$photo['room_main_photo_ids'] 	= $this->input->post('room_type_main_photo_'.$k);
				}
				
				// Cruise Main Photo
				if($photo['type'] == 2) {
					$photo['cruise_id'] 	= $data['cruise']['id'];
				}
		
				$updated_photos[$k] = $photo;
			}
			
			//print_r($updated_photos);exit();
				
			$save_status = $this->Photo_Model->update_photo($updated_photos, PHOTO_CRUISE);
				
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				
				redirect("cruises/photos/".$data['cruise']['id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data = get_library('sortable', $data);
		$data = get_library('imagecrop', $data);
		
		$data['page_js'] = get_static_resources('cruise.js');
		
		// render view
		_render_view('cruises/photo/cruise_photo', $data);
	}
	
	public function _get_common_data($data = array()){
		
		// filter: cabin type
		$cabin_id = 0;
		if(isset($_GET["r_id"]) && is_numeric($_GET["r_id"])) {
			$cabin_id = $_GET["r_id"];
		}
		$data['cabin_id'] = $cabin_id;
		
		// filter: photo gallery
		$photo_type = 0;
		if(isset($_GET["type"]) && is_numeric($_GET["type"]) && $_GET["type"] == 1) {
			$photo_type = $_GET["type"];
		}
		$data['photo_type'] = $photo_type;
		
		$data['site_title'] = lang('cruise_photo_title');
		
		$data['side_mnu_index'] = 3;
		
		$data['cabins'] = $this->Cabin_Model->get_cabins($data['cruise']['id']);
		
		$data['cruise_photo_type'] = $this->config->item('cruise_photo_type');
		
		$photos = $this->Photo_Model->get_photos(PHOTO_CRUISE, $data['cruise']['id'], $photo_type, $cabin_id);
		
		foreach ($photos as $k => $photo) {
			
			$photo['room_ids'] = _array_to_string($photo['room_ids']);
			$photo['room_main_photo_ids'] = _array_to_string($photo['room_main_photo_ids']);
			
			$photos[$k] = $photo;
		}
		
		$data['photos'] = $photos;
		
		$data['photo_count'] = $this->Photo_Model->count_photo($data['cruise']['id'], $data['cabins'], PHOTO_CRUISE);
	
		return $data;
	}
	
	function _delete_photo($data) {
		
		if(isset($_GET["p_id"]) && is_numeric($_GET["p_id"])) {
			$id = $_GET["p_id"];
			$photo = $this->Photo_Model->get_photo($id);
			
			if ($photo !== false) {
				// remove photo in db
				$save_status = $this->Photo_Model->delete_photo($id, PHOTO_CRUISE);
					
				// remove photo in uploads folder
				delete_config_photo($photo['name'], MODULE_CRUISES);
				
				if ($save_status)
				{
					$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
						
					redirect("cruises/photos/".$data['cruise']['id']);
				} else {
					if(!is_null($save_status)){
						$data['save_status'] = $save_status;
					}
				}	
			}
		}
		
		$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		
		return $data;
	}
	
	function cabin_check($field, $index) {
		
		$type = $this->input->post('type_'.$index);
		$cabin_ids 	= $this->input->post('room_type_'.$index);
		$caption 	= trim($this->input->post('caption_'.$index));
		
		if ($type == 3 && empty($cabin_ids))
		{
			$this->form_validation->set_message('cabin_check', lang('error_empty_cabintype'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function re_order() {
		$type = $this->input->get('type');
		$photo_id = $this->input->get('p_id');
		
		$cruise_photo = $this->Photo_Model->get_photo($photo_id);
		
		if ( $cruise_photo !== false ) {
			$save_status = $this->Photo_Model->re_order_photo($photo_id, $type, PHOTO_CRUISE);
			
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
			
				redirect("cruises/photos/".$cruise_photo['cruise_id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
	}
	
	function crop_image() {
	
		$photo_config = $this->config->item('cruise_photo');
		
		$path = $photo_config['upload_path'];//str_replace('../', '', $photo_config['upload_path']);
	
		extract($_POST);
	
		if(isset($img_id) and !empty($img_id))
		{
			$photo = $this->Photo_Model->get_photo($img_id);
			
			list($t_width, $t_height) = @getimagesize(get_static_resources('/images/cruises/uploads/'.$photo['name']));
			
			$img = $photo['name'];
			
			$img_new_name = $photo['name'];
				
			$nw = $crop_width;//ceil($w * $ratio);
			$nh = ceil($nw * 3/4);//ceil($h * $ratio);
			$nx = $x1;//($x1 > 0) ? floor($x1 * $t_width/$crop_width) : 0;
			
			$dst_x = 0;   // X-coordinate of destination point.
			$dst_y = 0;   // Y --coordinate of destination point.
			$src_x = $nx; // Crop Start X position in original image
			$src_y = $y1;//($x1 > 0) ? floor($y1 * $nx/$x1) : floor($y1); // Crop Srart Y position in original image
			$dst_w = $nw; // Crop image width
			$dst_h = $nh; // Crop image height
			$src_w = $nw; // $src_x + $dst_w Crop end X position in original image
			$src_h = $nh; // $src_y + $dst_h Crop end Y position in original image
			
			if (file_exists($path.$img)) {
				// Creating an image with true colors having thumb dimensions.( to merge with the original image )
				$nimg = imagecreatetruecolor($dst_w,$dst_h);
				
				// Get original image
				$src_image = imagecreatefromjpeg($path.$img);
				
				// Cropping
				imagecopyresampled($nimg, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
				
				// Saving
				imagejpeg($nimg, $path.$img_new_name, 100);
				
				$resize_data = array(
					'file_name' => $img_new_name,
					'full_path' => $path.$img_new_name,
					'image_width' 	=> $nw,
					'image_height' 	=> $nh,
				);
				
				$photo_folder = $this->config->item('cruise_photo_size');
				resize_and_crop($resize_data, $photo_folder);
				
				// update photo dimension
				$updated_photos[] = array(
						'id' 		=> $photo['id'],
						'width' 	=> $nw,
						'height' 	=> $nh,
				);
				$this->Photo_Model->update_photo($updated_photos, PHOTO_CRUISE);
				
				echo "Photo Crop is completed !!!";
				exit();
			}
		}
	
		echo '<span class="error"><strong>Oops!</strong> Try that again in a few moments.</span>';
	}
}
