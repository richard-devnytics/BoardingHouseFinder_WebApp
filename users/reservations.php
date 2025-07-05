<?php
include("../inc/db.php");

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Fetch pending reservations for the logged-in user
$userId = $_SESSION['user_id'];
$query = "SELECT reservations.*, holiday_homes.name, holiday_homes.image_path, room.room_id, room.image
          FROM reservations 
          INNER JOIN holiday_homes ON reservations.home_id = holiday_homes.home_id
          INNER JOIN room ON reservations.room_id = room.room_id
          WHERE reservations.user_id = $userId AND reservations.status = 'pending'";
          
$result = $con->query($query);

$reservations = array();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}


//Fetch confirm reservations
$query1 = "SELECT reservations.*, holiday_homes.name, holiday_homes.image_path, room.room_id, room.image
          FROM reservations 
          INNER JOIN holiday_homes ON reservations.home_id = holiday_homes.home_id
          INNER JOIN room ON reservations.room_id = room.room_id
          WHERE reservations.user_id = $userId AND reservations.status IN('admin_confirmed', 'tenant_confirmed') AND reservations.payment_status !='paid' ";
$result2 = $con->query($query1);

$con_reservations = array();
if ($result2 && $result2->num_rows > 0) {
    while ($rows = $result2->fetch_assoc()) {
        $con_reservations[] = $rows;
    }
}
?>


<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | My Reservation</title>
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

<!--buttonCSS-->
<link href="../css/button.css" rel="stylesheet">

<style>

  @keyframes scaleUp {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

    
    i{
      color: orange;
    }

    .boardinghouse{
      margin-top: -100 px;
      margin-bottom: -60px;
      align: center;
    }

    .single-item {
      margin-top: 10px;
      margin-bottom: 60px;
      text-align: center;
      height: 420px; /* Adjusted fixed height for all boxes */
      width: 350px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border: 1px solid #ddd;
      padding: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      animation: scaleUp 1s ease-in-out forwards;
    }

    .single-item .fig_caption .icon {
      margin-bottom: 15px;
    }

    .single-item .fig_caption .icon img {
      max-width: 100%;
      height: auto;
      display: inline-block;
    }

    .single-item .details {
      padding: 10px;
      overflow: hidden;
    }

    .single-item .details h3 {
      margin-top: 0;
    }

    .single-item .details p {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      max-height: 60px; /* Adjust height as needed */
    }

    .serviceList {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
    }

    .col-md-4, .col-sm-6, .col-xs-12 {
      display: flex;
      align-items: stretch;
      justify-content: center;
      margin-bottom: 20px;
    }

     #cancel {
    position: absolute;
    top:380px;
    right: 110px;
    z-index: 10000;
    }
     #pay_online_btn {
    position: absolute;
    top:380px;
    right: 120px;
    z-index: 10000;
    width: 150px;
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
  <!--main-header end--> 
  <!--slider start-->
  
  <!--slider end-->
  
  
<!--about-info end--> 
<!--pending reservation start-->
<section class="services bg-gray" id=boardinghouse> 
  <!--container start-->
  <div class="container">
    <div class="section-title" style="margin-bottom: -15px;">
      <h3>Pending <span>Reservation</span></h3>
    </div>
    <!--row start-->
    <div class="row serviceList">
      <?php foreach($reservations as $room): ?>
      <!--col start-->
      <div class="col-md-4 col-sm-6 col-xs-12" >
        <a href="view_room.php?home_id=<?php echo $room['home_id']; ?>" class="single-item">
          <div class="fig_caption">
            <div class="icon" style="margin-bottom:-5px;">
              <img src="<?php echo '../images/room_images'.$room['image']; ?>" alt="<?php echo $room['name']; ?>" style="width:300px;height:250px;">

              <h3 style="font-size:18px;"><b><?php echo $room['name'].'(Room '.$room['room_id'].')'; ?></b></h3>
            </div>
            <div class="details" style="margin-top: -12px;">
               <h5>Rent Amount: <b>Php<?php echo $room['total_price'];?></b> </h5>
               <h5>Move-in Date:<b> <?php echo $room['check_in_date'] ?></b></h5>
                <h5>Occupy Until: <b><?php echo $room['check_out_date'] ?></b></h5>
            </div>
          </div>
      </a>
       <form method="POST" action="../users/cancel_booking.php" style="margin-top:5px;">
    <input type="hidden" id="delete_res" name="reservation_id" value="<?php echo $room['reservation_id']; ?>">
    <input type="hidden" name="cancel_booking" value="true"> <!-- Add this hidden input -->
    <button class="button-33" id="cancel" type="submit">Cancel Booking</button>
        </form>
      </div>
      <!--col end--> 
      <?php endforeach; ?>
    </div>
    <!--row end-->
    
    <div class="readmore text-center">
      <button class="main-btn btn-1 btn-1e">No More Result</button>
    </div>
  </div>
  <!--container end--> 
</section>
<!--pending reservation end--> 

