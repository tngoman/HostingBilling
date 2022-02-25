<?php
/* Module Name: ISPConfig
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Servers
 * Description: ISPConfig API Integration.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */


class Ispconfig extends Hosting_Billing
{      
    
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


    public function ispconfig_package_config ($values)
    {
        $config = array(

            array(
                'label' => 'Template ID',
                'id' => 'template_id',
                'placeholder' => 'The template ID number as it appears in ISPConfig',
                'value' => isset($values['template_id']) ? $values['template_id'] : ''

            ), 

            array(
                'label' => 'Shell Access',
                'id' => 'shell_access',
                'type' => 'dropdown',
                'options' => array(
                        'no' => 'No',
                        'jailkit' => 'Jailkit'
                ),
                'value' => isset($values['shell_access']) ? $values['shell_access'] : 'no'
            ),  

            array(
                'id' => 'create_website',
                'label' => 'Create Website',
                'type' => 'radio',
                'options' => array(
                        array(
                                'id' => 'create_website',
                                'value' => 'y',
                                'label' => 'Yes',
                                'checked' => isset($values['create_website']) ? ($values['create_website'] == 'y') ? TRUE: FALSE: FALSE
                        ),
                        array(
                                'id' => 'create_website',
                                'value' => 'n',
                                'label' => 'No',
                                'checked' => isset($values['create_website']) ? ($values['create_website'] == 'n') ? TRUE: FALSE: FALSE
                        )
                ),
                
            ),

            array(
                'label' => 'PHP Mode',
                'id' => 'php_mode',
                'type' => 'dropdown',
                'options' => array(
                        'no' => 'No',
                        'cgi' => 'CGI',
                        'fast-cgi' => 'Fast_CGI',
                        'php-fpm' => 'PHP-FPM',
                        'hhvm' => 'HHVM' 
                ),
                'value' => isset($values['php_mode']) ? $values['php_mode'] : 'no'
            ),

            array(
                'id' => 'storage_quota',
                'label' => 'Website Storage Quota',
                'input_addons' => array( 
                        'post' => 'MB'
                ),
                'value' => isset($values['storage_quota']) ? $values['storage_quota'] : '0'
            ),
            
            array(
                'id' => 'traffic_quota',
                'label' => 'Website Traffic Quota',
                'input_addons' => array( 
                        'post' => 'MB'
                ),
                'value' => isset($values['traffic_quota']) ? $values['traffic_quota'] : '0'
            ), 

            array(
                'id' => 'enable_cgi',
                'label' => 'Website Setting: Enable CGI',
                'type' => 'radio',
                'options' => array(
                        array(
                                'id' => 'enable_cgi',
                                'value' => 'y',
                                'label' => 'Yes',
                                'checked' => isset($values['enable_cgi']) ? ($values['enable_cgi'] == 'y') ? TRUE: FALSE: FALSE
                        ),
                        array(
                                'id' => 'enable_cgi',
                                'value' => 'n',
                                'label' => 'No',
                                'checked' => isset($values['enable_cgi']) ? ($values['enable_cgi'] == 'n') ? TRUE: FALSE: FALSE
                        )
                ),
                
            ),
            
            array(
                'id' => 'enable_ssi',
                'label' => 'Website Setting: Enable SSI',
                'type' => 'radio',
                'options' => array(
                        array(
                                'id' => 'enable_ssi',
                                'value' => 'y',
                                'label' => 'Yes',
                                'checked' => isset($values['enable_ssi']) ? ($values['enable_ssi'] == 'y') ? TRUE: FALSE: FALSE
                        ),
                        array(
                                'id' => 'enable_ssi',
                                'value' => 'n',
                                'label' => 'No',
                                'checked' => isset($values['enable_ssi']) ? ($values['enable_ssi'] == 'n') ? TRUE: FALSE: FALSE
                        )
                )
            ),

            array(
                'id' => 'enable_ruby',
                'label' => 'Website Setting: Enable Ruby',
                'type' => 'radio',
                'options' => array(
                        array(
                                'id' => 'enable_ruby',
                                'value' => 'y',
                                'label' => 'Yes',
                                'checked' => isset($values['enable_ruby']) ? ($values['enable_ruby'] == 'y') ? TRUE: FALSE: FALSE
                        ),
                        array(
                                'id' => 'enable_ruby',
                                'value' => 'n',
                                'label' => 'No',
                                'checked' => isset($values['enable_ruby']) ? ($values['enable_ruby'] == 'n') ? TRUE: FALSE: FALSE
                        )
                )
            ),

            array(
                'id' => 'force_suexec',
                'label' => 'Website Setting: Force SuExec',
                'type' => 'radio',
                'options' => array(
                        array(
                                'id' => 'force_suexec',
                                'value' => 'y',
                                'label' => 'Yes',
                                'checked' => isset($values['force_suexec']) ? ($values['force_suexec'] == 'y') ? TRUE: FALSE: FALSE
                        ),
                        array(
                                'id' => 'force_suexec',
                                'value' => 'n',
                                'label' => 'No',
                                'checked' => isset($values['force_suexec']) ? ($values['force_suexec'] == 'n') ? TRUE: FALSE: FALSE
                        )
                )
            ),

            array(
                'label' => 'Create Auto-Subdomain',
                'id' => 'create_subdomain',
                'type' => 'dropdown',
                'options' => array(
                        'none' => 'None',
                        'www' => 'www',
                        '*' => '*'                        
                ),
                'value' => isset($values['create_subdomain']) ? $values['create_subdomain'] : 'none'
            ),

            array(
                'id' => 'create_ftp',
                'label' => 'Create FTP Account',
                'type' => 'radio',
                'options' => array(
                        array(
                                'id' => 'create_ftp',
                                'value' => 'y',
                                'label' => 'Yes',
                                'checked' => isset($values['create_ftp']) ? ($values['create_ftp'] == 'y') ? TRUE: FALSE: FALSE
                        ),
                        array(
                                'id' => 'create_ftp',
                                'value' => 'n',
                                'label' => 'No',
                                'checked' => isset($values['create_ftp']) ? ($values['create_ftp'] == 'n') ? TRUE: FALSE: FALSE
                        )
                )
            ),
             
            array(
                'id' => 'create_dns_zone',
                'label' => 'Create DNS Zone',
                'type' => 'radio',
                'options' => array(
                        array(
                                'id' => 'create_dns_zone',
                                'value' => 'y',
                                'label' => 'Yes',
                                'checked' => isset($values['create_dns_zone']) ? ($values['create_dns_zone'] == 'y') ? TRUE: FALSE: FALSE
                        ),
                        array(
                                'id' => 'create_dns_zone',
                                'value' => 'n',
                                'label' => 'No',
                                'checked' => isset($values['create_dns_zone']) ? ($values['create_dns_zone'] == 'n') ? TRUE: FALSE: FALSE
                        )
                )
            ),

            array(
                'label' => 'DNS Template ID',
                'id' => 'dns_template_id',
                'placeholder' => 'The DNS Template ID number as it appears in ISPConfig',
                'value' => isset($values['dns_template_id']) ? $values['dns_template_id'] : ''
            ),

            array(
                'id' => 'create_mail_domain',
                'label' => 'Create Mail Domain',
                'type' => 'radio',
                'options' => array(
                        array(
                                'id' => 'create_mail_domain',
                                'value' => 'y',
                                'label' => 'Yes',
                                'checked' => isset($values['create_mail_domain']) ? ($values['create_mail_domain'] == 'y') ? TRUE: FALSE: FALSE
                        ),
                        array(
                                'id' => 'create_mail_domain',
                                'value' => 'n',
                                'label' => 'No',
                                'checked' => isset($values['create_mail_domain']) ? ($values['create_mail_domain'] == 'n') ? TRUE: FALSE: FALSE
                        )
                )
            ),

            array(
                'id' => 'enable_account',
                'label' => 'Enable account on creation',
                'type' => 'radio',
                'options' => array(
                        array(
                                'id' => 'enable_account',
                                'value' => 'y',
                                'label' => 'Yes',
                                'checked' => isset($values['enable_account']) ? ($values['enable_account'] == 'y') ? TRUE: FALSE: FALSE
                        ),
                        array(
                                'id' => 'enable_account',
                                'value' => 'n',
                                'label' => 'No',
                                'checked' => isset($values['enable_account']) ? ($values['enable_account'] == 'n') ? TRUE: FALSE: FALSE
                        )
                )
            ) 
        ); 
        
        return $config;
        
    }



