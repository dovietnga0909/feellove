<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tour_Model extends CI_Model {	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_tour_details($id, $is_land_tour = false)
    {
        $this->db->select('t.*, c.name as cruise_name, c.id as cruise_id, c.star, c.url_title as cruise_url_title');
            
        $this->db->from('tours as t');
        
        $this->db->join('cruises as c', 'c.id = t.cruise_id', 'left outer');
        
        $this->db->where('t.id', $id);
        
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        if (count($results) > 0)
        {
            
            $tour = $results[0];
            
            $tour['itinerary'] = $this->get_itineraries($tour['id']);
            
            $tours = get_route(array($tour));
            
            return $tours[0];
        }
        
        return null;
    }
	
	function get_tour_photos($tour_id){
	
		$this->db->select('id, name, caption, tour_photo_type as type, cruise_id, tour_id, width, height');
		$this->db->where('tour_id', $tour_id);
	
		$this->db->order_by('position');
	
		$query = $this->db->get('photos');
	
		return $query->result_array();
	}
	
	/*
	 * Deprecated : use route field in database (toanlk - 11/9/2014)
	 *  
	 * function get_tour_route($tours) {
	    
	    foreach ($tours as $k => $tour)
	    {
	        $this->db->select('d.id, d.name, d.url_title');
	        
	        $this->db->join('destinations as d', 'd.id = dt.destination_id', 'left outer');
	        
	        $this->db->where('dt.tour_id', $tour['id']);
	        
	        $this->db->where('dt.hidden', 0);
	        
	        $this->db->where('dt.is_show_on_route', STATUS_ACTIVE);
	        
	        $this->db->order_by('dt.position', 'asc');
	        
	        $query = $this->db->get('destination_tours dt');
	        
	        $route = $query->result_array();
	        
	        $tours[$k]['route'] = $route;
	    }
	    
	    $tours = get_route($tours);

		return $tours;
	} */
	
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
	
	function get_itinerary_photos($photo_ids) {
		
		$photo_ids = explode('-', $photo_ids);
		
		$this->db->select('name, caption, cruise_id, tour_id');
		
		$this->db->where_in('id', $photo_ids);
		
		$this->db->order_by('position');
		
		$query = $this->db->get('photos');
		
		return $query->result_array();
	}
	
	function get_accommodations($tour_id) {
		
		$this->db->where('tour_id', $tour_id);
		
		$this->db->where('deleted !=', DELETED);
		
		$this->db->order_by('position', 'asc');
		$query = $this->db->get('accommodations');
		
		return $query->result_array();
	}
	
    /**
    *  get accommodation rates
    *
    *  @author toanlk
    *  @since  Sep 15, 2014
    */
    function get_accommodation_rates($tour_id, $startdate, $enddate, $departure_id = null)
    {
        $startdate = format_bpv_date($startdate);
    
        $enddate = format_bpv_date($enddate);
    
        $this->db->where('tour_id', $tour_id);
    
        $this->db->where('date >=', date(DB_DATE_FORMAT, strtotime($startdate)));
    
        $this->db->where('date <=', date(DB_DATE_FORMAT, strtotime($enddate)));
    
        if(!empty($departure_id))
        {
            $this->db->where('tour_departure_id', $departure_id);
        }
    
        $query = $this->db->get('tour_rates');
    
        $results = $query->result_array();
    
        return $results;
    }
	
    /**
    *  get cancellation of tour
    *
    *  @author toanlk
    *  @since  Sep 15, 2014
    */
    function get_cancellation_of_tour($tour_id, $is_cruise = true)
    {
        $this->db->select('c.*');
        
        $this->db->from('tours t');
        
        if ($is_cruise)
        {
            $this->db->join('cruises as cr', 'cr.id = t.cruise_id');
            
            $this->db->join('cancellations as c', 'c.id = cr.cancellation_id');
            
            $this->db->where('c.deleted != ', DELETED);
        }
        else
        {
            $this->db->join('cancellations as c', 'c.id = t.cancellation_id');
        }

        $this->db->where('t.id', $tour_id);
    
        $query = $this->db->get();
    
        $results = $query->result_array();
    
        if (count($results) > 0)
        {
            return $results[0];
        }
    
        return null;
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
	
    /**
    *  get tour promotions
    *
    *  @author toanlk
    *  @since  Sep 15, 2014
    */
    function get_tour_promotions($tour_id, $startdate, $enddate, $departure_id = null)
    {
        $today = date(DB_DATE_FORMAT);
    
        // calculate day-before-staying date
        $today_time = strtotime($today);
    
        $stay_time = strtotime($startdate);
    
        $day_before = $stay_time - $today_time;
    
        $day_before = round($day_before / (60 * 60 * 24));
    
        $this->db->select('p.*,pt.id as tour_promotion_id, c.id as cancellation_id, c.name as cancellation_name, c.fit, c.fit_cutoff, c.git_cutoff, c.content');
    
        $this->db->from('promotion_tours pt');
    
        $this->db->join('promotions p', 'p.id = pt.promotion_id');
    
        $this->db->join('cancellations c', 'c.id = p.cancellation_id');
        
        if(!empty($departure_id))
        {
            $this->db->where('tour_departure_id', $departure_id);
        }
    
        $this->db->where('pt.tour_id', $tour_id);
    
        $this->db->where('p.deleted !=', DELETED);
    
        $this->db->where('p.stay_date_from <=', $enddate);
    
        $this->db->where('p.stay_date_to >=', $startdate);
    
        $this->db->where('p.book_date_from <=', $today);
    
        $this->db->where('p.book_date_to >=', $today);
    
        $this->db->order_by('p.show_on_web', 'desc');
    
        $this->db->order_by('p.get_1', 'desc');
    
        $query = $this->db->get();
    
        $results = $query->result_array();
    
        foreach ($results as $key => $value)
        {
    
            $value['accommodations'] = $this->get_promotion_accommodations($value['tour_promotion_id']);
    
            $results[$key] = $value;
        }
    
        return $results;
    }
	
    /**
    *  get promotion accommodations
    *
    *  @author toanlk
    *  @since  Sep 15, 2014
    */
    function get_promotion_accommodations($tour_promotion_id, $departure_id = null){
    
        if(!empty($departure_id))
        {
            $this->db->where('tour_departure_id', $departure_id);
        }
        
        $this->db->where('tour_promotion_id', $tour_promotion_id);
    
        $query = $this->db->get('tour_promotion_details');
    
        $results = $query->result_array();
    
        return $results;
    
    }
	
	function get_tour_promotions_4_list($tours, $startdate){
	
		$today = date(DB_DATE_FORMAT); // today
		
		$startdate = format_bpv_date($startdate);

		$tour_ids = array();
		
		foreach ($tours as $tour) {
			if( !in_array($tour['id'], $tour_ids) ) {
				$tour_ids[] = $tour['id'];
			}
		}
		
		if( !empty($tour_ids) ){
			
			$this->db->select('p.*, pt.tour_id');
			
			$this->db->join('promotions as p', 'p.id = pt.promotion_id');
			
			$this->db->where_in('pt.tour_id', $tour_ids);
			
			$this->db->where('p.book_date_to >=', $today);
			$this->db->where('p.stay_date_to >=', $today);
			$this->db->where('p.deleted != ', DELETED);
			
			$this->db->where('p.show_on_web', STATUS_ACTIVE);
			
			$this->db->order_by('p.id');
			
			$query = $this->db->get('promotion_tours pt');
			
			$promotions = $query->result_array();
			
			//echo("<pre>");print_r($this->db->last_query());echo("</pre>");exit();
			
			foreach ($tours as $key=>$tour){
					
				$pros = array();
					
				foreach ($promotions as $pro){
			
					if($pro['tour_id'] == $tour['id']){
							
						$pros[] = $pro;
							
					}
			
				}
					
				$tour['promotions'] = $pros;
					
				$tours[$key] = $tour;
					
			}
		}
	
		return $tours;
	}
	
	function get_tour_price_from_4_list($tours, $startdate){
	
		$tour_ids = array();
		
		foreach ($tours as $tour){
			$tour_ids[] = $tour['id'];
		}
	
		if(count($tours) == 0) return $tours;
	
		$startdate = format_bpv_date($startdate);
	
		$select_sql = 'tpf.tour_id, tpf.price_origin, IF(p.id IS NOT NULL,tpf.price_from,tpf.price_origin) as price_from';
	
		$this->db->select($select_sql, false);
	
		$this->db->from('tour_price_froms as tpf');
	
		$this->db->join('promotions as p', 'p.id = tpf.promotion_id AND '.sql_join_tour_promotion($startdate),'left outer');
	
		$this->db->where_in('tpf.tour_id', $tour_ids);
	
		$this->db->where('tpf.date', $startdate);
	
		$this->db->order_by('tpf.tour_id','asc');
	
		$this->db->order_by('price_from','asc');
	
		$query = $this->db->get();
	
		$price_froms = $query->result_array();
	
		foreach ($tours as $key=>$value){
			foreach ($price_froms as $price_from){
				if($value['id'] == $price_from['tour_id']){
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
	 * Get tour selected in auto complete function
	 */
	
	function get_search_tour($tour_id){
	
		// t.extra_cancellation,
		$this->db->select('t.id, t.name, t.url_title, t.cruise_id, t.cruise_id, t.service_excludes, t.service_includes');
		$this->db->from('tours t');
		$this->db->where('t.deleted !=', DELETED);
		$this->db->where('t.status', STATUS_ACTIVE);
		$this->db->where('t.id', $tour_id);
	
		$query = $this->db->get();
	
		$results = $query->result_array();
	
		if(count($results) > 0){
			return $results[0];
		} else {
			return '';
		}
	}
	
	function get_tour_bpv_promotion_4_list($tours) {
	
		$today = date(DB_DATE_FORMAT);
	
		$cruise_ids = array();
		foreach ($tours as $tour) {
			if( !in_array($tour['cruise_id'], $cruise_ids) ) {
				$cruise_ids[] = $tour['cruise_id'];
			}
		}
	
		if(count($cruise_ids) > 0){
				
			$this->db->distinct();
			$this->db->select('b.*, bh.cruise_id');
			$this->db->from('bpv_promotion_cruises bh');
			$this->db->join('bpv_promotions b', 'b.id = bh.bpv_promotion_id');
			$this->db->where_in('bh.cruise_id', $cruise_ids);
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
						
					if($pro['cruise_id'] == $tour['cruise_id']){
	
						$pros[] = $pro;
	
					}
						
				}
	
				$tour['bpv_promotions'] = $pros;
	
				$tours[$key] = $tour;
	
			}
				
		}
	
	
		return $tours;
	}
}
