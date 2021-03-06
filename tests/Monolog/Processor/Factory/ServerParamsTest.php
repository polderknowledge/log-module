<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Processor\Factory;

use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\Monolog\Processor\ServerParams as ServerParamsProcessor;

final class ServerParamsTest extends TestCase
{
    public function testInvoke()
    {
        // Arrange
        $container = [];

        $factory = new ServerParams();

        // Act
        $result = $factory->__invoke($container);

        // Assert
        static::assertInstanceOf(ServerParamsProcessor::class, $result);
    }
}
