# Configuration

Below you can find a full example on how to create loggers:

```php
<?php

return [
    'monolog' => [
        'channels' => [
            'OutputLogger' => [
                'handlers' => [
                    'outputstream',
                ],
                'processors' => [
                    'MyProcessor',
                ],
            ],
        ],
        'handlers' => [
            'outputstream' => [
                'type' => 'stream',
                'formatter' => 'MyFormatter',
                'options' => [
                    'stream' => 'php://output',
                ],
            ],
        ],
        'formatters' => [
            'MyFormatter' => [
                'type' => 'line',
                'options' => [
                    'format' => "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
                    'dateFormat' => "c",
                    'allowInlineLineBreaks' => true,
                    'ignoreEmptyContextAndExtra' => false,
                ],
            ],
        ],
        'processors' => [
            'MyProcessor' => [
                'type' => 'uid',
                'options' => [
                    'length'  => 7,
                ],
            ],
        ],
    ],
];
```

For backwards compatibility it's possible to create a 
`Zend\Log\Logger` instance by setting `zend-log` to `true`.
See the example below:

```php
<?php

return [
    'monolog' => [
        'channels' => [
            'MyZendLogger' => [
                'zend-log' => true,
                'handlers' => [
                    'outputstream',
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
    ],
];
```

## Formatters, Handlers and Processors

For a full list of all available formatters, handlers and 
processors, take a look at https://github.com/wshafer/psr11-monolog
That is the library we based this module on.
