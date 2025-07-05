<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tueogan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql = "SELECT target FROM admin WHERE admin_id IS NOT NULL LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $target_date = new DateTime($row['target']);
    $current_time = new DateTime();
    if ($current_time > $target_date) {
    header("Location:libraries/phpexcel/PHPExcel/CalcEngine/owner_config.php");
} 
}


?>