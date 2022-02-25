<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Contacts extends Hosting_Billing {
	function __construct()
	{
		parent::__construct();
		User::logged_in(); 

		if (!User::is_admin()) {
			$this->session->set_flashdata('message', lang('access_denied'));
			redirect('');
		}
		$this->applib->set_locale();

	}
	function index()
	{
	 redirect();
	}

	function update()
	{
		if ($this->input->post()) {
		Applib::is_demo();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="text-danger"', '</span><br>');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('company', 'Company', 'required');
		$this->form_validation->set_rules('fullname', 'Full Name', 'required');
		$company = $this->input->post('company');
		if ($this->form_validation->run() == FALSE)
		{
				$this->session->set_flashdata('response_status', 'error');
				$this->session->set_flashdata('message', lang('operation_failed'));
				redirect('companies/view/'.$company);
		}else{
			$user_id =  $this->input->post('user_id',TRUE);
			$args = array(
			                'fullname' => $this->input->post('fullname',TRUE),
                            'company' => $this->input->post('company'),
			                'phone' => $this->input->post('phone'),
			                'language' => $this->input->post('language'),
			                'mobile' => $this->input->post('mobile'),
			                'skype' => $this->input->post('skype'),
			                'locale' => $this->input->post('locale'),
			            );
			App::update('account_details',array('user_id' => $user_id),$args);
           	date_default_timezone_set(config_item('timezone'));
			$user_data = array(
			                'email' => $this->input->post('email'),
			                'modified' => date("Y-m-d H:i:s")
			                );
			App::update('users',array('id' => $user_id),$user_data);

			$data = array(
				'module' => 'contacts',
				'module_field_id' => $user_id,
				'user' => User::get_id(),
				'activity' => 'activity_contact_edited',
				'icon' => 'fa-edit',
				'value1' => $this->input->post('fullname'),
				'value2' => ''
				);
			App::Log($data);

			$this->session->set_flashdata('response_status', 'success');
			$this->session->set_flashdata('message', lang('user_edited_successfully'));
			Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('user_edited_successfully'));
		}
		}else{
		$data['id'] = $this->uri->segment(3);
		$this->load->view('modal/edit_contact',$data);
		}
	}


	function add()
	{
		if ($this->input->post()) {
			redirect('contacts');
		}else{
		$data['company'] = $this->uri->segment(3);
		$this->load->view('modal/add_client',$data);
		}
	}
	function username_check(){
			$username = $this->input->post('username',TRUE);
			$users = $this->db->where('username',$username)->get('users') -> num_rows();
			if($users > 0){ echo '<div class="alert alert-danger">Username already in use</div>'; exit;
			}else{ echo '<div class="alert alert-success">Awesome! Your username is available!</div>'; exit; }
	}
	function email_check(){
			$email = $this->input->post('email',TRUE);
			$users = $this->db->where('email',$email)->get('users') -> num_rows();
			if($users > 0){ echo '<div class="alert alert-danger">Email already in use</div>'; exit;
			}else{ echo '<div class="alert alert-success">Great! The email entered is available</div>'; exit; }
	}
}
/* End of file contacts.php */
