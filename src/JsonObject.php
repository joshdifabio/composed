<?php
namespace Composed;

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

    public static function fromFilePath(string $filePath) : self
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

        return self::fromArray($data);
    }

    public static function fromArray(array $data) : self
    {
        return new self($data);
    }

    public function get($keys = [], $default = null)
    {
        if (!is_array($keys)) {
            $keys = [$keys];
        }
        
        if (!$keys) {
            return $this->data;
        }

        return $this->deepGet($this->data, $keys, $default);
    }

    private function deepGet(array $current, array $keys = [], $default = null)
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
