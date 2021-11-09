<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('GoogleAuthenticator');
	}

	public function index()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

			$userdata = $this->am->getUserData(array('user_id !=' => 1), TRUE);
			if ($userdata) {
				foreach ($userdata as $key => $value) {
					$this->data['user_data'][] = array(
						'dtime'  => $value->dtime,
						'userid'  => $value->user_id,
						'usergroup'  => $value->user_group,
						'username'  => $value->user_name,
						//'password'  => decrypt_it($value->pass),
						'fullname'  => $value->full_name,
						'lastlogin'  => $value->last_login,
						'lastloginip'  => $value->last_login_ip,
						'lastupdated'  => $value->last_updated
					);
				}

				//print_obj($this->data['user_data']);die;

			} else {
				$this->data['user_data'] = '';
			}
			$this->load->view('users/vw_userlist', $this->data, false);
		} else {
			redirect(base_url());
		}
	}

	public function onCheckDuplicateUser()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$email = xss_clean($this->input->post('user_name'));
				//email
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					//$out_message = $email . " is a valid email address";
					$if_email = 1; //email valid
				} else {
					$if_email = 0;
					$out_message = $email . " is not a valid email address!!";
				}

				if ($if_email) {
					$user_exists = $this->am->getUserData(array('user_name' => $email), FALSE);
					if ($user_exists) {
						if ($email == $this->session->userdata('username')) {
							$return['user_exists'] = 3;
							$return['out_message'] = $email . "!! You can&apos;t use your current username!";
						} else {
							$return['user_exists'] = 1;
							$return['out_message'] = $email . " already exists!!";
						}
					} else {
						$return['user_exists'] = 0;
						$return['out_message'] = $email . " available";
					}
				} else {
					$return['user_exists'] = 1;
					$return['out_message'] = $out_message;
				}

				header('Content-Type: application/json');

				echo json_encode($return);
			} else {
				//exit('No direct script access allowed');
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}


	public function onGetUserProfile()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

			$user_id = decode_url(xss_clean($this->uri->segment(2)));

			$this->data['page_title'] = (isset($user_id) && $user_id != '') ? 'User' : 'Profile';
			$chkdata = array(
				'user_id'  => (isset($user_id) && $user_id != '') ? $user_id : $this->session->userdata('userid')
			);
			$userdata = $this->am->getUserData($chkdata, $many = FALSE);
			if ($userdata) {
				$this->data['user_data'] = array(
					'userid'  => $userdata->user_id,
					'user_group'  => $userdata->user_group,
					'username'  => $userdata->user_name,
					//'password'  => decrypt_it($userdata->pass),
					'fullname'  => $userdata->full_name,
					'lastlogin'  => $userdata->last_login,
					'lastloginip'  => $userdata->last_login_ip,
					'twofa_enabled'  => $userdata->twofa_enabled,
					'lastupdated'  => $userdata->last_updated
				);
				//print_obj($this->data['user_data']);die;
				$this->load->view('users/vw_profile', $this->data, false);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}

	public function onChangeUserProfile()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

			$user_id = xss_clean($this->input->post('user_id'));
			$page_title = xss_clean($this->input->post('page_title'));
			$chkdata = array('user_id'  => $user_id);

			$fullname = xss_clean($this->input->post('full_name'));
			if ($this->session->userdata('usergroup') == 1) {
				$user_group = xss_clean($this->input->post('user_group'));
			} else {
				$user_group = $this->session->userdata('usergroup');
			}

			$username = xss_clean($this->input->post('user_name'));
			$password = xss_clean($this->input->post('password'));

			if ($password != '') {
				$upd_userdata = array(
					'full_name'  => $fullname,
					'user_group'  => $user_group,
					'user_name'  => $username,
					'pass'  => encrypt_it($password),
					'last_updated'  => dtime,
					'updated_by'  => $this->session->userdata('userid')
				);
			} else {
				$upd_userdata = array(
					'full_name'  => $fullname,
					'user_group'  => $user_group,
					'user_name'  => $username,
					'last_updated'  => dtime,
					'updated_by'  => $this->session->userdata('userid')
				);
			}

			// print_obj($upd_userdata);die;

			$userdata = $this->am->getUserData($chkdata, $many = FALSE);
			if ($userdata && $username != '') {
				//update

				$upduser = $this->am->updateUser($upd_userdata, $chkdata);
				if ($upduser) {
					$this->data['update_success'] = 'Successfully updated.';
					//list

					$usrdata = $this->am->getUserData($chkdata, $many = FALSE);
					$this->data['user_data'] = array(
						'userid'  => $usrdata->user_id,
						'user_group'  => $usrdata->user_group,
						'username'  => $usrdata->user_name,
						//'password'  => decrypt_it($usrdata->pass),
						'fullname'  => $usrdata->full_name,
						'lastlogin'  => $usrdata->last_login,
						'lastloginip'  => $usrdata->last_login_ip,
						'twofa_enabled'  => $userdata->twofa_enabled,
						'lastupdated'  => $usrdata->last_updated
					);
					$setdata = array('username'  => $usrdata->user_name);
					$this->session->set_userdata($setdata);
				} else {
					$this->data['update_failure'] = 'Not updated!';
				}
				$this->data['page_title'] = $page_title;

				$this->load->view('users/vw_profile', $this->data, false);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}

	public function onCreateUserView()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {

			$this->data['page_title'] = 'User';
			$this->load->view('users/vw_createuser', $this->data, false);
		} else {
			redirect(base_url());
		}
	}

	public function onCreateUser()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$this->form_validation->set_rules('full_name', 'Full Name', 'trim|required|xss_clean|htmlentities');
				// $this->form_validation->set_rules('user_group', 'User Group', 'trim|required|xss_clean|htmlentities');
				//$this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^[0-9]{10}$/]|xss_clean|htmlentities');
				$this->form_validation->set_rules('user_name', 'Username', 'trim|required|valid_email|xss_clean|htmlentities');
				//$this->form_validation->set_rules('user_name', 'Username', 'trim|required|xss_clean|htmlentities');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|htmlentities');

				if ($this->form_validation->run() == FALSE) {
					$this->form_validation->set_error_delimiters('', '');
					$return['errors'] = validation_errors();
					$return['user_added'] = 'rule_error';
				} else {

					$fullname = xss_clean($this->input->post('full_name'));
					$user_group = '1'; //superadmin for all
					//$user_group = xss_clean($this->input->post('user_group'));
					$username = xss_clean($this->input->post('user_name'));
					$password = xss_clean($this->input->post('password'));
					// $phone = xss_clean($this->input->post('phone'));
					// $email = xss_clean($this->input->post('email'));
					$url = '';

					$chkdata = array('user_name'  => $username);
					$userdata = $this->am->getUserData($chkdata, FALSE);

					if (!$userdata) {
						//update

						$ins_userdata = array(
							'full_name'  => $fullname,
							'user_group'  => $user_group,
							'user_name'  => $username,
							'pass'  => encrypt_it($password),
							'dtime'  => dtime,
							'created_by'  => $this->session->userdata('userid')
						);
						// print_obj($ins_userdata);die;
						$adduser = $this->am->addUser($ins_userdata);

						if ($adduser) {

							//email
							$subject = 'Registered Successfully';
							$body = '';
							$body .= '<p>Hello ' . $fullname . ', </p>';
							$body .= '<p>You have successfully registered. Your login details are mentioned below. Please login.</p>';
							$body .= '<p><b>Admin URL : </b>' . $url . '</p>';
							$body .= '<p><b>Username : </b>' . $username . '</p>';
							$body .= '<p><b>Password : </b>' . decrypt_it($password) . '</p>';
							//$body .= '<p><b>OTP : </b>' . $otp . '</p>';
							$body .= '<br><p>* Please change your password after login.</p>';
							$body .= '<br><p>** This is a system generated email. Please do not reply to this email..</p>';

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
								$return['otp_status'] = 1;
								$return['otp_msg'] = 'An e-mail with OTP has been sent to your email (' . $username . ').';
							} else {
								//echo $this->email->print_debugger();die;
								$return['otp_status'] = 0;
								$return['otp_msg'] = 'E-mail not sent to ' . $username . '!';
							}
							$return['user_added'] = 'success';
						} else {
							$return['user_added'] = 'failure';
						}
					} else {
						$return['user_added'] = 'already_exists';
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

	public function onDeleteUser()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$user_id = decode_url(xss_clean($this->input->post('userid')));
				$userdata = $this->am->getUserData(array('user_id'  => $user_id), FALSE);

				if (!empty($userdata)) {
					//del

					$deluser = $this->am->delUser(array('user_id' => $user_id));

					if ($deluser) {
						$return['deleted'] = 'success';
					} else {
						$return['deleted'] = 'failure';
					}
				} else {
					$return['deleted'] = 'not_exists';
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

	public function onGetTwoFACode()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

			$this->data['page_title'] = 'Two-Factor Authentication';

			$chkdata = array(
				'user_id'  => $this->session->userdata('userid')
			);
			$userdata = $this->am->getUserData($chkdata, $many = FALSE);
			if (!empty($userdata)) {
				$this->data['user_data'] = array(
					'userid'  => $userdata->user_id,
					'user_group'  => $userdata->user_group,
					'username'  => $userdata->user_name,
					'twofa_enabled'  => $userdata->twofa_enabled
				);
				//print_obj($this->data['user_data']);die;

				if ($this->data['user_data']['twofa_enabled'] != 1) {
					$gaobj = new GoogleAuthenticator();
					$secretLength = 32;
					$name = 'BingeHQ';
					//secret key
					$this->data['gauth_secret'] = $gaobj->createSecret($secretLength);
					//qr url
					$this->data['qr_url'] = $gaobj->getQRCodeGoogleUrl($name, $this->data['gauth_secret']);
				} else {
					$this->data['gauth_secret'] = '';
					//qr url
					$this->data['qr_url'] = '';
				}



				$this->load->view('users/vw_2facode', $this->data, false);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}

	function onSet2FAuth()
	{

		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$this->form_validation->set_rules('tpasscode', 'Passcode', 'trim|required|numeric|xss_clean|htmlentities');
				$this->form_validation->set_rules('tqrcode', 'QR Code', 'trim|required|xss_clean|htmlentities');

				if ($this->form_validation->run() == FALSE) {
					$this->form_validation->set_error_delimiters('', '');
					$return['errors'] = validation_errors();
					$return['twofa'] = 'rule_error';
				} else {
					// 2 factor authentication codes....................................

					$gaobj = new GoogleAuthenticator();
					$secret = strip_tags($this->input->post('tqrcode'));
					$oneCode = strip_tags($this->input->post('tpasscode'));

					$token = $gaobj->getCode($secret);

					$checkResult = $gaobj->verifyCode($secret, $oneCode, 2); // 2 = 2*30sec clock tolerance

					if (!$checkResult) {
						$return['errors'] = 'Two-factor token Failed';
						$return['twofa'] = 'failure';
					} else {
						// Two-factor code success and now steps for username and password verification
						$param = array(
							'user_id'  => $this->session->userdata('userid')
						);

						$upData = array(
							'twofa_enabled'  => 1,
							'twofa_secret'  => $secret
						);

						$upDuser = $this->am->updateUser($upData, $param);
						if ($upDuser) {
							$return['twofa'] = 'success';
						} else {
							$return['errors'] = 'Two-factor Update Failed';
							$return['twofa'] = 'failure';
						}
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
}
