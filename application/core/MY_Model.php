<?php

 
class MY_Model extends CI_Model {

	// define variables
	protected $_table_name = '';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = '';
	public $rules = array();
	protected $_timestamps = FALSE;

	function __construct() {
		parent::__construct();
	}

    /**
     * Count database table.
     *
     * @param array $where 	optional param to add where statment  
     *
     * @return int  number of entries data
     */
     public function count($where=NULL) {
        if($where != NULL) {
            $this->db->where($where);
        }
        return $this->db->count_all_results($this->_table_name);
    }


    /**
     * Customize all post inputs in own array.
     *
     * @param array $fields  all post inputs  
     *
     * @return array  Return all post inputs in an array
     */
 	public function array_from_post($fields){
		$data = array();
		foreach ($fields as $field) {
			$data[$field] = $this->input->post($field);
		}
		return $data;
	}


    /**
     * Get data from database.
     *
     * @param int $id 	Optional id or NULL  
     *
     * @param bool $single 	TRUE or FALSE  
     *
     * @return array  return data from database in array
     */
 	public function get($id = NULL, $single = FALSE){

		if ($id != NULL) {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->where($this->_primary_key, $id);
			$method = 'row';
		}
		elseif($single == TRUE) {
			$method = 'row';
		}
		else {
			$method = 'result';
		}

		$this->db->order_by($this->_order_by);
		return $this->db->get($this->_table_name)->$method();
	}

    /**
     * Get data from database using where statment.
     *
     * @param array $where 	your where statment  
     *
     * @param bool $single 	TRUE or FALSE  
     *
     * @return array  return data from database in array
     */
	public function get_by($where = FALSE, $single = FALSE, $like = FALSE){
		if($where != FALSE) {
			$this->db->where($where);
		}
		if($like != FALSE) {
			$this->db->like($like);
		}
		return $this->get(NULL, $single);
	}

    /**
     * Insert or Update data in database.
     *
     * @param array $data 	data to save or update  
     *
     * @param int $id 	field id if update or NULL if save
     */
	public function save($data, $id = NULL){

		// Set timestamps
		if ($this->_timestamps == TRUE) {
			$now = date('Y-m-d H:i:s');
			$id || $data['created'] = $now;
			$data['modified'] = $now;
		}

		// Insert
		if ($id === NULL) {
			!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
			$this->db->set($data);
			$this->db->insert($this->_table_name);
			$id = $this->db->insert_id();
		}
		// Update
		else {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->set($data);
			$this->db->where($this->_primary_key, $id);
			$this->db->update($this->_table_name);
		}

		return $id;
	}

    /**
     * Delete data from database.
     *
     * @param int $id 	field id to delete
     */
	public function delete($id){
		$filter = $this->_primary_filter;
		$id = $filter($id);

		if (!$id) {
			return FALSE;
		}
		$this->db->where($this->_primary_key, $id);
		$this->db->limit(1);
		$this->db->delete($this->_table_name);
	}

    /**
     * Delete multiple rows data from database.
     *
     * @param mixed $id field id or multiple in array  
     */
	public function delete_multi($id){
		if (!$id) {
			return FALSE;
		}
		$this->db->where_in($this->_primary_key, $id);
		$this->db->delete($this->_table_name);
	}

}