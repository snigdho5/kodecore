<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	<?php $this->load->view('top_css'); ?>
	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'common/assets/extra-libs/multicheck/multicheck.css'; ?>">
	<link href="<?php echo base_url() . 'common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css'; ?>" rel="stylesheet">
	<title><?php echo comp_name; ?> | Add User</title>
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
						<h4 class="page-title">Add User</h4>
						<div class="ml-auto text-right">
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Add User</li>
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
							<form class="form-horizontal" id="create_user_form">
								<?php //print_obj($user_data);die; 
								?>
								<div class="card-body">
									<h4 class="card-title">Create New User <button type="button" class="btn badge badge-pill badge-success" onclick="location.href='<?php echo base_url() . 'users'; ?>'">Users List</button></h4>
									<div class="form-group row">
										<label for="full_name" class="col-sm-3 text-right control-label col-form-label">Full Name</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="full_name" name="full_name" placeholder="Full Name..">
										</div>
									</div>
									<div class="form-group row" style="display: none;">
										<label for="user_group" class="col-sm-3 text-right control-label col-form-label">User Group</label>
										<div class="col-sm-9">
											<select class="form-control" id="user_group" name="user_group">
												<option value="0">- Select -</option>
												<option value="2">Admin</option>
												<option value="3">User</option>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="user_name" class="col-sm-3 text-right control-label col-form-label">Email (User Name)</label>
										<div class="col-sm-9">
											<input type="email" class="form-control" id="user_name" name="user_name" placeholder="Email..">
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
										<button type="submit" id="user_btn_submit" class="btn btn-primary user_btn_submit">Submit</button>
									</div>
								</div>
							</form>
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
	<script src="<?php echo base_url() . 'common/dist/js/app/users.js?v=' . random_strings(6); ?>"></script>

</body>

</html>