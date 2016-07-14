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
     * @param Exception $exception
     */
    public function logException(Exception $exception)
    {
        while ($exception) {
            $this->logger->err($exception->getMessage(), [
                $exception->getCode(),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getTrace()
            ]);

            $exception = $exception->getPrevious();
        }
    }
}
