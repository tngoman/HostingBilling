<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class KB extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	static function pages()
	{
		self::$db->select('posts.*, cat_name'); 
		self::$db->from('posts');   
		self::$db->join('categories','categories.id = posts.knowledge_id'); 
		self::$db->where('status', 1);  
		return self::$db->get()->result();	
	}
 
	
	static function page($slug)
	{			
		self::$db->select('posts.*, cat_name'); 
		self::$db->from('posts');   
		self::$db->join('categories','categories.id = posts.knowledge_id'); 
		self::$db->where('slug', $slug);  
		$article = self::$db->get()->row();
		self::counter($article);
		return $article;
	}

	
	static function latest()
	{			
		self::$db->select('posts.title, cat_name, views, modified, slug'); 
		self::$db->from('posts');   
		self::$db->join('categories','categories.id = posts.knowledge_id');  
		self::$db->order_by('created', 'desc');  
		self::$db->limit(10);  
		return self::$db->get()->result(); 
	}



	static function popular()
	{			
		self::$db->select('posts.title, cat_name, views, modified, slug'); 
		self::$db->from('posts');   
		self::$db->join('categories','categories.id = posts.knowledge_id'); 
		self::$db->where('views >', 0);
		self::$db->order_by('views', 'desc');  
		self::$db->limit(10);  
		return self::$db->get()->result(); 
	}



	static function search($text)
	{			
		self::$db->select('title, slug'); 
		self::$db->from('posts'); 
		self::$db->where('knowledge', 1);
		self::$db->like('title', $text);		
		return self::$db->get()->result(); 
	}



	static function counter ($article)
	{	
		$data = array('views' => $article->views + 1);		
		App::update('posts', array('id' => $article->id), $data);
	}



	static function category($name)
	{			
		self::$db->select('posts.title, cat_name, views, modified, slug'); 
		self::$db->from('posts');   
		self::$db->join('categories','categories.id = posts.knowledge_id'); 
		self::$db->where('cat_name', $name);  
		return self::$db->get()->result();
	}



	static function categories()
	{
		self::$db->select('count(distinct hd_posts.id) AS num, cat_name'); 
		self::$db->from('posts');   
		self::$db->join('categories','categories.id = posts.knowledge_id');  
		self::$db->where('status', 1);  
		self::$db->group_by('cat_name');
		return self::$db->get()->result();	
	}
 
  	

}

/* End of file model.php */