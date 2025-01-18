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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="manifest" href="/manifest.json">




    <!-- Custom Styles -->
</head>
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

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(0, 0, 0, 1)' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
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
    font-family: sans-serif;
    width: 150px; 
    height: 150px; 
    border-radius: 50%; 
    font-size: 2rem; 
    font-weight: bold;
    display: flex; 
    justify-content: center; 
    align-items: center;
    padding: 0;
    box-shadow: 2px 5px 15px rgba(0, 0, 0, 0.3); 
    transition: transform 0.2s ease, background-color 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

/* Start button */
#startTracking {
    background: linear-gradient(90deg, #84bffa,  #55a1f3); /* Gradien dari biru (#007bff) ke biru muda (#66b3ff) */
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

#startTracking:disabled {
    background-color: #004085; 
    box-shadow: none; 
    transform: none; 
    cursor: not-allowed; 
    opacity: 0.6;
}

/* Stop button */
#stopTracking {
    background: linear-gradient(90deg, #fe8585,  #f35555);; 
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

<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Brand -->
            <div class="d-flex align-items-center">
                <div class="navbar-brand d-flex align-items-center" id="backToMain">
                    <img src="images/astra.png" alt="Logo Astra" style="height: 40px; margin-right: 10px;">
                    <img src="images/best.png" alt="Logo Best" style="height: 40px;">
                </div>
                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
    
            <!-- Navbar Menu -->
            <div class="collapse navbar-collapse justify-content-center text-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="{{ route('user')}}" id="homeButton" class="nav-link my-3 mx-3" style="border-bottom: gray solid 1px">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('userHistory')}}" id="viewHistory" class="nav-link my-3 mx-3" style="border-bottom: gray solid 1px">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div id="mainContent">
        <div class="container mt-4">
            <div class="welcome-container">
                <p style="font-size: 50px; font-family: 'DM Serif Text', serif; color: #000 ;">Healthy With Astra</p>
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
                    <span id="distance">0 km</span>
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
    </div>
    
   
    
    
    <!-- Footer -->
    <footer class="footer">
    <p>&copy; <?= date("Y") ?> H W A. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        


if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js').then(function (registration) {
        console.log('Service Worker terdaftar:', registration.scope);
    }).catch(function (error) {
        console.error('Service Worker gagal terdaftar:', error);
    });
}

if ("Notification" in window && navigator.serviceWorker) {
    Notification.requestPermission().then(permission => {
        if (permission !== "granted") {
            alert("Aktifkan izin notifikasi untuk fitur terbaik.");
        }
    });
}


function showNotification(title, body) {
    if ("Notification" in window && Notification.permission === "granted") {
        navigator.serviceWorker.getRegistration().then(function (registration) {
            registration.showNotification(title, {
                body: body,
                icon: "images/best.png",
                badge: "images/best.png",
                vibrate: [200, 100, 200],
                actions: [
                    { action: 'view', title: 'Lihat' },
                    { action: 'dismiss', title: 'Abaikan' }
                ],
                tag: 'location-tracking'
            });
        });
    }
}


let wakeLock = null;

async function requestWakeLock() {
    try {
        wakeLock = await navigator.wakeLock.request('screen');
        wakeLock.addEventListener('release', () => {
            console.log('Wake lock released');
        });
        console.log('Wake lock active');
    } catch (err) {
        console.error(`${err.name}, ${err.message}`);
    }
}

