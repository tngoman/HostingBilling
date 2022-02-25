<?php

/* Display PHP error options */
ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set("display_errors", 0); // Set this option to Zero on a production machine.

/* Open and log info for ISPConfig 3 */
openlog( "ispconfig3", LOG_PID | LOG_PERROR, LOG_LOCAL0 );

/* Sets default server ports & other info for provisioning module */
function ispconfig3_MetaData() {

	return array(
		'DisplayName' => 'ISPConfig 3', // Display name for module
		'APIVersion' => '1.1', // API version 1.1 preferred over version 1.0
		'RequiresServer' => true, // Requires a server to be configured in WHMCS
		'DefaultNonSSLPort' => '8080', // Control Panel non-SSL port
		'DefaultSSLPort' => '8080', // Control Panel SSL port
		);
}

/* Configuration options for the module product settings */
function ispconfig3_ConfigOptions() {
	$configarray = array(
		'ISPConfig 3 Version' => array( 'Type' => 'dropdown', 'Options' => '3.0,3.1', 'Default' => '3.0', 'Description' => 'Select the installed version of ISPConfig 3' ),
		'Version Details' => array( 'Description' => 'Select the installed version of ISPConfig 3.' ),
		'Template ID' => array( 'Type' => 'text', 'Size' => '3', 'Description' => '#' ),
		'Template ID Details' => array( 'Description' => 'Locate the template ID number in the ISPConfig 3 menu<br><b>Client » Template » Template ID</b>' ),
		'Client Limit' => array( 'Type' => 'text', 'Size' => '3', 'Default' => '0', 'Description' => '#' ),
		'Client Limit Details' => array( 'Description' => 'If the client is a Reseller, make sure the Client Limit<br>is equal to Max. number of Clients in the selected template.<br><br>Notice: If client is <b>NOT</b> a Reseller, a value of "0" must<br>be entered.' ),
		'Domain Tool' => array( 'Type' => 'yesno', 'Description' => 'Add domain to Domain Tool' ),
		'Domain Tool Details' => array( 'Description' => 'If you are using the Domain Tool in ISPConfig 3, you<br>must tick the check box to properly add the domain<br>for the client.' ),
		'Domain Setup' => array( 'Type' => 'yesno', 'Default' => 'yes', 'Description' => 'Setup Website, DNS & Email for domain' ),
		'Domain Setup Details' => array( 'Description' => 'Tick the check box to add DNS records, default<br>website, and add the domain for email.<br><br>Notice: A DNS template must be properly configured if<br>the Domain Setup option is ticked.' ),
		'Website Disk Space' => array( 'Type' => 'text', 'Size' => '10', 'Description' => 'MB' ),
		'Disk Space Details' => array( 'Description' => 'If the Domain Setup option is ticked, enter the amount<br>of disk space allowed for the default website.' ),
		'Website Bandwidth' => array( 'Type' => 'text', 'Size' => '10', 'Description' => 'MB' ),
		'Bandwidth Details' => array( 'Description' => 'If the Domain Setup option is ticked, enter the amount<br>of monthly bandwidth allowed for the default website.' ),
		'DNS Template ID' => array( 'Type' => 'text', 'Size' => '3', 'Description' => '#' ),
		'DNS Template ID Details' => array( 'Description' => 'If the Domain Setup option is ticked, you must have a<br> <b>DNS » DNS Wizard » DNS Template</b>' )
		);

		return $configarray;

}

