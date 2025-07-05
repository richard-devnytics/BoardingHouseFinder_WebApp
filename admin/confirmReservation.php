<?php
# Include the database connection file
include("../inc/db.php");

// Include PHPMailer
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

# Check if the 'id' parameter is set in the URL
if (isset($_GET['id']) AND isset($_GET['user_id']) AND isset($_GET['tenant_id'])) {
    $reservationId = $_GET['id'];
    $user_ID = $_GET['user_id'];
    $tenant_ID = $_GET['tenant_id'];

    # SQL query to update the reservation status to 'confirmed'
    $sqlUpdateReservation = "UPDATE reservations SET status = 'admin_confirmed' WHERE reservation_id = ?";
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

                # Fetch the tenant's email address
                $sqlFetchTenantEmail = "SELECT email FROM tenants WHERE tenant_id = ?";
                $stmtTenantEmail = $con->prepare($sqlFetchTenantEmail);

                if ($stmtTenantEmail) {
                    $stmtTenantEmail->bind_param("i", $tenant_ID);
                    $stmtTenantEmail->execute();
                    $stmtTenantEmail->bind_result($tenantEmail);
                    $stmtTenantEmail->fetch();
                    $stmtTenantEmail->close();

                    # Send confirmation email to user
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
                        $mail->Body = 'Dear Guest,<br> Your reservation with Tueogan has been confirmed. Please pay the downpayment to our tenants to avoid cancellation of reservation. Thank you!';

                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent to user. Mailer Error: {$mail->ErrorInfo}";
                    }

                    # Send notification email to tenant
                    try {
                        # Recipients
                        $mail->clearAddresses();
                        $mail->addAddress($tenantEmail);

                        # Content
                        $mail->isHTML(true);
                        $mail->Subject = 'New Reservation Confirmed';
                        $mail->Body = 'Dear Owner,<br> A new reservation has been confirmed. Please check the details in your dashboard. Thank you!';

                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent to tenant. Mailer Error: {$mail->ErrorInfo}";
                    }

                    # Redirect back to view_reservations.php after successful update
                    header("Location: view_confirmReservation.php");
                    exit();
                } else {
                    # Handle error if fetching tenant email fails
                    echo "Error fetching tenant email: " . $stmtTenantEmail->error;
                }
            } else {
                # Handle error if fetching user email fails
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
