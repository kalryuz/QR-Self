<?php

session_start();
require_once 'includes/database.php';
require_once 'includes/hash_url.php';

// Add/ Edit qr
if (isset($_POST['add_qr']) || isset($_POST['edit_qr'])) {
    $name = mysqli_real_escape_string($con, $_POST['qr_name']);
    $age = mysqli_real_escape_string($con, $_POST['qr_age']);
    $contact = mysqli_real_escape_string($con, $_POST['qr_contact']);
    $address = mysqli_real_escape_string($con, $_POST['qr_address']);

    // Add data qrcode
    if (isset($_POST['add_qr'])) {
		$sql = "INSERT INTO qr_code (name, age, contact, address) VALUES ('$name', $age, '$contact', '$address')";
		$add_data = mysqli_query($con, $sql);

		if ($add_data) {
			$id_qrcode = mysqli_insert_id($con);

			if ($id_qrcode) {
				require_once('vendors/phpqrcode/qrlib.php');

				// Encrypt QR code ID
				$qrcode_encrypt = encryptor('encrypt', $id_qrcode);

				// Generate QR code
				$tempDir = "src/images/qr_code/";
				$domain = '192.168.240.192'; //for display same network (localhost)
				// $domain = $_SERVER['HTTP_HOST'];
				$codeContents = "http://".$domain."/e-QRself/card.php?id=$qrcode_encrypt";
				$fileName = "QR_".$id_qrcode.".jpg";
				$filePath = $tempDir.$fileName;

				QRcode::png($codeContents, $filePath);

				// Update database with QR image name
				$sql = "UPDATE qr_code SET qr_image = '$fileName' WHERE id_qrcode = $id_qrcode";
				mysqli_query($con, $sql);

				$_SESSION['message'] = [
					'icon' 	=> 'success',
					'title' => "Successfully Create QR Code!",
					'text'	=> "QR Code has been created",
				];
			} else {
				$_SESSION['message'] = [
					'icon' 	=> 'error',
					'title' => "Failed to retrieve QR ID!",
					'text'	=> "Please contact admin"
				];
			}
		} else {
			$_SESSION['message'] = [
				'icon' 	=> 'error',
				'title' => "Failed to create QR Code!",
				'text'	=> "Please contact admin"
			];
		}
	} else {

		// Update details qrcode
		$id_qrcode = mysqli_real_escape_string($con, $_POST['id_qrcode']);
		$sql = "UPDATE qr_code SET name = '$name', age = '$age', contact = '$contact', address = '$address' WHERE id_qrcode = $id_qrcode";
		$update_qr = mysqli_query($con, $sql);

		if ($update_qr) {
			$_SESSION['message'] = [
				'icon' 	=> 'success',
				'title'	=> 'Successfully Updated!',
				'text'	=> 'Success update details QR Code'
			];
		} else {
			$_SESSION['message'] = [
				'icon' 	=> 'error',
				'title' => 'Failed to Update!',
				'text'	=> 'Failed update details QR Code'
			];
		}
	}

    header("Location: qr-code.php");
    exit();
}

