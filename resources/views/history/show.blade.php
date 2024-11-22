<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detail Polyline Tracking</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-8">
                <div class="container mx-auto px-4 py-8">
                    <div>
                        <a href="{{ route('history.index') }}" 
                           class="bg-blue-500 text-white px-5 py-3 rounded-lg font-semibold hover:bg-blue-600">
                            Back
                        </a>
                    </div>
                    <h2 class="text-2xl font-extrabold text-center text-blue-500 mb-6">
                        Detail Polyline Pelacakan
                    </h2>
                    
                    <div class="flex justify-around my-6 mb-7 pt-14">
                        <p class="text-center"><span class="text-2xl font-bold p-4 m-4 bg-gradient-to-r from-blue-300 to-blue-500 rounded-lg shadow-lg text-white"><i class="fa-solid fa-person-running"></i> {{($history->distance)}} Km</span></p>
                        <p class="text-center"><span class="text-2xl font-bold p-4 m-4 bg-gradient-to-r from-red-300 to-red-500 rounded-lg shadow-lg text-white"><i class="fa-regular fa-clock"></i> {{($history->duration)}}</span></p>
                    </div>
                    <div id="map" class="rounded-3xl h-96 shadow-lg border border-gray-300"></div>
                    <div class="my-6 mb-7 ">
                        <p class="font-bold text-2xl text-center">{{($history->username)}}</p>
                        <p class="text-center">{{($history->company_name)}}</p>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <script>
            // Inisialisasi peta Leaflet
            let map = L.map('map').setView([-8.378731110827148, 115.17459424051236], 9);

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
</x-app-layout>
