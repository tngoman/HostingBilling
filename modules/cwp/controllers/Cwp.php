<?php
/* Module Name: CWP
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Servers
 * Description: Centos Web Panel API Integration.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class CWP extends Hosting_Billing
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

    
    public function cwp_package_config ($values = null)
    {
        $config = array(
            array(
                'label' => 'Package ID',
                'id' => 'package',
                'placeholder' => 'The package ID as it appears in CWP',
                'value' => isset($values) ? $values['package'] : ''
            ) 
        ); 
        
        return $config;        
    }

    

    public function check_connection ($server = NULL)
    {        
        $data = array();
        $data['debug'] = 1;
        $data['action'] = 'list';
        
        $this->load->library('cwp/CWP_exec', $server);
        $this->cwp_exec->uri = 'packages'; 
        $response = $this->cwp_exec->exec($data); 
        echo $response;
        die();
        $response = json_decode($response, true);
        print_r($response);
        die;

        return ($response['status'] == 'Error') ? $response['msj'] : $response['status']; 
    }



    public function create_account($params)
    { 
        $data = array(
            'domain' => $params->account->domain, 
            'user' => $params->account->username, 
            'pass' => $params->account->password, 
            'email' => $params->client->company_email, 
            'package' => $params->package->package_name, 
            'inode' => 0,
            'limit_nproc' => 40, 
            'limit_nofile' => 150,  
            'autossl' => 0,
            'server_ips' => $params->server->hostname,
            'encodepass' => 0, 
            'action' => 'add',
            'debug' => 0
        ); 

        if($params->package->reseller_package == 'Yes') 
        {
            $data['reseller'] = 1;
        }
 
        $this->load->library('cwp/CWP_exec', $params->server); 
        $this->cwp_exec->uri = 'account'; 
        $response = $this->cwp_exec->exec($data);
        $response = json_decode($response, true);
        return ($response['status'] == 'Error') ? $response['msj'] : $response['status'];        
    }


    public function get_usage ($order)
    {
        $params = array();
        $usage = array('disk_limit' => 0, 'disk_used' => 0, 'bw_limit' => 0, 'bw_used' => 0);
        $server = Order::get_server($order->server); 
        $this->load->library('cwp/CWP_exec', $server);

        $data = array();
        $data['debug'] = 0; 
        $data['user'] = $order->username;
        $data['action'] = 'list';
        $this->cwp_exec->uri = 'account'; 
        $response = $this->cwp_exec->exec($data);         
        $response = json_decode($response, true);  
 
         if(isset($response['msj']))
        {
            foreach($response['msj'] as $account)
            {                 
                if($account['domain'] == $order->domain)
                {
                    $usage['disk_limit'] = $account['disklimit'];          
                    $usage['disk_used'] = $account['diskused'];
                    $usage['bw_limit'] = $account['bwlimit'];          
                    $usage['bw_used'] = (!empty($account['bandwidth'])) ? $account['bandwidth'] : 0;
                }
            }            
        }

        return $usage;
    } 



    public function change_password ($params)
    { 
        $this->load->library('cwp/CWP_exec', $params->server); 
        $data = array('user' => $params->account->username, 'pass' => $params->account->password);        
        $data['debug'] = 0;  
        $data['action'] = 'udp';
        $this->cwp_exec->uri = 'changepass'; 
        $response = $this->cwp_exec->exec($data);
        $response = json_decode($response, true);
        return ($response['status'] == 'Error') ? $response['msj'] : $response['status'];
    }
        


    public function suspend_account ($params)
    { 
        $this->load->library('cwp/CWP_exec', $params->server);
        $data = array();
        $data['debug'] = 0; 
        $data['user'] = $params->account->username;
        $data['action'] = 'susp';
        $this->cwp_exec->uri = 'account'; 
        $response = $this->cwp_exec->exec($data);
        $response = json_decode($response, true);
        return ($response['status'] == 'Error') ? $response['msj'] : $response['status'];
    }
    


    public function unsuspend_account ($params)
    { 
        $this->load->library('cwp/CWP_exec', $params->server);
        $data = array();
        $data['debug'] = 0; 
        $data['user'] = $params->account->username;
        $data['action'] = 'unsp';
        $this->cwp_exec->uri = 'account'; 
        $response = $this->cwp_exec->exec($data);
        $response = json_decode($response, true);
        return ($response['status'] == 'Error') ? $response['msj'] : $response['status'];
    }

 

    public function change_package ($params)
    {
        $this->load->library('cwp/CWP_exec', $params->server); 
        $data = array( 
            'user' => $params->account->username,            
            'package' => $params->package->package_name
        ); 
        $data['debug'] = 0;  
        $data['action'] = 'udp'; 

        $this->cwp_exec->uri = 'changepack'; 
        $response = $this->cwp_exec->exec($data);

        $response = json_decode($response, true);
        return ($response['status'] == 'Error') ? $response['msl'] : $response['status'];
    }
 


    public function terminate_account($params)
    {
        $this->load->library('cwp/CWP_exec', $params->server); 
        $data = array( 
            'user' => $params->account->username,
            'email' => $params->client->company_email,            
            'all' => 1
        ); 
        $data['debug'] = 0;  
        $data['action'] = 'del';
        $this->cwp_exec->uri = 'account'; 
        $response = $this->cwp_exec->exec($data);
        $response = json_decode($response, true);
        return $response['status'];
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
        $this->load->library('cwp/CWP_exec', $params->server); 
        $data = array( 
            'user' => $params->account->username, 
            'timer' => 5
        ); 
        $data['debug'] = 0;  
        $data['action'] = 'list';
        $this->cwp_exec->uri = 'user_session'; 
        $response = $this->cwp_exec->exec($data);
        $response = json_decode($response, true);
        $link = $response['msj']['details'];
        redirect($link[0]['url']);
    }

 


    function admin_options ($server) 
    { 
        $code = '<a class="btn btn-success btn-xs" href="'.base_url().'servers/index/'.$server->id.'"><i class="fa fa-options"></i> '.lang('test_connection').'</a>
        <a class="btn btn-primary btn-xs" href="'.base_url().'servers/edit_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-pencil"></i> '.lang('edit').'</a>
        <a class="btn btn-danger btn-xs" href="'.base_url().'servers/delete_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-trash"></i> '.lang('delete').'</a>
        <form action="https://'.$server->hostname.':2031" method="post" target="_blank" style="display:inline;">
        <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-user"></i> '.lang("login").'</button>
        </form>';

        return $code;
    }

     
}
