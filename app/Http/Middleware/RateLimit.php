<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\RequestHandler;
use App\Http\Response;

class RateLimit implements MiddlewareInterface
{
    protected $limiter;

    public function __construct(Limiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * DDoS Protect
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function process(Request $request, RequestHandler $handler) {
        $key = sha1($request->getServerParam('REMOTE_ADDR'));
        if ($this->limiter->tooManyAttempts($key)) {
            return new Response(429, [
                'Retry-After: ' . $this->limiter->availableIn($key)
            ]);
        }

        $this->limiter->hit($key);

        return $handler->handle($request);
    }
}