function ispconfig3_CreateAccount( $params ) {

	$productid		= $params['pid'];
	$accountid		= $params['accountid'];
	$domain			= strtolower( $params['domain'] );
	$username		= $params['username'];
	$password		= $params['password'];
	$clientsdetails		= $params['clientsdetails'];
	$soapuser		= $params['serverusername'];
	$soappassword		= $params['serverpassword'];
	$soapsvrurl		= $params['serverhostname'];
	$soapsvrssl		= $params['serversecure'];
	$soapsvrport		= $params['serverport'];
	$ispconfigver		= $params['configoption1'];
	$templateid		= $params['configoption3'];
	$clientlimit		= $params['configoption5'];
	$domaintool		= $params['configoption7'];
	$webcreation		= $params['configoption9'];
	$addmaildomain		= $params['configoption9'];
	$dns			= $params['configoption9'];
	$webquota		= $params['configoption11'];
	$webtraffic		= $params['configoption13'];
	$dnstemplate		= $params['configoption15'];

logModuleCall('ispconfig','CreateClient',$params['clientsdetails'],$params,'','');

require 'lib/soap_location.php';

	/* Username and password have been set or exit with error */
	if (
	((isset($username)) &&
	($username != '')) &&
	((isset($password)) &&
	($password != ''))
	) 
	{

	try {

	/* Connect to SOAP Server */
	require 'lib/soap_connect.php';

	/* Authenticate with the SOAP Server */
	require 'lib/soap_authenticate.php';

	$fullname = htmlspecialchars_decode( $clientsdetails['firstname'] );
	$fullname .= ' ' . htmlspecialchars_decode( $clientsdetails['lastname'] );
	$companyname = htmlspecialchars_decode( $clientsdetails['companyname'] );
	$address = $clientsdetails['address1'];
	if (!empty($clientsdetails['address2'])) { $address .= ',' . $clientsdetails['address2']; }
	$zip = $clientsdetails['postcode'];
	$city = $clientsdetails['city'];
	$state = $clientsdetails['state'];
	$mail = $clientsdetails['email'];
	$country = $clientsdetails['country'];
	$phonenumber = $clientsdetails['phonenumberformatted'];
	$customerno = $clientsdetails['userid'];

	/* Get the serverid's from WHMCS */
	$sql = 'SELECT serverid FROM tblservergroupsrel WHERE groupid  = '
		. '( SELECT servergroup FROM tblproducts '
		. 'WHERE id = "' . $productid . '")';
	$res = mysqli_query( $sql );
	$servernames = array();

	/* Loop through the serverid's and retrieve the hostnames of the servers from WHMCS */
	$i = 0;
	while ($groupservers = mysqli_fetch_array( $res )) {
		$sql = 'SELECT hostname FROM tblservers '
			. 'WHERE id  = "' . $groupservers['serverid'] . '"';
		$db_result = mysqli_query( $sql );
		$servernames2 = mysqli_fetch_array( $db_result );
		$servernames[$i] = $servernames2['hostname'];
		$i++;
	}

	$a = 0;
	$b = 0;
	$c = 0;
	$d = 0;
	$e = 0;
	$i = 0;
	$j = 1;
	$server = array();

	while ($j <= count( $servernames )) {

		/* Retreive the serverid from ispconfig */
		$result = $client->server_get_serverid_by_name( $session_id, $servernames[$i] );

		/* Retrieve the services for the server from ispconfig */
		$servicesresult = $client->server_get_functions( $session_id, $result[0]['server_id'] );

		/* Loop through the results to find the services on each server */
		if ($servicesresult[0]['mail_server'] == 1 ) {
			$server['mail_server'][$a]['server_id'] = $result[0]['server_id'];
			$server['mail_server'][$a]['hostname'] = $servernames[$i];
			$a++;
		}

		if ($servicesresult[0]['web_server'] == 1 ) {
			$server['web_server'][$b]['server_id'] = $result[0]['server_id'];
			$server['web_server'][$b]['hostname'] = $servernames[$i];
			$b++;
		}

		if ($servicesresult[0]['dns_server'] == 1 ) {
			$server['dns_server'][$c]['server_id'] = $result[0]['server_id'];
			$server['dns_server'][$c]['hostname'] = $servernames[$i];
			$c++;
		}

		if ($servicesresult[0]['file_server'] == 1 ) {
			$server['file_server'][$d]['server_id'] = $result[0]['server_id'];
			$server['file_server'][$d]['hostname'] = $servernames[$i];
			$d++;
		}

		if ($servicesresult[0]['db_server'] == 1 ) {
			$server['db_server'][$e]['server_id'] = $result[0]['server_id'];
			$server['db_server'][$e]['hostname'] = $servernames[$i];
			$e++;
		}

		++$i;
		++$j;
	}
        
	unset($a);
	unset($b);
	unset($c);
	unset($d);
	unset($e);

logModuleCall('ispconfig','CreateClient',$servicesresult,$server,'','');

	if (count( $server['mail_server'] ) == 1 ) {

		$defaultmailserver = $server['mail_server'][0]['server_id'];
            
	} else {

		$rnd = rand(0, ( count( $server['mail_server'] ) - 1 ) );
		$defaultmailserver = $server['mail_server'][$rnd]['server_id'];

	}

	if (count( $server['web_server'] ) == 1 ) {

		$defaultwebserver = $server['web_server'][0]['server_id'];

	} else {

		$a = 1;
		$b = 0;

		while ( $a <= count($server['web_server']) ) {

			$result = array();
			$result = $client->sites_web_domain_get( $session_id, array( 'server_id' => $server['web_server'][$b]['server_id'], 'type' => 'vhost') );

	if (!isset($webservercnt)) {

		$webservercnt = count( array_keys($result) );

	}

	if ( $webservercnt > count( array_keys($result) ) OR ( !isset($defaultwebserver) ) ) {

		$webservercnt = count( array_keys($result) );
		$defaultwebserver = $server['web_server'][$b]['server_id'];

	}

	$a++;
	$b++;

	}
            
            unset($a);
            unset($b);
            
        }
        
        if (count( $server['db_server'] ) == 1 ) {
            
            $defaultdbserver = $server['db_server'][0]['server_id'];
            
        } else {
            
            $rnd = rand(0, ( count( $server['db_server'] ) - 1 ) );
            $defaultdbserver = $server['db_server'][$rnd]['server_id'];
            
        }
        
        if (count( $server['dns_server'] ) == 1 ) {
            
            $defaultdnsserver = $server['dns_server'][0]['server_id'];
            
        } else {
            
            $rnd = rand(0, ( count( $server['dns_server'] ) - 1 ) );
            $defaultdnsserver = $server['dns_server'][$rnd]['server_id'];
            
        }
        
        if (count( $server['file_server'] ) == 1 ) {
            
            $defaultfileserver = $server['file_server'][0]['server_id'];
            
        } else {
            
            $rnd = rand(0, ( count( $server['file_server'] ) - 1 ) );
            $defaultfileserver = $server['file_server'][$rnd]['server_id'];
            
        }
        
        logModuleCall('ispconfig','CreateClient',$server,$server,'','');
            
            $ispcparams = array(
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
                    'email' => $mail,
                    'template_master' => $templateid,
                    'template_additional' => '',
                    'web_php_options' => 'no,php-fpm',
                    'ssh_chroot' => 'jailkit',
                    'default_mailserver' => $defaultmailserver,
                    'default_webserver' => $defaultwebserver,
                    'default_dbserver' => $defaultdbserver,
                    'default_dnsserver' => $defaultdnsserver,
                    'locked' => '0',
                    'limit_client' => $clientlimit,
                    'created_at' => date('Y-m-d')
                    );
        
            $reseller_id = 0;

            $client_id = $client->client_add( $session_id, $reseller_id, $ispcparams );

            logModuleCall('ispconfig','CreateClient',$client_id,$ispcparams,'','');
        
        if ( $domaintool == 'on' ) {
            
            $ispcparams = array( 'domain' => $domain );
            $domain_id = $client->domains_domain_add( $session_id, $client_id, $ispcparams );
            
            logModuleCall('ispconfig','CreateDomainAdd',$domain_id,$ispcparams,'','');
            
        }
        
        
        if ( $dns == 'on' ) {

            $dns_id = $client->dns_templatezone_add( $session_id, $client_id, $dnstemplate, $domain );
            logModuleCall('ispconfig','CreateDNSZone',$domain,'DNS Template '.$dnstemplate,'','');
            
        }


        if ( $webcreation == 'on' ) {
            
            $ispcparams = array(
                    'server_id' => $defaultwebserver, 
                    'ip_address' => '*',
                    'domain' => $domain,
                    'type' => 'vhost',
                    'parent_domain_id' => '0',
                    'vhost_type' => 'name',
                    'hd_quota' => $webquota,
                    'traffic_quota' => $webtraffic,
                    'cgi' => '',
                    'ssi' => '',
                    'perl' => '',
                    'ruby' => '',
                    'python' => '',
                    'suexec' => 'y',
                    'errordocs' => '',
                    'is_subdomainwww' => 1,
                    'subdomain' => 'www',
                    'php' => 'no',
                    'redirect_type' => '',
                    'redirect_path' => '',
                    'ssl' => '',
                    'ssl_state' => '',
                    'ssl_locality' => '',
                    'ssl_organisation' => '',
                    'ssl_organisation_unit' => '',
                    'ssl_country' => '',
                    'ssl_domain' => '',
                    'ssl_request' => '',
                    'ssl_key' => '',
                    'ssl_cert' => '',
                    'ssl_bundle' => '',
                    'ssl_action' => '',
                    'stats_password' => $password,
                    'stats_type' => 'awstats',
                    'allow_override' => 'All',
                    'php_open_basedir' => '/',
                    'php_fpm_use_socket' => 'y',
                    'pm' => 'dynamic',
                    'pm_max_children' => '10',
                    'pm_start_servers' => '2',
                    'pm_min_spare_servers' => '1',
                    'pm_max_spare_servers' => '5',
                    'pm_process_idle_timeout' => '10',
                    'pm_max_requests' => '0',
                    'custom_php_ini' => '',
                    'backup_interval' => '',
                    'backup_copies' => 1,
                    'active' => 'y',
                    'traffic_quota_lock' => 'n',
                    'added_date' => date("Y-m-d"),
                    'added_by' => $soapuser
                );
                
                if ($ispconfigver == "3.1") {
                    $ispcparams['http_port'] = '80';
                    $ispcparams['https_port'] = '443';
                };

            $website_id = $client->sites_web_domain_add( $session_id, $client_id, $ispcparams );

            logModuleCall('ispconfig','CreateWebDomain',$website_id,$ispcparams,'','');
            
            // Add A Record and CNAME Records for website to dns.
            if ( $dns == 'on' ) {
            
                $zone_id = $client->dns_zone_get_by_user($session_id, $client_id, $defaultdnsserver);
                $dns_svr = $client->dns_zone_get($session_id, $zone_id[0]['id']);
                $a_svr = $client->server_get_all($session_id);
                
                // Loop through the array till we find the mail server name
                while ($arec == '') {
                    $poparr = array_pop($a_svr);
                    if ( $poparr['server_id'] == $defaultwebserver )
                            $arec = $poparr['server_name'];
                }
                
                $sql = 'SELECT ipaddress FROM tblservers '
                    . 'WHERE hostname  = "' . $arec . '"';
                $db_result = mysqli_query( $sql );
                $a_ip = mysqli_fetch_array( $db_result );
                logModuleCall('ispconfig','CreateDNSA',$zone_mx,$a_ip,'','');
                
                $params = array(
                    'server_id' => $dns_svr['server_id'],
                    'zone' => $zone_id[0]['id'],
                    'name' => $domain.'.',
                    'type' => 'A',
                    'data' => $a_ip['ipaddress'],
                    'aux' => '0',
                    'ttl' => '3600',
                    'active' => 'y',
                    'stamp' => date('Y-m-d H:i:s'),
                    'serial' => '',
                );
                
                $zone_mx = $client->dns_a_add($session_id, $client_id, $params);
                logModuleCall('ispconfig','CreateDNSA',$zone_mx,$params,'','');
                
                // Add cname record
                $params = array(
                    'server_id' => $dns_svr['server_id'],
                    'zone' => $zone_id[0]['id'],
                    'name' => 'www',
                    'type' => 'CNAME',
                    'data' => $domain.'.',
                    'aux' => '0',
                    'ttl' => '3600',
                    'active' => 'y',
                    'stamp' => date('Y-m-d H:i:s'),
                    'serial' => '',
                );
                
                $zone_mx = $client->dns_cname_add($session_id, $client_id, $params);
                logModuleCall('ispconfig','CreateDNSCNAME',$zone_mx,$params,'','');
                
            }
            
        }

        if ( $addmaildomain == 'on' ) {
            
            $ispcparams = array( 
                    'server_id' => $defaultmailserver, 
                    'domain'    => $domain, 
                    'active'    => 'y' 
                );

            $maildomain_id = $client->mail_domain_add( $session_id, $client_id, $ispcparams );
            logModuleCall('ispconfig','CreateMailDomain',$maildomain_id,$ispcparams,'','');
            
            // Add MX Record to dns.
            if ( $dns == 'on' ) {
            
                $zone_id = $client->dns_zone_get_by_user($session_id, $client_id, $defaultdnsserver);
                $dns_svr = $client->dns_zone_get($session_id, $zone_id[0]['id']);
                $mx_svr = $client->server_get_all($session_id);
                
                // Loop through the array till we find the mail server name
                while ($mx == '') {
                    $poparr = array_pop($mx_svr);
                    if ( $poparr['server_id'] == $defaultmailserver )
                            $mx = $poparr['server_name'];
                }
                $params = array(
                    'server_id' => $dns_svr['server_id'],
                    'zone' => $zone_id[0]['id'],
                    'name' => $domain.'.',
                    'type' => 'mx',
                    'data' => $mx.'.',
                    'aux' => '0',
                    'ttl' => '3600',
                    'active' => 'y',
                    'stamp' => date('Y-m-d H:i:s'),
                    'serial' => '',
                );
                
                $zone_mx = $client->dns_mx_add($session_id, $client_id, $params);
                logModuleCall('ispconfig','CreateDNSMX',$zone_mx,$params,'','');
            }
            
        }

        if ( $client->logout( $session_id ) ) {
            
        }
        
        $successful = 1;
        
    } catch (SoapFault $e) {
        
        $error = 'SOAP Error: ' . $e->getMessage();
        $successful = '0';
        logModuleCall('ispconfig','Create Failed',$e->getMessage(), $params,'','');

        
    }

    if ( $successful == 1 ) {
        
        $result = "success";
        
    } else {
        
        $result = 'Error: ' . $error;
        
    }
    
    } else {
        /*
         * No username or password set.
         */
        $result = 'Username or Password is Blank or Not Set';
    }
            
    return $result;
}