<!--confirmed reservation start-->
<section class="services bg-gray" id=boardinghouse style="margin-top: -80px;"> 
  <!--container start-->
  <div class="container">
    <div class="section-title" style="margin-bottom: -15px;">
      <h3>Confirmed <span>Reservation</span></h3>
    </div>
    <!--row start-->
   <div class="row serviceList">
    <?php foreach($con_reservations as $rooms): ?>
    <!--col start-->
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="single-item" onclick="window.location='view_room.php?home_id=<?php echo $rooms['home_id']; ?>'" style="cursor: pointer;">
            <div class="fig_caption">
                <div class="icon" style="margin-bottom:-5px;">
                    <img src="<?php echo '../images/room_images'.$rooms['image']; ?>" alt="<?php echo $rooms['name']; ?>" style="width:300px;height:250px;">
                    <h3 style="font-size:18px;"><b><?php echo $rooms['name'].'(Room '.$rooms['room_id'].')'; ?></b></h3>
                </div>
                <div class="details" style="margin-top: -12px;">
                    <h5>Rental Fee: <b>Php<?php echo $rooms['total_price'];?></b></h5>
                    <h5>Move-in Date:<b> <?php echo $rooms['check_in_date'] ?></b></h5>
                    <h5>Occupy Until: <b><?php echo $rooms['check_out_date'] ?></b></h5>
                </div>
            </div>
        </div>
        <form action="process_online_payment.php" method="GET" style="margin-top:5px;">
            <input type="hidden" name="reservation_id" value="<?php echo urlencode($rooms['reservation_id']); ?>">
            <input type="hidden" name="rental_fee" value="<?php echo urlencode($rooms['total_price']); ?>">
            <button type="submit" id="pay_online_btn" class="btn btn-primary">Pay Now</button>
        </form>
    </div>
    <!--col end--> 
    <?php endforeach; ?>
</div>

    <!--row end-->
    
    <div class="readmore text-center">
      <button class="main-btn btn-1 btn-1e">No More Result</button>
    </div>
  </div>
  <!--container end--> 
</section>
<!--confirmed reservation end--> 

<!--counter start-->
<div class="counter"> 
  <!--container start-->
  <div class="container"> 
    <!--row start-->
    <div class="row"> 
      <!--col start-->
      <div class="col-md-3 col-sm-6 col-xs-12 counter-item">
        <div class="counterbox">
          <div class="counter-icon"><i class="fa fa-home" aria-hidden="true"></i></div>
          <div class="counter_area"><span class="counter-number" data-from="1" data-to="30" data-speed="100"></span> <span class="counter-text">Rooms</span> </div>
        </div>
      </div>
      <!--col end--> 
      <!--col start-->
      <div class="col-md-3 col-sm-6 col-xs-12 counter-item">
        <div class="counterbox">
          <div class="counter-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
          <div class="counter_area"> <span class="counter-number" data-from="1" data-to="20" data-speed="1000"></span> <span class="counter-text">Locations</span> </div>
        </div>
      </div>
      <!--col end--> 
      <!--col start-->
      <div class="col-md-3 col-sm-6 col-xs-12 counter-item">
        <div class="counterbox">
          <div class="counter-icon"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></div>
          <div class="counter_area"> <span class="counter-number" data-from="1" data-to="1756" data-speed="3000">1756</span> <span class="counter-text">Happy Guest</span> </div>
        </div>
      </div>
      <!--col end--> 
      <!--col start-->
      <div class="col-md-3 col-sm-6 col-xs-12 counter-item">
        <div class="counterbox">
          <div class="counter-icon"><i class="fa fa-hotel" aria-hidden="true"></i></div>
          <div class="counter_area_1"> <span class="counter-number" data-from="1" data-to="15" data-speed="3000">100+</span> <span class="counter-text">Ameneties</span></div>
        </div>
      </div>
      <!--col end--> 
      <!--col start--> 
    </div>
    <!--row end--> 
  </div>
  <!--container end--> 
</div>
<!--counter end--> 
<!--portfolio-area start-->
<!--portfolio-area end--> 
<!--whychoose-wrap start-->
<!--whychoose-wrap end--> 

<!--Testimonials Start-->

<!--Testimonials End--> 

<!--blogWrapper start-->

<!--blogWrapper end--> 

<!--brand-section start-->

<!--brand-sectionn end--> 
<!--footer-sec start-->

<!--footer-secn end--> 

<!--login-modal start-->

<!--login-modal end--> 
<!--registration-modal start-->

<!--registration-modal end--> 
<!--quote-modal start-->

<!--quote-modal end-->
</div>
<!--scroll-to-top start-->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="icon fa fa-angle-up"></span></div>
<!--scroll-to-top end--> 

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
        function handlePaymentSubmit(event) {
    // Stop the event from bubbling up to the parent div
    event.stopPropagation();
}

    </script> 
<script src="../js/script.js"></script> 
<!--jquery end-->
</body>

</html>
