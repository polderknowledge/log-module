<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\TaskService\Factory;

use Interop\Container\ContainerInterface;
use PolderKnowledge\LogModule\TaskService\ThrowableLogger;
use Zend\ServiceManager\Factory\FactoryInterface;

final class ThrowableLoggerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $errorLogger = $container->get('ErrorLogger'); // @todo Should we make this dynamic?

        return new ThrowableLogger($errorLogger);
    }
}
