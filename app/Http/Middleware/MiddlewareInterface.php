<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\RequestHandler;
use App\Http\Response;

interface MiddlewareInterface {
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     * @param Request $request
     * @param RequestHandler $handler
     * @return Response
     */
    public function process($request, $handler);
}