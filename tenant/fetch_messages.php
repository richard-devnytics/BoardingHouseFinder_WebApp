<?php
include('../inc/db.php');

// Fetch the sender and receiver from query params and session
$sender = $_GET['customer_id'];
$receiver = $_SESSION['tenant_id'];

// Query to fetch all messages between customer and tenant
$sql = "SELECT sender, message, receiver, timestamp FROM messages 
        WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
        ORDER BY timestamp ASC";

// Prepare the statement
$stmt = $con->prepare($sql);

// Bind parameters: customer as sender, tenant as receiver and vice versa
$stmt->bind_param("iiii", $sender, $receiver, $receiver, $sender);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch all the messages
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
