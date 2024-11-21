<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<div class="bg-blue-100" style="min-height: 9vh">

    <!-- Main Content -->
    <main class="container mx-auto py-10 px-7">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">

            <!-- List User -->
            <div class="bg-white shadow-lg rounded-xl p-8 hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-102">
                <div class="flex items-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a5 5 0 100-10 5 5 0 000 10zm-7 8a7 7 0 0114 0H3z" />
                    </svg>
                    <h2 class="ml-4 text-xl font-serif text-gray-800">List User</h2>
                </div>
                <p class="text-sm text-gray-600 mb-6">Kelola daftar pengguna di aplikasi Anda.</p>
                <a href="{{ route('user.index') }}" class="block">
                    <button class="w-full bg-blue-300 text-black py-3 px-6 rounded-lg hover:bg-blue-600 transition duration-300 ease-in-out">
                        Lihat Detail
                    </button>
                </a>
            </div>

            <!-- Data Tracking -->
            <div class="bg-white shadow-lg rounded-xl p-8 hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-102">
                <div class="flex items-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 3a7 7 0 100 14 7 7 0 000-14zm0 2a5 5 0 110 10A5 5 0 0110 5zm-1 2a1 1 0 112 0v3a1 1 0 01-2 0V7zm1 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                    </svg>
                    <h2 class="ml-4 text-xl font-serif text-gray-800">History Tracking</h2>
                </div>
                <p class="text-sm text-gray-600 mb-6">Pantau data aktivitas pengguna secara real-time.</p>
                <a href="{{ route('history.index') }}" class="block">
                    <button class="w-full bg-blue-300 text-black py-3 px-6 rounded-lg hover:bg-blue-500 transition duration-300 ease-in-out">
                        Lihat Detail
                    </button>
                </a>
            </div>

            <!-- Download Laporan -->
            <div class="bg-white shadow-lg rounded-xl p-8 hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-102">
                <div class="flex items-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8 10V2a1 1 0 112 0v8h2.586a1 1 0 01.707 1.707l-3.586 3.586a1 1 0 01-1.414 0L6.707 11.707A1 1 0 017.414 10H8zm10 2a1 1 0 00-1 1v3H3v-3a1 1 0 10-2 0v3a2 2 0 002 2h14a2 2 0 002-2v-3a1 1 0 00-1-1z" />
                    </svg>
                    <h2 class="ml-4 text-xl font-serif text-gray-800">Download Laporan</h2>
                </div>
                <p class="text-sm text-gray-600 mb-6">Unduh laporan dalam format PDF atau Excel.</p>
                <a href="{{ route('reports.index') }}" class="block">
                    <button class="w-full bg-blue-300 text-black py-3 px-6 rounded-lg hover:bg-blue-500 transition duration-300 ease-in-out">
                        Lihat Detail
                    </button>
                </a>
            </div>

        </div>
    </main>

    <!-- Footer Content -->
    <footer class="bg-blue-100 text-black py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 Astra On The Go. All Rights Reserved.</p>
        </div>
    </footer>
</div>
