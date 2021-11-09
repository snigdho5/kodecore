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
								<div class="table-responsive">
									<table id="zero_config" class="table table-striped table-bordered">
										<thead>
											<tr class="textcen">
												<th>Sl</th>
												<th>Created On</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Amount</th>
												<th>Status</th>
												<th>Action</th>

											</tr>
										</thead>
										<tbody class="textcen">
											<?php
											if (!empty($wallet_data)) {
												//print_obj($wallet_data);
												$sl = 1;
												foreach ($wallet_data as $key => $val) {
											?>
													<tr>
														<td><?php echo $sl; ?></td>
														<td><?php echo $val['dtime']; ?></td>
														<td><?php echo $val['user_name']; ?></td>
														<td><?php echo $val['user_email']; ?></td>
														<td><?php echo $val['user_phone']; ?></td>
														<td><?php echo $val['amount']; ?></td>
														<td><?php
														if($val['status'] == '1'){
															echo '<i class="icofont-tick-mark" style="color:green;"></i> Approved';
														}else if($val['status'] == '2'){
															echo '<i class="icofont-not-allowed" style="color:red;"></i> Rejected';
														}else{
															echo '<i class="icofont-clock-time" style="color:blue;"></i> Pending';
														}
														 ?></td>
														<td>
															<?php if (!empty($this->session->userdata('userid')) && $this->session->userdata('usr_logged_in') == 1 && $this->session->userdata('usergroup') == 1 && $val['status'] == '0') {  ?>
																<button type="button" class="apprv-withdrawal-req" data-id="<?php echo $val['wdlid']; ?>" data-fullname="<?php echo $val['user_name']; ?>" data-amount="<?php echo $val['amount']; ?>" data-apprv-type="1"><i class="icofont-tick-mark"></i></button>

																<button type="button" class="apprv-withdrawal-req" data-id="<?php echo $val['wdlid']; ?>" data-fullname="<?php echo $val['user_name']; ?>" data-amount="<?php echo $val['amount']; ?>" data-apprv-type="2"><i class="icofont-not-allowed"></i></button>
															<?php }
															else if($val['status'] == '1' && $val['payment_status'] == '0'){
																echo 'N/A';
																?>
																<!-- <button type="button" class="pay-now" data-id="<?php //echo $val['wdlid']; ?>" data-fullname="<?php //echo $val['user_name']; ?>" data-amount="<?php //echo $val['amount']; ?>" >Pay Now</button> -->
															<?php
															}  
															else{
																echo 'N/A';
															} ?>
														</td>
													</tr>
												<?php
													$sl++;
												}
											} else {
												?>
												<tr>
													<td colspan="7">No data found</td>
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
	<script src="<?php echo base_url() . 'common/dist/js/app/wallet_withd_req.js?v=' . random_strings(6); ?>"></script>
	<script>
		$('#zero_config').DataTable();
	</script>

</body>

</html>