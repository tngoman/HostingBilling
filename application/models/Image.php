<?php
 
class Image extends MY_Model
{
	protected $_table_name = 'hd_images';
	protected $_primary_key = 'id';
	protected $_order_by = 'id desc';

	public function update($data, $id = NULL){

		$check = parent::get_by(array('post_id' => $id));

		if(count($check)) {
	    	$filter = $this->_primary_filter;
	    	$id = $filter($id);
	    	$this->db->set($data);
	    	$this->db->where('post_id', $id);
	    	$id = $this->db->update($this->_table_name);
		} else {
			$data['post_id'] = $id;
			parent::save($data);
		}


		return $id;
	}

}