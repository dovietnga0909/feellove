<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketings extends BP_Controller {

	public function __construct()
    {

       	parent::__construct();
		$this->load->helper(array('url','form'));

		$this->load->model(array('Marketing_Model','Hotel_Model','Destination_Model'));
		$this->load->language('marketing');

		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->config('marketing_meta');

		//$this->output->enable_profiler(TRUE);
	}


	public function index($offset=0)
	{
		$data = $this->_set_common_data();

		$data = $this->_load_nav_menu($data, 0);

		$data = $this->_get_list_promotions($offset, $data);

		$data = $this->_set_promotion_paging_info($offset, $data);

		$data['site_title'] = lang('title_promotions');

		$data['search_frm'] = $this->load->view('marketings/pro/search_pro', $data, TRUE);

		$data['content'] = $this->load->view('marketings/pro/list_pro', $data, TRUE);

		$this->load->view('_templates/template', $data);
	}

	public function vouchers($offset=0){

		$data = $this->_set_common_data();

		$data = $this->_load_nav_menu($data, 1);

		$data = $this->_get_list_vouchers($offset, $data);

		$data = $this->_set_voucher_paging_info($offset, $data);

		$data['site_title'] = lang('title_vouchers');

		$data['search_frm'] = $this->load->view('marketings/voucher/search_voucher', $data, TRUE);

		$data['content'] = $this->load->view('marketings/voucher/list_voucher', $data, TRUE);

		$this->load->view('_templates/template', $data);

	}

	public function create_voucher(){

		$data = $this->_set_common_data();

		$data = $this->_load_nav_menu($data, 1);

		$data['site_title'] = lang('create_voucher');

		$action = $this->input->post('action');

		if($action == ACTION_SAVE){

			$save_status = $this->_save_voucher();

			if($save_status){

				$this->session->set_flashdata(ACTION_MESSAGE, lang('create_successful'));

				redirect(site_url('marketings/vouchers').'/');

			} else {

				$data['save_status'] = $save_status;

			}
		}

		$data['content'] = $this->load->view('marketings/voucher/create_voucher', $data, TRUE);
		$this->load->view('_templates/template', $data);;
	}

	/**
	 * Generate the log based on comparing $old & $new voucher data
	 * Khuyenpv 03.10.2014
	 *
	 */
	function _generate_voucher_log($old_voucher, $new_voucher){

		$voucher_status = $this->config->item('voucher_status');
		$voucher_delivered = $this->config->item('voucher_delivered');

		$log = '';

		// for creating new voucher: the old-voucher is empty
		if($old_voucher == ''){

			$log = lang_arg('log_created_by', date(DATE_TIME_FORMAT), get_username());

			if(isset($voucher_status[$new_voucher['status']])){

				$log .= '<br/>'.lang('voucher_field_amount').': <b>'. number_format($new_voucher['amount']).'</b> VND';
			}

			$log .= '<br/>'.lang('field_expired_date').': <b>'. date(DATE_FORMAT, strtotime($new_voucher['expired_date'])) .'</b>';

		} else {


			$log = lang_arg('log_edit_by', date(DATE_TIME_FORMAT), get_username());

			if($new_voucher['status'] != $old_voucher['status']){

				$log .= '<br/>'.lang_arg('log_change_staus', $voucher_status[$old_voucher['status']], $voucher_status[$new_voucher['status']]);

			}

			if($new_voucher['amount'] != $old_voucher['amount']){
				$log .= '<br/>'.lang_arg('log_change_amount', $old_voucher['amount'], $new_voucher['amount']);
			}

			if($new_voucher['delivered'] != $old_voucher['delivered']){
				$log .= '<br/>'.lang_arg('log_change_delivered', $voucher_delivered[$old_voucher['delivered']], $voucher_delivered[$new_voucher['delivered']]);
			}

			if($new_voucher['expired_date'] != $old_voucher['delivered']){
				$log .= '<br/>'.lang_arg('log_change_expried_date', $old_voucher['expired_date'], $new_voucher['expired_date'] );
			}

			$log = $log.'<hr>'.$old_voucher['log'];

		}

		return $log;
	}

	public function show_log_voucher(){

		$id = $this->input->post('v_id');

		$voucher = $this->Marketing_Model->get_voucher($id);

		$data['voucher'] = $voucher;
		$data['id'] = $id;

		$this->load->view('marketings/voucher/voucher_log', $data);

	}

	public function edit_voucher($id){

		$data = $this->_set_common_data();

		$data = $this->_load_nav_menu($data, 1);

		$data['site_title'] = lang('edit_voucher');

		$voucher = $this->Marketing_Model->get_voucher($id);

		if($voucher !== FALSE){

			$data['voucher'] = $voucher;

		}

		$action = $this->input->post('action');

		/**
		 * Only save voucher by admin or status is not used
		 * Tinvm 28.09.2014
		 */


		if($action == ACTION_SAVE && (is_administrator()!== null || $voucher['status'] < VOUCHER_STATUS_USED)){

			$save_status = $this->_save_voucher($id, $voucher);

			if($save_status){


				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));

				redirect(site_url('marketings/vouchers').'/');

			} else {

				$data['save_status'] = $save_status;

			}

		}


		$data['content'] = $this->load->view('marketings/voucher/edit_voucher', $data, TRUE);
		$this->load->view('_templates/template', $data);
	}

	public function delete_voucher($id){


		$status = $this->Marketing_Model->delete_voucher($id);

		if($status){

			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));

		} else {

			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}

		redirect(site_url('marketings/vouchers').'/');
	}


	public function create_pro(){

		$data = $this->_set_common_data();

		$data = get_library('tinymce', $data);

		$data = $this->_load_nav_menu($data, 0);

		$data['site_title'] = lang('create_promotion');

		$action = $this->input->post('action');

		if($action == ACTION_SAVE){

			$save_status = $this->_save_promotion();

			if($save_status){

				$this->session->set_flashdata(ACTION_MESSAGE, lang('create_successful'));

				redirect(site_url('marketings').'/');

			} else {

				$data['save_status'] = $save_status;

			}
		}

		$data['content'] = $this->load->view('marketings/pro/create_pro', $data, TRUE);
		$this->load->view('_templates/template', $data);
	}


	public function edit_pro($id){

		$data = $this->_set_common_data();

		$data = get_library('tinymce', $data);

		$data = $this->_load_pro_nav_menu($data, 0, $id);

		$data['site_title'] = lang('edit_promotion');

		$pro = $this->Marketing_Model->get_promotion($id);

		if($pro !== FALSE){

			$data['pro'] = $pro;

			if ($pro['is_multiple_time'] == 0)
            {
                $data['pro_customer'] = $this->Marketing_Model->get_promotion_code_used($id);
            }
		}

		$action = $this->input->post('action');

		if($action == ACTION_SAVE){

			$save_status = $this->_save_promotion($id);

			if($save_status){

				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));

				redirect(site_url('marketings').'/');

			} else {

				$data['save_status'] = $save_status;

			}
		}



		$data['content'] = $this->load->view('marketings/pro/edit_pro', $data, TRUE);
		$this->load->view('_templates/template', $data);
	}


	public function delete_pro($id){

		$status = $this->Marketing_Model->delete_promotion($id);

		if($status){

			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));

		} else {

			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}

		redirect(site_url('marketings').'/');
	}

	public function hotel_pro($pro_id, $des_id=''){

		$data = $this->_set_common_data();

		$data = $this->_load_pro_nav_menu($data, 1, $pro_id);

		$data['des_id'] = $des_id;

		$data['pro_id'] = $pro_id;

		$data['destinations'] = $this->Hotel_Model->get_all_destinations();

		$action = $this->input->post('action');

		if($action == ACTION_SAVE){

			$selected_hotels = $this->input->post('hotels');

			$save_status = $this->Marketing_Model->update_pro_hotels($pro_id, $des_id, $selected_hotels);

			$data['save_status'] = $save_status;

		}


		$hotels = $this->Marketing_Model->get_all_hotel_in_des($des_id);

		$pro_hotels = $this->Marketing_Model->get_pro_hotels($pro_id, $des_id);

		foreach ($hotels as $key=>$value){

			$selected = false;

			foreach ($pro_hotels as $pro_hotel){

				if($pro_hotel['hotel_id'] == $value['id']){

					$selected = true;

					break;
				}

			}

			$value['selected'] = $selected;

			$hotels[$key] = $value;
		}


		$data['hotels'] = $hotels;

		$data['site_title'] = lang('mk_mn_hotel_pro');

		$data['content'] = $this->load->view('marketings/pro/pro_hotel_setting', $data, TRUE);
		$this->load->view('_templates/template', $data);

	}


	public function _set_common_data($data = array()){

		// set session for menues
		$this->session->set_userdata('MENU', MNU_MARKETING);

		$data = get_library('datepicker', $data);

		$data = get_library('mask', $data);

		$data = get_library('typeahead', $data);

		$data['page_js'] = get_static_resources('marketing.js');

		$data['number_vouchers'] = $this->config->item('number_vouchers');

		$data['hotel_discount_types'] = $this->config->item('hotel_discount_types');

		$data['flight_discount_types'] = $this->config->item('flight_discount_types');

		$data['cruise_discount_types'] = $this->config->item('cruise_discount_types');

		$data['tour_discount_types'] = $this->config->item('cruise_discount_types');

		$data['voucher_status'] = $this->config->item('voucher_status');

		return $data;
	}

	public function _load_nav_menu($data, $mnu_index = 0){

		$nav_panel = $this->config->item('mk_nav_panel');

		$data['side_mnu_index'] = $mnu_index;

		$data['nav_panel'] = $nav_panel;

		return $data;
	}

	public function _load_pro_nav_menu($data, $mnu_index = 0, $id){

		$nav_panel = $this->config->item('pro_nav_panel');

		foreach ($nav_panel as $key=>$value){

			$value['link'] .= $id;

			$nav_panel[$key] = $value;

		}

		$data['side_mnu_index'] = $mnu_index;

		$data['nav_panel'] = $nav_panel;

		return $data;
	}

	public function _set_voucher_paging_info($offset, $data = array()){

		$search_criteria = isset($data['search_criteria'])? $data['search_criteria'] : array();

		$total_rows = $this->Marketing_Model->count_total_vouchers($search_criteria);

		$paging_config = get_paging_config($total_rows,'marketings/vouchers',PAGING_SEGMENT + 1);
		// initialize pagination
		$this->pagination->initialize($paging_config);

		$paging_info['paging_text'] = get_paging_text($total_rows, $offset);

		$paging_info['paging_links'] = $this->pagination->create_links();

		$data['paging_info'] = $paging_info;

		return $data;

	}

	public function _set_promotion_paging_info($offset, $data = array()){

		$search_criteria = isset($data['search_criteria'])? $data['search_criteria'] : array();

		$total_rows = $this->Marketing_Model->count_total_promotions($search_criteria);

		$paging_config = get_paging_config($total_rows,'marketings',PAGING_SEGMENT);
		// initialize pagination
		$this->pagination->initialize($paging_config);

		$paging_info['paging_text'] = get_paging_text($total_rows, $offset);

		$paging_info['paging_links'] = $this->pagination->create_links();

		$data['paging_info'] = $paging_info;

		return $data;

	}

	public function _get_list_vouchers($offset, $data = array()){

		$search_criteria = $this->_build_voucher_search_criteria();

		$data['search_criteria'] = $search_criteria;

		$per_page = $this->config->item('per_page');

		// for display correct order on the column # of table list
		$data['offset'] = $offset;

		$data['vouchers'] = $this->Marketing_Model->search_vouchers($search_criteria, $per_page, $offset);

		return $data;
	}

	public function _build_voucher_search_criteria(){

		$submit_action = $this->input->post('submit_action');

		// access the cancellation tab without search action
		if(empty($submit_action)){

			$search_criteria = $this->session->userdata(VOUCHER_SEARCH_CRITERIA);

			if(empty($search_criteria)){

				$search_criteria = array();

			}

		} else {

			if($submit_action == ACTION_RESET){

				$search_criteria = array();

			} elseif($submit_action == ACTION_SEARCH){

				$search_criteria['customer_name'] = $this->input->post('customer_name');

				$search_criteria['customer_id'] = $this->input->post('customer_id');

				$search_criteria['start_date'] = $this->input->post('start_date');

				$search_criteria['end_date'] = $this->input->post('end_date');

				$search_criteria['delivered'] = $this->input->post('delivered');

				$search_criteria['code'] = $this->input->post('code');

				$search_criteria['status']= $this->input->post('status');

			}

			$this->session->set_userdata(VOUCHER_SEARCH_CRITERIA, $search_criteria);

		}

		return $search_criteria;
	}


	public function _get_list_promotions($offset, $data = array()){

		$search_criteria = $this->_build_promotions_search_criteria();

		$data['search_criteria'] = $search_criteria;

		$per_page = $this->config->item('per_page');

		// for display correct order on the column # of table list
		$data['offset'] = $offset;

		$data['promotions'] = $this->Marketing_Model->search_promotions($search_criteria, $per_page, $offset);


		return $data;
	}

	public function _build_promotions_search_criteria(){

		$submit_action = $this->input->post('submit_action');

		// access the cancellation tab without search action
		if(empty($submit_action)){

			$search_criteria = $this->session->userdata(BPV_PROMOTION_SEARCH_CRITERIA);

			if(empty($search_criteria)){

				$search_criteria = array();

			}

		} else {

			if($submit_action == ACTION_RESET){

				$search_criteria = array();

			} elseif($submit_action == ACTION_SEARCH){

				$search_criteria['start_date'] = $this->input->post('start_date');

				$search_criteria['end_date'] = $this->input->post('end_date');


				$search_criteria['status'] = $this->input->post('status');

			}

			$this->session->set_userdata(BPV_PROMOTION_SEARCH_CRITERIA, $search_criteria);

		}


		return $search_criteria;
	}

	public function _save_voucher($id='', $old_voucher = ''){

		if($this->_validate_voucher()){

			$voucher = $this->_get_voucher_post_data($old_voucher);

			if($id != ''){

				$save_status = $this->Marketing_Model->update_voucher($id, $voucher);

			} else {

				$save_status = $this->Marketing_Model->create_voucher($voucher);

			}

			return $save_status;


		}

		return NULL;

	}

	public function _get_voucher_post_data($old_voucher = ''){

		$voucher['customer_id'] = $this->input->post('customer_id');

		$voucher['amount'] = format_rate_input($this->input->post('amount', true));

		$voucher['expired_date'] = bpv_format_date($this->input->post('expired_date', true), DB_DATE_FORMAT);

		$voucher['number_voucher'] = $this->input->post('number_voucher', true);

		$voucher['delivered'] = $this->input->post('delivered', true);

		$voucher['status'] = $this->input->post('status', true);

		$voucher['log'] = $this->_generate_voucher_log($old_voucher, $voucher);

		return $voucher;
	}

	public function _set_validation_rules($val_cnf)
	{
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->form_validation->set_rules($val_cnf);
	}

	public function _validate_voucher()
	{
		$val_cnf = $this->config->item('voucher');

		$this->_set_validation_rules($val_cnf);

		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;

	}

	public function _get_date_vi($time){
		date_default_timezone_set("Asia/Jakarta");
		$currentTime = $time["hours"] . ":" . $time["minutes"] . ":" . $time["seconds"];
		$currentDate = $time["mday"] . "/" . $time["mon"] . "/" . $time["year"];

		return $currentTime."   ".$currentDate;
	}

	public function _validate_promotion()
	{
		$val_cnf = $this->config->item('promotions');

		$this->_set_validation_rules($val_cnf);

		$hotel_discount_type = $this->input->post('hotel_discount_type');

		if(!empty($hotel_discount_type)){

			$this->form_validation->set_rules('hotel_get', lang('pro_field_hotel_get'), 'required');

			$this->form_validation->set_rules('hotel_get_max', lang('pro_field_hotel_get_max'), 'required');

		}


		$flight_discount_type = $this->input->post('flight_discount_type');


		if(!empty($flight_discount_type)){

			$this->form_validation->set_rules('flight_get', lang('pro_field_flight_get'), 'required');

			//$this->form_validation->set_rules('flight_get_max', lang('pro_field_flight_get_max'), 'required');

		}

		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;

	}

	public function _save_promotion($id=''){

		if($this->_validate_promotion()){

			$promotion = $this->_get_promotion_post_data();

			if($id != ''){

				$save_status = $this->Marketing_Model->update_promotion($id, $promotion);

			} else {

				$save_status = $this->Marketing_Model->create_promotion($promotion);

			}

			return $save_status;


		}

		return NULL;

	}

	public function _get_promotion_post_data(){

		$promotion['name'] = $this->input->post('name');

		$promotion['code'] = $this->input->post('code');

		$promotion['status'] = $this->input->post('status', true);

		$promotion['public'] = $this->input->post('public', true);

		$promotion['apply_all'] = $this->input->post('apply_all', true);

		$promotion['expired_date'] = bpv_format_date($this->input->post('expired_date', true), DB_DATE_FORMAT);

		$promotion['max_nr_booked'] = $this->input->post('max_nr_booked', true);

		$promotion['init_nr_booked'] = $this->input->post('init_nr_booked', true);

		$promotion['description'] = $this->input->post('description', true);

		$promotion['discount_note'] = $this->input->post('discount_note', true);

		$promotion['hotel_discount_type'] = $this->input->post('hotel_discount_type', true);

		$promotion['hotel_get'] = format_rate_input($this->input->post('hotel_get', true), 1);

		$promotion['hotel_get_max'] = format_rate_input($this->input->post('hotel_get_max', true));

		$promotion['flight_discount_type'] = $this->input->post('flight_discount_type', true);

		$promotion['flight_get'] = format_rate_input($this->input->post('flight_get', true),1);

		$promotion['flight_get_max'] = format_rate_input($this->input->post('flight_get_max', true));

		$promotion['cruise_discount_type'] = $this->input->post('cruise_discount_type', true);

		$promotion['cruise_get'] = format_rate_input($this->input->post('cruise_get', true), 1);

		$promotion['cruise_get_max'] = format_rate_input($this->input->post('cruise_get_max', true));

		$promotion['tour_discount_type'] = $this->input->post('tour_discount_type', true);

		$promotion['tour_get'] = format_rate_input($this->input->post('tour_get', true), 1);

		$promotion['tour_get_max'] = format_rate_input($this->input->post('tour_get_max', true));

		$promotion['url_title'] = url_title(convert_unicode($promotion['name']), '-', true);
		
		$promotion['is_multiple_time'] = $this->input->post('is_multiple_time');

		return $promotion;
	}

	public function cruise_pro($pro_id){

		$data = $this->_set_common_data();

		$data = $this->_load_pro_nav_menu($data, 2, $pro_id);

		$data['pro_id'] = $pro_id;

		$action = $this->input->post('action');

		if($action == ACTION_SAVE){

			$pro_cruise_post_data = array();

			$selected_cruises = $this->input->post('cruises');

			foreach ($selected_cruises as $cruise_id){
				$cruise_get = $this->input->post('specific_cruises_'.$cruise_id);

				if(!empty($cruise_get)) $cruise_get = str_replace(',', '', $cruise_get);

				$pc['cruise_id'] = $cruise_id;
				$pc['cruise_get'] = $cruise_get;

				$pro_cruise_post_data[] = $pc;
			}

			$save_status = $this->Marketing_Model->update_pro_cruises($pro_id, $pro_cruise_post_data);

			$data['save_status'] = $save_status;

		}


		$cruises = $this->Marketing_Model->get_all_cruises();

		$pro_cruises = $this->Marketing_Model->get_pro_cruises($pro_id);

		foreach ($cruises as $key=>$value){

			$selected = false;
			
			$cruise_get = 0;

			foreach ($pro_cruises as $pro_cruise){

				if($pro_cruise['cruise_id'] == $value['id']){

					$selected = true;
					
					$cruise_get = $pro_cruise['cruise_get'];

					break;
				}

			}

			$value['selected'] = $selected;
			
			$value['cruise_get'] = $cruise_get;

			$cruises[$key] = $value;
		}


		$data['cruises'] = $cruises;

		$data['site_title'] = lang('mk_mn_cruise_pro');

		$data['content'] = $this->load->view('marketings/pro/pro_cruise_setting', $data, TRUE);
		$this->load->view('_templates/template', $data);

	}

	public function tour_pro($pro_id, $des_id = ''){

		$data = $this->_set_common_data();

		$data = $this->_load_pro_nav_menu($data, 3, $pro_id);

		$data['des_id'] = $des_id;

		$data['pro_id'] = $pro_id;

		$data['destinations_domistic'] = $this->Destination_Model->get_tour_destinations(STATUS_INACTIVE);

		$data['destinations_outbound'] = $this->Destination_Model->get_tour_destinations(STATUS_ACTIVE);


		$action = $this->input->post('action');

		if($action == ACTION_SAVE){

			$pro_tour_post_data = array();

			$selected_tours = $this->input->post('tours');


			foreach ($selected_tours as $tour_id){

				$tour_get = $this->input->post('tour_get_'.$tour_id);

				if(!empty($tour_get)) $tour_get = str_replace(',', '', $tour_get);

				$pt['tour_id'] = $tour_id;
				$pt['tour_get'] = $tour_get;

				$pro_tour_post_data[] = $pt;

			}

			//print_r($pro_tour_post_data);exit();

			$save_status = $this->Marketing_Model->update_pro_tours($pro_id, $des_id, $pro_tour_post_data);

			$data['save_status'] = $save_status;

		}


		$tours = $this->Marketing_Model->get_all_tour_in_des($des_id);

		$pro_tours = $this->Marketing_Model->get_pro_tours($pro_id, $des_id);

		foreach ($tours as $key=>$value){

			$selected = false;
			$tour_get = 0;

			foreach ($pro_tours as $pro_tour){

				if($pro_tour['tour_id'] == $value['id']){

					$selected = true;

					$tour_get = $pro_tour['tour_get'];

					break;
				}

			}

			$value['selected'] = $selected;
			$value['tour_get'] = $tour_get;

			$tours[$key] = $value;
		}


		$data['tours'] = $tours;

		$data['site_title'] = lang('mk_mn_tour_pro');

		$data['content'] = $this->load->view('marketings/pro/pro_tour_setting', $data, TRUE);
		$this->load->view('_templates/template', $data);
	}

}