<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crypto extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function onGetCryptoDetails()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Update KodeCoin';


            $cryptodata = $this->am->getCryptoData(array('crypto_id' => 1));
            if ($cryptodata) {
                $this->data['crypto_data'] = array(
                    'cryptoid' => encode_url($cryptodata->crypto_id),
                    'name' => $cryptodata->name,
                    'symbol' => $cryptodata->symbol,
                    'description' => $cryptodata->description,
                    'price_inr' => $cryptodata->price_inr,
                    'price_btc' => $cryptodata->price_btc,
                    'price_usd' => $cryptodata->price_usd
                );
                //print_obj($this->data['crypto_data']);die;
                $this->load->view('crypto/vw_crypto_edit', $this->data, false);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    public function onSetCryptoDetails()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'KodeCoin';
            $crypto_id = decode_url(xss_clean($this->input->post('cryptoid')));
            $chkdata = array('crypto_id'  => $crypto_id);

            $name = xss_clean($this->input->post('name'));
            $symbol = xss_clean($this->input->post('symbol'));
            $description = xss_clean($this->input->post('description'));
            $price_inr = xss_clean($this->input->post('price_inr'));
            $price_btc = xss_clean($this->input->post('price_btc'));
            $price_usd = xss_clean($this->input->post('price_usd'));


            $upd_userdata = array(
                'name'  => $name,
                'symbol'  => $symbol,
                'description'  => $description,
                'price_inr'  => $price_inr,
                'price_btc'  => $price_btc,
                'price_usd'  => $price_usd,
                'edited_dtime'  => dtime,
                'edited_by'  => $this->session->userdata('userid')
            );
            // print_obj($upd_userdata);die;

            $cryptodata = $this->am->getCryptoData($chkdata);
            if ($cryptodata) {
                //update

                $updcrypto = $this->am->updateCrypto($upd_userdata, $chkdata);
                if ($updcrypto) {
                    $this->data['update_success'] = 'Successfully updated.';
                    //list

                    $cryptoDataUpd = $this->am->getCryptoData($chkdata);
                    $this->data['crypto_data'] = array(
                        'cryptoid' => encode_url($cryptoDataUpd->crypto_id),
                        'name'  => $cryptoDataUpd->name,
                        'symbol'  => $cryptoDataUpd->symbol,
                        'description'  => $cryptoDataUpd->description,
                        'price_inr'  => $cryptoDataUpd->price_inr,
                        'price_btc'  => $cryptoDataUpd->price_btc,
                        'price_usd'  => $cryptoDataUpd->price_usd,
                        'editeddtime'  => $cryptoDataUpd->edited_dtime
                    );
                } else {
                    $this->data['update_failure'] = 'Not updated!';
                }

                $this->load->view('crypto/vw_crypto_edit', $this->data, false);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    public function onGetCryptoTransactionsBuy()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Cryptocurrency Transactions Buy';

            //buy
            $getBoughtCrypto = $this->am->getCryptoBuyUserData(null, TRUE);

            if (!empty($getBoughtCrypto)) {

                foreach ($getBoughtCrypto as $key => $value) {
                    $resp_buy[] = array(
                        'crypto_buyid' => encode_url($value->crypto_buy_id),
                        'user_id' => encode_url($value->customer_id),
                        'crypto_id' => $value->crypto_pid,
                        'user_name' => $value->first_name . ' ' . $value->last_name,
                        'user_email' => $value->email,
                        'user_phone' => $value->phone,
                        //'actual_amount' => $value->actual_amount,
                        'received_amount' => $value->received_amount,
                        'quantity' => $value->quantity,
                        'application_status' => $value->application_status,
                        'payment_status' => ($value->payment_status == '0') ? 'Not Paid' : 'Paid',
                        'payment_mode' => $value->payment_mode,
                        'dtime' => $value->added_dtime,
                        'trans_type' => 'buy'
                    );
                }
            } else {
                $resp_buy = [];
            }

            $this->data['crypto_data'] = $resp_buy;
            // print_obj($this->data['crypto_data']);die;
            $this->load->view('crypto/vw_crypto_transactions_buy', $this->data, false);
        } else {
            redirect(base_url());
        }
    }


    public function onGetCryptoTransactionsSell()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Cryptocurrency Transactions Sell';

            //sell
            $getSoldCrypto = $this->am->getCryptoSellUserData(null, TRUE);


            if (!empty($getSoldCrypto)) {

                foreach ($getSoldCrypto as $key => $value) {
                    $resp_sell[] = array(
                        'crypto_sellid' => encode_url($value->crypto_sell_id),
                        'user_id' => encode_url($value->customer_id),
                        'crypto_id' => $value->crypto_pid,
                        'user_name' => $value->first_name . ' ' . $value->last_name,
                        'user_email' => $value->email,
                        'user_phone' => $value->phone,
                        //'actual_amount' => $value->actual_amount,
                        'received_amount' => $value->received_amount,
                        'quantity' => $value->quantity,
                        'application_status' => $value->application_status,
                        'payment_status' => ($value->payment_status == '0') ? 'Not Paid' : 'Paid',
                        'payment_mode' => $value->payment_mode,
                        'dtime' => $value->added_dtime,
                        'trans_type' => 'sell'
                    );
                }
            } else {
                $resp_sell = [];
            }

            $this->data['crypto_data'] = $resp_sell;
            // print_obj($this->data['crypto_data']);die;
            $this->load->view('crypto/vw_crypto_transactions_sell', $this->data, false);
        } else {
            redirect(base_url());
        }
    }


    public function onApproveBuyReq()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $crypto_buy_id = decode_url(xss_clean($this->input->post('id')));
                $apprvtype = xss_clean($this->input->post('apprvtype'));
                $getdata = $this->am->getCryptoBuyData(array('crypto_buy_id'  => $crypto_buy_id));

                if (!empty($getdata)) {
                    $upd = $this->am->updateCryptoBuy(array('application_status'  => $apprvtype, 'app_rej_dtime'  => dtime), array('crypto_buy_id'  => $crypto_buy_id));

                    if ($upd) {
                        if ($apprvtype == 1) {
                            //approve

                            $return['resp'] = 'success';
                            $return['msg'] = 'Crypto buy request approved!';
                        } else {
                            //reject
                            $return['resp'] = 'success';
                            $return['msg'] = 'Crypto buy request rejected!';
                        }
                    } else {
                        $return['msg'] = 'Something went wrong!';
                        $return['resp'] = 'failure';
                    }
                } else {
                    $return['msg'] = 'Crypto buy request not exists!';
                    $return['resp'] = 'not_exists';
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

    
    public function onApproveSellReq()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $crypto_sell_id = decode_url(xss_clean($this->input->post('id')));
                $apprvtype = xss_clean($this->input->post('apprvtype'));
                $getdata = $this->am->getCryptoSellData(array('crypto_sell_id'  => $crypto_sell_id));

                if (!empty($getdata)) {
                    $upd = $this->am->updateCryptoSell(array('application_status'  => $apprvtype, 'app_rej_dtime'  => dtime), array('crypto_sell_id'  => $crypto_sell_id));

                    if ($upd) {
                        if ($apprvtype == 1) {
                            //approve

                            $return['resp'] = 'success';
                            $return['msg'] = 'Crypto sell request approved!';
                        } else {
                            //reject
                            $return['resp'] = 'success';
                            $return['msg'] = 'Crypto sell request rejected!';
                        }
                    } else {
                        $return['msg'] = 'Something went wrong!';
                        $return['resp'] = 'failure';
                    }
                } else {
                    $return['msg'] = 'Crypto sell request not exists!';
                    $return['resp'] = 'not_exists';
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
