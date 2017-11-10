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
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

final class LoggerAbstractServiceFactoryTest extends TestCase
{
    private $channelChanger;

    protected function setUp()
    {
        parent::setUp();

        $this->channelChanger = $this->getMockBuilder(ChannelChanger::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return ContainerInterface
     */
    private function givenAContainer()
    {
        $container = $this->getMockForAbstractClass(ContainerInterface::class);
        $container->expects($this->at(0))->method('get')->with($this->equalTo(ChannelChanger::class))->willReturn($this->channelChanger);

        return $container;
    }

    private function givenAServiceManager()
    {
        $serviceLocator = $this->getMockBuilder(ServiceManager::class)->getMock();
        $serviceLocator->expects($this->at(0))->method('get')->with($this->equalTo(ChannelChanger::class))->willReturn($this->channelChanger);
        return $serviceLocator;
    }

    public function testCanCreateWithExisting()
    {
        // Arrange
        $this->channelChanger->expects($this->once())->method('has')->willReturn(true);

        $factory = new LoggerAbstractServiceFactory();

        // Act
        $result = $factory->canCreate($this->givenAContainer(), 'existing');

        // Assert
        static::assertTrue($result);
    }

    public function testCanCreateWithExistingLegacy()
    {
        // Arrange
        $this->channelChanger->expects($this->once())->method('has')->willReturn(true);

        $factory = new LoggerAbstractServiceFactory();

        // Act
        $result = $factory->canCreateServiceWithName($this->givenAServiceManager(), 'existing', 'existing');

        // Assert
        static::assertTrue($result);
    }

    public function testCanCreateWithNonExisting()
    {
        // Arrange
        $this->channelChanger->expects($this->once())->method('has')->willReturn(false);

        $factory = new LoggerAbstractServiceFactory();

        // Act
        $result = $factory->canCreate($this->givenAContainer(), 'non-existing');

        // Assert
        static::assertFalse($result);
    }

    public function testCanCreateWithNonExistingLegacy()
    {
        // Arrange
        $this->channelChanger->expects($this->once())->method('has')->willReturn(false);

        $factory = new LoggerAbstractServiceFactory();

        // Act
        $result = $factory->canCreate($this->givenAServiceManager(), 'non-existing'. 'non-existing');

        // Assert
        static::assertFalse($result);
    }

    public function testInvokeWithNonExisting()
    {
        // Arrange
        $this->channelChanger->expects($this->once())->method('get')->willReturn(null);

        $factory = new LoggerAbstractServiceFactory();

        // Act
        $result = $factory->__invoke($this->givenAContainer(), 'non-existing');

        // Assert
        static::assertNull($result);
    }

    /**
     * @dataProvider provideLoggerConfig
     */
    public function testInvokeWithExisting($loggerConfig, $expectedFqcn)
    {
        // Arrange
        $container = $this->givenAContainer();
        $container->expects($this->at(1))->method('get')->with($this->equalTo('config'))->willReturn([
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
        $result = $factory->__invoke($container, 'existing');

        // Assert
        static::assertInstanceOf($expectedFqcn, $result);
    }

    /**
     * @dataProvider provideLoggerConfig
     */
    public function testInvokeWithExistingLegacy($loggerConfig, $expectedFqcn)
    {
        // Arrange
        $container = $this->givenAServiceManager();
        $container->expects($this->at(1))->method('get')->with($this->equalTo('config'))->willReturn([
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
        $result = $factory->createServiceWithName($container, 'existing', 'existing');

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
