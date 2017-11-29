<?php
namespace App\Http;

class RequestHandler
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback function (Request $request) : Response
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Handle the request and return a response.
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        return call_user_func($this->callback, $request);
    }
}