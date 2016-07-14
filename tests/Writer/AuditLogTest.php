<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledgeTest\Log\Writer;

use PHPUnit_Framework_TestCase;
use PolderKnowledge\LogModule\Writer\AuditLog;

class AuditLogTest extends PHPUnit_Framework_TestCase
{

    protected $auditLoggerMock;

    protected $writer;

    protected function setUp()
    {
        $this->auditLoggerMock = $this->getMock('\Zend\Log\Logger');
        $options = array(
            'auditLogger' => $this->auditLoggerMock
        );
        $this->writer = new AuditLog($options);
    }

    public function testDebugIsCalledOnAuditLogger()
    {
        $this->auditLoggerMock->expects($this->once())->method('log');

        $options = array(
            'auditLogger' => $this->auditLoggerMock
        );
        $writer = new AuditLog($options);
        $writer->write(
            array(
                'priority' => 'debug',
                'message' => 'dummyMessage',
                'extra' => array('requestId' => 'dummyid')
            )
        );
    }

    public function testThrowExceptionIfAuditLoggerIsNotSet()
    {
        $this->setExpectedException(
            '\Zend\Log\Exception\RuntimeException',
            'No auditlogger registered'
        );
        $writer = new AuditLog();
        $writer->write(
            array(
                'message' => 'dummyMessage',
                'extra' => array('requestId' => 'dummyid')
            )
        );
    }
}
