<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Template_model extends CI_Model{
	function __construct(){
        parent::__construct();	
		$this->load->database();
	}
	
	function search_template($search_criteria = '', $num, $offset, $order_field = 'id', $order_type = 'asc'){
		
		$this->db->select('t.id, t.name, t.type, t.status, t.content, t.user_created_id, t.date_created, t.user_modified_id, t.date_modified, u.username as last_modified_by, un.username as created_template_by');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->join('users u', 't.user_modified_id = u.id', 'left outer');
		$this->db->join('users un', 't.user_created_id = un.id', 'left outer');
		
		$this->db->where('t.deleted !=', DELETED);
		
		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get('templates t', $num, $offset);
		
		return $query->result_array();
	}
	
	private function _set_search_criteria($search_criteria = ''){
		
		if ($search_criteria == '')	{
					
			return;
		}
		
		foreach ($search_criteria as $key => $value) {
			switch ($key) {
				case 'search_text' :
					$value =  $this->db->escape_like_str($value);
					if(!empty($value)){
						$this->db->like('t.name', $value, 'both');
					}
					break;
				case 'template_type' :
				    $this->db->where('t.type &'.pow(2, $value).' > 0');
				    break;
				case 'status':
					$this->db->where('t.status', $value);
			}
		}
	}
	
	function get_numb_template($search_criteria = ''){
		
		$this->db->select('count(*) as number');
		
		$this->_set_search_criteria($search_criteria);
		
		$this->db->where('t.status', STATUS_ACTIVE);
		
		$this->db->where('t.deleted !=', DELETED);
		
		$query = $this->db->get('templates t');
		
		$results = $query->result_array();
		
		if(count($results)>0 ){
			
			return $results[0]['number'];
		}
	}
	
	function create_template($template){
	
		// Additional data
		$additional_data = array(
				'user_created_id'	=> get_user_id(),
				'user_modified_id'	=> get_user_id(),
				'date_created'		=> date(DB_DATE_TIME_FORMAT),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
				'status'			=> STATUS_ACTIVE,
		);
		
		//filter out any data passed that doesnt have a matching column in the users table
		//and merge the set user data and the additional data
		$template_data = array_merge($template, $additional_data);
	
		$this->db->insert('templates', $template_data);
	
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	
	}
	
	function get_templates($id) {
	
		if(empty($id)) {
			return FALSE;
		}
		$this->db->where('id', $this->db->escape_str($id));
		
		$this->db->where('deleted !=', DELETED);
	
		$query = $this->db->get('templates');
	
		$results = $query->result_array();
	
		if (count($results) === 1){
			
			return $results[0];
		}
		return FALSE;
	}
	
	function update_templates($template){
		
		// Additional data
		$additional_data = array(
				'user_modified_id'	=> get_user_id(),
				'date_modified'		=> date(DB_DATE_TIME_FORMAT),
		);
		
		$template_data = array_merge($template, $additional_data);
		
		$this->db->trans_start();
		
		$this->db->update('templates', $template_data, array('id' => $template['id']));

		$this->db->trans_complete();
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	function delete_templates($id){
		
		// update facilities table
		$templates['deleted'] = DELETED;
	
		$this->db->update('templates', $templates, array('id' => $id));
		
		$error_nr = $this->db->_error_number();
		
		return !$error_nr;
	}
	
	
	
}