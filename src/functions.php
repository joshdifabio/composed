<?php
namespace ComposerUtils;

define(__NAMESPACE__ . '\VENDOR_DIR', Internal\findVendorDir());
define(__NAMESPACE__ . '\BASE_DIR', Internal\findBaseDir(VENDOR_DIR));

/**
 * @return PackageCollection
 */
function packages($includeRoot = true)
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

/**
 * @return array
 */
function package_configs($keys = array(), $default = null)
{
    /**
     * If $default is not explicitly provided, it should not be passed to Package::getConfig(),
     * hence not simply calling getConfig($keys, $default), as explicitly passing $default = null
     * to getConfig() is different to omitting it
     */
    return call_user_func_array(array(packages(), 'getConfig'), func_get_args());
}

/**
 * @return mixed
 */
function package_config($packageName, $keys = array(), $default = null)
{
    return package($packageName)->getConfig($keys, $default);
}

/**
 * @return mixed
 */
function project_config($keys = array(), $default = null)
{
    return project()->getConfig($keys, $default);
}

/**
 * @return RootPackage
 */
function project()
{
    return RootPackage::getInstance();
}
