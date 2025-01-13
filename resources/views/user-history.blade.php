<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<Style>
     @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Text&display=swap'); /* Import custom font */

body {
    font-family: 'Roboto', Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
    color: #343a40;
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

        .table {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f3f5;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.8rem;
        margin: 0 2px;
        border-radius: 5px;
        background: #333;
        color: #fff !important;
        border: none;
        transition: background 0.3s ease-in-out;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #007bff;
        color: #fff !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #007bff;
        color: #fff !important;
        font-weight: bold;
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


</Style>
<body>
    
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

     <!-- History View -->
<div id="historyContainer" style="margin-top: 20px;">
    <div class="container">
        <div class="container mt-5">
            <h3 class="text-center mb-4">User History Table</h3>

            <!-- Kotak Statistik -->
            <div class="container mb-4">

                
    <!-- Group 1 -->
    <div class="d-flex justify-content-between mb-3">
        <div class="card text-center flex-fill mx-2">
            <div class="card-body">
                <h5 class="card-title">Total Distance</h5>
                <p class="card-text" id="totalDistance">{{ $user->total_distance ?? 0 }} </p>
            </div>
        </div>
        <div class="card text-center flex-fill mx-2">
            <div class="card-body">
                <h5 class="card-title">Total Duration</h5>
                <p class="card-text" id="totalDuration">{{ $user->histories->first()->total_duration ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    <!-- Group 2 -->
    <div class="d-flex justify-content-between">
        <div class="card text-center flex-fill mx-2">
            <div class="card-body">
                <h5 class="card-title">Total Calories</h5>
                <p class="card-text" id="totalCalories">{{ $user->total_calori ?? 0 }} kcal</p>
            </div>
        </div>
        <div class="card text-center flex-fill mx-2">
            <div class="card-body">
                <h5 class="card-title">Total Steps</h5>
                <p class="card-text" id="totalSteps">{{ $user->total_steps ?? 0 }} steps</p>
            </div>
        </div>
    </div>
</div>


            <!-- Akhir Kotak Statistik -->

            <table id="historyTable" class="table table-striped table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Start Time</th>
                        <th>Distance (km)</th>
                        <th>Duration</th>
                        <th>Polyline</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
        <nav>
            <ul class="pagination justify-content-center" id="paginationLinks"></ul>
        </nav>
        
        <div class="mt-5 pt-5 text-center">
            <form method="POST" action="{{ route('logout') }}" class="d-grid">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
</div>

    
    
    <!-- Footer -->
    <footer class="footer">
    <p>&copy; <?= date("Y") ?> H W A. All Rights Reserved.</p>


    <script>
        $('#historyTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: '/history',
                type: 'GET',
                dataSrc: function (json) {
                    return json.data || [];
                },
                error: function (xhr, error, thrown) {
                    console.error('Error saat memuat data:', xhr.responseText || error);
                }
            },
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            columns: [
    { data: 'start_time', name: 'start_time' },
    { 
        data: 'distance', 
        name: 'distance', 
        render: function (data, type, row) {
            if (type === 'display' || type === 'filter') {
                // Cek apakah data valid dan merupakan angka
                var value = parseFloat(data);
                
                // Jika data bukan angka atau null, kembalikan tanda strip '-'
                if (isNaN(value) || value === null) {
                    return '-'; // Kembalikan simbol atau teks pengganti jika data tidak valid
                }

                // Jika data kurang dari 1 km, konversi ke meter
                if (value < 1) {
                    return (value * 1000).toFixed(0) + ' m'; // Konversi ke meter
                }

                // Jika data >= 1 km, tampilkan dalam kilometer
                return value.toFixed(2) + ' km';
            }

            // Jika bukan tipe display/filter, kembalikan data asli
            return data;
        }

    },
    { data: 'duration', name: 'duration' },
    {
        data: 'id',
        render: function (data) {
            return `
                <a href="/history/${data}/polyline" 
                   class="w-10 h-10 bg-blue-500 text-blue rounded hover:bg-blue-600 flex items-center justify-center">
                    <i class="fas fa-map-marker-alt"></i>
                </a>`;
        },
        orderable: false,
        searchable: false
    }
]
    });
    </script>
</body>
</html>
