<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool|array run(string $source, $logger = null)
 * @method static bool|array getXmlData($source)
 * @method static \App\Models\CurrencyRate|string importRate($currencyXmlElement, $date)
 * @method static string getFormattedDate($xmlElement, $sourceFormat = 'd.m.Y', $targetFormat = 'Y-m-d')
 *
 * @see \App\Services\XmlImportCurrencyService
 */
class ImportCurrency extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ImportCurrency';
    }
}