function ispconfig3_TerminateAccount( $params ) {

    $username           = $params['username'];
    $password           = $params['password'];
    $clientsdetails     = $params['clientsdetails'];
    $domain             = $params['domain'];
    $soapuser           = $params['serverusername'];
    $soappassword       = $params['serverpassword'];
    $soapsvrurl         = $params['serverhostname'];
    $soapsvrssl         = $params['serversecure'];
    $soapsvrport        = $params['serverport'];
    $domaintool         = $params['configoption7'];
    
require 'lib/soap_location.php';

    if (
            ((isset($username)) &&
            ($username != '')) &&
            ((isset($password)) &&
            ($password != ''))
            ) 

        {
    
    try {
        /* Connect to SOAP Server */
        require 'lib/soap_connect.php';
        
        /* Authenticate with the SOAP Server */
        require 'lib/soap_authenticate.php';
              
        $domain_id = $client->client_get_by_username( $session_id, $username );

        $group_id = $domain_id['default_group'];
        $client_id = $domain_id['client_id'];
        
        if ( $domaintool == 'on' ) {

            $result = $client->domains_get_all_by_user( $session_id, $group_id );
            logModuleCall('ispconfig','Terminate Get Domains','Get Domains',$result,'','');
            if (!empty($result)) {
                $key = '0';
                foreach ( $result as $key => $value ) {
                
                    if ( $result[$key]['domain'] == $domain ) {
                    
                        $primary_id = $result[$key]['domain_id'];
                        continue;
                    
                    }
                }
            
                $result = $client->domains_domain_delete( $session_id, $primary_id );
                logModuleCall('ispconfig','Terminate Domain',$primary_id, $result,'','');
            }
        }

        $client_res = $client->client_delete_everything( $session_id, $client_id );
        logModuleCall('ispconfig','Terminate Client',$client_id, $client_res,'','');
        
        if ( $client->logout( $session_id ) ) {
            
        }

        $successful = '1';
        
    } catch (SoapFault $e) {
        
        $error = 'SOAP Error: ' . $e->getMessage();
        $successful = '0';
        
    }

    if ( $successful == 1 ) {
        
        $result = "success";
        
    } else {
        
        $result = 'Error: ' . $error;
        
    }

    } else {
        /*
         * No username or password set.
         */
        $result = 'Username or Password is not set';
    }
    
    return $result;
}

