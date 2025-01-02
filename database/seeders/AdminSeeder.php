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
            'email' => 'astrahondaadm@gmail.com',  // Email admin
            'password' => Hash::make('ahmbali2024'), // Password admin
            'is_admin' => true, // Tandai sebagai admin
        ]);
    }
}

