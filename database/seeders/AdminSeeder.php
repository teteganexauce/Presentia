<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@eber.org'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone' => '+22900000000',
                'email' => 'admin@eber.org',
                'password' => Hash::make('Admin@2026!'),
                'status' => 'ACTIVE',
            ]
        );
    }
}
