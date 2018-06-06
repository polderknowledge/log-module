<?php

namespace PolderKnowledge\LogModule\Formatter;

use Monolog\Formatter\NormalizerFormatter;
use WShafer\PSR11MonoLog\FactoryInterface;

/**
 * Format an Exception in a similar way PHP does by default when an exception bubbles to the top
 */
class HumanReadableExceptionFormatter extends NormalizerFormatter implements FactoryInterface
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
        $throwable = $record['context']['exception'] ?? null;

        $record = parent::format($record);

        $result = sprintf(
            "[%s] %s.%s: %s\n\n",
            $record['datetime'],
            $record['channel'],
            $record['level_name'],
            $record['message']
        );

        if (isset($record['context']['file'])) {
            $result .= "[Context]\n\n";

            if (isset($record['context']['message'])) {
                $result .= sprintf("  Message: %s\n", $record['context']['message']);
            }

            $result .= sprintf("  File: %s\n", $record['context']['file']);
            $result .= sprintf("  Line: %d\n", $record['context']['line']);

            if (isset($record['context']['code'])) {
                $result .= sprintf("  Code: %s\n\n", $record['context']['code']);
            }
        }

        $exceptionCounter = 1;

        while ($throwable !== null) {
            $result .= sprintf("[Exception #%d]\n", $exceptionCounter++);
            $result .= sprintf("  Type: %s\n", get_class($throwable));
            $result .= sprintf("  Message: %s\n", $throwable->getMessage());
            $result .= sprintf("  Code: %d\n", $throwable->getCode());
            $result .= sprintf("  File: %s\n", $throwable->getFile());
            $result .= sprintf("  Line: %d\n\n", $throwable->getLine());

            $result .= "[Trace]\n";
            $result .= $this->indentLines($throwable->getTraceAsString());
            $result .= "\n\n";

            $throwable = $throwable->getPrevious();
        }

        foreach ($record['extra'] as $key => $params) {
            if (is_array($params) || is_object($params)) {
                $lines = json_encode($params, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
            } else {
                $lines = $params;
            }

            $result .= "[Extra - " . $key . "]\n";
            $result .= $this->indentLines($lines) . "\n\n";
        }

        return $result;
    }

    private function indentLines(string $input, int $spaces = 2)
    {
        $lines = explode("\n", $input);

        $indented = array_map(function ($item) use ($spaces) {
            return str_repeat(' ', $spaces) . $item;
        }, $lines);

        return implode("\n", $indented);
    }
}
