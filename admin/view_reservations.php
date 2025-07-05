<?php
# Include the database connection file
include("../inc/db.php");
$Result ="";

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

# Fetch reservation data from the database
$sqlReservations = "SELECT * FROM reservations WHERE status='pending' ";
$resultReservations = $con->query($sqlReservations);

# Check if there are any reservations
if ($resultReservations->num_rows > 0) {
    $reservations = $resultReservations->fetch_all(MYSQLI_ASSOC);
} else {
    // No reservations found
    $reservations = array();
    $Result = False;
}

# Fetch user name
$userName = '';
if (!$Result) {
    // Get the user ID from the first reservation (assuming you want to get the name for all unique user IDs)
    $userIDs = array_column($reservations, 'user_id');
    $userIDs = array_unique($userIDs); // Ensure unique user IDs

    // Fetch all user names for these user IDs
    $userNames = array();
    foreach ($userIDs as $userID) {
        // Prepare the SQL statement
        $getname = $con->prepare("SELECT name FROM USERS WHERE user_id = ?");

        // Bind the parameter
        $getname->bind_param("i", $userID);

        // Execute the statement
        $getname->execute();

        // Get the result
        $result = $getname->get_result();

        // Fetch the user name (assuming there is only one row)
        if ($row = $result->fetch_assoc()) {
            $userNames[$userID] = $row['name'];
        }

        // Close the statement
        $getname->close();
    }
}

?>

<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | Confirmed Reservation</title>
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
i{
    font-size: 15px;
}
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
    .table-responsive {
        display: flex;
        justify-content: center;
    }

    .table {
        border: 2px solid #ffa500;
        width: 90%;
        margin-top: 20px;
        animation: slideInFromLeft 2s ease-out forwards;
    }

    .table th, .table td {
        border: 2px solid #ffa500;
        color: #333;
    }

    .table th {
        background-color: #ffa500;
        color: white;
    }

    .btn-primary {
        background-color: darkblue;
        border-color: #ff8c00;
    }

    .btn-primary:hover {
        background-color: #ff7043;
        border-color: #ff7043;
    }

    .btn-danger {
        background-color: #ff4500;
        border-color: #ff4500;
    }

    .btn-danger:hover {
        background-color: #ff6347;
        border-color: #ff6347;
    }

    .btn-success {
        background-color: #32cd32;
        border-color: #32cd32;
    }

    .btn-success:hover {
        background-color: #2e8b57;
        border-color: #2e8b57;
    }

    .btn-secondary {
        background-color: #ffdead;
        border-color: #ffdead;
    }

    .btn-secondary:hover {
        background-color: #ffe4b5;
        border-color: #ffe4b5;
    }
    
    /* Modal background and text colors */
.modal-header, .modal-footer {
    background-color: #FF8C00;
    color: white;
    margin-top: 50px;
    margin-bottom: -10px;
    height: 60px;
}

.modal-body {
    background-color: #FFEFD5;
}

/* Button styles */
.btn-primary {
    background-color: #FF8C00;
    border-color: #FF8C00;
}

.btn-primary:hover {
    background-color: #FF7F00;
    border-color: #FF7F00;
}

.btn-close {
    color: white;
    opacity: 1;
}

.btn-close:hover {
    color: #FFEFD5;
}

/* Input field focus border color */
.form-control:focus {
    border-color: #FF8C00;
    box-shadow: 0 0 0 0.25rem rgba(255, 140, 0, 0.25);
}

/* Label colors */
.form-label {
    color: black;
}


</style>

