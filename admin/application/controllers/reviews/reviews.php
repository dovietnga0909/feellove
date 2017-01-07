<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reviews extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model(array('Tour_Model', 'Destination_Model', 'Hotel_Model', 'Reviews_Model'));
		
		$this->load->helper(array('hotel', 'search', 'tour', 'cruise'));
		
		$this->load->library(array('pagination', 'form_validation'));
		
		$this->load->language('review');
		
		$this->config->load('review_meta');
		$this->config->load('hotel_meta');
		$this->config->load('tour_meta');
	}
	
	public function index()
	{		
		$hotel_id = $this->get_seg_id('hotels');
		
		if(!empty($hotel_id)) {
			$data = _get_hotel_data(array(), $hotel_id);
			
			// set session for menues
			$this->session->set_userdata('MENU', MNU_HOTEL_REVIEWS);
		}
		
		$tour_id = $this->get_seg_id('tours');
		
		if(!empty($tour_id)) {
			$data = $this->_get_tour(array(), $tour_id);
			
			// set session for menues
			$this->session->set_userdata('MENU', MNU_TOUR_REVIEWS);
		}
		
		$cruise_id = $this->get_seg_id('cruises');
		
		if(!empty($cruise_id)) {
			$data = _get_cruise_data(array(), $cruise_id);
				
			// set session for menues
			$this->session->set_userdata('MENU', MNU_CRUISE_REVIEWS);
		}
		
		if (empty($tour_id) && empty($hotel_id) && empty($cruise_id)) {
			_show_error_page(lang('no_review_found'));
			exit();
		}
	
		$data = $this->_get_list_data($data);
	
		// render view
		$data['site_title'] = lang('hotel_reviews_title');
	
		_render_view('reviews/list_reviews', $data);
	}
	
	function get_seg_id($seg_name) {
		
		$segs = $this->uri->segment_array();
		
		foreach ($segs as $segment)
		{
			if($segment == $seg_name) {
				$array = $this->uri->uri_to_assoc(2);
				
				return $array['reviews'];
			}
		}

		return null;
	}
	
	function _get_tour($data, $id) {
		$CI =& get_instance();
		
		$CI->load->model('Tour_Model');
		$tour = $CI->Tour_Model->get_tour($id);
		
		$data['tour'] = $tour;
		
		return $data;
	}
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MODULE_HOTEL_REVIEWS, $data);
	
		$search_criteria = $data['search_criteria'];
	
		// set hotel id
		if(!empty($data['hotel'])) {
			$search_criteria['hotel_id'] = $data['hotel']['id'];
		}
		
		if(!empty($data['tour'])) {
			$search_criteria['tour_id'] = $data['tour']['id'];
		}
		
		if(!empty($data['cruise'])) {
			$search_criteria['cruise_id'] = $data['cruise']['id'];
		}
		
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
	
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['reviews'] = $this->Reviews_Model->search_reviews($search_criteria, $per_page, $offset);
	
		$total_rows = $this->Reviews_Model->get_numb_reviews($search_criteria);
	
		$data = set_paging_info($data, $total_rows, 'hotels/reviews/');
	
		return $data;
	}
	
	// create a new review
	public function create(){
		
		$data = $this->_set_common_data();
		
		$hotel_id = $this->input->get('hotel_id');
		
		$review_for['type'] = HOTEL;
		
		if(!empty($hotel_id)) {
			$data = _get_hotel_data($data, $hotel_id);
			
			$review_for['name'] = $data['hotel']['name'];
			$review_for['hotel_id'] = $data['hotel']['id'];
			$review_for['tour_id'] = '';
		}
		
		$tour_id = $this->input->get('tour_id');
		
		if(!empty($tour_id)) {
			$data = _get_tour_data($data, $tour_id);
				
			$review_for['name'] = $data['tour']['name'];
			$review_for['tour_id'] = $data['tour']['id'];
			$review_for['hotel_id'] = '';
			$review_for['type'] = CRUISE;
		}
		
		$cruise_id = $this->input->get('cruise_id');
		
		if(!empty($cruise_id)) {
			$data = _get_cruise_data($data, $cruise_id);
			$data['tours'] = $this->Tour_Model->get_cruise_tours($data['cruise']['id']);
		
			$review_for['tour_id'] = '';
			$review_for['hotel_id'] = '';
			$review_for['type'] = CRUISE;
			
			$this->form_validation->set_rules('tour_id', lang('reviews_field_for'), 'required');
		}
		
		$data['review_for'] = $review_for;
	
		if ($this->form_validation->run() == true) {
	
			$review = $this->_get_post_data($data);
	
			$save_status = $this->Reviews_Model->create_review($review);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				$this->_redirect($data);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
	
		// render view
		$data['site_title'] = lang('create_review_title');
	
		_render_view('reviews/create_review', $data);
	}
	
	public function _set_common_data($data = array()) {
	
		// set session for menues
		//$this->session->set_userdata('MENU', MNU_HOTEL_REVIEWS);
		
		$data['score_types'] = $this->config->item('score_types');
		
		$data['customer_types'] = $this->config->item('customer_types');
		
		$data['customer_cities'] = $this->Destination_Model->get_customer_cities();
		
		$room_config = $this->config->item('create_review');
		$this->form_validation->set_rules($room_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		$this->_setValidationRules();
	
		$data = get_library('datepicker', $data);
		$data = get_library('typeahead', $data);
		//$data = get_library('tinymce', $data);
		
		$data['page_js'] = get_static_resources('marketing.js,review.js');
	
		return $data;
	}
	
	function _setValidationRules()
	{
		$types = $this->config->item('score_types');
	
		foreach ($types as $key_type => $value_type){
				
			foreach ($value_type as $key => $value){
	
				$this->form_validation->set_rules($key_type.'_'.$key, lang($value), 'numeric|max_length[4]');
			}
		}
	}
	
	// edit the room type
	public function edit(){
		
		$data = $this->_set_common_data();
	
		$data = $this->_get_review($data);
	
		if ($this->form_validation->run() == true) {
	
			$review = $this->_get_post_data($data);
			
			$review['id'] = $data['review']['id'];
	
			$save_status = $this->Reviews_Model->update_review($review);
	
			if ($save_status)
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				$this->_redirect($data);
			} else {
				if(!is_null($save_status)){
					$data['save_status'] = $save_status;
				}
			}
		}
		
		$data['status_config'] = $this->config->item('status_config');
	
		// render view
		$data['site_title'] = lang('edit_review_title');
	
		_render_view('reviews/edit_review', $data);
	}
	
	function _get_post_data($data) {
	
		$review = array(
				'customer_id'		=> trim($this->input->post('customer_id')),
				'customer_name'		=> trim($this->input->post('customer_name')),
				'customer_type'  	=> trim($this->input->post('customer_type')),
				'customer_city'  	=> trim($this->input->post('customer_city')),
				'review_date'  		=> trim($this->input->post('review_date')),
				'total_score' 		=> $this->input->post('total_score'),
					
				'title'  	=> trim($this->input->post('title')),
				'review_content'  	=> trim($this->input->post('review_content')),
					
				'hotel_id'  		=> $this->input->post('hotel_id'),
					
				'tour_id'  			=> $this->input->post('tour_id'),
		);
			
		$review['review_date'] = date(DB_DATE_FORMAT, strtotime($review['review_date']));
			
		if( !empty($review['tour_id']) ) {
			$tour = $this->Tour_Model->get_tour($review['tour_id']);
			$review['cruise_id'] = $tour['cruise_id'];
		}
			
		$reviewForType = !empty($review['hotel_id']) ? HOTEL : CRUISE;
			
		// default is Cruise Tour
		$sct = $data['score_types'][$reviewForType];
			
		$review_scores = array();
			
		foreach ($sct as $key => $value){
			$score = $this->input->post($reviewForType . '_' . strval($key));
	
			$review_scores[] = array('score' => $score, 'score_type' => $key);
		}
			
		$review['review_scores'] = $review_scores;
	
		return $review;
	}
	
	function _get_review($data = array()) {
		
		$id = (int)$this->uri->segment(NORMAL_ID_SEGMENT);
		
		$review = $this->Reviews_Model->get_review($id);
		if ($review == false) {
			_show_error_page(lang('no_review_found'));
			exit;
		}
		
		$data['review'] = $review;
		
		$data['score_types'] = $this->config->item('score_types');
		
		$data['customer_types'] = $this->config->item('customer_types');
		
		$data['customer_cities'] = $this->Destination_Model->get_customer_cities();
		
		$review_for['type'] = HOTEL;
		
		if( !empty($review['hotel_id']) ) {
			$data = _get_hotel_data($data, $review['hotel_id']);
		} else if( !empty($review['cruise_id']) ) {
			$data = _get_cruise_data($data, $review['cruise_id']);
			$review_for['type'] = CRUISE;
		} else if( !empty($review['tour_id']) ) {
			$data = _get_tour_data($data, $review['tour_id']);
			$review_for['type'] = CRUISE;
		}
		
		$data['review_for'] = $review_for;
		
		return $data;
	}
	
	public function delete(){
	
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$data = $this->_get_review();
	
		$status = $this->Reviews_Model->delete_review($id);
	
		if($status){
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
	
		} else {
	
			$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
		}
		
		if(!empty($data['review']['hotel_id'])) {
			redirect("hotels/reviews/".$data['review']['hotel_id']);
		} else if(!empty($data['review']['cruise_id'])) {
			redirect("cruises/reviews/".$data['review']['cruise_id']);
		} else if(!empty($data['review']['tour_id'])) {
			redirect("tours/reviews/".$data['review']['tour_id']);
		}
	}
	
	function _redirect($data) {
		
		if(!empty($data['hotel']['id'])) {
			redirect("hotels/reviews/".$data['hotel']['id']);
		} else if(!empty($data['tour']['id'])) {
			redirect("tours/reviews/".$data['tour']['id']);
		} else if(!empty($data['cruise']['id'])) {
			redirect("cruises/reviews/".$data['cruise']['id']);
		}
		
	}
	
	function suggest_hotels() {
		$term = $this->input->get('query');
	
		$hotels = $this->Hotel_Model->suggest_hotels($term);
	
		foreach ($hotels as $k => $des)  {
			$hotels[$k]['type'] = HOTEL;
		}
	
		echo json_encode($hotels);
	}
	
	function suggest_tours() {
		$term = $this->input->get('query');
	
		$tours = $this->Tour_Model->suggest_tours($term);
	
		foreach ($tours as $k => $des)  {
			$tours[$k]['type'] = CRUISE;
		}
	
		echo json_encode($tours);
	}
}
