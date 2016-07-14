<?php

namespace PolderKnowledge\LogModule\Service;

use Zend\Log\Exception\InvalidArgumentException;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\AbstractPluginManager;

class LoggerServiceManager extends AbstractPluginManager
{
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof LoggerInterface) {
            return;
        }

        throw new InvalidArgumentException(sprintf(
            'Plugin of type %s is invalid; must implement Zend\Log\LoggerInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
