<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*  News
*
*  News from Bestprice, out source and marketing content
*
*  @author toanlk
*  @since  Sep 12, 2014
*/
class News extends BP_Controller {
	
	function __construct()
    {
        parent::__construct();
        
        $this->load->model('News_Model');
        
        $this->load->language('news');
        
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('image_lib');
        
        $this->load->helper('search');
        $this->load->helper('photo');
        
        $this->config->load('news_meta');
    }

    public function index()
    {
        $this->session->set_userdata('MENU', MNU_NEWS);
        
        $data['site_title'] = lang('list_news_title');
        
        $data = $this->_get_list_data($data);
        
        _render_view('/news/list_news', $data, '/news/search_news');
    }

    function _get_list_data($data = array())
    {
        $data['categories'] = $this->config->item('news_categories');
        
        $data = build_search_criteria(MODULE_NEWS, $data);
        
        $search_criteria = $data['search_criteria'];
        
        $offset = (int) $this->uri->segment(PAGING_SEGMENT);
        
        $per_page = $this->config->item('per_page');
        
        // for display correct order on the column # of table list
        $data['offset'] = $offset;
        
        $data['lst_news'] = $this->News_Model->search_news($search_criteria, $per_page, $offset);
        
        $total_rows = $this->News_Model->get_numb_news($search_criteria);
        
        $data = set_paging_info($data, $total_rows, 'news');
        
        $data = set_max_min_pos($data, MODULE_NEWS);
        
        $data['news_types'] = $this->config->item('news_types');
        
        return $data;
    }
    
