<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'nyoman.arini@hso.astra.co.id',  // Email admin
            'password' => Hash::make('admbali2024'), // Password admin
            'is_admin' => true, // Tandai sebagai admin
        ]);
    }
}

