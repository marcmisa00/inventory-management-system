<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'username' => 'nesiadmin',
            ],
            [
                'idno' => '103563',
                'password' => Hash::make('nesi2026'),
                'role' => 'Inventory Admin',
                'is_active' => true,
            ]
        );
    }
}