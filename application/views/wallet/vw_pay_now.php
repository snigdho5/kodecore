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
							<div class="card-body">
								<h5 class="card-title"><?php echo $page_title; ?> </h5>

                                <p class="pl-25 pr-25" id="pay_msg" style="display: none;"></p>
                            
                                <p class="hide_this">
                                    <b>Payment Type:</b> <?php echo $desc; ?><br />
                                    <b>Amount:</b> <i class="fa fa-inr"></i><?php echo $amount / 100; ?>/-<br />
                                    <b>Your Name:</b> <?php echo $name; ?><br />
                                    <b>Email:</b> <?php echo $email; ?><br />
                                    <b>Phone:</b> <?php echo $phone; ?><br />
                                </p>
                                <p class="hide_this">Pay now.</p>

                                <div class="single_form mt-15 hide_this">
                                    <button class="main-btn text-white" id="rzp-button1">Pay Now</button>
                                </div>


							</div>
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
	<script src="<?php echo base_url() . 'common/dist/js/app/wallet_withd_req.js?v=' . random_strings(6); ?>"></script>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "<?php echo KEY_ID; ?>",
            "amount": "<?php echo $amount; ?>",
            "currency": "INR",
            "name": "KodeCore",
            "description": "<?php echo $desc; ?>",
            "image": "https://www.bhavishyaindia.com/common/images/logo.png",
            "order_id": "<?php echo $order_id; ?>",
            "handler": function(response) {

                var rp_paymentid = response.razorpay_payment_id;
                var rp_orderid = response.razorpay_order_id;
                var rp_sign = response.razorpay_signature;
                var paydbid = <?php echo $paydbid; ?>;
                
                $.ajax({

                    type:'POST',

                    url: BASE_URL + 'setpayment',

                    data:{rp_paymentid:rp_paymentid,rp_orderid:rp_orderid,rp_sign:rp_sign,paydbid:paydbid},

                    success:function(d){

                        if(d.resp=='failure'){

                            $('#pay_msg').show();
                            $('#pay_msg').html('<b style=""><i class="fa fa-warning" style="color:red"></i> Error: Payment Failure! Contact Us if amount is deducted.</b>');

                        }else if(d.resp=='success'){
                            $('.hide_this').hide();
                            $('#pay_msg').show();
                            $('#pay_msg').html('<b style=""><i class="fa fa-check" style="color:green"></i> Payment Success. #Payment ID: '+rp_paymentid+'. Keep this Payment id for your reference. We will contact you soon.</b>');
                            //window.location.reload();

                        }else{
                            alert('Something went wrong!');
                        }
                    }

                    });
            },
            "prefill": {
                "name": "<?php echo $name; ?>",
                "email": "<?php echo $email; ?>",
                "contact": "<?php echo $phone; ?>"
            },
            "notes": {
                "address": "Not provided",
                "shopping_order_id": "<?php echo $sp_orderid; ?>"
            },
            "theme": {
                "color": "#F37254"
            }
        };
        var rzp1 = new Razorpay(options);
        document.getElementById('rzp-button1').onclick = function(e) {
            rzp1.open();
            e.preventDefault();
        }
    </script>

</body>

</html>