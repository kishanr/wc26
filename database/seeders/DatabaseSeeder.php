<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@wc26.app',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'is_verified' => true,
            'xp_points' => 0,
        ]);

        $this->command->info('✅ Created admin user: admin@wc26.app / password');

        // Seed teams, stadiums, and matches
        $this->call([
            TeamSeeder::class,
            StadiumSeeder::class,
            MatchSeeder::class,
        ]);
    }
}
