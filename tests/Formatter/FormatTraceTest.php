<?php

namespace PolderKnowledge\LogModule\Formatter;

use PHPUnit\Framework\TestCase;

/**
 * Throwable or Exception is not mockable due to its final methods
 * So we cannot unittest everything
 */
class FormatTraceTest extends TestCase
{
    public function testFormatStackTrace()
    {
        $trace = $this->getTrace();

        self::assertEquals(
            "MyClass->MyMethod(false, 'mystring', array(3), stdClass, NULL):42",
            ExceptionPrinter::formatTraceLine($trace[0])
        );

        self::assertEquals(
            'index.php(144)',
            ExceptionPrinter::formatTraceLine($trace[1])
        );

    }

    private function getTrace()
    {
        return [
            [
                'file' => 'MyClass.php',
                'line' => 42,
                'class' => 'MyClass',
                'type' => '->',
                'function' => 'MyMethod',
                'args' => [
                    false,
                    'mystring',
                    [0, 0, 0],
                    new \stdClass,
                    null,
                ],
            ], [
                'file' => 'index.php',
                'line' => '144',
                'class' => null,
                'type' => null,
                'function' => null,
                'args' => null,
            ]
        ];
    }
}
