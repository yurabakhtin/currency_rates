<?php

namespace Tests\Feature\Controllers\Api;

use App\Facades\Token;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');

        // Create one fake User for token authorization:
        $this->user = User::factory()->createOne();
    }

    /**
     * A feature test to get new user token / Log in
     *
     * @return void
     */
    public function testUpdate()
    {
        $response = $this->postJson('/api/token');
        $response->assertForbidden();

        $response = $this->postJson('/api/token', [
            'email' => $this->user->email,
            'password' => $this->user->name,
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /**
     * A feature test to clear user token / Log out
     *
     * @return void
     */
    public function testDelete()
    {
        $response = $this->deleteJson('/api/token');
        $response->assertUnauthorized();

        $response = $this->deleteJson('/api/token', [], [
            'HTTP_Authorization' => 'Bearer ' . Token::user($this->user)->getNew(16)
        ]);
        $response->assertStatus(200)
            ->assertExactJson(['message' => 'Token has been cleaned']);
    }
}
