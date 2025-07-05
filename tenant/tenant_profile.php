<?php

# database
include("../inc/db.php");
$passwordChangeError="";
$passwordChangeSuccess="";
// Check if the admin is logged in, if not, redirect to the login page
if (!isset($_SESSION['tenant_id']) || !$_SESSION['tenant_id']) {
    header("Location: tenant_login.php");
    exit();
}

// Retrieve admin data from the database based on the admin ID stored in the session
$tenantID = $_SESSION['tenant_id'];

// Perform a database query to get the admin's information
$sql = "SELECT * FROM tenants WHERE tenant_id = $tenantID";
$result = $con->query($sql);

if ($result->num_rows == 1) {
    $tenantData = $result->fetch_assoc();
    $tenantName = $tenantData['name'];
    $tenantEmail = $tenantData['email'];
} else {
    // Handle the case where the admin data couldn't be retrieved
    echo "Error: Tenant data not found.";
    exit();
}

// Check if the admin submitted a password change request
if (isset($_POST['changePassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    // Check if the current password matches the stored password
    $storedPassword = $tenantData['password'];

    if (hash('sha256', $currentPassword) === $storedPassword) {
        // Current password matches the stored password
        if ($newPassword !== $currentPassword) {
            // Ensure the new password is not the same as the current password
            if ($newPassword === $confirmNewPassword) {
                // New password and confirm new password match
                // Update the admin's password in the database using SHA-256
                $hashedNewPassword = hash('sha256', $newPassword);
                $updateSql = "UPDATE tenants SET password = '$hashedNewPassword' WHERE tenant_id = $tenantID";
                if ($con->query($updateSql)) {
                    // Password updated successfully
                    $passwordChangeSuccess = "Password updated successfully.";
                } else {
                    $passwordChangeError = "Password update failed. Please try again.";
                }
            } else {
                $passwordChangeError = "New password and confirm new password do not match.";
            }
        } else {
            $passwordChangeError = "The new password is the same as the current password.";
        }
    } else {
        $passwordChangeError = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | Update User's Password</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="Tueogan">
<meta name="keywords" content="boardinghouse, html, template, responsive, rooms">
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
            max-width: 800px;
            width: 100%;
            height:470px;
            margin-top: 50px;
            margin-bottom: 150px;
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
        }  </style>
<script>
        function togglePasswordVisibility() {
            var password = document.getElementById("currentPassword");
            var newPassword = document.getElementById("newPassword");
            var confirmNewPassword=document.getElementById("confirmNewPassword");
            if (password.type === "password") {
                password.type = "text";
                newPassword.type = "text";
                confirmNewPassword.type="text";
            } else {
                password.type = "password";
                newPassword.type = "password";
                confirmNewPassword="password";
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
  
  <?php include("../tenant/inc/tenant_header.php");?>
  <!--main-header end--> 
  <!--slider start-->
  
  <!--slider end-->
  
  
<!--about-info end--> 
 
 <div class="container">
        <div class="registration-form">
            <div class="messages">
                <!-- Add your PHP messages here -->
                 <?php if($passwordChangeError) {
                  echo '<div class="alert alert-warning" role="alert">
                  <i class="glyphicon glyphicon-exclamation-sign"></i>
                  '.$passwordChangeError.'</div>';                    
                  
                } ?>
                <?php if($passwordChangeSuccess) {
                  echo '<div class="alert alert-success" role="alert">
                  <i class="glyphicon glyphicon-exclamation-sign"></i>
                  '.$passwordChangeSuccess.'</div>';                    
                  
                } ?>
            </div>
            <h3 class="modal-title" align="center"><b> Hello there, <span style="color:orange;"><?php echo $tenantName?></span></b></h3>
            <form action="" method="POST" id="changeProfile-form">
                <fieldset>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <i class="fa fa-user"></i>
                            <input type="text" name="name" id="name" class="form-control" value='<?php echo $tenantName?>' readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="newPassword" id="newPassword" class="form-control" placeholder="New Password" required data-error="Confirming password is required.">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <i class="fa fa-envelope"></i>
                            <input type="email" name="email" id="email" class="form-control" value='<?php echo $tenantEmail?>' readonly>
                        </div>
                         <div class="form-group col-md-6">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="confirmNewPassword" id="confirmNewPassword" class="form-control" placeholder="Confirm Password" required data-error="Confirming password is required.">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="currentPassword" id="currentPassword" class="form-control" placeholder="Password" required data-error="Password is required.">
                        </div>
                        <div class="show" align="center">
                             <input type="checkbox" onclick="togglePasswordVisibility()"> Show Password
                        </div>
                    </div>
                    <button class="button-33" type="submit" name="changePassword" style="font-size:20px">Update Password</button>
                </fieldset>
            </form>
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
