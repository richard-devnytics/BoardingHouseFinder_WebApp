
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Header</title>
 <style>
  
    body {
   
  /* Adjust based on header height to prevent content from being hidden */

    }

    /* Header styling */
    .main-header {
      background-color: black;
      width: 100%;
      position: relative;
      top: 0;
      left: 0;
      z-index: 9000; /* Ensure header is on top */
    }

    .main-menu {
      display: flex;
      justify-content: center;
      background-color: rgb(116, 166, 98);
     
    }

    .nav__logo {
      font-size: 1.5rem;
      font-weight: bold;
      color: #333; /* Adjust as needed */
      text-decoration: none;
    }

    .nav__menu {
      display: flex;
      align-items: center;
    }

    .nav__list {
      display: flex;
      list-style: none;
    }

    .nav__item {
      margin-left: 2rem;
    }

    .nav__link {
      text-decoration: none;
      color: black; /* Adjust as needed */
      font-size: 1.6rem;
      transition: color 0.3s;
    }

    .nav__link:hover {
      color: white; /* Adjust as needed */
    }

    /* Button styles */
    .nav__toggle, .nav__close {
      display: none;
      font-size: 1.5rem;
      cursor: pointer;
      background-color: black; /* Adjust as needed */
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .nav__toggle:hover, .nav__close:hover {
      background-color: orange; /* Adjust as needed */
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .nav__menu {
        position: fixed;
        top: 0;
        right: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.95);
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
      }

      .nav__list {
        flex-direction: column;
      }

      .nav__item {
        margin: 1.5rem 0;
      }

      .nav__toggle {
        display: block;
      }

      .nav__menu.active {
        transform: translateX(0);
      }

      .nav__close {
        display: block;
        position: absolute;
        top: 1rem;
        right: 1.5rem;
      }
    }

    .badge {
    background-color: orange;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 16px;
    position: absolute;
    top: 27%;
    right: 25%;
    transform: translate(50%, -50%);
    border: 1px solid black;
    animation: bounce 2s infinite;
}
 @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translate(50%, -50%);
            }
            40% {
                transform: translate(50%, -5%);
            }
            60% {
                transform: translate(50%, -5%);
            }
        }


  .navbar-brand{
    animation: slideIn 2s ease-in-out;
  }
  @keyframes slideIn {
    from {
        transform: translateX(-100%);
    }
    to {
        transform: translateX(0);
    }
}

  </style>
</head>
<body>
 <header class="main-header">     
              <!--main-menu start-->
              <nav class="main-menu">
                <div class="navbar-header" style="align-content: center;">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                    </button>
                    <a class="navbar-brand" href="#">
                        <img src="../images/my_logo.png" alt="Logo" style="height: 80px; margin-top:-30px;">
                    </a>
                </div>
                <div class="navbar-collapse collapse clearfix">
                  <ul class="navigation clearfix">
                    <li><a class="hvr-link" href="../home-page.php">Home</a>
        <?php if(isset($_SESSION['tenant_name'])):?>
        <li><a class="hvr-link" href="../tenant/tenant_dashboard.php">DashBoard</a>
                    </li>

<?php
$servername = "localhost"; 
$username = "root";
$password = ""; 
$database = "tueogan";

// Creating a connection
$con = mysqli_connect($servername, $username, $password, $database);

// Checking the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


$noResult = false;

# Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['tenant_loggedin']) || $_SESSION['tenant_loggedin'] !== true) {
    header("Location: tenant_login.php");
    exit();
}

$tenant_ID = $_SESSION['tenant_id'];

# Fetch reservation data from the database
$sqlReservations = "SELECT COUNT(*) as count FROM reservations WHERE status = 'pending' AND tenant_id = $tenant_ID";
$resultReservations = $con->query($sqlReservations);

# Initialize the pendingCount variable
$pendingCount = 0;

# Check if there are any reservations
if ($resultReservations && $resultReservations->num_rows > 0) {
    $row = $resultReservations->fetch_assoc();
    $pendingCount = $row['count'];
} else {
    $noResult = true;
}

