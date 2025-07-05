<?php
include ('../inc/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from the form
    $home_id = $_POST['home_id'];
    $room_id = $_POST['room_id'];
    $bedspace_added = intval($_POST['bedspace_added']);
    
    // Initialize image variable
    $imagePath = null;

    // Retrieve the current image from the database
    $sqlCurrentImage = "SELECT image FROM room WHERE home_id = ? AND room_id = ?";
    $stmtCurrent = $con->prepare($sqlCurrentImage);
    $stmtCurrent->bind_param("ii", $home_id, $room_id);
    $stmtCurrent->execute();
    $result = $stmtCurrent->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentImage = $row['image'];
    }

    // Check if an image file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../images/room_images/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is a valid image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $imagePath = basename($_FILES['image']['name']); // New image uploaded
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }

    // If no new image uploaded, retain the current image
    if (empty($imagePath)) {
        $imagePath = $currentImage; // Use the existing image from the database
    }

    // Prepare the SQL statement
    $sql = "UPDATE room SET bedspace = bedspace + ?, image = ? WHERE home_id = ? AND room_id = ?";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare($sql);
    $stmt->bind_param("isii", $bedspace_added, $imagePath, $home_id, $room_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Room updated successfully.";
    } else {
        echo "Error updating room: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $stmtCurrent->close();
    $con->close();

    // Redirect back to the previous page (or another page)
    header("Location: view_availableRoom.php"); // Change to your desired redirect page
    exit();
}
?>
