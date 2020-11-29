<?php
namespace App\Services;

use Illuminate\Console\Concerns\InteractsWithIO;

class BaseImportService
{
    /**
     * @var object|InteractsWithIO Logger
     */
    protected $logger;

    /**
     * Log a message
     *
     * @param string $message
     * @param string $type
     */
    protected function log(string $message, string $type)
    {
        if (is_object($this->logger) &&
            method_exists($this->logger, $type)) {
            $this->logger->$type($message);
        }
    }

    /**
     * Log a message as info
     *
     * @param string
     */
    protected function info($message)
    {
        $this->log($message, __FUNCTION__);
    }

    /**
     * Log a message as comment
     *
     * @param string
     */
    protected function comment($message)
    {
        $this->log($message, __FUNCTION__);
    }

    /**
     * Log a message as warning
     *
     * @param string
     */
    protected function warn($message)
    {
        $this->log($message, __FUNCTION__);
    }

    /**
     * Log a message as error
     *
     * @param string
     */
    protected function error($message)
    {
        $this->log($message, __FUNCTION__);
    }
}