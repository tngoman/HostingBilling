<?php
/* Module Name: Bitcoin
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Payment Gateways
 * Description: Bitcoin Payment Gateway.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Bitcoin extends Hosting_Billing
{               
   
	private $blockchain_xpub;
	private $blockchain_api_key;
	

	function __construct()
	{
		parent::__construct();	
		$this->applib->set_locale(); 
        $this->config = get_settings('bitcoin');
        if(!empty($this->config))
        {   $this->blockchain_xpub = $this->config['blockchain_xpub'];
            $this->blockchain_api_key = $this->config['blockchain_api_key'];
        }			
    }

    public function bitcoin_config ($values = null)
    {
        $config = array(  
            array(
                'label' => lang('blockchain_xpub'), 
                'id' => 'blockchain_xpub',
                'value' => isset($values) ? $values['blockchain_xpub'] : ''
            ), 
            array(
                'label' => lang('blockchain_api_key'), 
                'id' => 'blockchain_api_key',
                'value' => isset($values) ? $values['blockchain_api_key'] : ''
            ) 
        ); 
        
        return $config;        
    }


	
	function curl_get_contents($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}


	function round_up ( $value, $precision ) 
	{ 
		$pow = pow ( 10, $precision ); 
		return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
	}


	function pay($invoice = NULL)
	{ 
		$blockchain_root = "https://blockchain.info/";
		$blockchain_receive_root = "https://api.blockchain.info/";
		$secret = "7Q1Vasdeo8k6T51rQn9w5DQrcGG06VMF";
		$my_xpub = $this->blockchain_xpub;
		$my_api_key = $this->blockchain_api_key;


		$userid = User::get_id();
		$info = Invoice::view_by_id($invoice);
		$invoice_due = Invoice::get_invoice_due_amount($invoice);
		if ($invoice_due <= 0) {  $invoice_due = 0.00;	}
		$data['invoice_info'] = array('item_name'=> $info->reference_no, 
										'item_number' => $invoice,
										'currency' => $info->currency,
										'amount' => $invoice_due) ;
		$data['bitcoin'] = TRUE;

		$urls = $blockchain_root.'tobtc?currency='.$info->currency."&value=".$invoice_due;
		$btc_amount = $this->curl_get_contents($urls);

		$data['btc_amount'] = $this->round_up($btc_amount, 3);

		$callback_url = base_url().'bitcoin/success/?usdamount='.$invoice_due.'&invoicename='.$info->reference_no.'&btcamount='.$data['btc_amount'].'&invoice='.$invoice.'&client='.$info->client.'&secret='.$secret;

		$url = $blockchain_receive_root . "v2/receive?key=" . $my_api_key . "&callback=". urlencode($callback_url) . "&xpub=" . $my_xpub;

		$resp = $this->curl_get_contents($url);
		
		$decoded = json_decode($resp);
 
		$data['btc_address'] = isset($decoded->address) ? $decoded->address : $decoded->message;
		
		$this->load->view('form',$data);
	}
	

	function cancel()
	{
		$this->session->set_flashdata('response_status', 'error');
		$this->session->set_flashdata('message', 'Bitcoin payment canceled.');
		redirect('clients');
	}
	

	function success(){
		echo "*ok*";
		function round_up ( $value, $precision ) { 
			$pow = pow ( 10, $precision ); 
			return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
		}
		$transactionid = $_GET['transaction_hash'];
		$invoiceid = $_GET['invoice'];
		$invoicename = $_GET['invoicename'];
		$usdamount = $_GET['usdamount'];
		$btcamount = $_GET['btcamount'];
		$client = $_GET['client'];
		$amountsentsatoshi = $_GET['value'];
		$amountsent = $amountsentsatoshi / 100000000;
		$company_name = Client::view_by_id($client)->company_name; //get client username
		$company_email = Client::view_by_id($client)->company_email; //get client email
		$ratio = $amountsent / $btcamount;
		$paid = $usdamount * $ratio;
		$paid = round_up($paid, 2);
		
		$data = array(
			'invoice' => $invoiceid,
			'paid_by' => $client,
			'payment_method' => '1',
			'amount' => $paid,
			'trans_id' => $transactionid,
			'notes' => 'Amount in BTC: '.$amountsent,
			'month_paid' => date('m'),
			'year_paid' => date('Y'),
		);
		App::save_data('payments',$data); // insert to payments

		if(Invoice::get_invoice_due_amount($invoiceid) <= 0.00){
			App::update('invoices',array('inv_id'=> $invoiceid),array('status'=>'Paid'));
			modules::run('orders/process', $invoiceid);
		}

		$data = array(
				'user'				=> Client::view_by_id($client)->primary_contact,
				'module' 			=> 'invoices',
				'module_field_id'	=> $invoiceid,
				'activity'			=> 'activity_payment_of',
				'icon'				=> 'fa-btc',
				'value1'         	=> 'BTC '.$paid,
				'value2'            => Invoice::view_by_id($invoiceid)->reference_no
			);
		App::Log($data);		

		send_payment_email($invoiceid, $paid); // Send email to client
		
		if(config_item('notify_payment_received') == 'TRUE'){
			$cur = App::currencies(Invoice::view_by_id($invoiceid)->currency);
			notify_admin($invoiceid,$paid,$cur->symbol);
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
 