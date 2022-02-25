<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends Hosting_Billing {
 
    function __construct()
    {
        parent::__construct();   
        $this->load->module('layouts');
        $this->load->library('template');

        $lang = config_item('default_language');
        if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
        if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
        $this->lang->load('hd', $lang);
        
        if (!$this->session->userdata('cart')) {
            $this->session->set_userdata('cart', array());
        }

        if (!$this->session->userdata('codes')) {
            $this->session->set_userdata('codes', array());
        }

        if (!$this->session->userdata('new_cust')) {
            $this->session->set_userdata('new_cust', array());
        }

        if (!$this->session->userdata('discounted')) {
            $this->session->set_userdata('discounted', array());
        }
    }
    
    
    function layout($data, $view)
    {
        $data['page'] = lang($view);

        if(!User::is_logged_in()) 
        {   $this->template->set_theme(config_item('active_theme'));
            $this->template->set_partial('header', 'sections/header');
            $this->template->set_partial('footer', 'sections/footer');
            $this->template->set_layout('main')->build('cart/' . $view, isset($data) ? $data : NULL);
        }
        else
        {   
            $this->template->set_layout('users')->build(config_item('active_theme').'/views/cart/' . $view, isset($data) ? $data : NULL);
        }
    }

  

    function index()
    {        
        $data = array();
        $this->template->title(lang('shopping_cart')); 
        $this->layout($data, 'shopping_cart');       
    }



    function options()
    {  
       
        $this->template->title(lang('add_to_cart'));            
        $cart = $this->session->userdata('cart'); 
        
        if ($this->input->post()) {                  
            
            $selected_options = $this->input->post('selected');
            foreach ($selected_options as $selected)
            {
                $selected = explode(',', $selected);  
                $item = Item::view_item($selected[0]); 
                
                $tax = Applib::format_deci((null != $item->item_tax_rate && $item->item_tax_rate > 0) ? ($selected[3]*$item->item_tax_rate)/100 : '0.00');
                if($item->require_domain == 'Yes') {           
                    if(isset($cart[count($cart) - 1]->nameservers) && !isset($cart[count($cart) - 1]->domain_only)){                    
                        $cart[] = (object) array('cart_id' => $cart[count($cart) - 1]->cart_id, 
                        'item' => $selected[0], 
                        'name' => $selected[1], 
                        'renewal' => $selected[2], 
                        'price' => $selected[3],
                        'tax' => $tax, 
                        'authcode' => '', 
                        'domain' => $cart[count($cart) - 1]->domain);
                        $this->session->set_userdata('cart', $cart);
                        
                        $domain = $cart[count($cart) - 2];
                        $tld = explode('.', $domain->domain, 2);
                        $ext = $tld[1]; 
    
                        if($this->additional_fields($ext) || $domain->name == lang('domain_transfer'))
                        {     
                             if($domain->name == lang('domain_transfer')) {
                                $this->session->set_userdata('transfer', true);
                             } 
    
                            redirect('cart/domain_fields');
                        }
    
                        else {
                            redirect('cart');
                        }                    
                    }
    
                    else {                                        
                        $this->session->set_userdata('hold', array(
                        'cart_id' => time(), 
                        'item' => $selected[0], 
                        'name' => $selected[1], 
                        'renewal' => $selected[2], 
                        'price' => $selected[3],       
                        'tax' => $tax,         
                        'domain' => '')); 
                        
                        if($item->setup_fee > 0) { 
                            $this->session->set_userdata('setup', array(
                            'cart_id' => time(), 
                            'item' => '', 
                            'name' => $selected[1]. ' ' .lang('setup_fee'), 
                            'renewal' => 'one_time_payment', 
                            'tax' => $tax, 
                            'price' => $item->setup_fee, 
                            'domain' => '-')); 
                        }
                        
                        redirect('cart/domain');                
                    } 
                }
                else 
                {    
                    $time = time();        
                    $cart_item = (object) array(                    
                    'cart_id' => $time, 
                    'item' => $selected[0], 
                    'name' => $selected[1], 
                    'renewal' => $selected[2], 
                    'price' => $selected[3], 
                    'tax' => $tax, 
                    'domain' => $item->item_desc);

                    if ($this->session->userdata('parent')) 
                    {
                        $cart_item->parent = $this->session->userdata('parent');
                    }

                    $cart[] = $cart_item;
    
                    if($item->setup_fee > 0) { 
                        $tax = Applib::format_deci((null != $item->item_tax_rate && $item->item_tax_rate > 0) ? ($item->setup_fee*$item->item_tax_rate)/100 : '0.00');
                        $cart[] = (object) array(
                        'cart_id' => time(), 
                        'item' => '', 
                        'name' => $selected[1]. ' ' .lang('setup_fee'), 
                        'renewal' => 'one_time_payment', 
                        'tax' => $tax, 
                        'price' => $item->setup_fee, 
                        'domain' => '-');
                    }
    
                    $this->session->set_userdata('cart', $cart); 
                    $this->show_addons($selected[0], $time);
                }
            }
        
        }
        
        else {
            $item = '';
            if(isset($_GET['item'])) { 
                $item = Item::view_item($_GET['item']);

                if(!isset($item))
                {
                    redirect('home');
                }
            }            
            $data['package'][] = $item;
            $this->layout($data, 'options'); 
        }       
    }



    function domain()
    { 
        $this->template->title(lang('domain_registration'));
        $data['domains'] = Item::get_domains();  
        $this->layout($data,  'domain'); 
    }



    function hosting_packages()
    { 
        if(User::is_logged_in()) {redirect('orders/add_order');}
        $this->template->title(lang('hosting_packages'));
        $data = array();
        $this->layout($data, 'hosting_packages'); 
    }



    function add_nameservers()
    { 
        $nameservers = $this->input->post('nameserver_1', true).",".$this->input->post('nameserver_2', true);
        if($this->input->post('nameserver_3', true) != '') : $nameservers .= ",".$this->input->post('nameserver_3', true); endif;
        if($this->input->post('nameserver_4', true) != '') : $nameservers .= ",".$this->input->post('nameserver_4', true); endif;
        $cart = $this->session->userdata('cart'); 
        $cart[count($cart) - 1]->nameservers = $nameservers;
        $this->session->set_userdata('cart', $cart); 

        $item = $cart[count($cart) - 1]; 
        $tld = explode('.', $item->domain, 2);
        $ext = $tld[1]; 
        
        if($this->additional_fields($ext) || $item->name == lang('domain_transfer'))
        {    
            if($item->name == lang('domain_transfer')) {
                $this->session->set_userdata('transfer', true);
            }
            redirect('cart/domain_fields');
        }

        else {
            redirect('cart'); 
        }       
    }



    function default_nameservers()
    { 
        $cart = $this->session->userdata('cart'); 
        $item = $cart[count($cart) - 1];
        $tld = explode('.', $item->domain, 2);
        $ext = $tld[1]; 

        if($this->additional_fields($ext) || $item->name == lang('domain_transfer'))
        {    
            if($item->name == lang('domain_transfer')) {
                $this->session->set_userdata('transfer', true);
            } 

            redirect('cart/domain_fields');
        }

        else {
            $this->show_addons($item->item, $item->cart_id);
        }     
    }



    function show_addons($item, $parent)
    {
        $addons = Addon::all();
        $addon_list = array();
        foreach($addons as $addon)
        {
            if(in_array($item, unserialize($addon->apply_to)))
            {
                $addon_list[] = $addon;
            }
        }
        
        if(empty($addon_list))
        {
            redirect('cart'); 
        }
        else
        {
            $this->session->set_userdata('parent',  $parent);
            $data['id'] = $item; 
            $data['addons'] = $addon_list;            
            $this->layout($data, 'addons'); 
        }        
    }



    function domain_only()
    { 
        $cart = $this->session->userdata('cart'); 
        if(isset($cart[count($cart) - 1]->nameservers)){
             $cart[count($cart) - 1]->domain_only = true;
        }
        $this->session->set_userdata('cart', $cart); 
        redirect('cart/nameservers');
    }



    function nameservers()
    {   $data = array();   
        $this->template->title(lang('name_servers'));
        $this->layout($data, 'nameservers'); 
    }



    function add_existing()
    { 
        $data = array();        
        $data['page'] = lang('existing_domain');
        $this->template->title(lang('existing_domain')); 
        $this->layout($data, 'existing_domain'); 
    }



    function existing_domain()
    {
        $cart = $this->session->userdata('cart');
        if ($this->input->post()) {
            if($this->session->userdata('hold')) {
                $held = $this->session->userdata('hold');
                $held['domain'] = $this->input->post('domain', true);
                $cart[] = (object) $held;
                $this->session->unset_userdata('hold');
                if($this->session->userdata('setup')) {
                    $cart[] = (object) $this->session->userdata('setup');
                    $this->session->unset_userdata('setup');
                }
            }

            $this->session->set_userdata('cart', $cart);  
            $this->show_addons($held['item'], $held['cart_id']);
        }
    }



    function add_domain()
    {
        $cart = $this->session->userdata('cart');
           if ($this->input->post()) {          

            $count = 0;

            foreach($cart AS $item) {
                if(
                    $item->domain == $this->input->post('domain') && $item->item == '') {
                    $count++;
                }
            }

            if($count == 0) {

                $time = time();
                $this->session->set_userdata('fields',  $time);

                $tld = explode('.', $this->input->post('domain'), 2);
                $ext = $tld[1]; 
                $item = $this->db->where('item_name', $ext)->get('items_saved')->row();
                $tax = Applib::format_deci((null != $item->item_tax_rate && $item->item_tax_rate > 0) ? ($this->input->post('price') * $item->item_tax_rate) / 100 : '0.00');
                $i = Item::view_item($item->item_id);
                
                $c = (object) array(
                    'cart_id' =>  $time,
                    'item' => $item->item_id, 
                    'renewal' => 'annually', 
                    'name' => $this->input->post('type'),             
                    'price' => $this->input->post('price'), 
                    'domain' => $this->input->post('domain'), 
                    'tax' => $tax, 
                    'authcode' => '',
                    'nameservers' => ''); 

                    if($item->max_years > 1)
                    {
                        $c->years = floatval($this->input->post('price')) / floatval($i->registration);
                    }
                
                $cart[] = $c;

                if($this->session->userdata('hold')) {
                    $held = $this->session->userdata('hold');
                    $held['domain'] = $this->input->post('domain', true); 
                    $cart[] = (object) $held;
                    $this->session->unset_userdata('hold');
                    if($this->session->userdata('setup')) {
                        $cart[] = (object) $this->session->userdata('setup');
                        $this->session->unset_userdata('setup');
                    }
                }
            }

            $this->session->set_userdata('cart', $cart);     
            if($this->additional_fields($ext) || $item->name == lang('domain_transfer'))
            {    
                if($item->name == lang('domain_transfer')) {
                    $this->session->set_userdata('transfer', true);
                } 
    
                redirect('cart/domain_fields');
            }
    
            else 
            {
                if($count == 0)
                {
                    $this->show_addons($item->item_id, $time);
                }
            }                  
        }
    }



    function additional_fields($tld = null)
    {
        $tlds = array("us", "co.uk", "net.uk", "org.uk", "plc.uk", "ltd.uk", "me.uk", "uk", "ca", "es", "sg", "com.sg", "edu.sg", "net.sg", "org.sg", "per.sg", "tel", "it", 
        "de", "com.au", "net.au", "org.au", "asn.au", "id.au", "asia", "pro", "coop", "cn", "fr", "re", "pm", "tf", "wf", "yt", "nu", "quebec", "jobs", "travel",
        "ru", "ro", "srts.ro", "co.ro", "com.ro", "firm.ro", "info.ro", "nom.ro", "nt.ro", "org.ro", "rec.ro", "ro.ro", "store.ro", "tm.ro", "www.ro",
        "hk", "com.hk", "edu.hk", "gov.hk", "idv.hk", "net.hk", "org.hk", "pl", "pc.pl", "miasta.pl", "atm.pl", "rel.pl", "gmina.pl", "szkola", "sos.pl",
        "media.pl", "edu.pl", "auto.pl", "agro.pl", "turystyka.pl", "gov.pl", "aid.pl", "nieruchomosci.pl", "com.pl", "priv.pl", "tm.pl", "travel.pl", "info.pl",
        "org.pl", "net.pl", "sex.pl", "sklep.pl", "powiat.pl", "mail.pl", "realestate.pl", "shop.pl", "mil.pl", "nom.pl", "gsm.pl", "tourism.pl", "targi.pl", "biz.pl",
        "se", "tm.se", "org.se", "pp.se", "parti.se", "presse.se");
        if(in_array($tld, $tlds)) {
            $this->session->set_userdata('tld', $tld);
            return true;
        }
        return false;
    }




    function remove($id)
    {
        $cart = $this->session->userdata('cart');
        
        foreach($cart as $key => $row) {
            if($row->cart_id == $id || isset($row->parent) && $row->parent == $id) {
                unset($cart[$key]);
            } 

        }  
        $this->session->set_userdata('cart', $cart);
        redirect('cart');         
    }



    function remove_all()
    {
        $this->session->set_userdata('cart', array());
        $this->session->set_userdata('codes', array()); 
        $this->session->set_userdata('new_cust', array()); 
        $this->session->set_userdata('discounted', array());

        $this->session->unset_userdata('hold');
        $this->session->set_userdata('tld', '');
        redirect('cart');         
    }



    function continue()
    {   
        (User::is_logged_in()) ? redirect('orders/add_order') : redirect(base_url());
    }



    function shopping_cart()
    {   $data = array();
        $this->template->title(lang('shopping_cart')); 
        $this->layout($data, 'shopping_cart'); 
    }


    
    function domain_fields()
    {   $data = array();
        $this->template->title(lang('domain_registration')); 
        if ($this->input->post()) {            
            foreach($this->input->post() as $key => $value)
                {    
                    if($key != 'authcode') {
                        $fields = array(
                        'domain' => $this->session->userdata('fields'),                
                        'field_name' => str_replace("_"," ",$key),
                        'field_value' => $value
                        );
                        App::save_data('additional_fields', $fields); 
                    }                   
                }

            $cart = $this->session->userdata('cart'); 
            $cart[count($cart) - 1]->authcode = $this->input->post('authcode');           
            $this->session->set_userdata('cart', $cart);
            $this->session->unset_userdata('transfer');
            $this->session->unset_userdata('tld');
            $this->show_addons($cart[count($cart) - 1]->item, $cart[count($cart) - 1]->cart_id);  
        }
        $this->layout($data, 'additional_fields'); 
    }

    	

	function checkout()
	{ 
        $order = (object) array('order' => $this->session->userdata('cart'));
        $data = array('order' => $order, 'process' => true);
        $this->session->set_userdata($data);
        redirect('auth/register');
    }
    

    
    function validate_code()
	{ 
        $code = $this->input->post('code', true); 
        $promotion = $this->db->where('code', $code)->get('promotions')->row(); 

        if(!empty($promotion))
        { 
            $codes = $this->session->userdata('codes');
            $new_cust = $this->session->userdata('new_cust');
            $discounted = $this->session->userdata('discounted');

            $items = unserialize($promotion->apply_to);
            $required = unserialize($promotion->required);
            $billing = unserialize($promotion->billing_cycle);

            $type = $promotion->type;
            $value = $promotion->value;
                        
            if($promotion->use_date == 1 &&
            (strtotime(date('Y-m-d')) < strtotime($promotion->start_date) ||
            strtotime(date('Y-m-d')) > strtotime($promotion->end_date)))
            {
                redirect('cart');
            }

            $cart = $this->session->userdata('cart');
 
            foreach($cart as $k => $c)
            { 
                if($c->domain != 'promo')
                {
                    $i = time();
                    $processed = false;
    
                    if(!empty($discounted))
                    {     
                        if($promotion->per_order == 1)
                        {
                            foreach($discounted as $discount)
                            { 
                                if($discount['code'] == $code)
                                {
                                    $processed = true;
                                } 
                            }
                        }
                        
                        else
                        { 
                            foreach($discounted as $discount)
                            {  
                                if($discount['code'] == $code && $k == $discount['key'])
                                { 
                                    $processed = true;
                                }                                
                            } 
                        }
                    
    
                        if($processed)
                        { 
                            continue;
                        }

                    }
    
     
                    if(is_array($items) && in_array($c->item, $items) || $c->item == $items)
                    {
                        if(!empty($required))
                        {
                            $count = 0;
                                              
                            foreach($items as $item)
                            {
                                if(is_array($required) && in_array($item, $required) || $item == $required)
                                {
                                    $count++;
                                }
                            }
    
                            if($count == 0)
                            { 
                                continue;
                            }
                        }
                    }
     
    
                    if(!empty($billing))
                    {
                        if(is_array($billing) && !in_array($c->renewal, $billing) || $c->renewal != $billing)
                        { 
                            continue;
                        }
                    }
                    
                    if($promotion->per_order == 1)
                    {
                        if(in_array($code, $codes))
                        { 
                            continue;
                        }
                    }
    
     
                    $item = Item::view_item($c->item); 
                    $tax = Applib::format_deci((null != $item->item_tax_rate && $item->item_tax_rate > 0) ? 
                    ($c->price * $item->item_tax_rate)/100 : 0);
                    $total = $c->price + $tax;
    
                    if($type == 2)
                    {
                        $discount = ($value / 100) * $total; 
                    } 
                    
                    else
                    {
                        $discount = $value; 
                    }
     
                      
                    if($promotion->new_customers == 1)
                    {
                        $new_cust[] = $i;
                        $this->session->set_userdata('new_cust', $new_cust); 
                        $promotion->description = (User::is_logged_in()) ? 
                        $promotion->description : 
                        $promotion->description . 
                        " (" . lang('pending_validation') . ")";
                    } 
    
                    $cart[] = (object) array(
                        'cart_id' => $i, 
                        'item' => $promotion->description, 
                        'name' => $promotion->code, 
                        'renewal' => 'one_time_payment', 
                        'price' => -1 * $discount,       
                        'tax' => 0,         
                        'domain' => 'promo');
                    
                    $this->session->set_userdata('cart', $cart);                 
     
                    $discounted[] = array('code' => $code, 'item' => $c->item, 'amount' => $discount, 'key' => $k);
                    $this->session->set_userdata('discounted', $discounted); 
                    
                    $codes[] = $code;
                    $codes = $this->session->set_userdata('codes', $codes); 
                }               
            }
        }        

       redirect('cart');
	}


 

}

/* End of file cart.php */