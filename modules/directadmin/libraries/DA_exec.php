<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DA_exec 
{
    public $handle;
	public $list_result = true;	
	public $host = "";
	public $username = "";
	public $protocol = "";
	public $password = "";
	public $login_as = false;
	public $login = false;
	public $error = false;
	private static $db;

 
	public function __construct($server) {
		$this->handle = curl_init();
		curl_setopt_array($this->handle, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_TIMEOUT => 60
		));
	  
		$this->protocol = ($server->use_ssl == 'Yes') ? 'https://' : 'http://';
		$host = $server->hostname.":".$server->port;
		$this->connect($host)->login($server->username, $server->authkey);
	}

	private function set_auth($auth) {
		$header = $auth ? array("Authorization: Basic ".base64_encode($auth)) : array();
		curl_setopt($this->handle, CURLOPT_HTTPHEADER, $header);
		return $this;
	}

	public function connect($host) {
		$this->host = rtrim(strval($host), "/");
		$this->login = $this->host == "" || $this->username == "" || $this->password == "" ? false : true;
		return $this;
	}

	public function login($username, $password) {
		$this->username = strval($username);
		$this->password = strval($password);
		$this->login_as = false;
		$this->login = $this->host == "" || $this->username == "" || $this->password == "" ? false : true;
		$this->set_auth($this->username.":".$this->password);
		return $this;
	}

	public function login_as($username) {
		$this->login_as = strval($username);
		$this->set_auth($this->username."|".$this->login_as.":".$this->password);
		return $this;
	}

	public function logout($all = false) {
		if ($all || !$this->login_as) {
			$this->username = "";
			$this->password = "";
			$this->login_as = false;
			$this->login = false;
			$this->set_auth(false);
		} else {
			$this->login($this->username, $this->password);
		}
		return $this;
	}

	public function query($command, $form = null, $method = "GET") {
		if ($this->host == "" || $this->username == "" || $this->password == "") {
			$this->login = false;
			$this->error = true;
			return false;
		}	

		$command = ltrim($command, "/");
		$form = is_array($form) ? http_build_query($form) : (is_string($form) ? $form : null);
		curl_setopt_array($this->handle, array(
			CURLOPT_URL => $this->protocol.$this->host."/".$command.($method === "GET" && is_string($form) ? "?".$form : ""),
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => $method !== "GET" ? $form : null
		));
		$response = curl_exec($this->handle);
		if (curl_errno($this->handle) === 0) {
			return $this->parse($response, false, $command);
		} else {
			$this->error = true;
			return $response;
		}
	}

	public function parse($response, $force = true, $command = "CMD_API_") {
		if ($force || substr($command, 0, 8) === "CMD_API_") {
			if (!$force && stripos($response, "<html>") !== false) {
				$this->error = true;
				return $response;
			}
			parse_str($response, $array);
			if (!isset($array["error"]) || $array["error"] === "0") {
				$this->error = false;
				if ($this->list_result && !isset($array["error"]) && isset($array["list"])) {
					$array = $array["list"];
				}
			} else {
				$this->error = true;
			}
			return $array;
		} else {
			$this->error = null;
			return $response;
		}
	}
          

}

/* End of file model.php */
