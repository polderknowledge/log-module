<?php

namespace PolderKnowledge\LogModule\Service;

use Zend\Log as ZendLog;
use Zend\Log\Exception\InvalidArgumentException;
use Zend\Log\Formatter\FormatterInterface;
use Zend\Log\Logger;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerServiceFactory implements FactoryInterface, MutableCreationOptionsInterface
{

    /**
     * @var string
     */
    protected $configKey = 'logger';

    /**
     * @param array $createOptions
     */
    public function __construct(array $createOptions = null)
    {
        if (null !== $createOptions) {
            $this->setCreationOptions($createOptions);
        }
    }

    /**
     *
     * @param array $options
     */
    public function setCreationOptions(array $options)
    {
        if (isset($options['config_key'])) {
            $this->configKey = $options['config_key'];
        }
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Logger
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (method_exists($serviceLocator, 'getServiceLocator')) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        $config = $serviceLocator->get('Config');
        $options = (isset($config[$this->configKey])) ? $config[$this->configKey] : array();
        $writers = array();
        $processors = array();

        if (isset($options['writers'])) {
            $writers = $this->createWriters($serviceLocator, $options['writers']);
            unset($options['writers']);
        }

        if (isset($options['processors'])) {
            $processors = $this->createProcessors($serviceLocator, $options['processors']);
            unset($options['processors']);
        }

        $logger = new ZendLog\Logger($options);

        foreach ($writers as $writer) {
            list($writer, $priority) = $writer;
            $logger->addWriter($writer, $priority);
        }

        foreach ($processors as $processor) {
            list($processor, $priority) = $processor;
            $logger->addProcessor($processor, $priority);
        }

        return $logger;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array                                        $writers
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function createWriters(ServiceLocatorInterface $serviceLocator, array $writers)
    {
        $writerPluginManager = $serviceLocator->get('Zend\Log\WriterPluginManager');

        $createdWriters = array();

        foreach ($writers as $writer) {
            if (!isset($writer['name'])) {
                throw new ZendLog\Exception\InvalidArgumentException('Options must contain a name for the writer');
            }

            $priority = (isset($writer['priority'])) ? $writer['priority'] : null;
            $writerOptions = (isset($writer['options'])) ? $writer['options'] : null;
            $filters = array();
            $formatter = null;

            if (isset($writerOptions['formatter'])) {
                $formatter = $this->createFormatter($serviceLocator, $writerOptions['formatter']);
                unset($writerOptions['formatter']);
            }

            if (isset($writerOptions['filters'])) {
                $filters = $this->createFilters($serviceLocator, $writerOptions['filters']);
                unset($writerOptions['filters']);
            }

            /** @var $writer \Zend\Log\Writer\WriterInterface */
            $writer = $writerPluginManager->get($writer['name'], $writerOptions);
            foreach ($filters as $filter) {
                $writer->addFilter($filter);
            }

            if (null !== $formatter) {
                $writer->setFormatter($formatter);
            }

            $createdWriters[] = array($writer, $priority);
        }

        return $createdWriters;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array $processors
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function createProcessors(ServiceLocatorInterface $serviceLocator, array $processors)
    {
        $writerPluginManager = $serviceLocator->get('Zend\Log\ProcessorPluginManager');

        $createdProcessors = array();

        foreach ($processors as $processor) {
            if (!isset($processor['name'])) {
                throw new ZendLog\Exception\InvalidArgumentException('Options must contain a name for the processor');
            }

            $priority = (isset($processor['priority'])) ? $processor['priority'] : null;
            $processor = $writerPluginManager->get($processor['name']);

            $createdProcessors[] = array($processor, $priority);
        }

        return $createdProcessors;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array                                        $filters
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function createFilters(ServiceLocatorInterface $serviceLocator, array $filters)
    {
        $filterPluginManager = $serviceLocator->get('Zend\Log\Writer\FilterPluginManager');

        $createdFilters = array();

        foreach ($filters as $filter) {
            if (!isset($filter['name'])) {
                throw new ZendLog\Exception\InvalidArgumentException('Options must contain a name for the filter');
            }

            $filterOptions = (isset($filter['options'])) ? $filter['options'] : null;
            $filter = $filterPluginManager->get($filter['name'], $filterOptions);

            $createdFilters[] = $filter;
        }

        return $createdFilters;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array                                        $formatter
     *
     * @return FormatterInterface
     * @throws InvalidArgumentException
     */
    protected function createFormatter(ServiceLocatorInterface $serviceLocator, array $formatter)
    {
        $formatterPluginManager = $serviceLocator->get('Zend\Log\Writer\FormatterPluginManager');

        if (!isset($formatter['name'])) {
            throw new ZendLog\Exception\InvalidArgumentException('Options must contain a name for the formatter');
        }

        $formatterOptions = (isset($formatter['options'])) ? $formatter['options'] : null;

        return $formatterPluginManager->get($formatter['name'], $formatterOptions);
    }
}
