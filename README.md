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

### Get the requirements for a specific package

```php
$requires = ComposerUtils\package_config('phpunit/phpunit', 'require');
```

### Get the PHP version requirement for a specific package

```php
$phpRequirement = ComposerUtils\package_config('phpunit/phpunit', ['require', 'php']);
```

### Get the PHP version requirement for all installed packages

```php
$valuesByPackage = ComposerUtils\package_configs(['require', 'php']);
```

### Get the absolute path to a file in a package

```php
$path = ComposerUtils\package('phpunit/phpunit')->getPath('composer.json');
```

### Get all packages installed on your project

```php
foreach (ComposerUtils\packages() as $packageName => $package) {
    $pathToPackageConfig = $package->getPath('composer.json);
    // ...
}
```

### Get a value from your project's Composer config

```php
$projectAuthors = ComposerUtils\project_config('authors');
```

## Installation

Install Composer Utils using [composer](https://getcomposer.org/).

```
composer require joshdifabio/composer-utils
```

## License

Composer Utils is released under the [MIT](https://github.com/joshdifabio/composer-utils/blob/master/LICENSE) license.
