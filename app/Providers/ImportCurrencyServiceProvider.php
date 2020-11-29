<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ImportCurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ImportCurrency', 'App\Services\XmlImportCurrencyService');
    }
}
