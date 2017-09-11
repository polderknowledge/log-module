<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\TaskService;

use Exception;
use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\TaskService\ThrowableLogger;
use Psr\Log\LoggerInterface;

final class ThrowableLoggerTest extends TestCase
{
    public function testLoggerCalled()
    {
        // Arrange
        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $throwableLogger = new ThrowableLogger($logger);
        $throwable = new Exception();

        // Act
        $throwableLogger->logThrowable($throwable);

        // Assert
        // ... defined in arrange ...
    }

    public function testLoggerCalledForPrevious()
    {
        // Arrange
        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $logger->expects($this->exactly(2))->method('error');

        $throwableLogger = new ThrowableLogger($logger);
        $throwable = new Exception('', 0, new Exception());

        // Act
        $throwableLogger->logThrowable($throwable);

        // Assert
        // ... defined in arrange ...
    }
}
