<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Riwayat Pelacakan GPS</title>
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <!-- jQuery UI CSS -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-8">
                <div class="container mx-auto mt-8 p-5 bg-white shadow-lg rounded-lg">
                    <h1 class="text-3xl font-extrabold text-center text-blue-600 mb-2">Riwayat Pelacakan GPS</h1>
                    <!-- Filter dan Search -->
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <label for="dateFilter" class="block text-gray-700 text-sm font-medium">Filter by Date:</label>
                            <input 
                                type="text" 
                                id="dateFilter" 
                                class="border rounded p-2 w-full" 
                                placeholder="Pilih tanggal"
                            />
                        </div>
                    </div>

                    <!-- Tabel Riwayat GPS -->
                    <table id="historiesTable" class="min-w-full bg-white border-collapse border border-gray-300 rounded-lg shadow-sm">
                        <thead class="bg-blue-500 text-white border border-gray-300">
                            <tr>
                                <th class="px-4 py-2 border border-gray-300">NO</th>
                                <th class="px-4 py-2 border border-gray-300">Username</th>
                                <th class="px-4 py-2 border border-gray-300">Company Name</th>
                                <th class="px-4 py-2 border border-gray-300">Distance (km)</th>
                                <th class="px-4 py-2 border border-gray-300">Duration</th>
                                <th class="px-4 py-2 border border-gray-300">Start Time</th>
                                <th class="px-4 py-2 border border-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
    $(document).ready(function () {
        // Setup CSRF Token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize DataTable
        var table = $('#historiesTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: "{{ route('admin.histories') }}",
                data: function (d) {
                    d.date = $('#dateFilter').val();
                }
            },
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'username', name: 'username' },
                { data: 'company_name', name: 'company_name' },
                { 
                    data: 'distance', 
                    name: 'distance', 
                    render: function (data) {
                        const distance = parseFloat(data);
                        return distance < 1 
                            ? `${(distance * 1000).toFixed(0)} m` 
                            : `${distance.toFixed(2)} km`;
                    }
                },
                { data: 'duration', name: 'duration' },
                { data: 'start_time', name: 'start_time' },
                {
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <div class="flex space-x-2 items-center">
                                <a href="/admin/history/show/${data}" class="w-10 h-10 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                                <button 
                                    type="button" 
                                    class="w-10 h-10 bg-red-500 text-white rounded hover:bg-red-600 flex items-center justify-center delete-btn" 
                                    data-id="${data}"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            rowCallback: function(row, data, index){
                if (index % 2 === 0) {
                    $(row).addClass('bg-gray-100');
                }
                $('td', row).addClass('border border-gray-300');
            },
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });

        // Event listener untuk tombol hapus
        $(document).on('click', '.delete-btn', function () {
            var id = $(this).data('id'); // Ambil ID dari tombol

            // Tampilkan konfirmasi menggunakan SweetAlert2
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan data yang sudah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lakukan permintaan AJAX untuk menghapus data
                    $.ajax({
                        url: `/admin/history/delete/${id}`, // URL endpoint untuk hapus
                        method: 'DELETE',
                        success: function (response) {
                            // Tampilkan notifikasi sukses dengan SweetAlert2
                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            );
                            table.ajax.reload(); // Reload DataTable
                        },
                        error: function (xhr, status, error) {
                            // Tampilkan notifikasi error dengan SweetAlert2
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

    </body>
    </html>
</x-app-layout>
