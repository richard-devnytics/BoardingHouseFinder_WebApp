<?php
include '../inc/db.php';
if (isset($_GET['tenant_id']) && isset($_GET['amount'])) {
    // Sanitize the input
    $tenant_id = intval($_GET['tenant_id']);
    $amount = floatval($_GET['amount']);
    
    // Get the current date (start date)
    $start_date = date('Y-m-d');
    
    // Calculate the end date (1 month after the start date)
    $end_date = date('Y-m-d', strtotime('+1 month', strtotime($start_date)));
    
    // Set the status to 'active'
    $status = 'active';
    
    // Prepare the SQL query
    $sql = "INSERT INTO subscription (tenant_id, start_date, end_date, amount, status) 
            VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $con->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param("issds", $tenant_id, $start_date, $end_date, $amount, $status);
        
        // Execute the statement
        if ($stmt->execute()) {
             header("Location: subscription.php?tenant_id=$tenant_id");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing query: " . $con->error;
    }
}
?>