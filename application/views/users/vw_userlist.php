<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	<?php $this->load->view('top_css'); ?>
	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'common/assets/extra-libs/multicheck/multicheck.css'; ?>">
	<link href="<?php echo base_url() . 'common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css'; ?>" rel="stylesheet">
	<title><?php echo comp_name; ?> | Users</title>
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
						<h4 class="page-title">Users</h4>
						<div class="ml-auto text-right">
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Users</li>
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
								<h5 class="card-title">Users <button type="button" class="btn badge badge-pill badge-success" onclick="location.href='<?php echo base_url() . 'add-user'; ?>'">Add User</button></h5>
								<div class="table-responsive">
									<table id="zero_config" class="table table-striped table-bordered">
										<thead>
											<tr class="textcen">
												<th>Sl</th>
												<th>Created On</th>
												<th>Name</th>
												<th>Username</th>
												<th>Last Login</th>
												<th>Action</th>

											</tr>
										</thead>
										<tbody class="textcen">
											<?php
											if (!empty($user_data)) {
												//print_obj($broc_list);
												$sl = 1;
												foreach ($user_data as $key => $val) {
											?>
													<tr>
														<td><?php echo $sl; ?></td>
														<td><?php echo $val['dtime']; ?></td>
														<td><?php echo $val['fullname']; ?></td>
														<td><?php echo $val['username']; ?></td>
														<td><?php echo ($val['lastlogin'] != '') ? $val['lastlogin'] : 'Not logged in'; ?></td>
														<td>
															<?php if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1) {  ?>
																<button type="button" onclick="location.href='<?php echo base_url() . 'profile/' . encode_url($val['userid']); ?>'"><i class="icofont-pencil-alt-2"></i></button>
																<button type="button" class="del_user" data-userid="<?php echo encode_url($val['userid']); ?>" data-fullname="<?php echo $val['fullname']; ?>"><i class="fas fa-trash-alt"></i></button>
															<?php } ?>
														</td>
													</tr>
												<?php
													$sl++;
												}
											} else {
												?>
												<tr>
													<td colspan="6">No data found</td>
												</tr>
											<?php
											}
											?>
										</tbody>
									</table>
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
	<script src="<?php echo base_url() . 'common/dist/js/app/users.js?v=' . random_strings(6); ?>"></script>
	<script>
		$('#zero_config').DataTable();
	</script>

</body>

</html>