function ispconfig3_ChangePackage( $params ) {

    $username           = $params['username'];
    $password           = $params['password'];
    $clientsdetails     = $params['clientsdetails'];
    $soapuser           = $params['serverusername'];
    $soappassword       = $params['serverpassword'];
    $soapsvrurl         = $params['serverhostname'];
    $soapsvrssl         = $params['serversecure'];
    $soapsvrport        = $params['serverport'];
    $templateid         = $params['configoption3'];

require 'lib/soap_location.php';
 
    if (
            ((isset($username)) &&
            ($username != '')) &&
            ((isset($password)) &&
            ($password != ''))
            ) 
        {
    
    try {
        /* Connect to SOAP Server */
        require 'lib/soap_connect.php';
  
        /* Authenticate with the SOAP Server */
        require 'lib/soap_authenticate.php';
        
        $domain_id = $client->client_get_by_username( $session_id, $username );

        $client_id = $domain_id['client_id'];

        $client_record = $client->client_get( $session_id, $client_id );
        $client_record['template_master'] = $templateid;
        $reseller_id = $client->client_get( $session_id, $client_id );
        $parent_client_id = $resellerid['parent_client_id'];

        $affected_rows = $client->client_update( $session_id, $client_id, $parent_client_id, $client_record );

        if ($client->logout( $session_id )) {

        }

        $successful = '1';
    
    } catch (SoapFault $e) {
        
        $error = 'SOAP Error: ' . $e->getMessage();
        $successful = '0';
        
    }

    if ($successful == 1) {

        $result = 'success';

    } else {

        $result = 'Error: ' . $error;

    }
    
    } else {
        /*
         * No username or password set.
         */
        $result = 'Username or Password is Blank or Not Set';
    }
    
    return $result;
}

