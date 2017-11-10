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
use Zend\ServiceManager\AbstractPluginManager;

final class LogThrowableFactoryTest extends TestCase
{
    /**
     * @var AbstractPluginManager
     */
    private $pluginManager;

    /** @var  ContainerInterface */
    private $container;

    protected function setUp()
    {
        $logger = new ThrowableLogger($this->getMockForAbstractClass(LoggerInterface::class));

        $this->container = $this->getMockForAbstractClass(ContainerInterface::class);
        $this->container->expects($this->once())->method('get')->willReturn($logger);
        $this->pluginManager = $this->getMockForAbstractClass(AbstractPluginManager::class, [$this->container]);
    }

    public function testLoggerCalled()
    {
        // Arrange
        $factory = new LogThrowableFactory();

        // Act
        $result = $factory->__invoke($this->container, LogThrowable::class);

        // Assert
        static::assertInstanceOf(LogThrowable::class, $result);
    }

    /**
     * @group legacy
     */
    public function testLegacyFactoryMethod()
    {
        // Arrange
        $factory = new LogThrowableFactory();

        // Act
        $result = $factory->createService($this->pluginManager);

        // Assert
        static::assertInstanceOf(LogThrowable::class, $result);
    }
}
