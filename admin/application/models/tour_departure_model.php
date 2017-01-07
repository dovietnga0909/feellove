<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tour_Departure_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        
        $this->load->database();
    }

    function get_departing_froms()
    {
        $this->db->select('id, name');
            
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('is_tour_departure_destination', STATUS_ACTIVE);
        
        $query = $this->db->get('destinations');
        
        $results = $query->result_array();
        
        return $results;
    }

    /**
     * ----- Create, update and delete tour departure -----
     * xxxxxxxxxxxxxxxxxxxxxxx
     */
    function create_tour_departure($tour_departure, $is_single_departing = false)
    {
        $position = $this->get_max_position(0, $tour_departure) + 1;
        
        $additional_data = array(
            'user_created_id' => get_user_id(),
            'user_modified_id' => get_user_id(),
            'date_created' => date(DB_DATE_TIME_FORMAT),
            'date_modified' => date(DB_DATE_TIME_FORMAT),
            'position' => $position
        );
        
        $tour_departure = array_merge($tour_departure, $additional_data);
        
        // create new tour departure
        $this->db->insert('tour_departures', $tour_departure);
        
        if ($is_single_departing)
        {
            $tour_update['departure_date_type'] = $tour_departure['departure_date_type'];
            $tour_update['departure_specific_date'] = $tour_departure['departure_specific_date'];
            $tour_update['id'] = $tour_departure['tour_id'];
            
            $this->Tour_Model->update_tour($tour_update);
        }
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function update_tour_departure($tour_departure, $is_single_departing = false)
    {
        $tour_departure['user_modified_id'] = get_user_id();
        $tour_departure['date_modified'] = date(DB_DATE_TIME_FORMAT);
        
        // create new tour
        $this->db->update('tour_departures', $tour_departure, array(
            'id' => $tour_departure['id']
        ));
        
        if ($is_single_departing)
        {
            $tour_update['departure_date_type'] = $tour_departure['departure_date_type'];
            $tour_update['departure_specific_date'] = $tour_departure['departure_specific_date'];
            $tour_update['id'] = $tour_departure['tour_id'];
            
            $this->Tour_Model->update_tour($tour_update);
        }
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function delete_tour_departure($id)
    {
        $tour_departure['deleted'] = DELETED;
        
        $this->db->update('tour_departures', $tour_departure, array(
            'id' => $id
        ));
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function get_tour_departure($tour_departure_id, $tour_id = null)
    {
        if (empty($tour_id) && empty($tour_departure_id))
        {
            return null;
        }
        
        if (! empty($tour_id))
        {
            $this->db->where('tour_id', $this->db->escape_str($tour_id));
        }
        else
        {
            $this->db->where('id', $this->db->escape_str($tour_departure_id));
        }
        
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('tour_departures');
        
        $result = $query->result_array();
        
        if (!empty($result))
        {
            return $result[0];
        }
        
        return null;
    }

    function get_tour_departures($tour_id)
    {
        $this->db->select('td.*, d.name, d.url_title');
        
        $this->db->join('destinations d', 'td.destination_id = d.id', 'left outer');
        
        $this->db->where('td.tour_id', $this->db->escape_str($tour_id));
        
        $this->db->where('td.deleted !=', DELETED);
        
        $this->db->order_by('date_modified', 'desc');
        
        $query = $this->db->get('tour_departures td');
        
        return $query->result_array();
    }

    function get_numb_tour_departures($search_criteria = '')
    {
        $this->_set_search_criteria($search_criteria);
        
        $this->db->where('td.deleted !=', DELETED);
        
        return $this->db->count_all_results('tour_departures td');
    }

    function search_tour_departures($search_criteria = '', $num, $offset, $order_field = 'position', $order_type = 'asc')
    {
        $this->db->select('td.*, d.name, u.username as last_modified_by');
        
        $this->db->join('users u', 'td.user_modified_id = u.id', 'left outer');
        
        $this->db->join('destinations d', 'td.destination_id = d.id', 'left outer');
        
        $this->_set_search_criteria($search_criteria);
        
        $this->db->where('td.deleted !=', DELETED);
        
        $this->db->order_by($order_field, $order_type);
        
        $query = $this->db->get('tour_departures td', $num, $offset);
        
        return $query->result_array();
    }

    function _set_search_criteria($search_criteria = '', $mask_name = 'td.')
    {
        $this->db->where($mask_name . 'deleted !=', DELETED);
        
        if ($search_criteria == '')
        {
            return;
        }
        foreach ($search_criteria as $key => $value)
        {
            switch ($key)
            {
                case 'tour_id':
                    $this->db->where($mask_name . 'tour_id', $value);
                    break;
            }
        }
    }

    /**
     * ----- Create, update and delete tour departure date -----
     * xxxxxxxxxxxxxxxxxxxxxxx
     */
    function create_tour_departure_date($tour_departure_date)
    {
        $this->db->insert('tour_departure_dates', $tour_departure_date);
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function update_tour_departure_date($tour_departure_date)
    {
        $this->db->update('tour_departure_dates', $tour_departure_date, array(
            'id' => $tour_departure_date['id']
        ));
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function delete_tour_departure_date($id)
    {
        $this->db->delete('tour_departure_dates', array(
            'id' => $id
        ));
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function get_tour_departure_date($id)
    {
        $this->db->where('id', $id);
        
        $query = $this->db->get('tour_departure_dates');
        
        $results = $query->result_array();
        
        if (! empty($results))
        {
            return $results[0];
        }
        
        return null;
    }

    function get_tour_departure_dates($tour_id, $tour_departure_id)
    {
        $this->db->where('tour_id', $tour_id);
        
        $this->db->where('tour_departure_id', $tour_departure_id);
        
        $query = $this->db->get('tour_departure_dates');
        
        return $query->result_array();
    }

    function get_max_position($type = 0, $tour_departure = null)
    {
        if ($type == 0)
        {
            $this->db->select_max('position');
        }
        else
        {
            $this->db->select_min('position');
        }
        
        if (! empty($tour_departure))
        {
            $this->db->where('tour_id', $tour_departure['tour_id']);
        }
        
        $query = $this->db->get('tour_departures');
        
        $results = $query->result_array();
        if (! empty($results))
        {
            
            return $results[0]['position'];
        }
        
        return 0;
    }
}

?>