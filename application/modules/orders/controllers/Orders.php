<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Orders extends Hosting_Billing 
{
	private $server;

	function __construct()
	{
		parent::__construct(); 
		User::logged_in();
		
		$this->load->module('layouts');      
        $this->load->library('template');
 		$this->filter_by = $this->_filter_by(); 	
		$server = $this->db->where(array('selected'=> 1))->get('servers')->row();	

		if($server) {
			$this->server = $server->id;
		}	
		
		$lang = config_item('default_language');
		if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
		if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
		$this->lang->load('hd', $lang);
	}
	


	function index()
	{
		App::module_access('menu_orders');
		$this->template->title(lang('orders').' - '.config_item('company_name'));
		$data['page'] = lang('orders');
		$data['datatables'] = TRUE;		
		$data['form'] = true;
		$array = $this->filter_by($this->filter_by);
		$data['orders'] = Order::list_orders($array);
		$this->template
		->set_layout('users')
		->build('orders',isset($data) ? $data : NULL);
	}


	
	function _filter_by()
	{
		$filter = isset($_GET['view']) ? $_GET['view'] : '';
		return $filter;
	}



	function filter_by($filter_by) 
	{
		switch ($filter_by) {
			case 'unpaid':
			return array('hd_invoices.status' => 'Unpaid', 'status_id <>' => 8, 'status_id <>' => 2);
			break;

			case 'paid':
			return array('hd_invoices.status' => "Paid", 'status_id <>' => 8, 'status_id <>' => 2);
			break; 
			
			default:
			return array('status_id <>' => 8, 'status_id <>' => 2);
			break;
		}
	}

		

function activate($id = null)
	{	

		$domain_servers = array();

		App::module_access('menu_orders');
		if($this->input->post()) {  

				$result = "";
				if ($this->input->post('hosting')) {  

					$client = Client::view_by_id($this->input->post('client_id'));
					$accounts = $this->input->post('username');
					$domain = $this->input->post('hosting_domain');
					$passwords = $this->input->post('password');
					$hosting = $this->input->post('hosting');
					$service = $this->input->post('service');
					$servers = $this->input->post('server');
					$user = User::view_user($client->primary_contact);
					$profile = User::profile_info($client->primary_contact);

					$this->db->set('inv_deleted', "No"); 
					$this->db->where('inv_id', $this->input->post('inv_id'));  
					$this->db->update('invoices');

					foreach($accounts as $key => $account) 
					{
						$item = $this->input->post('item_id');

						$domain_servers[] = array($domain[$key] => $servers[$key]);

						$update = array(
							"status_id" => 6,
							"username" => $accounts[$key],
							"password" => $passwords[$key],
							"server" => $servers[$key]				
						); 
						
						$this->db->where('id', $hosting[$key]);  
						if($this->db->update('orders', $update)) 
						{
							$result .= $service[$key]." for ". $domain[$key] ." activated.<br>";
						}						
						
						$username = $accounts[$key];
						
						if($username && $username.'_send_details' == 'on') {
							Order::send_account_details($hosting[$key]);
						}

						$acc = Order::get_order($hosting[$key]); 

						if(config_item('demo_mode') != 'TRUE') 
						{
							if($this->input->post($acc->username.'_controlpanel')  == 'on') 
							{
								$package = $this->db->where(array('item_id'=> $acc->item_parent))->get('items_saved')->row();								 
								$server = $this->db->where(array('id'=> $servers[$key]))->get('servers')->row(); 
			
								$details = (object) array(
									'user' => $user, 
									'profile' => $profile, 
									'client' => $client, 
									'account' => $acc,
									'package' => $package,
									'server' => $server
								);
			
								$result .= modules::run($server->type.'/create_account', $details);  
							}
						}			
					}						
				}

 


				if ($this->input->post('domain')) {
					$domains = $this->input->post('domain');  

					foreach($domains as $key => $account) {	
 
						$acc = Order::get_order($domains[$key]);  

							$update = array(
								"status_id" => 6,
								"authcode" => $this->input->post('authcode')[$key],
								'registrar' => $this->input->post('registrar')[$key]
							); 
					
							$this->db->where('id', $domains[$key]);  

							if($this->db->update('orders', $update)){ 

								$domain = explode('.', $acc->domain, 2); 

								if($this->input->post($domain[0].'_activate') == 'on') {  
					
									if($this->input->post('registrar')[$key] != '') { 

										$registrar = $this->input->post('registrar')[$key]; 	

											$action = '/register_domain';

											$nameservers = Order::get_nameservers($domains[$key], $domain_servers);	
 
											if($nameservers != '')
											{
												$nameservers = explode(",", $nameservers);
											}

											else
											{
												$nameservers = array();
												if(config_item('nameserver_one') != '') {
													$nameservers[] = config_item('nameserver_one');
												}
												if(config_item('nameserver_two') != '') {
													$nameservers[] = config_item('nameserver_two');
												}
												if(config_item('nameserver_three') != '') {
													$nameservers[] = config_item('nameserver_three');
												}
												if(config_item('nameserver_four') != '') {
													$nameservers[] = config_item('nameserver_four');
												}
												if(config_item('nameserver_five') != '') {
													$nameservers[] = config_item('nameserver_five');
												}
											}	 
								
											if($acc->item_name == lang('domain_renewal')) {
												$action = '/renew_domain';
											}

											if($acc->item_name == lang('domain_transfer')) {
												$action = '/transfer_domain';
											} 

											$result .= modules::run($registrar.$action, $domains[$key], $nameservers);		
										}										
										
										$data = array(
											'user' => User::get_id(),
											'module' => 'accounts',
											'module_field_id' => $domains[$key],
											'activity' => $result,
											'icon' => 'fa-usd',
											'value1' =>  $acc->domain,
											'value2' => ''
										);
											App::Log($data);  
									
									$result .= "<p>" .$acc->domain." activated! </p>";
							} 
						}				
					}						
				}

				$this->session->set_flashdata('response_status', 'warning');
                $this->session->set_flashdata('message', $result);				
				redirect($_SERVER['HTTP_REFERER']);				
			 
		}

		else {
			$data['order'] = $this->get_order($id);
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/activate', $data);
		}
	}





	function cancel($id = null)
	{
		App::module_access('menu_orders');
		if ($this->input->post()) { 

				if($this->input->post('credit_account') == 'on')
				{
					Invoice::credit_client($this->input->post('invoice_id'));
				}
 
				$result = "";
				if ($this->input->post('hosting')) {
					$accounts = $this->input->post('username');
					$hosting = $this->input->post('hosting');
					$service = $this->input->post('service');
					$domain = $this->input->post('account');

					$this->db->set('inv_deleted', "Yes"); 
					$this->db->where('inv_id', $this->input->post('invoice_id'));  
					$this->db->update('invoices');

					foreach($accounts as $key => $a) 
					{
						$this->db->set('status_id', 7); 
						$this->db->where('id', $hosting[$key]);  
						if($this->db->update('orders')) {
							$result .=  $service[$key]." for ". $domain[$key] ." cancelled.<br>";
						}

						if(config_item('demo_mode') != 'TRUE') {

							$account = Order::get_order($hosting[$key]); 

							if($this->input->post($account->username.'_delete_controlpanel') == 'on') 
							{ 
								$server = Order::get_server($account->server);
								$client = Client::view_by_id($account->client_id); 
								$user = User::view_user($client->primary_contact);
								$details = (object) array('account' => $account, 'server' => $server, 'package' => $package, 'client' => $client, 'user' => $user);
								$result .= modules::run($server->type.'/terminate_account', $details);			 
		
							}
						}				
					}						
				}
				


				if ($this->input->post('domain')) {
					$domains = $this->input->post('domain');
					$domain = $this->input->post('domain_name');

					foreach($domains as $key => $account) { 

							$this->db->set('status_id', 7); 
							$this->db->where('id', $domains[$key]);  
							if($this->db->update('orders')){
								$result .= "Domain: " .$domain[$key]." cancelled!<br>";
						} 					 				
					}						
				}

				$this->session->set_flashdata('response_status', 'warning');
                $this->session->set_flashdata('message', $result);
                redirect($_SERVER['HTTP_REFERER']);

		}

		else {	
			$data['order'] = $this->get_order($id);
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/cancel', $data);
		}
	}




	function delete($id = null)
	{
		App::module_access('menu_orders');
		if ($this->input->post()) {

				if($this->input->post('credit_account') == 'on')
				{
					Invoice::credit_client($this->input->post('invoice_id'));
				}

				$result = "";
				if ($this->input->post('hosting')) {
					$accounts = $this->input->post('username');
					$hosting = $this->input->post('hosting');
					$service = $this->input->post('service');
					$domain = $this->input->post('account'); 

					foreach($accounts as $key => $a) {
					
						$this->db->where('id', $hosting[$key]);  
						if($this->db->delete('orders')) {
							$result .=  $service[$key]." for ". $domain[$key] ." deleted.<br>";
						}

					if(config_item('demo_mode') != 'TRUE') {

						$account = Order::get_order($hosting[$key]); 

						if($this->input->post($account->username.'_delete_controlpanel') == 'on') 
							{							
								$server = Order::get_server($account->server);
								$client = Client::view_by_id($account->client_id); 
								$user = User::view_user($client->primary_contact);
								$details = (object) array('account' => $account, 'server' => $server, 'package' => $package, 'client' => $client, 'user' => $user);
								$result .= modules::run($server->type.'/terminate_account', $details); 
							}
						}									
					}
				}


				if ($this->input->post('domain')) {

					$domains = $this->input->post('domain');
					$domain = $this->input->post('domain_name');

					foreach($domains as $key => $account) { 
 
							$this->db->where('id', $domains[$key]);  
							if($this->db->delete('orders')){
								$result .= "Domain: " .$domain[$key]." deleted!<br>";
						} 
					 				
					}						
				}

				$invoice = $this->input->post('invoice_id');
				Invoice::delete($invoice);	


				$this->session->set_flashdata('response_status', 'warning');
                $this->session->set_flashdata('message', $result);				
                redirect($_SERVER['HTTP_REFERER']);

		}

		else {
			$data['order'] = $this->get_order($id);
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/delete', $data);
		}
	}


	function select_client()
	{
		if($this->input->post()) {
			$this->session->set_userdata(array('co_id' => $this->input->post('co_id')));
			redirect('orders/add_order'); 
		}
		else
		{
			$this->template->title(lang('orders').' - '.config_item('company_name'));
			$data['page'] = lang('new_order'); 
            $data['form'] = true;
			$this->template
			->set_layout('users')
			->build('select_client',isset($data) ? $data : NULL);
		}
	}


 

	
	function get_order($id) 
	{
		$this->db->select('*');
		$this->db->from('orders');
		$this->db->join('items','orders.item = items.item_id','LEFT');
		$this->db->where('order_id',$id);			
		return $this->db->get()->result();
	}



	function add_order()
	{
		if ($this->input->post()) { 
				$this->session->set_flashdata('response_status', 'warning');
                $this->session->set_flashdata('message', $result);
                redirect($_SERVER['HTTP_REFERER']);

		}

		else { 		
			$this->template->title(lang('orders').' - '.config_item('company_name'));
			$data['page'] = lang('orders');
			$data['datepicker'] = true;
            $data['form'] = true;
			$this->template
			->set_layout('users')
			->build(config_item('active_theme').'/views/pages/add_order',isset($data) ? $data : NULL);
		}
	}




	private function process_upgrade($o_id) 
	{	
	 	$order =  $this->db->select('*')->from('orders')->join('items_saved','orders.item_parent = items_saved.item_id','inner')->where('o_id',$o_id)->get()->row();
		$domain =  $this->db->select('*')->from('orders')->where('id', $o_id)->get()->row();
		$package = $this->db->where(array('item_id'=> $order->item_parent))->get('items_saved')->row();
		
		if($order->renewal == 'annually') {
			$process_id = $domain->process_id; 
		}

		else {
			$process_id = time();
		}
 
  		$update = array(
			"status_id" => 6,
			"order_id" => $domain->order_id,
			"process_id" => $process_id,
			"o_id" => 0
		); 
		
		$this->db->where('o_id', $o_id);  
 		if($this->db->update('orders', $update)) {
			$result = "Order updated. <br>";

			$activity = array(
				'user' => User::get_id(),
				'module' => 'accounts',
				'module_field_id' => $order,
				'activity' => 'activity_activate_upgrade',
				'icon' => 'fa-plus',
				'value1' => $order->invoice_id
			);

			App::Log($activity);
 
			$update_item = array(
				"item_name" => $order->item_name
			); 
			
			$this->db->where('item_id', $order->item);  
			$this->db->update('items', $update_item);

			$this->db->where('id', $o_id);
			$this->db->delete('orders');

			if($order->server != null && config_item('demo_mode') != 'TRUE') {		
 
				$client = Client::view_by_id($order->client_id); 
				$user = User::view_user($client->primary_contact);
				$profile = User::profile_info($client->primary_contact);
				$server = Order::get_server($order->server);

				$details = (object) array(
					'user' => $user, 
					'profile' => $profile, 
					'client' => $client, 
					'account' => $account,
					'package' => $package,
					'server' => $server
				); 

				$details = array('server' => $server, 'account' => $order);				
				$result = modules::run($server->type.'/change_package', $details); 

				$activity = array(
					'user' => User::get_id(),
					'module' => 'accounts',
					'module_field_id' => $order,
					'activity' => $result,
					'icon' => 'fa-plus',
					'value1' => $order->invoice_id
				);
	
				App::Log($activity);			 
			}

			$this->session->set_flashdata('response_status', 'warning');
			$this->session->set_flashdata('message', $result);

			$from = $_SERVER['HTTP_REFERER'];
			$segments = explode('/', $from);
			
			if($segments[3] == 'invoices') {
				redirect('accounts');
			}
			
			else {
				redirect($_SERVER['HTTP_REFERER']);	
			}
					
		}
	}

	

	static function process($id)
	{
		$ci =& get_instance();
		$item = $ci->db->where('invoice_id', $id)->get('items')->result(); 
		if($item[0]->item_name == lang('add_funds')) 
		{		 
			$payment = Payment::by_invoice($id);
			$amount = $payment[0]->amount;

			$client = Client::view_by_id(Invoice::view_by_id($id)->client);
			$credit = $client->transaction_value;
			$bal = $credit + $amount;
			
			$balance = array(
				'transaction_value' => Applib::format_deci($bal)
			);
			
			$ci->db->where('co_id', $client->co_id)->update('companies', $balance);
			return true;
		}

		$ci->db->select('*');
		$ci->db->from('orders');
		$ci->db->join('items_saved','orders.item_parent = items_saved.item_id','LEFT');
		$ci->db->join('invoices','orders.invoice_id = invoices.inv_id','inner');
		$ci->db->where('inv_id', $id);
		$accounts = $ci->db->get()->result();

		foreach($accounts as $acc)
		{
			$referral = $ci->db->where('order_id', $acc->id)->get('referrals')->row();
			if(is_object($referral))
			{
				$affiliate = $ci->db->where('affiliate_id', $referral->affiliate_id)->get('companies')->row();
				$balance = $affiliate->affiliate_balance + $referral->commission;
				
				$aff_data = array(
					'affiliate_balance' => $balance
				); 
				$ci->db->where('affiliate_id', $referral->affiliate_id);
				$ci->db->update('companies', $aff_data);
			}
		}

		if (config_item('automatic_activation') == 'TRUE' && 
			$ci->db->where('invoice_id', $id)->where('status_id', 5)->get('orders')->num_rows() > 0) {

				if(count($accounts) == 1 && $accounts[0]->o_id > 0) { 
				$order = $accounts[0];
				$o_id = $accounts[0]->o_id;

				$domain =  $ci->db->select('*')->from('orders')->where('id', $o_id)->get()->row();
				$package = $ci->db->where(array('item_id'=> $order->item_parent))->get('items_saved')->row();
				
				if($order->renewal == 'annually') {
					$process_id = $domain->process_id; 
				}
		
				else {
					$process_id = time();
				}
		 
				  $update = array(
					"status_id" => 6,
					"order_id" => $domain->order_id,
					"process_id" => $process_id,
					"o_id" => 0
				); 
				
				 $ci->db->where('o_id', $o_id);  
				 if($ci->db->update('orders', $update)) {
					$result = "Order updated. <br>";
		
					$activity = array(
						'user' => User::get_id(),
						'module' => 'accounts',
						'module_field_id' => $order->id,
						'activity' => 'activity_activate_upgrade',
						'icon' => 'fa-plus',
						'value1' => $order->invoice_id
					);
		
					App::Log($activity);
		 
					$update_item = array(
						"item_name" => $order->item_name
					); 
					
					$ci->db->where('item_id', $order->item);  
					if($ci->db->update('items', $update_item))
					{	
						App::delete('orders', array('id' => $o_id));						
						if($order->server != null && config_item('demo_mode') != 'TRUE') {		
			
							$client = Client::view_by_id($order->client_id); 
							$user = User::view_user($client->primary_contact);
							$profile = User::profile_info($client->primary_contact);
							$server = Order::get_server($order->server);
			
							$details = (object) array(
								'user' => $user, 
								'profile' => $profile, 
								'client' => $client, 
								'account' => $order,
								'package' => $package,
								'server' => $server
							); 
								
							$result = modules::run($server->type.'/change_package', $details); 
			
							$activity = array(
								'user' => User::get_id(),
								'module' => 'accounts',
								'module_field_id' => $order->id,
								'activity' => $result,
								'icon' => 'fa-plus',
								'value1' => $order->invoice_id
							);
				
							App::Log($activity);
						}						 
					}		
				}
			}

			else {			 

			foreach($accounts AS $account) {

				$client =  Client::view_by_id($account->client_id);
				$user = User::view_user($client->primary_contact);
				$profile = User::profile_info($client->primary_contact);

				if($account->type == 'hosting') {	
					
					$update = array(
						"status_id" => 6,
						"server" => (null != $account->server && $account->server > 0 && $account->server != '') ? $account->server : $ci->server	
					); 
					
					$ci->db->where('id', $account->id);  
					if($ci->db->update('orders', $update)) {

						$data = array(
							'user' => $account->client_id,
							'module' => 'accounts',
							'module_field_id' => $account->id,
							'activity' => 'activity_account_activated',
							'icon' => 'fa-usd',
							'value1' => $account->reference_no,
							'value2' => $account->inv_id
						);
							App::Log($data); 			
					}					
		
					 Order::send_account_details($account->id);
					 
					 $server = $ci->db->where('id', $account->server)->get('servers')->row();
					 
					 if(!$server && !empty($ci->server)) {
						$server = $ci->db->where('id', $ci->server)->get('servers')->row();
					 }
 
					 if ($server && config_item('demo_mode') != 'TRUE')
					 { 					 
							$package = $ci->db->where(array('item_id'=> $account->item_parent))->get('items_saved')->row();

							$details = (object) array(
								'user' => $user, 
								'profile' => $profile, 
								'client' => $client, 
								'account' => $account,
								'package' => $package,
								'server' => $server
							);
		
							   modules::run($server->type.'/create_account', $details);  						

								$data = array(
								'user' => $account->client_id,
								'module' => 'accounts',
								'module_field_id' => $account->id,
								'activity' => 'activity_cpanel_creation',
								'icon' => 'fa-usd',
								'value1' => $result,
								'value2' => $account->inv_id
							);
						}	

							App::Log($data);  				 
					 }
			 

				if($account->type == 'domain' || $account->type == 'domain_only') {
					$registrar = '';

					if(empty($account->registrar)) {
						$item = $ci->db->where('item_id', $account->item_parent)->get('items_saved')->row();
						$item->default_registrar;

						$ci->db->set('status_id', 6); 
						$ci->db->set('registrar', $registrar);
						$ci->db->where('id', $account->id);  
						$ci->db->update('orders');
						}
						else {
							$registrar = $account->registrar;
						}
					 
						if(!empty($registrar)){

							$process = $account->domain. " activated!";

							if(Plugin::get_plugin($registrar)) { 
 	
							$action = '/register_domain';	
							
							$nameservers = Order::get_nameservers($account->id);	
 
							if($nameservers != '')
							{
								$nameservers = explode(",", $nameservers);
							}

							else
							{
								$nameservers = array();
								if(config_item('nameserver_one') != '') {
									$nameservers[] = config_item('nameserver_one');
								}
								if(config_item('nameserver_two') != '') {
									$nameservers[] = config_item('nameserver_two');
								}
								if(config_item('nameserver_three') != '') {
									$nameservers[] = config_item('nameserver_three');
								}
								if(config_item('nameserver_four') != '') {
									$nameservers[] = config_item('nameserver_four');
								}
								if(config_item('nameserver_five') != '') {
									$nameservers[] = config_item('nameserver_five');
								}
							}	 

											
							if($account->item_name == lang('domain_renewal')) {
								$action = '/renew_domain';
							}
							if($account->item_name == lang('domain_transfer')) {
								$action = '/transfer_domain';
							}
 							$process .= modules::run($registrar.$action, $account->id, $nameservers);							 
							
							}

							$data = array(
								'user' => $account->client_id,
								'module' => 'accounts',
								'module_field_id' => $account->id,
								'activity' => $process,
								'icon' => 'fa-usd',
								'value1' =>  $account->domain,
								'value2' => ''
							);
								App::Log($data); // Log activity
						} 
					}
		  		}			 			
			}			
		}

		else
		{		 
			$ci->db->join('items','items.item_id = orders.item'); 		
			$account = $ci->db->where(array('status_id'=> 9,'items.invoice_id' => $id))->get('orders')->row();  
			if(isset($account))
			{  
				$ci->db->where('id', $account->id);  
				if($ci->db->update('orders', array("status_id" => 6))) 
				{					 
					if (config_item('automatic_email_on_recur') == 'TRUE')
					{   
						send_email($id, 'service_unsuspended', $account);
					}

					if (config_item('sms_gateway') == 'TRUE' && 
						config_item('sms_service_unsuspended') == 'TRUE')
					{   
						send_message($id, 'service_unsuspended');
					}
				}
			}
		}
	}	
}
 

/* End of file orders.php */