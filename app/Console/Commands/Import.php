<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\CurrencyRate;
use DateTime;
use Illuminate\Console\Command;
use SimpleXMLElement;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currate:import {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import currency rates from xml file';

    /**
     * Execute the console command to import currency rates
     */
    public function handle()
    {
        $this->info('Importing currency rates from: ' . $this->argument('url') . '...');

        $importResult = $this->importCurrencies($this->argument('url'));

        if (is_array($importResult)) {
            foreach ($importResult as $resultType => $resultNumber) {
                $this->comment('* '.ucfirst($resultType).': ' . $resultNumber);
            }
        }

        $this->info('Import has been finished.');
    }

    /**
     * Import currency rates
     *
     * @param string URL or path to XML file
     * @return bool|array
     */
    private function importCurrencies($source)
    {
        $currenciesXmlData = $this->getCurrenciesXmlData($source);
        if (!$currenciesXmlData) {
            return false;
        }

        $this->comment('Date: ' . $currenciesXmlData['date']);

        $currenciesNumber = [
            'processed' => 0,
            'imported' => 0,
            'failed' => 0,
        ];

        foreach ($currenciesXmlData['currencies'] as $currencyXmlElement) {
            /** @var $currencyXmlElement SimpleXMLElement */
            $currenciesNumber['processed']++;

            $currencyRate = $this->importCurrencyRate($currencyXmlElement, $currenciesXmlData['date']);

            if (is_string($currencyRate)) {
                $currenciesNumber['failed']++;
                $this->warn(' - Error: ' . $currencyRate . ' => <fg=black;bg=red>' . $currencyXmlElement->asXML() . '</>');
                continue;
            }

            $currenciesNumber['imported']++;
            $this->info(' - Currency: #' . $currencyRate->getCurrency()->internal_id . ' (' . $currencyRate->getCurrency()->code . ') ' . $currencyRate->value . ' - Imported.');
        }

        return $currenciesNumber;
    }

    /**
     * Get currency rates XML data from provided source url/file
     *
     * @param string URL or path to XML file
     * @return bool|array
     */
    private function getCurrenciesXmlData($source)
    {
        libxml_use_internal_errors(true);
        $currenciesXmlData = simplexml_load_file($source);
        if ($currenciesXmlData === false) {
            foreach (libxml_get_errors() as $error) {
                $this->error($error->message);
            }
            return false;
        }

        if (!$currenciesXmlData->count() ||
            !$currenciesXmlData->children()->count()) {
            $this->warn('XML file has no records!');
            return false;
        }

        if ($currenciesXmlData->getName() != 'ValCurs' ||
            $currenciesXmlData->children()->getName() != 'Valute') {
            $this->error('XML file has wrong data!');
            return false;
        }

        return [
            'date' => $this->getImportDate($currenciesXmlData),
            'currencies' => $currenciesXmlData->children(),
        ];
    }

    /**
     * Get date from attribute of XML element
     *
     * @param SimpleXMLElement $xmlElement
     * @param string Source date format
     * @param string Target date format
     * @return string
     */
    private function getImportDate($xmlElement, $sourceFormat = 'd.m.Y', $targetFormat = 'Y-m-d')
    {
        $attributes = $xmlElement->attributes();

        // Check date to required format:
        if ($attributes &&
            isset($attributes->Date) &&
            ($dateTime = DateTime::createFromFormat($sourceFormat, $attributes->Date)) &&
            $dateTime->format($sourceFormat) == $attributes->Date) {
            return $dateTime->format($targetFormat);
        }

        // Use current date by default if date from XML is wrong:
        return date($targetFormat);
    }

    /**
     * Import currency rate
     *
     * @param SimpleXMLElement $currencyXmlElement
     * @param string Date in format 'YYYY-mm-dd'
     * @return CurrencyRate|string Currency object on success import, or Error message on fail
     */
    private function importCurrencyRate($currencyXmlElement, $date)
    {
        $validColumns = ['ID', 'NumCode', 'CharCode', 'Name', 'Nominal', 'Value'];
        $xmlData = [];

        $xmlAttributes = $currencyXmlElement->attributes();
        if ($xmlAttributes && isset($xmlAttributes->ID)) {
            $xmlData['ID'] = $xmlAttributes->ID->__toString();
        }

        $xmlProperties = $currencyXmlElement->children();
        foreach ($xmlProperties as $property) {
            if (in_array($property->getName(), $validColumns)) {
                $xmlData[$property->getName()] = $property->__toString();
            }
        }

        if (count($xmlData) != count($validColumns)) {
            return 'No required properties!';
        }

        $currency = Currency::where('internal_id', $xmlData['ID'])
            ->orWhere('number', $xmlData['NumCode'])
            ->orWhere('code', $xmlData['CharCode'])
            ->first();
        if (!$currency) {
            $currency = new Currency;
        }
        $currency->internal_id = $xmlData['ID'];
        $currency->number = $xmlData['NumCode'];
        $currency->code = $xmlData['CharCode'];
        $currency->name = $xmlData['Name'];

        if (!$currency->save()) {
            return 'Cannot save Currency into DB!';
        }

        $currencyRate = CurrencyRate::where('currency_id', $currency->id)
            ->where('date', $date)
            ->first();
        if (!$currencyRate) {
            $currencyRate = new CurrencyRate;
            $currencyRate->currency_id = $currency->id;
            $currencyRate->date = $date;
        }
        $currencyRate->denomination = $xmlData['Nominal'];
        $currencyRate->value = str_replace(',', '.', $xmlData['Value']);

        if (!$currencyRate->save()) {
            return 'Cannot save Currency Rate into DB!';
        }

        return $currencyRate;
    }
}
