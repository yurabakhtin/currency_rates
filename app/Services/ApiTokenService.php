<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class ApiTokenService
{
    /**
     * @var User
     */
    private $user;

    /**
     * Set User to work with token
     *
     * @param User $user
     * @return ApiTokenService
     */
    public function user(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get new token
     *
     * @param int $length
     * @return string
     */
    public function getNew(int $length = 60)
    {
        // Search unique token in users table:
        do {
            $token = Str::random($length);
        } while(User::where(['api_token' => $this->hash($token)])->exists());

        $this->save($token);

        return $token;
    }

    /**
     * Delete token from the User
     */
    public function delete()
    {
        $this->save('');
    }

    /**
     * Save token to the User
     *
     * @param string
     */
    private function save(string $token)
    {
        if ($this->user) {
            $this->user->api_token = $this->hash($token);
            $this->user->update();
        }
    }

    /**
     * Hash token
     *
     * @param string
     * @return string
     */
    public function hash(string $token)
    {
        return empty($token) ? null : hash('sha256', $token);
    }
}