<?php
include ('../inc/db.php');
$user_id = $_SESSION['user_id'];

$sql = "SELECT sender, COUNT(*) AS unread_count 
        FROM messages 
        WHERE receiver = ? AND read_status = 0 
        GROUP BY sender";

// Prepare and execute the query
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$unread_data = [];
while ($row = $result->fetch_assoc()) {
    $unread_data[] = [
        'sender' => $row['sender'],
        'unread_count' => $row['unread_count']
    ];
}

// Close the statement
$stmt->close();
$con->close();

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($unread_data);
?>