function ispconfig3_SuspendAccount( $params ) {

    $username           = $params['username'];
    $password           = $params['password'];
    $domain             = strtolower( $params['domain'] );
    $clientsdetails     = $params['clientsdetails'];
    $soapuser           = $params['serverusername'];
    $soappassword       = $params['serverpassword'];
    $soapsvrurl         = $params['serverhostname'];
    $soapsvrssl         = $params['serversecure'];
    $soapsvrport        = $params['serverport'];
    $webcreation        = $params['configoption9'];
    $dns                = $params['configoption9'];
    $addmaildomain      = $params['configoption9'];

        /* SOAP server location */
        require 'lib/soap_location.php';
    
    if (
            ((isset($username)) &&
            ($username != '')) &&
            ((isset($password)) &&
            ($password != ''))
            ) 
        {
    
    try {
        /* Connect to SOAP server */
        require 'lib/soap_connect.php';
        
        /* Authenticate with the SOAP Server */
        require 'lib/soap_authenticate.php';
        
        $result_id = $client->client_get_by_username( $session_id, $username );
        
        $sys_userid = $result_id['client_id'];
        $sys_groupid = $result_id['groups'];
        $client_detail = $client->client_get( $session_id, $sys_userid );
        $parent_client_id = $client_detail['parent_client_id'];
        
        $domain_id = $client->dns_zone_get_by_user( $session_id, $sys_userid,  $client_detail['default_dnsserver'] );
        
        if ( $webcreation == 'on' ) {
            
            $clientsites = $client->client_get_sites_by_user( $session_id, $sys_userid, $sys_groupid );
            
            $i = 0;
            $j = 1;
            while ($j <= count($clientsites) ) {

                $domainres = $client->sites_web_domain_set_status( $session_id, $clientsites[$i]['domain_id'],  'inactive' );
                logModuleCall('ispconfig','Suspend Web Domain',$clientsites[$i]['domain_id'], $clientsites[$i],'','');
                $i++;
                $j++;
                
            }
            
        }

        if ( $addmaildomain == 'on' ) {
            
            $emaildomain = $client->mail_domain_get_by_domain( $session_id, $domain );            
            $mailid = $client->mail_domain_set_status($session_id, $emaildomain[0]['domain_id'], 'inactive');
            logModuleCall('ispconfig','Suspend Email Domain',$emaildomain[0]['domain_id'], $mailid,'','');
            
        }
        
        if ( $dns == 'on' ) {           
        
            $i = 0;
            $j = 1;
            while ($j <= count($domain_id) ) {

                $affected_rows = $client->dns_zone_set_status( $session_id, $domain_id[$i]['id'], 'inactive' );
                $i++;
                $j++;
                logModuleCall('ispconfig','Suspend Domain',$domain_id[$i]['id'], $affected_rows,'','');
                
            }
            
        }

        $client_detail['locked'] = 'y';
        $client_detail['password'] = '';
        $client_result = $client->client_update( $session_id, $sys_userid, $parent_client_id, $client_detail );
        
        logModuleCall('ispconfig','Suspend Client', $sys_userid.' '.$sys_groupid, $client_result,'','');
        
        if ($client->logout( $session_id )) {
        }

        $successful = '1';
    
    } catch (SoapFault $e) {
        
        $error = 'SOAP Error: ' . $e->getMessage();
        $successful = '0';
        
    }

    if ($successful == 1) {

        $result = 'success';

    } else {

        $result = 'Error: ' . $error;

    }
    
    } else {
        /*
         * No username or password set.
         */
        $result = 'Username or Password is Blank or Not Set';
    }
    
    return $result;
}

