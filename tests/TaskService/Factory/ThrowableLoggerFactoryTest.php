<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\TaskService\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\TaskService\ThrowableLogger;
use Psr\Log\LoggerInterface;

final class ThrowableLoggerFactoryTest extends TestCase
{
    public function testLoggerCalled()
    {
        // Arrange
        $logger = $this->getMockForAbstractClass(LoggerInterface::class);

        $container = $this->getMockForAbstractClass(ContainerInterface::class);
        $container->expects($this->once())->method('get')->willReturn($logger);

        $factory = new ThrowableLoggerFactory();

        // Act
        $result = $factory->__invoke($container, ThrowableLogger::class);

        // Assert
        static::assertInstanceOf(ThrowableLogger::class, $result);
    }
}
