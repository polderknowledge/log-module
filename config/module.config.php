<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule;

use Zend\Log\LoggerAbstractServiceFactory;

return [
    'audit_logger' => [
        'processors' => [
            ['name' => 'GetParamsProcessor'],
            ['name' => 'PostParamsProcessor'],
            ['name' => 'SessionParamsProcessor'],
            ['name' => 'ServerParamsProcessor'],
        ],
        'writers' => [
            'dailystream' => [
                'name' => 'requestidstream',
                'options' => [
                    'stream' => getcwd() . '/data/logs/audit_',
                    'filters' => [
                        'suppress' => [
                            'name' => 'suppressfilter',
                            'options' => [
                                'suppress' => false,
                            ],
                        ],
                    ],
                    'formatter' => [
                        'name' => 'dailystream'
                    ],
                ],
            ],
        ],
    ],
    'command_logger' => [
        'writers' => [
            'outputstream' => [
                'name' => 'outputstream',
                'options' => [
                    'stream' => 'php://stdout',
                    'filters' => [
                        'suppress' => [
                            'name' => 'suppressfilter',
                            'options' => [
                                'suppress' => false,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'error_logger' => [
        'exceptionhandler' => true,
        'errorhandler' => true,
        'fatal_error_shutdownfunction' => true,
        'writers' => [
            'errormail' => [
                'name' => 'errorMail',
                'options' => [
                    'subject_prepend_text' => sprintf('Error report for My Application'),
                    'recipient' => 'support@polderknowledge.nl',
                    'filters' => [
                        'suppress' => [
                            'name' => 'suppressfilter',
                            'options' => [
                                'suppress' => true,
                            ],
                        ],
                        'priority' => [
                            'name' => 'priority',
                            'options' => [
                                'priority' => \Zend\Log\Logger::WARN,
                                'operator' => '<=',
                            ],
                        ],
                        'interval' => [
                            'name' => Filter\Interval::class,
                            'options' => [
                                'lockFile' => getcwd() . '/data/tmp/errormail.lock',
                                'interval' => 60 * 10, // Only mail once in 10 minutes
                            ],
                        ],
                    ],
                ],
            ],
            'dailystream' => [
                'name' => 'dailystream',
                'options' => [
                    'stream' => getcwd() . '/data/logs/php_log',
                    'filters' => [
                        'suppress' => [
                            'name' => 'suppressfilter',
                            'options' => [
                                'suppress' => false,
                            ],
                        ],
                    ],
                    'formatter' => [
                        'name' => 'dailystream'
                    ],
                    'log_separator' => PHP_EOL . str_pad('', 100, '-') . PHP_EOL . PHP_EOL,
                ],
            ],
            'auditlog' => [
                'name' => 'auditlog',
                'options' => [
                    'auditLogPath' => getcwd() . '/data/logs',
                    'filters' => [
                        'suppress' => [
                            'name' => 'suppressfilter',
                            'options' => [
                                'suppress' => false,
                            ],
                        ],
                        'priority' => [
                            'name' => 'priority',
                            'options' => [
                                'priority' => \Zend\Log\Logger::WARN,
                                'operator' => '<=',
                            ],
                        ],
                    ],
                    'formatter' => [
                        'name' => 'dailystream'
                    ],
                ],
            ],
        ],
        'processors' => [
            ['name' => 'backtrace'],
            ['name' => 'requestid'],
        ],
    ],
    'log_filter_plugin' => [
        'invokables' => [
            Filter\Interval::class => Filter\Interval::class,
        ],
    ],
    'log_formatter_plugin' => [
        'invokables' => [
            'dailystream' => Formatter\DailyStream::class,
        ],
    ],
    'log_processor_plugin' => [
        'invokables' => [
            Processor\GetParamsProcessor::class => Processor\GetParamsProcessor::class,
            Processor\PostParamsProcessor::class => Processor\PostParamsProcessor::class,
            Processor\SessionParamsProcessor::class => Processor\SessionParamsProcessor::class,
            Processor\ServerParamsProcessor::class => Processor\ServerParamsProcessor::class,
        ],
        'aliases' => [
            'GetParamsProcessor' => Processor\GetParamsProcessor::class,
            'PostParamsProcessor' => Processor\PostParamsProcessor::class,
            'SessionParamsProcessor' => Processor\SessionParamsProcessor::class,
            'ServerParamsProcessor' => Processor\ServerParamsProcessor::class,
        ]
    ],
    'log_writer_plugin' => [
        'factories' => [
            'auditlog' => Writer\Service\AuditLogFactory::class,
            'errorMail' => Writer\Service\ErrorMailFactory::class,
        ],
        'invokables' => [
            'dailystream' => Writer\DailyStream::class,
            'outputstream' => \Zend\Log\Writer\Stream::class,
            'requestidstream' => Writer\RequestIdStream::class,
        ],
    ],
    'logger_service' => [
        'factories' => [
            'AuditLog' => Service\LoggerServiceFactory::class,
            'CommandLog' => Service\LoggerServiceFactory::class,
            'ErrorLog' => Service\LoggerServiceFactory::class,
            'RequestLog' => Service\LoggerServiceFactory::class,
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            LoggerAbstractServiceFactory::class,
        ],
        'factories' => [
            Listener\MvcEventError::class => Listener\Service\MvcEventErrorFactory::class,
        ],
        'invokables' => [
            Service\LoggerServiceManager::class => Service\LoggerServiceManager::class,
            \Zend\Log\WriterPluginManager::class => \Zend\Log\WriterPluginManager::class,
            \Zend\Log\Writer\FilterPluginManager::class => \Zend\Log\Writer\FilterPluginManager::class,
            \Zend\Log\Writer\FormatterPluginManager::class => \Zend\Log\Writer\FormatterPluginManager::class,
            \Zend\Log\ProcessorPluginManager::class => \Zend\Log\ProcessorPluginManager::class,
        ],
    ],
];