function ispconfig3_UnsuspendAccount( $params ) {

    $username           = $params['username'];
    $password           = $params['password'];
    $domain             = strtolower( $params['domain'] );
    $clientsdetails     = $params['clientsdetails'];
    $soapuser           = $params['serverusername'];
    $soappassword       = $params['serverpassword'];
    $soapsvrurl         = $params['serverhostname'];
    $soapsvrssl         = $params['serversecure'];
    $soapsvrport        = $params['serverport'];
    $webcreation        = $params['configoption9'];
    $addmaildomain      = $params['configoption9'];
    $dns                = $params['configoption9'];
    
require 'lib/soap_location.php';
    
    if (
            ((isset($username)) &&
            ($username != '')) &&
            ((isset($password)) &&
            ($password != ''))
            ) 
        {
    
    try {
        /* Connect to SOAP Server */
        require 'lib/soap_connect.php';
    
        /* Authenticate with the SOAP Server */
        require 'lib/soap_authenticate.php';
        
        $result_id = $client->client_get_by_username( $session_id, $username );
        
        $sys_userid = $result_id['client_id'];
        $sys_groupid = $result_id['groups'];
        $client_detail = $client->client_get( $session_id, $sys_userid );
        $parent_client_id = $client_detail['parent_client_id'];
        
        $domain_id = $client->dns_zone_get_by_user( $session_id, $sys_userid,  $client_detail['default_dnsserver'] );
        
        if ( $webcreation == 'on' ) {
            
            $clientsites = $client->client_get_sites_by_user( $session_id, $sys_userid, $sys_groupid );
            
            $i = 0;
            $j = 1;
            while ($j <= count($clientsites) ) {

                $domainres = $client->sites_web_domain_set_status( $session_id, $clientsites[$i]['domain_id'],  'active' );
                logModuleCall('ispconfig','UnSuspend Web Domain',$clientsites[$i]['domain_id'], $domainres,'','');
                $i++;
                $j++;
                
            }
            
        }
   
        if ( $addmaildomain == 'on' ) {
            
            $emaildomain = $client->mail_domain_get_by_domain( $session_id, $domain );            
            $mailid = $client->mail_domain_set_status($session_id, $emaildomain[0]['domain_id'], 'active');
            logModuleCall('ispconfig','UnSuspend Email Domain',$emaildomain[0]['domain_id'], $mailid,'','');
            
        }
        
        if ( $dns == 'on' ) {           
        
            $i = 0;
            $j = 1;
            while ($j <= count($domain_id) ) {

                $affected_rows = $client->dns_zone_set_status( $session_id, $domain_id[$i]['id'], 'active' );
                $i++;
                $j++;
                logModuleCall('ispconfig','UnSuspend Domain',$domain_id[$i]['id'], $affected_rows,'','');
            }
            
        }

        $client_detail['locked'] = 'n';
        $client_detail['password'] = '';
        $client_result = $client->client_update( $session_id, $sys_userid, $parent_client_id, $client_detail );
        
        logModuleCall('ispconfig','UnSuspend Client', $sys_userid.' '.$sys_groupid, $client_result,'','');
        
        if ($client->logout( $session_id )) {
        }

        $successful = '1';
    
    } catch (SoapFault $e) {
        
        $error = 'SOAP Error: ' . $e->getMessage();
        $successful = '0';
        
    }

    if ($successful == 1) {

        $result = 'success';

    } else {

        $result = 'Error: ' . $error;

    }
    
    } else {
        /*
         * No username or password set.
         */
        $result = 'Username or Password is Blank or Not Set';
    }
    
    return $result;
}

