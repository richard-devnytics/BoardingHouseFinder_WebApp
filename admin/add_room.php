<?php
include("../inc/db.php");

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}
$editError="";
// Initialize variables
$homeId = $_POST['home_id'];

// Retrieve form data
$added_room = $_POST["room_added"];
$available_room=$_POST['available_room'];
$total_room=$_POST["total_room"];

$updatedAvailableRoom=$added_room + $available_room;
$updatedTotalRoom=$added_room + $total_room;

  $updateQuery = "UPDATE holiday_homes SET
                total_rooms=?,
                available_rooms=?
                WHERE home_id = ?";
if ($stmt = $con->prepare($updateQuery)) {
                $stmt->bind_param("iii", $updatedTotalRoom, $updatedAvailableRoom, $homeId);

                if ($stmt->execute()) {
                    // Redirect back to view_holidayhomes.php after updating
                    header("Location: view_holidayhomes.php");
                    exit();
                } else {
                    $editError = "Error updating holiday home details: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $editError = "Error preparing the update statement: " . $con->error;
            }



if (isset($editError)) {
    echo "<div class='alert alert-danger'>$editError</div>";
}
?>
