<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*  News
*
*  Display news from Bestprice, out source and marketing content
*
*  @author toanlk
*  @since  Sep 12, 2014
*/
class News extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->language('flight');
		$this->load->language('news');
		
		$this->load->helper(array('form', 'cookie', 'flight', 'hotel', 'text'));
		
		$this->load->model('News_Model');
		$this->load->model('Flight_Model');
		$this->load->model('Hotel_Model');
		
		$this->load->config('news_meta');
	}

	public function index()
    {
        // get data
        $data = array();

        $this->session->unset_userdata('MENU');
        
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $canonical_link = get_url(NEWS_HOME_PAGE);
        
        $obj['canonical'] = '<link rel="canonical" href="' . $canonical_link . '" />'; 
        
        $data['meta'] = get_header_meta(NEWS_HOME_PAGE, $obj);
        
        $data['news_header'] = lang('news_title');
        
        $data = $this->_get_search_criteria($data);
        
        $data['categories'] = $this->config->item('news_categories');
        
        $data['first_news'] = $this->News_Model->get_first_page_news($is_mobile);
        
        if ($is_mobile)
        {
            $news_cat = array();
            
            foreach ($data['categories'] as $key => $value) {
                $s_category = array(
                    'id' => $key,
                    'name' => lang($value),
                    'url_title' => url_title(convert_unicode(lang($value)), '-', true)
                );
                
                $search_criteria['category'] = $s_category['id'];
                
                $search_criteria['first_news'] = $data['first_news'][0]['id'];
                
                $s_category['news'] = $this->News_Model->search_news($search_criteria, 5);
                
                $news_cat[] = $s_category;
            }
            
            $data['news_cat'] = $news_cat;
        }
        else
        {
            $first_news = array();
            foreach ($data['first_news'] as $news)
            {
                $first_news[] = $news['id'];
            }
            
            $data['search_criteria']['first_news'] = $first_news;
            
            if (! empty($data['search_criteria']['page']))
            {
                $data['first_news'] = null;
            }
            
            $data['count_results'] = $this->News_Model->count_search_news($data['search_criteria']);
            
            $data['search_news'] = $this->News_Model->search_news($data['search_criteria']);
            
            $data = $this->_set_paging_info($data);
            
            // render view
            $data['side_menu'] = $this->load->view($mobile_view . 'news/side_menu', $data, TRUE);
            
            $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
        }
        
        $data = get_in_page_theme(NEWS_HOME_PAGE, $data, $is_mobile);
        
        _render_view($mobile_view . 'news/home', $data, $is_mobile);
    }

    /**
      *  _get_search_criteria
      *
      *  @author toanlk
      *  @since  Sep 17, 2014
      */
    function _get_search_criteria($data)
    {
        $search_criteria = array();
        
        $page = $this->input->get_post('page', true);
        
        if ($page != '')
            $search_criteria['page'] = $page;
        
        $data['search_criteria'] = $search_criteria;
        
        return $data;
    }

    /**
     * _set_paging_info()
     *
     * set paging information
     *
     * @author toanlk
     * @since Sep 12, 2014
     */
    public function _set_paging_info($data)
    {
        $this->load->library('pagination');
        
        $search_criteria = $data['search_criteria'];
        
        $offset = ! empty($search_criteria['page']) ? $search_criteria['page'] : 0;
        
        $paging_config = $this->config->item('paging_config');
        
        $total_rows = $data['count_results'];
        
        // paging url
        $url = NEWS_HOME_PAGE;
        
        if(!empty($data['selected_category']))
        {
            $obj = array(
                'id' => $data['selected_category']['id'],
                'url_title' => url_title(convert_unicode($data['selected_category']['name']), '-', true)
            );
            $url = str_replace(base_url(), '', get_url(NEWS_CATEGORY_PAGE, $obj));
        }
        
        $paging_config = get_paging_config($total_rows, $url, 1);
        
        // initialize pagination
        $this->pagination->initialize($paging_config);
        
        $paging_info['paging_text'] = get_paging_text($total_rows, $offset);
        
        $paging_info['paging_links'] = $this->pagination->create_links();
        
        $data['paging_info'] = $paging_info;
        
        return $data;
    }
    
    /**
      *  get news by category
      *
      *  @author toanlk
      *  @since  Sep 17, 2014
      */
    function category($id)
    {
        // get data
        $data = array();
        
        $data['categories'] = $this->config->item('news_categories');
        
        $selected_category = null;
        
        foreach ($data['categories'] as $key => $value) {
            if($key == $id)
            {
                $selected_category = array(
                    'id' => $id,
                    'name' => lang($value),
                );
            }
        }
        
        if (empty($selected_category))
        {
            exit();
        }
        
        $data['selected_category'] = $selected_category;
        
        $this->session->unset_userdata('MENU');
        
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        $data['meta'] = get_header_meta(NEWS_CATEGORY_PAGE, $selected_category);
        
        $data['news_header'] = $selected_category['name'];
        
        $data = $this->_get_search_criteria($data);
        
        $search_criteria = $data['search_criteria'];
        
        $search_criteria['category'] = $id;
        
        $data['count_results'] = $this->News_Model->count_search_news($search_criteria);
        
        $data['search_news'] = $this->News_Model->search_news($search_criteria);
        
        $data = $this->_set_paging_info($data);
        
        $data = get_in_page_theme(NEWS_HOME_PAGE, $data, $is_mobile);
        
        if ($is_mobile)
        {
            // do nothing
            _render_view($mobile_view . 'news/category', $data, $is_mobile);
        }
        else
        {
            $data['side_menu'] = $this->load->view($mobile_view . 'news/side_menu', $data, TRUE);
            
            // render view
            $data['bpv_register'] = $this->load->view($mobile_view . 'common/bpv_register', $data, TRUE);
        
            _render_view($mobile_view . 'news/home', $data, $is_mobile);
        }

    }

    /**
      *  get news details content
      *
      *  @author toanlk
      *  @since  Sep 17, 2014
      */
    function details($news_id)
    {
        $is_mobile = is_mobile();
        
        $mobile_view = $is_mobile ? 'mobile/' : '';
        
        // get data
        $data = array();
        
        // get news
        $data['news'] = $this->News_Model->get_news_details($news_id);
        
        $this->session->unset_userdata('MENU');
               
        $data['exception_code'] = 1;
 
        if(empty($data['news']))
        {
            exit();
        }
        
        $data['meta'] = get_header_meta(NEWS_DETAILS_PAGE, $data['news']);
        
        $data['categories'] = $this->config->item('news_categories');
        
        $selected_category = null;
        
        foreach ($data['categories'] as $key => $value) {
            if(is_bit_value_contain($data['news']['category'], $key))
            {
                $selected_category = array(
                    'id' => $key,
                    'name' => lang($value),
                );
                break;
            }
        }
        
        $data['selected_category'] = $selected_category;
        
        $data['related_news'] = $this->News_Model->get_related_news($data['news']['id']);
        
        // render view
        $data['bpv_register'] = $this->load->view('common/bpv_register', $data, TRUE);
        
        // flag ads page
        if ($data['news']['id'] == 13)
        {   
            _render_view($mobile_view . 'news/marketing_golden_week', $data, $is_mobile);
        }
        else if ($data['news']['id'] == 15)
        {
            _render_view($mobile_view . 'news/more_people_more_fun', $data, $is_mobile);
        }
        else if ($data['news']['id'] == 16)
        {
            _render_view($mobile_view . 'news/wedding_session', $data, $is_mobile);
        }
        else if ($data['news']['id'] == 28)
        {
            $data['ads_grateful_week_page'] = true;
            _render_view($mobile_view . 'news/grateful_week', $data, $is_mobile);
        }
        else if ($data['news']['id'] == 37)
        {
            $data['ads_grateful_week_page'] = true;
            _render_view($mobile_view . 'news/go_out_together', $data, $is_mobile);
        }
        else if ($data['news']['id'] == 39)
        {
            _render_view($mobile_view . 'news/cooperate_nam_a_bank', $data, $is_mobile);
        }
        else if ($data['news']['id'] == 56)
        {
            _render_view($mobile_view . 'news/cooperate_fpt_microsoft_store', $data, $is_mobile);
        }
        else if ($data['news']['id'] == 60)
        {
            _render_view($mobile_view . 'news/company_5th_birthday', $data, $is_mobile);
        }
        else if ($data['news']['id'] == 66)
        {
            _render_view($mobile_view . 'news/experience_halong_bay', $data, $is_mobile);
        }
        else
        {
            $data = get_in_page_theme(NEWS_HOME_PAGE, $data, $is_mobile);
            
            $data['side_menu'] = $this->load->view('news/side_menu', $data, TRUE);
            
            _render_view($mobile_view . 'news/details', $data, $is_mobile);
        }
    }
    
    // redirect old links to new page - by toanlk 15/09/2014
    function get_news($news_id)
    {
        $news = $this->News_Model->get_news_details($news_id);
        
        if (! empty($news))
        {
            redirect(NEWS_DETAILS_PAGE . $news['url_title'] . '-' . $news_id . URL_SUFFIX, 'location', 301);
        }
        
        exit();
    }
}