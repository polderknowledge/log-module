<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledgeTest\Log\Listener;

use Exception;
use PHPUnit_Framework_TestCase;
use PolderKnowledge\LogModule\Listener\MvcEventError;
use Zend\Log\LoggerInterface;
use Zend\Mvc\MvcEvent;

class MvcEventErrorTest extends PHPUnit_Framework_TestCase
{
    public function testLoggerIssetOnConstruct()
    {
        $loggerMock = $this->getMock(LoggerInterface::class);
        
        $mvcEventError = new MvcEventError($loggerMock);
        $this->assertSame($mvcEventError->getLogger(), $loggerMock);
    }
    
    /**
     * @dataProvider expectedParamsProvider
     */
    public function testLoggerIsCalledWithExpectedParams($exception, $extra)
    {
        $loggerMock = $this->getMock(LoggerInterface::class);
        $loggerMock->expects($this->once())->method('err')->with('Exception', $extra);
        $eventMock = $this->getMock(
            MvcEvent::class,
            array('getError', 'getParam')
        );
        
        $eventMock->expects($this->atLeastOnce())->method('getError')->willReturn('error-exception');
        $eventMock->expects($this->atLeastOnce())->method('getParam')->with('exception')->willReturn($exception);
        
        $mvcEventError = new MvcEventError($loggerMock);
        $mvcEventError->onError($eventMock);
    }
    
    public function expectedParamsProvider()
    {
        $exception = new Exception('Exception');
        $exceptionWithXDebug = new Exception('Exception');
        $exceptionWithXDebug->xdebug_message = 'xdebug_message';
        
        $extra = array(
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        );
        
        $extraWithXDebug = array(
            'file' => $exceptionWithXDebug->getFile(),
            'line' => $exceptionWithXDebug->getLine(),
            'trace' => $exceptionWithXDebug->getTrace(),
            'xdebug' => 'xdebug_message',
        );
        
        return array(
            array($exception, $extra),
            array($exceptionWithXDebug, $extraWithXDebug)
        );
    }
    
    public function testLoggerIsCalledForPreviousExceptions()
    {
        $loggerMock = $this->getMock(LoggerInterface::class);
        $loggerMock->expects($this->exactly(2))->method('err');
        $eventMock = $this->getMock(
            MvcEvent::class,
            array('getError', 'getParam')
        );
        
        $previousException = new Exception('PreviousException');
        $exception = new Exception('Exception', null, $previousException);
        
        $eventMock->expects($this->atLeastOnce())->method('getError')->willReturn('error-exception');
        $eventMock->expects($this->atLeastOnce())->method('getParam')->with('exception')->willReturn($exception);
        
        $mvcEventError = new MvcEventError($loggerMock);
        $mvcEventError->onError($eventMock);
    }
    
    public function testLoggerIsNotCalledWhenNoError()
    {
        $loggerMock = $this->getMock(LoggerInterface::class);
        $loggerMock->expects($this->never())->method('err');
        $eventMock = $this->getMock(MvcEvent::class);
        
        $mvcEventError = new MvcEventError($loggerMock);
        $mvcEventError->onError($eventMock);
    }
}
