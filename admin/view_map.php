<?php
include("../inc/db.php");
# Fetch room data from the database
$sqlHolidayHomes = "SELECT name, coordinates, availability_status, admin_approved FROM holiday_homes";
$resultHolidayHomes = $con->query($sqlHolidayHomes);

# Check if there are any rooms
if ($resultHolidayHomes->num_rows > 0) {
    $holidayHomes = $resultHolidayHomes->fetch_all(MYSQLI_ASSOC);
} else {
    // No rooms found
    $holidayHomes = array();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuegan | Location Picker</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!--buttonCSS-->
    <link href="../css/button.css" rel="stylesheet">

    <style>
        #map {
            height: 650px;
            width: 100%;
        }

        .leaflet-tooltip.my-custom-tooltip {
            color: black;
            border: none;
            box-shadow: none;
            font-weight: bold;
        }
        .tooltip-visible {
            background-color: #ACF3AE;
        }
        .tooltip-notVisible {
            background-color: #FA6B84;
        }
    </style>
</head>
<body>
    <h3 align="center">Location of all Rooms</h3>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Pass PHP array to JavaScript
        var holidayHomes = <?php echo json_encode($holidayHomes); ?>;

        var map = L.map('map').setView([11.8190, 122.1617], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Create a custom icon
        var customIcon = L.icon({
            iconUrl: '../images/map_marker.png', // URL to the default Leaflet marker icon
            iconSize: [50, 100], // Adjust the size as needed
            iconAnchor: [12, 41], // Point of the icon which will correspond to marker's location
            popupAnchor: [1, -34], // Point from which the popup should open relative to the iconAnchor
            shadowSize: [41, 41] // Size of the shadow
        });

        // Add markers for each coordinate from the database with permanent tooltips
        holidayHomes.forEach(function(home) {
            let visibility = "";
            let visibilityClass = "";
            if (home.admin_approved === "approved") {
                visibility = "visible";
                visibilityClass = "tooltip-visible";
            } else {
                visibility = "not visible";
                visibilityClass = "tooltip-notVisible";
            }
            var coords = home.coordinates.split(',');
            var lat = parseFloat(coords[0]);
            var lng = parseFloat(coords[1]);
            var marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
            marker.bindTooltip(`${home.name} (${visibility})`, {
                permanent: true,
                direction: 'left',
                className: `my-custom-tooltip ${visibilityClass}`
            }).openTooltip();
        });
    </script>
</body>
</html>
