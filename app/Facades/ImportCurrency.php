<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ImportCurrency extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ImportCurrency';
    }
}