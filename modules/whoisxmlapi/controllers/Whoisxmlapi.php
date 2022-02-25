<?php
/* Module Name: Whoisxmlapi
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Domain Availability
 * Description: Whois XML API Payment Gateway.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Whoisxmlapi extends Hosting_Billing
{ 
	
	private $key;

	function __construct()
	{
		parent::__construct(); 
		 $this->config = get_settings('whoisxmlapi');
		 if(!empty($this->config))
		 {    
			 $this->key = $this->config['api_key']; 
		}			
	 }
 
 
	 public function whoisxmlapi_config ($values = null)
	 {
		 $config = array( 
			 array(
				 'label' => lang('whoisxmlapi_key'), 
				 'id' => 'api_key',
				 'value' => isset($values) ? $values['api_key'] : ''
			 ) 
		 ); 
		 
		 return $config;        
	 } 

 
	public function check_domain ($sld, $tld)
	{	
		$domain = $sld .'.'. $tld;

		$url = 'https://domain-availability-api.whoisxmlapi.com/api/v1?apiKey='.$this->key.'&domainName='.$domain.'&mode=DNS_AND_WHOIS';
		$res = json_decode(file_get_contents($url), true);

		if ($res['DomainInfo']['domainName'] == $domain && $res['DomainInfo']['domainAvailability'] == 'UNAVAILABLE') {				
			return 0;  
		}

		elseif ($res['DomainInfo']['domainName'] == $domain && $res['DomainInfo']['domainAvailability'] == 'AVAILABLE') {
			return 1;
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

 