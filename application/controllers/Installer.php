<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

 
class Installer extends MX_Controller
{
    private $update_url;

    public function __construct()
    {
        parent::__construct(); 
        $this->load->helper(array('file')); 
    }

    public function index()
    {
        $this->load->view('install');
    }

    public function _check_install()
    {
        if (is_file('./application/config/installed.txt')) {
            return true;
        }
        return false;
    }

    public function _installed()
    {
        $this->_enable_system_access();
        $this->_change_routing();
        redirect();
    }

    public function start()
    {
        $this->session->sess_destroy();
        redirect('installer/?step=2', 'refresh');
    }

    public function db_setup()
    {
        $db_connect = $this->verify_db_connection();

        if ($db_connect) {
            $create_config = $this->_create_db_config();

            $this->_step_complete('database_setting', '3');

            redirect('installer/?step=3');
        } else {
            $this->session->set_flashdata('message', 'Database connection failed please try again.');
            redirect('installer/?step=2');
        }
    }


    public function install()
    {     
        $this->session->set_userdata('lang', 'english');
        
        if (!$this->_initialize_db(config_item('version'))) {
            $this->session->set_flashdata('message', 'Database import failed. Check if the file exists: resource/tmp/hostingbilling_'. $version .'.sql');
            redirect('installer/?step=3');
        } 

        $this->_step_complete('verify_purchase', '4');
        redirect('installer/?step=4');
    }


    public function complete()
    {
        $this->_enable_system_access();

        $this->_create_admin_account();

        $this->_change_routing();

        $this->_change_htaccess();

        $this->session->sess_destroy();

        redirect('installer/done');
    }

    public function done()
    {
        $this->load->view('installed');
    }

    public function _step_complete($setting, $next_step)
    {
        $formdata = array(
            $setting => 'complete',
            'next_step' => $next_step,
        );

        return $this->session->set_userdata($formdata);
    }

    public function _create_db_config()
    {
        // Replace the database settings
        $dbdata = read_file('./application/config/database.php');
        $dbdata = str_replace('db_name', $this->input->post('set_database'), $dbdata);
        $dbdata = str_replace('db_user', $this->input->post('set_db_user'), $dbdata);
        $dbdata = str_replace('db_pass', $this->input->post('set_db_pass'), $dbdata);
        $dbdata = str_replace('db_host', $this->input->post('set_hostname'), $dbdata);
        write_file('./application/config/database.php', $dbdata);
    }    
  
 

    public function _create_admin_account()
    {
        $this->load->library('tank_auth');
        $this->db->truncate('users');
        $this->db->truncate('account_details');
        $this->db->where('config_key', 'webmaster_email')->delete('config');

        // Prepare system settings
        $username = $this->input->post('set_admin_username');
        $email = $this->input->post('set_admin_email');
        $password = $this->input->post('set_admin_pass');
        $fullname = $this->input->post('set_admin_fullname');
        $company = $this->input->post('set_company_name');
        $company_email = $this->input->post('set_company_email');
        $email_activation = false;
        $base_url = $this->input->post('set_base_url');
        $purchase_code = $this->session->userdata('purchase_code');

        $codata = array('value' => $company);
        $this->db->where('config_key', 'company_name')->update('config', $codata);

        $codata = array('value' => $company);
        $this->db->where('config_key', 'company_legal_name')->update('config', $codata);

        $codata = array('value' => $company.' Sales');
        $this->db->where('config_key', 'billing_email_name')->update('config', $codata);

        $codata = array('value' => $company.' Support');
        $this->db->where('config_key', 'support_email_name')->update('config', $codata);

        $codata = array('value' => $company);
        $this->db->where('config_key', 'website_name')->update('config', $codata);

        $codata = array('value' => $fullname);
        $this->db->where('config_key', 'contact_person')->update('config', $codata);

        $codata = array('value' => $username);
        $this->db->where('config_key', 'mail_username')->update('config', $codata);

        $codata = array('value' => $purchase_code);
        $this->db->where('config_key', 'purchase_code')->update('config', $codata);

        $codata = array('value' => $company_email);
        $this->db->where('config_key', 'smtp_user')->update('config', $codata);

        $codata = array('value' => $company_email);
        $this->db->where('config_key', 'postmark_from_address')->update('config', $codata);

        $codata = array('value' => $company_email);
        $this->db->where('config_key', 'support_email')->update('config', $codata);

        $codata = array('value' => 'TRUE');
        $this->db->where('config_key', 'valid_license')->update('config', $codata);

        $codata = array('value' => $company_email);
        $this->db->where('config_key', 'company_email')->update('config', $codata);

        $codata = array('value' => $company_email);
        $this->db->where('config_key', 'paypal_email')->update('config', $codata);

        $codata = array('value' => $company_email);
        $this->db->where('config_key', 'billing_email')->update('config', $codata);

        $codata = array('value' => $base_url);
        $this->db->where('config_key', 'company_domain')->update('config', $codata);

        return $this->tank_auth->create_user(
            $username,
            $email,
            $password,
            $fullname,
            '-',
            '1',
            '',
            $email_activation,
            $company,
            '0'
        );
    }

