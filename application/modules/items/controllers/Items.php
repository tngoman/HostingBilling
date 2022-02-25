<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends Hosting_Billing 
{

	function __construct()
	{
		parent::__construct(); 
		$this->load->module('layouts'); 
		$this->load->library(array('form_validation','settings','template')); 
		$this->load->helper('form'); 
		$this->filter_by = $this->_filter_by();	
		$this->applib->set_locale(); 
	}


	function index()
	{
		$this->list_items();
		$this->can_access();
	}


	function can_access() {
		if(!User::is_admin() && !User::is_staff()) {
			redirect('clients');
		}
	}


	function list_items()
	{
		$this->can_access();
		$this->template->title(lang('item_lookups').' - '.config_item('company_name'));
		$data['page'] = lang('items');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$array = $this->filter_by($this->filter_by); 
		$data['invoice_items'] = Item::list_items($array);
		$this->template
		->set_layout('users')
		->build('items',isset($data) ? $data : NULL);
	}



	function categories()
	{
		if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	
		$this->template->title(lang('categories').' - '.config_item('company_name'));
		$data['page'] = lang('categories'); 
		$this->template
		->set_layout('users')
		->build('categories',isset($data) ? $data : NULL);
	}



	function _filter_by()
	{
		$filter = isset($_GET['view']) ? $_GET['view'] : '';
		return $filter;
	}



	function filter_by($filter_by) 
	{
		switch ($filter_by) {
			case 'domains':
			return array('hd_categories.parent' => 8, 'deleted' => 'No' );
			break;

			case 'hosting':
			return array('hd_categories.parent' => 9, 'deleted' => 'No');
			break;

			case 'service':
			return array('hd_categories.parent' => 10, 'deleted' => 'No');
			break;
			
			default:
			return array('deleted' => 'No');
			break;
		}
	}


	function add_hosting()
	{
		$this->add_item('hosting');
	}

	function add_domain()
	{
		$this->add_item('domain');
	}

	function add_service()
	{
		$this->add_item('service');
	}

	function add_addon()
	{
		$this->add_item('addon');
	}

	function add_item($item = null)
	{	App::module_access('menu_items');
		if ($this->input->post()) {		

			Applib::is_demo();

			if($this->input->post('unit_cost'))
			{
				$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			}

			else
			{
				$sub_total = 0;
			}
			
			$item_tax_rate = $this->input->post('item_tax_rate');
			$item_tax_total = Applib::format_deci(($item_tax_rate / 100) *  $sub_total);			

    		$data = array(
       		 'item_tax_rate' => $item_tax_rate,
			 'item_tax_total' => $item_tax_total,	
			 'quantity' => $this->input->post('quantity'),
			 'total_cost' => Applib::format_deci($sub_total + $item_tax_total),
			 'item_name' => $this->input->post('item_name'),  
			 'display' => ($this->input->post('display') == 'on') ? 'Yes' : 'No',
			 'item_features' => $this->input->post('item_features', false),
			 'unit_cost' => $this->input->post('unit_cost'),
			 'order_by' => $this->input->post('order_by') 
			);

			if($item == 'addon')
			{
				$data['apply_to'] = serialize($this->input->post('apply_to'));
				$data['item_desc'] = $this->input->post('item_desc', TRUE);
				$data['create_account'] = 'No';				
				$data['addon'] = 1;
				$category = 5;
			}

			else
			{
				$category = $this->input->post('category');
			}	
			
			if($this->input->post('server')) {
				$data['server'] = $this->input->post('server');
			 }

			if($this->input->post('max_years')) {
				$data['max_years'] = $this->input->post('max_years');
			 }
			 if($this->input->post('setup_fee')) {
				$data['setup_fee'] = $this->input->post('setup_fee');
			 }
			 if($this->input->post('require_domain')) {
				$data['require_domain'] = ($this->input->post('require_domain') == 'on') ? 'Yes' : 'No';
			 }
			 if($this->input->post('allow_upgrade')) {
				$data['allow_upgrade'] = ($this->input->post('allow_upgrade') == 'on') ? 'Yes' : 'No';
			 }
			 if($this->input->post('price_change')) {
				$data['price_change'] = ($this->input->post('price_change') == 'on') ? 'Yes' : 'No';
			 }
			 if($this->input->post('reseller_package')) {
				$data['reseller_package'] = ($this->input->post('reseller_package') == 'on') ? 'Yes' : 'No';
			 }
			 if($this->input->post('default_registrar')) {
				$data['default_registrar'] = $this->input->post('default_registrar');
			 }
			 if($this->input->post('create_account')) {
				$data['create_account'] = ($this->input->post('create_account') == 'on') ? 'Yes' : 'No';
			 }

			 if($item == 'hosting') 
			 {
				$data['create_account'] = 'Yes';
			 }	
			  

			if($this->db->insert('items_saved',$data)) {
				$id = $this->db->insert_id();
			
				$pricing = array(
					'item_id' => $id,
					'category' => $category,
					'monthly' => $this->input->post('monthly'),	
					'quarterly' => $this->input->post('quarterly'),
					'semi_annually' => $this->input->post('semi_annually'),
					'annually' => $this->input->post('annually'),
					'biennially' => $this->input->post('biennially'),
					'triennially' => $this->input->post('triennially'),
					'registration' => $this->input->post('registration'),
					'transfer' => $this->input->post('transfer'),
					'renewal' => $this->input->post('renewal')
				);
				

				if($this->input->post('monthly_payments')) {
					$pricing['monthly_payments'] = $this->input->post('monthly_payments');
				 }
				 if($this->input->post('quarterly_payments')) {
					$pricing['quarterly_payments'] = $this->input->post('quarterly_payments');
				 }
				 if($this->input->post('semi_annually_payments')) {
					$pricing['semi_annually_payments'] = $this->input->post('semi_annually_payments');
				 }
				 if($this->input->post('annually_payments')) {
					$pricing['annually_payments'] = $this->input->post('annually_payments');
				 }
				 if($this->input->post('biennially_payments')) {
					$pricing['biennially_payments'] = $this->input->post('biennially_payments');
				 }
				 if($this->input->post('triennially_payments')) {
					$pricing['triennially_payments'] = $this->input->post('triennially_payments');
				 }

				$this->db->insert('item_pricing',$pricing);
			} 
				 
				$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', lang('item_added_successfully'));
				redirect($this->input->post('r_url'));
		}
		else
		{
			$data['form'] = TRUE;
			$data['categories'] = App::get_by_where('categories',array('parent >'=>'7'));
			$data['rates'] = App::get_by_where('tax_rates',array());
			$data['servers'] = $this->db->get('servers')->result();
			$this->load->view('modal/add_'.$item ,$data);
		}
	}



	function edit_hosting($id = NULL)
	{
		$this->edit_item($id, 'hosting');
	}

	function edit_domain($id = NULL)
	{
		$this->edit_item($id, 'domain');
	}

	function edit_service($id = NULL)
	{
		$this->edit_item($id, 'service');
	}

	function edit_addon($id = NULL)
	{
		$this->edit_item($id, 'addon');
	}
	 

	function edit_item($id = NULL, $item = NULL)
	{	App::module_access('menu_items');
		if ($this->input->post()) {

			Applib::is_demo();
		
			if($this->input->post('unit_cost'))
			{
				$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			}

			else
			{
				$sub_total = 0;
			}
			
			$item_tax_rate = $this->input->post('item_tax_rate');
			$item_tax_total = Applib::format_deci(($item_tax_rate / 100) *  $sub_total);		

    		$data = array(
       		 'item_tax_rate' => $item_tax_rate,
			 'item_tax_total' => $item_tax_total,	
			 'quantity' => $this->input->post('quantity'),
			 'total_cost' => Applib::format_deci($sub_total + $item_tax_total),
			 'item_name' => $this->input->post('item_name'),	
			 'default_registrar' => $this->input->post('default_registrar'),
			 'display' => ($this->input->post('display') == 'on') ? 'Yes' : 'No',
			 'item_features' => $this->input->post('item_features', false),
			 'unit_cost' => $this->input->post('unit_cost'),
			 'order_by' => $this->input->post('order_by') 
			);

			if($this->input->post('max_years')) {
				$data['max_years'] = $this->input->post('max_years');
			 }
			if($this->input->post('setup_fee')) {
				$data['setup_fee'] = $this->input->post('setup_fee');
			 }
			 if($this->input->post('require_domain')) {
				$data['require_domain'] = ($this->input->post('require_domain') == 'on') ? 'Yes' : 'No';
			 }
			 if($this->input->post('allow_upgrade')) {
				$data['allow_upgrade'] = ($this->input->post('allow_upgrade') == 'on') ? 'Yes' : 'No';
			 }
			 if($this->input->post('price_change')) {
				$data['price_change'] = ($this->input->post('price_change') == 'on') ? 'Yes' : 'No';
			 }
			 if($this->input->post('reseller_package')) {
				$data['reseller_package'] = ($this->input->post('reseller_package') == 'on') ? 'Yes' : 'No';
			 } 
			 if($this->input->post('default_registrar')) {
				$data['default_registrar'] = $this->input->post('default_registrar');
			 }
			 if($this->input->post('create_account')) {
				$data['create_account'] = ($this->input->post('create_account') == 'on') ? 'Yes' : 'No';
			 }

			 if($item == 'addon')
			{
				$data['apply_to'] = serialize($this->input->post('apply_to'));
				$data['item_desc'] = $this->input->post('item_desc', TRUE);
				$data['create_account'] = 'No';				
				$data['addon'] = 1;
				$category = 5;
			}

			else
			{
				$category = $this->input->post('category');
			} 
			 
			$this->db->where('item_id', $this->input->post('item_id'));
			if($this->db->update('items_saved', $data)) {
				
				$pricing = array(
					'category' => $category,
					'monthly' => $this->input->post('monthly'),	
					'quarterly' => $this->input->post('quarterly'),
					'semi_annually' => $this->input->post('semi_annually'),
					'annually' => $this->input->post('annually'),
					'biennially' => $this->input->post('biennially'),
					'triennially' => $this->input->post('triennially'),
					'registration' => $this->input->post('registration'),
					'transfer' => $this->input->post('transfer'),
					'renewal' => $this->input->post('renewal')
				);

				if($this->input->post('monthly_payments')) {
					$pricing['monthly_payments'] = $this->input->post('monthly_payments');
				 }
				 if($this->input->post('quarterly_payments')) {
					$pricing['quarterly_payments'] = $this->input->post('quarterly_payments');
				 }
				 if($this->input->post('semi_annually_payments')) {
					$pricing['semi_annually_payments'] = $this->input->post('semi_annually_payments');
				 }
				 if($this->input->post('annually_payments')) {
					$pricing['annually_payments'] = $this->input->post('annually_payments');
				 }
				 if($this->input->post('biennially_payments')) {
					$pricing['biennially_payments'] = $this->input->post('biennially_payments');
				 }
				 if($this->input->post('triennially_payments')) {
					$pricing['triennially_payments'] = $this->input->post('triennially_payments');
				 }

				$this->db->where('item_id', $this->input->post('item_id'));
				$this->db->update('item_pricing', $pricing);
			  
			} 

		  		$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', lang('operation_successful'));
				redirect($this->input->post('r_url'));
		}
		else
		{	
			$data['categories'] = App::get_by_where('categories',array('parent >'=>'7'));
			$data['rates'] = App::get_by_where('tax_rates',array()); 
			$data['id'] = $id;
			$this->load->view('modal/edit_'.$item, $data);
		}
	}


	function package($id = NULL)
	{	App::module_access('menu_items');
		if ($this->input->post()) {
			Applib::is_demo();
			 $data['package_config'] = serialize($this->input->post());
			 $data['server'] = $this->input->post('server_id');
			 $data['package_name'] = $this->input->post('package');
			 $this->db->where('item_id', $this->input->post('item_id'));
			 
			if($this->db->update('items_saved', $data)) {				  	 
		  		$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', lang('operation_successful'));
				redirect($_SERVER['HTTP_REFERER']); 
			} 
		}
		else{	 
			$this->can_access();
			$this->template->title(lang('items').' - '.config_item('company_name'));
			$data['page'] = Item::view_item($id)->item_name; 
			$data['servers'] = $this->db->get('servers')->result();
			$data['id'] = $id;
			$this->template
			->set_layout('users')
			->build('package',isset($data) ? $data : NULL);			 
		}
	}




	function delete_hosting($id = NULL)
	{
		$this->delete_item($id, 'hosting');
	}

	function delete_domain($id = NULL)
	{
		$this->delete_item($id, 'domains');
	}

	function delete_service($id = NULL)
	{
		$this->delete_item($id, 'service');
	}
	
	function delete_addon($id = NULL)
	{
		$this->delete_item($id, 'addon');
	}


	function delete_item($id = NULL, $item = NULL) 
	{	App::module_access('menu_items');
		if ($this->input->post() ){
			Applib::is_demo();
			$item_id = $this->input->post('item', TRUE);
			App::delete('items_saved',array('item_id' => $item_id));
			App::delete('item_pricing',array('item_id' => $item_id));

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('item_deleted_successfully'));
			redirect($item == 'addon' ? 'addons' : $this->input->post('r_url'));
		}
		else {
			$data['item_id'] = $id;
			$data['item'] = $item;
			$this->load->view('modal/delete_item',$data);
		}		
	}



	function item_links($id = NULL) 
	{ 
		$data['id'] = $id;
		$this->load->view('modal/item_links',$data); 
	}



	function affiliates($id = NULL) 
	{ 
		if ($this->input->post()) 
		{
			 $data['commission'] = $this->input->post('commission');
			 $data['commission_payout'] = $this->input->post('commission_payout');
			 $data['commission_amount'] = $this->input->post('commission_amount');
			 $this->db->where('item_id', $this->input->post('item_id'));
			 
			if($this->db->update('items_saved', $data)) {				  	 
				$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', lang('operation_successful'));
				redirect($this->input->post('r_url'));
			} 
		}

		else 
		{
			$data['id'] = $id;
			$this->load->view('modal/item_affiliates',$data); 
		}
	}


	
	function items_block($id)
	{
		$category = $this->db->where('id', $id)->where('parent >','7')->get('categories')->row();
		$items = array();
		
		if($category->parent == 8) {
			$items = Item::get_domains($id);
			$view = 'domains_block';
		}

		if($category->parent == 9) {
			$items = Item::get_hosting($id);
			$view = 'hosting_block';
		}

		if($category->parent == 10) {
			$items = Item::get_services($id);
			$view = 'services_block';
		}

		$data['items'] = $items; 
		$data['style'] =  $category->pricing_table;
		$this->load->view(config_item('active_theme').'/views/blocks/'.$view, $data);
	}
 

}

/* End of file items.php */