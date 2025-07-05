<?php
include("../inc/db.php");
$verifyError = "";

// Include PHPMailer
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Check if all required parameters are set
if (isset($_GET['email']) && isset($_GET['name']) && isset($_GET['phone']) && isset($_GET['password'])) {
    $email = $_GET['email'];
    $name = $_GET['name'];
    $phone = $_GET['phone'];
    $password = $_GET['password'];
    $status='pending';

    if (isset($_POST['verify'])) {
        $enteredOtp = $_POST['otp'];
        
        // Use prepared statements to prevent SQL injection
        $stmt = $con->prepare("SELECT * FROM tenant_otp WHERE tenant_email = ? AND tenant_otp_code = ?");
        $stmt->bind_param("ss", $email, $enteredOtp);
        $stmt->execute();
        $otpResult = $stmt->get_result();

        if ($otpResult->num_rows == 1) {
            // Use prepared statement to prevent SQL injection for DELETE query
            $stmt = $con->prepare("DELETE FROM tenant_otp WHERE tenant_email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            
            // Use prepared statement to prevent SQL injection for INSERT query
            $stmt = $con->prepare("INSERT INTO tenants (name, email, phone, password, status) VALUES (?, ?, ?, ?, ?)");
            $status = 'pending';
            $stmt->bind_param("sssss", $name, $email, $phone, $password, $status);
            if ($stmt->execute()) {
                // Registration successful
             
                // Send confirmation email
                 $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                try {
                    // Server settings
                         $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = '';
                    $mail->Password = '';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('', 'Tueogan');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Notice of Registration';
                    $mail->Body = "Dear $name,<br><br>Thank you for signing up. Your registration is pending admin approval. If no confirmation receive within 24 hours, please contact admin at:<br><br>Phone No.: 09983069056<br>Email: renc@gmail.com <br><br>We will notify you once your account is confirmed.<br><br>Thank you!<br>From: Tueogan-Admin";

                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                echo '<script>alert("Thank you, Notice of Registration was sent to your email."); window.location.href = "../tenant/tenant_login.php";</script>';
                exit();
            } else {
                $verifyError = "Error in registration. Please try again.";
            }
        } else {
            $verifyError = "Invalid OTP. Please try again.";
        }
    }
} else {
    $verifyError = "Missing email, name, phone, or password parameter. Please provide a valid link.";
}
?>



<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | Users Login</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="SunRise construction & Builder company">
<meta name="keywords" content="construction, html, template, responsive, corporate">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!-- Fav Icon -->
<link rel="shortcut icon" href="../favicon.ico">
<!-- Style CSS -->
<link href="../css/bootstrap.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link rel="stylesheet" href="../dist/color-default.css">
<link rel="stylesheet" href="../dist/color-switcher.css">
<link href="../css/magnific-popup.css" rel="stylesheet">
<link href="../css/animate.css" rel="stylesheet">
<link href=".//css/owl.css" rel="stylesheet">
<link href="../css/jquery.fancybox.css" rel="stylesheet">
<link href="../css/style_slider.css" rel="stylesheet">
<link href="../rs-plugin/css/settings.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!--buttonCSS-->
<link href="../css/button.css" rel="stylesheet">

<style>

    body {
    background-image: url('../images/room3.jpg'); /* Replace with your image path */
    background-size: cover; /* Cover the entire background */
    background-position: center; /* Center the background image */
    background-repeat: no-repeat; /* Prevent the background image from repeating */
    height: 100%; /* Ensure full height coverage */
    margin: 0; /* Remove default margin */
    font-family: Arial, sans-serif; /* Optional: Set a default font */
  }
  .container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Adjust to center vertically within viewport */
    margin-top: 10px;
  }

  .login-form {
    margin-top: -300px;
    text-align: center;
    max-width: 400px; /* Adjust width as needed */
    width: 100%;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
  }

  .form-group i {
            position: absolute;
            left: 4px;
            top: 5px;
            color: #888;
        }

  .login-form .form-group {
    text-align: left; /* Align form inputs to the left */
    margin-bottom: 15px;
    margin-top: 20px;
  }

  .login-form button {
    width: 100%; /* Full-width button */
    margin-top: 10px;
  }

  .login-form p {
    margin-top: 15px;
  }

</style>


</head>
<body>
<div class="page-wrapper"> 
  <!--preloader start-->
  <div class="preloader"></div>
  <!--preloader end--> 
  <!--main-header start-->
  <?php include("../tenant/inc/tenant_header.php");?>
  
  <!--main-header end--> 
  <!--slider start-->
  
  <!--slider end-->
  
  
<!--about-info end--> 
  
  <div class="container">
  <div class="login-form">
    <div class="messages">
              <?php if($verifyError) {
                  echo '<div class="alert alert-warning" role="alert">
                  <i class="glyphicon glyphicon-exclamation-sign"></i>
                  '.$verifyError.'</div>';                    
                  
                } ?>
            </div>
    <h3 class="modal-title" align="center"><b>Verify <span style="color:orange;">OTP</span></b></h3>
    <form action="" method="POST" id="otp">
      <fieldset>
        <div class="form-group">
          <i class="fa fa-envelope"></i>
          <input type="otp" style=" height: 50px;" name="otp" id="otp" class="form-control" placeholder="One Time PIN" required >
        </div>
        <button class="button-33" type="submit" name="verify">Verify</button>
      </fieldset>
    </form>

    <p align="center">Did not Receive OTP? Contact Admin.</p>
  </div>
</div>

 
<!--jquery start--> 
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
<script src="../dist/color-switcher.js"></script> 
<script>
        var colorSheets = [  
     {
                color: "#fcb80b",
                title: "Switch to Default",
                href: "./dist/color-default.css"
            },
            {
                color: "#e41900",
                title: "Switch to Red",
                href: "./css/color-change-css/red.css"
            },
            {
                color: "#26a65b",
                title: "Switch to Green",
                href: "./css/color-change-css/green.css"
            },
            {
                color: "#2cabf5",
                title: "Switch to Blue",
                href: "./css/color-change-css/blue.css"
            },
            {
        color: "#05fff0",
                title: "Switch to Purple",
                href: "./css/color-change-css/purple.css"
                
            },
        {
                color: "#ff4229",
                title: "Switch to Orange",
                href: "./css/color-change-css/orange.css"
            }
        ];


        ColorSwitcher.init(colorSheets);


    </script> 
<script src="../js/script.js"></script> 
<!--jquery end-->
</body>

</html>