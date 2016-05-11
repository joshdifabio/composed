<?php
namespace Composed;

class PackageCollection implements \IteratorAggregate
{
    private $packages;

    /**
     * @param Package[] $packages
     */
    public function __construct(array $packages)
    {
        $this->packages = $packages;
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->packages);
    }

    /**
     * @return null|Package
     */
    public function getPackage(string $name)
    {
        return isset($this->packages[$name]) ? $this->packages[$name] : null;
    }

    /**
     * Get a config value from all packages
     *
     * @param string|array $keys Either a string, e.g. 'required' to get the dependencies by a package, or an array,
     * e.g. ['required', 'php'] to get the version of PHP required by a package
     *
     * @param mixed $default If this is not explicitly specified, packages which do not provide any config value will be
     * omitted from the result. Explicitly setting this to NULL is not the same as omitting it and will result in
     * packages which do not provide config being returned with a value of NULL
     *
     * @return array Keys are package names, values are the retrieved config as an array or scalar
     */
    public function getConfig($keys = [], $default = null) : array
    {
        if (2 > func_num_args()) {
            $default = new \stdClass;
        }

        $config = @array_map(
            function (AbstractPackage $package) use ($keys, $default) {
                return $package->getConfig($keys, $default);
            },
            $this->packages
        );

        if (2 > func_num_args()) {
            $config = array_filter($config, function ($value) use ($default) {
                return $value !== $default;
            });
        }

        return $config;
    }

    public function toArray() : array
    {
        return $this->packages;
    }

    public function getPackageNames() : array
    {
        return array_keys($this->packages);
    }

    /**
     * Returns all packages sorted based on their dependencies. Each package is guaranteed to appear after all of its
     * dependencies in the collection
     */
    public function sortByDependencies() : PackageCollection
    {
        /** @var $packages Package[] */
        $packages = $this->packages;
        $sortedPackages = [];

        while (!empty($packages)) {
            foreach ($packages as $packageName => $package) {
                $dependentPackageNames = $package->getDependencies()->getPackageNames();
                if (empty(array_diff_key($dependentPackageNames, $sortedPackages))) {
                    // all of this packages dependencies are already in the sorted array, so it can be appended
                    $sortedPackages[$packageName] = $packages;
                    unset($packages[$packageName]);
                    continue(2);
                }
            }
            throw new \LogicException('Packages have circular dependencies');
        }

        return new PackageCollection($sortedPackages);
    }
}
