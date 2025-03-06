<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\UserListSeeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data to avoid unique constraint violations
        User::truncate();
        
        $this->call([
            UserSeeder::class,
            UserListSeeder::class, 
            CourseSeeder::class
        ]);
    }

}

