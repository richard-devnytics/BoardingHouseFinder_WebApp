<?php
# database
include("../inc/db.php");

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
    exit();
}

# Initialize variables
$homeId = $_POST['home_id'];

# Retrieve form data (add more fields as needed)
$name = $_POST["name"];
$location = $_POST["location"];
$coordinates=$_POST['coordinates'];
$availability_status = $_POST["availability_status"];
$description = $_POST["description"];
$rating = $_POST["rating"];
$price = $_POST["price"];
$tenant_ID= $_POST['tenant_ID'];

# Check if a file was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpName = $_FILES['image']['tmp_name'];
    $fileType = $_FILES['image']['type'];

    // Check if the uploaded file is an image
    if (strpos($fileType, 'image') !== false) {
        // Generate a unique filename for the uploaded image
        $Filename = $_FILES['image']['name'];

        // Define the directory where the uploaded images will be stored
        $uploadDirectory = "../assets/img/holiday-homes/";

        $filePath = $uploadDirectory . $Filename;

        // Move the uploaded file to the image directory
        if (move_uploaded_file($fileTmpName, $filePath)) {
            // Update the image_path in the database
            $updateQuery = "UPDATE holiday_homes SET
                name = ?,
                location = ?,
                coordinates=?,
                availability_status = ?,
                description = ?,
                rating = ?,
                image_path = ?,
                price = ?,
                tenant_id = ?
                WHERE home_id = ?";

            if ($stmt = $con->prepare($updateQuery)) {
                $stmt->bind_param("sssssdsdii", $name, $location, $coordinates, $availability_status, $description, $rating, $filePath, $price, $tenant_ID, $homeId);

                if ($stmt->execute()) {
                    # Redirect back to view_holidayhomes.php after updating
                    header("Location: view_holidayhomes.php");
                    exit();
                } else {
                    $editError = "Error updating holiday home details: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $editError = "Error preparing the update statement: " . $con->error;
            }
        } else {
            $editError = "Error uploading the image.";
        }
    } else {
        $editError = "Invalid file type. Please upload an image.";
    }
} else {
    // File was not uploaded or an error occurred during upload, update other fields without changing the image
    $updateQuery = "UPDATE holiday_homes SET
        name = ?,
        location = ?,
        coordinates=?,
        availability_status = ?,
        description = ?,
        rating = ?,
        price = ?,
        tenant_id = ?
        WHERE home_id = ?";

    if ($stmt = $con->prepare($updateQuery)) {
        $stmt->bind_param("sssssddii", $name, $location, $coordinates, $availability_status, $description, $rating, $price, $tenant_ID, $homeId);

        if ($stmt->execute()) {
            # Redirect back to view_holidayhomes.php after updating
            header("Location: view_tenantsHome.php");
            exit();
        } else {
            $editError = "Error updating holiday home details: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $editError = "Error preparing the update statement: " . $con->error;
    }
}
?>