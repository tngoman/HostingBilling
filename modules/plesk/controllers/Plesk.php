<?php
/* Module Name: Plesk
 * Module URI: http://www.hostingbilling.net
 * Version: 1.1
 * Category: Servers
 * Description: Plesk API Integration.
 * Author: Hosting Billing
 * Author URI: www.hostingbilling.net
 */

class Plesk extends Hosting_Billing
{      
    
    public function plesk_package_config ($values = null)
    {
        $config = array(
            array(
                'label' => 'Package Name',
                'id' => 'package',
                'placeholder' => 'The package name as it appears in Plesk',
                'value' => isset($values) ? $values['package'] : ''
            ) 
        ); 
        
        return $config;        
    }

 

    public function check_connection ($server = NULL)
    { 
        $xml = '<packet>
                    <server>
                        <get_protos/>
                    </server>
                </packet>';
                    
        $this->load->library('plesk/Plesk_exec', $server); 
        $res = $this->plesk_exec->request($xml); 
        if(isset($res->server->get_protos->result->errcode)){
        return $res->server->get_protos->result->errtext;
        } 

        if(isset($res->server->get_protos->result->status)){
        return ucfirst($res->server->get_protos->result->status);
        }

        return $res;
               
    }



    public function create_account($params){

        $this->load->library('plesk/Plesk_exec', $params->server); 
        $user = $this->create_user($params);
        if(!isset($user->id)) {  
            return $user; 
        }


        if($params->package->reseller_package == 'Yes')
        {
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
            <packet>
            <reseller>
            <add>
                <gen-info>
                    <pname>'.$params->profile->fullname.'</pname>
                    <login>'.$params->account->username.'</login>
                    <passwd>'.$params->account->password.'</passwd>
                    <email>'.$params->client->company_email.'</email>
                </gen-info>
               <plan-name>'.$params->package->package_name.'</plan-name>
            </add>
            </reseller>
            </packet>';
    
    
            $res = $this->plesk_exec->request($xml);     
            
            if(isset($res->reseller->add->result->errcode)){
                return $res->reseller->add->result->errtext;
            }
                 
    
            if(isset($res->reseller->add->result->status) && $res->reseller->add->result->status == 'ok'){
                return $res->reseller->add->result->status;
            } 
        }


        else {
          
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                    <packet>
                        <ip>
                            <get/>
                        </ip>
                    </packet>';

            $resip = $this->plesk_exec->request($xml); 
    
            if(isset($resip->ip->get->result->addresses->ip_info)) {
                if (isset($resip->ip->get->result->addresses->ip_info->ip_address)) {

                $ip = $resip->ip->get->result->addresses->ip_info->ip_address; 

                } else {
                    $ips = $resip->ip->get->result->addresses->ip_info[0];
                    $ip = $ips->ip_address; 
                }
            }  


            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                    <packet>
                    <webspace>
                        <add>
                            <gen_setup>
                                <name>'.$params->account->domain.'</name>
                                <owner-id>'.$user->id.'</owner-id>
                                <htype>vrt_hst</htype>
                            </gen_setup>
                            <hosting>
                                <vrt_hst>
                                    <property>
                                        <name>ftp_login</name>
                                        <value>'.$params->account->username.'</value>
                                    </property>
                                    <property>
                                        <name>ftp_password</name>
                                        <value>'.$params->account->password.'</value>
                                    </property>
                                    <ip_address>'.$ip.'</ip_address>
                                </vrt_hst>
                            </hosting>
                            <plan-name>'.$params->package->package_name.'</plan-name>
                        </add>
                    </webspace>
                    </packet>'; 

            $res = $this->plesk_exec->request($xml); 
 
            if(isset($res->webspace->add->result->errcode)){
                return $res->webspace->add->result->errtext;
            } 
            
        return $res->webspace->add->result->status;
        }
    }




