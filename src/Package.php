<?php
namespace Composed;

/**
 * @author Josh Di Fabio <joshdifabio@gmail.com>
 */
class Package extends AbstractPackage
{
    public static function create(RootPackage $root, JsonObject $packageConfig) : self
    {
        if (null === $packageName = $packageConfig->get('name')) {
            throw new \InvalidArgumentException('Package data must include package name');
        }
        $dirPath = $root->getVendorPath(str_replace('/', DIRECTORY_SEPARATOR, $packageConfig->get('name')));

        return new static($root, $dirPath, $packageConfig);
    }

    public static function fromArray(RootPackage $root, array $packageConfig) : self
    {
        return self::create($root, JsonObject::fromArray($packageConfig));
    }
}
