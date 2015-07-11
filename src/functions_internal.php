<?php
namespace ComposerUtils\Internal;

function findVendorDir()
{
    $packagePath = dirname(__DIR__);

    if (is_dir($packagePath . DIRECTORY_SEPARATOR . 'vendor')) {
        return $packagePath . DIRECTORY_SEPARATOR . 'vendor';
    }

    return dirname(dirname(dirname(__DIR__)));
}

function findBaseDir($vendorDir)
{
    require "$vendorDir/composer/autoload_files.php";
    return $baseDir;
}
