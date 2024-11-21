<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Tracker</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
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
            margin: 5px 0;
        }

        .username {
            color: #007bff;
            font-weight: bold;
        }

        .company-name {
            color: #28a745;
            font-weight: bold;
        }

        .welcome-container hr {
            width: 50%;
            margin: 20px auto;
            border-top: 2px solid #007bff;
        }

        .buttons-container button,
        .buttons-container a {
            width: 100%;
            margin-bottom: 10px;
            font-size: 1rem;
            padding: 10px 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

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
            <p>Astra On The Go</p>
            <p>Welcome, <span class="username">{{ Auth::user()->name }}</span></p>
            <p>From, <span class="company-name">{{ Auth::user()->company_name }}</span></p>
            <hr>
        </div>

        <div class="mt-4">
            <div id="map"></div>
        </div>

        <div class="mt-4 buttons-container">
            <button id="startTracking" class="btn btn-success">Mulai Pelacakan</button>
            <button id="stopTracking" class="btn btn-danger" disabled>Stop Pelacakan</button>
            <a href="{{ route('history.index') }}" class="btn btn-primary">Lihat History</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Astra On The Go. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let map = L.map('map').setView([-8.378731110827148, 115.17459424051236], 10);
        let polyline = L.polyline([]).addTo(map);
        let marker;
        let tracking = false;
        let startTime;
        let interval;
        let positions = [];
        let username = "{{ Auth::check() ? Auth::user()->name : '' }}";
        let company = "{{ Auth::check() ? Auth::user()->company_name : '' }}";
        let user_id = "{{ Auth::check() ? Auth::user()->id : '' }}";

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        document.getElementById('startTracking').addEventListener('click', function () {
            tracking = true;
            startTime = new Date();
            this.disabled = true;
            document.getElementById('stopTracking').disabled = false;

            interval = setInterval(() => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const latlng = [position.coords.latitude, position.coords.longitude];
                        positions.push(latlng);
                        polyline.addLatLng(latlng);
                        if (marker) map.removeLayer(marker);
                        marker = L.marker(latlng).addTo(map);
                        map.setView(latlng, 15);
                    });
                }
            }, 1000);
        });

        document.getElementById('stopTracking').addEventListener('click', function () {
            tracking = false;
            this.disabled = true;
            document.getElementById('startTracking').disabled = false;
            clearInterval(interval);

            const endTime = new Date();
            const duration = (endTime - startTime) / 1000;
            const distance = calculateDistance(positions);

            fetch('/save-history', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ polyline: positions, duration, distance, startTime, username, company, user_id })
            }).then(response => response.json()).then(data => alert('History berhasil disimpan'));
        });

        function calculateDistance(positions) {
            let totalDistance = 0;
            for (let i = 1; i < positions.length; i++) {
                const latlng1 = L.latLng(positions[i - 1]);
                const latlng2 = L.latLng(positions[i]);
                totalDistance += latlng1.distanceTo(latlng2);
            }
            return totalDistance / 1000;
        }
    </script>
</body>

</html>
