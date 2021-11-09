$(document).ready(function () {
    $(document).on('click', '.del_cust', function () {

        var cust_id = $(this).attr('data-custid');
        var fullname = $(this).attr('data-fullname');


        Swal.fire({
            title: "Are you sure?",
            text: fullname + " will be deleted parmanently!",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((willDelete) => {
            if (willDelete.isConfirmed == true) {
                $.ajax({

                    type: 'POST',

                    url: BASE_URL + 'delcustomer',

                    data: { custid: cust_id },

                    success: function (d) {

                        if (d.deleted == 'success') {

                            Swal.fire({
                                icon: 'success',
                                title: 'Customer deleted!',
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33',
                                allowOutsideClick: false,
                            });
                            window.location.reload();

                        }
                        else if (d.deleted == 'not_exists') {

                            Swal.fire({
                                icon: 'error',
                                title: 'Customer not exists!',
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33',
                                allowOutsideClick: false,
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Something went wrong!',
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33',
                                allowOutsideClick: false,
                            });
                        }

                    }

                });
            } else {
                //Swal.fire("Okay!");
            }
        });

    });

    $(document).on('keyup', '#user_name', function () {
        var username = $('#user_name').val();
        if (username != '') {
            $.ajax({
                type: "POST",
                url: BASE_URL + 'duplicate_check_cust',
                data: {
                    dup_type: 'username',
                    user_name: username
                },

                success: function (d) {
                    if (d.if_exists == 1) {
                        $('#chk_username').show();
                        $('#chk_username').html('<i class="icofont-close-squared-alt"></i> Username already exists..!!');
                        $("#chk_username").css("color", "red");
                        $('#username_val').val('1');
                        return false;
                    } else {
                        $('#chk_username').show();
                        $('#chk_username').html('<i class="icofont-tick-boxed"></i> Username available.');
                        $("#chk_username").css("color", "green");
                        $('#username_val').val('0');
                    }
                }
            });

            // if ($('#username_val').val() == '0' && $('#email_val').val() == '0' && $('#phone_val').val() == '0') {
            //     console.log('if'+$('#username_val').val() + $('#email_val').val() + $('#phone_val').val());
            //     $('.customer_btn_submit').attr("disabled", false);
            // } else {
            //     console.log('else'+$('#username_val').val() + $('#email_val').val() + $('#phone_val').val());
            //     $('.customer_btn_submit').attr("disabled", true);
            // }
        } else {
            $('#chk_username').hide();
        }

    });

    $(document).on('keyup', '#email', function () {
        var email = $('#email').val();
        if (email != '') {
            $.ajax({
                type: "POST",
                url: BASE_URL + 'duplicate_check_cust',
                data: {
                    dup_type: 'email',
                    email: email
                },

                success: function (d) {
                    if (d.if_exists == 1) {
                        $('#chk_email').show();
                        $('#chk_email').html('<i class="icofont-close-squared-alt"></i> ' + d.out_message);
                        $("#chk_email").css("color", "red");
                        $('#email_val').val('1');
                        return false;
                    } else {
                        $('#chk_email').show();
                        $('#chk_email').html('<i class="icofont-tick-boxed"></i> ' + d.out_message);
                        $("#chk_email").css("color", "green");
                        $('#email_val').val('0');
                    }
                }
            });

        } else {
            $('#chk_username').hide();
        }

    });

    $(document).on('keyup', '#phone', function () {
        var phone = $('#phone').val();
        if (phone != '') {
            $.ajax({
                type: "POST",
                url: BASE_URL + 'duplicate_check_cust',
                data: {
                    dup_type: 'phone',
                    phone: phone
                },

                success: function (d) {
                    if (d.if_exists == 1) {
                        $('#chk_phone').show();
                        $('#chk_phone').html('<i class="icofont-close-squared-alt"></i> ' + d.out_message);
                        $("#chk_phone").css("color", "red");
                        $('#phone_val').val('1');
                        return false;
                    } else {
                        $('#chk_phone').show();
                        $('#chk_phone').html('<i class="icofont-tick-boxed"></i> ' + d.out_message);
                        $("#chk_phone").css("color", "green");
                        $('#phone_val').val('0');
                    }
                }
            });

        } else {
            $('#chk_phone').hide();
        }

    });

    $('#create_customer_form2').validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            user_name: {
                required: true,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 16,
                pwcheck: true
            }
        },
        messages: {
            first_name: {
                required: 'Please enter your full name',
            },
            last_name: {
                required: 'Please enter your full name',
            },
            user_name: {
                required: 'Please enter your username',
            },
            password: {
                required: 'Please enter your password',
                minlength: 'Minimum 8 characters required',
                maxlength: 'Maximum 16 characters allowed'
            }
        },
        errorPlacement: function (error, element) {
            error.insertBefore(element);
        },
        submitHandler: function (f) {
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'createuser',
                cache: false,
                data: $('#create_customer_form').serialize(),
                beforeSend: function () {
                    $('#customer_btn_submit').html('Creating..').prop('disabled', true);
                },
                success: function (d) {
                    if (d.added == 'rule_error') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: d.errors,
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        $('#customer_btn_submit').html('Submit').prop('disabled', false);

                    } else if (d.added == 'success') {

                        Swal.fire({
                            icon: 'success',
                            title: 'Customer added!',
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        window.location.reload();

                    } else if (d.added == 'already_exists') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Customer already exists!',
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        $('#customer_btn_submit').html('Submit').prop('disabled', false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong!',
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        $('#customer_btn_submit').html('Submit').prop('disabled', false);
                    }
                }
            });
        }
    });

    $("#create_customer_form").submit(function (e) {

        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'createcustomer',
            cache: false,
            data: $('#create_customer_form').serialize(),
            beforeSend: function () {
                $('#customer_btn_submit').html('Creating..').prop('disabled', true);
            },
            success: function (d) {
                if (d.added == "rule_error") {

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: d.errors,
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#d33',
                        allowOutsideClick: false,
                    });
                    $('#customer_btn_submit').html('Submit').prop('disabled', false);

                } else if (d.added == 'success') {

                    Swal.fire({
                        icon: 'success',
                        title: 'Customer added!',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#d33',
                        allowOutsideClick: false,
                    });

                    setTimeout(function () {
                        window.location.reload();
                    }, 100);

                } else if (d.added == 'already_exists') {

                    Swal.fire({
                        icon: 'error',
                        title: 'Customer already exists!',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#d33',
                        allowOutsideClick: false,
                    });
                    $('#customer_btn_submit').html('Submit').prop('disabled', false);

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong!',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#d33',
                        allowOutsideClick: false,
                    });
                    $('#customer_btn_submit').html('Submit').prop('disabled', false);
                }
            }
        });

    });

    $(document).on('click', '.generate_pass', function makeid() {
        var text = "";

        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 10; i++)

            text += possible.charAt(Math.floor(Math.random() * possible.length));

        $('#password').val(text);

    });

    
    $(document).on('click', '.view-kyc', function () {
        var custid = $(this).attr('data-custid');
        $('#exampleModal').modal('hide');
        var html = '';
        $('.kyc-details').html(html);
        $('.ack-msg').html('');

        if (custid != '') {
            $.ajax({
                type: "POST",
                url: BASE_URL + 'customer-kyc-details',
                data: {
                    custid: custid
                },

                success: function (d) {
                    if (d.success == 1) {
                        user_data = d.user_data;
                        // $('#exampleModal').trigger('focus');
                        $('#exampleModal').modal('toggle');
                        $('#exampleModal').modal('show');
                        html += '<p><span><b>Name:</b> ' + user_data['first_name'] + ' ' + user_data['last_name'];
                        html += ' <span><b>Bank Name:</b> ' + user_data['bank_name'] + '</p>';
                        html += '<p><span><b>Branch Name:</b> ' + user_data['branch_name'];
                        html += ' <span><b>A/c no:</b> ' + user_data['ac_no'] + '</p>';
                        html += '<p><span><b>IFSC:</b> ' + user_data['ifsc'];
                        html += ' <span><b>A/c Name:</b> ' + user_data['ac_name'] + '</p>';
                        html += '<p><span><b>PAN No:</b> ' + user_data['pan_no'];
                        html += (user_data['kyc_pan_file'] != '')?' <span><b>PAN File:</b> <a href="' + user_data['kyc_pan_file'] + '" target="_blank">View</a></p>':' <span><b>PAN File:</b> N/A</p>';
                        html += '<p><span><b>Aadhaar No:</b> ' + user_data['aadhaar_no'];
                        html += (user_data['kyc_aadhaar_file'] != '')?' <span><b>Aadhaar File:</b> <a href="' + user_data['kyc_aadhaar_file'] + '" target="_blank">View</a></p>':' <span><b>Aadhaar File:</b> N/A</p>';

                        
                        $('.kyc-details').html(html);
                        $('#set_custid').val(user_data['custid']);
                        return false;
                    } else {
                         $('#exampleModal').modal('hide');
                    }
                }
            });

        } else {
            //
        }

    });

    
    $(document).on('click', '.btn-ack-kyc', function () {
        var ack = $(this).attr('data-ack');
        var custid = $('#set_custid').val();

        if (ack != '') {
            $.ajax({
                type: "POST",
                url: BASE_URL + 'ack-customer-kyc',
                data: {
                    ack: ack,
                    custid: custid
                },

                success: function (d) {
                    if (d.success == 1) {

                        $('.ack-msg').html(' <b style="color:green;"><i class="icofont-tick-boxed"></i> Success: </b>' + d.msg);
                        return false;
                    } else {
                        $('.ack-msg').html(' <b style="color:red;"><i class="icofont-not-allowed"></i> Failure: </b>' + d.msg);
                    }
                }
            });

        } else {
            //
        }

    });
});