<?php

namespace App\Http;

class Response {
    /**
     * Status code
     *
     * @var int
     */
    protected $status = 200;

    /**
     * Headers list
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Body
     *
     * @var string
     */
    protected $body = 200;

    /**
     * Create new HTTP response.
     *
     * @param int $status The response status code.
     * @param array $headers The response headers.
     * @param string|null $body The response body.
     */
    public function __construct($status = 200, array $headers = [], $body = null)
    {
        $this->status = $status;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Gets the response status code.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * Gets the response headers.
     *
     * @return array List of headers.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get body.
     *
     * @return string body of page.
     */
    public function getBody()
    {
        return $this->body;
    }
}
