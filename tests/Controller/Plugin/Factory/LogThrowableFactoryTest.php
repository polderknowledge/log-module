<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Controller\Plugin\Factory;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\Controller\Plugin\LogThrowable;
use PolderKnowledge\LogModule\TaskService\ThrowableLogger;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\AbstractPluginManager;

final class LogThrowableFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var AbstractPluginManager
     */
    private $pluginManager;

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
