$(document).ready(function () {
    $(document).on('click', '.del_proj', function () {

        var projid = $(this).attr('data-projid');
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

                    url: BASE_URL + 'delproject',

                    data: { projid: projid },

                    success: function (d) {

                        if (d.deleted == 'success') {

                            Swal.fire({
                                icon: 'success',
                                title: 'Project deleted!',
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33',
                                allowOutsideClick: false,
                            });
                            window.location.reload();

                        }
                        else if (d.deleted == 'not_exists') {

                            Swal.fire({
                                icon: 'error',
                                title: 'Project not exists!',
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

    $(document).on('keyup', '#title', function () {
        var title = $('#title').val();
        if (title != '') {
            $.ajax({
                type: "POST",
                url: BASE_URL + 'duplicate_check_project',
                data: {
                    title: title
                },

                success: function (d) {
                    if (d.if_exists == 1) {
                        $('#chk_title').show();
                        $('#chk_title').html('<i class="icofont-close-squared-alt"></i> Title already exists..!!');
                        $("#chk_title").css("color", "red");
                        $('.proj_btn_submit').attr("disabled", true);
                        return false;
                    } else if (d.if_exists == 3) {
                        $('#chk_title').show();
                        $('#chk_title').html('<i class="icofont-close-squared-alt"></i> You can&apos;t use your current Title!');
                        $("#chk_title").css("color", "red");
                        $('.proj_btn_submit').attr("disabled", true);
                    } else {
                        $('#chk_title').show();
                        $('#chk_title').html('<i class="icofont-tick-boxed"></i> Title available.');
                        $("#chk_title").css("color", "green");
                        $('.proj_btn_submit').attr("disabled", false);
                    }
                }
            });
        } else {
            $('#chk_title').hide();
        }

    });

    
    $(document).on('change', '#payment_breakup', function () {
        var payment_breakup = $(this).val();
        $('.enter-brakup').hide();

        if (payment_breakup != '' && payment_breakup == 2) {
            $('.enter-brakup').show();
        } else {
            $('#enter_breakup').val('');
            $('#enter_breakup2').val('');
            $('#first_installment_amt').val('');
            $('#last_installment_amt').val('');
            $('.enter-brakup').hide();
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

    $("#create_proj_form").submit(function (e) {

        e.preventDefault();

        
        var duration = $('#duration').val();

        if(duration >= 1 && duration <= 12){
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'createproject',
                cache: false,
                data: $('#create_proj_form').serialize(),
                beforeSend: function () {
                    $('#proj_btn_submit').html('Creating..').prop('disabled', true);
                },
                success: function (d) {
                    if (d.added == "rule_error") {

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: d.errors,
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        $('#proj_btn_submit').html('Submit').prop('disabled', false);

                    } else if (d.added == 'success') {

                        Swal.fire({
                            icon: 'success',
                            title: 'Project added',
                            text: d.msg,
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
                            title: 'Error!',
                            title: d.msg,
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        $('#proj_btn_submit').html('Submit').prop('disabled', false);

                    } else if (d.added == 'breakup_blank') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: d.msg,
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        $('#proj_btn_submit').html('Submit').prop('disabled', false);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: d.msg,
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        $('#proj_btn_submit').html('Submit').prop('disabled', false);
                    }
                }
            });
        }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Duration cannot be grater than 12 and lesser than 1! You entered: ' + duration,
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#d33',
                    allowOutsideClick: false,
                });
        }

       

    });

    var max_chars = 2;

    $(document).on('keydown', '#enter_breakup', function () {
        var per = $(this).val();
        var amount = $('#amount').val();
        var ins1 = ((amount * per)/100);
        var subt = (100 - per);
        var ins2 = ((amount * subt)/100);
        if (per.length >= max_chars) { 
            $(this).val(per.substr(0, max_chars));
        }

        $('#enter_breakup2').val(subt);
        $('#first_installment_amt').val(ins1);
        $('#last_installment_amt').val(ins2);
        
    });

    $(document).on('keyup', '#enter_breakup', function () {
       var per = $(this).val();
        var amount = $('#amount').val();
        var ins1 = ((amount * per)/100);
        var subt = (100 - per);
        var ins2 = ((amount * subt)/100);
        if (per.length >= max_chars) { 
            $(this).val(per.substr(0, max_chars));
        }

        $('#enter_breakup2').val(subt);
        $('#first_installment_amt').val(ins1);
        $('#last_installment_amt').val(ins2);
        
    });

    
    $(document).on('keydown', '#amount', function () {
        $('#enter_breakup').val('');
        $('#enter_breakup2').val('');
        $('#first_installment_amt').val('');
        $('#last_installment_amt').val('');
    });

        
    $(document).on('keyup', '#amount', function () {
        $('#enter_breakup').val('');
        $('#enter_breakup2').val('');
        $('#first_installment_amt').val('');
        $('#last_installment_amt').val('');
    });

    $("#create_payout_form").submit(function (e) {

        e.preventDefault();
        
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'givemonthlypayout',
                cache: false,
                data: $('#create_payout_form').serialize(),
                beforeSend: function () {
                    $('#payout_btn_submit').html('Creating..').prop('disabled', true);
                },
                success: function (d) {
                    if (d.added == "rule_error") {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: d.errors,
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        $('#payout_btn_submit').html('Submit').prop('disabled', false);

                    } else if (d.added == 'success') {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: d.msg,
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });

                        setTimeout(function () {
                            window.location.reload();
                        }, 100);

                    }  else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: d.msg,
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33',
                            allowOutsideClick: false,
                        });
                        $('#payout_btn_submit').html('Submit').prop('disabled', false);
                    }
                }
            });
       

       

    });

    $(document).on('keyup', '#customer_id', function () {
        var customer_id = $('#customer_id').val();
        if (customer_id != '') {
            $.ajax({
                type: "POST",
                url: BASE_URL + 'duplicate_check_project',
                data: {
                    customer_id: customer_id
                },

                success: function (d) {
                    if (d.if_exists == 1) {
                        $('#chk_title').show();
                        $('#chk_title').html('<i class="icofont-close-squared-alt"></i> Title already exists..!!');
                        $("#chk_title").css("color", "red");
                        $('.proj_btn_submit').attr("disabled", true);
                    }else {
                        $('#chk_title').show();
                        $('#chk_title').html('<i class="icofont-tick-boxed"></i> Title available.');
                        $("#chk_title").css("color", "green");
                        $('.proj_btn_submit').attr("disabled", false);
                    }
                }
            });
        } else {
            //$('#chk_title').hide();
        }

    });


});