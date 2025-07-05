<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tueogan - Home</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/img/logo.jpg">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

    <style>
        #map {
            height: 400px;
        }
    </style>

</head>


<body>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let map;

        function initMap() {
            map = L.map('map').setView([51.505, -0.09], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            map.on('click', function (e) {
                document.getElementById('selectedLocation').value = JSON.stringify(e.latlng);
            });
        }

        // Call initMap when the DOM is ready
        document.addEventListener('DOMContentLoaded', initMap);

        function useCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    document.getElementById('location').value = `${latitude}, ${longitude}`;
                });
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }
    </script>
    <!-- Nav Bar -->


    <div class="container">
        <form name="searchForm" action="users/search.php" method="GET" class="form">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" placeholder="Location">

            <button type="button" onclick="useCurrentLocation()" class="btn btn-primary">Use Current Location</button>
            <button type="submit" name="search" class="btn btn-success">Search</button>
            <button type="reset" name="reset" class="btn btn-danger">Reset</button>
        </form>
    </div>

</body>

</html>
