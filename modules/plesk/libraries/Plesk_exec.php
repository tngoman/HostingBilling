<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Plesk_exec 
{
    private $host;
    private $port;
    private $protocol;
    private $login;
    private $password;
    private $secretKey; 
 

    public function __construct($server)
    {                     
        $this->host = $server->hostname; 
        $this->port = $server->port;

        if(strlen($server->authkey) > 30){
            $this->secretKey = $server->authkey;
        }
        else {
            $this->login = $server->username;
            $this->password = $server->authkey;
            $this->secretKey = false;
        }

        $this->protocol = ($server->use_ssl == 'Yes') ? 'https' : 'http';
    }

 
    public function request($request)
    {        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "$this->protocol://$this->host:$this->port/enterprise/control/agent.php");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

        $res = curl_exec($curl);
        curl_close($curl); 

        $result = new SimpleXMLElement($res);
        return $result;
    }


    
    private function getHeaders()
    {
        $headers = array(
            "Content-Type: text/xml",
            "HTTP_PRETTY_PRINT: TRUE",
        );

        if ($this->secretKey) {
            $headers[] = "KEY: $this->secretKey";
        } else {
            $headers[] = "HTTP_AUTH_LOGIN: $this->login";
            $headers[] = "HTTP_AUTH_PASSWD: $this->password";
        }

        return $headers;
    }



    







    public function getUsage($domain){
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

        $res = $this->request($xml);

        if(isset($res->webspace->get->result->data)) {
            return $res->webspace->get->result->data;
        }

        return false;
    }



    public function getResellerUsage($user){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
     <packet>
        <reseller>
        <get>
            <filter>
               <login>'.$user.'</login> 
            </filter>           
            <dataset>
                <gen-info/>
                <stat/>
                <limits/>
            </dataset> 
        </get>
        </reseller>
     </packet>';

        $res = $this->request($xml);
        if(isset($res->reseller->get->result->data)) {
            return $res->reseller->get->result->data;
        }

        return false;
    }



 

    public function suspendAccount($domain){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                <packet>
                <webspace>
                    <set>
                        <filter>
                            <name>'.$domain.'</name>
                        </filter>
                        <values>
                            <gen_info>
                                <status>16</status>
                            </gen_info>
                        </values>
                    </set>
                </webspace>
                </packet>';

                $res = $this->request($xml);

                if(isset($res->webspace->set->result->errcode)){
                    return $res->webspace->set->result->errtext;
                } 
                
                if(isset($res->webspace->set->result->status)){
                    return $res->webspace->set->result->status;
                }  
    }



    public function unsuspendAccount($domain){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                <packet>
                <webspace>
                    <set>
                        <filter>
                            <name>'.$domain.'</name>
                        </filter>
                        <values>
                            <gen_info>
                                <status>0</status>
                            </gen_info>
                        </values>
                    </set>
                </webspace>
                </packet>';

         $res = $this->request($xml);

         if(isset($res->webspace->set->result->errcode)){
            return $res->webspace->set->result->errtext;
        } 
        
        if(isset($res->webspace->set->result->status)){
            return $res->webspace->set->result->status;
        }  
    }




    public function suspendReseller($user){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                <packet>
                <reseller>
                    <set>
                        <filter>
                            <login>'.$user.'</login>
                        </filter>
                        <values>
                            <gen_info>
                                <status>16</status>
                            </gen_info>
                        </values>
                    </set>
                </reseller>
                </packet>';

                $res = $this->request($xml);

                if(isset($res->reseller->set->result->errcode)){
                    return $res->reseller->set->result->errtext;
                } 
                
                if(isset($res->reseller->set->result->status)){
                    return $res->reseller->set->result->status;
                }  
    }



    public function unsuspendReseller($user){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                <packet>
                <reseller>
                    <set>
                        <filter>
                            <login>'.$user.'</login>
                        </filter>
                        <values>
                            <gen_info>
                                <status>0</status>
                            </gen_info>
                        </values>
                    </set>
                </reseller>
                </packet>';

         $res = $this->request($xml);

         if(isset($res->reseller->set->result->errcode)){
            return $res->reseller->set->result->errtext;
        } 
        
        if(isset($res->reseller->set->result->status)){
            return $res->reseller->set->result->status;
        }  
    }




    public function createSession($user, $host){

        $ip = base64_encode($SERVER['REMOTE_ADDR']);
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

                $res = $this->request($xml);

                if(isset($res->server->create_session->result->errcode)){
                    return $res->server->create_session->result->errtext;
                } 
                
                if(isset($res->server->create_session->result->id)){
                    redirect('https://'.$host.':8443/enterprise/rsession_init.php?PLESKSESSID='.$res->server->create_session->result->id);
                }  
    }



    public function changePassword($user, $new_password){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
                <packet>
                <customer>
                    <set>
                        <filter>
                            <login>'.$user.'</login>
                        </filter>
                        <values>
                            <gen_info>
                                <passwd>'.$new_password.'</passwd>
                            </gen_info>
                        </values>
                    </set>
                </customer>
                </packet>';
        $res = $this->request($xml);
   
        if(isset($res->customer->set->result->errcode)){
            return $res->customer->set->result->errtext;
        } 
        
        if(isset($res->customer->set->result->status)){
            return $res->customer->set->result->status;
        }  
    } 


    public function changePackage($domain, $new_plan_name){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <service-plan>
            <get>
                <filter>
                  <name>'.$new_plan_name.'</name>
               </filter>
            </get>
        </service-plan>
        </packet>';
        $res = $this->request($xml);
        if(!isset($res->{'service-plan'}->get->result->guid)) { 
            return 'New package name not found in server!';
        }

        $gid = ($res->{'service-plan'}->get->result->guid);
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
          <webspace>
            <switch-subscription>
                <filter>
                    <name>'.$domain.'</name>
               </filter>
            <plan-guid>'.$gid.'</plan-guid>
            </switch-subscription>
          </webspace>
        </packet>';
        $res = $this->request($xml);   
        return 'Package changed!';   
    }



    public function changeReseller($domain, $new_plan_name){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <reseller-plan>
            <get>
                <filter>
                  <name>'.$new_plan_name.'</name>
               </filter>
            </get>
        </reseller-plan>
        </packet>';
        $res = $this->request($xml);
        if(!isset($res->{'reseller-plan'}->get->result->guid)) { 
            return 'New package name not found in server!';
        }

        $gid = ($res->{'reseller-plan'}->get->result->guid);
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
          <reseller>
            <switch-subscription>
                <filter>
                    <name>'.$domain.'</name>
               </filter>
            <plan-guid>'.$gid.'</plan-guid>
            </switch-subscription>
          </reseller>
        </packet>';
        $res = $this->request($xml);   
        return 'Package changed!';   
    }




    public function createReseller($data){ 
 
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <reseller>
        <add>
            <gen-info>
                <pname>'.$data->profile->fullname.'</pname>
                <login>'.$data->plan->username.'</login>
                <passwd>'.$data->plan->password.'</passwd>
                <email>'.$data->plan->email.'</email>
            </gen-info>
           <plan-name>'.$data->plan->package.'</plan-name>
        </add>
        </reseller>
        </packet>';


        $res = $this->request($xml);

        
        if(isset($res->reseller->add->result->errcode)){
            return $res->reseller->add->result->errtext;
        }
         


        if(isset($res->reseller->add->result->status) && $res->reseller->add->result->status == 'ok'){
            $res->reseller->add->result->status;
        } 
    }




    public function removeAccount($domain){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <webspace>
        <del>
           <filter>
              <name>'.$domain.'</name> 
           </filter>
        </del>
        </webspace>
        </packet>';
        $res = $this->request($xml);

        if(isset($res->customer->set->result->errcode)){
            return $res->customer->set->result->errtext;
        } 
        
        if(isset($res->customer->set->result->status)){
            return $res->customer->set->result->status;
        }
    }
 



    public function removeReseller($login){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
        <packet>
        <reseller>
        <del>
           <filter>
              <login>'.$login.'</login> 
           </filter>
        </del>
        </reseller>
        </packet>';
        $res = $this->request($xml);

        if(isset($res->customer->set->result->errcode)){
            return $res->customer->set->result->errtext;
        } 
        
        if(isset($res->customer->set->result->status)){
            return $res->customer->set->result->status;
        }
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
        $res = $this->request($xml);

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
        $res = $this->request($xml);

        if(isset($res->customer->get->result->errcode)){
            return $res->customer->get->result->errtext;
        } 
      
            return $res->customer->get;        
    }
    
    

    
    public function getPlan($id){
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
        $res = $this->request($xml);
        if(isset($res->{'service-plan'}->get->result)) { 
            return $res->{'service-plan'}->get->result;
        }
      return false;
    }
          

}

/* End of file model.php */
