<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create manager account
        User::create([
            'name' => 'VE Manager',
            'username' => 've_manager',
            'email' => 'evelynbalaisserrano@gmail.com',
            'password' => Hash::make('evelynserrano1234'),
            'role' => 'manager',
            'phoneNumber' => '09123456789',
            'email_verified_at' => now(),
        ]);

        // Create staff account
        User::create([
            'name' => 'Luxa Staff',
            'username' => 'luxa_staff',
            'email' => 'luxa@gmail.com',
            'password' => Hash::make('Jacinto22-1639'),
            'role' => 'staff',
            'phoneNumber' => '09987654321',
            'email_verified_at' => now(),
        ]);

        // Create guest account
        User::create([
            'name' => 'Mae mae',
            'username' => 'Mae Colo',
            'email' => 'mae@gmail.com',
            'password' => Hash::make('Jacinto22-1639'),
            'role' => 'guest',
            'phoneNumber' => '09876543210',
            'email_verified_at' => now(),
        ]);

           
    }
}

