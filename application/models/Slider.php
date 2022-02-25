<?php

class Slider extends CI_model
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
    

    static function get_slides($id)	
    {    
        self::$db->select('slider.*, sliders.*'); 
        self::$db->from('slider');
		self::$db->join('sliders', 'slider.slider_id = sliders.slider', 'right'); 
        self::$db->where('slider_id', $id);       
        return self::$db->get()->result();
    }
    

    static function get_slider ($id)	
    {       
        self::$db->select('*'); 
        self::$db->from('slider'); 
        self::$db->where('slider_id', $id);       
        return self::$db->get()->row();
    }
    

    static function get_slide ($id)	
    {      
        self::$db->select('*'); 
        self::$db->from('sliders');  
        self::$db->where('slide_id', $id);       
        return self::$db->get()->row();
	}


}
