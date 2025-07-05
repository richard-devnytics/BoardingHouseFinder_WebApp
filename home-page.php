<?php
session_start();
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="Tueogan, an Online room Finder">
<meta name="keywords" content="Room Finder, html, template, responsive, boarding house">
<meta name="author" content="BSCS Students">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!-- Fav Icon -->
<link rel="shortcut icon" href="favicon.ico">
<!-- Style CSS -->
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="dist/color-default.css">
<link rel="stylesheet" href="dist/color-switcher.css">
<link href="css/magnific-popup.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<link href="css/owl.css" rel="stylesheet">
<link href="css/jquery.fancybox.css" rel="stylesheet">
<link href="css/style_slider.css" rel="stylesheet">
<link href="rs-plugin/css/settings.css" rel="stylesheet">
<link href="css/messaging.css" rel="stylesheet">

<style type="text/css">
   
        .tp-banner{
            width: 100%;
        }

        .logo img {
            width: 150px;
            height: 100px;
            transition:  margin-left 1s ease;
            animation: slide 5s ease; /* Smooth transition effect */
        }

        /* Define the keyframe animation */
          @keyframes slide {
            0% { margin-left: 0%; } /* Start at 0% */
            50% { margin-left: 70%; } /* Slide to 80% at halfway */
            100% { margin-left: 0%; } /* Slide back to 0% at end */
        }
         .logo {
            margin-left: 25%;
        }
        
        h2{
            color:white;
            font-size:40px;
        }

        .page-wrapper{
          background-color:black;
        }

        .main-menu{
        display: flex;
        justify-content: center;
        background-color: rgb(116, 166, 98);
        }
        span img {
            width: 90%;
            height: 100%;
        }

        img{
            height: 70%;
            width: 100%;
            object-fit: cover;
        }
        
        /* CSS for adjusting slider in mobile view */
@media only screen and (max-width: 700px) {
  /* Increase button size in mobile view */
  .tp-banner .main-btn {
    font-size: 60px; /* Larger font size for button */
    padding: 12px 20px; /* Default padding for button */
    border: 2px solid #ffffff; /* Add border to the button */
    border-radius: 5px; /* Optional: Add border radius */
    display: inline-block; /* Ensure button displays as a block element */
    text-decoration: none; /* Remove underline */
   background-color: rgba(255, 165, 0, 0.7);
   margin-top: 100px;
  }
}
}
</style>

</head>
<body>
<div class="page-wrapper"> 
  <!--preloader start-->
  <div class="preloader"></div>
  <!--preloader end--> 
  <!--main-header start-->
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
                    <li><a class="hvr-link" href="about.php">About</a>
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
  <!--main-header end--> 
  <!--slider start-->
  <div class="tp-banner-container sliderWraper" style="align-content: center;">
    <div class="tp-banner" style="margin-left">
      <ul>
        <li data-slotamount="7" data-transition="pop" data-masterspeed="1000" data-saveperformance="on">
    <img alt="" src="images/dummy.png" data-lazyload="images/color.png">
   
    <div class="caption lft large-title tp-resizeme slidertext1" data-x="center" data-y="-20" data-speed="600" data-start="2200">
        <span style="color: gold;">
            <img src="images/home_cover.png" alt="TUEOGAN ">
        </span>
    </div>
    <div class="caption lfb large-title tp-resizeme slidertext2" data-x="center" data-y="300" data-speed="600" data-start="2800">
    </div>
    <div class="caption lfl large-title tp-resizeme slidertext3" data-x="10" data-y="270" data-speed="600" data-start="3500">
        <a href="users/search_.php" class="btn-primary" style="color: brown;">Get Started</a>
    </div>
</li>

        <li data-slotamount="7" data-transition="pop" data-masterspeed="1000" data-saveperformance="on">
    <img alt="" src="images/dummy.png" data-lazyload="images/home_cover2.png">
   
    <div class="caption lft large-title tp-resizeme slidertext1" data-x="center" data-y="10" data-speed="600" data-start="2200">
        
    </div>
    <div class="caption lfb large-title tp-resizeme slidertext2" data-x="center" data-y="300" data-speed="600" data-start="2800">
    </div>
    <div class="caption lfl large-title tp-resizeme slidertext3" data-x="10" data-y="280" data-speed="600" data-start="3500">
        <a href="users/search_.php" class="btn-primary">Get Started</a>
    </div>
