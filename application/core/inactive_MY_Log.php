<?php
 if (!defined('BASEPATH')) {
     exit('No direct script access allowed');
 }

 
class MY_Log extends CI_Log
{
    protected $_threshold = 1;
    protected $_date_fmt = 'Y-m-d H:i:s';
    protected $_levels = array('ERROR' => '1', 'DEBUG' => '2',  'INFO' => '3', 'ALL' => '4');
    /**
     * Constructor.
     */
    public function __construct()
    {
        $config = &get_config();
        if (is_numeric($config['log_threshold'])) {
            $this->_threshold = $config['log_threshold'];
        }
        if ($config['log_date_format'] != '') {
            $this->_date_fmt = $config['log_date_format'];
        }

        $this->_log_path = ($config['log_path'] !== '') ? $config['log_path'] : APPPATH.'logs/';
        file_exists($this->_log_path) OR mkdir($this->_log_path, 0755, TRUE);

		if ( ! is_dir($this->_log_path) OR ! is_really_writable($this->_log_path))
		{
			$this->_enabled = FALSE;
		}
    }
    // --------------------------------------------------------------------
    /**
     * Write Log to php://stderr.
     *
     * Generally this function will be called using the global log_message() function
     *
     * @param   string  the error level
     * @param   string  the error message
     * @param   bool    whether the error is a native PHP error
     *
     * @return bool
     */
    public function write_log($level, $msg, $php_error = false)
    {
        $level = strtoupper($level);
        if (!isset($this->_levels[$level]) or ($this->_levels[$level] > $this->_threshold)) {
            return false;
        }
        $config = &get_config();
        $ci = &get_instance();
        $ci->load->library('user_agent');
        $data = array(
                'browser'           => $ci->agent->browser(),
                'browser_agent'     => $ci->agent->version(),
                'os'                => $ci->agent->platform(),
                'server_host'       => $_SERVER['HTTP_HOST'],
                'server_name'       => $_SERVER['SERVER_NAME'],
                'url'               => 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
                'referer'           => $ci->agent->referrer(),
                'msg'               => $msg,
                'level'             => $level,
                'purchase_code'     => $config['purchase_code'],
                'date_raised'       => date($this->_date_fmt),
                'headers'           => json_encode(getallheaders())
            );
         //$this->_post_remote($data);

        $filepath = $this->_log_path.'log-'.date('Y-m-d').'.php';
        $message = '';
        if (!file_exists($filepath)) {
            $message .= '<'."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
        }
        if (!$fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
            return false;
        }
        $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' '.date($this->_date_fmt).' --> '.$msg."\n";
        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);
          
            if (octdec(substr(sprintf('%o', fileperms($filepath)), -4)) != FILE_WRITE_MODE) {
                @chmod($filepath, FILE_WRITE_MODE);
            }

        return true;
    }

    public function post_remote($data){
        $data_string = json_encode($data);

        $ch = curl_init('http://hostingdomain.co.za/api/logger/issues');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20); //timeout in seconds
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
            )
        );
        //$result = curl_exec($ch);
        //close connection
        curl_close($ch);
    }

}
// END Log Class
/* End of file MY_Log.php */
/* Location: ./application/core/MY_Log.php */
