<?php

namespace PolderKnowledge\LogModule\Formatter;

class ExceptionPrinter
{
    public static function linesFromException(\Throwable $exception, bool $nested = false): array
    {
        $lines = [];
        $lines[] = self::printFirstLineFromException($exception, $nested);

        foreach ($exception->getTrace() as $traceLine) {
            $lines[] = self::formatTraceLine($traceLine);
        }

        if ($exception->getPrevious()) {
            $previousLines = self::linesFromException($exception->getPrevious(), true);

            // recursion makes sure deeper nesting has longer prefixes
            foreach ($previousLines as $previousLine) {
                $lines[] = '  ' . $previousLine;
            }
        }

        return $lines;
    }

    public static function printFirstLineFromException(\Throwable $exception, bool $nested): string
    {
        return sprintf('[%s] %s: %s in %s:%s', ...[
            // only the root exception has a timestamp
            $nested ? 'Previous Exception' : date('r'),
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ]);
    }

    /**
     * Transform an element of the array Exception::getTrace
     * to a string of a single line
     *
     * @param mixed[] $trace element of Exception::getTrace
     * @return string of a single line
     */
    public static function formatTraceLine(array $trace): string
    {
        $output = '';

        // only display file if we're not in the context of a class
        // to save space for the sake for readability of the stack trace
        if (isset($trace['file']) && !isset($trace['class'])) {
            $output .= $trace['file'];

            if (isset($trace['line'])) {
                $output .= '(' . $trace['line'] . ')';
            }

            if (!isset($trace['function'])) {
                return $output;
            }
        }

        $output .= $trace['class'] ?? '';
        $output .= $trace['type'] ?? '';
        $output .= $trace['function'] ?? '';

        if (isset($trace['function'])) {
            $arguments = self::formatArguments($trace['args'] ?? []);
            $output .= '(' . $arguments . ')';
        }

        // print line number now if we skipped it
        if (isset($trace['class']) && isset($trace['line'])) {
            $output .= ':' . $trace['line'];
        }

        return $output;
    }

    /**
     * Convert function arguments of any type to a short readable string
     *
     * @param mixed[] $arguments
     * @return string a summary of the list of arguments
     */
    public static function formatArguments(array $arguments): string
    {
        return implode(', ', array_map([__CLASS__, 'formatArgument'], $arguments));
    }

    /**
     * Summarize a variable
     *
     * @param mixed $argument anything at all
     * @return string a summary of the argument
     */
    public static function formatArgument($argument): string
    {
        if (is_object($argument)) {
            return get_class($argument);
        }

        if (is_int($argument) || is_float($argument)) {
            return $argument;
        }

        if (is_string($argument)) {
            return self::truncateString($argument);
        }

        if ($argument === false) {
            return 'false';
        }

        if ($argument === true) {
            return 'true';
        }

        if (is_array($argument)) {
            return 'array(' . count($argument) . ')';
        }

        // resource or null
        return gettype($argument);
    }

    public static function truncateString(string $argument): string
    {
        if (strlen($argument) < 80) {
            $truncated = $argument;
        } else {
            $truncated = substr($argument, 0, 30) . '...' . substr($argument, -30, 30);
        }

        return "'$truncated'";
    }
}
