# A package for Laravel to interact with the Euromonitor API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bernskiold/laravel-euromonitor.svg?style=flat-square)](https://packagist.org/packages/bernskiold/laravel-euromonitor)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bernskiold/laravel-euromonitor/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bernskiold/laravel-euromonitor/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/bernskiold/laravel-euromonitor/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/bernskiold/laravel-euromonitor/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/bernskiold/laravel-euromonitor.svg?style=flat-square)](https://packagist.org/packages/bernskiold/laravel-euromonitor)

## Installation & Usage

You can install the package via composer:

```bash
composer require bernskiold/laravel-euromonitor
```

After installing the package, you may publish the configuration file:

```bash
php artisan vendor:publish --provider="Bernskiold\LaravelEuromonitor\LaravelEuromonitorServiceProvider" --tag="config"
```

This will publish a `euromonitor.php` file in your `config` directory.

The core configuration parameters for connecting to the Euromonitor API are provided
as environment variables in your `.env` file. These are:

```dotenv
EUROMONITOR_USERNAME=
EUROMONITOR_PASSWORD=
EUROMONITOR_API_KEY=
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Bernskiold](https://github.com/bernskiold)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
