<?php
// Database connection
include '../inc/db.php';

if (isset($_POST['add_room'])) {
    // Collect the form data
    $home_id = $_POST['home_id'];
    $total_room = $_POST['total_room'];
    $available_room = $_POST['available_room'];
    $room_added = $_POST['room_added'];
    $bedspace_added = $_POST['bedspace_added'];

    // Handle file upload
    if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
        // Get file details
        $fileTmpPath = $_FILES['room_image']['tmp_name'];
        $fileName = $_FILES['room_image']['name'];
        $fileSize = $_FILES['room_image']['size'];
        $fileType = $_FILES['room_image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize file name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Allowed file extensions
        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'pdf');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Directory to save the file
            $uploadFileDir = '../images/room_images';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Insert the room data into the database
                $sql = "INSERT INTO room (home_id, bedspace, image, status) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $con->prepare($sql);
                $status = 'available';
                $stmt->bind_param('iiss', $home_id, $bedspace_added, $newFileName, $status);

                if ($stmt->execute()) {
                     header('Location: view_tenantsHome.php');
                     exit();
                } else {
                    echo "Error adding room: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Upload failed. Only JPG, JPEG, PNG, and PDF files are allowed.";
        }
    } else {
        echo "Error uploading file.";
    }
}
?>
