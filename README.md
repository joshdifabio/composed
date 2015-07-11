# Composer Utils

## Introduction

This library provides a set of utility functions designed to help you parse your project's Composer configuration at runtime.

## Usage

The API combines functional and object-oriented approaches.

### Locate the vendor directory

(Chicken and egg...)

```php
$absoluteVendorPath = ComposerUtils\VENDOR_DIR;
```

### Locate the project's base directory

```php
$absoluteProjectPath = ComposerUtils\BASE_DIR;
```

### Get the authors of a specific package

You can fetch anything from the `composer.json` file of a package, for example authors.

```php
$requires = ComposerUtils\package_config('phpunit/phpunit', 'authors');

assert($requires === [
    'name' => "Sebastian Bergmann",
    'email' => "sebastian@phpunit.de",
    'role' => "lead",
]);
```

### Get licenses of all installed packages

```php
$phpRequires = ComposerUtils\package_configs('license');

assert($phpRequires === [
  'joshdifabio/composer-utils' => "MIT",
  'doctrine/instantiator' => "MIT",
  'phpunit/php-code-coverage' => "BSD-3-Clause",
]);
```

### Get the absolute path to a file in a package

```php
$path = ComposerUtils\package('phpunit/phpunit')->getPath('composer.json');
```

### Get all packages installed on your project

```php
foreach (ComposerUtils\packages() as $packageName => $package) {
    $pathToPackageConfig = $package->getPath('composer.json');
    // ...
}
```

### Get a value from your project's Composer config

```php
$projectAuthors = ComposerUtils\project_config('authors');

assert($projectAuthors === [
    [
        'name' => 'Josh Di Fabio',
        'email' => 'joshdifabio@somewhere.com',
    ],
]);
```

## Installation

Install Composer Utils using [composer](https://getcomposer.org/).

```
composer require joshdifabio/composer-utils
```

## Credits

Credit goes to @igorw whose [get-in](https://github.com/igorw/get-in) library is partially copied into this library. Unfortunately, `igorw/get-in` requires PHP 5.4 while Composer Utils aims for PHP 5.3 compatibility.

## License

Composer Utils is released under the [MIT](https://github.com/joshdifabio/composer-utils/blob/master/LICENSE) license.
