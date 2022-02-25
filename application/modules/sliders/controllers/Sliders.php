<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Sliders extends Hosting_Billing 
{
	function __construct()
	{		
		parent::__construct();	
        User::logged_in();
 
        $this->load->module('layouts');
        $this->load->model('Slider');		
		$this->load->library(array('template','form_validation'));
    }
    


	function index($id = null)
	{		
        
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	
        $this->template->title(lang('sliders'));
        $data['page'] = lang('sliders');	
        $data['datatables'] = TRUE;
        $data['sliders'] = $this->Slider->get_sliders();

        $this->template
        ->set_layout('users')
        ->build('index',isset($data) ? $data : NULL);       
    }

    

    function slider($id)
	{	 
        
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	
        $this->template->title(lang('sliders'));
        $data['page'] = lang('sliders');	
        $data['datatables'] = TRUE;
        $data['slider'] = $this->Slider->get_slides($id);
        $data['slider_id'] = $id;
        $this->template
        ->set_layout('users')
        ->build('slider',isset($data) ? $data : NULL); 
    }


    function sliders_block($id)
	{   
        $data['slider'] = $this->Slider->get_slides($id);
        $this->load->view('slider_block', $data);
    }
    


    function add()
    {
		if ($this->input->post()) { 
            $slider = array('name' => $this->input->post('name'));
	 
				if($this->db->insert('slider', $slider)){
                    $slider_id = $this->db->insert_id();
                    $data = array (
                        'name' => $this->input->post('name'),
                        'param' => "sliders_".$slider_id,
                        'type' => 'Module',
                        'module' => 'Sliders'
                    ); 
                    $this->db->insert('blocks_modules', $data);
					Applib::go_to('sliders','success',lang('slider_created'));
                }
		}else{            
			$this->load->view('modal/add_slider');
		}
    }

    

    function edit($id = null)
    {
		if ($this->input->post()) { 
            Applib::is_demo();
            $slider = array('name' => $this->input->post('name'));
            $this->db->where('slider_id', $this->input->post('slider_id'));
                if($this->db->update('slider', $slider)){

                    if($this->db->where('param', "sliders_".$this->input->post('slider_id'))->where('module','Sliders')->get('blocks_modules')->num_rows() == '0'){
                        $data = array (
                            'name' => $this->input->post('name'),
                            'param' => "sliders_".$this->input->post('slider_id'),
                            'type' => 'Module',
                            'module' => 'Sliders'
                        ); 
                        $this->db->insert('blocks_modules', $data);
                    }
                    else 
                    {
                        $data = array (
                            'name' => $this->input->post('name')
                        );
                        $this->db->where('param', "sliders_".$this->input->post('slider_id'));
                        $this->db->where('module', 'Sliders');
                        $this->db->update('blocks_modules', $data);
                    }

                    Applib::go_to('sliders','success',lang('slider_updated'));
                } 
		} else{
			$data['slider'] = Slider::get_slider($id);
			$this->load->view('modal/edit_slider', $data);
		}
	}




    function delete()
    { 
        
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	

		if ($this->input->post() ){
            Applib::is_demo();
            $id = $this->input->post('slider_id');
            $this->db->where('slider_id', $id);
            if($this->db->delete('slider')){ 
                $this->db->where('param', "sliders_".$id)->where('module', 'Sliders')->delete('blocks_modules');
                $block = $this->db->where('id', "sliders_".$id)->where('module', 'Sliders')->get('blocks')->row();
                    if($block) {
                        $this->db->where('id', "sliders_".$id)->where('module', 'Sliders')->delete('blocks');
                        $this->db->where('block_id', $block->block_id)->delete('blocks_pages');
                    } 
                $slides = $this->db->where('slider', $id)->get('sliders')->result();
                foreach($slides AS $slide) {
                    $fullpath = './resource/uploads/'.$slide->image; 
                    if (file_exists($fullpath)) {
                        unlink($fullpath);
                    }
                }
                $this->db->where('slider', $id);
                $this->db->delete('sliders');
                Applib::go_to('sliders','success',lang('slider_deleted'));
             }
        }
        else {
			$data['slider_id'] = $this->uri->segment(3);
			$this->load->view('modal/delete_slider',$data);
		}
    }
    


    function add_slide($id = null) 
    {
        if ($this->input->post()) { 
            $data = array();        
            
            if(count($this->input->post('images') > 0)) {
                $config['upload_path'] = './resource/uploads';
                $config['allowed_types'] = 'gif|png|jpeg|jpg';
                $config['max_size'] = config_item('file_max_size');
                $config['overwrite'] = false;
    
                $this->load->library('upload');
    
                $this->upload->initialize($config);
    
                if (!$this->upload->do_multi_upload('images')) {
                    Applib::make_flashdata(array(
                    'response_status' => 'error',
                    'message' => lang('operation_failed'),
                    'form_error' => $this->upload->display_errors('<span class="text-danger">', '</span><br>'),
                    ));
                    redirect('sliders/slider/'.$slider);
                } 
                
                else {
                    $fileinfs = $this->upload->get_multi_upload_data();                        
                    foreach ($fileinfs as $findex => $fileinf) {
                        $data['image'] = $fileinf['file_name'];                                          
                    }
                }
            } 
           
            $data['slider'] = $this->input->post('slider');
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');
           
            $slide_id = App::save_data('sliders', $data);
            Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('slide_created'));
           
        } 
        
        else {
            $data['slider_id'] = $id; 
            $this->load->view('modal/add_slide', $data);
        }
        // End file add
    }




    function edit_slide($id = null) 
    {
        
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	

        if ($this->input->post()) {
            Applib::is_demo();
            $data = array();  

            if($_FILES['images']['size'] == 0) {
                $config['upload_path'] = './resource/uploads';
                $config['allowed_types'] = 'gif|png|jpeg|jpg';
                $config['max_size'] = config_item('file_max_size');
                $config['overwrite'] = false;
    
                $this->load->library('upload');
    
                $this->upload->initialize($config);
    
                if (!$this->upload->do_multi_upload('images')) {
                    Applib::make_flashdata(array(
                    'response_status' => 'error',
                    'message' => lang('operation_failed'),
                    'form_error' => $this->upload->display_errors('<span class="text-danger">', '</span><br>'),
                    ));
                    redirect('sliders/slider/'.$slider);
                } 
                
                else {
                    $fileinfs = $this->upload->get_multi_upload_data();
                      
                    foreach ($fileinfs as $findex => $fileinf) {
                        $data['image'] = $fileinf['file_name'];                                          
                    }

                    $fullpath = './resource/uploads/'.$this->input->post('current_image'); 
                    if (file_exists($fullpath)) {
                        unlink($fullpath);
                    }

                }
            } 
           
            $data['title'] = $this->input->post('title');
            $data['description'] = $this->input->post('description');

            $entry = array('slide_id' => $this->input->post('slide_id'));

            App::update('sliders',$entry, $data);
            Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('slide_updated'));
        } 
        
        else {
            $data['slide'] = Slider::get_slide($id);; 
            $this->load->view('modal/edit_slide', $data);
        }
        // End file add
    }




    function delete_slide($id = null) 
    {
        
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('access_denied'));
        }	
        
        if ($this->input->post()) {
            Applib::is_demo();
            $data = array(); 

            $this->db->where('slide_id', $this->input->post('slide_id'));
            if($this->db->delete('sliders')) {
                $fullpath = './resource/uploads/'.$this->input->post('current_image'); 
                if (file_exists($fullpath)) {
                    unlink($fullpath);
                } 
            }

            Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('slide_deleted!'));

           }
        else {
            $data['slide'] = Slider::get_slide($id);; 
            $this->load->view('modal/delete_slide', $data);
        }
        // End file add
    }



}

/* End of file Sliders.php */