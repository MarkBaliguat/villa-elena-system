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
            'password' => Hash::make('Serrano!12'),
            'role' => 'manager',
            'phoneNumber' => null,
            'email_verified_at' => now(),
        ]);

    }
}

