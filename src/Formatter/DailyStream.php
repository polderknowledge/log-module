<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Formatter;

use DateTime;
use Traversable;
use Zend\Log\Formatter\FormatterInterface;

class DailyStream implements FormatterInterface
{
     protected $dateTimeFormat = self::DEFAULT_DATETIME_FORMAT;

    /**
     * Formats data into a single line to be written by the writer.
     *
     * @param  array  $event event data
     * @return string formatted line to write to the log
     */
    public function format($event)
    {
        if (isset($event['timestamp']) && $event['timestamp'] instanceof DateTime) {
            $timestamp = $event['timestamp']->format($this->getDateTimeFormat());
        } elseif (is_string($event['timestamp'])) {
            $timestamp = $event['timestamp'];
        } else {
            $timestamp = time();
        }

        $output = $timestamp . ' ' . $event['priorityName'] . ' ('
                . $event['priority'] . ') ' . $event['message'] .' in '
                . $event['extra']['file'] . ' on line ' . $event['extra']['line'];

        $debugtrace = null;
        $context = array();

        if (isset($event['extra']['trace'])) {
            $debugtrace = $event['extra']['trace'];
        }

        if (isset($event['extra']['context'])) {
            $context = $event['extra']['context'];
        }

        if (!empty($event['extra'])) {
            $context = array_merge($event['extra'], $context);
        }

        if (null !== $context) {
            $outputContext = '';
            foreach ($context as $key => $value) {
                if (in_array($key, array('trace', 'xdebug'))) {
                    continue;
                }
                $outputContext .= sprintf(
                    "  %s : %s\n",
                    str_pad(substr(ucfirst($key), 0, 15), 15),
                    $this->normalize($value, false)
                );
            }
            $output .= "\n[Context]\n" . $outputContext;
        }

        if (null !== $debugtrace) {
            $outputTrace = '';
            foreach ($debugtrace as $trace) {
                foreach ($trace as $tracekey => $tracevalue) {
                    if (empty($tracevalue)) {
                        continue;
                    }

                    switch ($tracekey) {
                        case 'type':
                            $outputTrace .= sprintf(
                                "  %s\t: %s\n",
                                ucfirst($tracekey),
                                $this->getType($tracevalue)
                            );
                            break;
                        case 'args':
                            $outputTrace .= sprintf(
                                "  %s\t: %s\n",
                                ucfirst($tracekey),
                                rtrim($this->normalize($tracevalue), "\n")
                            );
                            break;
                        default:
                            $outputTrace .= sprintf(
                                "  %s\t: %s\n",
                                substr(ucfirst($tracekey), 0, 5),
                                $this->normalize($tracevalue)
                            );
                            break;
                    }
                }
                $outputTrace .= "\n";

            }
            $output .= "\n[Trace]\n" . $outputTrace;
        }

        return $output;
    }

    /**
     *
     * @param mixed $value
     * @param boolean $recursive
     * @return string
     */
    protected function normalize($value, $recursive = true)
    {
        if (is_scalar($value) || null === $value) {
            return $value;
        }

        $normalizedValue = null;
        if ($value instanceof DateTime) {
            $normalizedValue = $value->format($this->getDateTimeFormat());
        } elseif (is_array($value) || $value instanceof Traversable) {
            if ($value instanceof Traversable) {
                $normalizedValue = iterator_to_array($value);
            }
            $temp = sprintf('array(%d)', count($value));
            if ($recursive) {
                foreach ($value as $key => $subvalue) {
                    $temp .= str_pad(PHP_EOL, 10) . sprintf('%s : %s', $key, $this->normalize($subvalue, false));
                }
            }
            $normalizedValue = $temp;
        } elseif (is_object($value)) {
            $normalizedValue = sprintf('object(%s)', get_class($value));
        } elseif (is_resource($value)) {
            $normalizedValue = sprintf('resource(%s)', get_resource_type($value));
        } elseif (!is_object($value)) {
            $normalizedValue = gettype($value);
        }

        return (string) $normalizedValue;
    }

    /**
     * Get the type of a function
     *
     * @param  string $type
     * @return string
     */
    protected function getType($type)
    {
        switch ($type) {
            case "::":
                return "static";
            case "->":
                return "method";
            default:
                return $type;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeFormat()
    {
        return $this->dateTimeFormat;
    }

    /**
     * {@inheritDoc}
     */
    public function setDateTimeFormat($dateTimeFormat)
    {
        $this->dateTimeFormat = (string) $dateTimeFormat;

        return $this;
    }
}
