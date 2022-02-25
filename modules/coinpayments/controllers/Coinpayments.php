<?php
/* Module Name: Coinpayments
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Payment Gateways
 * Description: Coinpayments Payment Gateway.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Coinpayments extends Hosting_Billing
{          
      
    private $public_key;
    private $private_key;

	function __construct()
	{
		parent::__construct();		
        User::logged_in();        
   
        require_once APPPATH.'/libraries/coinpayments/coinpayments.inc.php';
        $this->config = get_settings('coinpayments');
		if(!empty($this->config))
		{
			$this->public_key = $this->config['public_key'];
			$this->private_key  = $this->config['private_key']; 
		} 
	}


	public function coinpayments_config ($values = null)
		{
			$config = array(				
				array(
					'label' => lang('coinpayments_public_key'), 
					'id' => 'public_key',
					'value' => isset($values) ? $values['public_key'] : ''
				),
				
				array(
					'label' => lang('coinpayments_private_key'), 
					'id' => 'private_key',
					'value' => isset($values) ? $values['private_key'] : ''
				) 
			); 
			
			return $config;        
	}


    
	function index()
	{
        $this->session->set_flashdata('response_status', 'error');
        $this->session->set_flashdata('message', lang('coinpayments_canceled'));
        redirect('invoices');
	}    
    

	function pay($invoice = NULL)
	{   
       	$info = Invoice::view_by_id($invoice);
		$invoice_due = Invoice::get_invoice_due_amount($invoice);
		if ($invoice_due <= 0) {  $invoice_due = 0.00;	}

		$data['info'] = array(
							    'item_name'		=> $info->reference_no, 
								'item_number' 	=> $invoice,
								'currency' 		=> $info->currency,
								'amount' 		=> $invoice_due
                                );
	
		$this->load->view('form',$data);
    }
    

    
    function process ()
    {
        $invoice_id = $this->input->post('invoice',TRUE);
        $info = Invoice::view_by_id($invoice_id);
        $company = Client::view_by_id($info->client);
        $invoice_due = Invoice::get_invoice_due_amount($invoice_id);		

        $cps = new CoinPaymentsAPI();
        $cps->Setup($this->private_key, $this->public_key);
    
        $result = $cps->CreateTransactionSimple($invoice_due, $info->currency, config_item('accept_coin'), $company->company_email, '', base_url().'coinpayments/processed_ipn/'.$invoice_id.'_'.$info->client);
        $data['reference'] = $info->reference_no;
        $data['result'] = $result;
        $data['coin'] = 'BTC';
        $this->load->module('layouts');      
        $this->load->library('template');  
        $this->template->title(lang('payment').' - '.config_item('company_name'));
			$data['page'] = lang('coinpayments');			
			$this->template
			->set_layout('users')
			->build('response',isset($data) ? $data : NULL);
    }



    function processed_ipn ($transaction) {

        $data = explode('_', $transaction);
        $inv = Invoice::view_by_id($data[0]); 

        if($inv->client == $data[1])  {

        $invoice = $data[0];
        $client = Client::view_by_id($inv->client);
        $invoice_due = Invoice::get_invoice_due_amount($invoice);
        $paid_amount = Applib::format_deci($invoice_due);
        $this->load->helper('string');
            $data = array(
                        'invoice' => $invoice,
                        'paid_by' => $data[1],
                        'currency' => strtoupper($inv->currency),
                        'payer_email' => $client->company_email,
                        'payment_method' => '1',
                        'notes' =>  lang('coinpayments').' '.lang('payment'),
                        'amount' => $paid_amount,
                        'trans_id' => random_string('nozero', 6),
                        'month_paid' => date('m'),
                        'year_paid' => date('Y'),
                        'payment_date' => date('Y-m-d')
                    );

            // Store the payment in the database.
            if ($payment_id = App::save_data('payments', $data)) {
                $cur_i = App::currencies(strtoupper($inv->currency));
 

                $data = array(
                'module' => 'invoices',
                'module_field_id' => $invoice,
                'user' => $inv->client,
                'activity' => 'activity_payment_of',
                'icon' => 'fa-usd',
                'value1' => $inv->currency.''.$paid_amount,
                'value2' => $inv->reference_no
                );

                App::Log($data);

                send_payment_email($invoice, $paid_amount); // Send email to client

                if(config_item('notify_payment_received') == 'TRUE'){
                    notify_admin($trans->invoice, $paid, $cur_i->code); // Send email to admin
                }

           $invoice_due = Invoice::get_invoice_due_amount($invoice);
            if($invoice_due <= 0) {
                Invoice::update($invoice,array('status'=>'Paid'));
                modules::run('orders/process', $invoice);
                }
            }           
        }
    } 
    


	function cancel()
	{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('paypal_canceled'));
				redirect('clients');
	}
    
    
	function success()
	{
        if($_POST){
				$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', lang('payment_added_successfully'));
				redirect('clients');
        }else{
        $this->session->set_flashdata('response_status', 'error');
        $this->session->set_flashdata('message', 'Something went wrong please contact us if your Payment doesn\'t appear shortly');
        redirect('clients');
        }
    }
    

    public function activate($data)
    { 
        return true;
    }


    public function install()
    { 
        return true;
    }


    public function uninstall()
    { 
        return true;
    } 

}


////end 