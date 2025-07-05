<?php
include("../inc/db.php");
// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
    exit();
}

// Get the admin's name from the session

$tenant_ID = $_SESSION['tenant_id'];


// Fetch counts from the database
$sqlUserCount =  "SELECT COUNT(DISTINCT users.user_id) AS user_count
    FROM reservations
    JOIN users ON reservations.user_id = users.user_id
    WHERE reservations.tenant_id = $tenant_ID
";
$resultUserCount = $con->query($sqlUserCount);
$userCount = $resultUserCount->fetch_assoc()['user_count'];

$sqlHolidayHomeCount = "SELECT COUNT(home_id) AS home_count FROM holiday_homes WHERE tenant_id=$tenant_ID";
$resultHolidayHomeCount = $con->query($sqlHolidayHomeCount);
$holidayHomeCount = $resultHolidayHomeCount->fetch_assoc()['home_count'];

$sqlPendingResCount = "SELECT COUNT(reservation_id) AS pending_count FROM reservations WHERE status='pending' AND tenant_id=$tenant_ID";
$resultPendingResCount = $con->query($sqlPendingResCount);
$pendingResCount = $resultPendingResCount->fetch_assoc()['pending_count'];

$sqlConfirmResCount = "SELECT COUNT(reservation_id) AS confirm_count FROM reservations WHERE status IN ('admin_confirmed', 'tenant_confirmed') AND tenant_id=$tenant_ID ";
$resultConfirmResCount = $con->query($sqlConfirmResCount);
$confirmResCount = $resultConfirmResCount->fetch_assoc()['confirm_count'];


$sqlResCount = "SELECT COUNT(reservation_id) AS res_count FROM reservations WHERE tenant_id=$tenant_ID ";
$resultResCount = $con->query($sqlResCount);
$ResCount = $resultResCount->fetch_assoc()['res_count'];

// Prepare the SQL statement to get the count of rooms where the tenant_id matches
$sql = "SELECT COUNT(*) as room_count 
        FROM room 
        INNER JOIN holiday_homes ON room.home_id = holiday_homes.home_id 
        WHERE holiday_homes.tenant_id = ?";

$stmt = $con->prepare($sql);

// Bind the tenant_id parameter to the query
$stmt->bind_param('i', $tenant_ID);
$room_count="";
// Execute the query
if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Output the count of rooms
    $room_count = $row['room_count'];
} else {
    echo "Error executing query: " . $stmt->error;
}

$stmt->close();

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

    @keyframes  slideInFromLeft {
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
    .main-content {
            padding: 20px;
        }

    .card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
    background-color: white; /* White background color */
    color: #333; /* Ensure text is visible on light background */
    animation: slideInFromLeft 2s ease-out forwards;
}

.card:hover {
    transform: translateY(-5px);
    background-color: rgba(255, 165, 0, 0.7); 
}

.card:hover .fas {
    color: rgba(255, 255, 255, 1); /* White color for icons */
}

.card-body {
    padding: 20px;
    margin-top: 10px;
    margin-bottom: 10px;
}

.card-title {
    margin-top: 10px;
    font-size: 18px;
    font-weight: bold;
}

.card-body p {
    font-size: 24px;
    font-weight: bold;
    color: #333; /* Ensure text is visible on light background */
}

.fas {
    color: rgba(255, 165, 0, 1); /* Orange color for icons */
}

.container-fluid {
    max-width: 1200px;
    margin: auto;
}

.border-bottom {
    border-bottom: 2px solid #e5e5e5;
}

h1 {
    font-size: 32px;
    font-weight: bold;
}

</style>

</head>
<body>
<div class="page-wrapper"> 
  <!--preloader start-->
  <div class="preloader"></div>
  <!--preloader end--> 
  <!--main-header start-->
  <?php
include ("../tenant/inc/tenant_header.php");
 ?>
  <main class="main-content">
    <div class="container-fluid">

        <!-- Display counts for users, total holiday homes, and reserved homes -->
        <div class="row" style="margin-top:40px;">
            <div class="col-md-4">
                <a href="tenant_view_users.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-5x"></i>
                            <p class="mt-2"><?php echo $userCount; ?></p>
                            <h5 class="card-title">Guests</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="view_tenantsHome.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-home fa-5x"></i>
                            <p class="mt-2"><?php echo $holidayHomeCount; ?></p>
                            <h5 class="card-title">Boarding House</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="view_tenantConfirmReservation.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-check fa-5x"></i>
                            <p class="mt-2"><?php echo $confirmResCount; ?></p>
                            <h5 class="card-title">Confirmed Reservations</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4" style="margin-top:40px;">
                <a href="view_tenantReservations.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-address-book fa-5x"></i>
                            <p class="mt-2"><?php echo $pendingResCount; ?></p>
                            <h5 class="card-title">Pending Reservations</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4" style="margin-bottom: 100px; margin-top:40px;">
                <a href="view_availableRoom.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-bed fa-5x"></i>
                            <p class="mt-2"><?php echo $room_count; ?></p>
                            <h5 class="card-title">Available Rooms</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4" style="margin-bottom: 100px; margin-top:40px;">
                <a href="view_tenantAllReservations.php" style="text-decoration: none; color: inherit;">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-bed fa-5x"></i>
                            <p class="mt-2"><?php echo $ResCount; ?></p>
                            <h5 class="card-title">Total Reservations</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>

 <?php include("../footer.php") ?>
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

