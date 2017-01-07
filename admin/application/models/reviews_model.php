<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reviews_Model extends CI_Model{	

	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_reviews($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		
		return $this->db->count_all_results('reviews r');
	}
	
	function search_reviews($search_criteria = ''
		, $num, $offset
		, $order_field = 'review_date', $order_type = 'asc')
	{	
		//$this->benchmark->mark('code_start');
		
		$this->db->select('r.*, u.username as last_modified_by, h.name as review_for_hotel, t.name as review_for_tour');
		
		$this->db->join('users u', 'r.user_modified_id = u.id', 'left outer');
		
		$this->db->join('hotels h', 'r.hotel_id = h.id', 'left outer');
		
		$this->db->join('tours t', 'r.tour_id = t.id', 'left outer');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('reviews r', $num, $offset);
		
		$reviews = $query->result_array();

		//$this->benchmark->mark('code_end');
		
		//echo $this->benchmark->elapsed_time('code_start', 'code_end');exit();
		
		return $reviews;
	}

	public function _set_search_criteria($search_criteria = '')
	{
		$this->db->where('r.deleted !=', DELETED);
		
		if ($search_criteria == '')	{			
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				/*case 'search_text' :
					$searchStr =  $this->db->escape_like_str($value);
					$this->db->like('r.name', $value, 'both');
					break;*/
				case 'hotel_id' :
					$this->db->where('r.hotel_id', $value);
					break;
				case 'tour_id' :
					$this->db->where('r.tour_id', $value);
					break;
				case 'cruise_id' :
					$this->db->where('r.cruise_id', $value);
					break;
			}
		}
	}
	
	/**
	 * create_review
	 *
	 * @return bool
	 **/
	public function create_review($review)
	{
		$this->db->trans_start();
		
		$review_scores = $review['review_scores'];
		unset($review['review_scores']);
		
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
		);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$review_data = array_merge($review, $additional_data);
	
		$this->db->insert('reviews', $review_data);
	
		$id = $this->db->insert_id();
		
		// create review score
		
		foreach ($review_scores as $value){
				
			$this->db->set('score', $value['score']);
				
			$this->db->set('score_type', $value['score_type']);
				
			$this->db->set('review_id', $id);
				
			$this->db->insert('review_score');
		}
		
		// update hotel review score
		
		$this->update_review_score($review);
		
		$this->db->trans_complete();
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function update_review_score($review){
		
		$hotel_id = $review['hotel_id'];
		$tour_id = $review['tour_id'];
	
		$this->db->select('total_score');
	
		$this->db->where('deleted !=', DELETED);
	
		if( !empty($hotel_id) ) {
			$this->db->where('hotel_id', $hotel_id);
		}
		
		if( !empty($tour_id) ) {
			$this->db->where('tour_id', $tour_id);
		}
	
		$query = $this->db->get('reviews');
	
		$reviews = $query->result_array();
	
		$number_review = count($reviews);
	
		$total_score = 0;
	
		foreach ($reviews as $value) {
			$total_score = $total_score + $value['total_score'];		
		}
	
		if ($number_review != 0){
			$total_score = round($total_score/$number_review, 1);
		}
			
		$this->db->set('review_number', $number_review);
	
		$this->db->set('review_score', $total_score);
		
		if( !empty($hotel_id) ) {
			$this->db->where('id', $hotel_id);
			$this->db->update('hotels');
		}
	
		if( !empty($tour_id) ) {
			$this->db->where('id', $tour_id);
			$this->db->update('tours');
		
			$this->_set_score_for_cruise($review['cruise_id']);
		}
	
	}
	
	function _set_score_for_cruise($cruise_id){
			
		$this->db->select('r.total_score');
	
		$this->db->from('reviews as r');
	
		$this->db->join('tours as t','t.id = r.tour_id');
	
		$this->db->where('t.cruise_id', $cruise_id);
	
		$this->db->where('r.deleted !=', DELETED);
	
		$query = $this->db->get();
	
		$reviews = $query->result_array();
	
		$number_review = count($reviews);
	
		$total_score = 0;
	
		foreach ($reviews as $value) {
				
			$total_score = $total_score + $value['total_score'];
				
		}
	
		if ($number_review != 0){
			$total_score = round($total_score/$number_review, 1);
		}
			
		$this->db->set('review_number', $number_review);
	
		$this->db->set('review_score', $total_score);
	
		$this->db->where('id', $cruise_id);
	
		$this->db->update('cruises');
	}
	
	function get_review($id) {
	
		if(empty($id)) {
			return FALSE;
		}
		
		$this->db->select('r.*, c.full_name as customer_name, c.id as customer_id, h.name as review_for_hotel, t.name as review_for_tour');
		
		$this->db->join('hotels h', 'r.hotel_id = h.id', 'left outer');
		
		$this->db->join('tours t', 'r.tour_id = t.id', 'left outer');
		
		$this->db->join('customers c', 'r.customer_id = c.id', 'left outer');
	
		$this->db->where('r.id', $this->db->escape_str($id));
		$this->db->where('r.deleted !=', DELETED);
	
		$query = $this->db->get('reviews r');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{	
			$review = $result[0];
			$review['review_scores'] = $this->get_review_scores($review['id']);
			return $review;
		}
	
		return FALSE;
	}
	
	function get_review_scores($id) {
		
		$scores = array();
		
		$this->db->where('review_id', $id);
	
		$query = $this->db->get('review_score');
		
		$scs = $query->result_array();
		
		foreach ($scs as $key => $value){
			
			$scores[$value['score_type']] = $value['score'];
		}
		
		return $scores;
	}
	
	function update_review($review) {
		
		$this->db->trans_start();
		
		$review_scores = $review['review_scores'];
		unset($review['review_scores']);

		$review['user_modified_id'] = get_user_id();
		$review['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
	
		$this->db->update('reviews', $review, array('id' => $review['id']));
		
		$this->db->where('review_id', $review['id']);
		$this->db->delete('review_score');
		
		// create review score
		foreach ($review_scores as $value){
		
			$this->db->set('score', $value['score']);
		
			$this->db->set('score_type', $value['score_type']);
		
			$this->db->set('review_id', $review['id']);
		
			$this->db->insert('review_score');
		}
		
		// update hotel review score
		
		$this->update_review_score($review);
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_review($id) {
	
		$review['deleted'] = DELETED;
	
		$this->db->update('reviews', $review, array('id' => $id));
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_reviews($hotel_id = null, $cruise_id = null)
	{
		if(empty($hotel_id) && empty($cruise_id)) {
			return null;
		}
		
		if (!empty($hotel_id)) {
			$this->db->where('hotel_id', $hotel_id);
		}
		
		if (!empty($cruise_id)) {
			$this->db->where('cruise_id', $cruise_id);
		}
		
		$this->db->where('deleted !=', DELETED);
	
		$this->db->order_by('review_date', 'asc');
		$query = $this->db->get('reviews');
	
		return $query->result_array();
	}
}

?>