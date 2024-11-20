<x-app-layout>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Riwayat Pelacakan GPS</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-8">
                <div class="container mx-auto mt-8 p-5 bg-white shadow-lg rounded-lg">
                    <h2 class="text-2xl font-bold mb-4 text-center text-blue-500">Data User dan Riwayat GPS</h2>
                    
                    <table id="userTable" class="min-w-full bg-white border-collapse border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <tr>
                                    <th class="px-4 py-2 border">ID</th>
                                    <th class="px-4 py-2 border">Username</th>
                                    <th class="px-4 py-2 border">Perusahaan</th>
                                    <th class="px-4 py-2 border">Total Jarak (km)</th>
                                    <th class="px-4 py-2 border">Total Durasi</th>
                                    <th class="px-4 py-2 border">Aksi</th>
                                </tr>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <!-- DataTables akan mengisi ini -->
                        </tbody>
                    </table>

                
                </div>
            </div>
        </div>



    <!-- Tambahkan jQuery dan DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.histories') }}",
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'company_name' },
                    { data: 'total_distance', render: function(data, type, row) {
                        return parseFloat(data).toFixed(2); // Format ke 2 desimal
                    }},
                    { data: 'total_duration' },
                    { data: 'actions', orderable: false, searchable: false }, // Kolom aksi
                ],
                language: {
                    "processing": "Loading...",
                    "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                    "zeroRecords": "Tidak ada data ditemukan",
                    "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Tidak ada entri tersedia",
                    "infoFiltered": "(disaring dari _MAX_ total entri)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });

        });
        $(document).on('click', '.edit-btn', function() {
            const userId = $(this).data('id');
            alert('Edit user dengan ID: ' + userId);
            // Redirect ke halaman edit atau buka modal
            window.location.href = `/users/${userId}/edit`; // Ganti URL sesuai route
        });

        $(document).on('click', '.delete-btn', function() {
            const userId = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                $.ajax({
                    url: `/users/${userId}`, // Ganti URL sesuai route
                    type: 'DELETE',
                    success: function(result) {
                        alert('User berhasil dihapus!');
                        $('#userTable').DataTable().ajax.reload(); // Refresh tabel
                    },
                    error: function(err) {
                        alert('Terjadi kesalahan saat menghapus user.');
                    }
                });
            }
        });

    </script>
</body>
</html>
</x-app-layout>
