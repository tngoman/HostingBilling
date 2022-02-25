<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Auth extends Hosting_Billing
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library(array('tank_auth','form_validation','recaptcha'));
        $this->load->helper('security');
        $lang = config_item('default_language');
        if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
        if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
        $this->lang->load('hd', $lang);

        foreach (config_item('tank_auth') as $key => $value) {
            $this->config->set_item($key, $value);
        }
        $this->load->model(array('App', 'User'));

        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->set_theme(config_item('active_theme'));
        $this->template->set_partial('header', 'sections/header');
        $this->template->set_partial('footer', 'sections/footer');      

    }

    function index()
    {
        if ($message = $this->session->flashdata('message')) {
            $this->load->view('auth/general_message', array('message' => $message));
        } else {
            redirect('login');
        }
    }

    /**
     * Login user on the site
     *
     * @return void
     */
    function login()
    {
        if ($this->tank_auth->is_logged_in()) {     // logged in

            if($this->session->userdata('process') == true && !User::is_admin()) {
                redirect('invoices/create');
            } 
            
            else{
                redirect('/dashboard');
            }

            
            
        } elseif ($this->tank_auth->is_logged_in(FALSE)) {  // logged in, not activated
            redirect('/auth/send_again/');

        } else {
            $data['login_by_username'] = (config_item('login_by_username') AND
                config_item('use_username'));
            $data['login_by_email'] = config_item('login_by_email');

            $this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('remember', 'Remember me', 'integer');

         
            // Get login for counting attempts to login
            if (config_item('login_count_attempts') AND
                ($login = $this->input->post('login'))) {
                $login = $this->security->xss_clean($login);
            } else {
                $login = '';
            }

            $data['use_recaptcha'] = (config_item('use_recaptcha') == 'TRUE') ? TRUE : FALSE;
            if (config_item('captcha_login') == 'TRUE') {
                if ($data['use_recaptcha'])
                    $this->form_validation->set_rules('g-recaptcha-response', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
                else if(config_item('use_recaptcha') == 'TRUE'){
                    $this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
                }
                    
            }
            $data['errors'] = array();


            if ($this->form_validation->run($this)) {								// validation ok
                if ($this->tank_auth->login(
                    $this->form_validation->set_value('login'),
                    $this->form_validation->set_value('password'),
                    $this->form_validation->set_value('remember'),
                    $data['login_by_username'],
                    $data['login_by_email'])) {		
                    
                        if($this->session->userdata('process') == true && !User::is_admin()) {
                            redirect('invoices/create');
                        }
                        
                        else {
                            redirect('dashboard');
                        }
                    

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    if (isset($errors['banned'])) {
                        $this->session->set_flashdata('message',$this->lang->line('auth_message_banned').' '.$errors['banned']);	// banned user
                        //$this->_show_message();
                        redirect('login');

                    } elseif (isset($errors['not_activated'])) {				// not activated user
                        redirect('/auth/send_again/');

                    } else {													// fail
                        foreach ($errors as $k => $v) { $data['errors'][$k] = $this->lang->line($v); }
                    }
                }
            }


            $data['captcha_login'] = FALSE;
            $data['show_captcha'] = FALSE;
            if(config_item('captcha_login') == 'TRUE'){
                $data['show_captcha'] = TRUE;
                if ($data['use_recaptcha']) {
                     $data['recaptcha_html'] = $this->_create_recaptcha();
                } else {
                    $data['captcha_html'] = $this->_create_captcha();
                }
            }


            if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
                $data['show_captcha'] = TRUE;
            }
     
            
            $this->template->title(lang('welcome_to').' '.config_item('company_name'));
            $data['ref_item'] = $this->input->get('r_url', TRUE) ? $this->input->get('r_url', TRUE) : NULL;
            $this->template->set_layout('main')->build('auth/login_form', isset($data) ? $data : NULL);
        }
    }

    /**
     * Logout user
     *
     * @return void
     */
    function logout()
    {
        $this->tank_auth->logout();
        redirect('');

        //$this->_show_message($this->lang->line('auth_message_logged_out'));
    }

    /**
     * Register user on the site
     *
     * @return void
     */
    function register()
    {
        $captcha_registration   = (config_item('captcha_registration') == 'TRUE' ) ? TRUE : FALSE;
        $use_recaptcha  = (config_item('use_recaptcha') == 'TRUE') ? TRUE : FALSE;
        $use_username = config_item('use_username');

        if ($this->tank_auth->is_logged_in()) {     // logged in

            if($this->session->userdata('process') == true) {
                redirect('invoices/create');
            } 
            else {
                redirect('dashboard');
            }

        } elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
            redirect('/auth/send_again/');

        } elseif (config_item('allow_client_registration') == 'FALSE') {	// registration is off
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message', lang('auth_message_registration_disabled'));
            redirect('login');

        } else {

            if ($use_username) {
                $this->form_validation->set_rules('username', lang('username'), 'trim|required|xss_clean|min_length['.config_item('username_min_length').']|max_length['.config_item('username_max_length').']');
            }
            $this->form_validation->set_rules('fullname', lang('full_name'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', lang('email'), 'trim|required|xss_clean|valid_email');
            $this->form_validation->set_rules('password', lang('password'), 'trim|required|xss_clean|min_length['.config_item('password_min_length').']|max_length['.config_item('password_max_length').']');
            $this->form_validation->set_rules('confirm_password', lang('confirm_password'), 'trim|required|xss_clean|matches[password]');

            $this->form_validation->set_rules('address', lang('address_line_1'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('phone', lang('phone'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('city', lang('city'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('state', lang('state_province'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('zip', lang('zip_code'), 'trim|required|xss_clean');


            if ($captcha_registration) {
                if ($use_recaptcha) {
                    $this->form_validation->set_rules('g-recaptcha-response', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
                } else {
                    $this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
                }
            }
            $data['errors'] = array();
            $email_activation = config_item('email_activation');
            $individual = $this->input->post('company_name') == '' ? 1:0;
 

            if ($this->form_validation->run($this)) {					
              
                if (!is_null($data = $this->tank_auth->create_user(
                    $use_username ? $this->form_validation->set_value('username') : '',
                    $this->form_validation->set_value('email'),
                    $this->form_validation->set_value('password'),
                    $this->input->post('fullname'),
                    '-',
                    '2',
                    $this->input->post('phone'),
                    $email_activation,
                    $individual == 1 ? $this->input->post('fullname') : $this->input->post('company_name'),
                    $individual,
                    $this->input->post('address'),
                    $this->input->post('city'),
                    $this->input->post('state'),
                    $this->input->post('zip'),
                    $this->input->post('country'),
                    ''                   
                    ))) {	 // success

                        $data['site_name'] = config_item('company_name');
    
                        if ($email_activation) {									// send "activate" email
                            $data['activation_period'] = config_item('email_activation_expire') / 3600;
    
                            $this->_send_email('activate', $data['email'], $data);
    
                            unset($data['password']); // Clear password (just for any case)
    
                            $this->session->set_flashdata('message', lang('auth_message_registration_completed_1'));
                            redirect('/auth/login');
    
                        } else {
                            if (config_item('email_account_details') == 'TRUE') {	// send "welcome" email
    
                                $this->_send_email('welcome', $data['email'], $data);
                            }
                            unset($data['password']); // Clear password (just for any case)
                            $this->session->set_flashdata('message', lang('auth_message_registration_completed_2'));
                            redirect('/auth/login');
                            //$this->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
                        }
                    } else {
                        $errors = $this->tank_auth->get_error_message();
                        foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
                    }
                }
                if ($captcha_registration) {
                    if ($use_recaptcha) {
                        // $data['recaptcha_html'] = $this->_create_recaptcha();
                    } else {
                        $data['captcha_html'] = $this->_create_captcha();
                    }
                }
            
            $data['use_username'] = $use_username;
            $data['captcha_registration'] = $captcha_registration;
            $data['use_recaptcha'] = $use_recaptcha;
        
            $this->template->title('Register - '.config_item('company_name'));
            $this->template->set_layout('main')->build('auth/register_form', isset($data) ? $data : NULL);

        }
    }

    /**
     * Register user as admin on the site
     *
     * @return void
     */
    function register_user(){

        $use_username = config_item('use_username');

        $data['errors'] = array();

        $email_activation = config_item('email_activation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', lang('email'), 'trim|required|xss_clean|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean');
        $this->form_validation->set_rules('confirm_password', lang('confirm_password'), 'trim|required|xss_clean|matches[password]');

        $email_contact = ($this->input->post('email_contact')[0] == 'Yes') ? TRUE : FALSE;

        if ($this->form_validation->run($this)) {		// validation ok
            if (!is_null($data = $this->tank_auth->admin_create_user(
                $use_username ? $this->form_validation->set_value('username') : '',
                $this->form_validation->set_value('email'),
                $this->form_validation->set_value('password'),
                $this->input->post('fullname'),
                $this->input->post('company'),
                $this->input->post('role'),
                $this->input->post('phone'),
                $email_activation
            ))) {									// success

                $data['site_name'] = config_item('company_name');

                if ($email_activation) {									// send "activate" email
                    $data['activation_period'] = config_item('email_activation_expire') / 3600;

                    $this->_send_email('activate', $data['email'], $data);

                    unset($data['password']); // Clear password (just for any case)
                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message', lang('client_registered_successfully_activate'));
                    redirect($this->input->post('r_url'));

                } else {

                    if (config_item('email_account_details') == 'TRUE' && $email_contact) {	// send "welcome" email
                        $this->_send_email('welcome', $data['email'], $data);
                    }

                    unset($data['password']); // Clear password (just for any case)
                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message', lang('user_added_successfully'));
                    redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $errors = $this->tank_auth->get_error_message();

                foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
                $this->session->set_flashdata('response_status', 'error');
                $this->session->set_flashdata('message', lang('client_registration_failed'));
                $this->session->set_flashdata('form_errors', $data['errors'][$k]);
                redirect($this->input->post('r_url'));
            }
        }else{
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message', lang('client_registration_failed'));
            $this->session->set_flashdata('form_errors', validation_errors('<span class="text-danger">', '</span><br>'));
            redirect($this->input->post('r_url'));
        }

    }



    /**
     * Send activation email again, to the same or new email address
     *
     * @return void
     */
    function send_again()
    {
        if (!$this->tank_auth->is_logged_in(FALSE)) {							// not logged in or activated
            redirect('/auth/login/');

        } else {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run()) {								// validation ok
                if (!is_null($data = $this->tank_auth->change_email(
                    $this->form_validation->set_value('email')))) {			// success

                    $data['site_name']	= config_item('company_name');
                    $data['activation_period'] = config_item('email_activation_expire') / 3600;

                    $this->_send_email('activate', $data['email'], $data);
                    $this->session->set_flashdata('message', sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));
                    redirect('/auth/login');
                    //$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
                }
            }
        
            $this->template->title('Send Password - '.config_item('company_name'));
            $this->template->set_layout('main')->build('auth/send_again_form', isset($data) ? $data : NULL);
        }
    }

    /**
     * Activate user account.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function activate()
    {
        $user_id		= $this->uri->segment(3);
        $new_email_key	= $this->uri->segment(4);

        // Activate user
        if ($this->tank_auth->activate_user($user_id, $new_email_key)) {		// success
            $this->tank_auth->logout();
            //$this->_show_message($this->lang->line('auth_message_activation_completed').' '.anchor('/auth/login/', 'Login'));
            $this->session->set_flashdata('message', $this->lang->line('auth_message_activation_completed'));
            redirect('/auth/login');

        } else {
            // fail
            $this->session->set_flashdata('message', $this->lang->line('auth_message_activation_failed'));
            redirect('/auth/login');
            //$this->_show_message($this->lang->line('auth_message_activation_failed'));
        }
    }

    /**
     * Generate reset code (to change password) and send it to user
     *
     * @return void
     */
    function forgot_password()
    {
        if ($this->tank_auth->is_logged_in()) {									// logged in
            redirect('');

        } elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
            redirect('/auth/send_again/');

        } else {
            $this->form_validation->set_rules('login', 'Email or Username', 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run()) {								// validation ok
                if (!is_null($data = $this->tank_auth->forgot_password(
                    $this->form_validation->set_value('login')))) {

                    $data['site_name'] = config_item('company_name');

                    // Send email with password activation link
                    $this->_send_email('forgot_password', $data['email'], $data);
                    $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_sent'));
                    //$this->_show_message($this->lang->line('auth_message_new_password_sent'));
                    redirect('login');

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
                }
            }
       
            $this->template->title('Forgot Password - '.config_item('company_name'));
            $this->template->set_layout('main')->build('auth/forgot_password_form', isset($data) ? $data : NULL); 
        }
    }

    /**
     * Replace user password (forgotten) with a new one (set by user).
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function reset_password()
    {
        $user_id		= $this->uri->segment(3);
        $new_pass_key	= $this->uri->segment(4);

        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.config_item('password_min_length').']|max_length['.config_item('password_max_length').']');
        $this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

        $data['errors'] = array();

        if ($this->form_validation->run()) {								// validation ok
            if (!is_null($data = $this->tank_auth->reset_password(
                $user_id, $new_pass_key,
                $this->form_validation->set_value('new_password')))) {	// success

                $data['site_name'] = config_item('company_name');

                // Send email with new password
                $this->_send_email('reset_password', $data['email'], $data);
                $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_activated'));
                redirect('login');

            } else {
                // fail
                $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_failed'));
                redirect('login');
            }
        } else {
            // Try to activate user by password key (if not activated yet)

            if (config_item('email_activation')) {
                $this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);

            }

            if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
                $this->session->set_flashdata('message',$this->lang->line('auth_message_new_password_failed'));
                redirect('login');
            }
        }
 
        $this->template->title('Forgot Password - '.config_item('company_name'));
        $this->template->set_layout('main')->build('auth/reset_password_form', isset($data) ? $data : NULL);
    }

    /**
     * Change user password
     *
     * @return void
     */
    function change_password()
    {
        if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
            redirect('/auth/login/');

        } else {

            Applib::is_demo();

            $this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.config_item('password_min_length').']|max_length['.config_item('password_max_length').']');
            $this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

            $data['errors'] = array();

            if ($this->form_validation->run()) {								// validation ok
                if ($this->tank_auth->change_password(
                    $this->form_validation->set_value('old_password'),
                    $this->form_validation->set_value('new_password'))) {	// success

                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message',lang('auth_message_password_changed'));
                    redirect($this->input->post('r_url'));
                    //$this->_show_message($this->lang->line('auth_message_password_changed'));

                } else {														// fail
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
                }
            }

            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message',lang('password_or_field_error'));
            redirect('profile/settings');
        }
    }

    /**
     * Change user email
     *
     * @return void
     */
    function change_email()
    {
        if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
            redirect('login/');

        } else {
            Applib::is_demo();

            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run()) {								// validation ok
                if (!is_null($data = $this->tank_auth->set_new_email(
                    $this->form_validation->set_value('email'),
                    $this->form_validation->set_value('password')))) {			// success

                    $data['site_name'] = config_item('company_name');

                    // Send email with new email address and its activation link
                    $this->_send_email('change_email', $data['new_email'], $data);

                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message',sprintf(lang('auth_message_new_email_sent'), $data['new_email']));
                    redirect($this->input->post('r_url'));

                    //$this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));

                } else {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
                }
            }
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message',lang('password_or_email_error'));
            redirect('profile/settings');
        }
    }

    /**
     * Change username
     *
     * @return void
     */
    function change_username()
    {
        if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
            redirect('login');

        } else {

            Applib::is_demo();

            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run($this)) {								// validation ok
                $this->db->set('username', $this->input->post('username', TRUE));
                $this->db->where('username',$this->tank_auth->get_username())->update('users');
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message',lang('username_changed_successfully'));
                redirect($this->input->post('r_url'));
            }
            $this->session->set_flashdata('response_status', 'error');
            $this->session->set_flashdata('message',lang('username_not_available'));
            redirect('profile/settings');
        }
    }

    /**
     * Replace user email with a new one.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function reset_email()
    {
        $user_id		= $this->uri->segment(3);
        $new_email_key	= $this->uri->segment(4);

        // Reset email
        if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {	// success
            $this->tank_auth->logout();
            $this->session->set_flashdata('message',$this->lang->line('auth_message_new_email_activated'));
            redirect('login');

        } else {																// fail
            $this->session->set_flashdata('message',$this->lang->line('auth_message_new_email_failed'));
            redirect('login');
        }
    }

    /**
     * Delete user from the site (only when user is logged in)
     *
     * @return void
     */
    function unregister()
    {
        if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
            redirect('/auth/login/');

        } else {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

            $data['errors'] = array();

            if ($this->form_validation->run()) {								// validation ok
                if ($this->tank_auth->delete_user(
                    $this->form_validation->set_value('password'))) {		// success
                    $this->_show_message($this->lang->line('auth_message_unregistered'));

                } else {														// fail
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
                }
            }
            $this->load->view('auth/unregister_form', $data);
        }
    }

    /**
     * Show info message
     *
     * @param	string
     * @return	void
     */
    function _show_message($message)
    {
        $this->session->set_flashdata('message', $message);
        redirect('/auth/');
    }

    /**
     * Send email message of given type (activate, forgot_password, etc.)
     *
     * @param	string
     * @param	string
     * @param	array
     * @return	void
     */
    function _send_email($type, $email, &$data)
    {
        switch ($type)
        {
            case 'activate':
                return $this->_activate_email($email,$data);
                break;
            case 'welcome':
                return $this->_welcome_email($email,$data);
                break;
            case 'forgot_password':
                return $this->_email_forgot_password($email,$data);
                break;
            case 'reset_password':
                return $this->_email_reset_password($email,$data);
                break;
            case 'change_email':
                return $this->_email_change_email($email,$data);
                break;
        }
    }

    function _welcome_email($email,$data){

        $message = App::email_template('registration','template_body');
        $subject = App::email_template('registration','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $site_url = str_replace("{SITE_URL}",base_url().'auth/login',$logo);
        $username = str_replace("{USERNAME}",$data['username'],$site_url);
        $user_email =  str_replace("{EMAIL}",$data['email'],$username);
        $user_password =  str_replace("{PASSWORD}",$data['password'],$user_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$user_password);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);

    }


    function _email_change_email($email,$data){

        $message = App::email_template('change_email','template_body');
        $subject = App::email_template('change_email','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $email_key = str_replace("{NEW_EMAIL_KEY_URL}",base_url().'auth/reset_email/'.$data['user_id'].'/'.$data['new_email_key'],$logo);
        $new_email =  str_replace("{NEW_EMAIL}",$data['new_email'],$email_key);
        $site_url = str_replace("{SITE_URL}",base_url(),$new_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$site_url);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);

    }

    function _email_reset_password($email,$data){

        $message = App::email_template('reset_password','template_body');
        $subject = App::email_template('reset_password','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $username = str_replace("{USERNAME}",$data['username'],$logo);
        $user_email =  str_replace("{EMAIL}",$data['email'],$username);
        $user_password =  str_replace("{NEW_PASSWORD}",$data['new_password'],$user_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$user_password);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ '.config_item('company_name').' ]'.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);

    }

    function _email_forgot_password($email,$data){

        $message = App::email_template('forgot_password','template_body');
        $subject = App::email_template('forgot_password','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $site_url = str_replace("{SITE_URL}",base_url().'login',$logo);
        $key_url = str_replace("{PASS_KEY_URL}",base_url().'auth/reset_password/'.$data['user_id'].'/'.$data['new_pass_key'],$site_url);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$key_url);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;

        $params['subject'] = '[ '.config_item('company_name').' ] '.$subject;
        $params['message'] = $message;

        $params['attached_file'] = '';

        Modules::run('fomailer/send_email',$params);

    }

    function _activate_email($email,$data){

        $message = App::email_template('activate_account','template_body');
        $subject = App::email_template('activate_account','subject');
        $signature = App::email_template('email_signature','template_body');

        $logo_link = create_email_logo();

        $logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

        $activate_url = str_replace("{ACTIVATE_URL}",site_url('/auth/activate/'.$data['user_id'].'/'.$data['new_email_key']),$logo);
        $activate_period = str_replace("{ACTIVATION_PERIOD}",$data['activation_period'],$activate_url);
        $username = str_replace("{USERNAME}",$data['username'],$activate_period);
        $user_email =  str_replace("{EMAIL}",$data['email'],$username);
        $user_password =  str_replace("{PASSWORD}",$data['password'],$user_email);
        $EmailSignature = str_replace("{SIGNATURE}",$signature,$user_password);
        $message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

        $params['recipient'] = $email;
        $params['subject'] = '[ '.config_item('company_name').' ]'.' '.$subject;
        $params['message'] = $message;
        $params['attached_file'] = '';

        modules::run('fomailer/send_email',$params);

    }


    /**
     * Create CAPTCHA image to verify user as a human
     *
     * @return	string
     */
    function _create_captcha()
    {
        $this->load->helper('captcha');

        $cap = create_captcha(array(
            'img_path'		=> './'.config_item('captcha_path'),
            'img_url'		=> base_url().config_item('captcha_path'),
            'font_path'		=> './'.config_item('captcha_fonts_path'),
            'font_size'		=> config_item('captcha_font_size'),
            'img_width'		=> config_item('captcha_width'),
            'img_height'	=> config_item('captcha_height'),
            'show_grid'		=> config_item('captcha_grid'),
            'expiration'	=> config_item('captcha_expire'),
        ));

        // Save captcha params in database
        $data = array(
            'captcha_time' => time(),
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );
        $query = $this->db->insert_string('hd_captcha', $data);
        $this->db->query($query);

        return $cap['image'];
    }

    /**
     * Callback function. Check if CAPTCHA test is passed.
     *
     * @param	string
     * @return	bool
     */
    function _check_captcha()
    {
        // First, delete old captchas
        $expiration = time() - config_item('captcha_expire');
        $this->db->query("DELETE FROM hd_captcha WHERE captcha_time < ".$expiration);

        // Then see if a captcha exists:
        $sql = "SELECT COUNT(*) AS count FROM hd_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
        $binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();

        if ($row->count == 0){

            $this->form_validation->set_message('_check_captcha', lang('auth_incorrect_captcha'));
            return FALSE;
        }else{
            return TRUE;
        }
    }

    /**
     * Create reCAPTCHA JS and non-JS HTML to verify user as a human
     *
     * @return	string
     */
    function _create_recaptcha()
    {
        $this->load->helper('recaptcha');

        // Add custom theme so we can get only image
        $options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

        // Get reCAPTCHA JS and non-JS HTML
        $html = recaptcha_get_html(config_item('recaptcha_public_key'));

        return $options.$html;
    }

    /**
     * Callback function. Check if reCAPTCHA test is passed.
     *
     * @return	bool
     */
    function _check_recaptcha()
    {
      // Catch the user's answer
      $captcha_answer = $this->input->post('g-recaptcha-response');
      // Verify user's answer
      $response = $this->recaptcha->verifyResponse($captcha_answer);
      // Processing ...
      if ($response['success']) {
          return TRUE;
      } else {
          // Something goes wrong
          $this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
          return FALSE;
      }
    }

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */