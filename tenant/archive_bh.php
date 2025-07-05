<?php
// database connection
include("../inc/db.php");

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
    exit();
}

// Check if the 'id' and 'tenant_ID' parameters are set in the URL
if (isset($_GET['id']) && isset($_GET['tenant_ID'])) {
    $homeId = intval($_GET['id']);
    $tenant_ID = intval($_GET['tenant_ID']);

    // SQL query to check reservation status
    $sqlCheckReservation = "SELECT total_rooms, available_rooms FROM holiday_homes WHERE home_id = ?";
    $stmtCheckReservation = $con->prepare($sqlCheckReservation);

    if ($stmtCheckReservation) {
        $stmtCheckReservation->bind_param("i", $homeId);
        $stmtCheckReservation->execute();
        $resultCheckReservation = $stmtCheckReservation->get_result();

        // Check if there are any holiday homes
        if ($resultCheckReservation->num_rows > 0) {
            $checkRoom = $resultCheckReservation->fetch_assoc();

            if ($checkRoom['total_rooms'] > $checkRoom['available_rooms']) {
                echo '<script>alert("Action could not be completed, as there is a pending or confirmed reservation of room/s associated with this Boarding House"); window.location.href = "view_tenantsHome.php";</script>';
                exit();
            } else {
                // SQL query to update the holiday home
                $sqlArchiveRoom = "UPDATE holiday_homes SET archive = 'True', availability_status = 'not_available' WHERE home_id = ? AND tenant_id = ?";

                $stmtArchiveRoom = $con->prepare($sqlArchiveRoom);

                if ($stmtArchiveRoom) {
                    // Bind parameters to the statement
                    $stmtArchiveRoom->bind_param("ii", $homeId, $tenant_ID);

                    // Execute the statement
                    if ($stmtArchiveRoom->execute()) {
                        // Redirect back to view_tenantsHome.php after successful update
                        header("Location: view_tenantsHome.php");
                        exit();
                    } else {
                        // Handle error if update fails
                        echo "Error updating the holiday home: " . $stmtArchiveRoom->error;
                    }

                    // Close the statement
                    $stmtArchiveRoom->close();
                } else {
                    // Handle error if the statement preparation fails
                    echo "Error preparing statement: " . $con->error;
                }
            }
        } else {
            // No holiday homes found
            echo "No holiday home found.";
        }

        // Close the statement
        $stmtCheckReservation->close();
    } else {
        // Handle error if the statement preparation fails
        echo "Error preparing statement: " . $con->error;
    }
} else {
    // Handle the case where 'id' and 'tenant_ID' parameters are not set in the URL
    echo "Invalid request.";
}
?>
