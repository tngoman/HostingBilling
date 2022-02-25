<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Promotions extends Hosting_Billing 
{
	function __construct()
	{		
		parent::__construct();	
		User::logged_in();    
        $this->load->module('layouts');      
        $this->load->library('template');     
    }
    
    

	function index($id = null)
	{		
        
        $promotions = $this->db->get('promotions')->result();        
        $this->template->title(lang('promotions'));
        $data['promotions'] = $promotions;
        $data['page'] = lang('promotions');        
        $data['form'] = TRUE; 
        $data['datepicker'] = TRUE;
        $data['datatables'] = TRUE;  
        $this->template
        ->set_layout('users')
        ->build('index',isset($data) ? $data : NULL);       
    }


    
    function add_promotion (){
        if ($this->input->post()) {
            Applib::is_demo();

                $_POST['apply_to'] = serialize($this->input->post('apply_to'));
                $_POST['required'] = serialize($this->input->post('required'));
                $_POST['billing_cycle'] =  serialize($this->input->post('billing_cycle'));             
                if(App::save_data('promotions', $this->input->post())){
                    $this->session->set_flashdata('response_status', 'success');
                    $this->session->set_flashdata('message', lang('server_added'));
                    redirect($_SERVER['HTTP_REFERER']);                
                }
            } 
        else{
            $data['form'] = TRUE; 
            $data['datepicker'] = TRUE;
            $this->load->view('modal/add_promotion');
        }     
    }



    function edit($id = null){

        if ($this->input->post()) {
            Applib::is_demo();

            $_POST['apply_to'] = serialize($this->input->post('apply_to'));
            $_POST['required'] = serialize($this->input->post('required'));
            $_POST['billing_cycle'] =  serialize($this->input->post('billing_cycle'));  
                
            $this->db->where('id', $this->input->post('id'));  
            if($this->db->update('promotions', $_POST)) {
                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('promotion_edited'));
                redirect($_SERVER['HTTP_REFERER']);  
            }           
        } 
        else{
            $data['form'] = TRUE; 
            $data['datepicker'] = TRUE;
            $data['promo'] = $this->db->where(array('id'=> $id))->get('promotions')->row();
            $this->load->view('modal/edit_promotion', $data);
        }
    }



    function delete ($id = NULL) 
	{	 
		if ($this->input->post() ){
			Applib::is_demo(); 
			App::delete('promotions',array('id' => $this->input->post('id', TRUE))); 
			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('promotion_deleted_successfully'));
			redirect($_SERVER['HTTP_REFERER']);  
		}
		else {
			$data['id'] = $id; 
			$this->load->view('modal/delete_promotion',$data);
		}		
	}

 


}

/* End of file Servers.php */