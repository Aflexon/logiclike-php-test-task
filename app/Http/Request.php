<?php

namespace App\Http;

class Request {
    /**
     * The server variables at the time the request was created.
     *
     * @var array
     */
    protected $serverParams;

    /**
     * Create new HTTP request from globals
     *
     * @param array $globals
     * @return Request
     */
    public static function createFromGlobals(array $globals)
    {
        return new static($globals);
    }

    /**
     * Create new HTTP request
     *
     * @param array $serverParams
     */
    public function __construct($serverParams)
    {
        $this->serverParams = $serverParams;
    }

    /**
     * Retrieve server parameters.
     *
     * @return array
     */
    public function getServerParams()
    {
        return $this->serverParams;
    }
    /**
     * Retrieve a server parameter
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public function getServerParam($key, $default = null)
    {
        $serverParams = $this->getServerParams();
        return isset($serverParams[$key]) ? $serverParams[$key] : $default;
    }
}
