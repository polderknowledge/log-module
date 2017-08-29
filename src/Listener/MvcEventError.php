<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
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
