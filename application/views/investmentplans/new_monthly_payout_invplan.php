<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
	<?php $this->load->view('top_css'); ?>
	<!-- Custom CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'common/assets/extra-libs/multicheck/multicheck.css'; ?>">
	<link href="<?php echo base_url() . 'common/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css'; ?>" rel="stylesheet">
	<title><?php echo comp_name; ?> | Add <?php echo $page_title; ?></title>
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
						<h4 class="page-title">Add <?php echo $page_title; ?> </h4>
						<div class="ml-auto text-right">
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Add <?php echo $page_title; ?> </li>
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
							<form class="form-horizontal" id="create_payout_form">
								<div class="card-body">
									<h4 class="card-title">Create New <?php echo $page_title; ?> <button type="button" class="btn badge badge-pill badge-success" onclick="location.href='<?php echo base_url() . 'monthly-payout-inv-plans'; ?>'"><?php echo $page_title; ?>s List</button></h4>
									
									<div class="form-group row">
										<label for="customer_id" class="col-sm-3 text-right control-label col-form-label">Customer</label>
										<div class="col-sm-9">
											<select class="select2 form-control custom-select" id="customer_id" name="customer_id">
												<option value="">Select</option>
                                                <?php
                                                    if(!empty($cust_data)){
                                                        foreach($cust_data as $key => $value){
                                                ?>
                                                    <option value="<?php echo $value['custid'];?>"><?php echo $value['fullname'] . ' (' . $value['email'] . ', ' . $value['phone'] . ') [Rs. ' . $value['walletamt'] .']'; ?></option>
                                                <?php
                                                        }
                                                    }
                                                ?>
												
											</select>
										</div>
									</div>
									
									<div class="form-group row">
										<label for="plan_id" class="col-sm-3 text-right control-label col-form-label">Investment Plan</label>
										<div class="col-sm-9">
											<select class="select2 form-control custom-select" id="plan_id" name="plan_id">
												<option value="">Select</option>   
											</select>
										</div>
									</div>

									<div class="form-group row">
										<label for="amount" class="col-sm-3 text-right control-label col-form-label">Amount</label>
										<div class="col-sm-9">
											<input type="text" class="form-control number" id="amount" name="amount" placeholder="Amount..">
										</div>
									</div>
									
									<div class="form-group row" style="display:none;">
										<label for="deductions" class="col-sm-3 text-right control-label col-form-label">Deductions</label>
										<div class="col-sm-9">
											<input type="text" class="form-control number" id="deductions" name="deductions" placeholder="Deductions.." value="0">
										</div>
									</div>

									<div class="form-group row">
										<label for="remarks" class="col-sm-3 text-right control-label col-form-label">Remarks</label>
										<div class="col-sm-9">
											<textarea class="form-control" id="remarks" name="remarks" placeholder="Remarks.."></textarea>
										</div>
									</div>
									
								</div>
								<div class="border-top">
									<div class="card-body">
										<button type="submit" id="payout_btn_submit" class="btn btn-primary payout_btn_submit">Submit</button>
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
	<script src="<?php echo base_url() . 'common/dist/js/app/investmentplans.js?v=' . random_strings(6); ?>"></script>

	<script type="text/javascript">
		CKEDITOR.replace('remarks');

        $("#customer_id").select2({
            placeholder: "Select Customer",
            allowClear: true
        });

        $("#plan_id").select2({
            placeholder: "Select Investment Plan",
            allowClear: true
        });
	</script>

</body>

</html>