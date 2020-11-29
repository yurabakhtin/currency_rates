<?php

namespace App\Console\Commands;

use App\Facades\ImportCurrency;
use Illuminate\Console\Command;

class CurrencyImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:import {xml_file : URL or path to XML file}';

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
        $this->info('Importing currency rates from: ' . $this->argument('xml_file') . '...');

        $importResult = ImportCurrency::run($this->argument('xml_file'), $this);

        if (is_array($importResult)) {
            foreach ($importResult as $resultType => $resultNumber) {
                $this->comment('* '.ucfirst($resultType).': ' . $resultNumber);
            }
        }

        $this->info('Import has been finished.');
    }
}
