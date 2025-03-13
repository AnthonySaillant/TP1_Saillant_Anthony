<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Critic;
use App\Models\Film;

class CriticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $films = Film::all();

        if ($films->isEmpty()) {
            $this->command->warn("No films found in the database. Skipping CriticSeeder.");
            return;
        }

        foreach ($users as $user) {
            Critic::factory()->count(30)->create([
                'user_id' => $user->id,
                'film_id' => fn() => $films->random()->id, 
            ]);
        }
    }
}