    public function get_usage ($order)
    {
        $params = array();
        $usage = array('disk_limit' => 0, 'disk_used' => 0, 'bw_limit' => 0, 'bw_used' => 0);
        $server = Order::get_server($order->server);         

            if($order->reseller_package == 'Yes') {
                $res = $this->get_reseller_usage($server, $order->username);
            }
        
            else {
                $res = $this->get_account_usage($server, $order->domain);
            }            
        
            $res = json_decode(json_encode($res), true);  
               
            if(isset($res['limits']['limit'])) {
                foreach($res['limits']['limit'] as $limit) {
        
                    if($limit['name'] == 'disk_space') {
                        if($limit['value'] > 0) { $usage['disk_limit'] = $limit['value'] / (1024 * 1024); }
                    }
        
                    if($limit['name'] == 'max_traffic') {
                        if($limit['value'] > 0) { $usage['bw_limit'] = $limit['value'] / (1024 * 1024); }
                    }
                }                
            }
        
        
            if($order->reseller_package == 'Yes') {
          
                    if($res['stat']['disk-space'] > 0) { $disk_usage = $res['stat']['disk-space'] / (1024 * 1024); }  
                    if($res['stat']['traffic'] > 0) { $usage['bw_usage'] = $res['stat']['traffic'] / (1024 * 1024); } 
                    if(isset($res['stat']['traffic']) && $bw_limit == 0) { $usage['bw_limit'] = 9999999999;}
            }
        
            else {
                
                if(isset($res['resource-usage']['resource'])) {
                    foreach($res['resource-usage']['resource'] as $used) {
        
                        if($used['name'] == 'disk_space') {
                            if($used['value'] > 0) { $usage['disk_usage'] = $used['value'] / (1024 * 1024); }
                        }
        
                        if($used['name'] == 'max_traffic') {
                            if($used['value'] > 0) { $usage['bw_usage'] = $used['value'] / (1024 * 1024); }
                        }
                    }                
                }
            }
       
        return $usage;
    }
    
    


    public function get_reseller_usage($server, $username){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
            <reseller>
            <get>
                <filter>
                <login>'.$username.'</login> 
                </filter>           
                <dataset>
                    <gen-info/>
                    <stat/>
                    <limits/>
                </dataset> 
            </get>
            </reseller>
        </packet>';

        $this->load->library('plesk/Plesk_exec', $server); 
        $res = $this->plesk_exec->request($xml);
        if(isset($res->reseller->get->result->data)) {
            return $res->reseller->get->result->data;
        }

        return false;
    }



    public function get_account_usage($server, $domain){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
     <packet>
        <webspace>
        <get>
           <filter> 
              <name>'.$domain.'</name>
           </filter>
           <dataset> 
                <limits/> 
                <resource-usage/>
           </dataset>
        </get>
        </webspace>
        </packet>';

        $this->load->library('plesk/Plesk_exec', $server); 
        $res = $this->plesk_exec->request($xml);
 
        if(isset($res->webspace->get->result->data)) {
            return $res->webspace->get->result->data;
        }

        return false;
    }



    public function create_user($params){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <customer>
        <get>
         <filter>
                <login>'.$params->user->username.'</login>
         </filter>
         <dataset>
            <gen_info/> 
        </dataset>
        </get>
        </customer>
        </packet>';

        $res = $this->plesk_exec->request($xml);

        if(isset($res->customer->get->result->id)){
            return $res->customer->get->result;
        }
        
        
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                <packet>
                    <customer>
                        <add>
                            <gen_info>
                                <pname>'.$params->profile->fullname.'</pname>
                                <login>'.$params->user->username.'</login>
                                <passwd>'.$params->account->password.'</passwd>
                                <email>'.$params->client->company_email.'</email>
                            </gen_info>
                        </add>
                    </customer>
                </packet>';
        $res = $this->plesk_exec->request($xml);
 
        if(isset($res->customer->add->result->errcode)){
            return $res->customer->add->result->errtext;
        }

        return $res->customer->add->result;
    }



    public function suspend_account($params){

        $this->load->library('plesk/Plesk_exec', $params->server); 
        if($params->package->reseller_package == 'Yes') {

            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
            <packet>
            <reseller>
                <set>
                    <filter>
                        <login>'.$params->user->username.'</login>
                    </filter>
                    <values>
                        <gen_setup>
                            <status>16</status>
                        <gen_setup>
                    </values>
                </set>
            </reseller>
            </packet>';

            $res = $this->plesk_exec->request($xml);

            if(isset($res->reseller->set->result->errcode)){
                return $res->reseller->set->result->errtext;
            } 
            
            if(isset($res->reseller->set->result->status)){
                return $res->reseller->set->result->status;
            }  
        }

        else 
        {
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                <packet>
                <webspace>
                    <set>
                        <filter>
                            <name>'.$params->account->domain.'</name>
                        </filter>
                        <values>
                            <gen_setup>
                                <status>16</status>
                            </gen_setup>
                        </values>
                    </set>
                </webspace>
                </packet>';

                $res = $this->plesk_exec->request($xml);

                if(isset($res->webspace->set->result->errcode)){
                    return $res->webspace->set->result->errtext;
                } 
                
                if(isset($res->webspace->set->result->status)){
                    return $res->webspace->set->result->status;
                }  
            }
    }




