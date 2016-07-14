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
use PolderKnowledge\LogModule\Processor\ServerParamsProcessor;

/**
 * Description of ServerParamsProcessorTest
 *
 * @author verweel
 */
class ServerParamsProcessorTest extends PHPUnit_Framework_TestCase
{

    protected $processor;

    protected $event = array();

    protected function setUp()
    {
        $this->processor = new ServerParamsProcessor();
    }

    public function testSERVERParamsKeyExistsAfterProcessing()
    {
        $event = $this->processor->process($this->event);
        $this->assertArrayHasKey('extra', $event);
        $this->assertArrayHasKey('SERVERParams', $event['extra']);
    }

    public function testServerParamsAreJsonEncoded()
    {
        $event = $this->processor->process($this->event);
        $this->assertEquals(
            array(
                'extra' => array(
                    'SERVERParams' => json_encode(
                        $_SERVER,
                        ServerParamsProcessor::jsonFlags()
                    )
                )
            ),
            $event
        );
    }
}
