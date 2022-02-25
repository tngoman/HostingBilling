<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class FAQS extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	static function articles()
	{
		self::$db->select('posts.*'); 
		self::$db->from('posts');   
		self::$db->join('categories','categories.id = posts.faq_id'); 
		self::$db->where('faq', 1);
		self::$db->where('status', 1);  
		return self::$db->get()->result();	
	} 


	static function category($name)
	{			
		self::$db->select('posts.*'); 
		self::$db->from('posts');   
		self::$db->join('categories','categories.id = posts.faq_id'); 
		self::$db->where('posts.faq_id >', 0); 
		self::$db->where('cat_name', $name);  
		return self::$db->get()->result();
	}
 

	static function categories()
	{
		self::$db->select('count(distinct hd_posts.id) AS num, cat_name'); 
		self::$db->from('posts');   
		self::$db->join('categories','categories.id = posts.faq_id');  
		self::$db->where('posts.faq_id >', 0); 
		self::$db->where('status', 1);  
		self::$db->group_by('cat_name');
		return self::$db->get()->result();	
	}
  	

}

/* End of file model.php */