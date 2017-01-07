<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News_Model extends CI_Model {	
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
	}
	
	function get_news_details($id)
    {
        if (empty($id))
        {
            return FALSE;
        }
        
        $this->db->where('id', $this->db->escape_str($id));
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('news');
        
        $result = $query->result_array();
        
        if (count($result) === 1)
        {
            $news = $result[0];
            
            $news['photos'] = $this->get_news_photos($news['id']);
            
            return $news;
        }
        
        return FALSE;
    }

    function get_news_photos($news_id)
    {
        $this->db->where('news_id', $news_id);
        
        $query = $this->db->get('news_photos');
        
        return $query->result_array();
    }
	
	function get_news($type, $limit = FLIGHT_NEWS_LIMIT, $related_id = null)
    {
        $now = date(DB_DATE_FORMAT);
        
        // get apply on the day of the week
        $apply_on = date('w', strtotime($now));
        
        $this->db->select('n.id, n.name, n.content, n.url_title, n.picture, n.short_description');
        
        $this->db->where('n.deleted !=', DELETED);
        
        $this->db->where('n.status', STATUS_ACTIVE);
        
        // get related news case
        if (! empty($related_id))
        {
            $this->db->where('n.id !=', $related_id);
        }
        
        $pr_where = "(`n`.`start_date` <= '" . $now . "'";
        $pr_where .= " AND (`n`.`end_date` is NULL OR `n`.`end_date` >= '" . $now . "'))";
        
        $this->db->where($pr_where);
        
        $this->db->where('n.type', $type);
        
        $this->db->order_by('n.position', 'asc');
        
        $this->db->limit($limit);
        
        $query = $this->db->get('news n');
        
        // echo $this->db->last_query();exit();
        
        $results = $query->result_array();
        
        return $results;
    }
	
	function get_related_news($related_id, $limit = FLIGHT_NEWS_LIMIT) {
		
		$now = date(DB_DATE_FORMAT);
			
		// get apply on the day of the week
		$apply_on = date('w', strtotime($now));
		
					// flight news
		$query_str = "(SELECT `id`, `name`, `type`, `content`, `url_title` FROM (`news`) ".
					"WHERE `deleted` != 1 AND `status` = 1 AND `id` != '". $related_id ."' ".
					"AND (`start_date` <= '". $now ."' AND (`end_date` is NULL OR `end_date` >= '". $now ."')) ".
					"AND `type` = ". M_FLIGHT ." ".
					"ORDER BY `position` asc LIMIT ". $limit . ") UNION ".
					// hotel news
					"(SELECT `id`, `name`, `type`, `content`, `url_title` FROM (`news`) ".
					"WHERE `deleted` != 1 AND `status` = 1 AND `id` != '". $related_id ."' ".
					"AND (`start_date` <= '". $now ."' AND (`end_date` is NULL OR `end_date` >= '". $now ."')) ".
					"AND `type` = ". M_HOTEL ." ".
					"ORDER BY `position` asc LIMIT ". $limit . ") UNION ".
					// cruise news
					"(SELECT `id`, `name`, `type`, `content`, `url_title` FROM (`news`) ".
					"WHERE `deleted` != 1 AND `status` = 1 AND `id` != '". $related_id ."' ".
					"AND (`start_date` <= '". $now ."' AND (`end_date` is NULL OR `end_date` >= '". $now ."')) ".
					"AND `type` = ". M_CRUISE ." ".
					"ORDER BY `position` asc LIMIT ". $limit . ") UNION ".
					// tour news
					"(SELECT `id`, `name`, `type`, `content`, `url_title` FROM (`news`) ".
					"WHERE `deleted` != 1 AND `status` = 1 AND `id` != '". $related_id ."' ".
					"AND (`start_date` <= '". $now ."' AND (`end_date` is NULL OR `end_date` >= '". $now ."')) ".
					"AND `type` = ". M_TOUR ." ".
					"ORDER BY `position` asc LIMIT ". $limit . ") UNION ".
					// general news
					"(SELECT `id`, `name`, `type`, `content`, `url_title` FROM (`news`) ".
					"WHERE `deleted` != 1 AND `status` = 1 AND `id` != '". $related_id ."' ".
					"AND (`start_date` <= '". $now ."' AND (`end_date` is NULL OR `end_date` >= '". $now ."')) ".
					"AND `type` = ". M_GENERAL ." ".
					"ORDER BY `position` asc LIMIT ". $limit . ")";
			
		$query = $this->db->query($query_str);	
		
		$all_news = $query->result_array();
		
		$relate_news = null;
		
		foreach ($all_news as $news) {
			
			switch ($news['type']) {
				case M_FLIGHT:
					$relate_news['flight'][] = $news;
					break;
				case M_HOTEL:
					$relate_news['hotel'][] = $news;
					break;
				case M_CRUISE:
					$relate_news['cruise'][] = $news;
					break;
				case M_TOUR:
					$relate_news['tour'][] = $news;
					break;
				case M_GENERAL:
					$relate_news['general'][] = $news;
					break;
			}
		}
			
		return $relate_news;
	}
	
	function get_first_page_news($is_mobile)
	{
	    $this->db->select('n.id, n.name, n.content, n.short_description, n.link, n.source, n.url_title, n.picture, n.date_created');
	    
	    $this->db->where('n.deleted !=', DELETED);
	    
	    $this->db->where('n.status', STATUS_ACTIVE);
	    
	    if ($is_mobile)
        {
            $this->db->limit(1);
        }
        else
        {
            $this->db->limit(7);
        }
	    
	    $this->db->order_by('n.position', 'asc');
	    
	    $this->db->order_by('n.date_created', 'desc');
	     
	    $query = $this->db->get('news n');
	     
	    return $query->result_array();
	}
	
	/**
	*  search news
	*
	*  @author toanlk
	*  @since  Sep 12, 2014
	*/
	function search_news($search_criteria, $limit = null)
	{
	    // paging
	    $paging_config = $this->config->item('paging_config');
	    
	    $offset = ! empty($search_criteria['page']) ? $search_criteria['page'] : 0;
	    
	    // query
	    $this->db->select('n.id, n.name, n.short_description, n.link, n.source, n.content, n.url_title, n.picture, n.date_created');
	    
	    $this->_build_search_condition($search_criteria);
	    
	    $this->db->where('n.deleted !=', DELETED);
	    	
	    $this->db->where('n.status', STATUS_ACTIVE);
	    
	    if (empty($limit))
        {
            $this->db->limit($paging_config['per_page'], $offset);
        }
        else
        {
            $this->db->limit($limit);
        }
	    
	    $this->db->order_by('n.position', 'asc');
	    
	    $this->db->order_by('n.date_created', 'desc');
	    
	    $query = $this->db->get('news n');
	    
	    return $query->result_array();
	}
	
	function _build_search_condition($search_criteria)
	{
		foreach ($search_criteria as $key => $value) 
		{
			switch ($key) {
				case 'category' :
					$this->db->where('category &'.pow(2, $value).' > 0');
					break;
				case 'first_news' :
				    $this->db->where_not_in('id', $value);
				    break;
			}
		}
	}
	
	/**
	 *  count_search_news()
	 *
	 *  count tour search results
	 *
	 *  @author toanlk
	 *  @since  Sep 11, 2014
	 */
	function count_search_news($search_criteria)
	{
	    $this->db->where('n.deleted !=', DELETED);
	    
	    $this->_build_search_condition($search_criteria);
	    	
	    $this->db->where('n.status', STATUS_ACTIVE);
	    
	    $this->db->order_by('n.position', 'asc');
	    
	    $query = $this->db->get('news n');
	
	    $results = $query->result_array();
	
	    return count($results);
	}
}
