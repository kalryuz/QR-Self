<?php
session_start();
require_once 'includes/database.php';

// check session
if (empty($_SESSION['id'])) {
	header("Location: ./");
	exit();
}

// total qr
$sql = "SELECT COUNT(id_qrcode) AS total FROM qr_code";
$execute = mysqli_query($con, $sql);
$total_qrcode = mysqli_fetch_assoc($execute);
$total_qrcode = $total_qrcode['total'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>QR-Self | Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>
<body>
    <!-- header -->
    <?php require_once 'template/header.php' ?>

	<!-- sidebar -->
    <?php require_once 'template/sidebar.php' ?>

	<!-- welcome message -->
	<?php if (isset($_SESSION['welcome'])) : ?>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				Swal.fire({
					title: "<?= htmlspecialchars($_SESSION['welcome'], ENT_QUOTES, 'UTF-8'); ?>",
					text: "Login Success",
					icon: "success"
				});
			});
		</script>
		<?php unset($_SESSION['welcome']); // Remove session message after displaying ?>
	<?php endif; ?>

	<div class="main-container">
		<div class="pd-ltr-20">
			<div class="row">
				<div class="col-xl-6 mb-30">
					<div class="card-box height-100-p widget-style1 text-primary">
						<div class="d-flex flex-wrap align-items-center">
							<div class="m-2">
								<i class="icon-copy ion-cube" style="font-size: 60px;"></i>
							</div>
							<div class="widget-data">
								<div class="h3 mb-0 text-primary">Welcome, <?= $_SESSION['name']; ?></div>
								<div class="weight-600 font-14">You can create QR code user detail here</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 mb-30">
					<div class="card-box height-100-p widget-style1">
						<div class="d-flex flex-wrap align-items-center">
							<div class="m-2 text-info" style="padding-right: 8px; padding-left:10px;">
								<i class="icon-copy ion-android-phone-portrait" style="font-size: 60px;"></i>
							</div>
							<div class="widget-data">
								<div class="h4 mb-0 text-info"><?= $total_qrcode ?? 0; ?></div>
								<div class="weight-600 font-14 text-info">QR Code</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-3 mb-30">
					<div class="card-box height-100-p widget-style1 text-danger">
						<div class="d-flex flex-wrap align-items-center">
							<div class="m-2">
								<i class="icon-copy ion-android-people" style="font-size: 60px;"></i>
							</div>
							<div class="widget-data">
								<div class="h4 mb-0 text-danger"><?= $total_qrcode ?? 0; ?></div>
								<div class="weight-600 font-14">Views</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-8 mb-30">
					<div class="card-box height-100-p pd-20">
						<h2 class="h4 mb-20">Activity</h2>
						<div id="chart5"></div>
					</div>
				</div>
				<div class="col-xl-4 mb-30">
					<div class="card-box height-100-p pd-20">
						<h2 class="h4 mb-20">New QR Code</h2>
						<table class="data-table table nowrap">
							<thead>
								<tr>
									<th>Name</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql = "SELECT name, last_updated FROM qr_code LIMIT 3";
								$execute = mysqli_query($con, $sql);

								// if query
								if (!$execute) {
									die("<tr><td class='text-danger'>Query failed: " . mysqli_error($con) . "</td></tr>");
								}
								
								if (mysqli_num_rows($execute) > 0) :
									while ($row = mysqli_fetch_assoc($execute)) :
										$formatted_date = date('d-m-Y H:i', strtotime($row['last_updated']));
								?>
								<tr>
									<td>
										<h5 class="font-16"><?= $row['name'] ?></h5>
										<?= $formatted_date ?>
									</td>
								</tr>
								<?php 
									endwhile; 
								endif; 
								?>
							</tbody>
						</table>
						<a href="qr-code.php" class="btn btn-primary w-100 mt-1 text-white">View All</a>
					</div>
				</div>
			</div>
			
            <!-- footer -->
            <?php require_once 'template/footer.php' ?>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="src/plugins/apexcharts/apexcharts.min.js"></script>
	<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<script src="vendors/scripts/dashboard.js"></script>

</body>
</html>