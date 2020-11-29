<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\ApiTokenService;
use Tests\TestCase;

class ApiTokenServiceTest extends TestCase
{
    /**
     * @var ApiTokenService
     */
    private $apiTokenService;

    /**
     * @var int
     */
    private $tokenLength = 16;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiTokenService = new ApiTokenService();
    }

    /**
     * A unit test to get new token
     *
     * @return void
     */
    public function testGetNew()
    {
        $token = $this->apiTokenService->getNew($this->tokenLength);
        $this->assertIsString($token);
        $this->assertMatchesRegularExpression('/^[a-z0-9]{' . $this->tokenLength . '}$/i', $token);
    }

    /**
     * A unit test to get new token
     *
     * @return void
     */
    public function testHash()
    {
        $hashedToken = $this->apiTokenService->hash('');
        $this->assertNull($hashedToken);

        $token = $this->apiTokenService->getNew($this->tokenLength);
        $hashedToken = $this->apiTokenService->hash($token);
        $this->assertEquals($hashedToken, hash('sha256', $token));
    }

    /**
     * A unit test to link user
     *
     * @return void
     */
    public function testUser()
    {
        $apiTokenService = $this->apiTokenService->user(new User());
        $this->assertInstanceOf(ApiTokenService::class, $apiTokenService);
    }
}
