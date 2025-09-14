<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678')
        ]);
        $admin->assignRole('admin');

        $operator = User::create([
            'name' => 'Petugas PST',
            'email' => 'pst@example.com',
            'password' => bcrypt('12345678')
        ]);
        $operator->assignRole('petugas_pst');

        $pengolah = User::create([
            'name' => 'Pengolah Data',
            'email' => 'pengolah@example.com',
            'password' => bcrypt('12345678')
        ]);
        $pengolah->assignRole('pengolah_data');
    }
}
