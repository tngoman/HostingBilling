<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class Invoices_Recurring extends CI_Model {

    public $table                 = 'hd_invoices';
    public $primary_key           = 'hd_invoices.inv_id';
    public $recur_frequencies = array(
        '7D' => 'calendar_week',
        '1M' => 'calendar_month',
        '1Y' => 'year',
        '3M' => 'quarter',
        '6M' => 'six_months'
    );
    

    public function validation_rules()
    {
        return array(
            'invoice_id'           => array(
                'field' => 'invoice_id',
                'rules' => 'required'
            ),
            'recur_start_date'     => array(
                'field' => 'recur_start_date',
                'label' => lang('start_date'),
                'rules' => 'required'
            ),
            'recur_end_date'       => array(
                'field' => 'recur_end_date',
                'label' => lang('end_date')
            ),
            'recur_frequency'      => array(
                'field' => 'recur_frequency',
                'label' => lang('every'),
                'rules' => 'required'
            ),
        );
    }

    public function db_array()
    {
        $db_array = parent::db_array();

        $db_array['recur_start_date'] = date_to_mysql($db_array['recur_start_date']);
        $db_array['recur_next_date'] = $db_array['recur_start_date'];

        if ($db_array['recur_end_date'])
        {
            $db_array['recur_end_date'] = date_to_mysql($db_array['recur_end_date']);
        }
        else
        {
            $db_array['recur_end_date'] = '0000-00-00';
        }
        
        return $db_array;
    }
    
    public function stop($invoice_recurring_id)
    {
        $db_array = array(
            'recurring'     => 'No',
            'recur_end_date' => date('Y-m-d'),
            'recur_next_date' => '0000-00-00'
        );
        Invoice::update($invoice_recurring_id,$db_array);
        return TRUE;
    }
    
    /**
     * Sets filter to only recurring invoices which should be generated now
     * @return \Mdl_Invoices_Recurring
     */
    public function active()
    {

        $query = $this->db->query("SELECT * FROM hd_invoices  WHERE recur_next_date <= date(NOW()) AND (recur_end_date > date(NOW()) OR recur_end_date = '0000-00-00') AND recur_start_date <= date(NOW())") -> result();
        return $query;
    }

    function get_invoice($source_id,$invoice_table){
        return $this->db->where('inv_id',$source_id)->get($invoice_table)->row();
    }
    
    public function set_next_recur_date($invoice_id)
    {
        $invoice_recurring = Invoice::view_by_id($invoice_id);
        
        $recur_next_date = $this->_increment_date($invoice_recurring->recur_next_date, $invoice_recurring->recur_frequency);
        
        $db_array = array(
            'recur_next_date' => $recur_next_date
        );
        Invoice::update($invoice_id,$db_array);
    }


    /**
     * Adds interval to yyyy-mm-dd date and returns in same format
     * @param $date
     * @param $increment
     * @return date
     */
    function _increment_date($date, $increment)
    {
        $new_date = new DateTime($date);
        $new_date->add(new DateInterval('P' . $increment));
        return $new_date->format('Y-m-d');
    }

}

?>