# MVC Error Logging

This module will catch all errors that are triggered in the `MvcEvent::DISPATCH_ERROR` and `MvcEvent::RENDER_ERROR` 
events. These errors are written to the `ErrorLogger` channel.

It's also possible to manually log throwable objects. This module implements a view helper and a controller plugin 
which can be used to log those throwable objects. Simply call `$this->logThrowable($exception);` from the view or the 
controller.

The view helper and controller plugin both make use of the `ThrowableLogger` helper which can be retrieved from the 
service manager: `$container->get(\PolderKnowledge\LogModule\TaskService\ExceptionLogger::class);`
