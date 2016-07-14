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
use PolderKnowledge\LogModule\Processor\PostParamsProcessor;

class PostParamsProcessorTest extends PHPUnit_Framework_TestCase
{

    protected $processor;

    protected $event = array();

    protected function setUp()
    {
        $this->processor = new PostParamsProcessor();
    }

    public function testPOSTParamsKeyExistsAfterProcessing()
    {
        $_POST = array();

        $event = $this->processor->process($this->event);
        $this->assertArrayHasKey('extra', $event);
        $this->assertArrayHasKey('POSTParams', $event['extra']);
    }

    public function testPOSTParamsAreJsonEncoded()
    {
        $dummyPostParams = array(
            'dummyValue' => 'foobar',
            'dummyArrayValue' => array(
                'dummy1',
                'dummy2',
            )
        );
        $_POST = $dummyPostParams;

        $event = $this->processor->process($this->event);
        $this->assertEquals(
            array(
                'extra' => array(
                    'POSTParams' => json_encode(
                        $dummyPostParams,
                        PostParamsProcessor::jsonFlags()
                    )
                )
            ),
            $event
        );
    }
}
