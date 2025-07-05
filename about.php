<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About TUEOGAN</title>
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
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>

        
       <style type="text/css">
   
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


        .container {
            width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #5a67d8;
            text-align: center;
        }
        h2 {
            margin-top: 20px;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px 0;
            background: #5a67d8;
            color: #fff;
        }
        .logo {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }
        .team-member {
            text-align: center;
            margin-bottom: 20px;
        }
        .team-member img {
            width: 150px; /* Adjust the size as needed */
            height: auto;
            border-radius: 50%; /* Makes images circular */
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            .container {
                width: 95%; /* Adjust width for smaller screens */
            }
        }
    </style>
</head>
<body style="background-color:white;">
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

<div class="container">
    
    <h1>Welcome to<br/></h1>
    <div class="logo">
        <img src="images/my_logo.png" style="background-color:#5a67d8; border-radius: 5px;">
    </div>
    <p align="center">We are a dedicated team committed to helping you find the perfect boarding house in Ibajay, Aklan. Our platform is designed with your needs in mind, offering an easy and efficient way to discover accommodations that suit your lifestyle and budget.</p>

    <p align="center">Our Team:</p>
    <div class="row">
        <div class="col-md-4 team-member">
            <img src="images/group/1.png" alt="Elijah Allen Parlade">
            <br><strong>Elijah Allen Parlade</strong><br><p style="margin-top: -7px;">Programmer</p><br>
            <p style="margin-top:-15px">Elijah is the technical mastermind behind TUEOGAN. With a passion for coding and a keen eye for detail, he ensures that our website runs smoothly and delivers a seamless user experience.</p>
        </div>
        <div class="col-md-4 team-member">
            <img src="images/group/2.png" alt="Feddie Villanueva">
            <br><strong>Feddie Villanueva</strong> <br><p style="margin-top: -7px;">Writer</p><br>
            <p style="margin-top:-15px">Feddie brings our content to life. His expertise in crafting engaging narratives helps us communicate essential information about each boarding house, making your search not just informative but enjoyable.</p>
        </div>
        <div class="col-md-4 team-member">
            <img src="images/group/3.png"  alt="Dannel Garcia and Christian Basister">
           <br><strong>Christian Basister and Dannel Garcia</strong> <br><p style="margin-top: -7px;">Designer</p><br>
            <p style="margin-top:-15px">Christian and Dannel are the creative forces behind our website’s aesthetic. Their design philosophy focuses on user-friendly layouts and visually appealing interfaces, ensuring that your journey through TUEOGAN is as pleasant as possible.</p>
        </div>
    </div>

    <p align="center">At TUEOGAN, we understand the challenges of finding the right boarding house. That’s why we’ve combined our skills to create a reliable resource for residents and newcomers alike. Whether you’re a student, professional, or traveler, we’re here to help you find your ideal home away from home.</p>

    <p align="center">Thank you for choosing TUEOGAN!</p>
</div>

<footer>
    <p>&copy; 2024 TUEOGAN. All Rights Reserved.</p>
</footer>

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
</body>
</html>
