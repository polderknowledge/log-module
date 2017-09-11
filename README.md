# log-module

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

The log module is a Zend Framework module that provides support for Monolog logger channels. This module also comes
with a standard error logger enabled which is used to log PHP notices, warnings and errors in applications.

## Install

Via Composer

``` bash
$ composer require polderknowledge/log-module
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


## Usage

This module has a predefined `ErrorLogger` logging channel configured. This channel is used to write PHP notices, 
warnings and errors to. Since it depends on the application on how to handle these messages, there are no handlers 
defined for this channel.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please report them via [HackerOne](https://hackerone.com/polderknowledge) 
instead of using the issue tracker or e-mail.

## Community

We have an IRC channel where you can find us every now and then. We're on the Freenode network in the
channel #polderknowledge.

## Credits

- [Polder Knowledge][link-author]
- [All Contributors][link-contributors]

## License

Please see [LICENSE.md][link-license] for the license of this application.

[ico-version]: https://img.shields.io/packagist/v/polderknowledge/log-module.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/polderknowledge/log-module/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/polderknowledge/log-module.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/polderknowledge/log-module.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/polderknowledge/log-module.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/polderknowledge/log-module
[link-travis]: https://travis-ci.org/polderknowledge/log-module
[link-scrutinizer]: https://scrutinizer-ci.com/g/polderknowledge/log-module/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/polderknowledge/log-module
[link-downloads]: https://packagist.org/packages/polderknowledge/log-module
[link-author]: https://polderknowledge.com
[link-contributors]: ../../contributors
[link-license]: LICENSE.md
