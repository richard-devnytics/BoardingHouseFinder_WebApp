<?php
# Include the database connection file
include("../inc/db.php");

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
    exit();
}

# Check if the 'id' and 'home_id' parameters are set in the URL
if (isset($_GET['id']) && isset($_GET['home_id'])) {
    $reservationId = $_GET['id'];
    $home_ID = $_GET['home_id'];

    # Start a transaction
    $con->begin_transaction();

    try {
        # SQL query to delete the reservation
        $sqlDeleteReservation = "UPDATE reservations SET status='tenant_cancelled' WHERE reservation_id = ?";
        $stmt = $con->prepare($sqlDeleteReservation);

        if ($stmt) {
            $stmt->bind_param("i", $reservationId);

            # Execute the statement
            if ($stmt->execute()) {
                $stmt->close();

                # SQL query to update the availability_status of the holiday home
                $sqlUpdateHomeStatus = "UPDATE holiday_homes SET availability_status = 'available' WHERE home_id = ?";
                $stmt = $con->prepare($sqlUpdateHomeStatus);

                if ($stmt) {
                    $stmt->bind_param("i", $home_ID);

                    # Execute the statement
                    if ($stmt->execute()) {
                        # Commit the transaction
                        $con->commit();

                        # Redirect back to view_reservations.php after successful deletion and update
                        header("Location: view_tenantConfirmReservation.php");
                        exit();
                    } else {
                        throw new Exception("Error updating the home status: " . $stmt->error);
                    }

                    # Close the statement
                    $stmt->close();
                } else {
                    throw new Exception("Error preparing home status update statement: " . $con->error);
                }
            } else {
                throw new Exception("Error deleting the reservation: " . $stmt->error);
            }
        } else {
            throw new Exception("Error preparing reservation deletion statement: " . $con->error);
        }
    } catch (Exception $e) {
        # Rollback the transaction in case of error
        $con->rollback();
        echo $e->getMessage();
    }
} else {
    # Handle the case where 'id' or 'home_id' parameters are not set in the URL
    echo "Invalid request.";
}
?>
