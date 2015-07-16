<?php
namespace Composed;

/**
 * @author Josh Di Fabio <joshdifabio@gmail.com>
 */
class Package extends AbstractPackage
{
    protected static function create(JsonObject $json)
    {
        return new static($json, false);
    }

    protected function getDir()
    {
        $parts = array_filter(array(
            VENDOR_DIR,
            $this->getName(),
            $this->getConfig('target-dir', ''),
        ));
        
        $path = implode(DIRECTORY_SEPARATOR, $parts);

        return str_replace('/', DIRECTORY_SEPARATOR, $path);
    }
}
