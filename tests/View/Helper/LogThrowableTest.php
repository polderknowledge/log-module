<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\View\Helper;

use Exception;
use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\TaskService\ThrowableLogger;
use Psr\Log\LoggerInterface;

final class LogThrowableTest extends TestCase
{
    public function testLoggerCalled()
    {
        // Arrange
        $exception = new Exception();

        $logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $logger->expects($this->once())->method('error');

        $logThrowable = new LogThrowable(new ThrowableLogger($logger));

        // Act
        $logThrowable->__invoke($exception);

        // Assert
        // ... defined when arranging the mock ...
    }
}
