<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Crons extends Hosting_Billing 
{

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('email'));
        $this->load->model(array('Cron','Update'));
        $this->load->helper('curl','file'); 

        $this->applib->set_locale();
    }
    

    function run($cron_key = NULL)
    {
        if (config_item('cron_key') == $cron_key) {
            $cron_results = array();
            if (unserialize(config_item('cron_last_run'))) { $cron_results = unserialize(config_item('cron_last_run')); }
            $crons = $this->db->where('hook','cron_job_admin')->where('enabled',1)->order_by('order','ASC')->get('hooks')->result();
            foreach ($crons as $cron) {
                $cron_results[$cron->module] = modules::run($cron->route,$cron_key);
                $this->db->where('hook','cron_job_admin')->where('module',$cron->module)->update('hooks', array('last_run' => date('Y-m-d H:i:s')));
            }
            $this->db->where('config_key','cron_last_run')->update('config', array('value' => serialize($cron_results)));
        }
    }

    
    function email_piping()
    {
         $this->fetch_tickets(); 
    }

   
    public function recur($cron_key = NULL)
    {
        if ($cron_key == config_item('cron_key'))
        {
            $this->load->model('invoices/Invoices_recurring');
            $this->load->model('invoices/Invoices');


            // Gather a list of recurring invoices to generate
            $invoices_recurring = $this->Invoices_recurring->active();

            if (count($invoices_recurring) > 0) {
                foreach ($invoices_recurring as $invoice_recurring)
                {
                    // This is the original invoice id
                    $source_id = $invoice_recurring->inv_id;

                    // This is the original invoice
                    $invoice = $this->Invoices_recurring->get_invoice($source_id,'invoices');

                    // Create the new invoice
                    $db_array = array(
                        'client'       => $invoice->client,
                        'due_date'     => Cron::get_date_due($invoice_recurring->recur_next_date),
                        'reference_no' => config_item('invoice_prefix').Invoice::generate_invoice_number(),
                        'discount'     => $invoice->discount,
                        'tax'          => $invoice->tax,
                        'currency'     => $invoice->currency,
                        'notes'        => $invoice->notes
                    );

                    // This is the new invoice id
                    $target_id = App::save_data('invoices',$db_array);

                    // Copy the original invoice to the new invoice
                    $this->Invoices->copy_invoice($source_id, $target_id);

                    // Update the next recur date for the recurring invoice
                    $this->Invoices_recurring->set_next_recur_date($invoice_recurring->inv_id);

                    // Email the new invoice if applicable
                    if (config_item('automatic_email_on_recur') == 'TRUE')
                    {                        

                        // Record to logs
                        $this->_log_recur_activity($source_id,$new_invoice->reference_no);

                        send_email($target_id, 'email_invoice');

                        $data = array('emailed' => 'Yes', 'date_sent' => date ("Y-m-d H:i:s", time()));
                        Invoice::update($new_invoice->inv_id,$data);

                    }
                }
                return array('success'=> TRUE, 'result' => 'Email sent for '.count($invoices_recurring).' recurring invoices');
            } else {
                return array('success'=> TRUE, 'result' => 'There are no recurring invoices');
            }
        }else{
            log_message('error', 'Wrong CRON Key entered. Please verify your key');
            return array('success'=> FALSE, 'result' => 'Wrong CRON Key entered. Please verify your key');
        }
    }


    function _log_recur_activity($orig_invoice_id,$new_invoice_ref)
    {
        $reference_no = Invoice::view_by_id($orig_invoice_id)->reference_no;
        $random_admin = $this->db->where('role_id','1')->select_min('id')->get('users')->row()->id;

        $data = array(
            'user'              => $random_admin,
            'module'            => 'invoices',
            'module_field_id'   => $orig_invoice_id,
            'activity'          => 'activity_invoice_recur',
            'icon'              => 'fa-tweet',
            'value1'            => $reference_no,
            'value2'            => $new_invoice_ref
        );
        App::Log($data);
        return TRUE;
    }

 
    function invoices($cron_key)
    {
        if (config_item('cron_key') == $cron_key) {
            // Get a list of overdue invoices
            $email_lists = $this->Cron->overdue_invoices();
            if (count($email_lists) > 0) {
                foreach ($email_lists as $inv)
                {                    
                    send_email($inv->inv_id, 'invoice_reminder');

                    if (config_item('sms_gateway') == 'TRUE' && config_item('sms_invoice_reminder') == 'TRUE')
                    {   
                        send_message($inv->inv_id, 'invoice_reminder');
                    }
                    // We have sent an alert email
                    Invoice::update($inv->inv_id,array('alert_overdue' => '1'));
                }
                return array('success'=> TRUE, 'result' => 'Email sent for '.count($email_lists).' overdue invoices');
            }else{
                log_message('error', 'There are no overdue invoices to send emails');
                return array('success'=> TRUE, 'result' => 'There are no overdue invoices');
            }
        }else{
            log_message('error', 'Wrong CRON Key entered. Please verify your key');
            return array('success'=> FALSE, 'result' => 'Wrong CRON Key entered. Please verify your key');
        }
    }



  function accounts($cron_key) 
  {
        if (config_item('cron_key') == $cron_key) {
            // Get a list of account renewals
            $accounts = $this->Cron->get_renewals();
            if (count($accounts) > 0) {
                foreach ($accounts as $acc)
                {
                    $data = array();

                    foreach ($accounts as $account) {
                        if($account->fee <= 0)
                        {
                            continue;
                        }                        
                        if($acc->process_id == $account->process_id) {
                            $data[] = $account;
                        }
                        elseif($acc->client_id == $account->client_id)  {
                            $data[] = $account;
                        }
                    }

                    $this->createInvoice($data);
                }

                return array('success'=> TRUE, 'result' => 'Processed renewals for '.count($accounts).' services.');

            }else{
                log_message('error', 'There are no accounts to renew.');
                return array('success'=> TRUE, 'result' => 'There are no renewals due.');
            }
        }else{
            log_message('error', 'Wrong CRON Key entered. Please verify your key');
            return array('success'=> FALSE, 'result' => 'Wrong CRON Key entered. Please verify your key');
        }
    }



    function suspensions($cron_key) 
    {
        if (config_item('cron_key') == $cron_key) {
            // Get a list of accounts due for suspension
            $accounts = $this->Cron->get_suspensions();
 
            if (count($accounts) > 0) {

                $server_id = 0;

                foreach ($accounts as $account)
                {                                   
                    $this->db->set('status_id', 9); 
                    $this->db->where('id', $account->id);  

                    if($this->db->update('orders')) {

                        if($account->server != null && $server_id != $account->server ) {
                            $server = $this->db->where(array('id'=> $account->server))->get('servers')->row();                            
                        }

                        if($server) 
                        {
                            $package = $this->db->where(array('item_id'=> $account->item_parent))->get('items_saved')->row();
                            $client = Client::view_by_id($account->client_id); 
                            $user = User::view_user($client->primary_contact);
                            $profile = User::profile_info($client->primary_contact); 
                            $details = (object) array(
                                'user' => $user, 
                                'profile' => $profile, 
                                'client' => $client, 
                                'account' => $account,
                                'package' => $package,
                                'server' => $server
                            ); 

                            $result = modules::run($server->type.'/suspend_account', $details); 

                            if (config_item('automatic_email_on_recur') == 'TRUE')
                            {   
                               send_email($account->inv_id, 'service_suspended', $account);
                            }

                            if (config_item('sms_gateway') == 'TRUE' && config_item('sms_service_suspended') == 'TRUE')
                            {   
                               send_message($account->inv_id, 'service_suspended');
                            }
                        } 
                    } 
                }

                return array('success'=> TRUE, 'result' => 'Suspended '.count($accounts).' accounts.');

            } else{
                log_message('error', 'There are no accounts to suspend.');
                return array('success'=> TRUE, 'result' => 'There are no suspensions due.');
            }
        } else{
            log_message('error', 'Wrong CRON Key entered. Please verify your key');
            return array('success'=> FALSE, 'result' => 'Wrong CRON Key entered. Please verify your key');
        }
    }

    
    function terminations($cron_key) 
    {
        if (config_item('cron_key') == $cron_key) {
            // Get a list of accounts due for termination
            $accounts = $this->Cron->get_terminations();
            if (count($accounts) > 0) {

                $server_id = 0;

                foreach ($accounts as $account)
                {
                        $this->db->where('id', $account->id);  
                        if($this->db->delete('orders')) {

                        if($account->server != null && $server_id != $account->server ) {
                            $server = $this->db->where(array('id'=> $account->server))->get('servers')->row();                            
                        }
                        
                        if($server) 
                        {
                            $package = $this->db->where(array('item_id'=> $account->item_parent))->get('items_saved')->row();
                            $client = Client::view_by_id($account->client_id); 
                            $user = User::view_user($client->primary_contact);
                            $profile = User::profile_info($client->primary_contact); 
                            $details = (object) array(
                                'user' => $user, 
                                'profile' => $profile, 
                                'client' => $client, 
                                'account' => $account,
                                'package' => $package,
                                'server' => $server
                            ); 

                            $result = modules::run($server->type.'/terminate_account', $details); 
                        }

                    }
                }

                return array('success'=> TRUE, 'result' => 'Terminated '.count($accounts).' accounts.');

            } else{
                log_message('error', 'There are no accounts to terminate.');
                return array('success'=> TRUE, 'result' => 'There are no terminations due.');
            }
        } else{
            log_message('error', 'Wrong CRON Key entered. Please verify your key');
            return array('success'=> FALSE, 'result' => 'Wrong CRON Key entered. Please verify your key');
        }
    }



    function createInvoice($accounts) 
    {
                $interval = intervals();             
                $data = array(
                    'reference_no' => config_item('invoice_prefix').Invoice::generate_invoice_number(),                    
                    'currency' => config_item('default_currency'),
                    'due_date' => Cron::get_date_due($accounts[0]->renewal_date),
                    'client' => $accounts[0]->client_id
                ); 
           
                if ($invoice_id = App::save_data('invoices', $data)) { 
                    foreach($accounts AS $item) 
                    {   
                        $days = $interval[$item->renewal];                       
                        $count = 0;

                        $parent = Item::view_item($item->item_parent);

                        if($item->type == 'domain' || $item->type == 'domain_only')
                        { 
                            $days = $item->years * 365;
                            $renewal_date = Invoice::get_renewal_date($item->renewal_date, $days);                             
                            if(isset($parent->renewal))
                            {
                                $item->fee = $parent->renewal * $item->years;
                            }

                            else
                            {
                                $tld = explode('.', $item->domain);
                                $ext = $tld[1]; 
                                $parent = $this->db->where('item_name', $ext)->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER')->get('items_saved')->row();
                                if(isset($parent->renewal))
                                {
                                    $item->fee = $parent->renewal * $item->years;
                                }
                            }
                        }
                        
                        else
                        {                             
                            $renewal_date = Cron::get_processing_date($item->renewal_date, $days);  
 
                            if($item->promo == 0 && $parent->price_change == 'Yes')
                            { 
                               $parent = $this->db->where('items_saved.item_id', $item->item_parent)->join('item_pricing','items_saved.item_id = item_pricing.item_id','INNER')->get('items_saved')->row();   
                               $renewal = $item->renewal;
                            
                               if(isset($parent->$renewal))
                               {
                                  $item->fee = $parent->$renewal;
                                  
                               }                       
                            }                             
                        }  
                        
                        $tax = Applib::format_deci((null != $parent->item_tax_rate && $parent->item_tax_rate > 0) ? ($item->fee * $parent->item_tax_rate) / 100 : '0.00');
  
                        $items = array(
                                    'invoice_id' 	 => $invoice_id,
                                    'item_name'		 => ($item->type == 'domain') ? lang('domain_renewal') : $item->item_name,
                                    'item_desc'		 => $item->domain." ".$item->renewal_date. " - ".$renewal_date,
                                    'unit_cost'		 => $item->fee,
                                    'item_order'	 => 1,
                                    'item_tax_rate'	 => $parent->item_tax_rate,
                                    'item_tax_total' => $tax,
                                    'quantity'		 => 1,
                                    'total_cost'	 => $item->fee + $tax
                                    );            

                        if($item_id = App::save_data('items', $items)) {          
                            $update = array(
                                "invoice_id" => $invoice_id,
                                "processed" => date('Y-m-d'),
                                "fee"       => $item->fee,
                                "counter" => $item->counter+1,
                                "renewal_date" => $renewal_date,
                                "item" => $item_id	
                            );                                                        
                            
                            $this->db->where('id', $item->id);
                            $this->db->update('orders', $update);

                            $order = $this->db->where('id', $item->id)->get('orders')->row();  

                            $interval = $item->$renewal;
                            if($order->counter == $interval ."_payments") {
                                $this->db->set('status_id', 2)->where('id', $item->id)->update('orders');                                
                            }
 
                            if (config_item('automatic_email_on_recur') == 'TRUE')
                            {   
                               send_email($invoice_id, 'email_invoice');
                            }

                            if (config_item('sms_gateway') == 'TRUE' && config_item('sms_invoice') == 'TRUE')
                            {   
                              send_message($invoice_id, 'invoice');
                            }
                                                           
                         }        
                    } 
            }
    }



    function backup_db($cron_key = NULL) 
    {
        if (config_item('auto_backup_db')) {
            if (config_item('cron_key') == $cron_key) {
                $this->load->helper('file');
                $this->load->dbutil();
                $prefs = array('format' => 'zip', 'filename' => 'database-full-backup_' . date('Y-m-d'));

                $backup = $this->dbutil->backup($prefs);

                if (!write_file('./resource/backup/database-full-backup_' . date('Y-m-d') . '.zip', $backup)) {
                    log_message('error', 'Unable to write to ./resource/backup folder');
                    return array('success' => FALSE, 'result' => 'Unable to write to ./resource/backup folder');
                }
                return array('success' => TRUE, 'result' => 'Database backed up successfully');
            } else {
                log_message('error', 'Wrong CRON Key entered. Please verify your key');
                return array('success' => FALSE, 'result' => 'Wrong CRON Key entered. Please verify your key');
            }
        }
        return array('success' => FALSE, 'result' => 'Auto backup is disabled in the settings');
    }



    function outgoing_emails($cron_key)
    {
        if (config_item('cron_key') == $cron_key) {
            // Get a list of messages
            $email_lists = $this->db->where('delivered','0')->get('outgoing_emails')->result();
            if (count($email_lists) > 0) {
                foreach ($email_lists as $em)
                {

                    $params = array(
                        'recipient'     => $em->sent_to,
                        'subject'       => $em->subject,
                        'message'       => $em->message,
                        'attached_file'  => ''
                    );
                    modules::run('fomailer/send_email',$params);
                    // We have sent an alert email
                    $this->db->where('id',$em->id)->update('outgoing_emails',array('delivered' => '1'));
                    // Clean outgoing emails table
                    $this->db->truncate('outgoing_emails');
                }
                return array('success'=> TRUE, 'result' => count($email_lists).' outgoing emails sent');
            }else{
                log_message('error', 'There are no outgoing emails to send');
                return array('success'=> TRUE, 'result' => 'There are no outgoing emails');
            }
        }else{
            log_message('error', 'Wrong CRON Key entered. Please verify your key');
            return array('success'=> FALSE, 'result' => 'Wrong CRON Key entered. Please verify your key');
        }
    }



    function close_tickets($cron_key)
    {
        if(config_item('auto_close_ticket') > 0){
            if (config_item('cron_key') == $cron_key) {
                // Get a list of inactive tickets
                $tickets = $this->db->where(array('status !='=>'closed','archived_t'=>'0'))->get('tickets')->result();

                $close_tickets = array();
                $auto_close_ticket_sec = config_item('auto_close_ticket') * 86400;

                foreach ($tickets as $key => $t) {
                    $ticket_created = strtotime($t->created);

                    $reply_id = $this->db->select_max('id')->where(array('ticketid' => $t->id))
                        ->get('ticketreplies')->row();
                    if($reply_id->id > 0){
                        $row_data = $this->db->where(array('id' =>  $reply_id->id))->get('ticketreplies')->row();
                        $ticket_last_reply = strtotime($row_data->time);

                        if((time() - $ticket_last_reply) > $auto_close_ticket_sec){
                            $close_tickets[] = $t->id;
                        }
                    }

                }

                if(count($close_tickets) > 0){
                    $this->_close_tickets($close_tickets);
                    return array('success'=> TRUE, 'result' => count($close_tickets).' tickets closed');
                }else{
                    return array('success'=> TRUE, 'result' => 'There are no inactive tickets');
                }


            }else{
                log_message('error', 'Wrong CRON Key entered. Please verify your key');
                return array('success'=> FALSE, 'result' => 'Wrong CRON Key entered. Please verify your key');
            }
        }
        return array('success'=> FALSE, 'result' => 'Auto close ticket is disabled');
    }


    function _close_tickets($tickets)
    {
        foreach ($tickets as $t) {

            $message = App::email_template('auto_close_ticket','template_body');
            $subject = App::email_template('auto_close_ticket','subject');
            $signature = App::email_template('email_signature','template_body');

            $info = Ticket::view_by_id($t);
            $recipient = User::login_info($info->reporter)->email;

            $logo_link = create_email_logo();

            $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

            $strReporter = str_replace("{REPORTER_EMAIL}",$recipient,$logo);
            $strCode = str_replace("{TICKET_CODE}",$info->ticket_code,$strReporter);
            $title = str_replace("{SUBJECT}",$info->subject,$strCode);
            $strStatus =  str_replace("{TICKET_STATUS}",'closed',$title);
            $strLink = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$t,$strStatus);
            $signature = str_replace("{SIGNATURE}",$signature,$strLink);
            $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);


            $params = array(
                'recipient'     => $recipient,
                'subject'       => $subject,
                'message'       => $message,
                'attached_file' => '',
                'alt_email'     => 'support'
            );

            modules::run('fomailer/send_email',$params);

            // We have sent an alert email
            $this->db->where('id',$t)->update('tickets',array('status' => 'closed'));
        }
    }


    
    function xrates($cron_key) 
    {
        if (config_item('cron_key') == $cron_key) {

            if(config_item('update_xrates') == 'FALSE'){
                return array('success'=> TRUE, 'result' => 'Rate updates are disabled');
            }

            if (config_item('xrates_app_id') == '') {
                return array('success'=> FALSE, 'result' => 'App ID is blank. Rates updated');
            }
            $this->load->helper('curl');
            $last_check = config_item('xrates_check');
            $base_currency = config_item('default_currency');

            $url = "https://openexchangerates.org/api/latest.json?";
            $url .= "app_id=".config_item('xrates_app_id')."&base=USD"; 

            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true
            );
            if (!ini_get('safe_mode') && !ini_get('open_basedir')) {
                $options[CURLOPT_FOLLOWLOCATION] = true;
            }
            curl_setopt_array($ch, $options);
            $output = curl_exec($ch);
            curl_close($ch);
            $xrates = json_decode($output, TRUE);

            if (isset($xrates['error'])) {
                log_message('error', $xrates['description']);
            } else {
                if (!is_array($xrates)) { 
                    return array('success'=> FALSE, 'result' => 'There was an issue with the data'); 
                }

                $rates = $xrates['rates'];

                $this->db->where('code',config_item('default_currency'))->update('currencies',array('xrate' => '1'));

                $allcurrencies = $this->db->get('currencies')->result();
                    
                foreach ($allcurrencies as $cur) {
                    $currencies[] = $cur->code;
                }
                foreach ($rates as $cc => $xr) {
                    if (in_array($cc, $currencies)) {
                        $xr = number_format($xr, 5, '.','');
                        $this->db->where('code',$cc)->update('currencies',array('xrate' => $xr));
                    }
                }
                $this->db->where('config_key','xrates_check')->update('config',array("value"=>date("Y-m-d", time())));
                return array('success'=> TRUE, 'result' => 'Exchange rates updated');
            }
        } else {
            log_message('error', 'Wrong CRON Key entered. Please verify your key');
            return array('success'=> FALSE, 'result' => 'Wrong CRON Key entered. Please verify your key');
        }

    }


    function fetch_tickets()
    {
        if(config_item('email_piping') == 'TRUE'){
            if(config_item('mail_imap_host') != '' && config_item('mail_password') != ''
                && config_item('mail_username') != ''){

                $this->load->library('Peeker');
                $peeker = new Peeker();
                $this->load->helper('string');

                $tblTickets = 'hd_tickets';
                $tblUsers = 'hd_users';
                $tblReplies = 'hd_ticketreplies';

                $this->load->library('encryption');
                $this->encryption->initialize(
                    array(
                            'cipher' => 'aes-256',
                            'driver' => 'openssl',
                            'mode' => 'ctr'
                        )
                    );

                $config['login']         = config_item('mail_username');
                $config['pass']          = $this->encryption->decrypt(config_item('mail_password'));
                $config['host']          = config_item('mail_imap_host');
                $config['port']          = config_item('mail_port');
                $config['mailbox']       = config_item('mailbox');

                if(config_item('mail_imap') == 'TRUE'){ $flags = "/imap"; }else{ $flags = "/pop3"; }
                if(config_item('mail_ssl') == 'TRUE'){ $flags .= "/ssl"; }

                $config['service_flags'] = $flags.config_item('mail_flags');

                $peeker->initialize($config);

                $bool = $peeker->set_attachment_dir('./resource/attachments/');
                //Search Filter
                $peeker->set_search(config_item('mail_search'));

                if ($peeker->search_and_count_messages() != "0")
                {

                    log_message('error', 'CRON fetched '.$peeker->search_and_count_messages().' new email tickets.');

                    $id_array = $peeker->get_ids_from_search();
                    //walk trough emails
                    foreach($id_array as $email_id){
                
                        $email = $peeker->get_message($email_id);
                        $emailaddr = $email->get_from_array();
                        $emailaddr = $emailaddr[0]->mailbox.'@'.$emailaddr[0]->host; // Sender email

                        $emailbody = utf8_encode((nl2br($email->get_plain())));
                        $email_to = $email->get_to();
                        $subject = $email->get_subject();
                        $from = $emailaddr;

                        $ticketCode = $this->extractCode($subject, '[', ']'); // Get Ticket Code
                        $sql = "SELECT * FROM $tblTickets WHERE ticket_code='$ticketCode'";
                        $ticket = $this->db->query($sql);

                        // Check if ticket code exists
                        if($ticket->num_rows() > 0){
                            $ticket = $ticket->row();
                            $ticketId = $ticket->id;
                            $sql = "SELECT * FROM $tblUsers WHERE email='$from'";
                            $userInfo = $this->db->query($sql);
                            // Check if user with email address exists
                            if($userInfo->num_rows() > 0){
                                $userInfo = $userInfo->row();
                                $userId = $userInfo->id;
                                $data = array('ticketid' => $ticketId,
                                    'body'     => strpos($emailbody, "wrote:") ? substr($emailbody, 0, strpos($emailbody, "wrote:")) : $emailbody,
                                    'replier'  => $from,
                                    'replierid'=> $userId
                                );
                                $reply_id = Ticket::save_data('ticketreplies',$data); // Save Reply
                                Ticket::update_data('tickets',array('id'=>$ticketId),array('status' => 'pending'));

                                if ($reply_id > 0) {

                                    (User::login_info($userId)->role_id == '2')
                                        ? $this->_notify_ticket_reply('admin',$ticketId,$reply_id)
                                        : $this->_notify_ticket_reply('client',$ticketId,$reply_id);

                                    //Attachments
                                    $parts = $email->get_parts_array();

                                    if($email->has_attachment()){
                                        foreach ($parts as $part)
                                        {
                                            $savename = $email->get_fingerprint().random_string('alnum', 8).$part->get_filename();
                                            $orgname = $part->get_filename();
                                            $part->filename = $orgname;
                                            $orgname = preg_replace('/[^a-z0-9_\-\.]/i', '_', $orgname);
                                            $data = array('attachment' => $orgname);
                                            Ticket::update_data('ticketreplies',array('id'=>$reply_id),$data);
                                        }
                                        $email->save_all_attachments('./resource/attachments/');
                                    }


                                    $params = array('subject' => $subject,
                                        'message' => 'Ticket #'.$ticketCode.' reply submitted from '.$from,
                                        'attached_file' => '',
                                        'alt_email' => 'support',
                                        'recipient' => $from
                                    );

                                    modules::run('fomailer/send_email',$params);

                                    // return array('success'=> TRUE, 'result' => 'Ticket replied successfully');

                                } else { // Failed saving reply
                                    return array('success'=> FALSE, 'result' => 'Error saving reply to DB');
                                    // $this->_errorLog();
                                }

                            }else{ // User email not found
                                return array('success'=> FALSE, 'result' => $from.' cannot be found in the DB');
                                // $this->_errorLog();
                            }

                        }else{
                            // Create a new ticket
                            $user_exist = $this->db->where('email',$from)->get($tblUsers)->num_rows();
                            if($user_exist > 0){

                                $user_id = $this->db->where('email',$from)->get($tblUsers)->row()->id;
                                $code = Ticket::generate_code();
                                $subject = '['.$code.'] : '.$subject;
                                $data = array(
                                    'subject' => $subject,
                                    'ticket_code' => $code,
                                    'department' => config_item('ticket_default_department'),
                                    'priority' => 'High',
                                    'body' => $emailbody,
                                    'status' => 'open',
                                    'reporter' => $user_id,
                                    'created' => date("Y-m-d H:i:s",time())
                                );

                                $ticket_id = Ticket::save_data('tickets',$data);

                                if($ticket_id > 0){
                                    // Send email to Staff
                                    $this->_send_email_to_staff($ticket_id);
                                    // Send email to Client
                                    $this->_send_email_to_client($ticket_id);

                                    $data = array(
                                        'module' => 'tickets',
                                        'module_field_id' => $ticket_id,
                                        'user' => $user_id,
                                        'activity' => 'activity_ticket_created',
                                        'icon' => 'fa-ticket',
                                        'value1' => $subject,
                                        'value2' => ''
                                    );
                                    App::Log($data);
                                }

                                //Attachments
                                $parts = $email->get_parts_array();

                                if($email->has_attachment()){
                                    foreach ($parts as $part)
                                    {
                                        $savename = $email->get_fingerprint().random_string('alnum', 8).$part->get_filename();
                                        $orgname = $part->get_filename();
                                        $part->filename = $orgname;
                                        $orgname = preg_replace('/[^a-z0-9_\-\.]/i', '_', $orgname);
                                        $data = array('attachment' => $orgname);
                                        Ticket::update_data('tickets',array('id'=>$ticket_id),$data);
                                    }
                                    $email->save_all_attachments('./resource/attachments/');
                                }

                                log_message('error', 'New ticket created #'.$code);
                                // return array('success'=> TRUE, 'result' => '#'.$code.' has been created');
                            }
                        }



                    }

                    $peeker->close();

                    // now tell us the story of the connection
                    //  print_r($peeker->trace());

                }
                return array('success'=> TRUE, 'result' => 'Tickets fetched');

            }else{
                return array('success'=> FALSE, 'result' => 'IMAP Configuration is blank');
            }
    }else{
                return array('success'=> FALSE, 'result' => 'Email tickets disabled!');
            }
    }



    function _send_email_to_staff($id)
    {
        if (config_item('email_staff_tickets') == 'TRUE') {

            $message = App::email_template('ticket_staff_email','template_body');
            $subject = App::email_template('ticket_staff_email','subject');
            $signature = App::email_template('email_signature','template_body');

            $info = Ticket::view_by_id($id);

            $reporter_email = User::login_info($info->reporter)->email;

            $logo_link = create_email_logo();

            $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

            $code = str_replace("{TICKET_CODE}",$info->ticket_code,$logo);
            $title = str_replace("{SUBJECT}",$info->subject,$code);
            $reporter = str_replace("{REPORTER_EMAIL}",$reporter_email,$title);
            // $UserEmail =
            $link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$reporter);
            $signature = str_replace("{SIGNATURE}",$signature,$link);
            $message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);

            $subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']',$subject);
            $subject = str_replace("[SUBJECT]",$info->subject,$subject);

            $params['subject'] = $subject;

            $params['attached_file'] = '';
            $params['alt_email'] = 'support';

            $staff_members = User::team();
            // Send email to staff department
            foreach ($staff_members as $key => $user) {
                $dep = json_decode(User::profile_info($user->id)->department,TRUE);
                if (is_array($dep) && in_array($info->department, $dep)) {
                    $email = User::login_info($user->id)->email;
                    $params['message'] = str_replace("{USER_EMAIL}",$email,$message);
                    $params['recipient'] = $email;
                    modules::run('fomailer/send_email',$params);
                }
            }

            return TRUE;

        }else{
            return TRUE;
        }

    }



    function _send_email_to_client($id)
    {
        $message = App::email_template('ticket_client_email','template_body');
        $subject = App::email_template('ticket_client_email','subject');
        $signature = App::email_template('email_signature','template_body');

        $info = Ticket::view_by_id($id);

        $email = User::login_info($info->reporter)->email;

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $client_email = str_replace("{CLIENT_EMAIL}",$email,$logo);
        $ticket_code = str_replace("{TICKET_CODE}",$info->ticket_code,$client_email);
        $title = str_replace("{SUBJECT}",$info->subject,$ticket_code);
        $ticket_link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$title);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$ticket_link);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);
        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);

        $subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']',$subject);
        $subject = str_replace("[SUBJECT]",$info->subject,$subject);

        $params['recipient'] = $email;
        $params['subject'] = $subject;
        $params['message'] = $message;
        $params['attached_file'] = '';
        $params['alt_email'] = 'support';

        modules::run('fomailer/send_email',$params);
        return TRUE;

    }

 


    function _notify_ticket_reply($group,$id,$reply_id)
    {
        if (config_item('notify_ticket_reply') == 'TRUE') {

            $message = App::email_template('ticket_reply_email','template_body');
            $subject = App::email_template('ticket_reply_email','subject');
            $signature = App::email_template('email_signature','template_body');

            $info = Ticket::view_by_id($id);
            $reply = $this->db->where('id',$reply_id)->get('ticketreplies')->row();


            $logo_link = create_email_logo();

            $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

            $code = str_replace("{TICKET_CODE}",$info->ticket_code,$logo);
            $title = str_replace("{SUBJECT}",$info->subject,$code);
            $status = str_replace("{TICKET_STATUS}",ucfirst($info->status),$title);
            $link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$status);
            $body = str_replace("{TICKET_REPLY}",$reply->body,$link);
            $EmailSignature = str_replace("{SIGNATURE}",$signature,$body);

            $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

            $subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']',$subject);
            $subject = str_replace("[SUBJECT]",$info->subject,$subject);

            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);

            $params['subject'] = $subject;
            $params['message'] = $message;
            $params['attached_file'] = '';
            $params['alt_email'] = 'support';



            switch ($group) {
                case 'admin':
                    // Send to admins
                    if(count(User::admin_list())){
                        foreach (User::admin_list() as $u)
                        {
                            $params['recipient'] = User::login_info($u->id)->email;
                            modules::run('fomailer/send_email',$params);
                        }
                    }

                    return TRUE;
                    break;

                default:
                    $params['recipient'] = User::login_info($info->reporter)->email;
                    modules::run('fomailer/send_email',$params);

                    return TRUE;
                    break;
            }

        }
    }



    function _errorLog()
    {
        log_message('error', 'Ticket reply failed to save');
    }



    function extractCode($string, $start, $end) 
    {
        $string = " ".$string;
        $ini = strpos($string, $start);
        if ($ini == 0) return "";
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
     

}

/* End of file crons.php */