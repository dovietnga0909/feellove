<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Hotel_Model extends CI_Model {	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
			
		$this->load->database();
		
		$this->load->model('Destination_Model');
	}
	
	function get_top_hotel_destinations($limit = 6) {
	
		$this->db->select('id, name, url_title, number_of_hotels, picture, latitude, longitude');
	
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('is_top_hotel', 1);
		
		$this->db->where('number_of_hotels >', 0);
		
		//$this->db->limit($limit);
	
		$this->db->order_by('position', 'asc');
	
		$query = $this->db->get('destinations');
	
		return $query->result_array();
	}
	
	function get_all_hotel_destinations() {
	
		$this->db->select('d.id, d.name, d.url_title');
		
		$this->db->from('destination_places dp');

		$this->db->join('destinations d', 'd.id = dp.destination_id');
		
		$this->db->where('d.deleted !=', DELETED);
	
		$this->db->where('dp.parent_id', 1);
		
		$this->db->where('d.type', DESTINATION_TYPE_CITY);
		
		$this->db->where('d.number_of_hotels >', 0);
		
		$this->db->order_by('d.name', 'asc');
	
		$query = $this->db->get();
	
		return $query->result_array();
	}
	
	function _term_pre_processing($term) {
		
		$term = strtolower(trim($term));
		
		if ( stripos($term, 'd') !== false) {
			$ext_term = str_ireplace('d', 'đ', $term);
			
			$term = $term . ' ' . $ext_term;
		}
		
		return $term;
	}
	
	function suggest_destinations($term)
	{
		$term = $this->db->escape_str($term);
		
		$term = search_term_pre_process($term);
		
		$match_sql = "MATCH(d.keywords) AGAINST ('".$term."')";
		
		$this->db->select('d.id, d.name, d.url_title, '. $match_sql . ' AS score');
		
		$this->db->join('destination_places dp', 'dp.destination_id = d.id');
	
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->where($match_sql);
		
		// in vietnam
		$this->db->where('dp.parent_id', DESTINATION_VIETNAM);
		
		$this->db->order_by('score', 'desc');
		
		$this->db->order_by('d.number_of_hotels', 'desc');
		
		$this->db->order_by('d.type', 'asc');
		
		$this->db->order_by('d.name', 'asc');
		
		$this->db->limit(5);
	
		$query = $this->db->get('destinations d');
	
		$results = $query->result_array();
		
		return $results;
	}
	
	function suggest_hotels($term) 
	{
		$term = $this->db->escape_str($term);
		
		// remove vietnamese tones
		$hotel_name = search_term_pre_process($term);
		
		// add operator
		$term = search_term_pre_process($term, true);
		
		// score for order
		$match_sql = "MATCH(keywords) AGAINST ('".$hotel_name."') as score";
		
		$match_1_sql = "MATCH(keywords) AGAINST ('\"".$hotel_name."\"' IN BOOLEAN MODE) as score_1";
		
		$match_2_sql = "MATCH(keywords) AGAINST ('".$term."' IN BOOLEAN MODE) as score_2";
		
		$this->db->select('id, name, url_title, '. $match_sql . ',' .$match_1_sql . ',' .$match_2_sql);
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('status =', STATUS_ACTIVE);
		
		$match_where_sql = "MATCH(name) AGAINST ('".$term."' IN BOOLEAN MODE)";
		
		$this->db->where($match_where_sql);
		
		$this->db->order_by('score', 'desc');
		
		$this->db->order_by('score_1', 'desc');
		
		$this->db->order_by('score_2', 'desc');
		
		$this->db->order_by('name', 'asc');
		
		$this->db->limit(5);
		
		$query = $this->db->get('hotels');
		
		$results = $query->result_array();
		
		//print_r($this->db->last_query());exit();
		
		return $results;
	}
	
	function get_best_hotel_destinations($startdate, $limit = BEST_HOTEL_LIMIT, $hotel_litmit = 10) {
		$destinations = $this->get_top_hotel_destinations($limit);
		
		foreach ($destinations as $k => $des) {
			
			if ($k > ($limit - 1)) break;
			
			$hotels = $this->get_hotel_by_destination($des['id'], $startdate, $hotel_litmit);
			
			$des['hotels'] = $hotels;
			
			$destinations[$k] = $des;
		}
		
		return $destinations;
	}
	
	function get_hotel_by_destination($des_id, $startdate, $limit = 5, $get_price = true) {
		
		$this->db->select('h.id, h.name, h.url_title, h.address, h.picture, h.star, h.description, h.latitude, h.longitude, h.destination_id, h.review_score, h.review_number');
		
		$this->db->from('destination_hotels dh');
		
		$this->db->join('hotels h','h.id = dh.hotel_id');
		
		$this->db->where('h.status', STATUS_ACTIVE);
		
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->where('dh.destination_id', $des_id);
		
		$this->db->order_by('h.position', 'asc');
		
		if(!empty($limit)) {
			$this->db->limit($limit);
		}
		
		$query = $this->db->get();
		
		$hotels = $query->result_array();
		
		if($get_price) {
			$hotels = $this->get_hotel_price_from_4_list($hotels, $startdate);
			
			$hotels = $this->get_hotel_promotions_4_list($hotels, $startdate);
			
			$hotels = $this->get_hotel_bpv_promotion_4_list($hotels);
		}
		
		return $hotels;
	}
	
	function get_hotel_markers($des_id) {
	
		$this->db->select('h.id, h.name, h.url_title, h.latitude, h.longitude');
	
		$this->db->from('destination_hotels dh');
	
		$this->db->join('hotels h','h.id = dh.hotel_id');
	
		$this->db->where('h.deleted !=', DELETED);
	
		$this->db->where('dh.destination_id', $des_id);
	
		$this->db->order_by('h.position', 'asc');
	
		$query = $this->db->get();
	
		$hotels = $query->result_array();
		
		foreach ($hotels as $k => $hotel) {
			$hotel['full_url'] = hotel_build_url($hotel);
			$hotels[$k] = $hotel;
		}
		
		return $hotels;
	}
	
	function get_hotel_in_and_around($des, $startdate, $limit = 10) {
		
		$des_id = !empty($des['parent_id']) ? $des['parent_id'] : $des['id'];
		
		$distance_sql = '( 6371 * acos( cos( radians('.$des['latitude'].') ) * cos( radians( h.latitude ) ) ';
		
		$distance_sql .= '* cos( radians( h.longitude ) - radians('.$des['longitude'].') )';
		
		$distance_sql .=' + sin( radians('.$des['latitude'].') ) * sin( radians( h.latitude ) ) ) ) AS distance';
		
		$this->db->select('h.id, h.name, h.url_title, h.address, h.picture, h.star, h.description, h.latitude, h.longitude, h.destination_id, '.$distance_sql);
		 
		$this->db->from('destination_hotels dh');
		
		$this->db->join('hotels h','h.id = dh.hotel_id');
		
		$this->db->join('destinations d','d.id = dh.destination_id');
		
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->where('h.status', STATUS_ACTIVE);
		
		$this->db->where('dh.destination_id', $des_id);
		
		$this->db->having('distance <=', SEARCH_DISTANCE);
		
		$this->db->limit($limit);
		
		$this->db->order_by('distance', 'asc');
		
		$this->db->order_by('h.position', 'asc');
		
		$query = $this->db->get();
		
		$hotels = $query->result_array();
		
		$hotels = $this->get_hotel_price_from_4_list($hotels, $startdate);
		
		$hotels = $this->get_hotel_promotions_4_list($hotels, $startdate);
		
		$hotels = $this->get_hotel_bpv_promotion_4_list($hotels);
		
		return $hotels;
	}
	
	/**
	 * Get facilities for filter in hotel-search page
	 * 
	 */
	function get_search_filter_facilities($search_criteria){
		
		$selected_des = $search_criteria['selected_des'];
		
		if(empty($selected_des)) return array();
		
		//$this->db->distinct();
		
		$this->db->select('fa.id, fa.name, fa.group_id, fa.is_important, count(*) as number');
		
		$this->db->from('hotel_facilities hf');
		
		$this->db->join('facilities fa', 'fa.id = hf.facility_id');
		
		$this->db->join('hotels h', 'h.id = hf.hotel_id');
		
		$this->db->join('destination_hotels dh', 'dh.hotel_id = h.id');
		
		if($selected_des['type'] <= DESTINATION_TYPE_AREA){ // hotel in destinaton
			$this->db->where('dh.destination_id', $selected_des['id']);
		} else { // hotel near area
			$this->db->where('dh.destination_id', $selected_des['parent_id']);
		}
		
		$this->db->where('fa.type_id & 2 > 0'); // facility for hotel = 1, for room type = 2
		
		$this->db->where('fa.deleted !=', DELETED);
		
		$this->db->where('fa.hotel_id', 0); // no specific hotel facility
		
		$this->db->where('fa.status', STATUS_ACTIVE);
		
		$this->db->group_by('fa.id');
	
		$this->db->order_by('fa.position', 'asc');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
	}
	
	function get_search_filter_stars($search_criteria){
		
		$selected_des = $search_criteria['selected_des'];
		
		if(empty($selected_des)) return array();
		
		$this->db->select('h.star, count(*) as number');
		
		$this->db->from('destination_hotels as dh');
		
		$this->db->join('hotels as h','h.id = dh.hotel_id');
		
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->where('h.status', STATUS_ACTIVE);
		
		if($selected_des['type'] <= DESTINATION_TYPE_AREA){ // hotel in destinaton
			$this->db->where('dh.destination_id', $selected_des['id']);
		} else { // hotel near area
			$this->db->where('dh.destination_id', $selected_des['parent_id']);
		}
		
		$this->db->group_by('h.star');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
		
	}
	
	function get_search_filter_prices($search_criteria){
		
		$selected_des = $search_criteria['selected_des'];
		
		if(empty($selected_des)) return array();
		
		$startdate = $search_criteria['startdate'];
		
		$startdate = format_bpv_date($startdate);
		
		$this->db->select('hpf.range_index, count(*) as number');
		
		$this->db->from('destination_hotels as dh');
		
		$this->db->join('hotels as h','h.id = dh.hotel_id');
		
		$this->db->join('hotel_price_froms hpf','hpf.hotel_id = h.id AND hpf.date = "'.$startdate.'"');
		
		$this->db->join('promotions as p', 'p.id = hpf.promotion_id AND '.sql_join_hotel_promotion($startdate),'left outer');
		
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->where('h.status', STATUS_ACTIVE);
		
		if($selected_des['type'] <= DESTINATION_TYPE_AREA){ // hotel in destinaton
			$this->db->where('dh.destination_id', $selected_des['id']);
		} else { // hotel near area
			$this->db->where('dh.destination_id', $selected_des['parent_id']);
		}
		
		$this->db->group_by('hpf.range_index');
		
		$query = $this->db->get('');
		
		$results = $query->result_array();
		
		return $results;
		
	}
	
	/**
	 * Get hotel selected in auto complete function
	 */
	
	function get_search_hotel($hotel_id){
		
		$this->db->select('h.id, h.name, h.url_title, h.extra_cancellation, h.infant_age_util, 
				h.children_age_to, h.check_in, h.check_out, h.infants_policy, h.children_policy, d.name as destination_name');
		$this->db->from('hotels h');
		$this->db->join('destinations d', 'd.id = h.destination_id');
		$this->db->where('h.deleted !=', DELETED);
		$this->db->where('h.status', STATUS_ACTIVE);
		$this->db->where('h.id', $hotel_id);
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			return $results[0];
		} else {
			return '';
		}
	}
	
	function get_selected_hotel($hotel_id){
		
		$this->db->select('h.id, h.name, h.url_title, h.address, h.star, h.picture, h.partner_id, h.destination_id, h.infant_age_util, 
				h.children_age_to, h.check_in, h.check_out, h.infants_policy, h.children_policy, d.name as destination_name');
		
		$this->db->from('hotels h');
		
		$this->db->join('destinations d', 'd.id = h.destination_id');
		
		$this->db->where('h.deleted !=', DELETED);
		$this->db->where('h.status', STATUS_ACTIVE);
		$this->db->where('h.id', $hotel_id);
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			return $results[0];
		} else {
			return '';
		}
	}
	
	function search_hotel_suggestion($hotel_name, $startdate){
		
	    $startdate = format_bpv_date($startdate);
	    
		$hotel_name = urldecode($hotel_name);
		
		// remove vietnamese tones
		$hotel_name = search_term_pre_process($hotel_name);
		
		// add operator
		$term = search_term_pre_process($hotel_name, true);
		
		$hotel_name = $this->db->escape_like_str($hotel_name);
		
		$term = $this->db->escape_like_str($term);
		
		// score for order
		$match_sql = "MATCH(h.keywords) AGAINST ('".$hotel_name."') as score";
		
		$match_1_sql = "MATCH(h.keywords) AGAINST ('\"".$hotel_name."\"' IN BOOLEAN MODE) as score_1";
		
		$match_2_sql = "MATCH(h.keywords) AGAINST ('".$term."' IN BOOLEAN MODE) as score_2";
		
		// begin sql command
	
		$this->db->select('h.id, h.name, h.keywords, h.url_title, h.star, h.picture, h.address, hpf.price_origin, IF(p.id IS NOT NULL, hpf.price_from, hpf.price_origin) as price_from, d.name as destination_name, '. $match_sql . ',' . $match_1_sql . ',' . $match_2_sql );
		
		$this->db->_protect_identifiers = false;
		
		$this->db->from('hotels h');
		
		$this->db->join('destinations d','d.id = h.destination_id');
		
		$this->db->join('hotel_price_froms hpf', 'hpf.hotel_id = h.id AND hpf.date = "'.$startdate.'"','left outer');
		
		$this->db->join('promotions as p', 'p.id = hpf.promotion_id AND '.sql_join_hotel_promotion($startdate),'left outer');
	
		$match_sql = "MATCH(h.name) AGAINST ('".$term."' IN BOOLEAN MODE)";
	
		$this->db->where($match_sql);
	
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->where('h.status', STATUS_ACTIVE);
		
		$this->db->order_by('score','desc');
		
		$this->db->order_by('score_1','desc');
		
		$this->db->order_by('score_2','desc');
		
		$this->db->group_by('h.id');
		
		// add limit 
		$this->db->limit(5);
	
		$query = $this->db->get();
	
		$results = $query->result_array();
	
		return $results;
	
	}
	
	function _build_search_condition($search_criteria){
		
		
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->where('h.status', STATUS_ACTIVE);
		
		$search_des = isset($search_criteria['area_des']) ? $search_criteria['area_des'] : $search_criteria['selected_des'];
		
		if($search_des['type'] <= DESTINATION_TYPE_AREA){ // hotel in destinaton
			$this->db->where('dh.destination_id', $search_des['id']);
		} else { // hotel near area
			$this->db->where('dh.destination_id', $search_des['parent_id']);
		}
		
		
		if(!empty($search_criteria['star'])){
			$stars = explode(',', $search_criteria['star']);
			if(count($stars) > 0){
				
				for ($i = 1; $i <= 5; $i++){
					if(in_array($i, $stars)){
						$stars[] = $i + 0.5;
					}
				}
				$this->db->where_in('h.star', $stars);
			}
		}
		
		if(!empty($search_criteria['price'])){
			
			$prices = explode(',', $search_criteria['price']);
			
			if(count($prices) > 0){
				$this->db->where_in('hpf.range_index', $prices);
			}
			
		}
		
		if(!empty($search_criteria['facility'])){
			
			$fa_ids = explode(',', $search_criteria['facility']);
			
			if(count($fa_ids) > 0){
				
				$fa_sql = ' AND hf.facility_id IN ('.$search_criteria['facility'].')';

				
				$sql_cond = '((SELECT COUNT(*) FROM hotel_facilities as hf WHERE h.id = hf.hotel_id '.$fa_sql.') = '.count($fa_ids).')';
				
				$this->db->where($sql_cond);
				
			}
			
		}
			
		
	}
	
	function count_search_hotels($search_criteria){
		
		$startdate = $search_criteria['startdate'];
		
		$startdate = format_bpv_date($startdate);
		
		$this->db->_protect_identifiers = false;
		
		$this->db->select('h.id');
		
		$this->db->from('destination_hotels as dh');
		
		$this->db->join('hotels as h','h.id = dh.hotel_id');
		
		$this->db->join('hotel_price_froms hpf', 'hpf.hotel_id = h.id AND hpf.date = "'.$startdate.'"','left outer');
		
		$this->_build_search_condition($search_criteria);
		
		$this->db->group_by('h.id');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		$cnt = count($results);
		
		//$cnt = $this->db->count_all_results();
		
		return $cnt;
	}
	
	function search_hotels($search_criteria){
		
		$startdate = $search_criteria['startdate'];
		
		$startdate = format_bpv_date($startdate);
		
		$paging_config = $this->config->item('paging_config');
		$offset = !empty($search_criteria['page']) ? $search_criteria['page'] : 0;
		
		$select_sql = 'h.id, h.name, h.url_title, h.star, h.address, h.description, h.picture, h.latitude, h.longitude, h.facilities, h.destination_id, h.review_score, h.review_number, hpf.price_origin, IF(p.id IS NOT NULL, hpf.price_from, hpf.price_origin) as price_from';
		
		$this->db->_protect_identifiers = false;
		
		$this->db->select($select_sql);
		
		$this->db->from('destination_hotels as dh');
		
		$this->db->join('hotels as h','h.id = dh.hotel_id');
		
		$this->db->join('hotel_price_froms hpf', 'hpf.hotel_id = h.id AND hpf.date = "'.$startdate.'"','left outer');
		
		$this->db->join('promotions as p', 'p.id = hpf.promotion_id AND '.sql_join_hotel_promotion($startdate),'left outer');
		
		$this->_build_search_condition($search_criteria);
		
		if(isset($search_criteria['sort'])){
			
			if($search_criteria['sort'] == SORT_BY_PRICE){
	
				$this->db->order_by('IF(ISNULL(price_from),1,0),price_from');
				
				//$this->db->order_by('price_from IS NULL','desc');

			} 
			
			if($search_criteria['sort'] == SORT_BY_REVIEW){
			    $this->db->order_by('h.review_score','desc');
			}
			
			if($search_criteria['sort'] == SORT_BY_NAME){
				$this->db->order_by('h.name','asc');
			}
			
			if($search_criteria['sort'] == SORT_BY_STAR){
				$this->db->order_by('h.star','asc');
			}
			
		}

		$this->db->limit($paging_config['per_page'], $offset);
	
		$this->db->order_by('h.position','asc');
		$this->db->group_by('h.id');
		
		
		$query = $this->db->get();
	
		$results = $query->result_array();
		
		// set promotion from Hotel 
		$results = $this->get_hotel_promotions_4_list($results, $startdate);
		
		// set promotion from Best Price
		$results = $this->get_hotel_bpv_promotion_4_list($results);
	
		return $results;
	
	}
	
	/*
	 * Get recent hotels in cookie
	 */
	function get_recent_hotels($items, $startdate) {
		
		$hotels = null;
		
		if ( isset($items['hotel']) && !empty($items['hotel']) ) {
			foreach ($items['hotel'] as $item) {
				$hotel_ids[] = $item['hotel_id'];
			}
			
			$this->db->select('id, name, url_title, address, picture, star');
			
			$this->db->where('deleted !=', DELETED);
			
			$this->db->where_in('id', $hotel_ids);
			
			$query = $this->db->get('hotels');
			
			$results = $query->result_array();
			
			$hotels = $this->get_hotel_price_from_4_list($results, $startdate);
			
			foreach ($hotels as $k => $hotel) {
				foreach ($items['hotel'] as $item) {
					if($item['hotel_id']  == $hotel['id']) {
						$hotel['item_id'] = $item['id'];
					}
				}
				
				$hotels[$k] = $hotel;
			}
		}
		
		return $hotels;
	}

	function get_hotel_detail($id, $startdate = null){
		$this->db->select('h.*, d.name as destination_name, d.type as destination_type, d.number_of_hotels as number_of_hotels');
		$this->db->from('hotels as h');
		$this->db->join('destinations as d','d.id = h.destination_id');
		$this->db->where('h.id', $id);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) > 0){
			
			$hotel = $results[0];
			if( !empty($startdate) ) {
				$hotels = $this->get_hotel_price_from_4_list(array($hotel), $startdate);
				
				$hotel = $hotels[0];
			}
			
			return $hotel;
		}
		
		return '';
	}
	
	function get_hotel_parent_destinations($hotel_id){
		
		$this->db->distinct();
		$this->db->select('d.name, d.url_title, d.id, d.is_top_hotel, d.type');
		
		$this->db->from('destination_hotels as dh');
		
		$this->db->join('destinations as d','d.id = dh.destination_id');
		
		$this->db->where_in('d.type', array(DESTINATION_TYPE_CITY, DESTINATION_TYPE_DISTRICT, DESTINATION_TYPE_AREA));
		
		$this->db->where('dh.hotel_id', $hotel_id);
		
		$this->db->order_by('type');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
	}
	
	function get_hotel_price_from($hotel_id, $startdate){
		
		$startdate = format_bpv_date($startdate);
	
		$this->db->select('hpf.price_origin, IF(p.id IS NOT NULL, hpf.price_from, hpf.price_origin) as price_from', false);
		
		$this->db->from('hotel_price_froms hpf');
		
		$this->db->join('promotions p', 'p.id = hpf.promotion_id AND '.sql_join_hotel_promotion($startdate), 'left outer');
	
		$this->db->where('hpf.hotel_id', $hotel_id);
		
		$this->db->where('hpf.date', $startdate);
		
		$this->db->order_by('hpf.price_from','asc');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			
			return $results[0];
			
		}
		
		
		return FALSE;
		
	}
	
	function get_all_available_hotel_promotions($hotel_id){
	
		$today = date(DB_DATE_FORMAT); // today
			
		// get promotions for list hotels
		$this->db->select('id, name, offer, stay_date_from, stay_date_to, book_date_from, book_date_to, hotel_id, check_in_on');
		
		$this->db->from('promotions');
		
		$sql_cond = 'book_date_to >= "'.$today.'"'.
				' AND stay_date_to >= "'.$today.'"'.
				' AND deleted != '.DELETED;
		
		$this->db->where($sql_cond);
		
		$this->db->where('show_on_web', STATUS_ACTIVE);
		
		$this->db->where('hotel_id', $hotel_id);
	
		$this->db->order_by('id', 'asc');
		
		$query = $this->db->get();
		
		$promotions = $query->result_array();
			
		return $promotions;
	}
	
	function get_hotel_photos($hotel_id){
		
		$this->db->where('hotel_id', $hotel_id);
		
		$this->db->order_by('position');
	
		$query = $this->db->get('photos');
		
		$results = $query->result_array();
		
		return $results;
		
	}
	
	function get_hotel_room_types($hotel_id, $has_detail = false){
		
		$this->db->where('hotel_id', $hotel_id);
		
		$this->db->where('status', STATUS_ACTIVE);
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->order_by('position');
		
		$query = $this->db->get('room_types');
		
		$results = $query->result_array();
		
		if($has_detail){
			
			foreach ($results as $key=>$value){
				
				$value['room_facilities'] = $this->get_hotel_facilities($value['facilities'],2);
	
				$results[$key] = $value;
			}
			
		}
		
		return $results;
		
	}
	
	function get_hotel_facilities($str_facilities, $type = 1){
		
		if(!empty($str_facilities)){
			$str_facilities = explode('-', $str_facilities);
			
			if(count($str_facilities) > 0){
				$this->db->select('id, name, group_id, is_important');
				$this->db->where_in('id', $str_facilities);
				$this->db->where('deleted !=', DELETED);
				$this->db->where('status', STATUS_ACTIVE);
				$this->db->where('type_id & '.pow(2,$type) .' > 0'); // type hotel	
				$this->db->order_by('name','asc');
				$query = $this->db->get('facilities');
				
				$results = $query->result_array();
				
				return $results;
			}
			
		}
	
		return array();
	}
	
	
	function get_all_hotels_in_destination($destination_id){
	
		$this->db->select('h.id, h.name, h.url_title, h.star');
	
		$this->db->join('hotels h', 'h.id = dh.hotel_id');
	
		$this->db->where('h.deleted != ', DELETED);
	
		$this->db->where('h.status', STATUS_ACTIVE);
	
		$this->db->where('dh.destination_id', $destination_id);
	
		$this->db->order_by('h.position', 'asc');
	
		$query = $this->db->get('destination_hotels dh');
			
		$results = $query->result_array();
	
		return $results;
	
	}
	
	function get_hotel_room_rates($hotel_id, $startdate, $enddate){
	
		$this->db->where('hotel_id', $hotel_id);
	
		$this->db->where('date >=', $startdate);
	
		$this->db->where('date <=', $enddate);
	
		$query = $this->db->get('room_rates');
	
		$results = $query->result_array();
	
		return $results;
	
	}
	
	function get_hotel_promotions($hotel_id, $startdate, $night, $enddate){
		$today = date(DB_DATE_FORMAT);
	
		// calculate day-before-staying date
		$today_time = strtotime($today);
	
		$stay_time = strtotime($startdate);
	
		$day_before = $stay_time - $today_time;
	
		$day_before =  round($day_before/(60*60*24));
	
		$this->db->select('p.*, c.id as cancellation_id, c.name as cancellation_name, c.fit, c.fit_cutoff, c.git_cutoff, c.content');
	
		$this->db->from('promotions p');
	
		$this->db->join('cancellations c','c.id = p.cancellation_id');
		
		$this->db->where('p.hotel_id', $hotel_id);
	
		$this->db->where('p.deleted !=',DELETED);
	
		$this->db->where('p.stay_date_from <=', $enddate);
	
		$this->db->where('p.stay_date_to >=', $startdate);
	
		$this->db->where('p.book_date_from <=', $today);
	
		$this->db->where('p.book_date_to >=', $today);
	
		$this->db->where('p.display_on & '.pow(2,date('w',strtotime($today))).' > 0');
	
		$str_cond = '(p.promotion_type = ' . PROMOTION_TYPE_CUSTOMIZED . ' OR (p.promotion_type = '.PROMOTION_TYPE_EARLY_BIRD.' AND p.day_before_check_in <= '.$day_before.')'.
	
				' OR (p.promotion_type = '.PROMOTION_TYPE_LAST_MINUTE.' AND p.day_before_check_in >= '.$day_before.'))';
	
		$this->db->where($str_cond);
	
		$this->db->where('p.minimum_stay <=', $night);
	
		$this->db->where('(p.maximum_stay IS NULL OR p.maximum_stay = 0 OR p.maximum_stay >='.$night.')');
		
		$this->db->order_by('p.show_on_web','desc');
		
		$this->db->order_by('p.get_1','desc');
	
		$query = $this->db->get();
	
		$results = $query->result_array();
	
		foreach ($results as $key=>$value){
				
			$value['room_types'] = $this->get_promotion_room_types($value['id']);
				
			$results[$key] = $value;
				
		}
	
		return $results;
	}
	
	function get_promotion_room_types($promotion_id){
	
		$this->db->where('promotion_id', $promotion_id);
	
		$query = $this->db->get('promotion_room_types');
	
		$results = $query->result_array();
	
		return $results;
	
	}
	
	function get_cancellation_of_hotel($hotel_id){
		$this->db->select('c.*');
		$this->db->from('hotels h');
		$this->db->join('cancellations as c', 'c.id = h.cancellation_id');
		$this->db->where('c.deleted != ', DELETED);
		$this->db->where('h.id', $hotel_id);
		$query = $this->db->get();
	
		$results = $query->result_array();
	
		if(count($results) > 0){
			return $results[0];
		}
	
		return '';
	}
	
	function get_cancellation_by_id($can_id){
		
		$this->db->where('deleted != ', DELETED);
		$this->db->where('id', $can_id);
		$query = $this->db->get('cancellations');
	
		$results = $query->result_array();
	
		if(count($results) > 0){
			return $results[0];
		}
	
		return '';
	}
	
	function get_hotel_price_from_4_list($hotels, $startdate){
		
		$hotel_ids = get_ids_from_hotel_list($hotels);
		
		if(count($hotels) == 0) return $hotels;
		
		$startdate = format_bpv_date($startdate);
		
		$select_sql = 'hpf.hotel_id, hpf.price_origin, IF(p.id IS NOT NULL,hpf.price_from,hpf.price_origin) as price_from';
		
		$this->db->select($select_sql, false);
		
		$this->db->from('hotel_price_froms as hpf');
		
		$this->db->join('promotions as p', 'p.id = hpf.promotion_id AND '.sql_join_hotel_promotion($startdate),'left outer');
		
		$this->db->where_in('hpf.hotel_id', $hotel_ids);
		
		$this->db->where('hpf.date', $startdate);
		
		$this->db->order_by('hpf.hotel_id','asc');
		
		$this->db->order_by('price_from','asc');
		
		$query = $this->db->get();
		
		$price_froms = $query->result_array();
		
		foreach ($hotels as $key=>$value){
			foreach ($price_froms as $price_from){
				if($value['id'] == $price_from['hotel_id']){
					$value['price_origin'] = $price_from['price_origin'];
					$value['price_from'] = $price_from['price_from'];
					break;
				}
			}
			
			$hotels[$key] = $value;
		}
		
		return $hotels;
		
	}
	
	function get_similar_hotels($hotel, $startdate){
		
		$this->db->where('id !=', $hotel['id']);
		
		$this->db->where('destination_id', $hotel['destination_id']);
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('status', STATUS_ACTIVE);
		
		$this->db->where('star >=', $hotel['star']);
		
		$this->db->where('star <=', $hotel['star'] + 0.5);
		
		$this->db->limit(5);
		
		$this->db->order_by('position', 'asc');
		
		$query = $this->db->get('hotels');
		
		$hotels = $query->result_array();
		
		$hotels = $this->get_hotel_price_from_4_list($hotels, $startdate);
		
		return $hotels;
	}
	
	function get_same_destination_hotels($hotel, $startdate){
		
		$this->db->where('id !=', $hotel['id']);
		
		$this->db->where('destination_id', $hotel['destination_id']);
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->where('status', STATUS_ACTIVE);
		
		$this->db->where('RAND() <= ', 5/$hotel['number_of_hotels']);
		
		$this->db->limit(5);
		
		$query = $this->db->get('hotels');
		
		$hotels = $query->result_array();
		
		$hotels = $this->get_hotel_price_from_4_list($hotels, $startdate);
		
		return $hotels;
	}
	
	function get_hotel_surcharges($hotel_id, $startdate, $enddate){
		$this->db->where('hotel_id', $hotel_id);
		$this->db->where('start_date <=', $enddate);
		$this->db->where('end_date >=', $startdate);
		$this->db->where('amount >',0);
		$this->db->where('deleted !=', DELETED);
		$this->db->order_by('id');
		$query = $this->db->get('surcharges');
		$surcharges = $query->result_array();
		return $surcharges;
	}
	
	function get_recent_hotel_deals(){
		
		$today = date(DB_DATE_FORMAT);
		
		$this->db->select('h.id, h.name, h.star, h.url_title, h.description');
		
	}
	
	function get_hotel_bpv_promotion_4_list($hotels){
		
		$today = date(DB_DATE_FORMAT);
		
		$hotel_ids = get_ids_from_hotel_list($hotels);
		
		if(count($hotel_ids) > 0){
			
			$this->db->distinct();
			$this->db->select('b.*, bh.hotel_id');		
			$this->db->from('bpv_promotion_hotels bh');
			$this->db->join('bpv_promotions b', 'b.id = bh.bpv_promotion_id');
			$this->db->where_in('bh.hotel_id', $hotel_ids);
			$this->db->where('b.status', STATUS_ACTIVE);
			$this->db->where('b.deleted !=', DELETED);
			$this->db->where('b.expired_date >=', $today);
			$this->db->where('b.public', STATUS_ACTIVE);
			$this->db->order_by('b.id','asc');
			
			$query = $this->db->get();
			$bpv_promotions = $query->result_array();
			
			foreach ($hotels as $key=>$hotel){
				
				$pros = array();
				
				foreach ($bpv_promotions as $pro){
					
					if($pro['hotel_id'] == $hotel['id']){
						
						$pros[] = $pro;
						
					}
					
				}
				
				$hotel['bpv_promotions'] = $pros;
				
				$hotels[$key] = $hotel;
				
			}
			
		}
		
		
		return $hotels;
	}
	
	function get_hotel_promotions_4_list($hotels, $startdate){
		
		$today = date(DB_DATE_FORMAT); // today
		
		$startdate = format_bpv_date($startdate);
		
		$hotel_ids = get_ids_from_hotel_list($hotels);
		
		if(count($hotel_ids) > 0){
			
			$this->db->from('promotions p');
			
			$this->db->where_in('p.hotel_id', $hotel_ids);
			/*
			 * 11.06.2014: requirement from mr.Dung: show all available promotion of the hotel
			 * 
			$pro_sql = sql_join_hotel_promotion($startdate);
			
			$this->db->where($pro_sql);
			*/
			
			//$this->db->where('p.book_date_from <=', $today);
			$this->db->where('p.book_date_to >=', $today);
			$this->db->where('p.stay_date_to >=', $today);
			$this->db->where('p.deleted != ', DELETED);
			
			/* End of change requirement on 11.06.2014 */
			
			$this->db->where('p.show_on_web', STATUS_ACTIVE);
			
			$this->db->order_by('p.id');
			
			$query = $this->db->get();
			$promotions = $query->result_array();
			
			foreach ($hotels as $key=>$hotel){
			
				$pros = array();
			
				foreach ($promotions as $pro){
						
					if($pro['hotel_id'] == $hotel['id']){
			
						$pros[] = $pro;
			
					}
						
				}
			
				$hotel['promotions'] = $pros;
			
				$hotels[$key] = $hotel;
			
			}
			
		}
		
		return $hotels;
	}
	
	
	function get_hotel_bpv_promotions($hotel_id){
	
		$today = date(DB_DATE_FORMAT);
	
		$this->db->distinct();
		$this->db->select('b.*, bh.hotel_id');
		$this->db->from('bpv_promotion_hotels bh');
		$this->db->join('bpv_promotions b', 'b.id = bh.bpv_promotion_id');
		$this->db->where('bh.hotel_id', $hotel_id);
		$this->db->where('b.status', STATUS_ACTIVE);
		$this->db->where('b.deleted !=', DELETED);
		$this->db->where('b.public', STATUS_ACTIVE);
		$this->db->where('b.expired_date >=', $today);
		$this->db->order_by('b.id','asc');
			
		$query = $this->db->get();
		$bpv_promotions = $query->result_array();
		
		return $bpv_promotions;
	}
	
	/**
	 * Get Hotels for Map functions
	 */
	
	function get_main_hotel_on_map($id, $startdate){
		
		$this->db->select('h.id, h.name, h.address, h.star, h.picture, h.url_title, h.latitude, h.longitude, d.name as destination_name');
		$this->db->from('hotels as h');
		$this->db->join('destinations as d','d.id = h.destination_id');
		$this->db->where('h.id', $id);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) > 0){
				
			$hotel = $results[0];
			
			$hotels = $this->get_hotel_price_from_4_list(array($hotel), $startdate);
			
			$hotel = $hotels[0];
				
			return $hotel;
		}
		
		return '';
		
		
	}
	
	// get hotels in city
	function get_hotels_in_city($des_id, $startdate, $hotel_id = ''){
	
		$this->db->select('h.id, h.name, h.address, h.star, h.picture, h.url_title, h.latitude, h.longitude');
		
		$this->db->from('destination_hotels dh');
		
		$this->db->join('hotels h','h.id = dh.hotel_id');
		
		if($hotel_id != ''){
			$this->db->where('h.id !=', $hotel_id);
		}
		
		$this->db->where('h.deleted !=', DELETED);
		
		$this->db->where('dh.destination_id', $des_id);
		
		$this->db->where('(h.latitude > 0 AND h.longitude > 0)');
		
		
		$query = $this->db->get();
		
		$hotels = $query->result_array();
		
		if(count($hotels) > 0){

			$hotels = $this->get_hotel_price_from_4_list($hotels, $startdate);
			
			return $hotels;
		}
		
		return'';
	}
	
}
