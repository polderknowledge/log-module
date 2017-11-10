<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use PolderKnowledge\LogModule\TaskService\ThrowableLogger;
use PolderKnowledge\LogModule\View\Helper\LogThrowable;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class LogThrowableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ThrowableLogger $throwableLogger */
        $throwableLogger = $container->get(ThrowableLogger::class);

        return new LogThrowable($throwableLogger);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), ThrowableLogger::class);
    }
}
