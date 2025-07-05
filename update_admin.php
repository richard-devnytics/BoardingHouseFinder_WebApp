<?php
include("inc/db.php");

$password = 'admin';
$password = hash('sha256', $password);

$sql = "INSERT INTO `admin`(`admin_name`, `admin_email`, `admin_password`) VALUES ('renc','renc@gmail.com','$password')";

// Assuming $con is your database connection
if ($con->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $con->error;
}

// Close the connection
$con->close();

?>
