<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPS Tracking History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Riwayat Pelacakan GPS</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Jarak (km)</th>
                <th>Durasi</th>
                <th>Tanggal Mulai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($histories as $history)
                <tr>
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
    <a href="/" class="btn btn-primary">Kembali ke Halaman Utama</a>
</div>
</body>
</html>
