<?php
namespace App\Interfaces;

use App\Models\CurrencyRate;
use Illuminate\Console\Concerns\InteractsWithIO;

interface XmlImportInterface
{
    /**
     * Run import of currencies with rates from XML source
     *
     * @param string URL or path to XML file
     * @param object|InteractsWithIO|null Logger
     * @return bool|array
     */
    public function run(string $source, $logger = null);

    /**
     * Get currency rates XML data from provided source url/file
     *
     * @param string URL or path to XML file
     * @return bool|array
     */
    public function getXmlData($source);

    /**
     * Import currency rate
     *
     * @param SimpleXMLElement $currencyXmlElement
     * @param string Date in format 'YYYY-mm-dd'
     * @return CurrencyRate|string Currency object on success import, or Error message on fail
     */
    public function importRate(SimpleXMLElement $currencyXmlElement, $date);
}