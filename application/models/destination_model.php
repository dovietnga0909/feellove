<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Destination_Model extends CI_Model {	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_destination($id) {
	
		if(empty($id)) {
			return FALSE;
		}
		$this->db->select('id, name, url_title, parent_id, type, latitude, longitude, picture, 
				number_of_hotels, flight_tips, is_top_hotel, marketing_title, description_short, description, travel_tip,
				is_tour_destination_group, is_tour_includes_all_children_destination, nr_tour_domistic, nr_tour_outbound, is_outbound');
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
		
		$this->db->limit(1);
	
		$query = $this->db->get('destinations');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{
			return $result[0];
		}
	
		return FALSE;
	}
	
	/**
	 * 
	 * Get destination that the user selected on the autocomplete of the search form
	 * 
	 * @param destination id $id
	 * 
	 */
	function get_search_destination($id){
		
		$this->db->select('id, name, type, url_title, parent_id, latitude, longitude');
		$this->db->where('deleted !=', DELETED);
		$this->db->where('id', $id);
		
		$query = $this->db->get('destinations');
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			return $results[0];
		} else {
			return '';
		}
		
	}
	
	/**
	 * Get destination for the filter near the searched destination
	 * 
	 */
	
	function get_filter_destination($selected_des){
		
		$this->db->distinct();
		
		$this->db->select('d.id, d.name, d.type, d.number_of_hotels, d.parent_id');
		
		$this->db->from('destination_places as dp');
		
		$this->db->join('destinations as d','d.id = dp.destination_id');
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->where('d.number_of_hotels > ', 0);
		
		$this->db->where('dp.parent_id', $selected_des['id']);
		
		$this->db->order_by('d.position','asc');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
		
	}
	
	/**
	 * Return suggestion data for searching destinations
	 * 
	 * Steps:
	 *     - Get all match results as possible
	 *       Use full text search BOOLEAN MODE with * operators
	 *            
	 *     - Order return results by score
	 *       1. by score match in NATURAL MODE
	 *       2. by score match the exact term "search_text"
	 *       3. by score match if "search_text" begins with the word preceding
	 * 
	 * @param unknown $des_name
	 * @return unknown
	 */
	function search_destination_suggestion($des_name){
		
		$des_name = urldecode($des_name);
		
		// remove vietnamese tones
		$des_name = search_term_pre_process($des_name);
		
		// add operator
		$term = search_term_pre_process($des_name, true);
		
		$des_name = $this->db->escape_like_str($des_name);
		
		$term = $this->db->escape_like_str($term);
		
		// score for order
		$match_sql = "MATCH(d.keywords) AGAINST ('".$des_name."') as score";
		
		$match_1_sql = "MATCH(d.keywords) AGAINST ('\"".$des_name."\"' IN BOOLEAN MODE) as score_1";
		
		$match_2_sql = "MATCH(d.keywords) AGAINST ('".$term."' IN BOOLEAN MODE) as score_2";
		
		
		// begin sql command
		$this->db->select('d.id, d.name, d.keywords, d.type, d.number_of_hotels, p.name as parent_name,' . $match_sql . ',' . $match_1_sql . ',' . $match_2_sql );
		
		$this->db->from('destinations as d');
		
		$this->db->join('destinations as p', 'p.id = d.parent_id');
		
		$this->db->join('destination_places dp', 'dp.destination_id = d.id');
		
		$this->db->where("MATCH(d.keywords) AGAINST ('".$term."' IN BOOLEAN MODE)");
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->where('d.type >=', DESTINATION_TYPE_CITY);
		
		// in vietnam
		$this->db->where('dp.parent_id', DESTINATION_VIETNAM);
		
		$this->db->order_by('score','desc');
		
		$this->db->order_by('score_1','desc');
		
		$this->db->order_by('score_2','desc');
		
		$this->db->order_by('d.name','asc');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
		
	}
	
	function search_city_suggestion(){
		
		$this->db->select('d.id, d.name, d.type, d.number_of_hotels, p.name as parent_name');
		
		$this->db->from('destinations as d');
		
		$this->db->join('destinations as p', 'p.id = d.parent_id');
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->where('d.type', DESTINATION_TYPE_CITY);
		
		$this->db->where('d.parent_id', DESTINATION_VIETNAM);
		
		$this->db->where('d.number_of_hotels >',0);
		
		$this->db->order_by('d.parent_id','asc');
		
		$this->db->order_by('d.position','asc');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
		
	}
	
	function get_in_and_around_destination($des_id) {
		
		// get all places in and around destination
		$types = array(
				DESTINATION_TYPE_AIRPORT,
				DESTINATION_TYPE_BUS_STOP,
				DESTINATION_TYPE_TRAIN_STATION,
				DESTINATION_TYPE_ATTRACTION,
				DESTINATION_TYPE_SHOPPING_AREA,
				DESTINATION_TYPE_HERITAGE,
				DESTINATION_TYPE_LAND_MARK,
				DESTINATION_TYPE_AREA,
				DESTINATION_TYPE_DISTRICT,
		);
		
		$this->db->select('d.id, d.name, d.type, d.url_title, d.latitude, d.longitude');
		
		$this->db->join('destinations as d', 'd.id = dp.destination_id', 'left outer');
		
		$this->db->where_in('d.type', $types);
		
		$this->db->where_in('dp.parent_id', $des_id);
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->order_by('d.type','asc');
		
		$this->db->order_by('d.position','asc');
		
		$query = $this->db->get('destination_places dp');
		
		$results = $query->result_array();
		
		$in_and_around = array();
		
		foreach ($results as $des) {
			
			switch ($des['type']) {
				case DESTINATION_TYPE_AIRPORT:
					$in_and_around['airport'][] = $des;
					break;
				case DESTINATION_TYPE_TRAIN_STATION:
					$in_and_around['train_station'][] = $des;
					break;
				case DESTINATION_TYPE_BUS_STOP:
					$in_and_around['bus_stop'][] = $des;
					break;
				case DESTINATION_TYPE_ATTRACTION:
					$in_and_around['attractions'][] = $des;
					break;
				case DESTINATION_TYPE_SHOPPING_AREA:
					$in_and_around['shopping_areas'][] = $des;
					break;
				case DESTINATION_TYPE_HERITAGE:
					$in_and_around['heritages'][] = $des;
					break;
				case DESTINATION_TYPE_LAND_MARK:
					$in_and_around['landmarks'][] = $des;
					break;
				case DESTINATION_TYPE_AREA:
					$in_and_around['area'][] = $des;
					break;
				case DESTINATION_TYPE_DISTRICT:
					$in_and_around['district'][] = $des;
					break;
			}
		}
		
		//echo $this->db->last_query();exit();
		
		return $in_and_around;
	}
	
	function get_parent_destinations($des){
		
		$type = null;
		
		switch ($des['type']) {
			case DESTINATION_TYPE_AREA:
				$type = array(DESTINATION_TYPE_CITY, DESTINATION_TYPE_DISTRICT);
				break;
			case DESTINATION_TYPE_DISTRICT:
				$type = array(DESTINATION_TYPE_CITY);
				break;
			default:
				$type = array(DESTINATION_TYPE_CITY, DESTINATION_TYPE_DISTRICT, DESTINATION_TYPE_AREA);
				break;
		}
	
		$this->db->distinct();
		
		$this->db->select('dh.parent_id as id, d.name, d.url_title, d.type, d.picture, d.is_top_hotel');
	
		$this->db->from('destination_places as dh');
	
		$this->db->join('destinations as d','d.id = dh.parent_id');
	
		$this->db->where_in('d.type', $type);
	
		$this->db->where('dh.destination_id', $des['id']);
	
		$this->db->order_by('type', 'asc');
	
		$query = $this->db->get();
		
		//print_r($this->db->last_query());exit();
	
		$results = $query->result_array();
	
		return $results;
	}
	
	function get_customer_cities(){
		
		$this->db->select('d.id, d.name');
		
		$this->db->from('destination_places dp');
		
		$this->db->join('destinations d', 'd.id = dp.destination_id');
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->where('d.type', DESTINATION_TYPE_CITY);
		
		$this->db->where('dp.parent_id', DESTINATION_VIETNAM);
		
		$this->db->order_by('d.url_title');
		
		$query = $this->db->get();
		
		$results = $query->result_array();
		
		return $results;
	}
	
	/**
	 * Get city of the destination, used on Hotel Map 
	 * @param unknown $des_id
	 */
	function get_city_of_destination($des_id){
		
		$this->db->select('id, type');
		
		$this->db->where('id', $des_id);
		
		$this->db->where('type <=', DESTINATION_TYPE_CITY);
		
		$this->db->where('deleted !=', DELETED);
		
		$query = $this->db->get('destinations');
		
		$results = $query->result_array();
		
		if(count($results) > 0){
			
			return $results[0];
			
		} else {
			
			$this->db->select('d.id, d.type');
			
			$this->db->from('destination_places as dp');
			
			$this->db->join('destinations d', 'd.id = dp.parent_id');
			
			$this->db->where('dp.destination_id',$des_id);
		
			$this->db->where('d.type', DESTINATION_TYPE_CITY);
		
			$this->db->where('d.deleted !=', DELETED);
			
			$query = $this->db->get();
			
			$results = $query->result_array();
			
			if(count($results) > 0){
				
				return $results[0];
			}
			
		}
		
		return '';
	}
	
	
	function get_destinations_in_city($city_id){
	
		$this->db->select('d.id, d.name, d.type, d.picture, d.number_of_hotels, d.latitude, d.longitude, d.url_title');
	
		$this->db->from('destination_places dp');
	
		$this->db->join('destinations d','d.id = dp.destination_id');
	
		$this->db->where('dp.parent_id', $city_id);
		
		$this->db->where('d.type >', DESTINATION_TYPE_CITY);
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->order_by('d.type','asc');
		
		$this->db->order_by('d.position','asc');
	
		
		$query = $this->db->get();
	
		$des_result = $query->result_array();
	
		return $des_result;
	}
	
	/**
	 * Khuyenpv 12.09.2014
	 * Get all highlight sub-destination
	 */
	function get_children_highlight_des($parent_id){
		
		$this->db->select('d.id, d.name, d.url_title, d.nr_tour_domistic, d.nr_tour_outbound');
		
		$this->db->from('destination_places dp');
		
		$this->db->join('destinations d', 'd.id = dp.destination_id');
		
		$this->db->where('dp.parent_id', $parent_id);
		
		$this->db->where('d.deleted != ', DELETED);
		
		$this->db->where('d.is_tour_highlight_destination', STATUS_ACTIVE);
		
		$this->db->order_by('d.position','asc');
		
		$query = $this->db->get();
		
		$des_results = $query->result_array();
		
		return $des_results;
		
	}
	
	function get_destination_photos($destination_id, $photo_main = false){
	
		$this->db->select('id, name, caption, type, cruise_id, destination_id, width, height');
		
		if($photo_main){
			
			$this->db->where('type', 2);
			
		}
		
		$this->db->where('destination_id', $destination_id);
	
		$this->db->order_by('position');
	
		$query = $this->db->get('photos');
	
		return $query->result_array();
	}
	
	function get_activities($destination_id){
		
		$this->db->select('a.id, a.name, a.description, p.name as photo');
		
		$this->db->where('a.deleted !=' , DELETED);
		
		$this->db->join('photos as p', 'p.id = a.photos', 'left outer');
		
		$this->db->where('a.destination_id', $destination_id);
		
		$query = $this->db->get('activities a');
		
		$result = $query->result_array();
		
		return $result;
	}
	
	function get_destination_travel($des_id){
		
		$types = array(
				DESTINATION_TYPE_ATTRACTION,
				DESTINATION_TYPE_AREA,
		);
		
		$this->db->select('d.id, d.name, d.type, d.url_title, d.description, d.description_short');
		
		$this->db->join('destinations as d', 'd.id = dp.destination_id', 'left outer');
		
		$this->db->where_in('d.type', $types);
		
		$this->db->where_in('dp.parent_id', $des_id);
		
		$this->db->where('d.deleted !=', DELETED);
		
		$this->db->order_by('d.type','asc');
		
		$this->db->order_by('d.position','asc');
		
		$query = $this->db->get('destination_places dp');
		
		$des_travel = $query->result_array();
		
		$photo_main = TRUE;
		
		foreach($des_travel as $k =>$des){
			
			$des_photo_main = $this->get_destination_photos($des['id'], $photo_main);
			
			if(isset($des_photo_main) && count($des_photo_main) >0){
				
				$des_travel[$k]['photo'] = $des_photo_main[0]['name'];
				
			}else{
				
				$des_photo = $this->get_destination_photos($des_id);
				
				if(count($des_photo) >0){
					$des_travel[$k]['photo'] = $des_photo[0]['name'];
				}else{
					$des_travel[$k]['photo'] = "";
				}
				 
				
				
			}
			
		}
		
		return $des_travel;
	}

	/**
	 * get_nav_parent_destinations
	 *
	 * Get parent destinations for tour destination navigation
	 *
	 * @author toanlk
	 * @since  Dec 9, 2014
	 */
	function get_nav_parent_destinations($des)
	{
	    $this->db->distinct();
	
	    $this->db->select('dh.parent_id as id, d.name, d.url_title, d.type, d.picture, d.is_top_hotel');
	
	    $this->db->from('destination_places as dh');
	
	    $this->db->join('destinations as d','d.id = dh.parent_id');
	
	    $this->db->where_in('d.type', array(DESTINATION_TYPE_REGION, DESTINATION_TYPE_CONTINENT));
	    
	    $this->db->where('d.is_tour_destination_group', 1);
	
	    $this->db->where('dh.destination_id', $des['id']);
	
	    $this->db->order_by('type', 'desc');
	    
	    $this->db->limit(1);
	
	    $query = $this->db->get();
	
	    $results = $query->result_array();
	
	    return $results;
	}
}
