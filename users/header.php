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
  </style>
</head>
<body>
 <header class="main-header">     
              <!--main-menu start-->
              <nav class="main-menu">
                <div class="navbar-header" style="align-content: center;">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                </div>
                <div class="navbar-collapse collapse clearfix">
                  <ul class="navigation clearfix">
                    <li><a class="hvr-link" href="../home-page.php">Home</a>
                      <li><a class="hvr-link" href="../users/search_.php">Rooms</a>
                        <li><a class="hvr-link" href="../users/about.php">About</a>
        <?php if(isset($_SESSION['name'])): ?>
          <li><a class="hvr-link" href="../users/about.php">Messaages</a></li>
            <li class="dropdown"><a class="hvr-link" href=""><?php echo htmlspecialchars($_SESSION['name']); ?></a>
              <ul>
                <li>
                 <a class="hvr-link" href="../users/profile.php">My Profile</a> 
                </li>
                <li>
                  <a class="hvr-link" href="../users/logout.php">Logout</a>
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
</body>
</html>
