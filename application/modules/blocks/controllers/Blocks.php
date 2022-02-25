<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

(defined('EXT')) OR define('EXT', '.php');

class Blocks extends Hosting_Billing  { 

	function __construct()
	{		
		parent::__construct();	
        User::logged_in();
        $this->load->module('layouts');      
        $this->load->library('template');
        $this->load->model(array('App', 'Block', 'Page')); 
        $this->load->helper(array('file'));         
    }


 function index()
	{
        
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	

        $blocks = array();        
        $path    = APPPATH.'modules/';
        $modules = scandir($path);
        $modules = array_diff(scandir($path), array('.', '..'));
        foreach($modules as $module) {
          if(is_dir(APPPATH.'modules/'.$module.'/views')) {
            $views = scandir(APPPATH.'modules/'.$module.'/views/');
            foreach($views as $view) {
                $name = explode('.', $view);
                $str = $name[0];
                $arr = explode('_', $str);
                if($arr[0] == $module && $arr[count($arr) - 1] == 'block')
                 {
                     $data = read_file(APPPATH.'modules/'.$module.'/views/'.$view);
                     $mod = array('id' => implode('_', $arr), 'name' => ucfirst(implode(' ', array_slice($arr, 1, -1))), 'type' => 'Module', 'module' => ucfirst($module));
                     $blocks[] = (object) $mod;
                 }  
              }
           }          
        }
     
        $custom_blocks = $this->db->get('blocks_custom')->result();
        $module_blocks = $this->db->get('blocks_modules')->result();
        $blocks = array_merge($custom_blocks, $blocks, $module_blocks);
        $sections = $this->db->select('id, section')->get('blocks')->result();

        $this->template->title(lang('blocks'));
        $data = array();
        $data['page'] = lang('blocks');	
        $data['blocks'] = $blocks;
        $data['sections'] = $sections;
        $data['datatables'] = TRUE;   
        $this->template
        ->set_layout('users')
        ->build('index',isset($data) ? $data : NULL);       
    }

 

    function add()
    {
		if ($this->input->post()) {
            Applib::is_demo();
                    if($this->input->post('format') == 'rich_text') {
                        $block = array('name' => $this->input->post('name'), 'code' => $this->input->post('content', false), 'format' => $this->input->post('format'));	 
                        if($this->db->insert('blocks_custom', $block)) {
                            Applib::go_to('blocks','success',lang('block_created'));
                        }
                    }
                    else 
                    {
                        $sql = "INSERT INTO hd_blocks_custom (name, code, format) 
                        VALUES('".$this->input->post('name')."', '".$this->db->escape_str($this->input->post('content'))."', '".$this->input->post('format')."')";
                        if ($this->db->simple_query($sql))
                            {
                                Applib::go_to('blocks','success',lang('block_created'));
                            }
                            else
                            {
                                Applib::go_to('blocks','error', $this->db->error());
                            }
                    }
                } 
                else {      
                    $data['editor'] = true;   
                    $this->template->title(lang('new_block')); 
                    $data['page'] = lang('add_block');
                    $this->template
                    ->set_layout('users')
                    ->build('add',isset($data) ? $data : NULL);
                }            
    }





    function add_code()
    {
        if(config_item('allow_js_php_blocks') == "TRUE") {
            $this->template->title(lang('new_block')); 
            $data['page'] = lang('add_block');
            $this->template
            ->set_layout('users')
            ->build('add_code',isset($data) ? $data : NULL); 
        }
        else {
            redirect($_SERVER['HTTP_REFERER']);
        }        
    }


    

    function edit($id = null)
    {
        
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	
		if ($this->input->post()) {
            Applib::is_demo();
            if($this->input->post('format') == 'rich_text') {
                $block = array('name' => $this->input->post('name'), 'code' => $this->input->post('content', false), 'format' => $this->input->post('format'));	 
                $this->db->where('id', $this->input->post('id'));
                if($this->db->update('blocks_custom', $block)) {
                    Applib::go_to('blocks','success',lang('block_updated'));
                }
            }
            else 
            {
                $sql = "UPDATE hd_blocks_custom SET 
                name = '".$this->input->post('name')."', 
                code = '".$this->db->escape_str($this->input->post('content'))."',
                format = '".$this->input->post('format')."' 
                WHERE id = '".$this->input->post('id')."'";
                if ($this->db->simple_query($sql))
                    {
                        Applib::go_to('blocks','success',lang('block_updated'));
                    }
                    else
                    {
                        Applib::go_to('blocks','error', $this->db->error());
                    }
            }
        }  else {
            $data['block'][] = $this->Block->get_block($id);
            $view = ($data['block'][0]->format == 'rich_text') ? 'edit' : 'edit_code';
			$this->template->title(lang('edit_block')); 
            $data['page'] = lang('edit_block');
            $data['editor'] = true; 
            $this->template
            ->set_layout('users')
            ->build($view, isset($data) ? $data : NULL); 
            }
	}




    function delete($id = null)
    { 
        
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	

		if ($this->input->post() ){
            Applib::is_demo();
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            if($this->db->delete('blocks_custom')){ 

                $block = $this->db->where('id', $this->input->post('id'))->get('blocks')->row();
                if($block) {
                    $this->db->where('id', $this->input->post('id'))->delete('blocks');
                    $this->db->where('block_id', $block->block_id)->delete('blocks_pages');
                }
                Applib::go_to('blocks','success',lang('block_deleted'));
             }
        }
        else {
            $this->template->title(lang('delete_block')); 
            $data['page'] = lang('delete_block');	
			$data['id'] = $id;
			$this->load->view('modal/delete', $data);
		}
    }




