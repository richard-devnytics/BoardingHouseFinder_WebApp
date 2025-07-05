<?php
// Ensure Composer autoloading 
require '../inc/db.php'; // Add your database connection file here

use GuzzleHttp\Client;

$client = new Client();

// Your Base64 encoded API key. Replace with your actual key.
$apiKey = ''; 

$tenant_id = isset($_GET['tenant_id']) ? $_GET['tenant_id'] : null; 
$sub_fee = isset($_GET['amount']) ? $_GET['amount'] : 0; // Default value

// Check if booking_id exists
if ($tenant_id) {
    // Prepare SQL query to fetch the user's email by joining 'reservations' and 'users' tables
    $sql = "SELECT tenants.email 
            FROM tenants
            WHERE tenant_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();

    // Check if email is fetched
    if (!$email) {
        // If no email is found, set a default email or handle error
        $email = 'default@example.com';
    }
} else {
    echo "Booking ID is missing.";
    exit();
}

// Prepare the request data for PayMongo
$requestData = [
    'data' => [
        'attributes' => [
            'send_email_receipt' => true, // Set to true to send an email receipt
            'email' => $email, // Use the email fetched from the database
            'show_description' => false,
            'show_line_items' => true,
            'payment_method_types' => ['card', 'gcash'], // Specify payment methods
            'line_items' => [
                [
                    'amount' => round($sub_fee * 100), // Amount in centavos (e.g., 10000 = 100.00 PHP)
                    'currency' => 'PHP',
                    'name' => "Subscription at Tueogan", // Name of your product
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment', // Specify the mode (e.g., payment)
            'success_url' => "https://tueogan.com/tenant/subscription.php?tenant_id=$owner_id&amount=$sub_fee", 
            'cancel_url' => "https://tueogan.com/tenant/subscription.php?success='false'", // Replace with your cancel URL
        ],
    ],
];

try {
    // Make a POST request to create a checkout session
    $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
        'json' => $requestData, // Use 'json' to automatically encode
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($apiKey . ':'), // Correct authorization header
        ],
        'verify' => false, // Disable SSL verification (not recommended for production)
    ]);

    // Decode the response
    $data = json_decode($response->getBody(), true);
    
    // Redirect the user to the checkout URL
    $checkoutUrl = $data['data']['attributes']['checkout_url'];
    header('Location: ' . $checkoutUrl);
    exit();

} catch (Exception $e) {
    // Handle error
    echo 'Failed to create checkout session: ' . $e->getMessage();
}

?>