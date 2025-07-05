<?php
include('../inc/db.php');
$tenant_id = $_GET['tenant_id']; 
$sender_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $message = $_POST['message'];

  if (!empty($message)) {
    $stmt = $con->prepare("INSERT INTO messages (receiver, sender, message, timestamp) VALUES (?, ?, ?, NOW())");
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

$tenant_id = $_GET['tenant_id']; // Get the tenant ID from the URL parameter

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

?>
<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Messenger Page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<style type="text/css">
    .chat-container {
      height: 80vh;
      display: flex;
      flex-direction: column;
      width: 90%;
    }
    .chat-box {
      flex-grow: 1;
      overflow-y: auto;
      padding: 15px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
    }
    .chat-input {
      margin-top: 10px;
      margin-bottom: 10px;
    }
</style>

</head>
<body>
<div class="page-wrapper"> 
  <!--preloader start-->
  <div class="preloader"></div>
  <!--preloader end--> 
  <!--main-header start-->
  <?php include("../users/users_header.php"); ?>

<div class="container chat-container">
    <div class="row py-3 bg-primary text-white">
      <div class="col-12">
        <h3><?php echo $tenant['name'];?></h3>
      </div>
    </div>

    <!-- Chat Box -->
    <div class="chat-box" id="chatBox" style="height: 300px; overflow-y: scroll;">
      <?php foreach ($messages as $message): ?>
        <div class="message">
          <?php if ($message['sender'] == $_SESSION['user_id']): ?>
            <div class="sent">
              <strong>You:</strong> <?= htmlspecialchars($message['message']) ?>
            </div>
          <?php else: ?>
            <div class="received">
              <strong>Rider:</strong> <?= htmlspecialchars($message['message']) ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Chat Input -->
    <form id="chatForm" method="POST">
      <div class="input-group chat-input">
        <input type="text" class="form-control" id="chatMessage" name="message" placeholder="Type a message..." required>
        <button class="btn btn-primary" type="submit">Send</button>
      </div>
    </form>
  </div>
  
  

<?php include("../footer.php"); ?>
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
              messageDiv.innerHTML = `<div class="received">${message.message}</div>`;
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
    setInterval(fetchMessages, 5000);

    // Fetch messages on page load
    window.onload = fetchMessages;

    </script> 
<script src="../js/script.js"></script> 
<!--jquery end-->
</body>

</html>