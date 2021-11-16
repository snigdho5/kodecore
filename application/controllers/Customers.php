<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customers extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			$this->data['page_title'] = 'Customers';
			$custdata = $this->am->getCustomerData(null, $many = TRUE);
			if ($custdata) {
				foreach ($custdata as $key => $value) {
					$this->data['cust_data'][] = array(
						'dtime'  => $value->dtime,
						'custid'  => $value->customer_id,
						'username'  => $value->user_name,
						'fullname'  => $value->first_name . ' ' . $value->last_name,
						'phone'  => $value->phone,
						'email'  => $value->email,
						'walletactive'  => $value->is_wallet_active,
						'walletamt'  => $value->wallet_amount,
						'lastlogin'  => $value->last_login,
						'lastloginip'  => $value->last_login_ip,
						'lastupdated'  => $value->last_updated
					);
				}

				//print_obj($this->data['cust_data']);die;

			} else {
				$this->data['cust_data'] = '';
			}
			$this->load->view('customers/vw_customer_list', $this->data, false);
		} else {
			redirect(base_url());
		}
	}

	public function onCheckDuplicateCust()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {
				$dup_type = xss_clean($this->input->post('dup_type'));

				if ($dup_type == 'username') {
					$username = xss_clean($this->input->post('user_name'));

					$cust_exists = $this->am->getCustomerData($p = array('user_name' => $username), FALSE);
					if ($cust_exists) {
						$return['if_exists'] = 1;
					} else {
						$return['if_exists'] = 0;
					}
				} else if ($dup_type == 'email') {
					$email = xss_clean($this->input->post('email'));

					//email
					if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
						//$out_message = $email . " is a valid email address";
						$if_email = 1; //email valid
					} else {
						$if_email = 0;
						$out_message = $email . " is not a valid email address!!";
					}

					if ($if_email) {
						$cust_exists = $this->am->getCustomerData(array('email' => $email), FALSE);
						if ($cust_exists) {
							$return['if_exists'] = 1;
							$return['out_message'] = $email . " already exists!!";
						} else {
							$return['if_exists'] = 0;
							$return['out_message'] = $email . " available";
						}
					} else {
						$return['if_exists'] = 1;
						$return['out_message'] = $out_message;
					}
				} else if ($dup_type == 'phone') {
					$phone = xss_clean($this->input->post('phone'));

					//phone with 10 digit
					if (preg_match('/^[0-9]{10}+$/', $phone)) {
						//$out_message = $phone . " is a valid phone" ;
						$if_phone = 1; //phone valid
					} else {
						$if_phone = 0;
						$out_message = $phone . " is not a valid phone";
					}

					if ($if_phone) {
						$cust_exists = $this->am->getCustomerData($p = array('phone' => $phone), FALSE);
						if ($cust_exists) {
							$return['if_exists'] = 1;
							$return['out_message'] = $phone . " already exists!!";
						} else {
							$return['if_exists'] = 0;
							$return['out_message'] = $phone . " available";
						}
					} else {
						$return['if_exists'] = 1;
						$return['out_message'] = $out_message;
					}
				} else {
					$return['if_exists'] = 1;
					$return['out_message'] = " something went wrong!";
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


	public function onGetCustEdit()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

			$this->data['page_title'] = 'Edit Customer';
			$customer_id = decode_url(xss_clean($this->uri->segment(2)));
			$chkdata = array(
				'customer_id'  => $customer_id
			);
			$custdata = $this->am->getCustomerData($chkdata, $many = FALSE);
			if (!empty($custdata)) {
				$this->data['user_data'] = array(
					'custid'  => $custdata->customer_id,
					'username'  => $custdata->user_name,
					'first_name'  => $custdata->first_name,
					'last_name'  => $custdata->last_name,
					'phone'  => $custdata->phone,
					'email'  => $custdata->email,
					'lastlogin'  => $custdata->last_login,
					'lastloginip'  => $custdata->last_login_ip,
					'twofa_enabled'  => $custdata->twofa_enabled,
					'lastupdated'  => $custdata->last_updated
				);
				//print_obj($this->data['user_data']);die;
				$this->load->view('customers/vw_customer_edit', $this->data, false);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}

	public function onChangeCust()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

			$this->data['page_title'] = 'Customer';
			$customer_id = xss_clean($this->input->post('cust_id'));
			$chkdata = array('customer_id'  => $customer_id);

			$first_name = xss_clean($this->input->post('first_name'));
			$last_name = xss_clean($this->input->post('last_name'));

			$username = xss_clean($this->input->post('user_name'));
			$password = xss_clean($this->input->post('password'));
			$phone = xss_clean($this->input->post('phone'));
			$email = xss_clean($this->input->post('email'));

			if ($password != '') {
				$upd_userdata = array(
					'first_name'  => $first_name,
					'last_name'  => $last_name,
					'user_name'  => $username,
					'phone'  => $phone,
					'email'  => $email,
					'pass'  => encrypt_it($password),
					'last_updated'  => dtime,
					'updated_by'  => $this->session->userdata('userid')
				);
			} else {
				$upd_userdata = array(
					'first_name'  => $first_name,
					'last_name'  => $last_name,
					'user_name'  => $username,
					'phone'  => $phone,
					'email'  => $email,
					'last_updated'  => dtime,
					'updated_by'  => $this->session->userdata('userid')
				);
			}
			// print_obj($upd_userdata);die;

			$custdata = $this->am->getCustomerData($chkdata, $many = FALSE);
			if ($custdata && $username != '') {
				//update

				$upduser = $this->am->updateCustomer($upd_userdata, $chkdata);
				if ($upduser) {
					$this->data['update_success'] = 'Successfully updated.';
					//list

					$custDataUpd = $this->am->getCustomerData($chkdata, $many = FALSE);
					$this->data['user_data'] = array(
						'custid'  => $custDataUpd->customer_id,
						'username'  => $custDataUpd->user_name,
						'first_name'  => $custDataUpd->first_name,
						'last_name'  => $custDataUpd->last_name,
						'phone'  => $custDataUpd->phone,
						'email'  => $custDataUpd->email,
						'lastlogin'  => $custDataUpd->last_login,
						'lastloginip'  => $custDataUpd->last_login_ip,
						'twofa_enabled'  => $custDataUpd->twofa_enabled,
						'lastupdated'  => $custDataUpd->last_updated
					);
				} else {
					$this->data['update_failure'] = 'Not updated!';
				}

				$this->load->view('customers/vw_customer_edit', $this->data, false);
			} else {
				redirect(base_url());
			}
		} else {
			redirect(base_url());
		}
	}

	public function onCreateCustView()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

			$this->data['page_title'] = 'Customer';
			$this->load->view('customers/vw_customer_add', $this->data, false);
		} else {
			redirect(base_url());
		}
	}

	public function onCreateCust()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean|htmlentities');
				$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean|htmlentities');
				$this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^[0-9]{10}$/]|xss_clean|htmlentities');
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|htmlentities');
				$this->form_validation->set_rules('user_name', 'Username', 'trim|required|xss_clean|htmlentities');
				$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|htmlentities');

				if ($this->form_validation->run() == FALSE) {
					$this->form_validation->set_error_delimiters('', '');
					$return['errors'] = validation_errors();
					$return['added'] = 'rule_error';
				} else {

					$first_name = xss_clean($this->input->post('first_name'));
					$last_name = xss_clean($this->input->post('last_name'));
					$username = xss_clean($this->input->post('user_name'));
					$password = xss_clean($this->input->post('password'));
					$phone = xss_clean($this->input->post('phone'));
					$email = xss_clean($this->input->post('email'));
					$username_val = xss_clean($this->input->post('username_val'));
					$email_val = xss_clean($this->input->post('email_val'));
					$phone_val = xss_clean($this->input->post('phone_val'));

					$chkdata = array('user_name'  => $username);
					$custdata = $this->am->getCustomerData($chkdata, FALSE);

					if (!$custdata) {

						if ($username_val == '0' && $email_val == '0' && $phone_val == '0') {

							//add
							$ins_userdata = array(
								'first_name'  => $first_name,
								'last_name'  => $last_name,
								'user_name'  => $username,
								'phone'  => $phone,
								'email'  => $email,
								'pass'  => encrypt_it($password),
								'dtime'  => dtime,
								'created_by'  => $this->session->userdata('userid')
							);
							// print_obj($ins_userdata);die;
							$addcust = $this->am->addCustomer($ins_userdata);

							if ($addcust) {
								//email
								$subject = 'Registered Successfully';
								$body = '';
								$body .= '<p>Hello ' .$first_name . '' . $last_name . ', </p>';
								$body .= '<p>You have successfully registered. Your login details are mentioned below. Please login.</p>';
								$body .= '<p><b>Username : </b>' . $email . '</p>';
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
								$this->email->to($email);
								//$this->email->reply_to($replyemail);
								$this->email->subject($subject);
								$this->email->message($body);
								if ($this->email->send()) {
									$return['otp_status'] = 1;
									$return['otp_msg'] = 'An e-mail with OTP has been sent to your email (' . $email . ').';
								} else {
									//echo $this->email->print_debugger();die;
									$return['otp_status'] = 0;
									$return['otp_msg'] = 'E-mail not sent to ' . $email . '!';
								}
								$return['added'] = 'success';
							} else {
								$return['added'] = 'failure';
							}
						} else {
							$msg = '';
							if ($username_val == '1') {
								$msg .= 'Please check Username!\n';
							}
							if ($email_val == '1') {
								$msg .= 'Please check Email!\n';
							}
							if ($phone_val == '1') {
								$msg .= 'Please check Phone!\n';
							}
							$return['added'] = 'rule_error';
							$return['errors'] = $msg;
						}
					} else {
						$return['added'] = 'already_exists';
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

	public function onDeleteCust()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$customer_id = decode_url(xss_clean($this->input->post('custid')));
				$custdata = $this->am->getCustomerData(array('customer_id'  => $customer_id), FALSE);

				if (!empty($custdata)) {
					//del
					$delcust = $this->am->delCustomer(array('customer_id' => $customer_id));

					if ($delcust) {
						$return['deleted'] = 'success';
					} else {
						$return['deleted'] = 'failure';
					}
					// }
					// else{
					// 	$return['deleted'] = 'billed';
					// }



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

	public function onGetCustomerKyc()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {
				$customer_id = decode_url(xss_clean($this->input->post('custid')));
				$chkdata = array(
					'customer_id'  => $customer_id
				);
				$custdata = $this->am->getCustomerData($chkdata, $many = FALSE);
				if (!empty($custdata)) {
					$return['user_data'] = array(
						'custid'  => $custdata->customer_id,
						'username'  => $custdata->user_name,
						'first_name'  => $custdata->first_name,
						'last_name'  => $custdata->last_name,
						'phone'  => $custdata->phone,
						'email'  => $custdata->email,
						'lastlogin'  => $custdata->last_login,
						'lastloginip'  => $custdata->last_login_ip,
						'twofa_enabled'  => $custdata->twofa_enabled,
						'lastupdated'  => $custdata->last_updated,
						'kyc_type' => $custdata->kyc_type,
						// 'fcm_token' => $custdata->fcm_token,
						'bank_name' => $custdata->bank_name,
						'branch_name' => $custdata->branch_name,
						'ac_no' => $custdata->ac_no,
						'ifsc' => $custdata->ifsc,
						'ac_name' => $custdata->ac_name,
						'pan_no' => $custdata->pan_no,
						'aadhaar_no' => $custdata->aadhaar_no,
						'kyc_pan_file' => ($custdata->kyc_pan_file != '') ? base_url() . 'uploads/images/' . $custdata->kyc_pan_file : '',
						'kyc_aadhaar_file' => ($custdata->kyc_aadhaar_file != '') ? base_url() . 'uploads/images/' . $custdata->kyc_aadhaar_file : ''
					);
					// print_obj($return['user_data']);die;
					$return['success'] = '1';
				} else {
					$return['success'] = '0';
					$return['user_data'] = [];
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

	public function onAckCustomerKyc()
	{
		if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
			if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

				$customer_id = xss_clean($this->input->post('custid'));
				$ack = xss_clean($this->input->post('ack'));
				$custdata = $this->am->getCustomerData(array('customer_id'  => $customer_id), FALSE);

				// print_obj($custdata);die;

				if (!empty($custdata)) {
					$chkdata = array('customer_id'  => $customer_id);
					$upd_userdata = array(
										'kyc_status'  => ($ack == 'approve')? '2': '3',
										'last_updated'  => dtime,
										'updated_by'  => $this->session->userdata('userid')
					);
					$upduser = $this->am->updateCustomer($upd_userdata, $chkdata);

					if ($upduser) {
						$return['success'] = '1';
						$return['msg'] = ($ack == 'approve')? 'Successsfully Approved!': 'Successsfully Rejected!';
					} else {
						$return['success'] = '0';
						$return['msg'] = 'Something went wrong!';
					}

				} else {
					$return['success'] = '0';
					$return['msg'] = 'User does not exist!';
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
