<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wallet extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    //redeem
    public function onGetWalletRedeemRequests()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Wallet Redeem Requests';

            $getWalletRedeem = $this->am->getWalletRedeemUserData(null, TRUE);


            if (!empty($getWalletRedeem)) {

                foreach ($getWalletRedeem as $key => $value) {
                    $resp[] = array(
                        'redeemid' => encode_url($value->redeem_id),
                        'user_name' => $value->first_name . ' ' . $value->last_name,
                        'user_email' => $value->email,
                        'user_phone' => $value->phone,
                        'amount' => $value->redeem_amount,
                        'status' => $value->status,
                        'dtime' => $value->dtime
                    );
                }
            } else {
                $resp = [];
            }

            $this->data['wallet_data'] = $resp;
            // print_obj($this->data['crypto_data']);die;
            $this->load->view('wallet/vw_wallet_requests', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    public function onApproveWalletRedeemReq()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $redeem_id = decode_url(xss_clean($this->input->post('id')));
                $apprvtype = xss_clean($this->input->post('apprvtype'));
                $getdata = $this->am->getWalletRedeemData(array('redeem_id'  => $redeem_id), FALSE);

                if (!empty($getdata)) {
                    $upd = $this->am->updateWalletRedeem(array('status'  => $apprvtype, 'app_rej_dtime'  => dtime), array('redeem_id'  => $redeem_id));

                    if ($upd) {
                        if ($apprvtype == 1) {
                            //approve
                            $getuser = $this->am->getCustomerData(array('customer_id'  => $getdata->customer_id));

                            if (!empty($getuser)) {
                                $addamt = ($getuser->wallet_amount + $getdata->redeem_amount);

                                $upd_user = $this->am->updateCustomer(array('wallet_amount'  => $addamt), array('customer_id'  => $getdata->customer_id));

                                if ($upd_user) {

                                    //send notification starts
                                    $userDetails  =   $this->am->getCustomerData(array('customer_id' => $getdata->customer_id));

                                    // print_obj($userDetails);die;
                                    if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                        $fcm = $userDetails->fcm_token;
                                        $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                        //$fcm = 'cNf2---6Vs9';
                                        $icon = NOTIFICATION_ICON;
                                        $notification_title = 'Redeem request approved';
                                        $notification_body = 'Congrats! Your redeem request is now approved.';
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
                                                'customer_id' => $getdata->customer_id,
                                                "notification_title" => $notification_title,
                                                "notification_body" => $notification_body,
                                                "notification_event" => 'redeem_approved',
                                                "dtime" => dtime
                                            );

                                            $addLog = $this->am->addNotificationLog($logData);
                                        }
                                    }
                                    //send notification ends


                                    $return['resp'] = 'success';
                                    $return['msg'] = 'Wallet request approved!';
                                } else {
                                    $return['resp'] = 'failure';
                                    $return['msg'] = 'Wallet request failure!';
                                }
                            } else {
                                $return['resp'] = 'failure';
                                $return['msg'] = 'User not found!';
                            }
                        } else {
                            //reject

                            //send notification starts
                            $userDetails  =   $this->am->getCustomerData(array('customer_id' => $getdata->customer_id));

                            // print_obj($userDetails);die;
                            if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                $fcm = $userDetails->fcm_token;
                                $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                //$fcm = 'cNf2---6Vs9';
                                $icon = NOTIFICATION_ICON;
                                $notification_title = 'Redeem request rejected';
                                $notification_body = 'Sorry! Your redeem request is rejected.';
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
                                        'customer_id' => $getdata->customer_id,
                                        "notification_title" => $notification_title,
                                        "notification_body" => $notification_body,
                                        "notification_event" => 'redeem_rejected',
                                        "dtime" => dtime
                                    );

                                    $addLog = $this->am->addNotificationLog($logData);
                                }
                            }
                            //send notification ends

                            $return['resp'] = 'success';
                            $return['msg'] = 'Wallet request rejected!';
                        }
                    } else {
                        $return['msg'] = 'Something went wrong!';
                        $return['resp'] = 'failure';
                    }
                } else {
                    $return['msg'] = 'Wallet request not exists!';
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

    //withdrawal
    public function onGetWalletWithdrawalRequests()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1) {

            $this->data['page_title'] = 'Wallet Withdrawal Requests';

            $getWalletWithdrawal = $this->am->getWalletWithdrawalUserData(null, TRUE);


            if (!empty($getWalletWithdrawal)) {

                foreach ($getWalletWithdrawal as $key => $value) {
                    $resp[] = array(
                        'wdlid' => encode_url($value->wdl_id),
                        'user_name' => $value->first_name . ' ' . $value->last_name,
                        'user_email' => $value->email,
                        'user_phone' => $value->phone,
                        'amount' => $value->amount,
                        'status' => $value->status,
                        'payment_status' => $value->payment_status,
                        'dtime' => $value->dtime
                    );
                }
            } else {
                $resp = [];
            }

            $this->data['wallet_data'] = $resp;
            // print_obj($this->data['crypto_data']);die;
            $this->load->view('wallet/vw_wallet_withdrawal_requests', $this->data, false);
        } else {
            redirect(base_url());
        }
    }

    public function onApproveWalletWithdrawalReq()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $wdl_id = decode_url(xss_clean($this->input->post('id')));
                $apprvtype = xss_clean($this->input->post('apprvtype'));
                $getdata = $this->am->getWalletWithdrawalData(array('wdl_id'  => $wdl_id), FALSE);

                if (!empty($getdata)) {
                    $upd = $this->am->updateWalletWithdrawal(array('status'  => $apprvtype, 'app_rej_dtime'  => dtime), array('wdl_id'  => $wdl_id));

                    if ($upd) {
                        if ($apprvtype == 1) {
                            //approve
                            $getuser = $this->am->getCustomerData(array('customer_id'  => $getdata->customer_id));

                            if (!empty($getuser)) {
                                $subamt = ($getuser->wallet_amount - $getdata->amount);

                                $upd_user = $this->am->updateCustomer(array('wallet_amount'  => $subamt), array('customer_id'  => $getdata->customer_id));

                                if ($upd_user) {

                                    //send notification starts
                                    $userDetails  =   $this->am->getCustomerData(array('customer_id' => $getdata->customer_id));

                                    // print_obj($userDetails);die;
                                    if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                        $fcm = $userDetails->fcm_token;
                                        $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                        //$fcm = 'cNf2---6Vs9';
                                        $icon = NOTIFICATION_ICON;
                                        $notification_title = 'Withdrawal request approved';
                                        $notification_body = 'Congrats! Your withdrawal request is now approved.';
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
                                                'customer_id' => $getdata->customer_id,
                                                "notification_title" => $notification_title,
                                                "notification_body" => $notification_body,
                                                "notification_event" => 'withdrawal_approved',
                                                "dtime" => dtime
                                            );

                                            $addLog = $this->am->addNotificationLog($logData);
                                        }
                                    }
                                    //send notification ends

                                    $return['resp'] = 'success';
                                    $return['msg'] = 'Withdrawal request approved!';
                                } else {
                                    $return['resp'] = 'failure';
                                    $return['msg'] = 'Withdrawal request failure!';
                                }
                            } else {
                                $return['resp'] = 'failure';
                                $return['msg'] = 'User not found!';
                            }
                        } else {
                            //reject

                             //send notification starts
                             $userDetails  =   $this->am->getCustomerData(array('customer_id' => $getdata->customer_id));

                             // print_obj($userDetails);die;
                             if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                 $fcm = $userDetails->fcm_token;
                                 $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                 //$fcm = 'cNf2---6Vs9';
                                 $icon = NOTIFICATION_ICON;
                                 $notification_title = 'Withdrawal request rejected';
                                 $notification_body = 'Sorry! Your withdrawal request is rejected.';
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
                                         'customer_id' => $getdata->customer_id,
                                         "notification_title" => $notification_title,
                                         "notification_body" => $notification_body,
                                         "notification_event" => 'withdrawal_rejected',
                                         "dtime" => dtime
                                     );

                                     $addLog = $this->am->addNotificationLog($logData);
                                 }
                             }
                             //send notification ends


                            $return['resp'] = 'success';
                            $return['msg'] = 'Withdrawal request rejected!';
                        }
                    } else {
                        $return['msg'] = 'Something went wrong!';
                        $return['resp'] = 'failure';
                    }
                } else {
                    $return['msg'] = 'Withdrawal request not exists!';
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

    public function onPayNowWithdrawal()
    {
        if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {
            if ($this->input->is_ajax_request() && $this->input->server('REQUEST_METHOD') == 'POST') {

                $wdl_id = decode_url(xss_clean($this->input->post('id')));
                $amount = xss_clean($this->input->post('amount'));
                $uniq_id = random_strings(8);
                $amount = $amount * 100; // Amount is in currency subunits. Hence, 50000 refers to 50000 paise.

                
                $getdata = $this->am->getWalletWithdrawalUserData(array('wdl_id'  => $wdl_id));

                if ($amount > 0 && !empty($getdata)) {
                    $name = $getdata->first_name . ' ' . $getdata->last_name;
                    $email = $getdata->email;
                    $phone = $getdata->phone;

                    //rajorpay
                    $key_id = KEY_ID;
                    $key_secret = KEY_SECRET;

                    $ch = curl_init();
                    $payload = json_encode( array(
                            "amount" => $amount,
                            "currency" => "INR",
                            "receipt" => $uniq_id,
                            "payment_capture" => 1
                        ) );
                    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
                    curl_setopt($ch, CURLOPT_URL,"https://api.razorpay.com/v1/orders");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                    curl_setopt($ch, CURLOPT_USERPWD, $key_id.':'.$key_secret);
                    
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    
                    $server_output = curl_exec($ch);
                    curl_close ($ch);
                    $order_json = json_decode($server_output, true);

                    $order_id = (!empty($order_json))?$order_json['id']:'0';
                    $amount = $amount;
                    $name = $name;
                    $return['email'] = $email;
                    $return['phone'] = $phone;
                    $desc = 'Withdrawal Payment';
                    $sp_orderid = $uniq_id;
                    $paydbid = $wdl_id;

                    $add_data = array(
                        'order_id' => $order_id,
                        'amount' => $amount,
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'description' => $desc,
                        'sp_orderid' => $sp_orderid,
                        'paydbid' => $paydbid,
                        'tbl_name' => 'customer_wallet_withdrawal'
                    );


                    $added_id  =   $this->am->addPaymentLog($add_data);

                    //print_obj($server_output);die;

                    $return['resp'] = 'success';
                    $return['url'] = base_url() . 'pay-now/'.encode_url($sp_orderid);
                } else {
                    $return['msg'] = 'Amount is 0 or User is not found!';
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
