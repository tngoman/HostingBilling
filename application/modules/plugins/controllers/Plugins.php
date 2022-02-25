<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plugins extends Hosting_Billing 
{

    private $_plugins;

    public function __construct()
    {
        parent::__construct();
        $this->load->module('layouts');
        $this->load->library(array('template','settings'));  
        $this->load->model(array('Plugin', 'Update')); 
        $this->load->helper('form');
        $this->_plugins = $this->Plugin->get_plugins();
        $this->module->update_all_module_headers();
    }

    
    public function index()
    {
        $data = array();
        $data['plugins'] = $this->Plugin->get_plugins(); 
        $this->template->title(lang('plugins').' - '.config_item('company_name'));
        $data['page'] = lang('plugins'); 
        $data['datatables'] = true;
        $this->template
        ->set_layout('users')
        ->build('plugin_list',isset($data) ? $data : NULL);	 
    }


    public function config()
    {
        if($this->input->post())
        { 
            Applib::is_demo();
            Applib::update('plugins',
		    array('plugin_id' => $this->input->post('id')), array('config' => serialize($this->input->post())));
                $this->session->set_flashdata('response_status', 'success');
				$this->session->set_flashdata('message', ucfirst($this->input->post('system_name')) . ' ' . lang('settings_updated'));
				redirect($_SERVER['HTTP_REFERER']); 
             
        }
        else
        {
            
            $data = array();

            if( ! $plugin = $this->input->get('plugin'))
            {
                redirect('/');
            }
            elseif( ! isset($this->_plugins[$plugin]))
            {
                die("Unknown plugin {$plugin}");
            }
            elseif($this->_plugins[$plugin]->status != 1)
            {
                die("The plugin {$plugin} isn't enabled");
            }

            $data['config'] = Plugin::get_plugin($plugin); 
            $data['plugin'] = $plugin;
            $this->load->view('modal/settings', $data);  
        }
    }



    public function activate($module)
    {
        if(enable_module($module))
        {
            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', 'Enabled');				
            redirect($_SERVER['HTTP_REFERER']);
        }	 
    }



    public function deactivate($module)
    {
        if(disable_module($module))
        {
            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', 'Disabled');				
            redirect($_SERVER['HTTP_REFERER']);
        }  
    }



    public function uninstall($plugin)
    {
        if(Plugin::reset_settings($plugin))
        {
            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', 'Plugin settings removed');
        }   
        else
        {
            $this->session->set_flashdata('response_status', 'success');
            $this->session->set_flashdata('message', 'Error occurred!');
        }  
        
        redirect($_SERVER['HTTP_REFERER']); 
    }

     

    public function upload(){
    
        if ($this->input->post()) { 
            
            echo 6666;
            die;

            $this->load->library('Files');
            $main_path = FCPATH . "/resource/uploads/plugins";
            $upload_data = $this->files->upload_files('plugin_file', $main_path);
            echo 6666;
            die;

            $zip = $main_path . "/" . $upload_data['file_name'];
            $unzip_path = $main_path . "/" . $upload_data['raw_name']; 
            $controller_path = $unzip_path . "/" . $upload_data['raw_name'] . "/controllers";
            $model_path = $unzip_path . "/" . $upload_data['raw_name'] . "/models";
            $sql = $unzip_path . "/" . $upload_data['raw_name'] . ".sql";
            $plugin_module_path = $unzip_path . "/" . $upload_data['raw_name'];
            $js = $unzip_path . "/" . $upload_data['raw_name'] . ".js";
            $css = $unzip_path . "/" . $upload_data['raw_name'] . ".css";
            $js_path = FCPATH ."/resource/js/" . $upload_data['raw_name'] . ".js";
            $css_path = FCPATH ."/resource/css/" . $upload_data['raw_name'] . ".css";

            //unzip plugin
            if (strlen($upload_data['file_name']) > 0) {
                $this->load->library('Unzip');
                $this->unzip->extract($zip, $unzip_path);
                $this->unzip->close();
                //check if xml exists
                if ($this->files->check_if_file_exists($xml)) {
                    //check if xml format is good
                    $main_elms = array('name', 'description', 'defaultcontroller', 'defaultbackendmethod', 'version', 'developer', 'developercontact');
                    if ($this->files->is_main_xml_correct($xml, $main_elms)) {
              
                     
   
                       // $plugin_id = $this->plugin_model->add_plugin($plugin_data);
                        //check controller
                        if (!$this->files->is_dir_empty($controller_path)) {
                          //check model
                           
                            //check if admin menu exists
                            if ($this->files->if_node_exits($xml, 'admin_menu')) {        
                                                        
                                //move files to modules
                                $this->files->move_files($plugin_module_path, $module_path);
                                //move js
                                if ($this->files->check_if_file_exists($js))
                                    $this->files->move_files($js, $js_path);
                                //move css
                                if ($this->files->check_if_file_exists($css))
                                    $this->files->move_files($css, $css_path);
                                if (strlen($upload_data['raw_name']) > 0)
                                    $this->files->delete_directory($unzip_path);
                                if ($this->files->check_if_file_exists($zip))
                                    $this->files->delete_file($zip);

                                $this->index("Plugin has been successfully installed.", 'info');
                            } else {
                                if (strlen($upload_data['raw_name']) > 0)
                                    $this->files->delete_directory($unzip_path);
                                //delete zip
                                if ($this->files->check_if_file_exists($zip))
                                    $this->files->delete_file($zip);
                                //delete js
                                if ($this->files->check_if_file_exists($js_path))
                                    $this->files->delete_file($js_path);
                                //delete css
                                if ($this->files->check_if_file_exists($css_path))
                                    $this->files->delete_file($css_path);
                                //show message in view
                                $this->index("Sorry, Plugin has not been installed. Admin menu was not found", 'error');
                            }

                        } else {
                            if (strlen($upload_data['raw_name']) > 0)
                                $this->files->delete_directory($unzip_path);
                            //delete zip
                            if ($this->files->check_if_file_exists($zip))
                                $this->files->delete_file($zip);
                            //delete js
                            if ($this->files->check_if_file_exists($js_path))
                                $this->files->delete_file($js_path);
                            //delete css
                            if ($this->files->check_if_file_exists($css_path))
                                $this->files->delete_file($css_path);
                            //show message in view
                            $this->index("Sorry, Plugin has not been installed. Kindly check whether the controller exists", 'error');
                        }
                    } else {

                        //delete folder
                        $this->files->delete_directory($unzip_path);
                        //delete zip
                        if ($this->files->check_if_file_exists($zip))
                            $this->files->delete_file($zip);
                        //delete js
                        if ($this->files->check_if_file_exists($js_path))
                            $this->files->delete_file($js_path);
                        //delete css
                        if ($this->files->check_if_file_exists($css_path))
                            $this->files->delete_file($css_path);

                        //show message in view
                        $this->index("Sorry, Plugin has not been installed. The " . $upload_data['raw_name'] . ".xml file is missing a few key elements", 'error');
                    }

                } else {
                    //delete folder
                    if (strlen($upload_data['raw_name']) > 0)
                        $this->files->delete_directory($unzip_path);
                    //delete zip
                    if ($this->files->check_if_file_exists($zip))
                        $this->files->delete_file($zip);
                    //delete js
                    if ($this->files->check_if_file_exists($js_path))
                        $this->files->delete_file($js_path);
                    //delete css
                    if ($this->files->check_if_file_exists($css_path))
                        $this->files->delete_file($css_path);
                    //show message in view
                    $this->index("Sorry, Plugin has not been installed. Kindly check whether " . $upload_data['raw_name'] . ".xml exists", 'error');
                }
            } else {
                $this->index("Sorry, Plugin has not been installed. Kindly upload the plugin zip file", 'error');
            }
        } 

    }


    public function download()
    {        
        $this->template->title(lang('plugins').' - '.config_item('company_name'));
        $data['page'] = lang('plugins'); 
        $data['datatables'] = true;
        $data['plugins'] = Plugin::active_plugins();
        $this->template->set_layout('users')->build('download',isset($data) ? $data : NULL);	
    }


    function install($id)
    {  
      $this->template->title('Update '.config_item('company_name'));
      $data['page'] = 'Update Information';      
      $data['version_notifications_array'] = $this->process($id);
      $data['status'] = '';
      $this->template
      ->set_layout('users')
      ->build('install',isset($data) ? $data : NULL);		
    }


    function installed()
    {  
        redirect('plugins'); 	
    }


    function version($id)
    {   
       $this->template->title('Update '.config_item('company_name'));
      $data['page'] = 'Version Information';  
      $data['version_notifications_array'] = Update::version($id); 
      $data['version'] = $id;
      $this->template
      ->set_layout('users')
      ->build('version',isset($data) ? $data : NULL);		
    }


    function process($id)
    {
      $response = Update::install($id);

      if($response['notification_case'] == "notification_operation_ok")
        {
          $database = Update::database($id);

          if ($database['notification_case'] == "notification_operation_ok")  
            {
                $sql = trim($database['notification_data']);
                if($sql != '')
                {
                  $sqls = explode(';', $sql);
                  array_pop($sqls);

                  $this->db->trans_start();
                  foreach($sqls as $statement)
                  {
                      $statment = trim($statement) . ";";
                      $this->db->query($statement);   
                  }
                  $this->db->trans_complete(); 
                }               
            }
        }

        return $response;
    }


    public function add_plugin(){
        $this->load->view('modal/add_plugin'); 
    }


}
