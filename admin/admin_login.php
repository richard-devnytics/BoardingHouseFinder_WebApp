<?php
# database
include("../inc/db.php");
#include("../inc/api.php");
$loginError="";
if (isset($_POST['login'])) {
    $email = $_POST['admin_email'];
    $password = $_POST['admin_password'];

    // Hash the password
    $password = hash('sha256', $password);

    // SQL query to check if the email and password match in the database
    $sql = "SELECT * FROM admin WHERE admin_email = '$email' AND admin_password = '$password'";
    $result = $con->query($sql);

    if ($result && $result->num_rows == 1) {
        // Login successful
        $adminData = $result->fetch_assoc();
        $_SESSION['admin_loggedin'] = true;
        $_SESSION['admin_id'] = $adminData['admin_id'];
        $_SESSION['admin_name'] = $adminData['admin_name'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Login failed
        $loginError = "Invalid email or password";
    }
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
        background-image: url('../images/room3.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100%;
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        animation: slideInFromLeft 2s ease-in-out forwards;
        padding: 20px;
        box-sizing: border-box;
    }

    .login-form {
        margin-top: -100px;
        text-align: center;
        max-width: 400px;
        width: 100%;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        box-sizing: border-box;
    }

    .login-form .form-group {
        text-align: left;
        margin-bottom: 15px;
    }

    .login-form button {
        width: 100%;
        margin-top: 10px;
    }

    .login-form p {
        margin-top: 15px;
    }

    @media (max-width: 600px) {
        .login-form {
            padding: 10px;
            margin-top:20px;
        }
        .login-form .form-group {
            margin-bottom: 10px;
        }
        .login-form button {
            margin-top: 5px;
        }
        .container {
            height: auto;
        }
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
    <div class="preloader"></div>
    <?php include("../admin/inc/admin_header.php"); ?>
    <div class="container">
        <div class="login-form">
            <div class="messages">
                <?php if($loginError) {
                    echo '<div class="alert alert-warning" role="alert">
                    <i class="glyphicon glyphicon-exclamation-sign"></i>
                    '.$loginError.'</div>';
                } ?>
            </div>
            <h3 class="modal-title" align="center"><b>ADMIN <span style="color:orange;">LOGIN</span></b></h3>
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
            <p align="center">No Access? Contact Database Administrator.</p>
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
</div>
</body>
</html>
