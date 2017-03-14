<?php

namespace PolderKnowledge\LogModule\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Log as ZendLog;
use Zend\Log\Exception\InvalidArgumentException;
use Zend\Log\Formatter\FormatterInterface;
use Zend\Log\Logger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggerServiceFactory implements FactoryInterface
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
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $configKey = isset($options['config_key']) ? $options['config_key'] : $this->configKey;
        $options = (isset($config[$configKey])) ? $config[$configKey] : array();
        $writers = array();
        $processors = array();



        if (isset($options['writers'])) {
            $writers = $this->createWriters($container, $options['writers']);
            unset($options['writers']);
        }

        if (isset($options['processors'])) {
            $processors = $this->createProcessors($container, $options['processors']);
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
     *
     * @return Logger
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (method_exists($serviceLocator, 'getServiceLocator')) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        return $this($serviceLocator, ZendLog\Logger::class);
    }

    /**
     * @param ServiceLocatorInterface $container
     * @param array                                        $writers
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function createWriters(ContainerInterface $container, array $writers)
    {
        $writerPluginManager = $container->get('Zend\Log\WriterPluginManager');

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
                $formatter = $this->createFormatter($container, $writerOptions['formatter']);
                unset($writerOptions['formatter']);
            }

            if (isset($writerOptions['filters'])) {
                $filters = $this->createFilters($container, $writerOptions['filters']);
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
     * @param ServiceLocatorInterface $container
     * @param array $processors
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function createProcessors(ContainerInterface $container, array $processors)
    {
        $writerPluginManager = $container->get('Zend\Log\ProcessorPluginManager');

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
     * @param ServiceLocatorInterface $container
     * @param array                                        $filters
     *
     * @return array
     * @throws InvalidArgumentException
     */
    protected function createFilters(ContainerInterface $container, array $filters)
    {
        $filterPluginManager = $container->get(\Zend\Log\FilterPluginManager::class);

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
     * @param ServiceLocatorInterface $container
     * @param array                                        $formatter
     *
     * @return FormatterInterface
     * @throws InvalidArgumentException
     */
    protected function createFormatter(ContainerInterface $container, array $formatter)
    {
        $formatterPluginManager = $container->get(\Zend\Log\FormatterPluginManager::class);

        if (!isset($formatter['name'])) {
            throw new ZendLog\Exception\InvalidArgumentException('Options must contain a name for the formatter');
        }

        $formatterOptions = (isset($formatter['options'])) ? $formatter['options'] : null;

        return $formatterPluginManager->get($formatter['name'], $formatterOptions);
    }
}
