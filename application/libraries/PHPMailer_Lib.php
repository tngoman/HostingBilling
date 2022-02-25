<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;  

class PHPMailer_lib extends CI_Model
{
    public function __construct(){
 
        log_message('Debug', 'PHPMailer class is loaded.');


    }

    public function load(){
        // Include PHPMailer library files

        require_once APPPATH.'/libraries/PHPMailer/PHPMailer.php';
        require_once APPPATH.'/libraries/PHPMailer/SMTP.php';
        require_once APPPATH.'/libraries/PHPMailer/Exception.php';


        $mail = new PHPMailer(true);
        return $mail;
    }
}