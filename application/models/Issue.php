<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Issue extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	static function requests()
	{
		self::$db->select('tickets.*, count(distinct hd_ticketreplies.id) as comments'); 
		self::$db->join('ticketreplies','tickets.id = ticketreplies.ticketid','left'); 
		self::$db->from('tickets');
		self::$db->where('department =', 8, FALSE);
		self::$db->or_where('department =', 2, FALSE);
		self::$db->group_by('tickets.id');
		return self::$db->get()->result();	
	} 
	

	static function request($slug)
	{			
		self::$db->select('tickets.*'); 
		self::$db->from('tickets');   
		self::$db->where('ticket_code', explode('_', $slug)[0]);
		$article = self::$db->get()->row(); 
		return $article;
	}


	static function replies($slug)
	{			
		self::$db->select('ticketreplies.*'); 
		self::$db->join('tickets','tickets.id = ticketreplies.ticketid'); 
		self::$db->from('ticketreplies');     
		self::$db->where('ticket_code', explode('_', $slug)[0]);
		return self::$db->get()->result();
	}


	static function category($name)
	{			
		self::$db->select('tickets.*'); 
		self::$db->from('tickets');   
		self::$db->join('departments','departments.deptid = tickets.department');
		self::$db->where('deptname', $name);  
		return self::$db->get()->result();
	}
 
 
	static function categories()
	{
		self::$db->select('count(distinct hd_tickets.id) AS num, deptname as cat_name'); 
		self::$db->from('tickets');   
		self::$db->join('departments','departments.deptid = tickets.department');  
		self::$db->where('department =', 8, FALSE);
		self::$db->or_where('department =', 2, FALSE); 
		self::$db->group_by('cat_name');
		return self::$db->get()->result();	
	}


	static function search($text)
	{			
		self::$db->select('subject'); 
		self::$db->from('tickets');  
		self::$db->where('department =', 8, FALSE);
		self::$db->or_where('department =', 2, FALSE);
		self::$db->like('subject', $text);		
		return self::$db->get()->result(); 
	}
  	

}

/* End of file model.php */