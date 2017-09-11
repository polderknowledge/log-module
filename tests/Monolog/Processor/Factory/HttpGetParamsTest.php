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
use PolderKnowledge\LogModule\Monolog\Processor\HttpGetParams as HttpGetParamsProcessor;

final class HttpGetParamsTest extends TestCase
{
    public function testInvoke()
    {
        // Arrange
        $container = [];

        $factory = new HttpGetParams();

        // Act
        $result = $factory->__invoke($container);

        // Assert
        static::assertInstanceOf(HttpGetParamsProcessor::class, $result);
    }
}
