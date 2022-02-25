<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Order extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db; 
	}


	static function list_orders($array)
	{
		self::$db->select('min(hd_orders.id) AS id, order_id, status_id, inv_id, company_name, status.status AS order_status, date, hd_invoices.status, reference_no'); 
		self::$db->from('orders');  
		self::$db->join('invoices','orders.invoice_id = invoices.inv_id','LEFT');
		self::$db->join('status','orders.status_id = status.id','LEFT');
		self::$db->join('companies','orders.client_id = companies.co_id','LEFT');
		self::$db->join('items','orders.item = items.item_id','LEFT');
		self::$db->where('orders.invoice_id >', 0);
		self::$db->where($array); 
		self::$db->group_by('order_id');
		self::$db->order_by('id', 'desc');
		return self::$db->get()->result();	
	}



	static function get_order($id)
	{
		self::$db->select('orders.*, items.*, servers.type AS server_type, status.status AS order_status, items_saved.package_name, servers.name AS server_name, reseller_package');			
		self::$db->from('orders');
		self::$db->join('items','orders.item = items.item_id','LEFT');
		self::$db->join('items_saved','orders.item_parent = items_saved.item_id','LEFT');
		self::$db->join('servers','orders.server = servers.id','LEFT');
		self::$db->join('invoices','orders.invoice_id = invoices.inv_id','LEFT');
		self::$db->join('status','orders.status_id = status.id','LEFT');
		self::$db->where('orders.id', $id);
		return self::$db->get()->row();
	}


	static function get_server($id)
	{			
		self::$db->select('*'); 
		self::$db->from('servers');
		self::$db->where('id', $id); 
		return self::$db->get()->row();
	}


	static function get_package($id)
	{			
		self::$db->select('*'); 
		self::$db->from('items_saved');
		self::$db->where('item_id', $id); 
		return self::$db->get()->row();
	} 



	static function get_domain_order($id)
	{
		self::$db->select('orders.*, status.status as domain_status, invoices.inv_id, invoices.reference_no, items.*');
		self::$db->from('orders');
		self::$db->join('items','orders.item = items.item_id','LEFT');
		self::$db->join('status','orders.status_id = status.id','LEFT');
		self::$db->join('invoices','orders.invoice_id = invoices.inv_id','LEFT');
		self::$db->where('orders.id', $id);
		return self::$db->get()->row();
	}



	static function get_resellerclub_ids($domain)
	{
		self::$db->select('*');
		self::$db->from('resellerclub_ids');
		self::$db->where('domain', $domain);
		return self::$db->get()->row();
	}
 

	
	static function view_item($id)
	{			
		self::$db->select('*'); 
		self::$db->from('items_saved');  
		self::$db->join('item_pricing','items_saved.item_id = item_pricing.item_id','LEFT');
		self::$db->join('categories','categories.id = item_pricing.category','LEFT');
		self::$db->where('items_saved.item_id', $id);  
		return self::$db->get()->row();
	}



	static function pending_domains()
	{			
		self::$db->select('id'); 
		self::$db->from('orders');
		self::$db->where('status_id', 5);
		self::$db->where("(type ='domain' OR type ='domain_only')");
		return self::$db->get()->num_rows();
	}




	static function pending_accounts()
	{			
		self::$db->select('id'); 
		self::$db->from('orders');
		self::$db->where('status_id', 5);
		self::$db->where('type', 'hosting');
		return self::$db->get()->num_rows();
	}


	static function client_domains($client)
	{			
		self::$db->select('id'); 
		self::$db->from('orders');
		self::$db->where('client_id', $client);
		self::$db->where('status_id >', 5);
		self::$db->where("(type ='domain' OR type ='domain_only')");
		return self::$db->get()->num_rows();
	}



	static function client_accounts($client)
	{			
		self::$db->select('id'); 
		self::$db->from('orders');
		self::$db->where('client_id', $client);
		self::$db->where(array('o_id' => 0));
		self::$db->where("(type ='hosting')");
		return self::$db->get()->num_rows();
	}


	static function all_orders()
	{			
		self::$db->select('*'); 
		self::$db->from('orders');
		self::$db->join('items','orders.item = items.item_id','LEFT'); 
		self::$db->where('status_id', 6);
		self::$db->order_by("renewal_date", "DESC");
		return self::$db->get()->result();
	}


	static function client_orders($client)
	{			
		self::$db->select('*'); 
		self::$db->from('orders');
		self::$db->join('items','orders.item = items.item_id','LEFT');
		self::$db->where('client_id', $client);
		self::$db->where('status_id', 6);
		self::$db->where('date(renewal_date) >', date('Y-m-d'));
		return self::$db->get()->result();
	}


	static function unpaid_orders()
	{			
		self::$db->select('inv_id'); 
		self::$db->from('invoices');
		self::$db->join('orders','orders.invoice_id = invoices.inv_id','LEFT');
		self::$db->where('inv_deleted', 'No');
		self::$db->where('status', 'Unpaid');
		self::$db->group_by('inv_id');
		return self::$db->get()->num_rows();
	}


	static function get_nameservers ($id, $domain_servers = null)
	{ 		
		$order = self::get_order($id); 

		if($order->nameservers != '')
		{
			return $order->nameservers;
		}

		if($domain_servers)
		{
			 
			foreach ($domain_servers as $array)
			{
				foreach ($array as $k => $v)
				{
					if($k == $order->domain)
					{
						$server = self::$db->select('*')->from('servers')->where('id', $v)->get()->row();

					 	$ns = self::get_ns($server);
						return $ns;						
					}
				}
			}
		}

		$server = self::get_server($order->server);
		$ns = self::get_ns($server);
		return $ns; 
	}

	

	static function get_ns ($server)
	{
		$nameservers = '';

		if(isset($server->ns1) && $server->ns1 != '')
		{
			$nameservers .=	$server->ns1;
		}

		if(isset($server->ns2) && $server->ns2 != '')
		{
			$nameservers .=	','.$server->ns2;
		}

		if(isset($server->ns3) && $server->ns3 != '')
		{
			$nameservers .=	','.$server->ns3;
		}

		if(isset($server->ns4) && $server->ns4 != '')
		{
			$nameservers .=	','.$server->ns4;
		}

		if(isset($server->ns5) && $server->ns5 != '')
		{
			$nameservers .=	','.$server->ns5;
		}

		return $nameservers;
	}



	static function send_account_details($id = null) 
	{	
		$account = Order::get_order($id); 
		$client = Client::view_by_id($account->client_id);
		$message = App::email_template('hosting_account','template_body');
		$subject = App::email_template('hosting_account','subject');
		$signature = App::email_template('email_signature','template_body');
		$subject = $subject .' for '.$account->domain;

		$logo_link = create_email_logo();
		$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);
		$username = str_replace("{ACCOUNT_USERNAME}", $account->username, $logo);
		$password = str_replace("{ACCOUNT_PASSWORD}", $account->password, $username);
		$domain = str_replace("{DOMAIN}", $account->domain, $password);
		$renewal_date = str_replace("{RENEWAL_DATE}", $account->renewal_date, $domain);
		$package = str_replace("{PACKAGE}", $account->item_name, $renewal_date);
		$renewal = str_replace("{RENEWAL}", ucfirst($account->renewal), $package);
		$amount = str_replace("{AMOUNT}", App::currencies(config_item('default_currency'))->symbol . Applib::format_quantity($account->total_cost), $renewal);
		$EmailSignature = str_replace("{SIGNATURE}", $signature, $amount);
		$client_name = str_replace("{CLIENT}", $client->company_name, $EmailSignature);
		$message = str_replace("{SITE_NAME}",config_item('company_name'),$client_name);

		$params = array(
			'recipient' => $client->company_email,
			'subject' => $subject,
			'message' => $message,
		);

		modules::run('fomailer/send_email', $params);
	}


	static function update($order, $data)
    {
        return self::$db->where('id', $order)->update('orders', $data);
    }

}

/* End of file model.php */