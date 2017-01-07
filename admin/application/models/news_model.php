<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News_Model extends CI_Model{	
		
	var $facility_config = array();
	
	function __construct()
    {
        parent::__construct();	
		
		$this->load->database();
		$this->config->load('news_meta');
	}
	
	function get_numb_news($search_criteria = '')
	{
		$this->_set_search_criteria($search_criteria);
		$this->db->where('n.deleted !=', DELETED);
		return $this->db->count_all_results('news n');
	}
	
	function search_news($search_criteria = ''
		, $num, $offset
		, $order_field = 'position', $order_type = 'asc')
	{	
		$this->db->select('n.*, u.username as last_modified_by');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->join('users u', 'n.user_modified_id = u.id', 'left outer');
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('news n', $num, $offset);
		
		$results = $query->result_array();
		
		foreach ($results as $k => $news) {
			$news['type_name'] = $this->_get_news_type($news['type']);
			$results[$k] = $news;
		}
		
		return $results;
	}
	
	function _get_news_type($type_id) {
		$news_types = $this->config->item('news_types');
		
		$types = '';
		
		foreach ($news_types as $k => $v) {
			if($k == $type_id) {
				$types = lang($v);
			}
		}
		
		return $types;
	}

	function _set_search_criteria($search_criteria = '', $mask_name = 'n.')
	{
		$this->db->where($mask_name . 'deleted !=', DELETED);
		
		if ($search_criteria == '')	{			
			return;
		}
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				case 'search_text' :
					$searchStr =  $this->db->escape_like_str($value);
					$this->db->like($mask_name.'name', $value, 'both');
					break;
				case 'type' :
					$this->db->where($mask_name.'type', $value);
					break;
				case 'category' :
				    $this->db->where($mask_name.'category &'.pow(2, $value).' > 0');
				    break;
				case 'status' :
					$this->db->where($mask_name.'status', $value);
					break;
			}
		}
	}
	
	/**
	 * create_facility
	 *
	 * @return bool
	 **/
	public function create_news($news)
	{
		$position = $this->get_max_position() + 1;
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'status'			=> 1,
				'position'			=> $position,
				'start_date'		=> date(DB_DATE_FORMAT, strtotime($news['start_date'])),
				'end_date'			=> date(DB_DATE_FORMAT, strtotime($news['end_date'])),
				'url_title'			=> url_title(convert_unicode($news['name']), '-', true)
		);
	
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$news_data = array_merge($news, $additional_data);
	
		$this->db->insert('news', $news_data);
	
		$id = $this->db->insert_id();
	
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function get_news($id, $get_photo = false) {
	
		if(empty($id)) {
			return FALSE;
		}
	
		$this->db->where('id', $this->db->escape_str($id));
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('news');
	
		$result = $query->result_array();
	
		if (count($result) === 1)
		{
			$news = $result[0];
			
			if($get_photo){
				 
				$news['photos'] = $this->get_news_photos($news['id']);
				 
			}
			
			return $news;
		}
	
		return FALSE;
	}
	
	function update_news($news) {
	
		$news['user_modified_id'] 	= get_user_id();
		$news['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
		
		if(isset($news['name'])) {
			$news['url_title'] = url_title(convert_unicode($news['name']), '-', true);
		}
		if(isset($news['start_date'])) {
			$news['start_date']		= date(DB_DATE_FORMAT, strtotime($news['start_date']));
		}
		if(isset($news['end_date'])) {
			$news['end_date']		= date(DB_DATE_FORMAT, strtotime($news['end_date']));
		}
	
		$this->db->update('news', $news, array('id' => $news['id']));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_news($id) {
	
		// update facilities table
		$facility['deleted'] = DELETED;
	
		$this->db->update('news', $facility, array('id' => $id));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function is_unique_field_value($str, $id, $column_name){
			
		$this->db->where('deleted !=', DELETED);
			
		$this->db->where($column_name, $str);
			
		if(!empty($id)){
	
			$this->db->where('id !=', $id);
	
		}
			
		$cnt = $this->db->count_all_results('news');
			
		return $cnt > 0;
	}

	
	function get_max_position($type = 0) {
	
		if($type == 0) {
			$this->db->select_max('position');
		} else {
			$this->db->select_min('position');
		}
	
		$query = $this->db->get('news');
	
		$results = $query->result_array();
		if (!empty($results)) {
	
			return $results[0]['position'];
		}
	
		return 0;
	}
	
	function get_news_photos($news_id){
		 
		$this->db->where('news_id', $news_id);
		 
		$query = $this->db->get('news_photos');
		 
		return $query->result_array();
	}
	
	function create_news_photos($photos){
		 
		foreach ($photos as $photo){
	
			$this->db->insert('news_photos', $photo);
	
		}
	}
	
	function get_photo($id){
		 
		$this->db->where('id', $id);
		 
		$query = $this->db->get('news_photos');
		 
		$results = $query->result_array();
		 
		if(count($results) > 0){
	
			return $results[0];
	
		}
		return FALSE;
		 
	}
	
	function delete_photo($id){
		 
		$this->db->where('id', $id);
		 
		$this->db->delete('news_photos');
		 
	}
	
	function update_news_photos($id, $p, $news_id){

		// update main photo
		if($p['is_main_photo'] == 1) {
			$update_p = array('is_main_photo' => 0);
			$this->db->where('news_id', $news_id);
			$this->db->update('news_photos', $update_p);
			
			// update new
			$photo = $this->get_photo($id);
			if(!empty($photo)) {
				$news = array('picture' => $photo['name']);
				$this->db->update('news', $news, array('id' => $news_id));
			}
		}
		
		$this->db->where('id', $id);
		$this->db->update('news_photos', $p);
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
}

?>