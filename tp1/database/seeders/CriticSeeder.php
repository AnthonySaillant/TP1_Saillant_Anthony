<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Critic::factory()->count(30)->create([
                'user_id' => $user->id,
                'film_id' => Film::inRandomOrder()->first()->id,
            ]);
        }
    }
}
