<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledgeTest\Log\Listener;

use Exception;
use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\Listener\MvcEventError;
use Psr\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

class MvcEventErrorTest extends TestCase
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
    
    /**
     * @dataProvider expectedParamsProvider
     */
    public function testLoggerIsCalledWithExpectedParams($exception, $extra)
    {
        // Arrange
        $this->loggerMock->expects($this->once())->method('error')->with('Exception', $extra);

        $eventMock = $this->createMock(MvcEvent::class);
        $eventMock->expects($this->atLeastOnce())->method('getError')->willReturn('error-exception');
        $eventMock->expects($this->atLeastOnce())->method('getParam')->with('exception')->willReturn($exception);

        // Act
        $this->mvcEventError->__invoke($eventMock);

        // Assert
        // ...
    }
    
    public function expectedParamsProvider()
    {
        $exception = new Exception('Exception');
        $exceptionWithXDebug = new Exception('Exception');
        $exceptionWithXDebug->xdebug_message = 'xdebug_message';
        
        $extra = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ];
        
        $extraWithXDebug = [
            'file' => $exceptionWithXDebug->getFile(),
            'line' => $exceptionWithXDebug->getLine(),
            'trace' => $exceptionWithXDebug->getTrace(),
            'xdebug' => 'xdebug_message',
        ];
        
        return [
            [$exception, $extra],
            [$exceptionWithXDebug, $extraWithXDebug]
        ];
    }
    
    public function testLoggerIsCalledForPreviousExceptions()
    {
        // Arrange
        $previousException = new Exception('PreviousException');
        $exception = new Exception('Exception', null, $previousException);

        $this->loggerMock->expects($this->exactly(2))->method('error');

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
