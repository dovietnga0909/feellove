<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Cruise_Model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        
        $this->load->database();
        $this->load->model('Tour_Model');
    }

    function get_cruise_detail($id, $startdate = null)
    {
        $this->db->select('c.*, d.name as destination_name, d.type as destination_type');
        $this->db->from('cruises as c');
        $this->db->join('destinations as d', 'd.id = c.destination_id');
        $this->db->where('c.id', $id);
        $query = $this->db->get();
        $results = $query->result_array();
        if (count($results) > 0)
        {
            
            $cruise = $results[0];
            
            return $cruise;
        }
        
        return '';
    }
    
    /*
     * Get recent cruises in cookie
     */
    function get_recent_cruises($items, $startdate)
    {
        $cruises = null;
        
        if (isset($items['cruise']) && ! empty($items['cruise']))
        {
            foreach ($items['cruise'] as $item)
            {
                $cruise_ids[] = $item['cruise_id'];
            }
            
            $this->db->select('id, name, url_title, address, picture, star');
            
            $this->db->where('deleted !=', DELETED);
            
            $this->db->where_in('id', $cruise_ids);
            
            $query = $this->db->get('cruises');
            
            $cruises = $query->result_array();
            
            // $cruises = $this->get_cruise_price_from_4_list($results, $startdate);
            
            foreach ($cruises as $k => $cruise)
            {
                foreach ($items['cruise'] as $item)
                {
                    if ($item['cruise_id'] == $cruise['id'])
                    {
                        $cruise['item_id'] = $item['id'];
                    }
                }
                
                $cruises[$k] = $cruise;
            }
        }
        
        return $cruises;
    }

    function get_cruise_by_destination($des_id, $startdate, $limit = 5)
    {
        $this->db->select('c.id, c.name, c.url_title, c.address, c.picture, c.star, c.description, c.latitude, c.longitude');
        
        $this->db->from('cruises c');
        
        $this->db->where('c.deleted !=', DELETED);
        
        $this->db->where('c.destination_id', $des_id);
        
        $this->db->limit($limit);
        
        $this->db->order_by('c.position', 'asc');
        
        $query = $this->db->get();
        
        $cruises = $query->result_array();
        
        /*
         * $cruises = $this->get_cruise_price_from_4_list($cruises, $startdate); $cruises = $this->get_cruise_promotions_4_list($cruises, $startdate); $cruises = $this->get_cruise_bpv_promotion_4_list($cruises);
         */
        
        return $cruises;
    }

    /**
     * get all halong bay cruises
     *
     * @return unknown
     */
    function get_all_halong_bay_cruises()
    {
        $this->db->select('id, name, url_title, star');
        
        $this->db->from('cruises');
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('status', STATUS_ACTIVE);
        
        $this->db->where('destination_id', DESTINATION_HALONG);
        
        $this->db->order_by('position', 'asc');
        
        $query = $this->db->get();
        
        $cruises = $query->result_array();
        
        return $cruises;
    }

    function get_popular_halongbay_tours($startdate, $limit = 10)
    {
        $this->db->select('t.id, t.name, t.routes, t.url_title, t.duration, t.picture, t.cruise_id, c.star, t.review_score, t.review_number');
        
        $this->db->from('tours as t');
        
        $this->db->join('cruises as c', 'c.id = t.cruise_id');
        
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->where('t.destination_id', DESTINATION_HALONG);
        
        $this->db->order_by('t.position', 'asc');
        
        $this->db->limit($limit);
        
        $query = $this->db->get();
        
        $tours = $query->result_array();
        
        $tours = $this->Tour_Model->get_tour_price_from_4_list($tours, $startdate);
        
        // set promotion
        $tours = $this->Tour_Model->get_tour_promotions_4_list($tours, $startdate);
        
        // set promotion from Best Price
        $tours = $this->Tour_Model->get_tour_bpv_promotion_4_list($tours);
        
        // get route
        $tours = get_route($tours);
        
        return $tours;
    }

    function suggest_cruises($term)
    {
        $term = $this->db->escape_str($term);
        
        $match_sql = "MATCH(name) AGAINST ('" . $term . "*' IN BOOLEAN MODE)";
        
        $this->db->select('id, name, url_title, ' . $match_sql . ' AS score');
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where($match_sql);
        
        $this->db->order_by('score', 'desc');
        
        $this->db->order_by('name', 'asc');
        
        $this->db->limit(5);
        
        $query = $this->db->get('cruises');
        
        // echo $this->db->last_query();exit();
        $results = $query->result_array();
        
        return $results;
    }

    function get_cruise_facilities($str_facilities, $type = 3)
    {
        if (! empty($str_facilities))
        {
            $str_facilities = explode('-', $str_facilities);
            
            if (count($str_facilities) > 0)
            {
                $this->db->select('id, name, group_id, is_important');
                $this->db->where_in('id', $str_facilities);
                $this->db->where('deleted !=', DELETED);
                $this->db->where('status', STATUS_ACTIVE);
                $this->db->where('type_id & ' . pow(3, $type) . ' > 0'); // type cruise
                $this->db->order_by('name', 'asc');
                $query = $this->db->get('facilities');
                
                $results = $query->result_array();
                
                return $results;
            }
        }
        
        return array();
    }

    function get_cancellation_of_cruise($cruise_id)
    {
        $this->db->select('ca.*');
        $this->db->from('cruises c');
        $this->db->join('cancellations as ca', 'ca.id = c.cancellation_id');
        $this->db->where('c.deleted != ', DELETED);
        $this->db->where('c.id', $cruise_id);
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        if (count($results) > 0)
        {
            return $results[0];
        }
        
        return '';
    }

    function get_cruise_photos($cruise_id)
    {
        $this->db->where('cruise_id', $cruise_id);
        
        $this->db->order_by('position');
        
        $query = $this->db->get('photos');
        
        $results = $query->result_array();
        
        return $results;
    }

    function get_similar_cruises($cruise, $startdate, $is_mobile = false)
    {
        $this->db->where('id !=', $cruise['id']);
        
        $this->db->where('destination_id', $cruise['destination_id']);
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('star', $cruise['star']);
        
        $this->db->limit(4);
        
        $this->db->order_by('position', 'asc');
        
        $query = $this->db->get('cruises');
        
        $cruises = $query->result_array();
        
        //if ($is_mobile)
        //{
            $cruises = $this->get_cruise_price_from_4_list($cruises, $startdate);
        //}
        
        return $cruises;
    }

    function get_cruise_price_from_4_list($cruises, $startdate)
    {
        foreach ($cruises as $k => $cruise)
        {
            $tours = $this->get_cruise_tours($cruise['id']);
            $cruise_price_form = $this->get_cruise_price_from($tours, $startdate);
            
            $cruise['price_from'] = $cruise_price_form['price_from'];
            $cruise['price_origin'] = $cruise_price_form['price_origin'];
            $cruises[$k] = $cruise;
        }
        
        return $cruises;
    }

    function get_cruise_cabins($cruise_id, $has_detail = false)
    {
        $this->db->where('cruise_id', $cruise_id);
        
        $this->db->where('status', STATUS_ACTIVE);
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->order_by('position');
        
        $query = $this->db->get('cabins');
        
        $results = $query->result_array();
        
        if ($has_detail)
        {
            
            foreach ($results as $key => $value)
            {
                
                $value['cabin_facilities'] = $this->get_cruise_facilities($value['facilities'], 3);
                
                $results[$key] = $value;
            }
        }
        
        return $results;
    }

    /**
     * Get cruise selected in auto complete function
     */
    function get_search_cruise($cruise_id)
    {
        $this->db->select('id, name, url_title, extra_cancellation');
        $this->db->where('deleted !=', DELETED);
        $this->db->where('status', STATUS_ACTIVE);
        $this->db->where('id', $cruise_id);
        
        $query = $this->db->get('cruises');
        
        $results = $query->result_array();
        
        if (count($results) > 0)
        {
            return $results[0];
        }
        else
        {
            return '';
        }
    }

    function get_selected_cruise($cruise_id)
    {
        $this->db->select('id, name, url_title, address, star, picture, partner_id, destination_id, infant_age_util,
				children_age_to, check_in, check_out, infants_policy, children_policy');
        $this->db->where('deleted !=', DELETED);
        $this->db->where('status', STATUS_ACTIVE);
        $this->db->where('id', $cruise_id);
        
        $query = $this->db->get('cruises');
        
        $results = $query->result_array();
        
        if (count($results) > 0)
        {
            return $results[0];
        }
        else
        {
            return '';
        }
    }

    function get_cruise_tours($cruise_id)
    {
        $this->db->select('t.id, t.name, t.url_title, t.tour_highlight, t.duration, t.notes');
        
        $this->db->where('t.cruise_id', $cruise_id);
        
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->order_by('position', 'asc');
        
        $query = $this->db->get('tours t');
        
        $tours = $query->result_array();
        
        foreach ($tours as $k => $tour)
        {
            
            $tour['itinerary'] = $this->Tour_Model->get_itineraries($tour['id']);
            
            $tours[$k] = $tour;
        }
        
        return $tours;
    }

    function get_search_filter_prices($search_criteria)
    {
        $selected_des = $search_criteria['selected_des'];
        
        if (empty($selected_des))
            return array();
        
        $startdate = $search_criteria['startdate'];
        
        $startdate = format_bpv_date($startdate);
        
        $this->db->select('tpf.range_index, count(*) as number');
        
        $this->db->from('tours as t');
        
        $this->db->join('tour_price_froms tpf', 'tpf.tour_id = t.id AND tpf.date = "' . $startdate . '"');
        
        if (! empty($search_criteria['selected_des']))
        {
            $this->db->join('destination_tours as dt', 'dt.tour_id = t.id');
            $this->db->where('dt.destination_id', $search_criteria['selected_des']['id']);
        }
        
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->group_by('tpf.range_index');
        
        $query = $this->db->get('');
        
        $results = $query->result_array();
        
        return $results;
    }

    function get_search_filter_stars($search_criteria)
    {
        $this->db->select('c.star, count(*) as number');
        
        $this->db->from('tours as t');
        
        $this->db->join('cruises as c', 'c.id = t.cruise_id');
        
        if (! empty($search_criteria['selected_des']))
        {
            $this->db->join('destination_tours as dt', 'dt.tour_id = t.id');
            $this->db->where('dt.destination_id', $search_criteria['selected_des']['id']);
        }
        
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->group_by('c.star');
        
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        return $results;
    }

    function get_search_filter_facilities($search_criteria)
    {
        
        // $selected_des = $search_criteria['selected_des'];
        
        // if(empty($selected_des)) return array();
        
        // $this->db->distinct();
        $this->db->select('fa.id, fa.name, fa.group_id, fa.is_important, count(*) as number');
        
        $this->db->from('cruise_facilities cf');
        
        $this->db->join('facilities fa', 'fa.id = cf.facility_id');
        
        $this->db->join('cruises c', 'c.id = cf.cruise_id');
        
        $this->db->where('fa.type_id & 2 > 0'); // facility for cruise = 1, for room type = 2
        
        $this->db->where('fa.deleted !=', DELETED);
        
        $this->db->where('fa.cruise_id', 0); // no specific cruise facility
        
        $this->db->where('fa.status', STATUS_ACTIVE);
        
        $this->db->group_by('fa.id');
        
        $this->db->order_by('fa.position', 'asc');
        
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        return $results;
    }

    function count_search_tours($search_criteria)
    {
        $startdate = $search_criteria['startdate'];
        
        $startdate = format_bpv_date($startdate);
        
        $this->db->_protect_identifiers = false;
        
        $this->db->select('t.id');
        
        $this->db->from('tours as t');
        
        $this->db->join('cruises as c', 'c.id = t.cruise_id');
        
        $this->db->join('tour_price_froms tpf', 'tpf.tour_id = t.id AND tpf.date = "' . $startdate . '"', 'left outer');
        
        $this->_build_search_condition($search_criteria);
        
        $this->db->group_by('t.id');
        
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        $cnt = count($results);
        
        // $cnt = $this->db->count_all_results();
        
        return $cnt;
    }

    function _build_search_condition($search_criteria)
    {
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        if (! empty($search_criteria['duration']))
        {
            $this->db->where('t.duration', $search_criteria['duration']);
        }
        
        if (! empty($search_criteria['selected_cruise']))
        {
            $this->db->where('t.cruise_id', $search_criteria['selected_cruise']['id']);
        }
        
        if (! empty($search_criteria['selected_des']))
        {
            $this->db->join('destination_tours as dt', 'dt.tour_id = t.id');
            $this->db->where('dt.destination_id', $search_criteria['selected_des']['id']);
        }
        
        if (! empty($search_criteria['star']))
        {
            $stars = explode(',', $search_criteria['star']);
            if (count($stars) > 0)
            {
                
                for ($i = 1; $i <= 5; $i ++)
                {
                    if (in_array($i, $stars))
                    {
                        $stars[] = $i + 0.5;
                    }
                }
                $this->db->where_in('c.star', $stars);
            }
        }
        
        if (! empty($search_criteria['price']))
        {
            
            $prices = explode(',', $search_criteria['price']);
            
            if (count($prices) > 0)
            {
                $this->db->where_in('tpf.range_index', $prices);
            }
        }
        
        if (! empty($search_criteria['facility']))
        {
            
            $fa_ids = explode(',', $search_criteria['facility']);
            
            if (count($fa_ids) > 0)
            {
                
                $fa_sql = ' AND cf.facility_id IN (' . $search_criteria['facility'] . ')';
                
                $sql_cond = '((SELECT COUNT(*) FROM cruise_facilities as cf WHERE c.id = cf.cruise_id ' . $fa_sql . ') = ' . count($fa_ids) . ')';
                $this->db->where($sql_cond);
            }
        }
    }

    function search_tours($search_criteria)
    {
        $startdate = $search_criteria['startdate'];
        
        $startdate = format_bpv_date($startdate);
        
        $paging_config = $this->config->item('paging_config');
        $offset = ! empty($search_criteria['page']) ? $search_criteria['page'] : 0;
        
        $select_sql = 't.id, t.cruise_id, t.name, t.routes, t.duration, t.url_title, c.star, t.description, t.picture, t.review_score, t.review_number, c.facilities, tpf.price_origin, IF(p.id IS NOT NULL, tpf.price_from, tpf.price_origin) as price_from';
        
        $this->db->_protect_identifiers = false;
        
        $this->db->select($select_sql);
        
        $this->db->from('tours as t');
        
        $this->db->join('cruises as c', 'c.id = t.cruise_id');
        
        $this->db->join('tour_price_froms tpf', 'tpf.tour_id = t.id AND tpf.date = "' . $startdate . '"', 'left outer');
        
        $this->db->join('promotions as p', 'p.id = tpf.promotion_id AND ' . sql_join_tour_promotion($startdate), 'left outer');
        
        $this->_build_search_condition($search_criteria);
        
        if (isset($search_criteria['sort']))
        {
            
            if ($search_criteria['sort'] == SORT_BY_PRICE)
            {
                
                $this->db->order_by('IF(ISNULL(price_from),1,0),price_from');
                
                // $this->db->order_by('price_from IS NULL','desc');
            }
            
            if ($search_criteria['sort'] == SORT_BY_REVIEW)
            {
                $this->db->order_by('t.review_score', 'desc');
            }
            
            if ($search_criteria['sort'] == SORT_BY_NAME)
            {
                $this->db->order_by('t.name', 'asc');
            }
            
            if ($search_criteria['sort'] == SORT_BY_STAR)
            {
                $this->db->order_by('c.star', 'asc');
            }
        }
        
        $this->db->limit($paging_config['per_page'], $offset);
        
        $this->db->order_by('t.position', 'asc');
        $this->db->group_by('t.id');
        
        $query = $this->db->get();
        
        $tours = $query->result_array();
        
        // set promotion
        $tours = $this->Tour_Model->get_tour_promotions_4_list($tours, $startdate);
        
        // set promotion from Best Price
        $tours = $this->Tour_Model->get_tour_bpv_promotion_4_list($tours);
        
        // get routes
        $tours = get_route($tours);
        
        return $tours;
    }

    function search_cruise_suggestion($term)
    {
        $term = urldecode($term);
        
        $exp_term = explode(' ', $term);
        
        $search_term = '';
        foreach ($exp_term as $exp)
        {
            $search_term .= $exp . '* ';
        }
        $search_term = trim($search_term);
        
        $match_sql = "MATCH(name) AGAINST ('" . $search_term . "' IN BOOLEAN MODE)";
        
        $this->db->select('id, name, url_title, star, ' . $match_sql . ' AS score');
        
        $this->db->from('cruises');
        
        $this->db->where($match_sql);
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('status', STATUS_ACTIVE);
        
        $this->db->order_by('score', 'desc');
        
        $this->db->order_by('position', 'asc');
        
        $this->db->order_by('name', 'asc');
        
        $query = $this->db->get();
        
        // echo $this->db->last_query();exit();
        
        return $query->result_array();
    }

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

    function search_destination_suggestion($term)
    {
        $term = $this->_term_pre_processing($term);
        $term = $this->db->escape_str($term);
        
        $match_sql = "MATCH(d.name) AGAINST ('" . $term . "*' IN BOOLEAN MODE)";
        
        $this->db->select('d.id, d.name, d.type, p.name as parent_name, ' . $match_sql . ' AS score');
        
        $this->db->from('destination_tours as dt');
        
        $this->db->join('destinations as d', 'd.id = dt.destination_id');
        
        $this->db->join('destinations as p', 'p.id = d.parent_id');
        
        $this->db->where($match_sql);
        
        $this->db->where('d.deleted !=', DELETED);
        
        $this->db->order_by('score', 'desc');
        
        $this->db->order_by('d.name', 'asc');
        
        $this->db->group_by('d.id');
        
        $query = $this->db->get();
        
        // print_r($this->db->last_query());exit();
        
        return $query->result_array();
    }

    function search_city_suggestion()
    {
        $this->db->select('d.id, d.name, d.type, p.name as parent_name');
        
        $this->db->from('destination_tours as dt');
        
        $this->db->join('destinations as d', 'd.id = dt.destination_id');
        
        $this->db->join('destinations as p', 'p.id = d.parent_id');
        
        $this->db->where('d.deleted !=', DELETED);
        
        $this->db->order_by('d.position', 'asc');
        
        $this->db->group_by('d.id');
        
        $query = $this->db->get();
        
        return $query->result_array();
    }

    function search_main_cruise_suggestion()
    {
        $this->db->select('id, name, url_title, star');
        
        $this->db->from('cruises');
        
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('status', STATUS_ACTIVE);
        
        $this->db->order_by('position', 'asc');
        
        $this->db->order_by('name', 'asc');
        
        $query = $this->db->get();
        
        return $query->result_array();
    }

    function get_all_cruises()
    {
        $this->db->select('id, name');
        $this->db->where('deleted !=', DELETED);
        
        $this->db->order_by('name', 'asc');
        $this->db->order_by('position', 'asc');
        
        $query = $this->db->get('cruises');
        
        return $query->result_array();
    }

    function get_similar_tours($tour, $startdate = null, $is_mobile = false)
    {
        $this->db->select('t.*, c.id as cruise_id, c.name as cruise_name, c.star, c.url_title as cruise_url_title');
        
        $this->db->from('tours as t');
        
        $this->db->join('cruises c', 'c.id = t.cruise_id', 'left outer');
        
        $this->db->where('t.status', STATUS_ACTIVE);
        
        $this->db->where('t.deleted !=', DELETED);
        
        $this->db->where('c.deleted !=', DELETED);
        
        $this->db->where('t.duration', $tour['duration']);
        
        $this->db->where('t.id !=', $tour['id']);
        
        $this->db->order_by("t.position", "asc");
        
        //if ($is_mobile)
        //{
            $this->db->limit(5);
        //}
        
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        if (! empty($startdate))
        {
            $results = $this->Tour_Model->get_tour_price_from_4_list($results, $startdate);
        }
        
        return $results;
    }

    function get_cruise_tour_surcharges($tour, $startdate, $enddate)
    {
        $apply_tour_surcharge = array();
        
        $this->db->select('s.id, s.name, s.description, st.adult_amount, st.children_amount, s.charge_type, s.start_date, s.end_date, s.week_day');
        
        $this->db->join('surcharges s', 's.id = st.surcharge_id', 'left outer');
        
        $this->db->where('s.cruise_id', $tour['cruise']['id']);
        $this->db->where('s.start_date <=', $enddate);
        $this->db->where('s.end_date >=', $startdate);
        $this->db->where('s.deleted !=', DELETED);
        
        $this->db->where('st.tour_id', $tour['id']);
        
        $query = $this->db->get('surcharge_tours st');
        $apply_tour_surcharge = $query->result_array();
        
        return $apply_tour_surcharge;
    }

    function get_cruise_price_from($tours, $startdate)
    {
        $cruise_price_form = null;
        
        if (! empty($tours))
        {
            $tours = $this->Tour_Model->get_tour_price_from_4_list($tours, $startdate);
            
            foreach ($tours as $tour)
            {
                if (empty($tour['price_from']))
                    continue;
                
                if (empty($cruise_price_form) || $cruise_price_form['price_from'] > $tour['price_from'])
                {
                    $cruise_price_form['price_from'] = $tour['price_from'];
                    $cruise_price_form['price_origin'] = $tour['price_origin'];
                }
            }
        }
        
        return $cruise_price_form;
    }

    function get_all_available_cruise_promotions($cruise_id)
    {
        $today = date(DB_DATE_FORMAT); // today
                                       
        // get promotions for list hotels
        $this->db->select('id, name, offer, stay_date_from, stay_date_to, book_date_from, book_date_to, hotel_id, check_in_on');
        
        $this->db->from('promotions');
        
        $sql_cond = 'book_date_from <= "' . $today . '" AND book_date_to >= "' . $today . '"' . ' AND stay_date_to >= "' . $today . '"' . ' AND deleted != ' . DELETED;
        
        $this->db->where($sql_cond);
        
        $this->db->where('show_on_web', STATUS_ACTIVE);
        
        $this->db->where('cruise_id', $cruise_id);
        
        $this->db->order_by('id', 'asc');
        
        $query = $this->db->get();
        
        $promotions = $query->result_array();
        
        return $promotions;
    }

    function get_cruise_bpv_promotions($cruise_id)
    {
        $today = date(DB_DATE_FORMAT);
        
        $this->db->distinct();
        $this->db->select('b.*, bh.cruise_id');
        $this->db->from('bpv_promotion_cruises bh');
        $this->db->join('bpv_promotions b', 'b.id = bh.bpv_promotion_id');
        $this->db->where('bh.cruise_id', $cruise_id);
        $this->db->where('b.status', STATUS_ACTIVE);
        $this->db->where('b.deleted !=', DELETED);
        $this->db->where('b.public', STATUS_ACTIVE);
        $this->db->where('b.expired_date >=', $today);
        $this->db->order_by('b.id', 'asc');
        
        $query = $this->db->get();
        $bpv_promotions = $query->result_array();
        
        return $bpv_promotions;
    }
}
