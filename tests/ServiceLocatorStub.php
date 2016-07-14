<?php
/**
 * Polder Knowledge / LogModule (http://polderknowledge.nl)
 *
 * @link http://developers.polderknowledge.nl/gitlab/polderknowledge/log-module for the canonical source repository
 * @copyright Copyright (c) 2016 Polder Knowledge (http://www.polderknowledge.nl)
 * @license http://polderknowledge.nl/license/proprietary proprietary
 */

namespace PolderKnowledgeTest;

/**
 * ServiceLocator Stub to easly stub the ServiceLocator
 */
class ServiceLocatorStub implements \Zend\ServiceManager\ServiceLocatorInterface
{
    protected $services = array();

    /**
     *
     * @param string $name
     * @param mixed $serviceMock
     */
    public function addService($name, $serviceMock)
    {
        $this->services[$name] = $serviceMock;
    }

    /**
     *
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->services[$name];
        }

        throw new \Exception(sprintf('Service "%s" is not registered', $name));
    }

    /**
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->services[$name]);
    }
}
