<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule;

use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\Listener\MvcEventError;
use Psr\Log\LoggerInterface;
use Zend\EventManager\EventManager;
use Zend\Log\Logger;
use Zend\Log\Writer\Psr;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;

final class ModuleTest extends TestCase
{
    public function testGetConfig()
    {
        // Arrange
        $module = new Module();

        // Act
        $config = $module->getConfig();

        // Assert
        static::assertInternalType('array', $config);
    }

    /**
     * @dataProvider provideBootstrapLogger
     */
    public function testOnBootstrap($logger)
    {
        // Arrange
        $module = new Module();

        $mvcEventError = new MvcEventError($this->getMockForAbstractClass(LoggerInterface::class));

        $eventManager = new EventManager();

        $container = $this->getMockForAbstractClass(ContainerInterface::class);
        $container->expects($this->at(0))->method('get')->with($this->equalTo(MvcEventError::class))->willReturn($mvcEventError);
        $container->expects($this->at(1))->method('get')->with($this->equalTo('ErrorLogger'))->willReturn($logger);

        $application = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->getMock();
        $application->expects($this->once())->method('getEventManager')->willReturn($eventManager);
        $application->expects($this->once())->method('getServiceManager')->willReturn($container);

        $mvcEvent = new MvcEvent();
        $mvcEvent->setApplication($application);

        // Act
        $module->onBootstrap($mvcEvent);

        // Assert
        // ... defined in the arrange section ...
    }

    public function provideBootstrapLogger()
    {
        $psrLogger = $this->getMockForAbstractClass(LoggerInterface::class);
        $psrWriter = new Psr($psrLogger);

        $zendLogger = new Logger();
        $zendLogger->addWriter($psrWriter);

        return [
            [$zendLogger],
            [$psrLogger],
        ];
    }

    public function testInit()
    {
        // Arrange
        $module = new Module();

        $manager = $this->getMockForAbstractClass(ModuleManagerInterface::class);
        $manager->expects($this->once())->method('loadModule')->with($this->equalTo('WShafer\\PSR11MonoLog'));

        // Act
        $module->init($manager);

        // Assert
        // ... defined in the arrange section ...
    }
}
