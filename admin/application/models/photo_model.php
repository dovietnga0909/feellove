<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photo_Model extends CI_Model{	

	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	
	// ------------------------------------------------------------------------
	
	/**
	 * Create new photo of Cruise, Hotel or Tour
	 * 
	 * @param unknown $photos
	 * @param string $is_return
	 * @return unknown|boolean
	 */
	public function create_photo($photos, $category, $is_return = false)
	{
		foreach ($photos as $k => $photo) {
			$position = $this->_get_position($photo, $category);
			
			// Additional data
			$additional_data = array(
					'user_created_id'	=> get_user_id(),
					'user_modified_id'	=> get_user_id(),
					'date_created'		=> date(DB_DATE_TIME_FORMAT),
					'date_modified'		=> date(DB_DATE_TIME_FORMAT),
					'status'			=> STATUS_ACTIVE,
					'position'			=> $position + 1,
			);
			
			$photo = array_merge($photo, $additional_data);
			
			$this->db->insert('photos', $photo);
			
			$photo['id'] = $this->db->insert_id();
			
			$photos[$k] = $photo;
		}
		
		if($is_return) {
			return $photos;
		}
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_photo($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
	
		$query = $this->db->get('photos');
	
		$result = $query->result_array();
	
		if (count($result) == 1)
		{
			return $result[0];
		}
	
		return FALSE;
	}
	
	/*
	 * get_photos
	 * 
	 * type: type of photos such as gallery, room photo or room main photo
	 * category : hotel or cruise or tour photos
	 */
	function get_photos($category, $object_id, $type = null, $room_id = null) {
	
		if(empty($object_id)) {
			return FALSE;
		}

		$this->db->select('p.*, rp.room_id, rp.cabin_id');
		$this->db->join('room_photos rp', 'p.id = rp.photo_id', 'left outer');
		
		$this->_setCriteria($category, $object_id, $type, $room_id);
		
		$this->db->group_by('p.id');
		
		$this->db->order_by('p.position', 'asc');
	
		$query = $this->db->get('photos p');
		
		$photos = $query->result_array();
		
		if ($category != PHOTO_TOUR) {
			$col_name = $this->_get_col_name($category);
			
			foreach ($photos as $k => $photo) {
				$room_photos = $this->get_room_photos($category, null, $photo['id']);
			
				$room_ids = array();
				$room_main_photo_ids = array();
				if(!empty($room_photos)) {
					foreach ($room_photos as $room_photo) {
						$room_ids[] = $room_photo[$col_name];
							
						if($room_photo['is_main_photo'] == 1) {
							$room_main_photo_ids[] = $room_photo[$col_name];
						}
					}
				}
				$photo['room_ids'] 	= $room_ids;
				$photo['room_main_photo_ids'] = $room_main_photo_ids;
			
				$photos[$k] = $photo;
			}	
		}
		
		/*
		$this->db->where('hotel_id', $this->db->escape_str($hotel_id));
		
		if(!empty($type)) {
			$this->db->where('type', $this->db->escape_str($type));
		}
		
		$this->db->order_by('position', 'asc');
		
		$query = $this->db->get('photos');
		
		$photos = $query->result_array();
		
		foreach ($photos as $k => $photo) {
			
			$room_photos = $this->get_room_photos(null, $photo['id']);
			
			$room_ids = array();
			if(!empty($room_photos)) {
				foreach ($room_photos as $room_photo) {
					$room_ids[] = $room_photo['room_id'];
				}
			}
			$photo['room_ids'] = $room_ids;
			
			$photos[$k] = $photo;
		}
		
		$this->benchmark->mark('code_end');
		
		echo $this->benchmark->elapsed_time('code_start', 'code_end'); exit();
		*/
	
		return $photos;
	}
	
	function get_photos_destination($destination_id){
		
		$this->db->select('*');
		
		$this->db->where('destination_id', $destination_id);
		
		$query = $this->db->get('photos');
		
		$data = $query->result_array();
		
		return $data;
		
	}
	
	function get_photos_main_destination($photo_id){
		
		$this->db->select('*');
		
		$this->db->where('id', $photo_id);
		
		$query = $this->db->get('photos');
		
		$data = $query->result_array();
		
		return $data;
		
	}
	
	function update_photo_destination($photos){
		
		$this->db->trans_start();
		
		foreach ($photos as $photo) {
			$additional_data	=	array(
				'user_modified_id'	=> get_user_id(),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
			);
			
			$photo = array_merge($photo, $additional_data);
			
			$this->db->update('photos', $photo, array('id' => $photo['id']));
			
			if(isset($photo['type'])){
				
				if($photo['type'] == 2){
					
					$photo_main = $this->get_photos_main_destination($photo['id']);
					
					if(count($photo_main) == 1 ){
						
						$destination_picture['picture']	= $photo_main[0]['name'];
						
						$this->db->update('destinations', $destination_picture, array('id' => $photo['destination_id']));
						
					}
					
				}
			}
			
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	
	
	function update_photo($photos, $category) {
		
		//$this->benchmark->mark('code_start');
		//$cnt = 0;
		
		$this->db->trans_start();
		
		foreach ($photos as $photo) {
			// Additional data
			$additional_data = array(
				'user_modified_id'	=> get_user_id(),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
			);
				
			$photo = array_merge($photo, $additional_data);
			
			// Unset room id 
			$room_ids = isset($photo['room_ids']) ? $photo['room_ids'] : null;
			$room_main_photo_ids = isset($photo['room_main_photo_ids']) ? $photo['room_main_photo_ids'] : null;
			unset($photo['room_id']);
			unset($photo['room_ids']);
			unset($photo['room_main_photo_ids']);
				
			$this->db->update('photos', $photo, array('id' => $photo['id']));
			
			// update or create new room/cabin photos
			if (isset($photo['type'])) {
				if($photo['type'] == 3 && !empty($room_ids)) {
					$this->update_room_photo($photo, $category, $room_ids, $room_main_photo_ids);
					//$cnt++;
				} else {
					// remove room photos
					$this->delete_room_photo($photo['id'], $category, null);
					//$cnt++;
				}
					
				if($photo['type'] == 2) {
					$this->update_hotel_main_photo($photo, $category);
					//$cnt++;
				}
			}
			
			if (isset($photo['tour_photo_type'])) {
				if($photo['tour_photo_type'] == 2) {
					$this->update_tour_main_photo($photo);
				}
			}
		}
		
		$this->db->trans_complete();
		
		/*
		$this->benchmark->mark('code_end');
		
		echo '<code style="font-size: 24px; padding: 20px; float:left">';
		echo '<p>Number of photos: <b>'.count($photos).'</b></p>';
		echo 'Elapsed Time: <b>'. $this->benchmark->elapsed_time('code_start', 'code_end') . '</b>';
		echo '<p>Number of queries: <b>'.$cnt.'</b></p>'; 
		echo '</code>';
		exit();
		*/
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_photo($id, $category) {
	
		$this->db->where('id', $id);
		$this->db->delete('photos');
		
		$this->db->where('photo_id', $id);
		$this->db->delete('room_photos');
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function _get_position($photo, $category) {
		
		$this->db->select_max('position');
		
		$col_name = '';
		$object_id;
		
		switch ($category) {
			case PHOTO_HOTEL:
				$col_name = 'hotel_id';
				$object_id = $photo['hotel_id'];
				break;
			case PHOTO_CRUISE:
				$col_name = 'cruise_id';
				$object_id = $photo['cruise_id'];
				break;
			case PHOTO_TOUR:
				$col_name = 'tour_id';
				$object_id = $photo['tour_id'];
				break;
			case PHOTO_DESTINATION:
				$col_name = 'destination_id';
				$object_id = $photo['destination_id'];
				break;
		}
		
		$this->db->where($col_name, $object_id);
	
		$query = $this->db->get('photos');
	
		$results = $query->result_array();
		if (!empty($results)) {
				
			return $results[0]['position'];
		}
	
		return 0;
	}
	
	/**
	 * update_room_photo
	 * 
	 * @param unknown_type $photos
	 */
	function update_room_photo($photo, $category, $room_ids, $room_main_photo_ids) {
		
		$col_name = $this->_get_col_name($category);
		
		$this->db->trans_start();
		
		$room_ids = explode('-', $room_ids);
		$room_main_photo_ids = explode('-', $room_main_photo_ids);
		
		$current_room_photos = $this->get_room_photos($category, null, $photo['id']);
		$existed_room_ids = array();
		foreach ($current_room_photos as $room_photo) {
			$existed_room_ids[] = $room_photo[$col_name];
		}
	
		$diff = $this->_get_array_different($existed_room_ids, $room_ids);
		
		// insert new room photos
		if(!empty($diff['new'])) {
			$data = array();
			foreach ($diff['new'] as $room_id) {
				$r_photo['photo_id']	= $photo['id'];
				$r_photo[$col_name] 	= $room_id;
				
				if(in_array($room_id, $room_main_photo_ids)) {
					$r_photo['is_main_photo'] 	= 1;
					$this->update_room_main_photo($r_photo, $photo['id'], $category);
				}
				
				$data[] = $r_photo;
			}
			$this->db->insert_batch('room_photos', $data);
		}
			
		// remove room photos
		if(!empty($diff['removed'])) {
			$this->delete_room_photo($photo['id'], $diff['removed']);
		}
		
		// update current
		if(!empty($diff['no_change'])) {
			$data = array();
			foreach ($diff['no_change'] as $room_id) {
				$r_photo = array();
				$r_photo['photo_id']	= $photo['id'];
				$r_photo[$col_name] 	= $room_id;
				
				$update_photo = array();
				if(in_array($room_id, $room_main_photo_ids)) {
					$update_photo['is_main_photo'] 	= 1;
					$this->update_room_main_photo($r_photo, $photo['id'], $category);
				} else {
					$update_photo['is_main_photo'] 	= 0;
				}
				
				$this->db->update('room_photos', $update_photo, $r_photo);
			}
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_room_photo($photo_id, $category, $room_ids = null) {
		
		$this->db->where('photo_id', $photo_id);
		
		$col_name = $this->_get_col_name($category);
		
		if(!empty($room_ids)) {
			$this->db->where_in($col_name, $room_ids);
		}
		
		$this->db->delete('room_photos');
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_room_photos($category, $room_id = null, $photo_id = null) {
		if(empty($room_id) && empty($photo_id)) {
			return FALSE;
		}
		
		if(!empty($room_id)) {
			
			switch ($category) {
				case PHOTO_HOTEL:
					$this->db->where('room_id', $this->db->escape_str($room_id));
					break;
				case PHOTO_CRUISE:
					$this->db->where('cabin_id', $this->db->escape_str($room_id));
					break;
			}
		}
		
		if(!empty($photo_id)) {
			$this->db->where('photo_id', $this->db->escape_str($photo_id));
		}
		
		$query = $this->db->get('room_photos');
		
		return $query->result_array();
	}
	
	function _get_array_different($cr_arr, $new_arr) {
	
		$new_ele = array();
		$removed_ele = array();
		$no_change = array();
		
		foreach ($cr_arr as $ele) {
			if(!in_array($ele, $new_arr)) {
				$removed_ele[] = $ele;
			} else {
				$no_change[] = $ele;
			}
		}
		
		foreach ($new_arr as $ele) {
			if(!in_array($ele, $cr_arr)) {
				$new_ele[] = $ele;
			}
		}
		
		return array('new' => $new_ele, 'removed' => $removed_ele, 'no_change' => $no_change);
	}
	
	function update_hotel_main_photo($photo, $category) {
		
		$col_name = '';
		
		switch ($category) {
			case PHOTO_HOTEL:
				$col_name = 'hotel_id';
				break;
			case PHOTO_CRUISE:
				$col_name = 'cruise_id';
				break;
			case PHOTO_TOUR:
				$col_name = 'tour_id';
				break;
		}
		
		$this->db->trans_start();
		
		// update photos table
		$this->db->select('id, type, '. $col_name);
		
		$this->db->where($col_name, $this->db->escape_str($photo[$col_name]));
		$this->db->where('type', 2);
		$this->db->where('id !=', $photo['id']);
		
		$query = $this->db->get('photos');
		
		$results = $query->result_array();
		
		if(!empty($results)) {
			// move old hotel main photo to gallery
			$updated_photo = $results[0];
			$updated_photo['type'] = 1;
			
			$this->db->update('photos', $updated_photo, array('id' => $updated_photo['id']));
		}
		
		// update hotels table
		$r_photo = $this->get_photo($photo['id']);
		
		if ($category == PHOTO_HOTEL) {
			
			$hotel = array('picture' => $r_photo['name']);
			
			$this->db->update('hotels', $hotel, array('id' => $photo[$col_name]));
			
		} elseif ($category == PHOTO_CRUISE) {
			
			$cruise = array('picture' => $r_photo['name']);
			
			$this->db->update('cruises', $cruise, array('id' => $photo[$col_name]));
			
		} elseif ($category == PHOTO_TOUR) {
			
			$tour = array('picture' => $r_photo['name']);
			
			$this->db->update('tours', $tour, array('id' => $photo[$col_name]));
			
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function update_tour_main_photo($photo) {
	
		$this->db->trans_start();
	
		// update photos table
		$this->db->select('id, tour_photo_type, tour_id');
	
		$this->db->where('tour_id', $this->db->escape_str($photo['tour_id']));
		$this->db->where('tour_photo_type', 2);
		$this->db->where('id !=', $photo['id']);
	
		$query = $this->db->get('photos');
	
		$results = $query->result_array();
	
		if(!empty($results)) {
			// move old tour main photo to gallery
			$updated_photo = $results[0];
			$updated_photo['tour_photo_type'] = 1;
				
			$this->db->update('photos', $updated_photo, array('id' => $updated_photo['id']));
		}
	
		// update tour table
		$r_photo = $this->get_photo($photo['id']);
		
		$photo_name = $r_photo['name'];
		if(!empty($r_photo['cruise_id'])) {
			$photo_name = '[cruise]'.$r_photo['name'];
		}
		
		$tour = array('picture' => $photo_name);
				
		$this->db->update('tours', $tour, array('id' => $photo['tour_id']));
	
		$this->db->trans_complete();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function update_room_main_photo($room_photo, $main_photo_id, $category) {
		
		$this->db->trans_start();
		
		$this->db->set('is_main_photo', 0);
		
		$col_name = $this->_get_col_name($category);
		
		$this->db->where($col_name, $room_photo[$col_name]);
		
		$this->db->update('room_photos');

		// update room_types or cabins table
		$r_photo = $this->get_photo($main_photo_id);
		
		if ($category == PHOTO_HOTEL) {
				
			$room_type = array('picture' => $r_photo['name']);
		
			$this->db->update('room_types', $room_type, array('id' => $room_photo[$col_name]));
				
		} elseif ($category == PHOTO_CRUISE) {
				
			$cabin = array('picture' => $r_photo['name']);
		
			$this->db->update('cabins', $cabin, array('id' => $room_photo[$col_name]));
				
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	
	// ------------------------------------------------------------------------
	
	/**
	 * Get number of photos by filter
	 * 
	 * @param unknown $hotel_id
	 * @param unknown $room_types
	 * @param unknown $category
	 * @return multitype:NULL
	 */
	function count_photo($hotel_id, $room_types, $category) {
		$photo_count = array();
		
		// count all
		$photo_count[] = $this->_get_number_of_photo($hotel_id, null, null, $category);
		
		// count gallery
		$photo_count[] = $this->_get_number_of_photo($hotel_id, 1, null, $category);
		
		// count room types
		foreach ($room_types as $room_type) {
			$photo_count[] = $this->_get_number_of_photo($hotel_id, 3, $room_type['id'], $category);
		}
		
		return $photo_count;
	}
	
	function _get_number_of_photo($object_id, $type = null, $room_id = null, $category) {
		$this->db->select('p.id');
		
		if(!empty($room_id)) {
			$this->db->join('room_photos rp', 'p.id = rp.photo_id', 'left outer');
		}
		
		$this->_setCriteria($category, $object_id, $type, $room_id);
		
		return $this->db->count_all_results('photos p');
	}
	
	
	// ------------------------------------------------------------------------
	
	/**
	 * Reorder photos
	 * 
	 * @param unknown $id
	 * @param unknown $action
	 * @param unknown $category
	 * @return boolean
	 */
	function re_order_photo($id, $action, $category) {
		
		$col_name = '';
		
		switch ($category) {
			case PHOTO_HOTEL:
				$col_name = 'hotel_id';
				break;
			case PHOTO_CRUISE:
				$col_name = 'cruise_id';
				break;
			case PHOTO_TOUR:
				$col_name = 'tour_id';
				break;
			case PHOTO_DESTINATION:
				$col_name = 'destination_id';
				break;
		}
		
		$this->db->trans_start();
		
		$table = 'photos';
		
		// get object position
		$object =$this->get_photo($id);
		
		// validation object and action
		if($object == FALSE || !($action == GO_UP || $action == GO_DOWN)) {
			return FALSE;
		}
		
		// get sibling
		$this->db->select('id, position');
		$this->db->where($col_name, $object[$col_name]);
		
		if ($action == GO_DOWN) {
			$this->db->where('position > ', $object['position']);
			$this->db->order_by('position', 'asc');
		} else if ($action == GO_UP) {
			$this->db->where('position < ', $object['position']);
			$this->db->order_by('position', 'desc');
		}
		
		$this->db->limit(1);
		
		$query = $this->db->get($table);
		$results = $query->result_array();
		
		//print_r($this->db->last_query());exit();
		
		if (count($results) > 0) {
			$sibling = $results[0];
		
			// re-order
			$updated_object = array('position' => $sibling['position']);
			$this->db->update($table, $updated_object, array('id' => $id));
		
			$updated_object = array('position' => $object['position']);
			$this->db->update($table, $updated_object, array('id' => $sibling['id']));
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	
	// ------------------------------------------------------------------------
	
	/**
	 * Set criteria for photos
	 * 
	 * @param unknown $category
	 * @param unknown $object_id
	 * @param string $type
	 * @param string $room_id : could be id of room or cabin, it depends on category of photo
	 */
	function _setCriteria($category, $object_id, $type = null, $room_id = null) {
		
		switch ($category) {
			case PHOTO_HOTEL:
				$this->db->where('hotel_id', $this->db->escape_str($object_id));
				
				if(!empty($room_id)) {
					$this->db->where('rp.room_id', $this->db->escape_str($room_id));
				}

				break;
				
			case PHOTO_CRUISE:
				$this->db->where('cruise_id', $this->db->escape_str($object_id));
				
				if(!empty($room_id)) {
					$this->db->where('rp.cabin_id', $this->db->escape_str($room_id));
				}
				
				break;
				
			case PHOTO_TOUR:
				$this->db->where('tour_id', $this->db->escape_str($object_id));
				break;
			case PHOTO_DESTINATION:
				$this->db->where('destination_id', $this->db->escape_str($object_id));
				break;
		}
		
		if(!empty($type)) {
			$this->db->where('p.type', $this->db->escape_str($type));
		}
		
		return;
	}
	
	function _get_col_name($category) {
		
		$col_name = '';
		
		switch ($category) {
			case PHOTO_HOTEL:
				$col_name = 'room_id';
				break;
			case PHOTO_CRUISE:
				$col_name = 'cabin_id';
				break;
		}
		
		return $col_name;
	}
	
	function update_tour_photo($photo) {
		
		$this->db->update('photos', $photo, array('id' => $photo['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
}

?>