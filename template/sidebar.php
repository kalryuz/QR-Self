<div class="left-side-bar">
	<div class="brand-logo">
		<a href="dashboard.php" class="d-flex align-items-center">
			<i class="icon-copy ion-cube" style="font-size: 40px; margin-right:10px; margin-bottom: -23px;"></i>
			<h2 class="text-white" style="margin-bottom: -25px;">QR-Self</h2>
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<!-- menu -->
				<li>
					<div class="dropdown-divider"></div>
				</li>
				<li>
					<div class="sidebar-small-cap">Menu</div>
				</li>
				<li>
					<a href="dashboard.php" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-pie-chart"></span><span class="mtext">Dashboard</span>
					</a>
				</li>
				<li>
					<a href="qr-code.php" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-smartphone-1"></span><span class="mtext">QR Code</span>
					</a>
				</li>

				<!-- system -->
				<li>
					<div class="dropdown-divider"></div>
				</li>
				<li>
					<div class="sidebar-small-cap">System</div>
				</li>
				<li>
					<a href="profile.php" class="dropdown-toggle no-arrow">
						<span class="micon dw dw-user1"></span><span class="mtext">Profile</span>
					</a>
				</li>
				<li>
					<a href="#" class="dropdown-toggle no-arrow" data-toggle="modal" data-target="#logoutModal">
						<span class="micon dw dw-logout1"></span><span class="mtext">Logout</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="mobile-menu-overlay"></div>

<?php
if (isset($_POST['logout'])) {
	session_destroy();
	header('Location: ./');
	exit();
}
?>

<!-- logout modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myLargeModalLabel">Logout</h4>
			</div>
			<form action="" method="post">
			<div class="modal-body">
				<p>Are you sure to <b>Logout</b>?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="submit" name="logout" class="btn btn-danger">Sure</button>
			</div>
			</form>
		</div>
	</div>
</div>
