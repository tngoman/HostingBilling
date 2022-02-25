<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// Stores the requested URL, which will sometimes be different than previous url 
$hook['pre_controller'][] = array(
                                         'class'         => 'App_hooks',
                                         'function'      => 'save_requested',
                                         'filename'      => 'App_hooks.php',
                                         'filepath'      => 'hooks',
                                         'params'        => ''
                                                        );

// Allows us to perform good redirects to previous pages.
$hook['post_controller'][] = array(
                                         'class'         => 'App_hooks',
                                         'function'      => 'prep_redirect',
                                         'filename'      => 'App_hooks.php',
                                         'filepath'      => 'hooks',
                                         'params'        => ''
                                                        );

                                                        
// Load Config from DB
$hook['pre_controller'][] = array(
                                        'class'    => '',
                                        'function' => 'load_config',
                                        'filename' => 'App_config.php',
                                        'filepath' => 'hooks'
                                                        );
// Load the DB Language
$hook['pre_controller'][] = array(
                                        'class'    => '',
                                        'function' => 'set_lang',
                                        'filename' => 'App_lang.php',
                                        'filepath' => 'hooks'
                                                        );

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */