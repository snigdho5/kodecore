<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('GoogleAuthenticator');
	}

	public function get404()
	{
		$this->load->view('404');
	}

	public function index()
	{
		$sess_id = $this->session->userdata('userid');

		if (!empty($sess_id) && $this->session->userdata('usr_logged_in') == 1) {
			redirect(base_url() . 'dashboard');
		} else {
			redirect(base_url() . 'login');
		}
	}


	public function onGetDashboard()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

			//count visits
			$this->data['tot_visits'] = '0';
			$this->data['tot_visits_in'] = '0';
			$this->data['tot_visits_wb'] = '0';
			$this->data['tot_visits_us'] = '0';
			$this->data['tot_visits_fb'] = '0';
			$this->data['tot_visits_gl'] = '0';
			$this->data['tot_cont'] = '0';
			$this->data['tot_signups'] = '0';

			$this->load->view('vw_dashboard', $this->data, false);
		} else {
			redirect(base_url() . 'login');
		}
	}

	public function onSetLogin()
	{
		if (empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 0) {
			$this->load->view('vw_login');
		} else {
			redirect(base_url() . 'dashboard');
		}
	}

	//login
	public function onCheckLogin()
	{
		if (empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 0) {

			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$this->form_validation->set_rules('username', 'Username', 'trim|required|valid_email|xss_clean|htmlentities');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|htmlentities');

				if ($this->form_validation->run() == FALSE) {
					$this->form_validation->set_error_delimiters('', '');
					$return['errors'] = validation_errors();
					$return['checkedlogin'] = 'rule_error';
				} else {
					$username = xss_clean($this->input->post('username'));
					$password = encrypt_it(xss_clean($this->input->post('password')));

					$chkdata = array(
						'user_name'  => $username,
						'pass'     => $password,
						'user_blocked'     => 0
					);
					$userdata = $this->am->getUserData($chkdata, $many = FALSE);
					//print_obj($userdata);die;

					if (!empty($userdata)) {

						if ($userdata->twofa_enabled == 1) {
							$setdata = array(
								'auth_id'  => $userdata->user_id
							);
							//print_obj($setdata);die;
							$this->session->set_userdata($setdata);

							$return['checkedlogin'] = 'success2fa';
							//redirect(base_url().'dashboard');
						} else {
							$editarr = array('last_login_ip' => $this->input->ip_address(), 'last_login' => dtime);
							$upduser = $this->am->updateUser($editarr, array('user_id' => $userdata->user_id));
							if ($upduser) {
								$setdata = array(
									'userid'  => $userdata->user_id,
									'usergroup'  => $userdata->user_group,
									'username'  => $userdata->user_name,
									'fullname'  => $userdata->full_name,
									'usr_logged_in' => 1
								);
								//print_obj($setdata);die;
								$this->session->set_userdata($setdata);

								$return['checkedlogin'] = 'success';
							} else {
								$return['checkedlogin'] = 'upd_error';
							}

							//redirect(base_url().'dashboard');
						}
					} else {
						$return['checkedlogin'] = 'mismatch_error';
					}
				}
				header('Content-Type: application/json');
				echo json_encode($return);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url() . 'dashboard');
		}
	}

	function onCheck2FAuth()
	{

		if (empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 0  && $this->session->userdata('auth_id') != 0) {
			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$this->form_validation->set_rules('tpasscode', 'Passcode', 'trim|required|numeric|xss_clean|htmlentities');

				if ($this->form_validation->run() == FALSE) {
					$this->form_validation->set_error_delimiters('', '');
					$return['errors'] = validation_errors();
					$return['twofa'] = 'rule_error';
				} else {

					$chkdata = array(
						'user_id'  => $this->session->userdata('auth_id')
					);
					$userdata = $this->am->getUserData($chkdata, $many = FALSE);
					//print_obj($userdata);die;

					if (!empty($userdata)) {

						// 2 factor authentication codes....................................

						$gaobj = new GoogleAuthenticator();
						$secret = $userdata->twofa_secret;
						$oneCode = strip_tags($this->input->post('tpasscode'));

						$token = $gaobj->getCode($secret);

						$checkResult = $gaobj->verifyCode($secret, $oneCode, 2); // 2 = 2*30sec clock tolerance

						if (!$checkResult) {
							$return['errors'] = 'Two-factor token Failed';
							$return['twofa'] = 'failure';
						} else {
							$editarr = array('last_login_ip' => $this->input->ip_address(), 'last_login' => dtime);
							$upduser = $this->am->updateUser($editarr, array('user_id' => $userdata->user_id));
							if ($upduser) {
								$setdata = array(
									'auth_id'  => 0,
									'userid'  => $userdata->user_id,
									'usergroup'  => $userdata->user_group,
									'username'  => $userdata->user_name,
									'fullname'  => $userdata->full_name,
									'usr_logged_in' => 1
								);
								//print_obj($setdata);die;
								$this->session->set_userdata($setdata);

								$return['twofa'] = 'success';
								//redirect(base_url().'dashboard');
							} else {
								$return['twofa'] = 'upd_error';
							}
						}
					} else {
						$return['twofa'] = 'mismatch_error';
					}
				}
				header('Content-Type: application/json');
				echo json_encode($return);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}


	//login
	public function onSendPasswordRecovery()
	{
		if (empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 0) {

			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$this->form_validation->set_rules('emailid', 'Username / Email', 'trim|required|valid_email|xss_clean|htmlentities');

				if ($this->form_validation->run() == FALSE) {
					$this->form_validation->set_error_delimiters('', '');
					$return['errors'] = validation_errors();
					$return['status'] = 'rule_error';
				} else {
					$username = xss_clean($this->input->post('emailid'));

					$chkdata = array(
						'user_name'  => $username,
						'user_blocked'     => 0
					);
					$userdata = $this->am->getUserData($chkdata, $many = FALSE);
					//print_obj($userdata);die;

					if (!empty($userdata)) {
						$otp = random_strings(6);
						$from_email = '';
						$name = $userdata->full_name;
						$password = $userdata->pass;
						$subject = 'Forgot Password';

						$editarr = array('recovery_otp' => $otp);
						$upduser = $this->am->updateUser($editarr, array('user_id' => $userdata->user_id));
						if ($upduser) {
							//email

							$body = '';
							$body .= '<p>Name : ' . $name . '</p>';
							$body .= '<p>Username : ' . $username . '</p>';
							$body .= '<p>Password : ' . decrypt_it($password) . '</p>';
							$body .= '<br><br><p>** Please change your password after login.</p>';

							$this->load->library('email');
							$config = array(
								'protocol' => 'smtp',
								'smtp_host' => 'smtp.googlemail.com',
								'smtp_port' => 465,
								// 'smtp_user' => ADMIN_EMAIL,
								// 'smtp_pass' => ADMIN_EMAIL_PASS,
								'smtp_user' => 'noreply@staqo.com',
								'smtp_pass' => 'Welcome@123',
								'mailtype' => 'html',
								'smtp_crypto' => 'ssl',
								'smtp_timeout' => '4',
								'charset' => 'utf-8',
								'wordwrap' => TRUE
							);
							$this->email->initialize($config);


							$this->email->set_newline("\r\n");
							$this->email->set_mailtype("html");
							$this->email->from(FROM_EMAIL, 'Kode Core');
							$this->email->to($username);
							//$this->email->reply_to($replyemail);
							$this->email->subject($subject);
							$this->email->message($body);
							if ($this->email->send()) {
								$return['status'] = 'success';
								$return['msg'] = 'An e-mail with recovery details has been sent to your email (' . $username . ').';
							} else {
								//echo $this->email->print_debugger();die;
								$return['status'] = 'email_error';
								$return['errors'] = 'E-mail not sent to ' . $username . '!';
							}

						} else {
							$return['status'] = 'upd_error';
							$return['errors'] = '';
						}
					} else {
						$return['status'] = 'not_found';
						$return['errors'] = $username . ' not found in our system!';
					}
				}
				header('Content-Type: application/json');
				echo json_encode($return);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url() . 'dashboard');
		}
	}

	// Logout
	public function onSetLogout()
	{

		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			// $sess_array = array(
			// 	'userid'  => '',
			// 	'username'  => '',
			// 	'usr_logged_in' => 0
			// );
			//$this->session->unset_userdata($sess_array);
			$set_logout_time = array('last_logout' => dtime);
			$upduser = $this->am->updateUser($set_logout_time, array('user_id' => $this->session->userdata('userid')));
			$this->session->sess_destroy();

			$data['logout_success'] = 'success';
			$this->load->view('vw_login', $data);
		} else {
			redirect(base_url() . 'login');
		}
	}
}
