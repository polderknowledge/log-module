<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Writer\Service;

use PolderKnowledge\LogModule\Service\LoggerServiceManager;
use PolderKnowledge\LogModule\Writer\AuditLog;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuditLogFactory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $createOptions = array();

    /**
     * @param array $createOptions
     */
    public function __construct(array $createOptions = array())
    {
        $this->createOptions = $createOptions;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuditLog
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        $loggerServiceManager = $parentLocator->get(LoggerServiceManager::class);

        $auditLogger = $loggerServiceManager->get('AuditLog', ['config_key' => 'audit_logger']);

        var_dump($auditLogger);
        exit;

        $options = array_merge($this->createOptions, [
            'auditLogger' => $auditLogger,
        ]);

        return new AuditLog($options);
    }
}
