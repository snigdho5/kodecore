<?php
$page =  $this->uri->segment(1);
?>
<aside class="left-sidebar" data-sidebarbg="skin5">
	<!-- Sidebar scroll-->
	<div class="scroll-sidebar">
		<!-- Sidebar navigation-->
		<nav class="sidebar-nav">
			<ul id="sidebarnav" class="p-t-30">
				<li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?php echo base_url(); ?>dashboard" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>

				<li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-receipt"></i><span class="hide-menu">Manage </span></a>
					<ul aria-expanded="false" class="collapse  first-level <?php echo ($page == 'add-user' || $page == 'add-customer' || $page == 'edit-customer' || $page == 'changecustomer' || $page == 'add-it-project' || $page == 'edit-project' || $page == 'changeproject' || $page == 'add-investment-plan' || $page == 'edit-investment-plan' || $page == 'changeinvplan' || $page == 'update-cryptocurrency' || $page == 'wallet-withdrawal-requests') ? 'in' : ''; ?>">

						<li class="sidebar-item <?php echo ($page == 'add-user') ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>users" class="sidebar-link <?php echo ($page == 'add-user') ? 'active' : ''; ?>"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> User Management</span></a></li>

						<li class="sidebar-item <?php echo ($page == 'add-customer' || $page == 'edit-customer' || $page == 'changecustomer') ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>customers" class="sidebar-link <?php echo ($page == 'add-customer' || $page == 'edit-customer' || $page == 'changecustomer') ? 'active' : ''; ?>"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> Customer Management</span></a></li>

						<li class="sidebar-item <?php echo ($page == 'add-it-project' || $page == 'edit-project' || $page == 'changeproject') ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>it-projects" class="sidebar-link <?php echo ($page == 'add-it-project' || $page == 'edit-project' || $page == 'changeproject') ? 'active' : ''; ?>"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> IT Projects Management</span></a></li>

						<li class="sidebar-item <?php echo ($page == 'add-investment-plan' || $page == 'edit-investment-plan' || $page == 'changeinvplan') ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>investment-plans" class="sidebar-link <?php echo ($page == 'add-investment-plan' || $page == 'edit-investment-plan' || $page == 'changeinvplan') ? 'active' : ''; ?>"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> Investment Plans Management</span></a></li>

						<li class="sidebar-item <?php echo ($page == 'update-cryptocurrency') ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>view-cryptocurrency" class="sidebar-link <?php echo ($page == 'update-cryptocurrency') ? 'active' : ''; ?>"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> Cryptocurrency Management</span></a></li>

						<li class="sidebar-item "><a href="<?php echo base_url(); ?>buy-cryptocurrency-transactions" class="sidebar-link "><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> Cryptocurrency Transactions - Buy</span></a></li>

						<li class="sidebar-item "><a href="<?php echo base_url(); ?>sell-cryptocurrency-transactions" class="sidebar-link "><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> Cryptocurrency Transactions - Sell</span></a></li>

						<?php /*<li class="sidebar-item "><a href="<?php echo base_url();?>wallet-redeem-requests" class="sidebar-link "><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> Wallet Redeem Requests</span></a></li>*/ ?>

						<li class="sidebar-item "><a href="<?php echo base_url(); ?>wallet-withdrawal-requests" class="sidebar-link "><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> Wallet Withdrawal Requests</span></a></li>
					</ul>
				</li>

				<li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-receipt"></i><span class="hide-menu">Details </span></a>
					<ul aria-expanded="false" class="collapse  first-level <?php echo ($page == 'update-privacy-tnc') ? 'in' : ''; ?>">

						<li class="sidebar-item <?php echo ($page == 'update-privacy-tnc') ? 'active' : ''; ?>"><a href="<?php echo base_url(); ?>view-privacy-tnc" class="sidebar-link <?php echo ($page == 'update-privacy-tnc') ? 'active' : ''; ?>"><i class="mdi mdi-chart-bubble"></i><span class="hide-menu"> View / Edit Info</span></a></li>
					</ul>
				</li>

			</ul>
		</nav>
		<!-- End Sidebar navigation -->
	</div>
	<!-- End Sidebar scroll-->
</aside>