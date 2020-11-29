<?php

namespace Tests\Feature\Controllers\Api;

use App\Facades\Token;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class CurrencyControllerTest extends TestCase
{
    /**
     * @var array Headers for token authorization
     */
    private $token_headers;

    /**
     * @var Currency
     */
    private $currency;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');

        // Create one fake User for token authorization:
        $user = User::factory()->createOne();

        $this->token_headers = [
            'HTTP_Authorization' => 'Bearer ' . Token::user($user)->getNew(16)
        ];

        // Create one fake Currency:
        $this->currency = Currency::factory()->createOne([
            'code' => Str::random(3)
        ]);
    }

    /**
     * A feature test to get all currencies
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->getJson('/api/currency');
        $response->assertStatus(401);

        $response = $this->withHeaders($this->token_headers)
            ->getJson('/api/currency');
        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'current_page', 'per_page', 'total']);
    }

    /**
     * A feature test to get a currency by ID
     *
     * @return void
     */
    public function testShow()
    {
        $response = $this->withHeaders($this->token_headers)
            ->getJson('/api/currency/12345678');
        $response->assertStatus(404);

        $response = $this->getJson('/api/currency/' . $this->currency->id, $this->token_headers);
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'internal_id', 'number', 'code', 'name', 'rates']);
    }

    /**
     * A feature test to delete a currency by ID
     *
     * @return void
     */
    public function testDestroy()
    {
        $response = $this->withHeaders($this->token_headers)
            ->deleteJson('/api/currency/12345678');
        $response->assertStatus(404);

        $response = $this->withHeaders($this->token_headers)
            ->deleteJson('/api/currency/' . $this->currency->id);
        $response->assertStatus(200)
            ->assertExactJson(['message' => 'Currency has been deleted']);
    }
}
