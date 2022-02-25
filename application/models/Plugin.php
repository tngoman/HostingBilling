<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 

class Plugin extends CI_Model
{    
    private static $CI;
    private static $db;
    private static $path;


    function __construct()
    {
        parent::__construct();
        static::$CI =& get_instance();        
        static::$path = FCPATH.'/modules';
        self::$db = static::$CI->db;          
    }


    function modules()
    {
        $glob = glob(static::$path . '/*');

        if($glob === false)
        {
            return array();
        }

        return array_filter($glob, function($dir) {
            return is_dir($dir);
        });
    }

 

    public function get_plugins()
    { 
        
        $plugins = array();
        $return = array();   
        
        $this->load->helper(array('file')); 
        $query = self::$db->get('plugins');
        $result = $query->result();      

        foreach($this->modules() as $module) {
            $module =  explode('/', $module);
            $module = $module[count($module) -1]; 
          if(is_dir(static::$path.'/'.$module.'/controllers')) {
                $controller = static::$path.'/'.$module.'/controllers/'. ucfirst($module). '.php';
                $module_data = read_file($controller);
                preg_match ('|Module Name:(.*)$|mi', $module_data, $name);
                preg_match ('|Category:(.*)$|mi', $module_data, $category);
                preg_match ('|Module URI:(.*)$|mi', $module_data, $uri);
                preg_match ('|Version:(.*)|i', $module_data, $version);
                preg_match ('|Description:(.*)$|mi', $module_data, $description);
                preg_match ('|Author:(.*)$|mi', $module_data, $author_name);
                preg_match ('|Author URI:(.*)$|mi', $module_data, $author_uri);

                if (isset($name[1]))
                {
                    $arr['name'] = trim($name[1]);
                    $name = strtolower(str_replace(' ','_', $arr['name'])); 
                    $arr['system_name'] = $name;
                    foreach($result as $r)
                    {  
                        if($name == $r->system_name) 
                        {
                            $arr['status'] = $r->status;                            
                        }                
                    }
                }

                if(!isset($arr['status']))
                {
                    $arr['status'] = 0;
                    $arr['installed'] = 0;
                }

                else
                {
                    $arr['installed'] = 1;
                } 

                if (isset($uri[1]))
                {
                    $arr['uri'] = trim($uri[1]);
                }

                if (isset($category[1]))
                {
                    $arr['category'] = trim($category[1]);
                }

                if (isset($version[1]))
                {
                    $arr['version'] = trim($version[1]);
                }

                if (isset($description[1]))
                {
                    $arr['description'] = trim($description[1]);
                }

                if (isset($author_name[1]))
                {
                    $arr['author'] = trim($author_name[1]);
                }

                if (isset($author_uri[1]))
                {
                    $arr['author_uri'] = trim($author_uri[1]);
                }
                
                $return[$arr['system_name']] = (object) $arr;             
            }
        }
         

        return $return;
    }

    


    public function update_plugin_info($plugin, array $settings)
    { 
        if(self::$db->where('system_name', $plugin)->get('plugins')->num_rows() > 0)
        {
            return self::$db->where('system_name', $plugin)->update('plugins', $settings);
        } 
        else
        {
            $settings['system_name'] = strtolower(str_replace(' ','_', $settings['name']));
            self::$db->insert('plugins', $settings);
        }       
    }


    

    public function set_status($plugin, $status)
    {
        log_message("error","PLUGIN: $plugin; STATUS: $status");

        if( ! self::$db
            ->where('system_name', $plugin)
            ->update('plugins', ['status' => $status]))
        {
            return FALSE;
        }

        return TRUE;
    }


    static function reset_settings($plugin)
    {
        if(self::$db->where('system_name', $plugin)->update('plugins', ['config' => '']))
        {
            return true;
        }
        return false;
    }
   
    
    static function get_plugin($plugin)
    {
        $query = self::$db->get_where('plugins', ['system_name' => $plugin]);

        $result = $query->result();

        return ( ! @empty($result[0]) ? $result[0] : FALSE);
    }


    static function active_plugins()
    {
        return self::$db->select( 'plugin_id, system_name, version, category')->where('status', 1)->get('plugins')->result();
    }


    static function payment_gateways()
    {
        return self::$db->where('category', 'Payment Gateways')->where('status', 1)->get('plugins')->result();
    }


    static function domain_registrars()
    {
        return self::$db->where('category', 'Domain Registrars')->where('status', 1)->get('plugins')->result();
    }


    static function servers()
    {
        return self::$db->where('category', 'Servers')->where('status', 1)->get('plugins')->result();
    }

}