document.getElementById('startTracking').addEventListener('click', requestWakeLock);









        let map = L.map('map').setView([-8.378731110827148, 115.17459424051236], 10);
        let polyline;
        let marker;
        let tracking = false;
        let startTime;
        let timeInterval;
        let interval;
        let positions = [];
        let username = "{{ Auth::check() ? Auth::user()->name : '' }}";
        let company = "{{ Auth::check() ? Auth::user()->company_name : '' }}";
        let user_id = "{{ Auth::check() ? Auth::user()->id : '' }}";
    
        // Variables for tracking time, distance, steps, and calories
        let totalDistance = 0;
        let totalSteps = 0;
        let totalCalories = 0;
    
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    
        // Function to update live stats
        function updateStats() {
            const now = new Date();
            const timeElapsed = Math.floor((now - startTime) / 1000); // in seconds
            document.getElementById('time').textContent = formatTime(timeElapsed);
            const formattedDistance = totalDistance < 1 
            ? (totalDistance * 1000).toFixed(0) + " m" 
            : totalDistance.toFixed(2) + " km";
        document.getElementById('distance').textContent = formattedDistance;

            document.getElementById('steps').textContent = totalSteps;
            document.getElementById('calories').textContent = totalCalories.toFixed(1);
        }
    
        // Format time as MM:SS
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
    
        // Function to calculate steps and calories
        function updateStepsAndCalories() {
            const stepsPerKm = 1333; // Approximately 1333 steps per kilometer (can vary by person)
            totalSteps = Math.floor(totalDistance * stepsPerKm);
            totalCalories = totalSteps * 0.04; // Approximate calorie burn per step
        }
    
       

        document.getElementById('startTracking').addEventListener('click', function () {
        tracking = true;
        startTime = new Date();
        this.disabled = true;
        document.getElementById('stopTracking').disabled = false;

        // Reset stats
        totalDistance = 0;
        totalSteps = 0;
        totalCalories = 0;
        positions = []; // Reset positions

        showNotification("Tracking Dimulai", "Aplikasi sedang melacak lokasi Anda di latar belakang.");

        const color = getRandomColor();
        polyline = L.polyline([], { color: color, weight: 5 }).addTo(map);

        let firstPosition = true;

        timeInterval = setInterval(updateStats, 1000);

        interval = setInterval(() => {

            if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
        const latlng = [position.coords.latitude, position.coords.longitude];
        positions.push(latlng);
        polyline.addLatLng(latlng);

        if (marker) map.removeLayer(marker);
        marker = L.marker(latlng).addTo(map);

        if (firstPosition) {
            map.setView(latlng, 17);
            firstPosition = false;
        } else {
            map.setView(latlng);
        }

        if (positions.length > 1) {
            const lastDistance = calculateDistance([positions[positions.length - 2], latlng]);
            totalDistance += lastDistance;
            updateStepsAndCalories();
        }

        updateStats();
    }, function (error) {
        console.error("Error mendapatkan lokasi: ", error);
    }, {
        enableHighAccuracy: true,
        maximumAge: 0,
        timeout: 3000
    });
}

        }, 500);
    });
    
    document.getElementById('stopTracking').addEventListener('click', function () {
    tracking = false;
    this.disabled = true;
    document.getElementById('startTracking').disabled = false;
    
    // Hentikan interval untuk update waktu dan lokasi
    clearInterval(timeInterval);
    clearInterval(interval);  // Tambahkan ini untuk menghentikan interval lokasi

    showNotification("Tracking Dihentikan", "Pelacakan lokasi Anda telah dihentikan.");

    const endTime = new Date();
    const duration = (endTime - startTime) / 1000;

    // Send data to the server only when tracking stops
    fetch('/save-history', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            polyline: positions,
            duration,
            distance: totalDistance,
            startTime,
            username,
            company,
            calori: totalCalories,
            steps: totalSteps,
            user_id
        })
    })
    .then(response => response.json())
    .then(data => {
        // Tampilkan notifikasi sukses menggunakan SweetAlert
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'History berhasil disimpan.',
            confirmButtonText: 'OK',
            timer: 3000, // Toast akan otomatis tertutup dalam 3 detik
            timerProgressBar: true,
            position: 'top-end', // Posisi toast di pojok kanan atas
            toast: true,
            showCloseButton: true // Tambahkan tombol close
        });
    })
    .catch(error => {
        // Tampilkan notifikasi error jika terjadi kesalahan
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan history. Silakan coba lagi.',
            confirmButtonText: 'OK'
        });
    });
});

    
        function calculateDistance(positions) {
            const latlng1 = L.latLng(positions[0]);
            const latlng2 = L.latLng(positions[1]);
            return latlng1.distanceTo(latlng2) / 1000; // Convert to kilometers
        }
    
        // Function for random colors
        function getRandomColor() {
            return '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
        }
        
    
       
        
    </script>
        
    
    
    

</body>

</html>
