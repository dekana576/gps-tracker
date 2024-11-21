<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Riwayat Pelacakan GPS</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-8">
                <div class="container mx-auto mt-8 p-5 bg-white shadow-lg rounded-lg">
                    <h2 class="text-2xl font-bold mb-4 text-center text-blue-500">Riwayat Pelacakan GPS</h2>

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
                        <div class="w-1/3">
                            <label for="tableSearch" class="sr-only">Cari:</label>
                            <input 
                                type="search" 
                                class="border rounded p-2 w-full" 
                                id="tableSearch" 
                                placeholder="Cari data..."
                            />
                        </div>
                    </div>

                    <table id="historiesTable" class="min-w-full bg-white border-collapse border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <th class="px-4 py-2 border">NO</th>
                                <th class="px-4 py-2 border">Username</th>
                                <th class="px-4 py-2 border">Company Name</th>
                                <th class="px-4 py-2 border">Distance (km)</th>
                                <th class="px-4 py-2 border">Duration</th>
                                <th class="px-4 py-2 border">Start Time</th>
                                <th class="px-4 py-2 border">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Add Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function () {
                // Initialize Datepicker
                $("#dateFilter").datepicker({
                    dateFormat: "yy-mm-dd",
                });

                // Initialize DataTable
                var table = $('#historiesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false, // Menonaktifkan pencarian bawaan DataTables
                    ajax: {
                        url: "{{ route('admin.histories') }}",
                        data: function (d) {
                            d.date = $('#dateFilter').val(); // Kirim filter tanggal ke server
                        }
                    },
                    columns: [
                        {
                            data: 'id',
                            name: 'id',
                            render: function (data, type, row, meta) {
                                return meta.row + 1; // Nomor urut
                            }
                        },
                        { data: 'username', name: 'username' },
                        { data: 'company_name', name: 'company_name' },
                        { 
                            data: 'distance', 
                            name: 'distance', 
                            render: function (data) {
                                return `${parseFloat(data).toFixed(2)} km`;
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
                                        <form action="/admin/history/delete/${data}" method="POST" onsubmit="return confirmDelete(event, this);" class="w-10 h-10 flex items-center justify-center">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="w-full h-full bg-red-500 text-white rounded hover:bg-red-600">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                `;
                            }
                        }
                    ],
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                    }
                });

                // Event listener untuk filter tanggal
                $('#dateFilter').on('change', function () {
                    table.ajax.reload(); // Reload data berdasarkan filter tanggal
                });

                // Event listener untuk custom search
                $('#tableSearch').on('keyup', function () {
                    table.search(this.value).draw(); // Lakukan pencarian
                });
            });

            // Confirm delete function
            function confirmDelete(event, form) {
                event.preventDefault(); // Prevent the form from submitting directly
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    form.submit(); // Submit the form if the user confirms
                }
                return false; // Prevent default action jika user membatalkan
            }
        </script>
    </body>
    </html>
</x-app-layout>
