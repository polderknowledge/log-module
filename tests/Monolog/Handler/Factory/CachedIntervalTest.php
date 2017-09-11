<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Handler\Factory;

use Monolog\Handler\HandlerInterface;
use PolderKnowledge\LogModule\Monolog\Handler\CachedInterval as CachedIntervalHandler;
use PHPUnit\Framework\TestCase;
use WShafer\PSR11MonoLog\Service\HandlerManager;

final class CachedIntervalTest extends TestCase
{
    /**
     * @expectedException \WShafer\PSR11MonoLog\Exception\MissingServiceException
     * @expectedExceptionMessage Handler Manager service not set
     */
    public function testInvokeWithInvalidHandler()
    {
        // Arrange
        $options = [];

        $factory = new CachedInterval();

        // Act
        $result = $factory->__invoke($options);

        // Assert
        static::assertInstanceOf(CachedIntervalHandler::class, $result);
    }

    public function testInvokeWithValidHandler()
    {
        // Arrange
        $options = [
            'handler' => 'MyHandler',
        ];

        $handler = $this->getMockForAbstractClass(HandlerInterface::class);

        $handlerManager = $this->getMockBuilder(HandlerManager::class)->disableOriginalConstructor()->getMock();
        $handlerManager->expects($this->once())->method('get')->with($this->equalTo('MyHandler'))->willReturn($handler);

        $factory = new CachedInterval();
        $factory->setHandlerManager($handlerManager);

        // Act
        $result = $factory->__invoke($options);

        // Assert
        static::assertInstanceOf(CachedIntervalHandler::class, $result);
    }
}
