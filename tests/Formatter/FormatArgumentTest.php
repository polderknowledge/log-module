<?php

namespace PolderKnowledge\LogModule\Formatter;

use PHPUnit\Framework\TestCase;

/**
 * Throwable or Exception is not mockable due to its final methods
 * So we cannot unittest everything
 */
class FormatArgumentTest extends TestCase
{
    /**
     * @dataProvider formatProvider
     */
    public function testFormatArgument($argument, $expected)
    {
        self::assertEquals($expected, ExceptionPrinter::formatArgument($argument));
    }

    public function formatProvider()
    {
        return [
            [true, 'true'],
            [false, 'false'],
            [[0, 0, 0], 'array(3)'],
            [new \stdClass, 'stdClass'],
            [fopen(__FILE__, 'r'), 'resource'],
            ['mystring', "'mystring'"],
            [
                'If there is a long string in the arguments, it should be truncated,'
                    . ' so that the logging stays concise',
                "'If there is a long string in t...that the logging stays concise'",
            ],
            [null, 'NULL'],
        ];
    }
}