// Delete qrcode
if (isset($_POST['delete_qr'])) {
	$id_qrcode = mysqli_real_escape_string($con, $_POST['id_qrcode']);

	$sql = "DELETE FROM qr_code WHERE id_qrcode = $id_qrcode";
	$delete_qr = mysqli_query($con, $sql);
	
	if ($delete_qr) {
		$_SESSION['message'] = [
			'icon' 	=> 'success',
			'title'	=> 'Successfully Deleted!',
			'text'	=> 'Success delete QR Code'
		];
	} else {
		$_SESSION['message'] = [
			'icon' 	=> 'error',
			'title' => 'Failed to Delete!',
			'text'	=> 'Failed delete QR Code'
		];
	}

	header("Location: qr-code.php");
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>QR-Self | QR Code</title>
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
	<?php require_once 'template/header.php'; ?>

	<!-- sidebar -->
    <?php require_once 'template/sidebar.php'; ?>

	<!-- message -->
	<?php 
	if (isset($_SESSION['message'])) {
		require_once 'includes/message.php';

		$icon = $_SESSION['message']['icon'];
		$title = $_SESSION['message']['title'];
		$text = $_SESSION['message']['text'];

		message($icon, $title, $text);
	} 
	?>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12 mb-2">
							<div class="title">
								<h4>QR Code</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">QR Code</li>
								</ol>
							</nav>
						</div>
						<div class="col-md-6 col-sm-12 text-right">
							<button class="btn btn-primary" data-toggle="modal" data-target="#Medium-modal">
                                <i class="ion-android-add"></i> Add QR
                            </button>
							<button class="btn btn-info" id="printBtn">
                                <i class="ion-ios-printer"></i> Print QR
                            </button>
						</div>
					</div>
				</div>
				<!-- Simple Datatable start -->
				<div class="card-box mb-30">
					<div class="pd-20">
						<h4 class="text-blue h4">List QR Code</h4>
						<p class="mb-0">Click <i>Add QR Code</i> to create new QR Code</p>
					</div>
					<div class="pb-20">
						<form id="printForm" action="print.php" method="post">
						<table class="data-table table stripe hover nowrap">
							<thead>
								<tr>
									<th><input type="checkbox" id="selectAll"></th>
									<th>QR Image</th>
									<th class="table-plus datatable-nosort">Name</th>
									<th>Age</th>
									<th>Contact</th>
									<th>Address</th>
									<th class="datatable-nosort">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql = "SELECT * FROM qr_code ORDER BY id_qrcode DESC";
								$result = mysqli_query($con, $sql);

								if (mysqli_num_rows($result) > 0) {
									while ($row = mysqli_fetch_assoc($result)) :
								?>
								<tr>
									<td>
										<input type="checkbox" name="selected_ids[]" value="<?= $row['id_qrcode'] ?>" class="selectItem">
									</td>
									<td><img src="src/images/qr_code/<?= $row['qr_image'] ?>" width="60px" style="border-radius: 5px;"></td>
									<td class="table-plus"><?= $row['name'] ?></td>
									<td><?= $row['age'] ?></td>
									<td><?= $row['contact'] ?></td>
									<td><?= $row['address'] ?></td>
									<td>
										<div class="dropdown">
											<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
												<i class="dw dw-more"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#viewModal<?= $row['id_qrcode'] ?>"><i class="dw dw-eye"></i> View</a>
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#editModal<?= $row['id_qrcode'] ?>"><i class="dw dw-edit2"></i> Edit</a>
												<a class="dropdown-item" href="#" data-toggle="modal" data-target="#deleteModal<?= $row['id_qrcode'] ?>"><i class="dw dw-delete-3"></i> Delete</a>
											</div>
										</div>
									</td>
								</tr>
								<?php endwhile; } else { ?>
									<tr>
										<td colspan="7" class="text-center">No records found</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						</form>
					</div>
				</div>
				<!-- Simple Datatable End -->
			</div>

            <!-- add modal -->
            <div class="modal fade" id="Medium-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Add Details QR Code</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form action="" method="post">
						<div class="modal-body">
							<div class="form-group">
								<label>Name</label>
								<input class="form-control" type="text" name="qr_name" placeholder="Enter Name">
							</div>
							<div class="form-group">
								<label>Age</label>
								<input class="form-control" type="text" name="qr_age" placeholder="Enter Age">
							</div>
							<div class="form-group">
								<label>Contact</label>
								<input class="form-control" type="text" name="qr_contact" placeholder="Enter Contact">
							</div>
							<div class="form-group">
								<label>Address</label>
								<textarea name="qr_address" class="form-control" placeholder="Enter Address"></textarea>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Reset</button>
                            <button type="submit" name="add_qr" class="btn btn-primary">Save</button>
                        </div>
						</form>
                    </div>
                </div>
            </div>

			<!-- edit/delete/view modal -->
			<?php
			$sql = "SELECT * FROM qr_code ORDER BY id_qrcode DESC";
			$result = mysqli_query($con, $sql);

			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) :
			?>
			<!-- edit modal -->
            <div class="modal fade" id="editModal<?= $row['id_qrcode'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Edit Details QR Code</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form action="" method="post">
						<div class="modal-body">
							<input type="hidden" name="id_qrcode" value="<?= $row['id_qrcode'] ?>">
							<div class="form-group">
								<label>Name</label>
								<input class="form-control" type="text" value="<?= $row['name'] ?>" name="qr_name" placeholder="Enter Name">
							</div>
							<div class="form-group">
								<label>Age</label>
								<input class="form-control" type="text" value="<?= $row['age'] ?>" name="qr_age" placeholder="Enter Age">
							</div>
							<div class="form-group">
								<label>Contact</label>
								<input class="form-control" type="text" value="<?= $row['contact'] ?>" name="qr_contact" placeholder="Enter Contact">
							</div>
							<div class="form-group">
								<label>Address</label>
								<textarea name="qr_address" class="form-control" placeholder="Enter Address"><?= $row['address'] ?></textarea>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="edit_qr" class="btn btn-primary">Save</button>
                        </div>
						</form>
                    </div>
                </div>
            </div>

			<!-- view modal -->
			<div class="modal fade" id="viewModal<?= $row['id_qrcode'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">View QR Code</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
						<div class="modal-body">
							<div class="shadow p-3">
								<div class="d-flex align-items-center mb-2">
									<div class="w-25">
										<img src="src/images/qr_code/<?= $row['qr_image'] ?>" width="100" style="border-radius:5px;">
									</div>
									<div class="text-primary w-75 d-flex justify-content-center">
										<div class="text-center">
											<h1 class="text-primary">QR-Self</h1>
											<small>Create your QR here.</small>
										</div>
									</div>
								</div>
								<div class="p-2">
									<table style="width: 100%;">
										<?php 
										$datas = [
											'Name' => 'name', 
											'Age' => 'age', 
											'Contact' => 'contact', 
											'Address' => 'address'
										];

										foreach ($datas as $label => $field) : 
										?>
										<tr>
											<td style="width: 20%;"><?= $label ?></td>
											<td style="width: 3%;">:</td>
											<td style="width: 70%;"><?= htmlspecialchars($row[$field]) ?></td>
										</tr>
										<?php endforeach; ?>
									</table>
								</div>
							</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Back</button>
                        </div>
                    </div>
                </div>
            </div>

			<!-- delete modal -->
			<div class="modal fade" id="deleteModal<?= $row['id_qrcode'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">Delete QR Code</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form action="" method="post">
						<div class="modal-body">
							<input type="hidden" name="id_qrcode" value="<?= $row['id_qrcode'] ?>">
							<p>Are you sure to delete QR code name <b><?= $row['name'] ?></b></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_qr" class="btn btn-danger">Delete</button>
                        </div>
						</form>
                    </div>
                </div>
            </div>
			<?php endwhile; } ?>
			
            <!-- footer -->
            <?php require_once 'template/footer.php'; ?>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<!-- buttons for Export datatable -->
	<script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
	<script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
	<script src="src/plugins/datatables/js/buttons.print.min.js"></script>
	<script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
	<script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
	<script src="src/plugins/datatables/js/pdfmake.min.js"></script>
	<script src="src/plugins/datatables/js/vfs_fonts.js"></script>
	<!-- Datatable Setting js -->
	<script src="vendors/scripts/datatable-setting.js"></script>

	<script>
		// trigger form print
		document.getElementById("printBtn").addEventListener("click", function () {
			document.getElementById("printForm").submit();
		});

		// select id to print
		$(document).ready(function () {
			$("#selectAll").click(function () {
				$(".selectItem").prop("checked", this.checked);
			});

			$(".selectItem").click(function () {
				if ($(".selectItem:checked").length === $(".selectItem").length) {
					$("#selectAll").prop("checked", true);
				} else {
					$("#selectAll").prop("checked", false);
				}
			});
		});
	</script>
</body>
</html>