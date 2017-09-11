<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Processor;

use PHPUnit\Framework\TestCase;

final class HttpGetParamsTest extends TestCase
{
    public function testInvoke()
    {
        // Arrange
        $record = [
            'message' => 'hello',
        ];

        $factory = new HttpGetParams();

        // Act
        $result = $factory->__invoke($record);

        // Assert
        static::assertInternalType('array', $result);
        static::assertArrayHasKey('extra', $result);
    }
}