    function configure($id = null)
    {
        
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	
        
		if ($this->input->post()) { 
            Applib::is_demo();
            $block_id = 0;
            $block = $this->db->where('id', $this->input->post('id'))
            ->where('theme', config_item('active_theme'))
            ->where('module', $this->input->post('module'))
            ->get('blocks')->row(); 
            
            if($this->input->post('section') == '') {
                $this->db->where('id', $this->input->post('id'))->where('module', $this->input->post('module'))->where('theme', config_item('active_theme'))->delete('blocks');
                if($block) {
                    $this->db->where('block_id', $block->block_id)->where('module', $this->input->post('module'))->where('theme', config_item('active_theme'))->delete('blocks_pages');
                }
            }

            else {
                    if(!$block)
                    { 
                        $data = array (
                            'type' => $this->input->post('type'),
                            'name' => $this->input->post('name'),
                            'id' => $this->input->post('id'),
                            'theme' => config_item('active_theme'),
                            'module' => $this->input->post('module'),
                            'section' => $this->input->post('section'),
                            'weight' => $this->input->post('weight')
                        );              
                        $this->db->insert('blocks', $data);
                        $block_id = $this->db->insert_id();
                    }

                    else {
                        $data = array (   
                            'section' => $this->input->post('section'),
                            'weight' => $this->input->post('weight')
                        );
                        $this->db->where('id', $this->input->post('id'));
                        $this->db->where('module', $this->input->post('module'));
                        $this->db->where('theme', config_item('active_theme'));
                        $this->db->update('blocks', $data);
                        $block_id = $block->block_id;
                    }
                }

                $this->db->where('block_id', $block_id)->where('theme', config_item('active_theme'))->where('module', $this->input->post('module'))->delete('blocks_pages');
                $pages = $this->input->post('pages');
                if($this->input->post('section') != '') 
                {
                    if(is_array($pages) && count($pages) > 0)
                    {
                        foreach($pages as $page) {
                            $data = array (
                                'block_id' => $block_id,
                                'page' => $page,
                                'mode' => $this->input->post('mode'),
                                'module' => $this->input->post('module'),
                                'theme' => config_item('active_theme')
                            );              
                            $this->db->insert('blocks_pages', $data);
                        }
                    }                   

                    else {                    
                        $data = array (
                            'block_id' => $block_id,
                            'page' => 'all',
                            'mode' => $this->input->post('mode'),
                            'module' => $this->input->post('module'),
                            'theme' => config_item('active_theme')
                        );              
                        $this->db->insert('blocks_pages', $data);
                    }
                }

                if($this->input->post('section') != '' && is_array($pages) && count($pages) == 0) {
                    $data = array (
                        'block_id' => $block_id,
                        'page' => 'all',
                        'mode' => $this->input->post('mode'),
                        'module' => $this->input->post('module'),
                        'theme' => config_item('active_theme')
                    );              
                    $this->db->insert('blocks_pages', $data);
                }

                if($this->input->post('type') == 'Module') 
                {
                    $settings = array('settings' => serialize(array('title' => $this->input->post('title')))); 
                    
                    App::update('blocks_modules', array('param' => $this->input->post('id')), $settings);
                }                

                Applib::go_to('blocks','success',lang('block_updated'));
            }

        else {

                $id_array = explode('_', $id, 2);
                $blocks = array(); 
                if(is_numeric($id_array[1])) 
                    {
                        if($id_array[0] == 'block') 
                        {
                            $block = $this->db->where('id', $id_array[1])->get('blocks_custom')->row();
                        }
                        else                
                        {
                            $block = $this->db->where('param', $id)->get('blocks_modules')->row();
                        }                
                    }            
                
                    else {   
                
                        if(is_dir(APPPATH.'modules/'.$id_array[0].'/views')) {
                            $views = scandir(APPPATH.'modules/'.$id_array[0].'/views/');
                            foreach($views as $view) {
                                $name = explode('.', $view);
                                $str = $name[0];  
                                if($str == $id) {
                                    $mod = array('id' => $id, 'name' => ucfirst(implode(' ', array_slice($id_array, 1, -1))), 'type' => 'Module', 'module' => ucfirst($id_array[0]));
                                    $block = (object) $mod;                           
                                }
                            }          
                        }
                    }

                
                if(is_dir(active_theme().'blocks/')) {
                    $views = scandir(active_theme().'blocks/');
                    foreach($views as $view) { 
                        $data = read_file(active_theme().'blocks/'.$view);
                        preg_match ('|Name:(.*)$|mi', $data, $name);
                        if(count($name) > 0) {
                            $blocks[] = (object) array('name' => trim($name[1]), 'section' => explode('.',$view)[0]);
                        }                   
                    }
                }     

                $full_id = explode('_', $id);
                $filter = ($full_id[0] == 'block') ? $full_id[1] : $id;
                $this->db->select('blocks.*, blocks_pages.*');
                $this->db->join('blocks_pages', 'blocks_pages.block_id = blocks.block_id', 'INNER');
                $this->db->where('blocks.id', $filter);
                $this->db->where('blocks.theme', config_item('active_theme'));
                $config = $this->db->get('blocks')->result(); 
                
                $data = array();
                $this->template->title(lang('configure')); 
                $data['page'] = lang('configure').' '.lang('block');
                $data['pages'] = $this->Page->get_pages(); 
                $data['blocks']	= $blocks;
                $data['config']	= $config;
                $data['_block']	= $block;             
                $data['id'] = $id;
                $this->load->view('modal/configure', $data);
            }
        } 
  

}

/* End of file Blocks.php */