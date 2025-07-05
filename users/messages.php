<?php
include('../inc/db.php');
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User is not logged in, redirect to login page
    header("Location: login.php?");
    exit;
}


if(isset($_GET['tenant_id'])){
$tenant_id = $_GET['tenant_id']; 
$user_id = $_SESSION['user_id'];

$sql = "UPDATE messages 
        SET read_status = 1 
        WHERE receiver = ? AND read_status = 0";

// Prepare and execute the query
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

}
else{
  $tenant_id = 0;
}
$sender_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $message = $_POST['message'];

  if (!empty($message)) {
    $stmt = $con->prepare("INSERT INTO messages (receiver, sender, message, timestamp, read_status) VALUES (?, ?, ?, NOW(), 0)");
    $stmt->bind_param("iis", $tenant_id, $sender_id, $message);
    $stmt->execute();
    $stmt->close();
  }
}

$sql = "SELECT sender, message, timestamp FROM messages WHERE receiver = ? ORDER BY timestamp ASC";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
  $messages[] = $row;
}

$stmt->close();


// SQL query to fetch tenant details by tenant_id
$sql = "SELECT * FROM tenants WHERE tenant_id = ?";

// Prepare and execute the query
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $tenant_id); // Bind the tenant_id to the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the tenant details
$tenant = $result->fetch_assoc();

// Close the statement
$stmt->close();

$customer_id = $_SESSION['user_id'];

$sql = "SELECT DISTINCT tenants.* 
        FROM tenants
        JOIN reservations ON tenants.tenant_id = reservations.tenant_id
        JOIN messages ON (messages.sender = tenants.tenant_id OR messages.receiver = tenants.tenant_id)
        WHERE reservations.user_id = ?
        AND (messages.sender = ? OR messages.receiver = ?)";
        
// Prepare and execute the query
$stmt = $con->prepare($sql);
$stmt->bind_param("iii", $customer_id, $customer_id, $customer_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all tenant details
$tenants = $result->fetch_all(MYSQLI_ASSOC);

// Close the statement
$stmt->close();



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messenger Page</title>
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="SunRise construction & Builder company">
<meta name="keywords" content="construction, html, template, responsive, corporate">
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

  <style>
    body {
      background-color: #f8f9fa;
    }
    .chat-input {
  display: flex;
  width: 100%;
}

li:hover{
background-color:#5B99C2 ;
}

.chat-input .form-control {
  flex-grow: 1;
}

.chat-input .btn {
  flex-shrink: 0;
  width: auto;
}

    .chat-container {
      height: 100vh;
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
      margin-top: 3px;
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
       height: 300px;
      }
    }
  </style>
</head>
<body style="background-color:white;">
  <div class="outer_header">
    
  </div>
  <?php include('users_header.php');?>

  <div class="container-fluid chat-container" style="margin-top: 20px;">
    <div class="row">
      <!-- Sidebar for Tenants -->
      <div class="col-md-3 col-sm-12 sidebar" style="background-color:#0F67B1;">
        <h3 class="pt-3" align="center" style="color:white;">Boarding House Owner</h3>
        <ul class="list-group" style=" font-size: 20px;">
          <?php foreach ($tenants as $tenants): ?>
            <li class="list-group-item">
              <a href="messages.php?tenant_id=<?= $tenants['tenant_id'] ?>" class="text-decoration-none" style="color: black;">
                <?= htmlspecialchars($tenants['name']) ?>
                <span class="badge" id="<?php echo $tenants['tenant_id'];?>"></span>
                    </a>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <!-- Main Chat Area -->
      <div class="col-md-9 col-sm-12" id="message-box">
        <!-- Chat Header -->
        <div class="row py-3 bg-primary text-white">
          <div class="col-12">
            <h3 align="center" style="color:white;"><?php if($tenant){ echo $tenant['name'];}else{echo '....';} ?></h3>
          </div>
        </div>

        <!-- Chat Box -->
        <div class="chat-box" id="chatBox" style="height: 500px;">
          <?php foreach ($messages as $message): ?>
            <div class="message">
              <?php if ($message['sender'] == $_SESSION['user_id']): ?>
                <div class="sent">
                  <strong>You:</strong> <?= htmlspecialchars($message['message']) ?>
                </div>
              <?php else: ?>
                <div class="received">
                  <strong><?= htmlspecialchars($tenant['name']) ?>:</strong> <?= htmlspecialchars($message['message']) ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Chat Input -->
    <?php if($tenant): ?>
    <form id="chatForm" method="POST">
      <div class="input-group chat-input">
        <input type="text" class="form-control" id="chatMessage" name="message" placeholder="Type a message..." required>
        <button class="btn btn-primary" type="submit">Send</button>
      </div>
    </form>
  <?php endif;?>
  </div>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Fetch messages via AJAX
    function fetchMessages() {
      const chatBox = document.getElementById('chatBox');
      const tenantId = "<?= $tenant_id ?>";

      fetch('fetch_messages.php?tenant_id=' + tenantId)
        .then(response => response.json())
        .then(messages => {
          chatBox.innerHTML = ''; // Clear current messages
          messages.forEach(message => {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message');
            
            if (message.sender == <?= $_SESSION['user_id'] ?>) {
              messageDiv.innerHTML = `<div class="sent">${message.message}</div>`;
            } else {
               messageDiv.innerHTML = `<div class="received"><strong><?= $tenant['name'] ?>:</strong> ${message.message}</div>`;
            }
            chatBox.appendChild(messageDiv);
          });
          chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the latest message
        });
    }

    // Send message via AJAX
    document.getElementById('chatForm').addEventListener('submit', function(event) {
      event.preventDefault();
      const messageInput = document.getElementById('chatMessage');
      const message = messageInput.value.trim();

      if (message !== '') {
        fetch('', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ message })
        }).then(() => {
          messageInput.value = ''; // Clear input field
          fetchMessages(); // Refresh messages after sending
        });
      }
    });

    // Automatically fetch new messages every 5 seconds
    setInterval(fetchMessages, 1000);

    // Fetch messages on page load
    window.onload = fetchMessages;
  </script>

</body>
</html>
