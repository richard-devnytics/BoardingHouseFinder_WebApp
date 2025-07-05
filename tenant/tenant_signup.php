<?php

# database
include("../inc/db.php");
#include("../inc/api.php");

// Include PHPMailer
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
$registrationError="";

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (strlen($phone) == 11) {

        // Check if the email or phone number already exists in the database
        $checkQuery = "SELECT * FROM tenants WHERE email = '$email' OR phone = '$phone'";
        $result = $con->query($checkQuery);

        if ($result->num_rows > 0) {
            $registrationError = "An account with the same email or phone number already exists.";
        } else {
            // Perform password validation and hashing
            if ($password === $confirmPassword && strlen($password) > 7) {
                $password = hash('sha256', $password);

                $otp = rand(100000, 999999);

                // Store OTP in the database
                $insertOtp = "INSERT INTO tenant_otp (tenant_email, tenant_otp_code, timestamp) VALUES ('$email', $otp, NOW())";
                $con->query($insertOtp);

                // Send OTP via email
                $mail = new PHPMailer\PHPMailer\PHPMailer();

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = '';
                    $mail->Password = '';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                $mail->setFrom('', 'Tueogan');
                $mail->addAddress($email);

                $mail->Subject = 'Your OTP for Login';
                $mail->Body = "Your OTP is: $otp";

                if ($mail->send()) {
                    // Redirect to verify_otp.php with email and name parameter
                    header("Location: verify_tenant_otp.php?email=$email&name=$name&phone=$phone&password=$password");
                    exit();
                } else {
                    $registrationError = "Failed to send OTP. Please try again later.";
                }
            } else {
                $registrationError = "Passwords do not match or password length is less than 8.";
            }
        }
    } else {
        $registrationError = "Phone number should be of 11 digits.";
    }
}
?>


<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | Tenant Registration</title>
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

     @keyframes slideInFromLeft {
    from {
        opacity: 0;
        transform: translateX(-100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

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
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            width: 90%;
            margin: 50px auto 100px;
            animation: slideInFromLeft 2s ease-in-out forwards;
        }
        .modal-title {
            margin-bottom: 20px;
        }
        .form-group {
            position: relative;
            margin-bottom: 15px;
        }
        .form-group i {
            position: absolute;
            left: 20px;
            top: 17px;
            color: #888;
        }
        .form-control {
            padding-left: 30px;
            height: 50px;
        }
        .button-33 {
            background-color: orange;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            width: 100%;
        }
        
        @media (max-width: 768px) {
    .container {
        padding: 15px;
        margin-top: 30px;
        margin-bottom: 50px;
    }

    .form-control {
        height: 45px;
    }
}
</style>

<script>
        function togglePasswordVisibility() {
            var password = document.getElementById("password");
            var confirmPassword = document.getElementById("confirmPassword");
            if (password.type === "password") {
                password.type = "text";
                confirmPassword.type = "text";
            } else {
                password.type = "password";
                confirmPassword.type = "password";
            }
        }
    </script>

</head>
<body>
<div class="page-wrapper"> 
  <!--preloader start-->
  <div class="preloader"></div>
  <!--preloader end--> 
  <!--main-header start-->
   
  <?php include("../tenant/inc/tenant_header.php"); ?>
  
  <!--main-header end--> 
  <!--slider start-->
  
  <!--slider end-->
  
  
<!--about-info end--> 
  
 <div class="container">
        <div class="registration-form">
            <div class="messages">
                <!-- Add your PHP messages here -->
                 <?php if($registrationError) {
                  echo '<div class="alert alert-warning" role="alert">
                  <i class="glyphicon glyphicon-exclamation-sign"></i>
                  '.$registrationError.'</div>';                    
                  
                } ?>
            </div>
            <h3 class="modal-title" align="center"><b>OWNER'S <span style="color:orange;">REGISTRATION</span></b></h3>
            <form action="" method="POST" id="registration-form">
                <fieldset>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <i class="fa fa-user"></i>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name" required data-error="Name is required.">
                        </div>
                        <div class="form-group col-md-6">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required data-error="Password is required.">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <i class="fa fa-phone"></i>
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone" required data-error="Phone number is required.">
                        </div>
                        <div class="form-group col-md-6">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Confirm Password" required data-error="Confirming password is required.">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <i class="fa fa-envelope"></i>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required data-error="Valid email is required.">
                        </div>
                        <div class="form-group col-md-6">
                             <input type="checkbox" onclick="togglePasswordVisibility()"> Show Password
                        </div>
                    </div>
                    <button class="button-33" type="submit" name="register" style="font-size:20px">Register</button>
                    <p style="margin-top: 10px;" align="center"> Already have an account? <a href="../tenant/tenant_login.php" style="color: orange;"> CLick Here</a></p>
                </fieldset>
            </form>
        </div>
    </div>
 <?php include("../footer.php"); ?>
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