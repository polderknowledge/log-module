<?php

namespace PolderKnowledge\LogModule\Writer;

use Zend\Log\Exception\RuntimeException;
use Zend\Log\LoggerInterface;
use Zend\Log\Writer\AbstractWriter;

class AuditLog extends AbstractWriter
{
    /**
     * @var LoggerInterface
     */
    protected $auditLogger;

    /**
     * @param array $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);

        if (isset($options['auditLogger'])) {
            $this->setAuditLogger($options['auditLogger']);
        }
    }

    /**
     * @param \Zend\Log\LoggerInterface $auditLogger
     * @return \PolderKnowledge\LogModule\Writer\AuditLog
     */
    protected function setAuditLogger(LoggerInterface $auditLogger)
    {
        $this->auditLogger = $auditLogger;
        return $this;
    }

    /**
     * @param array $event
     * @throws RuntimeException
     */
    protected function doWrite(array $event)
    {
        if (null === $this->auditLogger) {
            throw new RuntimeException('No auditlogger registered');
        }

        $this->auditLogger->log(
            $event['priority'],
            $event['message'],
            isset($event['extra']) ? $event['extra'] : null
        );
    }
}
