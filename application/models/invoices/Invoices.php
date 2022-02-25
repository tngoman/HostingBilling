<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class Invoices extends CI_Model {

    public $table               = 'hd_invoices';
    public $primary_key         = 'hd_invoices.inv_id';



    public function create($db_array = NULL, $include_invoice_tax_rates = TRUE)
    {
        $invoice_id = parent::save(NULL, $db_array);

        // Create an invoice amount record
        $db_array = array(
            'invoice_id' => $invoice_id
        );

        $this->db->insert('fi_invoice_amounts', $db_array);

        if ($include_invoice_tax_rates)
        {
            // Create the default invoice tax record if applicable
            if ($this->mdl_settings->setting('default_invoice_tax_rate'))
            {
                $db_array = array(
                    'invoice_id'              => $invoice_id,
                    'tax_rate_id'             => $this->mdl_settings->setting('default_invoice_tax_rate'),
                    'include_item_tax'        => $this->mdl_settings->setting('default_include_item_tax'),
                    'invoice_tax_rate_amount' => 0
                );

                $this->db->insert('fi_invoice_tax_rates', $db_array);
            }
        }

        return $invoice_id;
    }

    public function get_url_key()
    {
        $this->load->helper('string');
        return random_string('unique');
    }

    /**
     * Copies invoice items, tax rates, etc from source to target
     * @param int $source_id
     * @param int $target_id
     */
    public function copy_invoice($source_id, $target_id)
    {
        //$this->load->model('invoices/mdl_items');

        $invoice_items = $this->db->where('invoice_id', $source_id)->get('items')->result();

        foreach ($invoice_items as $invoice_item)
        {
            $db_array = array(
                'invoice_id'    => $target_id,
                'item_name'     => $invoice_item->item_name,
                'item_desc'     => $invoice_item->item_desc,
                'unit_cost'     => $invoice_item->unit_cost,
                'quantity'      => $invoice_item->quantity,
                'total_cost'    => $invoice_item->total_cost,
            );
            App::save_data('items',$db_array);
        }
    }

    public function db_array()
    {
        $db_array = parent::db_array();

        // Get the client id for the submitted invoice
        $this->load->model('clients/mdl_clients');
        $db_array['client_id'] = $this->mdl_clients->client_lookup($db_array['client_name']);
        unset($db_array['client_name']);

        $db_array['invoice_date_created'] = date_to_mysql($db_array['invoice_date_created']);
        $db_array['invoice_date_due']     = $this->get_date_due($db_array['invoice_date_created']);
        $db_array['invoice_number']       = $this->get_invoice_number($db_array['invoice_group_id']);
        $db_array['invoice_terms']        = $this->mdl_settings->setting('default_invoice_terms');

        if (!isset($db_array['invoice_status_id']))
        {
            $db_array['invoice_status_id'] = 1;
        }

        // Generate the unique url key
        $db_array['invoice_url_key'] = $this->get_url_key();

        return $db_array;
    }

    public function get_invoice_number($invoice_group_id)
    {
        $this->load->model('invoice_groups/mdl_invoice_groups');
        return $this->mdl_invoice_groups->generate_invoice_number($invoice_group_id);
    }

    public function get_date_due($invoice_date_created)
    {
        $invoice_date_due = new DateTime($invoice_date_created);
        $invoice_date_due->add(new DateInterval('P' . config_item('invoices_due_after') . 'D'));
        return $invoice_date_due->format('Y-m-d');
    }

    public function delete($invoice_id)
    {
        parent::delete($invoice_id);

        $this->load->helper('orphan');
        delete_orphans();
    }



}

?>