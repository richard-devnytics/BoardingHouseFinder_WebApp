<?php
include('../inc/db.php');
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    // User is not logged in, redirect to login page
    header("Location: tenant_login.php?");
    exit;
}
// Get tenant ID from session
$tenant_id = $_SESSION['tenant_id'];

if(!isset($_GET['customer_id'])){
  $_GET['customer_id']=0;
}
else{
  $tenant_id = $_SESSION['tenant_id'];

  $sql = "UPDATE messages 
        SET read_status = 1 
        WHERE receiver = ? AND read_status = 0";

// Prepare and execute the query
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$stmt->close();
}

// Handle form submission (tenant sending a message)

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['customer_id'])) {

  $message = $_POST['message'];
  $receiver_id = $_GET['customer_id']; // Assume this is coming from a hidden field or URL parameter

  if (!empty($message) && !empty($receiver_id)) {
    $stmt = $con->prepare("INSERT INTO messages (receiver, sender, message, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $receiver_id, $tenant_id, $message); // Sender is tenant, receiver is customer
    $stmt->execute();
    $stmt->close();
  }
}

// Fetch messages between tenant and a specific customer
if(isset($_GET['customer_id'])){
$customer_id = $_GET['customer_id']; // Get customer ID from URL

$sql = "SELECT sender, message, timestamp FROM messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY timestamp ASC";
$stmt = $con->prepare($sql);
$stmt->bind_param("iiii", $tenant_id, $customer_id, $customer_id, $tenant_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
  $messages[] = $row;
}

$stmt->close();
}



$sql = "SELECT DISTINCT users.name, users.user_id 
        FROM users 
        JOIN messages ON users.user_id = messages.sender 
        JOIN reservations ON users.user_id = reservations.user_id 
        WHERE (messages.receiver = ? OR messages.sender = ?) 
        AND reservations.tenant_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("iii", $tenant_id, $tenant_id, $tenant_id); 
$stmt->execute();
$result = $stmt->get_result();
$customers = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if(isset($_GET['customer_id'])){
$customer_id = $_GET['customer_id']; 
$sql = "SELECT name 
        FROM users 
        WHERE user_id =?";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $customer_id); 
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Owner Chat</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="Tueogan">
<meta name="keywords" content="boardinghouse, html, template, responsive, rooms">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!-- Fav Icon -->
<link rel="shortcut icon" href="../favicon.ico">
<!-- Style CSS -->
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
  <style>
     body {
      background-color: #f8f9fa;
    }

    #message-box{
      width: 100%;
    }


    li:hover{
background-color:#5B99C2 ;
}

    .chat-input {
  display: flex;
  width: 100%;
}

.chat-input .form-control {
  flex-grow: 1;
}

.chat-input .btn {
  flex-shrink: 0;
  width: auto;
}

    .chat-container {
      height: 70vh;
      display: flex;
      flex-direction: column;
    }
    .sidebar {
      background-color: #f8f9fa;
      border-right: 1px solid #dee2e6;
    }
    .chat-box {
      flex-grow: 1;
      overflow-y: auto;
      padding: 25px;
      background-color: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding-bottom: 50px;
    }
    .message {
      margin-bottom: 15px;
      clear: both;
    }
    .sent {
      background-color: #007bff;
      color: white;
      padding: 10px;
      border-radius: 15px;
      max-width: 70%;
      text-align: right;
      float: right;
      margin-bottom: 3px;
      margin-top: 5px;
      word-wrap: break-word; 
      overflow-wrap: break-word; 
      white-space: normal; 
    }
    .received {
      background-color: #f1f0f0;
      padding: 10px;
      border-radius: 15px;
      max-width: 70%;
      text-align: left;
      float: left;
      margin-top: 3px;
      margin-bottom: 5px;
      word-wrap: break-word; 
      overflow-wrap: break-word; 
      white-space: normal; 
    }
    .chat-input {
      margin-top: 10px;
      margin-bottom: 10px;
    }
    /* Media Queries for Mobile View */
@media (max-width: 768px) {
  .container-fluid {
    padding: 0;
  }

  .row {
    margin: 0;
  }

  .sidebar {
    width: 100%;
    height: auto;
    margin-bottom: 20px;
  }

  .col-md-9 {
    width: 100%;
  }

  .chat-box {
    height: 300px; /* Adjust for mobile */
  }

  /* Adjust chat input to take full width */
  .input-group {
    width: 100%;
    margin-top: 10px;
  }

  #chatMessage {
    width: 80%;
    margin-right: 10px;
  }

  button.btn-primary {
    width: 15%;
    font-size: 14px;
  }

  /* Mobile-friendly badge */
  .badge {
    font-size: 12px;
  }

  /* Reduce font size in sidebar */
  .list-group-item a {
    font-size: 14px;
  }
}

