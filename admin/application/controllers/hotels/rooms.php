<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rooms extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('Hotel_Model');
		$this->load->model('Room_Type_Model');
		$this->load->model('Facility_Model');
		
		$this->load->helper('hotel');
		$this->load->helper('search');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->load->language('hotel');
		
		$this->config->load('room_meta');
		$this->config->load('hotel_meta');
	}

	public function index()
	{
		$data = _get_hotel_data();
		$data = _get_navigation($data, 5, MNU_HOTEL_PROFILE);
		
		$data = $this->_get_list_data($data);
		
		// render view
		$data['site_title'] = lang('hotel_rooms_title');
		
		_render_view('hotels/rooms/list_rooms', $data);
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_ROOM_TYPES, $data);
	
		$search_criteria = $data['search_criteria'];
		
		// set hotel id
		$search_criteria['hotel_id'] = $data['hotel']['id'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['room_types'] = $this->Room_Type_Model->search_room_types($search_criteria, $per_page, $offset);
		
		$total_rows = $this->Room_Type_Model->get_numb_room_types($search_criteria);
		
		$data = set_paging_info($data, $total_rows, 'hotels/rooms/');
		
		$data = set_max_min_pos($data, MODULE_ROOM_TYPES);
	
		return $data;
	}
	
	// activate the hotel
	function activate()
	{
		$this->_set_room_type_status(STATUS_ACTIVE);
	}
	
	// deactivate the hotel
	function deactivate()
	{
		$this->_set_room_type_status(STATUS_INACTIVE);
	}
	
	function _set_room_type_status($status) {
		$data = $this->_get_room_type();
	
		$room_type = array(
				'id'			=> $data['room_type']['id'],
				'status'     	=> $status,
		);
	
		$save_status = $this->Room_Type_Model->update_room_type($room_type);
	
		if ($save_status)
		{
			$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
			redirect("hotels/rooms/".$data['room_type']['hotel_id']);
		} else {
			if(!is_null($save_status)){
				$data['save_status'] = $save_status;
			}
		}
	}
	
	// create a new room
	public function create(){
		$data = _get_hotel_data();
		$data = _get_navigation($data, 5, MNU_HOTEL_PROFILE);
		
		$room_config = $this->config->item('create_room_type');
		$this->form_validation->set_rules($room_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
			
			$b_config = $this->input->post('bed_config');
			
			$facilities = $this->input->post('facilities');
	
			$room_type = array(
					'name'				=> trim($this->input->post('name')),
					'description'  		=> trim($this->input->post('description')),
					'minimum_room_size' => trim($this->input->post('room_size')),
					'view_id'  			=> trim($this->input->post('view_id')),
					'bed_config'  		=> calculate_list_value_to_bit($b_config),
					'facilities'  		=> _array_to_string($facilities),
					'hotel_id'  		=> $data['hotel']['id'],
			);
	
			$save_status = $this->Room_Type_Model->create_room_type($room_type);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("hotels/rooms/".$data['hotel']['id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
	
		// render view
		$data['site_title'] = lang('create_room_type_title');
		
		$data['room_views'] = $this->config->item('room_views');
		
		$data['bed_configuration'] = $this->config->item('bed_configuration');
		
		$data['facilities'] = $this->Facility_Model->get_facilities_by_type(null, null, FACILITY_ROOM_TYPE);
		
		$data = get_library('tinymce', $data);
	
		_render_view('hotels/rooms/create_room', $data);
	}
	
	// edit the room type
	public function edit(){
	
		$data = $this->_get_room_type();
		
		$room_config = $this->config->item('create_room_type');
		$this->form_validation->set_rules($room_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
	
			$b_config = $this->input->post('bed_config');
			
			$facilities = $this->input->post('facilities');
	
			$room_type = array(
					'id'				=> $data['room_type']['id'],
					'name'				=> trim($this->input->post('name')),
					'description'  		=> trim($this->input->post('description')),
					'minimum_room_size' => trim($this->input->post('room_size')),
					'view_id'  			=> trim($this->input->post('view_id')),
					'bed_config'  		=> calculate_list_value_to_bit($b_config),
					'facilities'  		=> _array_to_string($facilities),
					'status'  			=> trim($this->input->post('status')),
			);
	
			$save_status = $this->Room_Type_Model->update_room_type($room_type);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("hotels/rooms/".$data['room_type']['hotel_id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['status_config'] = $this->config->item('status_config');
	
		// render view
		$data['site_title'] = lang('edit_room_type_title');
		
		$data = get_library('tinymce', $data);
	
		_render_view('hotels/rooms/edit_room', $data);
	}
	
	function roomname_check()
	{
		$hotel_id = trim($this->input->post('hotel_id'));
		$room_name = trim($this->input->post('name'));
		$room_id = trim($this->input->post('room_id'));
		
		$is_unique = $this->Room_Type_Model->is_unique_room_name($hotel_id, $room_name, $room_id);
		
		if (!$is_unique)
		{
			$this->form_validation->set_message('roomname_check', lang('room_name_is_unique'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function _get_room_type($data = array()) {
		
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
		
		$room_type = $this->Room_Type_Model->get_room_type($id);
		if ($room_type == false) {
			_show_error_page(lang('hotel_notfound'));
			exit;
		}
		
		$data['room_type'] = $room_type;
		
		$data['room_views'] = $this->config->item('room_views');
		
		$data['bed_configuration'] = $this->config->item('bed_configuration');
		
		$data['facilities'] = $this->Facility_Model->get_facilities_by_type(null, null, FACILITY_ROOM_TYPE);
		
		$data = _get_hotel_data($data, $room_type['hotel_id']);
		$data = _get_navigation($data, 5, MNU_HOTEL_PROFILE);
		
		return $data;
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data = $this->_get_room_type();
	
		$status = $this->Room_Type_Model->delete_room_type($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect("hotels/rooms/".$data['room_type']['hotel_id']);
	}
	
	function settings() {
		$data = $this->_get_room_type();
		
		$data['max_occupancy'] 	= $this->config->item('max_occupancy_on_existing_bedding');
		$data['max_extra_beds'] = $this->config->item('max_extra_beds');
		$data['max_children'] 	= $this->config->item('max_children');
		
		$room_config = $this->config->item('room_settings');
		$this->form_validation->set_rules($room_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_message('is_natural_no_zero', lang('is_natural_no_zero'));
		$this->form_validation->set_message('less_than', lang('less_than'));
		
		if ($this->form_validation->run() == true) {
		
			$room_type = array(
					'id'  				=> $data['room_type']['id'],
					'number_of_rooms'	=> trim($this->input->post('number_of_rooms')),
					'max_occupancy'  	=> trim($this->input->post('max_occupancy')),
					'max_extra_beds' 	=> trim($this->input->post('max_extra_beds')),
					'max_children'  	=> trim($this->input->post('max_children')),
			);
		
			$save_status = $this->Room_Type_Model->update_room_type($room_type);
		
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("hotels/rooms/".$data['room_type']['hotel_id']);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		// render view
		$data['site_title'] = lang('room_settings_type_title');
		
		_render_view('hotels/rooms/room_settings', $data);
	}
	
	function re_order() {
		if(isset($_GET["id"]) && isset($_GET["act"]) && isset($_GET["h_id"])) {
			$id 	= $_GET["id"];
			$action = $_GET["act"];
			$hotel_id = $_GET["h_id"];
	
			if(is_numeric($id) && is_numeric($hotel_id)) {
				
				$search_criteria = array();
				$search_criteria['hotel_id'] = $hotel_id;
	
				$status = bp_re_order($id, $action, MODULE_ROOM_TYPES, $search_criteria);
					
				if ($status)
				{
					$this->session->set_flashdata('message', lang('update_successful'));
					redirect("hotels/rooms/".$hotel_id);
				}
			}
				
			if(!is_null($status)){
				$data['save_status'] = $status;
			}
		}
	}
	
	function facilities_check() {
		
		$facilities = $this->input->post('facilities');
		
		$cnt = $this->Facility_Model->get_numb_facilities();
		
		if( !empty($cnt) && empty($facilities)) {
			$this->form_validation->set_message('facilities_check', lang('facilities_is_required'));
			return false;
		}
		
		return true;
	}
}