    public function check_connection ($server = NULL)
    {         
        $this->load->library('ispconfig/ISP_exec', $server); 
        $response = $this->isp_exec->authenticate();
        if($response == true)
        {
            if($this->isp_exec->soap_session_id)
            {
                return 'OK';
            }
            else 
            {
                return $response;
            }
        }
        else 
        {
            return $this->isp_exec->error;
        } 
    }



    public function create_account($params)
    {     
                $config = (object) unserialize($params->package->package_config);  
                $fullname = htmlspecialchars_decode($params->profile->fullname);
                $companyname = htmlspecialchars_decode($params->client->company_name);
                $address = $params->client->company_address;
                if (!empty($params->client->company_address_two)) {
                        $address = $params->client->company_address .$params->client->company_address_two;
                }
                $accountid = $params->client->co_id;
                $productid = $config->template_id;
                $domain = $params->account->domain;
                $zip = $params->client->zip;
                $city = $params->client->city;
                $state = $params->client->state;
                $country = App::country_code($params->client->country);
                $email = $params->client->company_email;
                $phonenumber = $params->client->company_phone;                
                $username = $params->user->username;
                $password = $params->account->password;
                $templateid = $config->template_id;
                $shellaccess = $config->shell_access;
                $createweb = $config->create_website;
                $phpmode = $config->php_mode;
                $phpversion = '';
                $webstorage = $config->storage_quota;
                $webtraffic = $config->traffic_quota;
                $webenablecgi = $config->enable_cgi;
                $webenablessi = $config->enable_ssi;
                $webenableruby = $config->enable_ruby;
                $webenablesuexec =  $config->force_suexec;
                $webenableperl = '';
                $webenablepython = '';
                $webautosubdomain = $config->create_subdomain;
                $webcreateftp = $config->create_ftp;
                $createdns = $config->create_dns_zone;
                $dnstemplateid = $config->dns_template_id;
                $createmail = $config->create_mail_domain;                
                $active = $config->enable_account;  
                
                $this->load->library('ispconfig/ISP_exec', $params->server);                                  
                if($this->isp_exec->authenticate()) {            
                
                                if(isset($params->server->hostname)) {
                                        $nameserver = 0;
                                        $nameserverslave = 0;
                                        $defaultwebserver = 0;
                                        $defaultfileserver = 0;
                                        $defaultdbserver = 0;
                                        $defaultmailserver = 0;
                                        $defaultmailserverip = 0;
                                        $defaultdnsserver = 0;
                                        $index = 0;
                                        $server = array();
                                        $servermaster = array();
                                        
                                        $soap_result = $this->isp_exec->soap_client->server_get_serverid_by_ip($this->isp_exec->soap_session_id, $params->server->hostname);
                                        $soap_result[0] = isset($soap_result[0]) ? $soap_result[0] : $soap_result;
                                        $serverservices = $this->isp_exec->soap_client->server_get_functions($this->isp_exec->soap_session_id, $soap_result[0]['server_id']);
                                        
                                        if($serverservices['web_server'] == 1) {
                                                $defaultwebserver = $soap_result[0]['server_id'];
                                                if($serverservices['file_server'] == 1) {
                                                        $defaultfileserver = $soap_result[0]['server_id'];
                                                }
                                                if($serverservices['db_server'] == 1) {
                                                        $defaultdbserver = $soap_result[0]['server_id'];
                                                }
                                        }
                                        
                                        $soap_result = $this->isp_exec->soap_client->server_get_all($this->isp_exec->soap_session_id);

                                        $mail = 0;
                                        $mastermail = 0;
                                        $dns = 0;
                                        $masterdns = 0;
                                        $web = 0;
                                        $masterweb = 0;
                                        $file = 0;
                                        $masterfile = 0;
                                        $db = 0;
                                        $masterdb = 0; 
                                        
                                        foreach($soap_result as $index => $server) {
                                        $serverservices = $this->isp_exec->soap_client->server_get_functions($this->isp_exec->soap_session_id, $soap_result[$index]['server_id']);                    
                                
                                                if($serverservices['mail_server'] == 1) {
                                                        $server['mail_server'][$mail]['server_id'] = $soap_result[$index]['server_id'];
                                                        $server['mail_server'][$mail]['server_name'] = $soap_result[$index]['server_name'];
                                                        if($serverservices['mirror_server_id'] == 0) {
                                                                $servermaster['mail_server'][$mastermail]['server_id'] = $soap_result[$index]['server_id'];
                                                                $servermaster['mail_server'][$mastermail]['server_name'] = $soap_result[$index]['server_name'];
                                                                ++$mastermail;
                                                        }
                                                        ++$mail;
                                                }
                                                if($serverservices['dns_server'] == 1) {
                                                        $server['dns_server'][$dns]['server_id'] = $soap_result[$index]['server_id'];
                                                        $server['dns_server'][$dns]['server_name'] = $soap_result[$index]['server_name'];
                                                        if($serverservices['mirror_server_id'] == 0) {
                                                                $servermaster['dns_server'][$masterdns]['server_id'] = $soap_result[$index]['server_id'];
                                                                $servermaster['dns_server'][$masterdns]['server_name'] = $soap_result[$index]['server_name'];
                                                                ++$masterdns;
                                                        }
                                                        ++$dns;
                                                }
                                                if($serverservices['web_server'] == 1) {
                                                        $server['web_server'][$web]['server_id'] = $soap_result[$index]['server_id'];
                                                        $server['web_server'][$web]['server_name'] = $soap_result[$index]['server_name'];
                                                        if($serverservices['mirror_server_id'] == 0) {
                                                                $servermaster['web_server'][$masterweb]['server_id'] = $soap_result[$index]['server_id'];
                                                                $servermaster['web_server'][$masterweb]['server_name'] = $soap_result[$index]['server_name'];
                                                                ++$masterweb;
                                                        }
                                                        ++$web;
                                                }
                                                if($serverservices['file_server'] == 1) {
                                                        $server['file_server'][$file]['server_id'] = $soap_result[$index]['server_id'];
                                                        $server['file_server'][$file]['server_name'] = $soap_result[$index]['server_name'];
                                                        if($serverservices['mirror_server_id'] == 0) {
                                                                $servermaster['file_server'][$masterfile]['server_id'] = $soap_result[$index]['server_id'];
                                                                $servermaster['file_server'][$masterfile]['server_name'] = $soap_result[$index]['server_name'];
                                                                ++$masterfile;
                                                        }
                                                        ++$file;
                                                }
                                                if($serverservices['db_server'] == 1) {
                                                        $server['db_server'][$db]['server_id'] = $soap_result[$index]['server_id'];
                                                        $server['db_server'][$db]['server_name'] = $soap_result[$index]['server_name'];
                                                        if($serverservices['mirror_server_id'] == 0) {
                                                                $servermaster['db_server'][$masterdb]['server_id'] = $soap_result[$index]['server_id'];
                                                                $servermaster['db_server'][$masterdb]['server_name'] = $soap_result[$index]['server_name'];
                                                                ++$masterdb;
                                                        }
                                                        ++$db;
                                                }
                                                 
                                        }
                              
                                        
                                        if($defaultwebserver == 0) {  
                                                if(count($servermaster['web_server']) == 1) {
                                                        $defaultwebserver = $servermaster['web_server'][0]['server_id'];
                                                }
                                                else {
                                                        $rand = rand(0,(count($servermaster['web_server']) - 1));
                                                        $defaultwebserver = $servermaster['web_server'][$rand]['server_id'];
                                                }
                                                
                                                if(count($servermaster['file_server']) == 1) {
                                                        $defaultfileserver = $servermaster['file_server'][0]['server_id'];
                                                }
                                                else {
                                                        $rand = rand(0,(count($servermaster['file_server']) - 1));
                                                        $defaultfileserver = $servermaster['file_server'][$rand]['server_id'];
                                                }
                                                
                                                if(count($servermaster['db_server']) == 1) {
                                                        $defaultdbserver = $servermaster['db_server'][0]['server_id'];
                                                }
                                                else {
                                                        $rand = rand(0,(count($servermaster['db_server']) - 1));
                                                        $defaultdbserver = $servermaster['db_server'][$rand]['server_id'];
                                                }
                                        }
                                        
                                        if(count($servermaster['mail_server']) == 1) {
                                                $defaultmailserver = $servermaster['mail_server'][0]['server_id'];
                                                $defaultmailservername = $servermaster['mail_server'][0]['server_name'];
                                        }
                                        else {  
                                                $rand = rand(0,(count($servermaster['mail_server']) - 1));
                                                $defaultmailserver = $servermaster['mail_server'][$rand]['server_id'];
                                                $defaultmailservername = $servermaster['mail_server'][$rand]['server_name'];
                                        }
                                        
                                        if($defaultmailserver != 0) {
                                                $defaultmailserverip = $this->isp_exec->soap_client->server_ip_get($this->isp_exec->soap_session_id,$defaultmailserver);
                                        }
                                                
                                        if(count($servermaster['dns_server']) == 1) {
                                                $defaultdnsserver = $server['dns_server'][0]['server_id'];
                                                $nameserver = $server['dns_server'][0]['server_name'];
                                        }
                                        else {
                                                $rand = rand(0,(count($servermaster['dns_server']) -1));
                                                $defaultdnsserver = $servermaster['dns_server'][$rand]['server_id'];
                                                $nameserver = $servermaster['dns_server'][$rand]['server_name'];
                                        }
                                        
                                        $index = 0;
                                        $nameserverslave = 0;

                                        if(isset($server['dns_server'])) {
                                                foreach($server['dns_server'] as $index => $srv) {
                                                        $mirror_id = $this->isp_exec->soap_client->server_get_functions($this->isp_exec->soap_session_id, $server['dns_server'][$index]['server_id']);
                                                        $mirror_id[0] = isset($mirror_id[0]) ? $mirror_id[0] : $mirror_id;
                                                        if($mirror_id[0]['mirror_server_id'] == $defaultdnsserver) {
                                                                $nameserverslave = $server['dns_server'][$index]['server_name'];
                                                        }                                              
                                                }
                                        }                                        
                                       
                                        
                                        $config = (array) $config;
                                        if(count($config) >= 1) {
                        
                                                foreach($config as $config_option => $config_option_value) {
                                
                                                        $config_option = trim(strtolower($config_option));
                                
                                                        if($config_option == 'php_mode') {
                                                                $phpmode = $config_option_value;
                                                        }
                                
                                                        if($config_option == 'phpversion') {
                                                                
                                                                $config_option_value = trim(strtolower($config_option_value));
                                                                if($defaultwebserver != 0 && ($phpmode != '' || $phpmode != 'no')) {
                                                                        $php_versions = $this->isp_exec->soap_client->server_get_php_versions($this->isp_exec->soap_session_id,$defaultwebserver,$phpmode);
                                                                        
                                                                        foreach($php_versions as $php_name => $php_name_value) {
                                                                                $php_name_string = explode(':',$php_name_value);
                                                                                $php_name_string = strtolower($php_name_string[0]);
                                                                                if(strcmp($php_name_string,$config_option_value) == 0) {
                                                                                        $phpversion = $php_name_value;
                                                                                }
                                                                        }
                                                                }
                                                        }
                                                        
                                                        if($config_option == 'enable_cgi') {                                                        
                                                             $webenablecgi = $config_option_value; 
                                                        }
                                                        elseif($config_option == 'enable_ssi') { 
                                                             $webenablessi = $config_option_value;  
                                                        }
                                                        elseif($config_option == 'enable_perl') { 
                                                             $webenableperl = $config_option_value;  
                                                        }
                                                        elseif($config_option == 'enable_ruby') { 
                                                             $webenableruby = $config_option_value;  
                                                        }
                                                        elseif($config_option == 'enable_python') {                                                             
                                                             $webenablepython = $config_option_value; 
                                                        }
                                                }
                                        }
                                        
                        
                                        $ispconfigparams = array(
                                                'company_name' => $companyname,
                                                'contact_name' => $fullname,
                                                'customer_no' => $accountid,
                                                'username' => $username,
                                                'password' => $password,
                                                'language' => 'en',
                                                'usertheme' => 'default',
                                                'street' => $address,
                                                'zip' => $zip,
                                                'city' => $city,
                                                'state' => $state,
                                                'country' => $country,
                                                'telephone' => $phonenumber,
                                                'mobile' => '',
                                                'fax' => '',
                                                'email' => $email,
                                                'template_master' => $templateid,
                                                'web_php_options' => 'no,fast-cgi,cgi,php-fpm,hhvm',
                                                'ssh_chroot' => $shellaccess,
                                                'default_mailserver' => $defaultmailserver,
                                                'default_webserver' => $defaultwebserver,
                                                'default_dbserver' => $defaultdbserver,
                                                'default_dnsserver' => $defaultdnsserver,
                                                'locked' => '0',
                                                'limit_cron_type' => 1,
                                                'created_at' => date('Y-m-d')
                                                );

                                               $client = $this->isp_exec->soap_client->client_get($this->isp_exec->soap_session_id, array('customer_no' => $accountid));
                                      
                                               if(isset($client[0])) 
                                               {
                                                       $client_id = $client[0];
                                                       $uid = $client_id['client_id'];
                                               }

                                               if(!isset($client_id['client_id']))
                                                {
                                                        $reseller_id = ($params->package->reseller_package == 'Yes') ? 1 : 0;
                                                        $uid = $this->isp_exec->soap_client->client_add($this->isp_exec->soap_session_id, $reseller_id, $ispconfigparams);                                                  
                                                        $params = unserialize($params->client->params);
                                                        $params['ispp'] = $password; 
                                                        $data = array('params' => serialize($params));
                                                        App::update('companies', array('co_id' => $params->client->co_id), $data);                                                        
                                                }

                                              
 
                                        if($createdns == 'y') {  
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultdnsserver,
                                                        'origin' => $domain.'.',
                                                        'ns' => $nameserver.'.',
                                                        'mbox' => 'webmaster.'.$domain.'.',
                                                        'refresh' => '7200',
                                                        'retry' => '540',
                                                        'expire' => '604800',
                                                        'minimum' => '3600',
                                                        'ttl' => '3600',
                                                        'active' => 'y',
                                                        'sys_userid' => $uid, 
                                                        'stamp' => date('Y-m-d H:i:s')
                                                );
                                                $dns_id = $this->isp_exec->soap_client->dns_zone_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams);
                                                
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultdnsserver,
                                                        'zone' => $dns_id,
                                                        'name' => $domain.'.',
                                                        'type' => 'A',
                                                        'data' => $params->server->hostname,
                                                        'ttl' => '3600',
                                                        'active' => 'y',
                                                        'sys_userid' => $uid,
                                                        'stamp' => date('Y-m-d H:i:s')
                                                );
                                                $zone_id = $this->isp_exec->soap_client->dns_a_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams);
                                                
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultdnsserver,
                                                        'zone' => $dns_id,
                                                        'name' => 'www',
                                                        'type' => 'A',
                                                        'data' => $params->server->hostname,
                                                        'ttl' => '3600',
                                                        'active' => 'y',
                                                        'sys_userid' => $uid,
                                                        'stamp' => date('Y-m-d H:i:s')
                                                );
                                                $zone_id = $this->isp_exec->soap_client->dns_a_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams);
                                                
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultdnsserver,
                                                        'zone' => $dns_id,
                                                        'name' => 'mail',
                                                        'type' => 'A',
                                                        'data' => $defaultmailserverip['ip_address'],
                                                        'ttl' => '3600',
                                                        'active' => 'y',
                                                        'sys_userid' => $uid,
                                                        'stamp' => date('Y-m-d H:i:s')
                                                );
                                                $zone_id = $this->isp_exec->soap_client->dns_a_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams);
                                                
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultdnsserver,
                                                        'zone' => $dns_id,
                                                        'name' => $domain.'.',
                                                        'type' => 'MX',
                                                        'data' => $defaultmailservername.'.',
                                                        'aux' => '10',
                                                        'ttl' => '3600',
                                                        'active' => 'y',
                                                        'sys_userid' => $uid,
                                                        'stamp' => date('Y-m-d H:i:s')
                                                );
                                                $zone_id = $this->isp_exec->soap_client->dns_mx_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams);
                                                
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultdnsserver,
                                                        'zone' => $dns_id,
                                                        'name' => $domain.'.',
                                                        'type' => 'NS',
                                                        'data' => $nameserver.'.',
                                                        'ttl' => '3600',
                                                        'active' => 'y',
                                                        'sys_userid' => $uid,
                                                        'stamp' => date('Y-m-d H:i:s')
                                                );
                                                $zone_id = $this->isp_exec->soap_client->dns_ns_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams);
                                                
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultdnsserver,
                                                        'zone' => $dns_id,
                                                        'name' => $domain.'.',
                                                        'type' => 'NS',
                                                        'data' => $nameserverslave.'.',
                                                        'ttl' => '3600',
                                                        'active' => 'y',
                                                        'sys_userid' => $uid,
                                                        'stamp' => date('Y-m-d H:i:s')
                                                );
                                                $zone_id = $this->isp_exec->soap_client->dns_ns_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams);
                                        
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultdnsserver,
                                                        'zone' => $dns_id,
                                                        'name' => $domain.'.',
                                                        'type' => 'TXT',
                                                        'data' => 'v=spf1 mx a ~all',
                                                        'ttl' => '3600',
                                                        'active' => 'y',
                                                        'sys_userid' => $uid,
                                                        'stamp' => date('Y-m-d H:i:s')
                                                );
                                                $zone_id = $this->isp_exec->soap_client->dns_txt_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams);
                                                        
                                                      }
                                        
                                        if($createmail == 'y') {
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultmailserver,
                                                        'domain' => $domain,
                                                        'active' => 'y'
                                                        );
                                                        
                                                $mail_id = $this->isp_exec->soap_client->mail_domain_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams); 
                                        }
                                        
                                        if($createweb == 'y') {
                                                $ispconfigparams = array(
                                                        'server_id' => $defaultwebserver,
                                                        'ip_address' => '*',
                                                        'domain' => $domain,
                                                        'type' => 'vhost',
                                                        'parent_domain_id' => '0',
                                                        'vhost_type' => 'name',
                                                        'hd_quota' => $webstorage,
                                                        'traffic_quota' => $webtraffic,
                                                        'cgi' => $webenablecgi,
                                                        'ssi' => $webenablessi,
                                                        'perl' => $webenableperl,
                                                        'ruby' => $webenableruby,
                                                        'python' => $webenablepython,
                                                        'suexec' => $webenablesuexec,
                                                        'errordocs' => '',
                                                        'is_subdomainwww' => 1,
                                                        'subdomain' => $webautosubdomain,
                                                        'php' => $phpmode,
                                                        'fastcgi_php_version' => $phpversion,
                                                        'redirect_type' => '',
                                                        'redirect_path' => '',
                                                        'ssl' => 'y',
                                                        'ssl_letsencrypt' => 'n',
                                                        'ssl_state' => $state,
                                                        'ssl_locality' => $city,
                                                        'ssl_organisation' => $companyname,
                                                        'ssl_organisation_unit' => 'IT',
                                                        'ssl_country' => $country,
                                                        'ssl_domain' => $domain,
                                                        'ssl_request' => '',
                                                        'ssl_key' => '',
                                                        'ssl_cert' => '',
                                                        'ssl_bundle' => '',
                                                        'ssl_action' => 'create',
                                                        'stats_password' => $password,
                                                        'stats_type' => 'webalizer',
                                                        'allow_override' => 'All',
                                                        'php_open_basedir' => '/',
                                                        'php_fpm_use_socket' => 'y',
                                                        'pm' => 'ondemand',
                                                        'pm_max_children' => '10',
                                                        'pm_start_servers' => '2',
                                                        'pm_min_spare_servers' => '1',
                                                        'pm_max_spare_servers' => '5',
                                                        'pm_process_idle_timeout' => '10',
                                                        'pm_max_requests' => '1024',
                                                        'custom_php_ini' => '',
                                                        'backup_interval' => 'daily',
                                                        'backup_copies' => 3,
                                                        'active' => 'y',
                                                        'traffic_quota_lock' => 'n',
                                                        'http_port' => '80',
                                                        'https_port' => '443',
                                                        'added_date' => date("Y-m-d"),
                                                        'added_by' => $params->server->username
                                                );
                                                
                                                $website_id = $this->isp_exec->soap_client->sites_web_domain_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams,false); 
                                                
                                                if($webcreateftp == 'y') {
                                                        $domain_info = $this->isp_exec->soap_client->sites_web_domain_get($this->isp_exec->soap_session_id,$website_id);
                                                        $ispconfigparams = array(
                                                                'server_id' => $defaultwebserver,
                                                                'parent_domain_id' => $website_id,
                                                                'username' => $params->account->username,
                                                                'password' => $password,
                                                                'quota_size' => -1,
                                                                'active' => 'y',
                                                                'uid' => $domain_info['system_user'],
                                                                'gid' => $domain_info['system_group'],
                                                                'dir' => $domain_info['document_root'],
                                                                'quota_files' => -1,
                                                                'ul_ratio' => -1,
                                                                'dl_ratio' => -1,
                                                                'ul_bandwidth' => -1,
                                                                'dl_bandwidth' => -1
                                                        );
                                                        
                                                        $ftp_id = $this->isp_exec->soap_client->sites_ftp_user_add($this->isp_exec->soap_session_id, $uid, $ispconfigparams); 
                                                }
                                        }
                                }
                                if($this->isp_exec->soap_client->logout($this->isp_exec->soap_session_id))
                                {
                                        $successful = 1;
                                }
                                                        
                    
                        if($successful == 1) {
                                $result = 'success';
                        }
                        else {
                                $result = $error;
                        }
                }
                else {
                        $result = $this->isp_exec->error;
                }

               echo $result;
	 
    }




    function suspend_account ($params) {
	$config = (object) unserialize($params->package->package_config);  
                
                $accountid = $params->client->co_id; 
                $createweb = $config->create_website; 
                $webcreateftp = $config->create_ftp;
                $createdns = $config->create_dns_zone;
                $createmail = $config->create_mail_domain;                
                $active = $config->enable_account;                
                
                $this->load->library('ispconfig/ISP_exec', $params->server);                                  
                if($this->isp_exec->authenticate()) {    

			$ispconfigparams = array(
				'locked' => 'y'
			);
			$reseller_id = ($params->package->reseller_package == 'Yes') ? 1 : 0;
			$client_id = $this->isp_exec->soap_client->client_get($this->isp_exec->soap_session_id, array('customer_no' => $accountid))[0]; 
			$result = $this->isp_exec->soap_client->client_update($this->isp_exec->soap_session_id,$client_id['client_id'],$reseller_id,$ispconfigparams);
		 
			if($createdns == 'y') {
				$dns_id = $this->isp_exec->soap_client->dns_zone_get_by_user($this->isp_exec->soap_session_id,$client_id['client_id'],$client_id['default_dnsserver']);
				$index = 0;
				
				while($index <= (count($dns_id) - 1)) {
					$zone_id = $this->isp_exec->soap_client->dns_zone_set_status($this->isp_exec->soap_session_id,$dns_id[$index]['id'],'inactive');
					++$index; 
				}
			}
			
			if($createmail == 'y') {
				$index = 0;
				
				while($index <= (count($dns_id) - 1)) {
					$dns_domain = rtrim($dns_id[$index]['origin'],'.');
					$mail_domain = $this->isp_exec->soap_client->mail_domain_get_by_domain($this->isp_exec->soap_session_id,$dns_domain);
					$mail_id = $this->isp_exec->soap_client->mail_domain_set_status($this->isp_exec->soap_session_id,$mail_domain[0]['domain_id'],'inactive');
					++$index; 
				}
			}
			
			if($createweb == 'y') {
				$web_domain = $this->isp_exec->soap_client->client_get_sites_by_user($this->isp_exec->soap_session_id,$client_id['client_id'],$client_id['default_group']);
				$index = 0;
				
				while($index <= (count($web_domain) - 1)) {
					$web_id = $this->isp_exec->soap_client->sites_web_domain_set_status($this->isp_exec->soap_session_id,$web_domain[$index]['domain_id'],'inactive');
					
					$ftp_name = $this->isp_exec->soap_client->sites_ftp_user_get($this->isp_exec->soap_session_id,array('parent_domain_id' => $web_domain[$index]['domain_id']));
					if(count($ftp_name) > 0) {
						$index_ftp = 0;
						while($index_ftp <= (count($ftp_name) - 1)) {
							$ftp_username = $ftp_name[$index_ftp]['username'];
							$ftp_name[$index_ftp]['active'] = 'n';
							$ftp_id = $this->isp_exec->soap_client->sites_ftp_user_update($this->isp_exec->soap_session_id,$client_id['client_id'],$ftp_name[$index_ftp]['ftp_user_id'],$ftp_name[$index_ftp]);
							++$index_ftp; 
						}
					}
					
					++$index; 
				}
			}
			
			$this->isp_exec->soap_client->logout($this->isp_exec->soap_session_id);
			
			if($result == 1) {
				$successful = '1';
			}
			else {
				$successful = '0';
				$result = 'Suspend client failed';
                        }
              
		}		 
		
		if($successful == 1) {
			$result = 'success';
		}
		else {
			$result = 'Error: ' . $error;
                }
    
	
	        return $result;
        }





        function unsuspend_account ($params) {
                $config = (object) unserialize($params->package->package_config);  
                $accountid = $params->client->co_id; 
                $createweb = $config->create_website; 
                $webcreateftp = $config->create_ftp;
                $createdns = $config->create_dns_zone;
                $createmail = $config->create_mail_domain;                
                $active = $config->enable_account;            
                        
                        $this->load->library('ispconfig/ISP_exec', $params->server);                                  
                        if($this->isp_exec->authenticate()) {    

                                $ispconfigparams = array(
                                        'locked' => 'n'
                                );
                                $reseller_id = ($params->package->reseller_package == 'Yes') ? 1 : 0;
                                $client_id = $this->isp_exec->soap_client->client_get($this->isp_exec->soap_session_id, array('customer_no' => $accountid))[0];
                                $result = $this->isp_exec->soap_client->client_update($this->isp_exec->soap_session_id, $client_id['client_id'], $reseller_id,$ispconfigparams);
                        
                                if($createdns == 'y') {
                                        $dns_id = $this->isp_exec->soap_client->dns_zone_get_by_user($this->isp_exec->soap_session_id,$client_id['client_id'],$client_id['default_dnsserver']);
                                        $index = 0;
                                        
                                        while($index <= (count($dns_id) - 1)) {
                                                $zone_id = $this->isp_exec->soap_client->dns_zone_set_status($this->isp_exec->soap_session_id,$dns_id[$index]['id'],'active');
                                                ++$index; 
                                        }
                                }
                                
                                if($createmail == 'y') {
                                        $index = 0;
                                        
                                        while($index <= (count($dns_id) - 1)) {
                                                $dns_domain = rtrim($dns_id[$index]['origin'],'.');
                                                $mail_domain = $this->isp_exec->soap_client->mail_domain_get_by_domain($this->isp_exec->soap_session_id,$dns_domain);
                                                $mail_id = $this->isp_exec->soap_client->mail_domain_set_status($this->isp_exec->soap_session_id,$mail_domain[0]['domain_id'],'active');
                                                ++$index; 
                                        }
                                }
                                
                                if($createweb == 'y') {
                                        $web_domain = $this->isp_exec->soap_client->client_get_sites_by_user($this->isp_exec->soap_session_id,$client_id['client_id'],$client_id['default_group']);
                                        $index = 0;
                                        
                                        while($index <= (count($web_domain) - 1)) {
                                                $web_id = $this->isp_exec->soap_client->sites_web_domain_set_status($this->isp_exec->soap_session_id,$web_domain[$index]['domain_id'],'active');
                                                
                                                $ftp_name = $this->isp_exec->soap_client->sites_ftp_user_get($this->isp_exec->soap_session_id,array('parent_domain_id' => $web_domain[$index]['domain_id']));
                                                if(count($ftp_name) > 0) {
                                                        $index_ftp = 0;
                                                        while($index_ftp <= (count($ftp_name) - 1)) {
                                                                $ftp_username = $ftp_name[$index_ftp]['username'];
                                                                $ftp_name[$index_ftp]['active'] = 'y';
                                                                $ftp_id = $this->isp_exec->soap_client->sites_ftp_user_update($this->isp_exec->soap_session_id,$client_id['client_id'],$ftp_name[$index_ftp]['ftp_user_id'],$ftp_name[$index_ftp]);
                                                                ++$index_ftp; 
                                                        }
                                                }
                                                
                                                ++$index; 
                                        }
                                }
                                
                                $this->isp_exec->soap_client->logout($this->isp_exec->soap_session_id);
                                
                                if($result == 1) {
                                        $successful = '1';
                                }
                                else {
                                        $successful = '0';
                                        $result = 'Unsuspend client failed';
                                }
                
                        }		 
                        
                        if($successful == 1) {
                                $result = 'success';
                        }
                        else {
                                $result = 'Error: ' . $error;
                        }
        
                
                return $result;
        }




        function change_password ($params) {
                           
                $username = $params->account->username;
                $password = $params->account->password;    
                
                $this->load->library('ispconfig/ISP_exec', $params->server);                                  
                if($this->isp_exec->authenticate()) {   
                        
                        $client_id = $this->isp_exec->soap_client->client_get($this->isp_exec->soap_session_id, array('customer_no' => $params->account->client_id))[0];
                        $result = $this->isp_exec->soap_client->client_change_password($this->isp_exec->soap_session_id,$client_id['client_id'],$password); 
                        
                        $this->isp_exec->soap_client->logout($this->isp_exec->soap_session_id);
                        
                        if($result == 1) {
                                $params = unserialize($params->client->params);
                                $params['ispp'] = $password; 
                                $data = array('params' => serialize($params));
                                App::update('companies', array('co_id' => $params->client->co_id), $data);

                                $successful = '1';
                        }
                        else {
                                $successful = '0';
                                $result = 'Password change failed';
                        } 
                     
                        if($successful == 1) {
                                $result = 'success';
                        }
                        else {
                                $result = 'Error: ' . $error;
                        }
                }
                 
                return $result;
        }




        public function change_package ( $params ) 
        { 
                $config = (object) unserialize($params->package->package_config);                
                $accountid = $params->client->co_id;
                $productid = $config->template_id;                
                $shellaccess = $config->shell_access;
                $createweb = $config->create_website;
                $phpmode = $config->php_mode; 
                $webstorage = $config->storage_quota;
                $webtraffic = $config->traffic_quota;
                $webenablecgi = $config->enable_cgi;
                $webenablessi = $config->enable_ssi;
                $webenableruby = $config->enable_ruby;
                $webenablesuexec =  $config->force_suexec; 
                
                $this->load->library('ispconfig/ISP_exec', $params->server);                                  
                if($this->isp_exec->authenticate()) {   
                        $ispconfigparams = array(
                                'hd_quota' => $webstorage,
                                'traffic_quota' => $webtraffic,
                                'cgi' => $webenablecgi,
                                'ssi' => $webenablessi, 
                                'ruby' => $webenableruby, 
                                'suexec' => $webenablesuexec,  
                                'ssh_chroot' => $shellaccess,
                                'php' => $phpmode                                
                        );
                        $reseller_id = ($params->package->reseller_package == 'Yes') ? 1 : 0;
                        $client_id = $this->isp_exec->soap_client->client_get_by_username($this->isp_exec->soap_session_id, $params->user->username); 
                        $domains = $this->isp_exec->soap_client->client_get_sites_by_user($this->isp_exec->soap_session_id, $client_id['userid'],$client_id['sys_groupid']);

                        foreach($domains as $domain)
                        {
                           if($domain['domain'] == $params->account->domain)
                           {
                                $result = $this->isp_exec->soap_client->sites_web_domain_update($this->isp_exec->soap_session_id, $client_id['client_id'], $domain['domain_id'],$ispconfigparams);
                             }
                        }                       
                      
                        $this->isp_exec->soap_client->logout($this->isp_exec->soap_session_id);
                        
                        if($result == 1) {
                                $successful = '1';
                        }
                        else {
                                $successful = '0';
                                $result = 'Change package failed';
                        }

                        if($successful == 1) {
                                $result = 'success';
                        }
                        else {
                                $result = 'Error: ' . $error;
                        }
                }               
                
                return $result;
               
        }




        public function terminate_account ( $params ) 
        { 
                $config = (object) unserialize($params->package->package_config);                
                $accountid = $params->client->co_id;
      
                
                $this->load->library('ispconfig/ISP_exec', $params->server);                                  
                if($this->isp_exec->authenticate()) {   
                        
                        $reseller_id = ($params->package->reseller_package == 'Yes') ? 1 : 0;
                        $client_id = $this->isp_exec->soap_client->client_get_by_username($this->isp_exec->soap_session_id, $params->user->username); 
                        $domains = $this->isp_exec->soap_client->client_get_sites_by_user($this->isp_exec->soap_session_id, $client_id['userid'],$client_id['sys_groupid']);
 
                        foreach($domains as $domain)
                        {
                           if($domain['domain'] == $params->account->domain)
                           {
                                $result = $this->isp_exec->soap_client->sites_web_domain_delete($this->isp_exec->soap_session_id, $domain['domain_id']);                             
                           }
                        }                       
                      
                        $this->isp_exec->soap_client->logout($this->isp_exec->soap_session_id);
                        
                        if($result == 1) {
                                $successful = '1';
                        }
                        else {
                                $successful = '0';
                                $result = 'Change package failed';
                        }

                        if($successful == 1) {
                                $result = 'success';
                        }
                        else {
                                $result = 'Error: ' . $error;
                        }
                }               
                
                return $result;
               
        }



        

    //https://www.howtoforge.com/community/threads/api-get-disk-usage.81929/
    public function get_usage ($order)
    {

        // $server = Order::get_server($order->server); 
        // $this->load->library('cwp/CWP_exec', $server);

        // $this->load->library('ispconfig/ISP_exec', $server);                                  
        // if($this->isp_exec->authenticate()) {  
   
        // $record_record = $this->isp_exec->soap_client->client_get_sites_by_user($this->isp_exec->soap_session_id, 20,1);
        // $this->isp_exec->soap_client->logout($this->isp_exec->soap_session_id);
 
	// print_r($record_record);
        // echo "<br>";
        // die;

        // }
        
        $params = array();
        $usage = array('disk_limit' => 0, 'disk_used' => 0, 'bw_limit' => 0, 'bw_used' => 0);       
        return $usage;
    }   





    function admin_options($server) {
       
        if($server->use_ssl == 'Yes') {                    
                $soap_url = 'https://' . $server->hostname . ":" .$server->port;            
        }
        else {                    
                $soap_url = 'http://' . $server->hostname. ":" .$server->port; 
        } 
 
        $code = '<a class="btn btn-success btn-xs" href="'.base_url().'servers/index/'.$server->id.'"><i class="fa fa-settings"></i> '.lang('test_connection').'</a> 
        <a class="btn btn-primary btn-xs" href="'.base_url().'servers/edit_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-pencil"></i> '.lang('edit').'</a>
        <a class="btn btn-danger btn-xs" href="'.base_url().'servers/delete_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-trash"></i> '.lang('delete').'</a>';

        $code .= '<form id="frmIspconfigLogin" action="'.$soap_url.'/index.php" method="GET" target="_blank" style="display:inline;">
        <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-user"></i> '.lang("login").'</button>
        </form>

        <script type="text/javascript">
        $("#frmIspconfigLogin").submit(function(){
                $.ajax({
                type: "POST",
                url: "'.$soap_url.'/login/index.php",
                data: "s_mod=login&s_pg=index&username='.$server->username.'&password='.$server->authkey.'",
                xhrFields: {withCredentials: true}
                });
        
        });
        </script>';

        return $code;
    }




 
    function client_options($id = null) {
        $order = Order::get_order($id);
        $server = Order::get_server($order->server);
        $client = Client::view_by_id($order->client_id); 
	$user = User::view_user($client->primary_contact);
      
        if($server->use_ssl == 'Yes') {                    
                $soap_url = 'https://' . $server->hostname . ":" .$server->port;            
        }
        else {                    
                $soap_url = 'http://' . $server->hostname. ":" .$server->port; 
        }

        $code = '<a href="'.base_url().'accounts/change_password/'.$id.'" class="btn btn-sm btn-info" data-toggle="ajaxModal">
        <i class="fa fa-edit"></i>'.lang('change_cpanel_password').'</a> 
        
        <form action="'.$soap_url.'/login/index.php" method="POST" target="_blank" style="display:inline;">
        <input name="s_mod" value="login" type="hidden">
        <input name="s_pg" value="index" type="hidden">
        <input name="username" value="'.$user->username.'" type="hidden">
        <input name="password" value="'.$server->authkey.'" type="hidden">
        <button type="submit" class="btn btn-sm btn-warning"> <i class="fa fa-dashboard"></i> '.lang("client_area").'</button>
        </form>';

        return $code;
    }


    

 
}
