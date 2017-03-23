<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hotel_On_Map extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('hotel');
		$this->load->helper('rate');
		
		$this->load->model('Flight_Model');
		$this->load->model('Hotel_Model');
		$this->load->model('Destination_Model');
	}

	public function index()
	{
		$id = $this->input->get('id');
		
		if( !empty($id) && is_numeric($id) ) {
			$search_criteria = get_hotel_search_criteria();
			
			$data['hotel'] = $this->Hotel_Model->get_hotel_detail($id, $search_criteria['startdate']);
			
			echo $this->load->view('common/bpv_info_box', $data);
		}
		
		echo '';
	}
	
	public function get_hotel_main(){
	
		$id = $this->input->post('id');
		
		if( !empty($id) && is_numeric($id) ) {
			
			$search_criteria = get_hotel_search_criteria();
			
			$data = $this->Hotel_Model->get_main_hotel_on_map($id, $search_criteria['startdate']);
			
			$data['img_url'] = get_image_path(HOTEL, $data['picture'],'120x90');
			
			$data['full_url'] = hotel_build_url($data, $search_criteria);
			
			$data = json_encode($data);
			
			echo $data;
		}
	}
	
	public function get_hotels_in_city(){
	
		$hotel_id = $this->input->post('hotel_id');
		
		$des_id = $this->input->post('des_id');
		
		$is_get_hotel_in_city = $this->input->post('hotel_in_city');
		
		
		if(!empty($des_id) && is_numeric($des_id) ){
			
			$search_criteria = get_hotel_search_criteria();
			
			//if($is_get_hotel_in_city == 1){
				
				$city = $this->Destination_Model->get_city_of_destination($des_id);
				
				$des_id = !empty($city) ? $city['id'] : $des_id;
			//}
			
			$hotels = $this->Hotel_Model->get_hotels_in_city($des_id, $search_criteria['startdate'], $hotel_id);
		
			foreach ($hotels as $key=>$hotel){
				
				$hotel['img_url'] = get_image_path(HOTEL, $hotel['picture'],'160x120');
				
				$hotel['full_url'] = hotel_build_url($hotel, $search_criteria);
				
				$hotels[$key] = $hotel;
				
			}
			
			$hotels = json_encode($hotels);
			
			echo $hotels;
				
		} else {
			echo '';
		} 
		
	}
	
	public function get_destinations_in_city(){
	
		$des_id = $this->input->post('des_id');
		
		if(!empty($des_id) && is_numeric($des_id) ){
			
			$city = $this->Destination_Model->get_city_of_destination($des_id);
			
			if(!empty($city)){
			
				$destinations = $this->Destination_Model->get_destinations_in_city($city['id']);
				
				foreach ($destinations as $k => $des) {
					
					$des['full_url'] = get_url(HOTEL_DESTINATION_PAGE, $des);
					
					$destinations[$k] = $des;
				}
				
				
				$data = json_encode($destinations);
				
				echo $data;
			
			}
		}
		
	}
	
	function get_hotel_top_destination(){
	
		$top_des = $this->Hotel_Model->get_top_hotel_destinations();
		
		foreach ($top_des as $k => $des) {
			$des['full_url'] = get_url(HOTEL_DESTINATION_PAGE, $des);
			$top_des[$k] = $des;
		}
		
		$top_des = json_encode($top_des);
		
		echo $top_des;
	
	}
}