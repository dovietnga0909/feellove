<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotels extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Hotel_Model');
		$this->load->model('Partner_Model');
		$this->load->model('Destination_Model');
		
		$this->load->language(array('hotel', 'partner'));
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->load->helper(array('search', 'hotel'));
		
		$this->config->load('hotel_meta');
	}

	public function index()
	{
		$this->session->set_userdata('MENU', MNU_HOTEL);
		
		$data['site_title'] = lang('list_hotel_title');

		$data = $this->_get_list_data($data);

		_render_view('hotels/cp/list_hotels', $data, 'hotels/cp/search_hotel');
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_HOTEL, $data);
	
		$search_criteria = $data['search_criteria'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['hotels'] = $this->Hotel_Model->search_hotels($search_criteria, $per_page, $offset);
	
		$data['hotel_star'] = $this->config->item('hotel_star');
		
		$data['status_config'] = $this->config->item('status_config');
		
		$data['hotel_destinations'] = $this->Hotel_Model->get_all_destinations();
		
		$data['partners'] = $this->Partner_Model->get_all_partners();
		
		$total_rows = $this->Hotel_Model->get_numb_hotels($search_criteria);
		
		$data = set_paging_info($data, $total_rows, URL_HOTEL);
		
		$data = set_max_min_pos($data, MODULE_HOTEL);
	
		return $data;
	}
	
	// activate the hotel
	function activate()
	{
		$this->_set_hotel_status(STATUS_ACTIVE);
	}
	
	// deactivate the hotel
	function deactivate()
	{
		$this->_set_hotel_status(STATUS_INACTIVE);
	}
	
	function _set_hotel_status($status) {
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
	
		$hotel = $this->Hotel_Model->get_hotel($id);
		if ($hotel == false) {
			_show_error_page(lang('hotel_notfound'));
			exit;
		}
	
		$hotel = array(
				'id'			=> $hotel['id'],
				'status'     	=> $status,
		);
	
		$save_status = $this->Hotel_Model->update_hotel($hotel);
	
		if ($save_status)
		{
			$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
			redirect("hotels");
		} else {
			if(!is_null($save_status)){
				$data['save_status'] = $save_status;
			}
		}
	}
	
	// create a new hotel
	public function create(){
		$hotel_config = $this->config->item('hotel_rules');
		$create_hotel_rules = $this->config->item('create_hotel_rules');
		$hotel_config = array_merge_recursive($hotel_config, $create_hotel_rules);
		$this->form_validation->set_rules($hotel_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
	
		if ($this->form_validation->run() == true) {
				
			$hotel = array(
					'name'			=> trim($this->input->post('name')),
					'url_title'		=> strtolower(trim($this->input->post('url_title'))),
					'address'    	=> trim($this->input->post('address')),
					'star'    		=> trim($this->input->post('star')),
					'partner_id'    => trim($this->input->post('partner_id')),
					'description'  	=> trim($this->input->post('description')),
					'destination_id'	=> trim($this->input->post('destination_id')),
			);
	
			$save_status = $this->Hotel_Model->create_hotel($hotel);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect("hotels");
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['hotel_star'] = $this->config->item('hotel_star');
		
		$data['partners'] = $this->Partner_Model->get_all_partners();
		
		$data['destinations'] = $this->Hotel_Model->get_all_destinations();
	
		// render view
		$data['site_title'] = lang('create_hotel_title');
		
		$data = get_library('tinymce', $data);
	
		_render_view('hotels/cp/create_hotel', $data);
	}
	
	function hotel_name_check($str)
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$is_exist = $this->Hotel_Model->is_unique_hotel_name($str, $id);
	
		if ($is_exist)
		{
			$this->form_validation->set_message('hotel_name_check', lang('hotel_name_is_unique'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function _get_hotel_data($data = array()){
	
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
	
		$hotel = $this->Hotel_Model->get_hotel($id);
	
		$data['hotel'] = $hotel;
	
		return $data;
	}
	
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$status = $this->Hotel_Model->delete_hotel($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect('hotels');
	}
	
	function re_order() {
		if(isset($_GET["id"]) && isset($_GET["act"])) {
			$id 	= $_GET["id"];
			$action = $_GET["act"];
				
			if(is_numeric($id)) {
		
				$status = bp_re_order($id, $action, MODULE_HOTEL);
					
				if ($status)
				{
					$this->session->set_flashdata('message', lang('edit_hotel_successful'));
					redirect("hotels");
				} 
			}
			
			if(!is_null($status)){
				$data['save_status'] = $status;
			}
		}
	}
	
	function clear_cache() {
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
		
		$hotel = $this->Hotel_Model->get_hotel($id);
		
		if(!empty($hotel)) {
			deleteCache($hotel['url_title'].'-'.$hotel['id'], CACHE_HOTEL_PAGE);
				
			message_alert('', 'Clear cache '.$hotel['name'].' completed!');
		}
	}
}
