<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Film;
use App\Models\Critic;
use App\Models\User;

class FilmControllerTest extends TestCase
{
    use RefreshDatabase;

    const FILM_TITLE = 'Test Film';
    const FILM_RELEASE_YEAR = 2020;
    const FILM_LENGTH = 120;
    const FILM_DESCRIPTION = 'A description of the test film';
    const FILM_RATING = 'PG';
    const FILM_LANGUAGE_ID = 1;
    const FILM_SPECIAL_FEATURES = 'None';
    const FILM_IMAGE = 'test_image.jpg';

    const USER_LOGIN = 'johndoe';
    const USER_PASSWORD = 'password';
    const USER_EMAIL = 'john.doe@example.com';
    const USER_LAST_NAME = 'Doe';
    const USER_FIRST_NAME = 'John';

    const CRITIC_SCORE = 8.5;
    const CRITIC_COMMENT = 'Great film!';

    const NON_EXISTENT_FILM_ID = 9999;
    const FILM_NOT_FOUND_MESSAGE = 'Film not found';

    public function testGetFilms()
    {
        $this->seed();

        $response = $this->getJson('/api/films');
        $films_array = json_decode($response->getContent(), true);

        $this->assertEquals(20, count($films_array['data']));

        $film = Film::first();
        $response->assertJsonFragment([
            'title'  => $film->title,
            'rating' => $film->rating,
            'length' => $film->length
        ]);

        $response->assertStatus(200);
    }

    public function testShowActorsWithExistingFilm()
    {
        $this->seed();

        $film = Film::first();
        $response = $this->getJson("/api/films/{$film->id}/actors");
        $actors_array = json_decode($response->getContent(), true);

        $this->assertNotEmpty($actors_array['data']);
        $this->assertEquals($film->actors()->count(), count($actors_array['data']));

        $actor = $film->actors()->first();
        if ($actor) {
            $response->assertJsonFragment([
                'id'   => $actor->id,
                'first_name' => $actor->first_name,
                'last_name' => $actor->last_name
            ]);
        }

        $response->assertStatus(200);
    }

    public function testShowActorsWithNonExistentFilm()
    {
        $this->seed();

        $response = $this->getJson("/api/films/" . self::NON_EXISTENT_FILM_ID . "/actors");

        $response->assertStatus(404);
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals(self::FILM_NOT_FOUND_MESSAGE, $responseContent['message']);
    }

    public function testShowActorsWithNoActors()
    {
        $this->seed();
    
        $film = Film::create([
            'title' => self::FILM_TITLE,
            'release_year' => self::FILM_RELEASE_YEAR,
            'length' => self::FILM_LENGTH,
            'description' => self::FILM_DESCRIPTION,
            'rating' => self::FILM_RATING,
            'language_id' => self::FILM_LANGUAGE_ID,
            'special_features' => self::FILM_SPECIAL_FEATURES,
            'image' => self::FILM_IMAGE,
        ]);
        
        $response = $this->getJson("/api/films/{$film->id}/actors");
        $response->assertStatus(204);
    }

    public function testShowFilmWithCriticsWithExistingFilmAndCritics()
    {
        $this->seed();
    
        $user = User::create([
            'login' => self::USER_LOGIN,
            'password' => bcrypt(self::USER_PASSWORD),
            'email' => self::USER_EMAIL,
            'last_name' => self::USER_LAST_NAME,
            'first_name' => self::USER_FIRST_NAME
        ]);
    
        $film = Film::create([
            'title' => self::FILM_TITLE,
            'release_year' => self::FILM_RELEASE_YEAR,
            'length' => self::FILM_LENGTH,
            'description' => self::FILM_DESCRIPTION,
            'rating' => self::FILM_RATING,
            'language_id' => self::FILM_LANGUAGE_ID,
            'special_features' => self::FILM_SPECIAL_FEATURES,
            'image' => self::FILM_IMAGE
        ]);
    
        $critic = Critic::create([
            'user_id' => $user->id,
            'film_id' => $film->id,
            'score' => self::CRITIC_SCORE,
            'comment' => self::CRITIC_COMMENT
        ]);
    
        $response = $this->getJson("/api/films/{$film->id}/critics");
    
        $response->assertStatus(200);
    
        $response->assertJsonFragment([
            'id' => $film->id,
            'title' => $film->title,
        ]);
    
        $response->assertJsonFragment([
            'id' => $critic->id,
            'user_id' => $critic->user_id,
            'film_id' => $critic->film_id,
            'score' => $critic->score,
            'comment' => $critic->comment,
        ]);
    }

    public function testShowFilmWithCriticsWithExistingFilmButNoCritics()
    {
        $this->seed();
    
        $film = Film::create([
            'title' => self::FILM_TITLE,
            'release_year' => self::FILM_RELEASE_YEAR,
            'length' => self::FILM_LENGTH,
            'description' => self::FILM_DESCRIPTION,
            'rating' => self::FILM_RATING,
            'language_id' => self::FILM_LANGUAGE_ID,
            'special_features' => self::FILM_SPECIAL_FEATURES,
            'image' => self::FILM_IMAGE
        ]);
    
        $response = $this->getJson("/api/films/{$film->id}/critics");
    
        $response->assertStatus(204);
    
        $this->assertEmpty($response->getContent());
    }    

    public function testShowFilmWithCriticsWithNonExistentFilm()
    {
        $this->seed();

        $response = $this->getJson("/api/films/" . self::NON_EXISTENT_FILM_ID . "/critics");

        $response->assertStatus(404);
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals('Film not found', $responseContent['message']);
    }
}
