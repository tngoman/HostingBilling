<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Module {
 
    public static $instance;
    public static $modules; 
    public static $enabled_modules; 
    public static $actions = array(); 
    public static $current_action; 
    public static $run_actions = array();      
    private static $CI; 
    private static $PM; 
    private static $module_path; 
    private static $messages; 


    public function __construct()
    { 
        static::$CI =& get_instance(); 
        static::$instance = $this; 
        static::$CI->load->model('Plugin'); 
        static::$CI->load->helper('module'); 
        static::$PM = static::$CI->Plugin;

        static::$messages = array(
            'error' => [],
            'debug' => [],
            'warn'  => []
        ); 

        $this->set_module_dir(); 
        $this->get_modules(); 
        $this->include_enabled_modules(); 
        $this->load_enabled_modules();
    }

  

    private function set_module_dir()
    {
        $path = FCPATH . 'modules/'; 
        static::$module_path = str_replace('//','/',$path);
    } 


    public function view_controller($module, $module_data = NULL)
    { 
        
        if( ! method_exists($module, 'settings'))
        {
            $this->_error('error','Module Error',"The module {$module} does not have a controller", TRUE);
            return FALSE;
        } 

        $module = new $module;
 
        return call_user_func_array([$module, 'settings'], $module_data);
    }

    
    
    private function _error($message)
    { 
        array_push(static::$messages['error'], $message);
    }

    

    private function _debug($message)
    { 
        array_push(static::$messages['debug'], $message);
    }

 
    private function _warn($message)
    { 
        array_push(static::$messages['warn'], $message);
    }

     

    public function enable_module($module, $data = NULL)
    {  
            if($this->update_module_headers($module))
            { 
                if(method_exists(static::$modules[$module], 'install'))
                {
                   call_user_func(array(static::$modules[$module], 'install'), $data);
                }
            } 
             
         return static::$PM->set_status($module, 1);      
    }

     

    public function disable_module($module, $data=null)
    {  
        return static::$PM->set_status($module, 0); 
    }


     
    public function install_module($module, $data = NULL)
    {
    
        $system_name = strtolower($module); 
        $class_name = ucfirst($system_name); 
        $module_path = static::$module_path . "{$system_name}/controllers/{$system_name}.php"; 
        if( ! class_exists($class_name))
        { 
            if (file_exists($module_path))
            {
                if( ! include_once $module_path)
                {
                    $this->_error("Failed to install {$module}, there was an error loading the module file {$module_path}, is it readable?");
                }
                else
                {
                    $this->_debug("Successfully loaded the module file {$module_path}");
                }
            }
            else
            {
                $this->_error("Failed to install {$module}, unable to find the file {$module_path}");
            }
        }
 
        return call_user_func("{$class_name}::install", $data);
    }

     

    public function module_details($module)
    {
        return static::$PM->get_module($module);
    } 


    private function get_modules()
    {
        if( ! $modules = static::$PM->get_plugins())
        {
            return FALSE;
        }

 
        foreach($modules as $p)
        {  
            if( ! isset( static::$modules[ $p->system_name ] ) )
            {
                $this->_debug( "Adding module {$p->system_name}" );

                static::$modules[$p->system_name] = $p;

                if($p->status == '1')
                {
                    $this->_debug( "Enabling module {$p->system_name}" );

                    static::$enabled_modules[ $p->system_name ] = &static::$modules[$p->system_name];
                }
            }
        }
    }

     

    public function retrieve_modules()
    {
        return static::$PM->get_modules();
    }
 
    

    private function include_enabled_modules()
    {
        if(empty(static::$enabled_modules))
        {
            $this->_error("Unable to include enabled module files, enabled modules not retrieved");

            return FALSE;
        }

        foreach(static::$enabled_modules as $name => $p)
        { 
            $class = ucfirst($name);
            $module_path = static::$module_path . "{$name}/controllers/{$class}.php"; 
 
            if (file_exists($module_path))
            {
                if(include_once $module_path)
                { 
                    $this->_error("There was an error loading the module file {$module_path}");
                }
                else
                {
                    $this->_debug("Successfully loaded the module file {$module_path}");
                }
            }
            else
            {
                $this->_error("Failed to include the module {$name}, unable to find the file {$module_path}");
            }
        }
    }

    
    
    private function load_enabled_modules()
    {
   
        if(static::$enabled_modules)
        {
            foreach( static::$enabled_modules as $name => $p )
            {
                if(class_exists(ucfirst($name))) {
                    $name = ucfirst($name);
                    new $name;
                }
            }
        }
    }

    



    public function add_action($tag, $function, $priority = 10, $type = 'action')
    {
        if(is_array($function))
        {
            if(count($function) < 2)
            { 
                $function = $function[0];
            }
            elseif( ! is_object($function[0]))
            {
                $this->_error("Failing to add method '" . implode('::', $function) . "'' as {$type} to tag {$tag}, an array was given, first element was not an object");

                return FALSE;
            }
            elseif( ! method_exists($function[0], $function[1]))
            {
                $this->_error("Failing to add method '" . get_class($function[0]) . "::{$function[1]}' as {$type} to tag {$tag}, the method does not exist");

                return FALSE;
            }
        }

      
        if( ! is_array($function))
        {
            if( ! function_exists($function))
            {
                $this->_error("Failing to add function {$function} as {$type} to tag {$tag}, the function does not exist");

                return FALSE;
            }
        }

        if( ! in_array($type, ['action','filter']))
        {
            $this->_error("Unknown type '{$type}', must be 'filter' or 'action'");

            return FALSE;
        }

        static::$actions[$tag][$priority][] = array(
            'function' => $function,
            'type'  => $type
        );

        return TRUE;
    }

     

    public function add_filter($tag, $function, $priority = 10)
    {
        return $this->add_action($tag, $function, $priority, 'filter');
    }
 

    public function get_actions()
    {
        foreach(static::$actions as $k => $a)
            ksort(static::$actions[$k]);

        return static::$actions;
    }

    
    public function do_action($tag, array $args = NULL)
    {
        static::$current_action = $tag;

        array_push(static::$run_actions, $tag);

        if( ! isset(static::$actions[$tag]))
        {
            $this->_debug("No actions found for tag {$tag}");

            return $args;
        }

        ksort(static::$actions[$tag]); 

        foreach(static::$actions[$tag] as $actions)
        {
            foreach($actions as $a)
            { 
                if(is_array($a['function']))
                { 
                    if( ! method_exists($a['function'][0], $a['function'][1]))
                    {
                        $this->_error("Unable to execute method '" . get_class($a['function'][0]) . "::{$a['function'][1]}' for action {$tag}, the method doesn't exist");

                        return $args;
                    }
                }
                else
                {               
                    if( ! function_exists($a['function']))
                    {
                        $this->_error("Unable to execute function '{$a['function']}' for action {$tag}, the function doesn't exist");

                        return $args;
                    }
                }

                if($a['type'] == 'action')
                {
                     if( ! $args)
                    {
                        call_user_func( $a['function'] );
                    }
                    else
                    {
                        call_user_func_array( $a['function'], $args );
                    }
                }
                
                else
                {
                    if( ! $args)
                    {
                        $args = call_user_func( $a['function'] );
                    }
                    else
                    {
                        $args = call_user_func_array( $a['function'], $args );
                    }
                }
            }
        }

        static::$current_action = NULL;
 
        settype($args, gettype($args));

        return $args;
    }

  
    
    public function remove_action($tag, $function, $priority = 10)
    {
        if (isset(static::$actions[$tag][$priority][$function]))
        { 
            unset(static::$actions[$tag][$priority][$function]);
        }

        return TRUE;

    }

 
    

    public function current_action()
    {
        return static::$current_action;
    }


    

    public function has_run($action)
    {
        if (isset(static::$run_actions[$action]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
 



    public function update_module_headers($module)
    {
        if (isset(static::$modules[$module]))
        { 
            $arr = array();

            $module_data = file_get_contents(static::$module_path.$module."/controllers/".ucfirst($module).".php");  

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
            }

            if (isset($category[1]))
            {
                $arr['category'] = trim($category[1]);
            }

            if (isset($uri[1]))
            {
                $arr['uri'] = trim($uri[1]);
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
          
            if(empty($arr))
            {
                $this->_warn("Skipping header update for {$module}, no headers matched");
            }

        
            elseif(self::$PM->update_plugin_info($module, $arr))
            {  
                $this->_debug("Updated module headers for {$module}: " . serialize($arr));
            }
            else
            {
                $this->_error("Failed to update module headers for {$module}: " . serialize($arr));
            }
        }

        return TRUE;
    }

 
    

    public function update_all_module_headers()
    {
        if(empty(static::$modules))
        {
            $this->_warn("No modules to update headers for");

            return TRUE;
        }

        foreach(static::$modules as $name => $module)
        {
            $this->_debug("Updating module headers for {$name}");

            if( ! $this->update_module_headers($name))
            {
                return FALSE;
            }
        }

        return TRUE;
    }

 


    public function doing_action($action = NULL)
    {
        if(is_null($action))
        {
            return static::$current_action;
        }
        else
        {
            return $action === static::$current_action;
        }
    }

    


    public function did_action($tag)
    {
        return in_array($tag, static::$run_actions);
    }

 
    

    public function get_orphaned_modules()
    {
        $files = scandir(static::$module_path);

        $modules = static::$PM->get_modules();

        $orphaned = array();

        foreach($files as $f)
        {
            // Skip directories
            if(in_array($f, ['.','..'])) continue;

            if( ! isset($modules[$f]))
            {
                array_push($orphaned, $f);
            }
        }

        return (count($orphaned) > 0 ? $orphaned : FALSE);
    }

     
    public function get_messages($type = NULL)
    {
        if( ! $type)
        {
            return static::$messages;
        }
        elseif( ! isset(static::$messages[ strtolower($type) ]))
        {
            $this->_error("Failed to retrieve error type '{$type}', no such type found. Use 'error', 'warn' or 'debug'");

            return FALSE;
        }

        return static::$messages[strtolower($type)];
    }

   

    public function print_messages($type = NULL)
    {
        if($type)
        {
            if(@empty(static::$messages[ strtolower($type) ]) || ! isset(static::$messages[ strtolower($type) ]))
            {
                echo "{$type} IS EMPTY\n";
                return TRUE;
            }

            echo "<h3>Module Messages - <strong>" . ucfirst($type) . "</strong></h3>\n";

            echo "<ol>\n";

            foreach(static::$messages[ strtolower($type) ] as $m)
            {
                echo "<li>$m</li>\n";
            }

            echo "</ol>\n</hr>\n";

            return TRUE;
        }

        foreach(static::$messages as $type => $messages)
        {
            if(@empty($messages))
            {
                echo "{$type} IS EMPTY\n";
                continue;
            }

            echo "<h3>Module Messages - <strong>" . ucfirst($type) . "</strong></h3>\n";

            echo "<ol>\n";

            foreach($messages as $m)
            {
                echo "<li>$m</li>\n";
            }

            echo "</ol>\n</hr>\n";
        }

        return TRUE;
    }
}