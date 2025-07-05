<?php
// Include the database connection file
include("../inc/db.php");

// Include PHPMailer
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Check if the 'home_id' and 'tenant_email' parameters are set in the URL
if (isset($_GET['home_id']) && isset($_GET['tenant_email'])) {
    $home_ID = $_GET['home_id'];
    $tenant_email = $_GET['tenant_email'];

    // Start a transaction
    $con->begin_transaction();

    try {
        // SQL query to approve the home
        $sqlConfirmList = "UPDATE holiday_homes SET admin_approved='approved' WHERE home_id = ?";
        $stmt = $con->prepare($sqlConfirmList);

        if ($stmt) {
            $stmt->bind_param("i", $home_ID);

            // Execute the statement
            if ($stmt->execute()) {
                $stmt->close();

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = '';
                    $mail->Password = '';  // Use a more secure method to store the password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('', 'Tueogan');
                    $mail->addAddress($tenant_email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Congratulations!';
                    $mail->Body = 'Dear Owner,<br><br>Your Boarding House has been approved. Borders can now reserve your rooms.<br><br>Thank you!<br>From: Tueogan-Admin';

                    $mail->send();
                     $con->commit();
                    echo '<script>alert("Boarding House has been approved. Notice was sent through email."); window.location.href = "view_holidayhomes.php";</script>';
                    exit();
                } catch (Exception $e) {
                    echo "Message could not be sent to tenant. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                throw new Exception("Error executing statement: " . $stmt->error);
            }
        } else {
            throw new Exception("Error preparing statement: " . $con->error);
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $con->rollback();
        echo $e->getMessage();
    }

    // Commit the transaction if no errors
} else {
    // Handle the case where 'home_id' or 'tenant_email' parameters are not set in the URL
    echo "Invalid request.";
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
<meta charset="UTF-8">
<title>Tueogan | Admin Login</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="Tueogan">
<meta name="keywords" content="boardinghouse, html, template, responsive, rooms">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Fav Icon -->
<link rel="shortcut icon" href="../favicon.ico">
<!-- Style CSS -->
<link href="../css/bootstrap.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link rel="stylesheet" href="../dist/color-default.css">
<link rel="stylesheet" href="../dist/color-switcher.css">
<link href="../css/magnific-popup.css" rel="stylesheet">
<link href="../css/animate.css" rel="stylesheet">
<link href="../css/owl.css" rel="stylesheet">
<link href="../css/jquery.fancybox.css" rel="stylesheet">
<link href="../css/style_slider.css" rel="stylesheet">
<link href="../rs-plugin/css/settings.css" rel="stylesheet">
<!--buttonCSS-->
<link href="../css/button.css" rel="stylesheet">
<style>
    /* Loading screen styles */
        .loading-screen {
            display: none;
            position: fixed;
            z-index: 9999;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 2em;
        }
   
</style>

</head>
<body>
<div class="page-wrapper">
    <div class="preloader"></div>
    <?php include("../admin/inc/admin_header.php"); ?>
    <div class="container">
         <div class="loading-screen" id="loadingScreen">
        Sending email, please wait...
    </div>
    </div>
    <?php include("../footer.php"); ?>
    <script src="../js/jquery-2.1.4.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.magnific-popup.min.js"></script>
    <script src="../js/imagesloaded.pkgd.min.js"></script>
    <script src="../js/isotope.pkgd.min.js"></script>
    <script src="../js/jquery.fancybox8cbb.js?v=2.1.5"></script>
    <script src="../js/owl.carousel.js"></script>
    <script src="../rs-plugin/js/jquery.themepunch.tools.min.js"></script>
    <script src="../rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
    <script src="../js/counter.js"></script>
    <script>
      
    function showLoadingScreen() {
        document.getElementById('loadingScreen').style.display = 'flex';
    }

    </script>
    <script src="../js/script.js"></script>
</div>
</body>
</html>
