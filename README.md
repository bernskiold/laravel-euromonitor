# A package for Laravel to interact with the Euromonitor API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bernskiold/laravel-euromonitor.svg?style=flat-square)](https://packagist.org/packages/bernskiold/laravel-euromonitor)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/bernskiold/laravel-euromonitor/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/bernskiold/laravel-euromonitor/actions?query=workflow%3ATests+branch%3Amain)
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

## Company and brand identifiers

Euromonitor's catalog exposes stable identifiers for companies and brands. Use
them to look entities up once and then filter the share services by ID, rather
than matching on names.

```php
use Bernskiold\LaravelEuromonitor\Facades\Euromonitor;

// Every brand your subscription can see, with its owning company.
$brands = Euromonitor::brands()
    ->geographies([21])       // Belgium
    ->categories([11069])     // Toilet Paper
    ->page(1)
    ->perPage(1000)
    ->all()
    ->get();

$brands->first()->brandId;    // 903112
$brands->first()->brandName;  // "Lotus"
$brands->first()->companyId;  // 674613

// Companies only.
$companies = Euromonitor::companies()->all()->get();
```

Both endpoints are paginated with `page()` and `perPage()` (maximum 10,000 records
per page) and return one record per geography and category combination, so the same
`brandId` appears more than once across a full listing.

### Filtering share data by identifier

`brandShares()` accepts both `brands()` and `companies()`; `companyShares()` accepts
`companies()`. This works for live queries and for mass extraction jobs.

```php
$shares = Euromonitor::brandShares()
    ->percentages()
    ->shareType(0)
    ->geographies([21])
    ->categories([11069])
    ->brands([903112, 903118])
    ->all()
    ->get();

$shares->first()->brandId;        // 903112
$shares->first()->companyId;      // 674613
$shares->first()->entityId();     // 903112 - the brand on a brand share
$shares->first()->entityName();   // "Lotus"
```

Share records expose `brandId` and `companyId` alongside the names. Both are
nullable, since extract files produced before the identifiers were introduced do
not carry them - use `hasEntityId()` when you need to branch on that.

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
