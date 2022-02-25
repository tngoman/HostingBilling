<?php
/* Module Name: Paypal
 * Module URI: http://www.hostingbilling.net
 * Version: 1.3
 * Category: Payment Gateways
 * Description: Paypal Payment Gateway.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Paypal extends Hosting_Billing
{     
	
	private $email; 
    private $sandbox;
                   

	function __construct()
	{
		parent::__construct();		
		User::logged_in();
		
		$this->config = get_settings('paypal');
        if(!empty($this->config))
        {             
            $this->sandbox = $this->config['mode'] == 'test' ? 'TRUE' : 'FALSE';
            $this->email = $this->config['email']; 
       }			
    }


    public function paypal_config ($values = null)
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
                'label' => lang('paypal_email'), 
                'id' => 'email',
                'value' => isset($values) ? $values['email'] : ''
            ) 
        ); 
        
        return $config;        
    }    

	
	function index()
	{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('paypal_canceled'));
				redirect('clients');
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
								'client'		=> $info->client,
								'amount' 		=> $invoice_due
								);

		if ($this->sandbox == 'TRUE') {
			$paypalurl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}else{
			$paypalurl = 'https://www.paypal.com/cgi-bin/webscr';
		}
		
		$data['paypal_url'] = $paypalurl;
		$data['email'] = $this->email;
		
		$this->load->view('form',$data);
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


	 function ipn()
    {
        
        $raw_post_data = file_get_contents('php://input'); 
        $raw_post_array = explode('&', $raw_post_data); 
        $myPost = array(); 
        foreach ($raw_post_array as $keyval) { 
            $keyval = explode ('=', $keyval); 
            if (count($keyval) == 2) 
                $myPost[$keyval[0]] = urldecode($keyval[1]); 
        } 
         
        // Read the post from PayPal system and add 'cmd' 
        $req = 'cmd=_notify-validate'; 
        if(function_exists('get_magic_quotes_gpc')) { 
            $get_magic_quotes_exists = true; 
        } 
        foreach ($myPost as $key => $value) { 
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
                $value = urlencode(stripslashes($value)); 
            } else { 
                $value = urlencode($value); 
            } 
            $req .= "&$key=$value"; 
        } 
         

            // Post IPN data back to PayPal to validate the IPN data is genuine
                  if($this->sandbox == TRUE) {
                    $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
                } else {
                    $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
                }
                $ch = curl_init($paypal_url);
                if ($ch == FALSE) {
                    return FALSE;
                }
                    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
                    if(DEBUG == true) {
                        curl_setopt($ch, CURLOPT_HEADER, 1);
                        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
                    }
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: company-name')); 
                    $res = curl_exec($ch); 
     
                    curl_close($ch);
                    
 
                
                $tokens = explode("\r\n\r\n", trim($res)); 
                $res = trim(end($tokens)); 
                if (strcmp($res, "VERIFIED") == 0 || strcasecmp($res, "VERIFIED") == 0) { 
                            $invoice_id = $_POST['item_number'];
                            $invoice_ref = $_POST['item_name'];
                            $client = $_POST['custom'];
                            $txn_id = $_POST['txn_id'];
                            $client_email = $_POST['payer_email'];
                            $receiver = $_POST['receiver_email'];
                            $first_name = $_POST['first_name'];
                            $last_name = $_POST['last_name'];
                            $currency = $_POST['mc_currency'];
                            $paid_amount = $_POST['mc_gross'];

                            $info = Invoice::view_by_id($invoice_id);
                            $company = Client::view_by_id($client);

            $trans_id_exists = $this->db->where('trans_id',$txn_id)->get('payments')->num_rows();
            if($trans_id_exists > 0) return FALSE;

            // prepare data to insert to DB
            $data = array(
                                    'invoice' => $invoice_id,
                                    'paid_by' => $client,
                                    'payer_email' => $company->company_name,
                                    'payment_method' => '1',
                                    'currency' => $currency,
                                    'amount' => $paid_amount,
                                    'trans_id' => $txn_id,
                                    'notes' => 'Paid by '.$first_name.' '.$last_name.' to '.$receiver.' via Paypal',
                                    'payment_date' => date('Y-m-d'),
                                    'month_paid' => date('m'),
                                    'year_paid' => date('Y'),
                                    );

            App::save_data('payments',$data); // insert to payments

            // Log Activity
            $cur_i = App::currencies($currency);

             $data = array(
                'module' => 'invoices',
                'module_field_id' => $invoice_id,
                'user' => $company->primary_contact,
                'activity' => 'activity_payment_of',
                'icon' => 'fa-usd',
                'value1' => $cur_i->symbol.$paid_amount,
                'value2' => $invoice_ref
                );
            App::Log($data);

            send_payment_email($invoice_id,$paid_amount);

            if(config_item('notify_payment_received') == 'TRUE'){
                notify_admin($invoice_id,$paid_amount,$cur_i->code); // Send email to admin
            }

            $due = Invoice::get_invoice_due_amount($invoice_id);
            if($due <= 0){
                Invoice::update($invoice_id,array('status'=>'Paid'));
                modules::run('orders/process', $invoice_id);
            }
            
       }
       
    

                 
    }

    function send_payment_email($invoice_id, $paid_amount){

            $message = App::email_template('payment_email','template_body');
            $subject = App::email_template('payment_email','subject');
            $signature = App::email_template('email_signature','template_body');


            $info = Invoice::view_by_id($invoice_id);
            $cur = App::currencies($info->currency);

            $logo_link = create_email_logo();

            $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);
            $ref = str_replace("{REF}",$info->reference_no,$logo);

            $invoice_currency = str_replace("{INVOICE_CURRENCY}",$cur->symbol,$ref);
            $ref = str_replace("{INVOICE_REF}",$info->reference_no,$invoice_currency);
            $amount = str_replace("{PAID_AMOUNT}",$paid_amount,$ref);
            $EmailSignature = str_replace("{SIGNATURE}",$signature,$amount);
            $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);

            $params['recipient'] = Client::view_by_id($info->client)->company_email;

            $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
            $params['message'] = $message;
            $params['attached_file'] = '';

            modules::run('fomailer/send_email',$params);
    }

    function notify_admin($invoice, $amount, $cur)
    {
            $info = Invoice::view_by_id($invoice);

            foreach (User::admin_list() as $key => $user) {
                $data = array(
                                'email'         => $user->email,
                                'invoice_ref'   => $info->reference_no,
                                'amount'        => $amount,
                                'currency'      => $cur,
                                'invoice_id'    => $invoice,
                                'client'        => Client::view_by_id($info->client)->company_name
                            );

                $email_msg = $this->load->view('new_payment',$data,TRUE);

                $params = array(
                                'subject'       => '['.config_item('company_name').'] Payment Confirmation',
                                'recipient'     => $user->email,
                                'message'       => $email_msg,
                                'attached_file' => ''
                                );

                modules::run('fomailer/send_email',$params);
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


 