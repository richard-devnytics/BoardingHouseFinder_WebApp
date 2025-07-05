<?php
// Include database connection
include('../inc/db.php'); // Make sure this file contains the code to connect to your database

// Set the upload directory
$uploadDirectory = "../assets/img/holiday-homes/";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the home ID from the form
    $home_id = $_POST['home_id'];

    // Check if the file was uploaded without errors
    if (isset($_FILES['permitFile']) && $_FILES['permitFile']['error'] == 0) {
        $fileTmpPath = $_FILES['permitFile']['tmp_name'];
        $fileName = $_FILES['permitFile']['name'];
        $fileSize = $_FILES['permitFile']['size'];
        $fileType = $_FILES['permitFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize the file name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Directory where the file is going to be placed
        $dest_path = $uploadDirectory . $newFileName;

        // Move the file to the specified directory
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            // File was successfully uploaded, insert the file path into the database
            $stmt = $con->prepare("UPDATE holiday_homes SET b_permit_path = ? WHERE home_id = ?");
            $stmt->bind_param('si', $dest_path, $home_id);

            if($stmt->execute()) {
                echo "The file was uploaded successfully and the database was updated.";
                // Redirect to the desired page after successful upload
                header("Location: view_tenantsHome.php");
            } else {
                echo "There was an error updating the database.";
            }
        } else {
            echo "There was an error moving the uploaded file.";
        }
    } else {
        echo "There was an error with the file upload.";
    }
}
?>

