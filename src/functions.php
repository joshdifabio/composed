<?php
namespace Composed;

define(__NAMESPACE__ . '\VENDOR_DIR', Internal\findVendorDir());
define(__NAMESPACE__ . '\BASE_DIR', Internal\findBaseDir(VENDOR_DIR));

function packages($includeRoot = true) : PackageCollection
{
    return $includeRoot ? project()->getPackages() : project()->getLockFile()->getPackages();
}

/**
 * @return null|AbstractPackage
 */
function package($name, $graceful = false)
{
    $package = packages()->getPackage($name);

    if (!$graceful && !$package) {
        throw new \OutOfBoundsException('The specified package does not appear to be installed.');
    }

    return $package;
}

function package_configs($keys = [], $default = null) : array
{
    /**
     * If $default is not explicitly provided, it should not be passed to Package::getConfig(),
     * hence not simply calling getConfig($keys, $default), as explicitly passing $default = null
     * to getConfig() is different to omitting it
     */
    return packages()->getConfig(...func_get_args());
}

/**
 * @return mixed
 */
function package_config(string $packageName, $keys = [], $default = null)
{
    return package($packageName)->getConfig($keys, $default);
}

/**
 * @return mixed
 */
function project_config($keys = [], $default = null)
{
    return project()->getConfig($keys, $default);
}

function project(RootPackage $assign = null) : RootPackage
{
    static $project;

    if ($assign) {
        $project = $assign;
    } elseif (!$project) {
        $project = RootPackage::createFromPath(BASE_DIR);
    }

    return $project;
}
