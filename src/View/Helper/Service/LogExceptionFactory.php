<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\View\Helper\Service;

use PolderKnowledge\LogModule\TaskService\ExceptionLogger;
use PolderKnowledge\LogModule\View\Helper\LogException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LogExceptionFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ExceptionLogger $exceptionLogger */
        $exceptionLogger = $serviceLocator->getServiceLocator()->get(ExceptionLogger::class);
        
        return new LogException($exceptionLogger);
    }

}
