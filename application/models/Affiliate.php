<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Affiliate extends CI_Model
{

	private static $db;

	function __construct()
	{
		parent::__construct();
		self::$db = &get_instance()->db; 
	} 


	static function account($id)
	{
		self::$db->select('orders.date, items_saved.item_name, referrals.amount, referrals.commission, referrals.type, status'); 
		self::$db->from('referrals');  
		self::$db->join('orders','referrals.order_id = orders.id','LEFT'); 
		self::$db->join('items_saved','items_saved.item_id = orders.item_parent','LEFT');
		self::$db->join('status','orders.status_id = status.id','LEFT');  
		self::$db->where('referrals.affiliate_id =', $id); 
		self::$db->order_by('orders.date', 'desc'); 
		return self::$db->get()->result();
	}


	static function balance($id)
	{
		self::$db->select('sum(hd_referrals.commission) as balance'); 
		self::$db->from('referrals');  
		self::$db->join('orders','referrals.order_id = orders.id','LEFT'); 
		self::$db->where('orders.status_id =', 6);
		self::$db->where('orders.affiliate_paid =', 0);
		self::$db->where('referrals.affiliate_id =', $id);  	 
		return self::$db->get()->result();
	}


	static function withdrawals($id)
	{
		self::$db->select('hd_affiliates.*, company_name'); 
		self::$db->from('affiliates');  
		self::$db->join('companies','companies.co_id = affiliates.client_id','LEFT'); 
		self::$db->where('client_id =', $id);  
		self::$db->order_by('withdrawal_id', 'desc'); 	 
		return self::$db->get()->result();
	}  	

	static function withdrawal($id)
	{
		self::$db->select('hd_affiliates.*, company_name'); 
		self::$db->from('affiliates');  
		self::$db->join('companies','companies.co_id = affiliates.client_id','LEFT'); 
		self::$db->where('client_id =', $id); 
		self::$db->where('payment_date =', NULL);  
		self::$db->limit(1);
		return self::$db->get()->row();
	}  	



	static function all()
	{
		return self::$db->where(array('co_id >'=> 1, 'affiliate' => 1))->order_by('company_name','ASC')->get('companies')->result();
	}


}

/* End of file affiliate.php */