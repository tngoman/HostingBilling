<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



class Report extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	static function recent_paid()
	{
		return self::$db->order_by('created_date', 'desc')->where('inv_deleted','No')->get('payments',5)->result();
	}


	static function month_amount($year,$month){
        $total = 0;
        $query = "SELECT * FROM hd_payments WHERE MONTH(payment_date) = '$month' AND refunded = 'No' AND YEAR(payment_date) = '$year'";
        $payments = self::$db->query($query)->result();
        foreach($payments as $p) {
            $amount = $p->amount;
            if ($p->currency != config_item('default_currency')) {
                $amount = Applib::convert_currency($p->currency, $amount);
            }
            $total += $amount;
        }
        return round($total, config_item('currency_decimals'));
    }

    static function year_amount($year){
        $total = 0;
        $query = "SELECT * FROM hd_payments WHERE refunded = 'No' AND YEAR(payment_date) = '$year'";
        $payments = self::$db->query($query)->result();
        foreach($payments as $p) {
            $amount = $p->amount;
            if ($p->currency != config_item('default_currency')) {
                $amount = Applib::convert_currency($p->currency, $amount);
            }
            $total += $amount;
        }
        return round($total, config_item('currency_decimals'));
    }


    static function invoiced($year,$month){
    	$query = "SELECT * FROM hd_invoices WHERE MONTH(date_saved) = '$month' AND YEAR(date_saved) = '$year'";
        $invoices = self::$db->query($query)->result();
        $total = 0;
        foreach ($invoices as $key => $i) {
        	if ($i->currency != config_item('default_currency')) {
                $total += Applib::convert_currency($i->currency, Invoice::payable($i->inv_id));
            }else{
            	$total += Invoice::payable($i->inv_id);
            }

        }
        return round($total, config_item('currency_decimals'));
    }

    static function total_paid(){
        $total = 0;
        $query = "SELECT * FROM hd_payments WHERE refunded = 'No'";
        $payments = self::$db->query($query)->result();
        foreach($payments as $p) {
            $amount = $p->amount;
            if ($p->currency != config_item('default_currency')) {
                $amount = Applib::convert_currency($p->currency, $amount);
            }
            $total += $amount;
        }
        return round($total, config_item('currency_decimals'));
    }

    static function num_payments(){
        $query = "SELECT * FROM hd_payments WHERE refunded = 'No'";
        return self::$db->query($query)->num_rows();
    }

    static function paid_avg(){
    	self::$db->select_avg('amount');
        return self::$db->where('refunded','No')->get('payments')->row()->amount;
    }

    static function top_clients($limit = NULL){
    	self::$db->order_by('payment_date','desc');
    	self::$db->group_by("paid_by");
    	self::$db->join('companies','companies.co_id = payments.paid_by');
        return self::$db->where('refunded','No')->get('payments',$limit)->result();
    }

    static function outstanding($limit = NULL){
        $invoices = self::$db->where(array('archived'=>'0','status !=' => 'Cancelled'))->get('invoices',$limit)->result();
        foreach ($invoices as $key => &$i) {
        	if(Invoice::payment_status($i->inv_id) == 'fully_paid'){
                unset($invoices[$key]);
            }
        }
        return $invoices;
    }

 

    static function invoice_items()
	{
		return self::$db->order_by('item_id','desc')->get('items')->result();
	}


}

/* End of file Report.php */
