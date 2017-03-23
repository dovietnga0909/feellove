<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contracts extends BP_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->model(array('Contract_Model', 'Hotel_Model', 'Tour_Model'));
		
		$this->load->helper(array('hotel', 'search', 'cruise', 'tour', 'contract', 'download'));
		
		$this->load->library(array('upload'));
		
		$this->load->language('contracts');

		$this->config->load('contract_meta');
	}
	
	public function index()
	{		
		$data = $this->_set_common_data();
		
		$search_criteria = array();
		
		// set reference id
		if ( ! empty($data['hotel'])) 
		{
			$search_criteria['hotel_id'] = $data['hotel']['id'];
		}
		elseif ( ! empty($data['tour']))
		{
			$search_criteria['tour_id'] = $data['tour']['id'];
		}
		elseif ( !empty($data['cruise']))
		{
			$search_criteria['cruise_id'] = $data['cruise']['id'];
		}
		
		$data['files'] = $this->Contract_Model->get_files($search_criteria);
	
		// render view
		$data['site_title'] = lang('contracts_title');
	
		_render_view('contracts/list_contracts', $data);
	}
	
	function _set_common_data($data = array()) 
	{
		
		$hotel_id = $this->get_seg_id('hotels');
		
		if(!empty($hotel_id)) 
		{
			$data = _get_hotel_data(array(), $hotel_id);
				
			// set session for menues
			$this->session->set_userdata('MENU', MNU_HOTEL_CONTRACT);
		}
		
		$cruise_id = $this->get_seg_id('cruises');
		
		if(!empty($cruise_id)) 
		{
			$data = _get_cruise_data(array(), $cruise_id);
		
			// set session for menues
			$this->session->set_userdata('MENU', MNU_CRUISE_CONTRACT);
		}
		
		$tour_id = $this->get_seg_id('tours');
		
		if(!empty($tour_id)) 
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
			if($segment == $seg_name) {
				$array = $this->uri->uri_to_assoc(2);
	
				if(isset($array['contracts'])) 
				{
					return $array['contracts'];
				}
				if(isset($array['contract_upload'])) 
				{
					return $array['contract_upload'];
				}
			}
		}
	
		return null;
	}
	
	function force_download() 
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$file = $this->Contract_Model->get_file($id);
		
		if( ! empty($file))
		{
			$system_path = str_replace('system/', '', BASEPATH);
			
			$type = get_contract_type($file);
			
			$file_name    	=   $file['name'];
			$file_content	=  file_get_contents( $system_path . "documents/". $type ."/" . $file_name );
			
			force_download($file_name, $file_content);
		}
	}
	
	function delete() 
	{
		$id = $this->uri->segment(NORMAL_ID_SEGMENT);
		
		$file = $this->Contract_Model->get_file($id);
		
		if( ! empty($file))
		{
			$type = get_contract_type($file);
			
			// unlink file
			delete_contract_file($file['name'], $type);
			
			// delete record in db 
			$status = $this->Contract_Model->delete_contract($id);
			
			if($status){
			
				$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_successful'));
			
			} else {
			
				$this->session->set_flashdata(ACTION_MESSAGE, lang('delete_fail'));
			}
			
			$this->_remap_redirect($file);
		}
	}
	
	function view() {
	    
	    $id = $this->uri->segment(NORMAL_ID_SEGMENT);
	    
	    $file = $this->Contract_Model->get_file($id);
	    
	    if( ! empty($file))
	    {
	        $cat = get_contract_type($file);
	        
	        $system_path = str_replace('system/', '', BASEPATH);
	        $path = $system_path.'documents/'.$cat.'/'.$file['name'];
	        
            if (stripos($file['name'], '.pdf') !== false)
            {
                
                header('Content-type: application/pdf');
                header('Content-Disposition: inline; filename=' . $file['name']);
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($path));
                header('Accept-Ranges: bytes');
                
                @readfile($path);
            }
            elseif (stripos($file['name'], '.docx') !== false)
            {
                echo read_docx($path);
            }
	  
	    }
	    else
	    {
	        echo "Can't open this document.";
	    }

	}
	
	function rename()
    {
        $id = $this->input->post('id');
        $rename = $this->input->post('name');
        
        $file = $this->Contract_Model->get_file($id);
        
        $respond = 'failed';
        
        if (! empty($file))
        {
            $cat = get_contract_type($file);
            
            $system_path = str_replace('system/', '', BASEPATH) . 'documents/' . $cat . '/';
            
            $oldname = $system_path. $file['name'];
            
            $newname = $system_path . $rename;
            
            if (! file_exists($newname))
            {
                rename($oldname, $newname);
                
                $respond = 'done';
            }
            else
            {
                $actual_name = pathinfo($newname, PATHINFO_FILENAME);
                $original_name = $actual_name;
                $extension = pathinfo($newname, PATHINFO_EXTENSION);
                
                /*
                $i = 1;
                while (file_exists($system_path . $rename))
                {
                    $actual_name = (string) $original_name . $i;
                    $rename = $actual_name . "." . $extension;
                    $i ++;
                }
                
                $newname = $system_path . $rename;
                */
                $newname = set_filename($system_path, $rename);
                
                rename($oldname, $newname);
                
                $respond = 'done';
            }
            
            if($respond == 'done') {
                $update_file = array(
                    'id' => $file['id'],
                    'name' => $rename,
                );
                
                $this->Contract_Model->update_file($update_file);
            }
            
            echo $respond;
        }
    }
    
    function update() {
        
        $id = $this->input->post('id');
        $desc = $this->input->post('desc');
        
        $respond = 'failed';
        
        $file = $this->Contract_Model->get_file($id);
        
        if (! empty($file))
        {
            $update_file = array(
                'id' => $file['id'],
                'description' => $desc,
            );
            
            $this->Contract_Model->update_file($update_file);
            
            $respond = 'done';
        }
        
        echo $respond;
    }
	
	// Redirect
	function _remap_redirect($file) {
	    
		if( ! empty($file['hotel_id']))
		{
			redirect("hotels/contracts/".$file['hotel_id']);
		}
		elseif( ! empty($file['cruise_id']))
		{
			redirect("cruises/contracts/".$file['cruise_id']);
		}
		elseif( ! empty($file['tour_id'])) {
			redirect("tours/contracts/".$file['tour_id']);
		}
	}
	
	function _get_tour($data, $id) {
	
	    $tour = $this->Tour_Model->get_tour($id);
	
	    $data['tour'] = $tour;
	
	    return $data;
	}
}