    public function unsuspend_account($params){

        $this->load->library('plesk/Plesk_exec', $params->server); 
        if($params->package->reseller_package == 'Yes') 
        {
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
            <packet>
            <reseller>
                <set>
                    <filter>
                        <login>'.$params->user->username.'</login>
                    </filter>
                    <values>
                        <gen_setup>
                            <status>0</status>
                        </gen_setup>
                    </values>
                </set>
            </reseller>
            </packet>';

            $res = $this->plesk_exec->request($xml);

            if(isset($res->reseller->set->result->errcode)){
                return $res->reseller->set->result->errtext;
            } 
            
            if(isset($res->reseller->set->result->status)){
                return $res->reseller->set->result->status;
            }  

        }
        else
        {
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                <packet>
                <webspace>
                    <set>
                        <filter>
                            <name>'.$params->account->domain.'</name>
                        </filter>
                        <values>
                            <gen_setup>
                                <status>0</status>
                            </gen_setup>
                        </values>
                    </set>
                </webspace>
                </packet>';

         $res = $this->plesk_exec->request($xml);

         if(isset($res->webspace->set->result->errcode)){
            return $res->webspace->set->result->errtext;
        } 
        
        if(isset($res->webspace->set->result->status)){
            return $res->webspace->set->result->status;
        }
      }  

    }



    public function change_package($params){
        if($params->package->reseller_package == 'Yes') 
        {
            return $this->changeReseller($params);
        }

        else 
        {
            return $this->changePackage($params);
        }        
    }




    public function terminate_account($params){
        if($params->package->reseller_package == 'Yes') 
        {
            return $this->removeReseller($params);
        }

        else 
        {
            return $this->removeAccount($params);
        }        
    }



    private function changePackage($params){
        $this->load->library('plesk/Plesk_exec', $params->server); 
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <service-plan>
            <get>
                <filter>
                  <name>'.$params->package->package_name.'</name>
               </filter>
            </get>
        </service-plan>
        </packet>';
        $res = $this->plesk_exec->request($xml);
        if(!isset($res->{'service-plan'}->get->result->guid)) { 
            return 'New package name not found in server!';
        }

        $gid = ($res->{'service-plan'}->get->result->guid);
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
          <webspace>
            <switch-subscription>
                <filter>
                    <name>'.$params->account->domain.'</name>
               </filter>
            <plan-guid>'.$gid.'</plan-guid>
            </switch-subscription>
          </webspace>
        </packet>';
        $res = $this->plesk_exec->request($xml);   
        return 'Package changed!';   
    }



    private function changeReseller($params){
        $this->load->library('plesk/Plesk_exec', $params->server); 
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <reseller-plan>
            <get>
                <filter>
                  <name>'.$params->package->package_name.'</name>
               </filter>
            </get>
        </reseller-plan>
        </packet>';
        $res = $this->plesk_exec->request($xml); 
        if(!isset($res->{'reseller-plan'}->get->result->guid)) { 
            return 'New package name not found in server!';
        }

        $gid = ($res->{'reseller-plan'}->get->result->guid);
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
          <reseller>
            <switch-subscription>
                <filter>
                    <name>'.$params->account->domain.'</name>
               </filter>
            <plan-guid>'.$gid.'</plan-guid>
            </switch-subscription>
          </reseller>
        </packet>';
        $res = $this->plesk_exec->request($xml);   
        return 'Package changed!';   
    }



    public function removeAccount($params){
        $this->load->library('plesk/Plesk_exec', $params->server); 
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <webspace>
        <del>
           <filter>
              <name>'.$params->account->domain.'</name> 
           </filter>
        </del>
        </webspace>
        </packet>';
        $res = $this->plesk_exec->request($xml); 

        if(isset($res->customer->set->result->errcode)){
            return $res->customer->set->result->errtext;
        } 
        
        if(isset($res->customer->set->result->status)){
            return $res->customer->set->result->status;
        }
    }
 



    public function removeReseller($params){
        $this->load->library('plesk/Plesk_exec', $params->server); 
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <reseller>
        <del>
           <filter>
              <login>'.$params->account->username.'</login> 
           </filter>
        </del>
        </reseller>
        </packet>';
        $res = $this->plesk_exec->request($xml); 

        if(isset($res->customer->set->result->errcode)){
            return $res->customer->set->result->errtext;
        } 
        
        if(isset($res->customer->set->result->status)){
            return $res->customer->set->result->status;
        }
    }




    public function get_accounts($params){
        $this->load->library('plesk/Plesk_exec', $params->server); 
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <webspace>
        <get>
            <filter/>
            <dataset>
                <gen_info/>
                <hosting-basic/>
                <subscriptions/>
            </dataset>
        </get>
        </webspace>
        </packet>';
        $res = $this->plesk_exec->request($xml);

        if(isset($res->webspace->get->result->errcode)){
            return $res->webspace->get->result->errtext;
        }
           return $res->webspace->get;        
    } 




