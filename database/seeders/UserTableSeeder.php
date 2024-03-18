<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'phone' => '081234567890',
            'password' => Hash::make("secret123"),
        ]);

        $user = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'phone' => '081234567891',
            'password' => Hash::make("secret123"),
        ]);
    }
}
