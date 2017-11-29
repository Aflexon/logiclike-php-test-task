<?php
/**
 * Created by PhpStorm.
 * User: aflexon
 * Date: 29.11.2017
 * Time: 10:03
 */

namespace App\Http\Middleware;


use App\Storage\TemporaryStorage;

class Limiter
{
    /**
     * @var TemporaryStorage
     */
    protected $storage;

    /**
     * Max attempts count
     * @var int
     */
    protected $maxAttempts;

    /**
     * Minutes for the allowed attempts count
     * @var int
     */
    protected $decayMinutes;

    /**
     * Minutes for blocking key
     * @var int
     */
    protected $blockedMinutes;

    /**
     * Limiter constructor.
     * @param TemporaryStorage $storage
     * @param int $maxAttempts
     * @param int $decayMinutes
     * @param int $blockedMinutes
     */
    public function __construct(TemporaryStorage $storage, $maxAttempts = 5, $decayMinutes = 1, $blockedMinutes = 10)
    {
        $this->storage = $storage;
        $this->maxAttempts = $maxAttempts;
        $this->decayMinutes = $decayMinutes;
        $this->blockedMinutes = $blockedMinutes;
    }

    /**
     * Check if key exceeded the limit
     * @param $key
     * @return bool
     */
    public function tooManyAttempts($key)
    {
        if ($this->storage->get($key.':lockout')) {
            return true;
        }

        if ($this->attempts($key) >= $this->maxAttempts) {
            $this->lockout($key);
            return true;
        }

        return false;
    }

    /**
     * Increment the counter for a given key.
     *
     * @param  string  $key
     * @return int
     */
    public function hit($key)
    {
        $this->storage->add($key, 0, $this->decayMinutes * 60);

        return (int) $this->storage->increment($key);
    }

    /**
     * Add the lockout key to the storage.
     *
     * @param  string  $key
     * @return void
     */
    protected function lockout($key)
    {
        $this->storage->add($key.':lockout', time() + ($this->blockedMinutes * 60), $this->blockedMinutes * 60);
    }

    /**
     * Get the number of attempts for the given key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function attempts($key)
    {
        return $this->storage->get($key, 0);
    }

    /**
     * Get the timestamp when the "key" will be accessible again.
     *
     * @param  string  $key
     * @return int
     */
    public function availableIn($key)
    {
        return $this->storage->get($key.':lockout');
    }
}