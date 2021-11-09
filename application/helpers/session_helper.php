<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Returns a session variable
 *
 * @access	public
 * @param	string	variable name
 * @return	boolean
 */	

if (!function_exists('session_userdata'))
{
	function session_userdata($key)
	{
		$CI =& get_instance();
		if (!isset($CI->session))
		{
			$CI->load->library('session');
		}
		return $CI->session->userdata($key);
	}
}

// --------------------------------------------------------------------

/**
 * Sets a session variable
 *
 * @access	public
 * @param	string	variable name
 * @return	boolean
 */	
if (!function_exists('session_set_userdata'))
{
	function session_set_userdata($value)
	{
		$CI =& get_instance();
		if (!isset($CI->session))
		{
			$CI->load->library('session');
		}
		return $CI->session->set_userdata($value);
	}
}
// --------------------------------------------------------------------

/**
 * Returns a session flash variable
 *
 * @access	public
 * @param	string	variable name
 * @return	boolean
 */	
if (!function_exists('session_flashdata'))
{
	function session_flashdata($key)
	{
		$CI =& get_instance();
		if (!isset($CI->session))
		{
			$CI->load->library('session');
		}

		return $CI->session->flashdata($key);
	}
}

// --------------------------------------------------------------------

/**
 * Sets a session flash variable
 *
 * @access	public
 * @param	string	variable name
 * @return	boolean
 */	
if (!function_exists('session_set_flashdata'))
{
	function session_set_flashdata($key, $value)
	{
		$CI =& get_instance();
		if (!isset($CI->session))
		{
			$CI->load->library('session');
		}
		return $CI->session->set_flashdata($key, $value);
	}
}
/* End of file session_helper.php */