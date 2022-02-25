<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Dashboard extends Hosting_Billing {

    function __construct()
    {
        parent::__construct();
        
        User::logged_in();
   
        if (User::is_client()) {
            redirect('clients');
        }
        if(isset($_GET['setyear'])){ $this->session->set_userdata('chart_year', $_GET['setyear']); }
        if(isset($_GET['chart'])){ $this->session->set_userdata('chart', $_GET['chart']); }

        $lang = config_item('default_language');
        if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
        if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
        $this->lang->load('hd', $lang);
    }

    function index()
    {
    $this->load->module('layouts');
    $this->load->library('template');
    $this->template->title(config_item('company_name'));
    $data['page'] = lang('dashboard');
    $data['activities'] = App::get_activity($limit = 30);

    $data['sums'] = $this->_totals();
    $data['sums2'] = $this->_totals_per_currency();
        if(App::counter('items',array()) == 0){
            $data['no_invoices'] = TRUE;
        }
    $this->template
    ->set_layout('users')
    ->build('user_home',isset($data) ? $data : NULL);
    }
        
        
    function _totals() {
        $paid = $due = array();
        $currency = config_item('default_currency');
        $symbol = array();
        $paid = $due = 0;
        foreach(Invoice::get_invoices() as $inv) {
            $paid_amount = Invoice::get_invoice_paid($inv->inv_id);
            $due_amount = Invoice::get_invoice_due_amount($inv->inv_id);
            if ($inv->currency != $currency) {
                $paid_amount = Applib::convert_currency($inv->currency, $paid_amount);
                $due_amount = Applib::convert_currency($inv->currency, $due_amount);
            }
            $paid += $paid_amount;
            $due += $due_amount;
        }
        return array("paid"=>$paid, "due"=>$due);
    
    }
    
function _totals_per_currency() {
        $paid = $due = array();
        foreach(Invoice::get_invoices() as $inv) {
            $paid_amount = Invoice::get_invoice_paid($inv->inv_id);
            $due_amount = Invoice::get_invoice_due_amount($inv->inv_id);
            if (!isset($paid[$inv->currency])) { $paid[$inv->currency] = 0; }
            if (!isset($due[$inv->currency])) { $due[$inv->currency] = 0; }
            $paid[$inv->currency] += $paid_amount;
            $due[$inv->currency] += $due_amount;
        }
        return array("paid"=>$paid, "due"=>$due);
    
    }

}

/* End of file dashboard.php */