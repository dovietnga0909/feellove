<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cruises extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Cruise_Model');
		$this->load->model('Partner_Model');
		$this->load->model('Destination_Model');
		
		$this->load->language(array('cruise', 'partner'));
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		
		$this->load->helper(array('search', 'cruise'));
		
		$this->config->load('cruise_meta');
	}

	public function index()
    {
        $this->session->set_userdata('MENU', MNU_CRUISES);
        
        $data['site_title'] = lang('list_cruise_title');
        
        $data = $this->_get_list_data($data);
        
        _render_view('cruises/cp/list_cruises', $data, 'cruises/cp/search_cruise');
    }

    function _get_list_data($data = array())
    {
        $data = build_search_criteria(MODULE_CRUISES, $data);
        
        $search_criteria = $data['search_criteria'];
        
        $offset = (int) $this->uri->segment(PAGING_SEGMENT);
        
        $per_page = $this->config->item('per_page');
        
        // for display correct order on the column # of table list
        $data['offset'] = $offset;
        
        $data['cruises'] = $this->Cruise_Model->search_cruises($search_criteria, $per_page, $offset);
        
        $data['cruise_star'] = $this->config->item('cruise_star');
        
        $data['cruise_type'] = $this->config->item('cruise_type');
        
        $data['status_config'] = $this->config->item('status_config');
        
        $data['cruise_destinations'] = $this->Cruise_Model->get_all_destinations();
        
        // get service type cruise
        $data['partners'] = $this->Partner_Model->get_all_partners(1);
        
        $total_rows = $this->Cruise_Model->get_numb_cruises($search_criteria);
        
        $data = set_paging_info($data, $total_rows, URL_CRUISE);
        
        $data = set_max_min_pos($data, MODULE_CRUISES);
        
        return $data;
    }
        
        // create a new cruise
    public function create()
    {
        $cruise_config = $this->config->item('cruise_rules');
        $cruise_config_addition = $this->config->item('cruise_rules_addition');
        $cruise_config = array_merge($cruise_config, $cruise_config_addition);
        $this->form_validation->set_rules($cruise_config);
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        
        if ($this->form_validation->run() == true)
        {
            
            $cruise_types = $this->input->post('cruise_type');
            
            $cruise = array(
                'name' => trim($this->input->post('name')),
                'url_title' => strtolower(trim($this->input->post('url_title'))),
                'address' => trim($this->input->post('address')),
                'cruise_type' => calculate_list_value_to_bit($cruise_types),
                'star' => trim($this->input->post('star')),
                'partner_id' => trim($this->input->post('partner_id')),
                'description' => trim($this->input->post('description')),
                'destination_id' => DESTINATION_HALONG
            );
            
            $save_status = $this->Cruise_Model->create_cruise($cruise);
            
            if ($save_status)
            {
                $this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
                redirect("cruises");
            }
            else
            {
                if (! is_null($save_status))
                {
                    $data['save_status'] = $save_status;
                }
            }
        }
        
        $data['cruise_star'] = $this->config->item('cruise_star');
        
        $data['cruise_type'] = $this->config->item('cruise_type');
        
        // get service type cruise
        $data['partners'] = $this->Partner_Model->get_all_partners(1);
        
        // render view
        $data['site_title'] = lang('create_cruise_title');
        
        $data = get_library('tinymce', $data);
        
        _render_view('cruises/cp/create_cruise', $data);
    }

    function cruise_name_check($str)
    {
        $id = $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $is_exist = $this->Cruise_Model->is_unique_cruise_name($str, $id);
        
        if ($is_exist)
        {
            $this->form_validation->set_message('cruise_name_check', lang('cruise_name_is_unique'));
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function _get_cruise_data($data = array())
    {
        $id = (int) $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $cruise = $this->Cruise_Model->get_cruise($id);
        
        $data['cruise'] = $cruise;
        
        return $data;
    }

    public function delete()
    {
        $id = $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $status = $this->Cruise_Model->delete_cruise($id);
        
        if ($status)
        {
            
            $this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
        }
        else
        {
            
            $this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
        }
        
        redirect('cruises');
    }

    function re_order()
    {
        if (isset($_GET["id"]) && isset($_GET["act"]))
        {
            $id = $_GET["id"];
            $action = $_GET["act"];
            
            if (is_numeric($id))
            {
                
                $status = bp_re_order($id, $action, MODULE_CRUISES);
                
                if ($status)
                {
                    $this->session->set_flashdata('message', lang('edit_cruise_successful'));
                    redirect("cruises");
                }
            }
            
            if (! is_null($status))
            {
                $data['save_status'] = $status;
            }
        }
    }

    function clear_cache()
    {
        $id = (int) $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $cruise = $this->Cruise_Model->get_cruise($id);
        
        if (! empty($cruise))
        {
            deleteCache($cruise['url_title'] . '-' . $cruise['id'], CACHE_CRUISE_PAGE);
            
            message_alert('', 'Clear cache ' . $cruise['name'] . ' completed!');
        }
    }
}
