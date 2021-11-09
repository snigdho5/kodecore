<!DOCTYPE html>
<html dir="ltr">

<head>
	<?php $this->load->view('top_css'); ?>
	<title><?php echo comp_name; ?> | Login</title>
</head>

<body>
	<div class="main-wrapper">
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
		<!-- Preloader - style you can find in spinners.css -->
		<!-- ============================================================== -->
		<!-- ============================================================== -->
		<!-- Login box.scss -->
		<!-- ============================================================== -->
		<div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
			<div class="auth-box bg-dark border-top border-secondary">
				<div id="loginform">
					<div class="text-center p-t-20 p-b-20">
						<span class="db"><img src="<?php echo base_url() . 'common/assets/images/logo2.png'; ?>" alt="logo" /></span><br /><br />
						<h5 id="chk_msg2" style='display: none;'></h5>
					</div>
					<!-- Form -->
					<form class="form-horizontal m-t-20" id="login-form">
						<div class="row p-b-30">
							<div class="col-12">
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
									</div>
									<input type="email" class="form-control form-control-lg" placeholder="Username" id="username" name="username" aria-label="Username" aria-describedby="basic-addon1" required="">
								</div>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
									</div>
									<input type="password" class="form-control form-control-lg" placeholder="Password" id="password" name="password" aria-label="Password" aria-describedby="basic-addon1" required="">
								</div>
							</div>
						</div>
						<div class="row border-top border-secondary">
							<div class="col-12">
								<div class="form-group">
									<div class="p-t-20">
										<button class="btn btn-info" id="to-recover" type="button"><i class="fa fa-lock m-r-5"></i> Lost password?</button>
										<button class="btn btn-success float-right btn-login" type="submit" id="submit">Login</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>

				<!--login form ends-->
				<div id="2faform" style="display: none;">
					<div class="text-center">
						<span class="db"><img src="<?php echo base_url() . 'common/assets/images/logo2.png'; ?>" alt="logo" /></span><br /><br />
						<span class="text-white">Enter The Two-Factor Authentication Code Displayed On Your Authenticator App.</span><br>
						<img src="https://play-lh.googleusercontent.com/HPc5gptPzRw3wFhJE1ZCnTqlvEvuVFBAsV9etfouOhdRbkp-zNtYTzKUmUVPERSZ_lAL" width="50" title="Google Authenticator" />
						<p id="chk_msg3" style='display: none;'></p>
					</div>
					<div class="row m-t-20">
						<form class="col-12" id="2fa-form">
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="icofont-key"></i></span>
								</div>
								<input type="number" class="form-control form-control-lg" placeholder="2FA Code" name="tpasscode" id="tpasscode" aria-label="2FA Code" aria-describedby="basic-addon1" required="">
							</div>
							<div class="row m-t-20 p-t-20 border-top border-secondary">
								<div class="col-12">
									<button class="btn btn-info float-right" type="submit" name="action">Submit</button>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div id="recoverform">
					<div class="text-center">
						<span class="db"><img src="<?php echo base_url() . 'common/assets/images/logo2.png'; ?>" alt="logo" /></span><br /><br />
						<span class="text-white">Enter your e-mail address below and we will send you instructions how to recover a password.</span>
						<br /><br />
						<p id="chk_msg_rec" style='display: none;'></p>
					</div>
					<div class="row m-t-20">
						<form class="col-12" id="recover-form">
							<div class="input-group mb-3 email-div">
								<div class="input-group-prepend">
									<span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
								</div>
								<input type="email" class="form-control form-control-lg" name="emailid" id="emailid" placeholder="Email Address" aria-label="Username" aria-describedby="basic-addon1" required>
							</div>
							<div class="row m-t-20 p-t-20 border-top border-secondary">
								<div class="col-12">
									<a class="btn btn-success" href="javascript:void(0);" id="to-login" name="action">Back To Login</a>
									<button class="btn btn-info float-right btn-recover" type="submit" name="action">Recover</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- ============================================================== -->
	</div>
	<!-- ============================================================== -->

	<?php $this->load->view('bottom_js'); ?>
	<!-- This page plugin js -->
	<script src="<?php echo base_url() . 'common/dist/js/app/login.js?v=' . random_strings(6); ?>"></script>

</body>

</html>