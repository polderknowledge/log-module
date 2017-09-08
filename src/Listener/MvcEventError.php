<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Listener;

use Psr\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

/**
 * This class acts as an event listener so that we can log errors that occur in zendframework/zend-mvc.
 */
final class MvcEventError
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Gets the logger that is used.
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
    
    /**
     * Should be called when an MvcEvent is fired.
     *
     * @param MvcEvent $event The event that is fired.
     * @return void
     */
    public function __invoke(MvcEvent $event)
    {
        if ($event->getError() !== 'error-exception') {
            return;
        }

        $exception = $event->getParam('exception');
        $logMessages = [];

        do {
            $extra = array(
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace(),
            );
            if (isset($exception->xdebug_message)) {
                $extra['xdebug'] = $exception->xdebug_message;
            }

            $logMessages[] = array(
                'message' => $exception->getMessage(),
                'extra' => $extra,
            );
            $exception = $exception->getPrevious();
        } while ($exception);

        foreach (array_reverse($logMessages) as $logMessage) {
            $this->logger->error($logMessage['message'], $logMessage['extra']);
        }
    }
}
