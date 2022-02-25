<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cyber_exec 
{
    private $host;
    private $port;
    private $protocol;
    private $login;
    private $password;
    private $secretKey; 
 

    public function __construct($server)
    {                     
        $this->host = $server->hostname; 
        $this->port = $server->port;

        if(strlen($server->authkey) > 30){
            $this->secretKey = $server->authkey;
        }
        else {
            $this->login = $server->username;
            $this->password = $server->authkey;
            $this->secretKey = false;
        }

        $this->protocol = ($server->use_ssl == 'Yes') ? 'https' : 'http';
    }

 
    private function callUrl($url)
	{
        return $this->protocol. "://".$this->host.":". $this->port ."/api/".$url;
	}
	
	
   



    public function change_account_status($params)
    {
        $url = "submitWebsiteStatus";
        $postParams =
            [
                "adminUser" => $params["serverusername"],
                "adminPass" => $params["serverpassword"],
                "websiteName" => $params["domain"],
                "state" => $params["status"],
            ];
        $result = $this->call_cyberpanel($params, $url, $postParams);
        return $result;
    }

  

	
    public function terminate_account($params)
    {
        $url = "deleteWebsite";
        $postParams =
            [
                "adminUser" => $params["serverusername"],
                "adminPass" => $params["serverpassword"],
                "domainName"=> $params["domain"]
            ];
        $result = $this->call_cyberpanel($params, $url, $postParams);

        return $result;
    }
	
    public function change_account_password($params)
    {
        $url = "changeUserPassAPI";
        $postParams =
            [
                "adminUser" => $params["serverusername"],
                "adminPass" => $params["serverpassword"],
                "websiteOwner"=> $params["username"],
                "ownerPassword"=> $params["password"]
            ];
        $result = $this->call_cyberpanel($params, $url, $postParams);
        return $result;
    }
	
    public function change_account_package($params)
    {
        $url = "changePackageAPI";
        $postParams =
            [
                "adminUser" => $params["serverusername"],
                "adminPass" => $params["serverpassword"],
                "websiteName"=> $params["domain"],
                "packageName"=> $params['configoption1']
            ];
        $result = $this->call_cyberpanel($params, $url, $postParams);
        return $result;
    }


    public function call($url,$post = array())
	{
        $post['adminUser'] = $this->login;
        $post['adminPass'] = $this->password;

		$call = curl_init();
		curl_setopt($call, CURLOPT_URL, $this->callUrl($url));	
		curl_setopt($call, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($call, CURLOPT_RETURNTRANSFER, true);	
		curl_setopt($call, CURLOPT_POST, true);
		curl_setopt($call, CURLOPT_POSTFIELDS, json_encode($post));
		curl_setopt($call, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($call, CURLOPT_SSL_VERIFYPEER, false);

		// Fire api
		$result = curl_exec($call);
		$info = curl_getinfo($call);
		curl_close($call);
		$result = json_decode($result,true);

		// Return data
		return $result;
	}
          

}

/* End of file model.php */
