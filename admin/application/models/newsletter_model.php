<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Newsletter_Model extends CI_Model{
	function __construct(){
        parent::__construct();	
		$this->load->database();
	}
	
	function search_newsletter($search_criteria = '', $num, $offset, $order_field = 'id', $order_type = 'asc'){
		
		$this->db->select('n.id, n.name, n.display_name, n.template_type, n.customer_gender, n.customer_type, n.content, n.status, n.nr_total_email, n.nr_send_success, n.nr_view_email, n.nr_view_email_online, n.user_created_id, n.date_created, n.user_modified_id, n.date_modified, u.username as last_modified_by, un.username as created_newsletter_by');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->join('users u', 'n.user_modified_id = u.id', 'left outer');
		$this->db->join('users un', 'n.user_created_id = un.id', 'left outer');
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('newsletters n', $num, $offset);
		
		return $query->result_array();
	}
	
	private function _set_search_criteria($search_criteria = ''){
		
		if ($search_criteria == '')	{
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				case 'search_text' :
					$value =  $this->db->escape_like_str($value);
					if(!empty($value)){
						$this->db->like('n.name', $value, 'both');
					}
					break;
				case 'template_type' :
					if($value !=''){
						$this->db->where('n.template_type',$value);
					}
					break;
				case 'status':
					if($value !=''){
						$this->db->where('n.status',$value);
					}
					break;
			}
		}
	}
	
	function create_newsletter($pro){
		
		$addition_data = array(
			'status'				=> STATUS_NEW,
			'user_created_id'		=> get_user_id(),
			'date_created'			=> date(DB_DATE_TIME_FORMAT),
			'user_modified_id'		=> get_user_id(),
			'date_modified'			=> date(DB_DATE_TIME_FORMAT),
		);
		
		$newsletter['name'] 		 = $pro['name'];
		
		$newsletter['display_name']  = $pro['display_name'];
		
		$newsletter['template_type'] 	= $pro['template_type'];
		
		$newsletter['customer_gender'] 		 	= $pro['customer_gender'];
		
		$newsletter['customer_type'] = $pro['customer_type'];
		
		$newsletter['content'] 		 = $pro['content'];
		
		$newsletter_data = array_merge($newsletter, $addition_data);
		
		$this->db->insert('newsletters', $newsletter_data);
		
		$newsletter_id = $this->db->insert_id();

		$promotion_full= $pro['promotion_full'];
		
		if(isset($promotion_full) && is_array($promotion_full)){
			
			foreach($promotion_full as $value){
				
				$pro_full = array();
				
				$pro_full['newsletter_id'] = $newsletter_id;
				
				$pro_full['hotel_id'] 	= 0;
				
				$pro_full['cruise_id'] 	= 0;
				
				$pro_full['tour_id'] 	= 0;
				
				if(isset($value['hotel_id'])){
				
					$pro_full['hotel_id'] 	= $value['hotel_id'];
				}
				if(isset($value['cruise_id'])){
				
					$pro_full['cruise_id'] 	= $value['cruise_id'];
				}
				if(isset($value['tour_id'])){
				
					$pro_full['tour_id'] 	= $value['tour_id'];
				}
				
				$pro_full['promotion_name'] 	= $value['name'];
				
				$pro_full['promotion_id'] 		= $value['id'];
				
				$pro_full['price_origin'] 		= $value['price_origin'];
				
				$pro_full['price_from'] 			= $value['price_from'];
				
				$this->db->insert('newsletter_promotions', $pro_full);
			}
		}
			
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function update_newsletters($newsletter_id, $pro){
		
		// Additional data
		$additional_data = array(
				'user_modified_id'	=> get_user_id(),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
		);
		
		$newsletter['name'] 		 = $pro['name'];
		
		$newsletter['display_name']  = $pro['display_name'];
		
		$newsletter['template_type'] = $pro['template_type'];
		
		$newsletter['customer_gender'] 		 = $pro['customer_gender'];
		
		$newsletter['customer_type'] = $pro['customer_type'];
		
		$newsletter['content'] 		 = $pro['content'];
		
		$newsletter_data = array_merge($newsletter, $additional_data);
		
		$this->db->trans_start();
		
		$this->db->update('newsletters', $newsletter_data, array('id' => $newsletter_id));
		
		$promotion_full = $pro['promotion_full'];
		
		if(isset($promotion_full) && is_array($promotion_full)){
			
			if(count($promotion_full[0]) > 1){
				
				$this->db->delete('newsletter_promotions', array('newsletter_id'=>$newsletter_id));
			
				foreach($promotion_full as $value){
					
					$pro_full = array();
					
					$pro_full['newsletter_id'] = $newsletter_id;
					
					$pro_full['hotel_id'] 	= 0;
					
					$pro_full['cruise_id'] 	= 0;
					
					$pro_full['tour_id'] 	= 0;
					
					if(isset($value['hotel_id'])){
						
						$pro_full['hotel_id'] 	= $value['hotel_id'];
					}
					if(isset($value['cruise_id'])){
						
						$pro_full['cruise_id'] 	= $value['cruise_id'];
					}
					if(isset($value['tour_id'])){
						
						$pro_full['tour_id'] 	= $value['tour_id'];
					}
					
					$pro_full['promotion_name'] 	= $value['name'];
					
					$pro_full['promotion_id'] 		= $value['id'];
					
					$pro_full['price_origin'] 		= $value['price_origin'];
					
					$pro_full['price_from'] 			= $value['price_from'];
					
					$this->db->insert('newsletter_promotions', $pro_full);
				}
			}
		}
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function update_newsletter_content($newsletter_id, $content){
		
		$this->db->trans_start();
		
		$this->db->update('newsletters', $content, array('id' => $newsletter_id));
		
		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_ids_from_hotel_list($hotels){
		
		$ret = array();
		
		foreach ($hotels as $hotel){
			$ret[] = $hotel['id'];
		}
		
		return $ret;
	}
	
	function get_newsletters($id, $get_photo = false){
		
		if(empty($id)) {
			
			return FALSE;
		}
		$this->db->select('n.*');
		
		$this->db->where('n.id', $id);
	
		$query = $this->db->get('newsletters n');
		
		$results = $query->result_array();
	
		if (count($results) === 1){
			
			$newsletters = $results[0];
			
			if($get_photo){
				 
				$newsletters['photos'] = $this->get_newsletters_photos($id);
			}
			
			return $newsletters;
		}
		
		return FALSE;
	}
	
	function get_newsletter_promotion($id){
	
		if(empty($id)) {
			
			return FALSE;
		}
		$this->db->select('np.promotion_id as id');
		
		$this->db->where('np.newsletter_id', $id);
	
		$query = $this->db->get('newsletter_promotions np');
	
		$results = $query->result_array();
		
		if(count($results) >0){
			
			return $results;
		}
		return FALSE;
	}
	
	function get_newsletters_photos($newsletters_id){
		
		$this->db->where('newsletter_id', $newsletters_id);
		 
		$query = $this->db->get('newsletter_photos');
		 
		return $query->result_array();
	}
	
	function create_newsletters_photos($photos){
		
		foreach ($photos as $photo){
	
			$this->db->insert('newsletter_photos', $photo);
		}
	}
	
	function get_photo($id){
		 
		$this->db->where('id', $id);
		 
		$query = $this->db->get('newsletter_photos');
		 
		$results = $query->result_array();
		 
		if(count($results) > 0){
	
			return $results[0];
		}
		return FALSE;
	}
	
	function delete_photo($id){
		 
		$this->db->where('id', $id);
		 
		$this->db->delete('newsletter_photos');
	}
	
	function update_newsletters_photos($id, $p){
		
		$this->db->where('id', $id);
		
		$this->db->update('newsletter_photos', $p);
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_newsletters($id){
		
		$this->db->trans_start();
		
		$this->db->where('id', $id);
		
		$this->db->delete('newsletters');
		
		$this->db->trans_complete();
			
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_newsletter_promotion($newsletter_id){
		
		$this->db->trans_start();
		
		$this->db->where('newsletter_id', $newsletter_id);
		
		$this->db->delete('newsletter_promotions');
		
		$this->db->trans_complete();
			
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_newsletter_photos($newsletter_id){
		
		$this->db->trans_start();
		
		$this->db->where('newsletter_id', $newsletter_id);
		
		$this->db->delete('newsletter_photos');
		
		$this->db->trans_complete();
			
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_numb_newsletter($search_criteria = ''){
		
		$this->db->select('count(*) as number');
		
		if(!empty($search_criteria['template_type'])){
			
			$this->db->where('template_type', $search_criteria['template_type']);
		}
		
		$query = $this->db->get('newsletters');
		
		$results = $query->result_array();
		
		if(count($results)>0 ){
			
			return $results[0]['number'];
		}
	}
	
	function delete_newsletter($id){
		
		$this->db->delete('newsletters', array('id' => $id));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	/**
	 * Get hotel for newsletters
	 * 
	 * - get top hotel destinations
	 * - get hotels of the destination (includes price and promotions)
	 * 
	 */
	function get_top_hotel_destinations()
	{
	    $this->db->select('id, name');
	    
	    $this->db->where('deleted !=', DELETED);
	    
	    $this->db->where('is_top_hotel', 1);
	    
	    $query = $this->db->get('destinations');
	    
	    return $query->result_array();
	}
	
	function get_hotel_newsletter($des_id){
	
	    if($des_id !=''){
	        	
	        $this->db->select('h.id, h.name, h.star, h.picture, h.url_title');
	
	        $this->db->from('destination_hotels dh');
	        	
	        $this->db->join('hotels h','h.id = dh.hotel_id');
	        	
	        $this->db->where('h.status', STATUS_ACTIVE);
	        	
	        $this->db->where('h.deleted !=', DELETED);
	        	
	        $this->db->where('dh.destination_id', $des_id);
	        	
	        $query = $this->db->get();
	        	
	        $hotels = $query->result_array();
	        	
	        if(count($hotels) >0){
	
	            $hotels = $this->_get_hotel_promotions_4_list($hotels);
	        }
	        
	        return $hotels;
	    }
	}
	
	/**
	 * Get promotions for hotel [from front-end]
	 * 
	 * @param unknown $hotels
	 * @return multitype:unknown
	 */
	function _get_hotel_promotions_4_list($hotels){
	
	    $today = date(DB_DATE_FORMAT);
	
	    $hotel_ids = $this->get_ids_from_hotel_list($hotels);
	
	    if(count($hotel_ids) > 0){
	    	
	    	// get id, hotel_id, name of promotion

	    	$this->db->select('id, hotel_id, name');
	        	
	        $this->db->from('promotions p');
	        	
	        $this->db->where_in('p.hotel_id', $hotel_ids);
	        	
	        $this->db->where('p.book_date_to >=', $today);
	        	
	        $this->db->where('p.deleted != ', DELETED);
	        	
	        // $this->db->where('p.show_on_web', STATUS_ACTIVE);
	        	
	        $this->db->order_by('p.id');
	        	
	        $query = $this->db->get();
	        	
	        $promotions = $query->result_array();
	        
	        $hotel_promotion = array();
	        
	   		foreach ($hotels as $key => $hotel){
	            	
	            $pros = array();
	            	
	            foreach ($promotions as $pro){
	
	                if($pro['hotel_id'] == $hotel['id']){
	                    	
	                    $pros[] = $pro;
	                }
	            }
	            $hotel['promotions'] = $pros;
	            
	            if(count($hotel['promotions']) >0){
	            	
	            	$hotel_promotion[] = $hotel;
	            }
	        }
	        
	        return $hotel_promotion;
	        
	    }
	    return null;
	}
	
	function get_promotion_hotel_price($promotion_selected){
		
		$this->db->select('p.id, p.hotel_id, p.name, p.stay_date_from, p.stay_date_to');
		
        $this->db->where_in('p.id', $promotion_selected);
        
        $query = $this->db->get('promotions p');
        
        $promotions = $query->result_array();
        
        if(count($promotions) >0){
        	
        	$list_promotion = $this->_get_promotions_price_hotel($promotions);
        	
        	return $list_promotion;
        }
        
	   	return NULL;
	}
	
	function _get_promotions_price_hotel($list_promotion){
		
		if(count($list_promotion) >0){
			
			$promotion_full = array();
			
			foreach($list_promotion as $k =>$value){
			
				$this->db->select('h.id as hotel_id, h.name as hotel_name, h.url_title, h.star, h.picture, h.address, hpf.price_origin, hpf.price_from');
				
				$this->db->from('hotels h');
				
				$this->db->where('h.id', $value['hotel_id']);
				
				$this->db->join('hotel_price_froms hpf', 'hpf.hotel_id = h.id', 'left outer');
				
				$this->db->where('hpf.date', $value['stay_date_from']);
				
				$this->db->where('hpf.promotion_id', $value['id']);
				
				$query = $this->db->get();
				
				$promotion = $query->result_array();
				
				if(count($promotion) >0){
					
					$promotion_full[] = array_merge($value, $promotion[0] );
				}
			}
			
			return $promotion_full;
		}
		
		return NULL;
	}
	
	/**
	 *	Get destination tour domestic
	 *
	 *  @author nguyenson
	 *
	 *  @since Dec 20, 2014
	 *
	 */
	function get_destination_tour($is_outbound = false)
    {
        $groups = $this->get_tour_destination_group($is_outbound);
        
        foreach ($groups as $k => $group){
            
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
	 *	Get all category tour 
	 *
	 *  @author nguyenson
	 *
	 *  @since Dec 20, 2014
	 *
	 */
	
	function get_all_categories(){
		
        $this->db->where('deleted !=', DELETED);
        
        $this->db->order_by('position');
        
        $query = $this->db->get('categories');
        
        return $query->result_array();
    }
    
	/**
	 *	Get promotion tour newsletter
	 *
	 *  @author nguyenson
	 *
	 *  @since Dec 20, 2014
	 *
	 */
	
	function get_tour_newsletter($des_id){
		
		if($des_id !=''){
	        	
	        $this->db->select('t.id, t.name, t.picture, t.url_title');
	
	        $this->db->from('destination_tours dt');
	        	
	        $this->db->join('tours t','t.id = dt.tour_id');
	        	
	        $this->db->where('t.status', STATUS_ACTIVE);
	        	
	        $this->db->where('t.deleted !=', DELETED);
	        	
	        $this->db->where('dt.destination_id', $des_id);
	        
	        $this->db->where('dt.is_land_tour', 1);
	        	
	        $query = $this->db->get();
	        	
	        $tours = $query->result_array();
	        	
	        if(count($tours) >0){
	
	            $tours = $this->_get_tour_promotions_4_list($tours);
	        }
	        
	        return $tours;
	    }
	}
	
	function _get_tour_promotions_4_list($tours){
		
		$today = date(DB_DATE_FORMAT); // today
		
		$tour_ids = array();
		
		foreach ($tours as $tour) {
			if( !in_array($tour['id'], $tour_ids) ) {
				$tour_ids[] = $tour['id'];
			}
		}
		
		if( !empty($tour_ids) ){
			
			$this->db->select('id, tour_id, name');
	        	
	        $this->db->from('promotions p');
			
			$this->db->where_in('p.tour_id', $tour_ids);
			
	        $this->db->where('p.book_date_to >=', $today);
	        	
	        $this->db->where('p.deleted != ', DELETED);
			
			// $this->db->where('p.show_on_web', STATUS_ACTIVE);
			
			$this->db->order_by('p.id');
	        	
	        $query = $this->db->get();
	        	
	        $promotions = $query->result_array();
	        
	        $tour_promotion = array();
	        
	   		foreach ($tours as $key => $tour){
	            	
	            $pros = array();
	            	
	            foreach ($promotions as $pro){
	
	                if($pro['tour_id'] == $tour['id']){
	                    	
	                    $pros[] = $pro;
	                }
	            }
	            $tour['promotions'] = $pros;
	            
	            if(count($tour['promotions']) >0){
	            	
	            	$tour_promotion[] = $tour;
	            }
	        }
	        
	        return $tour_promotion;
	        
	    }
	    return null;
	}
	
	
	
	
	/**
	 *	Get promotion tour category newsletter
	 *
	 *  @author nguyenson
	 *
	 *  @since Dec 20, 2014
	 *
	 */
	
	function get_category_newsletter($category_id){
		
		if($category_id !=''){
			
			$this->db->select('t.id, t.name, t.picture, t.url_title');
	    	
	    	$this->db->from('tour_categories tc');
	    	
	    	$this->db->join('tours t', 't.id = tc.tour_id');
	    	
	    	$this->db->where('tc.category_id', $category_id);
	    	
	    	$this->db->where('t.deleted !=', DELETED);
	        
	        $this->db->where('t.status', STATUS_ACTIVE);
	        
	        $this->db->order_by('t.position', 'asc');
	        
	        $query = $this->db->get();
	        
	        $tours = $query->result_array();
	        
			if(count($tours) >0){
	
	            $tours = $this->_get_tour_promotions_4_list($tours);
	        }
	        
	        return $tours;
		}
		
		return NULL;
	}
	
	function get_promotion_tour_price($promotion_selected){
		
		$list_promotion = array();
	
		foreach($promotion_selected as $key =>$promotion_id){
			
			$this->db->select('p.id, p.tour_id, p.name, p.stay_date_from, p.stay_date_to');
			
	        $this->db->where('p.id', $promotion_id);
	        
	        $query = $this->db->get('promotions p');
	        
	        $promotions = $query->result_array();
	        
	        if(count($promotions) >0){
	        	
	        	$list_promotion[] = $promotions[0];
	        }else{
	        	
	        	$list_promotion[] = NULL;
	        }
		}
		
		if(count($list_promotion) >0){
			
	    	$list_promotion = $this->_get_promotions_price_tour($list_promotion);
	   	}
	    
	   	return $list_promotion;
	}
	
	
	function _get_promotions_price_tour($list_promotion){
		
		if(count($list_promotion) >0){
			
			$promotion_full = array();
			
			foreach($list_promotion as $k =>$value){
			
				$this->db->select('t.id as tour_id, t.name as tour_name, t.url_title, t.picture, t.is_outbound, tpf.price_origin, tpf.price_from');
				
				$this->db->from('tours t');
				
				$this->db->where('t.id', $value['tour_id']);
				
				$this->db->join('tour_price_froms tpf', 'tpf.tour_id = t.id', 'left outer');
				
				$this->db->where('tpf.date', $value['stay_date_from']);
				
				$this->db->where('tpf.promotion_id', $value['id']);
				
				$query = $this->db->get();
				
				$promotion = $query->result_array();
				
				if(count($promotion) >0){
					
					$promotion_full[] = array_merge($value, $promotion[0] );
				}
			}
			
			$list_promotion = $promotion_full;
			
			return $list_promotion;
		}
		
		return NULL;
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
    function get_tour_destination_group($is_outbound = false){
    	
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
     *	get cruise newsletter
     *
     *	- cruise have promotion, date
     *
     *  @author nguyenson
     *
     *  @since Dec 23, 2014
     */
    
	function get_cruise_newsletter(){
			
		$this->db->select('c.id, c.name, c.picture, c.url_title');
		
		$this->db->from('cruises c');
		
		$this->db->where('c.status', STATUS_ACTIVE);
		
		$this->db->where('c.deleted !=', DELETED);
		
		$query = $this->db->get();
		
		$cruises = $query->result_array();
		
		if(count($cruises) >0){
			
			$cruises = $this->_get_cruise_promotions_4_list($cruises);
			
			return $cruises;
		}
		return NULL;
	}
	
	function _get_cruise_promotions_4_list($cruises){
	
		$today = date(DB_DATE_FORMAT);
		
		$cruise_ids = array();
		
		foreach ($cruises as $cruise) {
			if( !in_array($cruise['id'], $cruise_ids) ) {
				$cruise_ids[] = $cruise['id'];
			}
		}
		
		if(count($cruise_ids) > 0){
			
			$this->db->select('p.id, p.cruise_id, p.name, p.tour_id');
			
			$this->db->from('promotions p');
			
			$this->db->where_in('p.cruise_id', $cruise_ids);
			
			$this->db->where('p.book_date_to >=', $today);
			
			$this->db->where('p.deleted != ', DELETED);
			
			$this->db->order_by('p.id');
			
			$query = $this->db->get();
			
			$promotions = $query->result_array();
			
			$cruise_promotion = array();
			
			foreach ($cruises as $key => $cruise){
			
				$pros = array();
			
				foreach ($promotions as $pro){
						
					if($pro['cruise_id'] == $cruise['id']){
			
						$pros[] = $pro;
					}
				}
				$cruise['promotions'] = $pros;
				
	            if(count($cruise['promotions']) >0){
	            	
	            	$cruise_promotion[] = $cruise;
	            }
			}
			return $cruise_promotion;
		}
		return NULL;
	}
	
	
	function get_promotion_cruise_price($promotion_selected){
		
		$this->db->select('p.id, pt.tour_id as tour_id, p.name, p.stay_date_from, p.stay_date_to, c.star, c.name as cruise_name, c.id as cruise_id');
		
		$this->db->join('promotion_tours pt', 'pt.cruise_id = p.cruise_id', 'left outer');
		
		$this->db->join('cruises c', 'c.id = pt.cruise_id', 'left outer');
		
		$this->db->where_in('pt.promotion_id', $promotion_selected);
		
        $this->db->where_in('p.id', $promotion_selected);
        
        $query = $this->db->get('promotions p');
        
        $promotions = $query->result_array();
        
        if(count($promotions) >0){
        	
        	$list_promotion = $this->_get_promotions_price_tour($promotions);
        	
        	return $list_promotion;
        }
	    
	   	return NULL;
	}
	
	
	
	/* SEND MAIL NEWSLETTERS*/
	
	/**
	 * Get list email for customer of type ...
	 * @param unknown_type $customer_type
	 */
	
	function get_email_customer($customer_type, $customer_gender){
		
		$this->db->distinct();
		
		$this->db->select('c.email');
		
		$this->db->from('service_reservations sr');
		
		$this->db->join('customer_bookings cb', 'cb.id = sr.customer_booking_id', 'left outer');
		
		$this->db->join('customers c', 'c.id = cb.customer_id');
		
		$this->db->where('POWER(2, c.gender) &'.$customer_gender.' > 0');
		
		$this->db->where('POWER(2, sr.reservation_type) &'.$customer_type.' > 0');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		if(($customer_type &pow(2,9)) >0 ){
	    		
    		$email_account = $this->_get_email_account();
    		
    		$results = array_merge($results, $email_account);
    	}
    	return $results;
	}
	
	/**
	 * Get list email for account register NEWSLETTER
	 * Enter description here ...
	 */
	
	function _get_email_account(){
		
		$this->db->select('a.email');
		
		$this->db->from('accounts a');
		
		//$this->db->where('a.register', NEWS_LETTER);
		$this->db->where('a.register', SYSTEM);
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * Insert list email of customer in table log_send_email_newsletters
	 * @param unknown_type $list_email
	 */
	
	function insert_email_log($newsletter_id='', $list_email = array()){
		
		$is_newsletter_log = $this->is_newsletter_log($newsletter_id);
		
		if(!empty($newsletter_id) && !empty($list_email) && !$is_newsletter_log){
			
			$list_log_email = array();
			
			foreach($list_email as $key=> $email){
				
				$pos	=	strpos($email['email'], EMAIL_BESTPRICE);
				
				if(filter_var($email['email'], FILTER_VALIDATE_EMAIL) && ($pos === false)){
					
					$email['newsletter_id'] = $newsletter_id;
					
					$list_log_email[] = $email;
				}
			}
			
			$this->db->trans_start();
			
			$this->db->insert_batch('log_send_email_newsletters', $list_log_email);
	
			$this->db->trans_complete();
			
			$error_nr = $this->db->_error_number();
			
			return !$error_nr;
		}elseif (empty($newsletter_id) ||empty($list_email) || $is_newsletter_log){
			
			return false;
		}
	}
	
	/**
	 * Check is newsletter_id on log_send_email_newsletters
	 * @param unknown_type $newsletter_id
	 * @return boolean
	 */
	function is_newsletter_log($newsletter_id =''){
		
		if(!empty($newsletter_id)){
			
			$this->db->select('newsletter_id');
			
			$this->db->from('log_send_email_newsletters');
			
			$this->db->where('newsletter_id', $newsletter_id);
			
			$query = $this->db->get();
			
			$nr_newsletter_id = count($query->result_array());
			
			if($nr_newsletter_id >0){
				return TRUE;
			}else{
				return FALSE;
			}
		}
		return FALSE;
	}
	
	/**
	 * 
	 * Update user when action send email
	 * @param unknown_type $newsletter_id
	 * @param unknown_type $data
	 */
	function update_user_send_newsletter($newsletter_id, $action = '', $data = array()){
		
		if($action == STATUS_SENDING){
			
			$additional_data = array(
				'user_send_id'		=> get_user_id(),
				'date_last_send'	=> date(DB_DATE_TIME_FORMAT),
				'status'			=> $action
			);
		}elseif($action == STATUS_STOP){
			
			$additional_data = array(
				'user_stop_id'		=> get_user_id(),
				'date_stop'			=> date(DB_DATE_TIME_FORMAT),
				'status'			=> $action
			);
		}elseif($action == STATUS_SENT){
			
			$additional_data = array(
				'user_stop_id'		=> get_user_id(),
				'date_stop'			=> date(DB_DATE_TIME_FORMAT),
				'status'			=> $action
			);
		}
		
		$data_update = array_merge($additional_data, $data);
		
		$this->db->trans_start();
		
		$this->db->update('newsletters', $data_update, array('id' => $newsletter_id));
		
		$this->db->trans_complete();
			
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	
	/**
	 * 
	 * Get number email sent of newsletter
	 * @param unknown_type $id
	 */
	function number_email_sent(){
		
		$now = date(DB_DATE_TIME_FORMAT);
		
		$this->db->select('count(*) as number_email_sent');
		
		$this->db->where('status_log !=', 0);
		
		$this->db->where("DATEDIFF('$now', date_log) <", 1);
		
		$query = $this->db->get('log_send_email_newsletters');
		
		$results = $query->result_array();
		
		if($results >0){
			
			return $results[0]['number_email_sent'];
		}
		return NULL;
	}
	
	/**
	 * Get array email limit 
	 * Enter description here ...
	 * @param unknown_type $newsletter_id
	 */
	
	function get_array_email($newsletter_id = '', $status_log = -1, $thread = -1){
		
		if(!empty($newsletter_id)){
			
			$this->db->select('email');
			
			$this->db->where('newsletter_id', $newsletter_id);
			
			if($status_log != -1){
				
				$this->db->where('status_log',  $status_log);
			}
			
			if($thread != -1){
				
				$this->db->where('thread_num',  $thread);
			}
			
			if($status_log == -1 && $thread == -1){
			
				$this->db->where_in('status_log',  array(LOG_INIT, LOG_FALSE));
				$this->db->where_in('thread_num',  array(0, 1));
			}
			
			$this->db->limit(ARRAY_LIMITED);
			
			$query = $this->db->get('log_send_email_newsletters');
			
			$results = $query->result_array();
			
			return $results;
		}
		return NULL;
	}
	
	/**
	 * 
	 * nr_total_email
	 * @param unknown_type $newsletter_id
	 * @param unknown_type $status_log
	 */
	function nr_total_email($newsletter_id = '', $status_log = -1, $thread = -1){
		
		if(!empty($newsletter_id)){
			
			$this->db->select('count(*) as nr_total_email');
			
			$this->db->where('newsletter_id', $newsletter_id);
			
			if($status_log != -1){
				
				$this->db->where('status_log', $status_log);
			}
			if($thread != -1){
				
				$this->db->where('thread_num', $thread);
			}
			
			if($status_log == -1 && $thread == -1){
			
				$this->db->where_in('status_log',  array(LOG_INIT, LOG_FALSE));
				$this->db->where_in('thread_num',  array(0, 1));
			}
			
			$query = $this->db->get('log_send_email_newsletters');
			
			$results = $query->result_array();
			
			if(count($results) >0){
				
				return $results[0]['nr_total_email'];
			}
		}
		return NULL;
	}
	
	function update_log($data_log = array()){
	
		$this->db->trans_start();
		
		$this->db->update_batch('log_send_email_newsletters', $data_log, 'email'); 

		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function status_newsletter($id = ''){
		
		if(!empty($id)){
			
			$this->db->select('status');
			
			$this->db->where('id', $id);
			
			$query = $this->db->get('newsletters');
			
			$results = $query->num_rows();
			
			return $results;
		}
		return NULL;
	}
	
}