<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Unduh Laporan</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gradient-to-br from-blue-100 to-blue-200 font-sans antialiased min-h-screen">
        <div class="max-w-4xl mx-auto mt-16 px-6 lg:px-8">
            <!-- Card Container -->
            <div class="bg-white shadow-lg rounded-lg p-8">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-blue-600 mb-2">Unduh Laporan</h1>
                    <p class="text-gray-600">Pilih laporan yang ingin Anda unduh di bawah ini</p>
                </div>

                <!-- Buttons Section -->
                <div class="flex flex-col sm:flex-row sm:justify-center sm:space-x-6 gap-4">
                    <!-- User Report Button -->
                    <a href="{{ route('export.users') }}" 
                       class="bg-gradient-to-r from-green-400 to-green-500 hover:from-green-500 hover:to-green-600 text-white px-6 py-3 rounded-lg shadow-lg transform hover:scale-105 transition-transform text-sm font-medium">
                        Unduh Laporan User
                    </a>

                    <!-- History Report Button -->
                    <a href="{{ route('export.histories') }}" 
                       class="bg-gradient-to-r from-blue-400 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white px-6 py-3 rounded-lg shadow-lg transform hover:scale-105 transition-transform text-sm font-medium">
                        Unduh Laporan History
                    </a>
                </div>
            </div>

            <!-- Decorative Footer -->
            <div class="text-center mt-12">
            <p>&copy; <?= date("Y") ?> H W A. All Rights Reserved.</p>
            </div>
        </div>
    </body>
    </html>
</x-app-layout>
