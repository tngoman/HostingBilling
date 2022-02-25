<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class T_ipn extends Hosting_Billing 
{
    function __construct()
    {
        parent::__construct(); 
    }


    function ipn()
    {

        // Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
        // Set this to 0 once you go live or don't require logging.
        define("DEBUG", 1);
        // Set to 0 once you're ready to go live
        $sandbox = (config_item('paypal_live') == 'TRUE') ? 0 : 1;
        define("USE_SANDBOX", $sandbox);

        // Read POST data
        // reading posted data directly from $_POST causes serialization
        // issues with array data in POST. Reading raw POST data from input stream instead.
            $raw_post_data = file_get_contents('php://input');
            $raw_post_array = explode('&', $raw_post_data);
            $myPost = array();
                    foreach ($raw_post_array as $keyval) {
                        $keyval = explode ('=', $keyval);
                        if (count($keyval) == 2)
                            $myPost[$keyval[0]] = urldecode($keyval[1]);
                    }
        // read the post from PayPal system and add 'cmd'
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
            // Without this step anyone can fake IPN data
                if(USE_SANDBOX == true) {
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
                    // CONFIG: Optional proxy configuration
                    //curl_setopt($ch, CURLOPT_PROXY, $proxy);
                    //curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
                    // Set TCP timeout to 30 seconds
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
                    // CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
                    // of the certificate as shown below. Ensure the file is readable by the webserver.
                    // This is mandatory for some environments.
                    //$cert = __DIR__ . "./cacert.pem";
                    //curl_setopt($ch, CURLOPT_CAINFO, $cert);
                    $res = curl_exec($ch);
                    if (curl_errno($ch) != 0) // cURL error
                        {
                        if(DEBUG == true) {
                            log_message('error',"Can't connect to PayPal to validate IPN message: " . curl_error($ch). PHP_EOL);
                        }
                        curl_close($ch);
                        exit;
                    } else {
                    // Log the entire HTTP response if debug is switched on.
                    if(DEBUG == true) {
                        log_message('error',"HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req". PHP_EOL);
                        log_message('error',"HTTP response of validation request: $res" . PHP_EOL);
                    }
                    curl_close($ch);
                }
                    // Inspect IPN validation result and act accordingly
                    // Split response headers and payload, a better way for strcmp
                    $tokens = explode("\r\n\r\n", trim($res));
                    $res = trim(end($tokens));
                    if (strcmp ($res, "VERIFIED") == 0) {

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

                    if(DEBUG == true) {
                            log_message('INFO',"Verified IPN: $req ". PHP_EOL);
                        }
                    } else if (strcmp ($res, "INVALID") == 0) {
                        // log for manual investigation
                        // Add business logic here which deals with invalid IPN messages
                        if(DEBUG == true) {
                            log_message('error',"Invalid IPN: $req" . PHP_EOL);
                        }
                    }
    }

    function _send_payment_email($invoice_id,$paid_amount){

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

    function _notify_admin($invoice,$amount,$cur)
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
}
