<?php

namespace Tests\Unit\Services;

use App\Services\XmlImportCurrencyService;
use Tests\TestCase;

class XmlImportCurrencyServiceTest extends TestCase
{
    /**
     * @var XmlImportCurrencyService
     */
    private $xmlImportCurrencyService;

    /**
     * @var string
     */
    private $sourceXml;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sourceXml = resource_path('test/XML_daily.xml');

        $this->xmlImportCurrencyService = new XmlImportCurrencyService();
    }

    /**
     * A unit test for import currencies
     *
     * @return void
     */
    public function testRun()
    {
        $results = $this->xmlImportCurrencyService->run($this->sourceXml);

        $this->assertEquals([
            'date' => '2020-11-25',
            'processed' => 4,
            'imported' => 4,
            'failed' => 0,
        ], $results);
    }
}
