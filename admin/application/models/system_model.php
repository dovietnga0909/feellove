<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class System_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        
        $this->load->database();
        $this->load->model('Hotel_Model');
        $this->load->model('Destination_Model');
        $this->load->model('News_Model');
        $this->load->model('Tour_Model');
    }

    /**
     * Create or update keywords field for searching function
     */
    function update_index_data()
    {
        $this->db->trans_start();
        
        // Destination data
        $destinations = $this->Destination_Model->get_all_destinations();
        
        foreach ($destinations as $des)
        {
            $destination['keywords'] = get_keywords($des['name'], 2);
            
            $this->db->update('destinations', $destination, array(
                'id' => $des['id']
            ));
        }
        
        // Hotel data
        
        $stopwords = array('khach san');
        
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('hotels');
        
        $hotels = $query->result_array();
        
        foreach ($hotels as $hotel)
        {
            $ud_hotel['keywords'] = get_keywords($hotel['name']);
            
            $this->db->update('hotels', $ud_hotel, array(
                'id' => $hotel['id']
            ));
        }
        
        $this->db->trans_complete();
        
        echo count($destinations) . ' destinations indexed, ' . count($hotels) . ' hotels indexed!';
        exit();
    }

    function fix_hotel_data()
    {
        $this->db->trans_start();
        
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('hotels');
        
        $hotels = $query->result_array();
        
        // update hotel title
        foreach ($hotels as $hotel)
        {
            
            $this->Hotel_Model->update_hotel($hotel);
        }
        
        $this->db->trans_complete();
        
        echo count($hotels) . ' hotels fixed!';
        exit();
    }

    function fix_flight_destination_data()
    {
        $this->db->trans_start();
        
        $this->db->select('id, name');
        $this->db->where('is_flight_destination', 1);
        $this->db->where('deleted != ', DELETED);
        $query = $this->db->get('destinations');
        
        $destiantions = $query->result_array();
        
        foreach ($destiantions as $des)
        {
            $this->Destination_Model->update_place_destination($des);
        }
        
        $this->db->trans_complete();
        
        echo count($destiantions) . ' destinations fixed!';
        exit();
    }

    function fix_url_title_data()
    {
        $destinations = $this->Destination_Model->get_all_destinations();
        
        foreach ($destinations as $des)
        {
            $this->Destination_Model->update_destination($des);
        }
        
        echo count($destinations) . ' destinations fixed!';
        
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('news');
        
        $news = $query->result_array();
        
        foreach ($news as $ne)
        {
            $this->News_Model->update_news($ne);
        }
        
        echo count($news) . ' news fixed!';
        exit();
    }

    function fix_tour_photo()
    {
        $this->db->trans_start();
        $this->db->select('id, name');
        $this->db->where('deleted !=', DELETED);
        $query = $this->db->get('cruises');
        $cruises = $query->result_array();
        
        foreach ($cruises as $cruise)
        {
            $this->db->select('id');
            $this->db->where('cruise_id', $cruise['id']);
            $this->db->order_by('id', 'asc');
            $query = $this->db->get('photos');
            $photos = $query->result_array();
            
            foreach ($photos as $k => $photo)
            {
                $photo['position'] = $k + 1;
                $this->db->update('photos', $photo, array(
                    'id' => $photo['id']
                ));
            }
        }
        
        $this->db->select('id, name');
        $this->db->where('deleted !=', DELETED);
        $query = $this->db->get('tours');
        $tours = $query->result_array();
        
        foreach ($tours as $tour)
        {
            $this->db->select('id');
            $this->db->where('tour_id', $tour['id']);
            $this->db->order_by('id', 'asc');
            $query = $this->db->get('photos');
            $photos = $query->result_array();
            
            foreach ($photos as $k => $photo)
            {
                $photo['position'] = $k + 1;
                $this->db->update('photos', $photo, array(
                    'id' => $photo['id']
                ));
            }
        }
        
        $this->db->trans_complete();
        
        echo 'Tour photos fixed!';
        exit();
    }
    
    function fix_cruise_tour_destinations()
    {
        // get all cruise tours
        $this->db->select('id, name');
        $this->db->where('deleted !=', DELETED);
        $this->db->where('cruise_id !=', 0);
        $query = $this->db->get('tours');
        $tours = $query->result_array();
        
        // get tour route
        foreach ($tours as $tour)
        {
            $this->db->select('dt.id');
            
            $this->db->join('destinations d', 'dt.destination_id = d.id', 'left outer');
            
            $this->db->where('dt.tour_id', $tour['id']);
            
            $this->db->order_by('dt.position', 'asc');
            
            $query = $this->db->get('destination_tours dt');
            
            $route = $query->result_array();
            
            foreach ($route as $value)
            {
                // update is_show_on_route=1
                
                $tour_des = array(
                    'is_show_on_route' => 1,
                );
                
                $this->db->update('destination_tours', $tour_des, array(
                    'id' => $value['id'],
                ));
            }
        }
        
        echo 'Tour destination: '.count($tours).' tours fixed!';
        exit();
    }
    
    function fix_tour_itinerary_departure()
    {
        $this->db->select('id, name');
        $this->db->where('deleted !=', DELETED);
        $this->db->where('cruise_id', 0);
        $this->db->where('departure_type', SINGLE_DEPARTING_FROM);
        $query = $this->db->get('tours');
        $tours = $query->result_array();
        
        foreach ($tours as $tour)
        {
            $tour_departures = $this->get_tour_departures($tour['id']);
            
            if(!empty($tour_departures)) {
                
                $this->db->where('i.tour_id', $tour['id']);
                
                $this->db->where('i.deleted !=', DELETED);
                
                $this->db->order_by('i.position', 'asc');
                $query = $this->db->get('itineraries as i');
                
                $itineraries =  $query->result_array();

                foreach ($itineraries as $itinerary) {
                    $itinerary['tour_departures'] = array($tour_departures[0]['id']);
                    $this->update_tour_departure_itinerary($itinerary['tour_departures'], $itinerary['id']);
                }
            }
        }
        
        echo 'Tour destination: '.count($tours).' tours fixed!';
        exit();
    }
    
    function get_tour_departures($tour_id) {
    
        $this->db->select('td.id, d.name, td.departure_date_type, td.departure_specific_date');
    
        $this->db->join('destinations as d', 'd.id = td.destination_id');
    
        $this->db->where('td.deleted !=', DELETED);
    
        $this->db->where('d.deleted !=', DELETED);
    
        $this->db->where('td.tour_id', $tour_id);
    
        $this->db->distinct('d.name');
    
        $this->db->order_by('name', 'asc');
    
        $query = $this->db->get('tour_departures td');
    
        return $query->result_array();
    }
    
    function update_tour_departure_itinerary($tour_departures, $itinerary_id)
    {
        $this->db->trans_start();
    
        // create or update to tour_departure_itineraries table
        $this->db->where('itinerary_id', $itinerary_id);
        $this->db->delete('tour_departure_itineraries');
    
        if (! empty($tour_departures))
        {
            foreach ($tour_departures as $value)
            {
                $itinerary_data = array(
                    'itinerary_id' => $itinerary_id,
                    'tour_departure_id' => $value
                );
    
                $this->db->insert('tour_departure_itineraries', $itinerary_data);
            }
        }
    
        $this->db->trans_complete();
    
        $error_nr = $this->db->_error_number();
    
        return ! $error_nr;
    }
}

?>