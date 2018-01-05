<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Listener;

use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

final class MvcEventErrorTest extends TestCase
{
    private $loggerMock;
    private $mvcEventError;

    protected function setUp()
    {
        parent::setUp();

        $this->loggerMock = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->mvcEventError = new MvcEventError($this->loggerMock);
    }

    public function testLoggerIssetOnConstruct()
    {
        // Arrange
        // ...

        // Act
        $result = $this->mvcEventError->getLogger();

        // Assert
        static::assertSame($this->loggerMock, $result);
    }

    public function testLoggerIsCalledWithExpectedParams()
    {
        // Arrange
        $exception = new Exception('Exception message');
        $this->loggerMock->expects($this->once())->method('error')->with('Exception message', ['exception' => $exception]);

        $eventMock = $this->createMock(MvcEvent::class);
        $eventMock->expects($this->atLeastOnce())->method('getError')->willReturn('error-exception');
        $eventMock->expects($this->atLeastOnce())->method('getParam')->with('exception')->willReturn($exception);

        // Act
        $this->mvcEventError->__invoke($eventMock);

        // Assert
        // ...
    }
    
    public function testLoggerIsNotCalledWhenNoError()
    {
        // Arrange
        $this->loggerMock->expects($this->never())->method('error');

        $eventMock = $this->createMock(MvcEvent::class);

        // Act
        $this->mvcEventError->__invoke($eventMock);

        // Assert
        // ...
    }
}
