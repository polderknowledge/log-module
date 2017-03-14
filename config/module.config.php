<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule;

use PolderKnowledge\LogModule\Formatter\DailyStream;
use PolderKnowledge\LogModule\Service\LoggerServiceManagerFactory;
use Zend\Log\LoggerAbstractServiceFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

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
    'controller_plugins' => [
        'factories' => [
            'logException' => Mvc\Controller\Plugin\Service\LogExceptionFactory::class,
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
        'aliases' => [
            'dailystream' => Formatter\DailyStream::class,
        ],
        'factories' => [
            Formatter\DailyStream::class => InvokableFactory::class,
        ]
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
            Writer\DailyStream::class => InvokableFactory::class,
            \Zend\Log\Writer\Stream::class => InvokableFactory::class,
            Writer\RequestIdStream::class => InvokableFactory::class,
        ],
        'aliases' => [
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
            TaskService\ExceptionLogger::class => TaskService\Service\ExceptionLoggerFactory::class,
            Service\LoggerServiceManager::class => LoggerServiceManagerFactory::class,
            \Zend\Log\WriterPluginManager::class => \Zend\Log\WriterPluginManagerFactory::class,
            \Zend\Log\FilterPluginManager::class => \Zend\Log\FilterPluginManagerFactory::class,
            \Zend\Log\FormatterPluginManager::class => \Zend\Log\FormatterPluginManagerFactory::class,
            \Zend\Log\ProcessorPluginManager::class => \Zend\Log\ProcessorPluginManagerFactory::class,
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'logException' => View\Helper\Service\LogExceptionFactory::class,
        ],
    ],
];
