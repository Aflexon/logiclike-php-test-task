<?php

namespace App\Http;


use App\Http\Middleware\MiddlewareInterface;
use UnexpectedValueException;

class Dispatcher
{
    /**
     * @param MiddlewareInterface[] $stack middleware stack
     */
    protected $stack = [
        ''
    ];

    /**
     * @param MiddlewareInterface[] $stack middleware stack (with at least one middleware component)
     */
    public function __construct(array $stack)
    {
        $this->stack = $stack;
    }

    /**
     * Dispatches the middleware stack and returns the resulting Response.
     * @param Request $request
     * @return Response
     */
    public function dispatch(Request $request)
    {
        $resolved = $this->resolve(0);
        return $resolved->handle($request);
    }
    /**
     * @param int $index middleware stack index
     *
     * @return RequestHandler
     */
    private function resolve($index)
    {
        return new RequestHandler(function (Request $request) use ($index) {
            if (!isset($this->stack[$index])) {
                // TODO: remove it from here
                return new Response(200, [], "Hello world");
            }
            $middleware = $this->stack[$index];
            if (!($middleware instanceof MiddlewareInterface)) {
                throw new UnexpectedValueException(
                    sprintf('The middleware must be an instance of %s', MiddlewareInterface::class)
                );
            }
            $response = $middleware->process($request, $this->resolve($index + 1));
            if (!($response instanceof Response)) {
                throw new UnexpectedValueException(
                    sprintf('The middleware must return an instance of %s', Response::class)
                );
            }
            return $response;
        });
    }
}