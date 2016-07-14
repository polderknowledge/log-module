<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Processor;

use Zend\Log\Processor\ProcessorInterface;

class PostParamsProcessor extends AbstractParamsProcessor implements ProcessorInterface
{
    /**
     * @param array $event
     * @return array
     */
    public function process(array $event)
    {
        if (!isset($event['extra'])) {
            $event['extra'] = array();
        }

        $event['extra']['POSTParams'] = json_encode($_POST, self::jsonFlags());
        return $event;
    }
}
