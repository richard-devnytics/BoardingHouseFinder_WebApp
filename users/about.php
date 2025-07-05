<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About TUEOGAN</title>
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        
        header {
            background: #5a67d8;
            color: #fff;
            padding: 10px 0;
            text-align: center;
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

<?php include('users_header.php'); ?>

<div class="container">
    
    <h1>Welcome to<br/></h1>
    <div class="logo">
        <img src="../images/my_logo.png" style="background-color:#5a67d8; border-radius: 5px;">
    </div>
    <p align="center">We are a dedicated team committed to helping you find the perfect boarding house in Ibajay, Aklan. Our platform is designed with your needs in mind, offering an easy and efficient way to discover accommodations that suit your lifestyle and budget.</p>

    <p align="center">Our Team:</p>
    <div class="row">
        <div class="col-md-4 team-member">
            <img src="../images/group/1.png" alt="Elijah Allen Parlade">
            <br><strong>Elijah Allen Parlade</strong><br><p style="margin-top: -7px;">Programmer</p><br>
            <p style="margin-top:-15px">Elijah is the technical mastermind behind TUEOGAN. With a passion for coding and a keen eye for detail, he ensures that our website runs smoothly and delivers a seamless user experience.</p>
        </div>
        <div class="col-md-4 team-member">
            <img src="../images/group/2.png" alt="Feddie Villanueva">
            <br><strong>Feddie Villanueva</strong> <br><p style="margin-top: -7px;">Writer</p><br>
            <p style="margin-top:-15px">Feddie brings our content to life. His expertise in crafting engaging narratives helps us communicate essential information about each boarding house, making your search not just informative but enjoyable.</p>
        </div>
        <div class="col-md-4 team-member">
            <img src="../images/group/3.png" alt="Dannel Garcia and Christian Basister">
           <br><strong>Dannel Garcia and Christian Basister</strong> <br><p style="margin-top: -7px;">Designer</p><br>
            <p style="margin-top:-15px">Dannel and Christian are the creative forces behind our website’s aesthetic. Their design philosophy focuses on user-friendly layouts and visually appealing interfaces, ensuring that your journey through TUEOGAN is as pleasant as possible.</p>
        </div>
    </div>

    <p align="center">At TUEOGAN, we understand the challenges of finding the right boarding house. That’s why we’ve combined our skills to create a reliable resource for residents and newcomers alike. Whether you’re a student, professional, or traveler, we’re here to help you find your ideal home away from home.</p>

    <p align="center">Thank you for choosing TUEOGAN!</p>
</div>

<footer>
    <p>&copy; 2024 TUEOGAN. All Rights Reserved.</p>
</footer>

</body>
</html>
