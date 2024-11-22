<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Tracker</title>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Custom Styles -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Text&display=swap'); /* Import custom font */

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #343a40;
        }

        #map {
            height: 300px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @media (min-width: 768px) {
            #map {
                height: 500px;
            }
        }

        /* Navbar */
        .navbar {
            background-color: #ffff;
            color: #fff;
        }

        .navbar-brand img {
            height: 40px;
        }

        .navbar .btn-danger {
            border-radius: 20px;
        }

        /* Welcome Container */
        .welcome-container {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .welcome-container p {
            font-size: 1.1rem;
            margin: 3px 0;
        }

        .username {
            font-family: 'Roboto', sans-serif;
            font-size: 1.2rem;
            font-weight: 500;
            color: #007bff;
        }

        .company-name {
            font-family: 'Roboto', sans-serif;
            font-size: 1.2rem;
            font-weight: 500;
            color: #28a745;
        }

        .welcome-container hr {
            border-top: 2px solid #007bff;
            width: 50%;
            margin: 15px auto;
        }

        /* Button Styles */
        #startTracking,
#stopTracking {
    width: 150px; 
    height: 150px; 
    border-radius: 50%; 
    font-size: 2rem; 
    font-weight: bold;
    display: flex; 
    justify-content: center; 
    align-items: center;
    padding: 0;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 1); 
    transition: transform 0.2s ease, background-color 0.3s ease, box-shadow 0.3s ease;
}

/* Start button */
#startTracking {
    background-color: #007bff; 
    color: white; 
}

#startTracking:hover {
    transform: scale(1.1);
    background-color: #0056b3; 
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3); 
}

#startTracking:active {
    transform: scale(0.95); 
    background-color: #004085;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); 
}

/* Stop button */
#stopTracking {
    background-color: #d9534f; 
    color: white; 
}

#stopTracking:hover {
    transform: scale(1.1); 
    background-color: #c9302c;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3); 
}

#stopTracking:active {
    transform: scale(0.95); 
    background-color: #bd362f; 
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); 
}

#stopTracking:disabled {
    background-color: #d9534f; 
    box-shadow: none; 
    transform: none; 
    cursor: not-allowed; 
    opacity: 0.6;
}




        .stats-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
            padding: 15px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 1.2rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center; /* Pusatkan secara horizontal */
            justify-content: center; /* Pusatkan secara vertikal */
            text-align: center; /* Pastikan teks di dalam elemen terpusat */
        }

        .stat-item span:not(.stat-title) {
            font-size: 1rem;
            color: #007bff; /* Warna biru */
        }

        /* Footer */
        .footer {
            background-color: #fff;
            color: #000;
            padding: 10px 0;
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <div class="navbar-brand">
                <img src="images/astra.png" alt="Logo Astra">
                <img src="images/best.png" alt="Logo Best">
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    @if(Auth::check())
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="welcome-container">
            <p style="font-size: 40px; font-family: 'DM Serif Text', serif; color: #000;">Astra On The Go</p>
            <p>Welcome, <span class="username">{{ Auth::user()->name }}</span></p>
            <p>From, <span class="company-name">{{ Auth::user()->company_name }}</span></p>
            <hr>
        </div>

        <div class="mt-4">
            <div id="map"></div>
        </div>

        <div class="stats-container">
            <div class="stat-item">
                <span class="stat-title">Time</span>
                <span id="time">00:00</span>
            </div>
            <div class="stat-item">
                <span class="stat-title">Distance</span>
                <span id="distance">0.0 km</span>
            </div>
            <div class="stat-item">
                <span class="stat-title">Steps</span>
                <span id="steps">0</span>
            </div>
            <div class="stat-item">
                <span class="stat-title">Calories</span>
                <span id="calories">0</span>
            </div>
        </div>


        <div class="mt-4 buttons-container gap-5 d-flex justify-content-around">
            <button id="startTracking" class="btn btn-success custom-hover">Start</button>
            <button id="stopTracking" class="btn btn-danger custom-hover" disabled>Stop</button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Astra On The Go. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        let map = L.map('map').setView([-8.378731110827148, 115.17459424051236], 10);
        let polyline;
        let marker;
        let tracking = false;
        let startTime;
        let interval;
        let positions = [];
        let username = "{{ Auth::check() ? Auth::user()->name : '' }}";
        let company = "{{ Auth::check() ? Auth::user()->company_name : '' }}";
        let user_id = "{{ Auth::check() ? Auth::user()->id : '' }}";

        // Request permission for notifications if it's not already granted
        if (Notification.permission !== 'granted') {
            Notification.requestPermission();
        }

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Function to show notification
        function showNotification(message) {
            if (Notification.permission === 'granted') {
                new Notification(message);
            }
        }

        // Handle page visibility change
        document.addEventListener('visibilitychange', function () {
            if (document.hidden && tracking) {
                showNotification("Tracking is running in the background");
            }
        });

        document.getElementById('startTracking').addEventListener('click', function () {
            tracking = true;
            startTime = new Date();
            this.disabled = true;
            document.getElementById('stopTracking').disabled = false;

            // Choose a random color for the polyline
            const color = getRandomColor();
            polyline = L.polyline([], { color: color, weight: 5 }).addTo(map);
            positions = []; // Reset positions

            let firstPosition = true;

            interval = setInterval(() => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const latlng = [position.coords.latitude, position.coords.longitude];
                        positions.push(latlng);
                        polyline.addLatLng(latlng);

                        if (marker) map.removeLayer(marker);
                        marker = L.marker(latlng).addTo(map);

                        // Zoom in on the first position
                        if (firstPosition) {
                            map.setView(latlng, 17);
                            firstPosition = false;
                        } else {
                            map.setView(latlng);
                        }
                    });
                }
            }, 1000);

            // Show notification when tracking starts
            showNotification("Tracking has started");
        });

        document.getElementById('stopTracking').addEventListener('click', function () {
            tracking = false;
            this.disabled = true;
            document.getElementById('startTracking').disabled = false;
            clearInterval(interval);

            const endTime = new Date();
            const duration = (endTime - startTime) / 1000;
            const distance = calculateDistance(positions);

            // Send data to server
            fetch('/save-history', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ polyline: positions, duration, distance, startTime, username, company, user_id })
            }).then(response => response.json()).then(data => alert('History berhasil disimpan'));

            // Show notification when tracking stops
            showNotification("Tracking has stopped");
        });

        function calculateDistance(positions) {
            let totalDistance = 0;
            for (let i = 1; i < positions.length; i++) {
                const latlng1 = L.latLng(positions[i - 1]);
                const latlng2 = L.latLng(positions[i]);
                totalDistance += latlng1.distanceTo(latlng2);
            }
            return totalDistance / 1000; // in kilometers
        }

        // Function for random colors
        function getRandomColor() {
            return '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
        }
    </script>

</body>

</html>
