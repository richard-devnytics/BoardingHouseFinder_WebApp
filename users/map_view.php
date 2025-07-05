<!DOCTYPE html>
<html>
<head>
    <title>Map View</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Include Leaflet CSS and JavaScript -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="../css/button.css" rel="stylesheet">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Include Leaflet Routing Machine CSS and JavaScript -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <style>
        #backButton {
            position: absolute;
            top: 10px;
            left: 50px;
            z-index: 1000;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px 20px;
            cursor: pointer;
        }

        #directionButton {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px 20px;
            cursor: pointer;
        }

        .leaflet-tooltip.my-custom-tooltip {
            background-color: orange !important; 
            color: black;
            font-size: 15px;
            border: none;
            box-shadow: none;
            font-weight: bold;
        }

        #map {
            height: 900px;
        }
    </style>
</head>
<body>
    <button class="button-33" id="backButton" onclick="goBack()">Back</button>
    <button class="button-33" id="directionButton" onclick="getDirections()">Directions</button>
    <div id="map"></div>

    <script>
        function goBack() {
            window.history.back();
        }

        // Extract coordinates and name from the URL
        let urlParams = new URLSearchParams(window.location.search);
        let coordinates = urlParams.get('coordinates').split(',');
        let name = urlParams.get('name');

        // Create a custom icon for the marker
        let orangeIcon = L.icon({
            iconUrl: '../images/map_marker.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.5.1/images/marker-shadow.png',
            iconSize: [100, 200], // Original size: [40, 64]
            iconAnchor: [20, 64],
            popupAnchor: [0, -48],
            shadowSize: [64, 64]
        });

        // Create a Leaflet map centered at the coordinates
        let map = L.map('map').setView(coordinates, 18);

        // Add a marker at the specified coordinates with the custom icon
        L.marker(coordinates, { icon: orangeIcon }).addTo(map).bindPopup(name);

        // Add a tile layer for the map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let control;

        // Function to get directions
        function getDirections() {
            map.locate({ setView: true, maxZoom: 16, timeout: 10000 });

            map.once('locationfound', function(e) {
                let userLocation = [e.latlng.lat, e.latlng.lng];

                // Add a marker for the user's location
                let userMarker = L.marker(userLocation).addTo(map).bindPopup("You are here").openPopup();

                // Remove existing routing control if it exists
                if (control) {
                    map.removeControl(control);
                }

                // Add routing from user's location to the room location
                control = L.Routing.control({
                    waypoints: [
                        L.latLng(userLocation),
                        L.latLng(coordinates)
                    ],
                    createMarker: function(i, waypoint, n) {
                        if (i === 0) {
                            return L.marker(waypoint.latLng).bindPopup("You are here");
                        } else if (i === n - 1) {
                            return L.marker(waypoint.latLng, { icon: orangeIcon }).bindPopup(name);
                        } else {
                            return L.marker(waypoint.latLng);
                        }
                    }
                }).addTo(map);
            });

            map.on('locationerror', function(e) {
                alert(e.message);
            });
        }
    </script>
</body>
</html>
