<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //ORDRE !!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $this->call([
            LanguageSeeder::class,
            FilmSeeder::class,
            ActorSeeder::class,
            ActorFilmSeeder::class,
            UserSeeder::class,
            CriticSeeder::class,
        ]);
    }
}