</li>
         <li data-slotamount="7" data-transition="pop" data-masterspeed="1000" data-saveperformance="on">
    <img alt="" src="images/dummy.png" data-lazyload="images/home_cover3.png">
    
    <div class="caption lft large-title tp-resizeme slidertext1" data-x="center" data-y="10" data-speed="600" data-start="2200">
    </div>
    <div class="caption lfb large-title tp-resizeme slidertext2" data-x="center" data-y="300" data-speed="600" data-start="2800">
    </div>
    <div class="caption lfl large-title tp-resizeme slidertext3" data-x="10" data-y="360" data-speed="600" data-start="3500">
        <a href="users/search_.php" class="btn-primary">Get Started</a>
    </div>
</li>
      </ul>
    </div>
  </div>
  <!--slider end-->
  <?php
  include ("footer.php");
  ?>
  
<!-- Message Modal -->
    <div id="chatModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="chatbox">
                <div class="messages" id="chatMessages">
                    <!-- Messages will appear here -->
                </div>
                <div class="input-group">
                    <input type="text" id="chatInput" placeholder="Type your message...">
                    <button id="sendButton">Send</button>
                </div>
            </div>
        </div>
    </div>

<!--Modal Script-->

<script>
        // Get the modal
        var modal = document.getElementById("chatModal");

        // Get the button that opens the modal
        var btn = document.getElementById("openMessage");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Add message to the chatbox
         // Function to add message to the chatbox
        function sendMessage() {
            var chatInput = document.getElementById("chatInput");
            var chatMessages = document.getElementById("chatMessages");
            if (chatInput.value.trim() !== "") {
                var message = document.createElement("div");
                message.textContent = chatInput.value;
                chatMessages.appendChild(message);
                chatInput.value = "";
                chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to the bottom
            }
        }

        // Add message on button click
        document.getElementById("sendButton").onclick = function() {
            sendMessage();
        }

        // Add message on Enter key press
        document.getElementById("chatInput").addEventListener("keyup", function(event) {
            if (event.keyCode === 13) { // Enter key code
                event.preventDefault();
                sendMessage();
            }
        });
    </script>


<!--about-info end--> 
<!--services start-->
<!--jquery start--> 
<script src="js/jquery-2.1.4.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/jquery.magnific-popup.min.js"></script> 
<script src="js/imagesloaded.pkgd.min.js"></script> 
<script src="js/isotope.pkgd.min.js"></script> 
<script src="js/jquery.fancybox8cbb.js?v=2.1.5"></script> 
<script src="js/owl.carousel.js"></script> 
<script src="rs-plugin/js/jquery.themepunch.tools.min.js"></script> 
<script src="rs-plugin/js/jquery.themepunch.revolution.min.js"></script> 
<script src="js/counter.js"></script> 
<script>
        var colorSheets = [  
     {
                color: "#fcb80b",
                title: "Switch to Default",
                href: "./dist/color-default.css"
            },
            {
                color: "#e41900",
                title: "Switch to Red",
                href: "./css/color-change-css/red.css"
            },
            {
                color: "#26a65b",
                title: "Switch to Green",
                href: "./css/color-change-css/green.css"
            },
            {
                color: "#2cabf5",
                title: "Switch to Blue",
                href: "./css/color-change-css/blue.css"
            },
            {
        color: "#05fff0",
                title: "Switch to Purple",
                href: "./css/color-change-css/purple.css"
                
            },
        {
                color: "#ff4229",
                title: "Switch to Orange",
                href: "./css/color-change-css/orange.css"
            }
        ];


        ColorSwitcher.init(colorSheets);


    </script> 
<script src="js/script.js"></script> 
<!--jquery end-->
</body>

</html>
