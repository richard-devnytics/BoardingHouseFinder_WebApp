<?php
# Include the database connection file
include("../inc/db.php");

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

# Initialize variables
$tenantId = $_POST['tenant_id']; // Get reservation_id from the form

# Retrieve form data (add more fields as needed)

$name = $_POST["name"];
$phone = $_POST["phone"];
$email = $_POST["email"];

# Update the reservation in the database using prepared statements
$updateQuery = "UPDATE tenants SET
    name = ?,
    phone = ?,
    email = ?
    WHERE tenant_id = ?";

if ($stmt = $con->prepare($updateQuery)) {
    $stmt->bind_param("sssi", $name, $phone, $email, $tenantId);
    
    if ($stmt->execute()) {
        # Redirect back to view_reservations.php after updating
        header("Location: view_tenants.php");
        exit();
    } else {
        $editError = "Error updating tenants details: " . $stmt->error;
    }

    $stmt->close();
} else {
    $editError = "Error preparing the update statement: " . $con->error;
}
?>