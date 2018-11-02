<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Handler\Factory;

use Monolog\Handler\StreamHandler;
use PHPUnit\Framework\TestCase;

final class DailyStreamTest extends TestCase
{
    public function testInvoke()
    {
        // Arrange
        $options = [];

        $factory = new DailyStream();

        // Act
        $result = $factory->__invoke($options);

        // Assert
        static::assertInstanceOf(StreamHandler::class, $result);
    }
}
