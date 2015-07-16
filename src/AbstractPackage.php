<?php
namespace Composed;

/**
 * @author Josh Di Fabio <joshdifabio@gmail.com>
 */
abstract class AbstractPackage
{
    private $config;
    private $isRoot;
    private $lockFile;

    public function __construct(JsonObject $config, $isRoot)
    {
        $this->config = $config;
        $this->isRoot = $isRoot;
    }

    /**
     * @param string $filePath Path to package's composer.json file
     * @return Package
     */
    public static function createFromPath($filePath)
    {
        return static::create(JsonObject::createFromPath($filePath));
    }

    /**
     * @return Package
     */
    public static function createFromJsonArray(array $data)
    {
        return static::create(JsonObject::create($data));
    }

    /**
     * @return null|string
     */
    public function getName($includeVendorName = true)
    {
        $name = $this->config->get(array('name'));

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

    /**
     * @return boolean
     */
    public function isRoot()
    {
        return $this->isRoot;
    }

    /**
     * @return mixed
     */
    public function getConfig($keys = array(), $default = null)
    {
        return $this->config->get($keys, $default);
    }

    /**
     * @return string
     */
    public function getPath($relativePath = '')
    {
        return $this->getDir() . (strlen($relativePath) ? DIRECTORY_SEPARATOR . $relativePath : '');
    }

    /**
     * @return null|LockFile
     */
    public function getLockFile()
    {
        if (null === $this->lockFile) {
            $filePath = $this->getPath('composer.lock');
            if (file_exists($filePath)) {
                $this->lockFile = LockFile::createFromPath($filePath);
            }
        }

        return $this->lockFile;
    }

    abstract protected function getDir();
}
