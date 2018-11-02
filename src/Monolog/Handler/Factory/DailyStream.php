<?php

namespace Application\Log\Handler;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use WShafer\PSR11MonoLog\FactoryInterface;

final class DailyStream implements FactoryInterface
{
    public function __invoke(array $options)
    {
        $stream = $this->getStream($options['stream'] ?? null);

        $level = (int)($options['level'] ?? Logger::DEBUG);
        $bubble = (boolean)($options['bubble'] ?? true);
        $filePermission = (int)($options['filePermission'] ?? 0644);
        $useLocking = (boolean)($options['useLocking'] ?? true);

        return new StreamHandler($stream, $level, $bubble, $filePermission, $useLocking);
    }

    protected function getStream($stream)
    {
        if (is_resource($stream)) {
            return $stream;
        }

        return sprintf((string)$stream, date('Ymd'));
    }
}
