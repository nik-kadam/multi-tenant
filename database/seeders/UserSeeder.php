<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Tenant 1',
                'email' => 'tenant1@example.com',
                'password' => Hash::make('Test@123'),
            ],
            [
                'name' => 'Tenant 2',
                'email' => 'tenant2@example.com',
                'password' => Hash::make('Test@123'),
            ],
        ]);
    }
}
