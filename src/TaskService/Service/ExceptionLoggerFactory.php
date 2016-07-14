<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\TaskService\Service;

use PolderKnowledge\LogModule\Service\LoggerServiceManager;
use PolderKnowledge\LogModule\TaskService\ExceptionLogger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class ExceptionLoggerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $loggerServiceManager = $serviceLocator->get(LoggerServiceManager::class);

        $errorLog = $loggerServiceManager->get('ErrorLog', array('config_key' => 'error_logger'));

        return new ExceptionLogger($errorLog);
    }
}
