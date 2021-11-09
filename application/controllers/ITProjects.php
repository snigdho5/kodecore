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
            if ($projectsdata) {
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
            if ($projectsdata) {
                $this->data['proj_data'] = array(
                    'dtime'  => $projectsdata->added_dtime,
                    'projid'  => $projectsdata->proj_id,
                    'title'  => $projectsdata->proj_title,
                    'description'  => $projectsdata->proj_description,
                    'amount'  => $projectsdata->proj_amount,
                    'duration'  => $projectsdata->proj_duration,
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


            $upd_userdata = array(
                'proj_title'  => $title,
                'proj_description'  => $description,
                'proj_amount'  => $amount,
                'proj_duration'  => $duration,
                'edited_dtime'  => dtime,
                'edited_by'  => $this->session->userdata('userid')
            );
            // print_obj($upd_userdata);die;

            $projdata = $this->am->getProjectData($chkdata, FALSE);
            if ($projdata) {
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
                        'editeddtime'  => $projDataUpd->edited_dtime
                    );
                } else {
                    $this->data['update_failure'] = 'Not updated!';
                }

                $this->load->view('itprojects/vw_project_edit', $this->data, false);
            } else {
                redirect(base_url());
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

                if ($this->form_validation->run() == FALSE) {
                    $this->form_validation->set_error_delimiters('', '');
                    $return['errors'] = validation_errors();
                    $return['added'] = 'rule_error';
                } else {

                    $title = xss_clean($this->input->post('title'));
                    $description = xss_clean($this->input->post('description'));
                    $amount = xss_clean($this->input->post('amount'));
                    $duration = xss_clean($this->input->post('duration'));

                    $chkdata = array('proj_title'  => $title);
                    $projdata = $this->am->getProjectData($chkdata, FALSE);

                    if (!$projdata) {
                        //add

                        $ins_userdata = array(
                            'proj_title'  => $title,
                            'proj_description'  => $description,
                            'proj_amount'  => $amount,
                            'proj_duration'  => $duration,
                            'added_dtime'  => dtime,
                            'added_by'  => $this->session->userdata('userid')
                        );
                        // print_obj($ins_userdata);die;
                        $addproj = $this->am->addProject($ins_userdata);

                        if ($addproj) {
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
}
