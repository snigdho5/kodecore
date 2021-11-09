$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();

    $(document).on('click', '#to-recover', function () {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });

    $(document).on('click', '#to-login', function () {
        $("#recoverform").hide();
        $("#loginform").fadeIn();
    });

    $("form[id='login-form']").submit(function (e) {

        var formData = new FormData($(this)[0]);

        $('.btn-login').html('Checking..').prop('disabled', true);
        $.ajax({
            url: BASE_URL + "chk_login",
            type: "POST",
            data: formData,
            success: function (d) {
                if (d.checkedlogin == 'rule_error') {

                    $('#chk_msg2').show();
                    $('#chk_msg2').html('<b style="color:#f5f2f0;"><i class="icofont-warning" style="color:yellow;"></i> ERROR: ' + d.errors + '</b>');
                    $('.btn-login').html('Submit').prop('disabled', false);

                } else if (d.checkedlogin == 'mismatch_error') {

                    $('#chk_msg2').show();
                    $('#chk_msg2').html('<b style="color:#f5f2f0;"><i class="icofont-warning" style="color:yellow"></i> ERROR: Mismatch in Username / Password!</b>');
                    $('.btn-login').html('Submit').prop('disabled', false);

                } else if (d.checkedlogin == 'upd_error') {

                    $('#chk_msg2').show();
                    $('#chk_msg2').html('<b style="color:#f5f2f0;"><i class="icofont-warning" style="color:yellow"></i> ERROR: Something went wrong!</b>');

                    $('.btn-login').html('Submit').prop('disabled', false);
                } else if (d.checkedlogin == 'success') {

                    window.location.href = BASE_URL + 'dashboard';

                } else if (d.checkedlogin == 'success2fa') {

                    $("#2faform").show();
                    $("#loginform").slideUp();
                    $("#2faform").fadeIn();

                } else {
                    Swal.fire('Something went wrong!');
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });

        e.preventDefault();
    });

    $("form[id='recover-form']").submit(function (e) {

        var formData = new FormData($(this)[0]);
        $('.btn-recover').html('Checking..').prop('disabled', true);

        $.ajax({
            url: BASE_URL + "send-password-recovery",
            type: "POST",
            data: formData,
            success: function (d) {
                if (d.status == 'rule_error' || d.status == 'not_found' || d.status == 'upd_error' || d.status == 'email_error') {

                    $('#chk_msg_rec').show();
                    $('#chk_msg_rec').html('<b style="color:#f5f2f0;"><i class="icofont-warning" style="color:yellow;"></i> ERROR: ' + d.errors + '</b>');

                    $('.btn-recover').html('Submit').prop('disabled', false);
                } else if (d.status == 'success') {
                    $('#chk_msg_rec').show();
                    $('#chk_msg_rec').html('<b style="color:#f5f2f0;"><i class="icofont-tick-mark" style="color:green;"></i> Success: ' + d.msg + '</b>');
                    $('.email-div').hide();
                    $('.btn-recover').hide();

                    //window.location.href = BASE_URL + 'recover-password';
                } else {
                    Swal.fire('Something went wrong!');
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });

        e.preventDefault();
    });

    $("form[id='2fa-form']").submit(function (e) {

        var formData = new FormData($(this)[0]);

        $.ajax({
            url: BASE_URL + "chklogin2fa",
            type: "POST",
            data: formData,
            success: function (d) {
                if (d.twofa == 'rule_error' || d.twofa == 'failure') {

                    $('#chk_msg3').show();
                    $('#chk_msg3').html('<b style="color:#f5f2f0;"><i class="icofont-warning" style="color:yellow;"></i> ERROR: ' + d.errors + '</b>');

                } else if (d.twofa == 'success') {
                    window.location.href = BASE_URL + 'dashboard';
                } else {
                    Swal.fire('Something went wrong!');
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });

        e.preventDefault();
    });
});