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
                    <h2 class="text-2xl font-bold mb-4 text-center text-blue-500">Riwayat Pelacakan GPS</h2>
                    
                    <table id="historiesTable" class="min-w-full bg-white border-collapse border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <th class="px-4 py-2 border">ID</th>
                                <th class="px-4 py-2 border">Username</th>
                                <th class="px-4 py-2 border">Company Name</th>
                                <th class="px-4 py-2 border">Distance (km)</th>
                                <th class="px-4 py-2 border">Duration</th>
                                <th class="px-4 py-2 border">Start Time</th>
                                <th class="px-4 py-2 border">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <!-- DataTables akan mengisi ini -->
                        </tbody>
                    </table>

                
                </div>
            </div>
        </div>
        
        <!-- Tambahkan Script -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#historiesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.histories') }}",
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'username', name: 'username', defaultContent: 'NULL' },
                        { data: 'company_name', name: 'company_name', defaultContent: 'NULL' },
                        { data: 'distance', name: 'distance', render: (data) => `${parseFloat(data).toFixed(2)} km` },
                        { data: 'duration', name: 'duration' },
                        { data: 'start_time', name: 'start_time' },
                        {
                            data: 'id',
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return `
                                    <a href="/admin/history/show/${data}" 
                                       class="btn btn-info text-white bg-blue-500 px-2 py-1 rounded hover:bg-blue-600">
                                       Lihat Polyline
                                    </a>
                                    <form action="/admin/history/delete/${data}" method="POST" style="display: inline-block;" onsubmit="return confirmDelete(event, this);">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" 
                                                class="btn btn-danger text-white bg-red-500 px-2 py-1 rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </form>
                                `;
                            }
                        }
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                    }
                });
            });

            // Fungsi konfirmasi penghapusan
            function confirmDelete(event, form) {
                event.preventDefault(); // Mencegah submit form secara langsung
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    form.submit(); // Submit form jika pengguna menekan "Yes"
                }
                // Jika pengguna menekan "No", tidak ada tindakan
                return false;
            }
        </script>
    </body>
    </html>
</x-app-layout>
