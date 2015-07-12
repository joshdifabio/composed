<?php
namespace ComposerUtils;

class PackageCollection implements \IteratorAggregate
{
    private $packages;
    
    public function __construct(array $packages)
    {
        $this->packages = $packages;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->packages);
    }

    /**
     * @param string $name
     * @return null|Package
     */
    public function getPackage($name)
    {
        return isset($this->packages[$name]) ? $this->packages[$name] : null;
    }

    /**
     * Get a config value from all packages
     *
     * @param string    $keys       Either a string, e.g. 'required' to get the dependencies by a
     *                              package, or an array, e.g. ['required', 'php'] to get the version of
     *                              PHP required by a package
     *
     * @param mixed     $default    If this is not explicitly specified, packages which do not provide
     *                              any config value will be omitted from the result. Explicitly setting
     *                              this to NULL is not the same as omitting it and will result in
     *                              packages which do not provide config being returned with a value of
     *                              NULL
     *
     * @return array    Keys are package names, values are the retrieved config as an array or scalar
     */
    public function getConfig($keys = array(), $default = null)
    {
        if (2 > func_num_args()) {
            $default = new \stdClass;
        }

        $config = @array_map(
            function ($package) use ($keys, $default) {
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

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->packages;
    }
}
