<?php
/* Module Name: Stripepay
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Payment Gateways
 * Description: Stripepay Payment Gateway.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Stripepay extends Hosting_Billing
{    
	private $public_key;
	private $private_key;      
      
	function __construct()
	{
		parent::__construct();
		User::logged_in(); 

		$this->config = get_settings('stripepay');
        if(!empty($this->config))
        {  
            $this->private_key = $this->config['private_key']; 
			$this->public_key = $this->config['public_key']; 
       }			
    }


    public function stripepay_config ($values = null)
    {
        $config = array( 
            array(
                'label' => lang('stripe_public_key'), 
                'id' => 'public_key',
                'value' => isset($values) ? $values['public_key'] : ''
			),
			array(
                'label' => lang('stripe_private_key'), 
                'id' => 'private_key',
                'value' => isset($values) ? $values['private_key'] : ''
            ) 
        ); 
        
        return $config;        
    } 



	function pay($invoice = NULL)
	{	          
		$inv = Invoice::view_by_id($invoice);
		$company = Client::view_by_id($inv->client);
		$due = Invoice::get_invoice_due_amount($invoice);
		
		$data['page'] = 'Stripe ' . lang('pay');
		$data['invoice'][] = $inv;			
		$data['company'][] = $company;
		$data['due'] = $due;
		$data['id'] = $invoice;
		$data['public_key'] = $this->public_key;
		
		$this->load->module('layouts');
        $this->load->library('template'); 
		$this->template->title(lang('payment').' - '.config_item('company_name'));
		$this->template
		->set_layout('users')
		->build('form', $data);        
    } 



	function authenticate(){

	// Check for a form submission:
	if ($_POST) {

	// Stores errors:
	$errors = array();

	// Need a payment token:
	if (isset($_POST['stripeToken'])) {

		$token = $this->input->post('stripeToken',true);

		// Check for a duplicate submission, just in case:
		// Uses sessions, you could use a cookie instead.
		if (isset($_SESSION['token']) && ($_SESSION['token'] == $token)) {
			$errors['token'] = 'You have apparently resubmitted the form. Please do not do that.';
		} else { // New submission.
			$_SESSION['token'] = $token;
		}

	} else {
		$errors['token'] = 'The order cannot be processed. Please make sure you have JavaScript enabled and try again.';
	}
 
	if (empty($errors)) {
 
		try {

			// Include the Stripe library:
			require_once APPPATH.'/libraries/stripe/init.php';
 
			\Stripe\Stripe::setApiKey($this->private_key);

			$invoice_id = $this->input->post('invoice',TRUE);
			$info = Invoice::view_by_id($invoice_id);
			$company = Client::view_by_id($info->client);

			$amount = intval($this->input->post('amount',TRUE) * 100);

			$metadata = array(
			                     'invoice_id' 	=> $invoice_id,
			                     'payer' 		=> User::displayName(User::get_id()),
			                     'payer_email' 	=> $company->company_email,
			                     'invoice_ref' 	=> $info->reference_no
			                     );

			try { $charge = \Stripe\Charge::create(array(
						"amount"   		=> $amount, // amount in cents
						"currency" 		=> $info->currency,
						"card" 			=> $token,
						"metadata" 		=> $metadata,
						"description" 	=> lang('invoice') ." - ".$info->reference_no
						)
					);

					
				} catch (\Stripe\Exception\InvalidRequestException $e) {
					$error = htmlspecialchars($e->getMessage());
					$this->session->set_flashdata('response_status', 'error');
					$this->session->set_flashdata('message', $error);
					redirect($_SERVER['HTTP_REFERER']);
				}
		
			// Check that it was paid:
			if ($charge->paid == true) {
				$metadata = $charge->metadata;
				$data = array(
				            'invoice' => $metadata->invoice_id,
				            'paid_by' => $info->client,
				            'currency' => strtoupper($charge->currency),
				            'payer_email' => $metadata->payer_email,
				            'payment_method' => '1',
				            'notes' => $charge->description.' by '.User::displayName(User::get_id()).' - Stripe',
				            'amount' => round($charge->amount/100,2),
				            'trans_id' => $charge->balance_transaction,
				            'month_paid' => date('m'),
							'year_paid' => date('Y'),
							'payment_date' => date('Y-m-d')
				        );
				// Store the order in the database.
				if ($payment_id = App::save_data('payments', $data)) {
                $cur_i = App::currencies(strtoupper($charge->currency));

                $received_amount = number_format($amount/100,2);

                $data = array(
                'module' => 'invoices',
                'module_field_id' => $invoice_id,
                'user' => $company->primary_contact,
                'activity' => 'activity_payment_of',
                'icon' => 'fa-usd',
                'value1' => $cur_i->symbol.''.$received_amount,
                'value2' => $info->reference_no
                );

            	App::Log($data);

            	$this->_send_payment_email($invoice_id,$received_amount); // Send email to client

            	if(config_item('notify_payment_received') == 'TRUE'){
            		$this->_notify_admin($invoice_id,$received_amount,$cur_i->code); // Send email to admin
            	}

   			$due = Invoice::get_invoice_due_amount($invoice_id);
			if($due <= 0){
				Invoice::update($invoice_id,array('status'=>'Paid'));
				modules::run('orders/process', $invoice_id);
			}

            	$this->session->set_flashdata('response_status', 'success'); 
				$this->session->set_flashdata('message', lang('payment_added_successfully'));
				redirect('invoices/view/'.$invoice_id);

				}else{
				$this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', 'Payment not recorded in the database. Please contact the system Admin.');
				redirect('invoices/view/'.$invoice_id);
				}


			} else { // Charge was not paid!
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', 'Your payment could NOT be processed (i.e., you have not been charged) because the payment system rejected the transaction. You can try again or use another card.');
				redirect($_SERVER['HTTP_REFERER']);
			}

		} catch (\Stripe\Exception\CardException $e) { 
			$e_json = $e->getJsonBody();
			$err = $e_json['error']; 
			$error =  $err['message'];
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', $error);
			redirect($_SERVER['HTTP_REFERER']);
		} catch (\Stripe\Exception\ApiConnectionException $e) {
			$error = htmlspecialchars($e->getMessage());
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', $error);
			redirect($_SERVER['HTTP_REFERER']);
		} catch (\Stripe\Exception\InvalidRequestException $e) {
			$error = htmlspecialchars($e->getMessage());
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', $error);
			redirect($_SERVER['HTTP_REFERER']);
		} catch (\Stripe\Exception\ApiException $e) {
			$error = htmlspecialchars($e->getMessage());
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', $error);
			redirect($_SERVER['HTTP_REFERER']);
		} catch (\Stripe\Exception\CardException $e) {
			$error = htmlspecialchars($e->getMessage());
			$this->session->set_flashdata('response_status', 'error');
			$this->session->set_flashdata('message', $error);
			redirect($_SERVER['HTTP_REFERER']);
		}

		} // A user form submission error occurred, handled below.


	} // Form submission.

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

 