    public function getCustomer($id){ 
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
            <customer>
                <get>
                    <filter>
                        <id>'.$id.'</id>
                    </filter>
                    <dataset>
                        <gen_info/>
                        <stat/>
                    </dataset>
                </get>
            </customer>
        </packet>';
        $res = $this->plesk_exec->request($xml);

        if(isset($res->customer->get->result->errcode)){
            return $res->customer->get->result->errtext;
        } 
      
            return $res->customer->get;        
    }
    
    

    
    public function getPlan($id){
        $this->load->library('plesk/Plesk_exec', $params->server); 
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <service-plan>
            <get>
                <filter>
                  <guid>'.$id.'</guid>
               </filter>
            </get>
        </service-plan>
        </packet>';
        $res = $this->plesk_exec->request($xml);
        if(isset($res->{'service-plan'}->get->result)) { 
            return $res->{'service-plan'}->get->result;
        }
      return false;
    }

 

    public function getAccounts(){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <webspace>
        <get>
            <filter/>
            <dataset>
                <gen_info/>
                <hosting-basic/>
                <subscriptions/>
            </dataset>
        </get>
        </webspace>
        </packet>';
        $res = $this->plesk_exec->request($xml);

        if(isset($res->webspace->get->result->errcode)){
            return $res->webspace->get->result->errtext;
        }
           return $res->webspace->get;        
    } 




    public function import($server)
    {
        try {
            $this->load->library('plesk/Plesk_exec', $server); 
            $response = $this->getAccounts();   
            $response = json_encode($response);
            $response = json_decode($response, true);            
                    
                if(is_array($response['result'])){
                    foreach($response['result'] as $key => $acc) {   

                        foreach($acc['data']['hosting']['vrt_hst']['property'] as $prop) {
                            if($prop['name'] == 'ftp_login') {
                                $response['result'][$key]['user'] = $prop['value'];
                            }

                            if($prop['name'] == 'ftp_password') {
                                $response['result'][$key]['pass'] = $prop['value'];
                            }
                        }   
                        
                        if(isset($acc['data']['gen_info']['owner-id'])) {
                        $customer = $this->getCustomer($acc['data']['gen_info']['owner-id']);
                        $customer = json_encode($customer);
                        $customer = json_decode($customer, true); 
                        $response['result'][$key]['email'] = $customer['result']['data']['gen_info']['email'];   
                        }
                        else
                        {
                            $response['result'][$key]['email'] = '';
                        } 

                        $response['result'][$key]['startdate'] = $acc['data']['gen_info']['cr_date'];
                        $response['result'][$key]['domain'] = $acc['data']['gen_info']['name'];     
                        $plan = $this->getPlan($acc['data']['subscriptions']['subscription']['plan']['plan-guid']);
                        $plan = json_encode($plan);
                        $plan = json_decode($plan, true);
                        $response['result'][$key]['plan'] = $plan['name'];  
                        unset($response['result'][$key]['data']);
                        
                    }
             
                    $list = $response['result'];                    
                } 

            }
            catch (Exception $e) {
                $list = 'Error: '. $e->getMessage();  
            }  

          return $list;
    }




    function createSession($user, $server){

        $ip = base64_encode($_SERVER['REMOTE_ADDR']);
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                <packet>
                    <server>
                    <create_session>
                    <login>'.$user.'</login>
                    <data>
                        <user_ip>'.$ip.'</user_ip>
                        <source_server></source_server>
                    </data>
                    </create_session>
                </server>
                </packet>';

        $this->load->library('plesk/Plesk_exec', $server); 
        $res = $this->plesk_exec->request($xml);

        if(isset($res->server->create_session->result->errcode)){
            return $res->server->create_session->result->errtext;
        } 
        
        if(isset($res->server->create_session->result->id)){
            redirect('https://'.$server->hostname.':8443/enterprise/rsession_init.php?PLESKSESSID='.$res->server->create_session->result->id);
        }  
    }





    public function client_login ($params)
    {          
        $result = $this->createSession($params->account->username, $params->server);
        
        $res = json_decode(json_encode($result), true);
        $result .= $res[0];
        $this->session->set_flashdata('response_status', 'warning');
        $this->session->set_flashdata('message', $result);
        redirect($_SERVER['HTTP_REFERER']);
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




    public function admin_login ($server)
    { 
        $this->createSession($server->username, $server);
        
        $res = json_decode(json_encode($result), true);
        $result .= $res[0];
        $this->session->set_flashdata('response_status', 'warning');
        $this->session->set_flashdata('message', $result);
        redirect($_SERVER['HTTP_REFERER']);
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
