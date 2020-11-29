<?php
namespace App\Services;

use App\Interfaces\XmlImportInterface;
use App\Models\Currency;
use App\Models\CurrencyRate;
use DateTime;
use SimpleXMLElement;

class XmlImportCurrencyService extends BaseImportService implements XmlImportInterface
{
    /**
     * @inheritdoc
     */
    public function run(string $source, $logger = null)
    {
        // Initialize logger:
        $this->logger = $logger;

        $currenciesXmlData = $this->getXmlData($source);
        if (!$currenciesXmlData) {
            return false;
        }

        $this->comment('Date: ' . $currenciesXmlData['date']);

        $currenciesNumber = [
            'date' => $currenciesXmlData['date'],
            'processed' => 0,
            'imported' => 0,
            'failed' => 0,
        ];

        foreach ($currenciesXmlData['currencies'] as $currencyXmlElement) {
            /** @var $currencyXmlElement SimpleXMLElement */
            $currenciesNumber['processed']++;

            $currencyRate = $this->importRate($currencyXmlElement, $currenciesXmlData['date']);

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
     * @inheritdoc
     */
    public function getXmlData($source)
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
            'date' => $this->getFormattedDate($currenciesXmlData),
            'currencies' => $currenciesXmlData->children(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function importRate($currencyXmlElement, $date)
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

    /**
     * Get date from attribute of XML element
     *
     * @param SimpleXMLElement $xmlElement
     * @param string Source date format
     * @param string Target date format
     * @return string
     */
    private function getFormattedDate($xmlElement, $sourceFormat = 'd.m.Y', $targetFormat = 'Y-m-d')
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
}