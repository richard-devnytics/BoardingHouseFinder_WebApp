<?php
// Include the database connection file
include("../inc/db.php");

// Include PHPMailer
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $tenantId = $_GET['id'];

    // SQL query to update the tenant status to 'confirmed'
    $sqlUpdateTenant = "UPDATE tenants SET status = 'confirmed' WHERE tenant_id = ?";
    $stmtUpdate = $con->prepare($sqlUpdateTenant);

    if ($stmtUpdate) {
        $stmtUpdate->bind_param("i", $tenantId);

        // Execute the update statement
        if ($stmtUpdate->execute()) {
            // Fetch the tenant's email address
            $sqlFetchEmail = "SELECT email FROM tenants WHERE tenant_id = ?";
            $stmtEmail = $con->prepare($sqlFetchEmail);

            if ($stmtEmail) {
                $stmtEmail->bind_param("i", $tenantId);
                $stmtEmail->execute();
                $stmtEmail->bind_result($tenantEmail);
                $stmtEmail->fetch();
                $stmtEmail->close();

                // Send confirmation email to tenant
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = '';
                    $mail->Password = '';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('', 'Tueogan');
                    $mail->addAddress($tenantEmail);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Owner Registration Confirmed';
                    $mail->Body = 'Dear Owner,<br><br>Your registration with Tueogan has been confirmed. You may now login as an owner to our platform.<br><br>Thank you!<br>From: Tueogan-Admin';

                    $mail->send();
                    echo '<script>alert("Owner status updated and confirmation email sent."); window.location.href = "view_tenants.php";</script>';
                    exit();
                } catch (Exception $e) {
                    echo "Message could not be sent to owner. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } else {
            echo "Error updating owner status: " . $stmtUpdate->error;
        }

        $stmtUpdate->close();
    } else {
        echo "Prepare statement error: " . $con->error;
    }
} else {
    echo "Missing tenant ID parameter.";
}
?>
