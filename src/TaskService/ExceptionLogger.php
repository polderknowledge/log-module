<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\TaskService;

use Exception;
use Zend\Log\Logger;

final class ExceptionLogger
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param Exception $e
     */
    public function logException(Exception $e)
    {
        $trace = $e->getTraceAsString();

        $i = 1;
        do {
            $messages[] = sprintf('%d: %s -> %s', $i++, get_class($e), $e->getMessage());
        } while ($e = $e->getPrevious());

        $log = "Exception:\n" . implode("\n", $messages) . "\n";
        $log .= "Trace:\n" . $trace;

        $this->logger->err($log);
    }
}
