
    <style>
        .background-image {
    background-image: url('../images/we.jpg'); /* Path relatif dari folder public */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    color: white; /* Untuk teks agar terlihat di atas gambar */
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5); /* Tambahkan bayangan pada teks */
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
