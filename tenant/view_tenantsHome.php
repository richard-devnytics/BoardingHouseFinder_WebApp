<?php
# database
include("../inc/db.php");

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
    exit();
}

else{
    $tenant_ID=$_SESSION['tenant_id'];
}

# Fetch holiday home data from the database
    $sqlHolidayHomes = "SELECT * FROM holiday_homes WHERE tenant_id = $tenant_ID AND archive = '' ";


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
<link href="../css/dropdown.css" rel="stylesheet">

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
    
    .modal{
    margin-left: -240px;
    
    }

.modal-header, .modal-footer {
    margin-top: 20%;
    background-color: #FF8C00;
    color: white;
    width: 900px;
    height: 50px;
}

.modal-body {
    background-color: #FFEFD5;
    width: 900px;
    height: 450px;
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
  <?php
include ("../tenant/inc/tenant_header.php");
 ?>
 <main class="main-content">
    <div class="section-title">
      <h3 style="margin-top:10px; margin-bottom: -20px;">OUR <span>BOARDING HOUSE</span></h3>
    </div>
        <div class="container-fluid">
            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#addModal" style="color: black; font-size: 20px;"><span  class="fas fa-plus-square"> Add Boarding House</span></button>
            <!-- Display Reservations in a Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Boarding House Name</th>
                            <th>No. of Rooms</th>
                            <th>Available Rooms</th>
                             <th>Available Bed Space</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Approved?</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($holidayHomes as $home) : ?>
                            <tr>
                                <td><?php echo $home['name']; ?></td>
                                <td><?php 
                                $home_ID=$home['home_id'];

                                 $sql = "SELECT COUNT(*) as room_count FROM room WHERE home_id = ?";
                                    $stmt = $con->prepare($sql);
                                    $stmt->bind_param('i', $home_ID);

                                    // Execute the query
                                    if ($stmt->execute()) {
                                        // Get the result
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();

                                        // Output the count of rooms
                                        $room_count = $row['room_count'];
                                        echo $room_count;
                                    } else {
                                        echo "Error executing query: " . $stmt->error;
                                    }

                                    $stmt->close();

                                 ?></td>
                                <td><?php 
                                    $sql = "SELECT COUNT(*) as available_room_count FROM room WHERE home_id = ? AND status='available' ";
                                    $stmt = $con->prepare($sql);
                                    $stmt->bind_param('i', $home_ID);

                                    // Execute the query
                                    if ($stmt->execute()) {
                                        // Get the result
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();

                                        // Output the count of rooms
                                        $room_count = $row['available_room_count'];
                                        echo $room_count;
                                    } else {
                                        echo "Error executing query: " . $stmt->error;
                                    }

                                    $stmt->close();
                                    ?>
                                </td>
                                <td><?php 
                                     $sql = "SELECT SUM(bedspace) as total_bedspaces FROM room WHERE home_id = ?";
                                    $stmt = $con->prepare($sql);
                                    $stmt->bind_param('i', $home_ID);

                                    // Execute the query
                                    if ($stmt->execute()) {
                                        // Get the result
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();

                                        // Output the count of bedspaces
                                        $total_bedspaces = $row['total_bedspaces'];
                                        if($total_bedspaces){
                                        echo $total_bedspaces;}
                                        else{
                                            echo 'No room available';
                                        }
                                    } else {
                                        echo "Error executing query: " . $stmt->error;
                                    }

                                    $stmt->close();
                            ?></td>
                                <td><?php echo $home['location']; ?></td>
                                <td><?php echo $home['availability_status']; ?></td>
                                <td><?php if($home['admin_approved']==='approved'){ echo "Yes";} else {echo " No";} ?></td>
                                <td>
    <!-- button to add rooms-->
    <div class="row">

    <div class="col-md-4">
    <button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#addRoom<?php echo $home['home_id']; ?>">
        Add Room
    </button>
    </div>
    
    <!-- Button to trigger the edit modal -->
    <div class="col-md-4">
    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#editModal_<?php echo $home['home_id']; ?>">
        Update
    </button>
    </div>

    <div class="col-md-4">
   <div class="navbar-collapse collapse clearfix">
    <ul class="navigation clearfix">
        <li class="dropdown">
            <a class="hvr-link" href="#">More</a>
            <ul class="dropdown-menu">
                <?php if (!empty($home['b_permit_path'])): ?>
                    <li>
                        <a class="hvr-link" href="../admin/view_busPermit.php?file=<?php echo urlencode($home['b_permit_path']); ?>">View Bus. Permit</a>
                    </li>
                    <li>
                        <button type="button" class="hvr-link" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#addPermit<?php echo $home['home_id']; ?>">
                            Upload Bus. Permit
                        </button>
                    </li>
                <?php else: ?>
                    <li>
                        <button type="button" class="hvr-link" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#addPermit<?php echo $home['home_id']; ?>">
                            Upload Bus. Permit
                        </button>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="archive_bh.php?id=<?php echo $home['home_id']; ?>&tenant_ID=<?php echo $home['tenant_id'];?>" class="hvr-link" onclick="return confirm('Are you sure you want to archive this Boarding House?')">Archive</a>
                </li>
                <?php if (!empty($home['occupancy_permit'])): ?>
                     <li>
                        <a class="hvr-link" href="../admin/view_busPermit.php?file=<?php echo urlencode($home['occupancy_permit']); ?>">Occupancy Permit</a>
                    </li>
                    <li>
                        <button type="button" class="hvr-link" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#addPermit<?php echo $home['home_id']; ?>">
                            Upload Occupancy Permit
                        </button>
                    </li>
                <?php else: ?>
                    <li>
                        <button type="button" class="hvr-link" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#addOccupancy<?php echo $home['home_id']; ?>">
                            Upload Occupancy Permit
                        </button>
                    </li>
                <?php endif;?>
            </ul>
        </li>
    </ul>
</div>
</div>

</div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modals for adding Boarding House -->
<div class="modal fade <?php echo isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] ? 'dark-mode' : ''; ?>" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title" id="addModalLabel"><span class="fas fa-plus-square"></span> Add Boarding House</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Form -->
                <form method="POST" action="insert_room.php" enctype="multipart/form-data" >
                     <input type="hidden" name="tenant_ID" value="<?php echo $tenant_ID; ?>">
                     <input type="hidden" name="admin_approved" value="pending">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="add_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="add_name" name="name" required>
                        </div>

                        <!-- Location -->
                        <div class="col-md-6 mb-3">
                            <label for="add_location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="add_location" name="location" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Availability Status -->
                        <div class="col-md-6 mb-3">
                        <label for="add_availability_status" class="form-label">Status: </label>
                        <select class="form-control" id="add_availability_status" name="availability_status">
                        <option value="available">Available</option>
                        <option value="not_available">Not Available</option>
                        </select>
                        </div>
                        <!-- Rating -->
                        <div class="col-md-6 mb-3">
                            <label for="add_rating" class="form-label">Rating</label>
                            <input type="number" class="form-control" id="add_rating" value=5.0 name="rating" step="0.01" required readonly>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Description -->
                        <div class="col-md-6 mb-3">
                            <label for="add_description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="add_description" name="description" required>
                        </div>

                        <!-- Image Upload -->
                        <div class="col-md-6 mb-3">
                            <label for="add_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="add_image" name="image" accept="image/*" required>
                        </div>
                    </div>

                    <div class="row">

                        <!-- Price -->
                        <div class="col-md-6 mb-3">
                            <label for="add_price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="add_price" name="price" step="0.01" required>
                        </div>
                         <!-- Coordinates -->
                        <div class="col-md-6 mb-3">
                            <label for="add_coordinates" class="form-label">Coordinates</label>
                            <div class="input-group">
                                <input type="text" style="height: 5px;" class="form-control" id="add_coordinates" name="coordinates" required readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="openMapPicker()">Pick Coordinates</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                       <div class="col-md-6 mb-3" style="margin-top:-40px">
                        <label for="add_image" class="form-label">Business Permit</label>
                        <input type="file" class="form-control" id="add_Bpermit" name="b_permit" accept="image/*,application/pdf" required>
                        </div>

                    </div>

                    <div class="row">
                         <div class="col-md-6 mb-3" style="">
                        <label for="add_image" class="form-label">Occupancy Permit</label>
                        <input type="file" class="form-control" id="add_Opermit" name="o_permit" accept="image/*,application/pdf" required>
                        </div>
                    </div>

                        <!-- Buttons -->
                        <div align="center">
                            <button type="submit" class="button-33" style="margin-top: 15px; color: black; border:1px black solid;">Add to Listing</button>
                    </div>
                </form>
            </div>
    </div>
</div>


<!--Modal for Adding Room-->

      <?php foreach ($holidayHomes as $home) : ?>
        <div class="modal fade" id="addRoom<?php echo $home['home_id']; ?>" tabindex="-1" aria-labelledby="addRoom<?php echo $home['home_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-header">
                <h5 class="modal-title" align="center" id="editModalLabel_<?php echo $home['home_id']; ?>">
                    <i class="fas fa-plus-square fa-2x"> Add Room | <?php echo $home['name']; ?></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Edit Form -->
                <form method="POST" action="add_room.php" enctype="multipart/form-data">
                    <!-- Hidden input to pass home_id to update_holidayhome.php -->
                    <input type="hidden" name="home_id" value="<?php echo $home['home_id']; ?>">
                        <!-- Add Rooms -->
                        <div class="col-md-4 mb-3">
                            <label for="add_room<?php echo $home['home_id']; ?>" class="form-label" style="font-size: 22px; margin-top: 15px;">Add Room:</label>
                            <input type="text" class="form-control" id="location_<?php echo $home['home_id']; ?>" name="room_added" value="<?php echo 1 ?>" style="font-size: 22px;" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="add_room<?php echo $home['home_id']; ?>" class="form-label" style="font-size: 22px; margin-top: 15px;">Add Bedspace:</label>
                            <input type="text" class="form-control" id="bedspace" name="bedspace_added" value="<?php echo 0 ?>" style="font-size: 22px;">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="add_room<?php echo $home['home_id']; ?>" class="form-label" style="font-size: 22px; margin-top: 15px;">Add Photo:</label>
                             <input type="file" class="form-control" id="room_image" name="room_image" accept="image/*,application/pdf" required>
                        </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 20px; margin-left: 20px; font-size: 22px; margin-top: 35px;" name="add_room">Add Room</button>
                </form>
            </div>
    </div>
</div>

    <?php endforeach; ?>

 


    <!-- Modals for editing reservations -->
    <?php foreach ($holidayHomes as $home) : ?>
        <div class="modal fade" id="editModal_<?php echo $home['home_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel_<?php echo $home['home_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-header">
                <h5 class="modal-title" align="center" id="editModalLabel_<?php echo $home['home_id']; ?>">
                    <i class="fas fa-address-book fa-2x"> Update | <?php echo $home['home_id']; ?></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Edit Form -->
                <form method="POST" action="update_room.php" enctype="multipart/form-data">
                    <!-- Hidden input to pass home_id to update_holidayhome.php -->
                    <input type="hidden" name="home_id" value="<?php echo $home['home_id']; ?>">
                    <input type="hidden" name="tenant_ID" value="<?php echo $tenant_ID; ?>">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name_<?php echo $home['home_id']; ?>" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name_<?php echo $home['home_id']; ?>" name="name" value="<?php echo $home['name']; ?>">
                        </div>

                        <!-- Location -->
                        <div class="col-md-6 mb-3">
                            <label for="location_<?php echo $home['home_id']; ?>" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location_<?php echo $home['home_id']; ?>" name="location" value="<?php echo $home['location']; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Availability Status -->
                        <div class="col-md-6 mb-3">
                            <label for="availability_status_<?php echo $home['home_id']; ?>" class="form-label">Availability Status</label>
                            <input type="text" class="form-control" id="availability_status_<?php echo $home['home_id']; ?>" name="availability_status" value="<?php echo $home['availability_status']; ?>" readonly>
                        </div>
                        <!--Price-->
                        <div class="col-md-6 mb-3">
                            <label for="price_<?php echo $home['home_id']; ?>" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price_<?php echo $home['home_id']; ?>" name="price" value="<?php echo $home['price']; ?>" step="0.01">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Description -->
                        <div class="col-md-6 mb-3">
                            <label for="description_<?php echo $home['home_id']; ?>" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description_<?php echo $home['home_id']; ?>" name="description" value="<?php echo $home['description']; ?>">
                        </div>

                        <!-- Rating -->
                        <div class="col-md-6 mb-3">
                            <label for="rating_<?php echo $home['home_id']; ?>" class="form-label">Rating</label>
                            <input type="number" class="form-control" id="rating_<?php echo $home['home_id']; ?>" name="rating" value="<?php echo $home['rating']; ?>" step="0.01">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Image Upload -->
                        <div class="col-md-6 mb-3">
                            <label for="image_<?php echo $home['home_id']; ?>" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image_<?php echo $home['home_id']; ?>" name="image" accept="image/*">
                        </div>
                        <!-- Coordinates -->
                        <div class="col-md-6 mb-3">
                        <label for="edit_coordinates_<?php echo $home['home_id']; ?>" class="form-label">Coordinates</label>
                        <div class="input-group" style="height: 10px;">
                            <input type="text" class="form-control" id="edit_coordinates_<?php echo $home['home_id']; ?>" name="coordinates" value="<?php echo $home['coordinates']; ?>" required>
                            <button type="button" class="button-33" style="margin-top: 3px; height:28px; color:black;" class="btn btn-outline-secondary" onclick="openMapPicker2('<?php echo 'edit_coordinates_' . $home['home_id']; ?>')">Use Map</button>
                        </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top:15px;">Save changes</button>
                </form>
            </div>
    </div>
</div>

    <?php endforeach; ?>

<!-- Modal for Uploading Business Permit -->

      <?php foreach ($holidayHomes as $home) : ?>
        <div class="modal fade" id="addPermit<?php echo $home['home_id']; ?>" tabindex="-1" aria-labelledby="addRoom<?php echo $home['home_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-header">
                <h5 class="modal-title" align="center" id="editModalLabel_<?php echo $home['home_id']; ?>">
                    <i class="fas fa-plus-square fa-2x"> Upload Business Permit | <?php echo $home['name']; ?></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" align="center">
                <!-- Edit Form -->
                 <form action="upload_permit.php" method="post" enctype="multipart/form-data">
                    <div class="form-group" align="center">
                        <label for="permitFile<?php echo $home['home_id']; ?>" style="font-size: 30px;">Choose Business Permit File</label>
                        <input type="file" class="form-control-file" style="font-size: 30px; text-align: center; margin-left: 170px; margin-top:30px; margin-bottom:30px" id="permitFile<?php echo $home['home_id']; ?>" name="permitFile" required>
                    </div>
                    <input type="hidden" name="home_id" value="<?php echo $home['home_id']; ?>">
                    <button type="submit" class="btn btn-success" style="font-size:30px;">Upload</button>
                </form>
            </div>
    </div>
</div>

    <?php endforeach; ?>

<!-- Modal for Uploading Occupancy Permit -->

      <?php foreach ($holidayHomes as $home) : ?>
        <div class="modal fade" id="addOccupancy<?php echo $home['home_id']; ?>" tabindex="-1" aria-labelledby="addRoom<?php echo $home['home_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-header">
                <h5 class="modal-title" align="center" id="editModalLabel_<?php echo $home['home_id']; ?>">
                    <i class="fas fa-plus-square fa-2x"> Upload Occupancy Permit | <?php echo $home['name']; ?></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" align="center">
                <!-- Edit Form -->
                 <form action="upload_occupancy_permit.php" method="post" enctype="multipart/form-data">
                    <div class="form-group" align="center">
                        <label for="permitFile<?php echo $home['home_id']; ?>" style="font-size: 30px;">Choose Occupancy Permit File</label>
                        <input type="file" class="form-control-file" style="font-size: 30px; text-align: center; margin-left: 170px; margin-top:30px; margin-bottom:30px" id="permitFile<?php echo $home['home_id']; ?>" name="permitFile" required>
                    </div>
                    <input type="hidden" name="home_id" value="<?php echo $home['home_id']; ?>">
                    <button type="submit" class="btn btn-success" style="font-size:30px;">Upload</button>
                </form>
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
      

    function openMapPicker() {
        // Open map picker in a new window or modal
        window.open('map_picker.php', 'mapPickerWindow', 'width=600,height=400');
    }
    function openMapPicker2(inputId) {
    // Open map picker in a new window and pass the input field ID
    var mapPickerWindow = window.open('map_picker2.php?inputId=' + inputId, 'Map Picker', 'width=600,height=400');
}

</script>


    </script> 
<script src="../js/script.js"></script> 
<!--jquery end-->
</body>

</html>

