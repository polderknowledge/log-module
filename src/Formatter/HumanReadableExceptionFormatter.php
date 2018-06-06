<?php

namespace PolderKnowledge\LogModule\Formatter;

use Monolog\Formatter\LineFormatter;
use WShafer\PSR11MonoLog\FactoryInterface;

/**
 * Format an Exception in a similar way PHP does by default when an exception bubbles to the top
 */
class HumanReadableExceptionFormatter extends LineFormatter implements FactoryInterface
{
    public function __invoke(array $options)
    {
        if (array_key_exists('dateFormat', $options)) {
            return new self($options['dateFormat']);
        }

        return new self();
    }

    public function format(array $record): string
    {
        $exception = $record['context']['exception'] ?? null;

        if (!$exception) {
            return parent::format($record);
        }

        return $this->printFromThrowable($record, $exception);
    }

    protected function printFromThrowable(array $record, \Throwable $throwable)
    {
        $record = $this->normalize($record);

        $result = sprintf(
            "[%s] %s.%s: %s\n\n",
            $record['datetime'],
            $record['channel'],
            $record['level_name'],
            $record['message']
        );

        $result .= "[Context]\n";
        $result .= sprintf("  Type: %s\n", get_class($throwable));
        $result .= sprintf("  Code: %d\n", $throwable->getCode());
        $result .= sprintf("  File: %s\n", $throwable->getFile());
        $result .= sprintf("  Line: %d\n\n", $throwable->getLine());

        $result .= "[Trace]\n";
        $result .= $this->buildTraceOutput($throwable->getTraceAsString());
        $result .= "\n\n";

        return $result;
    }

    private function buildTraceOutput($trace)
    {
        $lines = explode("\n", $trace);

        $indented = array_map(function ($item) {
            return '  ' . $item;
        }, $lines);

        return implode("\n", $indented);
    }
}
