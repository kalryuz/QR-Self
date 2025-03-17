<?php
session_start();
require_once 'includes/database.php';

// Display user data
$user_id = $_SESSION['id'];
$sql = "SELECT * FROM user WHERE id_user = '".$user_id."'";
$execute = mysqli_query($con, $sql);
$data = mysqli_fetch_assoc($execute);

// Update profile
if (isset($_POST['p_save'])) {
	$name = mysqli_real_escape_string($con, $_POST['name']);
	$username = mysqli_real_escape_string($con, $_POST['username']);
	$password = mysqli_real_escape_string($con, $_POST['password']);

	// check input not null
	if (empty($name) || empty($username) || empty($password)) {
		$_SESSION['message'] = [
			'icon' => 'error',
			'title' => 'Input cannot be empty',
			'text' => 'Please fill in all input',
		];
	} else {
		// update user data
		$sql = "UPDATE user SET name = '".$name."', username = '".$username."', password = '".$password."' WHERE id_user = '".$user_id."'";
		$execute = mysqli_query($con, $sql);

		if ($execute) {
			$_SESSION['message'] = [
				'icon' 	=> 'success',
				'title' => 'Successfully Updated!',
				'text' 	=> 'Your profile has been updated',
			];
		} else {
			$_SESSION['message'] = [
				'icon' 	=> 'error',
				'title' => 'Error Update Data!',
				'text' 	=> 'Please inform to admin',
			];
		}
	}

	header('Location: profile.php');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>QR-Self | Profile</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
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
		require 'includes/message.php';

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
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Profile</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Profile</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>

				<div class="pd-20 col-xl-6 card-box mb-30">
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">Profile Details</h4>
							<p class="mb-30">You can change your profile here</p>
						</div>
					</div>
					<form action="" method="post">
					<div class="form-group row">
						<label class="col-sm-12 col-md-2 col-form-label">Name</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" name="name" type="text" value="<?= $data['name'] ?>" placeholder="Enter Name">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 col-form-label">Username</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" name="username" placeholder="Enter username" value="<?= $data['username'] ?>" type="text">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-sm-12 col-md-2 col-form-label">Password</label>
						<div class="col-sm-12 col-md-10">
							<input class="form-control" name="password" placeholder="Enter password" value="<?= $data['password'] ?>" type="text">
						</div>
					</div>

					<div class="d-flex justify-content-end">
						<button class="btn btn-danger" style="margin-right: 10px;" type="reset">Reset</button>
						<button class="btn btn-primary" type="submit" name="p_save">Save</button>
					</div>
					</form>
				</div>
			</div>
			
            <!-- footer -->
            <?php require_once 'template/footer.php'; ?>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
</body>
</html>