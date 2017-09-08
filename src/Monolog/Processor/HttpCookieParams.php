<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule\Monolog\Processor;

final class HttpCookieParams
{
    public function __invoke(array $record)
    {
        $record['extra']['http_cookie_params'] = isset($_COOKIE) ? $_COOKIE : [];

        return $record;
    }
}
