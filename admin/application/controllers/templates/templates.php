<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Templates extends BP_Controller {
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('account_model');
		$this->load->model('template_model');
		$this->load->language('marketing');
		$this->load->language('newsletter');
		$this->load->library('pagination');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('upload');
		
		$this->load->helper('search');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('photo');
		
		$this->config->load('template_meta');
		$this->config->load('marketing_meta');
		
	}
	
	function index(){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_MARKETING);
		
		$data['site_title'] = lang('list_template_title');
		
		$data = $this->_get_list_data($data);
		
		$data['nav_panel'] = $this->config->item('mk_nav_panel');
		
		$data['template_type'] = $this->config->item('template_type');
		
		$data['side_mnu_index'] = 3;

		_render_view('newsletters/templates/list_templates', $data, 'newsletters/templates/search_templates');
	}
	
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MNU_TEMPLATE, $data);
		
		$search_criteria = $data['search_criteria'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
		
		$per_page = $this->config->item('per_page');
		
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['templates'] = $this->template_model->search_template($search_criteria, $per_page, $offset);
		
		$data['templates'] = $this->_set_type($data['templates']);
		
		$total_rows = $this->template_model->get_numb_template($search_criteria);
		
		$data = set_paging_info($data, $total_rows, URL_TEMPLATE);
	
		return $data;
	}
	
	function _set_type($templates){
		
		$template_type = $this->config->item('template_type');
		
		foreach($templates as $key => $value){
			
			$display_on_val = array();
			
			$display_on_val_k = array();
			
			$display_on = $value['type'];
			
			foreach ($template_type as $k => $v){
				
				if(is_bit_value_contain($display_on, $k)){
					
					$display_on_val[] = $v;
					
					$display_on_val_k[] = $k;
					
				}
			}
			$txt = implode(", ", $display_on_val);
			
			$txt_k = implode(", ", $display_on_val_k);
			
			$value['display_on_txt'] = $txt;
			
			$value['display_on_txt_k'] = $txt_k;
			
			$templates[$key] = $value;
		}
		
		return $templates;
	}
	
	function create(){
		
		$validation_config = $this->config->item('template_create');
        $this->form_validation->set_rules($validation_config);
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        
        $data['nav_panel'] = $this->config->item('mk_nav_panel');
		
		$data['side_mnu_index'] = 3;
		
		$data['site_title'] = lang('create_templates');
        
        if ($this->form_validation->run() == true){
        	
            $type = $this->input->post('type');
            
            $type_arr = array(
            	0	=> $type,
            );
            
            $newsletter = array(
                'name' 		=> trim($this->input->post('name')),
                'type' 		=> calculate_list_value_to_bit($type_arr),
            	'content'	=> $this->input->post('content'),
            );
            
            $save_status = $this->template_model->create_template($newsletter);
            
            if ($save_status){
            	
                $this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
                redirect("/templates/");
            }
            else{
                if (! is_null($save_status)){
                	
                    $data['save_status'] = $save_status;
                }
            }
        }
        
        $data['template_type'] = $this->config->item('template_type');
        
        // render view
        $data = get_library('tinymce', $data);
        
        _render_view('newsletters/templates/create_templates', $data);
	}
	
	function edit(){
		
		$data = $this->_get_templates_data();
		
		$data = $this->_set_type($data);
		
		$data['nav_panel'] = $this->config->item('mk_nav_panel');
		
		$data['side_mnu_index'] = 3;
		
		$data['site_title'] = lang('create_templates');
        
        $validation_config = $this->config->item('template_create');
        $this->form_validation->set_rules($validation_config);
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        
        if ($this->form_validation->run() == true){
            
            $type = $this->input->post('type');
            
            $type_arr = array(
            	0	=> $type,
            );
            
            $template = array(
            	
                'id' 			=> $data['templates']['id'],
                'name' 			=> trim($this->input->post('name')),
                'content' 		=> trim($this->input->post('content')),
                'status' 		=> $this->input->post('status'),
            	'type' 			=> calculate_list_value_to_bit($type_arr),
            );
            
            $save_status = $this->template_model->update_templates($template);
            
            if ($save_status){
            	
                $this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
                
                redirect("templates/");
            }else{
            	
            	if (!is_null($save_status)){
                	
                    $data['save_status'] = $save_status;
                }
            }
        }
        $data['template_type'] = $this->config->item('template_type');
        
        $data['status_config'] = $this->config->item('status_config');
        
        // render view
        $data['site_title'] = lang('edit_templates');
        
        $data = get_library('tinymce', $data);
        
        _render_view('newsletters/templates/edit_templates', $data);
	}
	
	
	public function delete(){
		
        $id = $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $status = $this->template_model->delete_templates($id);
        
        if ($status){
            
            $this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
        }else{
            
            $this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
        }
        redirect('templates');
    }
	
	public function _get_templates_data($data = array()){
		
        $id = (int) $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $templates = $this->template_model->get_templates($id);
        
        $data['templates'] = $templates;
        
        return $data;
    }
    
}