function ispconfig3_ChangePassword( $params ) {

    $username           = $params['username'];
    $password           = $params['password'];
    $clientsdetails     = $params['clientsdetails'];
    $soapuser           = $params['serverusername'];
    $soappassword       = $params['serverpassword'];
    $soapsvrurl         = $params['serverhostname'];
    $soapsvrssl         = $params['serversecure'];
    $soapsvrport        = $params['serverport'];

require 'lib/soap_location.php';
    
    if (
            ((isset($username)) &&
            ($username != '')) &&
            ((isset($password)) &&
            ($password != ''))
            ) 
        {
    
    try {
        /* Connect to SOAP Server */
        require 'lib/soap_connect.php';
        
        /* Authenticate with the SOAP Server */
        require 'lib/soap_authenticate.php';
        
        $domain_id = $client->client_get_by_username( $session_id, $username );

        $client_id = $domain_id['client_id'];

        $returnresult = $client->client_change_password( $session_id, $client_id, $password );

        logModuleCall('ispconfig','ChangePassword', $clientsdetails, $returnresult,'','');
        
        if ($client->logout( $session_id )) {

        }

        if ($returnresult == 1 ) {
            
            $successful = '1';
            
        } else {
            
            $successful = '0';
            $result = "Password change failed";
            
        }
        
        
    } catch (SoapFault $e) {
        
        $error = 'SOAP Error: ' . $e->getMessage();
        $successful = '0';
        
    }
    
    if ($successful == 1) {
        
        $result = 'success';
        
    } else {
        
        $result = 'Error: ' . $error;
        
    }
    
    } else {
        /*
         * No username or password set.
         */
        $result = 'Username or Password is Blank or Not Set';
    }
    
    return $result;
}

