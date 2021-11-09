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
            $invData = $this->am->getInvPlansData(null, TRUE);

            if ($invData) {
                foreach ($invData as $key => $value) {
                    $this->data['inv_plan_data'][] = array(
                        'planid'  => $value->plan_id,
                        'name'  => $value->plan_name,
                        'summary'  => $value->plan_summary,
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


            $upd_data = array(
                'plan_name'  => $plan_name,
                'plan_description'  => $plan_description,
                'plan_summary'  => $plan_summary,
                'plan_return_rate'  => $plan_return_rate,
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
                        'dtime'  => $invplansData->added_dtime,
                        'status'  => $invplansData->status
                    );
                } else {
                    $this->data['update_failure'] = 'Not updated!';
                }

                $this->load->view('investmentplans/vw_invplan_edit', $this->data, false);
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

                if ($this->form_validation->run() == FALSE) {
                    $this->form_validation->set_error_delimiters('', '');
                    $return['errors'] = validation_errors();
                    $return['added'] = 'rule_error';
                } else {

                    $plan_name = xss_clean($this->input->post('title'));
                    $plan_description = xss_clean($this->input->post('description'));
                    $plan_summary = xss_clean($this->input->post('summary'));
                    $plan_return_rate = xss_clean($this->input->post('return_rate'));

                    $chkdata = array('plan_name'  => $plan_name);
                    $plandata = $this->am->getInvPlansData($chkdata, FALSE);

                    if (!$plandata) {
                        //add

                        $ins_array = array(
                            'plan_name'  => $plan_name,
                            'plan_description'  => $plan_description,
                            'plan_summary'  => $plan_summary,
                            'plan_return_rate'  => $plan_return_rate,
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
}
