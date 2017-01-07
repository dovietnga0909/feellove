<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Category_Model extends CI_Model
{
    
    function __construct()
    {
        parent::__construct();
        
        $this->load->database();
    }

    function get_numb_categories($search_criteria = '')
    {
        $this->_set_search_criteria($search_criteria);
        $this->db->where('tc.deleted !=', DELETED);
        return $this->db->count_all_results('categories tc');
    }

    function search_categories($search_criteria = '', $num, $offset, $order_field = 'tc.position', $order_type = 'asc')
    {
        $ext_sql = '';
        if (! empty($search_criteria))
        {
            
            foreach ($search_criteria as $key => $value)
            {
                
                $value = $this->db->escape_str($value);
                
                if ($key == 'search_text'){
                	
                    $ext_sql = ", MATCH(tc.name) AGAINST ('" . $value . "*' IN BOOLEAN MODE) as score";
                    break;
                }
            }
        }
        
        $this->db->select('tc.*, u.username as last_modified_by' . $ext_sql);
        
        $this->db->join('users u', 'tc.user_modified_id = u.id', 'left outer');
        
        $this->_set_search_criteria($search_criteria);
        
        if (! empty($ext_sql))
        {
            $this->db->order_by('score', 'desc');
        }
        
        $this->db->order_by($order_field, $order_type);
        
        $query = $this->db->get('categories tc', $num, $offset);
        
        $results = $query->result_array();
        
        return $results;
    }

    function _set_search_criteria($search_criteria = '', $mask_name = 'tc.')
    {
        $this->db->where($mask_name . 'deleted !=', DELETED);
        
        if ($search_criteria == '')
        {
            return;
        }
        
        foreach ($search_criteria as $key => $value)
        {
            
            $value = $this->db->escape_str($value);
            
            switch ($key)
            {
                case 'search_text':
                    $this->db->where("MATCH(" . $mask_name . "name) AGAINST ('" . $value . "*' IN BOOLEAN MODE)");
                    break;
                case 'status':
                    $this->db->where($mask_name . 'status', $value);
                    break;
                case 'is_hot':
                    $this->db->where($mask_name . 'is_hot', $value);
                    break;
            }
        }
    }

    /**
     * create category
     *
     * @return bool
     *
     */
    public function create_category($category)
    {
        $this->db->trans_start();
        
        $position = $this->get_max_position() + 1;
        
        // Additional data
        $additional_data = array(
            'status' => STATUS_ACTIVE,
            'user_created_id' => get_user_id(),
            'user_modified_id' => get_user_id(),
            'date_created' => date(DB_DATE_TIME_FORMAT),
            'date_modified' => date(DB_DATE_TIME_FORMAT),
            'position' => $position
        );
        
        $category['url_title'] = url_title(convert_unicode($category['name']), '-', true);
        
        // filter out any data passed that doesnt have a matching column in the users table
        // and merge the set user data and the additional data
        $category_data = array_merge($category, $additional_data);
        
        $this->db->insert('categories', $category_data);
        
        $category['id'] = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    /**
     * get category
     *
     * @param unknown $id            
     * @return boolean|unknown
     */
    function get_category($id)
    {
        if (empty($id))
        {
            return FALSE;
        }
        
        $this->db->where('id', $this->db->escape_str($id));
        $this->db->where('deleted !=', DELETED);
        
        $query = $this->db->get('categories');
        
        $result = $query->result_array();
        
        if (count($result) === 1)
        {
            return $result[0];
        }
        
        return FALSE;
    }

    function update_category($category)
    {
        $category['user_modified_id'] = get_user_id();
        $category['date_modified'] = date(DB_DATE_TIME_FORMAT);
        
        // update tour url title
        if (isset($category['name']) && ! empty($category['name']))
        {
        	$category['url_title'] = url_title(convert_unicode($category['name']), '-', true);
        }
        
        $this->db->update('categories', $category, array(
            'id' => $category['id']
        ));
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function delete_category($id)
    {
        $category['deleted'] = DELETED;
        
        $this->db->update('categories', $category, array(
            'id' => $id
        ));
        
        $error_nr = $this->db->_error_number();
        
        return ! $error_nr;
    }

    function is_unique_tour_category_name($name, $id)
    {
        $this->db->where('deleted !=', DELETED);
        
        $this->db->where('name', $name);
        
        if (! empty($id))
        {
            
            $this->db->where('id !=', $id);
        }
        
        $cnt = $this->db->count_all_results('categories');
        
        return $cnt > 0;
    }
    
    function get_all_categories()
    {
        $this->db->where('deleted !=', DELETED);
        
        $this->db->order_by('position');
        
        $query = $this->db->get('categories');
        
        return $query->result_array();
    }
    
    function get_max_position($type = 0)
    {
        if ($type == 0)
        {
            $this->db->select_max('position');
        }
        else
        {
            $this->db->select_min('position');
        }
    
        $query = $this->db->get('categories');
    
        $results = $query->result_array();
        if (! empty($results))
        {
    
            return $results[0]['position'];
        }
    
        return 0;
    }
    
    /**
     * Update tour category
     *
     * @param unknown $tour_category
     * @param unknown $tour_id
     */
    function update_tour_category($categories, $tour_id)
    {
        $this->db->trans_start();
    
        // delete old tour category
        $this->db->where('tour_id', $tour_id);
        $this->db->delete('tour_categories');
    
        // create new tour category
        if (! empty($categories))
        {
            foreach ($categories as $cat)
            {
                
                $tour_category = array(
                    'tour_id' => $tour_id,
                    'category_id' => $cat
                );
                
                $this->db->insert('tour_categories', $tour_category);
            }
        }
    
        $this->db->trans_complete();
    
        $error_nr = $this->db->_error_number();
    
        return ! $error_nr;
    }
    
    function get_tour_category($tour_id) {
        
        $this->db->select('category_id');
        
        $this->db->where('tour_id', $tour_id);
        
        $query = $this->db->get('tour_categories');
        
        return $query->result_array();
    }
}

?>