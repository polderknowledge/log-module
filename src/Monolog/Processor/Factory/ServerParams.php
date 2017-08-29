<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
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
