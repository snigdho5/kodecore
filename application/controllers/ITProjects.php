<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ITProjects extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            $this->data['page_title'] = 'IT Projects';
            $projectsdata = $this->am->getProjectData(null, $many = TRUE);
            if (!empty($projectsdata)) {
                foreach ($projectsdata as $key => $value) {
                    $this->data['proj_data'][] = array(
                        'dtime'  => $value->added_dtime,
                        'projid'  => $value->proj_id,
                        'title'  => $value->proj_title,
                        'description'  => $value->proj_description,
                        'amount'  => $value->proj_amount,
                        'duration'  => $value->proj_duration,
                        'editeddtime'  => $value->edited_dtime
                    );
                }

                //print_obj($this->data['proj_data']);die;

            } else {
                $this->data['proj_data'] = '';
            }
            $this->load->view('itprojects/vw_project_list', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    public function onCheckDuplicateProject()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $title = xss_clean($this->input->post('title'));

                $project_exists = $this->am->getProjectData(array('proj_title' => $title), FALSE);
                if ($project_exists) {
                        $return['if_exists'] = 1;
                } else {
                    $return['if_exists'] = 0;
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


    public function onGetProjectEdit()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Edit Project';
            $proj_id = decode_url(xss_clean($this->uri->segment(2)));
            $chkdata = array(
                'proj_id'  => $proj_id
            );
            $projectsdata = $this->am->getProjectData($chkdata, $many = FALSE);
            if (!empty($projectsdata)) {
                $this->data['proj_data'] = array(
                    'dtime'  => $projectsdata->added_dtime,
                    'projid'  => $projectsdata->proj_id,
                    'title'  => $projectsdata->proj_title,
                    'description'  => $projectsdata->proj_description,
                    'amount'  => $projectsdata->proj_amount,
                    'duration'  => $projectsdata->proj_duration,
                    'payment_breakup'  => $projectsdata->payment_breakup,
                    'enter_breakup'  => $projectsdata->enter_breakup,
                    'enter_breakup2'  => $projectsdata->enter_breakup2,
                    'first_installment_amt'  => $projectsdata->first_installment_amt,
                    'last_installment_amt'  => $projectsdata->last_installment_amt,
                    'editeddtime'  => $projectsdata->edited_dtime
                );
                //print_obj($this->data['proj_data']);die;
                $this->load->view('itprojects/vw_project_edit', $this->data, false);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    public function onChangeProject()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Customer';
            $proj_id = xss_clean($this->input->post('projid'));
            $chkdata = array('proj_id'  => $proj_id);

            $title = xss_clean($this->input->post('title'));
            $description = xss_clean($this->input->post('description'));
            $amount = xss_clean($this->input->post('amount'));
            $duration = xss_clean($this->input->post('duration'));
            $payment_breakup = xss_clean($this->input->post('payment_breakup'));
            $enter_breakup = xss_clean($this->input->post('enter_breakup'));
            $enter_breakup2 = xss_clean($this->input->post('enter_breakup2'));
            $first_installment_amt = xss_clean($this->input->post('first_installment_amt'));
            $last_installment_amt = xss_clean($this->input->post('last_installment_amt'));


            $upd_userdata = array(
                'proj_title'  => $title,
                'proj_description'  => $description,
                'proj_amount'  => $amount,
                'proj_duration'  => $duration,
                'payment_breakup'  => $payment_breakup,
                'enter_breakup'  => $enter_breakup,
                'enter_breakup2'  => $enter_breakup2,
                'first_installment_amt'  => $first_installment_amt,
                'last_installment_amt'  => $last_installment_amt,
                'edited_dtime'  => dtime,
                'edited_by'  => $this->session->userdata('userid')
            );
            // print_obj($upd_userdata);die;

            $projdata = $this->am->getProjectData($chkdata, FALSE);
            if (!empty($projdata)) {
                //update

                $upduser = $this->am->updateProject($upd_userdata, $chkdata);
                if ($upduser) {
                    $this->data['update_success'] = 'Successfully updated.';
                    //list

                    $projDataUpd = $this->am->getProjectData($chkdata, FALSE);
                    $this->data['proj_data'] = array(
                        'dtime'  => $projDataUpd->added_dtime,
                        'projid'  => $projDataUpd->proj_id,
                        'title'  => $projDataUpd->proj_title,
                        'description'  => $projDataUpd->proj_description,
                        'amount'  => $projDataUpd->proj_amount,
                        'duration'  => $projDataUpd->proj_duration,
                        'payment_breakup'  => $projDataUpd->payment_breakup,
                        'enter_breakup'  => $projDataUpd->enter_breakup,
                        'enter_breakup2'  => $projDataUpd->enter_breakup2,
                        'first_installment_amt'  => $projDataUpd->first_installment_amt,
                        'last_installment_amt'  => $projDataUpd->last_installment_amt,
                        'editeddtime'  => $projDataUpd->edited_dtime
                    );
                } else {
                    $this->data['update_failure'] = 'Not updated!';
                }

                // $this->load->view('itprojects/vw_project_edit', $this->data, false);
                redirect(base_url('edit-project/'.encode_url($proj_id)));
            } else {
                redirect(base_url('edit-project/'.encode_url($proj_id)));
            }
        } else {
            redirect(base_url());
        }
    }

    public function onCreateProjectView()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Project';
            $this->load->view('itprojects/vw_project_add', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    public function onCreateProject()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean|htmlentities');
                $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean|htmlentities');
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean|htmlentities');
                $this->form_validation->set_rules('duration', 'Duration', 'trim|required|numeric|xss_clean|htmlentities');
                $this->form_validation->set_rules('payment_breakup', 'Payment Breakup', 'trim|required|numeric|xss_clean|htmlentities');

                if ($this->form_validation->run() == FALSE) {
                    $this->form_validation->set_error_delimiters('', '');
                    $return['errors'] = validation_errors();
                    $return['added'] = 'rule_error';
                } else {

                    $title = xss_clean($this->input->post('title'));
                    $description = xss_clean($this->input->post('description'));
                    $amount = xss_clean($this->input->post('amount'));
                    $duration = xss_clean($this->input->post('duration'));
                    $payment_breakup = xss_clean($this->input->post('payment_breakup'));
                    $enter_breakup = xss_clean($this->input->post('enter_breakup'));
                    $enter_breakup2 = xss_clean($this->input->post('enter_breakup2'));
                    $first_installment_amt = xss_clean($this->input->post('first_installment_amt'));
                    $last_installment_amt = xss_clean($this->input->post('last_installment_amt'));

                    if($payment_breakup == '2' && $enter_breakup == '' && $enter_breakup == 0 && $enter_breakup2 == '' && $enter_breakup2 == 0){
                        $return['added'] = 'breakup_blank';
                        $return['msg'] = 'You have selected Installment option. Please enter Breakup!';
                    }else{

                        $chkdata = array('proj_title'  => $title);
                        $projdata = $this->am->getProjectData($chkdata, FALSE);
    
                        if (!$projdata) {
                            //add
    
                            $ins_userdata = array(
                                'proj_title'  => $title,
                                'proj_description'  => $description,
                                'proj_amount'  => $amount,
                                'proj_duration'  => $duration,
                                'payment_breakup'  => $payment_breakup,
                                'enter_breakup'  => $enter_breakup,
                                'enter_breakup2'  => $enter_breakup2,
                                'first_installment_amt'  => $first_installment_amt,
                                'last_installment_amt'  => $last_installment_amt,
                                'added_dtime'  => dtime,
                                'added_by'  => $this->session->userdata('userid')
                            );
                            // print_obj($ins_userdata);die;
                            $addproj = $this->am->addProject($ins_userdata);
    
                            if ($addproj) {
                                $return['added'] = 'success';
                                $return['msg'] = 'Project added successfully!';
                            } else {
                                $return['added'] = 'failure';
                                $return['msg'] = 'Something went wrong!';
                            }
                        } else {
                            $return['added'] = 'already_exists';
                            $return['msg'] = 'Project already exists!';
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

    public function onDeleteProject()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $proj_id = decode_url(xss_clean($this->input->post('projid')));
                $projdata = $this->am->getProjectData(array('proj_id'  => $proj_id), FALSE);

                if (!empty($projdata)) {
                    //del
                    $delproj = $this->am->delProject(array('proj_id' => $proj_id));

                    if ($delproj) {
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

    public function onGetAppliedProject()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            $this->data['page_title'] = 'Applied Users for IT Projects';
            $proj_id = decode_url(xss_clean($this->uri->segment(2)));

            $projectsdata = $this->am->getCustomerProjects(array('customers_it_projects.proj_id'=> $proj_id), TRUE);
            if (!empty($projectsdata)) {
                foreach ($projectsdata as $key => $value) {

                      //  TCPDF Integration
                      $tcpdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
                      // Set Title
                      $tcpdf->SetTitle('Kodecore - IT Project Invoice');
                      // Set Header Margin
                      $tcpdf->SetHeaderMargin(30);
                      // Set Top Margin
                      $tcpdf->SetTopMargin(20);
                      // set Footer Margin
                      $tcpdf->setFooterMargin(20);
                      // Set Auto Page Break
                      $tcpdf->SetAutoPageBreak(true);
                      // Set Author
                      $tcpdf->SetAuthor('Snigdho');
                      // Set Display Mode
                      $tcpdf->SetDisplayMode('real', 'default');
                      // Set Write text
                      // $pdf->Write(5, 'TCPDF Integration');
                      // Set Output and file name

                      $proj_title = $value->proj_title;
                      $user_name = $value->first_name . ' ' . $value->last_name;
                      $email = $value->email;
                      $phone = $value->phone;
                      $project_amount = $value->proj_amount;
                      $subtotal = $value->subtotal;
                      $gst_per = $value->gst_per;
                      $gst_rate = $value->gst_rate;
                      $tds_per = $value->tds_per;
                      $tds_rate = $value->tds_rate;
                      $royalty_per = $value->royalty_per;
                      $royalty_rate = $value->royalty_rate;
                      $received_amount = $value->received_amount;
                      $dtime = $value->added_dtime;
                      $appl_status = ($value->application_status = 0)?'Applied':'Approved';
                      $pay_status = ($value->payment_status = 1)?'Paid':'Not Paid';

                      if($value->installment_serial == 1){
                          $ins_msg = '(First Installment)';

                          $installment_tr = '
                          <tr>
                          <td style="width:20%; height: 20px;">Installment #:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">'.$ins_msg.'</td>
                          </tr>';
                      }else if ($value->installment_serial == 2){
                          $ins_msg = '(Last Installment)';

                          $installment_tr = '
                          <tr>
                          <td style="width:20%; height: 20px;">Installment #:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">'.$ins_msg.'</td>
                          </tr>';
                      }else{
                          $ins_msg = '';
                          
                          $installment_tr = '
                          <tr>
                          <td style="width:20%; height: 20px;">Installment / Full:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">Full Payment</td>
                          </tr>';
                      }
                      

                      $tcpdf->AddPage();
                      
                      $html = <<<EOD
                      <center>
                      <table border="0" cellpadding="5" style="border: 1px solid #ccc; padding: 3%; font-family: arial; font-size: 14px;">
                      <tbody>
                          <tr>
                          <td style="text-align: center; height: 30px; font-size: 25px; font-weight: bold;" colspan="2">Receipt</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">IT Project:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$proj_title</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">Date:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$dtime</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">Name:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$user_name</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">Email:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$email</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">Phone:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$phone</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">Payment Status:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$pay_status</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">Status:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$appl_status</td>
                          </tr>

                          $installment_tr

                          
                          <tr>
                          <td style="width:20%; height: 20px;">Project Amount:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$project_amount</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">Base Amount:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$subtotal</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">GST ($gst_per %):</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">+ $gst_rate</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">TDS ($tds_per %):</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">+ $tds_rate</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">Royalty ($royalty_per %):</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">- $royalty_rate</td>
                          </tr>

                          <tr>
                          <td style="width:20%; height: 20px;">Received Amount:</td>
                          <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$received_amount</td>
                          </tr>
                      </tbody>
                      </table>

                      
                      </center>
                      EOD;
                      $tcpdf->writeHTML($html);


                      $filename = 'IT_Project_'.$key.'_'.date("YmdHis", time()) .'.pdf';
                      $filepath = base_url().'uploads/invoices/'.$filename;

                      $fullname = ABS_PATH . $filename;
                      
                      $tcpdf->Output($fullname, 'F');

                      // echo APPPATH.'uploads/invoices/'.$filename; die;

                    $this->data['applied_data'][] = array(
                        'row_id'  => $value->id,
                        'dtime'  => $value->added_dtime,
                        'projid'  => $value->proj_id,
                        'projtitle'  => $value->proj_title,
                        'custid'  => $value->customer_id,
                        'user_name'  => $value->first_name . ' ' . $value->last_name,
                        'user_email'  => $value->email,
                        'user_phone'  => $value->phone,
                        'project_amount'  => $value->project_amount,
                        'received_amount'  => $value->received_amount,
                        'application_status'  => $value->application_status,
                        'payment_status'  => $value->payment_status,
                        'payment_mode'  => $value->payment_mode,
                        'filepath'  => $filepath,
                        'added_dtime'  => $value->added_dtime
                    );
                }

                // print_obj($this->data['applied_data']);die;

            } else {
                $this->data['applied_data'] = '';
            }
            $this->load->view('itprojects/vw_applied_users_list', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    
    public function onGetPayoutITProject()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            $this->data['page_title'] = 'IT Projects - Monthly Payout';
            $payoutdata = $this->am->getITProjectPayoutUserData(null, $many = TRUE);
            if (!empty($payoutdata)) {
                foreach ($payoutdata as $key => $value) {
                    $this->data['payout_data'][] = array(
                        'dtime'  => $value->added_dtime,
                        'payoutid'  => $value->payout_id,
                        'customer_id'  => $value->customer_id,
                        'user_fullname'  => $value->first_name . ' ' . $value->last_name,
                        'remarks'  => $value->remarks,
                        'amount'  => $value->amount,
                        'user_email'  => $value->email,
                        'user_phone'  => $value->phone,
                    );
                }

                //print_obj($this->data['payout_data']);die;

            } else {
                $this->data['payout_data'] = '';
            }
            $this->load->view('itprojects/monthly_payout_it_projects', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    
    
    public function onGetNewPayoutITProject()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            $this->data['page_title'] = 'IT Projects - New Monthly Payout';
            $custdata = $this->am->getCustomerData(null, TRUE, 'first_name', 'ASC');
			if (!empty($custdata)) {
				foreach ($custdata as $key => $value) {
					$this->data['cust_data'][] = array(
						'dtime'  => $value->dtime,
						'custid'  => encode_url($value->customer_id),
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
            $this->load->view('itprojects/new_monthly_payout_it_projects', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    public function onSetNewPayoutITProject()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $this->form_validation->set_rules('customer_id', 'Customer', 'trim|required|xss_clean|htmlentities');
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean|htmlentities');
                $this->form_validation->set_rules('proj_id', 'IT Project', 'trim|required|numeric|xss_clean|htmlentities');
                // $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|htmlentities');

                if ($this->form_validation->run() == FALSE) {
                    $this->form_validation->set_error_delimiters('', '');
                    $return['errors'] = validation_errors();
                    $return['added'] = 'rule_error';
                } else {

                    $customer_id = xss_clean(decode_url($this->input->post('customer_id')));
                    $remarks = xss_clean($this->input->post('remarks'));
                    $amount = xss_clean($this->input->post('amount'));
                    $proj_id = xss_clean($this->input->post('proj_id'));

                    $chkdata = array('customer_id'  => $customer_id);
                    $custData = $this->am->getCustomerData($chkdata, FALSE);
    
                    if (!empty($custData)) {

                    $custPayoutMonth = $this->am->getITProjectPayoutMonthData($customer_id, $proj_id);

                    // print_obj($custPayoutMonth);die;

                    if(empty($custPayoutMonth)){
                        $ins_userdata = array(
                            'customer_id'  => $customer_id,
                            'proj_id'  => $proj_id,
                            'amount'  => $amount,
                            'remarks'  => $remarks,
                            'added_dtime'  => dtime,
                            'added_by'  => $this->session->userdata('userid')
                        );
                            // print_obj($ins_userdata);die;
                        $added = $this->am->addPayout($ins_userdata);
        
                        if ($added) {

                                    $addamt = ($custData->wallet_amount + $amount);
                                    $upd_user = $this->am->updateCustomer(array('wallet_amount'  => $addamt), array('customer_id'  => $customer_id));

                                    $return['added'] = 'success';
                                    $return['msg'] = 'Payout added successfully!';
                        } else {
                                    $return['added'] = 'failure';
                                    $return['msg'] = 'Something went wrong!';
                        }
                    }else{
                        $return['added'] = 'month_paid';
                        $return['msg'] = 'Already paid to customer for this month on ' . $custPayoutMonth->added_dtime . ', Rs. ' . $custPayoutMonth->amount . '!';
                    }
    
                    
                } else {
                        $return['added'] = 'already_exists';
                        $return['msg'] = 'Customer does not exist!';
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
