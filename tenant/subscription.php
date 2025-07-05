<?php
// Include the database connection
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
            echo "Subscription added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing query: " . $con->error;
    }
}



$owner_id = isset($_GET['owner_id']) ? $_GET['owner_id']: null ; 

// Prepare SQL query to fetch subscriptions for the specific owner
$sql = "SELECT holiday_homes.home_id, holiday_homes.tenant_id, SUM(room.bedspace) AS total_bedspaces
        FROM holiday_homes
        JOIN room ON room.home_id = holiday_homes.home_id
        WHERE holiday_homes.tenant_id = ?
        ";

// Prepare and execute the query
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize variables
$bedspaces = 0;
$amount = 0;

// Fetch the result and calculate total bedspaces and amount
if ($row = $result->fetch_assoc()) {
    $bedspaces = $row['total_bedspaces'];  // Sum of bedspaces
    $amount = 5 * $bedspaces;  // Calculate amount based on bedspaces
}
$active_subscription=false;

$subscriptions = [];
if (isset($_GET['tenant_id']) || isset($_GET['owner_id'])) {
    // Sanitize the tenant_id input
    $tenant_id = isset($_GET['tenant_id'])?intval($_GET['tenant_id']):$_GET['owner_id'];
    
    $sql = "SELECT * FROM subscription WHERE tenant_id = ?";
    
    if ($stmt = $con->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $tenant_id);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result set
        $result = $stmt->get_result();
        
        // Check if there are any rows
        if ($result->num_rows > 0) {
            // Fetch all records and store them in an array
            while ($row = $result->fetch_assoc()) {
                $subscriptions[] = $row;
                foreach($subscriptions as $sub){
                if($sub['status']=='active'){
                    $active_subscription=true;
                }
            }
            }
        } else {
            echo '<script>alert("You dont have an active subscription!");</script>';
        }
        
        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing query: " . $con->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Subscriptions</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link rel="stylesheet" href="../dist/color-default.css">
<link rel="stylesheet" href="../dist/color-switcher.css">
<link href="../css/magnific-popup.css" rel="stylesheet">
<link href="../css/animate.css" rel="stylesheet">
<link href=".//css/owl.css" rel="stylesheet">
<link href="../css/jquery.fancybox.css" rel="stylesheet">
<link href="../css/style_slider.css" rel="stylesheet">
<link href="../rs-plugin/css/settings.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!--buttonCSS-->
<link href="../css/button.css" rel="stylesheet">
<style type="text/css">
    th, td{
        text-align: center;
        color: black;
    }

</style>
</head>
<body>
   <?php include ("../tenant/inc/tenant_header.php");?>
<div class="container mt-5" style="margin-top:50px; margin-bottom:-30px;">
    <div style="display: flex; justify-content: flex-end;">
    <?php if ($active_subscription): ?>
    <script>
        alert("You already have an active subscription!");
    </script>
<?php endif; ?>

<a href="pay_subscription.php?amount=<?php echo $amount;?>&tenant_id=<?php echo $owner_id; ?>" 
   class="btn btn-primary" 
   style="margin-left: auto; margin-bottom:10px;"
   <?php echo $active_subscription ? 'onclick="return false;"' : ''; ?>>
   Pay Monthly Subscription
</a>
</div>
    <table class="table table-bordered">
        <thead>
            <tr><th colspan="5" align="center" style="font-size:20px;font-family: sans-serif; color:black;">Subscription Details</th></tr>
            <tr>
                <th>Subscription ID</th>
                <th>Subscription Amount</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subscriptions as $sub): ?>
                <tr>
                <td><?php echo $sub['subscription_id']; ?></td>
                <td><?php echo $sub['amount']; ?></td>
                <td><?php echo $sub['start_date']; ?></td>
                <td><?php echo $sub['end_date']; ?></td>
                <td><?php echo $sub['status']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!--jquery start--> 
<script src="../js/jquery-2.1.4.min.js"></script> 
<script src="../js/bootstrap.min.js"></script> 
<script src="../js/jquery.magnific-popup.min.js"></script> 
<script src="../js/imagesloaded.pkgd.min.js"></script> 
<script src="../js/isotope.pkgd.min.js"></script> 
<script src="../js/jquery.fancybox8cbb.js?v=2.1.5"></script> 
<script src="../js/owl.carousel.js"></script> 
<script src="../rs-plugin/js/jquery.themepunch.tools.min.js"></script> 
<script src="../rs-plugin/js/jquery.themepunch.revolution.min.js"></script> 
<script src="../js/counter.js"></script>  

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

?>
