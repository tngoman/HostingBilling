<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Reports extends Hosting_Billing 
{
	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');
		$this->load->library(array('template','form_validation'));
		$this->template->title(lang('reports').' - '.config_item('company_name'));
		if(!User::is_admin() && !User::is_staff()) {redirect('clients');}
		if(isset($_GET['setyear'])){ $this->session->set_userdata('chart_year', $_GET['setyear']); }
	}

	function index()
	{
		$data = array(
			'page' => lang('reports'),
		);
		$this->template
		->set_layout('users')
		->build('dashboard',isset($data) ? $data : NULL);
	}


	function view($report_view = NULL){
			switch ($report_view) {
				case 'invoicesreport':
					$this->_invoicesreport();
					break;
				case 'invoicesbyclient':
					$this->_invoicesbyclient();
					break;
				case 'paymentsreport':
					$this->_paymentsreport();
					break;
 
				case 'ticketsreport':
					$this->_ticketsreport();
					break;
				
				default:
					# code...
					break;
			}
	}

	function _invoicesreport(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->input->post()){
			$range = explode('-', $this->input->post('range'));
			$start_date = date('Y-m-d', strtotime($range[0]));
			$end_date = date('Y-m-d', strtotime($range[1]));
			$data['report_by'] = $this->input->post('report_by');
			$data['invoices'] = Invoice::by_range($start_date,$end_date,$data['report_by']);
			$data['range'] = array($start_date,$end_date);
		}else{
			$data['invoices'] = Invoice::by_range(date('Y-m').'-01',date('Y-m-d'));
			$data['range'] = array(date('Y-m').'-01',date('Y-m-d'));
		}
		$this->template
			->set_layout('users')
			->build('report/invoicesreport',isset($data) ? $data : NULL);
	}

	function _invoicesbyclient(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->input->post()){
			$client = $this->input->post('client');
			$data['invoices'] = Invoice::get_client_invoices($client);
			$data['client'] = $client;
		}else{
			$data['invoices'] = array();
			$data['client'] = NULL;
		}
		$this->template
			->set_layout('users')
			->build('report/invoicesbyclient',isset($data) ? $data : NULL);
	}

	function _paymentsreport(){
		$data = array('page' => lang('reports'),'form' => TRUE);
		if($this->input->post()){
			$range = explode('-', $this->input->post('range'));
			$start_date = date('Y-m-d', strtotime($range[0]));
			$end_date = date('Y-m-d', strtotime($range[1]));
			$data['payments'] = Payment::by_range($start_date,$end_date);
			$data['range'] = array($start_date,$end_date);
		}else{
			$data['payments'] = Payment::by_range(date('Y-m').'-01',date('Y-m-d'));
			$data['range'] = array(date('Y-m').'-01',date('Y-m-d'));
		}
		$this->template
			->set_layout('users')
			->build('report/paymentsreport',isset($data) ? $data : NULL);
	}
 
	function invoicespdf(){
		if($this->uri->segment(4)){

		$start_date = date('Y-m-d',$this->uri->segment(3));
		$end_date = date('Y-m-d',$this->uri->segment(4));
		$data['report_by'] = $this->uri->segment(5);
		$data['invoices'] = Invoice::by_range($start_date,$end_date,$data['report_by']);
		$data['range'] = array($start_date,$end_date);
		$data['page'] = lang('reports');
		$html = $this->load->view('pdf/invoices',$data,true);
		$file_name = lang('reports')."_".$start_date.'To'.$end_date.'.pdf';
	}else{
		$data['client'] = $this->uri->segment(3);
		$data['invoices'] = Invoice::get_client_invoices($data['client']);
		$data['page'] = lang('reports');
		$html = $this->load->view('pdf/clientinvoices',$data,true);
		$file_name = lang('reports')."_".Client::view_by_id($data['client'])->company_name.'.pdf';
	}

		

		$pdf = array(
			"html"      => $html,
			"title"     => lang('invoices_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

	function paymentspdf(){
		$start_date = date('Y-m-d',$this->uri->segment(3));
		$end_date = date('Y-m-d',$this->uri->segment(4));
		$data['payments'] = Payment::by_range($start_date,$end_date);
		$data['range'] = array($start_date,$end_date);
		$data['page'] = lang('reports');
		$html = $this->load->view('pdf/payments',$data,true);
		$file_name = lang('payments')."_".$start_date.'To'.$end_date.'.pdf';
		
		$pdf = array(
			"html"      => $html,
			"title"     => lang('payments_report'),
			"author"    => config_item('company_name'),
			"creator"   => config_item('company_name'),
			"badge"     => 'FALSE',
			"filename"  => $file_name
		);
		$this->applib->create_pdf($pdf);
	}

 


	function _filter_by(){

		$filter = isset($_GET['view']) ? $_GET['view'] : '';

		return $filter;
	}



}

/* End of file invoices.php */
