<?php
# Include the database connection file
include("../inc/db.php");
$noResult=False;
# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
    exit();
}

$tenant_ID=$_SESSION['tenant_id'];

# Fetch reservation data from the database
$sqlReservations = "SELECT reservations.*, room.room_id, room.bedspace
    FROM reservations
    INNER JOIN room ON reservations.room_id = room.room_id WHERE reservations.status IN ('admin_confirmed', 'tenant_confirmed') AND tenant_id=$tenant_ID";
$resultReservations = $con->query($sqlReservations);


# Check if there are any reservations
if ($resultReservations->num_rows > 0) {
    $reservations = $resultReservations->fetch_all(MYSQLI_ASSOC);
} else {
    // No reservations found
    $reservations = array();
    $noResult=True;
}


#Fetch user name
// Fetch user name
foreach ($reservations as $reservation) :
$userID = $reservation['user_id'];
endforeach;

// Prepare the SQL statement
$getname = $con->prepare("SELECT name, phone FROM USERS WHERE user_id = ?");

// Bind the parameter
$getname->bind_param("i", $userID);

// Execute the statement
$getname->execute();

// Get the result
$result = $getname->get_result();

// Fetch the user name (assuming there is only one row)
$userName = $result->fetch_assoc();

if($userName){
$name=$userName['name'];
$phone=$userName['phone'];
}

// Check if user name is fetched

// Close the statement
$getname->close();

?>

<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | Confirmed Reservations</title>
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
         text-align: center;
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
<?php include ("../tenant/inc/tenant_header.php"); ?>
 <main class="main-content">
    <div class="section-title">
      <h3 style="margin-top:10px; margin-bottom: -20px;">CONFIRMED <span>RESERVATIONS</span></h3>
    </div>
        <div class="container-fluid">
            <!-- Display Reservations in a Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tenant Name</th>
                            <th>Tenant Phone No.</th>
                            <th>Boarding House/Room</th>
                            <th>Moving-In Date</th>
                            <th>Occupy Until</th>
                            <th>Rental Fee</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation) : ?>
                            <tr>
                                <td><?php echo $name;?></td>
                                <td><?php echo $phone;?></td>
                                <?php 
                                $home_ID=$reservation['home_id'];
                                $sqlhome = "SELECT name FROM holiday_homes WHERE home_id=$home_ID";
                                $result_home = $con->query($sqlhome);
                                # Check if there are any reservations
                                if ($result_home->num_rows > 0) {
                                    $homes = $result_home->fetch_assoc();
                                } else {
                                    // No reservations found
                                    $homes = array();
                                }
                                foreach ($homes as $home_name):
                                    $homeName=$home_name;
                                endforeach;
                                ?>
                                <td><?php echo $homeName.' (Room '.$reservation['room_id'].')'; ?></td>
                                <td><?php echo $reservation['check_in_date']; ?></td>
                                <td><?php echo $reservation['check_out_date']; ?></td>
                                <td>Php <?php echo $reservation['total_price']; ?></td>
                                <td>

                                    <!-- Button to trigger the edit modal -->
                                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#editModal_<?php echo $reservation['reservation_id']; ?>">
                                        Edit
                                    </button>
                                    <a href="messages.php?customer_id=<?php echo $reservation['user_id'];?>" class="btn btn-primary" style="background-color: deepskyblue;">Chat Tenant</a>
                                    <a href="delete_confirmReservation.php?id=<?php echo $reservation['reservation_id']; ?>&home_id=<?php echo $home_ID; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this reservation?')">Cancel</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($noResult === true): ?>
    <div class="readmore text-center">
        <button class="main-btn btn-1 btn-1e">No Confirmed Reservations</button>
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

                            <!-- User Name -->
                            <div class="mb-3">
                                <label for="user_id_<?php echo $reservation['reservation_id']; ?>" class="form-label">Guest Name</label>
                                <input type="text" class="form-control" id="user_id_<?php echo $reservation['reservation_id']; ?>" name="user_id" value="<?php echo $name; ?>" readonly>
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

