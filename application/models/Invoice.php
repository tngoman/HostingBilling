<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Invoice extends CI_Model
{
    private static $db;

    public function __construct()
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    public static function view_by_id($invoice)
    {
        return self::$db->where('inv_id', $invoice)->get('invoices')->row();
    }

    public static function view_items($invoice)
    {
        return self::$db->where('invoice_id', $invoice)->get('items')->result();
    }

    public static function view_by_ref($reference)
    {
        return self::$db->where('reference_no', $reference)->get('invoices')->row();
    }


    public static function get_invoices($limit = null, $status = null, $cancelled = false)
    {
        if ($status != null) {
            self::$db->where('status', $status);
        }
        if($cancelled) :
            return self::$db->where(array('inv_id >' => 0, 'status !=' => 'Cancelled'))->get('invoices')->result();
        else:
        return self::$db->order_by('inv_id', 'desc')->where(array('inv_id >' => 0, 'inv_deleted' => 'No'))->get('invoices', $limit)->result();
        endif;
    }

    public static function saved_items()
    {
        return self::$db->where(array('unit_cost >' => 0, 'deleted' => 'No'))->get('items_saved')->result();
    }

    // Update Invoice
    public static function update($invoice, $data)
    {
        return self::$db->where('inv_id', $invoice)->update('invoices', $data);
    }

    // Save Invoice
    public static function save($data)
    {
        self::$db->insert('invoices', $data);

        return self::$db->insert_id();
    }

    public static function save_items($data)
    {
        return self::$db->insert('items', $data);
    }

    // Save tax rates
    public static function save_tax($data)
    {
        return self::$db->insert('tax_rates', $data);
    }

    // Get tax rate using ID
    public static function tax_by_id($id)
    {
        return self::$db->where('tax_rate_id', $id)->get('tax_rates')->row();
    }
    // Update tax rate
    public static function update_tax($id, $data)
    {
        return self::$db->where('tax_rate_id', $id)->update('tax_rates', $data);
    }

    // Delete tax rate from DB
    public static function delete_tax($id)
    {
        return self::$db->where('tax_rate_id', $id)->delete('tax_rates');
    }

    public static function view_item($id)
    {
        return self::$db->where('item_id', $id)->get('items')->row();
    }

    public static function get_invoice_due_amount($invoice)
    {
        $tax = self::get_invoice_tax($invoice, null, true);
        $discount = self::get_invoice_discount($invoice);
        $invoice_cost = self::get_invoice_subtotal($invoice);
        $payment_made = self::get_invoice_paid($invoice);
        $fee = self::get_invoice_fee($invoice);
        $due_amount = (($invoice_cost - $discount) + $tax + $fee) - $payment_made;
        if ($due_amount <= 0) {
            $due_amount = 0;
        }

        return round($due_amount, 2);
    }

    public static function payable($id)
    {
        return (self::get_invoice_subtotal($id) + self::get_invoice_tax($id, null, true) + self::get_invoice_fee($id)) - self::get_invoice_discount($id);
    }

    // Calculate Invoice Tax
    public static function get_invoice_tax($invoice, $type = 'tax1', $sum_tax = false)
    {
        if ($sum_tax) {
            return self::total_tax($invoice);
        }
        $tax = ($type == 'tax2') ? self::view_by_id($invoice)->tax2 : self::view_by_id($invoice)->tax;
        if ($type == 'tax2') {
            return ($tax / 100) * self::get_invoice_subtotal($invoice);
        }

        return ($tax / 100) * self::get_invoice_subtotal($invoice);
    }

    public static function total_tax($invoice = null)
    {
        if($invoice) {
            $tax1 = self::view_by_id($invoice)->tax;
            $tax2 = self::view_by_id($invoice)->tax2;

            return ($tax1 / 100) * self::get_invoice_subtotal($invoice) + ($tax2 / 100) * self::get_invoice_subtotal($invoice);
        }
        else {
            return 0;
        }
    }

    public static function get_invoice_discount($invoice = null)
    {
        if($invoice) {
         return (self::view_by_id($invoice)->discount / 100) * self::get_invoice_subtotal($invoice);
        }
        else 
        {
            return 0;
        }
    }

    public static function get_invoice_fee($invoice = null)
    {
        if($invoice) {
            return (self::view_by_id($invoice)->extra_fee / 100) * self::get_invoice_subtotal($invoice);
        }
        else 
        {
            return 0;
        }
    }

    public static function get_invoice_subtotal($invoice = null)
    {
        if($invoice) {
            return self::$db->select_sum('total_cost')->where('invoice_id', $invoice)->get('items')->row()->total_cost;
        }
        else 
        {
            return 0;
        }
    }

    public static function get_invoice_paid($invoice)
    {
        return self::$db->select_sum('amount')->where(array('invoice' => $invoice, 'refunded' => 'No'))->get('payments')->row()->amount;
    }

    public static function get_payment_process($id, $gateway)
    {
        return self::$db->select('*')->where(array('trans_id' => $id, 'gateway' => $gateway))->get('payment_process')->row();
    }

    public static function all_invoice_amount()
    {
        $invoices = self::get_invoices(null, null, true);
        $cost[] = array();
        foreach ($invoices as $key => $invoice) {
            $tax = self::get_invoice_tax($invoice->inv_id, null, true);
            $discount = self::get_invoice_discount($invoice->inv_id);
            $invoice_cost = self::get_invoice_subtotal($invoice->inv_id);

            $tempcost = ($invoice_cost + $tax) - $discount;
            if ($invoice->currency != config_item('default_currency')) {
                $tempcost = Applib::convert_currency($invoice->currency, $tempcost);
            }
            $cost[] = $tempcost;
        }
        if (is_array($cost)) {
            return round(array_sum($cost), config_item('currency_decimals'));
        } else {
            return 0;
        }
    }

    public static function outstanding()
    {
        $total = 0;
        $invoices = self::get_invoices(null, null, true);
        foreach ($invoices as $key => $inv) {
            if (config_item('default_currency') != config_item('default_currency')) {
                $total += Applib::convert_currency(config_item('default_currency'), self::get_invoice_due_amount($inv->inv_id));
            } else {
                $total += self::get_invoice_due_amount($inv->inv_id);
            }
        }

        return $total;
    }

    public static function overdue()
    {
        $total = 0;
        $date_today = date('Y-m-d');
        $sql = "SELECT * FROM hd_invoices WHERE DATE(due_date) <= '$date_today' AND status = 'Unpaid'";
        $invoices = self::$db->query($sql)->result();
        foreach ($invoices as $key => $inv) {
            if (config_item('default_currency') != config_item('default_currency')) {
                $total += Applib::convert_currency(config_item('default_currency'), self::get_invoice_due_amount($inv->inv_id));
            } else {
                $total += self::get_invoice_due_amount($inv->inv_id);
            }
        }

        return $total;
    }

    // Get tax rates
    public static function get_tax_rates()
    {
        return self::$db->get('tax_rates')->result();
    }

    // Get payment methods
    public static function payment_methods()
    {
        return self::$db->get('payment_methods')->result();
    }

    // List items ordered
    public static function has_items($id, $type = 'invoice')
    {
        $table = 'items';

        return self::$db->where($type.'_id', $id)->order_by('item_order', 'asc')->get($table)->result();
    }


    static function get_renewal_date($date, $days)
    {
        $renewal_date = new DateTime($date);
        $renewal_date->add(new DateInterval('P' . $days . 'D'));
        return $renewal_date->format('Y-m-d');
    }


    // Get Invoice Status
    public static function payment_status($invoice = null)
    {
        if($invoice) {
            $invoice_status = self::view_by_id($invoice)->status;
        }
        else {
            $invoice_status = lang('unknown');
        }
        
        $tax = self::get_invoice_tax($invoice, null, true);
        $discount = self::get_invoice_discount($invoice);
        $invoice_cost = self::get_invoice_subtotal($invoice);
        $payment_made = round(self::get_invoice_paid($invoice), 2);
        $due = round(((($invoice_cost - $discount) + $tax) - $payment_made));

        if ($invoice_status == 'Cancelled') {
            return 'cancelled'; // Cancelled
        }elseif ($invoice_status != 'Paid') {
            return 'not_paid'; // Not paid
        } elseif ($due <= 0) {
            return 'fully_paid'; // Fully paid
        } else {
            return 'partially_paid'; // Partially Paid
        }
    }

    // Get Invoice Activities
    public static function activities($invoice = null)
    {
        return self::$db->where(array('module_field_id' => $invoice, 'module' => 'invoices'))
            ->order_by('activity_date', 'desc')->get('activities')->result();
    }

    // Get Invoices by CLIENT ID
    public static function get_client_invoices($company)
    {
        return self::$db->where(array('client' => $company,'status !=' => 'Cancelled','show_client' => 'Yes'))->order_by('inv_id', 'desc')->get('invoices')->result();
    }
    // Get list of paid invoices
    public static function paid_invoices($company = null)
    {
        if ($company != null) {
            self::$db->where(array('client' => $company, 'show_client' => 'Yes'));
        }

        return self::$db->where(array('status' => 'Paid', 'inv_id >' => 0))->get('invoices')->result();
    }
    // Get list of unpaid invoices
    public static function unpaid_invoices($company = null)
    {
        $invoices = ($company != null) ? self::get_client_invoices($company) : self::get_invoices(null, null, true);
        foreach ($invoices as $key => &$inv) {
            if (self::payment_status($inv->inv_id) != 'not_paid') {
                unset($invoices[$key]);
            }
        }

        return $invoices;
    }

    // Generate new Invoice Number

    public static function generate_invoice_number()
    {
        $dbPrefix = self::$db->dbprefix;
        $query = self::$db->query('SELECT reference_no, inv_id FROM '.$dbPrefix.'invoices WHERE inv_id = (SELECT MAX(inv_id) FROM '.$dbPrefix.'invoices)');

        // $query = self::$db->select('reference_no')->select_max('inv_id')->get('invoices');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $ref_number = intval(substr($row->reference_no, -4));
            $next_number = $ref_number + 1;
            if ($next_number < config_item('invoice_start_no')) {
                $next_number = config_item('invoice_start_no');
            }
            $next_number = self::ref_exists($next_number);

            return sprintf('%04d', $next_number);
        } else {
            return sprintf('%04d', config_item('invoice_start_no'));
        }
    }

    // Verify if REF Exists

    public static function ref_exists($next_number)
    {
        $next_number = sprintf('%04d', $next_number);

        $records = self::$db->where('reference_no', config_item('invoice_prefix').$next_number)
            ->get('invoices')->num_rows();
        if ($records > 0) {
            return self::ref_exists($next_number + 1);
        } else {
            return $next_number;
        }
    }

    public static function by_range($start, $end, $report_by = null)
    {
        $report_by = ($report_by == 'InvoiceDueDate') ? 'due_date' : 'date_saved';
        $sql = "SELECT * FROM hd_invoices WHERE $report_by BETWEEN '$start' AND '$end'";

        return self::$db->query($sql)->result();
    }

    // Get a list of partially paid invoices
    public static function partially_paid_invoices($company = null)
    {
        $invoices = ($company != null) ? self::get_client_invoices($company) : self::get_invoices(null, null, true);
        foreach ($invoices as $key => &$inv) {
            if (self::payment_status($inv->inv_id) != 'partially_paid') {
                unset($invoices[$key]);
            }
        }

        return $invoices;
    }

    // Get a list of recurring invoices
    public static function recurring_invoices($company = null)
    {
        if ($company != null) {
            self::$db->where(array('client' => $company, 'show_client' => 'Yes'));
        }

        return self::$db->where(array('recurring' => 'Yes', 'inv_id >' => 0))->get('invoices')->result();
    }

    public static function evaluate_invoice($id)
    {
        $has_balance = self::get_invoice_due_amount($id);
        $has_items = self::$db->where('invoice_id', $id)->get('items')->num_rows();
        $is_cancelled = (self::view_by_id($id)->status == 'Cancelled') ? true : false;
        if(!$is_cancelled) :
            if ($has_items == 0 || $has_balance > 0) {
                self::$db->set('status', 'Unpaid')->where('inv_id', $id)->update('invoices');
            } else {
                self::$db->set('status', 'Paid')->where('inv_id', $id)->update('invoices');
            }
        endif;

        return;
    }

    // Delete Invoice
    public static function delete($invoice)
    {
        //delete invoice items
        self::$db->where(array('invoice_id' => $invoice))->delete('items');
        //delete invoice payments
        self::$db->where(array('invoice' => $invoice))->delete('payments');
        //clear invoice activities
        self::$db->where(array('module' => 'invoices', 'module_field_id' => $invoice))->delete('activities');
        //delete invoice
        self::$db->where(array('inv_id' => $invoice))->delete('invoices');
    }

    public static function recur($invoice, $data)
    {
        $recur_days = App::num_days($data['r_freq']);
        $due_date = self::view_by_id($invoice)->due_date;
        $next_date = date('Y-m-d', strtotime($due_date.'+ '.$recur_days.' days'));
        if ($data['recur_end_date'] == '') {
            $recur_end_date = '0000-00-00';
        } else {
            $recur_end_date = date_format(date_create_from_format(config_item('date_php_format'), $data['recur_end_date']), 'Y-m-d');
        }
        $data = array(
            'recurring' => 'Yes',
            'r_freq' => $recur_days,
            'recur_frequency' => $data['r_freq'],
            'recur_start_date' => date_format(date_create_from_format(config_item('date_php_format'), $data['recur_start_date']), 'Y-m-d'),
            'recur_end_date' => $recur_end_date,
            'recur_next_date' => $next_date,
        );
        self::update($invoice, $data);
        // Log recur activity
        $activity = array(
            'user' => User::get_id(),
            'module' => 'invoices',
            'module_field_id' => $invoice,
            'activity' => 'activity_invoice_made_recur',
            'icon' => 'fa-tweet',
            'value1' => self::view_by_id($invoice)->reference_no,
            'value2' => $next_date,
        );
        App::Log($activity);

        return true;
    }


    static function credit_client($id)
	{
        $payments = Payment::by_invoice($id);
        $amount = 0;

        foreach($payments as $payment)
        {
            $amount += $payment->amount;
        }

        $client = Client::view_by_id(self::view_by_id($id)->client);
        $credit = $client->transaction_value;
        $bal = $credit + $amount;
        
        $balance = array(
            'transaction_value' => Applib::format_deci($bal)
        );
        
        self::$db->where('co_id', $client->co_id)->update('companies', $balance); 
    }




    static function credit_item($id)
	{
        $item = Order::get_order($id);  
       if(self::payment_status($item->invoice_id) == 'fully_paid')
       {                          
            $client = Client::view_by_id($item->client_id);
            $credit = $client->transaction_value;
            $bal = $credit + $item->fee; 
            
            $balance = array(
                'transaction_value' => Applib::format_deci($bal)
            );
            
            self::$db->where('co_id', $client->co_id)->update('companies', $balance); 

       }

    }
    


}

/* End of file model.php */
