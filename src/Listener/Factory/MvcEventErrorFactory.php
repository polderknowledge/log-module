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
use PolderKnowledge\LogModule\Helper\ZendLogUtils;
use PolderKnowledge\LogModule\Listener\MvcEventError;
use Zend\Log\Logger;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * A factory class that creates an event listener for MVC event errors in zendframework/zend-mvc
 */
final class MvcEventErrorFactory implements FactoryInterface
{
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
