<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Info extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function onGetPrivacyDetails()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'View/Edit Info';


            $data = $this->am->getAppDetailsData(array('id' => 1));
            if ($data) {
                $this->data['info_data'] = array(
                    'id' => encode_url($data->id),
                    'terms' => $data->terms_cond,
                    'privacy' => $data->privacy,
                    'gst' => $data->gst,
                    'tds' => $data->tds,
                    'royalty' => $data->royalty,
                    'admin_charges' => $data->admin_charges
                );
                //print_obj($this->data['info_data']);die;
                $this->load->view('info/vw_privacy_edit', $this->data, false);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    public function onSetPrivacyDetails()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'View/Edit Info';
            $id = decode_url(xss_clean($this->input->post('id')));
            $chkdata = array('id'  => $id);

            $terms_cond = xss_clean($this->input->post('terms'));
            $privacy = xss_clean($this->input->post('privacy'));
            $gst = xss_clean($this->input->post('gst_rate'));
            $tds = xss_clean($this->input->post('tds_rate'));
            $royalty = xss_clean($this->input->post('royalty_rate'));
            $admin_charges = xss_clean($this->input->post('admin_charges'));


            $upd_userdata = array(
                'terms_cond'  => $terms_cond,
                'privacy'  => $privacy,
                'gst'  => $gst,
                'tds'  => $tds,
                'royalty'  => $royalty,
                'admin_charges'  => $admin_charges,
                'dtime'  => dtime,
                'edited_by'  => $this->session->userdata('userid')
            );
            // print_obj($upd_userdata);die;

            $getData = $this->am->getAppDetailsData($chkdata);
            if ($getData) {
                //update

                $updcrypto = $this->am->updateAppDetails($upd_userdata, $chkdata);
                if ($updcrypto) {
                    $this->data['update_success'] = 'Successfully updated.';
                    //list

                    $cryptoDataUpd = $this->am->getAppDetailsData($chkdata);
                    $this->data['info_data'] = array(
                        'id' => encode_url($cryptoDataUpd->id),
                        'terms'  => $cryptoDataUpd->terms_cond,
                        'privacy'  => $cryptoDataUpd->privacy,
                        'gst'  => $cryptoDataUpd->gst,
                        'tds'  => $cryptoDataUpd->tds,
                        'royalty'  => $cryptoDataUpd->royalty,
                        'admin_charges'  => $cryptoDataUpd->admin_charges,
                        'editeddtime'  => $cryptoDataUpd->dtime
                    );
                } else {
                    $this->data['update_failure'] = 'Not updated!';
                }

                $this->load->view('info/vw_privacy_edit', $this->data, false);
            } else {
                redirect(base_url('view-privacy-tnc'));
            }
        } else {
            redirect(base_url());
        }
    }

 
   
}
