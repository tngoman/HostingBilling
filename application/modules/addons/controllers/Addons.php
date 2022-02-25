<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addons extends Hosting_Billing 
{

	function __construct()
	{
		parent::__construct(); 
		$this->load->module('layouts');     
		$this->load->library(array('form_validation','template')); 
		$this->load->helper('form');  
	}


	function index()
	{
		$this->list_items();
		$this->can_access();
	}


	function can_access() {
		if(!User::is_admin() && !User::is_staff()) {
			redirect('clients');
		}
	}


	function list_items()
	{
		$this->can_access();
		$this->template->title(lang('addons').' - '.config_item('company_name'));
		$data['page'] = lang('addons');
		$data['datatables'] = TRUE;
		$data['form'] = TRUE;
		$data['addons'] = Addon::all();
		$this->template
		->set_layout('users')
		->build('addons',isset($data) ? $data : NULL);
	}
 

	function add (){
        if ($this->input->post()) {
            Applib::is_demo();

                $_POST['apply_to'] = serialize($this->input->post('apply_to'));             
                if(App::save_data('addons', $this->input->post())){
                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message', lang('server_added'));
                    redirect($_SERVER['HTTP_REFERER']);                
                }
            } 
        else{
            $data['form'] = TRUE; 
            $data['datepicker'] = TRUE;
            $this->load->view('modal/add');
        }     
    }

}

/* End of file addons.php */