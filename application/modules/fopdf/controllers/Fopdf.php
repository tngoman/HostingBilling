<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Fopdf extends Hosting_Billing {
	function __construct()
	{
		parent::__construct();
		User::logged_in();
		 
		$this->load->helper('invoicer');		
		$this->applib->set_locale();
		
	}

	function invoice($invoice_id = NULL){			
			$data['id'] = $invoice_id;
			$this->load->view('invoice_pdf',isset($data) ? $data : NULL);				
	}
 

	function attach_invoice($invoice){			
			$data['id'] = $invoice['inv_id'];
			$data['attach'] = TRUE;
			$invoice = $this->load->view('invoice_pdf',isset($data) ? $data : NULL,TRUE);	
			return $invoice;			
	}
	 



}

/* End of file fopdf.php */