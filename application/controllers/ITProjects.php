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
                    //   $tds_per = $value->tds_per;
                    //   $tds_rate = $value->tds_rate;
                    //   $royalty_per = $value->royalty_per;
                    //   $royalty_rate = $value->royalty_rate;
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

    //payout
    
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
                        'amount'  => $value->subtotal_amt,
                        'user_email'  => $value->email,
                        'user_phone'  => $value->phone,
						'filepath'  => $value->filepath,
                        'proj_title'  => $value->proj_title
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
                $this->form_validation->set_rules('deductions', 'Deductions', 'trim|required|xss_clean|htmlentities');

                if ($this->form_validation->run() == FALSE) {
                    $this->form_validation->set_error_delimiters('', '');
                    $return['errors'] = validation_errors();
                    $return['added'] = 'rule_error';
                } else {

                    $customer_id = xss_clean(decode_url($this->input->post('customer_id')));
                    $remarks = xss_clean($this->input->post('remarks'));
                    $amount = xss_clean($this->input->post('amount'));
                    $proj_id = xss_clean($this->input->post('proj_id'));
                    $deductions = xss_clean($this->input->post('deductions'));

                    $chkdata = array('customer_id'  => $customer_id);
                    $custData = $this->am->getCustomerData($chkdata, FALSE);
    
                    if (!empty($custData)) {

                    $custPayoutMonth = $this->am->getITProjectPayoutMonthData($customer_id, $proj_id);

                    // print_obj($custPayoutMonth);die;

                    if(empty($custPayoutMonth)){
                        
        
                        // if ($added) {

                            $getPrivacyTnC = $this->am->getAppDetailsData(array('id' => 1));
                            //print_obj($getPrivacyTnC);die;
    
                            if (!empty($getPrivacyTnC)) {
                                $gst = $getPrivacyTnC->gst;
                                $tds = $getPrivacyTnC->tds;
                                $royalty = $getPrivacyTnC->royalty;
                            }else{
                                $gst = '0.00';
                                $tds = '0.00';
                                $royalty = '0.00';
                            }

                            $gst_rate = (($amount * $gst)/100);
                            $royalty_rate = (($amount * $royalty)/100);

                            if($deductions != '' && $deductions > 0){
                                $subtotal_amt = (($amount - $royalty_rate - $deductions) + $gst_rate);
                            }else{
                                $subtotal_amt = (($amount - $royalty_rate) + $gst_rate);
                            }

                                $addamount = ($custData->wallet_amount + $subtotal_amt);
                                
                                    $ins_userdata = array(
                                        'customer_id'  => $customer_id,
                                        'proj_id'  => $proj_id,
                                        'amount'  => $amount,
                                        'deductions'  => $deductions,
                                        'gst'  => $gst,
                                        'gst_rate'  => $gst_rate,
                                        'royalty'  => $royalty,
                                        'royalty_rate'  => $royalty_rate,
                                        'subtotal_amt'  => $subtotal_amt,
                                        'updated_wallet'  => $addamount,
                                        'remarks'  => $remarks,
                                        // 'filepath'  => $filepath,
                                        'added_dtime'  => dtime,
                                        'added_by'  => $this->session->userdata('userid')
                                    );
                                        // print_obj($ins_userdata);die;
                                    $added = $this->am->addPayout($ins_userdata);

                                    $upd_user = $this->am->updateCustomer(array('wallet_amount'  => $addamount), array('customer_id'  => $customer_id));

                                    if($upd_user){
                                        //send notification starts
                                        $userDetails  =   $this->am->getCustomerData(array('customer_id' => $customer_id));

                                        $filepath = base_url() . 'api/it-project-invoice/' . encode_url($added);
                                        
                                        $updPayout = $this->am->updatePayout(array('filepath'  => $filepath), array('payout_id' => $added));

                                        // print_obj($userDetails);die;
                                        if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                            $fcm = $userDetails->fcm_token;
                                            $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                            //$fcm = 'cNf2---6Vs9';
                                            $icon = NOTIFICATION_ICON;
                                            $notification_title = 'Payout Received!';
                                            $notification_body = 'Congrats! You have a new payout in your wallet of Rs. ' . $amount . '.';
                                            $click_action = CLICK_ACTION;

                                            $data = array(
                                                "to" => $fcm,
                                                "notification" => array(
                                                    "title" => $notification_title,
                                                    "body" => $notification_body,
                                                    "icon" => $icon,
                                                    "click_action" => $click_action
                                                )
                                            );
                                            $data_string = json_encode($data);

                                            //echo "The Json Data : " . $data_string;

                                            $headers = array(
                                                'Authorization: key=' . API_ACCESS_KEY,
                                                'Content-Type: application/json'
                                            );

                                            $ch = curl_init();

                                            curl_setopt($ch, CURLOPT_URL, FCM_URL);
                                            curl_setopt($ch, CURLOPT_POST, true);
                                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

                                            $result = curl_exec($ch);

                                            curl_close($ch);

                                            $result_ar = json_decode($result);
                                            // print_obj($result_ar);die;
                                            if (!empty($result_ar) && $result_ar->success == 1) {
                                                $logData = array(
                                                    'customer_id' => $customer_id,
                                                    "notification_title" => $notification_title,
                                                    "notification_body" => $notification_body,
                                                    "notification_event" => 'payout_inv_plan',
                                                    "dtime" => dtime
                                                );

                                                $addLog = $this->am->addNotificationLog($logData);
                                            }
                                        }
                                        //send notification ends

                                        $return['added'] = 'success';
                                        $return['msg'] = 'Payout added successfully!';
                                    }else{
                                        $return['added'] = 'failure';
                                        $return['msg'] = 'Payout not added due to some unexpected error!';
                                    }

                                    
                        // } else {
                        //         $return['added'] = 'failure';
                        //         $return['msg'] = 'Something went wrong!';
                        // }
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


    
    public function onGetITProjectInvoice(){
        
        // use spipu\html2pdf\src\Html2Pdf;
        $payout_id = decode_url(xss_clean($this->uri->segment(3)));
        
        $projectsdata = $this->am->getITProjectPayoutUserData(array('it_projects_payout.payout_id'=> $payout_id));

        // print_obj($projectsdata);die;

        if(!empty($projectsdata)){
            $this->data['payoutData'] = $projectsdata;
        }else{
            $this->data['payoutData'] = [];
        }

        $this->data['logo'] = base_url() . 'uploads/img/kodelogo.png';
        $this->data['sign'] = base_url() . 'uploads/img/kodelogo-stamp.png';

        $this->load->view('itprojects/vw_itproj_invoice', $this->data, false);

    }

    public function test_invoice(){
          //  TCPDF Integration
          $tcpdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
          // Set Title
          $tcpdf->SetTitle('Kodecore - IT Project Payout');
          // Set Header Margin
          $tcpdf->SetHeaderMargin(30);
          // Set Top Margin
          $tcpdf->SetTopMargin(20);
          // set Footer Margin
          $tcpdf->setFooterMargin(20);
          // Set Auto Page Break
        //   $tcpdf->SetAutoPageBreak(true);
          // Set Author
          $tcpdf->SetAuthor('Snigdho');
          // Set Display Mode
          $tcpdf->SetDisplayMode('real', 'default');
          // Set Write text
          // $pdf->Write(5, 'TCPDF Integration');
          // Set Output and file name

          // $proj_title = $projectsdata->proj_title;
          // $user_name = $projectsdata->first_name . ' ' . $projectsdata->last_name;
          // $email = $projectsdata->email;
          // $phone = $projectsdata->phone;
          // $amount = $projectsdata->amount;
          // $deductions = $projectsdata->deductions;
          // $gst_per = $projectsdata->gst_per;
          // $gst_rate = $projectsdata->gst_rate;
          // $royalty_per = $projectsdata->royalty_per;
          // $royalty_rate = $projectsdata->royalty_rate;
          // $subtotal = $projectsdata->subtotal_amt;
          // $dtime = $projectsdata->added_dtime;

          $logo = base_url() . 'uploads/img/kodelogo.png';


          $tcpdf->AddPage();
          
          $html = <<<EOD
            
          <table style="margin: 0 auto; border-collapse: collapse;" cellpadding="2" cellspacing="0">
   <tbody>
      <tr>
         <td
            style="font-size:24px; color: #000; text-align: center; font-weight: 600; text-transform: capitalize; margin: 0;">
            Proforma Tax Invoice
         </td>
      </tr>
      <tr>
         <td
            style="font-size:11px; color: #000; text-align: center; font-weight: 400; text-transform: capitalize; margin: 0;">
            Proforma Invoice
         </td>
      </tr>
   </tbody>
</table>
<table style="margin: 0 auto; border-collapse: collapse;" border="1" cellpadding="2" cellspacing="0">
   <tbody>
      <tr>
         <td colspan="2" rowspan="4">
            <p><img src="$logo" style=""></p>
         </td>
         <td colspan="3" rowspan="4" >
               
               <table>
               <tr>
               <td style="font-size:13px; color: #000; text-align: left; font-weight: 600; text-transform: capitalize;">KODECORE</td>
               </tr>
               <tr>
               <td style="font-size:11px; color: #000; text-align: left;  font-weight: 500; text-transform: capitalize;">Astra Tower, ANO 402, 4th Floor, <br>
               Plot No- IIC/1, Action Area- II-C <br>
               Akankha More, Newtown, Rajarhat <br>
               Kolkata</td>
               </tr>
               </table>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">GSTIN/UIN: 19FJKPK1540P1ZM</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">State Name : West Bengal, Code : 19</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">E-Mail : account@kodecore.com</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Kodecore.com</p>
         </td>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Consignee (Ship to)</p>
            <p
               style="font-size:13px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">KCIN/PIN/001</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Dated</p>
            <p
               style="font-size:13px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">22-Sep-21</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Delivery Note</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Mode/Terms of Payment</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Reference No. & Date.</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Other References</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Buyer's Order No.</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Dated</p>
         </td>
      </tr>
      <tr>
         <td colspan="5" rowspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Consignee (Ship to)</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">XYZ Pvt.Ltd</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Abc</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Abc</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">State Name : West Bengal, Code : 19</p>
         </td>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Dispatch Doc No</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Delivery Note Date</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Dispatched through</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Destination</p>
         </td>
      </tr>
      <tr>
         <td colspan="5" rowspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Buyer (Bill to)</p>
            <p
               style="font-size:13px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">XYZ Pvt.Ltd</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Buyer (Bill to)</p>
            <p
               style="font-size:13px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">Abc</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Abc</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">NBCV</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">GSTIN/UIN : XXXXXXXXXXXXX</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">
            </p>
         </td>
      </tr>
      <tr>
         <td colspan="9" style="vertical-align: top;">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Terms of Delivery</p>
         </td>
      </tr>
      <tr>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Sl No</p>
         </td>
         <td colspan="4">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Description of Services</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">HSN/SAC</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Quantity</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Rate</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">per</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Amount</p>
         </td>
      </tr>
      <tr>
         <td style="vertical-align: top">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">01</p>
         </td>
         <td colspan="4">
            <p
               style="font-size:13px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">PDF to DOC Full Slot Monthly Billing</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">(-) Royalty (8%)</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">(-) TDS (10%)</p>
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">CGST</p>
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">SGST</p>
         </td>
         <td colspan="2" style="vertical-align: top">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">9985</p>
            <p></p>
         </td>
         <td colspan="2" style="vertical-align: top">
            <p>&nbsp;</p>
         </td>
         <td colspan="3" style="vertical-align: top">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">9</p>
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">9</p>
         </td>
         <td style="vertical-align: top">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">%</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">%</p>
         </td>
         <td style="vertical-align: top">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">94,067.80</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">8,880.00</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">10,212.60</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">&nbsp;</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">8,466.10
            </p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">8,466.10</p>
         </td>
      </tr>
      <tr>
         <td>
            <p>&nbsp;</p>
         </td>
         <td colspan="4">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Total</p>
         </td>
         <td colspan="2">
            <p>&nbsp;</p>
         </td>
         <td colspan="2">
            <p>&nbsp;</p>
         </td>
         <td colspan="3">
            <p>&nbsp;</p>
         </td>
         <td>
            <p>&nbsp;</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">₹ 91,908.00</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Amount Chargeable (in words) E. & O.E</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">INR Ninety One Thousand Nine Hundred Eight Only </p>
         </td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">E. & O.E</p>
         </td>
      </tr>
      <tr>
         <td colspan="1" rowspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">HSN/SAC</p>
         </td>
         <td rowspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Taxable Value</p>
         </td>
         <td colspan="4">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Central Tax</p>
         </td>
         <td colspan="5">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">State Tax</p>
         </td>
         <td rowspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Total Tax Amount</p>
         </td>
      </tr>
      <tr>
         <td colspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">rate</p>
         </td>
         <td colspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">AM</p>
         </td>
         <td colspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">RT</p>
         </td>
         <td colspan="3">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">AM</p>
         </td>
      </tr>
      <tr>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">9985</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">94,067.80</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">9%</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">8,466.10</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">9%</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">8,466.10</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">16,932.20</p>
         </td>
      </tr>
      <tr>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">Total</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">94,067.80</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">&nbsp;</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">8,466.10</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">&nbsp;</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">8,466.10</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">16,932.20</p>
         </td>
      </tr>
      <tr>
         <td colspan="14">
            <p
               style="font-size:12px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">Tax Amount (in words) : <b>INR Sixteen Thousand Nine Hundred Thirty Two and Twenty paise Only</b></p>
         </td>
      </tr>
      <tr>
         <td colspan="6"></td>
         <td colspan="8">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">Company's Bank Details</p>
         </td>
      </tr>
      <tr>
         <td colspan="6"></td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">A/c Holder's Name: <b>TRUVISORY KODECORE (OPC) PVT.Ltd</b></p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 500; text-transform: capitalize;"> Bank Name:</p>
         </td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"> Bank NameBank Name</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 500; text-transform: capitalize;"> A/c No:</p>
         </td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">X Y Z B A N K </p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Branch & IFS Code:</p>
         </td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">X Y Z B r a n c h</p>
         </td>
      </tr>
      <tr>
         <td colspan="7">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Declaration</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">We declare that this invoice shows the actual price of the goods described and that all particulars
               are true and correct.</p>
         </td>
         <td colspan="7" style="text-align: right;">
            <p style="font-size:11px; color: #000; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">for KODECORE</p>
            <p style="margin: 5px 0px; "><img src="kodelogo-stamp.png" style=" "></p>
            <p style="font-size:11px; color: #000; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">Authorised Signatory</p>
         </td>
      </tr>
   </tbody>
</table>
<table style="margin: 0 auto; border-collapse: collapse;" cellpadding="2" cellspacing="0">
   <tbody>
      <tr>
         <td
            style="font-size:13px; color: #000; text-align: center; font-weight: 600; text-transform: capitalize; margin: 0;">Subject To Kolkata <br>Jurisdiction</td>
      </tr>
      <tr>
         <td
            style="font-size:11px; color: #000; text-align: center; font-weight: 400; text-transform: capitalize; margin: 0;">This Is A Kodecore Original Invoice </td>
      </tr>
   </tbody>
</table>
          
EOD;
        //   $tcpdf->writeHTML($html);
          $tcpdf->writeHTML($html, true, false, true, false, '');


          $filename = 'IT_Project_Payout_'.date("YmdHis", time()) .'.pdf';
          $filepath = base_url().'uploads/invoices/'.$filename;

          $fullname = ABS_PATH . $filename;
          
          $tcpdf->Output($fullname, 'F');

          echo $filepath;die;
    }


    public function onGetITProjectByUser()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $customer_id = decode_url(xss_clean($this->input->post('customer_id')));
                $projData = $this->am->getCustomerProjects(array('customers_it_projects.customer_id'  => $customer_id), TRUE);
                $options = '<option value="">Select</option>';

                // print_obj($projData);

                if (!empty($projData)) {
                 
                    foreach ($projData as $key => $value) {
                        $proj_buy_date = $value->added_dtime;
                        $proj_duration = $value->proj_duration;
                        $endDate = $value->end_date;

                        $options .= '<option value="' . $value->proj_id . '">' . $value->proj_title . ' [Start Date: ' . $proj_buy_date .' - End Date: ' . $endDate .']</option>';
                    }

                    // print_obj($options);die;
                    $return['itproj_data'] = $options;

                    $return['status'] = '1';
                } else {
                    $return['itproj_data'] = [];
                    $return['status'] = '0';
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
