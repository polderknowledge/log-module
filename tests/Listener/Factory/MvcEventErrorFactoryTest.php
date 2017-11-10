<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Listener\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\Listener\MvcEventError;
use Psr\Log\LoggerInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Psr;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

final class MvcEventErrorFactoryTest extends TestCase
{
    public function testInvoke()
    {
        // Arrange
        $logger = $this->getMockForAbstractClass(LoggerInterface::class);

        $container = $this->getMockForAbstractClass(ContainerInterface::class);
        $container->expects($this->once())->method('get')->willReturn($logger);

        $factory = new MvcEventErrorFactory();

        // Act
        $result = $factory->__invoke($container, MvcEventError::class);

        // Assert
        static::assertInstanceOf(MvcEventError::class, $result);
    }

    public function testCreateService()
    {
        // Arrange
        $logger = $this->getMockForAbstractClass(LoggerInterface::class);

        $container = $this->getMockBuilder(ServiceManager::class)->getMock();
        $container->expects($this->once())->method('get')->willReturn($logger);

        $factory = new MvcEventErrorFactory();

        // Act
        $result = $factory->createService($container);

        // Assert
        static::assertInstanceOf(MvcEventError::class, $result);
    }

    public function testInvokeWithZendLogger()
    {
        // Arrange
        $psrLogger = $this->getMockForAbstractClass(LoggerInterface::class);

        $logger = new Logger();
        $logger->addWriter(new Psr($psrLogger));

        $container = $this->getMockForAbstractClass(ContainerInterface::class);
        $container->expects($this->once())->method('get')->willReturn($logger);

        $factory = new MvcEventErrorFactory();

        // Act
        $result = $factory->__invoke($container, MvcEventError::class);

        // Assert
        static::assertInstanceOf(MvcEventError::class, $result);
    }
}
