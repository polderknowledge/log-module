<?php
/**
 * Polder Knowledge / log-module (https://polderknowledge.com)
 *
 * @link https://github.com/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Polder Knowledge (https://polderknowledge.com)
 * @license https://github.com/polderknowledge/log-module/blob/master/LICENSE.md MIT
 */

namespace PolderKnowledge\LogModule;

use Monolog\Logger;
use PolderKnowledge\LogModule\Monolog\Handler\Factory\CachedInterval;

return [
    'controller_plugins' => [
        'aliases' => [
            'logException' => Controller\Plugin\LogThrowable::class,
            'logThrowable' => Controller\Plugin\LogThrowable::class,
        ],
        'factories' => [
            Controller\Plugin\LogThrowable::class => Controller\Plugin\Factory\LogThrowableFactory::class,
        ],
    ],
    'monolog' => [
        'channels' => [
            'ErrorLogger' => [
                'handlers' => [
                    'error-deduplication',
                    'error-stream',
                ],
                'processors' => [
                    'error-http-params-get',
                    'error-http-params-post',
                    'error-http-params-session',
                    'error-http-params-cookie',
                    'error-server-params',
                ],
            ],
        ],
        'handlers' => [
            'error-cached-interval' => [
                'type' => CachedInterval::class,
                'options' => [
                    'handler' => 'error-mailer',
                    'interval' => 30,
                ],
            ],
            'error-deduplication' => [
                'type' => 'deduplication',
                'options' => [
                    'handler' => 'error-cached-interval',
                    'deduplicationLevel' => Logger::DEBUG,
                ],
            ],
            'error-mailer' => [
                'type' => 'nativemailer',
                'options' => [
                    'to' => 'walter.tamboer@live.com',
                ],
            ],
            'error-stream' => [
                'type' => 'stream',
                'options' => [
                    'stream' => 'data/logs/php_log.' . date('Ymd'),
                ],
            ],
        ],
        'processors' => [
            'error-http-params-cookie' => [
                'type' => Monolog\Processor\Factory\HttpCookieParams::class,
            ],
            'error-http-params-get' => [
                'type' => Monolog\Processor\Factory\HttpGetParams::class,
            ],
            'error-http-params-post' => [
                'type' => Monolog\Processor\Factory\HttpPostParams::class,
            ],
            'error-http-params-session' => [
                'type' => Monolog\Processor\Factory\HttpSessionParams::class,
            ],
            'error-server-params' => [
                'type' => Monolog\Processor\Factory\ServerParams::class,
            ],
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            Factory\LoggerAbstractServiceFactory::class,
        ],
        'factories' => [
            Listener\MvcEventError::class => Listener\Factory\MvcEventErrorFactory::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'logException' => View\Helper\LogThrowable::class,
            'logThrowable' => View\Helper\LogThrowable::class,
        ],
        'factories' => [
            View\Helper\LogThrowable::class => View\Helper\Factory\LogThrowableFactory::class,
        ],
    ],
];
