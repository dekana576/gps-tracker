<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<x-guest-layout>

    <style>
        /* CSS untuk latar belakang */
        .background-image {
            background-image: url('{{ asset('images/www.jpg') }}'); /* Path gambar Anda */
            background-size: cover; /* Mengatur gambar agar menutupi seluruh area */
            background-position: center; /* Memusatkan gambar */
            background-repeat: no-repeat; /* Tidak mengulang gambar */
            min-height: 100vh; /* Mengisi tinggi layar penuh */
            display: flex; /* Gunakan flex untuk memposisikan konten */
            justify-content: center; /* Pusatkan secara horizontal */
            align-items: center; /* Pusatkan secara vertikal */
            padding: 20px; /* Tambahkan padding agar tidak terlalu mepet */
        }

        /* CSS untuk membuat konten transparan */
        .transparent-card {
            background-color: rgba(255, 255, 255, 0.8); /* Warna putih transparan */
            border-radius: 8px; /* Efek rounded */
            padding: 20px; /* Jarak dalam card */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Bayangan */
            max-width: 400px; /* Maksimal lebar untuk tampilan responsif */
            width: 100%; /* Pastikan card mengambil lebar penuh pada layar kecil */
        }
    </style>

    <div class="background-image">
        <x-authentication-card class="transparent-card"> <!-- Menambahkan kelas transparan -->
            <x-slot name="logo">
                <x-authentication-card-logo />
            </x-slot>

            <x-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-button class="ms-4">
                        {{ __('Log in') }}
                    </x-button>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <a href="{{ route('register') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ __('Don\'t have an account? Register') }}
                    </a>
                </div>
            </form>
        </x-authentication-card>
    </div>

    <script src="m/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</x-guest-layout>