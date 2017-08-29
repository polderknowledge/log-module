<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Monolog\Processor;

final class HttpSessionParams
{
    public function __invoke(array $record)
    {
        $record['extra']['http_session_params'] = isset($_SESSION) ? $_SESSION : [];

        return $record;
    }
}
