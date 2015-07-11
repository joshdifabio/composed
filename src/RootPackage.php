<?php
namespace ComposerUtils;

/**
 * @author Josh Di Fabio <joshdifabio@gmail.com>
 */
class RootPackage extends AbstractPackage
{
    private static $instance;

    private $packages;

    /**
     * @return RootPackage
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = self::createFromPath(BASE_DIR . DIRECTORY_SEPARATOR . 'composer.json');
        }

        return self::$instance;
    }

    /**
     * Returns all packages in the project, including the root one
     * 
     * @return PackageCollection
     */
    public function getPackages()
    {
        if (null === $this->packages) {
            $packages = array_merge(
                array(
                    $this->getName() => $this,
                ),
                $this->getLockFile()->getPackages()->toArray()
            );

            $this->packages = new PackageCollection($packages);
        }

        return $this->packages;
    }

    /**
     * @return LockFile
     */
    public function getLockFile()
    {
        if (null === $lockFile = parent::getLockFile()) {
            throw new \RuntimeException('Lock file not found.');
        }

        return $lockFile;
    }

    protected static function create(JsonObject $json)
    {
        return new static($json, true);
    }

    protected function getDir()
    {
        return BASE_DIR;
    }
}
