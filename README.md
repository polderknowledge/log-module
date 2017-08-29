# Log Module

The log module is a Zend Framework module that provides support for Monolog logger channels. This module also comes
with a standard error logger enabled which is used to log PHP notices, warnings and errors. in an application.

## Installation

```bash
composer require polderknowledge/log-module
```

Next add the module to the module config (usually `config/modules.php` or `config/application.config.php`):

```php
return [
    'modules' => [
        'PolderKnowledge\\LogModule',
    ],
];
```

Last but not least, copy over the dist configuration files located the `config/` directory to
your application's autoload directory.

## Concept of Logging

Logs are written to channels. A logging channel is a 

## Loggers

This module has a predefined `ErrorLogger` logging channel configured. This channel is used to write PHP notices, 
warnings and errors to. Since it depends on the application on how to handle these messages, there are no handlers 
defined for this channel.

## MVC Error Logging

This module will catch all errors that are triggered in the `MvcEvent::DISPATCH_ERROR` and `MvcEvent::RENDER_ERROR` 
events. These errors are written to the `ErrorLogger` channel.

It's also possible to manually log throwable objects. This module implements a view helper and a controller plugin 
which can be used to log those throwable objects. Simply call `$this->logThrowable($exception);` from the view or the 
controller.

The view helper and controller plugin both make use of the `ThrowableLogger` helper which can be retrieved from the 
service manager: `$container->get(\PolderKnowledge\LogModule\TaskService\ExceptionLogger::class);`

## How to inject a logger in your class

example:

```php
final class MyControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MyController(
            $container->get(LoggerServiceManager::class)->get('CommandLog')
        );
    }
}
```
