<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Writer;

use Zend\Log\Writer\Stream;
use PolderKnowledge\LogModule\Formatter\DailyStream as DailyStreamFormatter;

class DailyStream extends Stream
{
    /**
     * @param resource|array|string $streamOrUrl
     * @param string $mode
     */
    public function __construct($streamOrUrl, $mode = null)
    {
        if (is_array($streamOrUrl) && isset($streamOrUrl['stream'])) {
            $streamOrUrl = array_merge(
                $streamOrUrl,
                array('stream' => $this->getDailyStreamFileName($streamOrUrl['stream']))
            );
        } elseif (is_string($streamOrUrl)) {
            $streamOrUrl = $this->getDailyStreamFileName($streamOrUrl);
        }

        $this->setFormatter(new DailyStreamFormatter());
        parent::__construct($streamOrUrl, $mode);
    }

    /**
     * @param string $streamOrUrl
     * @return string
     */
    protected function getDailyStreamFileName($streamOrUrl)
    {
        $path = dirname($streamOrUrl);
        $filename = substr($streamOrUrl, strrpos($streamOrUrl, '/') + 1);
        $date = date('Ymd');

        /**
         * Add date between last dot and extension if exists, else append to filename
         */
        $pos = strrpos($filename, '.');
        if ($pos > 0) {
            $parts = array(
                substr($filename, 0, $pos),
                $date,
                substr($filename, $pos + 1),
            );

            $filename = implode('.', $parts);
        } else {
            $parts = array(
                $filename,
                $date,
            );

            $filename = implode('.', $parts);
        }

        return $path . DIRECTORY_SEPARATOR . $filename;
    }
}
