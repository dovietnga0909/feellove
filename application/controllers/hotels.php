<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotels extends CI_Controller {
	
	public function __construct()
    {
       	parent::__construct();
       	
		$this->load->language(array('hotel', 'deal'));
		$this->load->helper(array('hotel','flight','rate','cookie','form','text','display','booking','land_tour'));
		
		$this->load->model(array('Hotel_Model', 'Destination_Model', 'News_Model', 'Flight_Model', 'Advertise_Model', 'Deal_Model', 'Land_Tour_Model'));
		$this->load->config('hotel_meta');
		
		//$this->output->enable_profiler(TRUE);
	}
	
	/**
	 * Hotel Home Page
	 */
	public function index()
	{	
		
		set_cache_html();
		
		$is_mobile = is_mobile();
		
		$this->session->set_userdata('MENU', MNU_HOTELS);
		
		// build search form
		$data = build_search_criteria(array(), MODULE_HOTEL, $is_mobile);
		
		$data['meta'] = get_header_meta(HOTEL_HOME_PAGE);
		
		$startdate = $data['hotel_search_criteria']['startdate'];
		
		// Best hotels by destinations
		$data = load_best_hotel($data, $data['hotel_search_criteria'], $startdate);
		
		// Get recent view items
		$data = load_recent_items($data, $startdate);
		
		// get advertises
		$data['bpv_ads'] = load_bpv_ads(AD_PAGE_HOTEL_HOME);
		
		$data['bpv_why_us'] = load_bpv_why_us(HOME_PAGE);
		
		// Right Side: Feature Hotel Destinations
		$top_destinations = $this->Hotel_Model->get_top_hotel_destinations();
		
		foreach ($top_destinations as $k => $des) {
			$des['full_url'] = get_url(HOTEL_DESTINATION_PAGE, $des);
			$top_destinations[$k] = $des;
		}
		
		$data['top_destinations'] = $top_destinations;
		
		// Footer Links
		$data = load_footer_links($data, false, true);
		
		$data['n_book_together'] = $this->News_Model->get_news_details(10);
		
		// render view
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		
		$data = get_page_theme($data, $is_mobile);
		
		if($is_mobile){
			
			_render_view('mobile/hotels/home/hotel_home', $data, $is_mobile);
			
		} else {
			
			_render_view('hotels/home/hotel_home', $data);
		}
	
	}
	
	public function search(){
		
		$is_mobile = is_mobile();
		
		$data = get_page_theme(array(),$is_mobile);
		
		$this->session->set_userdata('MENU', MNU_HOTELS);
	
		
		$data['meta'] = get_header_meta(HOTEL_SEARCH_PAGE);
		
		$data['max_nights'] = $this->config->item('max_nights');
		
		$search_criteria = hotel_build_search_criteria();
		
		$data['search_criteria'] = $search_criteria;
		
		$data['suggestion_destinations']= $this->Hotel_Model->get_top_hotel_destinations();
		
		$action = $this->input->post('action');
		
		// user click on search button
		if($action == ACTION_SEARCH){
			
			$redirect_url = http_build_query($search_criteria);
			
			redirect(site_url(HOTEL_SEARCH_PAGE.'#'.$redirect_url));
			
		} else { // user access from the browser link
			
			$is_ajax = $this->input->get('isAjax');
			
			
			$is_correct_search = is_correct_hotel_search($search_criteria);

			// ajax geting data
			if($is_ajax){
				//sleep(70);
				if($is_correct_search){
					
					if(!empty($search_criteria['oid'])){
						
						$oid = $search_criteria['oid'];
						
						// auto complete select Hotel
						if(strpos($oid, 'h-') !== FALSE){
							
							$hotel_id = str_replace('h-', '', $oid);
							
							$selected_hotel = $this->Hotel_Model->get_search_hotel($hotel_id);
							
							if(!empty($selected_hotel)){
								
								$data['selected_hotel'] = $selected_hotel;
								
								$this->_go_to_hotel_detail_page($data);
								
							} else {
								
								$this->_go_to_search_suggestion_page($data);
							}
							
						} elseif(strpos($oid, 'd-') !== FALSE){ // autocomplete select hotel
							
							$destination_id = str_replace('d-', '', $oid);
							
							$selected_des = $this->Destination_Model->get_search_destination($destination_id);
							
							if(!empty($selected_des)){
								
								$data['selected_des'] = $selected_des;
								
								$this->_go_search_destination_page($data);
								
							} else {
								
								$this->_go_to_search_suggestion_page($data);
								
							}
							
						} else {
							
							$this->_go_to_search_suggestion_page($data);
							
						}
						 
						
					} else {
						
						$this->_go_to_search_suggestion_page($data);
					}
				
				} else {
					if($is_mobile){
						$this->load->view('mobile/hotels/common/hotel_search_top', $data);	
					}else{
						$this->load->view('hotels/common/hotel_search_top', $data);	
					}	
					
				}
				
			} else {
				if($is_mobile){
					$data['bpv_content'] = $this->load->view('mobile/hotels/hotel_search/search_waiting', $data, TRUE);
				
					$this->load->view('mobile/_templates/bpv_layout', $data);
				}else{
					$data['bpv_content'] = $this->load->view('hotels/hotel_search/search_waiting', $data, TRUE);
				
					$this->load->view('_templates/bpv_layout', $data);	
				}
				
				
			}
			
		}
			
	}
	
	public function search_suggestion(){
		
		$is_mobile = is_mobile();
		
		$this->session->set_userdata('MENU', MNU_HOTELS);
		
		$data = get_page_theme(array(), $is_mobile);
		
		$data['destination_types'] = $this->config->item('destination_types');
		
		$search_criteria = hotel_build_search_criteria();
		
		$data['search_criteria'] = $search_criteria;
		
		$data['suggestion_destinations']= $this->Hotel_Model->get_top_hotel_destinations();
		
		// load destination suggestion
		$destination_suggestions = $this->Destination_Model->search_destination_suggestion($search_criteria['destination']);
		
		// load hotel suggestions
		$hotel_suggestions = $this->Hotel_Model->search_hotel_suggestion($search_criteria['destination'], $search_criteria['startdate']);
		
		if (count($destination_suggestions) == 0 && count($hotel_suggestions) == 0)
        {
            
            $data['no_suggestion'] = 1;
            
            $destination_suggestions = $this->Destination_Model->search_city_suggestion();
        }
        else
        {
            // match destination
            if (count($destination_suggestions) >= 1 && is_best_match($destination_suggestions, $search_criteria['destination'], false))
            {
                
                $selected_des = $destination_suggestions[0];
                
                redirect(hotel_search_destination_url($selected_des, $search_criteria));
                
            }
            
            // match hotel
            if (count($hotel_suggestions) >= 1 && is_best_match($hotel_suggestions, $search_criteria['destination']))
            {
                
                $selected_hotel = $hotel_suggestions[0];
                
                redirect(hotel_build_url($selected_hotel, $search_criteria));
            }
        }
		
		
		$data['destination_suggestions'] = $this->_re_structure_destinaton_suggestions($destination_suggestions);
	
		
		$data['hotel_suggestions'] = $hotel_suggestions;
		
		if($is_mobile){
			
			$data	=	build_search_criteria($data, MODULE_HOTEL , $is_mobile);
			$data['bpv_content'] = $this->load->view('mobile/hotels/hotel_search/search_suggestion', $data, TRUE);
		
			$this->load->view('mobile/_templates/bpv_layout', $data);
		}else{
			
			$data['bpv_content'] = $this->load->view('hotels/hotel_search/search_suggestion', $data, TRUE);
		
			$this->load->view('_templates/bpv_layout', $data);	
		}
		
	}

	
	public function hotel_destination($des_id){
		
		set_cache_html();
		
		$is_mobile = is_mobile();
		
		$this->session->set_userdata('MENU', MNU_HOTELS);
		
		// get hotel destination data
		$destination = $this->Destination_Model->get_destination($des_id);
		
		$data['meta'] = get_header_meta(HOTEL_DESTINATION_PAGE, $destination);
		
		$data['destination'] = $destination;
		
		// build search form
		$data = build_search_criteria($data, MODULE_HOTEL_DESTINATION, $is_mobile);
		
		$search_criteria = $data['search_criteria'];
		
		/**
		 * Khuyenpv: set for url params in View More Link
		 */
		$url_params['destination'] = $search_criteria['destination'];
		$url_params['startdate'] = $search_criteria['startdate'];
		$url_params['night'] = $search_criteria['night'];
		$url_params['enddate'] = $search_criteria['enddate'];
		$url_params['oid'] = $search_criteria['oid'];
		$data['url_params'] = $url_params;
		
		// Khuyenpv: get advertise of this destination
		$bpv_ads_area_default = load_bpv_ads(AD_PAGE_HOTEL_DESTINATION, AD_AREA_DEFAULT, $des_id);
		$data['bpv_ads_default'] = trim($bpv_ads_area_default);
		
		$bpv_ads_area_2 = load_bpv_ads(AD_PAGE_HOTEL_DESTINATION, AD_AREA_2, $des_id);
		$data['bpv_ads_2'] = trim($bpv_ads_area_2);
		
		// khuyenpv: show hotel promotions in destination if there is no advertise
		if(!empty($bpv_ads_area_default)){
			$data['hotel_deals'] = $this->Deal_Model->get_hotel_in_des_has_promotion($des_id, 5);
			
			if(count($data['hotel_deals']) > 0){
				if($is_mobile){
					$data['deal_in_destination'] = $this->load->view('mobile/hotels/hotel_destination/deal_in_destination', $data, TRUE);
				}else{
					$data['deal_in_destination'] = $this->load->view('hotels/hotel_destination/deal_in_destination', $data, TRUE);
				}
			}
		}
		
		// get in and around destination
		$data['city'] = $destination;
		
		if ($destination['type'] > DESTINATION_TYPE_CITY) {
			
			$data['parents'] = $this->Destination_Model->get_parent_destinations($destination);
			
			foreach ($data['parents'] as $parent_des) {
				if($parent_des['is_top_hotel'] || $parent_des['type'] == DESTINATION_TYPE_CITY) {
					$data['city'] = $parent_des;
				}
			}
			
		}
		
		$data['in_and_around'] = $this->Destination_Model->get_in_and_around_destination($data['city']['id']);
		
		if($is_mobile){
			
			$data['place_of_interest'] = $this->load->view('mobile/hotels/hotel_destination/place_of_interest', $data, TRUE);
			
		}else{
		
			$data['place_of_interest'] = $this->load->view('hotels/hotel_destination/place_of_interest', $data, TRUE);
			
		}
		
		// get advertises
		//$data['advertises'] = $this->Advertise_Model->get_advertises(AD_PAGE_HOTEL_DESTINATION, null, $destination['id']);
		
		// if destination is area or district ... (ex: Ha Noi, ...etc)
		if( $data['destination']['type'] <= DESTINATION_TYPE_AREA) { 
			
			$data['hotels'] = $this->Hotel_Model->get_hotel_by_destination($destination['id'], $search_criteria['startdate'], 10);
			
		} else { // if destination is place (ex: Night market, ...etc)
			
			$data['hotels'] = $this->Hotel_Model->get_hotel_in_and_around($destination, $search_criteria['startdate']);
			
		}
		
		$data['bpv_why_us'] = load_bpv_why_us(HOME_PAGE);
		if($is_mobile){
			$data['hotel_list_in_des'] = $this->load->view('mobile/hotels/hotel_destination/hotel_list', $data, TRUE);
		}else{
			$data['hotel_list_in_des'] = $this->load->view('hotels/hotel_destination/hotel_list', $data, TRUE);
		}
		
		$data = $this->_load_hotel_by_star($data);
		
		$data = $this->_load_search_quick_links($data, $is_mobile);
		
		// render view
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		
		$data = get_page_theme($data, $is_mobile);
		
		if($is_mobile){
			_render_view('mobile/hotels/hotel_destination/hotel_by_destination', $data, $is_mobile);
		} else {
			_render_view('hotels/hotel_destination/hotel_by_destination', $data);
		}	
		
	}
	
	function _load_search_quick_links($data, $is_mobile = FALSE){
		
		$search_criteria = $data['search_criteria'];
		
		$search_criteria['selected_des'] = $data['city'];
		
		$data = $this->_load_prices_for_filter($data);
		
		$data = $this->_load_stars_for_filter($data);
		
		$data = $this->_load_facilities_for_filter($data);
		
		if($is_mobile){
			$data['quick_search_links'] = $this->load->view('mobile/hotels/hotel_destination/quick_search_links', $data, TRUE);
		}else{
			$data['quick_search_links'] = $this->load->view('hotels/hotel_destination/quick_search_links', $data, TRUE);
		}
		
	
		return $data;
	}
	
	
	function _load_hotel_by_star($data){
	
		$hotels = $this->Hotel_Model->get_all_hotels_in_destination($data['destination']['id']);
	
		$h_5_stars = array();
	
		$h_4_stars = array();
	
		$h_3_stars = array();
	
	
		foreach ($hotels as $hotel){
	
			if($hotel['star'] >= 5){
				$h_5_stars[] = $hotel;
			} elseif($hotel['star'] >= 4){
				$h_4_stars[] = $hotel;
			} elseif ($hotel['star'] >= 3){
				$h_3_stars[] = $hotel;
			}
	
		}
	
	
		$data['h_5_stars'] = $h_5_stars;
	
		$data['h_4_stars'] = $h_4_stars;
	
		$data['h_3_stars'] = $h_3_stars;
	
		$data['hotel_list_by_star'] = $this->load->view('hotels/hotel_destination/hotel_list_by_star', $data, TRUE);
	
		return $data;
	}
	
	
	function _load_search_form($data, $is_mobile = false){
		
		$search_criteria = $data['search_criteria'];
		
		$data['search_criteria'] = $search_criteria;
		
		$data['max_nights'] = $this->config->item('max_nights');
		
		if($is_mobile){
			
			// $data['hotel_search_overview'] = $this->load->view('mobile/hotels/common/search_overview', $data, TRUE);
		
			$data['hotel_search_form'] = $this->load->view('mobile/hotels/common/search_form', $data, TRUE);	
			
		}else{
			
			$data['hotel_search_overview'] = $this->load->view('hotels/common/search_overview', $data, TRUE);
		
			$data['hotel_search_form'] = $this->load->view('hotels/common/search_form', $data, TRUE);
			
		}
		
		return $data;
	}
	
	function _load_sort_by($data){
		
		$search_criteria = $data['search_criteria'];
		
		$sort_by = $this->config->item('sort_by');
		
		if(!empty($search_criteria['sort']) && isset($sort_by[$search_criteria['sort']])){
			
			$sort_by['popular']['selected'] = false;
			
			$sorted = $sort_by[$search_criteria['sort']];
			
			$sorted['selected'] = true;
			
			$sort_by[$search_criteria['sort']] = $sorted;
		}
		
		$data['sort_by'] = $sort_by;
		
		return $data;
	}
	
	function _load_prices_for_filter($data){
		
		$search_criteria = $data['search_criteria'];
		
		$filter_price = $this->config->item('filter_price');
		
		$count_prices = $this->Hotel_Model->get_search_filter_prices($search_criteria);
		
		
		foreach ($count_prices as $value){
			if($value['range_index'] > 0){
				$filter_price[$value['range_index']]['number'] = $value['number'];
			}
		}
		
		$data['filter_price'] = hotel_get_selected_filter($search_criteria, 'price', $filter_price);
		
		return $data;
		
	}
	
	function _load_stars_for_filter($data){
		
		$search_criteria = $data['search_criteria'];
		
		$filter_star = $this->config->item('filter_star');
		
		$count_stars = $this->Hotel_Model->get_search_filter_stars($search_criteria);
		
		/*
		foreach ($count_stars as $value){
			$filter_star[$value['star']]['number'] = $value['number'];
		}*/
		
		foreach ($filter_star as $key=>$value){
			
			$cnt = 0;
			
			foreach ($count_stars as $v){
				if($v['star'] == $key || $v['star'] == ($key + 0.5)){
					$cnt += $v['number'];
				}
			}
			
			$value['number'] = $cnt;
			
			$filter_star[$key] = $value;
		}
		
		$data['filter_star'] = hotel_get_selected_filter($search_criteria, 'star', $filter_star);
		
		return $data;
	}
	
	function _load_facilities_for_filter($data){
		
		$search_criteria = $data['search_criteria'];
		
		$filter_facility = $this->Hotel_Model->get_search_filter_facilities($search_criteria);
		
		$data['filter_limit'] = $this->config->item('filter_limit');
		
		if(count($filter_facility) > 0){
			
			foreach ($filter_facility as $key=>$value){
				
				$value['value'] = $value['id'];
				$value['label'] = $value['name'];
				$value['selected'] = FALSE;
				//$value['number'] = 0;
				
				$filter_facility[$key] = $value;
				
			}
			
			$filter_facility = hotel_get_selected_filter($search_criteria, 'facility', $filter_facility);
			
			$data['filter_facility'] = $filter_facility;
		}
			
		return $data;
		
	}
	
	function _load_near_areas_for_filter($data){
		
		$search_criteria = $data['search_criteria'];
		
		$selected_des = $data['selected_des'];
		
		if(!empty($selected_des)){
			
			$filter_area = $this->Destination_Model->get_filter_destination($selected_des);
			
			if(count($filter_area) > 0){
				
				foreach ($filter_area as $key=>$value){
				
					$value['value'] = $value['id'];
					$value['label'] = $value['name'];
					$value['selected'] = FALSE;
					$value['number'] = $value['number_of_hotels'];
				
					$filter_area[$key] = $value;
				
				}
				
				$filter_area = hotel_get_selected_filter($search_criteria, 'area', $filter_area);
				
				$data['filter_area'] = $filter_area;
			}
		}
		
		return $data;
	}
	
	public function _go_to_hotel_detail_page($data){
		
		$selected_hotel = $data['selected_hotel'];
		
		$search_criteria = $data['search_criteria'];
		
		$check_rate_params['startdate'] = $search_criteria['startdate'];
		$check_rate_params['enddate'] = $search_criteria['enddate'];
		$check_rate_params['night'] = $search_criteria['night'];
		
		echo '[hotel-detail-page]'.site_url('khach-san/'.$selected_hotel['url_title'].'-'.$selected_hotel['id'].'.html?'.http_build_query($check_rate_params));
		
	}
	
	public function _go_to_search_suggestion_page($data){
		
		$search_criteria = $data['search_criteria'];
		
		echo '[search-suggestion]'.site_url('khach-san/ho-tro-tim-kiem.html?'.http_build_query($search_criteria));
		
	}
	
	public function _go_search_destination_page($data){
		
		$is_mobile 	=	is_mobile();
		
		$search_criteria = $data['search_criteria'];
		
		// filter area
		if(!empty($search_criteria['area'])){
		
			$area_des_id = $search_criteria['area'];
				
			$area_des = $this->Destination_Model->get_search_destination($area_des_id);
				
			$search_criteria['area_des'] = $area_des;
		}
		
		$selected_des = $data['selected_des'];
		
		$search_criteria['selected_des'] = $selected_des;
		
		$search_criteria['is_default'] = false;
		
		$data['search_criteria'] = $search_criteria;
		
		$data['parent_des'] = $this->Destination_Model->get_parent_destinations($selected_des);
		
		
		$data = $this->_load_search_form($data, $is_mobile);
		
		$data = $this->_load_sort_by($data);
		
		$data = $this->_load_prices_for_filter($data);
		
		$data = $this->_load_stars_for_filter($data);
		
		$data = $this->_load_facilities_for_filter($data);
			
		$data = $this->_load_near_areas_for_filter($data);
		
		$data = $this->_load_search_hotels($data);
		
		$data = $this->_set_paging_info($data);
		
		if($is_mobile){
			
			$data['hotel_list_results'] = $this->load->view('mobile/hotels/common/hotel_list', $data, TRUE);
			
			$data['hotel_search_filters'] = $this->load->view('mobile/hotels/hotel_search/search_filters', $data, TRUE);
				
			$data['hotel_search_sorts'] = $this->load->view('mobile/hotels/hotel_search/search_sorts', $data, TRUE);
				
			$this->load->view('mobile/hotels/hotel_search/search_results', $data);
		
		}else{
			
			$data['hotel_list_results'] = $this->load->view('hotels/common/hotel_list', $data, TRUE);
			
			$data['hotel_search_filters'] = $this->load->view('hotels/hotel_search/search_filters', $data, TRUE);
				
			$data['hotel_search_sorts'] = $this->load->view('hotels/hotel_search/search_sorts', $data, TRUE);
				
			$this->load->view('hotels/hotel_search/search_results', $data);
		}
			
		
		
	}
	
	function _load_search_hotels($data){
		
		$search_criteria = $data['search_criteria'];
		
		$data['count_results'] = $this->Hotel_Model->count_search_hotels($search_criteria);
		
		$hotels = $this->Hotel_Model->search_hotels($search_criteria);
		
		$facilities = isset($data['filter_facility']) ? $data['filter_facility'] : array();
		
		foreach ($hotels as $key =>  $hotel){
			
			$hotel = get_important_hotel_facility($hotel, $facilities);
			$hotels[$key] = $hotel;
		}
		
		$data['hotels'] = $hotels;
		
		return $data;
	}
	
	function _re_structure_destinaton_suggestions($destination_suggestions){
		
		$ret = array();
		
		foreach ($destination_suggestions as $value){
			
			$ret[$value['type']][] = $value;
		
		}
		
		return $ret;
		
	}
	
	public function _set_paging_info($data){
		
		$this->load->library('pagination');
		
		$search_criteria = $data['search_criteria'];
		
		$offset = !empty($search_criteria['page'])?$search_criteria['page'] : 0;
		
		$total_rows = $data['count_results'];
	
		$paging_config = get_paging_config($total_rows,site_url(HOTEL_SEARCH_PAGE),1);
		// initialize pagination
		$this->pagination->initialize($paging_config);
	
		$paging_info['paging_text'] = get_paging_text($total_rows, $offset);
	
		$paging_info['paging_links'] = $this->pagination->create_links();
	
		$data['paging_info'] = $paging_info;
		
		return $data;
	
	}
}
