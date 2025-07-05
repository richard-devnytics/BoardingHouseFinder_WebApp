<?php
# database
include("../inc/db.php");

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
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
$tenant_ID=$_POST['tenant_ID'];
$admin_approved=$_POST['admin_approved'];

# File Upload Configuration
$uploadDirectory = "../assets/img/holiday-homes/";


// Define allowed file types and maximum file size
$allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
$maxFileSize = 5 * 1024 * 1024; // 5MB


// Check if both file input fields are set
if (isset($_FILES["image"]) && isset($_FILES["b_permit"])) {
    $file = $_FILES["image"];
    $b_permit_file = $_FILES["b_permit"];

    $files = [$file, $b_permit_file];
    $uploadPaths = [];

    foreach ($files as $file) {
        // Check if the file upload is successful
        if ($file["error"] === UPLOAD_ERR_OK) {
            $fileName = basename($file["name"]);
            $fileSize = $file["size"];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Check if the file type is allowed
            if (in_array($fileType, $allowedFileTypes)) {
                // Check if the file size is within limits
                if ($fileSize <= $maxFileSize) {
                    $uploadPath = $uploadDirectory . $fileName;

                    // Move the uploaded file to the specified directory
                    if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
                        $uploadPaths[] = $uploadPath;
                    } else {
                        echo "Error moving file: " . $fileName;
                        exit();
                    }
                } else {
                    echo "File size exceeds the allowed limit (5MB) for file: " . $fileName;
                    exit();
                }
            } else {
                echo "Invalid file type for file: " . $fileName . ". Allowed types: jpg, jpeg, png, gif, pdf.";
                exit();
            }
        } else {
            echo "Error uploading the file: " . $file["name"] . ". Error code: " . $file["error"];
            exit();
        }
    }

    // Check if both files have been uploaded successfully
    if (count($uploadPaths) === 2) {
        // Insert the new holiday home into the database
        $insertQuery = "INSERT INTO holiday_homes (name, location, coordinates, availability_status, description, rating, image_path, b_permit_path, price, tenant_id, admin_approved) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);

        if ($stmt) {
            $stmt->bind_param("sssssdsssis", $name, $location, $coordinates, $availability_status, $description, $rating, $uploadPaths[0], $uploadPaths[1], $price, $tenant_ID, $admin_approved);

            if ($stmt->execute()) {
                // Redirect back to view_tenantsHome.php after successful insertion
                 echo '<script>alert("Listing is pending for admin approval. We will notify you once approved."); window.location.href = "view_tenantsHome.php";</script>';
                exit();
            } else {
                // Handle error if insertion fails
                echo "Error inserting room: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            // Handle error if the statement preparation fails
            echo "Error preparing statement: " . $con->error;
        }
    } else {
        echo "Both files must be uploaded.";
    }
} else {
    echo "No files selected or file input names are incorrect.";
}
?>