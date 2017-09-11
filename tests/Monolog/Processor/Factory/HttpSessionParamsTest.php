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
use PolderKnowledge\LogModule\Monolog\Processor\HttpSessionParams as HttpSessionParamsProcessor;

final class HttpSessionParamsTest extends TestCase
{
    public function testInvoke()
    {
        // Arrange
        $container = [];

        $factory = new HttpSessionParams();

        // Act
        $result = $factory->__invoke($container);

        // Assert
        static::assertInstanceOf(HttpSessionParamsProcessor::class, $result);
    }
}
