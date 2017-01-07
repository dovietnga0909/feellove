<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promotions extends BP_Controller {

	public function __construct()
    {
    
       	parent::__construct();	
		
		$this->load->helper(array('url','form','promotion','cruise'));

		$this->load->model(array('Promotion_Model','Cancellation_Model'));
		$this->load->language('promotion');
		
		$this->load->library('form_validation');
		$this->load->library('pagination');	
		$this->load->config('promotion_meta');
	}
	
	
	public function index()
	{
		$data = $this->_set_common_data();
		
		$data = $this->_get_list_promotions($data);
		
		$data = $this->_set_paging_info($data);
		
		$data['search_frm'] = $this->load->view('cruises/promotions/search_promotion', $data, TRUE);
		
		$data['content'] = $this->load->view('cruises/promotions/list_promotion', $data, TRUE);
		
		$this->load->view('_templates/template', $data);
	}
	
	
	public function _set_common_data($data = array()){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_CRUISE_PROMOTION);
		
		$data['site_title'] = lang('title_promotions');
		
		$data = get_library('datepicker', $data);
		
		$cruise_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data['cruise_id'] = $cruise_id;
		
		$data = _get_cruise_data($data, $cruise_id);
		
		
		$data['promotion_types'] = $this->config->item('promotion_types');
		
		$data['week_days'] = $this->config->item('week_days');
		
		$data['night_limit'] = $this->config->item('promotion_night_limit');
		
		$data['discount_types'] = $this->config->item('discount_types');
		
		$data['apply_on'] = $this->config->item('apply_on');
		
		$data['room_limit'] = $this->config->item('pro_room_limit');
		
		$data['recurring_benefits'] = $this->config->item('pro_recurring_benefit');
		
		$data['apply_on_free_night'] = $this->config->item('apply_on_free_night');
		
		$data['pro_nights'] = $this->config->item('pro_nights');
		
		$data['pro_week_days'] = $this->config->item('pro_week_days');
		
		$data['pro_cruise_tours'] = $this->config->item('pro_cruise_tours');
	
		$data['cancellations'] = $this->Cancellation_Model->get_all_cancellations();
		
		$data['cruise_tours'] = $this->Promotion_Model->get_cruise_tours($cruise_id);
		
		
		$data = get_library('tinymce', $data);
		
		return $data;
	}
	
	public function _get_list_promotions($data = array()){
		
		$search_criteria = $this->_build_search_criteria();		
		
		$data['search_criteria'] = $search_criteria;
		
		$offset = (int)$this->uri->segment(PAGING_SEGMENT + 2);
		
		$per_page = $this->config->item('per_page');

		// for display correct order on the column # of table list
		$data['offset'] = $offset;
		
		$data['promotions'] = $this->Promotion_Model->search_promotion($search_criteria, $per_page, $offset);
	
		
		return $data;
	}
	
	public function _build_search_criteria(){
		
		$submit_action = $this->input->post('submit_action');
		
		$cruise_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		// access the Promotion tab without search action
		if(empty($submit_action)){			
			
			$search_criteria = $this->session->userdata(CRUISE_PROMOTION_SEARCH_CRITERIA.$cruise_id);

			if(empty($search_criteria)){
				
				$search_criteria = array();
				
			}
			
			$search_criteria['cruise_id'] = $cruise_id;
			
		} else {
			
			if($submit_action == ACTION_RESET){
				
				$search_criteria = array();
				
			} elseif($submit_action == ACTION_SEARCH){
				
				$search_criteria['start_date'] = $this->input->post('start_date');
				
				$search_criteria['end_date'] = $this->input->post('end_date');
				
				$search_criteria['promotion_type'] = $this->input->post('promotion_type');
				
				$search_criteria['show_on_web'] = $this->input->post('show_on_web');
			}
			
			$search_criteria['cruise_id'] = $cruise_id;
			
			$this->session->set_userdata(PROMOTION_SEARCH_CRITERIA.$cruise_id, $search_criteria);
			
		}
		
		
		return $search_criteria;
	}
	
	public function create($step){
		
		if(empty($step)){
			
			$this->session->unset_userdata(PROMOTION_CREATE_TEMP);
			
			$step = 1;
		}
		
		$data = $this->_set_common_data();
		$data['site_title'] = lang('pro_create_'.$step);
		
		$action = $this->input->post('action');
		
		$cruise_id = isset($data['cruise_id']) ? $data['cruise_id'] : 0;		
		
		
		if($action == 'next'){
			
			$next_status = $this->_next($step);

			if($next_status){
				redirect(site_url('cruises/promotions/'.$cruise_id).'/create/'.($step + 1).'/');
			}
		}

		$pro = $this->session->userdata(PROMOTION_CREATE_TEMP);
		
		if($action == 'save'){
			$pro['cruise_id'] = $cruise_id;
			
			$save_status = $this->Promotion_Model->create_promotion($pro);
			
			if($save_status){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('create_successful'));
				
				$this->session->unset_userdata(PROMOTION_CREATE_TEMP);
				
				redirect(site_url('cruises/promotions/'.$cruise_id).'/');
				
			} else {
				
				$data['save_status'] = $save_status;
				
			}
		}
		
		
		if(!empty($pro)){			
			$data['pro'] = $pro;
			
		}
		
		if($step > 1 && empty($pro)){
			redirect(site_url('cruises/promotions/'.$cruise_id).'/');
		}
		
		$data['current_step'] = $step;
		
		$data['pro_step'] = $this->load->view('cruises/promotions/step_promotion', $data, TRUE);
		
		$data = get_library('tinymce', $data);
		
		$data['content'] = $this->load->view('cruises/promotions/promotion_'.$step, $data, TRUE);		
		$this->load->view('_templates/template', $data);
	}
	
	
	public function edit($id, $step = 0){
		
		if($step == 0){ // first ennter edit, clear the previous temporary data
			
			$this->session->unset_userdata(PROMOTION_EDIT_TEMP.$id);
			
			$step = 1;
		}
		
		
		$data = $this->_set_common_data();
		$data['site_title'] = lang('pro_edit_'.$step);
		$data['edit_mode'] = 1;
		
		$action = $this->input->post('action');
		
		$cruise_id = isset($data['cruise_id']) ? $data['cruise_id'] : 0;		
		
		if($action == 'next'){
			
			$next_status = $this->_next($step, $id);

			if($next_status){
				redirect(site_url('cruises/promotions/'.$cruise_id).'/edit/'.$id.'/'.($step + 1).'/');
			}
		}
		
		$pro = $this->_get_promotion_for_edit($id);
		
		if($pro !== FALSE){
			
			$data['pro'] = $pro;
			
		}
		
		if($action == 'save'){
			
			$save_status = $this->Promotion_Model->update_promotion($id, $pro);
			
			if($save_status){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				
				$this->session->unset_userdata(PROMOTION_EDIT_TEMP.$id);
				
				redirect(site_url('cruises/promotions/'.$cruise_id).'/');
				
			} else {
				
				$data['save_status'] = $save_status;
				
			}
		}
		
		
		
		$data['current_step'] = $step;
		
		$data['pro_step'] = $this->load->view('cruises/promotions/step_promotion', $data, TRUE);
		
		$data = get_library('tinymce', $data);
		
		$data['content'] = $this->load->view('cruises/promotions/promotion_'.$step, $data, TRUE);		
		$this->load->view('_templates/template', $data);
	}
	
	public function view($id){
		
		$data = $this->_set_common_data();
		
		$data['site_title'] = lang('pro_view');
		
		$data['view_mode'] = 1;
		
		$pro = $this->Promotion_Model->get_promotion($id);
		
		if($pro !== FALSE){
			
			$data['pro'] = $pro;
			
		}
		
		$data['content'] = $this->load->view('cruises/promotions/promotion_5', $data, TRUE);		
		$this->load->view('_templates/template', $data);
		
	}
	
	public function _get_promotion_for_edit($id){
		
		// get from the session first
		$pro = $this->session->userdata(PROMOTION_EDIT_TEMP.$id);
		
		if(empty($pro)){
			$pro = $this->Promotion_Model->get_promotion($id);
		}
		
		return $pro;
	}
	
	
	public function delete(){
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
		
		$cruise_id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$status = $this->Promotion_Model->delete_promotion($id);
		
		if($status){
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
			
		} else {
			
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
		
		redirect(site_url('cruises/promotions/'.$cruise_id).'/');
	}
	
	public function _next($step, $id = ''){
		
		if($this->_validate($step, $id)){
			
			if($id == ''){ // create promotion
			
				$pro = $this->session->userdata(PROMOTION_CREATE_TEMP);
				
				$pro = empty($pro) ? array() : $pro;
				
				$pro = $this->_get_post_data($step, $pro);
				
				$this->session->set_userdata(PROMOTION_CREATE_TEMP, $pro);
				
			} else {
				
				$pro = $this->_get_promotion_for_edit($id);
				
				$pro = $this->_get_post_data($step, $pro);
				
				$this->session->set_userdata(PROMOTION_EDIT_TEMP.$id, $pro);
				
			}
			
			return TRUE;
		}
		
		return FALSE;
		
	}
	
	
	public function _get_post_data($step, $pro = array()){
		
		if(isset($pro['step']) && $pro['step'] > $step){
			// do nothing
		} else {
			$pro['step'] = $step + 1;
		}
		
		if($step == 1){ // step 1: promotion type
		
			$pro['name'] = $this->input->post('name');
			
			$pro['promotion_type'] = $this->input->post('promotion_type');
			
			$pro['show_on_web'] = $this->input->post('show_on_web');
			
			$pro['offer'] = $this->input->post('offer');
			
		}

		if($step == 2){
			
			$day_before_check_in = $this->input->post('day_before_check_in');
			
			if($day_before_check_in != ''){
				
				$pro['day_before_check_in'] = $day_before_check_in;
				
			}
			
			//$pro['minimum_stay'] = $this->input->post('minimum_stay');
												
			$pro['book_date_from'] = $this->input->post('book_date_from');			
			$pro['book_date_to'] = $this->input->post('book_date_to');
			
			$pro['book_date_from'] = date(DB_DATE_FORMAT, strtotime($pro['book_date_from']));
			$pro['book_date_to'] = date(DB_DATE_FORMAT, strtotime($pro['book_date_to']));
			
			$pro['stay_date_from'] = $this->input->post('stay_date_from');			
			$pro['stay_date_to'] = $this->input->post('stay_date_to');
			
			$pro['stay_date_from'] = date(DB_DATE_FORMAT, strtotime($pro['stay_date_from']));
			$pro['stay_date_to'] = date(DB_DATE_FORMAT, strtotime($pro['stay_date_to']));
			
			
			$display_on = $this->input->post('display_on');		
			$display_on = calculate_list_value_to_bit($display_on);
			$pro['display_on'] = $display_on;
			
			$check_in_on = $this->input->post('check_in_on');		
			$check_in_on = calculate_list_value_to_bit($check_in_on);
			$pro['check_in_on'] = $check_in_on;
			
			$pro['book_time_from'] = $this->input->post('book_time_from');
			$pro['book_time_to'] = $this->input->post('book_time_to');
			
			
			//$pro['maximum_stay'] = $this->input->post('maximum_stay');
			
		}
		
		if($step == 3){
			
			$pro['discount_type'] = $this->input->post('discount_type');
			$pro['apply_on'] = $this->input->post('apply_on');
			
			$pro['get_1'] = $this->input->post('get_1');
			
			/*
			$pro['minimum_room'] = $this->input->post('minimum_room');
			$pro['recurring_benefit'] = $this->input->post('recurring_benefit');
			
			$apply_on_free_night = $this->input->post('apply_on_free_night');
			
			if($pro['discount_type'] == 4){
				
				$pro['apply_on'] = $apply_on_free_night;
				
			}
			
			for ($i = 1; $i <= 7; $i++){				
				$pro['get_'.$i] = $this->input->post('get_'.$i);
			}*/
			
			
		}
		
		if($step == 4){
			
			$pro['room_type'] = $this->input->post('room_type');			
			$pro['cancellation_id'] = $this->input->post('cancellation_policy');
			
			$pro_tours = $this->input->post('pro_tours');
			
			if(!empty($pro_tours)){
				
				$p_tour_array = array();
				
				foreach ($pro_tours as $value){		
								
					$offer_note = $this->input->post('offer_'.$value);
					
					$get = $this->input->post('get_'.$value);
					
					$p_tour['tour_id'] = $value;					
					$p_tour['offer_note'] = $offer_note;
					
					$accs = $this->input->post('tour_acc_' . $value);
					
					if(!empty($accs)){
						
						$tour_pro_deatails_arr = array();
						
						foreach ($accs as $acc_id){
							
							$offer_note = $this->input->post('offer_'.$value.'_'.$acc_id);
							$get = $this->input->post('get_'.$value.'_'.$acc_id);
								
							$tour_pro_detail['tour_id'] = $value;
							$tour_pro_detail['accommodation_id'] = $acc_id;
							$tour_pro_detail['offer_note'] = $offer_note; 
							$tour_pro_detail['get'] = $get;
							
							$tour_pro_deatails_arr[] = $tour_pro_detail;
							
						}
						
						$p_tour['tour_pro_details'] = $tour_pro_deatails_arr;
						
					}
					
					
					
					$p_tour_array[] = $p_tour;
				}
				
				$pro['pro_tours'] = $p_tour_array;
				
			}
			
		}
		
			
		return $pro;
	}
	
	public function _set_validation_rules($step)
	{
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_rules($this->config->item('promotion_'.$step));
	}
	
	public function _validate($step, $id='')
	{
		$this->_set_validation_rules($step);
		
		if($step == 2){
			
			if($id == ''){
			
				$pro = $this->session->userdata(PROMOTION_CREATE_TEMP);
			
			} else {
				
				$pro = $this->_get_promotion_for_edit($id);
				
			}
			
			if(isset($pro['promotion_type']) && $pro['promotion_type'] == PROMOTION_TYPE_EARLY_BIRD){
				$this->form_validation->set_rules('day_before_check_in', lang('pro_field_minimum_day_before_check_in'), 'required|is_natural_no_zero');;
			}
			
			if(isset($pro['promotion_type']) && $pro['promotion_type'] == PROMOTION_TYPE_LAST_MINUTE){
				$this->form_validation->set_rules('day_before_check_in', lang('pro_field_maximum_day_before_check_in'), 'required|is_natural_no_zero');;
			}
			
		}
		
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;

	}
	
	public function _set_paging_info($data = array()){
		
		$search_criteria = isset($data['search_criteria'])? $data['search_criteria'] : array();
		
		$cruise_id = isset($data['cruise_id']) ? $data['cruise_id'] : 0;
		
		$total_rows = $this->Promotion_Model->count_total_promotion($search_criteria);
		
		$offset = $this->uri->segment(PAGING_SEGMENT + 2);
		
		$paging_config = get_paging_config($total_rows,'/cruises/promotions/'.$cruise_id.'/',PAGING_SEGMENT + 2);		
		// initialize pagination
		$this->pagination->initialize($paging_config);
		
		$paging_info['paging_text'] = get_paging_text($total_rows, $offset);
		
		$paging_info['paging_links'] = $this->pagination->create_links();
		
		$data['paging_info'] = $paging_info;
		
		return $data;
		
	}
	
	public function pro_name_check($str)
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT + 2);
		
		$is_exist = $this->Promotion_Model->is_pro_name_exist($str, $id);
		
		if ($is_exist)
		{
			$this->form_validation->set_message('pro_name_check', lang('promotion_name_exist'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function book_date_check($str)
	{
		
		$ret = FALSE;
		
		if (substr_count($str, '-') == 2) {
	        list($d, $m, $y) = explode('-', $str);
	        $ret =  checkdate($m, $d, sprintf('%04u', $y));
	    }
	    
	    // check format
	    if ($ret === FALSE){
	    	$this->form_validation->set_message('book_date_check', lang('pro_date_valid_format'));
	    } else {
	    	// check endate > start date
	    	
	    	$start_date = $this->input->post('book_date_from');
	    	
	    	$end_date = $this->input->post('book_date_to');
	    	
	    	$start_date = strtotime($start_date);
	    	
	    	$end_date = strtotime($end_date);
	    	
	    	if($start_date > $end_date){
	    		
	    		$ret = FALSE;
	    		
	    		$this->form_validation->set_message('book_date_check', lang('pro_book_date_valid_value'));
	    	}
	    }
	
	    return $ret;
	}
	
	public function stay_date_check($str)
	{
		
		$ret = FALSE;
		
		if (substr_count($str, '-') == 2) {
	        list($d, $m, $y) = explode('-', $str);
	        $ret =  checkdate($m, $d, sprintf('%04u', $y));
	    }
	    
	    // check format
	    if ($ret === FALSE){
	    	$this->form_validation->set_message('stay_date_check', lang('pro_date_valid_format'));
	    } else {
	    	// check endate > start date
	    	
	    	$start_date = $this->input->post('stay_date_from');
	    	
	    	$end_date = $this->input->post('stay_date_to');
	    	
	    	$start_date = strtotime($start_date);
	    	
	    	$end_date = strtotime($end_date);
	    	
	    	if($start_date > $end_date){
	    		
	    		$ret = FALSE;
	    		
	    		$this->form_validation->set_message('stay_date_check', lang('pro_stay_date_valid_value'));
	    	}
	    }
	
	    return $ret;
	}
	
	function run_update_pro(){
		
		$this->Promotion_Model->update_promotion_max_discount_from_room_types();
		
		echo 'promotion data updated!';
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/Promotion.php */