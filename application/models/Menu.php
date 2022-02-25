<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Model
{

		private static $db;

		function __construct()
		{
			parent::__construct();
			self::$db = &get_instance()->db;
		} 

		
		public function get_menu($group_id)
		{
			self::$db->select('*');
			self::$db->from('menu');
			self::$db->where('group_id',$group_id);
			self::$db->order_by('parent_id , position');
			$query = self::$db->get();
			$res = $query->result();
			if ($res){
				return $res;
			}
			else{
				return false;
			}
		}
	
		/**
		 * Get group title
		 *
		 * @param int $group_id
		 * @return string
		 */
		public function get_menu_group_title($group_id) {
			self::$db->select('*');
			self::$db->from('menu_group');
			self::$db->where('id', $group_id);
			$query = self::$db->get();
			return $query->row();
		}
	
		/**
		 * Get all items in menu group table
		 *
		 * @return array
		 */
		public function get_menu_groups() {
			self::$db->select('*');
			self::$db->from('menu_group');
			$query = self::$db->get();
			return $query->result();
		}
	
		public function add_menu_group($data) {
			if (self::$db->insert('menu_group', $data)) {
				$response['status'] = 1;
				$response['id'] = self::$db->Insert_ID();
				return $response;
			} else {
				$response['status'] = 2;
				$response['msg'] = 'Add group error.';
				return $response;
			}
		}
	
		public function get_row($id) {
			self::$db->select('*');
			self::$db->from('menu');
			self::$db->where('id', $id);
			$query = self::$db->get();
			return $query->row();
		}
	
		/**
		 * Get the highest position number
		 *
		 * @param int $group_id
		 * @return string
		 */
		public function get_last_position($group_id) {
			$pos;
			self::$db->select_max('position');
			self::$db->from('menu');
			self::$db->where('group_id', $group_id);
			self::$db->where('parent_id', '0');
			$query = self::$db->get();
			$data = $query->row();
			$pos = $data->position + 1;
			return $pos;
		}
	
		/**
		 * Recursive method
		 * Get all descendant ids from current id
		 * save to $this->ids
		 *
		 * @param int $id
		 */
		public function get_descendants($id) {
			self::$db->select('id');
			self::$db->from('menu');
			self::$db->where('parent_id', $id);
			$query = self::$db->get();
			$data = $query->row();
	
			$ids;
			if (!empty($data)) {
				foreach ($data as $v) {
					$ids[] = $v;
					$this->get_descendants($v);
				}
			}
		}
	
	//Delete the menu
		public function delete_menu($id) {
			self::$db->where('id', $id);
			return self::$db->delete('menu');
		}
	
	//Update MenuController Group
		public function update_menu_group($data, $id) {
			if (self::$db->update('menu_group', $data, 'id' . ' = ' . $id)) {
				return true;
			}
		}
	
	//Delete MenuController Group
		public function delete_menu_group($id) {
			self::$db->where('id', $id);
			return self::$db->delete('menu_group');
		}
	
		public function delete_menus($id) {
			self::$db->where('group_id', $id);
			return self::$db->delete('menu');
		}
	
	}
	