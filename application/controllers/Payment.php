<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function onPayNow()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {

                $this->data['page_title'] = 'Wallet Withdrawal Payment';

                $sp_orderid = decode_url(xss_clean($this->uri->segment(2)));

                
                $getdata = $this->am->getPaymentLogData(array('sp_orderid'  => $sp_orderid));

                if (!empty($getdata)) {

                    $this->data['order_id'] = $getdata->order_id;
                    $this->data['amount'] = $getdata->amount;
                    $this->data['name'] = $getdata->name;
                    $this->data['email'] = $getdata->email;
                    $this->data['phone'] = $getdata->phone;
                    $this->data['desc'] = $getdata->description;
                    $this->data['sp_orderid'] = $getdata->sp_orderid;
                    $this->data['paydbid'] = $getdata->paydbid;
                } else {
                    $this->data['order_id'] = '';
                    $this->data['amount'] = '';
                    $this->data['name'] = '';
                    $this->data['email'] = '';
                    $this->data['phone'] = '';
                    $this->data['desc'] = '';
                    $this->data['sp_orderid'] = '';
                    $this->data['paydbid'] = '';
                }

                // print_obj($this->data['crypto_data']);die;
                $this->load->view('wallet/vw_pay_now', $this->data, false);
           
        } else {
            redirect(base_url());
        }
    }

    public function onSavePayment()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {

            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $paydbid = xss_clean($this->input->post('paydbid'));
                $rp_paymentid = xss_clean($this->input->post('rp_paymentid'));
                $rp_orderid = xss_clean($this->input->post('rp_orderid'));
                $rp_sign = xss_clean($this->input->post('rp_sign'));

                
                $getdata = $this->am->getWalletWithdrawalData(array('wdl_id'  => $paydbid));

                if (!empty($getdata)) {

                   
                    $upd = $this->am->updateWalletWithdrawal(array('status'  => 1, 'app_rej_dtime'  => dtime, 'payment_status'  => 1, 'payment_response'  => $rp_orderid), array('wdl_id'  => $paydbid));

                    if ($upd) {
                            $return['resp'] = 'success';
                    } else {
                        $return['msg'] = 'Something went wrong!';
                        $return['resp'] = 'failure';
                    }
                } else {
                    
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