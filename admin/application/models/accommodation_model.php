<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accommodation_Model extends CI_Model{	

	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
		
		$this->load->model(array('Tour_Rate_Model'));
	}
	
	function get_numb_accommodations($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		
		return $this->db->count_all_results('accommodations a');
	}
	
	function search_accommodations($search_criteria = ''
		, $num, $offset
		, $order_field = 'position', $order_type = 'asc')
	{	
		//$this->benchmark->mark('code_start');
		
		$this->db->select('a.*, u.username as last_modified_by');
		
		$this->db->join('users u', 'a.user_modified_id = u.id', 'left outer');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('accommodations a', $num, $offset);
		
		$accommodations = $query->result_array();
	
		//$this->benchmark->mark('code_end');
		
		//echo $this->benchmark->elapsed_time('code_start', 'code_end');exit();
		
		return $accommodations;
	}

	public function _set_search_criteria($search_criteria = '')
	{
		$this->db->where('a.deleted !=', DELETED);
		
		if ($search_criteria == '')	{			
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				/*case 'search_text' :
					$searchStr =  $this->db->escape_like_str($value);
					$this->db->like('cb.name', $value, 'both');
					break;*/
				case 'tour_id' :
					$this->db->where('a.tour_id', $value);
					break;
			}
		}
	}
	
	/**
	 * create_accommodation
	 *
	 * @return bool
	 **/
	public function create_accommodation($accommodation)
	{
		$position = $this->get_max_position() + 1;
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'position'			=> $position,
		);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$accommodation_data = array_merge($accommodation, $additional_data);
	
		$this->db->insert('accommodations', $accommodation_data);
	
		$id = $this->db->insert_id();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_accommodation($id) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('accommodations');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{	
			$accommodation = $result[0];
			return $accommodation;
		}
	
		return FALSE;
	}
	
	function update_accommodation($accommodation) {

		$accommodation['user_modified_id'] 	= get_user_id();
		$accommodation['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
	
		$this->db->update('accommodations', $accommodation, array('id' => $accommodation['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_accommodation($id) {
		
		$accomm = $this->get_accommodation($id);
		
		if(!empty($accomm)) {
			
			// delete tour accommodations
			$accommodation['deleted'] = DELETED;
			
			$this->db->update('accommodations', $accommodation, array('id' => $id));
			
			// update tour price from
			
			$tour_rate_actions = $this->Tour_Rate_Model->get_all_hr_actions($accomm['tour_id']);
			
			foreach ($tour_rate_actions as $tour_rate) {
				
				$tour_rate['rras'] = $this->Tour_Rate_Model->get_rras($tour_rate['id']);
				
				foreach ($tour_rate['rras'] as $key => $value) {
					unset($value['id']);
					unset($value['tour_rate_action_id']);
					
					$tour_rate['rras'][$key] = $value;
				}
				
				$this->Tour_Rate_Model->update_tour_rate_action($tour_rate['id'], $tour_rate);
			}	
		}
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_accommodations($tour_id, $extra_info = false)
	{
		if( !$extra_info ) {
			$this->db->select('id, name');
		} else {
			$this->db->select('id, name, number_of_accommodations, max_extra_beds, max_occupancy, max_children, rack_rate, min_rate');
		}
		
		$this->db->where('tour_id', $tour_id);
		
		$this->db->where('deleted !=', DELETED);
	
		$this->db->order_by('position', 'asc');
		$query = $this->db->get('accommodations');
	
		return $query->result_array();
	}
	
	function _get_accommodation_main_photo($accommodation_id) {
		
		$photo_name = '';
		
		$this->db->select('p.name');
		
		$this->db->join('room_photos rp', 'rp.photo_id = p.id', 'left outer');
		
		$this->db->where('rp.accommodation_id', $accommodation_id);
		
		$this->db->where('rp.is_main_photo', 1);

		$this->db->limit(1);
		
		$query = $this->db->get('photos p');
		
		$photos = $query->result_array();
		
		if(!empty($photos)) {
			$photo_name = $photos[0]['name'];
		}
		
		return $photo_name;
	}
	
	function get_max_position($type = 0) {
	
		if($type == 0) {
			$this->db->select_max('position');
		} else {
			$this->db->select_min('position');
		}
	
		$query = $this->db->get('accommodations');
	
		$results = $query->result_array();
		if (!empty($results)) {
	
			return $results[0]['position'];
		}
	
		return 0;
	}
	
	function is_unique_accommodation_name($tour_id, $accommodation_name, $accommodation_id) {
		
		$this->db->where('tour_id', $tour_id);
		
		$this->db->where('name', $accommodation_name);
		
		if(!empty($accommodation_id)) {
			$this->db->where('id !=', $accommodation_id);
		}
		
		$this->db->limit(1);
		
		$query = $this->db->get('accommodations');
		
		$results = $query->result_array();
		
		if(!empty($results)) {
			return false;
		}
		
		return true;
	}
}

?>