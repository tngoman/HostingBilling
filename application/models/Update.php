<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



class Update extends CI_Model
{ 
	
	static function get_versions()
	{
		return true;
	}


	static function version($id)
	{
		return true;
	}


	static function install($id)
	{
		return true;	
	}


	static function database($id)
	{
		return true;
	}

 
  

	static function update_database()
	{
		return true;
	}

	
}

/* End of file update_model.php */