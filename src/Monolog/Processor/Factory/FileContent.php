<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Processor\Factory;

use PolderKnowledge\LogModule\Monolog\Processor\FileContent as Processor;
use WShafer\PSR11MonoLog\FactoryInterface;

final class FileContent implements FactoryInterface
{
    public function __invoke(array $options)
    {
        if (!array_key_exists('file_path', $options)) {
            throw new \InvalidArgumentException('Missing required option "file_path"');
        }

        if (!array_key_exists('field', $options)) {
            throw new \InvalidArgumentException('Missing required option "field"');
        }

        return new Processor($options['file_path'], $options['field']);
    }
}
