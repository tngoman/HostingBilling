<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Items extends Hosting_Billing  {

	function __construct()
	{	
		parent::__construct();	
		User::logged_in();	
 
		$this->load->library(array('form_validation'));
		$this->load->model(array('Invoice','App','Client'));

		$this->applib->set_locale();
	}

	function add(){
		if ($this->input->post()) {

		$invoice_id = $this->input->post('invoice_id',TRUE);
		$this->form_validation->set_rules('invoice_id', 'Invoice ID', 'required');
		$this->form_validation->set_rules('item_name', 'Item Name', 'required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required');
		$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');

		if ($this->form_validation->run() == FALSE)
		{	
				$_POST = '';
				Applib::go_to('invoices/view/'.$invoice_id,'error',lang('error_in_form'));	
		}else{	
			$item_name = $this->input->post('item_name',TRUE);
			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$tax_rate = $this->input->post('item_tax_rate');

			if ($tax_rate == '0.00') {
				if($row = $this->db->where('item_name',$item_name)->get('items_saved')->row()){
					$tax_rate = $row->item_tax_rate;
				}
				
			}
			$item_tax_total = Applib::format_deci(($tax_rate / 100) *  $sub_total);
			$total_cost =  Applib::format_deci($sub_total + $item_tax_total);

			$data = array(
						'invoice_id'	=> $this->input->post('invoice_id',TRUE),
						'item_name'		=> $item_name,
						'item_desc'		=> $this->input->post('item_desc',TRUE),
						'unit_cost'		=> $this->input->post('unit_cost',TRUE),
						'item_order'	=> $this->input->post('item_order',TRUE),
						'item_tax_rate'	=> $tax_rate,
						'item_tax_total'=> $item_tax_total,
						'quantity'		=> $this->input->post('quantity',TRUE),
						'total_cost'	=> $total_cost
						);
			// unset($_POST['tax']);

				if(App::save_data('items',$data)){
					Applib::go_to('invoices/view/'.$invoice_id,'success',lang('item_added_successfully'));
				}
			}
		}
	}

	function edit(){
		if ($this->input->post()) {

		$item_id = $this->input->post('item_id',TRUE);
		$invoice_id = Invoice::view_item($item_id)->invoice_id;

		$this->form_validation->set_rules('invoice_id', 'Invoice ID', 'required');
		$this->form_validation->set_rules('item_name', 'Item Name', 'required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required');
		$this->form_validation->set_rules('unit_cost', 'Unit Cost', 'required');

		if ($this->form_validation->run() == FALSE)
		{	
				$_POST = '';
				Applib::go_to('invoices/view/'.$invoice_id,'error',lang('error_in_form'));	
		}else{	
			
			$sub_total = $this->input->post('unit_cost') * $this->input->post('quantity');
			$tax_rate = $this->input->post('item_tax_rate');
			$item_tax_total = Applib::format_deci(($tax_rate / 100) *  $sub_total);

			$total_cost = Applib::format_deci($sub_total + $item_tax_total);

			$data = array(
						'invoice_id'	=> $this->input->post('invoice_id',TRUE),
						'item_name'		=> $this->input->post('item_name',TRUE),
						'item_desc'		=> $this->input->post('item_desc',TRUE),
						'unit_cost'		=> $this->input->post('unit_cost',TRUE),
						'item_tax_rate'	=> $tax_rate,
						'item_tax_total'=> $item_tax_total,
						'quantity'		=> $this->input->post('quantity',TRUE),
						'total_cost'	=> $total_cost
						);

			if(App::update('items', array('item_id' => $item_id),$data)){
					Applib::go_to('invoices/view/'.$invoice_id,'success',lang('item_added_successfully'));
			}
		}
		}else{
			$data['id'] = $this->uri->segment(4);
			$this->load->view('modal/edit_item',$data);
		}
	}

	function insert()
	{
		if ($this->input->post()) {
			$invoice = $this->input->post('invoice',TRUE);

			$this->form_validation->set_rules('item', 'Item Name', 'required');

			if ($this->form_validation->run() == FALSE)
			{
					Applib::go_to('invoices/view/'.$invoice,'error',lang('operation_failed'));
			}else{	

			$item = $this->input->post('item',TRUE);
			$saved_item = $this->db->where(array('item_id'=>$item))->get('items_saved')->row();
			
            $items = Invoice::has_items($invoice);

			$form_data = array(
			                'invoice_id' 		=> $invoice,
			                'item_name'  		=> $saved_item->item_name,
			                'item_desc' 		=> $saved_item->item_desc,
			                'unit_cost' 		=> $saved_item->unit_cost,
			                'item_tax_rate' 	=> $saved_item->item_tax_rate,
			                'item_tax_total' 	=> $saved_item->item_tax_total,
			                'quantity' 			=> $saved_item->quantity,
			                'total_cost' 		=> $saved_item->total_cost,
                            'item_order' 		=> count($items) + 1
			            );
			if(App::save_data('items',$form_data)){
					Applib::go_to('invoices/view/'.$invoice,'success',lang('item_added_successfully'));
				}
			}
		}else{
			$data['invoice'] = $this->uri->segment(4);
			$this->load->view('modal/quickadd',$data);
		}
	}

	function delete(){
		if ($this->input->post() ){
					$item_id = $this->input->post('item', TRUE);
					$invoice = $this->input->post('invoice', TRUE);
				if(App::delete('items',array('item_id' => $item_id))){
						Applib::go_to('invoices/view/'.$invoice,'success',lang('item_deleted_successfully'));
				}
		}else{
			$data['item_id'] = $this->uri->segment(4);
			$data['invoice'] = $this->uri->segment(5);
			$this->load->view('modal/delete_item',$data);
		}
	}

	function reorder(){
                if ($this->input->post() ){
                        $items = $this->input->post('json', TRUE);
                        $items = json_decode($items);
                        foreach ($items[0] as $ix => $item) {
                            App::update('items', array('item_id' => $item->id),array("item_order"=>$ix+1));
                        }
                }
                $data['json'] = $items;
                $this->load->view('json',isset($data) ? $data : NULL);
	}
        
}

/* End of file invoices.php */