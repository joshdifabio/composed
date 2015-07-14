<?php
namespace ComposerUtils;

/**
 * @author Josh Di Fabio <joshdifabio@gmail.com>
 */
class JsonObject
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param string $filePath
     * @return JsonObject
     */
    public static function createFromPath($filePath)
    {
        if (false === $fileContent = @file_get_contents($filePath)) {
            if (!file_exists($filePath)) {
                throw new \RuntimeException('File not found.');
            }

            throw new \RuntimeException('Failed to open file.');
        }

        if (null === $data = json_decode($fileContent, true)) {
            throw new \RuntimeException('File does not contain valid JSON.');
        }

        return self::create($data);
    }

    /**
     * @return JsonObject
     */
    public static function create(array $data)
    {
        return new self($data);
    }

    /**
     * This method is copied from igorw/get-in with minor changes. At present this lib supports
     * PHP 5.3 but igorw/get-in does not
     * 
     * @link https://github.com/igorw/get-in/blob/master/src/get_in.php
     */
    public function get($keys = array(), $default = null)
    {
        if (!is_array($keys)) {
            $keys = array($keys);
        }
        
        if (!$keys) {
            return $this->data;
        }

        return $this->deepGet($this->data, $keys, $default);
    }

    private function deepGet(array $current, array $keys = array(), $default = null)
    {
        foreach ($keys as $key) {
            if (!is_array($current) || !array_key_exists($key, $current)) {
                return $default;
            }
            $current = $current[$key];
        }

        return $current;
    }
}
