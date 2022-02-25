<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends Hosting_Billing {

	function __construct()
		{
			parent::__construct();
			User::logged_in();

			$this->load->module('layouts');
			$this->load->library('template'); 
			$this->filter_by = $this->_filter_by();		
		}
 


	function index() 
	{			
		$type = "(orders.type ='hosting')";
		$array = $this->filter_by($this->filter_by);
		
		if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')){
			$data['accounts'] = Domain::by_where($array, $type);
		}else{
			$array['client_id'] = User::profile_info(User::get_id())->company;
			$data['accounts'] = Domain::by_where($array, $type);				
		}			

		$this->template->title(lang('accounts').' - '.config_item('company_name'));
		$data['page'] = lang('accounts');
		$data['datatables'] = TRUE;
		
		$this->template
		->set_layout('users')
		->build('accounts',isset($data) ? $data : NULL);
	}



	function _filter_by()
	{
		$filter = isset($_GET['view']) ? $_GET['view'] : '';
		return $filter;
	}



	function filter_by($filter_by) 
	{
		switch ($filter_by) {
			case 'pending':
			return array('status_id' => 5);
			break;

			case 'active':
			return array('status_id' => 6);
			break;

			case 'cancelled':
			return array('status_id' => 7);
			break;

			case 'suspended':
			return array('status_id' => 9);
			break;
			
			default:
			return array('status_id <>' => 8, 'status_id <>' => 2);
			break;
		}
	}





	function activate($id = null)
	{
		App::module_access('menu_accounts');
		if ($this->input->post()) {
				$result = ""; 

				$id = $this->input->post('id'); 		
				$account = Order::get_order($id); 		
		 
				$client = Client::view_by_id($account->client_id); 
				$user = User::view_user($client->primary_contact);
				$profile = User::profile_info($client->primary_contact);
 
				if ($this->input->post('server') != '') {
					$server = $this->db->where(array('id'=> $this->input->post('server')))->get('servers')->row();
				} 

				else{				
					$server = $this->db->where(array('selected'=> 1))->get('servers')->row();			
				}  
	
				$update = array(
					"status_id" => 6,
					"username" => $this->input->post('username'),
					"password" => $this->input->post('password')
				);

				if($server) {
					$update['server'] = $server->id;
				}


				$this->db->where('id', $id);  
				if($this->db->update('orders', $update)) {
					$result .= lang('account_activated')."<br>";

					$this->db->set('inv_deleted', "No"); 
					$this->db->where('inv_id', $account->invoice_id);  
					$this->db->update('invoices');
				} 
				

				if($this->input->post('send_details') == 'on') {
					Order::send_account_details($id);
				}

				if($this->input->post('create_controlpanel') == 'on') {
				
					$account = Order::get_order($id);
					$package = $this->db->where(array('item_id'=> $account->item_parent))->get('items_saved')->row();

					$details = (object) array(
						'user' => $user, 
						'profile' => $profile, 
						'client' => $client, 
						'account' => $account,
						'package' => $package,
						'server' => $server
					);

					$result .= modules::run($server->type.'/create_account', $details);  
				}
  
				$this->session->set_flashdata('response_status', 'info');
                $this->session->set_flashdata('message', $result);				
                redirect($_SERVER['HTTP_REFERER']);
		}

		else {
			$data['item'] = Order::get_order($id); 
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/activate', $data);
		}
	}





	function cancel($id = null)
	{
		App::module_access('menu_accounts');
		if ($this->input->post()) {

			if($this->input->post('credit_account') == 'on')
			{
				Invoice::credit_item($this->input->post('id'));
			}

			$result = "";			 
			$id = $this->input->post('id'); 		
			$account = Order::get_order($id); 		

			$this->db->set('status_id', 7); 
			$this->db->where('id', $id);  

			if($this->db->update('orders')) { 

				if(config_item('demo_mode') != 'TRUE') {

					$package = $this->db->where(array('item_id'=> $account->item_parent))->get('items_saved')->row();						
					
					if($this->input->post('delete_controlpanel') == 'on') {

						$server = Order::get_server($account->server);
						$client = Client::view_by_id($account->client_id); 
						$user = User::view_user($client->primary_contact);
						$details = (object) array('account' => $account, 'server' => $server, 'package' => $package, 'client' => $client, 'user' => $user);
						$result .= modules::run($server->type.'/terminate_account', $details);

						$this->db->set('server', ''); 
						$this->db->where('id', $id);  
						$this->db->update('orders');	

					}						
				}								
			}
						
			$this->db->set('inv_deleted', "Yes"); 
			$this->db->where('inv_id', $this->input->post('inv_id'));  
			$this->db->update('invoices');
			
			$this->session->set_flashdata('response_status', 'info');
			$this->session->set_flashdata('message', $result);
			redirect($_SERVER['HTTP_REFERER']);
		}

		else {
			$data['item'] = Order::get_order($id); 
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/cancel', $data);
		}
	}




	function delete($id = null)
	{
		App::module_access('menu_accounts');
		if ($this->input->post()) {

			if($this->input->post('credit_account') == 'on')
			{
				Invoice::credit_item($this->input->post('id'));
			}			

			$result = "";			
			$id = $this->input->post('id'); 		
			$account = Order::get_order($id); 
			$terminate = false; 

			if($this->db->where('order_id', $account->order_id)->get('orders')->num_rows() == 1) {
				$this->db->where('id', $id);  
				if($this->db->delete('orders')) { 
					$terminate = true; 
					Invoice::delete($account->invoice_id);
				}
			}

			else {
				$this->db->where('id', $id);  
				if($this->db->delete('orders')) { 
					$terminate = true;
				}
			} 
			
			
			if($terminate){
		
				if(config_item('demo_mode') != 'TRUE') {					
					
					$package = $this->db->where(array('item_id'=> $account->item_parent))->get('items_saved')->row();						
					
					if($this->input->post('delete_controlpanel') == 'on') {

						$server = Order::get_server($account->server);
						$client = Client::view_by_id($account->client_id); 
						$user = User::view_user($client->primary_contact);
						
						$details = (object) array('account' => $account, 'server' => $server, 'package' => $package, 'client' => $client, 'user' => $user);
						$result .= modules::run($server->type.'/terminate_account', $details);	
					}
				}									
			}
						
	
			$this->session->set_flashdata('response_status', 'info');
			$this->session->set_flashdata('message', $result);
			redirect('accounts');
		}

		else {
			$data['item'] = Order::get_order($id); 
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/delete', $data);
		}
	}



	function suspend($id = null)
	{
		App::module_access('menu_accounts');
		if ($this->input->post()) {
			$account = Order::get_order($this->input->post('id'));
			$reason = $this->input->post('reason');			
			$result = "";
			$this->db->set('status_id', 9); 
			$this->db->where('id', $this->input->post('id'));  
			if($this->db->update('orders')) {
				$result .=  $account->domain." has been suspended.<br>";

					$package = $this->db->where(array('item_id'=> $account->item_parent))->get('items_saved')->row();
					$client = Client::view_by_id($account->client_id); 
					$user = User::view_user($client->primary_contact);
					$profile = User::profile_info($client->primary_contact);
					$server = $this->db->where(array('id'=> $account->server))->get('servers')->row();

					$details = (object) array(
						'user' => $user, 
						'profile' => $profile, 
						'client' => $client, 
						'account' => $account,
						'package' => $package,
						'server' => $server,
						'reason' => $reason
					); 

				$result .= modules::run($account->server_type.'/suspend_account', $details);   
			} 

			$this->session->set_flashdata('response_status', 'warning');
			$this->session->set_flashdata('message', $result);
			redirect($_SERVER['HTTP_REFERER']);
		}

		else {
			$data['id'] = $id; 
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/suspend', $data);
		}
	}




	function unsuspend($id = null)
	{
		App::module_access('menu_accounts');
		if ($this->input->post()) {
			$account = Order::get_order($this->input->post('id')); 
			$this->load->library('Cpanel', trim($account->authkey));
			$result = "";
			$this->db->set('status_id', 6); 
			$this->db->where('id', $this->input->post('id'));  
			if($this->db->update('orders')) {
				$result .=  $account->domain." has been unsuspended.<br>";	

				$package = $this->db->where(array('item_id'=> $account->item_parent))->get('items_saved')->row();
				$client = Client::view_by_id($account->client_id); 
				$user = User::view_user($client->primary_contact);
				$profile = User::profile_info($client->primary_contact);
				$server = Order::get_server($account->server);
				$details = (object) array(
					'user' => $user, 
					'profile' => $profile, 
					'client' => $client, 
					'account' => $account,
					'package' => $package,
					'server' => $server
				); 

				 $result .= modules::run($account->server_type.'/unsuspend_account', $details); 

		  
			} 

			$this->session->set_flashdata('response_status', 'warning');
			$this->session->set_flashdata('message', $result);
			redirect($_SERVER['HTTP_REFERER']);
		}

		else {
			$data['id'] = $id; 
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/unsuspend', $data);
		}
	}
 


	function change()
	{
		$id = $_GET['plan'];
		$current = Item::view_item($id);
		$parent = $current->parent;
		$this->session->set_userdata('account_id', $_GET['account']);
		$this->db->select('items_saved.item_id, item_name, item_features, monthly, quarterly, semi_annually, annually'); 
		$this->db->from('items_saved');  
		$this->db->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER');
		$this->db->join('categories','categories.id = item_pricing.category','LEFT');
		$this->db->where('deleted', 'No');
		$this->db->where('display', 'Yes');  
		$this->db->where('category', $current->category); 
		$this->db->where('items_saved.item_id <>', $id); 
		$data['packages'] = $this->db->get()->result(); 
		$this->load->view('modal/change', $data);		
	}



	function show_options($id)
	{	 
		$this->session->set_userdata('item_id', $id);
		$data['current'] =  $this->session->userdata('account_id'); 
		$data['options'] =  $id;
		$this->template->title(lang('review').' - '.config_item('company_name'));
		$data['page'] = lang('options');			
		$this->template
		->set_layout('users')
		->build('options', $data);	
	}




	function manage ($id = null)
	{ 
		if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) {

			if($this->input->post()) { 

				$details = $this->input->post();
				$details['processed'] = explode(' ', $this->input->post('date'))[0];
				
				if(App::update('orders', array('id' => $this->input->post('id')), $details))
				{
					Applib::go_to('accounts/account/'.$this->input->post('id'), 'success', lang('account_updated'));
				}
				else {
					redirect($_SERVER['HTTP_REFERER']);
				}
			}

			else {				
				$this->template->title(lang('account').' - '.config_item('company_name'));
				$data['account'] = array();
				$data['account_details'] = true;
				$data['page'] = lang('account');
				$data['datepicker'] = true;
				$data['form'] = true;
				$data['id'] = $id;			
				$this->template
				->set_layout('users')
				->build('manage', $data);	
			}
		}
		else {
			redirect(base_url()."accounts");
		}
	}



	function account ($id)
	{	
		$order = Order::get_order($id);
		$client = Client::get_by_user(User::get_id());
		if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts') || (isset($client) && $client->co_id == $order->client_id )){
			$this->template->title(lang('account').' - '.config_item('company_name'));
			$data['account_details'] = true;
			$data['page'] = lang('account');
			$data['id'] = $id;			
			$this->template
			->set_layout('users')
			->build('account', $data);	
		}
		else {
			redirect(base_url()."accounts");
		}
	}



	function review()
	{	 	 
		$data['renewal'] = $this->input->post('renewal');
		$data['renewal_date'] = $this->input->post('next_due');
		$data['payable'] = $this->input->post('payable');
		$data['amount'] = $this->input->post('amount'); 
		$data['item'] = $this->session->userdata('item_id');
		$data['account'] = $this->session->userdata('account_id');

		$upgrade = array(
			'renewal' => $data['renewal'],
			'renewal_date' => $data['renewal_date'],  
			'account' => $data['account'],
			'amount' => $data['amount'],
			'item' => $this->session->userdata('item_id'),
			'payable' => $data['payable']
		);
 
		$this->session->set_userdata('upgrade', $upgrade);
		$this->template->title(lang('review').' - '.config_item('company_name'));

		$data['page'] = lang('review');			
		$this->template
		->set_layout('users')
		->build('review', $data);
	}



	function view_logins($id = null)
	{ 
		$data['item'] = Order::get_order($id); 
		$data['servers'] = $this->db->get('servers')->result();
		$this->load->view('modal/view_logins', $data);
	}



	function change_password($id = null)
	{Applib::is_demo();
		App::module_access('menu_accounts');
		if ($this->input->post()) {
			$account = Order::get_order($this->input->post('id'));
			$password = $this->input->post('password');

			$update = array(
				"password" => $password
			); 
			
			$this->db->where('id', $this->input->post('id'));  
			if($this->db->update('orders', $update)) {

				$account = Order::get_order($this->input->post('id'));
				$server = Order::get_server($account->server);
				$client = Client::view_by_id($account->client_id); 
				$user = User::view_user($client->primary_contact);
				
				$details = (object) array( 
					'account' => $account, 
					'server' => $server,
					'user' => $user
				); 

				 $result = modules::run($account->server_type.'/change_password', $details);  
			} 

			$this->session->set_flashdata('response_status', 'info');
			$this->session->set_flashdata('message', $result);
			redirect($_SERVER['HTTP_REFERER']);
		}

		else {
			$data['id'] = $id; 
			$this->load->view('modal/change_password', $data);
		}
	}




	function login($id) 
	{	Applib::is_demo();
		$order = Order::get_order($id);
		$client = Client::get_by_user(User::get_id());
		if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts') || (isset($client) && $client->co_id == $order->client_id ))
		{
			$account = Order::get_order($id);
			$server = Order::get_server($account->server);
			$params = (object) array('account' => $account, 'server' => $server);

			modules::run($server->type.'/client_login', $params);  
		}
		
		else {
			redirect(base_url());
		}
	}



	public function import_accounts()
	{
        $count = 0;

		if($this->input->post()) 
		{
           $array = array();
           $list = array();       

		   foreach($this->input->post() as $k => $r)
           {
			   if($k != 'package')
			   {
					$array[] = $k;
			   }                
           }

           $accounts = $this->session->userdata('import_accounts');
           foreach($accounts as $k => $r)
           {
                if(in_array($r->id, $array))
                {
                    $list[] = $r;
                }
           }
 

			if(count($list) > 0) 
			{
                foreach($list as $client) 
	  
                {   
					$package = $this->input->post('package');
					if($package[$client->id] > 0)
					{  
						if($this->db->where('co_id', $client->user_id)->where('imported', 1)->get('companies')->num_rows() > 0)
						{ 
							$item = $this->db->where('items_saved.item_id', $package[$client->id])->join('item_pricing', 'item_pricing.item_id = items_saved.item_id')->get('items_saved')->row();
				
							switch($client->status)
							{
								case 'Active' : $status = 6; 
								break;

								case 'Cancelled' : $status = 7; 
								break;

								case 'Terminated' : $status = 8; 
								break;

								case 'Pending' : $status = 5; 
								break;

								case 'Suspended' : $status = 9; 
								break;
							}

							$time = time();
							$date = date('Y-m-d H:i:s', strtotime('first day of last month'));

							$interval = strtolower($client->renewal);

							if($interval == 'semianually')
							{
								$interval == 'semi_anually';
							}

							$items = array(
								'invoice_id' 	=> 0,
								'item_name'		=> $client->domain,
								'item_desc'		=> '-',
								'unit_cost'		=> $client->recurring_amount,
								'item_order'	=> 1,
								'item_tax_rate'	=> 0,
								'item_tax_total'=> 0,
								'quantity'		=> 1,
								'total_cost'	=> $client->recurring_amount
								);
								
							if($item_id = App::save_data('items', $items))

							{
								$order = array(
									'client_id' 	=> $client->user_id,
									'invoice_id'    => 0,
									'date'          => $date,  
									'item'		    => $item_id,
									'domain'        => $client->domain,
									'username'      => $client->username,
									'password'      => !empty($client->password) ? $client->password : create_password(),
									'item_parent'   => $item->item_id,
									'type'		    => 'hosting',
									'process_id'    => $time,
									'order_id'      => $time, 
									'fee'           => $client->recurring_amount,
									'processed'     => $date, 
									'renewal_date'  => !empty($client->due_date) ? $client->due_date : '0000-00-00',
									'status_id'     => $status, 
									'server'		=> $item->server,
									'renewal'       => $interval
								);    
								
								if($order_id = App::save_data('orders', $order)) 
								{
									$count++;
								}
							}							
						}  
					}                    
				}	
			} 
				 
			$this->session->unset_userdata('import');		 		

			$this->session->set_flashdata('response_status', 'info');
			$this->session->set_flashdata('message', "Created ".$count." accounts");			
			if($count == 0)	
            {
                redirect($_SERVER['HTTP_REFERER']);
            }
            else
            {
                redirect('accounts'); 
            }
		}
		else 
		{
 			$this->template->title(lang('import'));
			$data['page'] = lang('accounts');	
			$data['datatables'] = TRUE;  
			$this->template
			->set_layout('users')
			->build('import',isset($data) ? $data : NULL); 
		}
	}



    function import()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('import'));
        $data['page'] = lang('accounts');
        $this->template
        ->set_layout('users')
        ->build('import',isset($data) ? $data : NULL); 
    }


	
	function upload()
	{	 
		if($this->input->post()) {

			$this->load->library('excel');
			ob_start();
			$file = $_FILES["import"]["tmp_name"];
			if (!empty($file)) {
				$valid = false;
				$types = array('Excel2007', 'Excel5', 'CSV');
				foreach ($types as $type) {
					$reader = PHPExcel_IOFactory::createReader($type);
					if ($reader->canRead($file)) {
						$valid = true;
					}
				}
				if (!empty($valid)) {
					try {
						$objPHPExcel = PHPExcel_IOFactory::load($file);
					} catch (Exception $e) {
						$this->session->set_flashdata('response_status', 'warning');
						$this->session->set_flashdata('message', "Error loading file:" . $e->getMessage());			
						redirect($_SERVER['HTTP_REFERER']);						
					}

					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);  
					$accounts = array();
					$list = array();

					for ($x = 3; $x <= count($sheetData); $x++) {
						if($this->db->where('domain', $sheetData[$x]["G"])->where('username', $sheetData[$x]["P"])->where('client_id', $sheetData[$x]["B"])->where('type', 'hosting')->get('orders')->num_rows() == 0)
						{
							$domain = array();
							$domain['id'] = $sheetData[$x]["A"];
							$domain['user_id'] = $sheetData[$x]["B"];
							$domain['domain'] = $sheetData[$x]["G"];
							$domain['first_payment'] = $sheetData[$x]["J"];						
							$domain['recurring_amount'] = $sheetData[$x]["K"];                        
							$domain['renewal'] = $sheetData[$x]["L"];
							$domain['due_date'] = $sheetData[$x]["M"];
							$domain['username'] = $sheetData[$x]["P"]; 
							$domain['password'] = $sheetData[$x]["Q"];
							$domain['status'] = $sheetData[$x]["O"];
							$domain['notes'] = $sheetData[$x]["R"];
							$domain['reason'] = $sheetData[$x]["T"];
							$accounts[] = (object) $domain;
						}							
					}	
					
					$this->session->set_userdata('import_accounts', $accounts);

				} else {
					$this->session->set_flashdata('response_status', 'warning');
					$this->session->set_flashdata('message', lang('not_csv'));			
					redirect($_SERVER['HTTP_REFERER']);	
				}
			} else {
				$this->session->set_flashdata('response_status', 'warning');
				$this->session->set_flashdata('message', lang('no_csv'));			
				redirect($_SERVER['HTTP_REFERER']);	
			}			
            redirect('accounts/import');	
			}

		else {  
            $this->load->module('layouts');
            $this->load->library('template');         
			$this->template->title(lang('import'));
			$data['page'] = lang('accounts');	 
			$this->template
			->set_layout('users')
			->build('upload',isset($data) ? $data : NULL);
		}
	}
 
 

}