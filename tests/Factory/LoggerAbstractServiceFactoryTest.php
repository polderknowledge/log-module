<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use WShafer\PSR11MonoLog\ChannelChanger;
use Zend\Log\Logger;

final class LoggerAbstractServiceFactoryTest extends TestCase
{
    private $container;
    private $channelChanger;

    protected function setUp()
    {
        parent::setUp();

        $this->channelChanger = $this->getMockBuilder(ChannelChanger::class)->disableOriginalConstructor()->getMock();

        $this->container = $this->getMockForAbstractClass(ContainerInterface::class);
        $this->container->expects($this->at(0))->method('get')->with($this->equalTo(ChannelChanger::class))->willReturn($this->channelChanger);
    }

    public function testCanCreateWithExisting()
    {
        // Arrange
        $this->channelChanger->expects($this->once())->method('has')->willReturn(true);

        $factory = new LoggerAbstractServiceFactory();

        // Act
        $result = $factory->canCreate($this->container, 'existing');

        // Assert
        static::assertTrue($result);
    }

    public function testCanCreateWithNonExisting()
    {
        // Arrange
        $this->channelChanger->expects($this->once())->method('has')->willReturn(false);

        $factory = new LoggerAbstractServiceFactory();

        // Act
        $result = $factory->canCreate($this->container, 'non-existing');

        // Assert
        static::assertFalse($result);
    }

    public function testInvokeWithNonExisting()
    {
        // Arrange
        $this->channelChanger->expects($this->once())->method('get')->willReturn(null);

        $factory = new LoggerAbstractServiceFactory();

        // Act
        $result = $factory->__invoke($this->container, 'non-existing');

        // Assert
        static::assertNull($result);
    }

    /**
     * @dataProvider provideLoggerConfig
     */
    public function testInvokeWithExisting($loggerConfig, $expectedFqcn)
    {
        // Arrange
        $this->container->expects($this->at(1))->method('get')->with($this->equalTo('config'))->willReturn([
            'monolog' => [
                'channels' => [
                    'existing' => $loggerConfig,
                ],
            ],
        ]);

        $logger = $this->getMockForAbstractClass(LoggerInterface::class);

        $this->channelChanger->expects($this->once())->method('get')->willReturn($logger);

        $factory = new LoggerAbstractServiceFactory();

        // Act
        $result = $factory->__invoke($this->container, 'existing');

        // Assert
        static::assertInstanceOf($expectedFqcn, $result);
    }

    public function provideLoggerConfig()
    {
        return [
            [
                [
                    'zend-log' => true,
                ],
                Logger::class
            ],
            [
                [
                    'zend-log' => false,
                ],
                LoggerInterface::class
            ],
            [
                [
                ],
                LoggerInterface::class
            ],
        ];
    }
}
