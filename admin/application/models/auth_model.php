<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name: Auth Model
* 
* Last Change: 10.01.14
*
* Changelog: 
* * 16-01-14 - Additional csrf added
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Auth_Model extends CI_Model
{
	/**
	 * error message (uses lang file)
	 *
	 * @var string
	 **/
	protected $errors;
	
	/**
	 * set_error
	 *
	 * Set an error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_error($error)
	{
		$this->errors[] = $error;
	
		return $error;
	}
	
	/**
	 * login
	 *
	 * @return bool
	 **/
	public function login($username, $password, $remember=FALSE)
	{
		if (empty($username) || empty($password))
		{
			$this->set_error('login_unsuccessful');
			return FALSE;
		}
	
		$query = $this->db->select('username, id, password, partner_id, last_login')
		->where('username', $this->db->escape_str($username))
		->where('status', STATUS_ACTIVE)
		->where('deleted !=', STATUS_ACTIVE)
		->limit(1)
		->get('users');
	
		if($this->is_time_locked_out($username))
		{
			$this->set_error('login_timeout');
	
			return FALSE;
		}
	
		if ($query->num_rows() === 1)
		{
			$user = $query->row();
			
			$correct_password = (md5($password) == $user->password) ? TRUE : FALSE;
			
			if ($correct_password === TRUE)
			{
				$this->set_session($user);
	
				$this->update_last_login($user->id);
	
				$this->clear_login_attempts($username);
	
				if ($remember && $this->config->item('remember_users', 'app_config'))
				{
					$this->remember_user($user->id);
				}

				return TRUE;
			}
		}

		$this->set_error('login_unsuccessful');
	
		return FALSE;
	}
	
	/**
	 * update_last_login
	 *
	 * @return bool
	 **/
	public function update_last_login($id)
	{	
		$this->load->helper('date');
	
		$this->db->update('users', array('last_login' => time()), array('id' => $id));
	
		return $this->db->affected_rows() == 1;
	}
	
	/**
	 * remember_user
	 *
	 * @return bool
	 **/
	public function remember_user($id)
	{
		if (!$id)
		{
			return FALSE;
		}
	
		$user = $this->user($id)->row();
	
		$salt = sha1($user->password);
	
		$this->db->update('users', array('remember_code' => $salt), array('id' => $id));
	
		if ($this->db->affected_rows() > -1)
		{
			// if the user_expire is set to zero we'll set the expiration two years from now.
			if($this->config->item('user_expire', 'app_config') === 0)
			{
				$expire = (60*60*24*365*2);
			}
			// otherwise use what is set
			else
			{
				$expire = $this->config->item('user_expire', 'app_config');
			}
	
			set_cookie(array(
					'name'   => 'username',
					'value'  => $user->username,
					'expire' => $expire
					));
	
			set_cookie(array(
					'name'   => 'remember_code',
					'value'  => $salt,
					'expire' => $expire
			));
	
			return TRUE;
		}
	
		return FALSE;
	}
	
	/**
	 * set_session
	 *
	 * @return bool
	 **/
	public function set_session($user)
	{
		$session_data = array(
				'user_id'			=> $user->id,
				'username'          => $user->username,
				'partner_id'        => $user->partner_id,
		);
	
		$this->session->set_userdata($session_data);
	
		return TRUE;
	}
	
	/**
	 * Get a boolean to determine if an account should be locked out due to
	 * exceeded login attempts within a given period
	 *
	 * @return	boolean
	 */
	public function is_time_locked_out($identity) {
	
		return $this->is_max_login_attempts_exceeded($identity) && $this->get_last_attempt_time($identity) > time() - $this->config->item('lockout_time', 'app_config');
	}
	
	/**
	 * is_max_login_attempts_exceeded
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity
	 * @return boolean
	 **/
	public function is_max_login_attempts_exceeded($identity) {
		if ($this->config->item('track_login_attempts', 'app_config')) {
			$max_attempts = $this->config->item('maximum_login_attempts', 'app_config');
			if ($max_attempts > 0) {
				$attempts = $this->get_attempts_num($identity);
				return $attempts >= $max_attempts;
			}
		}
		return FALSE;
	}
	
	/**
	 * Get number of attempts to login occured from given IP-address or identity
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param	string $identity
	 * @return	int
	 */
	function get_attempts_num($identity)
	{
		if ($this->config->item('track_login_attempts', 'app_config')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());
			$this->db->select('1', FALSE);
			if ($this->config->item('track_login_ip_address', 'app_config')) $this->db->where('ip_address', $ip_address);
			else if (strlen($identity) > 0) $this->db->or_where('login', $identity);
			$qres = $this->db->get('login_attempts');
			return $qres->num_rows();
		}
		return 0;
	}
	
	/**
	 * Get the time of the last time a login attempt occured from given IP-address or identity
	 *
	 * @param	string $identity
	 * @return	int
	 */
	public function get_last_attempt_time($identity) {
		if ($this->config->item('track_login_attempts', 'app_config')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());
	
			$this->db->select_max('time');
			if ($this->config->item('track_login_ip_address', 'app_config')) $this->db->where('ip_address', $ip_address);
			else if (strlen($identity) > 0) $this->db->or_where('login', $identity);
			$qres = $this->db->get('login_attempts', 1);
	
			if($qres->num_rows() > 0) {
				return $qres->row()->time;
			}
		}
	
		return 0;
	}
	
	/**
	 * increase_login_attempts
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity
	 **/
	public function increase_login_attempts($identity) {
		if ($this->config->item('track_login_attempts', 'app_config')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());
			return $this->db->insert('login_attempts', array('ip_address' => $ip_address, 'login' => $identity, 'time' => time()));
		}
		return FALSE;
	}
	
	/**
	 * clear_login_attempts
	 * Based on code from Tank Auth, by Ilya Konyukhov (https://github.com/ilkon/Tank-Auth)
	 *
	 * @param string $identity
	 **/
	public function clear_login_attempts($identity, $expire_period = 86400) {
		if ($this->config->item('track_login_attempts', 'app_config')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());
	
			$this->db->where(array('ip_address' => $ip_address, 'login' => $identity));
			// Purge obsolete login attempts
			$this->db->or_where('time <', time() - $expire_period, FALSE);
	
			return $this->db->delete('login_attempts');
		}
		return FALSE;
	}
	
	protected function _prepare_ip($ip_address) {
		if ($this->db->platform() === 'postgre' || $this->db->platform() === 'sqlsrv' || $this->db->platform() === 'mssql')
		{
			return $ip_address;
		}
		else
		{
			return inet_pton($ip_address);
		}
	}
	
	/**
	 * login_remembed_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function login_remembered_user()
	{
		//check for valid data
		if (!get_cookie('username') || !get_cookie('remember_code') || !$this->identity_check(get_cookie('username')))
		{
			return FALSE;
		}
	
		//get the user
		$query = $this->db->select('username, id, partner_id, last_login')
		->where('username', get_cookie('username'))
		->where('remember_code', get_cookie('remember_code'))
		->limit(1)
		->get('users');
	
		//if the user was found, sign them in
		if ($query->num_rows() == 1)
		{
			$user = $query->row();
	
			$this->update_last_login($user->id);
	
			$this->set_session($user);
	
			//extend the users cookies if the option is enabled
			if ($this->config->item('user_extend_on_login', 'app_config'))
			{
				$this->remember_user($user->id);
			}

			return TRUE;
		}
	
		return FALSE;
	}
	
	/**
	 * change password
	 *
	 * @return bool
	 **/
	public function change_password($identity, $old, $new)
	{
		$query = $this->db->select('id, password')
		->where('username', $identity)
		->limit(1)
		->get('users');
	
		if ($query->num_rows() !== 1)
		{
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}
	
		$user = $query->row();
	
		$old_password_matches = (md5($old) == $user->password) ? TRUE : FALSE;
	
		if ($old_password_matches === TRUE)
		{
			//store the new password and reset the remember code so all remembered instances have to re-login
			//$hashed_new_password  = $this->hash_password($new, $user->salt);
			$hashed_new_password = md5($new);
			$data = array(
					'password' => $hashed_new_password,
					'remember_code' => NULL,
			);
	
			$successfully_changed_password_in_db = $this->db->update('users', $data, array('username' => $identity));
			
			log_message('error', '[INFO] Update password [username: '.$identity.'], Query: '.$this->db->last_query());
			
			if ($successfully_changed_password_in_db)
			{
				$this->session->set_flashdata('message', lang('password_change_successful'));
			}
			else
			{
				$this->set_error('password_change_unsuccessful');
			}
	
			return $successfully_changed_password_in_db;
		}
	
		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}
}
