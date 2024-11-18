<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Polyline Tracking</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Detail Polyline Pelacakan</h2>
    <div id="map" style="height: 500px;"></div>
    <a href="{{ route('history.index') }}" class="btn btn-primary mt-3">Kembali ke Riwayat</a>
</div>

<script>
    // Inisialisasi peta Leaflet
    let map = L.map('map').setView([-8.378731110827148, 115.17459424051236], 9); // Zoom awal ke 15 untuk tampilan lebih dekat

    // Tile layer dari OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Ambil polyline dari controller
    let polylineData = {!! json_encode($history->polyline) !!};


    if (polylineData.length > 0) {
        let polyline = L.polyline(polylineData, { color: 'blue' }).addTo(map);

        // Sesuaikan peta agar menampilkan polyline dengan benar
        if (polylineData.length === 1) {
            // Jika hanya ada satu titik (jarak 0 km), tetap fokus pada titik tersebut
            map.setView(polylineData[0], 15); // Zoom lebih dekat pada titik tunggal
            L.marker(polylineData[0]).addTo(map).bindPopup("Titik Mulai dan Akhir").openPopup();
        } else {
            // Jika ada lebih dari satu titik, tampilkan seluruh polyline
            map.fitBounds(polyline.getBounds());

            // Tambahkan marker di titik awal dan titik akhir
            L.marker(polylineData[0]).addTo(map).bindPopup("Titik Mulai").openPopup();
            L.marker(polylineData[polylineData.length - 1]).addTo(map).bindPopup("Titik Akhir");
        }
    } else {
        alert('Tidak ada data polyline tersedia.');
    }
</script>
</body>
</html>
