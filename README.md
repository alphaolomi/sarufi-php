# Sarufi for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alphaolomi/sarufi-php.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/sarufi-php)
[![Tests](https://github.com/alphaolomi/sarufi-php/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/alphaolomi/sarufi-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/alphaolomi/sarufi-php.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/sarufi-php)


## Installation

You can install the package via Composer:

```bash
composer require alphaolomi/sarufi-php
```

## Usage

```php
use Alphaolomi\Sarufi\Sarufi;

$sarufi = new Sarufi('your_token');

// Create an Empty bot
$bot1 = $sarufi->createBot([
    "name" => "YOUR AWESOME BOT NAME",
    "description" => "PUT DESCRIPTION HERE",
    "industry" => "YOUR BOT INDUSTRY",
    "intents" => [],
    "flows" => [],
]);
print_r($bot1);

// OR
// From files with intent and metadata
$bot2 = $sarufi->createFromFile(
    intents: __DIR__ . 'data/intents.yaml',
    flow:  __DIR__ . 'data/flow.yaml',
    metadata:  __DIR__ . 'data/metadata.yaml'
);


print_r($bot2);
```

##  API available

- `Sarufi(string $token)`
- `public function createBot(string $name, null|string $description = null, string $industry = "general", $flows = [], $intents = [], bool $visibleOnCommunity = false)`
- `public function createFromFile($metadata = null, $intents = null, $flows = null)`
- `public function updateBot($id, $name, $industry, $description, $intents, $flows, $visibleOnCommunity)`
- `public function updateFromFile( $id, $intents, $flows, $metadata)`
- `public function getBot($id)`
- `public function chat(int $botId, string $chatId, string $message = "Hello", string $messageType = "text", string $channel = "general")`
- `public function deleteBot($id)`

## Testing

Uses [PestPHP](https://pestphp.com/).

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on recent changes.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on reporting security vulnerabilities.

## Credits

- [Alpha Olomi](https://github.com/alphaolomi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