/* For smaller mobile devices (portrait mode) */
@media (max-width: 480px) {
  .sidebar {
    padding: 8px;
  }

  .list-group-item a {
    font-size: 12px;
  }

  .chat-box {
    height: 250px;
  }

  #chatMessage {
    font-size: 12px;
  }

  button.btn-primary {
    font-size: 12px;
  }
}
    @media (max-width: 768px) {
      .col-md-3 {
        flex: 0 0 100%;
        max-width: 100%;
      }
      .col-md-9 {
        flex: 0 0 100%;
        max-width: 100%;
      }
      .chat-box {
       padding-bottom: 80px;
      }
      
    }
  </style>
</head>
<body>
  <?php include('inc/tenant_header.php'); ?>
 
  <div class="container-fluid chat-container" style="margin-top: 20px;">
    <div class="row">
      <!-- Sidebar for Tenants -->
      <div class="col-md-3 col-sm-12 sidebar" style="background-color:#0F67B1;">
        <h3 class="pt-3" align="center" style="color:white;">Tenants</h3>
        <ul class="list-group" style=" font-size: 16px;">
          <?php foreach ($customers as $customer): ?>
            <li class="list-group-item">
              <a href="messages.php?customer_id=<?= $customer['user_id'] ?>" class="text-decoration-none" style="color: black;">
                <?= htmlspecialchars($customer['name']) ?>
                  <span class="badge" id="<?php echo $customer['user_id']; ?>"></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

       <div class="chat-container">
      <div class="col-md-9 col-sm-12" id="message-box">
        <!-- Chat Header -->
        <div class="row py-3 bg-primary text-white">
          <div class="col-12">
            <h3 align="center" style="color:white;"><?php if($user){ echo $user['name'];} else{echo '';} ?></h3>
          </div>
        </div>

        <!-- Chat Box -->
        <div class="chat-box" id="chatBox" style="height: 500px;">
          <?php if($_GET['customer_id'] > 0): ?>
          <?php foreach ($messages as $message): ?>
            <div class="message">
              <?php if ($message['sender'] == $_SESSION['tenant_id']): ?>
                <div class="sent">
                  <strong>You:</strong> <?= htmlspecialchars($message['message']) ?>
                </div>
              <?php else: ?>
                <div class="received" style="margin-bottom:3px;margin-top: 3px;">
                  <strong><?= htmlspecialchars($user['name']) ?>:</strong> <?= htmlspecialchars($message['message']) ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
        </div>
      </div>
    </div>
    </div>

    <!-- Chat Input -->
    <?php if ($user): ?>
    <form id="chatForm" method="POST">
      <div class="input-group chat-input">
        <input type="text" class="form-control" id="chatMessage" name="message" placeholder="Type a message..." required>
        <button class="btn btn-primary" type="submit">Send</button>
      </div>
    </form>
  <?php endif; ?>
  </div>

  <script type="text/javascript">
 




function fetchMessages() {
  const chatBox = document.getElementById('chatBox');
  const customerId = "<?= $customer_id ?>"; // Customer ID from PHP

  fetch('fetch_messages.php?customer_id=' + customerId)
    .then(response => response.json())
    .then(messages => {
      chatBox.innerHTML = ''; // Clear current messages
      messages.forEach(message => {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message');
        
        // Check if the message is sent by the tenant or the customer
        if (message.sender == <?= $tenant_id ?>) {
          messageDiv.innerHTML = `<div class="sent"><strong>You:</strong> ${message.message}</div>`;
        } else {
          messageDiv.innerHTML = `<div class="received"><strong><?= $customer['name'] ?>:</strong> ${message.message}</div>`;
        }
        chatBox.appendChild(messageDiv);
      });
      chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the latest message
    })
    .catch(error => {
      console.error("Error fetching messages:", error);
    });
}

// Send message
document.getElementById('chatForm').addEventListener('submit', function(event) {
  event.preventDefault();
  const messageInput = document.getElementById('chatMessage');
  const message = messageInput.value.trim();

  if (message !== '') {
    fetch('', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        message: message,
        customer_id: "<?= $customer_id ?>"
      })
    })
    .then(response => response.text())
    .then(data => {
      messageInput.value = ''; // Clear input field
      fetchMessages(); // Refresh messages after sending
    })
    .catch(error => {
      console.error("Error sending message:", error);
    });
  }
});

// Fetch messages every 5 seconds (adjust the interval as needed)
setInterval(fetchMessages, 1000);

  </script>
</body>
</html>
