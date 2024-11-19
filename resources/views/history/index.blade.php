<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Tracking History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for a modern look */
        body {
            background-color: #f4f8fb;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 1200px;
        }

        .table {
            border-collapse: collapse;
        }

        .table th, .table td {
            text-align: center;
            padding: 15px;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .table td {
            background-color: #fff;
        }

        .btn {
            margin-top: 5px;
        }

        .alert {
            margin-bottom: 20px;
        }

        /* Button Hover Effects */
        .btn-info:hover {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        /* Responsive Table */
        @media (max-width: 767px) {
            .table th, .table td {
                padding: 10px;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 class="page-title">Riwayat Pelacakan GPS</h2>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Perusahaan</th>
                        <th>Jarak (km)</th>
                        <th>Durasi</th>
                        <th>Tanggal Mulai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $history)
                    <tr>
                        <td>{{ $history->username }}</td>
                        <td>{{ $history->company_name }}</td>
                        <td>{{ $history->distance }} km</td>
                        <td>{{ $history->duration }}</td>
                        <td>{{ $history->start_time->format('d-m-Y H:i:s') }}</td>
                        <td>
                            <a href="{{ route('history.show', $history->id) }}" class="btn btn-info">Lihat Polyline</a>
                            <form action="{{ route('history.destroy', $history->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada riwayat ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <a href="/" class="btn btn-primary">Kembali ke Halaman Utama</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
