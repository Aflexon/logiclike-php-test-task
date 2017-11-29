<?php

namespace App\Storage;


use Memcache;

class MemcacheStorage implements TemporaryStorage
{
    /**
     * Memcache object
     * @var Memcache
     */
    protected $memcache;

    public function __construct($host, $port)
    {
        $this->memcache = new Memcache();
        $this->memcache->connect($host, $port);
    }

    /**
     * Save value to storage if not exists
     * @param string $key
     * @param mixed $value
     * @param int $ttl storage time
     */
    public function add($key, $value, $ttl)
    {
        $this->memcache->add($key, $value, false, $ttl);
    }

    /**
     * Get value from storage
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = $this->memcache->get($key);
        return $value !== false ? $value : $default;
    }

    /**
     * Increment value of item
     * @param int $key
     * @param int $value
     * @return int|bool
     */
    public function increment($key, $value = 1)
    {
        return $this->memcache->increment($key, $value);
    }
}