<?php

namespace Tests\Feature\Controllers\Api;

use App\Facades\Token;
use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class CurrencyRateControllerTest extends TestCase
{
    /**
     * @var array Headers for token authorization
     */
    private $token_headers;

    /**
     * @var CurrencyRate
     */
    private $currencyRate;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');

        // Create one fake User for token authorization:
        $user = User::factory()->createOne();

        $this->token_headers = [
            'HTTP_Authorization' => 'Bearer ' . Token::user($user)->getNew(16)
        ];

        // Create one fake Currency with one Rate:
        $currency = Currency::factory()
            ->hasRates(1)
            ->createOne(['code' => Str::random(3)]);
        $this->currencyRate = $currency->rates[0];
        $this->api_prefix = '/api/currency/' . $currency->id . '/';
    }

    /**
     * A feature test to get all currencies
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->getJson($this->api_prefix . 'rate');
        $response->assertStatus(401);

        $response = $this->withHeaders($this->token_headers)
            ->getJson($this->api_prefix . 'rate');
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
            ->getJson($this->api_prefix . 'rate/12345678');
        $response->assertStatus(404);

        $response = $this->withHeaders($this->token_headers)
            ->getJson($this->api_prefix . 'rate/' . $this->currencyRate->id);
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'date', 'denomination', 'value']);
    }

    /**
     * A feature test to delete a currency by ID
     *
     * @return void
     */
    public function testDestroy()
    {
        $response = $this->withHeaders($this->token_headers)
            ->deleteJson($this->api_prefix . 'rate/12345678');
        $response->assertStatus(404);

        $response = $this->withHeaders($this->token_headers)
            ->deleteJson($this->api_prefix . 'rate/' . $this->currencyRate->id);
        $response->assertStatus(200)
            ->assertExactJson(['message' => 'Currency Rate has been deleted']);
    }
}
