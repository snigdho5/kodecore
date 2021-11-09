<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <?php $this->load->view('top_css'); ?>
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'common/assets/extra-libs/multicheck/multicheck.css'; ?>">
    <link href="<?php echo base_url() . 'common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css'; ?>" rel="stylesheet">
    <title><?php echo comp_name; ?> | <?php echo $page_title; ?></title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <?php $this->load->view('header_main'); ?>
        <!-- End Topbar header -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <?php $this->load->view('sidebar_main'); ?>
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title"><?php echo $page_title; ?></h4>
                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $page_title; ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <?php
                                if($user_data['twofa_enabled'] != 1){
                            ?>
                            <form class="form-horizontal" id="enable2fa_form">
                                <?php //print_obj($user_data);die; 
                                ?>
                                <div class="card-body">
                                    <h4 class="card-title">Enable Google Authenticator</h4>
                                    <p id="chk_msg2" style="display: none;"></p>

                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 text-right control-label col-form-label">
                                            <span>Step 1:</span>
                                            <i class="icofont-android-nexus"></i>Open Google Authenticator App (<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank"><i class="icofont-brand-android-robot"></i></a> | <a href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank"><i class="icofont-brand-apple"></i></a>) and scan this QR code to register<br>
                                            <img src="https://play-lh.googleusercontent.com/HPc5gptPzRw3wFhJE1ZCnTqlvEvuVFBAsV9etfouOhdRbkp-zNtYTzKUmUVPERSZ_lAL" width="50" title="Google Authenticator"/>
                                        </label>
                                        <div class="col-sm-7">
                                            <h5 class="card-subtitle"><img src="<?php echo $qr_url; ?>" title="Scan this using any authenticator app"/></h5>
                                            <p> Can't Scan? Use <a href="javascript:void(0);" class="click-setupkey">Enter A Setup Key</a> option.</p>
                                            <p class="show-qrcode" style="display: none;">Copy this code into the Authenticator app: <?php echo $gauth_secret; ?></p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-sm-3 text-right control-label col-form-label"><span>Step 2:</span> Enter Passcode</label>
                                        <div class="col-sm-7">
                                            <input type="hidden" class="form-control" id="tqrcode" name="tqrcode" value="<?php echo $gauth_secret; ?>">
                                            <input type="number" class="form-control" id="tpasscode" name="tpasscode" placeholder="Enter the 2FA code to register.." required="">
                                        </div>
                                    </div>

                                </div>
                                <div class="border-top">
                                    <div class="card-body">
                                        <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>

                            <?php
                                }
                                else{
                            ?>

                                <div class="card-body">
                                    <h4 class="card-title">Enable Google Authenticator</h4>
                                    <p><i class="icofont-tick-mark" style="color:green;"></i> Already Enabled!</p>
                                </div>
                                
                            <?php
                                }
                            ?>


                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <?php $this->load->view('footer'); ?>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <?php $this->load->view('bottom_js'); ?>
    <!-- this page js -->
    <script src="<?php echo base_url() . 'common/assets/extra-libs/multicheck/datatable-checkbox-init.js'; ?>"></script>
    <script src="<?php echo base_url() . 'common/assets/extra-libs/multicheck/jquery.multicheck.js'; ?>"></script>
    <script src="<?php echo base_url() . 'common/assets/extra-libs/DataTables/datatables.min.js'; ?>"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.click-setupkey', function() {
                $(".show-qrcode").show();
            });

            $("form[id='enable2fa_form']").submit(function(e) {
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: "<?php echo base_url(); ?>set2fa",
                    type: "POST",
                    data: formData,
                    success:function(d){

                        if(d.twofa == 'rule_error'){

                            $('#chk_msg2').show();
                            $('#chk_msg2').html('<i class="icofont-close-squared-alt"></i> Error: '+d.errors);
                            $("#chk_msg2").css("color", "red");
                            //$('#submit').attr("disabled", true);

                        }else if(d.twofa=='success'){

                            alert('<?php echo $page_title; ?> Enabled!');
                            window.location.reload();

                        }
                        else if(d.twofa=='failure'){

                            $('#chk_msg2').show();
                            $('#chk_msg2').html('<i class="icofont-close-squared-alt"></i> Error: '+d.errors);
                            $("#chk_msg2").css("color", "red");
                            //$('#submit').attr("disabled", true);

                        }else{
                            alert('Something went wrong!');
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

                e.preventDefault();
                }); 



        });
    </script>

</body>

</html>