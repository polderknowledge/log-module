<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Listener;

use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

class MvcEventError implements LoggerAwareInterface
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
        $this->setLogger($logger);
    }
    
    /**
     * @param MvcEvent $event
     */
    public function onError(MvcEvent $event)
    {
        if ($event->getError() !== 'error-exception') {
            return;
        }

        $exception = $event->getParam('exception');
        $logger = $this->getLogger();
        $logMessages = array();

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
            $logger->err($logMessage['message'], $logMessage['extra']);
        }
    }

    /**
     * @param LoggerInterface $logger
     * @return \PolderKnowledge\LogModule\Listener\MvcEventError
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }
    
    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
