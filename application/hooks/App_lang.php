<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Author Message
|--------------------------------------------------------------------------
|
| Set the default App Language
| 
*/


  //Loads configuration from database into global CI config
   function set_lang()
   {
    $CI =& get_instance();
    $system_lang = $CI->Inithook->get_lang();
 
    $CI->config->set_item('language', $system_lang);
    
    $CI->lang->load('hd', $system_lang ? $system_lang : 'english');
 
    date_default_timezone_set($CI->config->item('timezone'));
    
    // Load plugin translations
    // $plugins = $CI->db->get('plugins')->result();
    // foreach($plugins as $plugin) {
    //     $CI->lang->load($plugin->route, $system_lang ? $system_lang : 'english', FALSE, TRUE, '', $plugin->route);
    // }
    
   }