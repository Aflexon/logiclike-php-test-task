<?php
namespace App\Storage;


interface TemporaryStorage
{
    /**
     * Save value to storage if not exists
     * @param string $key
     * @param mixed $value
     * @param int $ttl storage time
     */
    public function add($key, $value, $ttl);

    /**
     * Save value of storage
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Increment value of item
     * @param int $key
     * @param int $value
     * @return int|bool
     */
    public function increment($key, $value = 1);
}