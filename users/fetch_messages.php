<?php
include('../inc/db.php');

// Fetch tenant ID and sender ID from query parameters and session
$tenant_id = $_GET['tenant_id']; 
$sender_id = $_SESSION['user_id'];

// Query to fetch all messages between sender (user) and receiver (tenant)
$sql = "SELECT sender, message, timestamp FROM messages 
        WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) 
        ORDER BY timestamp ASC";

// Prepare the statement
$stmt = $con->prepare($sql);

// Bind parameters: user as sender, tenant as receiver and vice versa
$stmt->bind_param("iiii", $sender_id, $tenant_id, $tenant_id, $sender_id);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch all messages
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// Close the statement and connection
$stmt->close();
$con->close();

// Set the header for JSON response
header('Content-Type: application/json');

// Return the messages as JSON
echo json_encode($messages);
?>
