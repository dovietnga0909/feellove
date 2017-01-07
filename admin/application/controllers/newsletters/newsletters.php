<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Newsletters extends BP_Controller {
	
	function __construct(){
		
		parent::__construct();
		$this->load->model('Newsletter_Model');
		$this->load->model('account_model');
		$this->load->model('destination_model');
		$this->load->model('Hotel_Model');
		
		$this->load->language('newsletter');
		$this->load->language('marketing');
		
		$this->load->library('pagination');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('upload');
		
		$this->load->helper('newsletter');
		$this->load->helper('search');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('photo');
		
		$this->load->helper('promotion');
		
		$this->load->helper('file');
		$this->load->helper('directory');
		
		$this->config->load('newsletter_meta');
		$this->config->load('marketing_meta');
		
	}
	
	function index(){
		
		$data = $this->_set_common_data();
		
		$data['site_title'] = lang('list_newsletter_title');
		
		$data = $this->_get_list_data($data);
		
		_render_view('newsletters/list_newsletters', $data, 'newsletters/search_newsletters');
	}
	
	function hotel_html(){
		
		$this->load->view('newsletters/templates/hotel_html');
	}
	
	
	function _get_list_data($data = array()){
	
		$data = build_search_criteria(MNU_NEWSLETTER, $data);
		
		$search_criteria = $data['search_criteria'];
	
		$offset = (int)$this->uri->segment(PAGING_SEGMENT);
		
		$per_page = $this->config->item('per_page');
	
		// for display correct order on the column # of table list
		$data['offset'] = $offset;
	
		$data['newsletters'] = $this->Newsletter_Model->search_newsletter($search_criteria, $per_page, $offset);
		
		
		$total_rows = $this->Newsletter_Model->get_numb_newsletter($search_criteria);
		
		$data = set_paging_info($data, $total_rows, URL_NEWSLETTER);
	
		return $data;
	}
	
	function _set_common_data($data = array()){
		
		// set session for menues
		$this->session->set_userdata('MENU', MNU_NEWSLETTER);
		
		$data['nav_panel'] = $this->config->item('mk_nav_panel');
		
		$data['side_mnu_index'] = 2;
		
		$data['templates_type']	= $this->config->item('template_type');
		
		$data['customer_gender'] 	= $this->config->item('customer_gender');
		
		$data['customer_type'] 		= $this->config->item('customer_type');
		
		$data['status']				= $this->config->item('newsletter_status');
		
		return $data;
	}
	
	
	function create($step){
		
	    // initial step when it's empty
	    if (empty($step))
        {
            $this->session->unset_userdata(NEWSLETTER_TEMP);
            
            $step = 1;
        }
	    
	    $action = $this->input->post('action');
	    
	    // redirect next step
	    if($action == 'next'){
	        	
	        $next_status = $this->_next($step);
	        	
	        if($next_status){
	    
	            redirect(site_url('newsletters/create').'/'.($step + 1).'/');
	        }
	    }
	    
	    // save newsletter
	    $pro = $this->session->userdata(NEWSLETTER_TEMP);
	    
	    if($action == 'save'){
	    	
	    	$pro['content'] = $this->input->post('content');
	        	
	        $save_status = $this->Newsletter_Model->create_newsletter($pro);
	        	
	        if($save_status){
	    
	            $this->session->set_flashdata(ACTION_MESSAGE, lang('create_successful'));
	    
	            $this->session->unset_userdata(NEWSLETTER_TEMP);
	    
	            redirect(site_url('newsletters').'/');
	        } else {
	    
	            $data['save_status'] = $save_status;
	        }
	    }
	    
	    // render view
	    if(!empty($pro)){
	         
	        $data['pro'] = $pro;
	    }
	    
	    if($step > 1 && empty($pro)){
	        	
	        //redirect(site_url('newsletters'));
	    }
	    
	    // get hotel destinations
	    if($pro['template_type'] == HOTEL_HTML){
	    	
	        //$data['hotel_destinations'] = $this->Hotel_Model->get_all_destinations();
	    
	        // toanlk: get top only
	        $data['hotel_destinations'] = $this->Newsletter_Model->get_top_hotel_destinations();
	        
	    }elseif($pro['template_type'] == TOUR_HTML){
	    	
	        $data['tour_domestic'] = $this->Newsletter_Model->get_destination_tour(false);
	        
	        $data['tour_outbound'] = $this->Newsletter_Model->get_destination_tour(true);
	        
	        $data['tour_category'] = $this->Newsletter_Model->get_all_categories();
	        
	    }elseif($pro['template_type'] == CRUISE_HTML){
	    	
	        $data['cruises'] = $this->Newsletter_Model->get_cruise_newsletter();
	    }
	    
	    // set common data
	    $data = $this->_set_common_data($data);
	     
	    $data['site_title'] = lang('create_newsletter_title_'.$step);
	    
	    $data['current_step'] = $step;
	     
	    $data['pro_step'] = $this->load->view('newsletters/step_newsletters', $data, TRUE);
	     
	    $data = get_library('tinymce', $data);
		
		$data['content'] = $this->load->view('newsletters/newsletter_'.$step, $data, TRUE);
			
		$this->load->view('_templates/template', $data);
	}

	
	public function edit($id, $step = 0){
		
	    // initial step when it's empty
		if($step == 0){
			
			$this->session->unset_userdata(NEWSLETTER_EDIT_TEMP.$id);
			
			$step = 1;
		}
		
		$data['edit_mode'] = 1;
		
		$action = $this->input->post('action');
		
		if($action == 'next'){
			
			$next_status = $this->_next($step, $id);
			
			if($next_status){
				
				redirect(site_url('newsletters/edit').'/'.$id.'/'.($step + 1).'/');
			}
		}
		
		$pro = $this->_get_newsletter_for_edit($id);
		
		if($action == 'save'){
			
			$pro['content'] = $this->input->post('content');
			
			$save_status = $this->Newsletter_Model->update_newsletters($id, $pro);
			
			if($save_status){
				
				$this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
				
				$this->session->unset_userdata(NEWSLETTER_EDIT_TEMP.$id);
				
				redirect(site_url('newsletters').'/');
				
			} else {
				
				$data['save_status'] = $save_status;
				
			}
		}
		
		// get hotel destinations
		if($pro['template_type'] == HOTEL_HTML){
			
		    //$data['hotel_destinations'] = $this->Hotel_Model->get_all_destinations();
	    
	        // toanlk: get top only
	        $data['hotel_destinations'] = $this->Newsletter_Model->get_top_hotel_destinations();
	        
		}elseif($pro['template_type'] == TOUR_HTML){
	    	
	        $data['tour_domestic'] = $this->Newsletter_Model->get_destination_tour(false);
	        
	        $data['tour_outbound'] = $this->Newsletter_Model->get_destination_tour(true);
	        
	        $data['tour_category'] = $this->Newsletter_Model->get_all_categories();
	        
	    }elseif($pro['template_type'] == CRUISE_HTML){
	    	
	        $data['cruises'] = $this->Newsletter_Model->get_cruise_newsletter();
	    }
		
		$this->session->set_userdata(NEWSLETTER_EDIT_TEMP.$id, $pro);
		
		if($pro !== FALSE){
			
			$data['pro'] = $pro;
		}
		
		// set common data
		$data = $this->_set_common_data($data);
		
		$data['site_title'] = lang('edit_newsletter_title_'.$step);
		
		$data['current_step'] = $step;
		
		$data['pro_step'] = $this->load->view('newsletters/step_newsletters', $data, TRUE);
		
		$data = get_library('tinymce', $data);
		
		$data['content'] = $this->load->view('newsletters/newsletter_'.$step, $data, TRUE);
			
		$this->load->view('_templates/template', $data);
	}
	
	function _get_newsletter_for_edit($id){
		
		// get from the session first
		
		$pro = $this->session->userdata(NEWSLETTER_EDIT_TEMP.$id);
		
		if(empty($pro)){
			
			$pro = array();
			
			$pro = $this->Newsletter_Model->get_newsletters($id);
			
			$pro['promotion_full'] = $this->Newsletter_Model->get_newsletter_promotion($id);
			
			$this->session->set_userdata(NEWSLETTER_EDIT_TEMP.$id, $pro);
		}
		
		return $pro;
	}
	
	public function _next($step, $id = ''){
		
		if($this->_validate($step, $id)){
			
			if($id == ''){ // create promotion
				
				$pro = $this->session->userdata(NEWSLETTER_TEMP);
				
				$pro = empty($pro) ? array() : $pro;
				
				$pro = $this->_get_post_data($step, $pro);
				
				$this->session->set_userdata(NEWSLETTER_TEMP, $pro);
			} else {
				
				$pro = $this->_get_newsletter_for_edit($id);
				
				$pro = $this->_get_post_data($step, $pro);
				
				$this->session->set_userdata(NEWSLETTER_EDIT_TEMP.$id, $pro);
			}
			return TRUE;
		}
		return FALSE;
	}
	
	public function _get_post_data($step, $pro = array()){
		
		if(isset($pro['step']) && $pro['step'] > $step){
			
			// do nothing
		}else{
			
			$pro['step'] = $step + 1;
		}
		
		if($step == 1){ // step 1: newsletter type
		
			$pro['name'] 			= $this->input->post('name');
			
			$pro['display_name'] 	= $this->input->post('display_name');
			
			$pro['template_type'] 	= $this->input->post('template_type');
			
			$pro['customer_gender'] = calculate_list_value_to_bit($this->input->post('customer_gender'));
			
			$pro['customer_type'] 	= calculate_list_value_to_bit($this->input->post('customer_type'));
		}
		
		if($step == 2){
			
			$pro['promotion_selected'] = trim($this->input->post('promotion_selected'),',');
			
			$pro['resource_path']	= $this->config->item('resource_path');
			
			if($pro['template_type'] == HOTEL_HTML){
				
				$pro = $this->get_hotel_data($pro);
			}
			
			if($pro['template_type'] == CRUISE_HTML){
				
				$pro = $this->get_cruise_data($pro);
			}
			
			if($pro['template_type'] == TOUR_HTML){
				
				$pro = $this->get_tour_data($pro);
			}
			
		}
		return $pro;
	}
	
	public function _set_validation_rules($step){
		
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');
		
		$this->form_validation->set_rules($this->config->item('newsletter_create_'.$step));
	}
	
	public function _validate($step, $id=''){
		
		$this->_set_validation_rules($step);
		
		if ($this->form_validation->run() == false) {
			
			return false;
		}
		return true;
	}
	
	function search_hotel(){
		
		$promotion_selected = array();
		
		$id 	= $this->input->post('des_id');
		
		$selected 	= $this->input->post('selected');
		
		// $newsletter_id = $this->input->post('newsletter_id');
		
		if(isset($selected) && $selected !=''){
			
			$promotion_selected = array_unique($selected);
			
		}
		
		if( !empty($id) && is_numeric($id) ) {
			
			$hotels_promotion 			= $this->Newsletter_Model->get_hotel_newsletter($id);
			
			$data['hotels']				= $hotels_promotion;
			
			$view_select_promotion 		= $this->load->view('/newsletters/hotel_promotion', $data, true);
			
			echo $view_select_promotion;
		}
	}
	
	function search_tour(){
		
		$promotion_selected = array();
		
		$id 	= $this->input->post('des_id');
		
		$outbound = $this->input->post('outbound');
		
		$selected 	= $this->input->post('selected');
		
		// $newsletter_id = $this->input->post('newsletter_id');
		
		if(isset($selected) && $selected !=''){
			
			$promotion_selected = array_unique($selected);
			
		}
		
		if( !empty($id) && is_numeric($id) ) {
			
			$tour_promotion 			= $this->Newsletter_Model->get_tour_newsletter($id);
			
			$data['tours']				= $tour_promotion;
			
			$view_select_promotion 		= $this->load->view('/newsletters/tour_promotion', $data, true);
			
			echo $view_select_promotion;
		}
	}
	
	function search_category(){
		
		$promotion_selected = array();
		
		$id 	= $this->input->post('cat_id');
		
		$selected 	= $this->input->post('selected');
		
		// $newsletter_id = $this->input->post('newsletter_id');
		
		if(isset($selected) && $selected !=''){
			
			$promotion_selected = array_unique($selected);
			
		}
		
		if( !empty($id) && is_numeric($id) ) {
			
			$cat_promotion 			= $this->Newsletter_Model->get_category_newsletter($id);
			
			$data['categories']				= $cat_promotion;
			
			$view_select_promotion 		= $this->load->view('/newsletters/category_promotion', $data, true);
			
			echo $view_select_promotion;
		}
	}
	
	
	function get_hotel_data($pro){
		
		// get hotel promotion and price
		
		$promotion_selected = explode(',', $pro['promotion_selected']);
		
		$pro['promotion_full'] = $this->Newsletter_Model->get_promotion_hotel_price($promotion_selected);
		
		$pro['content'] = $this->load->view('newsletters/templates/hotel_html', $pro, true);
		
		return $pro;
	}
	
	function get_tour_data($pro){
		
		// get tour promotion and price
		
		$promotion_selected = explode(',', $pro['promotion_selected']);
		
		$pro['promotion_full'] = $this->Newsletter_Model->get_promotion_tour_price($promotion_selected);
		
		$pro['content'] = $this->load->view('newsletters/templates/tour_html', $pro, true);
		
		return $pro;
	
	}
	
	function get_cruise_data($pro){
		
		// get tour promotion and price
		
		$promotion_selected = explode(',', $pro['promotion_selected']);
		
		$pro['promotion_full'] = $this->Newsletter_Model->get_promotion_cruise_price($promotion_selected);
		
		$pro['content'] = $this->load->view('newsletters/templates/cruise_html', $pro, true);
		
		return $pro;
	
	}
	
	public function _get_newsletters_data($photo = ''){
		
        $id = (int) $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $newsletters = $this->Newsletter_Model->get_newsletters($id, $photo);
        
        $data['newsletters'] = $newsletters;
        
        return $data;
    }
	
	function delete(){
		
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
        
        $status = $this->Newsletter_Model->delete_newsletters($id);
        
        $status_promotion = $this->Newsletter_Model->delete_newsletter_promotion($id);
        
        $status_photo = $this->Newsletter_Model->delete_newsletter_photos($id);
		
		if ($status && $status_promotion && $status_photo){
            
            $this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
        }else{
            
            $this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
        }
        redirect('newsletters');
	}
	
	function photos(){
		
		$data = $this->_get_newsletters_data(true);
		
		$data['nav_panel'] 		= $this->config->item('mk_nav_panel');
		
		$data['resource_path'] 	= $this->config->item('resource_path');
		
		$data['side_mnu_index'] = 2;
        
        $action = $this->input->post('submit_action');
        
        if ($action == ACTION_UPLOAD){
        	
            $data = $this->_upload_photos($data['newsletters'], $data);
        }
        
        if ($action == ACTION_SAVE){
            
            if (! empty($data['newsletters']['photos'])){
                
                foreach ($data['newsletters']['photos'] as $key => $photo){
                    
                    $p['name'] 				= $photo['name'];
                    
                    $p['newsletter_id'] 	= $photo['newsletter_id'];
                    
                    $p['caption'] 			= $this->input->post('caption_'.$key);
                    
                    $p['date_modified'] 	= date(DB_DATE_TIME_FORMAT);
                    
                    $p['user_modified_id'] 	= get_user_id();
                    
                    $save_status 	= $this->Newsletter_Model->update_newsletters_photos($photo['id'], $p);
                }
                
                if ($save_status){
                	
                	$newsletter_id =	(int) $this->uri->segment(NORMAL_ID_SEGMENT);
                	
                	$newsletter_arr	= $this->Newsletter_Model->get_newsletters($newsletter_id, true);
                	
                	if(!empty($newsletter_arr) && !empty($newsletter_arr['content']) && isset($newsletter_arr['photos'])){
                		
                		if(isset($newsletter_arr['photos'][0])){
                			
                			$srs1 = 'width="100%" height="250" src ="'.$data['resource_path'].'images/newsletters/'.$newsletter_arr['photos'][0]['name'].'"';
                			
                			//$html = preg_replace('~(start-photo-1)(.*)(end-photo-1)~', 'start-photo-1 '.$srs1.' end-photo-1', $newsletter_arr['content']);
                			
                			$html = preg_replace('~(id="nguyenson-1")(.*)(/>)~', 'id="nguyenson-1" '.$srs1.' />', $newsletter_arr['content']);
                			
                			$newsletter_arr['content'] = $html;
                		}
                		
                		if(isset($newsletter_arr['photos'][1])){
                			
                			$srs2 = 'width="100%" height="250" src ="'.$data['resource_path'].'/images/newsletters/'.$newsletter_arr['photos'][1]['name'].'"';
                			
                			//$html = preg_replace('~(start-photo-2)(.*)(end-photo-2)~', 'start-photo-2 '.$srs2.' end-photo-2', $newsletter_arr['content']);
                			
                			$html = preg_replace('~(id="nguyenson-2")(.*)(/>)~', 'id="nguyenson-2" '.$srs2.' />', $newsletter_arr['content']);
                		
                			$newsletter_arr['content'] = $html;
                		}
                		
                		$content['content']	= $newsletter_arr['content'];
                		
                		$this->Newsletter_Model->update_newsletter_content($newsletter_id, $content);
                		
                		$newsletter_arr1 = $this->Newsletter_Model->get_newsletters($newsletter_id);
                		
                	}
                    $this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
                    
                    redirect(site_url('newsletters/photos/' . $data['newsletters']['id']) . '/');
                }else{
                	
                    if (! is_null($save_status)){
                    	
                        $data['save_status'] = $save_status;
                    }
                }
            }
        }
        
        // render view
        $data['site_title'] = lang('photos_newsletters_title');
        
        _render_view('newsletters/newsletters_photos', $data);
	}
	
	function _upload_photos($newsletters, $data = array()){
		
        if (empty($newsletters)) return;
        
        $this->upload->initialize(get_photo_config($newsletters['name'], 'newsletters_photo'));
        
        if (! $this->upload->do_multi_upload("photos")){
            
            $data['uploaded_errors'] = $this->upload->display_errors('<p class="text-danger">', '</p>');
            
        }else{
        	
            $upload_data = $this->upload->get_multi_upload_data();
            
            $photos = array();
            
            foreach ($upload_data as $upload){
                
                $photo = array();
                $photo['name']	 		= $upload['file_name'];
                $photo['newsletter_id'] = $newsletters['id'];
                
                $photo['date_created'] 	= date(DB_DATE_TIME_FORMAT);
                $photo['date_modified'] = date(DB_DATE_TIME_FORMAT);
                
                $photo['user_created_id'] 	= get_user_id();
                $photo['user_modified_id'] 	= get_user_id();
                
                $photos[] = $photo;
            }
            
            $this->Newsletter_Model->create_newsletters_photos($photos);
            
            redirect(site_url('newsletters/photos/' . $data['newsletters']['id']) . '/');
        }
        
        return $data;
    }
    
	function delete_photo($newsletters_id, $photo_id){
		
        if (! empty($photo_id)){
            
            $photo = $this->Newsletter_Model->get_photo($photo_id);
            
            $this->Newsletter_Model->delete_photo($photo_id);
            
            delete_file_photo($photo['name'], 'images/newsletters/');
        }
        
        redirect(site_url('newsletters/photos/' . $newsletters_id) . '/');
    }
	
	function review(){
		
		$id = $this->input->post('id');
		
		$data = $this->Newsletter_Model->get_newsletters($id);
		
		$data_render = $this->load->view('newsletters/review', $data, true);
	
		echo $data_render;
	}
	
	/*
	 * 
	 * SEND EMAIL NEWSLETTER
	 * 
	 */
	
    function send_email(){
    	
    	$id 	= $this->input->post('id');
    	
    	//$action = $this->input->post('action');
    	$action	= STATUS_SENDING;
    	
		$data 	= $this->Newsletter_Model->get_newsletters($id);
		
    	$list_email	= $this->_get_email_customer_type($data['customer_type'], $data['customer_gender']);
    	
    	//status update log email 
    	$status_email_log = $this->Newsletter_Model->insert_email_log($id, $list_email);
    	
    	$status_user_log = $this->Newsletter_Model->update_user_send_newsletter($id, $action);
    	
    	if($status_email_log && $status_user_log){
    		
    		$list_gmail_account = $this->config->item('gmail_account');
    		
    		$nr_gmail_account = count($list_gmail_account);
    		
    		$nr_total_email = $this->Newsletter_Model->nr_total_email($id , LOG_INIT, $thread_num =0);
    		
    		$data_nr_total = array(
    			
    			'nr_total_email'		=>	$nr_total_email,
    		);
    		
    		$this->Newsletter_Model->update_user_send_newsletter($id, $action, $data_nr_total);
    		
    		if( $nr_total_email > SEND_EMAIL_LIMITED){
    			
    			$for_limit = SEND_EMAIL_LIMITED;
    		}else{
    			
    			$for_limit = $nr_total_email;
    		}
    		
    		for($i = 0; $i < $for_limit; $i ++){
    			
    			$array_email = $this->Newsletter_Model->get_array_email($id, LOG_INIT, $thread_num =0);
    			
    			$email_array_to = array();
    			
    			foreach($array_email as $email){
    			
    				$email_array_to[] = $email['email'];
    			}
    			
		    	$nr_email_sent 	= $this->Newsletter_Model->number_email_sent();
		    		
		    	(int)$key_account	= round($nr_email_sent / EMAIL_LIMITED_ACCOUNT);
		    	
		    	$status_newsletter	=	$this->Newsletter_Model->status_newsletter($id);
		    		
		    	if(isset($key_account) && ($key_account < $nr_gmail_account) && ($status_newsletter != STATUS_STOP)){
		    			
		    		$gmail_account = $list_gmail_account[$key_account];
		    		
		    		$subject = $data['name'];
		    		
		    		$display_name = $data['display_name'];
		    		
		    		$content = $data['content'];
		    		
		    		$status_sendMail = $this->_sendMail($subject, $content, $email_array_to, $gmail_account, $display_name);
		    		
		    		if($status_sendMail){
		    			
		    			$data_log = array();
		    			
		    			foreach($array_email as $k =>$value){
		    				
		    				$value['status_log']	= LOG_SUCCESS;
		    				$value['thread_num']	= 1;
		    				$value['date_log']		= date(DB_DATE_TIME_FORMAT);
		    				
		    				$data_log[] = $value;
		    			}
		    			
		    		}else{
		    			
		    			$data_log = array();
		    			
		    			foreach($array_email as $k =>$value){
		    				
		    				$value['status_log']	= LOG_FALSE;
		    				$value['thread_num']	= 1;
		    				$value['date_log']		= date(DB_DATE_TIME_FORMAT);
		    				
		    				$data_log[] = $value;
		    			}
		    		}
		    		
		    		$status_update_log	= $this->Newsletter_Model->update_log($data_log);
		    		
		    		$nr_total_email = $this->Newsletter_Model->nr_total_email($id , LOG_INIT, $thread_num = 0);
		    		
		    		$nr_total_email_success = $this->Newsletter_Model->nr_total_email($id , LOG_SUCCESS, $thread_num = 1);
		    		
		    		$data_nr_send_success	= array(
		    			
		    			'nr_send_success'		=>	$nr_total_email_success,
		    		);
		    		
		    		$status_update_newsletter = $this->Newsletter_Model->update_user_send_newsletter($id, STATUS_SENDING, $data_nr_send_success);
		    		
		    		if($nr_total_email == 0 || $nr_total_email == null){
		    			
		    			$status_update_newsletter = $this->Newsletter_Model->update_user_send_newsletter($id, STATUS_SENT);
		    			break;
		    		}
			    }else{
			    	
		    		$status_update_newsletter = $this->Newsletter_Model->update_user_send_newsletter($id, STATUS_STOP);
		    		break;
		    	}
    		}
    		
    	}else{
    		
	    	//update log send init false
    		$status_user_log = $this->Newsletter_Model->update_user_send_newsletter($id, STATUS_STOP);
    	}
    }
    
    /*
	 * 
	 * RESEND EMAIL NEWSLETTER
	 * 
	 */
    
	function resend_email(){
    	
    	$id 	= $this->input->post('id');
    	
    	//$action = $this->input->post('action');
    	$action	= STATUS_SENDING;
    	
		$data 	= $this->Newsletter_Model->get_newsletters($id);
		
    	$status_user_log = $this->Newsletter_Model->update_user_send_newsletter($id, $action);
    	
    	if($status_user_log){
    		
    		$list_gmail_account = $this->config->item('gmail_account');
    		
    		$nr_gmail_account = count($list_gmail_account);
    		
    		/*$nr_send_false = $this->Newsletter_Model->nr_total_email($id , LOG_FALSE, $thread_num = 1);*/
    		$nr_send_false = $this->Newsletter_Model->nr_total_email($id , LOG_FALSE);
    		$data_nr_total = array(
    			
    			'nr_send_false'		=>	$nr_send_false,
    		);
    		
    		$this->Newsletter_Model->update_user_send_newsletter($id, $action, $data_nr_total);
    		
    		$nr_resend	=	$this->Newsletter_Model->nr_total_email($id);
    		
    		if( $nr_resend > RESEND_EMAIL_LIMITED){
    			
    			$for_limit = RESEND_EMAIL_LIMITED;
    		}else{
    			
    			$for_limit = $nr_resend;
    		}
    		
    		for($i = 0; $i < $for_limit; $i ++){
    			
    			$array_email = $this->Newsletter_Model->get_array_email($id);
    			
    			$email_array_to = array();
    			
    			foreach($array_email as $email){
    			
    				$email_array_to[] = $email['email'];
    			}
    			
		    	$nr_email_sent 	= $this->Newsletter_Model->number_email_sent();
		    		
		    	(int)$key_account	= round($nr_email_sent / EMAIL_LIMITED_ACCOUNT);
		    	
		    	$status_newsletter	=	$this->Newsletter_Model->status_newsletter($id);
		    		
		    	if(isset($key_account) && ($key_account < $nr_gmail_account) && ($status_newsletter != STATUS_STOP)){
		    			
		    		$gmail_account = $list_gmail_account[$key_account];
		    		
		    		$subject = $data['name'];
		    		
		    		$display_name = $data['display_name'];
		    		
		    		$content = $data['content'];
		    		
		    		$status_sendMail = $this->_sendMail($subject, $content, $email_array_to, $gmail_account, $display_name);
		    		
		    		if($status_sendMail){
		    			
		    			$data_log = array();
		    			
		    			foreach($array_email as $k =>$value){
		    				
		    				$value['status_log']	= LOG_SUCCESS;
		    				$value['thread_num']	= 1;
		    				$value['date_log']		= date(DB_DATE_TIME_FORMAT);
		    				
		    				$data_log[] = $value;
		    			}
		    			
		    		}else{
		    			
		    			$data_log = array();
		    			
		    			foreach($array_email as $k =>$value){
		    				
		    				$value['status_log']	= LOG_FALSE;
		    				$value['thread_num']	= 1;
		    				$value['date_log']		= date(DB_DATE_TIME_FORMAT);
		    				
		    				$data_log[] = $value;
		    			}
		    		}
		    		
		    		$status_update_log	= $this->Newsletter_Model->update_log($data_log);
		    		
		    		$nr_resend = $this->Newsletter_Model->nr_total_email($id);
		    		
		    		
		    		$nr_total_email_success = $this->Newsletter_Model->nr_total_email($id , LOG_SUCCESS, $thread_num = 1);
		    		
		    		$data_nr_send_success	= array(
		    			
		    			'nr_send_success'		=>	$nr_total_email_success,
		    		);
		    		
		    		$status_update_newsletter = $this->Newsletter_Model->update_user_send_newsletter($id, STATUS_SENDING, $data_nr_send_success);
		    		
		    		if($nr_resend == 0 || $nr_resend == null){
		    			
		    			$status_update_newsletter = $this->Newsletter_Model->update_user_send_newsletter($id, STATUS_SENT);
		    			break;
		    		}
			    }else{
			    	
		    		$status_update_newsletter = $this->Newsletter_Model->update_user_send_newsletter($id, STATUS_STOP);
		    		break;
		    	}
    		}
    		
    	}else{
    		
	    	//update log send init false
    		$status_user_log = $this->Newsletter_Model->update_user_send_newsletter($id, STATUS_STOP);
    	}
    }
    
    
    function newsletter_status(){
    	
    	$id 	= $this->input->post('id');
    	
    	$status_user_log = $this->Newsletter_Model->update_user_send_newsletter($id, STATUS_STOP);
    	
    	echo $status_user_log;
    }
    
    
    /**
     * 
     * Get list email of customer type
     * @param unknown_type $customer_type
     */
    function _get_email_customer_type($customer_type, $customer_gender){
    	
    	if(isset($customer_type) && isset($customer_gender)){
    		
	    	$email = $this->Newsletter_Model->get_email_customer($customer_type, $customer_gender);
	    	
	    	return $email;
    	}
    	return NULL;
    }
    
   	/**
   	 * _sendMail active 
   	 * @param unknown_type $subject
   	 * @param unknown_type $content
   	 * @param unknown_type $mail_to
   	 * @param unknown_type $gmail_account
   	 */
    
	function _sendMail($subject, $content, $mail_to, $gmail_account, $display_name){
		
		$CI =& get_instance();
		
		$CI->load->library('email');
		
		$config = array();
		
		$config['protocol']='smtp';
		
		$config['smtp_host']	= 'ssl://smtp.googlemail.com';
		$config['smtp_port']	= '465';
		$config['smtp_timeout']	= '30';
		
		$config['smtp_user']	= $gmail_account['gmail'];
		$config['smtp_pass']	= $gmail_account['pass'];
		
		$config['charset']='utf-8';
		$config['newline']="\r\n";
		$config['mailtype'] = 'html';
		
		$CI->email->initialize($config);
		
		$CI->email->from($gmail_account['gmail'], $display_name);
		
		$CI->email->to($mail_to);
		
		$CI->email->subject($subject);
		
		$CI->email->message($content);
		
		if (!$CI->email->send()){
			
			//log_message('error', 'Login - sendMail(): cannot send email!');
			return false;
		}else{
			
			return true;
		}
		
	}
	
}