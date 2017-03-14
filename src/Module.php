<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule;

use PolderKnowledge\LogModule\Listener\MvcEventError;
use PolderKnowledge\LogModule\Service\LoggerServiceManager;
use Zend\Log\FilterPluginManager;
use Zend\Log\FormatterPluginManager;
use Zend\Log\ProcessorPluginManager;
use Zend\Log\WriterPluginManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * @param ModuleManagerInterface $manager
     */
    public function init(ModuleManagerInterface $manager)
    {
        $sm = $manager->getEvent()->getParam('ServiceManager');

        $serviceListener = $sm->get('ServiceListener');
        $serviceListener->addServiceManager(LoggerServiceManager::class, 'logger_service', '', '');
        $serviceListener->addServiceManager(WriterPluginManager::class, 'log_writer_plugin', '', '');
        $serviceListener->addServiceManager(FilterPluginManager::class, 'log_filter_plugin', '', '');
        $serviceListener->addServiceManager(FormatterPluginManager::class, 'log_formatter_plugin', '', '');
        $serviceListener->addServiceManager(ProcessorPluginManager::class, 'log_processor_plugin', '', '');

        $sharedManager = $manager->getEventManager()->getSharedManager();
        $sharedManager->attach('Zend\Mvc\Application', MvcEvent::EVENT_BOOTSTRAP, function (MvcEvent $event) {
            $application = $event->getApplication();
            $serviceManager = $application->getServiceManager();
            $loggerServiceManager = $serviceManager->get(LoggerServiceManager::class);
            $loggerServiceManager->get('ErrorLog', array('config_key' => 'error_logger'));
        }, PHP_INT_MAX);
    }

    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $eventManager = $application->getEventManager();
        $serviceManager = $application->getServiceManager();

        $callback = [$serviceManager->get(MvcEventError::class), 'onError'];

        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, $callback);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, $callback);
    }
}
