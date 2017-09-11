<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\View\Helper;

use Exception;
use PolderKnowledge\LogModule\TaskService\ExceptionLogger;
use PolderKnowledge\LogModule\TaskService\ThrowableLogger;
use Throwable;
use Zend\View\Helper\AbstractHelper;

/**
 * A view helper that helps to log a throwable object.
 */
final class LogThrowable extends AbstractHelper
{
    /**
     * @var ThrowableLogger
     */
    private $logger;

    /**
     * Initializes a new instance of this class.
     *
     * @param ThrowableLogger $logger
     */
    public function __construct(ThrowableLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Called when the view helper is invoked.
     *
     * @param Throwable $throwable The throwable object to log.
     * @return void
     */
    public function __invoke(Throwable $throwable)
    {
        $this->logger->logThrowable($throwable);
    }
}