    public function _initialize_db($version = null)
    {
        // Run the installer sql schema
        $this->load->database();

        $file = 'hostingbilling_'. $version .'.sql';

        $templine = '';
        // Read in entire file
        $lines = file('./resource/tmp/' . $file);
        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }
            $templine .= $line;
            if (substr(trim($line), -1, 1) == ';') {
                $this->db->query($templine) or print 'Error performing query \'<strong>'.$templine.'\': '.mysql_error().'<br /><br />';
                $templine = '';
            }
        }

        return true;
    }

    public function _enable_system_access()
    {
        $confdata = read_file('./application/config/config.php');
        $confdata = str_replace(
            '$config[\'enable_hooks\'] = FALSE;',
            '$config[\'enable_hooks\'] = TRUE;',
            $confdata);
        $confdata = str_replace(
            '$config[\'index_page\'] = \'index.php\';',
            '$config[\'index_page\'] = \'\';',
            $confdata);

        write_file('./application/config/config.php', $confdata);

        $libdata = read_file('./application/config/autoload.php');
        $libdata = str_replace(
            '$autoload[\'libraries\'] = array(\'session\');',
            '$autoload[\'libraries\'] = array(\'session\',\'database\',\'tank_auth\',\'applib\',\'module\');',
            $libdata);
        write_file('./application/config/autoload.php', $libdata);
    }

    public function _change_routing()
    {
        // Replace the default routing controller
        $rdata = read_file('./application/config/routes.php');
        $rdata = str_replace('installer', 'home', $rdata);
        write_file('./application/config/routes.php', $rdata);

        $data = 'Installed';
        if (write_file('./application/config/installed.txt', $data)) {
            return true;
        }
    }

    public function _change_htaccess()
    {
        $subfolder = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        if (!empty($subfolder)) {
            $input = '<IfModule mod_rewrite.c>
                        RewriteEngine On
                        RewriteBase '.$subfolder.'
                        RewriteCond %{REQUEST_URI} ^system.*
                        RewriteRule ^(.*)$ /index.php?/$1 [L]

                        RewriteCond %{REQUEST_URI} ^application.*
                        RewriteRule ^(.*)$ /index.php?/$1 [L]

                        RewriteCond %{REQUEST_FILENAME} !-f
                        RewriteCond %{REQUEST_FILENAME} !-d
                        RewriteRule ^(.*)$ index.php?/$1 [L]
                        </IfModule>

                        <IfModule !mod_rewrite.c>
                        ErrorDocument 404 /index.php
                       </IfModule>';

            $current = @file_put_contents('./.htaccess', $input);
        }
    }

    // -------------------------------------------------------------------------------------------------

    /*
     * Database validation check from user input settings
     */
    public function verify_db_connection()
    {
        $link = @mysqli_connect(
            $this->input->post('set_hostname'),
            $this->input->post('set_db_user'),
            $this->input->post('set_db_pass'),
            $this->input->post('set_database')
        );
        if (!$link) {
            @mysqli_close($link);

            return false;
        }

        @mysqli_close($link);

        return true;
    }

    // -------------------------------------------------------------------------------------------------

    /*
     * Database check connection
     */
    public function _verify_db_config($host, $user, $pass, $database)
    {
        $link = @mysqli_connect(
            $host,
            $user,
            $pass,
            $database
        );
        if (!$link) {
            @mysqli_close($link);

            return false;
        }

        @mysqli_close($link);

        return true;
    }
 
}

/* End of file installer.php */
