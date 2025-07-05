<?php
# database
include("../inc/db.php");

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

# Initialize variables for form data
$name = $_POST["name"];
$location = $_POST["location"];
$coordinates= $_POST["coordinates"];
$availability_status = $_POST["availability_status"];
$description = $_POST["description"];
$rating = $_POST["rating"];
$price = $_POST["price"];
$tenant_id=$_POST['tenant_id'];

# File Upload Configuration
$uploadDirectory = "../assets/img/holiday-homes/";
$allowedFileTypes = array('jpg', 'jpeg', 'png', 'gif');
$maxFileSize = 5 * 1024 * 1024;

# Check if the file input field is set
if (isset($_FILES["image"])) {
    $file = $_FILES["image"];

    # Check if the file upload is successful
    if ($file["error"] === UPLOAD_ERR_OK) {
        $fileName = $file["name"];
        $fileSize = $file["size"];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        # Check if the file type is allowed
        if (in_array($fileType, $allowedFileTypes)) {
            # Check if the file size is within limits
            if ($fileSize <= $maxFileSize) {
                $uploadPath = $uploadDirectory . $fileName;

                # Move the uploaded file to the specified directory
                if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
                    # Insert the new holiday home into the database
                    $insertQuery = "INSERT INTO holiday_homes (name, location,coordinates, availability_status, description, rating, image_path, price, tenant_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $con->prepare($insertQuery);

                    if ($stmt) {
                        $stmt->bind_param("sssssdsdi", $name, $location, $coordinates, $availability_status, $description, $rating, $uploadPath, $price, $tenant_id);

                        if ($stmt->execute()) {
                            # Redirect back to view_holidayhomes.php after successful insertion
                            header("Location: view_holidayhomes.php");
                            exit();
                        } else {
                            # Handle error if insertion fails
                            echo "Error inserting the holiday home: " . $stmt->error;
                        }

                        # Close the statement
                        $stmt->close();
                    } else {
                        # Handle error if the statement preparation fails
                        echo "Error preparing statement: " . $con->error;
                    }
                } else {
                    echo "Error uploading the file.";
                }
            } else {
                echo "File size exceeds the allowed limit (5MB).";
            }
        } else {
            echo "Invalid file type. Allowed types: jpg, jpeg, png, gif.";
        }
    } else {
        echo "Error uploading the file: " . $file["error"];
    }
} else {
    echo "No file selected.";
}
?>