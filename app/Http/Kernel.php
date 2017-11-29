<?php

namespace App\Http;

use App\Http\Middleware\Limiter;
use App\Http\Middleware\MiddlewareInterface;
use App\Http\Middleware\RateLimit;
use App\Storage\MemcacheStorage;
use UnexpectedValueException;

class Kernel
{
    /**
     * @var MiddlewareInterface[] $stack middleware stack
     */
    protected $globalMiddlewares = [];

    /**
     * HTTP Request from client.
     *
     * @var Request
     */
    protected $request;

    public function __construct()
    {
        $this->request = Request::createFromGlobals($_SERVER);
        $memcacheStorage = new MemcacheStorage('localhost', 11211);
        $this->globalMiddlewares = [
            new RateLimit(new Limiter($memcacheStorage))
        ];
    }

    public function run()
    {
        $dispatcher = new Dispatcher($this->globalMiddlewares);
        $response = $dispatcher->dispatch($this->request);
        if (!($response instanceof Response)) {
            throw new UnexpectedValueException(
                sprintf('The dispatcher must return an instance of %s', Response::class)
            );
        }
        $this->respond($response);
    }

    public function respond(Response $response)
    {
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $header) {
            header($header);
        }
        printf($response->getBody());
    }

}