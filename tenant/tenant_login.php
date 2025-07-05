<?php
include("../inc/db.php");
// include("../inc/api.php");
$loginError = "";

if (isset($_POST['login'])) {
    $email = $_POST['admin_email'];
    $password = $_POST['admin_password'];

    // Hash the password
    $hashedPassword = hash('sha256', $password);

    // SQL query to check if the email and password match in the database
    $sql = "SELECT * FROM tenants WHERE email = ? AND password = ?";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $email, $hashedPassword);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            // Check the status
            $tenantData = $result->fetch_assoc();
            $status = $tenantData['status'];

            if ($status === 'confirmed' || $status === 'unblocked') {
                // Login successful
                $_SESSION['tenant_loggedin'] = true;
                $_SESSION['tenant_id'] = $tenantData['tenant_id'];
                $_SESSION['tenant_name'] = $tenantData['name'];
                header("Location: tenant_dashboard.php");
                exit();
            } elseif ($status === 'pending') {
                $loginError = "Your registration is pending. Please wait for admin confirmation.";
            } elseif ($status === 'blocked') {
                $loginError = "Your account has been blocked. Please contact the admin.";
            } else {
                $loginError = "Unknown account status. Please contact the admin.";
            }
        } else {
            // Login failed due to invalid credentials
            $loginError = "Invalid email or password.";
        }
        $stmt->close();
    } else {
        $loginError = "Database query error: " . $con->error;
    }
}
?>


<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | Tenant Login</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="Tueogan">
<meta name="keywords" content="boardinghouse, html, template, responsive, room">
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
<link href="../css/owl.css" rel="stylesheet">
<link href="../css/jquery.fancybox.css" rel="stylesheet">
<link href="../css/style_slider.css" rel="stylesheet">
<link href="../rs-plugin/css/settings.css" rel="stylesheet">
<!-- button CSS -->
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
        margin-top:20px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Adjust to center vertically within viewport */
        animation: slideInFromLeft 2s ease-in-out forwards;
        margin-bottom:-100px;
    }

    .login-form {
        margin-top: -175px;
        text-align: center;
        max-width: 400px; /* Adjust width as needed */
        width: 100%;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .login-form .form-group {
        text-align: left; /* Align form inputs to the left */
        margin-bottom: 15px;
    }

    .login-form button {
        width: 100%; /* Full-width button */
        margin-top: 10px;
    }

    .login-form p {
        margin-top: 15px;
    }

    @media (max-width: 768px) {
        .container {
            padding: 10px;
            margin-top:-100px;
            margin-bottom:-100px;
        }

        .login-form {
            padding: 15px;
            margin-top: 10px;
        }

        .login-form .form-group input {
            font-size: 16px;
        }

        .login-form h3 {
            font-size: 24px;
        }

        .login-form button {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .login-form {
            padding: 10px;
            margin-top: -80px;
        }

        .login-form .form-group input {
            font-size: 14px;
        }

        .login-form h3 {
            font-size: 20px;
        }

        .login-form button {
            font-size: 14px;
        }
    }
     .modal{
      margin-top:100px;
  }

  .modal-header {
            background-color: #ff9800;
            color: white;
        }
        .modal-body {
            background-color: #ffe0b2;
        }
        .modal-footer {
            background-color: #ffcc80;
        }
        .btn-primary {
            background-color: #ff9800;
            border-color: #ff9800;
        }
        .btn-primary:hover {
            background-color: #e68900;
            border-color: #e68900;
        }
        .close {
            color: white;
            opacity: 1;
        }
        .close:hover {
            color: #ccc;
        }
</style>

<script>
    function togglePasswordVisibility() {
        var password = document.getElementById("admin_password");
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }
    }
</script>

</head>
<body>
<div class="page-wrapper"> 
    <!-- preloader start -->
    <div class="preloader"></div>
    <!-- preloader end --> 
    <!-- main-header start -->
    <?php include("../tenant/inc/tenant_header.php"); ?>
    
    <div class="container">
        <div class="login-form">
            <div class="messages">
                <?php if($loginError) {
                    echo '<div class="alert alert-warning" role="alert">
                    <i class="glyphicon glyphicon-exclamation-sign"></i>
                    '.$loginError.'</div>';                    
                } ?>
            </div>
            <h3 class="modal-title" align="center"><b>OWNER'S <span style="color:orange;">LOGIN</span></b></h3>
            <form action="" method="POST" id="login-form">
                <fieldset>
                    <div class="form-group">
                        <i class="fa fa-envelope"></i>
                        <input type="email" style="height: 50px;" name="admin_email" id="admin_email" class="form-control" placeholder="Email" required data-error="Valid email is required.">
                    </div>
                    <div class="form-group">
                        <i class="fa fa-lock"></i>
                        <input type="password" style="height: 50px;" name="admin_password" id="admin_password" class="form-control" placeholder="Password" required data-error="Password is required.">
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" onclick="togglePasswordVisibility()">
                            <em>Show Password</em>
                        </label>
                    </div>
                    <button class="button-33" type="submit" name="login">Login</button>
                </fieldset>
            </form>
            <p align="center">New to our Platform?<a href="../tenant/tenant_signup.php"> Sign Up Here.</a></p>
            <p align="center">Forgot Password? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#resetPasswordModal">Reset Here</a></p>
        </div>
    </div>
    
    <div class="modal fade" id="resetPasswordModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Reset Password</h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="resetPasswordForm" action="reset_tenantPassword.php" method="GET">
                    <div class="form-group">
                        <label for="email">Enter your email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>
    </div>
</div>

    <?php include("../footer.php"); ?>
    <!-- jquery start --> 
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
    <!-- jquery end -->
</div>
</body>

</html>
