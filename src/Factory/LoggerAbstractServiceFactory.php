<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Factory;

use Interop\Container\ContainerInterface;
use WShafer\PSR11MonoLog\ChannelChanger;
use Zend\Log\Logger as ZendLogger;
use Zend\Log\Writer\Psr;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

final class LoggerAbstractServiceFactory implements AbstractFactoryInterface
{
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $channelChanger = $container->get(ChannelChanger::class);

        return $channelChanger->has($requestedName);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $channelChanger = $container->get(ChannelChanger::class);

        $channel = $channelChanger->get($requestedName);

        if (!$channel) {
            return null;
        }

        /** @var array $config */
        $config = $container->get('config');

        if ($this->isZendLogger($config['monolog']['channels'], $requestedName)) {
            $zendLogger = new ZendLogger();
            $zendLogger->addWriter(new Psr($channel));

            $channel = $zendLogger;
        }

        return $channel;
    }

    private function isZendLogger(array $channels, $requestedName)
    {
        if (!array_key_exists('zend-log', $channels[$requestedName])) {
            return false;
        }

        return $channels[$requestedName]['zend-log'] === true;
    }
}
