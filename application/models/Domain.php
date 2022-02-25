<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



class Domain extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}


	static function by_where($array = NULL, $type){
		self::$db->select('hd_orders.id AS id,
						inv_id, 
						order_id, 
						client_id, 
						item_name, 
						status_id, 
						orders.username, 
						domain, 
						company_name, 
						status.status AS order_status, 
						date, 
						item_parent,
						hd_invoices.status, 
						nameservers,
						servers.type,
						servers.name AS server_name,
						reference_no'); 
		self::$db->from('orders');  
		self::$db->join('items','orders.item = items.item_id','LEFT');
		self::$db->join('invoices','orders.invoice_id = invoices.inv_id','LEFT');
		self::$db->join('status','orders.status_id = status.id','LEFT');
		self::$db->join('companies','orders.client_id = companies.co_id','LEFT');
		self::$db->join('servers','orders.server = servers.id','LEFT');
		self::$db->where($type);
		self::$db->where($array);
		self::$db->where(array('o_id' => 0));		
		self::$db->order_by('id', 'desc');
		return self::$db->get()->result();		 

	}


	static function by_client($company, $type){
		self::$db->select('hd_orders.id AS id, 
						order_id, 
						client_id, 
						item_name, 
						status_id, 
						username, 
						password,
						domain, 
						company_name, 
						status.status AS order_status, 
						date, 
						nameservers,
						hd_invoices.status, 
						reference_no'); 
		self::$db->from('orders');  
		self::$db->join('items','orders.item = items.item_id','LEFT');
		self::$db->join('invoices','orders.invoice_id = invoices.inv_id','LEFT');
		self::$db->join('status','orders.status_id = status.id','LEFT');
		self::$db->join('companies','orders.client_id = companies.co_id','LEFT');
		self::$db->where($type);
		self::$db->where('client_id', $company);
		self::$db->order_by('id', 'desc');
		return self::$db->get()->result();		 

	}


	static function get_details($domain){
		self::$db->select('*'); 
		self::$db->from('orders'); 
		self::$db->where('type', 'domain');
		self::$db->where('domain', $domain);
		return self::$db->get()->row();
	}



}

/* End of file domain.php */
