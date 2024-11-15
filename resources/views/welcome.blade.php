<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Tracker</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between">

        <h2>GPS Tracker</h2>
    
        @if(Auth::check())
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                Logout
            </button>
        </form>
        @endif
    </div>

    <div id="map" style="height: 500px;"></div>
    <button id="startTracking" class="btn btn-success mt-3">Mulai Pelacakan</button>
    <button id="stopTracking" class="btn btn-danger mt-3" disabled>Stop Pelacakan</button>
    <a href="{{ route('history.index') }}" class="btn btn-primary mt-3">Lihat History</a>
</div>

<script>
    let map = L.map('map').setView([-8.378731110827148, 115.17459424051236], 9); // Perbesar map dengan zoom 15 -8.378731110827148, 115.17459424051236
    let polyline = L.polyline([]).addTo(map);
    let marker;
    let tracking = false;
    let startTime;
    let interval;
    let positions = [];

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
                startTime: startTime
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
