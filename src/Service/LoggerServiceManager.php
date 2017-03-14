<?php

namespace PolderKnowledge\LogModule\Service;

use Zend\Log\Exception\InvalidArgumentException;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\AbstractPluginManager;

class LoggerServiceManager extends AbstractPluginManager
{
    public function __construct($configInstanceOrParentLocator = null, array $config = [])
    {
        $this->instanceOf = LoggerInterface::class;
        parent::__construct($configInstanceOrParentLocator, $config);
    }

    public function validate($instance)
    {
        $this->validatePlugin($instance);
    }

    public function validatePlugin($plugin)
    {
        if ($plugin instanceof $this->instanceOf) {
            return;
        }

        throw new InvalidArgumentException(sprintf(
            'Plugin of type %s is invalid; must implement Zend\Log\LoggerInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
