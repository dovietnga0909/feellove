<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->library('bp_auth');
		$this->load->library('form_validation');
		
		$this->load->helper('url');
		
		$this->load->language('auth');
		
		$this->config->load('user_meta');
		
		// Load models
		$this->load->model('auth_model');
	}

	//log the user in
	function index()
	{
		if(logged_in()) {
			redirect('bookings');
		} else {
			$this->data['title'] = "Login";
			
			//validate form input
			$this->form_validation->set_message('required', 'Enter your %s.');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			
			if ($this->form_validation->run() == true)
			{
				//check to see if the user is logging in
				//check for "remember me"
				$remember = (bool) $this->input->post('remember');
			
				if ($this->auth_model->login($this->input->post('username'), $this->input->post('password'), $remember))
				{
					//if the login is successful the redirect them back to the home page
					
					// make sure user change the default password
					if($this->input->post('password') == '123456') {
						$this->session->set_flashdata('message', 'Please change your default password!');
						redirect('change_password');
					} else {
						redirect(site_url().'bookings/');
					}
					
				}
				else
				{
					//if the login was un-successful
					//redirect them back to the login page
					$this->session->set_flashdata('message', lang('login_unsuccessful'));
					redirect(); //use redirects instead of loading views for compatibility with MY_Controller libraries
				}
			}
			else
			{
				//the user is not logging in so display the login page
				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
				$this->load->view('auth/login', $this->data);
			}	
		}
	}

	//log the user out
	function logout()
	{
		//log the user out
		$logout = $this->bp_auth->logout();

		//redirect them to the login page
		redirect(site_url());
	}

	//change password
	function change_password()
	{	
		// set session for menues
		$this->session->unset_userdata('MENU');
		
		$change_pass_config = $this->config->item('change_password');
		
		//print_r($this->input->post('old_password'));exit();
		
		$this->form_validation->set_rules($change_pass_config);
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		if ($this->form_validation->run() == true)
		{
			$identity = $this->session->userdata('username');
			
			$change = $this->auth_model->change_password($identity, $this->input->post('old_password'), $this->input->post('new_password'));
			
			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata(ACTION_MESSAGE, 'password_change_successful');
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata(ACTION_MESSAGE, lang('password_change_unsuccessful'));
				redirect('change_password');
			}
		}
		else
		{
			//print_r('here');exit();
			//display the form
			//set the flash data error message if there is one
			//$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
			$this->load->config('app_config');
			$data['min_password_length'] = $this->config->item('min_password_length');
				
			$data['site_title'] = 'Change Password';
			
			//render view
			_render_view('auth/change_password', $data);
		}
	}

	//forgot password
	function forgot_password()
	{
		$this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data);
		}
		else
		{
			// get identity for that email
            $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
            if(empty($identity)) {
                $this->ion_auth->set_message('forgot_password_email_not_found');
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("auth/forgot_password", 'refresh');
            }
            
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				//if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				//display the form

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
				'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				//render
				$this->_render_page('auth/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						//if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						$this->logout();
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}
}
