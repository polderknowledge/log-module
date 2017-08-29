<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Factory;

use WShafer\PSR11MonoLog\ChannelChanger;
use Zend\Log\Logger as ZendLogger;
use Zend\Log\Writer\Psr;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

final class LoggerAbstractServiceFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $channelChanger = $serviceLocator->get(ChannelChanger::class);

        return $channelChanger->has($requestedName);
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $channelChanger = $serviceLocator->get(ChannelChanger::class);

        $channel = $channelChanger->get($requestedName);

        /** @var array $config */
        $config = $serviceLocator->get('config');

        if ($this->isZendLogger($config['monolog']['channels'][$requestedName])) {
            $zendLogger = new ZendLogger();
            $zendLogger->addWriter(new Psr($channel));

            $channel = $zendLogger;
        }

        return $channel;
    }

    private function isZendLogger(array $config)
    {
        if (!array_key_exists('zend-log', $config)) {
            return false;
        }

        return $config['zend-log'] === true;
    }
}
