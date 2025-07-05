<?php
include '../inc/db.php';

if (isset($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']); // Sanitize the input

    $sqlUpdate = "UPDATE reservations SET payment_status = 'paid' WHERE reservation_id = ?";

    if ($stmt = $con->prepare($sqlUpdate)) {
        $stmt->bind_param("i", $reservation_id);
        
        if ($stmt->execute()) {
            // Payment status updated successfully, redirect to ongoing_rent.php with alert
            echo "<script>
                    alert('Payment status updated successfully!');
                    window.location.href = 'ongoing_rent.php';
                  </script>";
        } else {
            echo "Error updating payment status: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing query: " . $con->error;
    }
} else {
    echo "No reservation ID provided.";
}
?>
