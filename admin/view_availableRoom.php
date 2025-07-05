<?php
# database
include("../inc/db.php");

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

# Fetch room data from the database
$sqlHolidayHomes = "SELECT * FROM holiday_homes WHERE availability_status='available'";
$resultHolidayHomes = $con->query($sqlHolidayHomes);

# Check if there are any rooms
if ($resultHolidayHomes->num_rows > 0) {
    $holidayHomes = $resultHolidayHomes->fetch_all(MYSQLI_ASSOC);
} else {
    // No rooms found
    $holidayHomes = array();
}

# Fetch tenant data from the database
$sqlTenants = "SELECT tenant_id, name FROM tenants";
$resultTenants = $con->query($sqlTenants);

# Check if there are any tenants
if ($resultTenants->num_rows > 0) {
    $tenants = $resultTenants->fetch_all(MYSQLI_ASSOC);
} else {
    // No tenants found
    $tenants = array();
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
    margin-top: 150px;
}

.modal-body {
    background-color: #FFEFD5;
    width: 900px;
    height: 470px;
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
  <?php include ("../admin/inc/admin_header.php"); ?>
 <main class="main-content">
    <div class="section-title">
      <h3 style="margin-top:10px; margin-bottom: -20px;">AVAILABLE <span>ROOMS</span></h3>
    </div>
        <div class="container-fluid">
            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#addModal" style="color: black; font-size: 20px;"><span  class="fas fa-plus-square"> Add Room</span></button>
            <!-- Display Reservations in a Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Boarding House Name</th>
                            <th>Tenant Name</th>
                            <th>Location</th>
                            <th>Available Room</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($holidayHomes as $home) : ?>
                            <tr>
                                <td><?php echo $home['name']; ?></td>
                                <?php
                                $tenant_ID = $home['tenant_id'];
                                $sql_Tenants = "SELECT name FROM tenants WHERE tenant_id=$tenant_ID";
                                $result_Tenants = $con->query($sql_Tenants);

                                // Check if there are any tenants
                                if ($result_Tenants->num_rows > 0) {
                                    $tenant_ = $result_Tenants->fetch_assoc();
                                } else {
                                    // No tenants found
                                    $tenant_ = array();
                                }
                                ?>
                                <td><?php
                                if (!empty($tenant_)) {
                                    echo htmlspecialchars($tenant_['name']);
                                } else {
                                    echo "No tenant found";
                                }
                                ?></td>

                                <td><?php echo $home['location']; ?></td>
                                <td align="center"><?php echo $home['available_rooms']; ?></td>
                                <td><?php echo $home['price']; ?></td>
                                <td align="center">

                                    <!-- Button to trigger the edit modal -->
                                    <button type="button" style="margin-right:10px;" class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#editModal_<?php echo $home['home_id']; ?>">
                                        Edit
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

    <!-- Modals for adding Boarding House -->
<!-- Add New Holiday Home Modal -->
<div class="modal fade <?php echo isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] ? 'dark-mode' : ''; ?>" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title" id="addModalLabel"><span class="fas fa-plus-square"></span> Add Boarding House</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Form -->
                <form method="POST" action="insert_holidayhome.php" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="add_name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="add_name" name="name" required>
                        </div>

                        <!-- Location -->
                        <div class="col-md-6 mb-3">
                            <label for="add_location" class="form-label">Location:</label>
                            <input type="text" class="form-control" id="add_location" name="location" required>
                        </div>
                    </div>

                    <div class="row">


                        <!-- Availability Status -->
                        <div class="col-md-6 mb-3">
                        <label for="add_availability_status" class="form-label">Status: </label>
                        <select class="form-control" id="add_availability_status" name="availability_status">
                        <option value="">~~SELECT~~</option>
                        <option value="available">Available</option>
                        <option value="not_available">Not Available</option>
                        </select>
                        </div>
                        <!-- Rating -->
                        <div class="col-md-6 mb-3">
                            <label for="add_rating" class="form-label">Rating:</label>
                            <input type="number" class="form-control" id="add_rating" name="rating" step="0.01" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Description -->
                        <div class="col-md-6 mb-3">
                            <label for="add_description" class="form-label">Description:</label>
                            <input type="text" class="form-control" id="add_description" name="description" required>
                        </div>

                        <!-- Image Upload -->
                        <div class="col-md-6 mb-3">
                            <label for="add_image" class="form-label">Image:</label>
                            <input type="file" class="form-control" id="add_image" name="image" accept="image/*" required>
                        </div>
                    </div>

                    <div class="row">

                        <!-- Price -->
                        <div class="col-md-6 mb-3">
                            <label for="add_price" class="form-label">Price:</label>
                            <input type="number" class="form-control" id="add_price" name="price" step="0.01" required>
                        </div>
                         <!-- Coordinates -->
                        <div class="col-md-6 mb-3">
                            <label for="add_coordinates" class="form-label">Coordinates:</label>
                            <div class="input-group" style="height: 10px;">
                                <input type="text" class="form-control" id="add_coordinates" name="coordinates" required readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="openMapPicker()">Pick Coordinates</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!--Tenant-->
                        <div class="col-md-6 mb-3" style="margin-top:-40px;">
                            <label for="add_tenant_id" class="form-label">Tenant:</label>
                            <select class="form-control" id="add_tenant_id" name="tenant_id">
                                <option value="">Select</option>
                                <?php foreach ($tenants as $tenant): ?>
                                    <option value="<?php echo htmlspecialchars($tenant['tenant_id']); ?>"><?php echo htmlspecialchars($tenant['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                        <!-- Buttons -->
                        <div align="center">
                            <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Add Boarding House</button>
                    </div>
                </form>
            </div>
    </div>
</div>



    <!-- Modals for editing reservations -->
    <?php foreach ($holidayHomes as $home) : ?>
        <div class="modal fade" id="editModal_<?php echo $home['home_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel_<?php echo $home['home_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-header">
                <h5 class="modal-title" align="center" id="editModalLabel_<?php echo $home['home_id']; ?>">
                    <i class="fas fa-address-book fa-2x"> Edit <?php echo $home['name']; ?></i>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Edit Form -->
                <form method="POST" action="update_holidayhome.php" enctype="multipart/form-data">
                    <!-- Hidden input to pass home_id to update_holidayhome.php -->
                    <input type="hidden" name="home_id" value="<?php echo $home['home_id']; ?>">

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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="hidden" class="form-control" id="add_tenant_id" name="tenant_id" value="<?php echo htmlspecialchars($home['tenant_id']); ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top:15px;">Save changes</button>
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

