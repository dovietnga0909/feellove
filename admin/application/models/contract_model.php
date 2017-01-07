<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract_Model extends CI_Model{

	function __construct()
	{
		parent::__construct();

		$this->load->database();
	}


	// ------------------------------------------------------------------------

	/**
	 * Upload Files
	 * 
	 * @param File $files
	 * @return boolean
	 */
	public function upload_files($files)
	{
		foreach ($files as $k => $file)
		{

			// Additional data
			$additional_data = array(
					'user_created_id'	=> get_user_id(),
					'user_modified_id'	=> get_user_id(),
					'date_created'		=> date(DB_DATE_TIME_FORMAT),
					'date_modified'		=> date(DB_DATE_TIME_FORMAT),
			);

			$file = array_merge($file, $additional_data);

			$this->db->insert('contracts', $file);
			
			$file['id'] = $this->db->insert_id();
				
			$files[$k] = $file;
		}
		
		return $files;
	}

	/**
	 * Get File
	 * 
	 * This function returns file information
	 */
	function get_file($id)
	{
		if(empty($id)) {
			return null;
		}

		$this->db->select('id, name, hotel_id, cruise_id, tour_id');

		$this->db->where('id', $this->db->escape_str($id));

		$query = $this->db->get('contracts');

		$results = $query->result_array();

		if ( !empty($results) )
		{
			return $results[0];
		}

		return null;
	}

	/**
	 * Get Files
	 *
	 * This function returns all contract files of each category.
	 */
	function get_files($search_criteria)
	{

		$this->db->select('c.*, u.username as last_modified_by');

		$this->db->join('users u', 'c.user_modified_id = u.id', 'left outer');

		$this->_set_search_criteria($search_criteria);

		$this->db->order_by('date_created', 'desc');

		$query = $this->db->get('contracts c');

		return $query->result_array();
	}
	
	public function _set_search_criteria($search_criteria = '')
	{
	
		if ($search_criteria == '')	
		{
			return;
		}
		
		foreach ($search_criteria as $key => $value) 
		{
			switch ($key) {
				case 'hotel_id' :
					$this->db->where('hotel_id', $value);
					break;
				case 'cruise_id' :
					$this->db->where('cruise_id', $value);
					break;
				case 'tour_id' :
				    $this->db->where('tour_id', $value);
				    break;
			}
		}
	}
	
	function update_file($update_files, $is_array = false)
    {
        if ($is_array)
        {
            foreach ($update_files as $file)
            {   
                $file['user_modified_id'] = get_user_id();
                $file['date_modified'] = date(DB_DATE_TIME_FORMAT);
                
                $this->db->update('contracts', $file, array(
                    'id' => $file['id']
                ));
            }
        }
        else
        {
            $update_files['user_modified_id'] = get_user_id();
            $update_files['date_modified'] = date(DB_DATE_TIME_FORMAT);
            
            $this->db->update('contracts', $update_files, array(
                'id' => $update_files['id']
            ));
        }
        
        $error_nr = $this->db->_error_number();
        
        return !$error_nr;
    }

	function delete_contract($id)
	{

		$this->db->where('id', $id);
		$this->db->delete('contracts');

		$error_nr = $this->db->_error_number();

		return !$error_nr;
	}
}

?>