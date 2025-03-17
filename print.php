<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR-Self | Print</title>
</head>
<body>
    <div style="display: flex; align-items: center;">
    <?php
    session_start();
    require_once 'includes/database.php';

    if (isset($_POST['selected_ids']) && !empty($_POST['selected_ids'])) {
        $selected_id = $_POST['selected_ids'];
        $list_id = implode(", ", $selected_id);

        // Display QR
        $sql = "SELECT qr_image, name FROM qr_code WHERE id_qrcode IN ($list_id)";
        $execute = mysqli_query($con, $sql);

        while ($data = mysqli_fetch_assoc($execute)) :
    ?>
        <div style="margin: 20px; text-align: center;">
            <img src="src/images/qr_code/<?= $data['qr_image']; ?>" alt="QR Code" style="margin-bottom:-20px;">
            <p><?= $data['name']; ?></p>
        </div>
    <?php
        endwhile;
    } else {
        $_SESSION['message'] = [
            'icon'  => 'error',
            'title' => 'Tick Box To Print',
            'text'  => 'No QR codes selected.'
        ];
        
        header("Location: qr-code.php");
        exit();
    }
    ?>
    </div>
</body>
</html>
<script>
    window.print();
</script>