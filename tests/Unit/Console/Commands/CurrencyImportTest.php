<?php

namespace Tests\Unit\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CurrencyImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');
    }

    /**
     * A unit test for console command to import currencies from XML source
     *
     * @return void
     */
    public function testHandle()
    {
        $xml_source = resource_path('test/XML_daily.xml');

        $this->artisan('currency:import', ['xml_file' => $xml_source])
            ->expectsOutput('Importing currency rates from: ' . $xml_source . '...')
            ->expectsOutput('Date: 2020-11-25')
            ->expectsOutput(' - Currency: #R01010 (AUD) 55.7844 - Imported.')
            ->expectsOutput(' - Currency: #R01020A (AZN) 44.6231 - Imported.')
            ->expectsOutput(' - Currency: #R01035 (GBP) 101.3869 - Imported.')
            ->expectsOutput(' - Currency: #R01060 (AMD) 15.2162 - Imported.')
            ->expectsOutput('* Date: 2020-11-25')
            ->expectsOutput('* Processed: 4')
            ->expectsOutput('* Imported: 4')
            ->expectsOutput('* Failed: 0')
            ->expectsOutput('Import has been finished.')
            ->assertExitCode(0);
    }
}
