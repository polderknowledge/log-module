<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Helper;

use PolderKnowledge\LogModule\Helper\Exception\NoPsrLoggerAvailable;
use ReflectionClass;
use Zend\Log\Logger;
use Zend\Log\Writer\Psr;

/**
 * A helper class that extracts a PSR logger from a Zend Logger.
 */
final class ZendLogUtils
{
    public static function extractPsrLogger(Logger $logger)
    {
        $writers = $logger->getWriters()->toArray();

        $psrWriter = static::findPsrWriter($writers);

        if (!$psrWriter) {
            throw new NoPsrLoggerAvailable();
        }

        $reflectionClass = new ReflectionClass($psrWriter);

        $loggerProperty = $reflectionClass->getProperty('logger');
        $loggerProperty->setAccessible(true);

        return $loggerProperty->getValue($psrWriter);
    }

    private static function findPsrWriter(array $writers)
    {
        foreach ($writers as $writer) {
            if ($writer instanceof Psr) {
                return $writer;
            }
        }

        return null;
    }
}
