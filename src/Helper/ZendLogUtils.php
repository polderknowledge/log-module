<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Helper;

use ReflectionClass;
use Zend\Log\Logger;

/**
 * A helper class that logs a throwable object to a logger.
 */
final class ZendLogUtils
{
    public static function extractPsrLogger(Logger $logger)
    {
        $writers = $logger->getWriters()->toArray();

        $reflectionClass = new ReflectionClass($writers[0]);

        $loggerProperty = $reflectionClass->getProperty('logger');
        $loggerProperty->setAccessible(true);

        return $loggerProperty->getValue($writers[0]);
    }
}
