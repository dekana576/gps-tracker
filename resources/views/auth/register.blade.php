<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<x-guest-layout>
<style>
    /* CSS untuk latar belakang */
    .background-image {
        background-image: url('../images/ahm.jpg'); /* Path relatif dari folder public */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        color: white; /* Untuk teks agar terlihat di atas gambar */
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

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" class="text-black" />
                <x-input id="name" class="block mt-1 w-full text-black" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="company_name" value="{{ __('Nama Perusahaan') }}" class="text-black" />
                <x-input id="company_name" class="block mt-1 w-full text-black" type="text" name="company_name" :value="old('company_name')"  />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" class="text-black" />
                <x-input id="email" class="block mt-1 w-full text-black" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" class="text-black" />
                <x-input id="password" class="block mt-1 w-full text-black" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-black" />
                <x-input id="password_confirmation" class="block mt-1 w-full text-black" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms" class="text-black">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</div>
</x-guest-layout>
