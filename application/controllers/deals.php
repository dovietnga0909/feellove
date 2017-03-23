<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deals extends CI_Controller {
	
	public function __construct()
    {
        
       	parent::__construct();
       	
       	$this->load->helper(array('form','text','booking','hotel','flight', 'land_tour'));
       	
       	$this->load->language(array('deal','hotel'));
       	
       	$this->load->model(array('Deal_Model','Flight_Model','Hotel_Model','Land_Tour_Model'));
	
	}
	
	function index()
	{
		set_cache_html();
		
		$data = $this->_set_common_data();
		
		$data['meta'] = get_header_meta(HOT_DEAL_PAGE);
		
		$data = $this->_load_hotel_deals($data);
		
		$data['bpv_ads'] = load_bpv_ads(AD_PAGE_DEAL);
		
		$data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
		$data['bpv_content'] = $this->load->view('deals/deals', $data, TRUE);
		
		$this->load->view('_templates/bpv_layout', $data);
	}
	
	function _set_common_data($data = array()){
		$this->session->set_userdata('MENU', MNU_DEALS);
		
		$data = build_search_criteria($data, MODULE_HOTEL);
		
		//$data = get_page_theme($data, true);
		
		$data['page_css'] = get_static_resources('deal.css');
		
		$data['des_id'] = $this->input->get('des_id');
		
	
		return $data;
	}
	
	function _load_hotel_deals($data){
		
		$important_facilities = $this->Deal_Model->get_important_facilities();
		
		$top_des_has_promotion = array();
		
		$top_des = $this->Deal_Model->get_hotel_top_des();
		
		foreach ($top_des as $k=>$des){
			
			$hotels = $this->Deal_Model->get_hotel_in_des_has_promotion($des['id']);
			
			if(count($hotels) > 0){
				/*
				foreach ($hotels as $key =>  $hotel){
						
					$hotel = get_important_hotel_facility($hotel, $important_facilities);
					$hotels[$key] = $hotel;
				}*/
				
				
				$des['hotels'] = $hotels;
				
				
				//if(count($top_des_has_promotion) < 10){
				
					$top_des_has_promotion[] = $des;
				
				//}
				
			}
			
		}
		
		
		$data['top_des'] = $top_des_has_promotion;
		
		return $data;
	}
	
	function apply_promotion_code(){
		
		$service_type = $this->input->post('service_type');
		
		$code = $this->input->post('pro_code');
		
		$hotel_id = $this->input->post('hotel_id');
		
		$cruise_id = $this->input->post('cruise_id');
		
		$tour_id = $this->input->post('tour_id');
		
		$nr_passengers = $this->input->post('nr_passengers');
		
		$pro_phone = $this->input->post('pro_phone');
		
		$discount_info = get_pro_code_discount_info($code, $service_type, $hotel_id, $cruise_id, $tour_id, $nr_passengers, $pro_phone, true);
		
		if(!empty($discount_info)){
			echo json_encode($discount_info);
		} else {
			echo '';
		}
		
	}

}
