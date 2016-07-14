<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Listener\Service;

use PolderKnowledge\LogModule\Listener\MvcEventError;
use PolderKnowledge\LogModule\Service\LoggerServiceManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MvcEventErrorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $loggerManager = $serviceLocator->get(LoggerServiceManager::class);

        $errorLog = $loggerManager->get('ErrorLog', array('config_key' => 'error_logger'));
        
        return new MvcEventError($errorLog);
    }
}
