<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Controller\Plugin;

use PolderKnowledge\LogModule\Helper\ThrowableLogger;
use Throwable;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

final class LogThrowable extends AbstractPlugin
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
     * Called when a throwable should be logged.
     *
     * @param Throwable $throwable
     */
    public function __invoke(Throwable $throwable)
    {
        $this->logger->logThrowable($throwable);
    }
}
