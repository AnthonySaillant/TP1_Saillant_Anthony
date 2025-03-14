<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Critic;

class CriticControllerTest extends TestCase
{
    use RefreshDatabase;
    
    const USER_ID = 1;
    const FILM_ID = 1;
    const SCORE = 5;
    const COMMENT = 'Excellent movie!';
    const CRITIC_DELETED_SUCCESSFULLY = 'Critique deleted successfully.';
    const CRITIC_NOT_FOUND = 'Critic not found';

    public function testDestroyCriticSuccessfully()
    {
        $critic = Critic::create([
            'user_id' => self::USER_ID,
            'film_id' => self::FILM_ID,
            'score' => self::SCORE,
            'comment' => self::COMMENT,
        ]);

        $response = $this->deleteJson("/api/critics/{$critic->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => self::CRITIC_DELETED_SUCCESSFULLY,
        ]);

        $this->assertDatabaseMissing('critics', [
            'id' => $critic->id,
        ]);
    }

    public function testDestroyCriticNotFound()
    {
        $response = $this->deleteJson('/api/critics/9999');

        $response->assertStatus(404);
        $response->assertJson([
            'message' => self::CRITIC_NOT_FOUND,
        ]);
    }
}
