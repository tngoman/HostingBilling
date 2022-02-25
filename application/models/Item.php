<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Item extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	static function list_items($array)
	{
		self::$db->select('items_saved.*, item_pricing.*, servers.name AS server, categories.parent, categories.cat_name, categories.id as category_id, categories.pricing_table'); 
		self::$db->from('items_saved');  
		self::$db->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER');
		self::$db->join('categories','categories.id = item_pricing.category','LEFT');
		self::$db->join('servers','items_saved.server = servers.id','LEFT');
		self::$db->where($array);  
		return self::$db->get()->result();	
	}
 
	
	static function view_item($id)
	{			
		self::$db->select('items_saved.*, item_pricing.*, categories.parent'); 
		self::$db->from('items_saved');  
		self::$db->join('item_pricing','items_saved.item_id = item_pricing.item_id','LEFT');
		self::$db->join('categories','categories.id = item_pricing.category','LEFT');
		self::$db->where('items_saved.item_id', $id);
		return self::$db->get()->row();
	}


	static function get_domains($id = null)
	{
		self::$db->select('item_name, registration, renewal, transfer'); 
		self::$db->from('items_saved');  
		self::$db->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER');
		self::$db->join('categories','categories.id = item_pricing.category','LEFT');
		self::$db->where('deleted', 'No');  
		self::$db->where('active', 'Yes');
		self::$db->where('display', 'Yes');
		if($id){
			self::$db->where('categories.id', $id);
		}
		self::$db->where('categories.parent', 8);        
        self::$db->order_by('items_saved.order_by', 'ASC');  
		return self::$db->get()->result();	
	}
 
	
	static function get_hosting($id = null)
	{			
		self::$db->select('*'); 
		self::$db->from('items_saved');  
		self::$db->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER');
		self::$db->join('categories','categories.id = item_pricing.category','LEFT');
		self::$db->where('deleted', 'No'); 
		self::$db->where('active', 'Yes');
		self::$db->where('display', 'Yes');
		if($id){
			self::$db->where('categories.id', $id);
		}
		self::$db->where('categories.parent', 9); 
        self::$db->order_by('items_saved.order_by', 'ASC');    
		return self::$db->get()->result();
	}




	static function get_services($id = null)
	{			
		self::$db->select('*'); 
		self::$db->from('items_saved');  
		self::$db->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER');
		self::$db->join('categories','categories.id = item_pricing.category','LEFT');
		self::$db->where('deleted', 'No'); 
		self::$db->where('active', 'Yes');	
		self::$db->where('categories.id', $id);
        self::$db->order_by('items_saved.order_by', 'ASC');    
		return self::$db->get()->result();
	}


	static function get_items ()
	{
		self::$db->select('item_name, item_id'); 
		self::$db->from('items_saved');  
		self::$db->where('deleted', 'No'); 
		self::$db->where('active', 'Yes');	      
		return self::$db->get()->result();
	} 


    static function update($item, $data)
    {
        return self::$db->where('item_id', $item)->update('items', $data);
    }

}

/* End of file model.php */