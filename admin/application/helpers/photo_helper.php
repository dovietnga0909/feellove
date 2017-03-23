<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_photo_config($obj_name, $obj_config) {
	$CI =& get_instance();
	$config = $CI->config->item($obj_config);
	
	$fileName = array();
	
	if(isset($_FILES['photos']) && isset($_FILES['photos']['name'])) {
		
		foreach ($_FILES['photos']['name'] as $image) {
		
			$name = strtolower(convert_unicode($obj_name). ' ' . uniqid());
			
			//print_r($name);exit();
		
			$fileName[]= url_title($name);
		}
		
		$config['file_name'] = $fileName;
		
	}
	
	return $config;
}

function get_advertise_photo_config($advertise) {
	$CI =& get_instance();
	$config = $CI->config->item('advertise_photo');
	
	$fileName = array();
	
	if(isset($_FILES['photos'])) {
		foreach ($_FILES['photos'] as $image) {
		
			$name = strtolower(convert_unicode($advertise['name']). ' ' . uniqid());
		
			$fileName[]= url_title($name);
		}
		
		$config['file_name'] = $fileName;
		
	}
	
	return $config;
}

function delete_file_photo($photos, $path)
{
	$CI =& get_instance();
	$system_path = str_replace('system/', '', BASEPATH);

	if(empty($photos)) return false;

	if(is_array($photos)) {
		foreach ($photos as $photo) {
			$full_path = $system_path . $path . $photo['name'];
			
			@unlink( $full_path );
		}
	} else {
		$full_path = $system_path . $path . $photos;
		
		@unlink( $full_path );
	}
	
}

function delete_config_photo($photos, $module) {
	
	$CI =& get_instance();
	if($module == MODULE_HOTEL) {
		$upload_config = 'hotel_photo';
		$resize_config = 'hotel_photo_size';
	} elseif($module == MODULE_CRUISES) {
		$upload_config = 'cruise_photo';
		$resize_config = 'cruise_photo_size';
	} elseif($module == MODULE_TOURS) {
		$upload_config = 'tour_photo';
		$resize_config = 'tour_photo_size';
	} elseif($module == MODULE_DESTINATION) {
		$upload_config = 'destination_photo';
		$resize_config = 'destination_photo_size';
	}
	
	// upload config
	$u_photo_config = $CI->config->item($upload_config);
	
	$path = str_replace('../', '', $u_photo_config['upload_path']);
	
	delete_file_photo($photos, $path);
	
	// resize config
	if(!empty($resize_config)) {
		$h_photo_config = $CI->config->item($resize_config);
	
		foreach ($h_photo_config as $folder) {
	
			$path = str_replace('../', '', $folder['path']);
	
			foreach ($folder['size'] as $size) {
				$h_size = $size['width'] . 'x' .$size['height'];
	
				if(is_array($photos)) {
					foreach ($photos as $photo) {
						$name = explode('.', $photo['name']);
						$photo_name = $name[0] . '-' . $h_size . '.' . $name[1];
	
						delete_file_photo($photo_name, $path);
					}
				} else {
					$name = explode('.', $photos);
					$photo_name = $name[0] . '-' . $h_size . '.' . $name[1];
	
					//echo $path . $photo_name . '<br>';
					delete_file_photo($photo_name, $path);
				}
			}
		}
	}
}



/**
 * Resize And Crop Image
 *
 * Takes the source image resizesand crop it to the specified width & height or proportionally if crop is off.
 * It always crops from the center.
 * @param string $source_image The location to the original raw image.
 * @param int $quality The quality of the JPG to produce 1 - 100
 */
function resize_and_crop($upload_data, $hotel_photo_folder, $quality = 100) {
	$CI =& get_instance();
	
	$data = array();
	$error = array();
	
	foreach ($hotel_photo_folder as $folder) {
	
		$photo_sizes = $folder['size'];
	
		foreach ($photo_sizes as $size) {
				
			// photo name = Object name . uniqueID . size
				
			$str_name = explode('.', $upload_data['file_name']);
			$file_name = convert_unicode($str_name[0]). '-' . $size['width'] . 'x' .$size['height'] . '.' . $str_name[1];
			
			$src_aspect = number_format($upload_data['image_width']/$upload_data['image_height'], 1);
			$des_aspect = number_format($size['width']/$size['height'], 1);
	
			// set the resize config
			$resize_conf = array(
					'source_image'  	=> $upload_data['full_path'],
					'new_image'     	=> $folder['path'] . $file_name,
					'maintain_ratio'	=> true,
					'quality'			=> $quality,
					'width'         	=> $size['width'],
					'height'        	=> $size['height'],
					'master_dim'		=> $src_aspect > $des_aspect ? 'height' : 'width',
					//'master_dim'		=> ($upload_data['image_width'] > $upload_data['image_height']) ? 'height' : 'width',
			);
				
			// initializing
			$CI->image_lib->initialize($resize_conf);
				
			// do it!
			if ( ! $CI->image_lib->resize())
			{
				// if got fail.
				$error['resize'][] = $CI->image_lib->display_errors();
			}
			
			
			// ---- Crop Image
			list($old_width, $old_height) = @getimagesize($folder['path'] . $file_name);
			
			$output = get_axis($old_width, $old_height, $size['width'], $size['height']);
			
			// set the crop config
			$crop_conf = array(
					'source_image'  	=> $folder['path'] . $file_name,
					'new_image'     	=> $folder['path'] .$file_name,
					'maintain_ratio'	=> false,
					'quality'			=> $quality,
					'x_axis'			=> $output['x_axis'],
					'y_axis'			=> $output['y_axis'],
					'width'         	=> $size['width'],
					'height'        	=> $size['height'],
			);
			
			$CI->image_lib->initialize($crop_conf);
			
			if ( ! $CI->image_lib->crop())
			{
				$error['crop'][] = $CI->image_lib->display_errors();
				log_message('error', $CI->image_lib->display_errors());
			}
				
			// see what we get
			if(count($error > 0))
			{
				$data['error'] = $error;
			}
		}
	}
	
	return $data;
}

function get_axis($old_width, $old_height, $new_width, $new_height)
{
	// Default values
	$output['x_axis'] = 0;
	$output['y_axis'] = 0;

	/**
	 * Calculate where to crop based on the center of the image
	 */
	if ($old_width > $new_width) {
		$output['x_axis'] = floor( ( $old_width - $new_width  ) / 2 );
	}
	
	if ($old_height > $new_height) {
		$output['y_axis'] = round( ( $old_height - $new_height ) / 2 );
	}
	
	return $output;
}

function check_file_upload_limit() {
	
	$counter = 0;
	foreach($_FILES as $value){
		if( isset($value['name']) && !empty($value['name']) ) {
			foreach ($value['name'] as $name) {
				if (strlen($name)){
					$counter++;
				}
			}
		}
	}
	
	if ($counter > UPLOAD_FILE_LIMIT) {
		return false;
	}
	
	return true;
}

function get_file_upload_caption($image_name) {
	
	$caption_name = '';

	$pos = strrpos($image_name, '.');
		
	if ($pos === false) {
		// do nothing for wrong file name
	} else {
		$caption_name = substr($image_name, 0, $pos);
			
		//$image_name = convert_unicode($image_name);
			
		$split_char = array('_', '-');
		foreach ($split_char as $char) {
			$caption_name = str_replace($char, ' ', $caption_name);
		}
		
		$caption_name = ucwords(trim($caption_name));
	}

	return $caption_name;
}

?>