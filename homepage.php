<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teuogan | Homepage</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Reset some default browser styles */
body, h1, form, input, button, label {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* General styles for the body */
body {
    font-family: Arial, sans-serif;
    height: 100vh;
    overflow: hidden;
    margin: 0;
    color: black;
}

/* Video container to ensure the video covers the entire background */
.video-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -1;
}

video#background-video {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: -1;
    transform: translate(-50%, -50%);
}

/* Container to center content */
.container {
    position: relative;
    background: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
    text-align: center;
    margin: auto;
    top: 60%;
    right:25%;
    transform: translateY(-50%);
}

/* Styles for the form elements */
form {
    margin-top: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background: #007BFF;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background: #0056b3;
}

/* Error message styling */
.error {
    color: red;
    margin-top: 10px;
}

/* Responsive styles */
@media (max-width: 600px) {
    .container {
        padding: 15px;
    }

    button {
        width: 100%;
        padding: 12px;
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
                    <a class="navbar-brand" href="your-homepage-url">
                        <img src="images/my_logo.png" alt="Logo" style="height: 80px; margin-top:-30px;">
                    </a>
                </div>

                <div class="navbar-collapse collapse clearfix">
                  <ul class="navigation clearfix">
                    <li><a class="hvr-link" href="home-page.php">Home</a>
                    </li>
                    <li class="dropdown"><a class="hvr-link" href="#">About</a>
                    </li>
                    <li><a class="hvr-link" href="users/search_.php">Rooms</a>
        <?php if(isset($_SESSION['name'])): ?>
            <li class="dropdown"><a class="hvr-link" href=""><?php echo htmlspecialchars($_SESSION['name']); ?></a>
              <ul>
                <li>
                 <a class="hvr-link" href="users/profile.php">My Profile</a> 
                </li>
                <li>
                 <a class="hvr-link" href="users/reservations.php">My Reservation</a> 
                </li>
                <li>
                  <a class="hvr-link" href="users/logout.php">Logout</a>
                </li>
              </ul>
            </li>
            <li><a class="hvr-link" href="#" id="openMessage"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>

        <?php elseif (isset($_SESSION['admin_name'])):?>
             <li class="dropdown"><a class="hvr-link" href=""><?php echo htmlspecialchars($_SESSION['admin_name']); ?></a>
              <ul>
                <li>
                 <a class="hvr-link" href="admin/admin_dashboard.php">Admin Dashboard</a> 
                </li>
                <li>
                 <a class="hvr-link" href="admin/admin_profile.php">Admin Profile</a> 
                </li>
                <li>
                  <a class="hvr-link" href="admin/admin_logout.php">Logout</a>
                </li>
              </ul>
            </li>
            <li><a class="hvr-link" href="#" id="openMessage"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>

        <?php elseif (isset($_SESSION['tenant_name'])):?>
             <li class="dropdown"><a class="hvr-link" href=""><?php echo htmlspecialchars($_SESSION['tenant_name']); ?></a>
              <ul>
                <li>
                 <a class="hvr-link" href="tenant/tenant_dashboard.php">Owner's Dashboard</a> 
                </li>
                <li>
                 <a class="hvr-link" href="tenant/tenant_profile.php">Owner's Profile</a> 
                </li>
                <li>
                  <a class="hvr-link" href="tenant/tenant_logout.php">Logout</a>
                </li>
              </ul>
            </li>
            <li><a class="hvr-link" href="#" id="openMessage"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>

        <?php else: ?>
            <li class="dropdown"><a class="hvr-link" href="">Login/Sign-Up</a>
              <ul>
                <li><a class="hvr-link" href="users/login.php">Login</a></li>
                  <li><a class="hvr-link" href="users/signup.php">Sign-Up</a></li>
                  <li><a class="hvr-link" href="tenant/tenant_signup.php">Register as Owner</a></li>
                  <li><a class="hvr-link" href="tenant/tenant_login.php">Login as Owner</a></li>
                  <li><a class="hvr-link" href="admin/admin_login.php">Login as Admin</a></li>
              </ul>
            </li>
        <?php endif; ?>
                  </ul>
                </div>
              </nav>
              <!--main-menu end--> 
  </header>
    <div class="video-container">
        <video autoplay muted loop id="background-video">
            <source src="../images/homepage.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    <div class="container">
        <a href="users/search_.php" class="btn-primary">Get Started</a>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</body>
</html>
