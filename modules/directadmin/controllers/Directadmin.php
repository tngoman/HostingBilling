<?php
/* Module Name: Directadmin
 * Module URI: http://www.hostingbilling.net
 * Version: 1.0
 * Category: Servers
 * Description: Directadmin API Integration.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Directadmin extends Hosting_Billing
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


    public function directadmin_package_config ($values = null)
    {
        $config = array(
            array(
                'label' => 'Package Name',
                'id' => 'package',
                'placeholder' => 'The package name as it appears in Directadmin',
                'value' => isset($values) ? $values['package'] : ''
            ) 
        ); 
        
        return $config;        
    }

    
    public function check_connection ($server = NULL)
    { 
        $this->load->library('directadmin/DA_exec', $server);
        $req = array('user' => $server->username); 			
        $response = $this->da_exec->query("CMD_API_SHOW_DOMAINS", $req, "GET");
        return (empty($response) || isset($response[0])) ? 'OK' : ((is_array($response) && !isset($response[0])) ? 'USE SSL' : '');
    }


    public function create_account($params)
    {
        $request = array(
        'action' => 'create',
        'package' => $params->package->package_name,
        'username' => $params->account->username,
        'passwd' => $params->account->password,
        'passwd2' => $params->account->password,
        'domain' => $params->account->domain,
        'email' => $params->client->company_email,
        'notify' => 'no',
        'ip' => gethostbyname($params->server->hostname)
        );					

        $this->load->library('directadmin/DA_exec', $params->server);		
        if($params->package->reseller_package == 'Yes') { 	
            $response = $this->da_exec->query("CMD_ACCOUNT_RESELLER", $request, "POST"); 
        }
        else{
            $response = $this->da_exec->query("CMD_API_ACCOUNT_USER", $request, "POST"); 
        }        
        
        return $response['details'];				
    }



    public function get_usage ($order)
    {
        $params = array();
        $usage = array('disk_limit' => 0, 'disk_used' => 0, 'bw_limit' => 0, 'bw_used' => 0);
        $server = Order::get_server($order->server);
        $package = Order::get_package($order->item_parent);

        $params = array(
            'package' => $package->package_name
        );					

        $this->load->library('directadmin/DA_exec', $server);		
        $response = $this->da_exec->query("CMD_API_PACKAGES_USER", $params, "GET"); 

        if(isset($response['quota'])) {
            $usage['disk_limit'] = (is_numeric($response['quota'])) ? round($response['quota']) : $response['quota'];
            $usage['bw_limit'] = (is_numeric($response['bandwidth'])) ? round($response['bandwidth']) : $response['bandwidth'];
        }

        $req = array(
            'user' => $order->username
        ); 		

        $response = $this->da_exec->query("CMD_API_SHOW_USER_USAGE", $req, "GET"); 
        if(isset($response['quota'])) {
            $usage['disk_used'] = (is_numeric($response['quota'])) ? round($response['quota']) : $response['quota'];
            $usage['bw_used'] = (is_numeric($response['bandwidth'])) ? round($response['bandwidth']) : $response['bandwidth'];
        }
       
        return $usage;
    } 
    
    

    public function suspend_account ($params)
    {       				
            
        if($params->account->status_id == 6) { 

            $this->load->library('directadmin/DA_exec', $params->server); 
				$req = array(							
					"suspend" => "Suspend/Unsuspend",
					"select0" => $params->account->username
				);	
		 
				return $this->da_exec->query("CMD_SELECT_USERS", $req, "POST");
        } 
    }




    public function unsuspend_account ($params)
    {       				
            
        if($params->account->status_id == 9) { 

            $this->load->library('directadmin/DA_exec', $params->server); 
				$req = array(							
					"suspend" => "Suspend/Unsuspend",
					"select0" => $params->account->username
				);	
		 
				return $this->da_exec->query("CMD_SELECT_USERS", $req, "POST");
        } 
    }



    public function change_password ($params)
    {    
        $req = array(							
                "username" => $params->account->username,
                "passwd" => $params->account->password,
                "passwd2" => $params->account->password
            );	

            $this->load->library('directadmin/DA_exec', $params->server);
            $result = $this->da_exec->query("CMD_API_USER_PASSWD", $req, 'POST'); 
            if(isset($result['details'])) {
                $result = $result['details'];
            }					
            else {
                $result = lang('connection_failed');
            }
    
        return $result;        
    }



    public function change_package ($params)
    {
        $this->load->library('directadmin/DA_exec', $params->server);
        if($params->package->reseller_package == 'Yes') { 										
            $req = array(
                'action' => 'package',
                'user' => $params->account->username,
                'package' => $params->package->package_name
            );					 
            $response = $this->da_exec->query("CMD_API_MODIFY_RESELLER", $req, "POST"); 
         }
         else
         {
            $req = array(
                'action' => 'package',
                'user' => $params->account->username,
                'package' => $params->package->package_name
            );					
 
            $result = $this->da_exec->query("CMD_API_MODIFY_USER", $req, "POST");
         } 

         if(isset($result['details'])) {
            $result = $result['details'];
        }					
        else {
            $result = lang('connection_failed');
        }

        return $result;           
    }



    public function terminate_account ($params)
    {
        $this->load->library('directadmin/DA_exec', $params->server);
        $req = array(
        'confirmed' => 'Confirm',
        'delete' => 'yes',
        'select0' => $params->account->username
        );

        $result = $this->da_exec->query("CMD_SELECT_USERS", $req, "POST");        

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
    {   $req = array('user' => $server->username);

        try {
            $this->load->library('directadmin/DA_exec', $server);			
            $response = $this->da_exec->query("CMD_API_SHOW_USERS", $req, "GET");    

            if(is_array($response)){
                foreach($response as $acc) { 
                    $req = array('user' => $acc);		
                    $response = $this->da_exec->query("CMD_API_SHOW_USER_CONFIG", $req, "GET");
                    if(is_array($response)) { 
                        $response['user'] = $response['username'];
                        $response['plan'] = $response['package'];
                        $response['startdate'] = $response['date_created'];
                        $response['pass'] = '';
                        $list[] = $response;
                    }
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
        $this->template->title('DirectAdmin');
        $data['page'] = 'Directadmin'; 
        $data['hostname'] = $server->hostname;
        $data['port'] = $server->port;
        $data['username'] = $server->username;
        $data['authkey'] = $server->authkey;
        $this->template
        ->set_layout('users')
        ->build('login',isset($data) ? $data : NULL);  
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

 
 
}
