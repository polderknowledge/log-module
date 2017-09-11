<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Listener\Factory;

use PHPUnit\Framework\TestCase;
use PolderKnowledge\LogModule\Helper\ZendLogUtils;
use Zend\Log\Logger;

final class ZendLogUtilsTest extends TestCase
{
    /**
     * @expectedException \PolderKnowledge\LogModule\Helper\Exception\NoPsrLoggerAvailable
     */
    public function testExtractPsrLoggerWithoutPsrLogger()
    {
        // Arrange
        $logger = new Logger();

        // Act
        $result = ZendLogUtils::extractPsrLogger($logger);

        // Assert
        // ... defined in docblock ...
    }
}
