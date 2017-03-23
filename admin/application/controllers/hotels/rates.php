<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rates extends BP_Controller {

	public function __construct()
    {
    
       	parent::__construct();	
		
		$this->load->helper(array('url','form','hotel','rate'));

		$this->load->model('Rate_Model');
		$this->load->language('rate');
		
		$this->load->library('form_validation');
		$this->load->config('rate_meta');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	
	public function index()
	{
		$data = $this->_set_common_data();
		
		$data = $this->_load_nav_menu($data, $data['hotel_id']);
		
		$data['search_criteria'] = $this->_build_search_criteria();
		
		$start_date = $data['search_criteria']['start_date'];
		$end_date = $data['search_criteria']['end_date'];
		
		$data['date_shows'] = get_date_rate_show($start_date);
		
		$data['month_shows'] = get_month_rate_show($start_date);
		
		$data['room_type_shows'] = $this->_get_room_type_show($data['room_types'], $data['search_criteria']);
		
		$room_type_ids = $this->_get_room_type_ids($data['room_type_shows']);
		
		$data['room_rates'] = $this->Rate_Model->get_room_rate_in_range($room_type_ids, $start_date, $end_date);
		
		$data['search_rate'] = $this->load->view('hotels/rates/search_rate', $data, TRUE);
		
		$data['next_back'] = $this->load->view('hotels/rates/next_back', $data, TRUE);
			
		$data['content'] = $this->load->view('hotels/rates/rate_control', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	
	public function _set_common_data($data = array()){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_HOTEL_RATE_AVAILABILITY);
		
		$hotel_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data['hotel_id'] = $hotel_id;
		
		$data = _get_hotel_data($data, $hotel_id);
		
		$data['room_types'] = $this->Rate_Model->get_room_types($hotel_id);
		
		$data['site_title'] = lang('tile_rate_control');
		
		$data = get_library('datepicker', $data);
		
		$data = get_library('mask', $data);
		
		$data['week_days'] = $this->config->item('week_days');
	
		
		return $data;
	}
	

	public function _build_search_criteria(){
		
		$submit_action = $this->input->post('submit_action');
		
		// access the Rate Control tab without search action
		if(empty($submit_action)){			
			
			$search_criteria = $this->session->userdata(HOTEL_RATE_SEARCH_CRITERIA);

			if(empty($search_criteria)){
				
				$search_criteria['start_date'] = date(DATE_FORMAT);				
				
				$search_criteria['end_date'] = get_end_date_show_rates($search_criteria['start_date']);
			}
			
		} else {
			
			$search_criteria = $this->session->userdata(HOTEL_RATE_SEARCH_CRITERIA);
			
			if($submit_action == ACTION_RESET){
				
				$search_criteria = array();
				
			} 
			
			if($submit_action == ACTION_SEARCH){
				
				$search_criteria['start_date'] = $this->input->post('start_date');
				
				$search_criteria['end_date'] = get_end_date_show_rates($search_criteria['start_date']);
				
				$search_criteria['room_type'] = $this->input->post('room_type');
				
			}
			
			if($submit_action == ACTION_NEXT || $submit_action == ACTION_BACK){
				
				$search_criteria['start_date'] = get_next_rate_date($search_criteria['start_date'], $submit_action);
				
				$search_criteria['end_date'] = get_end_date_show_rates($search_criteria['start_date']);
			}
		
			
			$this->session->set_userdata(HOTEL_RATE_SEARCH_CRITERIA, $search_criteria);
			
		}
		
		
		return $search_criteria;
	}
	
	function change_room_rates(){
		
		$data = $this->_set_common_data();
		
		$data = $this->_load_nav_menu($data, $data['hotel_id'], 1);
		
		$data['names'] = get_room_names_by_type($data['room_types']);
		
		$action = $this->input->post('submit_action');
		
		if($action == ACTION_SAVE){
			
			$save_staus = $this->_change_room_rates($data['hotel_id']);
			
			if($save_staus){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('rate_change_sucessful'));
				redirect(site_url('hotels/rates/'.$data['hotel_id']).'/');
			}
			
		}
		
		
		$data['site_title'] = lang('change_room_rate');
			
		$data['content'] = $this->load->view('hotels/rates/change_room_rate', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
		
	}
	
	function _change_room_rates($hotel_id){
		
		if($this->_validate_change_rate()){
			
			$change_rates = $this->_get_change_rate_post_data();
			
			$this->Rate_Model->change_room_rates($hotel_id, $change_rates);
			
			return TRUE;
		}
		
		return FALSE;
		
	}
	
	function _get_change_rate_post_data(){
		
		$change_rates['week_day'] = $this->input->post('week_day');
		
		$change_rates['room_types'] = $this->input->post('room_types');
		
		$change_rates['start_date'] = $this->input->post('start_date');
		
		$change_rates['end_date'] = $this->input->post('end_date');
		
		$change_rates['full_occupancy'] = format_rate_input($this->input->post('full_occupancy'));
		
		$change_rates['triple'] = format_rate_input($this->input->post('triple'));
		
		$change_rates['double'] = format_rate_input($this->input->post('double'));
		
		$change_rates['single'] = format_rate_input($this->input->post('single'));
		
		$change_rates['extra_bed'] = format_rate_input($this->input->post('extra_bed'));
		
		return $change_rates;
	}

	
	public function _load_nav_menu($data, $id, $mnu_index = 0){
		
		$nav_panel = $this->config->item('rate_nav_panel');
		
		foreach ($nav_panel as $key => $value){
			
			$value['link'] .= $id.'/';
			
			$nav_panel[$key] = $value;
			
		}
		
		$data['side_mnu_index'] = $mnu_index;
		
		$data['nav_panel'] = $nav_panel;
		
		return $data;
	}
	
	public function _get_room_type_show($room_types, $search_criteria){
		
		if(empty($search_criteria['room_type'])){
			
			return $room_types;
			
		} else {
			
			$ret = array();
			
			foreach ($room_types as $value){
				
				if($value['id'] == $search_criteria['room_type']){
					
					$ret[] = $value;
					
					return $ret;
					
				}
				
			}
			
		}
		
	}
	
	
	public function _set_change_rate_validation_rules()
	{
		$this->form_validation->set_error_delimiters('<p class="error">', '</p>');
		$this->form_validation->set_rules($this->config->item('change_room_rate'));
	}
	
	public function _validate_change_rate()
	{
		$this->_set_change_rate_validation_rules();
		
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;

	}
	
	public function rate_date_check($str)
	{
		
		$ret = FALSE;
		
		if (substr_count($str, '-') == 2) {
	        list($d, $m, $y) = explode('-', $str);
	        $ret =  checkdate($m, $d, sprintf('%04u', $y));
	    }
	    
	    // check format
	    if ($ret === FALSE){
	    	$this->form_validation->set_message('rate_date_check', lang('rate_date_valid_format'));
	    } else {
	    	// check endate > start date
	    	
	    	$start_date = $this->input->post('start_date');
	    	
	    	$end_date = $this->input->post('end_date');
	    	
	    	$start_date = strtotime($start_date);
	    	
	    	$end_date = strtotime($end_date);
	    	
	    	if($start_date > $end_date){
	    		
	    		$ret = FALSE;
	    		
	    		$this->form_validation->set_message('rate_date_check', lang('rate_date_valid_value'));
	    	}
	    }
	
	    return $ret;
	}
	
	function _get_room_type_ids($room_types){
		
		$ret = array();
		
		foreach ($room_types as $value){
			
			$ret[] = $value['id'];
			
		}
		
		return $ret;
	}
	
	function show_surcharge_info(){
		$this->load->language('surcharge');
		$this->load->config('surcharge_meta');
		
		$hotel_id = $this->input->post('hotel_id');
		
		$date = $this->input->post('date');
		
		$surcharges = $this->Rate_Model->get_hotel_surcharge_in_date($hotel_id, $date);
		
		$data['surcharges'] = $surcharges;
		$data['charge_types'] = $this->config->item('charge_types');
		$data['week_days'] = $this->config->item('week_days');
		
		$surcharge_info_view = $this->load->view('hotels/rates/surcharge_info', $data, TRUE);
		
		echo $surcharge_info_view;
		
	}
	
	function room_rate_action(){
		
		$data = $this->_set_common_data();
		
		$data = $this->_load_nav_menu($data, $data['hotel_id'], 2);
		
		$hotel_rate_actions = $this->Rate_Model->get_all_hr_actions($data['hotel_id']);
		
		$data['hotel_rate_actions'] = $hotel_rate_actions;
		
		$data['site_title'] = lang('rate_mnu_room_rate_actions');
			
		$data['content'] = $this->load->view('hotels/rates/list_room_rate_actions', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
		
	}
	
	function create_room_rate_action(){
		
		$data = $this->_set_common_data();
		
		$data = $this->_load_nav_menu($data, $data['hotel_id'], 2);
		
		$action = $this->input->post('action');
		
		$hotel_id = isset($data['hotel_id']) ? $data['hotel_id'] : 0;
		
		$room_types = $data['room_types'];
		
		if($action == 'save'){
				
			$save_status = $this->_save_hr_action(false, $room_types);
		
			if($save_status){
		
				$this->session->set_flashdata(ACTION_MESSAGE, lang('create_successful'));
		
				redirect(site_url('hotels/room-rate-action/'.$hotel_id).'/');
		
			} else {
		
				$data['save_status'] = $save_status;
		
			}
		}
		
		
		$data['site_title'] = lang('create_room_rate_action');
			
		$data['content'] = $this->load->view('hotels/rates/create_room_rate_action', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	public function edit_room_rate_action(){
	
		$data = $this->_set_common_data();
		
		$data = $this->_load_nav_menu($data, $data['hotel_id'], 2);
		
		$room_types = $data['room_types'];
	
		$action = $this->input->post('action');
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
	
		$hotel_id = isset($data['hotel_id']) ? $data['hotel_id'] : 0;
	
		$hra = $this->Rate_Model->get_hra($id);
	
		if($hra !== FALSE){
				
			$data['hra'] = $hra;
				
		}
	
		if($action == 'save'){
				
			$save_status = $this->_save_hr_action(true, $room_types);
	
			if($save_status === TRUE){
	
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				redirect(site_url('hotels/room-rate-action/'.$hotel_id).'/');
	
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}

		$data['content'] = $this->load->view('hotels/rates/edit_room_rate_action', $data, TRUE);
		$this->load->view('_templates/template', $data);
	
	}
	
	public function delete_room_rate_action(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
	
		$hotel_id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
		$status = $this->Rate_Model->delete_hra($id);
	
		if($status){
				
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
				
		} else {
				
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
	
		redirect(site_url('hotels/room-rate-action/'.$hotel_id).'/');
	}
	
	public function _save_hr_action($is_edit = false, $room_types = array()){
	
		if($this->_validate($room_types)){
				
			$hr_action = $this->_get_post_data($room_types);
				
			if($is_edit){
	
				$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
				
				$save_status = $this->Rate_Model->update_hr_action($id, $hr_action);
	
			} else {
	
				$hotel_id = $this->uri->segment(NORMAL_ID_SEGMENT);
	
				$hr_action['hotel_id'] = $hotel_id;
	
				$save_status = $this->Rate_Model->create_hr_action($hr_action);	
	
			}
				
			return $save_status;
				
				
		}
	
		return NULL;
	
	}
	
	
	public function _get_post_data($room_types){
		
		$hotel_id = $this->uri->segment(NORMAL_ID_SEGMENT);

		$hra['start_date'] = $this->input->post('start_date');
	
		$hra['start_date'] = date(DB_DATE_FORMAT, strtotime($hra['start_date']));
	
		$hra['end_date'] = $this->input->post('end_date');
	
		$hra['end_date'] = date(DB_DATE_FORMAT, strtotime($hra['end_date']));
	
		$week_day = $this->input->post('week_day');
	
		$week_day_nr = calculate_list_value_to_bit($week_day);
	
		$hra['week_day'] = $week_day_nr;
		
		$hra['hotel_id'] = $hotel_id;
		
		$hra['rras'] = array();
		
		foreach ($room_types as $value){
			$rra['full_occupancy_rate'] = format_rate_input($this->input->post('rr_full_occupancy_'.$value['id']));
			$rra['triple_rate'] = format_rate_input($this->input->post('rr_triple_'.$value['id']));
			$rra['double_rate'] = format_rate_input($this->input->post('rr_double_'.$value['id']));
			$rra['single_rate'] = format_rate_input($this->input->post('rr_single_'.$value['id']));
			$rra['extra_bed_rate'] = format_rate_input($this->input->post('rr_extra_bed_'.$value['id']));
			
			
			$rra['full_occupancy_net'] = format_rate_input($this->input->post('net_rr_full_occupancy_'.$value['id']));
			$rra['triple_net'] = format_rate_input($this->input->post('net_rr_triple_'.$value['id']));
			$rra['double_net'] = format_rate_input($this->input->post('net_rr_double_'.$value['id']));
			$rra['single_net'] = format_rate_input($this->input->post('net_rr_single_'.$value['id']));
			
			$rra['room_type_id'] = $value['id'];
	
			$rra['hotel_id'] = $hotel_id;
			
			$hra['rras'][] = $rra;
		}
			
		return $hra;
	}

	
	public function _validate($room_types)
	{
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_rules($this->config->item('hotel_rate_action'));
		
		foreach ($room_types as $value){
			$this->form_validation->set_rules('rr_full_occupancy_'.$value['id'], 'Full Occupancy Rate','');
			$this->form_validation->set_rules('rr_triple_'.$value['id'], 'Triple Rate','');
			$this->form_validation->set_rules('rr_double_'.$value['id'], 'Double Rate','');
			$this->form_validation->set_rules('rr_single_'.$value['id'], 'Single Rate','');
			$this->form_validation->set_rules('rr_extra_bed_'.$value['id'], 'Extra-bed Rate','');
		}
	
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;
	
	}
	
	public function rra_date_check($str)
	{
	
		$ret = FALSE;
	
		if (substr_count($str, '-') == 2) {
			list($d, $m, $y) = explode('-', $str);
			$ret =  checkdate($m, $d, sprintf('%04u', $y));
		}
		 
		// check format
		if ($ret === FALSE){
			$this->form_validation->set_message('rra_date_check', lang('rra_date_valid_format'));
		} else {
			// check endate > start date
	
			$start_date = $this->input->post('start_date');
	
			$end_date = $this->input->post('end_date');
	
			$start_date = strtotime($start_date);
	
			$end_date = strtotime($end_date);
	
			if($start_date > $end_date){
		   
				$ret = FALSE;
		   
				$this->form_validation->set_message('rra_date_check', lang('rra_date_valid_value'));
			}
		}
	
		return $ret;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/Promotion.php */