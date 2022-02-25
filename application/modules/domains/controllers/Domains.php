<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Domains extends Hosting_Billing {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');
		$this->load->library('template'); 
		$this->filter_by = $this->_filter_by();

		$lang = config_item('default_language');
		if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
		if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
		$this->lang->load('hd', $lang);
	}	



	function index() 
	{	 
		$type = "(orders.type ='domain' OR orders.type ='domain_only')";
		$array = $this->filter_by($this->filter_by);	 
		
		if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')){
			$data['domains'] = Domain::by_where($array, $type);
		}
		else
		{
			$array['client_id'] = User::profile_info(User::get_id())->company;
			$data['domains'] = Domain::by_where($array, $type);				
		}

		$this->template->title(lang('domains').' - '.config_item('company_name'));
		$data['page'] = lang('domains');
		$data['datatables'] = TRUE;
		
		$this->template
		->set_layout('users')
		->build('domains',isset($data) ? $data : NULL);		
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
			
			default:
			return array('status_id <>' => 8, 'status_id <>' => 2);
			break;
		}
	}





	function activate($id = null)
	{ 
		if ($this->input->post()) {
				
				$domain = $this->input->post('domain');
				$order_id = $this->input->post('id');
					if($this->input->post('activate_domain') == 'on' && $this->input->post('domain_status') != 6) {
						$update = array('status_id'=> 6, 'authcode' => $this->input->post('authcode'), 'registrar' => $this->input->post('registrar')); 
						$this->db->where('id', $order_id);  
						if($this->db->update('orders', $update)){
							$result = "Domain: " .$domain." activated! <br/>";
						}						
					  
						if($this->input->post('registrar') != '') { 
							$account = Order::get_order($order_id); 

							$action = '/register_domain';	
							
							$nameservers = Order::get_nameservers($order_id);	
 
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
					 

							$action = '/register_domain';							
							if($account->item_name == lang('domain_renewal')) {
								$action = '/renew_domain';							
							}
							if($account->item_name == lang('domain_transfer')) {
								$action = '/transfer_domain';
							}

							$result .= modules::run($account->registrar.$action, $order_id, $nameservers);	
						 }
					}				
			
			$this->session->set_flashdata('response_status', 'success');
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
 
				$domain = $this->input->post('domain');
				$order_id = $this->input->post('id'); 

				if($this->input->post('cancel_domain') == 'on') {
						$this->db->set('status_id', 7); 
						$this->db->where('id', $order_id);  
						if($this->db->update('orders')){
							$result = "Domain: " .$domain." cancelled!";

							if($this->input->post('order') == 'domain_only') {
								$this->db->set('inv_deleted', "Yes"); 
								$this->db->where('inv_id', $this->input->post('inv_id'));  
								$this->db->update('invoices');
							}							
						}
					} 
		 
			$this->session->set_flashdata('response_status', 'success');
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
		App::module_access('menu_domains');
		if ($this->input->post()) {
				if($this->input->post('credit_account') == 'on')
				{
					Invoice::credit_item($this->input->post('id'));
				}
 
			$domain = $this->input->post('domain');
			$order_id = $this->input->post('id'); 

			if($this->input->post('delete_domain') == 'on') {
					$this->db->set('status_id', 8); 
					$this->db->where('id', $order_id);  
					if($this->db->update('orders')){
						$result = "Domain: " .$domain." deleted!";

						if($this->input->post('order') == 'domain_only') {
							$this->db->set('inv_deleted', "Yes"); 
							$this->db->where('inv_id', $this->input->post('inv_id'));  
							$this->db->update('invoices');
						}
					}
				} 
	 
		$this->session->set_flashdata('response_status', 'success');
		$this->session->set_flashdata('message', $result);
		redirect($_SERVER['HTTP_REFERER']);
	}

		else {
			$data['item'] = Order::get_order($id); 
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/delete', $data);
		}
	}



	function proccess() 
	{

		$registrar = $this->uri->segment(3);  
		$action = "/".$this->uri->segment(4);  
		$order = $this->uri->segment(5);  

		$nameservers = Order::get_nameservers($order);	
 
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

		$process = modules::run($registrar.$action, $order, $nameservers);
		$data = array(
			'user' => User::get_id(),
			'module' => 'accounts',
			'module_field_id' => $order,
			'activity' => $process,
			'icon' => 'fa-globe',
			'value1' => $registrar,
			'value2' => ''
		);
			App::Log($data);  

		$this->session->set_flashdata('response_status', 'info');
		$this->session->set_flashdata('message', $process);				
		redirect($_SERVER['HTTP_REFERER']);
	}



	function domain_pricing() {
		return Item::get_domains();
	} 

 

	function check_availability ()
	{  
		$domain = $this->input->post('domain', true);
		$type = $this->input->post('type', true);

		$domain_name = explode(".", $domain, 2);
		$domain_checker = config_item('domain_checker') . '/check_domain';
	 
		$this->db->select('item_name, registration, renewal, transfer, max_years'); 
		$this->db->from('items_saved');  
		$this->db->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER');
        $this->db->where('item_name', $domain_name[1]); 
        $ext = $this->db->get()->row();

		if($type == lang('domain_registration')) {
			$unavailable = array('result' => "<div class='alert alert-danger'><strong>".$domain."</strong> ".lang('is_registered')."</div>"); 		
			
			if($ext->max_years > 1)
			{
				$options = "<div class='alert alert-success'><strong>".$domain."</strong> ".lang('is_available')." &nbsp; &nbsp; <select id='domain_price'>"; 
				$i = 1;
				while ($i < $ext->max_years)
				{ $amount = $ext->registration * $i; 

					$options .= '<option value="'.$amount.'">'. Applib::format_currency(config_item('default_currency'), $amount) .' - ' . $i . ' ' . lang('years') .'</option>';
					$i++;
				 } 	
				 
				$options .= '</select>';
				$available = array('domain' => $domain, 
				'price' => $ext->registration, 
				'result' => $options . "&nbsp; &nbsp; <span id='add_available' class='btn btn-success' onClick='$(this).continueOrder()'>".lang('add_to_cart')."</span></div>"); 

			}
			else
			{
				$available = array('domain' => $domain, 
				'price' => $ext->registration, 
				'result' => "<div class='alert alert-success'><strong>".$domain."</strong> ".lang('is_available')." &nbsp; | &nbsp; ".
				Applib::format_currency(config_item('default_currency'), $ext->registration)." ".lang('per_year')." &nbsp; | &nbsp; <span id='add_available' class='btn btn-success' onClick='$(this).continueOrder()'>".lang('add_to_cart')."</span></div>"); 
			}

			$error = array('result' => "<div class='alert alert-danger'><strong>".$domain."</strong> ".lang('error_checking')."</div>");
			
			if(config_item('domain_checker') != 'default') {
				$result = modules::run($domain_checker, $domain_name[0], $domain_name[1]);
					
					switch($result) {
						case 1: $this->output->set_content_type('application/json')->set_output(json_encode($available)); 
								break;
						case 0:	$this->output->set_content_type('application/json')->set_output(json_encode($unavailable));
								break;
						default :$this->output->set_content_type('application/json')->set_output(json_encode($error));
					}
				}

				else {
					if (gethostbyname($domain) != $domain) {				
						$this->output->set_content_type('application/json')->set_output(json_encode($unavailable));  
					}

					else {
							$this->output->set_content_type('application/json')->set_output(json_encode($available)); 
					}
				} 
 			
		}


		if($type == lang('domain_transfer')) {
			$unavailable = array('result' => "<div class='alert alert-danger'><strong>".$domain."</strong> ".lang('is_not_registered')."</div>"); 		
			$available = array('domain' => $domain, 'price' => $ext->transfer, 'result' => "<div id='add_available' class='alert alert-success'><strong>".$domain."</strong> ".lang('is_available_transfer')." &nbsp; | &nbsp; ".Applib::format_currency(config_item('default_currency'), $ext->transfer)." ".lang('per_year')." &nbsp; | &nbsp; <span class='btn btn-success' onClick='$(this).continueOrder()'>".lang('add_to_cart')."</span></div>"); 
			$error = array('result' => "<div class='alert alert-danger'><strong>".$domain."</strong> ".lang('error_checking')."</div>"); 

			if(config_item('domain_checker') != 'default') {
				$result = modules::run($domain_checker, $domain_name[0], $domain_name[1]);	

					switch($result) {
						case 0: $this->output->set_content_type('application/json')->set_output(json_encode($available)); 
								break;
						case 1:	$this->output->set_content_type('application/json')->set_output(json_encode($unavailable));
								break;
						default :$this->output->set_content_type('application/json')->set_output(json_encode($error));
					}
				}

				else {
					if (gethostbyname($domain) != $domain) {				
						$this->output->set_content_type('application/json')->set_output(json_encode($available));  
					}
					else {
							$this->output->set_content_type('application/json')->set_output(json_encode($unavailable)); 
					}
				}  
		 			
		}
	}




	function manage ($id = null)
	{ 
		if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts')) {

			if($this->input->post()) { 
				
				if(App::update('orders', array('id' => $this->input->post('id')), $this->input->post()))
				{
					Applib::go_to('domains/domain/'.$this->input->post('id'), 'success', lang('domain_updated'));
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
			redirect(base_url()."domains");
		}
	}



	function domain ($id) 
	{	
		$order = Order::get_order($id);
		$client = Client::get_by_user(User::get_id());
		if(User::is_admin() || User::perm_allowed(User::get_id(),'manage_accounts') || (isset($client) && $client->co_id == $order->client_id )){
			$data['page'] = lang('manage_domain');
			$data['id'] = $id;		
			$this->template
			->set_layout('users')
			->build('domain',isset($data) ? $data : NULL);
		}
		else {
			redirect(base_url()."domains");
		}
	}



	function manage_nameservers ($id) 
	{	 		
		$data['page'] = lang('update_nameservers');
		$data['id'] = $id;	 
		$this->load->view('domains/modal/nameservers', $data);
	}


	function suspend ($id) 
	{	 		
		$data['page'] = lang('suspend');
		$data['id'] = $id;	 
		$this->load->view('modal/suspend', $data);
	}



	public function import_domains()
	{
        $count = 0;

		if($this->input->post()) 
		{
           $array = array();
           $list = array();

           foreach($this->input->post() as $k => $r)
           {
			   if($k != 'registrar')
			   {
					$array[] = $k;
			   }                
           }

           $accounts = $this->session->userdata('import_domains');
           foreach($accounts as $k => $r)
           {
                if(in_array($r->id, $array))
                {
                    $list[] = $r;
                }
           }
 
			if(count($list) > 0) {

                foreach($list as $client) 
                {  
					if($this->db->where('co_id', $client->user_id)->where('imported', 1)->get('companies')->num_rows() > 0)
                    {						
							$tld = explode('.', $client->domain, 2);
							$ext = $tld[1];  
							$item = $this->db->where('item_name', $ext)->join('item_pricing', 'item_pricing.item_id = items_saved.item_id')->get('items_saved')->row();

							if($item)
							{
							
							$items = array(
								'invoice_id' 	=> 0,
								'item_name'		=> "Domain " . $client->type,
								'item_desc'		=> '-',
								'unit_cost'		=> $item->renewal,
								'item_order'	=> 1,
								'item_tax_rate'	=> 0,
								'item_tax_total'=> 0,
								'quantity'		=> 1,
								'total_cost'	=> $item->renewal
								);
								
							$item_id = App::save_data('items', $items);
							$time = strtotime($client->registration);

							switch($client->status)
							{
								case 'Active' : $status = 6; break;
								case 'Cancelled' : $status = 7; break;
								case 'Expired' : $status = 11; break;
								case 'Pending' : $status = 5; break;
								case 'Pending Transfer' : $status = 5; break;
								case 'Terminated' : $status = 8; break;
							}

							$order = array(
								'client_id' 	=> $client->user_id,
								'invoice_id'    => 0,
								'date'          => $client->registration . " " . date('H:i:s'),  
								'item'		    => $item_id,
								'domain'        => $client->domain,
								'item_parent'   => $item->item_id,
								'type'		    => 'domain',
								'process_id'    => $time,
								'order_id'      => $time,
								'registrar' 	=> $this->input->post('registrar'),
								'fee'           => 0,
								'processed'     => $client->registration, 
								'renewal_date'  => $client->expires,
								'years'			=> $client->period,
								'status_id'     => $status, 
								'renewal'       => 'annually'
							);    
							
							if($order_id = App::save_data('orders', $order)) 
							{
								$count++;
							}
						}
					}                      
				}	
			} 
				 
			$this->session->unset_userdata('import');		 		

			$this->session->set_flashdata('response_status', 'info');
			$this->session->set_flashdata('message', "Created ".$count." domains");			
			if($count == 0)	
            {
                redirect($_SERVER['HTTP_REFERER']);
            }
            else
            {
                redirect('domains'); 
            }
		}
		else 
		{
 			$this->template->title(lang('import'));
			$data['page'] = lang('domains');	
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
        $data['page'] = lang('domains');
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

					$domains = array();
					$list = array();
					for ($x = 3; $x <= count($sheetData); $x++) {	
					 
						if($this->db->where('domain', $sheetData[$x]["F"])->where('client_id', $sheetData[$x]["B"])->where('type <>', 'hosting')->get('orders')->num_rows() == 0)			
						{	
							$domain = array();					 
							$domain['id'] = $sheetData[$x]["A"];
							$domain['user_id'] = $sheetData[$x]["B"];
							$domain['type'] = $sheetData[$x]["E"];
							$domain['domain'] = $sheetData[$x]["F"]; 
							$domain['period'] = $sheetData[$x]["I"];
							$domain['registration'] = $sheetData[$x]["J"];
							$domain['expires'] = $sheetData[$x]["K"]; 
							$domain['status'] = $sheetData[$x]["O"];
							$domain['notes'] = $sheetData[$x]["P"];
							$domains[] = (object) $domain;		
						}				
					}	
					
					$this->session->set_userdata('import_domains', $domains);

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
            redirect('domains/import');	
			}

		else {  
            $this->load->module('layouts');
            $this->load->library('template');         
			$this->template->title(lang('import'));
			$data['page'] = lang('domains');	 
			$this->template
			->set_layout('users')
			->build('upload',isset($data) ? $data : NULL);
		}
	}


}


////end 