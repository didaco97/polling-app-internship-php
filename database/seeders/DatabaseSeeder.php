<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed users (admin + demo)
        $this->call(UserSeeder::class);

        // Create sample polls
        $poll1 = DB::table('polls')->insertGetId([
            'question' => 'What is your favorite programming language?',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('poll_options')->insert([
            ['poll_id' => $poll1, 'option_text' => 'PHP', 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $poll1, 'option_text' => 'JavaScript', 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $poll1, 'option_text' => 'Python', 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $poll1, 'option_text' => 'Java', 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $poll2 = DB::table('polls')->insertGetId([
            'question' => 'Which framework do you prefer?',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('poll_options')->insert([
            ['poll_id' => $poll2, 'option_text' => 'Laravel', 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $poll2, 'option_text' => 'Django', 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $poll2, 'option_text' => 'Express.js', 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $poll2, 'option_text' => 'Spring Boot', 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $poll3 = DB::table('polls')->insertGetId([
            'question' => 'How do you prefer to learn?',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('poll_options')->insert([
            ['poll_id' => $poll3, 'option_text' => 'Video tutorials', 'display_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $poll3, 'option_text' => 'Documentation', 'display_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $poll3, 'option_text' => 'Hands-on projects', 'display_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['poll_id' => $poll3, 'option_text' => 'Books', 'display_order' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
