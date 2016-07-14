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
use PolderKnowledge\LogModule\Writer\RequestIdStream;

class RequestIdStreamTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PolderKnowledge\LogModule\Writer\RequestIdStream
     */
    protected $writer;

    protected function setUp()
    {
        $this->writer = new RequestIdStream();
    }

    public function testAcceptsAllowedModes()
    {
        $allowedModes = array(
            'a', 'x', 'w', 'c',
        );
        $flags = array(
            'b', 't'
        );

        foreach ($allowedModes as $mode) {
            foreach ($flags as $flag) {
                $options = array('mode' => $mode.$flag);
                $this->assertInstanceOf(
                    'PolderKnowledge\LogModule\Writer\RequestIdStream',
                    new RequestIdStream($options)
                );
            }
            $options = array('mode' => $mode);
            $this->assertInstanceOf(
                'PolderKnowledge\LogModule\Writer\RequestIdStream',
                new RequestIdStream($options)
            );
        }
    }

    public function testThrowExceptionOnInvalidNode()
    {
        $this->setExpectedException(
            '\Zend\Log\Exception\InvalidArgumentException',
            'Invalid mode (r)'
        );
        new RequestIdStream(array('mode' => 'r'));
    }

    public function testThrowExceptionIfRequestIdIsNotPresent()
    {
        $this->setExpectedException(
            '\Zend\Log\Exception\RuntimeException',
            'Missing requestId. The RequestId Processor must be enabled to use this writer'
        );
        $this->writer->write(
            array(
                'message' => 'dummyMessage',
            )
        );
    }

    public function testRequestIdIsAppendedToUrlOnce()
    {
        $writer = new RequestIdStream(array('mode' => 'a', 'stream' => sys_get_temp_dir() . '/'));
        $writer->write(
            array(
                'message' => 'dummyMessage',
                'extra' => array('requestId' => 'dummyId'),
            )
        );
        $reflecationClass = new \ReflectionClass($writer);
        $urlProperty = $reflecationClass->getProperty('url');
        $urlProperty->setAccessible(true);

        $this->assertEquals(sys_get_temp_dir() . '/dummyId', $urlProperty->getValue($writer));

        $writer->write(
            array(
                'message' => 'dummyMessage',
                'extra' => array('requestId' => 'dummyId'),
            )
        );
        $this->assertEquals(sys_get_temp_dir() . '/dummyId', $urlProperty->getValue($writer));
    }

    public function testFileIsCreated()
    {
        $writer = new RequestIdStream(array('mode' => 'a', 'stream' => sys_get_temp_dir() . '/'));
        $writer->write(
            array(
                'message' => 'dummyMessage',
                'extra' => array('requestId' => 'dummy-id'),
            )
        );

        $this->assertFileExists(sys_get_temp_dir() . '/dummy-id');
    }

    public function testThrowExceptionOnInvalidUrl()
    {
        $this->setExpectedException(
            '\Zend\Log\Exception\RuntimeException',
            '"/dev/null/dummyId" cannot be opened with mode "a"'
        );
        $writer = new RequestIdStream(array('mode' => 'a', 'stream' => '/dev/null/'));
        $writer->write(
            array(
                'message' => 'dummyMessage',
                'extra' => array('requestId' => 'dummyId'),
            )
        );
    }
}
