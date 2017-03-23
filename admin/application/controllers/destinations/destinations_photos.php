<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Destinations_Photos extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Destination_Model');
		$this->load->model('Photo_Model');
		
		$this->load->language('destination');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		$this->load->library('image_lib');
		
		$this->load->helper('photo');
		$this->load->helper('destination');
		
		$this->config->load('destination_meta');
		
		//$this->output->enable_profiler(TRUE);
	}

	public function index(){
		
		$data = _get_destination_data();
		
		$data = _get_navigation($data, 5, MNU_DESTINATION_PROFILE);
		
		$data = $this->_get_common_data($data);
		
		if(isset($_GET["action"]) && $_GET["action"] == 'remove') {
			$data = $this->_delete_photo($data);
		}
		
		if(isset($_GET["action"]) && $_GET["action"] == 're_order') {
			$data = $this->re_order();
		}
		
		$destination_photos = $data['photos'];
		
		$this->form_validation->set_error_delimiters('<span class="error">', '</span><br>');
		
		foreach ($destination_photos as $k => $photo) {
			$this->form_validation->set_rules('caption_'.$k, 'lang:destination_field_caption','required|trim|max_length[200]');
			$this->form_validation->set_rules('identity_'.$k, '','');
			$this->form_validation->set_rules('position_'.$k, '','');
		}
		
		if ($this->form_validation->run() == true) {
			
			$updated_photos = array();
				
			foreach ($destination_photos as $k => $value) {
				
				$photo = array();
				$photo['caption'] 		= trim($this->input->post('caption_'.$k));
				$photo['id'] 			= $this->input->post('identity_'.$k);
				$photo['position'] 		= $this->input->post('position_'.$k);
				$photo['type'] 			= $this->input->post('type_'.$k);
				
				// Destination Main Photo
				
				if($photo['type'] == 2) {
					$photo['destination_id'] 	= $data['destination']['id'];
				}
		
				$updated_photos[$k] = $photo;
			}
			
			$save_status = $this->Photo_Model->update_photo_destination($updated_photos);
				
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				
				redirect("destinations/photos/".$data['destination']['id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data = get_library('sortable', $data);
		$data = get_library('imagecrop', $data);
		
		$data['page_js'] = get_static_resources('destination.js');
		
		// render view
		_render_view('destinations/photo/destination_photo', $data);
	}
	
	function _get_common_data($data = array()){
		
		$data['site_title'] = lang('destination_photo_title');
		
		$data['side_mnu_index'] = 5;
		
		$data['destination_photo_type'] = $this->config->item('destination_photo_type');
		
		$data['photos'] = $this->Photo_Model->get_photos_destination($data['destination']['id']);
		
		return $data;
	}
	function _delete_photo($data) {
		
		if(isset($_GET["p_id"]) && is_numeric($_GET["p_id"])) {
			$id = $_GET["p_id"];
			$photo = $this->Photo_Model->get_photo($id);
			
			if ($photo !== false) {
				
				$save_status = $this->Photo_Model->delete_photo($id);
				
				// remove photo in uploads folder
				delete_config_photo($photo['name'], MODULE_DESTINATION);
				
				if ($save_status) {
					$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
						
					redirect("destinations/photos/".$data['destination']['id']);
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
	
	function re_order() {
		$type = $this->input->get('type');
		$photo_id = $this->input->get('p_id');
		
		$destination_photo = $this->Photo_Model->get_photo($photo_id);
		
		if ( $destination_photo !== false ) {
			$save_status = $this->Photo_Model->re_order_photo($photo_id, $type, PHOTO_DESTINATION);
			
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
			
				redirect("destinations/photos/".$destination_photo['destination_id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
	}
	
	function crop_image() {
	
		$photo_config = $this->config->item('destination_photo');
		
		$path = $photo_config['upload_path'];//str_replace('../', '', $photo_config['upload_path']);
	
		extract($_POST);
	
		if(isset($img_id) and !empty($img_id))
		{
			$photo = $this->Photo_Model->get_photo($img_id);
			
			list($t_width, $t_height) = @getimagesize(get_static_resources('/images/destinations/uploads/'.$photo['name']));
			
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
				
				$photo_folder = $this->config->item('destination_photo_size');
				resize_and_crop($resize_data, $photo_folder);
				
				// update photo dimension
				$updated_photos[] = array(
						'id' 		=> $photo['id'],
						'width' 	=> $nw,
						'height' 	=> $nh,
				);
				$this->Photo_Model->update_photo($updated_photos, PHOTO_DESTINATION);
				
				echo "Photo Crop is completed !!!";
				exit();
			}
		}
	
		echo '<span class="error"><strong>Oops!</strong> Try that again in a few moments.</span>';
	}
}
