<?php

namespace PolderKnowledge\LogModule\Formatter;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\NormalizerFormatter;

/**
 * Format an Exception in a similar way PHP does by default when an exception bubbles to the top
 */
class HumanReadableExceptionFormatter extends NormalizerFormatter implements FormatterInterface
{
    public function format(array $record): string
    {
        $exception = $record['context']['exception'] ?? null;
        if ($exception) {
            return $this->printFromException($exception);
        } else {
            return $this->printWithoutException($record);
        }
    }

    protected function printWithoutException(array $record): string
    {
        return sprintf('[%s] %s: %s', ...[
            date('r'),
            $record['level_name'],
            $record['message']
        ]);
    }
    
    protected function printFromException(\Throwable $exception)
    {
        return implode("\n", ExceptionPrinter::linesFromException($exception)) . "\n"
            . "---------------------------------------\n";
    }
}
