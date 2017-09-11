<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Processor\Factory;

use PolderKnowledge\LogModule\Monolog\Processor\ServerParams as ServerParamsProcessor;
use WShafer\PSR11MonoLog\FactoryInterface;

final class ServerParams implements FactoryInterface
{
    public function __invoke(array $options)
    {
        return new ServerParamsProcessor();
    }
}
