$(document).ready(function () {
    $(document).on('click', '.apprv-buy-req', function () {

        var id = $(this).attr('data-id');
        var fullname = $(this).attr('data-fullname');
        var amount = $(this).attr('data-amount');
        var apprvtype = $(this).attr('data-apprv-type');

        if(apprvtype == '1'){
            var cnf_msg = "Are you sure to APPROVE Buy request of Rs. " + amount + " for " + fullname + "?";
        }else{
            var cnf_msg = "Are you sure to REJECT Buy request of Rs. " + amount + " for " + fullname + "?";
        }


        Swal.fire({
            title: "Are you sure?",
            text: cnf_msg,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((willDelete) => {
            if (willDelete.isConfirmed == true) {
                $.ajax({

                    type: 'POST',

                    url: BASE_URL + 'approve-buy-req',

                    data: { id: id, apprvtype: apprvtype },

                    success: function (d) {

                        if (d.resp == 'success') {

                            Swal.fire({
                                icon: 'success',
                                title: d.msg,
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33',
                                allowOutsideClick: false,
                            });
                            window.location.reload();

                        }
                        else if (d.resp == 'not_exists') {

                            Swal.fire({
                                icon: 'error',
                                title: d.msg,
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33',
                                allowOutsideClick: false,
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: d.msg,
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


    $(document).on('click', '.apprv-sell-req', function () {

        var id = $(this).attr('data-id');
        var fullname = $(this).attr('data-fullname');
        var amount = $(this).attr('data-amount');
        var apprvtype = $(this).attr('data-apprv-type');

        if(apprvtype == '1'){
            var cnf_msg = "Are you sure to APPROVE Sell request of Rs. " + amount + " for " + fullname + "?";
        }else{
            var cnf_msg = "Are you sure to REJECT Sell request of Rs. " + amount + " for " + fullname + "?";
        }


        Swal.fire({
            title: "Are you sure?",
            text: cnf_msg,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((willDelete) => {
            if (willDelete.isConfirmed == true) {
                $.ajax({

                    type: 'POST',

                    url: BASE_URL + 'approve-sell-req',

                    data: { id: id, apprvtype: apprvtype },

                    success: function (d) {

                        if (d.resp == 'success') {

                            Swal.fire({
                                icon: 'success',
                                title: d.msg,
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33',
                                allowOutsideClick: false,
                            });
                            window.location.reload();

                        }
                        else if (d.resp == 'not_exists') {

                            Swal.fire({
                                icon: 'error',
                                title: d.msg,
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33',
                                allowOutsideClick: false,
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: d.msg,
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

});