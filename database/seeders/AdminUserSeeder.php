<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['username' => env('ADMIN_USERNAME', 'admin')],
            ['password' => env('ADMIN_PASSWORD', 'change-me'), 'role' => 'admin', 'has_paid' => true]
        );
    }
}
