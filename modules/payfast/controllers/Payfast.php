<?php
/* Module Name: Payfast
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Payment Gateways
 * Description: Payfast Payment Gateway.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Payfast extends Hosting_Billing
{          

    private $merchant_key;
    private $merchant_id;
    private $passphrase;
    private $sandbox;

               
  	function __construct()
	{
		parent::__construct();		
        User::logged_in();
     
        $this->config = get_settings('payfast');
        if(!empty($this->config))
        {             
            $this->sandbox = $this->config['mode'] == 'test' ? 'TRUE' : 'FALSE';
            $this->merchant_key = $this->config['merchant_key'];
            $this->merchant_id = $this->config['merchant_id'];
            $this->passphrase = $this->config['passphrase'];
       }			
    }


    public function payfast_config ($values = null)
    {
        $config = array(
            array(
                'label' => lang('mode'),
                'id' => 'mode',
                'type' => 'dropdown',
                'options' => array(
                        'live' => lang('live'),
                        'test' => lang('test')
                ),
                'value' => isset($values['mode']) ? $values['mode'] : 'live'
            ), 
            array(
                'label' => lang('payfast_merchant_id'), 
                'id' => 'merchant_id',
                'value' => isset($values) ? $values['merchant_id'] : ''
            ), 
            array(
                'label' => lang('payfast_merchant_key'), 
                'id' => 'merchant_key',
                'value' => isset($values) ? $values['merchant_key'] : ''
            ), 
            array(
                'label' => lang('payfast_passphrase'), 
                'id' => 'passphrase',
                'value' => isset($values) ? $values['passphrase'] : ''
            ) 
        ); 
        
        return $config;        
    }    

    
	function index()
	{ 
        redirect('invoices');
	}    
    

	function pay($invoice = NULL)
	{
		$inv = Invoice::view_by_id($invoice); 
        $company = Client::view_by_id($inv->client); 
        $client_cur = 'ZAR';
                                       
        $invoice_due = Applib::client_currency('ZAR', Invoice::get_invoice_due_amount($invoice));
        $data['symbol'] = App::currencies($client_cur)->symbol;
        $data['currency'] = $client_cur;
     
         
        if ($invoice_due <= 0) {  
            $invoice_due = 0.00;
        }
            $data['due'] = $invoice_due;
            $data['id'] = $invoice;


		$data['info'] = array(
                            'company_name'	=> $company->company_name, 
                            'company_ref'	=> $company->company_ref, 
                            'company_email'	=> $company->company_email,
                            'company_id'	=> $inv->client,
                            'item_name'		=> $inv->reference_no, 
                            'item_number' 	=> $invoice,
                            'currency' 		=> $inv->currency,
                            'amount' 		=> $invoice_due
                            );

        $this->load->module('layouts');
        $this->load->library('template');	
        $this->template->title(lang('payment').' - '.config_item('company_name'));

        $data['merchant_id'] = $this->merchant_id;
        $data['merchant_key'] = $this->merchant_key;
        $data['sandbox'] = $this->sandbox;
        $data['passphrase'] = $this->passphrase;
        $data['page'] = 'Payfast';			
        $this->template
        ->set_layout('users')
        ->build('form',isset($data) ? $data : NULL);
    }
    


    
   function processed_ipn () {

    require_once APPPATH.'/libraries/payfast/pf.inc.php';

    if (isset($_POST['payment_status'])) {  

        $pfHost = ($this->sandbox == 'TRUE') ? 'https://sandbox.payfast.co.za' : 'https://www.payfast.co.za';
        $error = false;

        pflog('ITN received from payfast.co.za');
        if (!pfValidIP($_SERVER['REMOTE_ADDR'])) {
            pflog('REMOTE_IP mismatch: ');
            $error = true;
            return false;
        }

        $data = pfGetData();

        pflog('POST received from payfast.co.za: ' . print_r($data, true));

        if ($data === false) {
            pflog('POST is empty: ' . print_r($data, true));
            $error = true;
            return false;
        }

        if (!pfValidSignature($data, $this->passphrase)) {
            pflog('Signature mismatch on POST');
            $error = true;
            return false;
        }

        pflog('Signature OK');

        $itnPostData = array();
        $itnPostDataValuePairs = array();

        foreach ($_POST as $key => $value) {
            if ($key == 'signature')
                continue;

            $value = urlencode(stripslashes($value));
            $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value);
            $itnPostDataValuePairs[] = "$key=$value";
        }

        $itnVerifyRequest = implode('&', $itnPostDataValuePairs);
        if (!pfValidData($pfHost, $itnVerifyRequest, "$pfHost/eng/query/validate")) {
            pflog("ITN mismatch for $itnVerifyRequest\n");
            pflog('ITN not OK');
            $error = true;
            return false;
        }

        pflog('ITN OK');
        pflog("ITN verified for $itnVerifyRequest\n");

        if ($error == false and $_POST['payment_status'] == "COMPLETE") {
                $client_id = intval($_POST['custom_int1']);
                $amount_paid = $_POST['amount_gross'];
                $invoice = $_POST['m_payment_id'];
                $txn_id =  $_POST['pf_payment_id'];    
                $client = Client::view_by_id($client_id);
                $invoice_due = Invoice::get_invoice_due_amount($invoice);
                $paid_amount = Applib::convert_currency('ZAR', $amount_paid);
                $inv = Invoice::view_by_id($invoice);
                $this->load->helper('string');

                $data = array(
                            'invoice' => $invoice,
                            'paid_by' => $client_id,
                            'currency' => strtoupper($inv->currency),
                            'payer_email' => $client->company_email,
                            'payment_method' => '1',
                            'notes' => 'Payfast Transaction: '.$txn_id,
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
                        'user' => $client->primary_contact,
                        'activity' => 'activity_payment_of',
                        'icon' => 'fa-usd',
                        'value1' => $inv->currency.''.$paid_amount,
                        'value2' => $inv->reference_no
                        );

                        App::Log($data);
                        
                        $invoice_due = Invoice::get_invoice_due_amount($invoice);
                        if($invoice_due <= 0) {
                        Invoice::update($invoice,array('status'=>'Paid'));
                        modules::run('orders/process', $invoice);
                        }

                        send_payment_email($invoice, $paid_amount); // Send email to client

                        if(config_item('notify_payment_received') == 'TRUE'){
                            notify_admin($trans->invoice, $paid, $cur_i->code); // Send email to admin
                        }

                }           
            }
        }
    }

    


	function cancel()
	{
        $this->session->set_flashdata('response_status', 'error');
        $this->session->set_flashdata('message', 'Payfast Payment Cancelled!');
        redirect('clients');
	}
    
    
	function success($id = null)
	{    
        $this->session->set_flashdata('response_status', 'success');
        $this->session->set_flashdata('message', lang('payment_added_successfully'));
        redirect('invoices/view/'.$id);       
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

 