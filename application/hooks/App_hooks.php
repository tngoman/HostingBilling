<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

class App_hooks
{

	/**
	 * Stores the CodeIgniter core object.
	 *
	 * @access private
	 *
	 * @var object
	 */
	private $ci;

	/**
	 * List of pages where the hooks are not run.
	 *
	 * @access private
	 *
	 * @var array
	 */
	private $ignore_pages = array('/auth/login', '/auth/logout', '/auth/register/', '/auth/forgot_password', '/auth/register', '/auth/resend_activation','login','logout','register','set_language');

	//--------------------------------------------------------------------


	/**
	 * Costructor
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
	}//end __construct()

	public function fix_cache(){
		// Cache control
		$this->ci->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->ci->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->ci->output->set_header('Pragma: no-cache');
        $this->ci->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}

	//--------------------------------------------------------------------


	/**
	 * Stores the name of the current uri in the session as 'previous_page'.
	 * This allows redirects to take us back to the previous page without
	 * relying on inconsistent browser support or spoofing.
	 *
	 * Called by the "post_controller" hook.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function prep_redirect()
	{
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}

		if (!in_array($this->ci->uri->uri_string(), $this->ignore_pages))
		{
			$this->ci->session->set_userdata('previous_page', current_url());
		}
	}//end prep_redirect()

	//--------------------------------------------------------------------

	/**
	 * Store the requested page in the session data so we can use it
	 * after the user logs in.
	 *
	 * Called by the "pre_controller" hook.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function save_requested()
	{
		if (!class_exists('CI_Session'))
		{
			$this->ci->load->library('session');
		}

		if (!in_array($this->ci->uri->uri_string(), $this->ignore_pages))
		{
			$this->ci->session->set_userdata('requested_page', current_url());
		}
	}//end save_requested()

	

	//--------------------------------------------------------------------


	


}//end class
