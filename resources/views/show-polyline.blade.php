<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Polyline Tracking</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .custom-container {
            max-width: 800px; /* Atur sesuai kebutuhan */
        }
    </style>

</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-8">
            <div class="container px-4 py-8">
                
                <h2 class="text-2xl font-extrabold text-center  mb-6">
                    Detail Polyline Tracking <br>
                    <span class="text-blue-500">{{ $history->start_time }}</span>
                </h2>
                
                <!-- Informasi Data -->
                <div class="flex justify-around my-3 pt-14">
                    <p class="text-center"><span class="font-bold p-4 m-4 bg-gradient-to-r from-gray-500 to-gray-700 rounded-lg shadow-lg text-white"><i class="fa-solid fa-road"></i> {{ $history->distance }} Km</span></p>
                    <p class="text-center"><span class="font-bold p-4 m-4 bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg shadow-lg text-white"><i class="fa-solid fa-person-running"></i> {{ $history->steps }} Steps</span></p>
                </div>
                <div class="flex justify-around my-3 mb-11 pt-14">
                    <p class="text-center"><span class="font-bold p-4 m-4 bg-gradient-to-r from-red-500 to-red-700 rounded-lg shadow-lg text-white"><i class="fa-solid fa-fire"></i> {{ $history->calori }} Cal</span></p>
                    <p class="text-center"><span class="font-bold p-4 m-4 bg-gradient-to-r from-yellow-500 to-yellow-700 rounded-lg shadow-lg text-white"><i class="fa-regular fa-clock"></i> {{ $history->duration }}</span></p>

                </div>
                
                <!-- Peta -->
                <div id="map" class="rounded-3xl h-96 shadow-lg border border-gray-300"></div>
                <div class="my-11">
                    <a href="{{ route('history.index') }}" 
                       class="bg-blue-500 text-white px-5 py-3 rounded-lg font-semibold hover:bg-blue-600 my-6">
                        Back
                    </a>
                </div>
                    

                
            </div>
        </div>
    </div>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        // Inisialisasi peta Leaflet
        let map = L.map('map').setView([-8.378731110827148, 115.17459424051236], 9);

        // Tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ambil data polyline dari server
        let polylineData = {!! json_encode($history->polyline) !!};

        if (polylineData && polylineData.length > 0) {
            // Tambahkan polyline ke peta
            let polyline = L.polyline(polylineData, { color: 'blue', weight: 5 }).addTo(map);

            // Tambahkan marker di titik awal
            L.marker(polylineData[0]).addTo(map).bindPopup('Titik Awal').openPopup();

            // Tambahkan marker di titik akhir
            L.marker(polylineData[polylineData.length - 1]).addTo(map).bindPopup('Titik Akhir');

            // Sesuaikan peta agar menampilkan polyline
            map.fitBounds(polyline.getBounds());
        } else {
            alert('Tidak ada data polyline tersedia.');
        }
    </script>
</body>
</html>
