<?php
/* Module Name: Cyberpanel
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Servers
 * Description: Cyberpanel API Integration.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Cyberpanel extends Hosting_Billing
{      

               
    public function activate($data)
    { 
        return true;
    }


    public function install()
    { 
        return true;
    }


    public function uninstall()
    { 
        return true;
    }


    public function cyberpanel_package_config ($values = null)
    {
        $config = array(
            array(
                'label' => 'Package Name',
                'id' => 'package',
                'placeholder' => 'The package name as it appears in Cyberpanel',
                'value' => isset($values) ? $values['package'] : ''
            ) 
        ); 
        
        return $config;        
    }

    
    public function check_connection ($server = NULL)
    {
        $this->load->library('cyberpanel/Cyber_exec', $server); 
        $result = $this->cyber_exec->call("verifyConn", array());
        if(isset($result['verifyConn']))
        {
            return 'OK';
        }

        else {
            return $result;
        }
    }



    public function create_account($params)
    {       
        $postParams =  [ 
            "domainName" => $params->account->domain,
            "ownerEmail" => $params->client->company_email,
            "packageName" => $params->package->package_name,
            "websiteOwner" => $params->account->username,
            "ownerPassword" => $params->account->password,
            "acl" => 'user',
        ]; 

        $this->load->library('cyberpanel/Cyber_exec', $params->server); 
        $result = $this->cyber_exec->call("createWebsite", $postParams);
        
        if(isset($result['createWebSiteStatus']) && $result['createWebSiteStatus'] == 1)
        {
            return 'OK';
        }

        else {
            return $result['error_message'];
        }
    }




    public function suspend_account($params)
    {        
        $postParams =  [ 
            "websiteName" => $params->account->domain,
            "state" => "Suspend" 
        ];
         
        $this->load->library('cyberpanel/Cyber_exec', $params->server); 
        $result = $this->cyber_exec->call("submitWebsiteStatus", $postParams);
        
        if(isset($result['websiteStatus']) && $result['websiteStatus'] == 1)
        {
            return 'OK';
        }

        else {
            return $result['error_message'];
        }
    }




    public function unsuspend_account($params)
    {        
        $postParams =  [ 
            "websiteName" => $params->account->domain,
            "state" => "Activate" 
        ];
         
        $this->load->library('cyberpanel/Cyber_exec', $params->server); 
        $result = $this->cyber_exec->call("submitWebsiteStatus", $postParams);
        
        if(isset($result['websiteStatus']) && $result['websiteStatus'] == 1)
        {
            return 'OK';
        }

        else {
            return $result['error_message'];
        }
    }




    public function change_password($params)
    {       
        $postParams =  [  
            "websiteOwner" => $params->account->username,
            "ownerPassword" => $params->account->password
        ];
         
        $this->load->library('cyberpanel/Cyber_exec', $params->server); 
        $result = $this->cyber_exec->call("changeUserPassAPI", $postParams);
 
        if(isset($result['changeStatus']) && $result['changeStatus'] == 1)
        {
            return 'OK';
        }

        else {
            return $result['error_message'];
        }
    }




    public function change_package($params)
    {       
        $postParams =  [  
            "websiteName" => $params->account->domain,
            "packageName" => $params->package->package_name
        ];
         
        $this->load->library('cyberpanel/Cyber_exec', $params->server); 
        $result = $this->cyber_exec->call("changePackageAPI", $postParams);
 
        if(isset($result['changePackage']) && $result['changePackage'] == 1)
        {
            return 'OK';
        }

        else {
            return $result['error_message'];
        }
    }




    public function terminate_account ($params)
    {       
        $postParams =  [  
            "domainName" => $params->account->domain 
        ];
         
        $this->load->library('cyberpanel/Cyber_exec', $params->server); 
        $result = $this->cyber_exec->call("deleteWebsite", $postParams);
 
        if(isset($result['websiteDeleteStatus']) && $result['websiteDeleteStatus'] == 1)
        {
            return 'OK';
        }

        else {
            return $result['error_message'];
        }
    }




    
    public function get_usage ($order)
    {
        $params = array();
        $usage = array('disk_limit' => 0, 'disk_used' => 0, 'bw_limit' => 0, 'bw_used' => 0);        
        return $usage;
    } 
    
    

    
    public function client_options ($id = null) 
    { 
        $code = '<a href="'.base_url().'accounts/view_logins/'.$id.'" class="btn btn-sm btn-success" data-toggle="ajaxModal">
        <i class="fa fa-eye"></i>'.lang('view_cpanel_logins').'</a>
        <a href="'.base_url().'accounts/change_password/'.$id.'" class="btn btn-sm btn-info" data-toggle="ajaxModal">
        <i class="fa fa-edit"></i>'.lang('change_cpanel_password').'</a>         
        <a href="'.base_url().'accounts/login/'.$id.'" class="btn btn-sm btn-warning" target="_blank"><i class="fa fa-sign-in"></i> &nbsp;'.lang('control_panel').'</a>';

        return $code; 
    }
 


    public function client_login ($params)
    { 
        $this->load->library('cpanel/Cpanel_exec', $params->server); 
        $req = array(							
            'user' => $params->account->username,
            'service' => 'cpaneld'
        );			
        $response = $this->cpanel_exec->call('create_user_session', $req); 

        if(isset($response['data'])) {
            $url = $response['data']['url'];
            redirect($url);
        }
        
        else {			
            $this->session->set_flashdata('response_status', 'warning');
            $this->session->set_flashdata('message', $response['metadata']['reason']);
            redirect($_SERVER['HTTP_REFERER']);
        }
    }




 


    function admin_options ($server) 
    {   $protocol = ($server->use_ssl == 'Yes') ? 'https://' : 'http://';
        $code = '<a class="btn btn-success btn-xs" href="'.base_url().'servers/index/'.$server->id.'"><i class="fa fa-options"></i> '.lang('test_connection').'</a>
        <a class="btn btn-primary btn-xs" href="'.base_url().'servers/edit_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-pencil"></i> '.lang('edit').'</a>
        <a class="btn btn-danger btn-xs" href="'.base_url().'servers/delete_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-trash"></i> '.lang('delete').'</a>
        <form action="'. $protocol . $server->hostname.':8090" method="get" target="_blank" style="display:inline;">
        <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-user"></i> '.lang("login").'</button>
        </form>';

        return $code;
    }


 
}
