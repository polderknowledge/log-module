# Service Manager

This module provides a LoggerAbstractServiceFactory which can be 
used to setup loggers quickly. The factory is already registered
so it can be used straight away.

```php
<?php

return [
    'monolog' => [
        'channels' => [
            'SomeLogger' => [
                'handlers' => [
                    'outputstream',
                ],
                'processors' => [
                    'my-processor',
                ],
            ],
        ],
        'handlers' => [
            'outputstream' => [
                'type' => 'stream',
                'options' => [
                    'stream' => 'php://output',
                ],
            ],
        ],
        'processors' => [
            'my-processor' => [
                // ...
            ],
        ],
    ],
];
```

## Injecting loggers into Controllers

Simply retrieve them from the container, the abstract service 
factory will create it for you.

```php
final class MyControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $logger = $container->get('CommandLog');
        
        return new MyController($logger);
    }
}
```
