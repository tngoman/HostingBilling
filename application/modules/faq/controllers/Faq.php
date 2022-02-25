<?php

 
class Faq extends Hosting_Billing 
{

    public function __construct() {

      parent::__construct();
        User::logged_in();   
            
        $this->load->module('layouts');
        $this->load->model('FAQS');
        $this->load->library(array('template')); 
        $this->template->set_theme(config_item('active_theme'));
        $this->template->set_partial('header', 'sections/header');
        $this->template->set_partial('footer', 'sections/footer');
    }


    function index()
    { 
        $this->template->title(lang('faq').' | '.config_item('company_name'));      
        $this->template->set_metadata('description', config_item('site_description')); 
        $data['page'] = lang('frequently_asked_questions'); 
        $data['categories'] = FAQS::categories(); 
        $data['articles'] = FAQS::articles();
  
        $this->template->set_layout('main')->build('pages/faq', isset($data) ? $data : NULL);
    }


    function category($name)
    {    
      $name = str_replace("_", " ", $name);
      $this->template->title($name);
      $data['page'] = ucfirst(ucfirst($name)); 
      $data['categories'] = FAQS::categories(); 
      $data['articles'] = FAQS::category($name);
      $this->template->set_layout('main')->build('pages/faq', isset($data) ? $data : NULL);
    }
  
   

}