<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('email');
        $config = array(
            // 'protocol' => 'smtp',
            // 'smtp_host' => 'smtp.googlemail.com',
            // 'smtp_port' => 465,
            // 'smtp_user' => ADMIN_EMAIL,
            // 'smtp_pass' => ADMIN_EMAIL_PASS,
            'mailtype' => 'html',
            'smtp_crypto' => 'ssl',
            'smtp_timeout' => '4',
            'charset' => 'utf-8',
            'wordwrap' => TRUE
        );
        $this->email->initialize($config);
    }

    public function onEncryptAll()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $param = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {
                        if (isset($decodedParam->strtoenc) && $decodedParam->strtoenc != '') {
                            $encrypted_text = encrypt_it($decodedParam->strtoenc);
                            $decrypted_text = decrypt_it($encrypted_text);

                            //$encrypted_text = encode_url($decodedParam->strtoenc);
                            //$decrypted_text = decode_url($encrypted_text);

                            $return['respData'] = array(
                                'encrypted_str'  => $encrypted_text,
                                'decrypted_str'  => $decrypted_text
                            );
                            $return['success'] = 1;
                            $return['message'] = 'Successfully Encrypted!';
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = 'Missing Required Fields!!';
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'Error in Encryption!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON data is empty';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onDecryptAll()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $param = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {
                        if (isset($decodedParam->strtodec) && $decodedParam->strtodec != '') {
                            //$decrypted_text = decrypt_it($decodedParam->strtodec);
                            $decrypted_text = decode_url($decodedParam->strtodec);

                            $return['respData'] = array(
                                'decrypted_str'  => $decrypted_text
                            );
                            $return['success'] = 1;
                            $return['message'] = 'Successfully Encrypted!';
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = 'Missing Required Fields!!';
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'Error in Decryption!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON data is empty';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onRegisterCustomer()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->fname) && $decodedParam->fname != '' && isset($decodedParam->lname) && $decodedParam->lname != '' && isset($decodedParam->email) && $decodedParam->email != '' && isset($decodedParam->phone) && $decodedParam->phone != '' && isset($decodedParam->fcm_token) && $decodedParam->fcm_token != '') {

                            $if_not_blank = 1; //not blank
                            $email = xss_clean($decodedParam->email);
                            $phone = xss_clean($decodedParam->phone);
                            $first_name = xss_clean($decodedParam->fname);
                            $last_name = xss_clean($decodedParam->lname);
                            $fcm_token = xss_clean($decodedParam->fcm_token);
                            $password = encrypt_it(random_strings(8));
                            $otp = random_numbers(6);
                            // $otp = '123456';


                            // decrypt 
                            // $email = decrypt_it($email_enc);
                            // $phone = decrypt_it($phone_enc);

                            //email
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                // $out_message = $email . " is a valid email address" ;
                                $if_email = 1; //email valid
                            } else {
                                $if_email = 0;
                                $out_message += array('valid_email' => $email . " is not a valid email address");
                            }

                            //phone with 10 digit
                            if (preg_match('/^[0-9]{10}+$/', $phone)) {
                                //$out_message = $phone . " is a valid phone" ;
                                $if_phone = 1; //phone valid
                            } else {
                                $if_phone = 0;
                                $out_message += array('valid_phone' => $phone . " is not a valid phone");
                            }
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1 && $if_email == 1 && $if_phone == 1) {

                            $userdata = $this->am->checkAppUser($email, $phone);
                            if (empty($userdata)) {
                                $insert_array = array(
                                    'first_name' => $first_name,
                                    'last_name' => $last_name,
                                    'email' => $email,
                                    'phone' => $phone,
                                    'pass' => $password,
                                    'fcm_token' => $fcm_token,
                                    'dtime' => dtime,
                                    'added_source' => 2,
                                    'otp_status' => 1, //sent
                                    'otp_value' => $otp
                                );
                                $added = $this->am->addCustomer($insert_array);
                                if ($added) {

                                    $resp = array(
                                        'user_id' => strval($added),
                                        'first_name' => $first_name,
                                        'last_name' => $last_name,
                                        'email' => $email,
                                        'fcm_token' => $fcm_token,
                                        'phone' => $phone,
                                        'kyc_status' => '0',
                                        'otp_status' => '1'
                                    );

                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'You have registered successfully. An OTP has been sent to your phone and email. Please Verify!';


                                    //otp sms
                                    $username = SMS_USERNAME;
                                    $password = SMS_PASSWORD;
                                    $to = $phone;
                                    $from = SMS_FROM;
                                    $text = urlencode('Your Registration OTP is '.$otp.' KODECORE');
                                    $send_url = 'https://103.229.250.200/smpp/sendsms?username='.$username.'&password='.$password.'&to='.$to.'&from='.$from.'&text='.$text;

                                    $curl = curl_init();

                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => $send_url,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'GET',
                                    ));

                                    $response = curl_exec($curl);
                                    curl_close($curl);

                                    $return['otp_response'] = $response;

                                    // echo $response;
                                    //otp sms ends


                                    //email
                                    $subject = 'Registered Successfully';
                                    $body = '';
                                    $body .= '<p>Hello ' . $first_name . '' . $last_name . ', </p>';
                                    $body .= '<p>You have successfully registered. Your login details are mentioned below. Please login and verify.</p>';
                                    $body .= '<p><b>Username : </b>' . $phone . '</p>';
                                    $body .= '<p><b>Email : </b>' . $email . '</p>';
                                    //$body .= '<p><b>Password : </b>' . decrypt_it($password) . '</p>';
                                    $body .= '<p><b>OTP : </b>' . $otp . '</p>';
                                    $body .= '<br><p>* Please change your password after login.</p>';
                                    $body .= '<br><p>** This is a system generated email. Please do not reply to this email..</p>';

                                    $this->email->set_newline("\r\n");
                                    $this->email->set_mailtype("html");
                                    $this->email->from(FROM_EMAIL, 'Kode Core');
                                    $this->email->to($email);
                                    //$this->email->reply_to($replyemail);
                                    $this->email->subject($subject);
                                    $this->email->message($body);
                                    if ($this->email->send()) {
                                        $return['otp_msg'] = 'An e-mail with OTP has been sent to your email (' . $email . ').';
                                    } else {
                                        //echo $this->email->print_debugger();die;
                                        $return['otp_msg'] = 'E-mail not sent to ' . $email . '!';
                                    }
                                }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'Email / Phone already exists!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onVerifyCustomer()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->user_otp) && $decodedParam->user_otp != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $user_otp = xss_clean($decodedParam->user_otp);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $param = array('customer_id' => $customer_id, 'user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param);
                            if (!empty($userdata)) {
                                if ($userdata->otp_value == $user_otp) {
                                    $name = $userdata->first_name . '' . $userdata->last_name;
                                    $email = $userdata->email;
                                    $upd_array = array(
                                        'otp_status' => 2
                                    );
                                    $paramup = array('customer_id' => $customer_id);
                                    $updated = $this->am->updateCustomer($upd_array, $paramup);
                                    if ($updated) {

                                        $resp = array(
                                            'user_id' => $customer_id,
                                            'otp_status' => "2",
                                            'kyc_status' => $userdata->kyc_status
                                        );
                                        $return['respData'] = $resp;
                                        $return['success'] = 1;
                                        $return['message'] = 'User verified successfully!';


                                        //email
                                        $subject = 'Verified Successfully';
                                        $body = '';
                                        $body .= '<p>Hello ' . $name . ', </p>';
                                        $body .= '<p>You have successfully verified. Please login.</p>';

                                        $body .= '<br><p>** This is a system generated email. Please do not reply to this email..</p>';

                                        $this->email->set_newline("\r\n");
                                        $this->email->set_mailtype("html");
                                        $this->email->from(FROM_EMAIL, 'Kode Core');
                                        $this->email->to($email);
                                        //$this->email->reply_to($replyemail);
                                        $this->email->subject($subject);
                                        $this->email->message($body);
                                        if ($this->email->send()) {
                                            // $return['otp_status'] = 1;
                                            // $return['otp_msg'] = 'OTP verified and sent to (' . $email . ').';
                                        } else {
                                            //echo $this->email->print_debugger();die;
                                            // $return['otp_status'] = 0;
                                            // $return['otp_msg'] = 'E-mail not sent to ' . $email . '!';
                                        }
                                    }
                                } else {
                                    $return['respData'] = '';
                                    $return['success'] = 0;
                                    $return['message'] = 'Entered OTP not correct!';
                                }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'User does not exist!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onKycCustomer()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        // print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            // print_obj($decodedParam); die;

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->bank_name) && $decodedParam->bank_name != '' && isset($decodedParam->ac_no) && $decodedParam->ac_no != '' && isset($decodedParam->branch_name) && $decodedParam->branch_name != '' && isset($decodedParam->ifsc) && $decodedParam->ifsc != '' && isset($decodedParam->ac_name) && $decodedParam->ac_name != '' && isset($decodedParam->pan_no) && $decodedParam->pan_no != '' && isset($decodedParam->aadhaar_no) && $decodedParam->aadhaar_no != '' && isset($decodedParam->kyc_type) && $decodedParam->kyc_type != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $bank_name = xss_clean($decodedParam->bank_name);
                            $branch_name = xss_clean($decodedParam->branch_name);
                            $ac_no = xss_clean($decodedParam->ac_no);
                            $ifsc = xss_clean($decodedParam->ifsc);
                            $ac_name = xss_clean($decodedParam->ac_name);
                            $pan_no = xss_clean($decodedParam->pan_no);
                            $aadhaar_no = xss_clean($decodedParam->aadhaar_no);
                            $kyc_type = xss_clean($decodedParam->kyc_type);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $param = array('customer_id' => $customer_id, 'user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param);
                            if (!empty($userdata)) {

                                $paramup = array('customer_id' => $customer_id);

                                //upload pan
                                if(isset($_FILES["kyc_pan_file"]['name']) && $_FILES["kyc_pan_file"]['name'] != ''){
                                    $new_name                  =   time() . '-' . $_FILES["kyc_pan_file"]['name'];
                                    $config['file_name']       =   $new_name;
                                    $config['upload_path']     =   "uploads/images/";
                                    $config['allowed_types']   =   'gif|jpg|png|pdf';
    
                                    $this->load->library('upload', $config);
                                    $this->upload->initialize($config);
    
                                    if ($this->upload->do_upload('kyc_pan_file')) {
                                        $uploadData          =     $this->upload->data();
                                        $uploadedFile        =     $uploadData['file_name'];

                                        $fupd_array = array(
                                            'kyc_pan_file' => $uploadedFile
                                        );
                                        $updated = $this->am->updateCustomer($fupd_array, $paramup);
                                    } else {
                                        $uploadedFile   =     '';
                                    }
                                }

                                //upload aadhaar
                                if(isset($_FILES["kyc_aadhaar_file"]['name']) && $_FILES["kyc_aadhaar_file"]['name'] != ''){
                                    $new_name                  =   time() . '-' . $_FILES["kyc_aadhaar_file"]['name'];
                                    $config['file_name']       =   $new_name;
                                    $config['upload_path']     =   "uploads/images/";
                                    $config['allowed_types']   =   'gif|jpg|png|pdf';
    
                                    $this->load->library('upload', $config);
                                    $this->upload->initialize($config);
    
                                    if ($this->upload->do_upload('kyc_aadhaar_file')) {
                                        $uploadData          =     $this->upload->data();
                                        $uploadedFile2        =     $uploadData['file_name'];

                                        $fupd_array = array(
                                            'kyc_aadhaar_file' => $uploadedFile2
                                        );
                                        $updated = $this->am->updateCustomer($fupd_array, $paramup);
                                    } else {
                                        $uploadedFile2   =     '';
                                    }
                                }
                                
                                
                                    $upd_array = array(
                                        'bank_name' => $bank_name,
                                        'branch_name' => $branch_name,
                                        'ac_no' => $ac_no,
                                        'ifsc' => $ifsc,
                                        'ac_name' => $ac_name,
                                        'pan_no' => $pan_no,
                                        'aadhaar_no' => $aadhaar_no,
                                        'kyc_type' => $kyc_type,
                                        'kyc_status' => '1'
                                    );
                                    $paramup = array('customer_id' => $customer_id);
                                    $updated = $this->am->updateCustomer($upd_array, $paramup);

                                    if ($updated) {

                                        $resp = array(
                                            'user_id' => $customer_id,
                                            'bank_name' => $bank_name,
                                            'branch_name' => $branch_name,
                                            'ac_no' => $ac_no,
                                            'ifsc' => $ifsc,
                                            'ac_name' => $ac_name,
                                            'pan_no' => $pan_no,
                                            'aadhaar_no' => $aadhaar_no,
                                            'kyc_pan_file' => (isset($uploadedFile) && $uploadedFile != '') ? base_url() . 'uploads/images/' . $uploadedFile : '',
                                            'kyc_aadhaar_file' => (isset($uploadedFile2) && $uploadedFile2 != '') ? base_url() . 'uploads/images/' . $uploadedFile2 : '',
                                            'kyc_status' => '1',
                                            'kyc_type' => $kyc_type
                                        );
                                        
                                        $return['respData'] = $resp;
                                        $return['success'] = 1;
                                        $return['message'] = 'KYC updated successfully!';

                                    }else{
                                        $return['respData'] = [];
                                        $return['success'] = 0;
                                        $return['message'] = 'KYC not updated!';
                                    }
                                
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'User does not exist!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onChangePassword()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->password) && $decodedParam->password != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $password = xss_clean($decodedParam->password);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $param = array('customer_id' => $customer_id, 'user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param);

                            if (!empty($userdata)) {
                                $name = $userdata->first_name . '' . $userdata->last_name;
                                $email = $userdata->email;
                                $upd_array = array(
                                    'pass' => encrypt_it($password)
                                );
                                $paramUp = array('customer_id' => $customer_id);

                                $updated = $this->am->updateCustomer($upd_array, $paramUp);
                                if ($updated) {

                                    $resp = array(
                                        'user_id' => $customer_id
                                    );
                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'Password Changed successfully!';


                                    //email
                                    $subject = 'Password Changed Successfully';
                                    $body = '';
                                    $body .= '<p>Hello ' . $name . ', </p>';
                                    $body .= '<p>You have successfully changed password. Please login.</p>';

                                    $body .= '<br><p>** This is a system generated email. Please do not reply to this email..</p>';


                                    $this->email->set_newline("\r\n");
                                    $this->email->set_mailtype("html");
                                    $this->email->from(FROM_EMAIL, 'Kode Core');
                                    $this->email->to($email);
                                    //$this->email->reply_to($replyemail);
                                    $this->email->subject($subject);
                                    $this->email->message($body);
                                    if ($this->email->send()) {
                                        // $return['otp_status'] = 1;
                                        // $return['otp_msg'] = 'OTP verified and sent to (' . $email . ').';
                                    } else {
                                        //echo $this->email->print_debugger();die;
                                        // $return['otp_status'] = 0;
                                        // $return['otp_msg'] = 'E-mail not sent to ' . $email . '!';
                                    }
                                }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'User does not exist!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onGetCustomers()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                        } else {
                            $if_not_blank = 0;
                            //$out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $param = array('customer_id' => $customer_id, 'user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param);

                            if (!empty($userdata)) {

                                $resp = array(
                                    'user_id' => $userdata->customer_id,
                                    'first_name' => $userdata->first_name,
                                    'last_name' => $userdata->last_name,
                                    'email' => $userdata->email,
                                    'phone' => $userdata->phone,
                                    'is_wallet_active' => $userdata->is_wallet_active,
                                    'wallet_amount' => $userdata->wallet_amount,
                                    'added_dtime' => $userdata->dtime,
                                    'last_login' => $userdata->last_login,
                                    'added_source' => $userdata->added_source,
                                    'otp_status' => $userdata->otp_status,
                                    'kyc_status' => $userdata->kyc_status,
                                    'kyc_type' => $userdata->kyc_type,
                                    'fcm_token' => $userdata->fcm_token,
                                    'bank_name' => $userdata->bank_name,
                                    'branch_name' => $userdata->branch_name,
                                    'ac_no' => $userdata->ac_no,
                                    'ifsc' => $userdata->ifsc,
                                    'ac_name' => $userdata->ac_name,
                                    'pan_no' => $userdata->pan_no,
                                    'aadhaar_no' => $userdata->aadhaar_no,
                                    'kyc_pan_file' => ($userdata->kyc_pan_file != '') ? base_url() . 'uploads/images/' . $userdata->kyc_pan_file : '',
                                    'kyc_aadhaar_file' => ($userdata->kyc_aadhaar_file != '') ? base_url() . 'uploads/images/' . $userdata->kyc_aadhaar_file : '',
                                    'user_blocked' => $userdata->user_blocked
                                );

                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Success!';
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'No data found!';
                            }
                        } else {
                            $param = array('user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param, TRUE);

                            // print_obj($userdata);die;

                            if (!empty($userdata)) {
                                foreach ($userdata as $key => $value) {
                                    $resp[] = array(
                                        'user_id' => $value->customer_id,
                                        'first_name' => $value->first_name,
                                        'last_name' => $value->last_name,
                                        'email' => $value->email,
                                        'phone' => $value->phone,
                                        'is_wallet_active' => $value->is_wallet_active,
                                        'wallet_amount' => $value->wallet_amount,
                                        'added_dtime' => $value->dtime,
                                        'last_login' => $value->last_login,
                                        'added_source' => $value->added_source,
                                        'otp_status' => $value->otp_status,
                                        'kyc_status' => $value->kyc_status,
                                        'kyc_type' => $value->kyc_type,
                                        'fcm_token' => $value->fcm_token,
                                        'bank_name' => $value->bank_name,
                                        'branch_name' => $value->branch_name,
                                        'ac_no' => $value->ac_no,
                                        'ifsc' => $value->ifsc,
                                        'ac_name' => $value->ac_name,
                                        'pan_no' => $value->pan_no,
                                        'aadhaar_no' => $value->aadhaar_no,
                                        'kyc_pan_file' => ($value->kyc_pan_file != '') ? base_url() . 'uploads/images/' . $value->kyc_pan_file : '',
                                        'kyc_aadhaar_file' => ($value->kyc_aadhaar_file != '') ? base_url() . 'uploads/images/' . $value->kyc_aadhaar_file : '',
                                        'fcm_token' => $value->fcm_token,
                                        'user_blocked' => $value->user_blocked
                                    );
                                }

                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Success!';
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'No data found!';
                            }
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onLoginCustomer()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->phone) && $decodedParam->phone != '' && isset($decodedParam->fcm_token) && $decodedParam->fcm_token != '') {

                            $if_not_blank = 1; //not blank
                            $phone = xss_clean($decodedParam->phone);
                            $fcm_token = xss_clean($decodedParam->fcm_token);

                            // $otp = '123456';
                            $otp = random_numbers(6);

                            //phone with 10 digit
                            if (preg_match('/^[0-9]{10}+$/', $phone)) {
                                //$out_message = $phone . " is a valid phone" ;
                                $if_phone = 1; //phone valid
                            } else {
                                $if_phone = 0;
                                $out_message += array('valid_phone' => $phone . " is not a valid phone");
                            }
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_phone == 1 && $if_not_blank == 1) {
                            $param = array('phone' => $phone, 'user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param);
                            if (!empty($userdata)) {
                                // if ($userdata->login_otp_status == 0) {

                                    //otp sms
                                    $username = SMS_USERNAME;
                                    $password = SMS_PASSWORD;
                                    $to = $phone;
                                    $from = SMS_FROM;
                                    $text = urlencode('Your Login OTP is '.$otp.' KODECORE');
                                    $send_url = 'https://103.229.250.200/smpp/sendsms?username='.$username.'&password='.$password.'&to='.$to.'&from='.$from.'&text='.$text;

                                    $curl = curl_init();

                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => $send_url,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'GET',
                                    ));

                                    $response = curl_exec($curl);
                                    curl_close($curl);

                                    // echo $response;
                                    //otp sms ends


                                $upd_array = array(
                                    'login_otp_status' => 1, //sent
                                    'login_otp_value' => $otp,
                                    'fcm_token' => $fcm_token
                                );
                                $paramup = array('customer_id' => $userdata->customer_id);
                                $updated = $this->am->updateCustomer($upd_array, $paramup);
                                
                                if ($updated) {

                                    $resp = array(
                                        'user_id' => $userdata->customer_id,
                                        // 'first_name' => $userdata->first_name,
                                        // 'last_name' => $userdata->last_name,
                                        // 'email' => $userdata->email,
                                        // 'phone' => $userdata->phone,
                                        // 'is_wallet_active' => $userdata->is_wallet_active,
                                        // 'wallet_amount' => $userdata->wallet_amount,
                                        // 'added_dtime' => $userdata->dtime,
                                        // 'last_login' => $userdata->last_login,
                                        // 'added_source' => $userdata->added_source,
                                        'otp_status' => $userdata->otp_status,
                                        'login_otp_status' => $userdata->login_otp_status,
                                        'kyc_status' => $userdata->kyc_status,
                                        'otp_response' => $response,
                                        // 'user_blocked' => $userdata->user_blocked
                                    );
                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'User OTP sent successfully!';
                                }
                                // } else {
                                //     $return['respData'] = '';
                                //     $return['success'] = 0;
                                //     $return['message'] = 'OTP not verified!';
                                // }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'User does not exist!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onVerifyCustomerLogin()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->login_otp) && $decodedParam->login_otp != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $user_otp = xss_clean($decodedParam->login_otp);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $ip = $this->input->ip_address();

                            if ($ip != '::1') {
                                $iplocation = getiplocation($ip);
                                //print_obj($iplocation);die;
                                if (!empty($iplocation) && $iplocation->status != 'fail') {
                                    $ip_country = $iplocation->country;
                                    $ip_countryCode = $iplocation->countryCode;
                                    $ip_region = $iplocation->region;
                                    $ip_regionName = $iplocation->regionName;
                                    $ip_city = $iplocation->city;
                                } else {
                                    $ip_country = 'NA';
                                    $ip_countryCode = 'NA';
                                    $ip_region = 'NA';
                                    $ip_regionName = 'NA';
                                    $ip_city = 'NA';
                                }
                            } else {
                                $ip_country = 'NA';
                                $ip_countryCode = 'NA';
                                $ip_region = 'NA';
                                $ip_regionName = 'NA';
                                $ip_city = 'NA';
                            }

                            $param = array('customer_id' => $customer_id, 'user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param);
                            if (!empty($userdata)) {
                                if ($userdata->login_otp_value == $user_otp) {
                                    $name = $userdata->first_name . ' ' . $userdata->last_name;
                                    $email = $userdata->email;
                                    $upd_array = array(
                                        'login_otp_status' => 2,
                                        'last_login' => dtime
                                    );
                                    $paramup = array('customer_id' => $customer_id);
                                    $updated = $this->am->updateCustomer($upd_array, $paramup);
                                    if ($updated) {
                                        $userDataAfter = $this->am->getCustomerData($param);
                                        $resp = array(
                                            'user_id' => $userDataAfter->customer_id,
                                            'first_name' => $userDataAfter->first_name,
                                            'last_name' => $userDataAfter->last_name,
                                            'email' => $userDataAfter->email,
                                            'phone' => $userDataAfter->phone,
                                            'is_wallet_active' => $userDataAfter->is_wallet_active,
                                            'wallet_amount' => $userDataAfter->wallet_amount,
                                            'added_dtime' => $userDataAfter->dtime,
                                            'last_login' => $userDataAfter->last_login,
                                            'added_source' => $userDataAfter->added_source,
                                            'otp_status' => $userDataAfter->otp_status,
                                            'login_otp_status' => $userDataAfter->login_otp_status,
                                            'user_blocked' => $userDataAfter->user_blocked,
                                            'kyc_status' => $userDataAfter->kyc_status
                                        );
                                        $return['respData'] = $resp;
                                        $return['success'] = 1;
                                        $return['message'] = 'User logged in successfully!';



                                        //email
                                        $subject = 'Logged in Successfully';
                                        $body = '';
                                        $body .= '<p>Hello ' . $name . ', </p>';
                                        $body .= '<p>You have successfully logged in.</p>';
                                        $body .= '<p>Here is your login details:</p>';
                                        $body .= '<p>IP: ' . $ip . '</p>';
                                        $body .= '<p>Country: ' . $ip_country . '</p>';
                                        $body .= '<p>Country Code: ' . $ip_countryCode . '</p>';
                                        $body .= '<p>Region: ' . $ip_region . '</p>';
                                        $body .= '<p>Region Name: ' . $ip_regionName . '</p>';
                                        $body .= '<p>City: ' . $ip_city . '</p>';

                                        $body .= '<br><p>** This is a system generated email. Please do not reply to this email..</p>';

                                        $this->email->set_newline("\r\n");
                                        $this->email->set_mailtype("html");
                                        $this->email->from(FROM_EMAIL, 'Kode Core');
                                        $this->email->to($email);
                                        //$this->email->reply_to($replyemail);
                                        $this->email->subject($subject);
                                        $this->email->message($body);
                                        if ($this->email->send()) {
                                            // $return['otp_status'] = 1;
                                            // $return['otp_msg'] = 'OTP verified and sent to (' . $email . ').';
                                        } else {
                                            //echo $this->email->print_debugger();die;
                                            // $return['otp_status'] = 0;
                                            // $return['otp_msg'] = 'E-mail not sent to ' . $email . '!';
                                        }
                                    }
                                } else {
                                    $return['respData'] = '';
                                    $return['success'] = 0;
                                    $return['message'] = 'Entered OTP not correct!';
                                }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'User does not exist!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onLogoutCustomer()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $param = array('customer_id' => $customer_id, 'user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param);

                            if (!empty($userdata)) {
                                $name = $userdata->first_name . '' . $userdata->last_name;
                                $email = $userdata->email;
                                $upd_array = array(
                                    'login_otp_status' => 0,
                                    'last_logout' => dtime
                                );
                                $paramUp = array('customer_id' => $customer_id);

                                $updated = $this->am->updateCustomer($upd_array, $paramUp);
                                if ($updated) {

                                    $resp = array(
                                        'user_id' => $customer_id,
                                        'login_otp_status' => 0
                                    );
                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'Logout successfully!';
                                }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'User does not exist!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onUpdateCustomerProfile()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts

                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->fname) && $decodedParam->fname != '' && isset($decodedParam->lname) && $decodedParam->lname != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $first_name = xss_clean($decodedParam->fname);
                            $last_name = xss_clean($decodedParam->lname);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $param = array('customer_id' => $customer_id, 'user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param);
                            if (!empty($userdata)) {
                                $paramup = array('customer_id' => $userdata->customer_id);
                                // if ($userdata->login_otp_status == 0) {
                                $upd_array = array(
                                    'first_name' => $first_name,
                                    'last_name' => $last_name
                                );
                                $updated = $this->am->updateCustomer($upd_array, $paramup);
                                if ($updated) {

                                    $upUserData = $this->am->getCustomerData($param);

                                    $resp = array(
                                        'user_id' => $upUserData->customer_id,
                                        'first_name' => $upUserData->first_name,
                                        'last_name' => $upUserData->last_name,
                                        'email' => $upUserData->email,
                                        'phone' => $upUserData->phone,
                                        'bank_name' => $upUserData->bank_name,
                                        'branch_name' => $upUserData->branch_name,
                                        'ac_no' => $upUserData->ac_no,
                                        'ifsc' => $upUserData->ifsc,
                                        'ac_name' => $upUserData->ac_name,
                                        'pan_no' => $upUserData->pan_no,
                                        'aadhaar_no' => $upUserData->aadhaar_no,
                                        'is_wallet_active' => $upUserData->is_wallet_active,
                                        'wallet_amount' => $upUserData->wallet_amount,
                                        'added_dtime' => $upUserData->dtime,
                                        'last_login' => $upUserData->last_login,
                                        'added_source' => $upUserData->added_source,
                                        'otp_status' => $upUserData->otp_status,
                                        'login_otp_status' => $upUserData->login_otp_status,
                                        'kyc_status' => $upUserData->kyc_status,
                                        // 'user_blocked' => $upUserData->user_blocked
                                    );
                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'User updated successfully!';
                                }
                                // } else {
                                //     $return['respData'] = '';
                                //     $return['success'] = 0;
                                //     $return['message'] = 'OTP not verified!';
                                // }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'User does not exist!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return ['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onLoginCustomer_old()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->email) && $decodedParam->email != '' && isset($decodedParam->password) && $decodedParam->password != '') {

                            $if_not_blank = 1; //not blank
                            $email = xss_clean($decodedParam->email);
                            $password = xss_clean($decodedParam->password);

                            //email
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                // $out_message = $email . " is a valid email address" ;
                                $if_email = 1; //email valid
                            } else {
                                $if_email = 0;
                                $out_message += array('valid_email' => $email . " is not a valid email address");
                            }
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_email == 1 && $if_not_blank == 1) {
                            $param = array('email' => $email, 'user_blocked' => 0);
                            $userdata = $this->am->getCustomerData($param);
                            if (!empty($userdata)) {
                                if ($userdata->otp_status == 2) {
                                    if ($userdata->pass == encrypt_it($password)) {
                                        $upd_array = array(
                                            'last_login' => dtime
                                        );
                                        $paramup = array('customer_id' => $userdata->customer_id);
                                        $updated = $this->am->updateCustomer($upd_array, $paramup);
                                        if ($updated) {

                                            $resp = array(
                                                'user_id' => $userdata->customer_id,
                                                'first_name' => $userdata->first_name,
                                                'last_name' => $userdata->last_name,
                                                'email' => $userdata->email,
                                                'phone' => $userdata->phone,
                                                'is_wallet_active' => $userdata->is_wallet_active,
                                                'wallet_amount' => $userdata->wallet_amount,
                                                'added_dtime' => $userdata->dtime,
                                                'last_login' => $userdata->last_login,
                                                'added_source' => $userdata->added_source,
                                                'otp_status' => $userdata->otp_status,
                                                'user_blocked' => $userdata->user_blocked
                                            );
                                            $return['respData'] = $resp;
                                            $return['success'] = 1;
                                            $return['message'] = 'User logged in successfully!';
                                        }
                                    } else {
                                        $return['respData'] = '';
                                        $return['success'] = 0;
                                        $return['message'] = 'Password is incorrect!';
                                    }
                                } else {
                                    $return['respData'] = '';
                                    $return['success'] = 0;
                                    $return['message'] = 'OTP not verified!';
                                }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'User does not exist!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onGetITProjects()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->it_proj_id) && $decodedParam->it_proj_id != '') {

                            $if_not_blank = 1; //not blank
                            $proj_id = xss_clean($decodedParam->it_proj_id);
                        } else {
                            $if_not_blank = 0;
                            //$out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $param = array('proj_id' => $proj_id, 'status' => 1);
                            $projdata = $this->am->getProjectData($param);

                            if (!empty($projdata)) {

                                $resp = array(
                                    'it_proj_id' => $projdata->proj_id,
                                    'title' => $projdata->proj_title,
                                    'description' => $projdata->proj_description,
                                    'amount' => $projdata->proj_amount,
                                    'duration' => $projdata->proj_duration,
                                    'payment_breakup' => ($projdata->payment_breakup != '')?$projdata->payment_breakup:'',
                                    '1st_installment_per' => ($projdata->enter_breakup != '')?$projdata->enter_breakup:'',
                                    'last_installment_per' => ($projdata->enter_breakup2 != '')?$projdata->enter_breakup2:'',
                                    'first_installment_amt' => ($projdata->first_installment_amt != '')?$projdata->first_installment_amt:'',
                                    'last_installment_amt' => ($projdata->last_installment_amt != '')?$projdata->last_installment_amt:'',
                                    'added_dtime' => $projdata->added_dtime,
                                    'status' => $projdata->status
                                );

                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Success!';
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'No data found!';
                            }
                        } else {
                            $param = array('status' => 1);
                            $projdata = $this->am->getProjectData($param, TRUE);

                            // print_obj($projdata);die;

                            if (!empty($projdata)) {
                                foreach ($projdata as $key => $value) {
                                    $resp[] = array(
                                        'it_proj_id' => $value->proj_id,
                                        'title' => $value->proj_title,
                                        'description' => $value->proj_description,
                                        'amount' => $value->proj_amount,
                                        'duration' => $value->proj_duration,
                                        'payment_breakup' => ($value->payment_breakup != '')?$value->payment_breakup:'',
                                        '1st_installment_per' => ($value->enter_breakup != '')?$value->enter_breakup:'',
                                        'last_installment_per' => ($value->enter_breakup2 != '')?$value->enter_breakup2:'',
                                        'first_installment_amt' => ($value->first_installment_amt != '')?$value->first_installment_amt:'',
                                        'last_installment_amt' => ($value->last_installment_amt != '')?$value->last_installment_amt:'',
                                        'added_dtime' => $value->added_dtime,
                                        'status' => $value->status
                                    );
                                }

                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Success!';
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'No data found!';
                            }
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onCustomerBuyITProject()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->it_proj_id) && $decodedParam->it_proj_id != '' && isset($decodedParam->amount) && $decodedParam->amount != '' && isset($decodedParam->payment_status) && $decodedParam->payment_status != '' && isset($decodedParam->payment_id) && $decodedParam->payment_id != '' && isset($decodedParam->gst_per) && $decodedParam->gst_per != '' && isset($decodedParam->gst_rate) && $decodedParam->gst_rate != '' && isset($decodedParam->grand_total) && $decodedParam->grand_total != '' && isset($decodedParam->payment_breakup) && $decodedParam->payment_breakup != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $proj_id = xss_clean($decodedParam->it_proj_id);
                            $received_amount = xss_clean($decodedParam->amount);
                            $gst_per = xss_clean($decodedParam->gst_per);
                            $gst_rate = xss_clean($decodedParam->gst_rate);
                            // $tds_per = xss_clean($decodedParam->tds_per);
                            // $tds_rate = xss_clean($decodedParam->tds_rate);
                            // $royalty_per = xss_clean($decodedParam->royalty_per);
                            // $royalty_rate = xss_clean($decodedParam->royalty_rate);
                            $grand_total = xss_clean($decodedParam->grand_total);
                            $payment_status = xss_clean($decodedParam->payment_status);
                            $payment_response = xss_clean($decodedParam->payment_id);
                            $payment_breakup = xss_clean($decodedParam->payment_breakup);

                            //validations
                            if($payment_status == 1){
                                $if_pay_status = 1;
                            }else{
                                $if_pay_status = 0;
                                $out_message += array('valid_fields' => 'Payment is not successful!');
                            }

                            if($payment_breakup == 2){
                                //if installment is selected then check
                                $installment_serial = (isset($decodedParam->installment_serial) && $decodedParam->installment_serial != '')?xss_clean($decodedParam->installment_serial):'';//1,2
                                $enter_breakup = (isset($decodedParam->first_installment_per) && $decodedParam->first_installment_per != '')?xss_clean($decodedParam->first_installment_per):'';
                                $enter_breakup2 = (isset($decodedParam->last_installment_per) && $decodedParam->last_installment_per)?xss_clean($decodedParam->last_installment_per):'';
                                $first_installment_amt = (isset($decodedParam->first_installment_amt) && $decodedParam->first_installment_amt)?xss_clean($decodedParam->first_installment_amt):'';
                                $last_installment_amt = (isset($decodedParam->last_installment_amt) && $decodedParam->last_installment_amt)?xss_clean($decodedParam->last_installment_amt):'';

                                if($installment_serial != '' && $enter_breakup != '' && $enter_breakup2 != '' && $first_installment_amt != '' && $last_installment_amt != ''){
                                    $if_ins_status = 1;
                                }else{
                                    $if_ins_status = 0;
                                    $out_message += array('valid_fields' => 'Installment details are missing!');
                                }
                                
                            }else{
                                //full payment
                                $installment_serial = '0';
                                $enter_breakup = '0.00';
                                $enter_breakup2 = '0.00';
                                $first_installment_amt = '0.00';
                                $last_installment_amt = '0.00';

                                $if_ins_status = 1;
                            }
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1 && $if_pay_status == 1 && $if_ins_status == 1) {

                            $paramcheck = array(
                                'customer_id' => $customer_id,
                                'proj_id' => $proj_id
                            );
                            
                            $getITProject = $this->am->getProjectData(array('proj_id' => $proj_id));
                            if(!empty($getITProject)){
                                $proj_buy_date = dtime;
                                $proj_duration = $getITProject->proj_duration;
    
                                $endDate = date('Y-m-d H:i:s', strtotime("$proj_buy_date +$proj_duration Month"));
                            }else{
                                $proj_duration = 0;
                                $endDate = '';
                            }
                            
                        // echo $proj_buy_date . '<>' .$endDate;die;

                        $checkCustomerProject = $this->am->getCustomerProjectData($paramcheck);
                            // if (empty($checkCustomerProject)) {
                                $param = array(
                                    'customer_id' => $customer_id,
                                    'proj_id' => $proj_id,
                                    'duration' => $proj_duration,
                                    'end_date' => $endDate,
                                    //'project_amount' => $project_amount,
                                    'received_amount' => $grand_total,
                                    'gst_per' => $gst_per,
                                    'gst_rate' => $gst_rate,
                                    // 'tds_per' => $tds_per,
                                    // 'tds_rate' => $tds_rate,
                                    // 'royalty_per' => $royalty_per,
                                    // 'royalty_rate' => $royalty_rate,
                                    'subtotal' => $received_amount,
                                    'payment_status' => $payment_status,
                                    'payment_response' => $payment_response,
                                    'payment_breakup' => $payment_breakup,
                                    'installment_serial' => $installment_serial,
                                    'enter_breakup' => $enter_breakup,
                                    'enter_breakup2' => $enter_breakup2,
                                    'first_installment_amt' => $first_installment_amt,
                                    'last_installment_amt' => $last_installment_amt,
                                    'added_dtime' => dtime
                                );
                                $added = $this->am->addCustomerProject($param);

                                if ($added) {
                                    $paramUp = array('id' => $added);
                                    $getCustomerProject = $this->am->getCustomerProjectData($paramUp);

                                    if (!empty($getCustomerProject)) {

                                        //send notification starts
                                        $userDetails  =   $this->am->getCustomerData(array('customer_id' => $customer_id));

                                        // print_obj($userDetails);die;
                                        if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                            $fcm = $userDetails->fcm_token;
                                            $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                            //$fcm = 'cNf2---6Vs9';
                                            $icon = NOTIFICATION_ICON;

                                            if($payment_breakup == '2'){
                                                if($installment_serial == '1'){
                                                    $notification_title = 'You have successfully paid your LOI for an IT project';
                                                    $notification_body = 'Thanks for buying an IT project. Payment of LOI was successful';
                                                }else{
                                                    $notification_title = 'You have successfully paid your rest amount for an IT project';
                                                    $notification_body = 'Thanks for buying an IT project. Payment of rest amount was successful';
                                                }
                                            }else{
                                                $notification_title = 'You bought an IT project';
                                                $notification_body = 'Thanks for buying an IT project. Payment was successful';
                                            }
                                            
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
                                                    'customer_id' => $getCustomerProject->customer_id,
                                                    "notification_title" => $notification_title,
                                                    "notification_body" => $notification_body,
                                                    "notification_event" => 'buy_it_project',
                                                    "dtime" => dtime
                                                );

                                                $addLog = $this->am->addNotificationLog($logData);
                                            }
                                        }
                                        //send notification ends

                                        $resp = array(
                                            'itproj_added_id' => $added,
                                            'user_id' => $getCustomerProject->customer_id,
                                            'it_proj_id' => $getCustomerProject->proj_id,
                                            'project_amount' => $getCustomerProject->project_amount,
                                            'received_amount' => $getCustomerProject->received_amount,
                                            'application_status' => $getCustomerProject->application_status,
                                            'payment_status' => $getCustomerProject->payment_status,
                                            'payment_mode' => $getCustomerProject->payment_mode
                                        );



                                        $return['respData'] = $resp;
                                        $return['success'] = 1;
                                        $return['message'] = 'IT project bought successfully!';
                                    } else {
                                        $return['respData'] = '';
                                        $return['success'] = 0;
                                        $return['message'] = 'IT project bought successfully but data is missing!';
                                    }
                                } else {
                                    $return['respData'] = '';
                                    $return['success'] = 0;
                                    $return['message'] = 'IT project not bought successfully!';
                                }
                            // } else {
                            //     $return['respData'] = '';
                            //     $return['success'] = 0;
                            //     $return['message'] = 'IT project already applied by you!';
                            // }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onCustomerBoughtITProjects()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {

                            if (isset($decodedParam->it_proj_id) && $decodedParam->it_proj_id != '') {
                                $proj_id = xss_clean($decodedParam->it_proj_id);

                                $paramcheck = array(
                                    'customers_it_projects.customer_id' => $customer_id,
                                    'customers_it_projects.proj_id' => $proj_id
                                );

                                $getCustomerProject = $this->am->getCustomerProjects($paramcheck);

                                if (!empty($getCustomerProject)) {

                                    $resp = array(
                                        'user_id' => $getCustomerProject->customer_id,
                                        'it_proj_id' => $getCustomerProject->proj_id,
                                        'project_title'  => $getCustomerProject->proj_title,
                                        'user_name'  => $getCustomerProject->first_name . ' ' . $getCustomerProject->last_name,
                                        'user_email'  => $getCustomerProject->email,
                                        'user_phone'  => $getCustomerProject->phone,
                                        'project_amount' => $getCustomerProject->proj_amount,
                                        'received_amount' => $getCustomerProject->received_amount,
                                        'application_status' => $getCustomerProject->application_status,
                                        'payment_status' => $getCustomerProject->payment_status,
                                        'installment_serial' => $getCustomerProject->installment_serial,
                                        'dtime' => $getCustomerProject->added_dtime,
                                        'payment_mode' => $getCustomerProject->payment_mode
                                    );

                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'IT project found!';
                                } else {
                                    $return['respData'] = [];
                                    $return['success'] = 0;
                                    $return['message'] = 'IT project not found!';
                                }
                            } else {
                                $paramcheck = array(
                                    'customers_it_projects.customer_id' => $customer_id
                                );

                                $getCustomerProject = $this->am->getCustomerProjects($paramcheck, TRUE);

                                // print_obj($getCustomerProject);die;
 
                                if (!empty($getCustomerProject)) {
                                    foreach ($getCustomerProject as $key => $value) {

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
                                            $ins_msg = '(LOI)';

                                            $installment_tr = '
                                            <tr>
                                            <td style="width:20%; height: 20px;">Installment #:</td>
                                            <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">'.$ins_msg.'</td>
                                            </tr>';
                                        }else if ($value->installment_serial == 2){
                                            $ins_msg = '(Rest Amount)';

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


                                        $resp[] = array(
                                            'user_id' => $value->customer_id,
                                            'it_proj_id' => $value->proj_id,
                                            'project_title'  => $proj_title,
                                            'user_name'  => $user_name,
                                            'user_email'  => $email,
                                            'user_phone'  => $phone,
                                            'project_amount' => $project_amount,
                                            'received_amount' => $received_amount . $ins_msg,
                                            'application_status' => $value->application_status,
                                            'payment_status' => $value->payment_status,
                                            'installment_serial' => $value->installment_serial,
                                            'dtime' => $dtime,
                                            'payment_mode' => $value->payment_mode,
                                            'filepath' => $filepath
                                        );
                                    }

                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'IT project found!';
                                } else {
                                    $return['respData'] = [];
                                    $return['success'] = 0;
                                    $return['message'] = 'IT project not found!';
                                }
                            }
                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onGetInvPlans()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->inv_plan_id) && $decodedParam->inv_plan_id != '') {

                            $if_not_blank = 1; //not blank
                            $plan_id = xss_clean($decodedParam->inv_plan_id);
                        } else {
                            $if_not_blank = 0;
                            //$out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $param = array('plan_id' => $plan_id, 'status' => 1);
                            $planData = $this->am->getInvPlansData($param);

                            if (!empty($planData)) {

                                $resp = array(
                                    'inv_plan_id' => $planData->plan_id,
                                    'title' => $planData->plan_name,
                                    'description' => $planData->plan_description,
                                    'summary' => $planData->plan_summary,
                                    'return_rate' => $planData->plan_return_rate,
                                    'amount' => $planData->amount,
                                    'duration' => $planData->duration,
                                    'added_dtime' => $planData->added_dtime,
                                    'status' => $planData->status
                                );

                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Success!';
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'No data found!';
                            }
                        } else {
                            $param = array('status' => 1);
                            $planData = $this->am->getInvPlansData($param, TRUE, 'order_', 'ASC');

                            // print_obj($planData);die;

                            if (!empty($planData)) {
                                foreach ($planData as $key => $value) {
                                    $resp[] = array(
                                        'inv_plan_id' => $value->plan_id,
                                        'title' => $value->plan_name,
                                        'description' => $value->plan_description,
                                        'summary' => $value->plan_summary,
                                        'return_rate' => $value->plan_return_rate,
                                        'amount' => $value->amount,
                                        'duration' => $value->duration,
                                        'added_dtime' => $value->added_dtime,
                                        'status' => $value->status
                                    );
                                }

                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Success!';
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'No data found!';
                            }
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onCustomerBuyInvPlan()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->inv_plan_id) && $decodedParam->inv_plan_id != '' && isset($decodedParam->inv_amount) && $decodedParam->inv_amount != '' && isset($decodedParam->payment_status) && $decodedParam->payment_status != '' && isset($decodedParam->payment_id) && $decodedParam->payment_id != '' && isset($decodedParam->grand_total) && $decodedParam->grand_total != '') {

                            //&& isset($decodedParam->gst_per) && $decodedParam->gst_per != '' && isset($decodedParam->gst_rate) && $decodedParam->gst_rate != '' && isset($decodedParam->tds_per) && $decodedParam->tds_per != '' && isset($decodedParam->tds_rate) && $decodedParam->tds_rate != ''

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $plan_id = xss_clean($decodedParam->inv_plan_id);
                            $received_amount = xss_clean($decodedParam->inv_amount);
                            // $gst_per = xss_clean($decodedParam->gst_per);
                            // $gst_rate = xss_clean($decodedParam->gst_rate);
                            // $tds_per = xss_clean($decodedParam->tds_per);
                            // $tds_rate = xss_clean($decodedParam->tds_rate);
                            $grand_total = xss_clean($decodedParam->grand_total);
                            $payment_status = xss_clean($decodedParam->payment_status);
                            $payment_response = xss_clean($decodedParam->payment_id); 
                            
                            //validations
                            if($payment_status == 1){
                                $if_pay_status = 1;
                            }else{
                                $if_pay_status = 0;
                                $out_message += array('valid_fields' => 'Payment is not successful!');
                            }
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1 && $if_pay_status == 1) {

                            // $paramcheck = array(
                            //     'customer_id' => $customer_id,
                            //     'plan_id' => $plan_id
                            // );
                            // $checkCustomerProject = $this->am->getCustomerInvesmentPlanData($paramcheck);

                            // if (empty($checkCustomerProject)) {

                                $getInvPlan = $this->am->getInvPlansData(array('plan_id' => $plan_id));
                                if(!empty($getInvPlan)){
                                    $buy_date = dtime;
                                    $duration = $getInvPlan->duration;
        
                                    $endDate = date('Y-m-d H:i:s', strtotime("$buy_date +$duration Month"));
                                }else{
                                    $duration = 0;
                                    $endDate = '';
                                }

                            $param = array(
                                'customer_id' => $customer_id,
                                'plan_id' => $plan_id,
                                'received_amount' => $grand_total,
                                'duration' => $duration,
                                'end_date' => $endDate,
                                // 'gst_per' => $gst_per,
                                // 'gst_rate' => $gst_rate,
                                // 'tds_per' => $tds_per,
                                // 'tds_rate' => $tds_rate,
                                'subtotal' => $received_amount,
                                'payment_status' => $payment_status,
                                'payment_response' => $payment_response,
                                'added_dtime' => dtime
                            );
                            $added = $this->am->addCustomerInvesmentPlan($param);

                            if ($added) {
                                $paramUp = array('id' => $added);
                                $getCustomerProject = $this->am->getCustomerInvesmentPlanData($paramUp);

                                if (!empty($getCustomerProject)) {

                                    //send notification starts
                                    $userDetails  =   $this->am->getCustomerData(array('customer_id' => $customer_id));

                                    // print_obj($userDetails);die;
                                    if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                        $fcm = $userDetails->fcm_token;
                                        $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                        //$fcm = 'cNf2---6Vs9';
                                        $icon = NOTIFICATION_ICON;
                                        $notification_title = 'You bought an Investment Plan';
                                        $notification_body = 'Thanks for buying an Investment Plan. It is now under approval. We will update you as soon as it gets approved.';
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
                                                "customer_id" => $customer_id,
                                                "notification_title" => $notification_title,
                                                "notification_body" => $notification_body,
                                                "notification_event" => 'buy_inv_plan',
                                                "dtime" => dtime
                                            );

                                            $addLog = $this->am->addNotificationLog($logData);
                                        }
                                    }
                                    //send notification ends

                                    $resp = array(
                                        'inv_added_id' => $added,
                                        'user_id' => $getCustomerProject->customer_id,
                                        'inv_plan_id' => $getCustomerProject->plan_id,
                                        //'actual_amount' => $getCustomerProject->actual_amount,
                                        'received_amount' => $getCustomerProject->received_amount,
                                        'application_status' => $getCustomerProject->application_status,
                                        'payment_status' => $getCustomerProject->payment_status,
                                        'payment_mode' => $getCustomerProject->payment_mode
                                    );
                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'Invesment Plan bought successfully!';
                                } else {
                                    $return['respData'] = '';
                                    $return['success'] = 0;
                                    $return['message'] = 'Invesment Plan bought successfully but data is missing!';
                                }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'Invesment Plan not bought successfully!';
                            }
                            // } else {
                            //     $return['respData'] = '';
                            //     $return['success'] = 0;
                            //     $return['message'] = 'Invesment Plan already applied by you!';
                            // }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onCustomerBoughtInvPlans()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {

                            if (isset($decodedParam->inv_plan_id) && $decodedParam->inv_plan_id != '') {
                                $plan_id = xss_clean($decodedParam->inv_plan_id);

                                $paramcheck = array(
                                    'customers_investment_plans.customer_id' => $customer_id,
                                    'customers_investment_plans.plan_id' => $plan_id
                                );
                                $getCustomerPlan = $this->am->getCustomerInvesmentPlans($paramcheck);

                                if (!empty($getCustomerPlan)) {

                                    $resp = array(
                                        'inv_row_id' => $getCustomerPlan->id,
                                        'user_id' => $getCustomerPlan->customer_id,
                                        'inv_plan_id' => $getCustomerPlan->plan_id,
                                        'plan_title'  => $getCustomerPlan->plan_name,
                                        'user_name'  => $getCustomerPlan->first_name . ' ' . $getCustomerPlan->last_name,
                                        'user_email'  => $getCustomerPlan->email,
                                        'user_phone'  => $getCustomerPlan->phone,
                                        //'actual_amount' => $getCustomerPlan->actual_amount,
                                        'inv_amount' => $getCustomerPlan->received_amount,
                                        'application_status' => $getCustomerPlan->application_status,
                                        'payment_status' => $getCustomerPlan->payment_status,
                                        'dtime' => $getCustomerPlan->added_dtime,
                                        'payment_mode' => $getCustomerPlan->payment_mode
                                    );

                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'Investment Plans found!';
                                } else {
                                    $return['respData'] = [];
                                    $return['success'] = 0;
                                    $return['message'] = 'Investment Plans not found!';
                                }
                            } else {
                                $paramcheck = array(
                                    'customers_investment_plans.customer_id' => $customer_id
                                );
                                $getCustomerPlan = $this->am->getCustomerInvesmentPlans($paramcheck, TRUE);

                                if (!empty($getCustomerPlan)) {

                                    foreach ($getCustomerPlan as $key => $value) {

                                         //  TCPDF Integration
                                         $tcpdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
                                         // Set Title
                                         $tcpdf->SetTitle('Kodecore - Investment Plan Invoice');
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
 
                                         $title = $value->plan_name;
                                         $user_name = $value->first_name . ' ' . $value->last_name;
                                         $email = $value->email;
                                         $phone = $value->phone;
                                        //  $project_amount = $value->project_amount;
                                        $subtotal = $value->subtotal;
                                        $gst_per = $value->gst_per;
                                        $gst_rate = $value->gst_rate;
                                        $tds_per = $value->tds_per;
                                        $tds_rate = $value->tds_rate;
                                         $received_amount = $value->received_amount;
                                         $dtime = $value->added_dtime;
                                         $appl_status = ($value->application_status = 0)?'Applied':'Approved';
                                         $pay_status = ($value->payment_status = 1)?'Paid':'Not Paid';
 
                                         $tcpdf->AddPage();
                                         
                                         $html = <<<EOD
                                         <center>
                                         <table border="0" cellpadding="5" style="border: 1px solid #ccc; padding: 3%; font-family: arial; font-size: 14px;">
                                         <tbody>
                                             <tr>
                                             <td style="text-align: center; height: 30px; font-size: 25px; font-weight: bold;" colspan="2">Receipt</td>
                                             </tr>
 
                                             <tr>
                                             <td style="width:20%; height: 20px;">Title:</td>
                                             <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$title</td>
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
 
                                             <tr>
                                             <td style="width:20%; height: 20px;">Actual Amount:</td>
                                             <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$subtotal</td>
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
 
 
                                         $filename = 'Invplan_'.$key.'_'.date("YmdHis", time()) .'.pdf';
                                         $filepath = base_url().'uploads/invoices/'.$filename;
 
                                         $fullname = ABS_PATH . $filename;
                                         
                                         $tcpdf->Output($fullname, 'F');


                                        $resp[] = array(
                                            'inv_row_id' => $value->id,
                                            'user_id' => $value->customer_id,
                                            'inv_plan_id' => $value->plan_id,
                                            'plan_title'  => $value->plan_name,
                                            'user_name'  => $value->first_name . ' ' . $value->last_name,
                                            'user_email'  => $value->email,
                                            'user_phone'  => $value->phone,
                                            //'actual_amount' => $value->actual_amount,
                                            'inv_amount' => $value->received_amount,
                                            'application_status' => $value->application_status,
                                            'payment_status' => $value->payment_status,
                                            'dtime' => $value->added_dtime,
                                            'payment_mode' => $value->payment_mode,
                                            'filepath' => $filepath
                                        );
                                    }

                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'Investment Plans found!';
                                } else {
                                    $return['respData'] = [];
                                    $return['success'] = 0;
                                    $return['message'] = 'Investment Plans not found!';
                                }
                            }
                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onGetCryptoList()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        $resp = array();
                        //validation starts
                        if (isset($decodedParam->crypto_id) && $decodedParam->crypto_id != '') {

                            $if_not_blank = 1; //not blank
                            $crypto_id = xss_clean($decodedParam->crypto_id);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        // validation ends

                        // check if valid
                        if ($if_not_blank == 0) {

                            //get kodecoin
                            $cryptodata = $this->am->getCryptoData(array('crypto_id' => 1));
                            if (!empty($cryptodata)) {
                                // if ($userdata->login_otp_status == 0) {
                                $kode_array = array(
                                    'crypto_id' => $cryptodata->crypto_pid,
                                    'crypto_name' => $cryptodata->name,
                                    'crypto_symbol' => $cryptodata->symbol,
                                    'crypto_description' => $cryptodata->description,
                                    'current_price_inr' => $cryptodata->price_inr,
                                    'current_price_btc' => $cryptodata->price_btc,
                                    'current_price_usd' => $cryptodata->price_usd
                                );
                                $resp[] = $kode_array;
                            }
                            //ends kodecoin

                            //get bitcoin & eth
                            $crypto_arr = array(
                                'bitcoin',
                                'ethereum'
                            );

                            if (!empty($crypto_arr)) {
                                foreach ($crypto_arr as $key => $value) {
                                    $ch = curl_init();

                                    curl_setopt($ch, CURLOPT_URL, 'https://api.coingecko.com/api/v3/coins/' . $value);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


                                    $headers = array();
                                    $headers[] = 'Accept: */*';
                                    $headers[] = 'User-Agent: Thunder Client (https://www.thunderclient.io)';
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                    $result = curl_exec($ch);
                                    if (curl_errno($ch)) {
                                        echo 'Error:' . curl_error($ch);
                                    }
                                    curl_close($ch);

                                    $result = json_decode($result);
                                    //echo $result->symbol;
                                    // print_obj($result);die;

                                    if (!empty($result)) {
                                        $btc_array = array(
                                            'crypto_id' => $result->id,
                                            'crypto_name' => $result->name,
                                            'crypto_symbol' => $result->symbol,
                                            'crypto_description' => $result->description->en,
                                            'current_price_inr' => strval($result->market_data->current_price->inr),
                                            'current_price_btc' => strval($result->market_data->current_price->btc),
                                            'current_price_usd' => strval($result->market_data->current_price->usd)
                                        );
                                        $resp[] = $btc_array;
                                    }
                                }
                            }


                            //ends bitcoin & eth

                            //print_obj($resp);die;

                            if (!empty($resp)) {
                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Cryptocurrency found successfully!';
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'Cryptocurrency not found!';
                            }
                        } else {

                            if ($crypto_id == 'kodecoin') {
                                //get kodecoin
                                $cryptodata = $this->am->getCryptoData(array('crypto_id' => 1));
                                if (!empty($cryptodata)) {
                                    // if ($userdata->login_otp_status == 0) {
                                    $kode_array = array(
                                        'crypto_id' => $cryptodata->crypto_pid,
                                        'crypto_name' => $cryptodata->name,
                                        'crypto_symbol' => $cryptodata->symbol,
                                        'crypto_description' => $cryptodata->description,
                                        'current_price_inr' => $cryptodata->price_inr,
                                        'current_price_btc' => $cryptodata->price_btc,
                                        'current_price_usd' => $cryptodata->price_usd
                                    );
                                    $resp[] = $kode_array;
                                }
                                //ends kodecoin
                            } else if ($crypto_id == 'bitcoin' || $crypto_id == 'ethereum') {
                                $ch = curl_init();

                                curl_setopt($ch, CURLOPT_URL, 'https://api.coingecko.com/api/v3/coins/' . $crypto_id);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


                                $headers = array();
                                $headers[] = 'Accept: */*';
                                $headers[] = 'User-Agent: Thunder Client (https://www.thunderclient.io)';
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                $result = curl_exec($ch);
                                if (curl_errno($ch)) {
                                    echo 'Error:' . curl_error($ch);
                                }
                                curl_close($ch);

                                $result = json_decode($result);
                                //echo $result->symbol;
                                // print_obj($result);die;

                                if (!empty($result)) {
                                    $btc_array = array(
                                        'crypto_id' => $result->id,
                                        'crypto_name' => $result->name,
                                        'crypto_symbol' => $result->symbol,
                                        'crypto_description' => $result->description->en,
                                        'current_price_inr' => strval($result->market_data->current_price->inr),
                                        'current_price_btc' => strval($result->market_data->current_price->btc),
                                        'current_price_usd' => strval($result->market_data->current_price->usd)
                                    );
                                    $resp[] = $btc_array;
                                }
                            } else {
                                $resp = array();
                            }


                            if (!empty($resp)) {
                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Cryptocurrency found successfully!';
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'Cryptocurrency not found!';
                            }
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onCustomerBuyCrypto()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->crypto_id) && $decodedParam->crypto_id != '' && isset($decodedParam->quantity) && $decodedParam->quantity != '' && isset($decodedParam->amount) && $decodedParam->amount != '' && isset($decodedParam->payment_status) && $decodedParam->payment_status != '' && isset($decodedParam->payment_id) && $decodedParam->payment_id != '' && isset($decodedParam->grand_total) && $decodedParam->grand_total != '') {

                            // && isset($decodedParam->gst_per) && $decodedParam->gst_per != '' && isset($decodedParam->gst_rate) && $decodedParam->gst_rate != '' && isset($decodedParam->tds_per) && $decodedParam->tds_per != '' && isset($decodedParam->tds_rate) && $decodedParam->tds_rate != ''

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $crypto_id = xss_clean($decodedParam->crypto_id);
                            $received_amount = xss_clean($decodedParam->amount);
                            $quantity = xss_clean($decodedParam->quantity); 
                            $payment_status = xss_clean($decodedParam->payment_status);
                            $payment_response = xss_clean($decodedParam->payment_id);
                            // $gst_per = xss_clean($decodedParam->gst_per);
                            // $gst_rate = xss_clean($decodedParam->gst_rate);
                            // $tds_per = xss_clean($decodedParam->tds_per);
                            // $tds_rate = xss_clean($decodedParam->tds_rate);
                            $grand_total = xss_clean($decodedParam->grand_total);
 
                            //validations
                            if($payment_status == 1){
                                $if_pay_status = 1;
                            }else{
                                $if_pay_status = 0;
                                $out_message += array('valid_fields' => 'Payment is not successful!');
                            }
							
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1 && $if_pay_status == 1) {

                            $param = array(
                                'customer_id' => $customer_id,
                                'crypto_pid' => $crypto_id,
                                'received_amount' => $grand_total,
                                // 'gst_per' => $gst_per,
                                // 'gst_rate' => $gst_rate,
                                // 'tds_per' => $tds_per,
                                // 'tds_rate' => $tds_rate,
                                'subtotal' => $received_amount,
                                'quantity' => $quantity,
                                'payment_status' => $payment_status,
                                'payment_response' => $payment_response,
                                'added_dtime' => dtime
                            );
                            $added = $this->am->addCryptoBuy($param);

                            if ($added) {
                                $paramUp = array('crypto_buy_id' => $added);
                                $getCrypto = $this->am->getCryptoBuyData($paramUp);

                                if (!empty($getCrypto)) {

                                    //send notification starts
                                    $userDetails  =   $this->am->getCustomerData(array('customer_id' => $customer_id));

                                    // print_obj($userDetails);die;
                                    if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                        $fcm = $userDetails->fcm_token;
                                        $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                        //$fcm = 'cNf2---6Vs9';
                                        $icon = NOTIFICATION_ICON;
                                        $notification_title = 'You bought Cryptocurrency';
                                        $notification_body = 'Thanks for buying Cryptocurrency. It is now under approval. We will update you as soon as it gets approved.';
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
                                                "notification_event" => 'buy_crypto',
                                                "dtime" => dtime
                                            );

                                            $addLog = $this->am->addNotificationLog($logData);
                                        }
                                    }
                                    //send notification ends

                                    $resp = array(
                                        'crypto_buyid' => strval($added),
                                        'user_id' => $getCrypto->customer_id,
                                        'crypto_id' => $getCrypto->crypto_pid,
                                        //'actual_amount' => $getCrypto->actual_amount,
                                        'received_amount' => $getCrypto->received_amount,
                                        'quantity' => $getCrypto->quantity,
                                        'application_status' => $getCrypto->application_status,
                                        'payment_status' => $getCrypto->payment_status,
                                        'payment_mode' => $getCrypto->payment_mode
                                    );
                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'Cryptocurrency bought successfully!';
                                } else {
                                    $return['respData'] = '';
                                    $return['success'] = 0;
                                    $return['message'] = 'Cryptocurrency bought successfully but data is missing!';
                                }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'Cryptocurrency not bought successfully!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }


    public function onCustomerBoughtCrypto()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {

                            if (isset($crypto_id) && $crypto_id != '') {
                                $paramUp = array('crypto_pid' => $crypto_id, 'customer_id' => $customer_id);
                            } else {
                                $paramUp = array('customer_id' => $customer_id);
                            }

                            $getCrypto = $this->am->getCryptoBuyData($paramUp, TRUE);

                            if (!empty($getCrypto)) {

                                foreach ($getCrypto as $key => $value) {
                                    $resp[] = array(
                                        'crypto_buyid' => $value->crypto_buy_id,
                                        'user_id' => $value->customer_id,
                                        'crypto_id' => $value->crypto_pid,
                                        //'actual_amount' => $value->actual_amount,
                                        'received_amount' => $value->received_amount,
                                        'quantity' => $value->quantity,
                                        'application_status' => $value->application_status,
                                        'payment_status' => $value->payment_status,
                                        'payment_mode' => $value->payment_mode,
                                        'dtime' => $value->added_dtime
                                    );
                                }

                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Cryptocurrency detals found!';
                            } else {
                                $return['respData'] = [];
                                $return['success'] = 0;
                                $return['message'] = 'Cryptocurrency not bought!';
                            }
                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }


    public function onCustomerSellCrypto()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->crypto_id) && $decodedParam->crypto_id != '' && isset($decodedParam->quantity) && $decodedParam->quantity != '' && isset($decodedParam->amount) && $decodedParam->amount != '' && isset($decodedParam->grand_total) && $decodedParam->grand_total != '') {

                            //&& isset($decodedParam->gst_per) && $decodedParam->gst_per != '' && isset($decodedParam->gst_rate) && $decodedParam->gst_rate != '' && isset($decodedParam->tds_per) && $decodedParam->tds_per != '' && isset($decodedParam->tds_rate) && $decodedParam->tds_rate != '' 

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $crypto_id = xss_clean($decodedParam->crypto_id);
                            $received_amount = xss_clean($decodedParam->amount);
                            // $gst_per = xss_clean($decodedParam->gst_per);
                            // $gst_rate = xss_clean($decodedParam->gst_rate);
                            // $tds_per = xss_clean($decodedParam->tds_per);
                            // $tds_rate = xss_clean($decodedParam->tds_rate);
                            $grand_total = xss_clean($decodedParam->grand_total);
                            $quantity = xss_clean($decodedParam->quantity);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Fields should not empty!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {

                            $param = array(
                                'customer_id' => $customer_id,
                                'crypto_pid' => $crypto_id,
                                'received_amount' => $grand_total,
                                // 'gst_per' => $gst_per,
                                // 'gst_rate' => $gst_rate,
                                // 'tds_per' => $tds_per,
                                // 'tds_rate' => $tds_rate,
                                'subtotal' => $received_amount,
                                'quantity' => $quantity,
                                'added_dtime' => dtime
                            );
                            $added = $this->am->addCryptoSell($param);

                            if ($added) {
                                $paramUp = array('crypto_sell_id' => $added);
                                $getCrypto = $this->am->getCryptoSellData($paramUp);

                                if (!empty($getCrypto)) {
                                    //send notification starts
                                    $userDetails  =   $this->am->getCustomerData(array('customer_id' => $customer_id));

                                    // print_obj($userDetails);die;
                                    if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                        $fcm = $userDetails->fcm_token;
                                        $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                        //$fcm = 'cNf2---6Vs9';
                                        $icon = NOTIFICATION_ICON;
                                        $notification_title = 'You sold Cryptocurrency';
                                        $notification_body = 'Thanks for selling Cryptocurrency. It is now under approval. We will update you as soon as it gets approved.';
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
                                                "notification_event" => 'buy_crypto',
                                                "dtime" => dtime
                                            );

                                            $addLog = $this->am->addNotificationLog($logData);
                                        }
                                    }
                                    //send notification ends


                                    $resp = array(
                                        'crypto_sellid' => strval($added),
                                        'user_id' => $getCrypto->customer_id,
                                        'crypto_id' => $getCrypto->crypto_pid,
                                        //'actual_amount' => $getCrypto->actual_amount,
                                        'received_amount' => $getCrypto->received_amount,
                                        'quantity' => $getCrypto->quantity,
                                        'application_status' => $getCrypto->application_status,
                                        'payment_status' => $getCrypto->payment_status,
                                        'payment_mode' => $getCrypto->payment_mode
                                    );
                                    $return['respData'] = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'Cryptocurrency sold successfully!';
                                } else {
                                    $return['respData'] = '';
                                    $return['success'] = 0;
                                    $return['message'] = 'Cryptocurrency sold successfully but data is missing!';
                                }
                            } else {
                                $return['respData'] = '';
                                $return['success'] = 0;
                                $return['message'] = 'Cryptocurrency not sold successfully!';
                            }
                        } else {
                            $return['respData'] = '';
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = '';
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }


    public function onCryptoWallet()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $crypto_id = (isset($decodedParam->crypto_id) && $decodedParam->crypto_id != '')?xss_clean($decodedParam->crypto_id):'';
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {
                            $total_buy_amount = 0;
                            $total_buy_qty = 0;
                            $total_sell_amount = 0;
                            $total_sell_qty = 0;

                            
                            if($crypto_id != ''){
                                $paramUp = array('application_status' => '1', 'crypto_pid' => $crypto_id, 'customer_id' => $customer_id);

                                $getBuyCrypto = $this->am->getCryptoBuyFinalData($paramUp);



                                if (!empty($getBuyCrypto) && $getBuyCrypto->customer_id != '') {

                                    $resp['buy_data'][] = array(
                                        'user_id' => $getBuyCrypto->customer_id,
                                        'crypto_id' => $getBuyCrypto->crypto_pid,
                                        'total_received_amount' => $getBuyCrypto->total_received_amount,
                                        'total_quantity' => $getBuyCrypto->total_quantity
                                    );
                                    $total_buy_amount = $getBuyCrypto->total_received_amount;
                                    $total_buy_qty = $getBuyCrypto->total_quantity;
                                } else {
                                    $resp['buy_data'] = [];
                                    $total_buy_amount = 0;
                                    $total_buy_qty = 0;
                                }

                                $paramUp = array('application_status' => '1', 'crypto_pid' => $crypto_id, 'customer_id' => $customer_id);

                                $getSellCrypto = $this->am->getCryptoSellFinalData($paramUp);

                                if (!empty($getSellCrypto) && $getSellCrypto->customer_id != '') {

                                    $resp['sell_data'][] = array(
                                        'user_id' => $getSellCrypto->customer_id,
                                        'crypto_id' => $getSellCrypto->crypto_pid,
                                        'total_received_amount' => $getSellCrypto->total_received_amount,
                                        'total_quantity' => $getSellCrypto->total_quantity
                                    );
                                    $total_sell_amount = $getSellCrypto->total_received_amount;
                                    $total_sell_qty = $getSellCrypto->total_quantity;
                                } else {
                                    $resp['sell_data'] = [];
                                    $total_sell_amount = 0;
                                    $total_sell_qty = 0;
                                }

                                if (!empty($resp)) {

                                    // if (!empty($resp['buy_data'])) {
                                    $tot_amount = ($total_buy_amount - $total_sell_amount);
                                    $resp['total_amount'] = strval(number_format((float)$tot_amount, 2, '.', ''));
                                    $resp['total_qty'] = strval(($total_buy_qty - $total_sell_qty));
                                    $resp['crypto_id'] = $crypto_id;
                                    // }else{
                                    //     $resp['total_amount'] = $total_sell_amount;
                                    //     $resp['total_qty'] = $total_sell_qty;
                                    // }


                                    $resp_1 = $resp;
                                    $return['success'] = 1;
                                    $return['message'] = 'Found!';
                                } else {
                                    $return['respData'] = [];
                                    $return['success'] = 0;
                                    $return['message'] = 'Not found!';
                                }
                                  
                            }else{
                                $c_array = array(
                                    'bitcoin',
                                    'kodecoin',
                                    'ethereum'
                                );

                                foreach($c_array as $key => $val){
                                    $paramUp = array('application_status' => '1', 'crypto_pid' => $val, 'customer_id' => $customer_id);

                                    $getBuyCrypto = $this->am->getCryptoBuyFinalData($paramUp);
        
        
        
                                    if (!empty($getBuyCrypto) && $getBuyCrypto->customer_id != '') {
        
                                        $resp['buy_data'][] = array(
                                            'user_id' => $getBuyCrypto->customer_id,
                                            'crypto_id' => $getBuyCrypto->crypto_pid,
                                            'total_received_amount' => $getBuyCrypto->total_received_amount,
                                            'total_quantity' => $getBuyCrypto->total_quantity
                                        );
                                        $total_buy_amount = $getBuyCrypto->total_received_amount;
                                        $total_buy_qty = $getBuyCrypto->total_quantity;
                                    } else {
                                        $resp['buy_data'] = [];
                                        $total_buy_amount = 0;
                                        $total_buy_qty = 0;
                                    }
        
                                    $paramUp = array('application_status' => '1', 'crypto_pid' => $crypto_id, 'customer_id' => $customer_id);
        
                                    $getSellCrypto = $this->am->getCryptoSellFinalData($paramUp);
        
                                    if (!empty($getSellCrypto) && $getSellCrypto->customer_id != '') {
        
                                        $resp['sell_data'][] = array(
                                            'user_id' => $getSellCrypto->customer_id,
                                            'crypto_id' => $getSellCrypto->crypto_pid,
                                            'total_received_amount' => $getSellCrypto->total_received_amount,
                                            'total_quantity' => $getSellCrypto->total_quantity
                                        );
                                        $total_sell_amount = $getSellCrypto->total_received_amount;
                                        $total_sell_qty = $getSellCrypto->total_quantity;
                                    } else {
                                        $resp['sell_data'] = [];
                                        $total_sell_amount = 0;
                                        $total_sell_qty = 0;
                                    }
        
                                    if (!empty($resp)) {
        
                                        // if (!empty($resp['buy_data'])) {
                                        $tot_amount = ($total_buy_amount - $total_sell_amount);
                                        $resp['total_amount'] = strval(number_format((float)$tot_amount, 2, '.', ''));
                                        $resp['total_qty'] = strval(($total_buy_qty - $total_sell_qty));
                                        $resp['crypto_id'] = $val;
                                        // }else{
                                        //     $resp['total_amount'] = $total_sell_amount;
                                        //     $resp['total_qty'] = $total_sell_qty;
                                        // }
        
        
                                        $resp_1[] = $resp;
                                        $return['success'] = 1;
                                        $return['message'] = 'Found!';
                                    } else {
                                        $return['respData'] = [];
                                        $return['success'] = 0;
                                        $return['message'] = 'Not found!';
                                    }
                                } 
                            }

                            if (!empty($resp)) {
                                $return['respData'] = $resp_1;
                                $return['success'] = 1;
                                $return['message'] = 'Found!';
                            }else{
                                $return['respData'] = [];
                                $return['success'] = 0;
                                $return['message'] = 'Not found!';
                            }
                            
                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onCustomerRedeemRequest()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->amount) && $decodedParam->amount != '' && isset($decodedParam->payment_status) && $decodedParam->payment_status != '' && isset($decodedParam->payment_id) && $decodedParam->payment_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $redeem_amount = xss_clean($decodedParam->amount);
                            $payment_status = xss_clean($decodedParam->payment_status);
                            $payment_response = xss_clean($decodedParam->payment_id);

                            
                            //validations
                            if($payment_status == 1){
                                $if_pay_status = 1;
                            }else{
                                $if_pay_status = 0;
                                $out_message += array('valid_fields' => 'Payment is not successful!');
                            }
							

                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1 && $if_pay_status == 1) {

                            // if (isset($crypto_id) && $crypto_id != '') {
                            //     $paramUp = array('crypto_pid' => $crypto_id, 'customer_id' => $customer_id);
                            // } else {
                            //     $paramUp = array('customer_id' => $customer_id);
                            // }

                            // $getCrypto = $this->am->getCryptoBuyData($paramUp, TRUE);

                            // if (!empty($getCrypto)) {

                            $add_arr = array(
                                'customer_id' => $customer_id,
                                'redeem_amount' => $redeem_amount,
                                'payment_status' => $payment_status,
                                'payment_response' => $payment_response,
                                'dtime' => dtime
                            );

                            $added = $this->am->addWalletRedeem($add_arr);

                            if ($added) {

                                //send notification starts
                                $userDetails  =   $this->am->getCustomerData(array('customer_id' => $customer_id));

                                // print_obj($userDetails);die;
                                if (!empty($userDetails) && $userDetails->fcm_token != '') {
                                    $fcm = $userDetails->fcm_token;
                                    $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                    //$fcm = 'cNf2---6Vs9';
                                    $icon = NOTIFICATION_ICON;
                                    $notification_title = 'Redeem requested successfully';
                                    $notification_body = 'Congrats! We got your redeem request. We will let you know as soon as it gets approved.';
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
                                            "customer_id" => $customer_id,
                                            "notification_title" => $notification_title,
                                            "notification_body" => $notification_body,
                                            "notification_event" => 'redeem_requested',
                                            "dtime" => dtime
                                        );

                                        $addLog = $this->am->addNotificationLog($logData);
                                    }
                                }
                                //send notification ends

                                $return['respData'] = [];
                                $return['success'] = 1;
                                $return['message'] = 'Redeem requested successfully!';
                            } else {
                                $return['respData'] = [];
                                $return['success'] = 0;
                                $return['message'] = 'Redeem request failure!';
                            }


                            // } else {
                            //     $return['respData'] = [];
                            //     $return['success'] = 0;
                            //     $return['message'] = ' data is missing!';
                            // }
                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }


    public function onCryptoTransactions()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {

                            $resp = [];
                            if (isset($crypto_id) && $crypto_id != '') {
                                $paramUp = array('crypto_pid' => $crypto_id, 'customer_id' => $customer_id);
                            } else {
                                $paramUp = array('customer_id' => $customer_id);
                            }

                            $getUser = $this->am->getCustomerData(array('customer_id' => $customer_id));

                                     if(!empty($getUser)){
                                        $user_name = $getUser->first_name . ' ' . $getUser->last_name;
                                        $email = $getUser->email;
                                        $phone = $getUser->phone;
                                     }else{
                                        $user_name = '';
                                        $email = '';
                                        $phone = '';
                                     }

                            //buy
                            $getBoughtCrypto = $this->am->getCryptoBuyData($paramUp, TRUE);

                            // print_obj($getBoughtCrypto);die;

                            if (!empty($getBoughtCrypto)) {

                                foreach ($getBoughtCrypto as $key => $value) {

                                     //  TCPDF Integration
                                     $tcpdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
                                     // Set Title
                                     $tcpdf->SetTitle('Kodecore - Cryptocurrency Invoice');
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

                                     
                                     $title = $value->crypto_pid;
                                    //  $project_amount = $value->project_amount;
                                     $subtotal = $value->subtotal;
                                     $gst_per = $value->gst_per;
                                     $gst_rate = $value->gst_rate;
                                     $tds_per = $value->tds_per;
                                     $tds_rate = $value->tds_rate;
                                     $received_amount = $value->received_amount;
                                     $quantity = $value->quantity;
                                     $dtime = $value->added_dtime;
                                     $appl_status = ($value->application_status = 0)?'Applied':'Approved';
                                     $pay_status = ($value->payment_status = 1)?'Paid':'Not Paid';
                                     $transaction_type = 'buy';

                                     $tcpdf->AddPage();
                                     
                                     $html = <<<EOD
                                     <center>
                                     <table border="0" cellpadding="5" style="border: 1px solid #ccc; padding: 3%; font-family: arial; font-size: 14px;">
                                     <tbody>
                                         <tr>
                                         <td style="text-align: center; height: 30px; font-size: 25px; font-weight: bold;" colspan="2">Receipt</td>
                                         </tr>

                                         <tr>
                                         <td style="width:20%; height: 20px;">Title:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$title</td>
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
                                         <td style="width:20%; height: 20px;">Transaction Type:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$transaction_type</td>
                                         </tr>

                                         <tr>
                                         <td style="width:20%; height: 20px;">Quantity:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$quantity</td>
                                         </tr>

                                         <tr>
                                         <td style="width:20%; height: 20px;">Payment Status:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$pay_status</td>
                                         </tr>

                                         <tr>
                                         <td style="width:20%; height: 20px;">Status:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$appl_status</td>
                                         </tr>

                                         <tr>
                                         <td style="width:20%; height: 20px;">Actual Amount:</td>
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
                                         <td style="width:20%; height: 20px;">Amount:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$received_amount</td>
                                         </tr>

                                     </tbody>
                                     </table>

                                     
                                     </center>
                                     EOD;
                                     $tcpdf->writeHTML($html);


                                     $filename = 'Crypto_inv_'.$key.'_'.date("YmdHis", time()) .'.pdf';
                                     $filepath = base_url().'uploads/invoices/'.$filename;

                                     $fullname = ABS_PATH . $filename;
                                     
                                     $tcpdf->Output($fullname, 'F');

                                    $resp_buy[] = array(
                                        'crypto_tid' => $value->crypto_buy_id,
                                        'user_id' => $value->customer_id,
                                        'crypto_id' => $value->crypto_pid,
                                        //'actual_amount' => $value->actual_amount,
                                        'received_amount' => $value->received_amount,
                                        'quantity' => $value->quantity,
                                        'application_status' => $value->application_status,
                                        'payment_status' => $value->payment_status,
                                        'payment_mode' => $value->payment_mode,
                                        'dtime' => $value->added_dtime,
                                        'trans_type' => $transaction_type,
                                        'filepath' => $filepath
                                    );
                                }
                            } else {
                                $resp_buy = [];
                            }

                            //sell

                            $getSoldCrypto = $this->am->getCryptoSellData($paramUp, TRUE);


                            if (!empty($getSoldCrypto)) {

                                foreach ($getSoldCrypto as $key => $value) {

                                     //  TCPDF Integration
                                     $tcpdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
                                     // Set Title
                                     $tcpdf->SetTitle('Kodecore - Cryptocurrency Invoice');
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

                                     
                                     $title = $value->crypto_pid;
                                    //  $project_amount = $value->project_amount;
                                     $subtotal = $value->subtotal;
                                     $gst_per = $value->gst_per;
                                     $gst_rate = $value->gst_rate;
                                     $tds_per = $value->tds_per;
                                     $tds_rate = $value->tds_rate;
                                     $received_amount = $value->received_amount;
                                     $quantity = $value->quantity;
                                     $dtime = $value->added_dtime;
                                     $appl_status = ($value->application_status = 0)?'Applied':'Approved';
                                     $pay_status = ($value->payment_status = 1)?'Paid':'Not Paid';
                                     $transaction_type = 'sell';

                                     $tcpdf->AddPage();
                                     
                                     $html = <<<EOD
                                     <center>
                                     <table border="0" cellpadding="5" style="border: 1px solid #ccc; padding: 3%; font-family: arial; font-size: 14px;">
                                     <tbody>
                                         <tr>
                                         <td style="text-align: center; height: 30px; font-size: 25px; font-weight: bold;" colspan="2">Receipt</td>
                                         </tr>

                                         <tr>
                                         <td style="width:20%; height: 20px;">Title:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$title</td>
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
                                         <td style="width:20%; height: 20px;">Transaction Type:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$transaction_type</td>
                                         </tr>

                                         <tr>
                                         <td style="width:20%; height: 20px;">Quantity:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$quantity</td>
                                         </tr>

                                         <tr>
                                         <td style="width:20%; height: 20px;">Payment Status:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$pay_status</td>
                                         </tr>

                                         <tr>
                                         <td style="width:20%; height: 20px;">Status:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$appl_status</td>
                                         </tr>
                                         
                                         <tr>
                                         <td style="width:20%; height: 20px;">Actual Amount:</td>
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
                                         <td style="width:20%; height: 20px;">Amount:</td>
                                         <td style="border-bottom: 1px solid #ccc; width: 60%; height: 20px;">$received_amount</td>
                                         </tr>

                                     </tbody>
                                     </table>

                                     </center>
                                     EOD;
                                     $tcpdf->writeHTML($html);


                                     $filename = 'Crypto_inv_'.$key.'_'.date("YmdHis", time()) .'.pdf';
                                     $filepath = base_url().'uploads/invoices/'.$filename;

                                     $fullname = ABS_PATH . $filename;
                                     
                                     $tcpdf->Output($fullname, 'F');


                                    $resp_sell[] = array(
                                        'crypto_tid' => $value->crypto_sell_id,
                                        'user_id' => $value->customer_id,
                                        'crypto_id' => $value->crypto_pid,
                                        //'actual_amount' => $value->actual_amount,
                                        'received_amount' => $value->received_amount,
                                        'quantity' => $value->quantity,
                                        'application_status' => $value->application_status,
                                        'payment_status' => $value->payment_status,
                                        'payment_mode' => $value->payment_mode,
                                        'dtime' => $value->added_dtime,
                                        'trans_type' => $transaction_type,
                                        'filepath' => $filepath
                                    );
                                }
                            } else {
                                $resp_sell = [];
                            }


                            if (!empty($resp_buy)) {
                                $resp = array_merge($resp, $resp_buy);
                            }

                            if (!empty($resp_sell)) {
                                $resp = array_merge($resp, $resp_sell);
                            }

                            //print_obj($resp);die;
                            if (!empty($resp)) {
                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Cryptocurrency detals found!';
                            } else {
                                $return['respData'] = [];
                                $return['success'] = 0;
                                $return['message'] = 'Cryptocurrency not bought!';
                            }
                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    public function onGetPrivacyTnC()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        // if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                        //     $if_not_blank = 1; //not blank
                        //     $customer_id = xss_clean($decodedParam->user_id);
                        //     $redeem_amount = xss_clean($decodedParam->amount);
                        // } else {
                        //     $if_not_blank = 0;
                        //     $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        // }
                        //validation ends

                        //check if valid
                        // if ($if_not_blank == 1) {


                        $getPrivacyTnC = $this->am->getAppDetailsData(array('id' => 1));
                        //print_obj($getPrivacyTnC);die;

                        if (!empty($getPrivacyTnC)) {
                            $resp = array(
                                'terms' => $getPrivacyTnC->terms_cond,
                                'privacy' => $getPrivacyTnC->privacy,
                                'gst' => $getPrivacyTnC->gst,
                                'tds' => $getPrivacyTnC->tds,
                                'royalty' => $getPrivacyTnC->royalty
                            );
                            $return['respData'] = $resp;
                            $return['success'] = 1;
                            $return['message'] = 'Data found!';
                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = 'Data not found!';
                        }

                        // } else {
                        //     $return['respData'] = [];
                        //     $return['success'] = 0;
                        //     $return['message'] = $out_message;
                        // }


                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }


    public function onGetNotificationLogs()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {

                            $paramUp = array('customer_id' => $customer_id);

                            $getLogs = $this->am->getNotificationLogData($paramUp, TRUE);

                            if (!empty($getLogs)) {

                                foreach ($getLogs as $key => $value) {
                                    $resp[] = array(
                                        'notification_id' => $value->nlog_id,
                                        'user_id' => $value->customer_id,
                                        'notification_event' => $value->notification_event,
                                        'notification_title' => $value->notification_title,
                                        'notification_body' => $value->notification_body,
                                        'dtime' => $value->dtime
                                    );
                                }

                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Notifications found!';
                            } else {
                                $return['respData'] = [];
                                $return['success'] = 0;
                                $return['message'] = 'Notifications not found!';
                            }


                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }

    
    public function onCustomerWithdrawalRequest()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '' && isset($decodedParam->amount) && $decodedParam->amount != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                            $amount = xss_clean($decodedParam->amount);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {

                            $userDetails  =   $this->am->getCustomerData(array('customer_id' => $customer_id));

                            if (!empty($userDetails)) {

                                if($userDetails->wallet_amount >= $amount){
                                    $add_arr = array(
                                        'customer_id' => $customer_id,
                                        'amount' => $amount,
                                        'dtime' => dtime
                                    );
        
                                    $added = $this->am->addWalletWithdrawal($add_arr);
    
                                    //send notification starts
    
                                    // print_obj($userDetails);die;
                                    if ($userDetails->fcm_token != '') {
                                        $fcm = $userDetails->fcm_token;
                                        $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                        //$fcm = 'cNf2---6Vs9';
                                        $icon = NOTIFICATION_ICON;
                                        $notification_title = 'Withdrawal requested successfully';
                                        $notification_body = 'Congrats! We got your Withdrawal request. We will let you know as soon as it gets approved.';
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
                                                "notification_event" => 'withdrawal_requested',
                                                "dtime" => dtime
                                            );
    
                                            $addLog = $this->am->addNotificationLog($logData);
                                        }
                                    }
                                    //send notification ends
    
                                    //email starts
                                    if (TRUE){
    
                                        $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                                        $subject = 'Withdrawal request';
                                        $body = '';
                                        $body .= '<p>Hello, </p>';
                                        $body .= '<p>You have got a withdrawal request from <b>' . $name . '</b>.</p>';
                                        $body .= '<p><b>Amount : </b>' . $amount . '</p>';
                                        $body .= '<p>Login to admin panel for more details.</p>';
                                        $body .= '<br><p>** This is a system generated email. Please do not reply to this email..</p>';
    
                                        $this->email->set_newline("\r\n");
                                        $this->email->set_mailtype("html");
                                        $this->email->from(FROM_EMAIL, 'Kode Core');
                                        $this->email->to(ADMIN_EMAIL);
                                        //$this->email->reply_to($replyemail);
                                        $this->email->subject($subject);
                                        $this->email->message($body);
                                        if ($this->email->send()) {
                                            $return['mail_status'] = 'E-mail sent';
                                        } else {
                                            // echo $this->email->print_debugger();die;
                                            $return['mail_status'] = 'E-mail not sent!';
                                        }
                                    }
                                    //email ends
                                     
    
                                    $return['respData'] = [];
                                    $return['success'] = 1;
                                    $return['message'] = 'Withdrawal requested successfully!';
                                }else{
                                    $return['respData'] = [];
                                    $return['success'] = 0;
                                    $return['message'] = 'Wallet amount is less than requested amount!';
                                }

                            } else {
                                $return['respData'] = [];
                                $return['success'] = 0;
                                $return['message'] = 'Withdrawal request failure!';
                            }

                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }


    public function onGetPaymentKeys()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {


                            if (KEY_ID !== NULL && KEY_SECRET !== NULL) {
                                $resp = array(
                                    'Key_Id' => KEY_ID,
                                    'Key_Secret' => KEY_SECRET
                                );

                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Details found!';
                            } else {
                                $return['respData'] = [];
                                $return['success'] = 0;
                                $return['message'] = 'Details not found!';
                            }

                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }


    public function onGetPayoutData()
    {
        $jsonData = $this->input->post('oAuth_json');
        $json_Param = $this->input->post('jsonParam');
        //print_obj($this->input->post());

        if (!empty($jsonData)) {
            $decoded = json_decode($jsonData);
            $decodedParam = json_decode($json_Param);
            //print_obj($decodedParam);

            if ($decoded != '' || $decoded != NULL) {
                $sKey = $decoded->sKey;
                $aKey = $decoded->aKey;
                $out_message = array();

                if ($sKey == secretKey && $aKey == accessKey) {

                    if (!empty($decodedParam)) {

                        //validation starts
                        if (isset($decodedParam->user_id) && $decodedParam->user_id != '') {

                            $if_not_blank = 1; //not blank
                            $customer_id = xss_clean($decodedParam->user_id);
                        } else {
                            $if_not_blank = 0;
                            $out_message += array('valid_fields' => 'Missing Required Fields!!');
                        }
                        //validation ends

                        //check if valid
                        if ($if_not_blank == 1) {

                            $paramUp = array('it_projects_payout.customer_id' => $customer_id);

                            $payoutdata = $this->am->getITProjectPayoutUserData($paramUp, TRUE);

                            if (!empty($payoutdata)) {
                                    foreach ($payoutdata as $key => $value) {
                                        $resp[] = array(
                                            'dtime'  => $value->added_dtime,
                                            'payoutid'  => $value->payout_id,
                                            'customer_id'  => $value->customer_id,
                                            'user_fullname'  => $value->first_name . ' ' . $value->last_name,
                                            'remarks'  => $value->remarks,
                                            'amount'  => $value->subtotal_amt,
                                            'user_email'  => $value->email,
                                            'user_phone'  => $value->phone,
                                            'proj_title'  => $value->proj_title,
                                            'filepath' => 'https://kodecore.com/app/uploads/invoices/IT_Project_0_20211126065459.pdf'
                                        );
                                    }

                                    //print_obj($resp);die;


                                $return['respData'] = $resp;
                                $return['success'] = 1;
                                $return['message'] = 'Payout found!';
                            } else {
                                $return['respData'] = [];
                                $return['success'] = 0;
                                $return['message'] = 'Payout not found!';
                            }


                        } else {
                            $return['respData'] = [];
                            $return['success'] = 0;
                            $return['message'] = $out_message;
                        }
                    } else {
                        $return['respData'] = [];
                        $return['success'] = 0;
                        $return['message'] = 'JSON data payload is empty!';
                    }
                } else {
                    $return['success'] = '0';
                    $return['message'] = 'Security credentials mismatch!';
                }
            } else {
                $return['success'] = '0';
                $return['message'] = 'JSON data error';
            }
        } else {
            $return['success'] = '0';
            $return['message'] = 'JSON Auth payload is empty!';
        }

        header('Content-Type: application/json');
        echo json_encode($return);
    }
    
}
