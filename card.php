<?php
session_start();
require_once 'includes/database.php';
require_once 'includes/hash_url.php';

?>

<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>QR-Self | Card</title>

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-119386393-1');
	</script>
</head>
<body class="login-page">
	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-5 mb-3">
                    <div class="shadow p-3 bg-white">
                        <?php
                        if (isset($_GET['id']) && !empty($_GET['id'])) {
                            $id = encryptor('decrypt', $_GET['id']);
                            $id = intval($id); //convert to int

                            $sql = "SELECT * FROM qr_code WHERE id_qrcode = $id";
                            $result = mysqli_query($con, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) :
                        ?>
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
                        <?php 
                                endwhile; 
                            } else {
                                echo "QR code not found!";
                            }
                        } else {
                            echo "QR code not found!";
                        }
                        ?>
                    </div>
				</div>
                <div class="col-md-6 col-lg-7 text-center">
					<p>QR-Self @ Developed by <a href="https://kalryuz.com" target="_blank" class="text-primary">Kalryuz Dev</a></p>
				</div>
			</div>
		</div>
	</div>
	<!-- js -->
	<script src="vendors/scripts/core.js"></script>
	<script src="vendors/scripts/script.min.js"></script>
	<script src="vendors/scripts/process.js"></script>
	<script src="vendors/scripts/layout-settings.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>