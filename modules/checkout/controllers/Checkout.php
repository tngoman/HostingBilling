<?php
/* Module Name: Checkout
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Payment Gateways
 * Description: 2Checkout Payment Gateway.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Checkout extends Hosting_Billing
{      

private $seller_id;
private $publishable_key;
private $private_key;
private $sandbox; 

function __construct()
	{
		parent::__construct();
		User::logged_in(); 
		$this->config = get_settings('checkout');
        if(!empty($this->config))
        {             
            $this->sandbox = $this->config['mode'] == 'test' ? true : false;
            $this->seller_id = $this->config['seller_id'];
            $this->publishable_key = $this->config['publishable_key'];
			$this->private_key = $this->config['private_key'];
       }			
    }


public function checkout_config ($values = null)
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
                'label' => lang('checkout_seller_id'), 
                'id' => 'seller_id',
                'value' => isset($values) ? $values['seller_id'] : ''
            ), 
            array(
                'label' => lang('checkout_publishable_key'), 
                'id' => 'publishable_key',
                'value' => isset($values) ? $values['publishable_key'] : ''
            ), 
            array(
                'label' => lang('checkout_private_key'), 
                'id' => 'private_key',
                'value' => isset($values) ? $values['private_key'] : ''
            ) 
        ); 
        
        return $config;        
    }


function pay($invoice = NULL)
	{

		$info = Invoice::view_by_id($invoice);

		$invoice_due = Invoice::get_invoice_due_amount($invoice);
		if ($invoice_due <= 0) $invoice_due = 0.00;

		$data['info'] = array('item_name'=> $info->reference_no,
							  'item_number' => $invoice,
							  'currency' => $info->currency,
							  'amount' => $invoice_due
							);
		$data['publishable_key'] = $this->publishable_key;
		$data['seller_id'] = $this->seller_id;
		$data['live'] = $this->sandbox;
		$this->load->view('form', $data);
	}

function process()
	{

		if ($this->input->post()) {
			$errors = array();
			$invoice_id = $this->input->post('invoice_id');
			if (!isset($_POST['token'])) {
				$errors['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
			}
			// If no errors, process the order:
	if (empty($errors)) {
			require_once(APPPATH.'libraries/2checkout/Twocheckout.php');

			Twocheckout::privateKey($this->private_key);
			Twocheckout::sellerId($this->seller_id);
			Twocheckout::sandbox($this->sandbox);

			// Twocheckout::verifySSL(false);

			$info = Invoice::view_by_id($invoice_id); // Invoice Details
			$company = Client::view_by_id($info->client); // Get company details

	try {

    	$charge = Twocheckout_Charge::auth(array(
			        "merchantOrderId" => $info->inv_id,
			        "token"      => $_POST['token'],
			        "currency"   => $info->currency,
			        "total"      => $this->input->post('amount'),
			        "billingAddr" => array(
			            "name" => $company->company_name,
			            "addrLine1" => $company->company_address,
			            "city" => $company->city,
			            "state" => $company->state,
			            "zipCode" => $company->zip,
			            "country" => $company->country,
			            "email" => $company->company_email,
			            "phoneNumber" => $company->company_phone
			        )
			    ));

    	if ($charge['response']['responseCode'] == 'APPROVED') {
				$data = array(
				                     'invoice' => $charge['response']['merchantOrderId'],
				                     'paid_by' => $company->co_id,
				                     'payer_email' => $charge['response']['billingAddr']['email'],
				                     'payment_method' => '1',
				                     'currency' => $charge['response']['currencyCode'],
				                     'notes' => 'Paid by '.User::displayName(User::get_id()).' via 2checkout',
				                     'amount' => $charge['response']['total'],
				                     'trans_id' => $charge['response']['transactionId'],
				                     'month_paid' => date('m'),
									 'year_paid' => date('Y'),
									 'payment_date' => date('Y-m-d H:i:s')
				                     );
				// Store the order in the database.
				if ($payment_id = App::save_data('payments', $data)) {
                    $cur_i = App::currencies(strtoupper($charge['response']['currencyCode']));

                // Log activity
				$data = array(
					'module' => 'invoices',
					'module_field_id' => $invoice_id,
					'user' => User::get_id(),
					'activity' => 'activity_payment_of',
					'icon' => 'fa-usd',
					'value1' => $cur_i->symbol.''.$charge['response']['total'],
					'value2' => $info->reference_no
					);
				App::Log($data);

            	send_payment_email($invoice_id,$charge['response']['total']); // Send email to client

            	if(config_item('notify_payment_received') == 'TRUE'){
            		// Send email to admin
            		notify_admin($invoice_id,$charge['response']['total'],$cur_i->code);
            	}

            	$due = Invoice::get_invoice_due_amount($invoice_id);
				if($due <= 0){
					Invoice::update($invoice_id,array('status'=>'Paid'));
					modules::run('orders/process', $invoice_id);
				}


            	$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', 'Payment received and applied to Invoice '.$info->reference_no);
				redirect('invoices/view/'.$info->inv_id);

				}else{
				$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', 'Payment not recorded in the database. Please contact the system Admin.');
				redirect('invoices/view/'.$info->inv_id);
					}

				}
			} catch (Twocheckout_Error $e) {
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', 'Payment declined with error: '.$e->getMessage());
				redirect('invoices/view/'.$info->inv_id);
			}
		}
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

 