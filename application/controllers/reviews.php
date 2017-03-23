<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reviews extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper('review');
        $this->load->model(array(
            'Review_Model',
            'Hotel_Model',
            'Tour_Model',
            'Cruise_Model'
        ));
    }

    function index()
    {
        // check if its an ajax request, exit if not
        if (! $this->input->is_ajax_request())
        {
            die();
        }
        
        // get post data
        $data = $this->_build_review_search_param();
        
        $search_criteria = $data['search_criteria'];
        
        $offset = ! empty($data['search_criteria']['page']) ? $search_criteria['page'] : 0;
        
        // get review from db
        $data['reviews'] = $this->Review_Model->getReviews($search_criteria, $offset);
        
        $data['count_results'] = $this->Review_Model->getNumReviews($search_criteria);
        
        // set paging
        $data = $this->_set_paging_info($data);
        
        $scores = $this->Review_Model->get_review_scores($search_criteria);
        
        // Score Types: location, services, ...etc
        
        $score_type_config = $this->config->item('score_types');
        
        if (! empty($search_criteria['hotel_id']))
        {
            $score_types = $score_type_config[HOTEL];
        }
        else
        {
            $score_types = $score_type_config[CRUISE];
        }
        
        $data['score_types'] = _getAverageScores($score_types, $scores);
        
        // Score Breakdown: excellent, good, ...etc
        
        $data['score_breakdown'] = _getNumberReviewsEachType($search_criteria, 'review_score_breakdown');
        
        // Prevent round inaccurate total score
        // $data['total_score'] = $data['obj']['review_score'];
        
        $data['total_score'] = _getTotalScore($data['score_types']);
        
        // Customer Types: family, couple, ...etc
        
        $data['customer_types'] = _getNumberReviewsEachType($search_criteria, 'review_customer_types');
        
        header("Content-type: text/html; charset=utf-8");
        
        $mobile_view = ( !empty($search_criteria['mobile']) && $search_criteria['mobile'] == 'on' ) ? 'mobile/' : '';
        
        $this->load->view( $mobile_view. 'common/bpv_reviews', $data );
    }

    public function _set_paging_info($data)
    {
        $this->load->library('pagination');
        
        $search_criteria = $data['search_criteria'];
        
        $offset = ! empty($search_criteria['page']) ? $search_criteria['page'] : 0;
        
        $total_rows = $data['count_results'];
        
        $url = get_review_url($search_criteria);
        
        $paging_config = get_paging_config($total_rows, $url, 1);
        
        // initialize pagination
        $this->pagination->initialize($paging_config);
        
        $paging_info['paging_text'] = get_paging_text($total_rows, $offset, 'review_paging_result');
        
        $paging_info['paging_links'] = $this->pagination->create_links();
        
        $data['paging_info'] = $paging_info;
        
        return $data;
    }

    function _build_review_search_param()
    {
        $data = array();
        $search_criteria = array();
        
        // get object details
        
        $hotel_id = $this->input->get('hotel_id');
        
        if (! empty($hotel_id))
        {
            $hotel = $this->Hotel_Model->get_hotel_detail($hotel_id);
            
            $data['obj'] = $hotel;
            
            $search_criteria['hotel_id'] = $hotel_id;
        }
        
        $tour_id = $this->input->get('tour_id');
        
        if (! empty($tour_id))
        {
            $tour = $this->Tour_Model->get_tour_details($tour_id);
            
            $data['obj'] = $tour;
            
            $search_criteria['tour_id'] = $tour_id;
        }
        
        $cruise_id = $this->input->get('cruise_id');
        
        if (! empty($cruise_id))
        {
            $cruise = $this->Cruise_Model->get_cruise_detail($cruise_id);
            
            $data['obj'] = $cruise;
            
            $search_criteria['cruise_id'] = $cruise_id;
        }
        
        // get platform
        $mobile = $this->input->get('mobile');
        
        if (! empty($mobile) && $mobile == 'on' )
        {
            $search_criteria['mobile'] = $mobile;
        }
        
        // get review details
        
        $review_score = $this->input->get('review_score');
        
        if (! empty($review_score))
        {
            $search_criteria['review_score'] = $review_score;
        }
        
        $customer_type = $this->input->get('customer_type');
        
        if (! empty($customer_type))
        {
            $search_criteria['customer_type'] = $customer_type;
        }
        
        $page = $this->input->get('page');
        
        if (! empty($page))
        {
            $search_criteria['page'] = $page;
        }
        
        $data['search_criteria'] = $search_criteria;
        
        return $data;
    }

    function review_voting()
    {
        if ($_POST)
        {
            $review_id = $this->input->post('id', TRUE);
            
            // Convert content ID to MD5 hash (optional)
            $unique_content_id = hash('md5', $review_id);
            
            // check if its an ajax request, exit if not
            if (! $this->input->is_ajax_request())
            {
                die();
            }
            
            // check if user has already voted, determined by unique content cookie
            $voted = $this->session->userdata("voted_" . $unique_content_id);
            
            // cookie found, user has already voted
            if (is_voted_review($review_id))
            {
                echo ('-1');
                exit(); // exit script
            }
            
            $total_rows = $this->Review_Model->review_vote($review_id);
            
            // set cookie that expires in 2 hour "time()+7200".
            $review_vote = array(
                'id' => uniqid(),
                'review_id' => $review_id
            );
            
            $this->session->set_userdata("voted_" . $unique_content_id, $review_vote);
            
            echo ($total_rows); // display total liked votes
        }
    }
}
