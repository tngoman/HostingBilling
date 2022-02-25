<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Addon extends CI_Model
{
	private static $db;

	function __construct()
	{
		parent::__construct();
		self::$db = &get_instance()->db;
	}


	static function all ()
	{
		self::$db->select('items_saved.*, item_pricing.*'); 
		self::$db->from('items_saved');  
		self::$db->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER'); 
		self::$db->where('addon', 1);  
		return self::$db->get()->result();	
	}
 
	
	static function view ($id)
	{			
		self::$db->select('items_saved.*, item_pricing.*'); 
		self::$db->from('items_saved');  
		self::$db->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER'); 
		self::$db->where('items_saved.item_id', $id);  
		return self::$db->get()->row();
	} 


	static function get_addons ()
	{
		self::$db->select('item_name, item_id'); 
		self::$db->from('items_saved');  
		self::$db->where('deleted', 'No'); 
		self::$db->where('active', 'Yes');
		self::$db->where('addon', 1); 	      
		return self::$db->get()->result();
	} 

}

/* End of file model.php */