function ispconfig3_LoginLink( $params ) {
    $soapsvrurl         = $params['serverhostname'];
    $soapsvrssl         = $params['serversecure'];
    $soapsvrport        = $params['serverport'];

    if ( $soapsvrssl == 'on' ) {
        
        $soapsvrurl = 'https://' . $soapsvrurl .':'. $soapsvrport. '';
        
    } else {
        
        $soapsvrurl = 'http://' . $soapsvrurl .':'. $soapsvrport . '';
        
    }

    return '
    <button type="button" class="btn-xs" onclick="$(\'#frmIspconfigLogin\').submit()">Control Panel Login</button>
    <script type="text/javascript">
    var ispconfigForm = "<form id=\"frmIspconfigLogin\" action=\"'.$soapsvrurl.'/index.php\" method=\"GET\" target=\"_blank\"></form>";
    $(document).ready(function(){
        $("body").append(ispconfigForm);
        $("#frmIspconfigLogin").submit(function(){
            $.ajax({ 
                type: "POST", 
                url: "'.$soapsvrurl.'/content.php",
                data: "s_mod=login&s_pg=index&username='.$params['username'].'&passwort='.$params['password'].'", 
                xhrFields: {withCredentials: true} 
            });
        });
    });
    </script>';
}

function ispconfig3_ClientArea( $params ) {
    $soapsvrurl         = $params['serverhostname'];
    $soapsvrssl         = $params['serversecure'];
    $soapsvrport        = $params['serverport'];

    if ( $soapsvrssl == 'on' ) {
        
        $soapsvrurl = 'https://' . $soapsvrurl .':'. $soapsvrport. '';
        
    } else {
        
        $soapsvrurl = 'http://' . $soapsvrurl .':'. $soapsvrport . '';
        
    }

    $code = '
    <form id="frmIspconfigLogin" action="'.$soapsvrurl.'/index.php" method="GET" target="_blank">
    <button type="submit" class="btn-xs">ISPConfig 3 Panel Login</button>
    </form>

    <script type="text/javascript">
    $("#frmIspconfigLogin").submit(function(){
        $.ajax({ 
            type: "POST", 
            url: "'.$soapsvrurl.'/content.php",
            data: "s_mod=login&s_pg=index&username='.$params['username'].'&passwort='.$params['password'].'", 
            xhrFields: {withCredentials: true} 
        });
    });
    </script>';

    return $code;
}

?>
