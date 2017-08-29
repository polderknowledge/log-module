<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Monolog\Handler;

use DateTime;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;

final class CachedInterval extends AbstractProcessingHandler
{
    /**
     * The handler to call when flushing.
     *
     * @var HandlerInterface
     */
    private $handler;

    /**
     * The interval in seconds before the cache is flushed.
     *
     * @var int
     */
    private $interval;

    /**
     * The path to the file which should be used to cache the records.
     *
     * @var string
     */
    private $store;

    /**
     * Initializes a new instance of this class.
     *
     * @param HandlerInterface $handler
     * @param int $interval
     * @param string $store
     * @param int $level
     * @param bool $bubble
     */
    public function __construct(
        HandlerInterface $handler,
        int $interval,
        string $store = null,
        int $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);

        $this->handler = $handler;
        $this->interval = $interval;
        $this->store = $store ?? sys_get_temp_dir() . '/monolog-cachedinterval-' . substr(md5(__FILE__), 0, 20) .'.log';
    }

    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        // Remove the formatted context, it's not relevant for us.
        unset($record['formatted']);

        if ($this->isExpired()) {
            $this->flush();
        } else {
            $this->appendLog($record);
        }
    }

    private function isExpired()
    {
        $handle = fopen($this->store, 'a+');
        $line = fgets($handle);
        fclose($handle);

        if (!$line) {
            return false;
        }

        $log = unserialize($line);

        /** @var DateTime $logDate */
        $logDate = $log['datetime'];

        return $logDate->getTimestamp() + $this->interval < time();
    }

    private function flush()
    {
        $lines = file($this->store);

        $logs = [];

        foreach ($lines as $line) {
            $logs[] = unserialize($line);
        }

        $this->handler->handleBatch($logs);

        unlink($this->store);
    }

    private function appendLog(array $record)
    {
        file_put_contents($this->store, serialize($record) . "\n", FILE_APPEND);
    }
}
