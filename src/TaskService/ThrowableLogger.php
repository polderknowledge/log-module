<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\TaskService;

use Throwable;
use Psr\Log\LoggerInterface;

/**
 * A helper class that logs a throwable object to a logger.
 */
final class ThrowableLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Initializes a new instance of this class.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Logs a throwable object.
     *
     * @param Throwable $throwable The throwable object to log.
     * @return void
     */
    public function logThrowable(Throwable $throwable)
    {
        while ($throwable) {
            $this->logger->error($throwable->getMessage(), [
                $throwable->getCode(),
                $throwable->getFile(),
                $throwable->getLine(),
                $throwable->getTrace()
            ]);

            $throwable = $throwable->getPrevious();
        }
    }
}
