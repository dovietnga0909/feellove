<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Land_Tour_Model extends CI_Model {	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	/**
	*  Get all tour departure destination
	*
	*  @author toanlk
	*  @since  Sep 11, 2014
	*/
	function get_tour_departing_from()
    {
        $this->db->select('id, name, url_title');
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('is_tour_departure_destination', STATUS_ACTIVE);
        
        $this->db->order_by('name', 'asc');
        
        $query = $this->db->get('destinations');
        
        return $query->result_array();
    }
    
    /**
    *  Get all departure of tours
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_tour_departures($tour_id, $departure_id = null) {
        
        $this->db->select('td.id, d.name, d.url_title, td.departure_date_type, td.departure_specific_date, td.service_includes, td.service_excludes');
        
        $this->db->join('destinations as d', 'd.id = td.destination_id');
        
        $this->db->where('td.deleted !=', DELETED);
        
        $this->db->where('d.deleted !=', DELETED);
        
        $this->db->where('td.tour_id', $tour_id);
        
        if(!empty($departure_id))
        {
            $this->db->where('td.id', $departure_id);
        }
        
        $this->db->distinct('d.name');
        
        $this->db->order_by('name', 'asc');
        
        $query = $this->db->get('tour_departures td');
        
        return $query->result_array();    
    }
    
    /**
    *  Get all departing from
    *
    *  Structure:
    *  Destination group:
    *  - departure destination
    *
    *  Ex: Mien Bac
    *  - Hanoi
    *  - Hoa Binh
    *  - Moc Chau
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_tour_departure_destinations($is_outbound = false)
    {
        $groups = $this->get_tour_destination_group($is_outbound);
        
        foreach ($groups as $k => $group)
        {
            
            $this->db->select('d.id, d.name, d.url_title, d.is_outbound, d.nr_tour_domistic, d.nr_tour_outbound');
            
            $this->db->from('destination_places as dp');
            
            $this->db->join('destinations as d', 'd.id = dp.destination_id');
            
            $this->db->where('d.deleted !=', DELETED);
            
            $this->db->where('dp.parent_id', $group['id']);
            
            $this->db->where('d.is_tour_highlight_destination', STATUS_ACTIVE);
            
            $this->db->order_by('d.position', 'asc');
            
            $query = $this->db->get();
            
            $group['destinations'] = $query->result_array();
            
            $groups[$k] = $group;
        }
        
        return $groups;
    }

    /**
     * Get all destination group
     * 
     * Ex: 
     *      for domestic tours: Mien Bac, Mien Trung 
     *      
     *      for outbound tours: Dong Nam A, Chau A
     *
     * @author toanlk
     * @since  Sep 11, 2014         
     */
    function get_tour_destination_group($is_outbound = false)
    {
        $this->db->select('id, name, url_title, is_outbound, nr_tour_domistic, nr_tour_outbound');
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('is_tour_destination_group', STATUS_ACTIVE);
        
        if ($is_outbound)
        {
        	// update by Khuyenpv on 15.09.2014, use is_outboud flag
            $this->db->where('is_outbound', STATUS_ACTIVE);
        }
        else
        {
            $this->db->where('is_outbound', STATUS_INACTIVE);
        }
        
        $this->db->order_by('position', 'asc');
        
        $query = $this->db->get('destinations');
        
        return $query->result_array();
    }
    
    /**
    *  Get all tour categories
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_categories() {
        
        $this->db->select('id, name, is_hot, link, picture, url_title, description');
        
        $this->db->where('status', STATUS_ACTIVE);
            
        $this->db->where('deleted !=', DELETED);
        
        $this->db->order_by('position', 'asc');

        $query = $this->db->get('categories');
        
        return $query->result_array();
    }
    
    /**
     * Khuyenpv: Get Category By Id
     * 12.09.2014
     */
    function get_category($cat_id){
    	
    	$this->db->select('id, name, is_hot, link, picture, description');
    	
    	$this->db->where('status', STATUS_ACTIVE);
    	
    	$this->db->where('deleted !=', DELETED);
    	
    	$this->db->where('id', $cat_id);
    	
    	$this->db->order_by('position', 'asc');
    	
    	$query = $this->db->get('categories');
    	
    	$results = $query->result_array();
    	
    	if(count($results) > 0){
    		
    		return $results[0];
    		
    	}
    	
    	return FALSE;
    	
    }
    
    /**
    *  Get all tour categories domestic
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */

    function get_tour_categories($categories_id, $startdate, $is_outbound = '', $limit =  3){
    	
    	$this->db->select('t.id, t.cruise_id, t.name, t.code, t.url_title, t.duration, t.picture, t.review_score, t.review_number, t.routes');
    	
    	$this->db->join('tour_categories as tc', 'tc.tour_id = t.id', 'left outer');
    	
    	$this->db->where('tc.category_id', $categories_id);
    	
    	$this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        if($is_outbound !== ''){
        	$this->db->where('t.is_outbound', $is_outbound);
        }
        
        $this->db->order_by('t.position', 'asc');
        
        $this->db->limit($limit);
        
        $query = $this->db->get('tours t');
        
        $tours = $query->result_array();
       	
        // set price
        $tours = $this->get_tour_price_from_4_list($tours, $startdate);
        
        // set promotion
        $tours = $this->get_tour_promotions_4_list($tours, $startdate);
        
        $tours = get_route($tours);
        
        return $tours;
    }
    
	/**
     *  Get all tour categories outbound
     */
    
    function get_tour_categories_outbound($categories_id, $startdate, $limit =  3){
    	
    	$this->db->select('t.id, t.name, t.url_title, t.duration, t.picture, t.review_score, t.review_number');
    	
    	$this->db->join('tour_categories as tc', 'tc.tour_id = t.id', 'left outer');
    	
    	$this->db->where('tc.category_id', $categories_id);
    	
    	$this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->where('t.cruise_id', 0);
        
        $this->db->where('t.is_outbound', 1);
        
        $this->db->order_by('t.position', 'asc');
        
        $this->db->limit($limit);
        
        $query = $this->db->get('tours t');
        
        $tours = $query->result_array();
       	
        // set price
        $tours = $this->get_tour_price_from_4_list($tours, $startdate);
        
        // set promotion
        $tours = $this->get_tour_promotions_4_list($tours, $startdate);
        
        return $tours;
    }
    
	
    /**
     * 
     * Khuyenpv: 12.09.2014
     * Get top tours by category
     * 
     */
    
    function get_tours_by_category($category_id, $startdate, $limit =  10){
    	
    	$this->db->select('t.id, t.name, t.code, t.url_title, t.duration, t.picture, t.review_score, t.review_number, t.routes, t.cruise_id');
    	
    	$this->db->from('tour_categories tc');
    	
    	$this->db->join('tours t', 't.id = tc.tour_id');
    	
    	$this->db->where('tc.category_id', $category_id);
    	
    	$this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->order_by('t.position', 'asc');
        
      	$this->db->limit($limit);
        
        $query = $this->db->get();
        
        $tours = $query->result_array();
       	
        // set price
        $tours = $this->get_tour_price_from_4_list($tours, $startdate);
        
        // set promotion
        $tours = $this->get_tour_promotions_4_list($tours, $startdate);
        
        // get route
   		$tours = get_route($tours);
   		
   		// get departure date
    	$tours = $this->get_departure_date($tours, $startdate);
    	
    	// get transportations
    	$tours = $this->get_transportations($tours);
        
        return $tours;
    }
    
  
    
    /**
     * Get destination in destination parent  
     */
    
    function destination_of_destination_parent($parent_id, $is_group = false, $is_children = false){
    	
    	$this->db->select('d.id');
    	
    	$this->db->where('d.parent_id', $parent_id);
    	
    	if($is_group = true){
    		
    		$this->db->where('d.is_tour_highlight_destination', 1);
  			
    	}
    	
    	$query = $this->db->get('destinations d');
    	
    	$result = $query->result_array();
    
    	return $result;
    }
    
    /**
     * Get destiantion in destination
     */
    
    function destination_in_destination($list_des_parent){
    	
    	$this->db->select('d.id');
    	
    	$list = array();
    	
    	foreach($list_des_parent as $list){
    		$list[] = $list['id'];
     	}
    	
    	$this->db->where_in('d.parent_id', $list);
    	
    	$query = $this->db->get('destinations d');
    	
    	$result = $query->result_array();
    	
    	return $result;
    
    }
    
    /**
     * 
     * Get popular tours in Destinations
     * 
     */
    function get_popular_tours_in_destination($destination_id, $startdate, $limit = 10){
    	
    	$this->db->select('t.id, t.code, t.name, t.url_title, t.duration, t.picture, t.review_score, t.review_number, t.routes, t.cruise_id');
    	
    	$this->db->from('destination_tours dt');
    	
    	$this->db->join('tours t', 'dt.tour_id = t.id');
    	
    	$this->db->where('dt.is_land_tour', STATUS_ACTIVE);
    	
    	$this->db->where('dt.destination_id', $destination_id);
    	
    	$this->db->where('t.deleted !=', DELETED);
    	
    	$this->db->where('t.status', STATUS_ACTIVE);
    	
    	$this->db->order_by('t.position', 'asc');
    	
    	$this->db->limit($limit);
    	
    	$query = $this->db->get();
    	 
    	$tours =  $query->result_array();
    	 
    	// set price
    	$tours = $this->get_tour_price_from_4_list($tours, $startdate);
    	
    	// set promotion
    	$tours = $this->get_tour_promotions_4_list($tours, $startdate);
    	
    	// get route
    	$tours = get_route($tours);
    	
    	// get departure date
    	$tours = $this->get_departure_date($tours, $startdate);
    	
    	// get transportations
    	$tours = $this->get_transportations($tours);
    	
    	return $tours;
    	
    }
    
    /**
     * 	Khuyenpv 12.09.2014
     *  Get all tours go to all children destinations
     */
    function get_popular_tours_pass_all_children_des($destination_id, $startdate, $limit = 10){
    	
    	/**
    	 * Step 1: get all direct children destinations
    	 */
    	
    	$this->db->select('id');
    	$this->db->where('parent_id', $destination_id);
    	$this->db->where('deleted !=', DELETED);
    	$query = $this->db->get('destinations');
    	
    	$children_des =  $query->result_array();
    	
    	if(empty($children_des)) return array();
    
    	
    	$this->db->select('t.id, t.name, t.code, t.url_title, t.duration, t.picture, t.review_score, t.review_number, t.routes, t.cruise_id');
    	 
    	$this->db->from('tours t');
    	 
    	foreach ($children_des as $sub_des){

    		$sql_cond = "EXISTS (SELECT 1 FROM destination_tours dt WHERE dt.is_land_tour = 1 AND dt.tour_id = t.id AND dt.destination_id = ".$sub_des['id'].")";
    		 
    		$this->db->where($sql_cond, NULL, false);
    	}
    	 
    	$this->db->where('t.deleted !=', DELETED);
    	 
    	$this->db->where('t.status', STATUS_ACTIVE);
    	 
    	$this->db->order_by('t.position', 'asc');
    	 
    	$this->db->limit($limit);
    	 
    	$query = $this->db->get();
    	
    	$tours =  $query->result_array();
    	
    	// set price
    	$tours = $this->get_tour_price_from_4_list($tours, $startdate);
    	 
    	// set promotion
    	$tours = $this->get_tour_promotions_4_list($tours, $startdate);
    	 
    	// get route
    	$tours = get_route($tours);
    	
    	// get departure date
    	$tours = $this->get_departure_date($tours, $startdate);
    	
    	// get transportations
    	$tours = $this->get_transportations($tours);
    	
    	return $tours;
    }
    
    
    /**
     *	get tour land in destination	
     *
     *  @author nguyenson
     *
     *  @since Sep 12, 2014
     *
     */
    
    function get_land_tour_destination($destination_id, $startdate, $is_land_tour = false, $limit = 3){
    
    	$this->db->select('t.id, t.name, t.code, t.cruise_id, t.url_title, t.duration, t.picture, t.review_score, t.review_number, t.routes');
    	
    	$this->db->join('tours as t', 't.id = dt.tour_id', 'left outer');
    	
    	$this->db->where('dt.destination_id', $destination_id);
    	
    	if($is_land_tour){
    	
    		$this->db->where('dt.is_land_tour', STATUS_ACTIVE);
    	}else{
    		
    		$this->db->where('dt.is_land_tour', STATUS_INACTIVE);
    	}
    	
    	$this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->order_by('t.position', 'asc');
        
        $this->db->limit($limit);	
        
        $query = $this->db->get('destination_tours dt');
        
        $tours = $query->result_array();
       	
        // set price
        $tours = $this->get_tour_price_from_4_list($tours, $startdate);
        
        // set promotion
        $tours = $this->get_tour_promotions_4_list($tours, $startdate);
        
        // get route
   		$tours = get_route($tours);
   		
   		// get departure date
    	$tours = $this->get_departure_date($tours, $startdate);
    	
    	// get transportations
    	$tours = $this->get_transportations($tours);
        
        return $tours;
    	
    }
    
    
    /**
    *  get suggestion destination in search
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function search_destination_suggestion($term)
    {
        $term = $this->_term_pre_processing($term);
        $term = $this->db->escape_str($term);
    
        $match_sql = "MATCH(d.keywords) AGAINST ('" . $term . "*' IN BOOLEAN MODE)";
    
        $this->db->select('d.id, d.name, d.type, p.name as parent_name, ' . $match_sql . ' AS score');
    
        $this->db->from('destination_tours as dt');
    
        $this->db->join('destinations as d', 'd.id = dt.destination_id');
    
        $this->db->join('destinations as p', 'p.id = d.parent_id');
    
        $this->db->where($match_sql);
        //$this->db->like('d.keywords', $term, 'both');
    
        $this->db->where('d.deleted !=', DELETED);
    
        $this->db->order_by('score', 'desc');
    
        $this->db->order_by('d.position', 'asc');
    
        $this->db->group_by('d.id');
    
        $query = $this->db->get();
        
        //print_r($this->db->last_query());exit();
    
        return $query->result_array();
    }
    
    /**
    *  Replace unwannted character in search team
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function _term_pre_processing($term)
    {
        $term = strtolower(trim($term));
    
        if (stripos($term, 'd') !== false)
        {
            $ext_term = str_ireplace('d', 'đ', $term);
    
            $term = $term . ' ' . $ext_term;
        }
    
        return $term;
    }
    
    /**
    *  get popular domestic tours
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function popular_domestic_tour($startdate, $limit = 4) {
        
        $this->db->select('t.id, t.name, t.code, t.cruise_id, t.routes, t.url_title, t.duration, t.night, t.picture, t.review_score, t.review_number');
        
        $this->db->from('tours as t');
        
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->where('t.is_outbound', 0);
        
        $this->db->order_by('t.position', 'asc');
        
        $this->db->limit($limit);
        
        $query = $this->db->get();
        
        $tours = $query->result_array();
        
        // get route
        $tours = get_route($tours);
        
        // set price
        $tours = $this->get_tour_price_from_4_list($tours, $startdate);
        
        // set promotion
        $tours = $this->get_tour_promotions_4_list($tours, $startdate);
        
        // set promotion from Best Price
        $tours = $this->get_tour_bpv_promotion_4_list($tours);
        
        return $tours;
    }
    
    /**
    *  get popular outbound tours
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function popular_outbound_tour($startdate, $limit = 4) {

        $this->db->select('t.id, t.name, t.code, t.cruise_id, t.routes, t.url_title, t.duration, t.night, t.picture, t.review_score, t.review_number');
        
        $this->db->from('tours as t');
        
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->where('t.is_outbound', STATUS_ACTIVE);
        
        $this->db->order_by('t.position', 'asc');
        
        $this->db->limit($limit);
        
        $query = $this->db->get();
        
        $tours = $query->result_array();
        
        // get route
        $tours = get_route($tours);
        
        // set price
        $tours = $this->get_tour_price_from_4_list($tours, $startdate);
        
        // set promotion
        $tours = $this->get_tour_promotions_4_list($tours, $startdate);
        
        // set promotion from Best Price
        $tours = $this->get_tour_bpv_promotion_4_list($tours);
        
        return $tours;
    }
    
    /**
    *  get tour price from
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_tour_price_from_4_list($tours, $startdate)
    {
 
        $tour_ids = array();
        
        foreach ($tours as $tour)
        {
            $tour_ids[] = $tour['id'];
        }
        
        if (count($tours) == 0)
            return $tours;
        
        $startdate = format_bpv_date($startdate);
        
        $select_sql = 'tpf.tour_id, tpf.price_origin, IF(p.id IS NOT NULL,tpf.price_from,tpf.price_origin) as price_from';
        
        $this->db->select($select_sql, false);
        
        $this->db->from('tour_price_froms as tpf');
        
        $this->db->join('promotions as p', 'p.id = tpf.promotion_id AND ' . sql_join_tour_promotion($startdate), 'left outer');
        
        $this->db->where_in('tpf.tour_id', $tour_ids);
        
        $this->db->where('tpf.date', $startdate);
        
        $this->db->order_by('tpf.tour_id', 'asc');
        
        $this->db->order_by('price_from', 'asc');
        
        $query = $this->db->get();
        
        $price_froms = $query->result_array();
        
        foreach ($tours as $key => $value)
        {
            foreach ($price_froms as $price_from)
            {
                if ($value['id'] == $price_from['tour_id'])
                {
                    $value['price_origin'] = $price_from['price_origin'];
                    $value['price_from'] = $price_from['price_from'];
                    break;
                }
            }
            
            $tours[$key] = $value;
        }
        
        return $tours;
    }
    
    /**
    *  get tour promotions
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_tour_promotions_4_list($tours, $startdate)
    {
        if(empty($startdate)) {
            $startdate = date(DB_DATE_FORMAT); // today
        }
        
        $startdate = format_bpv_date($startdate);
        
        $tour_ids = array();
        
        foreach ($tours as $tour)
        {
            $tour_ids[] = $tour['id'];
        }
        
        if (! empty($tour_ids))
        {
            
            $this->db->select('p.*, pt.tour_id');
            
            $this->db->join('promotions as p', 'p.id = pt.promotion_id');
            
            $this->db->where_in('pt.tour_id', $tour_ids);
            
            $this->db->where('p.book_date_to >=', $startdate);
            $this->db->where('p.stay_date_to >=', $startdate);
            $this->db->where('p.deleted != ', DELETED);
            
            $this->db->where('p.show_on_web', STATUS_ACTIVE);
            
            $this->db->group_by('pt.tour_id');
            
            $this->db->order_by('p.id');
            
            $query = $this->db->get('promotion_tours pt');
            
            $promotions = $query->result_array();
            
            foreach ($tours as $key => $tour)
            {
                
                $pros = array();
                
                foreach ($promotions as $pro)
                {
                    
                    if ($pro['tour_id'] == $tour['id'])
                    {
                        
                        $pros[] = $pro;
                    }
                }
                
                $tour['promotions'] = $pros;
                
                $tours[$key] = $tour;
            }
        }
        
        return $tours;
    }
    
    /**
      *  get_tour_bpv_promotion_4_list
      *
      *  @author toanlk
      *  @since  Sep 18, 2014
      */
    function get_tour_bpv_promotion_4_list($tours) {
    
        $today = date(DB_DATE_FORMAT);
    
        $tour_ids = array();
        foreach ($tours as $tour) {
            if( !in_array($tour['id'], $tour_ids) ) {
                $tour_ids[] = $tour['id'];
            }
        }
    
        if(count($tour_ids) > 0){
    
            $this->db->distinct();
            $this->db->select('b.*, bt.tour_id');
            $this->db->from('bpv_promotion_tours bt');
            $this->db->join('bpv_promotions b', 'b.id = bt.bpv_promotion_id');
            $this->db->where_in('bt.tour_id', $tour_ids);
            $this->db->where('b.status', STATUS_ACTIVE);
            $this->db->where('b.deleted !=', DELETED);
            $this->db->where('b.expired_date >=', $today);
            $this->db->where('b.public', STATUS_ACTIVE);
            $this->db->order_by('b.id','asc');
    
            $query = $this->db->get();
            $bpv_promotions = $query->result_array();
    
            foreach ($tours as $key=>$tour){
    
                $pros = array();
    
                foreach ($bpv_promotions as $pro){
    
                    if($pro['tour_id'] == $tour['id']){
    
                        $pros[] = $pro;
    
                    }
    
                }
    
                $tour['bpv_promotions'] = $pros;
    
                $tours[$key] = $tour;
    
            }
    
        }
    
    
        return $tours;
    }
    
    /**
    *  get filter price
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_search_filter_prices($search_criteria)
    {
        $startdate = $search_criteria['startdate'];
    
        $startdate = format_bpv_date($startdate);
    
        $this->db->select('tpf.mid_range_index, count(*) as number, t.id');
        
        $this->db->from('tours as t');
        
        $this->db->join('tour_price_froms tpf', 'tpf.tour_id = t.id AND tpf.date = "' . $startdate . '"');
        
        $this->_build_search_condition($search_criteria, true);
    
        $this->db->group_by('tpf.mid_range_index');
    
        $query = $this->db->get();
    
        $results = $query->result_array();
        
        //echo "<pre>";print_r($this->db->last_query());echo "</pre>";exit();
    
        return $results;
    }
    
    /**
    *  get filter duration
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_search_filter_durations($search_criteria)
    {
        $this->db->select('t.duration, count(*) as number');
    
        $this->db->from('tours as t');
    
        $this->_build_search_condition($search_criteria, true);
    
        $this->db->group_by('t.duration');
    
        $query = $this->db->get();
    
        $results = $query->result_array();
    
        return $results;
    }
    
    /**
      *  get filter departing
      *
      *  @author toanlk
      *  @since  Sep 22, 2014
      */
    function get_search_filter_departing($search_criteria)
    {
        $this->db->select('td.destination_id as id, count(*) as number');
    	
    	$this->db->from('tours t');
    	
    	$this->db->join('tour_departures td', 't.id = td.tour_id');
    	
    	$this->_build_search_condition($search_criteria, true);
    	
    	$this->db->group_by('td.destination_id');
    	
    	$query = $this->db->get();
    	
    	$results = $query->result_array();
    	
    	return $results;
    }
    
    /**
    *  get filter categories
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_search_filter_categories($search_criteria)
    {
        $this->db->select('tc.category_id, count(*) as number');
        
        $this->db->from('tours t');
        
        if (empty($search_criteria['category'])) 
        {
            $this->db->join('tour_categories as tc', 't.id = tc.tour_id');
        }
        
        $this->_build_search_condition($search_criteria, true);
    
        $this->db->group_by('tc.category_id');
    
        $query = $this->db->get();
    
        $results = $query->result_array();
    
        return $results;
    }
    
    /**
    *  Set search criteria for tour filters
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    /* function _set_filter_condition($search_criteria, $column_name = 't.id') {
        
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        // Set tour destination
        if (! empty($search_criteria['des_id']))
        {
            $this->db->join('destination_tours as dt', 'dt.tour_id = '.$column_name);
            $this->db->where('dt.destination_id', $search_criteria['des_id']);
        }
        
        // Set tour departure
        if (! empty($search_criteria['dep_id']))
        {
            $this->db->join('tour_departures as td', 'td.tour_id = '.$column_name);
            $this->db->where('td.destination_id', $search_criteria['dep_id']);
        }
        
        // domestic tours
        if (isset($search_criteria['is_outbound']) && is_numeric($search_criteria['is_outbound']))
        {
            $this->db->where_in('t.is_outbound', $search_criteria['is_outbound']);
        }
        
        // Set tour category
         if (! empty($search_criteria['category']) && $column_name !='tc.tour_id')
        {
            $this->db->join('tour_categories as tc', 'tc.tour_id = t.id');
            $this->db->where('tc.category_id', $search_criteria['category']);
        }
    } */
    
    /**
    *  count tour search results
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function count_search_tours($search_criteria)
    {
        $startdate = $search_criteria['startdate'];
        
        $startdate = format_bpv_date($startdate);
        
        $this->db->_protect_identifiers = false;
        
        $this->db->select('t.id');
        
        $this->db->from('tours as t');
        
        $this->db->join('tour_price_froms tpf', 'tpf.tour_id = t.id AND tpf.date = "' . $startdate . '"', 'left outer');
        
        $this->_build_search_condition($search_criteria);
        
        $this->db->group_by('t.id');
        
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        $cnt = count($results);
        
        return $cnt;
    }

    /**
    *  _build_search_condition()
    *
    *  set search criteria
    *  
    *  $is_search_only: only for search parameter 
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function _build_search_condition($search_criteria, $is_search_only = false)
    {
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        if (! empty($search_criteria['duration']) || (! empty($search_criteria['f_duration']) && ! $is_search_only))
        {
            $dur = ! empty($search_criteria['f_duration']) ? $search_criteria['f_duration'] : $search_criteria['duration'];
            
            $durations = explode(',', $dur);
            
            if (count($durations) > 0)
            {
                $is_over = false;
                $sql_d = '';
                foreach ($durations as $d)
                {
                    if ($d > TOUR_MAX_DURATION)
                    {
                        $is_over = true;
                        break;
                    }
                    $sql_d .= $d.',';
                }
                $sql_d = rtrim($sql_d, ',');
                
                if (count($durations) == 1 && $is_over)
                {
                    $this->db->where('t.duration >', TOUR_MAX_DURATION);
                }
                else
                {
                    
                    if($is_over)
                    {
                        $where = "(t.duration IN (".$sql_d.") OR t.duration > ".TOUR_MAX_DURATION.")";

                        $this->db->where($where);
                    } else {
                        $this->db->where_in('t.duration', $durations);
                    }
                }
            }
        }
        
        // Set tour destination
        if (! empty($search_criteria['des_id']))
        {
            $this->db->join('destination_tours as dt', 'dt.tour_id = t.id');
            $this->db->where('dt.destination_id', $search_criteria['des_id']);
            $this->db->where('dt.is_land_tour', 1);
        }
        
        // domestic tours
        if (isset($search_criteria['is_outbound']) && is_numeric($search_criteria['is_outbound']))
        {
            $this->db->where_in('t.is_outbound', $search_criteria['is_outbound']);
        }
        
        // Set tour departure
        if (! empty($search_criteria['dep_id']) || (! empty($search_criteria['f_departing']) && ! $is_search_only))
        {
            $dep_id = !empty($search_criteria['f_departing']) ? $search_criteria['f_departing'] : $search_criteria['dep_id'];
            
            $this->db->join('tour_departures as td', 'td.tour_id = t.id');
            $this->db->where('td.destination_id', $dep_id);
        }
        
        // Set tour category
        if (! empty($search_criteria['category']))
        {
            $categories = explode(',', $search_criteria['category']);
            
            $this->db->join('tour_categories as tc', 'tc.tour_id = t.id');
            $this->db->where_in('tc.category_id', $categories);
        }
        
        // Set price range
        if (! empty($search_criteria['price']) && ! $is_search_only)
        {
            $prices = explode(',', $search_criteria['price']);
            
            if (count($prices) > 0)
            {
                $this->db->where_in('tpf.mid_range_index', $prices);
            }
        }
    }

    /**
    *  search tour
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function search_tours($search_criteria)
    {
        $startdate = $search_criteria['startdate'];
        
        $startdate = format_bpv_date($startdate);
        
        $paging_config = $this->config->item('paging_config');
        
        $offset = ! empty($search_criteria['page']) ? $search_criteria['page'] : 0;
        
        $select_sql = 't.id, t.cruise_id, t.name, t.code, t.departure_type, t.departure_date_type, t.departure_specific_date, t.routes, t.duration, t.night, t.url_title, t.description, t.picture, t.review_score, t.review_number, tpf.price_origin, IF(p.id IS NOT NULL, tpf.price_from, tpf.price_origin) as price_from';
        
        $this->db->_protect_identifiers = false;
        
        $this->db->select($select_sql);
        
        $this->db->from('tours as t');
        
        $this->db->join('tour_price_froms tpf', 'tpf.tour_id = t.id AND tpf.date = "' . $startdate . '"', 'left outer');
        
        $this->db->join('promotions as p', 'p.id = tpf.promotion_id AND ' . sql_join_tour_promotion($startdate), 'left outer');
        
        $this->_build_search_condition($search_criteria);
        
        if (isset($search_criteria['sort']))
        {
            if ($search_criteria['sort'] == SORT_BY_POPULAR)
            {
                $this->db->order_by('t.position', 'asc');
            }
            
            if ($search_criteria['sort'] == SORT_BY_PRICE)
            {
                $this->db->order_by('IF(ISNULL(price_from),1,0),price_from');
            }
            
            if ($search_criteria['sort'] == SORT_BY_REVIEW)
            {
                $this->db->order_by('t.review_score', 'desc');
            }
            
            if ($search_criteria['sort'] == SORT_BY_DURATION)
            {
                $this->db->order_by('t.duration', 'asc');
            }

        }
        
        $this->db->limit($paging_config['per_page'], $offset);
        
        $this->db->order_by('t.position', 'asc');
        $this->db->group_by('t.id');
        
        $query = $this->db->get();
        
        $tours = $query->result_array();
        
        // set promotion
        $tours = $this->get_tour_promotions_4_list($tours, $startdate);
        
        // set promotion from Best Price
        $tours = $this->get_tour_bpv_promotion_4_list($tours);
        
        // get route
   		$tours = get_route($tours);
   		
   		// get departure date
   		$tours = $this->get_departure_date($tours, $startdate);
   		
   		// get transportations
   		$tours = $this->get_transportations($tours);
        
        return $tours;
    }
    
    /**
      *  get tour transportations
      *
      *  @author toanlk
      *  @since  Oct 10, 2014
      */
    function get_transportations($tours)
    {
        foreach ($tours as $key => $tour)
        {
            $this->db->select('transportations');
            
            $this->db->where('tour_id', $tour['id']);
            
            $this->db->where('deleted !=', DELETED);
            
            $this->db->group_by('transportations');
            
            $query = $this->db->get('itineraries');
            
            $itineraries =  $query->result_array();
            
            $trans = array();
            foreach ($itineraries as $value)
            {
                $tx_trans = explode('-', $value['transportations']);
                
                foreach ($tx_trans as $tx)
                {
                    if (! in_array($tx, $trans))
                    {
                        $trans[] = $tx;
                    }
                }
            }
            
            $str = '';
            foreach ($trans as $value) {
                $str .= $value.'-';
            }
            
            $tour['transportations'] = rtrim($str, '-');
            
            $tours[$key] = $tour;
        }
        
        return $tours;
    }
    
    /**
    *  Get default cancellation of tour
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_cancellation_of_tour($tour_id)
    {
        $this->db->select('ca.*');
        
        $this->db->from('tours t');
        
        $this->db->join('cancellations as ca', 'ca.id = t.cancellation_id');
        
        $this->db->where('t.deleted != ', DELETED);
        
        $this->db->where('t.id', $tour_id);
        
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        if (count($results) > 0)
        {
            return $results[0];
        }
        
        return '';
    }

    /**
    *  get price table
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_tour_rate_actions($tour, $tour_departures, $submit_startdate = null)
    {   
    	$tour_id = $tour['id'];
    	
        $today = format_bpv_date(date(DB_DATE_FORMAT));
        
        foreach ($tour_departures as $k => $departure)
        {
            if ($departure['departure_date_type'] == DEPARTURE_SPECIFIC_DATES)
            {
                $departure_dates = explode(';', $departure['departure_specific_date']);
                
                foreach ($departure_dates as $str_date)
                {
                    $str_date = str_replace('-', '/', $str_date);
                    
                    $startdate = format_bpv_date($str_date);
                    
                    // eliminate rate in the past
                    if(strtotime($startdate) < strtotime($today)) continue;
                    
                    $departure_id = null;
                    if ($tour['departure_type'] == MULTIPLE_DEPARTING_FROM)
                    {
                        $departure_id = $departure['id'];
                    }
                    
                    // get accommodation rates
                    $enddate = $startdate;          
                    $check_rate_info = array(
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                        'adults' => 1,
                        'children' => 0,
                        'infants' => 0,
                        'departure_id' => $departure_id,
                    );
                    
                    $accommodation_rates = _load_tour_accommodation_rates($tour['id'], $check_rate_info);

                    $accommodations = array();
                    
                    $accom_ids = array();
                    
                    foreach ($accommodation_rates as $value)
                    {
                        if(!in_array($value['id'], $accom_ids)) {
                            $basic_rate = !empty($value['adult_rate']) ? $value['adult_rate'] : $value['adult_basic_rate'];
                            $accommodations[] = array(
                                'date' => $startdate,
                                'id' => $value['id'],
                                'price_from' => $basic_rate
                            );
                            
                            $accom_ids[] = $value['id'];
                        }
                    }
                    
                    $obj = array(
                        'date' => $startdate,
                        'accommodations' => $accommodations
                    );
                    
                    $departure['dates'][] = $obj;
                }
            }
            elseif ($departure['departure_date_type'] == DEPARTURE_SPECIFIC_WEEKDAYS)
            {
                $departure_id = null;
                if ($tour['departure_type'] == MULTIPLE_DEPARTING_FROM)
                {
                    $departure_id = $departure['id'];
                }
                
                $this->db->where('tour_departure_id', $departure['id']);
                    
                $this->db->where('tour_id', $tour_id);

                $this->db->where('end_date >=', $today);
                
                $query = $this->db->get('tour_departure_dates');
                
                $results = $query->result_array();
                
                foreach ($results as $key => $value) {
                    
                    $startTime = $value['start_date'];
                    
                    // Loop between timestamps, 1 day at a time 
                    while (strtotime($startTime) <= strtotime($value['end_date']))
                    {
                    	$day = date('w', strtotime($startTime));
                        
                        if (is_bit_value_contain ( $value ['weekdays'], $day ) && strtotime ( $startTime ) >= strtotime ( $today )) {
							
							// get accommodation rates
                            $check_rate_info = array(
                                'startdate' => $startTime,
                                'enddate' => $startTime,
                                'adults' => 1,
                                'children' => 0,
                                'infants' => 0,
                                'departure_id' => $departure_id,
                            );
                            
                            $accommodation_rates = _load_tour_accommodation_rates($tour['id'], $check_rate_info);
							
							$accommodations = array ();
							
                            foreach ($accommodation_rates as $rate)
                            {
                                $basic_rate = !empty($rate['adult_rate']) ? $rate['adult_rate'] : $rate['adult_basic_rate'];
                                $accommodations[] = array(
                                    'date' => $startTime,
                                    'id' => $rate['id'],
                                    'price_from' => $basic_rate
                                );
                            }
							
							$obj = array (
									'date' => date ( DATE_FORMAT_JS, strtotime ( $startTime ) ) . ' ' . get_day_of_week ( $startTime ),
									'accommodations' => $accommodations 
							);
							
							$value ['days'] [] = $obj;
						}
                        
                        $startTime = date("Y-m-d", strtotime("+1 day", strtotime($startTime)));
                    }

                    $results[$key] = $value;
                } 
                
                $departure['dates'] = $results;                
                
            }
            elseif ($departure['departure_date_type'] == DEPARTURE_DAILY)
            {
                $this->db->select('tr.id, tr.tour_id, tr.start_date, tr.end_date, tr.week_day');
                
                if ($tour['departure_type'] == MULTIPLE_DEPARTING_FROM)
                {
                    $this->db->join('tour_departure_rates tdr', 'tdr.tour_rate_action_id =tr.id', 'left outer');
                    
                    $this->db->where('tdr.tour_departure_id', $departure['id']);
                }
                
                $this->db->where('tr.tour_id', $tour_id);
                
                $this->db->order_by('tr.start_date', 'asc');
                
                $query = $this->db->get('tour_rate_actions tr');
                
                $tour_rate_actions = $query->result_array();
                
                $accommodations = $this->get_accommodations($tour_id);
                
                foreach ($tour_rate_actions as $i => $rate)
                {
                	$startTime = $rate['start_date'];
                
                	$d_submit = strtotime(date('Y-d-m', strtotime($submit_startdate)));
                	$d_start = strtotime($rate['start_date']);
                	$d_end = !empty($rate['end_date']) ? strtotime($rate['end_date']) : null;
                	
                	if(!empty($submit_startdate) 
                			&& $d_submit >= $d_start && ( $d_submit <= $d_end || empty($d_end) ) ) {
                		$startTime = $submit_startdate;
                	}
                    
                    // get accommodation rates
                    $check_rate_info = array(
                        'startdate' => $startTime,
                        'enddate' => $startTime,
                        'adults' => 1,
                        'children' => 0,
                        'infants' => 0,
                        //'departure_id' => $departure['id'],
                    );
                    
                    $accommodation_rates = _load_tour_accommodation_rates($tour['id'], $check_rate_info);
                
                    $accommodations = array ();
                    	
                    foreach ($accommodation_rates as $rate)
                    {
                        $basic_rate = !empty($rate['adult_rate']) ? $rate['adult_rate'] : $rate['adult_basic_rate'];
                        $accommodations[] = array(
                            'date' => $startTime,
                            'id' => $rate['id'],
                            'price_from' => $basic_rate
                        );
                    }
                    
                    $tour_rate_actions[$i]['accommodations'] = $accommodations;
                    
                    /* foreach ($accommodations as $k => $value)
                    {
                        
                        $this->db->select('1_pax_rate as price_from');
                        
                        $this->db->where('accommodation_id', $value['id']);
                        
                        $this->db->where('tour_rate_action_id', $rate['id']);
                        
                        $this->db->where('tour_id', $tour_id);
                        
                        $query = $this->db->get('tour_room_rate_actions');
                        
                        $tour_rates = $query->result_array();
                        
                        $price_from = 0;
                        
                        if (! empty($tour_rates))
                        {
                            $price_from = $tour_rates[0]['price_from'];
                        }
                        
                        $accommodations[$k]['price_from'] = $price_from;
                    }
                    
                    $tour_rate_actions[$i]['accommodations'] = $accommodations; */
                }
                
                $departure['dates'] = $tour_rate_actions;
            }
            
            $tour_departures[$k] = $departure;
        }
        
        
        return $tour_departures;
    }
    
    /**
    *  get tour accommodations
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_accommodations($tour_id) {
        
        $this->db->select('a.id, a.name');
        
        $this->db->where('a.deleted !=', DELETED);
        
        $this->db->where('a.tour_id', $tour_id);
        
        $this->db->order_by('a.position', 'asc');
        
        $query = $this->db->get('accommodations as a');
        
        $accommodations = $query->result_array();
        
        return $accommodations;
    }
    
    /**
    *  Get similar tours
    *
    *  @author toanlk
    *  @since  Sep 11, 2014
    */
    function get_similar_tours($tour, $startdate = null, $is_mobile = false, $limit = 4)
    {   
        $this->db->select('d.id, d.name');
        
        $this->db->join('destinations as d', 'd.id = dt.destination_id');
        
        $this->db->where('dt.is_land_tour', 1);
        
        $this->db->where('dt.tour_id', $tour['id']);
        
        if($tour['is_outbound'] == TOUR_OUTBOUND) {
            $this->db->where('d.type', DESTINATION_TYPE_CITY);
        } else {
            $this->db->where('d.id !=', DESTINATION_VIETNAM);
            $this->db->where('d.type', DESTINATION_TYPE_COUNTRY);
        }
        
        $this->db->order_by("d.position", "asc");
        
        $this->db->group_by("d.id");
            
        $query = $this->db->get('destination_tours dt');
        
        $destinations = $query->result_array();
        
        $destination_id = null;
        
        if (! empty($destinations))
        {
            $destination_id = $destinations[0]['id'];
        }
        
        $this->db->select('t.id, t.name, t.url_title, t.duration, t.picture, t.review_score, t.review_number');
    
        $this->db->from('destination_tours as dt');
        
        $this->db->join('tours as t', 'dt.tour_id = t.id', 'left outer');
        
        $this->db->where('dt.destination_id', $destination_id);
    
        $this->db->where('t.status', STATUS_ACTIVE);
    
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.is_outbound', $tour['is_outbound']);
    
        $this->db->where('t.id !=', $tour['id']);
    
        $this->db->order_by("t.position", "asc");
        
        $this->db->limit($limit);
    
        $query = $this->db->get();
    
        $results = $query->result_array();
    
        if (! empty($startdate))
        {
            $results = $this->get_tour_price_from_4_list($results, $startdate);
        }
    
        return $results;
    }
    
    /**
     * Khuyenpv: 11.09.2014
     * Count number of tour in each depature destination based on options parameters
     */
	function count_tour_departure($options){
    	
    	$this->db->select('td.destination_id, count(*) as nr_tour');
    	
    	$this->db->from('tour_departures td');
    	
    	$this->db->join('tours t', 't.id = td.tour_id');
    	
    	$this->db->where('t.status', STATUS_ACTIVE);
    	
    	$this->db->where('t.deleted !=', DELETED);
    	
    	if(isset($options['is_outbound'])){
    		
    		$this->db->where('t.is_outbound', $options['is_outbound']);
    		
    	}
    	
    	if(isset($options['des_id'])){
    		
    		$sql_cond = "EXISTS (SELECT 1 FROM destination_tours des_tour WHERE des_tour.tour_id = t.id AND des_tour.is_land_tour = 1 AND des_tour.destination_id = ".$options['des_id'].")";
    		
    		$this->db->where($sql_cond, NULL, false);
    		
    	}
    	
    	if(isset($options['category'])){
    		
    		$sql_cond = "EXISTS (SELECT 1 FROM tour_categories tour_cat WHERE tour_cat.tour_id = t.id AND tour_cat.category_id = ".$options['category'].")";
    		
    		$this->db->where($sql_cond, NULL, false);
    		
    	}
    	
    	$this->db->group_by('td.destination_id');
    	
    	$query = $this->db->get();
    	
    	$results = $query->result_array();
    	
    	return $results;
    	
    }
    
    /**
     * Khuyenpv: 11.09.2014
     * Count number of tour in each duration
     */
    function count_tour_durations($options){
    	
    	$this->db->select('t.duration, count(*) as nr_tour');
    	
    	$this->db->from('tours as t');
    	
    	$this->db->where('t.deleted !=', DELETED);
    	
    	$this->db->where('t.status', STATUS_ACTIVE);
    	
    	if(isset($options['is_outbound'])){
    	
    		$this->db->where('t.is_outbound', $options['is_outbound']);
    	
    	}
    	
    	if(isset($options['des_id'])){
    	
    		$sql_cond = "EXISTS (SELECT 1 FROM destination_tours des_tour WHERE des_tour.tour_id = t.id AND des_tour.is_land_tour = 1 AND des_tour.destination_id = ".$options['des_id'].")";
    	
    		$this->db->where($sql_cond, NULL, false);
    	
    	}
    	
    	if(isset($options['category'])){
    	
    		$sql_cond = "EXISTS (SELECT 1 FROM tour_categories tour_cat WHERE tour_cat.tour_id = t.id AND tour_cat.category_id = ".$options['category'].")";
    	
    		$this->db->where($sql_cond, NULL, false);
    	
    	}
    	
    	$this->db->group_by('t.duration');
    	
    	$query = $this->db->get();
    	
    	$results = $query->result_array();
    	
    	return $results;
    }
    
    /**
     * count tours of category
     * 
     * Nguyen Son
     * @param id of category :$category_id
     */
    
    function count_tour_category($category_id){
    	
    	$this->db->select('count(*) as n_tour');
    	
    	$this->db->from('tour_categories tc');
    	
    	$this->db->join('tours t', 't.id = tc.tour_id');
    	
    	$this->db->where('tc.category_id', $category_id);
    	
    	$this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $query = $this->db->get();
        
        $tours = $query->result_array();
        
        if(count($tours) >0){

        	return $tours[0];
        }
    	
    }
    
    /**
      *  get tour departure date
      *
      *  @author toanlk
      *  @since  Sep 17, 2014
      */
    function get_departure_date($tours, $startdate)
    {
        foreach ($tours as $i => $tour)
        {
            $tour_departures = $this->get_tour_departures($tour['id']);
            
            foreach ($tour_departures as $k => $depart)
            {
                if ($depart['departure_date_type'] == DEPARTURE_SPECIFIC_WEEKDAYS)
                {
                    $this->db->where('tour_departure_id', $depart['id']);
                    
                    $this->db->where('tour_id', $tour['id']);
                    
                    $this->db->where('start_date <=', $startdate);
                    
                    $this->db->where('end_date >=', $startdate);
                    
                    $query = $this->db->get('tour_departure_dates');
                    
                    $result = $query->result_array();
                    
                    $depart['dates'] = $result;
                    
                    $tour_departures[$k] = $depart;
                }
            }
            
            $tour['tour_departures'] = $tour_departures;
            
            $tours[$i] = $tour;
        }
        
        return $tours;
    }
    
    /**
      *  get_tour_details
      *
      *  @author toanlk
      *  @since  Sep 18, 2014
      */
    function get_tour_details($id, $startdate = null)
    {
        if (empty($startdate))
        {
            $today = date('d-m-Y');
            $tommorow = date('d/m/Y', strtotime($today . " +1 day"));
            $startdate = $tommorow;
        }
        
        $startdate = format_bpv_date($startdate);
        
        $this->db->select('t.*, c.name as cruise_name, c.id as cruise_id, c.star, c.url_title as cruise_url_title');
        
        $this->db->from('tours as t');
        
        $this->db->join('cruises as c', 'c.id = t.cruise_id', 'left outer');
        
        $this->db->where('t.id', $id);
        
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        if (! empty($results))
        {
            
            $tour = $results[0];
            
            $tour['itinerary'] = $this->get_itineraries($tour['id']);
            
            $tour['destinations'] = $this->get_destination_tours($tour);
            
            // get route
            $tour_routes = get_route(array($tour));

            $tour['route'] = $tour_routes[0]['route'];
            
            // get departure date
            $tour_departs = $this->get_departure_date(array($tour), $startdate); 

            if(!empty($tour_departs[0]['tour_departures'])) {
                $tour['tour_departures'] = $tour_departs[0]['tour_departures'];
            }
            
            return $tour;
        }
        
        return null;
    }
    
    /**
      *  get_destination_tours
      *
      *  @author toanlk
      *  @since  Oct 14, 2014
      */
    function get_destination_tours($tour)
    {
        $this->db->select('d.id, d.name, d.url_title, d.is_tour_destination_group, d.is_tour_highlight_destination');
        
        $this->db->join('destinations as d', 'd.id = dt.destination_id');
        
        $this->db->where('dt.is_land_tour', 1);
        
        //$this->db->or_where('d.is_tour_destination_group', 1);
        
        $sql = '(d.is_tour_highlight_destination = 1 OR d.is_tour_destination_group=1)';
        
        $this->db->where($sql);
        
        $this->db->where('dt.tour_id', $tour['id']);
        
        $this->db->order_by('d.is_tour_destination_group', 'desc');
        
        $this->db->order_by('d.position', 'asc');
        
        $query = $this->db->get('destination_tours dt');
        
        $results = $query->result_array();
 
        $destinations = array();

        /*
         * For navigation: Mien Nam -> Phu Quoc -> Tour_Phu_Quoc...
         * 
         * Mien Nam: group destionation
         * Phu Quoc: highlight destination
         */
        
        // get destination group
        foreach ($results as $des)
        {
            if ($des['is_tour_destination_group'] == 1 && !in_array($des, $destinations))
            {
                $destinations[] = $des;
                break;
            }
        }
        
        // get destination highlights
        foreach ($results as $des)
        {
            if ($des['is_tour_highlight_destination'] == 1 && $des['is_tour_destination_group'] != 1 && !in_array($des, $destinations))
            {
                $destinations[] = $des;
                break;
            }
        }
        
        return $destinations;
    }
    
    /**
      *  get_itineraries
      *
      *  @author toanlk
      *  @since  Sep 18, 2014
      */
    function get_itineraries($tour_id, $tour_departure = null)
    {
        if(!empty($tour_departure)) {
             
            $this->db->join('tour_departure_itineraries as tdi', 'tdi.itinerary_id = i.id', 'left outer');
             
            $this->db->where('tdi.tour_departure_id', $tour_departure);
        }
         
        $this->db->where('i.tour_id', $tour_id);
    
        $this->db->where('i.deleted !=', DELETED);
    
        $this->db->order_by('i.position', 'asc');
        $query = $this->db->get('itineraries as i');
    
        $itineraries =  $query->result_array();
    
        foreach ($itineraries as $k => $itinerary) {
            $itinerary['photos'] = $this->get_itinerary_photos($itinerary['photos']);
            	
            $itineraries[$k] = $itinerary;
        }
    
        return $itineraries;
    }
    
    /**
      *  get_itinerary_photos
      *
      *  @author toanlk
      *  @since  Sep 18, 2014
      */
    function get_itinerary_photos($photo_ids) {
    
        $photo_ids = explode('-', $photo_ids);
    
        $this->db->select('name, caption, cruise_id, tour_id');
    
        $this->db->where_in('id', $photo_ids);
    
        $this->db->order_by('position');
    
        $query = $this->db->get('photos');
    
        return $query->result_array();
    }
    
    /**
      *  get_tour_details_check_rate
      *
      *  @author toanlk
      *  @since  Oct 8, 2014
      */
    function get_tour_details_check_rate($tour_id, $departure_id = null){
    
        $this->db->select('t.id, t.name, t.url_title, t.cruise_id, t.service_excludes, 
            t.service_includes, t.extra_cancellation, t.infant_age_util, 
            t.infants_policy, t.children_age_to, t.children_policy');

        $this->db->where('t.deleted !=', DELETED);
        $this->db->where('t.status', STATUS_ACTIVE);
        $this->db->where('t.id', $tour_id);
        
        $query = $this->db->get('tours t');
        
        $results = $query->result_array();
        
        if (! empty($results))
        {
            
            $tour = $results[0];
            
            // set service inclusion and service exlusion depend on departure
            if (! empty($departure_id) && is_numeric($departure_id))
            {
                $tour_departures = $this->get_tour_departures($tour['id'], $departure_id);
                
                if (! empty($tour_departures))
                {
                    $tour_departure = $tour_departures[0];
                    
                    if (! empty($tour_departure['service_includes']))
                    {
                        $tour['service_includes'] = $tour_departure['service_includes'];
                    }
                    
                    if (! empty($tour_departure['service_excludes']))
                    {
                        $tour['service_excludes'] = $tour_departure['service_excludes'];
                    }
                }
            }
            
            return $tour;
        }
        
        return null;
    }
}