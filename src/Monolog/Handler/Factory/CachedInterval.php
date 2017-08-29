<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Monolog\Handler\Factory;

use PolderKnowledge\LogModule\Monolog\Handler\CachedInterval as CachedIntervalHandler;
use WShafer\PSR11MonoLog\FactoryInterface;
use WShafer\PSR11MonoLog\HandlerManagerAwareInterface;
use WShafer\PSR11MonoLog\HandlerManagerTrait;

final class CachedInterval implements FactoryInterface, HandlerManagerAwareInterface
{
    use HandlerManagerTrait;

    public function __invoke(array $options)
    {
        $handler = $this->getHandlerManager()->get($options['handler']);

        return new CachedIntervalHandler(
            $handler,
            $options['interval'] ?? 60,
            $options['store'] ?? null
        );
    }
}
