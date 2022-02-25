<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Whoisxmlapi extends MX_Controller {

	private $key;

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->model(array('App'));
		$this->key = config_item('whoisxmlapi_key');
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

	
}


// End of file
