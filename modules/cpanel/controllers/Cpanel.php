<?php
/* Module Name: cPanel
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Servers
 * Description: cPanel API Integration.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class cPanel extends Hosting_Billing
{      
    
    public function cpanel_package_config ($values = null)
    {
        $config = array(
            array(
                'label' => 'Package Name',
                'id' => 'package',
                'placeholder' => 'The package name as it appears in WHM',
                'value' => isset($values) ? $values['package'] : ''
            ) 
        ); 
        
        return $config;        
    }



    public function check_connection ($server = NULL)
    {
        $params = array(
            'user' => trim($server->username)
        );  
        
        $this->load->library('cpanel/Cpanel_exec', $server);
        $response = $this->cpanel_exec->call('accountsummary', $params);
        return (isset($response['metadata']['reason'])) ? $response['metadata']['reason'] : 'Connection Failed!'; 
    }



    public function create_account ($params)
    {          				
        $this->load->library('cpanel/Cpanel_exec', $params->server);
        $payload = array(
            'plan' => $params->package->package_name,
            'username' => $params->account->username,
            'password' => $params->account->password,
            'domain' => $params->account->domain,
            'contactemail' => $params->client->company_email,
            'cgi' => 1,
            'hasshell' => 1,
            'cpmod' => 'paper_lantern'
        );

        $response =$this->cpanel_exec->call('createacct', $payload);
        $result .= $domain." ".$response['metadata']['reason'];	
            

        if($params->package->reseller_package == 'Yes') { 
                if(isset($response['metadata'])){
                    $payload = array(
                        'makeowner' => 1,
                        'username' => $params->account->username
                    );

                    $response = $this->cpanel_exec->call('setupreseller', $payload);
                    $result .=  $response['metadata']['reason'];

                }
            }
            
        return $result;
    }



    public function suspend_account ($params)
    {      
        $this->load->library('cpanel/Cpanel_exec', $params->server);        
       
            $req = array(							
                "user" => $params->account->username,
                "reason" => $params->reason
            );			

            if($params->package->reseller_package == 'Yes') 
            {
                $response = $this->cpanel_exec->call('suspendreseller', $req); 
            }
            else
            {
                $response = $this->cpanel_exec->call('suspendacct', $req); 
            } 
             
            $result = isset($response['metadata']['reason']) ? $response['metadata']['reason'] : '';        
            
        return $result;
    }




    public function unsuspend_account ($params)
    {        
        $this->load->library('cpanel/Cpanel_exec', $params->server);								 
        $req = array(							
            "user" => $params->account->username 
        );			
        $response = $this->cpanel_exec->call('unsuspendacct', $req); 
        $result = $response['metadata']['reason'];
            
        return $result;
    }




    public function change_password ($params)
    {       
        $this->load->library('cpanel/Cpanel_exec', $params->server);								 
        $req = array(							
            "user" => $params->account->username,
            "password" => $params->account->password
        );			
        $response = $this->cpanel_exec->call('passwd', $req); 
        $result = $response['metadata']['reason'];
            
        return $result;
    }




    public function get_usage ($order)
    {
        $params = array();
        $usage = array('disk_limit' => 0, 'disk_used' => 0, 'bw_limit' => 0, 'bw_used' => 0);
        $server = Order::get_server($order->server);

        $this->load->library('cpanel/Cpanel_exec', $server);
        $response = $this->cpanel_exec->call('showbw', $params);      

        if(isset($response['data']['acct'])){
            $data = $response['data']['acct'];
            foreach($data AS $account) {
                if($account['maindomain'] == $order->domain) {
                    $bwused = $account["totalbytes"];
                    $bwlimit = $account["limit"];
                    $usage['bw_used'] = $bwused / (1024 * 1024);
                    $usage['bw_limit'] = $bwlimit / (1024 * 1024);
                }
            }
        }       

        $params = array( 
            'domain' => $order->domain
        );
        $response = $this->cpanel_exec->call('accountsummary', $params); 

        if(isset($response['data']) && isset($response['data']['acct'][0])) { 
            $data = $response['data']['acct'][0];
            $usage['disk_limit'] = preg_replace('/[^0-9]/', '', $data['disklimit']);
            $usage['disk_used'] = preg_replace('/[^0-9]/', '', $data['diskused']); 
        }
       
        return $usage;
    }
    
    

    public function change_package ($params)
    {
        $this->load->library('cpanel/Cpanel_exec', $params->server);								 
        $req = array(
            'user' => $params->account->username,
            'pkg' => $params->package->package_name			
        );
        $response = $this->cpanel_exec->call('changepackage', $req); 
        $result = $response['metadata']['reason'];
            
        return $result; 
    }



    public function terminate_account ($params)
    {
        $request = array(							
            'username' => $params->account->username								
        );
        
        $this->load->library('cpanel/Cpanel_exec', $params->server);

        if($params->package->reseller_package == 'Yes') {	
            $response = $this->cpanel_exec->call('terminatereseller', $request); 
        }
        else
        {
            $response = $this->cpanel_exec->call('removeacct', $request); 
        }

        $result = $response['metadata']['reason'];
            
        return $result; 
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



    public function import($server)
    {
        try {

            $params = array(
                'user' => trim($server->username)
            ); 
            
            $this->load->library('cpanel/Cpanel_exec', $server); 
            $response = $this->cpanel_exec->call('listaccts', $params);
            $list = (is_array($response['data']['acct'])) ? $response['data']['acct'] : ['metadata']['reason']; 
            if(is_array($list)) {
                foreach($list as $key => $li){
                    $list[$key]['pass'] = '';
                }
            }

          } catch (Exception $e) {
             $list = 'Error: '. $e->getMessage();  
          } 

          return $list;
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




    public function admin_login ($server)
    {
        $this->load->library('cpanel/Cpanel_exec', $server); 
        $req = array(							
            'user' => $server->username,
            'service' => 'cpaneld'
        );			
        $response = $this->cpanel_exec->call('create_user_session', $req); 

        if(isset($response['data'])) {
            $url = $response['data']['url'];
            $url = str_replace(2083,2087,$url);
            redirect($url);
        }
        
        else {			
            $this->session->set_flashdata('response_status', 'warning');
            $this->session->set_flashdata('message', $response['metadata']['reason']);
            redirect($_SERVER['HTTP_REFERER']);
        }
    } 




    function admin_options ($server) 
    { 
        $code = '<a class="btn btn-success btn-xs" href="'.base_url().'servers/index/'.$server->id.'"><i class="fa fa-options"></i> '.lang('test_connection').'</a>
        <a class="btn btn-warning btn-xs" href="'.base_url().'servers/import/'.$server->id.'"  ><i class="fa fa-download"></i> '.lang('import_accounts').'</a>
        <a class="btn btn-primary btn-xs" href="'.base_url().'servers/edit_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-pencil"></i> '.lang('edit').'</a>
        <a class="btn btn-danger btn-xs" href="'.base_url().'servers/delete_server/'.$server->id.'" data-toggle="ajaxModal"><i class="fa fa-trash"></i> '.lang('delete').'</a>
        <a class="btn btn-success btn-xs" href="'.base_url().'servers/login/'.$server->id.'" target="_blank" ><i class="fa fa-user"></i> '.lang('login').'</a>';

        return $code;
    }


                
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


    
}
