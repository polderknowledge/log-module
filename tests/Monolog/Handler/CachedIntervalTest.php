<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

final class CachedIntervalTest extends TestCase
{
    private $rootDirectory;

    public function setUp()
    {
        $this->rootDirectory = vfsStream::setup('tmp');
    }

    public function testWrite()
    {
        // Arrange
        $storeFile = vfsStream::url('tmp/' . __FUNCTION__ . '.txt');

        $internalHandler = $this->getMockForAbstractClass(HandlerInterface::class);

        $handler = new CachedInterval($internalHandler, 1, $storeFile);

        $record = [
            'level' => Logger::DEBUG,
            'extra' => [],
            'context' => [],
        ];

        // Act
        $result = $handler->handle($record);

        // Assert
        static::assertFalse($result);
        static::assertFileExists($storeFile);
    }

    public function testFlush()
    {
        // Arrange
        $storeFile = vfsStream::url('tmp/' . __FUNCTION__ . '.txt');
        file_put_contents($storeFile, 'a:5:{s:7:"message";s:5:"world";s:5:"level";i:100;s:5:"extra";a:0:{}s:7:"context";a:0:{}s:8:"datetime";O:8:"DateTime":3:{s:4:"date";s:26:"2017-09-11 09:17:09.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}}' . "\n");

        $internalHandler = $this->getMockForAbstractClass(HandlerInterface::class);

        $handler = new CachedInterval($internalHandler, 0, $storeFile);

        $record = [
            'message' => 'world',
            'level' => Logger::DEBUG,
            'extra' => [],
            'context' => [],
            'datetime' => new \DateTime(),
        ];

        // Act
        $result = $handler->handle($record);

        // Assert
        static::assertFalse($result);
        static::assertFileNotExists($storeFile);
    }

    public function testFlushEmptyFile()
    {
        // Arrange
        $storeFile = vfsStream::url('tmp/' . __FUNCTION__ . '.txt');
        touch($storeFile);

        $internalHandler = $this->getMockForAbstractClass(HandlerInterface::class);

        $handler = new CachedInterval($internalHandler, 0, $storeFile);

        $record = [
            'message' => 'world',
            'level' => Logger::DEBUG,
            'extra' => [],
            'context' => [],
            'datetime' => new \DateTime(),
        ];

        // Act
        $result = $handler->handle($record);

        // Assert
        static::assertFalse($result);
        static::assertFileExists($storeFile);
    }
}
