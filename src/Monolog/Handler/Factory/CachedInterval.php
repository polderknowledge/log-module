<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
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
