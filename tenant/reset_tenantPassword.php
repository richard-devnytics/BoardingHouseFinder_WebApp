<?php
# Include the database connection file
include("../inc/db.php");

// Include PHPMailer
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

# Function to generate a random password
function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

# Function to send an email using PHPMailer
function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kaksbustedfuse@gmail.com';
    $mail->Password = 'imek bcvr zsyr bvju';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('kaksbustedfuse@gmail.com', 'Tueogan');
    $mail->addAddress($to);

    $mail->Subject = $subject;
    $mail->Body    = $message;

    return $mail->send();
}

# Check if the 'email' parameter is set in the URL
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    # SQL query to check if the email exists in the database
    $sqlCheckEmail = "SELECT * FROM tenants WHERE email = ?";
    $stmtCheckEmail = $con->prepare($sqlCheckEmail);

    if ($stmtCheckEmail) {
        $stmtCheckEmail->bind_param("s", $email);
        $stmtCheckEmail->execute();
        $stmtCheckEmail->store_result();

        # If the email exists, proceed to update the password and send email
        if ($stmtCheckEmail->num_rows > 0) {
            # Generate a new random password
            $newPassword = generateRandomPassword();
            $hashedPassword = hash('sha256', $newPassword);

            # SQL query to update the user's password
            $sqlUpdatePassword = "UPDATE tenants SET password = ? WHERE email = ?";
            $stmtUpdatePassword = $con->prepare($sqlUpdatePassword);

            if ($stmtUpdatePassword) {
                $stmtUpdatePassword->bind_param("ss", $hashedPassword, $email);

                # Execute the statement
                if ($stmtUpdatePassword->execute()) {
                    # Send the new password to the user's email
                    $subject = "Change Password";
                    $message = "Dear Tenant, You attempted to reset your password at Tueogan. Please update your password in your profile after login. Your new password is: " . $newPassword;
                    if (sendEmail($email, $subject, $message)) {
                        echo '<script>alert("Password has been sent to your email."); window.location.href = "tenant_login.php";</script>';
                    } else {
                        echo '<script>alert("Error while sending password to your email."); window.location.href = "tenant_login.php";</script>';
                    }
                } else {
                    echo '<script>alert("Error updating the password."); window.location.href = "tenant_login.php";</script>';
                }

                # Close the statement
                $stmtUpdatePassword->close();
            } else {
                echo '<script>alert("Error preparing update statement."); window.location.href = "tenant_login.php";</script>';
            }
        } else {
            echo '<script>alert("Email not found in our database."); window.location.href = "tenant_login.php";</script>';
        }

        # Close the statement
        $stmtCheckEmail->close();
    } else {
        echo '<script>alert("Error preparing check statement."); window.location.href = "tenant_login.php";</script>';
    }
} else {
    echo '<script>alert("Invalid request."); window.location.href = "tenant_login.php";</script>';
}
?>
