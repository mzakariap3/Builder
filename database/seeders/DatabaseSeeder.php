<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin Odeon',
            'email'    => 'admin@odeon.com',
            'password' => Hash::make('admin123'), // Password default
        ]);
    }
}