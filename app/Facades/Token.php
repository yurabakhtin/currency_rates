<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\ApiTokenService user(\App\Models\User $user)
 * @method static string getNew(int $length = 60)
 * @method static void delete()
 * @method static void save(string $token)
 * @method static string hash(string $token)
 *
 * @see \App\Services\ApiTokenService
 */
class Token extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Token';
    }
}