    // create a news
    public function create()
    {
        $validation_config = $this->config->item('create_news');
        $this->form_validation->set_rules($validation_config);
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        
        if ($this->form_validation->run() == true)
        {
            $category = $this->input->post('category');
            
            $news = array(
                'name' => trim($this->input->post('name')),
                'link' => trim($this->input->post('link')),
                'source' => trim($this->input->post('source')),
                'short_description' => trim($this->input->post('short_description')),
                'type' => $this->input->post('type'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'content' => $this->input->post('content'),
                'category' => calculate_list_value_to_bit($category)
            );
            
            $save_status = $this->News_Model->create_news($news);
            
            if ($save_status)
            {
                $this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
                redirect("news/");
            }
            else
            {
                if (! is_null($save_status))
                {
                    $data['save_status'] = $save_status;
                }
            }
        }
        
        $data['news_types'] = $this->config->item('news_types');
        
        $data['categories'] = $this->config->item('news_categories');
        
        // render view
        $data['site_title'] = lang('create_news_title');
        
        $data = get_library('datepicker', $data);
        $data = get_library('tinymce', $data);
        
        _render_view('news/create_news', $data);
    }

    public function _get_news_data($data = array())
    {
        $id = (int) $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $news = $this->News_Model->get_news($id, true);
        
        $data['news'] = $news;
        
        return $data;
    }
    
    // edit the news
    public function edit()
    {
        $data = $this->_get_news_data();
        $data = $this->_load_nav_menu($data, $data['news']['id']);
        
        $validation_config = $this->config->item('create_news');
        $this->form_validation->set_rules($validation_config);
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        
        if ($this->form_validation->run() == true)
        {
            
            $category = $this->input->post('category');
            
            $news = array(
                'id' => $data['news']['id'],
                'name' => trim($this->input->post('name')),
                'link' => trim($this->input->post('link')),
                'source' => trim($this->input->post('source')),
                'short_description' => trim($this->input->post('short_description')),
                'status' => $this->input->post('status'),
                'type' => $this->input->post('type'),
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'content' => $this->input->post('content'),
                'category' => calculate_list_value_to_bit($category)
            );
            
            $save_status = $this->News_Model->update_news($news);
            
            if ($save_status)
            {
                $this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
                redirect("news/");
            }
            else
            {
                if (! is_null($save_status))
                {
                    $data['save_status'] = $save_status;
                }
            }
        }
        
        $data['status_config'] = $this->config->item('status_config');
        
        $data['news_types'] = $this->config->item('news_types');
        
        $data['categories'] = $this->config->item('news_categories');
        
        // render view
        $data['site_title'] = lang('edit_news_title');
        
        $data = get_library('datepicker', $data);
        $data = get_library('tinymce', $data);
        
        _render_view('news/edit_news', $data);
    }

    function _load_nav_menu($data, $id, $mnu_index = 0)
    {
        $nav_panel = $this->config->item('nav_panel');
        
        foreach ($nav_panel as $key => $value)
        {
            
            $value['link'] .= $id . '/';
            
            $nav_panel[$key] = $value;
        }
        
        $data['side_mnu_index'] = $mnu_index;
        
        $data['nav_panel'] = $nav_panel;
        
        return $data;
    }

    public function delete()
    {
        $id = $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $status = $this->News_Model->delete_news($id);
        
        if ($status)
        {
            
            $this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
        }
        else
        {
            
            $this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
        }
        
        redirect('news');
    }

    function news_name_check($str)
    {
        $id = $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $is_exist = $this->News_Model->is_unique_field_value($str, $id, 'name');
        
        if ($is_exist)
        {
            $this->form_validation->set_message('news_name_check', lang('news_name_exist'));
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function date_check($str)
    {
        $ret = FALSE;
        
        if (substr_count($str, '-') == 2)
        {
            list ($d, $m, $y) = explode('-', $str);
            $ret = checkdate($m, $d, sprintf('%04u', $y));
        }
        
        // check format
        if ($ret === FALSE)
        {
            $this->form_validation->set_message('date_check', lang('news_date_valid_format'));
        }
        else
        {
            // check endate > start date
            
            $start_date = $this->input->post('start_date');
            
            $end_date = $this->input->post('end_date');
            
            $start_date = strtotime($start_date);
            
            $end_date = strtotime($end_date);
            
            if ($start_date > $end_date)
            {
                
                $ret = FALSE;
                
                $this->form_validation->set_message('date_check', lang('news_end_date_valid_value'));
            }
        }
        
        return $ret;
    }

    function re_order()
    {
        if (isset($_GET["id"]) && isset($_GET["act"]))
        {
            $id = $_GET["id"];
            $action = $_GET["act"];
            
            if (is_numeric($id))
            {
                
                $status = bp_re_order($id, $action, MODULE_NEWS);
                
                if ($status)
                {
                    $this->session->set_flashdata('message', lang('update_successful'));
                    redirect("news");
                }
            }
            
            if (! is_null($status))
            {
                $data['save_status'] = $status;
            }
        }
    }

    function photos()
    {
        $data = $this->_get_news_data();
        $data = $this->_load_nav_menu($data, $data['news']['id'], 1);
        
        $action = $this->input->post('submit_action');
        
        if ($action == ACTION_UPLOAD)
        {
            $data = $this->_upload_photos($data['news'], $data);
        }
        
        if ($action == ACTION_SAVE)
        {
            
            if (! empty($data['news']['photos']))
            {
                
                foreach ($data['news']['photos'] as $photo)
                {
                    
                    $status = $this->input->post('status_' . $photo['id']);
                    
                    $is_main_photo = $this->input->post('is_main_photo_' . $photo['id']);
                    
                    $p['status'] = $status;
                    $p['is_main_photo'] = $is_main_photo;
                    $p['date_modified'] = date(DB_DATE_TIME_FORMAT);
                    $p['user_modified_id'] = get_user_id();
                    
                    $save_status = $this->News_Model->update_news_photos($photo['id'], $p, $data['news']['id']);
                }
                
                if ($save_status)
                {
                    $this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
                    redirect(site_url('news/photos/' . $data['news']['id']) . '/');
                }
                else
                {
                    if (! is_null($save_status))
                    {
                        $data['save_status'] = $save_status;
                    }
                }
            }
        }
        
        // render view
        $data['site_title'] = lang('photos_news_title');
        
        _render_view('news/news_photos', $data);
    }

    function _upload_photos($news, $data = array())
    {
        if (empty($news))
            return;
        
        $this->upload->initialize(get_photo_config($news['name'], 'news_photo'));
        
        if (! $this->upload->do_multi_upload("photos"))
        {
            
            $data['uploaded_errors'] = $this->upload->display_errors('<p class="text-danger">', '</p>');
        }
        else
        {
            $upload_data = $this->upload->get_multi_upload_data();
            
            $photos = array();
            $photo_folder = $this->config->item('news_photo_size');
            
            foreach ($upload_data as $upload)
            {
                
                $photo = array();
                $photo['news_id'] = $news['id'];
                
                $photo['width'] = $upload['image_width'];
                $photo['height'] = $upload['image_height'];
                
                $photo['status'] = STATUS_ACTIVE;
                $photo['name'] = $upload['file_name'];
                
                $photo['date_created'] = date(DB_DATE_TIME_FORMAT);
                $photo['date_modified'] = date(DB_DATE_TIME_FORMAT);
                
                $photo['user_created_id'] = get_user_id();
                $photo['user_modified_id'] = get_user_id();
                
                $photos[] = $photo;
                
                resize_and_crop($upload, $photo_folder, 90);
            }
            
            $this->News_Model->create_news_photos($photos);
            
            redirect(site_url('news/photos/' . $data['news']['id']) . '/');
        }
        
        return $data;
    }

    function delete_photo($news_id, $photo_id)
    {
        if (! empty($photo_id))
        {
            
            $photo = $this->News_Model->get_photo($photo_id);
            
            $this->News_Model->delete_photo($photo_id);
            
            delete_file_photo($photo['name'], 'images/news/');
        }
        
        redirect(site_url('news/photos/' . $news_id) . '/');
    }
}
