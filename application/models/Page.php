<?php


class Page extends MY_Model
{
	protected $_table_name = 'posts';
	protected $_primary_key = 'posts.id';
	protected $_order_by = 'pubdate desc, posts.id desc';
	protected $_timestamps = TRUE;
	public $rules = array(
	 
		'title' => array(
			'field' => 'title',
			'label' => 'Title',
			'rules' => 'trim|required|xss_clean'
		),
		'slug' => array(
			'field' => 'slug',
			'label' => 'Slug',
			'rules' => 'trim|required|max_length[100]|xss_clean'
		), 
		'body' => array(
			'field' => 'body',
			'label' => 'Body',
			'rules' => 'trim|required'
		),
		'meta_title' => array(
			'field' => 'meta_title',
			'label' => 'Page Title',
			'rules' => 'trim|max_length[100]|xss_clean'
		),
		'meta_desc' => array(
			'field' => 'meta_desc',
			'label' => 'Page Description',
			'rules' => 'trim|max_length[200]|xss_clean'
		),
		'video' => array(
			'field' => 'video',
			'label' => 'Video URL',
			'rules' => 'trim|max_length[200]|xss_clean'
		)
	);

	public function get_new ()
	{
		$page = new stdClass();
		$page->title = '';
		$page->slug = '';
		$page->body = ''; 
		$page->status = 1;
		$page->menu = 0; 
		$page->sidebar_right = 0; 
		$page->sidebar_left = 0; 
        $page->post_type = 'page';
		$page->pubdate = date('Y-m-d');
		$page->user_id = $this->session->userdata('user_id'); 
		$page->order = '0'; 
		return $page;
	}

  
	public function get($id = NULL, $single = FALSE, $published = FALSE)
	{
		$this->db->where('posts.post_type', 'page');
		$this->db->select('*'); 
        if($published != FALSE){
		    $this->set_published();
        }
        $this->_primary_key = 'posts.id';
		return parent::get($id, $single);
	}


	public function get_by_slug() 
	{
		$slug = get_slug();
		$this->db->where('post_type', 'page');
		$this->db->where('slug', $slug);
		$this->db->select('*'); 
		return $this->db->get('posts')->row();
	}


	public function get_by($where = FALSE, $single = FALSE, $like = FALSE){
		if($where != FALSE) {
			$this->db->where($where);
		}
		if($like != FALSE) {
			$this->db->like($like);
		}
		return $this->get(NULL, $single, TRUE);
	}


	public function get_by_id($id = NULL, $single = FALSE, $published = FALSE)
	{
        if($published != FALSE){
		    $this->set_published();
        }
        $this->_primary_key = 'posts.id';
		return $this->get($id, $single);
	}


	public function set_published(){
		$this->db->where('pubdate <=', date('Y-m-d'));
		$this->db->where('status', 1);
	}


	public function get_pages ($published = FALSE)
	{
		$this->db->where('posts.post_type', 'page');
		$this->db->select('title, slug'); 
        if($published != FALSE){
		    $this->set_published();
        }
        $this->_primary_key = 'posts.id';
		return parent::get();
	}

}