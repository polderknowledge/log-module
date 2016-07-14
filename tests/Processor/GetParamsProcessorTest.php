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
use PolderKnowledge\LogModule\Processor\GetParamsProcessor;

class GetParamsProcessorTest extends PHPUnit_Framework_TestCase
{

    protected $processor;

    protected $event = array();

    protected function setUp()
    {
        $this->processor = new GetParamsProcessor();
    }

    public function testGETParamsKeyExistsAfterProcessing()
    {
        $_GET = array();

        $event = $this->processor->process($this->event);
        $this->assertArrayHasKey('extra', $event);
        $this->assertArrayHasKey('GETParams', $event['extra']);
    }

    public function testGetParamsAreJsonEncoded()
    {
        $dummyGetParams = array(
            'dummyValue' => 'foobar',
            'dummyArrayValue' => array(
                'dummy1',
                'dummy2',
            )
        );
        $_GET = $dummyGetParams;

        $event = $this->processor->process($this->event);
        $this->assertEquals(
            array(
                'extra' => array(
                    'GETParams' => json_encode(
                        $dummyGetParams,
                        GetParamsProcessor::jsonFlags()
                    )
                )
            ),
            $event
        );
    }
}
