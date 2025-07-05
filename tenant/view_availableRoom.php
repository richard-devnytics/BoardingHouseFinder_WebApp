<?php
# database
include("../inc/db.php");

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
    exit();
}

    $tenant_ID=$_SESSION['tenant_id'];


# Fetch holiday home data from the database
$sqlHolidayHomes = "SELECT room.*, holiday_homes.home_id, holiday_homes.name, holiday_homes.price
                    FROM room
                    LEFT JOIN holiday_homes ON room.home_id = holiday_homes.home_id
                    WHERE holiday_homes.tenant_id = $tenant_ID
                    AND holiday_homes.availability_status = 'available'";

$resultHolidayHomes = $con->query($sqlHolidayHomes);

# Check if there are any holiday homes
if ($resultHolidayHomes->num_rows > 0) {
    $holidayHomes = $resultHolidayHomes->fetch_all(MYSQLI_ASSOC);
} else {
    // No holiday homes found
    $holidayHomes = array();
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | Boarding House</title>
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
        color: black;
        text-align: center;
    }

    .table th {
        background-color: #ffa500;
        color: black;
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
    width: 900px;
    margin-top: 100px;
}

.modal-body {
    background-color: #FFEFD5;
    width: 900px;
    height: 420px;
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

.img_modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 80%; /* Full width */
  height: 80%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0,0,0,0.9); /* Black with opacity */
  margin-left: 15%;
  margin-top: 8%;
}
.modal-content {
  margin: auto;
  display: block;
  width: 78%;
  max-width: 95%;
  max-height: 95%;
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: white;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
  cursor: pointer;
}

.close:hover,
.close:focus {
  color: red;
  text-decoration: none;
  cursor: pointer;
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
    <div class="section-title">
      <h3 style="margin-top:10px; margin-bottom: -20px;">OUR <span>ROOMS</span></h3>
    </div>
        <div class="container-fluid">
            <!-- Display Reservations in a Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Boarding House Name</th>
                            <th>Room Number</th>
                            <th>Bedspace</th>
                            <th>Rental Fee</th>
                            <th>Room Photo</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($holidayHomes as $home) : ?>
                            <tr>
                                <td><?php echo $home['name']; ?></td>
                                <td><?php echo $home['room_id']; ?></td>
                                <td><?php echo $home['bedspace']; ?></td>
                                <td><?php echo'₱'.$home['price']; ?></td>
                               <td><img src="<?php echo '../images/room_images'.$home['image'];?>" width="100" height="50" alt="No Image" onclick="openModal('<?php echo '../images/room_images'.$home['image'];?>')"></td>
                                <td align="center">

                                    <!-- Button to trigger the edit modal -->
                                    <button type="button" style="margin-right:10px;" class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#editModal_<?php echo $home['room_id']; ?>">
                                        Edit
                                    </button>
                                    <button type="button" style="margin-right:10px;" class="btn btn-success" style="background-color: #16325B;" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#editModal_<?php echo $home['home_id']; ?>">
                                        Add Bedspace
                                    </button>
                                    <a href="delete_holidayhome?id=<?php echo $home['home_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

   
    <!-- Modals for editing room-->
    <?php foreach ($holidayHomes as $home) : ?>
        <div class="modal fade" id="editModal_<?php echo $home['room_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel_<?php echo $home['room_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-header">
                <h5 class="modal-title" align="center" id="editModalLabel_<?php echo $home['home_id']; ?>">
                    <i class="fas fa-address-book fa-2x"> Edit Room No. <?php echo $home['room_id']; ?></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Edit Form -->
                <form method="POST" action="update_room_details.php" enctype="multipart/form-data">
                    <!-- Hidden input to pass home_id to update_holidayhome.php -->
                    <input type="hidden" name="home_id" value="<?php echo $home['home_id']; ?>">

                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name_<?php echo $home['home_id']; ?>" class="form-label">Room Number:</label>
                            <input type="text" class="form-control" id="name_<?php echo $home['home_id']; ?>" name="room_id" value="<?php echo $home['room_id']; ?>" readonly>
                        </div>

                        <!-- Location -->
                        <div class="col-md-6 mb-3">
                            <label for="location_<?php echo $home['home_id']; ?>" class="form-label">Number of Bedspace</label>
                            <input type="text" class="form-control" id="location_<?php echo $home['home_id']; ?>" name="location" value="<?php echo $home['bedspace']; ?>" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Availability Status -->
                        <div class="col-md-6 mb-3">
                            <label for="availability_status_<?php echo $home['home_id']; ?>" class="form-label">Availability Status</label>
                            <input type="text" class="form-control" id="availability_status_<?php echo $home['home_id']; ?>" name="availability_status" value="<?php echo $home['status']; ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image_<?php echo $home['home_id']; ?>" class="form-label">Image</label>
                            <input type="file" value="<?php echo $home['image']; ?>" class="form-control" id="image_<?php echo $home['home_id']; ?>" name="image" accept="image/*">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Description -->
                        <div class="col-md-6 mb-3">
                           <label for="description_<?php echo $home['home_id']; ?>" class="form-label">Add Bedspace</label>
                            <input type="number" class="form-control" id="description_<?php echo $home['home_id']; ?>" 
                           name="bedspace_added" value="0" min="0" required oninput="this.value = Math.max(this.value, 1);">

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top:15px;">Save changes</button>
                </form>
            </div>
    </div>
</div>

    <?php endforeach; ?>

<div id="imageModal" class="img_modal">
  <span class="close" onclick="closeModal()">&times;</span>
  <img class="modal-content" id="modalImage">
</div>

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
<script src="../dist/color-switcher.js"></script> 
<script>
       

    function openMapPicker() {
        // Open map picker in a new window or modal
        window.open('map_picker.php', 'mapPickerWindow', 'width=600,height=400');
    }
    function openMapPicker2(inputId) {
    // Open map picker in a new window and pass the input field ID
    var mapPickerWindow = window.open('map_picker2.php?inputId=' + inputId, 'Map Picker', 'width=600,height=400');
}


  // Open the modal
  function openModal(imageSrc) {
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("modalImage");
    modal.style.display = "block";
    modalImg.src = imageSrc;
     console.log("Image source: " + imageSrc); 
  }

  // Close the modal
  function closeModal() {
    var modal = document.getElementById("imageModal");
    modal.style.display = "none";
  }


</script>


    </script> 
<script src="../js/script.js"></script> 
<!--jquery end-->
</body>

</html>

