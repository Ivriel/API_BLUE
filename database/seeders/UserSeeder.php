<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'id' => (string) Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@blue.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
        UserFactory::new()->count(15)->create();
    }
}
