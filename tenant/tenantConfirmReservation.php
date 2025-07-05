<?php
# Include the database connection file
include("../inc/db.php");

// Include PHPMailer
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
    exit();
}

# Check if the 'id' parameter is set in the URL
if (isset($_GET['id']) AND isset($_GET['user_id'])) {
    $reservationId = $_GET['id'];
    $user_ID = $_GET['user_id'];

    # SQL query to update the reservation status to 'confirmed'
    $sqlUpdateReservation = "UPDATE reservations SET status = 'tenant_confirmed' WHERE reservation_id = ?";
    $stmt = $con->prepare($sqlUpdateReservation);

    if ($stmt) {
        $stmt->bind_param("i", $reservationId);

        # Execute the statement
        if ($stmt->execute()) {
            # Fetch the user's email address
            $sqlFetchEmail = "SELECT email FROM users WHERE user_id = ?";
            $stmtEmail = $con->prepare($sqlFetchEmail);

            if ($stmtEmail) {
                $stmtEmail->bind_param("i", $user_ID);
                $stmtEmail->execute();
                $stmtEmail->bind_result($userEmail);
                $stmtEmail->fetch();
                $stmtEmail->close();

                # Send confirmation email
                $mail = new PHPMailer\PHPMailer\PHPMailer();

                try {
                    # Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = '';
                    $mail->Password = '';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    # Recipients
                    $mail->setFrom('', 'Tueogan');
                    $mail->addAddress($userEmail);

                    # Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Reservation Confirmation';
                    $mail->Body = 'Dear Guest, Your reservation with Tueogan has been confirmed. Please pay the downpayment to our tenants to avoid cancellation of reservation. Thank you!';

                    $mail->send();
                    echo 'alert("localhost says:\nConfirmation email has been sent.");';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                # Redirect back to view_reservations.php after successful update
                header("Location: view_tenantConfirmReservation.php");
                exit();
            } else {
                # Handle error if fetching email fails
                echo "Error fetching user email: " . $stmtEmail->error;
            }
        } else {
            # Handle error if update fails
            echo "Error updating reservation status: " . $stmt->error;
        }

        # Close the statement
        $stmt->close();
    } else {
        # Handle error if the statement preparation fails
        echo "Error preparing statement: " . $con->error;
    }
} else {
    # Handle the case where 'id' parameter is not set in the URL
    echo "Invalid request.";
}
?>
