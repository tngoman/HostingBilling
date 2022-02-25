<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Tickets extends Hosting_Billing  {

	function __construct()
	{
		parent::__construct();
		User::logged_in();

		$this->load->module('layouts');
		$this->load->library(array('template','form_validation'));
		$this->template->title(lang('tickets').' - '.config_item('company_name'));
		
		$lang = config_item('default_language');
        if (isset($_COOKIE['fo_lang'])) { $lang = $_COOKIE['fo_lang']; }
        if ($this->session->userdata('lang')) { $lang = $this->session->userdata('lang'); }
        $this->lang->load('hd', $lang);

		$this->load->model(array('Ticket','App'));

		App::module_access('menu_tickets');

		$archive = FALSE;
		if (isset($_GET['view'])) { if ($_GET['view'] == 'archive') { $archive = TRUE; } }

		$this->filter_by = $this->_filter_by();
	}

	function index()
	{
		$archive = FALSE;
		if (isset($_GET['view'])) { if ($_GET['view'] == 'archive') { $archive = TRUE; } }
		$data = array(
			'page' => lang('tickets'),
			'datatables' => TRUE,
			'archive' => $archive,
			'tickets' => $this->_ticket_list($archive)
		);
		$this->template
		->set_layout('users')
		->build('tickets',isset($data) ? $data : NULL);
	}

	function _filter_by(){

		$filter = isset($_GET['view']) ? $_GET['view'] : '';

		return $filter;
	}


	function _ticket_list($archive = NULL){

			if (User::is_admin()) {
				return $this->_admin_tickets($archive,$this->filter_by);
			}

			elseif (User::is_staff()) {
				return $this->_staff_tickets($archive,$this->filter_by);
			}
			
			else{
				return $this->_client_tickets($archive,$this->filter_by);
			}
	}

	function view($id = NULL)
	{

		if(!User::can_view_ticket(User::get_id(),$id)){ App::access_denied('tickets'); }

		$data['page'] = lang('tickets');
		$data['editor'] = TRUE;
		$data['id'] = $id;
		$data['tickets'] = $this->_ticket_list(); // GET a list of the Tickets

		$this->template
		->set_layout('users')
		->build('view',isset($data) ? $data : NULL);
	}



	function add()
	{
		if ($this->input->post()) {
			if (isset($_POST['dept'])) {
				Applib::go_to('tickets/add/?dept='.$_POST['dept'],'success','');
			}

			$this->form_validation->set_rules('ticket_code', 'Ticket Code', 'required');
			$this->form_validation->set_rules('subject', 'Subject', 'required');
			$this->form_validation->set_rules('body', 'Body', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				Applib::make_flashdata(array(
					'response_status' => 'error',
					'message' => lang('operation_failed'),
					'form_error'=> validation_errors()
				));

				redirect($_SERVER['HTTP_REFERER']);
			}else{
                date_default_timezone_set(config_item('timezone'));
				$attachment = '';
				if($_FILES['ticketfiles']['tmp_name'][0]){
					$attachment = $this->_upload_attachment($_POST);
				}

				// check additional fields
				$additional_fields = array();
				$additional_data = $this->db->where(array('deptid'=>$_POST['department']))
				->get('fields')
				->result_array();
				if (is_array($additional_data))
				foreach ($additional_data as $additional)
				{
					// We create these vales as an array
					$name = $additional['uniqid'];
					$additional_fields[$name] = $this->input->post($name);
				}
				$subject = $this->input->post('subject',true);
				$code = $this->input->post('ticket_code',true);

				$_POST['real_subject'] = $subject;

				$_POST['subject'] = '['.$code.'] : '.$subject;

				$insert = array(
					'subject' => $_POST['subject'],
					'ticket_code' => $code,
					'department' => $_POST['department'],
					'priority' => $_POST['priority'],
					'body' => $this->input->post('body'),
					'status' => 'open',
					'created' => date("Y-m-d H:i:s",time())
				);

				if (is_array($additional_fields)){
					$insert['additional'] = json_encode($additional_fields);
				}

				if (isset($attachment)){
					$insert['attachment'] = $attachment;
				}
				if (!User::is_admin()) {
					$insert['reporter'] = User::get_id();
					$_POST['reporter'] = User::get_id();
				}else{
					$insert['reporter'] = $_POST['reporter'];
				}



				if($ticket_id = Ticket::save_data('tickets',$insert)){

					// Send email to Staff
					$this->_send_email_to_staff($ticket_id);
					// Send email to Client
					$this->_send_email_to_client($ticket_id);
 

		            $data = array(
						'module' => 'tickets',
						'module_field_id' => $ticket_id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_created',
						'icon' => 'fa-ticket',
						'value1' => $subject,
						'value2' => ''
						);
					App::Log($data);


					Applib::go_to('tickets/view/'.$ticket_id,'success',lang('ticket_created_successfully'));
				}


			}
		}else{

			$data = array(
				'page' 		 => lang('tickets'),
				'datepicker' => TRUE,
				'form'		 => TRUE,
				'editor'	 => TRUE,
				'tickets'	 => $this->_ticket_list()
			);

			$this->template
			->set_layout('users')
			->build('create_ticket',isset($data) ? $data : NULL);

		}
	}





	function edit($id = NULL)
	{

		if ($this->input->post()) {
			$ticket_id = $this->input->post('id', TRUE);

			$this->form_validation->set_rules('ticket_code', 'Ticket Code', 'required');
			$this->form_validation->set_rules('subject', 'Subject', 'required');
			$this->form_validation->set_rules('body', 'Body', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				Applib::make_flashdata(array(
					'response_status' => 'error',
					'message' => lang('error_in_form'),
					'form_error'=> validation_errors()
				));

				redirect($_SERVER['HTTP_REFERER']);
			}else{

				if($_FILES['ticketfiles']['tmp_name'][0]){
					$attachment = $this->_upload_attachment($this->input->post());
				}

				if (isset($attachment)){
					$_POST['attachment'] = $attachment;
				}

				Ticket::update_data('tickets',array('id'=>$ticket_id),$this->input->post());

				 $data = array(
						'module' => 'tickets',
						'module_field_id' => $ticket_id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_edited',
						'icon' => 'fa-pencil',
						'value1' => $this->input->post('subject',TRUE),
						'value2' => ''
						);
					App::Log($data);
					Applib::go_to('tickets/view/'.$ticket_id,'success',lang('ticket_edited_successfully'));

			}
		}else{
			if(!User::can_view_ticket(User::get_id(),$id)){ App::access_denied('tickets'); }
			$data = array(
				'page'		 	 => lang('tickets'),
				'datepicker' 	 => TRUE,
				'form'		 	 => TRUE,
				'editor'	 	 => TRUE,
				'tickets'	 	 => $this->_ticket_list(),
				'id' 			 => $id
			);

			$this->template
			->set_layout('users')
			->build('edit_ticket',isset($data) ? $data : NULL);

		}
	}


	function quick_edit(){
		if($this->input->post()){
			$ticket_id = $this->input->post('id',TRUE);
			$data = array('reporter' 	=> $this->input->post('reporter', TRUE),
						  'department'	=> $this->input->post('department', TRUE),
						  'priority'	=> $this->input->post('priority', TRUE),
						  );
			Ticket::update_data('tickets',array('id'=>$ticket_id),$data);


			Applib::go_to('tickets/view/'.$ticket_id,'success',lang('ticket_edited_successfully'));
		}
	}


	function reply()
	{
		if ($this->input->post()) {
			$ticket_id = $this->input->post('ticketid');

			$this->form_validation->set_rules('reply', 'Ticket Reply', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				$_POST = '';
				Applib::go_to('tickets/view/'.$ticket_id,'error',lang('error_in_form'));
			}else{

				$attachment = '';
				if($_FILES['ticketfiles']['tmp_name'][0]){
					$attachment = $this->_upload_attachment($this->input->post());
				}
				$insert = array(
					'ticketid' 		=> $_POST['ticketid'],
					'body' 			=> $this->input->post('reply'),
					'attachment' 	=> $attachment,
					'replierid' 	=> User::get_id(),
				);


				if($reply_id = Ticket::save_data('ticketreplies',$insert)){

					// if ticket is closed send re-opened email to staff/client
				if(Ticket::view_by_id($ticket_id)->status == 'closed'){
					if(config_item('notify_ticket_reopened') == 'TRUE'){
						$this->_notify_ticket_reopened($ticket_id);
					}

				}

					Ticket::update_data('tickets',array('id'=> $ticket_id),array('status' => 'open'));

					(User::is_client())
								? $this->_notify_ticket_reply('admin',$ticket_id,$reply_id)
								: $this->_notify_ticket_reply('client',$ticket_id,$reply_id);
					// Send email to client/admins
 

		            $data = array(
						'module' => 'tickets',
						'module_field_id' => $ticket_id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_replied',
						'icon' => 'fa-ticket',
						'value1' => Ticket::view_by_id($ticket_id)->subject,
						'value2' => ''
						);
					App::Log($data);

					Applib::go_to('tickets/view/'.$ticket_id,'success',lang('ticket_replied_successfully'));
				}


			}
		}else{
			$this->index();

		}
	}


	function delete($id = NULL)
	{
		if ($this->input->post()) {

			$ticket = $this->input->post('ticket', TRUE);

			App::delete('ticketreplies',array('ticketid'=>$ticket)); //delete ticket replies
			//clear ticket activities
			App::delete('activities',array('module'=>'tickets', 'module_field_id' => $ticket));
			//delete ticket
			App::delete('tickets',array('id'=>$ticket));

			Applib::go_to('tickets','success',lang('ticket_deleted_successfully'));

		}else{
			$data['ticket'] = $id;
			$this->load->view('modal/delete_ticket',$data);

		}
	}

	function archive()
	{
		$id = $this->uri->segment(3);
		$info = Ticket::view_by_id($id);
		$archived = $this->uri->segment(4);
		$data = array("archived_t" => $archived);
		Ticket::update_data('tickets',array('id'=>$id),$data);

		$data = array(
						'module' => 'tickets',
						'module_field_id' => $id,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_edited',
						'icon' => 'fa-pencil',
						'value1' => $info->subject,
						'value2' => ''
						);
		App::Log($data);
		Applib::go_to('tickets','success',lang('ticket_edited_successfully'));
	}

	function download_file($ticket = NULL)
	{
		$this->load->helper('download');
		$file_name = Ticket::view_by_id($ticket)->attachment;
		if(file_exists('./resource/attachments/'.$file_name)){
			$data = file_get_contents('./resource/attachments/'.$file_name); // Read the file's contents
			force_download($file_name, $data);
		}else{
			Applib::go_to('tickets/view/'.$ticket,'error',lang('operation_failed'));
		}
	}


	function status($ticket = NULL){
		if (isset($_GET['status'])) {
			$status = $_GET['status'];
			$current_status = Ticket::view_by_id($ticket)->status;

			if($current_status == 'closed' && $status != 'closed'){
					if(config_item('notify_ticket_reopened') == 'TRUE'){
						$this->_notify_ticket_reopened($ticket);
					}

				}

			$data = array('status' => $status);
			Ticket::update_data('tickets',array('id' => $ticket),$data);

			if ($status == 'closed' && $current_status != 'closed') {
				// Send email to ticket reporter
				$this->_ticket_closed($ticket);
			}
 

            $data = array(
						'module' => 'tickets',
						'module_field_id' => $ticket,
						'user' => User::get_id(),
						'activity' => 'activity_ticket_status_changed',
						'icon' => 'fa-ticket',
						'value1' => Ticket::view_by_id($ticket)->subject,
						'value2' => ''
						);
			App::Log($data);
			Applib::go_to('tickets/view/'.$ticket,'success',lang('ticket_status_changed'));

		}else{
			$this->index();
		}
	}




	function _ticket_closed($ticket){

		if (config_item('notify_ticket_closed') == 'TRUE') {
			$message = App::email_template('ticket_closed_email','template_body');
			$subject = App::email_template('ticket_closed_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($ticket);

			$no_of_replies = App::counter('ticketreplies',array('ticketid' => $ticket));

			$reporter_email = User::login_info($info->reporter)->email;

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$code = str_replace("{TICKET_CODE}",$info->ticket_code,$logo);
			$title = str_replace("{SUBJECT}",$info->subject,$code);
			$reporter = str_replace("{REPORTER_EMAIL}",$reporter_email,$title);
			$staff = str_replace("{STAFF_USERNAME}",User::displayName(User::get_id()),$reporter);
			$status = str_replace("{TICKET_STATUS}",'Closed',$staff);
			$replies = str_replace("{NO_OF_REPLIES}",$no_of_replies,$status);
			$link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$ticket,$replies);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$link);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']', $subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);

			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';

			$params['recipient'] = $reporter_email;
			modules::run('fomailer/send_email',$params);
		}

	}

	function _notify_ticket_reply($group,$id,$reply_id){

		if (config_item('notify_ticket_reply') == 'TRUE') {

			$message = App::email_template('ticket_reply_email','template_body');
			$subject = App::email_template('ticket_reply_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($id);
			$reply = $this->db->where('id',$reply_id)->get('ticketreplies')->row();


			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$code = str_replace("{TICKET_CODE}",$info->ticket_code,$logo);
			$title = str_replace("{SUBJECT}",$info->subject,$code);
			$status = str_replace("{TICKET_STATUS}",ucfirst($info->status),$title);
			$link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$status);
			$body = str_replace("{TICKET_REPLY}",$reply->body,$link);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$body);

			$message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']'.$info->subject,$subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);

			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';



			switch ($group) {
				case 'admin':
				// Send to admins
				if(count(User::team())){

	        	$staff_members = User::team();
				// Send email to staff department
				foreach ($staff_members as $key => $user) {
					$dep = json_decode(User::profile_info($user->id)->department,TRUE);
					if (is_array($dep) && in_array($info->department, $dep)) {
	            		$email = User::login_info($user->id)->email;
						$params['recipient'] = $email;
						modules::run('fomailer/send_email',$params);
	        		}
				}

				}

				return TRUE;
				break;

				default:
				$params['recipient'] = User::login_info($info->reporter)->email;
				modules::run('fomailer/send_email',$params);

				return TRUE;
				break;
				}

		}
	}


	function _notify_ticket_reopened($ticket){

			$message = App::email_template('ticket_reopened_email','template_body');
			$subject = App::email_template('ticket_reopened_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($ticket);

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$title = str_replace("{SUBJECT}",$info->subject,$logo);
			$user = str_replace("{USER}",User::displayName(User::get_id()),$title);
			$link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$ticket,$user);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$link);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);

			$subject = str_replace("[SUBJECT]",$info->subject, $subject);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);

			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';

	        if(User::is_client()){
	        	// Get admins
	        	if(count(User::team())){
	        	$staff_members = User::team();
				// Send email to staff department
				foreach ($staff_members as $key => $user) {
					$dep = json_decode(User::profile_info($user->id)->department,TRUE);
					if (is_array($dep) && in_array($info->department, $dep)) {
	            		$email = User::login_info($user->id)->email;
						$params['message'] = str_replace("{RECIPIENT}",$email,$message);
						$params['recipient'] = $email;
						modules::run('fomailer/send_email',$params);
	        		}
				}
			}

			}else{
				$email = User::login_info($info->reporter)->email;
				$params['message'] = str_replace("{RECIPIENT}",$email,$message);
				$params['recipient'] = $email;
				modules::run('fomailer/send_email',$params);
			}


	}

	function _send_email_to_staff($id)
	{
		if (config_item('email_staff_tickets') == 'TRUE') {

			$message = App::email_template('ticket_staff_email','template_body');
			$subject = App::email_template('ticket_staff_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($id);

			$reporter_email = User::login_info($info->reporter)->email;

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$code = str_replace("{TICKET_CODE}",$info->ticket_code,$logo);
			$title = str_replace("{SUBJECT}",$info->subject,$code);
			$reporter = str_replace("{REPORTER_EMAIL}",$reporter_email,$title);
			// $UserEmail =
			$link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$reporter);
			$signature = str_replace("{SIGNATURE}",$signature,$link);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$signature);

			$data['message'] = $message;
			$message = $this->load->view('email_template', $data, TRUE);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']',$subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$params['subject'] = $subject;

			$params['attached_file'] = '';
			$params['alt_email'] = 'support';

			if(count(User::team())){
			$staff_members = User::team();
			// Send email to staff department
			foreach ($staff_members as $key => $user) {
				$dep = json_decode(User::profile_info($user->id)->department,TRUE);
				if (is_array($dep) && in_array($info->department, $dep)) {
            		$email = User::login_info($user->id)->email;
					$params['message'] = str_replace("{USER_EMAIL}",$email,$message);
					$params['recipient'] = $email;
					modules::run('fomailer/send_email',$params);
        		}
			}
		}

			return TRUE;

		}else{
			return TRUE;
		}

	}

	function _send_email_to_client($id)
	{

			$message = App::email_template('ticket_client_email','template_body');
			$subject = App::email_template('ticket_client_email','subject');
			$signature = App::email_template('email_signature','template_body');

			$info = Ticket::view_by_id($id);

			$email = User::login_info($info->reporter)->email;

			$logo_link = create_email_logo();

			$logo = str_replace("{INVOICE_LOGO}",$logo_link,$message);

			$client_email = str_replace("{CLIENT_EMAIL}",$email,$logo);
			$ticket_code = str_replace("{TICKET_CODE}",$info->ticket_code,$client_email);
			$title = str_replace("{SUBJECT}",$info->subject,$ticket_code);
			$ticket_link = str_replace("{TICKET_LINK}",base_url().'tickets/view/'.$id,$title);
			$EmailSignature = str_replace("{SIGNATURE}",$signature,$ticket_link);
			$message = str_replace("{SITE_NAME}",config_item('company_name'),$EmailSignature);
			$data['message'] = $message;

			$message = $this->load->view('email_template', $data, TRUE);

			$subject = str_replace("[TICKET_CODE]",'['.$info->ticket_code.']',$subject);
			$subject = str_replace("[SUBJECT]",$info->subject,$subject);

			$params['recipient'] = $email;
			$params['subject'] = $subject;
			$params['message'] = $message;
			$params['attached_file'] = '';
	        $params['alt_email'] = 'support';

			modules::run('fomailer/send_email',$params);
			return TRUE;

	}

	function _upload_attachment($data){

		$config['upload_path'] = './resource/attachments/';
		$config['allowed_types'] = config_item('allowed_files');
		$config['max_size'] = config_item('file_max_size');
		$config['overwrite'] = FALSE;
		$this->load->library('upload', $config);

		if(!$this->upload->do_multi_upload("ticketfiles")) {
			Applib::make_flashdata(array(
				'response_status' => 'error',
				'message' => lang('operation_failed'),
				'form_error'=> $this->upload->display_errors('<span class="text-danger">', '</span><br>')
			));
			redirect($_SERVER['HTTP_REFERER']);
		} else {

			$fileinfs = $this->upload->get_multi_upload_data();
			foreach ($fileinfs as $fileinf) {
				$attachment[] = $fileinf['file_name'];
			}

			return json_encode($attachment);

		}




	}



	function _admin_tickets($archive = FALSE,$filter_by = NULL){

		if($filter_by == NULL) return Ticket::by_where(array('archived_t !=' => '1'));

		if ($archive) return Ticket::by_where(array('archived_t' => '1'));

		switch ($filter_by) {
			case 'open':
			return Ticket::by_where(array('archived_t !='=>'1','status' => 'open'));
			break;
			case 'closed':
			return Ticket::by_where(array('archived_t !='=>'1','status' => 'closed'));
			break;
			case 'pending':
			return Ticket::by_where(array('archived_t !='=>'1','status' => 'pending'));
			break;
			case 'resolved':
			return Ticket::by_where(array('archived_t !='=>'1','status' => 'resolved'));
			break;

			default:
			return Ticket::by_where(array('archived_t !='=>'1'));
			break;
		}

	}


	function _staff_tickets($archive = FALSE, $filter_by = NULL){

		$staff_department = User::profile_info(User::get_id())->department;
		$dep = json_decode($staff_department,TRUE);

		if($filter_by == NULL){

			($archive) ? $this->db->where(array('archived_t' => '1'))
					: $this->db->where(array('archived_t !=' => '1'));

			if(is_array($dep)){
				$this->db->where_in('department', $dep);
			}else{
				$this->db->where('department',$staff_department);
			}
			$output = $this->db->or_where('reporter',User::get_id())->get('tickets')->result();

			return $output;

		}

		switch ($filter_by) {
			case 'open':
			$this->db->where(array('archived_t !=' => '1','status' => 'open'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();

			break;
			case 'closed':

			$this->db->where(array('archived_t !=' => '1','status' => 'closed'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();

			break;
			case 'pending':
			$this->db->where(array('archived_t !=' => '1','status' => 'pending'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();

			break;
			case 'resolved':
			$this->db->where(array('archived_t !=' => '1','status' => 'resolved'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();

			break;

			default:
			$this->db->where(array('archived_t !=' => '1'));
			if(is_array($dep)){ $this->db->where_in('department', $dep); }else{
				$this->db->where('department',$staff_department);
			}
			return $this->db->or_where('reporter',User::get_id())->get('tickets')->result();
			break;
		}



	}



	function _client_tickets($archive = FALSE, $filter_by = NULL){


		if($filter_by == NULL){

			if($archive){
				return Ticket::by_where(array('reporter'=>User::get_id(),'archived_t'=>'1'));
			}else{
				return Ticket::by_where(array('reporter'=>User::get_id(),'archived_t !='=>'1'));
			}

		}

		switch ($filter_by) {
			case 'open':
			return Ticket::by_where(array('archived_t !='=>'1','status'=>'open','reporter'=>User::get_id()));

			break;
			case 'closed':
			return Ticket::by_where(array('archived_t !='=>'1','status'=>'closed','reporter'=>User::get_id()));

			break;
			case 'pending':
			return Ticket::by_where(array('archived_t !='=>'1','status'=>'pending','reporter'=>User::get_id()));

			break;
			case 'resolved':
			return Ticket::by_where(array('archived_t !='=>'1','status'=>'resolved','reporter'=>User::get_id()));

			break;

			default:
			return Ticket::by_where(array('archived_t !='=>'1','reporter'=>User::get_id()));
			break;
		}
	}


}

/* End of file invoices.php */
