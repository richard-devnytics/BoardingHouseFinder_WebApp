<?php
# Include the database connection file
include("../inc/db.php");
$noResult=False;
# Check if the tenant is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

# Fetch tenant data from the database
$sqlTenants = "SELECT tenants.*, subscription.status as sub_status 
                FROM tenants 
                LEFT JOIN subscription ON tenants.tenant_id = subscription.tenant_id";
$resultTenants = $con->query($sqlTenants);

# Check if there are any tenants
if ($resultTenants->num_rows > 0) {
    $tenants = $resultTenants->fetch_all(MYSQLI_ASSOC);
} else {
    // No tenants found
    $tenants = array();
    $noResult=True;
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | Owners' List</title>
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
<link href="../css/owl.css" rel="stylesheet">
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
    margin-top: 60px;
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
      <h3 style="margin-top:10px; margin-bottom: -20px;">LIST OF <span>OWNERS</span></h3>
    </div>
        <div class="container-fluid">
            <!-- Display Reservations in a Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Owner's Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Subscription Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tenants as $tenant) : ?>
                            <tr>
                                <td><?php echo $tenant['name'];?></td>
                                <td><?php echo $tenant['email']; ?></td>
                                <td><?php echo $tenant['phone']; ?></td>
                                <td><?php echo $tenant['sub_status'];?></td>
                                <td><?php if ($tenant['status']==='unblocked') {echo 'Active_Owner';}else{echo'Blocked';}?></td>
                                <td>

                                    <!-- Button to trigger the edit modal -->
                                    <button type="button" style="margin-left: 10px;" class="btn btn-primary" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#editModal_<?php echo $tenant['tenant_id']; ?>">
                                        Edit
                                    </button>
                                   <?php if ($tenant['status'] === 'pending'): ?>
                                  <a href="confirm_tenant.php?id=<?php echo $tenant['tenant_id']?>" class="btn btn-success" onclick="return confirm('Are you sure you want to confirm this owner?')">Confirm</a>
                                    <?php endif; ?>

                                    <?php if ($tenant['status'] === 'blocked' OR $tenant['status'] === ''): ?>
                                  <a href="unblock_tenant.php?id=<?php echo $tenant['tenant_id']?>" class="btn btn-success" onclick="return confirm('Are you sure you want to unblock this owner?')">Unblock</a>
                                    <?php endif; ?>

                                     <?php if ($tenant['status'] === 'unblocked'): ?>
                                  <a href="block_tenant.php?id=<?php echo $tenant['tenant_id']?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to block this owner?')">Block</a>
                                    <?php endif; ?>

                                     <?php if ($tenant['status'] === 'confirmed'): ?>
                                  <a href="block_tenant.php?id=<?php echo $tenant['tenant_id']?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to block this owner?')">Block</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($noResult === true): ?>
    <div class="readmore text-center">
        <button class="main-btn btn-1 btn-1e">No Pending Reservations</button>
    </div>
<?php endif; ?>
    </main>

    <!-- Modals for editing tenants -->
    <?php foreach ($tenants as $tenant) : ?>
        <div class="modal fade" id="editModal_<?php echo $tenant['tenant_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel_<?php echo $tenant['tenant_id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" align="center" id="editModalLabel_<?php echo $tenant['tenant_id']; ?>"><i class="fas fa-address-book fa-2x">    Edit Owner No. <?php echo $tenant['tenant_id']; ?></i></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Edit Form -->
                        <form method="POST" action="update_tenants.php">
                            <!-- Hidden input to pass tenant_id to update_reservation.php -->
                            <input type="hidden" name="tenant_id" value="<?php echo $tenant['tenant_id']; ?>">

                            <!-- User ID -->
                            <div class="mb-3">
                                <input type="hidden" class="form-control" id="tenant_id_<?php echo $tenant['tenant_id']; ?>" name="tenant_id" value="<?php echo $tenant['tenant_id']; ?>" readonly>
                            </div>


                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name_<?php echo $tenant['name']; ?>" class="form-label">Owner's Full Name</label>
                                <input type="text" class="form-control" id="name_<?php echo $tenant['tenant_id']; ?>" name="name" value="<?php echo $tenant['name']; ?>">
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email_<?php echo $tenant['email']; ?>" class="form-label">Owner's Email</label>
                                <input type="text" class="form-control" id="email_<?php echo $tenant['tenant_id']; ?>" name="email" value="<?php echo $tenant['email']; ?>">
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3" style="margin-bottom:10px;">
                                <label for="phone_<?php echo $tenant['phone']; ?>" class="form-label">Owner's Phone Number</label>
                                <input type="text" class="form-control" id="phone_<?php echo $tenant['phone']; ?>" name="phone" value="<?php echo $tenant['phone']; ?>">
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
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
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
 
    </script> 
<script src="../js/script.js"></script> 
<!--jquery end-->
</body>

</html>
