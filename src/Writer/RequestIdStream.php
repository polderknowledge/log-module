<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Writer;

use Zend\Log\Formatter\Simple as SimpleFormatter;
use Zend\Log\Writer\AbstractWriter;
use Zend\Log\Exception\InvalidArgumentException;
use Zend\Log\Exception\RuntimeException;
use Zend\Stdlib\ErrorHandler;

class RequestIdStream extends AbstractWriter
{
    /**
     * @var string
     */
    protected $mode;

    /**
     * @var resource
     */
    protected $stream;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param array $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if ($options instanceof Traversable) {
            $options = iterator_to_array($options);
        }

        $this->setMode(isset($options['mode']) ? $options['mode'] : null);
        $streamOrUrl = isset($options['stream']) ? $options['stream'] : null;

        if (is_resource($streamOrUrl)) {
            if ('stream' != get_resource_type($streamOrUrl)) {
                throw new InvalidArgumentException(sprintf(
                    'Resource is not a stream; received "%s',
                    get_resource_type($streamOrUrl)
                ));
            }

            if ('a' != $this->mode) {
                throw new \InvalidArgumentException(sprintf(
                    'Mode must be "a" on existing streams; received "%s"',
                    $this->mode
                ));
            }

            $this->stream = $streamOrUrl;
        } elseif (is_string($streamOrUrl)) {
            $this->url = $streamOrUrl;
        }

        if ($this->formatter === null) {
            $this->formatter = new SimpleFormatter();
        }
    }

    /**
     * @param array $event
     * @throws RuntimeException
     */
    protected function doWrite(array $event)
    {
        if (!isset($event['extra']['requestId'])) {
            throw new RuntimeException('Missing requestId. The RequestId Processor must be enabled to use this writer');
        }

        $requestId = $event['extra']['requestId'];
        $this->addRequestIdToUrl($requestId);

        fwrite($this->getStream(), $this->formatter->format($event));
    }

    /**
     * @param string $mode
     * @throws InvalidArgumentException
     */
    protected function setMode($mode)
    {
        // Setting the default mode
        if (null === $mode) {
            $mode = 'a';
        }

        if (!preg_match('~^([acxw]([tb])?)$~', $mode)) {
            throw new InvalidArgumentException(
                sprintf('Invalid mode (%s)', $mode)
            );
        }

        $this->mode = $mode;
    }

    /**
     * @return resource
     * @throws RuntimeException
     */
    protected function getStream()
    {
        if (!is_resource($this->stream)) {
            ErrorHandler::start();
            $this->stream = fopen($this->url, $this->mode, false);
            $error = ErrorHandler::stop();
            if (!$this->stream) {
                throw new RuntimeException(
                    sprintf(
                        '"%s" cannot be opened with mode "%s"',
                        $this->url,
                        $this->mode
                    ),
                    0,
                    $error
                );
            }
        }

        return $this->stream;
    }

    /**
     * @param string $requestId
     */
    protected function addRequestIdToUrl($requestId)
    {
        if (!$this->endsWith($this->url, $requestId)) {
            $this->url = $this->url . $requestId;
        }
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    protected function endsWith($haystack, $needle)
    {
        return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * Close the stream resource.
     *
     * @return void
     */
    public function shutdown()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }
}
