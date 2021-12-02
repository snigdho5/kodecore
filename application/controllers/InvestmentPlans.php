<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InvestmentPlans extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            $this->data['page_title'] = 'Investment Plans';
            $invData = $this->am->getInvPlansData(null, TRUE, 'order_', 'ASC');

            if ($invData) {
                foreach ($invData as $key => $value) {
                    $this->data['inv_plan_data'][] = array(
                        'planid'  => $value->plan_id,
                        'name'  => $value->plan_name,
                        'summary'  => $value->plan_summary,
                        'duration'  => $value->duration,
                        'amount'  => $value->amount,
                        'description'  => $value->plan_description,
                        'return_rate'  => $value->plan_return_rate,
                        'dtime'  => $value->added_dtime,
                        'status'  => $value->status
                    );
                }

                //print_obj($this->data['inv_plan_data']);die;

            } else {
                $this->data['inv_plan_data'] = '';
            }
            $this->load->view('investmentplans/vw_invplan_list', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    public function onCheckDuplicateInvPlan()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $title = xss_clean($this->input->post('title'));

                $if_exists = $this->am->getInvPlansData(array('plan_name' => $title), FALSE);
                if ($if_exists) {
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


    public function onGetInvPlanEdit()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Edit Investment Plan';
            $plan_id = decode_url(xss_clean($this->uri->segment(2)));
            $chkdata = array(
                'plan_id'  => $plan_id
            );
            $invplansData = $this->am->getInvPlansData($chkdata, FALSE);
            if ($invplansData) {
                $this->data['inv_plan_data'] = array(
                    'planid'  => $invplansData->plan_id,
                    'title'  => $invplansData->plan_name,
                    'summary'  => $invplansData->plan_summary,
                    'duration'  => $invplansData->duration,
                    'amount'  => $invplansData->amount,
                    'description'  => $invplansData->plan_description,
                    'return_rate'  => $invplansData->plan_return_rate,
                    'dtime'  => $invplansData->added_dtime,
                    'status'  => $invplansData->status
                );
                //print_obj($this->data['inv_plan_data']);die;
                $this->load->view('investmentplans/vw_invplan_edit', $this->data, false);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    public function onChangeInvPlan()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Investment Plan';
            $plan_id = xss_clean($this->input->post('planid'));
            $chkdata = array('plan_id'  => $plan_id);

            $plan_name = xss_clean($this->input->post('title'));
            $plan_description = xss_clean($this->input->post('description'));
            $plan_summary = xss_clean($this->input->post('summary'));
            $plan_return_rate = xss_clean($this->input->post('return_rate'));
            $duration = xss_clean($this->input->post('duration'));
            $amount = xss_clean($this->input->post('amount'));


            $upd_data = array(
                'plan_name'  => $plan_name,
                'plan_description'  => $plan_description,
                'plan_summary'  => $plan_summary,
                'plan_return_rate'  => $plan_return_rate,
                'duration'  => $duration,
                'amount'  => $amount,
                'edited_dtime'  => dtime,
                'edited_by'  => $this->session->userdata('userid')
            );
            // print_obj($upd_data);die;

            $invplandata = $this->am->getInvPlansData($chkdata, FALSE);
            if ($invplandata) {
                //update

                $updinv = $this->am->updateInvPlan($upd_data, $chkdata);
                if ($updinv) {
                    $this->data['update_success'] = 'Successfully updated.';
                    //list

                    $invplansData = $this->am->getInvPlansData($chkdata, FALSE);
                    $this->data['inv_plan_data'] = array(
                        'planid'  => $invplansData->plan_id,
                        'title'  => $invplansData->plan_name,
                        'summary'  => $invplansData->plan_summary,
                        'description'  => $invplansData->plan_description,
                        'return_rate'  => $invplansData->plan_return_rate,
                        'duration'  => $invplansData->duration,
                        'amount'  => $invplansData->amount,
                        'dtime'  => $invplansData->added_dtime,
                        'status'  => $invplansData->status
                    );
                } else {
                    $this->data['update_failure'] = 'Not updated!';
                }
                redirect(base_url('edit-investment-plan/' . encode_url($plan_id)));
                // $this->load->view('investmentplans/vw_invplan_edit', $this->data, false);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    public function onCreateInvPlanView()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Investment Plan';
            $this->load->view('investmentplans/vw_invplan_add', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    public function onCreateInvPlan()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean|htmlentities');
                $this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean|htmlentities');
                $this->form_validation->set_rules('summary', 'Summary', 'trim|required|xss_clean|htmlentities');
                $this->form_validation->set_rules('return_rate', 'Return Rate', 'trim|required|numeric|xss_clean|htmlentities');
                $this->form_validation->set_rules('duration', 'Duration', 'trim|required|numeric|xss_clean|htmlentities');
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean|htmlentities');

                if ($this->form_validation->run() == FALSE) {
                    $this->form_validation->set_error_delimiters('', '');
                    $return['errors'] = validation_errors();
                    $return['added'] = 'rule_error';
                } else {

                    $plan_name = xss_clean($this->input->post('title'));
                    $plan_description = xss_clean($this->input->post('description'));
                    $plan_summary = xss_clean($this->input->post('summary'));
                    $plan_return_rate = xss_clean($this->input->post('return_rate'));
                    $duration = xss_clean($this->input->post('duration'));
                    $amount = xss_clean($this->input->post('amount'));

                    $chkdata = array('plan_name'  => $plan_name);
                    $plandata = $this->am->getInvPlansData($chkdata, FALSE);

                    if (!$plandata) {
                        //add

                        $ins_array = array(
                            'plan_name'  => $plan_name,
                            'plan_description'  => $plan_description,
                            'plan_summary'  => $plan_summary,
                            'plan_return_rate'  => $plan_return_rate,
                            'duration'  => $duration,
                            'amount'  => $amount,
                            'added_dtime'  => dtime,
                            'added_by'  => $this->session->userdata('userid')
                        );
                        // print_obj($ins_userdata);die;
                        $added = $this->am->addInvPlan($ins_array);

                        if ($added) {
                            $return['added'] = 'success';
                        } else {
                            $return['added'] = 'failure';
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

    public function onDeleteInvPlan()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $plan_id = decode_url(xss_clean($this->input->post('planid')));
                $getData = $this->am->getInvPlansData(array('plan_id'  => $plan_id), FALSE);

                if (!empty($getData)) {
                    //del
                    $del = $this->am->delInvPlan(array('plan_id' => $plan_id));

                    if ($del) {
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

    public function onGetAppliedInvPlan()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            $this->data['page_title'] = 'Applied Users for Investment Plan';
            $plan_id = decode_url(xss_clean($this->uri->segment(2)));

            $plansdata = $this->am->getCustomerInvesmentPlans(array('customers_investment_plans.plan_id'=> $plan_id), TRUE);
            if (!empty($plansdata)) {
                foreach ($plansdata as $key => $value) {
                    $this->data['applied_data'][] = array(
                        'row_id'  => $value->id,
                        'dtime'  => $value->added_dtime,
                        'planid'  => $value->plan_id,
                        'plantitle'  => $value->plan_name,
                        'custid'  => $value->customer_id,
                        'user_name'  => $value->first_name . ' ' . $value->last_name,
                        'user_email'  => $value->email,
                        'user_phone'  => $value->phone,
                        //'actual_amount'  => $value->actual_amount,
                        'received_amount'  => $value->received_amount,
                        'application_status'  => $value->application_status,
                        'payment_status'  => $value->payment_status,
                        'payment_mode'  => $value->payment_mode,
                        'added_dtime'  => $value->added_dtime
                    );
                }

                // print_obj($this->data['applied_data']);die;

            } else {
                $this->data['applied_data'] = '';
            }
            $this->load->view('investmentplans/vw_applied_users_list', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    //payout
       
    public function onGetPayoutInvPlan()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            $this->data['page_title'] = 'Investment Plans - Monthly Payout';
            $payoutdata = $this->am->getInvPlanPayoutUserData(null, $many = TRUE);
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
                        'plan_name'  => $value->plan_name
                    );
                }

                //print_obj($this->data['payout_data']);die;

            } else {
                $this->data['payout_data'] = '';
            }
            $this->load->view('investmentplans/monthly_payout_invplan', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    
    
    public function onGetNewPayoutInvPlan()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            $this->data['page_title'] = 'Investment Plans - New Monthly Payout';
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
            $this->load->view('investmentplans/new_monthly_payout_invplan', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    public function onSetNewPayoutInvPlan()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $this->form_validation->set_rules('customer_id', 'Customer', 'trim|required|xss_clean|htmlentities');
                $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean|htmlentities');
                $this->form_validation->set_rules('plan_id', 'Investment Plan', 'trim|required|numeric|xss_clean|htmlentities');
                $this->form_validation->set_rules('remarks', 'Remarks', 'trim|required|xss_clean|htmlentities');
                // $this->form_validation->set_rules('deductions', 'Deductions', 'trim|required|xss_clean|htmlentities');

                if ($this->form_validation->run() == FALSE) {
                    $this->form_validation->set_error_delimiters('', '');
                    $return['errors'] = validation_errors();
                    $return['added'] = 'rule_error';
                } else {

                    $customer_id = xss_clean(decode_url($this->input->post('customer_id')));
                    $remarks = xss_clean($this->input->post('remarks'));
                    $amount = xss_clean($this->input->post('amount'));
                    $plan_id = xss_clean($this->input->post('plan_id'));
                    // $deductions = xss_clean($this->input->post('deductions'));

                    $chkdata = array('customer_id'  => $customer_id);
                    $custData = $this->am->getCustomerData($chkdata, FALSE);
    
                    if (!empty($custData)) {

                    $custPayoutMonth = $this->am->getInvPlanPayoutMonthData($customer_id, $plan_id);

                    // print_obj($custPayoutMonth);die;

                    if(empty($custPayoutMonth)){
                        
        
                        // if ($added) {

                            $getPrivacyTnC = $this->am->getAppDetailsData(array('id' => 1));
                            //print_obj($getPrivacyTnC);die;
    
                            if (!empty($getPrivacyTnC)) {
                                $gst = $getPrivacyTnC->gst;
                                $tds = $getPrivacyTnC->tds;
                                $admin_charges = $getPrivacyTnC->admin_charges;
                            }else{
                                $gst = '0.00';
                                $tds = '0.00';
                                $royalty = '0.00';
                            }

                            $tds_rate = (($amount * $tds)/100);
                            $admin_charges_rate = (($amount * $admin_charges)/100);

                            // if($deductions != '' && $deductions > 0){
                            //     $subtotal_amt = (($amount - $royalty_rate - $deductions) + $gst_rate);
                            // }else{
                                $subtotal_amt = ($amount + $admin_charges_rate + $tds_rate);
                            // }


                                $addamount = ($custData->wallet_amount + $subtotal_amt);

                                    $ins_userdata = array(
                                        'customer_id'  => $customer_id,
                                        'plan_id'  => $plan_id,
                                        'amount'  => $amount,
                                        // 'deductions'  => $deductions,
                                        'tds'  => $tds,
                                        'tds_rate'  => $tds_rate,
                                        'admin_charges'  => $admin_charges,
                                        'admin_charges_rate'  => $admin_charges_rate,
                                        'subtotal_amt'  => $subtotal_amt,
                                        'updated_wallet'  => $addamount,
                                        'remarks'  => $remarks,
                                        'added_dtime'  => dtime,
                                        'added_by'  => $this->session->userdata('userid')
                                    );
                                        // print_obj($ins_userdata);die;
                                    $added = $this->am->addPayoutInvPlan($ins_userdata);

                                    $upd_user = $this->am->updateCustomer(array('wallet_amount'  => $addamount), array('customer_id'  => $customer_id));

                                    if($upd_user){
                                        //send notification starts
                                        $userDetails  =   $this->am->getCustomerData(array('customer_id' => $customer_id));

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

    public function onGetInvPlanByUser()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $customer_id = decode_url(xss_clean($this->input->post('customer_id')));
                $invPlanData = $this->am->getCustomerInvesmentPlans(array('customers_investment_plans.customer_id'  => $customer_id), TRUE);
                $options = '<option value="">Select</option>';

                // print_obj($invPlanData);

                if (!empty($invPlanData)) {
                 
                    foreach ($invPlanData as $key => $value) {
                        $proj_buy_date = $value->added_dtime;
                        $proj_duration = $value->duration;
                        $endDate = $value->end_date;

                        $options .= '<option value="' . $value->plan_id . '">' . $value->plan_name . ' [Start Date: ' . $proj_buy_date .' - End Date: ' . $endDate .']</option>';
                    }

                    // print_obj($options);die;
                    $return['inv_data'] = $options;

                    $return['status'] = '1';
                } else {
                    $return['inv_data'] = [];
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