</head>
<body>
<div class="page-wrapper"> 
  <!--preloader start-->
  <div class="preloader"></div>
  <!--preloader end--> 
  <!--main-header start-->
  <?php include("../admin/inc/admin_header.php"); ?>
 <main class="main-content">
    <div class="section-title">
      <h3 style="margin-top:10px; margin-bottom: -20px;">PENDING <span>RESERVATIONS</span></h3>
    </div>
        <div class="container-fluid">
            <!-- Display Reservations in a Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Guest Name</th>
                            <th>Room</th>
                            <th>Check-In Date</th>
                            <th>Check-Out Date</th>
                            <th>Total Price</th>
                            <th>Tenant Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation) : ?>
                            <tr>
                                <td><?php echo $userNames[$userID];?></td>
                                <?php 
                                $home_ID=$reservation['home_id'];
                                $sqlhome = "SELECT name, tenant_id FROM holiday_homes WHERE home_id=$home_ID";
                                $result_home = $con->query($sqlhome);
                                # Check if there are any reservations
                                if ($result_home->num_rows > 0) {
                                    $homes = $result_home->fetch_all(MYSQLI_ASSOC);
                                } else {
                                    // No reservations found
                                    $homes = array();
                                }
                                foreach ($homes as $home_name):
                                    $homeName=$home_name['name'];
                                    $tenant_ID=$home_name['tenant_id'];
                                endforeach;
                                ?>
                                <td><?php echo $homeName; ?></td>
                                <td><?php echo $reservation['check_in_date']; ?></td>
                                <td><?php echo $reservation['check_out_date']; ?></td>
                                <td>Php <?php echo $reservation['total_price']; ?></td>
                                <?php
                                # Fetch tenant data from the database
                                $sqlTenant = "SELECT tenant_id, name FROM tenants WHERE tenant_id=$tenant_ID";
                                $result_tenant = $con->query($sqlTenant);
                                # Check if there are any tenant
                                if ($result_tenant->num_rows > 0) {
                                    $tenants = $result_tenant->fetch_all(MYSQLI_ASSOC);
                                } else {
                                    // No tenant found
                                    $tenants = array();
                                    $tenantName=" No Tenant Found";
                                }
                                foreach ($tenants as $tenant):
                                    $tenantName=$tenant['name'];
                                endforeach;
                                ?>
                                <td><?php echo $tenantName; ?> </td>
                                <td>

                                    <!-- Button to trigger the edit modal -->
                                    <button type="button" style="margin-left: 10px;" class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#editModal_<?php echo $reservation['reservation_id']; ?>">
                                        Edit
                                    </button>
                                    <a href="delete_reservation.php?id=<?php echo $reservation['reservation_id']; ?>&home_id=<?php echo $home_ID; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this reservation?')">Cancel</a>
                                      <a href="confirmReservation.php?id=<?php echo $reservation['reservation_id']; ?>&user_id=<?php echo $userID; ?>&tenant_id=<?php echo $tenant_ID; ?>" class="btn btn-success" onclick="return confirm('Are you sure you want to confirm this reservation?')">Confirm</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($Result === False): ?>
    <div class="readmore text-center">
        <button class="main-btn btn-1 btn-1e">No Pending Reservations</button>
    </div>
<?php endif; ?>
    </main>

    <!-- Modals for editing reservations -->
    <?php foreach ($reservations as $reservation) : ?>
        <div class="modal fade" id="editModal_<?php echo $reservation['reservation_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel_<?php echo $reservation['reservation_id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" align="center" id="editModalLabel_<?php echo $reservation['reservation_id']; ?>"><i class="fas fa-address-book fa-2x">    Edit Reservation No. <?php echo $reservation['reservation_id']; ?></i></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Edit Form -->
                        <form method="POST" action="update_reservation.php">
                            <!-- Hidden input to pass reservation_id to update_reservation.php -->
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">

                            <!-- User ID -->
                            <div class="mb-3">
                                <label for="user_id_<?php echo $reservation['reservation_id']; ?>" class="form-label">Guest Name</label>
                                <input type="text" class="form-control" id="user_id_<?php echo $reservation['reservation_id']; ?>" name="user_id" value="<?php echo $userNames[$userID]; ?>" readonly>
                            </div>

                            <!-- Home ID -->
                            <div class="mb-3">
                                <label for="home_id_<?php echo $reservation['reservation_id']; ?>" class="form-label">Boarding House</label>
                                <input type="text" class="form-control" id="home_id_<?php echo $reservation['reservation_id']; ?>" name="home_id" value="<?php echo $homeName; ?>" readonly>
                            </div>

                            <!-- Check-In Date -->
                            <div class="mb-3">
                                <label for="check_in_date_<?php echo $reservation['reservation_id']; ?>" class="form-label">Check-In Date</label>
                                <input type="date" class="form-control" id="check_in_date_<?php echo $reservation['reservation_id']; ?>" name="check_in_date" value="<?php echo $reservation['check_in_date']; ?>">
                            </div>

                            <!-- Check-Out Date -->
                            <div class="mb-3">
                                <label for="check_out_date_<?php echo $reservation['reservation_id']; ?>" class="form-label">Check-Out Date</label>
                                <input type="date" class="form-control" id="check_out_date_<?php echo $reservation['reservation_id']; ?>" name="check_out_date" value="<?php echo $reservation['check_out_date']; ?>">
                            </div>

                            <!-- Total Price -->
                            <div class="mb-3" style="margin-bottom:10px;">
                                <label for="total_price_<?php echo $reservation['reservation_id']; ?>" class="form-label">Total Price</label>
                                <input type="number" class="form-control" id="total_price_<?php echo $reservation['reservation_id']; ?>" name="total_price" value="<?php echo $reservation['total_price']; ?>" step="0.01">
                            </div>
                            <!-- Tenant-->
                           <?php foreach ($tenants as $tenant): ?>
                                <div class="mb-3" style="margin-bottom:10px;">
                                    <label for="tenant_<?php echo htmlspecialchars($tenant['tenant_id']); ?>" class="form-label">Tenant</label>
                                    <input type="text" class="form-control" id="tenant_<?php echo htmlspecialchars($tenant['tenant_id']); ?>" 
                                           value="<?php echo htmlspecialchars($tenant['name']); ?>" readonly>
                                    <input type="hidden" name="tenant_id" value="<?php echo htmlspecialchars($tenant['tenant_id']); ?>">
                                </div>
                            <?php endforeach; ?>

                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php endforeach; ?>

  <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

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

