<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule;

use Monolog\ErrorHandler;
use PolderKnowledge\LogModule\Helper\ZendLogUtils;
use PolderKnowledge\LogModule\Listener\MvcEventError;
use PolderKnowledge\LogModule\Service\FormatterPluginManager;
use PolderKnowledge\LogModule\Service\HandlerPluginManager;
use PolderKnowledge\LogModule\Service\ProcessorPluginManager;
use Psr\Container\ContainerInterface;
use Zend\EventManager\EventInterface;
use Zend\Log\Logger;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;

final class Module implements ConfigProviderInterface, BootstrapListenerInterface, InitProviderInterface
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $event
     * @return array
     */
    public function onBootstrap(EventInterface $event)
    {
        /** @var MvcEvent $mvcEvent */
        $mvcEvent = $event;

        $container = $mvcEvent->getApplication()->getServiceManager();
        $mvcErrorLogger = $container->get(MvcEventError::class);
        $phpErrorLogger = $container->get('ErrorLogger');

        // @todo Remove this statement in the next major version. This is just here for backwards compatibility.
        if ($phpErrorLogger instanceof Logger) {
            $phpErrorLogger = ZendLogUtils::extractPsrLogger($phpErrorLogger);
        }

        ErrorHandler::register($phpErrorLogger);

        $eventManager = $mvcEvent->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, $mvcErrorLogger);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, $mvcErrorLogger);
    }

    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     * @return void
     */
    public function init(ModuleManagerInterface $manager)
    {
        // Load WShafer's PSR11MonoLog module so we have access to all Monolog factories.
        $manager->loadModule('WShafer\\PSR11MonoLog');
    }
}
