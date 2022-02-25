<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Sidebar extends Hosting_Billing 
{

	function __construct()
	{
		parent::__construct();
	 }

	public function admin_menu()
	{
 
        $this->load->view('admin_menu',isset($data) ? $data : NULL);
	}

	public function staff_menu()
	{
 
		$this->load->view('staff_menu',isset($data) ? $data : NULL);
	}

	public function client_menu()	{
 
        $this->load->view('user_menu',isset($data) ? $data : NULL);
	}

	public function top_header()
	{ 
                $data['updates'] = array();

                $this->load->view('top_header',isset($data) ? $data : NULL);
	}
	
	public function scripts()
	{
		$this->load->view('scripts/app_scripts',isset($data) ? $data : NULL);
	}
	
	public function flash_msg()
	{
		$this->load->view('flash_msg',isset($data) ? $data : NULL);
	}
}
/* End of file sidebar.php */