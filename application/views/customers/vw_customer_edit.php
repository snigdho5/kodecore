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
							if (isset($update_success) && $update_success != '') {
								echo "<p><i class=\"icofont-tick-boxed\" style=\"color:green\"></i> Status: " . $update_success . "</p>";
							} elseif (isset($update_failure) && $update_failure != '') {
								echo "<p><i class=\"fas fa-exclamation-triangle\" style=\"color:yellow\"></i> Error: " . $update_failure . "</p>";
							} else {
								//echo "<p style='color:#f5f2f0'><i class=\"fas fa-exclamation-triangle\" style=\"color:yellow\"></i> Something went wrong!</p>";
							}
							?>
							<form class="form-horizontal" method="post" action="<?php echo base_url() . 'changecustomer'; ?>">

								<div class="card-body">
									<h4 class="card-title">Personal Info</h4>
									<div class="form-group row">
										<label for="first_name" class="col-sm-3 text-right control-label col-form-label">First Name</label>
										<div class="col-sm-9">
											<input type="hidden" name="cust_id" value="<?php echo ($user_data) ? $user_data['custid'] : ''; ?>">
											<input type="hidden" class="form-control" id="username_val" name="username_val" value="1">
											<input type="hidden" class="form-control" id="email_val" name="email_val" value="1">
											<input type="hidden" class="form-control" id="phone_val" name="phone_val" value="1">
											<input type="text" class="form-control" name="first_name" placeholder="First Name.." value="<?php echo ($user_data) ? $user_data['first_name'] : ''; ?>" required="">
										</div>
									</div>

									<div class="form-group row">
										<label for="last_name" class="col-sm-3 text-right control-label col-form-label">Last Name</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="last_name" placeholder="Last Name.." value="<?php echo ($user_data) ? $user_data['last_name'] : ''; ?>" required="">
										</div>
									</div>

									<div class="form-group row">
										<label for="email" class="col-sm-3 text-right control-label col-form-label">Email</label>
										<div class="col-sm-9">
											<input type="email" class="form-control" id="email" name="email" placeholder="Email.." value="<?php echo ($user_data) ? $user_data['email'] : ''; ?>">
											<label id="chk_email" style="display: none;"></label>
										</div>
									</div>

									<div class="form-group row">
										<label for="phone" class="col-sm-3 text-right control-label col-form-label">Phone</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="phone" name="phone" placeholder="Phone.." value="<?php echo ($user_data) ? $user_data['phone'] : ''; ?>">
											<label id="chk_phone" style="display: none;"></label>
										</div>
									</div>

									<div class="form-group row">
										<label for="lname" class="col-sm-3 text-right control-label col-form-label">User Name</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="user_name" name="user_name" placeholder="User Name.." value="<?php echo ($user_data) ? $user_data['username'] : ''; ?>" required="">
											<label id="chk_username" style="display: none;"></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="password" class="col-sm-3 text-right control-label col-form-label">Password</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="password" name="password" placeholder="Password..">
										</div>
										<div class="col-sm-2">
											<button type="button" class="btn btn-success generate_pass">Generate</button>
										</div>
									</div>

								</div>
								<div class="border-top">
									<div class="card-body">
										<button type="submit" id="submit" class="btn btn-primary customer_btn_submit">Submit</button>
									</div>
								</div>
							</form>

							<p><i class="icofont-login"></i> <b>Last Login:</b> <?php echo ($user_data) ? $user_data['lastlogin'] : ''; ?> <i class="icofont-computer"></i> <b>Last Login IP:</b> <?php echo ($user_data) ? $user_data['lastloginip'] : ''; ?> <i class="icofont-ui-clock"></i> <b>Last Updated:</b> <?php echo ($user_data) ? $user_data['lastupdated'] : ''; ?></p>
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
	<script src="<?php echo base_url() . 'common/dist/js/app/customers.js?v=' . random_strings(6); ?>"></script>

</body>

</html>