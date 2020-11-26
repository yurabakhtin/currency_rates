<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {username : User name}
        {--p|password= : Set user password; Auto set same as username if not passed}
        {--e|email= : Set user E-mail address; Auto generated from username if not passed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        if (empty($email)) {
            // Generate email from username:
            $email = $this->argument('username') . '@fake.email';
            $this->warn('Email "' . $email . '" is generated from username.');
        } else if (!Validator::make(['email' => $email], ['email' => 'email'])->passes()) {
            // Don't create a user with wrong passed email:
            $this->error('Email "' . $email . '" is wrong!');
            return;
        }

        if (User::where('email', $email)->exists()) {
            $this->error('User already exists with email "' . $email . '"!');
            return;
        }

        $password = $this->option('password');
        if (empty($password)) {
            // Use password same as username if it is not provided:
            $password = $this->argument('username');
            $this->warn('Password is assigned same as username.');
        }

        $user = new User;
        $user->email = $email;
        $user->name = $this->argument('username');
        $user->password = Hash::make($password);

        if (!$user->save()) {
            $this->error('Cannot save User into DB!');
        }

        $this->info('User "' . $user->name . '" has been created.');
    }
}
