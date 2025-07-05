<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Picker</title>
    <!-- Include Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Include Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map {
            height: 400px;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        // Function to get query parameter value by name
        function getQueryParam(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Get input field ID from query parameter
        var inputId = getQueryParam('inputId');

        // Initialize map
        var map = L.map('map').setView([11.8190, 122.1617], 16); // Initial coordinates

        // Add a tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Example marker for picking coordinates
        var marker;

        // Add click event to map
        map.on('click', function(e) {
            // Remove existing marker if it exists
            if (marker) {
                map.removeLayer(marker);
            }

            // Add new marker
            marker = L.marker(e.latlng).addTo(map);

            // Set coordinates in parent window
            var coordinatesInput = window.opener.document.getElementById(inputId);
            coordinatesInput.value = e.latlng.lat.toFixed(6) + ',' + e.latlng.lng.toFixed(6);

            // Close map picker window after selection (optional)
            window.close();
        });
    </script>
</body>
</html>
