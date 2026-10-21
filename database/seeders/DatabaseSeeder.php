<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin (Zahra Araya)
        User::create([
            'name' => 'Zahra Araya',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Akun User (Aya)
        User::create([
            'name' => 'Aya',
            'email' => 'aya@siswa.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}