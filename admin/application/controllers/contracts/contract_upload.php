<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract_Upload extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model(array('Contract_Model', 'Hotel_Model', 'Tour_Model'));
		
		$this->load->helper(array('hotel', 'search', 'cruise', 'tour', 'contract', 'download'));
		
		$this->load->library(array('upload', 'form_validation'));
		
		$this->load->language('contracts');

		$this->config->load('contract_meta');
	}
	
	public function index()
    {
        $data = $this->_set_common_data();
        
        if (isset($_POST["action"]) && $_POST["action"] == ACTION_UPLOAD)
        {
            // upload photos
            $data = $this->_upload_files($data);
        }
        
        // render view
        $data['site_title'] = lang('contracts_title');
        
        _render_view('contracts/contract_upload', $data);
    }
    
    /**
     * Upload Contract Files
     * 
     * 
     */
    function _upload_files($data)
    {

        if (! check_file_upload_limit())
        {
            
            $data['error'][] = lang('error_upload_file_limit');
        }
        else
        {
            $contract_config = get_contract_config('upload_contract');
            
            if (! empty($data['tour']))
            {
                $contract_config['upload_path'] = str_replace('hotels', 'tours', $contract_config['upload_path']);
            }
            elseif (! empty($data['cruise']))
            {
                $contract_config['upload_path'] = str_replace('hotels', 'cruises', $contract_config['upload_path']);
            }
            
            $this->upload->initialize($contract_config);
            
            if (! $this->upload->do_multi_upload("contracts"))
            {
                $error = array(
                    'error' => $this->upload->display_errors()
                );
                
                $data['error'] = $error;
            }
            else
            {
                $upload_data = $this->upload->get_multi_upload_data();
                
                $upload_files = array();
                
                foreach ($upload_data as $upload)
                {
                    $file = array();
                    
                    // set reference id
                    if (! empty($data['hotel']))
                    {
                        $file['hotel_id'] = $data['hotel']['id'];
                    }
                    elseif (! empty($data['tour']))
                    {
                        $file['tour_id'] = $data['tour']['id'];
                    }
                    elseif (! empty($data['cruise']))
                    {
                        $file['cruise_id'] = $data['cruise']['id'];
                    }
                    
                    $file['name'] = $upload['file_name'];
                    $file['size'] = $upload['file_size'];
                    // $file['kind'] = $upload['file_ext'];
                    
                    $upload_files[] = $file;
                }
                
                $uploaded_contracts = $this->Contract_Model->upload_files($upload_files);
                
                // store uploaded photos in session
                $this->session->set_userdata('uploaded_contracts', $uploaded_contracts);
                
                $this->_remap_redirect($data);
            }
        }
        
        return $data;
    }

    function uploaded()
    {
        
        // get common data
        $data = $this->_set_common_data();
        
        // get uploaded contracts
        $uploaded_contracts = $this->session->userdata('uploaded_contracts');
        
        $data['redirect_url'] = $this->_remap_redirect($data, 'contracts', true);
        
        if (empty($uploaded_contracts))
        {
            redirect($data['redirect_url']);
        }
        
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        foreach ($uploaded_contracts as $k => $contract)
        {
            $this->form_validation->set_rules('description_' . $k, 'lang:contract_field_description', 'trim|max_length[500]');
        }
        
        if ($this->form_validation->run() == true)
        {
            
            foreach ($uploaded_contracts as $k => $contract)
            {
                
                $contract['description'] = $this->input->post('description_' . $k);
                
                $uploaded_contracts[$k] = $contract;
            }
            
            $save_status = $this->Contract_Model->update_file($uploaded_contracts, true);
            
            if ($save_status)
            {
                $this->session->set_flashdata(ACTION_MESSAGE, lang('update_successful'));
                
                // clear session
                $this->session->unset_userdata('uploaded_contracts');
                
                $this->_remap_redirect($data, 'contracts');
            }
            else
            {
                if (! is_null($save_status))
                {
                    $data['save_status'] = $save_status;
                }
            }
        }
        
        $data['uploaded_contracts'] = $uploaded_contracts;
        
        // render view
        $data['site_title'] = lang('contracts_title');
        
        _render_view('contracts/contract_uploaded', $data);
    }

    function _set_common_data($data = array())
    {
        $hotel_id = $this->get_seg_id('hotels');
        
        if (! empty($hotel_id))
        {
            $data = _get_hotel_data(array(), $hotel_id);
            
            // set session for menues
            $this->session->set_userdata('MENU', MNU_HOTEL_CONTRACT);
        }
        
        $cruise_id = $this->get_seg_id('cruises');
        
        if (! empty($cruise_id))
        {
            $data = _get_cruise_data(array(), $cruise_id);
            
            // set session for menues
            $this->session->set_userdata('MENU', MNU_CRUISE_CONTRACT);
        }
        
        $tour_id = $this->get_seg_id('tours');
        
        if (! empty($tour_id))
        {
            $data = $this->_get_tour(array(), $tour_id);
            
            // set session for menues
            $this->session->set_userdata('MENU', MNU_TOUR_CONTRACT);
        }
        
        if (empty($hotel_id) && empty($cruise_id) && empty($tour_id))
        {
            _show_error_page(lang('no_review_found'));
            exit();
        }
        
        return $data;
    }

    function get_seg_id($seg_name)
    {
        $segs = $this->uri->segment_array();
        
        foreach ($segs as $segment)
        {
            if ($segment == $seg_name)
            {
                $array = $this->uri->uri_to_assoc(2);
                
                if (isset($array['contracts']))
                {
                    return $array['contracts'];
                }
                elseif (isset($array['contract_upload']))
                {
                    return $array['contract_upload'];
                }
                elseif (isset($array['contract_uploaded']))
                {
                    return $array['contract_uploaded'];
                }
            }
        }
        
        return null;
    }
    
    // Redirect
    function _remap_redirect($data, $to = 'contract_uploaded', $link_only = false)
    {
        $link = '';
        if (! empty($data['hotel']))
        {
            $link = "hotels/" . $to . "/" . $data['hotel']['id'];
        }
        elseif (! empty($data['cruise']))
        {
            $link = "cruises/" . $to . "/" . $data['cruise']['id'];
        }
        elseif (! empty($data['tour']))
        {
            $link = "tours/" . $to . "/" . $data['tour']['id'];
        }
        
        if($link_only) {
            return $link;
        }
        
        redirect($link);
    }

    function _get_tour($data, $id)
    {
        $tour = $this->Tour_Model->get_tour($id);
        
        $data['tour'] = $tour;
        
        return $data;
    }
}
