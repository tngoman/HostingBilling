<?php

class Block extends CI_model
{
    private static $db;

    function __construct()
    {
		parent::__construct();
		self::$db = &get_instance()->db;
    }
    
    
    public function get_sliders($status = NULL)	
    { 
        self::$db->select('slider.*, sliders.*, count(slide_id) AS slides'); 
        self::$db->from('slider');
		self::$db->join('sliders', 'slider.slider_id = sliders.slider', 'left'); 
        self::$db->group_by('slider.slider_id'); 
        if(!is_null($status)) {
        	self::$db->where('status', 1);
        }    
		return self::$db->get()->result();
    }
         

    public function get_block ($id)	
    {      
        self::$db->select('*'); 
        self::$db->from('blocks_custom');  
        self::$db->where('id', $id);       
        return self::$db->get()->row();
    }
    

    static function load_blocks ($page, $section)	
    {      
        self::$db->select('*'); 
        self::$db->from('blocks');  
        self::$db->where('section', $section);   
        self::$db->where('page', $page);    
        return self::$db->get()->result();
	}


}
