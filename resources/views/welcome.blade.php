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
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        #map {
            height: 250px;
            width: 100%;
        }

        @media (min-width: 768px) {
            #map {
                height: 400px;
            }
        }

        .navbar {
            padding: 0.5rem 1rem;
        }

        .navbar-brand-center {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .navbar-brand-center img {
            height: 40px;
            margin: 0 10px;
        }

        .navbar-text {
            font-size: 1.2rem;
            font-weight: bold;
            flex-grow: 1;
            text-align: center;
            margin: 0;
            padding-top: 20px;
        }


        .btn {
            width: 100%;
            margin-bottom: 10px;
        }

        .footer {
            background-color: #f1f1f1;
            padding: 15px 0;
            text-align: center;
        }

        /* Styling for welcome section */
        .welcome-container {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
        }

        .welcome-container p {
            font-size: 1rem;
            font-weight: bold;
            color: #333;
        }

        .welcome-container .username {
            font-size: 1rem;
            color: #007bff;
        }

        .welcome-container .company-name {
            font-size: 1rem;
            color: #28a745;
        }

        .welcome-container hr {
            width: 50%;
            margin: 20px auto;
            border-top: 2px solid #007bff;
        }

        /* Add margin to map section */
        #map {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <!-- Logo dan teks di tengah -->
            <div class="navbar-brand-center">
                <img src="images/astra.png" alt="Logo Astra">
                <img src="images/best.png" alt="Logo Best">
                
            </div>

            <!-- Tombol Navbar Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navbar -->
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
    <div class="welcome-container">
        <p>Astra On The Go</p>
        <p>Welcome, <span class="username">{{ Auth::user()->name }}</span></p>
        <p>From, <span class="company-name">{{ Auth::user()->company_name }}</span></p>
        <hr>
    </div>

    <div class="container mt-3">
        <div id="map"></div>

        <div class="mt-3 d-flex flex-column d-flex-row gap-2">
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
        let map = L.map('map').setView([-8.378731110827148, 115.17459424051236], 9); // Perbesar map dengan zoom 15
        let polyline = L.polyline([]).addTo(map);
        let marker;
        let tracking = false;
        let startTime;
        let interval;
        let positions = [];
        let username = "{{ Auth::check() ? Auth::user()->name : '' }}";
        let company = "{{ Auth::check() ? Auth::user()->company_name : '' }}";
        let user_id = "{{ Auth::check() ? Auth::user()->id : '' }}";

        // Tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
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

                        // Tambahkan polyline baru ke peta
                        polyline.addLatLng(latlng);

                        // Jika marker sudah ada, hapus marker sebelumnya
                        if (marker) {
                            map.removeLayer(marker);
                        }

                        // Tambahkan marker baru di lokasi saat ini
                        marker = L.marker(latlng).addTo(map);

                        // Atur peta untuk mengikuti lokasi pengguna
                        map.setView(latlng, 15); // Pastikan zoom tetap di 15
                    });
                }
            }, 3000);
        });

        document.getElementById('stopTracking').addEventListener('click', function () {
            tracking = false;
            this.disabled = true;
            document.getElementById('startTracking').disabled = false;
            clearInterval(interval);

            const endTime = new Date();
            const duration = (endTime - startTime) / 1000; // dalam detik
            const distance = calculateDistance(positions); // Hitung jarak menggunakan fungsi jarak

            // Kirim data ke server untuk disimpan ke history
            fetch('/save-history', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    polyline: positions,
                    duration: duration,
                    distance: distance,
                    startTime: startTime,
                    username: username,
                    company: company,
                    user_id: user_id,
                })
            }).then(response => response.json())
              .then(data => {
                  alert('History berhasil disimpan');
              });
        });

        // Fungsi untuk menghitung jarak antara titik-titik posisi
        function calculateDistance(positions) {
            let totalDistance = 0;
            for (let i = 1; i < positions.length; i++) {
                const latlng1 = L.latLng(positions[i - 1]);
                const latlng2 = L.latLng(positions[i]);
                totalDistance += latlng1.distanceTo(latlng2);
            }
            return totalDistance / 1000; // Jarak dalam km
        }
    </script>
</body>

</html>
