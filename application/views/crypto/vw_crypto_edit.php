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
							<form class="form-horizontal" method="post" action="<?php echo base_url() . 'update-cryptocurrency'; ?>">

								<div class="card-body">
									<h4 class="card-title"><?php echo $page_title; ?> Info</h4>
									<div class="form-group row">
										<label for="title" class="col-sm-3 text-right control-label col-form-label">Crypto Name</label>
										<div class="col-sm-9">
											<input type="hidden" name="cryptoid" value="<?php echo ($crypto_data) ? $crypto_data['cryptoid'] : ''; ?>">
											<input type="text" class="form-control" id="name" name="name" placeholder="Crypto Name.." value="<?php echo ($crypto_data) ? $crypto_data['name'] : ''; ?>">
											<label id="chk_title" style="display: none;"></label>
										</div>
									</div>

                                    <div class="form-group row">
										<label for="title" class="col-sm-3 text-right control-label col-form-label">Crypto Symbol</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" id="symbol" name="symbol" placeholder="Crypto Symbol.." value="<?php echo ($crypto_data) ? $crypto_data['symbol'] : ''; ?>">
										</div>
									</div>

									<div class="form-group row">
										<label for="description" class="col-sm-3 text-right control-label col-form-label">Description</label>
										<div class="col-sm-9">
											<textarea class="form-control" id="description" name="description" placeholder="Description.."><?php echo ($crypto_data) ? $crypto_data['description'] : ''; ?></textarea>
										</div>
									</div>

									<div class="form-group row">
										<label for="amount" class="col-sm-3 text-right control-label col-form-label">Price (inr)</label>
										<div class="col-sm-9">
											<input type="number" class="form-control" id="price_inr" name="price_inr" placeholder="Price (inr).." value="<?php echo ($crypto_data) ? $crypto_data['price_inr'] : ''; ?>">
										</div>
									</div>
                                    <div class="form-group row">
										<label for="amount" class="col-sm-3 text-right control-label col-form-label">Price (btc)</label>
										<div class="col-sm-9">
											<input type="number" class="form-control" id="price_btc" name="price_btc" placeholder="Price (btc).." value="<?php echo ($crypto_data) ? $crypto_data['price_btc'] : ''; ?>">
										</div>
									</div>

                                    <div class="form-group row">
										<label for="amount" class="col-sm-3 text-right control-label col-form-label">Price (usd)</label>
										<div class="col-sm-9">
											<input type="number" class="form-control" id="price_usd" name="price_usd" placeholder="Price (usd).." value="<?php echo ($crypto_data) ? $crypto_data['price_usd'] : ''; ?>">
										</div>
									</div>
									

								</div>
								<div class="border-top">
									<div class="card-body">
										<button type="submit" id="submit" class="btn btn-primary proj_btn_submit">Submit</button>
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
	<!-- <script src="<?php echo base_url() . 'common/dist/js/app/itprojects.js?v=' . random_strings(6); ?>"></script> -->

	<script type="text/javascript">
		CKEDITOR.replace('description');
	</script>

</body>

</html>