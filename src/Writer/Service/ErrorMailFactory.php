<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledge\LogModule\Writer\Service;

use PolderKnowledge\LogModule\Writer\AuditLog;
use Zend\Log\Exception\RuntimeException;
use Zend\Mail\Message;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ErrorMailFactory implements FactoryInterface
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
        if (!isset($this->createOptions['recipient'])) {
            throw new RuntimeException('No recipient configured');
        }

        $mail = new Message();
        $mail->setTo($this->createOptions['recipient']);

        $options = array_merge($this->createOptions, [
            'mail' => $mail,
        ]);

        return $serviceLocator->get('mail', $options);
    }
}
