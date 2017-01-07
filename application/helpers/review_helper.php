<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function _getNumberReviewsEachType($search_criteria, $filter_name)
{
	$CI =& get_instance();

	$ret = array();
	$filter_types = $CI->config->item($filter_name);

	foreach ($filter_types as $key => $value) {

		if($filter_name == 'review_customer_types') {
			$search_criteria['customer_type'] = $key;
		}

		if($filter_name == 'review_score_breakdown') {
			$search_criteria['review_score'] = $key;
		}

		$ret[] = array(
				'key' => $key,
				'name' => $value,
				'value' => $CI->Review_Model->getNumReviews($search_criteria, $key),
		);
	}

	return $ret;
}

function _getAverageScoreByType($type, $scores){
	$index = 0;
	$score = 0;
	foreach ($scores as $value) {

		if ($type == $value['score_type']){

			$score = $score + $value['score'];

			$index = $index + 1;
		}

	}
	if ($index != 0){
		return round($score/$index,1);
	}
}

function _getAverageScores($score_types, $scores){
	
	$ret = array();

	foreach ($score_types as $key=>$value) {
		$ret[] = array(
				'key' => $key,
				'name' => $value,
				'value' => _getAverageScoreByType($key, $scores),
		);
	}

	return $ret;
}

function _getTotalScore($average_scores) {
	
	$total = 0;
	
	if ( !empty($average_scores) )
	{
		foreach ($average_scores as $value) {
			$total = $total + $value['value'];
		}
		
		$total = round($total/count($average_scores), 1);
	}
	
	return $total;
}

function get_review_url($search_criteria, $filter_name = null, $filter_value = null, $is_remove = false) {
	
	$param = '';
	
	if(!empty($filter_name)) {
		
		if($filter_name == 'review_customer_types') {
			$search_criteria['customer_type'] = $filter_value;
		}
		
		if($filter_name == 'review_score_breakdown') {
			$search_criteria['review_score'] = $filter_value;
		}
		
		// remove parameters
		if($is_remove) {
			unset($search_criteria[$filter_name]);
		}	
	}
	
	foreach ($search_criteria as $key => $value) {
		
		if($key == 'page') continue;
		
		if(empty($param)) {
			$param = '?'.$key.'='.$value;
		} else {
			$param .= '&'.$key.'='.$value;
		}
		
	}
	
	$url = '/reviews/'.$param;
	
	return $url;
}

function get_filter_selections($search_criteria, $score_breakdown, $customer_types) {
	
	$txt = '';
	
	foreach ($search_criteria as $key => $value) {
		if($key == 'review_score') {
			foreach ($score_breakdown as $score) {
				if($score['key'] == $value) {
					$url = get_review_url($search_criteria, 'review_score', $value, true);
					
					$btn = '<a class="btnFilter review_filter" data-url="'.$url.'" href="javascript:void(0)">';
					$btn .= lang($score['name']).'<span class="icon icon-review-remove"></span></a>';
					
					$txt .= $btn;
				}
			}
		}
		
		if($key == 'customer_type') {
			foreach ($customer_types as $cus) {
				if($cus['key'] == $value) {
					$url = get_review_url($search_criteria, 'customer_type', $value, true);
					
					$btn = '<a class="btnFilter review_filter" data-url="'.$url.'" href="javascript:void(0)">';
					$btn.= lang($cus['name']).'<span class="icon icon-review-remove"></span></a>';
					
					$txt .= $btn;
				}
			}
		}
	}
	
	return $txt;
}


function is_filter_selected($search_criteria, $filter_name, $filter_value) {
	
	foreach ($search_criteria as $key => $value) {
		if($key == $filter_name
				&& $filter_value == $value) {
			return true;
		}
	}
	
	return false;
}

function is_voted_review($review_id) {
	
	$CI =& get_instance();
	
	$unique_content_id = hash('md5', $review_id);
	
	$voted = $CI->session->userdata("voted_".$unique_content_id);
		
	if ( isset($voted) && !empty($voted) ) {
		return true;
	}
	
	return false;
		
}