# Composed

[![Code Quality](https://img.shields.io/scrutinizer/g/joshdifabio/composed.svg?style=flat-square)](https://scrutinizer-ci.com/g/joshdifabio/composed/)

This library provides a set of utility functions designed to help you parse your project's Composer configuration, and those of its dependencies, at runtime.

## Usage

The API combines functional and object-oriented approaches.

### Locate the vendor directory

(Chicken and egg...)

```php
$absoluteVendorPath = Composed\VENDOR_DIR;
```

### Locate the project's base directory

```php
$absoluteProjectPath = Composed\BASE_DIR;
```

### Get the authors of a specific package

You can fetch data from the `composer.json` file of a specific package.

```php
$authors = Composed\package_config('phpunit/phpunit', 'authors');

assert($authors === [
    [
        'name' => "Sebastian Bergmann",
        'email' => "sebastian@phpunit.de",
        'role' => "lead",
    ],
]);
```

### Get licenses of all installed packages

You can fetch data from all `composer.json` files in your project in one go.

```php
$licenses = Composed\package_configs('license');

assert($licenses === [
  'joshdifabio/composed' => "MIT",
  'doctrine/instantiator' => "MIT",
  'phpunit/php-code-coverage' => "BSD-3-Clause",
]);
```

### Get the absolute path to a file in a package

```php
$path = Composed\package('phpunit/phpunit')->getPath('composer.json');
```

### Get all packages installed on your project

```php
foreach (Composed\packages() as $packageName => $package) {
    $pathToPackageConfig = $package->getPath('composer.json');
    // ...
}
```

### Get data from your project's Composer config

You can also fetch data from the `composer.json` file located in your project root.

```php
$projectAuthors = Composed\project_config('authors');

assert($projectAuthors === [
    [
        'name' => 'Josh Di Fabio',
        'email' => 'joshdifabio@somewhere.com',
    ],
]);
```

## Installation

Install Composed using [composer](https://getcomposer.org/).

```
composer require joshdifabio/composed
```

## Credits

Credit goes to @igorw whose [get-in](https://github.com/igorw/get-in) library is partially copied into this library. Unfortunately, `igorw/get-in` requires PHP 5.4 while Composed aims for PHP 5.3 compatibility.

## License

Composed is released under the [MIT](https://github.com/joshdifabio/composed/blob/master/LICENSE) license.
