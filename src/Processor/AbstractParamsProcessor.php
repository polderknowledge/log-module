<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Processor;

class AbstractParamsProcessor
{
    /**
     * @var int
     */
    protected static $jsonFlags = 0;

    /**
     * Initializes a new instance of this class.
     */
    public function __construct()
    {
        $jsonFlags = 0;
        $jsonFlags |= defined('JSON_UNESCAPED_SLASHES') ? JSON_UNESCAPED_SLASHES : 0;
        $jsonFlags |= defined('JSON_UNESCAPED_UNICODE') ? JSON_UNESCAPED_UNICODE : 0;

        self::$jsonFlags = $jsonFlags;
    }

    /**
     * @return integer
     */
    public static function jsonFlags()
    {
        return self::$jsonFlags;
    }
}
