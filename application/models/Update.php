<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



class Update extends CI_Model
{ 
	function __construct()
	{
		parent::__construct();	
		require_once FCPATH.'/core/core_config.php';
		require_once FCPATH.'/core/core_updates.php'; 
	}


	static function get_versions()
	{
		return ausGetAllVersions();
	}


	static function version($id)
	{
		return ausGetVersion($id);
	}


	static function install($id)
	{
		return ausDownloadFile('version_upgrade_file', $id); 	
	}


	static function database($id)
	{
		return ausFetchQuery('upgrade', $id);
	}


    private static function _p($msg){
        $ci =& get_instance();
        $ci->session->set_flashdata('response_status', 'error');
        $ci->session->set_flashdata('message', $msg);
        redirect('updates');
	}
	
  

	static function update_database()
	{
		return ausFetchQuery();
	}

	
}

/* End of file update_model.php */