<?php
include("../inc/db.php");
$noResult="True";
# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $keyword = $con->real_escape_string($keyword);
   
// Get the admin's name from the session
$adminName = $_SESSION['admin_name'];
# Fetch reservation data from the database if keyword is not empty

if(empty($keyword)){
# Fetch reservation data from the database
$sqlReservations = "SELECT h.name as home_name, u.name as user_name, u.email, r.reservation_id, 
                    r.check_in_date, r.check_out_date, r.status, r.transaction_date, r.total_price, t.tenant_id, t.name as tenant_name
                    FROM holiday_homes h
                    INNER JOIN reservations r ON h.home_id = r.home_id
                    INNER JOIN users u ON r.user_id = u.user_id
                    INNER JOIN tenants t ON r.tenant_id = t.tenant_id";

$resultReservations = $con->query($sqlReservations);
# Check if there are any reservations
if ($resultReservations->num_rows > 0) {
    $noResult="False";
    $reservations = $resultReservations->fetch_all(MYSQLI_ASSOC);
} else {
    // No reservations found
    $reservations = array();
}

}
else
{
$sqlReservations = "SELECT h.name as home_name, u.name as user_name, u.email, r.reservation_id, 
                    r.check_in_date, r.check_out_date, r.status, r.transaction_date, r.total_price, t.tenant_id, t.name as tenant_name
                    FROM holiday_homes h
                    INNER JOIN reservations r ON h.home_id = r.home_id
                    INNER JOIN users u ON r.user_id = u.user_id
                    INNER JOIN tenants t ON r.tenant_id = t.tenant_id
                    WHERE h.name like '%$keyword%' or u.name like '%$keyword%' or t.name like
                    '%$keyword%' or r.transaction_date like '%$keyword%' ";

$resultReservations = $con->query($sqlReservations);
# Check if there are any reservations
if ($resultReservations->num_rows > 0) {
    $noResult = "False";
    $reservations = $resultReservations->fetch_all(MYSQLI_ASSOC);
} else {
    // No reservations found
    $reservations = array();
}
}
}

else{
$sqlReservations = "SELECT h.name as home_name, u.name as user_name, u.email, r.reservation_id, 
                    r.check_in_date, r.check_out_date, r.status, r.transaction_date, r.total_price, t.tenant_id, t.name as tenant_name
                    FROM holiday_homes h
                    INNER JOIN reservations r ON h.home_id = r.home_id
                    INNER JOIN users u ON r.user_id = u.user_id
                    INNER JOIN tenants t ON r.tenant_id = t.tenant_id";

$resultReservations = $con->query($sqlReservations);
# Check if there are any reservations
if ($resultReservations->num_rows > 0) {
    $reservations = $resultReservations->fetch_all(MYSQLI_ASSOC);
} else {
    // No reservations found
    $reservations = array();
    $noResult="True";
}
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | List of Reservations</title>
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
        width: 97%;
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
        text-align: center;
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

.bloq-search{
    width: 30%;
    margin-left: 67%;
    z-index: 1000;
    margin-bottom: -30px;
    margin-top: 20px;
}


</style>

</head>
<body>
<?php include ("../admin/inc/admin_header.php"); ?>
 <main class="main-content">
    <div class="section-title">
      <h3 style="margin-top:10px; margin-bottom: -20px;">LIST OF <span>RESERVATIONS</span></h3>
      <div class="search">
        <form action="view_AllReservation.php" method="GET" class="bloq-search">
          <input type="text" name="keyword" placeholder="Enter Keyword...">
          <button class="button-33" role="button">Search</button>
        </form>
      </div>
    </div>
        <div class="container-fluid">
             <!-- Export Button -->
        <button id="exportButton" class="btn btn-success" style="margin-top:-30px;">Export to Excel</button>

            <!-- Display Reservations in a Table -->
            <div class="table-responsive">
                <table class="table table-bordered" id="reservationTable">
                    <thead>
                        <tr>
                            <th>Date Reserved</th>
                            <th>Guest Name</th>
                            <th>Boarding House</th>
                            <th>Owner</th>
                            <th>Check-In Date</th>
                            <th>Check-Out Date</th>
                            <th>Total Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation) : ?>
                            <tr>
                                <td><?php echo $reservation['transaction_date']; ?></td>
                                <td><?php echo $reservation['user_name']?></td>
                                <td><?php echo $reservation['home_name']; ?></td>
                                <td><?php echo $reservation['tenant_name']; ?></td>
                                <td><?php echo $reservation['check_in_date']; ?></td>
                                <td><?php echo $reservation['check_out_date']; ?></td>
                                <td>Php <?php echo $reservation['total_price']; ?></td>
                                <td><?php echo $reservation['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
      <?php if ($noResult == "True"): ?>
    <div class="readmore text-center">
        <button class="main-btn btn-1 btn-1e">No Reservation Found</button>
    </div>
<?php endif; ?>

    </main>

  <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>


<!-- jQuery and SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!--script for exporting into excell-->
<script>
    document.getElementById('exportButton').addEventListener('click', function() {
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.table_to_sheet(document.getElementById('reservationTable'));
        XLSX.utils.book_append_sheet(wb, ws, 'Reservations');
        XLSX.writeFile(wb, 'reservations.xlsx');
    });
</script>

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

