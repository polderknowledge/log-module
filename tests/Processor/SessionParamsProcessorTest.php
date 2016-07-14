<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledgeTest\Log\Processor;

use PHPUnit_Framework_TestCase;
use PolderKnowledge\LogModule\Processor\SessionParamsProcessor;

/**
 * @backupGlobals disabled
 */
class SessionParamsProcessorTest extends PHPUnit_Framework_TestCase
{

    protected $processor;

    protected $event = array();

    /**
     * @beforeClass
     */
    public static function setupSession()
    {
        @session_start();
    }

    /**
     * @afterClass
     */
    public static function destorySession()
    {
        session_destroy();
    }

    protected function setUp()
    {
        $this->processor = new SessionParamsProcessor();
    }

    /**
     * @Run
     */
    public function testPOSTParamsKeyExistsAfterProcessing()
    {
        $_POST = array();

        $event = $this->processor->process($this->event);
        $this->assertArrayHasKey('extra', $event);
        $this->assertArrayHasKey('SESSIONParams', $event['extra']);
    }

    public function testPOSTParamsAreJsonEncoded()
    {
        $dummySessionParams = array(
            'dummyValue' => 'foobar',
            'dummyArrayValue' => array(
                'dummy1',
                'dummy2',
            )
        );
        $_SESSION = $dummySessionParams;

        $event = $this->processor->process($this->event);
        $this->assertEquals(
            array(
                'extra' => array(
                    'SESSIONParams' => json_encode(
                        $dummySessionParams,
                        SessionParamsProcessor::jsonFlags()
                    )
                )
            ),
            $event
        );
    }
}
