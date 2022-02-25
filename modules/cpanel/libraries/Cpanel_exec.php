<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cpanel_exec 
{
    private $host;
    private $username;
    private $hash;
    private $port;  
    private $use_ssl;
    protected $headers = [];
    
    public function __construct($server)
    { 
        if(!$server) { 
                throw new Exception('Authorization Key not found!', 2301); 
        }
        return $this->checkSettings($server)
            ->setHost(trim($server->hostname))
            ->setPort($server->port)
            ->setProtocol($server->use_ssl)
            ->setAuth(trim($server->username), trim($server->authkey));
    }


    public function call($method, $arg)
    {
        $this->buildArg($arg);

        return $this->cpQuery($method);
    }

    
    private function checkSettings($options)
    {
        if (empty($options->username)) {
            throw new Exception('Username is not set', 2301);
        }
        if (empty($options->authkey)) {
            throw new Exception('Hash is not set', 2302);
        }
        if (empty($options->hostname)) {
            throw new Exception('CPanel Host is not set', 2303);
        }

        return $this;
    }

    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    public function setProtocol($yesno)
    {
        $this->use_ssl = $yesno;

        return $this;
    }

    public function setAuth($user, $hash)
    {
        $this->user = $user;
        $this->hash = $hash;

        return $this;
    }

    public function callHost()
    {
        return $this->host;
    }

    public function callUser()
    {
        return $this->user;
    }

    public function callHash()
    {
        return $this->hash;
    }

    public function setHeader($name, $value = '')
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function buildArg($arg)
    {
        $this->arg = http_build_query($arg);

        return $this;
    }

    private function makeHeader()
    {
        $headers = $this->headers;
        $user = $this->callUser();
        $hash = $this->callHash();

        return $headers['Authorization'] = 'WHM '.$user.':'.preg_replace("'(\r|\n)'", '', $hash);
    }

    protected function cpQuery($method)
    {
        $host = $this->callHost();
        $user = $this->callUser();
        $token = $this->callHash(); 

        $protocol = ($this->use_ssl == 'Yes') ? 'https' : 'http';
        $port = $this->port;
        $args = $this->arg;                


        $query = $protocol."://" . $host . ":".$port."/json-api/".$method."?api.version=1&".$args;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        $header[0] = "Authorization: whm $user:$token";
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl, CURLOPT_URL, $query);

        $result = curl_exec($curl);

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_status != 200) {
            return array("error" => $http_status);

        } else {
            return json_decode($result, true);
            }           

    }

          

}

/* End of file model.php */
