<x-guest-layout>
    <style>
        /* CSS untuk latar belakang */
        .background-image {
            background-image: url('{{ asset('images/ahm.jpg') }}'); /* Ganti dengan path gambar Anda */
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

    <div class="background-image">
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
