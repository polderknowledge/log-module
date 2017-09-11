<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\TaskService\ThrowableLogger;
use PolderKnowledge\LogModule\View\Helper\LogThrowable;
use Psr\Log\LoggerInterface;

final class LogThrowableFactoryTest extends TestCase
{
    public function testLoggerCalled()
    {
        // Arrange
        $logger = new ThrowableLogger($this->getMockForAbstractClass(LoggerInterface::class));

        $container = $this->getMockForAbstractClass(ContainerInterface::class);
        $container->expects($this->once())->method('get')->willReturn($logger);

        $factory = new LogThrowableFactory();

        // Act
        $result = $factory->__invoke($container, LogThrowable::class);

        // Assert
        static::assertInstanceOf(LogThrowable::class, $result);
    }
}
