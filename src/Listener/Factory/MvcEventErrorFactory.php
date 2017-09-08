<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Listener\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use PolderKnowledge\LogModule\Helper\ZendLogUtils;
use PolderKnowledge\LogModule\Listener\MvcEventError;
use Zend\Log\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * A factory class that creates an event listener for MVC event errors in zendframework/zend-mvc
 */
final class MvcEventErrorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, null);
    }

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $errorLogger = $container->get('ErrorLogger');

        // @todo Remove this statement in the next major release.
        if ($errorLogger instanceof Logger) {
            $errorLogger = ZendLogUtils::extractPsrLogger($errorLogger);
        }

        return new MvcEventError($errorLogger);
    }
}
