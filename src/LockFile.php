<?php
namespace ComposerUtils;

/**
 * @author Josh Di Fabio <joshdifabio@gmail.com>
 */
class LockFile
{
    private static $instance;
    
    private $json;
    private $packages;

    public function __construct(JsonObject $json)
    {
        $this->json = $json;
    }

    /**
     * @return LockFile
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = self::createFromPath(BASE_DIR . DIRECTORY_SEPARATOR . 'composer.lock');
        }

        return self::$instance;
    }

    /**
     * @param string $filePath
     * @return JsonObject
     */
    public static function createFromPath($filePath)
    {
        return new self(JsonObject::createFromPath($filePath));
    }

    /**
     * @return PackageCollection
     */
    public function getPackages()
    {
        if (null === $this->packages) {
            $packages = array();
            $packagesData = $this->json->get(array('packages'), array());
            foreach ($packagesData as $packageData) {
                $package = Package::createFromJsonArray($packageData);
                $packages[$package->getName()] = $package;
            }
            $this->packages = new PackageCollection($packages);
        }

        return $this->packages;
    }

    public function get($keys = array(), $default = null)
    {
        return $this->json->get($keys, $default);
    }
}
