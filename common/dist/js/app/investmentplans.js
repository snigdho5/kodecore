$(document).ready(function () {
    $(document).on('click', '.del_invplan', function () {

        var planid = $(this).attr('data-planid');
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

                    url: BASE_URL + 'delinvplan',

                    data: { planid: planid },

                    success: function (d) {

                        if (d.deleted == 'success') {

                            Swal.fire({
                                icon: 'success',
                                title: 'Investment Plan deleted!',
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33',
                                allowOutsideClick: false,
                            });
                            window.location.reload();

                        }
                        else if (d.deleted == 'not_exists') {

                            Swal.fire({
                                icon: 'error',
                                title: 'Investment Plan not exists!',
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
                url: BASE_URL + 'duplicate_check_ip',
                data: {
                    title: title
                },

                success: function (d) {
                    if (d.if_exists == 1) {
                        $('#chk_title').show();
                        $('#chk_title').html('<i class="icofont-close-squared-alt"></i> Plan Name already exists..!!');
                        $("#chk_title").css("color", "red");
                        $('.proj_btn_submit').attr("disabled", true);
                        return false;
                    } else {
                        $('#chk_title').show();
                        $('#chk_title').html('<i class="icofont-tick-boxed"></i> Plan Name available.');
                        $("#chk_title").css("color", "green");
                        $('.proj_btn_submit').attr("disabled", false);
                    }
                }
            });
        } else {
            $('#chk_title').hide();
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

    $("#create_plan_form").submit(function (e) {

        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: BASE_URL + 'createinvplan',
            cache: false,
            data: $('#create_plan_form').serialize(),
            beforeSend: function () {
                $('#btn_submit').html('Creating..').prop('disabled', true);
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
                    $('#btn_submit').html('Submit').prop('disabled', false);

                } else if (d.added == 'success') {

                    Swal.fire({
                        icon: 'success',
                        title: 'Investment Plan added!',
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
                        title: 'Investment Plan already exists!',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#d33',
                        allowOutsideClick: false,
                    });
                    $('#btn_submit').html('Submit').prop('disabled', false);

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong!',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#d33',
                        allowOutsideClick: false,
                    });
                    $('#btn_submit').html('Submit').prop('disabled', false);
                }
            }
        });

    });


     $("#create_payout_form").submit(function (e) {

        e.preventDefault();
        
            $.ajax({
                type: 'POST',
                url: BASE_URL + 'givemonthlypayout-inv-plans',
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

    $(document).on('change', '#customer_id', function () {
        var customer_id = $('#customer_id').val();
        $('#plan_id').html('<option value="">Select</option>');
        
        if (customer_id != '') {
            $.ajax({
                type: "POST",
                url: BASE_URL + 'get-inv-plans-by-user',
                data: {
                    customer_id: customer_id
                },

                success: function (d) {
                    if (d.status == 1) {
                        $('#plan_id').html(d.inv_data);
                    }else {
                        $('#plan_id').html('<option value="">Select</option>');
                    }
                }
            });
        } else {
            // $('#plan_id').html('<option value="">Select</option>');
        }

    });

});