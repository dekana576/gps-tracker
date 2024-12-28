<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Riwayat Pelacakan GPS</title>

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-8">
                <div class="container mx-auto mt-8 p-5 bg-white shadow-lg rounded-lg">
                <h1 class="text-3xl font-extrabold text-center text-blue-600 mb-2">Data User dan Riwayat GPS</h1>

                    <table id="userTable" class="min-w-full bg-white border-collapse border border-gray-300 rounded-lg shadow-sm">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <th class="px-4 py-2 border border-gray-300">No</th>
                                <!-- <th class="px-4 py-2 border border-gray-300">ID</th> -->
                                <th class="px-4 py-2 border border-gray-300">Username</th>
                                <th class="px-4 py-2 border border-gray-300">Perusahaan</th>
                                <th class="px-4 py-2 border border-gray-300">Total Jarak (km)</th>
                                <th class="px-4 py-2 border border-gray-300">Total Durasi</th>
                                <th class="px-4 py-2 border border-gray-300">Total Steps</th>
                                <th class="px-4 py-2 border border-gray-300">Total Calori</th>
                                <th class="px-4 py-2 border border-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <!-- DataTables akan mengisi ini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- jQuery dan DataTables JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#userTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('user.histories') }}",
                    columns: [
                        {
                            data: 'id',
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1; // Nomor urut
                            }
                        },
                        // { data: 'id' },
                        { data: 'name' },
                        { data: 'company_name' },
                        {
                            data: 'total_distance',
                            render: function(data) {
                                return parseFloat(data).toFixed(2) + ' km'; // Format ke 2 desimal
                            }
                        },
                        { data: 'total_duration' },
                        { data: 'total_steps' },
                        { data: 'total_calori' },
                        { data: 'actions', orderable: false, searchable: false }, // Kolom aksi
                    ],
                    language: {
                        processing: "Loading...",
                        lengthMenu: "Tampilkan _MENU_ entri per halaman",
                        zeroRecords: "Tidak ada data ditemukan",
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                        infoEmpty: "Tidak ada entri tersedia",
                        infoFiltered: "(disaring dari _MAX_ total entri)",
                        search: "Cari:",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Berikutnya",
                            previous: "Sebelumnya"
                        }
                    },
                    rowCallback: function(row, data, index) {
                        if (index % 2 === 0) {
                            $(row).addClass('bg-gray-100'); // Baris ganjil dengan warna latar belakang berbeda
                        }
                        $('td', row).addClass('border border-gray-300');
                    }
                });

                // Fungsi untuk mengedit data
                $(document).on('click', '.edit-btn', function() {
                    const userId = $(this).data('id');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda akan mengedit data user ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, edit!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire(
                                'Diedit!',
                                'User dengan ID ' + userId + ' akan diedit.',
                                'success'
                            ).then(() => {
                                window.location.href = `/users/${userId}/edit`; // Ganti URL sesuai route edit
                            });
                        }
                    });
                });

                // Fungsi untuk menghapus data
                $(document).on('click', '.delete-btn', function() {
                    const userId = $(this).data('id');

                    // Tampilkan dialog konfirmasi SweetAlert2 untuk penghapusan data
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Lakukan penghapusan data via AJAX
                            $.ajax({
                                url: `/users/${userId}`, // Ganti URL sesuai route penghapusan
                                type: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF
                                },
                                success: function(result) {
                                    Swal.fire(
                                        'Terhapus!',
                                        'User berhasil dihapus.',
                                        'success'
                                    );
                                    $('#userTable').DataTable().ajax.reload(); // Refresh tabel setelah penghapusan
                                },
                                error: function(err) {
                                    Swal.fire(
                                        'Gagal!',
                                        'Terjadi kesalahan saat menghapus user.',
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
