
    <style>
        .background-image {
            background-image: url('{{ asset('images/we.jpg') }}'); /* Ganti dengan path gambar Anda */
            background-size: cover; /* Mengatur ukuran gambar agar menutupi seluruh area */
            background-position: center; /* Memusatkan gambar */
            background-repeat: no-repeat; /* Tidak mengulang gambar */
            min-height: 100vh; /* Mengatur tinggi minimum untuk mengisi layar */
            display: flex; /* Menggunakan flexbox untuk memposisikan konten */
            justify-content: center; /* Memusatkan konten secara horizontal */
            align-items: center; /* Memusatkan konten secara vertikal */
            position: relative; /* Mengatur posisi relatif untuk elemen di dalamnya */
        }

        /* CSS untuk membuat konten transparan */
        .transparent-card {
            background-color: rgba(255, 255, 255, 0.8); /* Warna putih dengan transparansi */
            border-radius: 8px; /* Radius border untuk efek rounded */
            padding: 20px; /* Memberikan padding di dalam card */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Menambahkan bayangan */
        }
    </style>
<x-app-layout>
    <div class="background-image">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div>
    </div>

    
</x-app-layout>
