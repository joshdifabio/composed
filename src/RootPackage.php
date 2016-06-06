<?php
namespace Composed;

/**
 * @author Josh Di Fabio <joshdifabio@gmail.com>
 */
class RootPackage extends AbstractPackage
{
    private $packages;
    private $installedPackagesFile;

    public function __construct(string $dirPath, JsonObject $config)
    {
        parent::__construct($this, $dirPath, $config);
    }

    public static function createFromPath(string $dirPath) : self
    {
        return new static($dirPath, JsonObject::fromFilePath($dirPath . DIRECTORY_SEPARATOR . 'composer.json'));
    }

    /**
     * Returns all packages in the project, including the root one
     */
    public function getPackages() : PackageCollection
    {
        if (null === $this->packages) {
            $packages = array_merge(
                array(
                    $this->getName() => $this,
                ),
                $this->getInstalledPackagesFile()->getPackages()->toArray()
            );

            $this->packages = new PackageCollection($packages);
        }

        return $this->packages;
    }

    public function getLockFile() : LockFile
    {
        if (null === $lockFile = parent::getLockFile()) {
            throw new \RuntimeException('Lock file not found.');
        }

        return $lockFile;
    }

    public function getInstalledPackagesFile() : InstalledPackagesFile
    {
        if (null === $this->installedPackagesFile) {
            $filePath = $this->getPath('vendor/composer/installed.json');
            if (file_exists($filePath)) {
                $this->installedPackagesFile = InstalledPackagesFile::fromFilePath($this, $filePath);
            }
        }

        return $this->installedPackagesFile;
    }

    public function getVendorDir() : string
    {
        return 'vendor';
    }

    public function getVendorPath(string $relativePath = '') : string
    {
        return $this->getPath($this->getVendorDir() . (strlen($relativePath) ? DIRECTORY_SEPARATOR . $relativePath : ''));
    }
}