?>


                    <li class="dropdown"><a class="hvr-link" href="#">Reservation
                      <?php if($pendingCount>0): ?>
                      <span class="badge"><b> <?php echo $pendingCount; ?></b></span>
                    <?php endif; ?> 
                    </a>
                    <ul>
                <li>
                 <a class="hvr-link" href="../tenant/view_tenantReservations.php">Pending Reservation
                   <?php if($pendingCount>0): ?>
                      <span class="badge"><?php echo $pendingCount; ?></span>
                    <?php endif; ?> 
                 </a> 
                </li>
                <li>
                  <a class="hvr-link" href="../tenant/view_tenantConfirmReservation.php">Confirm Reservation</a>
                </li>
                 <li>
                  <a class="hvr-link" href="../tenant/view_tenantAllReservations.php">List of Reservation</a>
                </li>
              </ul>
                    </li>
                     <li><a class="hvr-link" href="../tenant/view_tenantsHome.php">Ongoing Rent</a></li>
                      <li><a class="hvr-link" href="../tenant/view_availableRoom.php">Rooms</a></li>
                    <li><a class="hvr-link" href="../tenant/view_tenantsHome.php">Boarding House</a></li>
                    <li><a class="hvr-link" href="../tenant/tenant_view_users.php">Guests</a>
                    <li><a class="hvr-link" href="../tenant/messages.php">Messages
                      <span class="badge" id="unreadCount"></span>
                    </a>
            <li class="dropdown"><a class="hvr-link" href=""><?php echo htmlspecialchars($_SESSION['tenant_name']); ?></a>
              <ul>
                <li>
                 <a class="hvr-link" href="../tenant/tenant_profile.php">My Profile</a> 
                </li>
                <li>
                 <a class="hvr-link" href="../tenant/subscription.php?owner_id=<?php echo $tenant_ID; ?>">My Subscription</a> 
                </li>
                <li>
                  <a class="hvr-link" href="../tenant/tenant_logout.php">Logout</a>
                </li>
              </ul>
            </li>
        <?php else: ?>
            <li class="dropdown"><a class="hvr-link" href="">Login/Sign-Up</a>
              <ul>
                <li><a class="hvr-link" href="../users/login.php">Login</a></li>
                  <li><a class="hvr-link" href="../users/signup.php">Sign-Up</a></li>
                  <li><a class="hvr-link" href="../tenant/tenant_signup.php">Register as Tenant</a></li>
                  <li><a class="hvr-link" href="../tenant/tenant_login.php">Login as Tenant</a></li>
                  <li><a class="hvr-link" href="../admin/admin_login.php">Login as Admin</a></li>
              </ul>
            </li>
        <?php endif; ?>
                  </ul>
                </div>
              </nav>
              <!--main-menu end--> 
  </header>
  <script type="text/javascript">
 function fetchUnreadCount() {
    fetch('fetch_unread_count.php?tenant_id=<?= $_SESSION['tenant_id'] ?>')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const unreadCountElem = document.getElementById('unreadCount');
            let totalUnreadCount = 0;

            if (data && Array.isArray(data)) {
                // Loop through the data and process unread count and sender info
                data.forEach(item => {
                    totalUnreadCount += parseInt(item.unread_count, 10);
                    // Check if the sender ID matches any customer in the list
                    const senderId = item.sender;

                    // Find the corresponding list item (li) for the sender
                    const listItem = document.getElementById(senderId); // Use senderId to match the list item ID
                    console.log(listItem+' '+senderId);
                    // If a matching customer exists, change the background color
                    if (listItem) {
                        listItem.innerText = "New Message";
                    }

                    console.log(`Sender: ${item.sender}, Unread Count: ${item.unread_count}`);
                });

                // Update the unread count badge
                if (totalUnreadCount > 0) {
                    unreadCountElem.innerText = totalUnreadCount;
                    unreadCountElem.style.display = 'inline'; // Show badge
                } else {
                    unreadCountElem.style.display = 'none'; // Hide badge if no unread messages
                }
            }
        })
        .catch(error => {
            console.error('Error fetching unread count:', error);
        });
}


setInterval(fetchUnreadCount, 1000);

  </script>
</body>
</html>
