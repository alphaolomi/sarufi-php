# Sarufi for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alphaolomi/sarufi-php.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/sarufi-php)
[![Tests](https://github.com/alphaolomi/sarufi-php/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/alphaolomi/sarufi-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/alphaolomi/sarufi-php.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/sarufi-php)


## Installation

You can install the package via composer:

```bash
composer require alphaolomi/sarufi-php
```

## Usage

```php
use Alphaolomi\Sarufi\Sarufi;

$sarufi = new Sarufi('your_email', 'your_password');

$bot1 = $sarufi->createBot([
    "name" => "YOUR AWESOME BOT NAME",
    "description" => "PUT DESCRIPTION HERE",
    "industry" => "YOUR BOT INDUSTRY",
    "intents" => [],
    "flows" => [],
]);
print_r($bot1);

// OR using 
$bot2 = $sarufi->createFromFile(
    intents: 'data/intents.yaml',
    flow: 'data/flow.yaml',
    metadata: 'data/metadata.yaml'
);


print_r($bot2);

```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alpha Olomi](https://github.com/alphaolomi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
