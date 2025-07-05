
<?php
# database
include("../inc/db.php");

// Initialize variables
$searchError = "";
$searchResults = array();
$distance="";

// Check if the form data has been submitted
if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
    $lat = trim($_GET['latitude']);
    $long = trim($_GET['longitude']);
    $coordinate = $lat . ',' . $long;

    // Calculate distance and sort by nearest (Harvisine Formula)
   $query = "SELECT *, (6371 * acos(cos(radians(?)) * cos(radians(CAST(SUBSTRING_INDEX(coordinates, ',', 1) AS DECIMAL(10,7)))) * cos(radians(CAST(SUBSTRING_INDEX(coordinates, ',', -1) AS DECIMAL(10,7))) - radians(?)) + sin(radians(?)) * sin(radians(CAST(SUBSTRING_INDEX(coordinates, ',', 1) AS DECIMAL(10,7)))))) AS distance 
FROM holiday_homes WHERE availability_status='available' AND available_rooms > 0 AND admin_approved= 'approved'
HAVING distance < 10 
ORDER BY distance 
LIMIT 0, 20";

    $stmt = $con->prepare($query);
    if ($stmt === false) {
        $searchError = "Error preparing query: " . $con->error;
    } else {
        $stmt->bind_param("ddd", $lat, $long, $lat);
        if (!$stmt->execute()) {
            $searchError = "Error executing query: " . $stmt->error;
        } else {
            $result = $stmt->get_result();
            
            // Fetch search results
            while ($row = $result->fetch_assoc()) {
                // Debugging output
                $distance= "" . $row['name'] . ": " . number_format((float)$row['distance'], 2, '.', '') . " km<br>";
                
                $searchResults[] = $row;
            }

            $result->free();
        }
        $stmt->close();
    }
}

?>


<!DOCTYPE html>
<html lang="zxx">

<head>
<meta charset="UTF-8">
<title>Tueogan | Map Picker</title>
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="description" content="SunRise construction & Builder company">
<meta name="keywords" content="boardinghouse, html, template, responsive, corporate">
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
    i{
      color: orange;
    }

    .boardinghouse{
      margin-top: -100 px;
    }

    .single-item {
      margin-bottom: 20px;
      text-align: center;
      height: 400px; /* Adjusted fixed height for all boxes */
      width: 350px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border: 1px solid #ddd;
      padding: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
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
    }

    .col-md-4, .col-sm-6, .col-xs-12 {
      display: flex;
      align-items: stretch;
      justify-content: center;
      margin-bottom: 20px;
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
<!--services start-->
<section class="services bg-gray" id=boardinghouse> 
  <!--container start-->
  <div class="container">
    <div class="section-title">
      <h3 style="margin-top: -40px;">OUR NEAREST <span>ROOMS</span></h3>
     <div class="search">
      <form action="search_.php" method="GET" class="bloq-search">
      <input type="text" name="keyword" placeholder="Enter Keyword..">
      <button class="button-33" role="button">Search</button>
     </form>
     <p class="text-center">
                <span>Search through google map?</span>
                <a href="../users/search_map.php">
                  <span>Click here</span>
                </a>
              </p>
   </div>
    </div>
    <!--row start-->
    <div class="row serviceList">
      <?php foreach($searchResults as $room): ?>
      <!--col start-->
      <div class="col-md-4 col-sm-6 col-xs-12">
        <a href="reserve.php?home_id=<?php echo $room['home_id']; ?>" class="single-item">
          <div class="fig_caption">
            <div class="icon">
              <img src="<?php echo $room['image_path']; ?>" alt="<?php echo $room['name']; ?>" style="width:300px;height:250px;">
              <h3><?php echo $room['name']; ?></h3>
               <h5>Loc: <b><?php echo $room['location']; ?></b> | Room: <b><?php echo $room['available_rooms'];?> Left</b> </h5>
            </div>
            <div class="details">
              <h5><b><?php echo "Distance from Location: " . number_format((float)$room['distance']*1.5, 2, '.', '') . " km<br>";?></b></h5>
            </div>
          </div>
      </a>
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
<!--services end--> 

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


    </script> 
<script src="../js/script.js"></script> 
<!--jquery end-->
</body>

</html>
