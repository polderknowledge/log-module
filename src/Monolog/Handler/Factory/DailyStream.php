<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Handler\Factory;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use WShafer\PSR11MonoLog\FactoryInterface;

final class DailyStream implements FactoryInterface
{
    public function __invoke(array $options)
    {
        $stream = $this->getStream(
            $options['stream'] ?? null,
            $options['dateFormat'] ?? 'Ymd'
        );

        $level = (int)($options['level'] ?? Logger::DEBUG);
        $bubble = (boolean)($options['bubble'] ?? true);
        $filePermission = (int)($options['filePermission'] ?? 0644);
        $useLocking = (boolean)($options['useLocking'] ?? true);

        return new StreamHandler($stream, $level, $bubble, $filePermission, $useLocking);
    }

    protected function getStream($stream, $dateFormat)
    {
        if (is_resource($stream)) {
            return $stream;
        }

        return sprintf((string)$stream, date($dateFormat));
    }
}
