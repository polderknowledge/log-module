# Log Module

The log module is a Zend Framework module that enables error logging in an application.
It provides a set of loggers that can be used throughout the application.

## Installation

```bash
composer require polderknowledge/log-module
```

Next add the module to the application config:

```php
return [
    'modules' => [
        'PolderKnowledge\\LogModule',
    ],
];
```

Last but not least, copy over the dist configuration files located the `config/` directory to
your application's autoload directory.

## Loggers

The module provides three preconfigured loggers. An *error logger*, an *audit logger* and
a *command logger*.

### Error Logger

The error logger will log PHP errors. In order to do this, there are a couple of writers
pre-configured:
* errormail: An e-mail will be sent once every 'n' minutes containing the errors.
* dailystream: Logs errors to the `data/logs/php_log.yyyymmdd` file.
* auditlog: Uses the *audit logger* to write logs.

### Audit Logger

The audit logger logs each message to a separate file. In this file additional information about
the current request is provided.

## Command Logger

The command logger can be used by job workers in order to write information about jobs. It
simply writes information to the output stream.

## Disabling Loggers

Copy `config/logging.local.php.dist` to your application's `config/autoload/` directory.

## Exception Logging

This module implements a view helper and a controller plugin which can be used to log exceptions
manually. Simply call `$this->logException($exception);` from the view or the controller.

The view helper and controller plugin both make use of the `ExceptionLogger` task service which can
be retrieved from the service manager: `$serviceLocator->get(\PolderKnowledge\LogModule\TaskService\ExceptionLogger::class);`

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
