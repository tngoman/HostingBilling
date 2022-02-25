<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class CWP_exec 
{
    private $host;
    private $port;
    private $protocol;
    private $login; 
    private $secretKey;
    public $uri;
 

    public function __construct($server)
    {                     
        $this->host = $server->hostname; 
        $this->port = $server->port;
        $this->login = $server->username; 
        $this->secretKey = $server->authkey;      
        $this->protocol = ($server->use_ssl == 'Yes') ? 'https://' : 'http://';
        $this->host = $this->protocol.$this->host.':' .$this->port.'/v1/';         
    }

 

    public function exec($data)
    {
        $data['key'] = $this->secretKey;
        $url = $this->host . $this->uri;  

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response; 
    }
 
          

}

/* End of file model.php */
