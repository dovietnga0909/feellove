<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itinerary_Model extends CI_Model
{	

	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_numb_itineraries($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		
		return $this->db->count_all_results('itineraries i');
	}
	
	function search_itineraries($search_criteria = ''
		, $num, $offset
		, $order_field = 'position', $order_type = 'asc')
	{	
		//$this->benchmark->mark('code_start');
		
		$this->db->select('i.*, u.username as last_modified_by');
		
		$this->db->join('users u', 'i.user_modified_id = u.id', 'left outer');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->order_by($order_field, $order_type);
		
		$query = $this->db->get('itineraries i', $num, $offset);
		
		$itineraries = $query->result_array();
	
		//$this->benchmark->mark('code_end');
		
		//echo $this->benchmark->elapsed_time('code_start', 'code_end');exit();
		
		return $itineraries;
	}

	public function _set_search_criteria($search_criteria = '')
	{
		$this->db->where('i.deleted !=', DELETED);
		
		if ($search_criteria == '')	{			
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				case 'tour_id' :
					$this->db->where('i.tour_id', $value);
					break;
			}
		}
	}
	
	/**
	 * create_itinerary
	 *
	 * @return bool
	 **/
	public function create_itinerary($itinerary)
	{
	    $this->db->trans_start();
	    
		$position = $this->get_max_position(0, $itinerary) + 1;
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'position'			=> $position,
		);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$itinerary_data = array_merge($itinerary, $additional_data);
		
		$tour_departures = null;
        
        if (isset($itinerary['tour_departures']))
        {
            
            $tour_departures = $itinerary['tour_departures'];
            
            unset($itinerary_data['tour_departures']);
        }
        
        $this->db->insert('itineraries', $itinerary_data);
        
        $id = $this->db->insert_id();
        
        if (isset($itinerary['tour_departures']))
        {
            
            $this->update_tour_departure_itinerary($tour_departures, $id);
        }
        
        $this->db->trans_complete();
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
	}
	
	function get_itinerary($id)
    {
        if (empty($id))
        {
            return FALSE;
        }
        
        $this->db->where('id', $this->db->escape_str($id));
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('itineraries');
        
        $result = $query->result_array();
        
        if (count($result) === 1)
        {
            $itinerary = $result[0];
            return $itinerary;
        }
        
        return FALSE;
    }
	
	function update_itinerary($itinerary)
    {
        $this->db->trans_start();
        
        $itinerary['user_modified_id'] = get_user_id();
        $itinerary['date_modified'] = date(DB_DATE_TIME_FORMAT);
        
        if (isset($itinerary['tour_departures']))
        {
            
            $this->update_tour_departure_itinerary($itinerary['tour_departures'], $itinerary['id']);
            
            unset($itinerary['tour_departures']);
        }
        
        $this->db->update('itineraries', $itinerary, array(
            'id' => $itinerary['id']
        ));
        
        $this->db->trans_complete();
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }
	
	function delete_itinerary($id) {
	
		$itinerary['deleted'] = DELETED;
	
		$this->db->update('itineraries', $itinerary, array('id' => $id));
	
		$error_nr = $this->db->_error_number();
	
		return !$error_nr;
	}
	
	function get_itineraries($tour_id, $extra_info = false)
    {
        if (! $extra_info)
        {
            $this->db->select('id, name');
        }
        else
        {
            $this->db->select('id, name, number_of_itineraries, max_extra_beds, max_occupancy, max_children, rack_rate, min_rate');
        }
        
        $this->db->where('tour_id', $tour_id);
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->order_by('position', 'asc');
        $query = $this->db->get('itineraries');
        
        return $query->result_array();
    }
	
	function _get_itinerary_main_photo($itinerary_id)
    {
        $photo_name = '';
        
        $this->db->select('p.name');
        
        $this->db->join('room_photos rp', 'rp.photo_id = p.id', 'left outer');
        
        $this->db->where('rp.itinerary_id', $itinerary_id);
        
        $this->db->where('rp.is_main_photo', 1);
        
        $this->db->limit(1);
        
        $query = $this->db->get('photos p');
        
        $photos = $query->result_array();
        
        if (! empty($photos))
        {
            $photo_name = $photos[0]['name'];
        }
        
        return $photo_name;
    }

    function get_max_position($type = 0, $itinerary = null)
    {
        if ($type == 0)
        {
            $this->db->select_max('position');
        }
        else
        {
            $this->db->select_min('position');
        }
        
        if (! empty($itinerary))
        {
            $this->db->where('tour_id', $itinerary['tour_id']);
        }
        
        $query = $this->db->get('itineraries');
        
        $results = $query->result_array();
        if (! empty($results))
        {
            
            return $results[0]['position'];
        }
        
        return 0;
    }

    function is_unique_itinerary_name($tour_id, $itinerary_name, $itinerary_id)
    {
        $this->db->where('tour_id', $tour_id);
        
        $this->db->where('name', $itinerary_name);
        
        if (! empty($itinerary_id))
        {
            $this->db->where('id !=', $itinerary_id);
        }
        
        $this->db->limit(1);
        
        $query = $this->db->get('itineraries');
        
        $results = $query->result_array();
        
        if (! empty($results))
        {
            return false;
        }
        
        return true;
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

    function get_tour_departure_itinerary($id)
    {
        if (empty($id))
        {
            return null;
        }
        
        $this->db->where('itinerary_id', $this->db->escape_str($id));
        
        $query = $this->db->get('tour_departure_itineraries');
        
        return $query->result_array();
    }
}

?>