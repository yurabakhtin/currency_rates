<?php

namespace Tests\Unit\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');
    }

    /**
     * A unit test for console command to create a user
     *
     * @return void
     */
    public function testUserCreate()
    {
        $testUserName = Str::random(10);
        $testUserEmail = $testUserName . '@fake.email';
        $messages = [
            'generated_email' => 'Email "%s" is generated from username.',
            'same_password'   => 'Password is assigned same as username.',
            'user_created'    => 'User "%s" has been created.',
            'duplicated_user' => 'User already exists with email "%s"!',
        ];

        $this->artisan('user:create', [
                'username' => $testUserName
            ])
            ->expectsOutput(sprintf($messages['generated_email'], $testUserEmail))
            ->expectsOutput($messages['same_password'])
            ->expectsOutput(sprintf($messages['user_created'], $testUserName))
            ->assertExitCode(0);

        $this->artisan('user:create', [
                'username' => $testUserName
            ])
            ->expectsOutput(sprintf($messages['generated_email'], $testUserEmail))
            ->expectsOutput(sprintf($messages['duplicated_user'], $testUserEmail))
            ->assertExitCode(0);

        $testUserName = 'user_with_email';
        $this->artisan('user:create', [
                'username' => $testUserName,
                '--email' => $testUserName . '@mail.fake'
            ])
            ->expectsOutput($messages['same_password'])
            ->expectsOutput(sprintf($messages['user_created'], $testUserName))
            ->assertExitCode(0);

        $testUserName = 'user_with_pass';
        $this->artisan('user:create', [
                'username' => $testUserName,
                '--password' => $testUserName,
                '--email' => $testUserName . '@mail.fake'
            ])
            ->expectsOutput(sprintf($messages['user_created'], $testUserName))
            ->assertExitCode(0);
    }
}
