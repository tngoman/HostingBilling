<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



class Ticket extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}


	// Get tickets LIMIT
	static function get_tickets()
	{
		return self::$db->order_by('created','desc')->get('tickets')->result();
	}

	// Get tickets WHERE array
	static function by_where($array = NULL){
		return self::$db->where($array)->order_by('id','DESC')->get('tickets')->result();
	}

	// Get ticket information
	static function view_by_id($ticket)
	{
		return self::$db->where('id',$ticket)->get('tickets')->row();
	}

	// Get ticket replies
	static function view_replies($id)
	{
		return self::$db->where('ticketid',$id)->order_by('id','asc')->get('ticketreplies')->result();
	}

	// Save any data
	static function save_data($table, $data){
		self::$db->insert($table,$data);
		return self::$db->insert_id();
	}

	// Update Data
	static function update_data($table,$where,$data){
		return self::$db->where($where)->update($table,$data);
	}

	static function generate_code() {
		$query = self::$db->select('ticket_code')->select_max('id')->get('tickets');
		if ($query->num_rows() > 0)
		{
			$row = $query->row();
			$code = intval(substr($row->ticket_code, -4));
			$next_number = $code + 1;
			if ($next_number < config_item('ticket_start_no')) { $next_number = config_item('ticket_start_no'); }
			$next_number = self::ref_exists($next_number);
			return sprintf('%04d', $next_number);
		}else{
			return sprintf('%04d', config_item('ticket_start_no'));
		}
	}

	// Verify if REF Exists

	static function ref_exists($next_number){
		$next_number = sprintf('%04d', $next_number);

		$records = self::$db->where('ticket_code',$next_number)
							->get('tickets')->num_rows();
		if ($records > 0) {
			return self::ref_exists($next_number + 1);
		}else{
			return $next_number;
		}
	}


}

/* End of file model.php */
