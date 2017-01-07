<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_Model extends CI_Model {	

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();	
        
		$this->load->database();
    }
    
    function getReviews($search_criteria, $offset = 0, $num = 10){
    	
    	$this->db->select('r.id, r.customer_name, r.total_score, r.review_date, r.title, r.review_content, r.vote_up, d.name as city_name');
    	
    	$this->_build_search_condition($search_criteria);
    	
    	$this->db->join('destinations as d','d.id = r.customer_city');
    	
    	$this->db->order_by("r.review_date", "desc");
    	
    	$query = $this->db->get('reviews r', $num, $offset);
    
    	return $query->result_array();
    }
    
    function getNumReviews($search_criteria) {
    	$this->_build_search_condition($search_criteria);
    	
    	return $this->db->count_all_results('reviews r');
    }
    
    function get_review_scores($search_criteria) {
    	
    	$this->db->select('sc.score score, sc.score_type, sc.review_id');
    	
    	$this->db->from('review_score sc');
    	
    	$this->db->join('reviews r', 'r.id = sc.review_id');
    	
    	$this->_build_search_condition($search_criteria);
    	
    	$query = $this->db->get();
    	
    	return $query->result_array();
    }

    function _build_search_condition($search_criteria) {
    	
    	$this->db->where('r.deleted !=', DELETED);
    	
    	if( !empty($search_criteria['review_score']) ) {
    		
	    	switch ($search_criteria['review_score']) {
	    		case 1 :
	    			$this->db->where('r.total_score >=', 9);
	    			break;
	    		case 2 :
	    			$this->db->where('r.total_score >=', 8);
	    			$this->db->where('r.total_score <', 9);
	    			break;
	    		case 3 :
	    			$this->db->where('r.total_score >=', 7);
	    			$this->db->where('r.total_score <', 8);
	    			break;
	    		case 4 :
	    			$this->db->where('r.total_score >=', 5);
	    			$this->db->where('r.total_score <', 7);
	    			break;
	    		case 5 :
	    			$this->db->where('r.total_score <', 5);
	    			break;
	    	}
    	}
    	
    	if( !empty($search_criteria['cruise_id']) ) {
    		$this->db->where('r.cruise_id', $search_criteria['cruise_id']);
    	}
    	
    	if( !empty($search_criteria['hotel_id']) ) {
    		$this->db->where('r.hotel_id', $search_criteria['hotel_id']);
    	}
    	
    	if( !empty($search_criteria['tour_id']) ) {
    		$this->db->where('r.tour_id', $search_criteria['tour_id']);
    	}
    	
    	if( !empty($search_criteria['customer_type']) ) {
    		$this->db->where('r.customer_type', $search_criteria['customer_type']);
    	}
    }
    
    function get_last_review($search_criteria) {
    	
    	$this->db->select('r.id, r.customer_name, r.total_score, r.review_date, r.title, r.review_content, d.name as city_name');
    	 
    	$this->_build_search_condition($search_criteria);
    	 
    	$this->db->join('destinations as d','d.id = r.customer_city');
    	
    	$this->db->where('r.review_content !=',  "");
    	
    	$this->db->order_by("r.review_date", "desc");

    	$this->db->limit(1);
    	
    	$query = $this->db->get('reviews r');
    	
    	$reviews = $query->result_array();
    	
    	if(!empty($reviews)) {
    		return $reviews[0];
    	}
    	
    	return null;
    }
    
    function review_vote($review_id) {
    	
    	$vote_up = 0;
    	
    	//get vote_up value from db using review_id
    	$this->db->where('id',  $review_id);
    	$query = $this->db->get('reviews');
    	$reviews = $query->result_array();
    	
    	if( !empty($reviews) ) {
    		$review = $reviews[0];
    		
    		$review['vote_up'] = $review['vote_up'] + 1;
    		
    		$this->db->update('reviews', $review, array('id' => $review['id']));
    		
    		$vote_up = $review['vote_up'];
    	}
    	
    	return $vote_up;
    }
}