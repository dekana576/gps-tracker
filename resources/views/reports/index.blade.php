<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Unduh Laporan</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 font-sans antialiased">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-10">
            <div class="bg-white p-6 shadow-md rounded-md text-center">
                <h1 class="text-2xl font-bold text-blue-500 mb-6">Unduh Laporan</h1>
                <div style="display: flex; justify-content:space-around">
                    <a href="{{ route('export.users') }}" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 text-sm mb-4">
                        Unduh Laporan User
                    </a>
                    <a href="{{ route('export.histories') }}" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 text-sm mb-4">
                        Unduh Laporan History
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>
</x-app-layout>
