<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use PolderKnowledge\LogModule\Controller\Plugin\LogThrowable;
use PolderKnowledge\LogModule\TaskService\ThrowableLogger;
use Zend\ServiceManager\Factory\FactoryInterface;

final class LogThrowableFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ThrowableLogger $throwableLogger */
        $throwableLogger = $container->get(ThrowableLogger::class);

        return new LogThrowable($throwableLogger);
    }
}
