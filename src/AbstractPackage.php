<?php
namespace Composed;

/**
 * @author Josh Di Fabio <joshdifabio@gmail.com>
 */
abstract class AbstractPackage
{
    private $dirPath;
    private $config;
    private $root;
    private $lockFile;
    private $directDependencies;
    private $dependencies;

    protected function __construct(RootPackage $root, string $dirPath, JsonObject $config)
    {
        $this->root = $root;
        $this->dirPath = $dirPath;
        $this->config = $config;
    }

    /**
     * @return null|string
     */
    public function getName(bool $includeVendorName = true)
    {
        $name = $this->config->get(['name']);

        if ($includeVendorName) {
            return $name;
        }

        return ltrim(strstr($name, '/'), '/');
    }

    /**
     * @return null|string
     */
    public function getVendorName()
    {
        if (null === $name = $this->getName()) {
            return null;
        }

        return strstr($name, '/', true);
    }

    public function isRoot() : bool
    {
        return $this->root === $this;
    }

    /**
     * @return mixed
     */
    public function getConfig($keys = [], $default = null)
    {
        return $this->config->get($keys, $default);
    }

    public function getPath(string $relativePath = '') : string
    {
        return $this->dirPath . (strlen($relativePath) ? DIRECTORY_SEPARATOR . $relativePath : '');
    }

    /**
     * @return null|LockFile
     */
    public function getLockFile()
    {
        if (null === $this->lockFile) {
            $filePath = $this->getPath('composer.lock');
            if (file_exists($filePath)) {
                $this->lockFile = LockFile::fromFilePath($this->root, $filePath);
            }
        }

        return $this->lockFile;
    }

    public function directlyRequires(string $packageName) : bool
    {
        return null !== $this->getDirectDependencies()->getPackage($packageName);
    }

    public function requires(string $packageName) : bool
    {
        return null !== $this->getDependencies()->getPackage($packageName);
    }

    public function getDirectDependencies() : PackageCollection
    {
        if (null === $this->directDependencies) {
            $packageNames = array_keys($this->getConfig('require', $default = []));
            $packages = array_map(
                function ($packageName) {
                    $this->root->getPackages()->getPackage($packageName);
                },
                $packageNames
            );
            $this->directDependencies = new PackageCollection(array_combine($packageNames, $packages));
        }

        return $this->directDependencies;
    }

    public function getDependencies() : PackageCollection
    {
        if (null === $this->dependencies) {
            $packages = [];
            foreach ($this->getDirectDependencies() as $name => $package) {
                $packages[$name] = $package;
                $packages = array_merge($packages, $package->getDependencies());
            }
            $this->dependencies = new PackageCollection($packages);
        }

        return $this->dependencies;
    }
}
